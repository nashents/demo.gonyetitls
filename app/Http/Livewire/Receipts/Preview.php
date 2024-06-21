<?php

namespace App\Http\Livewire\Receipts;

use Livewire\Component;

class Preview extends Component
{
    public $receipt;
    public $invoice;
    public $invoice_items;
    public $company;
    public $receipts;
    public $receipt_id;

    public function mount($receipt, $company, $invoice){
            $this->invoice = $invoice;
            if (isset($this->invoice)) {
                $this->invoice_items = $invoice->invoice_items;
            }
          
            $this->receipt = $receipt;
            $this->company = $company;
    }
    public function render()
    {
        return view('livewire.receipts.preview');
    }
}
