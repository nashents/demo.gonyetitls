<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Bill;
use App\Models\User;
use App\Models\Expense;
use App\Models\Invoice;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Container;
use App\Models\BillExpense;
use App\Mail\invoiceOrderMail;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Pending extends Component
{
    public $invoices;
    public $invoice_id;
    public $trip_id;
    public $authorize;
    public $comments;
    public $invoice;

    public function mount(){
        $period = Auth::user()->employee->company->period;
        if (isset( $period)) {
            if ($period != "all") {
                $this->invoices = Invoice::where('authorization', 'pending')->whereYear('created_at',$period)->latest()->get();
            }else {
                $this->invoices = Invoice::where('authorization', 'pending')->latest()->get();
            }
        }

    }

    public function billNumber(){

        if (isset(Auth::user()->company)) {
            $str = Auth::user()->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }elseif (isset(Auth::user()->employee->company)) {
            $str = Auth::user()->employee->company->name;
            $words = explode(' ', $str);
            if (isset($words[1][0])) {
                $initials = $words[0][0].$words[1][0];
            }else {
                $initials = $words[0][0];
            }
        }

        $bill = Bill::latest()->orderBy('id','desc')->first();

        if (!$bill) {
            $bill_number =  $initials .'B'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $bill->id + 1;
            $bill_number =  $initials .'B'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $bill_number;


    }

    public function authorize($id){
        $invoice = Invoice::find($id);
        $this->invoice_id = $invoice->id;
        $this->invoice = $invoice;
        $this->dispatchBrowserEvent('show-invoiceAuthorizationModal');
      }

      public function update(){
      try{
            $invoice = Invoice::find($this->invoice_id);
            $invoice->authorized_by_id = Auth::user()->id;
            $invoice->authorization = $this->authorize;
            $invoice->comments = $this->comments;
            $invoice->update();

        if ($this->authorize == "approved") {

            if (isset($invoice->tax_amount) && $invoice->tax_amount > 0) {

                $bill = new Bill;
                $bill->user_id = Auth::user()->id;
                $bill->bill_number = $this->billNumber();
                $bill->bill_date = $invoice->date;
                $bill->invoice_id = $invoice->id;
                $bill->currency_id = $invoice->currency_id;
                $bill->total = $invoice->tax_amount;
                $bill->balance = $invoice->tax_amount;
                $bill->save();
    
                $expense = Expense::where('name','VAT')->get()->first();
    
                $bill_expense = new BillExpense;
                $bill_expense->user_id = Auth::user()->id;
                $bill_expense->bill_id = $bill->id;
                $bill_expense->currency_id = $bill->currency_id;
                if (isset($expense)) {
                    $bill_expense->expense_id = $expense->id;
                }
                $bill_expense->qty = 1;
                $bill_expense->amount = $invoice->tax_amount;
                $bill_expense->subtotal = $invoice->tax_amount;
                $bill_expense->save();    
            }
  

            $this->dispatchBrowserEvent('hide-invoiceAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Invoice Approved Successfully"
            ]);
            return redirect()->route('invoices.approved');
        }else {
            $this->dispatchBrowserEvent('hide-invoiceAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Invoice Rejected Successfully"
            ]);
            return redirect()->route('invoices.rejected');
        }
}
catch(\Exception $e){
    $this->dispatchBrowserEvent('hide-invoiceEditModal');
    $this->dispatchBrowserEvent('alert',[
        'type'=>'error',
        'message'=>"Something went wrong while trying to authorize an invoice!!"
    ]);
    }

      }
    public function render()
    {
        $this->invoices = Invoice::where('authorization', 'pending')->latest()->get();
        return view('livewire.invoices.pending',[
            'invoices' => $this->invoices
        ]);
    }
}
