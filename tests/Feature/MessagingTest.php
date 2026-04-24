<?php

use App\Enums\GigStatus;
use App\Enums\UserRole;
use App\Events\MessageSent;
use App\Livewire\Freelancer\Onboarding;
use App\Livewire\Message\ConversationPage;
use App\Livewire\Message\Inbox;
use App\Models\Category;
use App\Models\Conversation;
use App\Models\Gig;
use App\Models\Message;
use App\Models\User;
use Illuminate\Broadcasting\PrivateChannel;
use Livewire\Livewire;

test('unverified freelancers can access the inbox during onboarding', function () {
    $freelancer = User::factory()->unverified()->create([
        'role' => UserRole::Freelancer,
    ]);

    $this->actingAs($freelancer)
        ->get(route('inbox'))
        ->assertOk();
});

test('freelancers see admin suggestions in the inbox and can start a conversation', function () {
    $freelancer = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $admin = User::factory()->create([
        'role' => UserRole::Admin,
        'name' => 'Admin Support',
    ]);

    $this->actingAs($freelancer);

    Livewire::test(Inbox::class)
        ->assertSee('Discuter avec un admin')
        ->assertSee($admin->name)
        ->call('startConversation', $admin->id)
        ->assertRedirect(route('inbox.show', Conversation::first()));

    $conversation = Conversation::query()->first();

    expect($conversation)->not->toBeNull();
    expect($conversation->users()->pluck('users.id')->all())
        ->toContain($freelancer->id, $admin->id);
});

test('freelancer onboarding can open a direct chat with an admin', function () {
    $freelancer = User::factory()->create([
        'role' => UserRole::Freelancer,
    ]);

    $admin = User::factory()->create([
        'role' => UserRole::Admin,
        'name' => 'Admin Coach',
    ]);

    $this->actingAs($freelancer);

    Livewire::test(Onboarding::class)
        ->assertSee($admin->name)
        ->call('openAdminChat')
        ->assertRedirect(route('inbox.show', Conversation::first()));

    $conversation = Conversation::query()->first();

    expect($conversation)->not->toBeNull();
    expect($conversation->users()->pluck('users.id')->all())
        ->toContain($freelancer->id, $admin->id);
});

test('clients see freelancer suggestions in the inbox', function () {
    $client = User::factory()->create([
        'role' => UserRole::Client,
    ]);

    $freelancer = User::factory()->create([
        'role' => UserRole::Freelancer,
        'name' => 'Fatou Freelance',
    ]);

    $category = Category::query()->create([
        'name' => 'Design Graphique',
        'slug' => 'design-graphique',
        'is_active' => true,
        'sort_order' => 1,
    ]);

    $freelancer->freelancerProfile()->create([
        'tagline' => 'Je cree des identites visuelles memorables.',
        'skills' => ['Branding', 'Logo'],
        'languages' => ['Francais'],
    ]);

    Gig::query()->create([
        'freelancer_id' => $freelancer->id,
        'category_id' => $category->id,
        'title' => 'Creation de logo professionnel',
        'description' => 'Je conçois un logo professionnel avec declinaisons et guide de base.',
        'starting_price' => 30000,
        'currency' => 'XOF',
        'delivery_days' => 4,
        'revision_count' => 2,
        'status' => GigStatus::Published,
    ]);

    $this->actingAs($client);

    Livewire::test(Inbox::class)
        ->assertSee('Discuter avec un freelance')
        ->assertSee($freelancer->name)
        ->assertSee('Je cree des identites visuelles memorables.');
});

test('conversation page sends messages', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    $conversation = Conversation::findOrCreateBetweenUsers($sender->id, $recipient->id);

    $this->actingAs($sender);

    Livewire::test(ConversationPage::class, ['conversation' => $conversation])
        ->set('newMessage', 'Bonjour, on peut echanger maintenant ?')
        ->call('sendMessage')
        ->assertHasNoErrors();

    $message = Message::query()->first();

    expect($message)->not->toBeNull();
    expect($message->conversation_id)->toBe($conversation->id);
    expect($message->sender_id)->toBe($sender->id);
    expect($message->body)->toBe('Bonjour, on peut echanger maintenant ?');
});

test('message sent event broadcasts to the conversation and both participants', function () {
    $sender = User::factory()->create();
    $recipient = User::factory()->create();

    $conversation = Conversation::findOrCreateBetweenUsers($sender->id, $recipient->id);

    $message = Message::query()->create([
        'conversation_id' => $conversation->id,
        'sender_id' => $sender->id,
        'body' => 'Message temps reel',
    ])->load('conversation.users');

    $event = new MessageSent($message);
    $channels = $event->broadcastOn();

    expect($channels)->toHaveCount(3);
    expect($channels[0])->toBeInstanceOf(PrivateChannel::class);
    expect($channels[1])->toBeInstanceOf(PrivateChannel::class);
    expect($channels[2])->toBeInstanceOf(PrivateChannel::class);
    expect($channels[0]->name)->toBe('private-conversations.'.$conversation->id);
    expect($channels[1]->name)->toBe('private-users.'.$sender->id);
    expect($channels[2]->name)->toBe('private-users.'.$recipient->id);
});
