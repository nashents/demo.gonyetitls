<?php

namespace App\Http\Livewire\Containers;

use App\Models\Bill;
use App\Models\TopUp;
use App\Models\Vendor;
use App\Models\Expense;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Currency;
use App\Models\Container;
use App\Models\BillExpense;
use Illuminate\Support\Str;
use Livewire\WithPagination;
use App\Models\ContainerCount;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    protected $queryString = ['search'];
    public $from;
    public $to;

    private $containers;
    public $container_filter;
    public $container_number;
    public $container_id;
    public $container_currency_id;
    public $currency_id;
    public $currencies;
    public $vendor_id;
    public $name;
    public $expense_id;
    public $date;
    public $email;
    public $phonenumber;
    public $purchase_type;
    public $address;
    public $total_fuel;
    public $vendors;
    public $capacity;
    public $quantity;
    public $rate;
    public $fuel_type;
    public $amount;
    public $balance;



    public $user_id;

    public function mount(){
        $this->resetPage();
        $this->currencies = Currency::all();
        $value = 'Fuel';
        $this->vendors = Vendor::with(['vendor_type'])
        ->whereHas('vendor_type', function($q) use($value) {
        $q->where('name', '=', $value);
        })->get();
    }
    public function containerNumber(){
       
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

            $container = Container::orderBy('id', 'desc')->first();

        if (!$container) {
            $container_number =  $initials .'FS'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $container->id + 1;
            $container_number =  $initials .'FS'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $container_number;


    }

    public function orderNumber(){
       
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

            $container = TopUp::where('container_id', $this->container_id)->orderBy('id', 'desc')->first();

        if (!$container) {
            $container_number =  $initials .'TO'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $container->id + 1;
            $container_number =  $initials .'TO'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $container_number;


    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        // 'capacity' => 'required',
        'name' => 'required|unique:cargos,name,NULL,id,deleted_at,NULL|string|min:2',
        'email' => 'nullable|email',
        'phonenumber' => 'nullable',
        'container_currency_id' => 'required',
        'address' => 'nullable',
        'fuel_type' => 'required',
        'purchase_type' => 'required',
    ];
    private function resetInputFields(){

        $this->balance = "";
        $this->vendor_id = "";
        $this->name = "";
        $this->email = "";
        $this->container_currency_id = "";
        $this->phonenumber = "";
        $this->address = "";
        $this->purchase_type = "";
        $this->currency_id = "";
        $this->fuel_type = "";
        $this->capacity = "";
        $this->quantity = "";
        $this->rate = "";
        $this->amount = "";
    }
    public function showTopUpModal($id){
        $this->container_id = $id;
        $container = Container::find($id);
        $this->fuel_type = $container->fuel_type;
        $this->capacity = $container->capacity;
        $this->balance = $container->balance;
        $this->dispatchBrowserEvent('show-top_upModal');
    }
    public function topup(){

        try{

        $container = Container::find($this->container_id);
        $top_up = new TopUp;
        $top_up->user_id = Auth::user()->id;
        $top_up->order_number = $this->orderNumber();
        $top_up->container_id = $container->id ? $container->id : NULL;
        $top_up->vendor_id = $this->vendor_id ? $this->vendor_id : NULL;
        $top_up->date = $this->date;
        $top_up->currency_id = $this->currency_id ? $this->currency_id : NULL;
        $top_up->fuel_type = $container->fuel_type;
        $top_up->quantity = $this->quantity;
        $top_up->rate = $this->rate;
        $top_up->amount = $this->amount;
        $top_up->save();

        $container = Container::find($this->container_id);
        $container->balance = $container->balance + $this->quantity;
        $container->update();

        if (isset($top_up->amount) && $top_up->amount > 0) {
            $bill = new Bill;
            $bill->user_id = Auth::user()->id;
            $bill->bill_number = $this->billNumber();
            $bill->container_id = $container->id;
            $bill->top_up_id = $top_up->id;
            $bill->vendor_id = $top_up->vendor_id;
            $bill->category = "Fuel Topup";
            $bill->bill_date = date("Y-m-d");
            $bill->currency_id =  $this->currency_id;
            $bill->total =  $top_up->amount;
            $bill->balance =  $top_up->amount;
            $bill->save();

            $expense = Expense::where('name','Fuel Topup')->get()->first();
    
            $bill_expense = new BillExpense;
            $bill_expense->user_id = Auth::user()->id;
            $bill_expense->bill_id = $bill->id;
            $bill_expense->currency_id = $bill->currency_id;
            if (isset($expense)) {
                $bill_expense->expense_id = $expense->id;
            }
            $bill_expense->qty = 1;
            $bill_expense->amount = $top_up->amount;
            $bill_expense->subtotal = $top_up->amount;
            $bill_expense->save();
        }



        $this->dispatchBrowserEvent('hide-top_upModal');
        $this->resetInputFields();
        Session::flash('success','Fuel Top Up Created Successfully!!');
        return redirect(route('top_ups.manage',$this->container_id));
       
        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('hide-top_upModal');
        $this->dispatchBrowserEvent('alert',[

            'type'=>'error',
            'message'=>"Something went wrong while creating fuel topup!!"
        ]);
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


    public function store(){
        try{
        $existing_container = Container::where('name',$this->name)->get()->first();
        if (!$existing_container) {

        $container = new Container;
        $container->user_id = Auth::user()->id;
        $container->container_number = $this->containerNumber();
        $container->name = $this->name;
        $container->email = $this->email;
        $container->currency_id = $this->container_currency_id;
        $container->phonenumber = $this->phonenumber;
        $container->purchase_type = $this->purchase_type;
        $container->address = $this->address;
        $container->fuel_type = $this->fuel_type;
        $container->capacity = $this->capacity;
        $container->balance = $this->quantity;
        $container->save();

        $this->container_id = $container->id;

        $top_up = new TopUp;
        $top_up->user_id = Auth::user()->id;
        $top_up->order_number = $this->orderNumber();
        $top_up->container_id = $container->id ? $container->id : NULL;
        $top_up->vendor_id = $this->vendor_id ? $this->vendor_id : NULL;
        $top_up->date = date('Y-m-d');
        $top_up->currency_id = $this->currency_id ? $this->currency_id : NULL;
        $top_up->fuel_type = $container->fuel_type;
        $top_up->quantity = $this->quantity;
        $top_up->rate = $this->rate;
        $top_up->amount = $this->amount;
        $top_up->save();
        
        if (isset($top_up->amount) && $top_up->amount > 0) {
            
        $bill = new Bill;
        $bill->user_id = Auth::user()->id;
        $bill->bill_number = $this->billNumber();
        $bill->container_id = $container->id;
        $bill->top_up_id = $top_up->id;
        $bill->vendor_id = $top_up->vendor_id;
        $bill->category = "Fuel Topup";
        $bill->bill_date = $top_up->date;
        $bill->currency_id =  $top_up->currency_id;
        $bill->total = $top_up->amount;
        $bill->balance = $top_up->amount;
        $bill->save();


        $expense = Expense::where('name','Fuel Topup')->get()->first();

        $bill_expense = new BillExpense;
        $bill_expense->user_id = Auth::user()->id;
        $bill_expense->bill_id = $bill->id;
        $bill_expense->currency_id = $bill->currency_id;
        if (isset($expense)) {
            $bill_expense->expense_id = $expense->id;
        }
        $bill_expense->qty = 1;
        $bill_expense->amount = $top_up->amount;
        $bill_expense->subtotal = $top_up->amount;
        $bill_expense->save();
        
        }


        $this->dispatchBrowserEvent('hide-containerModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Fueling Station Created Successfully!!"
        ]);

              # code...
            }else {
                $this->dispatchBrowserEvent('hide-containerModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Fueling Station Name Exists!!"
                ]);
            }

        }
        catch(\Exception $e){
        // Set Flash Message
        $this->dispatchBrowserEvent('hide-containerModal');
        $this->dispatchBrowserEvent('alert',[

            'type'=>'error',
            'message'=>"Something went wrong while creating station!!"
        ]);
    }

    }

    public function edit($id){
    $container = Container::find($id);
    $this->name = $container->name;
    $this->email = $container->email;
    $this->phonenumber = $container->phonenumber;
    $this->address = $container->address;
    $this->user_id = $container->user_id;
    $this->purchase_type = $container->purchase_type;
    $this->fuel_type = $container->fuel_type;
    $this->container_currency_id = $container->currency_id;
    $this->capacity = $container->capacity;
    $this->balance = $container->balance;
    $this->container_id = $container->id;
    $this->dispatchBrowserEvent('show-containerEditModal');

    }


    public function update()
    {
        if ($this->container_id) {
            try{
            $container = container::find($this->container_id);
            $container->user_id = Auth::user()->id;
            $container->fuel_type = $this->fuel_type;
            $container->name = $this->name;
            $container->email = $this->email;
            $container->purchase_type = $this->purchase_type;
            $container->currency_id = $this->container_currency_id;
            $container->phonenumber = $this->phonenumber;
            $container->address = $this->address;
            $container->capacity = $this->capacity;
            $container->balance = $this->balance;
            $container->update();

            $this->dispatchBrowserEvent('hide-containerEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Fueling station Updated Successfully!!"
            ]);
            }
            catch(\Exception $e){
            // Set Flash Message
            $this->dispatchBrowserEvent('hide-containerEditModal');
            $this->dispatchBrowserEvent('alert',[

                'type'=>'error',
                'message'=>"Something went wrong while creating fueling Station!!"
            ]);
        }
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {
 
        if ($this->quantity != null && $this->rate != null) {
            $this->amount = $this->quantity * $this->rate;
        }

        if ((isset($this->quantity) && $this->quantity != null)  && (isset($this->balance) && $this->balance != null)) {
            $this->total_fuel = $this->quantity + $this->balance;
        }

        $value = 'Fuel';
        $this->vendors = Vendor::with(['vendor_type'])
        ->whereHas('vendor_type', function($q) use($value) {
        $q->where('name', '=', $value);
        })->get();
   
        if (isset($this->search)) {
            return view('livewire.containers.index',[
                'containers' => Container::query()->with('vendor','currency')
                ->where('container_number','like', '%'.$this->search.'%')
                ->orwhere('name','like', '%'.$this->search.'%')
                ->orWhere('fuel_type','like', '%'.$this->search.'%')
                ->orWhere('purchase_type','like', '%'.$this->search.'%')
                ->orWhereHas('currency', function ($query) {
                    return $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orWhereHas('vendor', function ($query) {
                    return $query->where('name', 'like', '%'.$this->search.'%');
                })
                ->orderBy('container_number','desc')->paginate(10),
                'amount' => $this->amount,
                'total_fuel' => $this->total_fuel,
                'quantity' => $this->quantity,
                'vendors' => $this->vendors,
            ]);
        }
        else {
           
            return view('livewire.containers.index',[
                'containers' => Container::query()->with('vendor','currency')->orderBy('container_number','desc')->paginate(10),
                'amount' => $this->amount,
                'total_fuel' => $this->total_fuel,
                'vendors' => $this->vendors,
            ]);
          
        }

   
    }
}
