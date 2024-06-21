<?php

namespace App\Http\Livewire\Bills;

use App\Models\Bill;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Product;
use Livewire\Component;
use App\Models\BillExpense;

class Expenses extends Component
{

    public $expenses;
    public $expense_id;
    public $qty;
    public $description;
    public $amount;
    public $subtotal;
    public $total;
    public $bills;
    public $bill;
    public $bill_id;
    public $bill_expenses;
    public $bill_expense_id;

    public $products;
    public $selectedProduct;
    public $accounts;
    public $selectedAccount;
    public $tax_accounts;
    public $selectedTax;

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

    public function mount($id){
        $this->bill_id = $id;
        $this->bill = Bill::find($id);
        $this->bill_expenses = BillExpense::where('bill_id', $this->bill_id)->get();
        $this->products = Product::where('buy',True)->orderBy('name','asc')->get();
        $this->tax_accounts = Account::whereHas('account_type', function ($query) {
            return $query->where('name','Sales Taxes');
        })->orderBy('name','asc')->get();
        $this->accounts = Account::whereHas('account_type.account_type_group', function ($query) {
            return $query->where('name','Expenses');
        })->orderBy('name','asc')->get();
    }

  
    public function updatedSelectedProduct($id){
        if (!is_null($id)) {
            $product = Product::find($id);
            if (isset($product)) {
                if ($product->price) {
                    $this->amount = $product->price;
                }
                if ($product->description) {
                    $this->description = $product->description;
                }
                if ($product->expense_account_id) {
                    $this->selectedAccount = $product->expense_account_id;
                }
                if ($product->tax_id) {
                    $this->selectedTax = $product->tax_id;
                    $tax = Account::find($product->tax_id);
                    if (isset($tax)) {
                        $this->tax_rate = $tax->rate;
                    }
                    
                }  
            }
           
        }
    }

    public function updatedSelectedTax($id, $key){
        if(!is_null($id)){
            $tax = Account::find($id);
            if (isset($tax)) {
                $this->tax_rate = $tax->rate;
            }
           
        }
    }

    public function store(){
        if (isset($this->expense_id)) {
            foreach ($this->expense_id as $key => $value) {
                $bill_expense = new BillExpense;
                $bill_expense->bill_id = $this->bill->id;
                $bill_expense->currency_id = $this->bill->currency_id;
                if (isset($this->expense_id[$key])) {
                    $bill_expense->expense_id = $this->expense_id[$key];
                }
                if (isset($this->qty[$key])) {
                    $bill_expense->qty = $this->qty[$key];
                }
                if (isset($this->description[$key])) {
                    $bill_expense->description = $this->description[$key];
                }
                if (isset($this->amount[$key])) {
                    $bill_expense->amount = $this->amount[$key];
                }
                if (isset( $this->amount[$key]) && isset($this->qty[$key])) {
                    $bill_expense->subtotal = $this->amount[$key] * $this->qty[$key];
                }
               
              $bill_expense->save();  

            }
            $this->total = BillExpense::where('bill_id',$this->bill->id)->sum('subtotal');
            $bill = Bill::find($this->bill->id);
            $bill->total = $this->total;
            $bill->update();

            $this->dispatchBrowserEvent('hide-bill_expenseModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Bill Expense(s) Added Successfully!!"
            ]);
        }
      
    }

    public function edit($id){
        $bill_expense = BillExpense::find($id);
        $this->selectedProduct = $bill_expense->product_id;
        $this->selectedAccount = $bill_expense->account_id;
        $this->selectedTax = $bill_expense->tax_id;
        $this->qty = $bill_expense->qty;
        $this->description = $bill_expense->description;
        $this->bill_id = $bill_expense->bill_id;
        $this->amount = $bill_expense->amount;
        $this->bill_expense_id = $bill_expense->id;
        $this->dispatchBrowserEvent('show-bill_expenseEditModal');
    }

    public function update(){
   
                $bill_expense = BillExpense::find($this->bill_expense_id);
                $bill_expense->product_id = $this->selectedProduct;
                $bill_expense->account_id = $this->selectedAccount;
                $bill_expense->tax_id = $this->selectedTax;
                $bill_expense->qty = $this->qty;
                $bill_expense->description = $this->description;
                $bill_expense->amount = $this->amount;

                if (isset( $this->amount) && isset($this->qty)) {
                    $bill_expense->subtotal = $this->amount * $this->qty;
                }
               
              $bill_expense->update();

              $this->total = BillExpense::where('bill_id',$this->bill_id)->sum('subtotal');
              $bill = Bill::find($this->bill_id);
              $bill->total = $this->total;
              $bill->update();
            
              
              $this->dispatchBrowserEvent('hide-bill_expenseEditModal');
              $this->dispatchBrowserEvent('alert',[
                  'type'=>'success',
                  'message'=>"Expense(s) Updated Successfully!!"
              ]);
      

      
    }

   
    public function render()
    {
        $this->bill_expenses = BillExpense::where('bill_id', $this->bill_id)->get();
        return view('livewire.bills.expenses',[
            'bill_expenses' => $this->bill_expenses,
            'expenses' => $this->expenses,
        ]);
    }
}
