<?php

namespace App\Http\Livewire\Trips;

use App\Models\Bill;
use App\Models\Trip;
use App\Models\Expense;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Currency;
use App\Models\BillExpense;
use App\Models\TripExpense;
use App\Models\ExpenseCategory;
use Illuminate\Support\Facades\Auth;

class Expenses extends Component
{

    public $trip;
    public $trip_id;
    public $user_id;
    public $expenses;
    public $category;
    public $expense_id;
    public $trip_expenses;
    public $trip_expense_id;
    public $usd;
    public $rtgs;
    public $exchange_rate;
    public $exchange_amount;
    public $turnover;
    public $cost_of_sales;
    public $total_transporter_expenses = 0;
    public $total_customer_expenses = 0;

    public $name;
    public $amount;
    public $currencies;
    public $currency_id;

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
        $this->expense_id = "" ;
        $this->amount = "";
        $this->currency_id = "";

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

    public function mount($id){
    $this->trip = Trip::find($id);
    $this->trip_id = $id;
    $this->currencies = Currency::latest()->get();
    $this->expenses = Expense::whereHas('account', function($q){
        $q->where('name', 'Trip Expense');
     })->get();
    $this->trip_expenses = TripExpense::where('trip_id', $this->trip->id)->latest()->get();
    }

    public function updated($value){
        $this->validateOnly($value);
    }

    protected $messages =[
       
        'expense_id.*.required' => 'Expense field is required',
        'expense_id.0.required' => 'Expense field is required',
    ];
    protected $rules = [
        'expense_id.0' => 'required',
        'expense_id.*' => 'required',
    ];


    public function store(){
        // try{
      
            // if (isset($this->expense_id)) {
            // foreach ($this->expense_id as $key => $value) {
            $trip_expense = new TripExpense;
            $trip_expense->user_id = Auth::user()->id;
            $trip_expense->trip_id = $this->trip->id;

            $trip_expense->currency_id = $this->currency_id;
            $trip_expense->expense_id = $this->expense_id;
            $trip_expense->amount = $this->amount;
            $trip_expense->exchange_rate = $this->exchange_rate;
            $trip_expense->exchange_amount = $this->exchange_amount;
            $trip_expense->category = $this->category;

            if (isset($this->category)  && isset($this->amount)) {
                if ($this->currency_id == $this->trip->currency_id) {
                if ($this->category == "Transporter") {
                    $this->total_transporter_expenses = $this->total_transporter_expenses + $this->amount;
                 }elseif ($this->category == "Customer") {
                     $this->total_customer_expenses = $this->total_customer_expenses + $this->amount;
                 }
                }
            }
           
            $trip_expense->save();


            $bill = new Bill;
            $bill->user_id = Auth::user()->id;
            $bill->bill_number = $this->billNumber();
            $bill->trip_id = $this->trip->id;
            $bill->trip_expense_id = $trip_expense->id;
            $bill->horse_id = $this->trip->horse_id;
            $bill->driver_id = $this->trip->driver_id;
            $bill->bill_date = $this->trip->start_date;
            $bill->currency_id = $this->trip->currency_id;
            $bill->total = $trip_expense->amount;
            $bill->balance = $trip_expense->amount;
            $bill->save();

            $bill_expense = new BillExpense;
            $bill_expense->user_id = Auth::user()->id;
            $bill_expense->bill_id = $bill->id;
            $bill_expense->currency_id = $bill->currency_id;
            $bill_expense->expense_id = $trip_expense->expense_id;
            $bill_expense->qty = 1;
            $bill_expense->amount = $trip_expense->amount;
            $bill_expense->subtotal = $trip_expense->amount;
            $bill_expense->save();
            
            // }


                $trip = Trip::find($this->trip->id);
                $this->turnover = $trip->turnover;
                $this->cost_of_sales = $trip->cost_of_sales;
                
                if ($this->total_customer_expenses > 0) {
                    $this->turnover = $this->turnover +  $this->total_customer_expenses;
                    $trip->turnover = $this->turnover;
                }
                if ($this->total_transporter_expenses > 0) {
                    $this->cost_of_sales = $this->cost_of_sales - $this->total_transporter_expenses;
                    $trip->cost_of_sales = $this->cost_of_sales;
                }
                
                if (isset($this->cost_of_sales) && $this->cost_of_sales != "" && $this->cost_of_sales > 0) {
                    $trip->gross_profit = $this->turnover - $this->cost_of_sales;
                    $this->grossprofit = $this->turnover - $this->cost_of_sales;

                    if((isset($this->grossprofit) && $this->grossprofit > 0) && (isset($this->turnover) && $this->turnover > 0)){
                        $trip->markup_percentage = (($this->grossprofit/$this->cost_of_sales) * 100);
                        $trip->gross_profit_percentage = (($this->grossprofit/$this->turnover) * 100);
                    }
                  

                }else {
                    $trip->gross_profit = $this->turnover;
                    $trip->gross_profit_percentage = 100 ;
                    $trip->markup_percentage = 100 ;
                }


            // }

            $this->dispatchBrowserEvent('hide-tripExpenseModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Expense(s) Added Successfully!!"
            ]);

        // }catch(\Exception $e){
        //     // Set Flash Message
        //     $this->dispatchBrowserEvent('alert',[
        //         'type'=>'error',
        //         'message'=>"Something went wrong while adding expense(s)!!"
        //     ]);
        // }
    }
    public function edit($id){

        $expense = TripExpense::find($id);
        $this->user_id = $expense->user_id;
        $this->trip_id = $expense->trip_id;
        $this->trip = Trip::find($this->trip_id);
        $this->currency_id = $expense->currency_id;
        $this->expense_id = $expense->expense_id;
        $this->category = $expense->category;
        $this->amount = $expense->amount;
        $this->exchange_rate = $expense->exchange_rate;
        $this->exchange_amount = $expense->exchange_amount;
        $this->trip_expense_id = $expense->id;
        $this->dispatchBrowserEvent('show-tripExpenseEditModal');

        }

        public function update()
        {
            if ($this->trip_expense_id) {
                try{

                $bill = Bill::where('trip_id',$this->trip_id)
                            ->whereHas('bill_expenses', function($q){
                    $q->where('expense_id', $this->expense_id);
                    })->get()->first();
               
                if (isset($bill)) {   
                    if ($bill->status != "Paid") {
                      
                $trip_expense = TripExpense::find($this->trip_expense_id);
                $trip_expense->amount = $this->amount;
                $trip_expense->trip_id = $this->trip_id;
                $trip_expense->user_id = Auth::user()->id;
                $trip_expense->expense_id = $this->expense_id;
                $trip_expense->category = $this->category;
                $trip_expense->exchange_rate = $this->exchange_rate;
                $trip_expense->exchange_amount = $this->exchange_amount;
                $trip_expense->currency_id = $this->currency_id;
                $trip_expense->update();

               
                $bill->trip_id = $this->trip->id;
                $bill->horse_id = $this->trip->horse_id;
                $bill->driver_id = $this->trip->driver_id;
                $bill->bill_date = $this->trip->start_date;
                $bill->currency_id = $this->trip->currency_id;
                $bill->total = $trip_expense->amount;
                $bill->balance = $trip_expense->amount;
                $bill->update();
    
                $bill_expense = BillExpense::where('bill_id',$bill->id)
                                            ->where('expense_id',$this->expense_id)->get()->first();
                $bill_expense->user_id = Auth::user()->id;
                $bill_expense->bill_id = $bill->id;
                $bill_expense->currency_id = $bill->currency_id;
                $bill_expense->expense_id = $trip_expense->expense_id;
                $bill_expense->qty = 1;
                $bill_expense->amount = $trip_expense->amount;
                $bill_expense->subtotal = $trip_expense->amount;
                $bill_expense->update();

                $this->dispatchBrowserEvent('hide-tripExpenseEditModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Expense Updated Successfully!!"
                ]);
            }else {
                $this->dispatchBrowserEvent('hide-tripExpenseEditModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Expense Update Failed, Bill Paid Already!!"
                ]);
            }
            }
            }catch(\Exception $e){
                // Set Flash Message
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Something went wrong while updating expense(s)!!"
                ]);
            }

            }
        }
    public function render()
    {
        try {
            $this->expenses = Expense::whereHas('account', function($q){
                $q->where('name', 'Trip Expense');
             })->get();
             
        $this->trip_expenses = TripExpense::where('trip_id', $this->trip->id)->latest()->get();
        $trip = Trip::find($this->trip->id);
        if (isset($trip->amount)) {
            $this->usd = TripExpense::where('trip_id', $this->trip->id)
            ->where('currency_id', 1)->latest()->get()->sum('amount');
        }
        if (isset($trip->amount)) {
            $this->rtgs = TripExpense::where('trip_id', $this->trip->id)
            ->where('currency_id', 2)->latest()->get()->sum('amount');
        }

        }catch(\Exception $e){
            // Set Flash Message
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something went wrong while loading expense(s)!!"
            ]);
        }

        if ((isset($this->exchange_rate) && $this->exchange_rate > 0)  &&  ( isset($this->amount) && $this->amount > 0 )) {

            $this->exchange_amount = $this->exchange_rate * $this->amount;

        }
       

        return view('livewire.trips.expenses',[
            'trip_expenses'=> $this->trip_expenses,
            'usd'=> $this->usd,
            'rtgs'=> $this->rtgs,
        ]);
    }
}
