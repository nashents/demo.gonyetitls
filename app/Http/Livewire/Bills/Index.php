<?php

namespace App\Http\Livewire\Bills;


use App\Models\Bill;
use App\Models\Brand;
use App\Models\Account;
use App\Models\Payment;
use App\Models\Receipt;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\Currency;
use App\Models\BankAccount;
use App\Models\Denomination;
use Livewire\WithPagination;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
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
    public $bank_accounts;
    public $bank_account_id;
    public $currencies;
    public $currency_id;
    public $payment_type;
    public $trips;
    public $trip;
    public $trip_id;
    private $bills;
    public $bill;
    public $bill_id;
    public $bill_balance;
    public $bill_currency;
    public $bill_filter;
    public $pop;
    public $reference_code;
    public $name;
    public $denomination;
    public $denomination_qty;
    public $surname;
    public $notes;
    public $user_id;
    public $customer_id;
    public $amount;
    public $current_balance;
    public $mode_of_payment;
    public $accounts;
    public $account_id;
    public $date;
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
   

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'account_id' => 'required',
    ];

    private function resetInputFields(){
        $this->payment_type = '';
        $this->method_of_payment = '';
    }

    private function recalculateBills(){
        $bills = Bill::where('trip_id',"!=", Null)
                        ->where('transporter_id','!=', Null)
                        ->where('status','!=','Paid')->get();

        if ((isset($bills) && $bills->count()>0)) {
                foreach ($bills as $bill) {
                    if (Auth::user()->employee->company->offloading_details == TRUE) {
                        $delivery_note = $bill->trip->delivery_note;
                        $bill->total = $delivery_note->transporter_offloaded_freight; 
                    }
                }
        }
    }

    public function mount(){
        $this->resetPage();
        $this->recalculateBills();
        $this->bill_filter = "created_at";
        $this->currencies = Currency::latest()->get();
        $this->bank_accounts = BankAccount::latest()->get();
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

    
    public function showPayment($id){
        $this->bill_id = $id ;
        $this->bill = Bill::find($id);
        $this->name = Auth::user()->name;
        $this->surname = Auth::user()->surname;
        $this->bill_currency = $this->bill->currency;
        $this->bill_balance = $this->bill->balance;
        $this->current_balance = $this->bill_balance - $this->amount;
        $this->dispatchBrowserEvent('show-paymentModal');
    }

    public function recordPayment(){

        $account = Account::find($this->account_id);
        $current_balance = $account->balance;
        if (isset($current_balance)) {
           
        if ($current_balance >= $this->amount ) {
        
        $account->balance = $current_balance - $this->amount;
        $account->update();

        $payment = new Payment;
        $payment->company_id = Auth::user()->employee->company ? Auth::user()->employee->company->id : "";
        $payment->vendor_id = $this->bill->vendor_id;
        $payment->transporter_id = $this->bill->transporter_id;
        $payment->container_id = $this->bill->container_id;
        $payment->top_up_id = $this->bill->top_up_id;
        $payment->trip_id = $this->bill->trip_id;
        $payment->bill_id = $this->bill->id;
        $payment->user_id = Auth::user()->id;
        $payment->currency_id = $this->bill->currency_id;
        $payment->payment_number = $this->paymentNumber();   
        $payment->name = $this->name;
        $payment->surname = $this->surname;
        $payment->notes = $this->notes;
        $payment->category = "Bill";
        $payment->mode_of_payment = $this->mode_of_payment;
        $payment->payment_type = $this->payment_type;
        $payment->reference_code = $this->reference_code;
        $payment->bank_account_id = $this->bank_account_id;
        $payment->account_id = $this->account_id;
        $payment->amount = $this->amount;
        if ($this->bill) {
            $this->current_balance = $this->bill->balance - $this->amount;
        }
        $payment->balance = $this->current_balance;
        $payment->date = $this->date;
        $payment->save();

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

        $bill = Bill::find($this->bill->id);
        $bill->balance = $this->current_balance;
        if ($this->current_balance <= 0) {
            $bill->status = "Paid";
        }else {
            $bill->status = "Partial";
        }
        $bill->update();

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

        if ($bill->trip) {
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->transporter_id = $this->bill->transporter_id;
            $cashflow->trip_id = $this->bill->trip_id;
            $cashflow->horse_id = $this->bill->horse_id;
            $cashflow->driver_id = $this->bill->driver_id;
            $cashflow->currency_id = $this->bill->currency_id;
            $cashflow->bill_id = $this->bill->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'Direct';
            $cashflow->sub_type = 'Expense';
            $cashflow->category = 'Trip';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();

        }elseif ($bill->vendor) {
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->vendor_id = $this->bill->vendor_id;
            $cashflow->currency_id =$this->bill->currency_id;
            $cashflow->bill_id = $this->bill->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Expense';
            $cashflow->category = 'Vendor';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();

        }elseif ($bill->top_up) {
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->container_id = $this->bill->container_id;
            $cashflow->top_up_id = $this->bill->top_up_id;
            $cashflow->currency_id =$this->bill->currency_id;
            $cashflow->bill_id = $this->bill->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Expense';
            $cashflow->category = 'Fuel';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();
        }elseif($bill->ticket){
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->ticket_id = $this->bill->ticket_id;
            $cashflow->ticket_expense_id = $this->bill->ticket_expense_id;
            $cashflow->currency_id =$this->bill->currency_id;
            $cashflow->bill_id = $this->bill->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Expense';
            $cashflow->category = 'Ticket';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();
        }elseif($bill->inventory){
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->inventory_id = $this->bill->inventory_id;
            $cashflow->ticket_expense_id = $this->bill->ticket_expense_id;
            $cashflow->currency_id =$this->bill->currency_id;
            $cashflow->bill_id = $this->bill->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Expense';
            $cashflow->category = 'Ticket';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();
        }elseif($bill->invoice){
            $cashflow = new CashFlow;
            $cashflow->user_id = Auth::user()->id;
            $cashflow->invoice_id = $this->bill->invoice_id;
            $cashflow->currency_id =$this->bill->currency_id;
            $cashflow->bill_id = $this->bill->id;
            $cashflow->account_id = $this->account_id;
            $cashflow->type = 'InDirect';
            $cashflow->sub_type = 'Expense';
            $cashflow->category = 'Invoice';
            $cashflow->date = $payment->date;
            $cashflow->amount = $payment->amount;
            $cashflow->save();
        }

        $this->dispatchBrowserEvent('hide-paymentModal');
        
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Payment Recorded Successfully!!"
        ]);
        return redirect()->route('payments.index');
              # code...
            } else {

         $this->dispatchBrowserEvent('hide-paymentModal');
         $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Transaction failed, amount to pay exceeds account floating balance!!"
        ]);
                # code...
            }
        }
    }

    
    public function dateRange(){
 
        // $this->resetPage();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
        if ($this->bill_balance != "" && $this->amount != "") {
            $this->current_balance = $this->bill_balance - $this->amount;
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
                    return view('livewire.bills.index',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )
                        ->where('bill_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('bill_date','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('ticket', function ($query) {
                            return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('invoice', function ($query) {
                            return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('transporter', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('container', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('purchase', function ($query) {
                            return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('vendor', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                        'current_balance' => $this->current_balance
                    ]);
                }else {
                    return view('livewire.bills.index',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                        'current_balance' => $this->current_balance
                    ]);
                }
               
            }
            elseif (isset($this->search)) {
               
                return view('livewire.bills.index',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->where('bill_number','like', '%'.$this->search.'%')
                    ->orWhere('status','like', '%'.$this->search.'%')
                    ->orWhere('bill_date','like', '%'.$this->search.'%')
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('ticket', function ($query) {
                        return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('invoice', function ($query) {
                        return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('transporter', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('currency', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('container', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('purchase', function ($query) {
                        return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('vendor', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                    'current_balance' => $this->current_balance
                ]);
            }
            else {
               
                return view('livewire.bills.index',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth('created_at', date('m'))
                    ->whereYear($this->bill_filter, date('Y'))->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                    'current_balance' => $this->current_balance
                ]);
              
            }
        }else {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.bills.index',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
                        ->where('bill_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('bill_date','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('ticket', function ($query) {
                            return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('invoice', function ($query) {
                            return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('transporter', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('container', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('purchase', function ($query) {
                            return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('vendor', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                        'current_balance' => $this->current_balance
                    ]);
                }else{
                    return view('livewire.bills.index',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                        'current_balance' => $this->current_balance
                    ]);
                }
              
               
            }
            elseif (isset($this->search)) {
                return view('livewire.bills.index',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth($this->bill_filter, date('m'))
                    ->whereYear($this->bill_filter, date('Y'))->where('bill_number','like', '%'.$this->search.'%')->where('user_id',Auth::user()->id)
                    ->where('bill_number','like', '%'.$this->search.'%')
                    ->orWhere('bill_date','like', '%'.$this->search.'%')
                    ->orWhere('status','like', '%'.$this->search.'%')
                   
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('ticket', function ($query) {
                        return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('currency', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('invoice', function ($query) {
                        return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('transporter', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('container', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('purchase', function ($query) {
                        return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('vendor', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                    'current_balance' => $this->current_balance
                ]);
            }
            else {
                
                return view('livewire.bills.index',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth($this->bill_filter, date('m'))
                    ->whereYear($this->bill_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                    'current_balance' => $this->current_balance
                ]);

            }

        }
   

    }
}
