<?php

namespace App\Livewire\Order;

use App\Enums\OrderStatus;
use App\Events\NotificationSent;
use App\Models\{Gig, GigPackage, Order};
use App\Notifications\NewOrderNotification;
use Illuminate\Contracts\View\View;
use Illuminate\Validation\Rule;
use Livewire\Component;

class OrderCreate extends Component
{
    public Gig $gig;
    public ?int $selectedPackageId = null;
    public string $requirements = '';
    public array $selectedExtras = [];

    public function mount(Gig $gig): void
    {
        abort_if(auth()->id() === $gig->freelancer_id, 403, 'Vous ne pouvez pas commander votre propre service.');

        $this->gig = $gig->load(['packages', 'extras', 'freelancer']);
        $this->selectedPackageId = $gig->packages->first()?->id;
    }

    public function placeOrder(): void
    {
        $packageIds = $this->gig->packages->pluck('id')->toArray();
        $extraIds   = $this->gig->extras->pluck('id')->toArray();

        $validated = $this->validate([
            'selectedPackageId' => $packageIds ? ['required', Rule::in($packageIds)] : ['nullable'],
            'requirements'      => ['required', 'string', 'min:10', 'max:3000'],
            'selectedExtras'    => ['array'],
            'selectedExtras.*'  => [Rule::in($extraIds)],
        ]);

        $package = $this->selectedPackageId
            ? GigPackage::find($this->selectedPackageId)
            : null;

        $basePrice   = $package ? $package->price : $this->gig->starting_price;
        $deliveryDays = $package ? $package->delivery_days : $this->gig->delivery_days;
        $revisions   = $package ? $package->revision_count : $this->gig->revision_count;

        $extrasTotal = 0;
        if (!empty($this->selectedExtras)) {
            $extrasTotal = $this->gig->extras->whereIn('id', $this->selectedExtras)->sum('price');
        }

        $totalPrice = $basePrice + $extrasTotal;

        $order = Order::create([
            'gig_id'           => $this->gig->id,
            'package_id'       => $package?->id,
            'client_id'        => auth()->id(),
            'freelancer_id'    => $this->gig->freelancer_id,
            'title'            => $this->gig->title,
            'requirements'     => $validated['requirements'],
            'price'            => $totalPrice,
            'currency'         => $this->gig->currency ?? 'XOF',
            'delivery_days'    => $deliveryDays,
            'revisions_allowed'=> $revisions,
            'revisions_used'   => 0,
            'status'           => OrderStatus::Pending,
            'due_date'         => now()->addDays($deliveryDays),
        ]);

        $freelancer = $this->gig->freelancer;
        $freelancer->notify(new NewOrderNotification($order));
        broadcast(new NotificationSent(
            $freelancer->id,
            'Nouvelle commande reçue',
            auth()->user()->name . ' a commandé "' . $order->title . '".',
        ));

        $this->redirect(route('orders.checkout', $order->uuid), navigate: true);
    }

    public function render(): View
    {
        $selectedPackage = $this->selectedPackageId
            ? $this->gig->packages->firstWhere('id', $this->selectedPackageId)
            : $this->gig->packages->first();

        return view('livewire.order.order-create', [
            'gig'             => $this->gig,
            'selectedPackage' => $selectedPackage,
        ])->layout('layouts.afritask');
    }
}
