<?php

namespace App\Http\Livewire\Invoices;

use Carbon\Carbon;
use App\Models\Cargo;
use App\Models\Account;
use App\Models\Company;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Receipt;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Document;
use App\Models\BankAccount;
use App\Models\Destination;
use App\Models\Denomination;
use Livewire\WithPagination;
use Livewire\WithFileUploads;
use App\Models\InvoicePayment;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    use WithFileUploads;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $search;
    protected $queryString = ['search'];
    public $from;
    public $to;
    public $invoice_filter;
    private $invoices;
    public $invoice_products;
    public $invoice;
    public $invoice_id;
    public $customers;
    public $currencies;
    public $currency_id;
    public $receipt_number;
    public $invoice_number;
    public $date;
    public $amount;
    public $receipt;
    public $balance;   
    public $accounts;
    public $account_id;
    public $bank_accounts;
    public $bank_account_id;
    public $trips;
    public $trip;
    public $trip_id;
    public $notes;
    public $invoice_balance;
    public $pop;
    public $reference_code;
    public $invoice_currency;
    public $name;
    public $denomination;
    public $denomination_qty;
    public $surname;
    public $user_id;
    public $customer_id;
    public $current_balance;
    public $mode_of_payment;
    public $selectedCustomer ;
    public $customer_accounts;
    public $selectedCustomerAccount;
    public $unpaid_invoices;
    public $selectedInvoice;
    public $account_payments;
    public $payment_id;
    public $selectedPayment;
    public $drawdown_invoice_balance;
    public $drawdown_amount;
    public $invoice_drawdown_current_balance;
    public $invoice_drawdown_balance;
    public $payment_drawdown_balance;
    public $amount_paid;

    public $expires_at;
    public $title;
    public $file;


    public $inputs = [];
    public $i = 1;
    public $n = 1;

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }
   
    

    public function mount(){
        $this->resetPage();
        $this->invoice_filter = "created_at";
        $this->currencies = Currency::latest()->get();
        $this->bank_accounts = BankAccount::latest()->get();
        $this->customers = Customer::latest()->get();
        $this->accounts = Account::where('account_type_id',1)->latest()->get();

    }



    public function receiptNumber(){

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
    
        $receipt = Receipt::orderBy('id','desc')->first();
    
        if (!$receipt) {
            $receipt_number =  $initials .'R'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $receipt->id + 1;
            $receipt_number =  $initials .'R'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
    
        return  $receipt_number;
    
    
    }
    public function paymentNumber(){

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
    
        $payment = Payment::orderBy('id','desc')->first();
    
        if (!$payment) {
            $payment_number =  $initials .'P'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $payment->id + 1;
            $payment_number =  $initials .'P'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
    
        return  $payment_number;
    
    
    }


    public function updatedSelectedCustomer($id){
        if (!is_null($id)) {
            $this->selectedCustomer = $id;
            $this->customer_accounts = Account::where('customer_id',$id)->latest()->get();
        } 
    }

    public function updatedSelectedCustomerAccount($id){
        if (!is_null($id)) {
            $this->selectedCustomerAccount = $id;
            $account = Account::find($id);
            $currency_id = $account->currency_id;
            $this->unpaid_invoices = Invoice::where('customer_id',$this->selectedCustomer)
            ->where('currency_id',$currency_id)
            ->where('authorization','approved')
            ->where('status','Unpaid')
            ->orWhere('customer_id',$this->selectedCustomer)
            ->where('currency_id',$currency_id)
            ->where('authorization','approved')
            ->where('status','Partial')
            ->orderBy('created_at','desc')->get();

            $this->account_payments = Payment::where('customer_account_id',$id)
            ->where('drawdown_balance','>',0)
            ->orderBy('created_at','desc')->get();
        } 
    }

    public function updatedSelectedPayment($id){
        if (!is_null($id)) {
            $this->selectedPayment = $id;
            $payment = Payment::find($id);
            $this->drawdown_amount = $payment->drawdown_balance;
        }
    }   

    public function updatedSelectedInvoice($id){
        if (!is_null($id)) {
        $this->selectedInvoice = $id;
        $this->invoice = Invoice::find($id);
        $this->invoice_drawdown_balance = $this->invoice->balance;
      

        if (isset($this->drawdown_amount) && isset($this->invoice_drawdown_balance) && ($this->drawdown_amount >= $this->invoice_drawdown_balance)) {
            $this->payment_drawdown_balance = $this->drawdown_amount - $this->invoice_drawdown_balance;
            $this->invoice_drawdown_balance = 0;
            $this->amount_paid = $this->invoice_drawdown_balance;
        }else{
            $this->invoice_drawdown_balance = $this->invoice_drawdown_balance - $this->drawdown_amount;
            $this->payment_drawdown_balance = 0;
            $this->amount_paid = $this->drawdown_amount;
        }
        
        }
      
    }




    public function showPayment($id){
        $this->invoice_id = $id ;
        $this->invoice = Invoice::find($id);
        $this->invoice_currency = $this->invoice->currency;
        $this->customer_id = $this->invoice->customer_id;
        $this->invoice_balance = $this->invoice->balance;
        $this->current_balance = $this->invoice_balance - $this->amount;
        $this->dispatchBrowserEvent('show-paymentModal');
    }

    public function drawdownPayments(){

        $payment = Payment::find($this->selectedPayment);
        $payment->drawdown_balance = $this->payment_drawdown_balance;
        $payment->update();
        
        $invoice = Invoice::find($this->selectedInvoice); 

        $invoice_payment = new InvoicePayment;
        $invoice_payment->invoice_id = $this->selectedInvoice;
        $invoice_payment->customer_id = $invoice->customer_id;
        $invoice_payment->payment_id = $this->selectedPayment;
        $invoice_payment->currency_id = $invoice->currency_id;
        $invoice_payment->amount = $this->amount_paid;
        $invoice_payment->save();
      
    

        $invoice = Invoice::find($this->selectedInvoice);
        $invoice->balance = $this->invoice_drawdown_balance;
        if ($this->invoice_drawdown_balance <= 0) {
            $invoice->status = "Paid";
        }else {
            $invoice->status = "Partial";
        }
        $invoice->update();
 
        $this->dispatchBrowserEvent('hide-paymentDrawdownModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Payment Drawdown Effected Successfully!!"
        ]);
       
    }

    public function recordPayment(){

      
        $payment = new Payment;
        $payment->company_id = Auth::user()->employee->company ? Auth::user()->employee->company->id : "";
        $payment->customer_id = $this->invoice->customer_id;
        $payment->user_id = Auth::user()->id;
        $payment->currency_id = $this->invoice->currency_id;
        $payment->payment_number = $this->paymentNumber();   
        $payment->name = $this->name;
        $payment->notes = $this->notes;
        $payment->surname = $this->surname;
        $payment->mode_of_payment = $this->mode_of_payment;
        $payment->category = "invoice";
        $payment->reference_code = $this->reference_code;
        $payment->bank_account_id = $this->bank_account_id;
        $payment->invoice_id = $this->invoice->id;
        $payment->account_id = $this->account_id;
        $payment->amount = $this->amount;
        if ($this->invoice) {
            $this->current_balance = $this->invoice->balance - $this->amount;
        }
        $payment->balance = $this->current_balance;
        $payment->date = $this->date;
        $payment->save();

        $invoice_payment = new InvoicePayment;
        $invoice_payment->customer_id = $this->invoice->customer_id;
        $invoice_payment->invoice_id = $this->invoice->id;
        $invoice_payment->payment_id = $payment->id;
        $invoice_payment->currency_id = $this->invoice->currency_id;
        $invoice_payment->amount = $this->amount;
        $invoice_payment->save();  
        

        if(isset($this->pop)){
            $file = $this->pop;
            // get file with ext
            $fileNameWithExt = $file->getClientOriginalName();
            //get filename
            $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
            //get extention
            $extention = $file->getClientOriginalExtension();
            //file name to store
            $fileNameToStore = $filename.'_'.time().'.'.$extention;
            $file->storeAs('/documents', $fileNameToStore, 'my_files');

                    $document = new Document;
        $document->payment_id = $payment->id;
        $document->category = 'payment';
        $document->title = "Proof Of Payment";
        if (isset( $fileNameToStore)) {
             $document->filename = $fileNameToStore;
        }
        if(isset($this->expires_at)){
            $document->expires_at = Carbon::create($this->expires_at[$key])->toDateTimeString();
            $today = now()->toDateTimeString();
            $expire = Carbon::create($this->expires_at[$key])->toDateTimeString();
            if ($today <=  $expire) {
                $document->status = 1;
            }else{
                $document->status = 0;
            }
        }else {
        $document->status = 1;
        }
        $document->save();
  
        }


        $invoice = Invoice::find($this->invoice->id);
        $invoice->balance = $this->current_balance;
        if ($this->current_balance <= 0) {
            $invoice->status = "Paid";
        }else {
            $invoice->status = "Partial";
        }
        $invoice->update();

        if ($this->denomination) {
            foreach ($this->denomination as $key => $value) {
                $denomination = new Denomination;
                $denomination->payment_id = $payment->id;
                if (isset($this->denomination[$key])) {
                    $denomination->denomination = $this->denomination[$key];
                }
              if (isset($this->denomination_qty[$key])) {
                $denomination->quantity =  $this->denomination_qty[$key];
              }
               
                $denomination->save();
            }
        }

        if (isset($this->account_id)) {
            $account = Account::find($this->account_id);
            $current_balance = $account->balance;
            $account->balance = $current_balance + $this->amount;
            $account->update();
        }
     

        
        $cashflow = new CashFlow;
        $cashflow->user_id = Auth::user()->id;
        $cashflow->customer_id = $this->invoice->customer_id;
        $cashflow->currency_id =$this->invoice->currency_id;
        $cashflow->invoice_id = $this->invoice->id;
        $cashflow->payment_id = $payment->id;
        $cashflow->account_id = $this->account_id;
        $cashflow->type = 'Direct';
        $cashflow->sub_type = 'Income';
        $cashflow->category = 'Invoice';
        $cashflow->transaction_type = 'Deposit';
        $cashflow->date = $payment->date;
        $cashflow->amount = $payment->amount;
        $cashflow->save();        

      
        $receipt =  new Receipt;
        $receipt->payment_id = $payment->id;
        $receipt->company_id = $payment->company_id;
        $receipt->invoice_id = $payment->invoice_id;
        $receipt->vendor_id = $payment->vendor_id;
        $receipt->transporter_id = $payment->transporter_id;
        $receipt->currency_id = $payment->currency_id;
        $receipt->receipt_number = $this->receiptNumber(); ;
        $receipt->user_id = Auth::user()->id;
        $receipt->amount = $this->amount;
        $receipt->balance = $this->current_balance;
        $receipt->date = $this->date;
        $receipt->save();

       
        $this->dispatchBrowserEvent('hide-paymentModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Payment Recorded Successfully!!"
        ]);
        return redirect()->route('payments.index');
       
    }

    public function dateRange(){

    }
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {

        $this->amount;
        $this->invoice_balance;

        if ($this->invoice_balance != "" && $this->amount != "") {
            $this->current_balance = $this->invoice_balance - $this->amount;
        }

        $departments = Auth::user()->employee->departments;
        foreach($departments as $department){
            $department_names[] = $department->name;
        }
        $roles = Auth::user()->roles;
        foreach($roles as $role){
            $role_names[] = $role->name;
        }
        $ranks = Auth::user()->employee->ranks;
        foreach($ranks as $rank){
            $rank_names[] = $rank->name;
        }
        if (in_array('Admin', $role_names) || in_array('Super Admin', $role_names)) {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.invoices.index',[
                        'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereBetween($this->invoice_filter,[$this->from, $this->to] )
                        ->where('invoice_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('date','like', '%'.$this->search.'%')
                        ->orWhere('expiry','like', '%'.$this->search.'%')
                        ->orWhere('authorization','like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('created_at','desc')->paginate(10),
                        'invoice_filter' => $this->invoice_filter,
                        'current_balance' => $this->current_balance
                    ]);
                }else {
                    return view('livewire.invoices.index',[
                        'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereBetween($this->invoice_filter,[$this->from, $this->to] )->orderBy('created_at','desc')->paginate(10),
                        'invoice_filter' => $this->invoice_filter,
                        'current_balance' => $this->current_balance
                    ]);
                }
               
            }
            elseif (isset($this->search)) {
               
                return view('livewire.invoices.index',[
                    'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->where('invoice_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('date','like', '%'.$this->search.'%')
                        ->orWhere('expiry','like', '%'.$this->search.'%')
                        ->orWhere('authorization','like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('created_at','desc')->paginate(10),
                        'invoice_filter' => $this->invoice_filter,
                        'current_balance' => $this->current_balance
                ]);
            }
            else {
               
                return view('livewire.invoices.index',[
                    'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereMonth('created_at', date('m'))
                    ->whereYear($this->invoice_filter, date('Y'))->orderBy('created_at','desc')->paginate(10),
                    'invoice_filter' => $this->invoice_filter,
                    'current_balance' => $this->current_balance
                ]);
              
            }
        }else {

           

            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.invoices.index',[
                        'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereBetween($this->invoice_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
                        ->where('invoice_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('date','like', '%'.$this->search.'%')
                        ->orWhere('expiry','like', '%'.$this->search.'%')
                        ->orWhere('authorization','like', '%'.$this->search.'%')
                        ->orWhereHas('customer', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('created_at','desc')->paginate(10),
                        'invoice_filter' => $this->invoice_filter,
                        'current_balance' => $this->current_balance
                    ]);
                }else{
                    return view('livewire.invoices.index',[
                        'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereBetween($this->invoice_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10),
                        'invoice_filter' => $this->invoice_filter,
                        'current_balance' => $this->current_balance
                    ]);
                }
              
               
            }
            elseif (isset($this->search)) {
                return view('livewire.invoices.index',[
                    'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereMonth($this->invoice_filter, date('m'))
                    ->whereYear($this->invoice_filter, date('Y'))->where('trip_number','like', '%'.$this->search.'%')->where('user_id',Auth::user()->id)
                    ->where('invoice_number','like', '%'.$this->search.'%')
                    ->orWhere('status','like', '%'.$this->search.'%')
                    ->orWhere('date','like', '%'.$this->search.'%')
                    ->orWhere('expiry','like', '%'.$this->search.'%')
                    ->orWhere('authorization','like', '%'.$this->search.'%')
                    ->orWhereHas('customer', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('currency', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('created_at','desc')->paginate(10),
                    'invoice_filter' => $this->invoice_filter,
                    'current_balance' => $this->current_balance
                ]);
            }
            else {
                
                return view('livewire.invoices.index',[
                    'invoices' => Invoice::query()->with(['customer:id,name','currency'])->whereMonth($this->invoice_filter, date('m'))
                    ->whereYear($this->invoice_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10),
                    'invoice_filter' => $this->invoice_filter,
                    'current_balance' => $this->current_balance
                ]);

            }

        }

    }
}
