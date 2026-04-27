<?php

namespace Database\Seeders;

use App\Models\Badge;
use App\Models\Category;
use App\Models\Commission;
use App\Models\Conversation;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // ── Catégories principales ─────────────────────────────────
        $categories = [
            ['name' => 'Graphisme & Design',    'slug' => 'graphisme-design',     'icon' => '🎨', 'color' => '#8B5CF6', 'sort_order' => 1],
            ['name' => 'Programmation & Tech',   'slug' => 'programmation-tech',   'icon' => '💻', 'color' => '#3B82F6', 'sort_order' => 2],
            ['name' => 'Marketing Digital',      'slug' => 'marketing-digital',    'icon' => '📈', 'color' => '#10B981', 'sort_order' => 3],
            ['name' => 'Vidéo & Animation',      'slug' => 'video-animation',      'icon' => '🎬', 'color' => '#F59E0B', 'sort_order' => 4],
            ['name' => 'Rédaction & Traduction', 'slug' => 'redaction-traduction', 'icon' => '✍️', 'color' => '#EF4444', 'sort_order' => 5],
            ['name' => 'Musique & Audio',        'slug' => 'musique-audio',        'icon' => '🎵', 'color' => '#6366F1', 'sort_order' => 6],
            ['name' => 'Business & Consulting',  'slug' => 'business-consulting',  'icon' => '💼', 'color' => '#64748B', 'sort_order' => 7],
            ['name' => 'Services IA',            'slug' => 'services-ia',          'icon' => '🤖', 'color' => '#06B6D4', 'sort_order' => 8],
        ];

        foreach ($categories as $cat) {
            Category::query()->firstOrCreate(['slug' => $cat['slug']], $cat);
        }

        // ── Sous-catégories Design ─────────────────────────────────
        $design = Category::where('slug', 'graphisme-design')->first();
        foreach (['Logo & Identité visuelle', 'Illustration', 'Design web & app', 'Flyer & Print'] as $i => $name) {
            $slug = Str::slug($name);
            Category::query()->firstOrCreate(['slug' => $slug], ['parent_id' => $design->id, 'name' => $name, 'slug' => $slug, 'sort_order' => $i + 1]);
        }

        // ── Sous-catégories Dev ────────────────────────────────────
        $dev = Category::where('slug', 'programmation-tech')->first();
        foreach (['Site Web', 'Application mobile', 'WordPress', 'E-commerce'] as $i => $name) {
            $slug = Str::slug($name).'-dev';
            Category::query()->firstOrCreate(['slug' => $slug], ['parent_id' => $dev->id, 'name' => $name, 'slug' => $slug, 'sort_order' => $i + 1]);
        }

        // ── Badges ─────────────────────────────────────────────────
        $badges = [
            ['type' => 'new_seller',        'name' => 'Nouveau vendeur',    'icon' => '🆕', 'color' => '#64748B'],
            ['type' => 'verified',          'name' => 'Identité vérifiée', 'icon' => '✓',  'color' => '#3B82F6'],
            ['type' => 'quick_response',    'name' => 'Réponse rapide',    'icon' => '⚡', 'color' => '#F59E0B'],
            ['type' => 'level1',            'name' => 'Vendeur Niveau 1',  'icon' => '⭐', 'color' => '#10B981'],
            ['type' => 'level2',            'name' => 'Vendeur Niveau 2',  'icon' => '⭐⭐', 'color' => '#6366F1'],
            ['type' => 'top_rated',         'name' => 'Top Vendeur',       'icon' => '🏆', 'color' => '#F59E0B'],
            ['type' => 'high_satisfaction', 'name' => 'Haute satisfaction', 'icon' => '💎', 'color' => '#8B5CF6'],
        ];
        foreach ($badges as $badge) {
            Badge::query()->firstOrCreate(['type' => $badge['type']], $badge);
        }

        // ── Commission par défaut (20%) ────────────────────────────
        Commission::query()->firstOrCreate(
            ['category_id' => null],
            ['rate' => 0.2000, 'label' => 'Commission par défaut', 'is_active' => true]
        );

        // ── Administrateur ─────────────────────────────────────────
        User::query()->firstOrCreate(['email' => 'admin@afritask.com'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Admin AfriTask',
            'username' => 'admin',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'admin',
            'is_verified' => true,
            'is_active' => true,
        ]);

        // ── Client de test ─────────────────────────────────────────
        $client = User::query()->firstOrCreate(['email' => 'client@afritask.com'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Amina Traoré',
            'username' => 'amina_client',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'client',
            'country_code' => 'SN',
            'city' => 'Dakar',
            'is_active' => true,
        ]);
        $client->getOrCreateWallet();

        // ── Freelance de test ──────────────────────────────────────
        $freelancer = User::query()->firstOrCreate(['email' => 'freelance@afritask.com'], [
            'uuid' => (string) Str::uuid(),
            'name' => 'Jean-Baptiste Koné',
            'username' => 'jb_design',
            'email_verified_at' => now(),
            'password' => Hash::make('password'),
            'role' => 'freelance',
            'country_code' => 'CI',
            'city' => 'Abidjan',
            'is_verified' => true,
            'is_active' => true,
        ]);

        if (! $freelancer->freelancerProfile) {
            $freelancer->freelancerProfile()->create([
                'tagline' => 'Designer graphique — Logo, Identité visuelle, Web',
                'skills' => ['Photoshop', 'Illustrator', 'Figma'],
                'languages' => ['Français', 'Anglais'],
                'availability' => 'available',
                'seller_level' => 'new',
            ]);
        }
        $freelancer->getOrCreateWallet();

        $newSellerBadge = Badge::where('type', 'new_seller')->first();
        if ($newSellerBadge) {
            $freelancer->badges()->syncWithoutDetaching([$newSellerBadge->id => ['earned_at' => now()]]);
        }

        // ── Conversation de test (client ↔ freelance) ──────────────
        $conversation = Conversation::findOrCreateBetweenUsers($client->id, $freelancer->id);

        if ($conversation->messages()->count() === 0) {
            $conversation->messages()->create([
                'sender_id' => $freelancer->id,
                'body'      => 'Bonjour ! Je suis disponible pour discuter de votre projet. N\'hésitez pas à me contacter.',
            ]);
            $conversation->messages()->create([
                'sender_id' => $client->id,
                'body'      => 'Merci, j\'aimerais en savoir plus sur vos services de design.',
            ]);
            $conversation->touch();
        }

        $this->command->info('✅ AfriTask seeded : 8 catégories, 7 badges, 1 commission, 3 utilisateurs, 1 conversation.');
    }
}
