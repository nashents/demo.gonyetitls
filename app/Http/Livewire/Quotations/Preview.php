<?php

namespace App\Http\Livewire\Quotations;

use Livewire\Component;
use App\Models\Quotation;
use App\Mail\SendingQuotationMail;
use Illuminate\Support\Facades\Mail;

class Preview extends Component
{
    public $quotation;
    public $quotation_products;
    public $company;

    public function mount($quotation, $quotation_products, $company){
        $this->quotation = $quotation;
        $this->quotation_products = $quotation_products;
        $this->company = $company;
    }

    public function sendEmail($id){
        $quotation = Quotation::find($id);
        $quotation_products = $quotation->quotation_products;
        $company = $quotation->company;
        $this->email = $quotation->customer ? $quotation->customer->email : "";
         
        if ($this->email != "") {
            try {
                Mail::to($this->email)->send(new SendingQuotationMail($quotation, $quotation_products, $company));
            }
            catch(\Exception $e){
            // Set Flash Message
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something went wrong while sending quotation!!"
            ]);
        }
           
        }
     
        return redirect()->back();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Email Sent Successfully!!"
        ]);
    }
    
    public function render()
    {
        return view('livewire.quotations.preview');
    }
}
