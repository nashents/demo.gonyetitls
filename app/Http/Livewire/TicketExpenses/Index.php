<?php

namespace App\Http\Livewire\TicketExpenses;

use App\Models\Bill;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Product;
use Livewire\Component;
use App\Models\Currency;
use App\Models\BillExpense;
use App\Models\TicketExpense;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    
    public $ticket;
    public $ticket_id;
    public $accounts;
    public $selectedAccount;
    public $products;
    public $selectedProduct;
    public $currencies;
    public $currency_id;
    public $expenses;
    public $selectedExpense;
    public $ticket_expenses;
    public $ticket_expense_id;
    public $qty;
    public $amount;
    public $expense_qty;
    public $weight;
    public $measurement;
    public $subtotal;
    public $usage;

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
    public function mount($ticket){
        $this->ticket = $ticket;
        $this->currencies = Currency::all();
        $this->accounts = Account::whereHas('account_type.account_type_group', function ($query) {
            return $query->where('name','Expenses');
        })->get();
        $this->products = Product::where('buy',True)->orderBy('name','asc')->get();
        $this->ticket_expenses = TicketExpense::where('ticket_id', $this->ticket->id)->latest()->get();
    }


    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'selectedExpense' => 'required',
        'qty' => 'required',
        'amount' => 'required',
    ];

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

    private function resetInputFields(){
        $this->selectedExpense = '';
        $this->selectedAccount = '';
        $this->qty = '';
        $this->currency_id = '';
        $this->amount = '';
    }

    public function updatedSelectedAccount($id){
        if (!is_null($id)) {
            $this->expenses = Expense::where('account_id',$id)->orderBy('name','asc')->get();
        }
    }

    public function store(){
        // try{

        foreach ($this->selectedExpense as $key => $value) {

            $ticket_expense = new  TicketExpense;
            $ticket_expense->ticket_id = $this->ticket->id;
            $ticket_expense->account_id =  $this->selectedAccount;
            $ticket_expense->currency_id =  $this->currency_id;
            if (isset($this->selectedProduct[$key])) {
                $ticket_expense->product_id =  $this->selectedProduct[$key];
            }
            if (isset($this->qty[$key])) {
                $ticket_expense->qty =  $this->qty[$key];
            }
            if (isset($this->amount[$key])) {
                $ticket_expense->amount =  $this->amount[$key];
            }
          
            if ((isset($this->qty[$key]) && $this->qty[$key] > 0) && (isset($this->amount[$key]) && $this->amount[$key] > 0)) {
                $ticket_expense->subtotal = $this->amount[$key] * $this->qty[$key];
            }
            $ticket_expense->save();

            $bill = new Bill;
            $bill->user_id = Auth::user()->id;
            $bill->bill_number = $this->billNumber();
            $bill->ticket_id = $this->ticket->id;
            $bill->ticket_expense_id = $ticket_expense->id;
            $bill->account_id = $this->selectedAccount;
            $bill->bill_date = date('Y-m-d');
            $bill->currency_id = $ticket_expense->currency_id;
            $bill->total = $ticket_expense->subtotal;
            $bill->balance = $ticket_expense->subtotal;
            $bill->save();

            $bill_expense = new BillExpense;
            $bill_expense->user_id = Auth::user()->id;
            $bill_expense->bill_id = $bill->id;
            $bill_expense->currency_id = $bill->currency_id;
            $bill_expense->account_id = $this->selectedAccount;
            if (isset($this->selectedProduct[$key])) {
                $bill_expense->product_id = $this->selectedProduct[$key];
            }
            $bill_expense->qty = 1;
            $bill_expense->amount = $ticket_expense->amount;
            $bill_expense->subtotal = $ticket_expense->amount;
            $bill_expense->save();
        }


        $this->dispatchBrowserEvent('hide-ticket_expenseModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Expense(s) Added Successfully!!"
        ]);

    //     }
    //     catch(\Exception $e){
    //     // Set Flash Message
    //     $this->dispatchBrowserEvent('alert',[
    //         'type'=>'error',
    //         'message'=>"Something goes wrong while creating cargo!!"
    //     ]);
    // }
    }

    public function edit($id){
        $ticket_expense = TicketExpense::find($id);
        $this->selectedExpense = $ticket_expense->expense_id;
        $this->selectedAccount = $ticket_expense->account_id;
        $this->qty = $ticket_expense->qty;
        $this->amount = $ticket_expense->amount;
        $this->subtotal = $ticket_expense->subtotal;
        $this->currency_id = $ticket_expense->currency_id;
        $this->ticket_expense_id = $ticket_expense->id;
        $this->dispatchBrowserEvent('show-ticket_expenseEditModal');
    }

    public function update(){
        // try{

            $ticket_expense = TicketExpense::find($this->ticket_expense_id);
            $ticket_expense->ticket_id = $this->ticket->id;
            $ticket_expense->account_id =  $this->selectedAccount;
            $ticket_expense->expense_id =  $this->selectedExpense;
            $ticket_expense->currency_id =  $this->currency_id;
            $ticket_expense->qty =  $this->qty;
            $ticket_expense->amount =  $this->amount;
            if ((isset($this->qty) && $this->qty > 0) && (isset($this->amount) && $this->amount > 0)) {
                $ticket_expense->subtotal = $this->amount * $this->qty;
            }
            $ticket_expense->update();

            $bill = $ticket_expense->bill;
            $bill->user_id = Auth::user()->id;
            $bill->bill_number = $this->billNumber();
            $bill->ticket_id = $this->ticket->id;
            $bill->ticket_expense_id = $ticket_expense->id;
            $bill->bill_date = now();
            $bill->currency_id = $ticket_expense->currency_id;
            $bill->total = $ticket_expense->subtotal;
            $bill->balance = $ticket_expense->subtotal;
            $bill->update();

            $bill_expense = BillExpense::where('bill_id',$bill->id)
            ->where('expense_id',$ticket_expense->expense_id)->get()->first();
            $bill_expense->user_id = Auth::user()->id;
            $bill_expense->bill_id = $bill->id;
            $bill_expense->currency_id = $bill->currency_id;
            $bill_expense->expense_id = $ticket_expense->expense_id;
            $bill_expense->qty = 1;
            $bill_expense->amount = $ticket_expense->amount;
            $bill_expense->subtotal = $ticket_expense->amount;
            $bill_expense->update();
       

        $this->dispatchBrowserEvent('hide-ticket_expenseEditModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Expense(s) Updated Successfully!!"
        ]);

    //     }
    //     catch(\Exception $e){
    //     // Set Flash Message
    //     $this->dispatchBrowserEvent('alert',[
    //         'type'=>'error',
    //         'message'=>"Something goes wrong while creating cargo!!"
    //     ]);
    // }
    }

    public function render()
    {
        $this->ticket_expenses = TicketExpense::where('ticket_id', $this->ticket->id)->latest()->get();
        return view('livewire.ticket-expenses.index',[
            'ticket_expenses' => $this->ticket_expenses,
            'expense_quantity' => $this->expense_qty,
        ]);
    }
}
