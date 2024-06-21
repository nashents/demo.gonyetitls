<?php

namespace App\Http\Livewire\InventoryPurchases;

use Livewire\Component;
use App\Models\Purchase;

class Show extends Component
{
    public $purchase;
    public $purchase_id;

    public function mount($purchase){
    $this->purchase = Purchase::find($purchase->id);
    }
    public function render()
    {
        return view('livewire.inventory-purchases.show');
    }
}
