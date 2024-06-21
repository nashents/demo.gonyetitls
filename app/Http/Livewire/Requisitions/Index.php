<?php

namespace App\Http\Livewire\Requisitions;

use App\Models\Trip;
use App\Models\Account;
use App\Models\Expense;
use Livewire\Component;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Department;
use App\Models\Requisition;
use Livewire\WithPagination;
use App\Models\RequisitionItem;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    protected $queryString = ['search'];
    public $from;
    public $to;

    public $requisition_filter;

    public $accounts;
    public $account;
    public $selectedAccount;
    public $trips;
    public $trip_id;
    public $expenses;
    public $expense_id;
    public $currencies;
    public $currency_id;
    private $requisitions;
    public $requisition;
    public $subject;
    public $requisition_id;
    public $employees;
    public $employee;
    public $employee_id;
    public $departments;
    public $department;
    public $department_id;
    public $description;
    public $date;
    public $qty;
    public $amount;
    public $total;
    public $subtotal;
    public $exchange_amount;
    public $exchange_rate;


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

    private function resetInputFields(){
        $this->trip_id = '';
        $this->employee_id = '';
        $this->department_id = '';
        $this->date = '';
        $this->currency_id = '';
        $this->expense_id = '';
        $this->selectedAccount = '';
        $this->qty = '';
        $this->amount = '';
        $this->description = '';
        $this->subject = '';
    }
    


    public function mount(){
        $this->requisition_filter = 'created_at';
        $this->resetPage();
        $this->employees = Employee::all();
        $this->trips = Trip::orderBy('trip_number','desc')->get();
        $this->departments = Department::all();
        $this->currencies = Currency::all();
        $this->accounts = Account::all();
        $this->expenses = collect();
    }

    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        'currency_id' => 'required',
        'expense_id.0' => 'required',
        'selectedAccount.0' => 'required',
        'employee_id.0' => 'required',
        'qty.0' => 'required',
        'amount.0' => 'required',
        'expense_id.*' => 'required',
        'selectedAccount.*' => 'required',
        'qty.*' => 'required',
        'amount.*' => 'required',
    ];


    public function requisitionNumber(){

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

        $requisition = Requisition::latest()->orderBy('id','desc')->first();

        if (!$requisition) {
            $requisition_number =  $initials .'RQ'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $requisition->id + 1;
            $requisition_number =  $initials .'RQ'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $requisition_number;


    }

    public function updatedSelectedAccount($id){
            if (!is_null($id)) {
                $this->expenses = Expense::where('account_id',$id)->get();
            }
    }

    public function store(){

        // try{

        $requisition = new Requisition;
        $requisition->requisition_number = $this->requisitionNumber();
        $requisition->user_id = Auth::user()->id;
        $requisition->department_id = $this->department_id;
        $requisition->trip_id = $this->trip_id;
        $requisition->employee_id = $this->employee_id;
        $requisition->currency_id = $this->currency_id;
        $requisition->date = $this->date;
        $requisition->description = $this->description;
        $requisition->subject = $this->subject;
        $requisition->status = "Unpaid";
        $requisition->save();

        if (isset($this->expense_id)) {
            foreach($this->expense_id as $key => $value){
                $requisition_item = new RequisitionItem;
                $requisition_item->requisition_id = $requisition->id;
                if (isset($this->expense_id[$key])) {
                    $requisition_item->expense_id = $this->expense_id[$key];
                }
                if (isset($this->selectedAccount[$key])) {
                    $requisition_item->account_id = $this->selectedAccount[$key];
                }
                if (isset($this->qty[$key])) {
                    $requisition_item->qty = $this->qty[$key];
                }
                if (isset($this->amount[$key])) {
                    $requisition_item->amount = $this->amount[$key];
                }
                if (isset($this->amount[$key]) && isset($this->qty[$key])) {
                $this->subtotal = ($this->amount[$key] * $this->qty[$key]);
                }
                $requisition_item->subtotal = $this->subtotal;
                $requisition_item->save();
                $this->total = $this->total +   $this->subtotal ;
            }
        }
        $requisition = Requisition::find($requisition->id);
        $requisition->total = $this->total;
        $requisition->exchange_rate = $this->exchange_rate;
        if (isset($this->exchange_rate) && isset($this->total)) {
           $exchange_amount = $this->exchange_rate * $this->total;
           $requisition->exchange_amount = $exchange_amount;
        }
        $requisition->update();

        $this->dispatchBrowserEvent('hide-requisitionModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Requisition Created Successfully!!"
        ]);

//     }
//     catch(\Exception $e){
//     // Set Flash Message
//     $this->dispatchBrowserEvent('alert',[
//         'type'=>'error',
//         'message'=>"Something goes wrong while creating requisition!!"
//     ]);
// }

    }

    public function edit($id){
        $requisition = Requisition::find($id);
        $this->currency_id = $requisition->currency_id;
        $this->trip_id = $requisition->trip_id;
        $this->employee_id = $requisition->employee_id;
        $this->department_id = $requisition->department_id;
        $this->date = $requisition->date;
        $this->description = $requisition->description;
        $this->subject = $requisition->subject;
        $this->requisition_id = $requisition->id;
        $this->dispatchBrowserEvent('show-requisitionEditModal');
    }

        public function update(){

        // try{

        $requisition =  Requisition::find($this->requisition_id);
        $requisition->user_id = Auth::user()->id;
        $requisition->department_id = $this->department_id;
        $requisition->trip_id = $this->trip_id;
        $requisition->employee_id = $this->employee_id;
        $requisition->currency_id = $this->currency_id;
        $requisition->date = $this->date;
        $requisition->description = $this->description;
        $requisition->subject = $this->subject;
        $requisition->update();


        $this->dispatchBrowserEvent('hide-requisitionEditModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Requisition Updated Successfully!!"
        ]);

//     }
//     catch(\Exception $e){
//     // Set Flash Message
//     $this->dispatchBrowserEvent('alert',[
//         'type'=>'error',
//         'message'=>"Something goes wrong while creating requisition!!"
//     ]);
// }

    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $this->requisitions = Requisition::all();
        $this->employees = Employee::all();
        $this->departments = Department::all();
        $this->currencies = Currency::all();
        $this->accounts = Account::all();
        $this->trips = Trip::orderBy('trip_number','desc')->get();
      
        if (isset($this->from) && isset($this->to)) {
            if (isset($this->search)) {
                return view('livewire.requisitions.index',[
                    'requisitions' => Requisition::query()->with('employee','department','trip','currency','payments')->whereBetween($this->requisition_filter,[$this->from, $this->to] )
                    ->where('requisition_number','like', '%'.$this->search.'%')
                    ->orWhere('status','like', '%'.$this->search.'%')
                    ->orWhere('date','like', '%'.$this->search.'%')
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('currency', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('requisition_number','desc')->paginate(10),
                    'requisition_filter' => $this->requisition_filter,
                    'employees' => $this->employees,
                    'departments' => $this->departments,
                    'currencies' => $this->currencies,
                    'accounts' => $this->accounts,
                    'trips' => $this->trips,
                ]);
            }else {
                return view('livewire.requisitions.index',[
                    'requisitions' => requisition::query()->with('employee','department','trip','currency','payments')->whereBetween($this->requisition_filter,[$this->from, $this->to] )->orderBy('requisition_number','desc')->paginate(10),
                    'requisition_filter' => $this->requisition_filter,
                    'employees' => $this->employees,
                    'departments' => $this->departments,
                    'currencies' => $this->currencies,
                    'accounts' => $this->accounts,
                    'trips' => $this->trips,
                ]);
            }
           
        }
        elseif (isset($this->search)) {
           
            return view('livewire.requisitions.index',[
                'requisitions' => Requisition::query()->with('employee','department','trip','currency','payments')->whereMonth('created_at', date('m'))
                ->whereYear('created_at', date('Y'))
                ->where('requisition_number','like', '%'.$this->search.'%')
                ->orWhere('status','like', '%'.$this->search.'%')
                ->orWhere('date','like', '%'.$this->search.'%')
                ->orWhereHas('trip', function ($query) {
                    return $query->where('trip_number', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('currency', function ($query) {
                    return $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orderBy('requisition_number','desc')->paginate(10),
                'requisition_filter' => $this->requisition_filter,
                'employees' => $this->employees,
                'departments' => $this->departments,
                'currencies' => $this->currencies,
                'accounts' => $this->accounts,
                'trips' => $this->trips,
            ]);
        }
        else {
           
            return view('livewire.requisitions.index',[
                'requisitions' => Requisition::query()->with('employee','department','trip','currency','payments')->whereMonth('created_at', date('m'))
                ->whereYear($this->requisition_filter, date('Y'))->orderBy('requisition_number','desc')->paginate(10),
                'requisition_filter' => $this->requisition_filter,
                'employees' => $this->employees,
                'departments' => $this->departments,
                'currencies' => $this->currencies,
                'accounts' => $this->accounts,
                'trips' => $this->trips,
            ]);
          
        }
       
   

    }
}
