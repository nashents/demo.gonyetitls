<?php

namespace App\Http\Livewire\TyrePurchases;

use Livewire\Component;
use App\Models\Purchase;

class Approved extends Component
{

    public $purchases;
    public $purchase;
    public $department;
    public $purchase_id;

    public function mount($category){
        $this->department = $category;
        $this->purchases = Purchase::where('authorization', 'approved')
        ->where('department',$this->department)->latest()->get();
    }
    public function authorize($id){
        $purchase = Purchase::find($id);
        $this->purchase_id = $purchase->id;
        $this->purchase = $purchase;
        $this->dispatchBrowserEvent('show-purchaseAuthorizationModal');
      }

      public function update(){
        $purchase = Purchase::find($this->purchase_id);
        if ($purchase->authorization == "approved") {
            $this->dispatchBrowserEvent('hide-purchaseAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Purchase Order Approved Already"
            ]);
        }else {
            $purchase->authorized_by_id = Auth::user()->id;
            $purchase->authorization = $this->authorize;
            $purchase->comments = $this->comments;
            $purchase->update();
        if ($this->authorize == "approved") {
            $this->dispatchBrowserEvent('hide-purchaseAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Purchase Order Approved Successfully"
            ]);
            return redirect()->route('tyre_purchases.approved');
        }else {
            $this->dispatchBrowserEvent('hide-purchaseAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Purchase Order Approved Successfully"
            ]);
            return redirect()->route('tyre_purchases.rejected');
        }
        }
      }

    public function render()
    {
        return view('livewire.tyre-purchases.approved');
    }
}
