<?php

namespace App\Http\Livewire\Fuels;

use Carbon\Carbon;
use App\Models\Fuel;
use App\Models\Trip;
use App\Models\Asset;
use App\Models\Horse;
use App\Models\TopUp;
use App\Models\Driver;
use App\Models\Vendor;
use App\Models\Expense;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Category;
use App\Models\Currency;
use App\Models\Employee;
use App\Models\Container;
use App\Models\FuelCount;
use App\Models\TripExpense;
use App\Exports\FuelsExport;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use App\Models\CategoryValue;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendingNotificationEmails;
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    use WithFileUploads;

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $fuels;
    public $fuel_id;
    public $assets;
    public $categories;
    public $category_values;
    public $asset_id;
    public $trips;
    public $vehicle;
    public $currencies;
    public $currency_id;
    public $selectedVehicle;
    public $vehicles;
    public $employees;
    public $employee;
    public $employee_id;
    public $horse;
    public $horses;
    public $selectedHorse;
    public $drivers;
    public $driver;
    public $fuel_tank_capacity;
    public $driver_id;
    public $containers;
    public $selected_horse;
    public $horse_id;
    public $container;
    public $container_id;
    public $type = NULL;


    public $transporter_price = 0;
    public $transporter_total;
    public $fuel_profit;

    public $unit_price = 0;
    public $amount = 0;
    public $quantity = 0 ;
    public $mileage;
    public $date;
    public $fillup;
    public $invoice_number;
    public $fuel_consumption;
    public $distance;
    public $file;
    public $comments;
    public $fuel_trip;


    public $user_id;
    public $selectedContainer;
    public $container_balance;
    public $selectedCategory;
    public $selectedCategoryValue;
    public $selectedTrip;
    public $selected_trip;
    public $selected_container;

    public $search;
    protected $queryString = ['search'];
    public $fuel_filter;
    public $from;
    public $to;

    public $exchange_rate;
    public $exchange_amount;
    public $category;
    public $total_transporter_expenses = 0;
    public $total_customer_expenses = 0;
    public $total_expenses = 0;

    public function exportFuelsCSV(Excel $excel){

        return $excel->download(new FuelsExport($this->from, $this->to, $this->fuel_filter,$this->container_id), 'fuel_orders_' .time().'.csv', Excel::CSV);
    }
    public function exportFuelsPDF(Excel $excel){

        return $excel->download(new FuelsExport($this->from, $this->to, $this->fuel_filter,$this->container_id), 'fuel_orders_' .time().'.pdf', Excel::DOMPDF);
    }
    public function exportFuelsExcel(Excel $excel){
        return $excel->download(new FuelsExport($this->from, $this->to, $this->fuel_filter,$this->container_id), 'fuel_orders_' .time().'.xlsx');
    }

    public function mount(){

        $this->resetPage();

        $this->fuel_filter = "created_at";

        $this->horses = Horse::where('service',0)->orderBy('registration_number','asc')->get();
        $this->vehicles = Vehicle::where('service',0)->orderBy('registration_number','asc')->get();
        $this->assets = Asset::latest()->get();
        $this->fillup = 1;
        $this->categories = Category::latest()->get();
        $this->category_values = collect();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->employees = Employee::orderBy('name','asc')->get();
        $this->trips = collect();
        $this->containers = Container::where('balance','>',0)->orderBy('name','asc')->latest()->get();
        $this->drivers = Driver::latest()->get();
    

    }

    

    public function updatedSelectedHorse($id)
    {
        if (!is_null($id) ) {
        $this->horse = Horse::find($id);
        $this->selected_horse = Horse::find($id);
        $this->trips = Trip::with('horse','destination')->where('authorization','approved')->where('horse_id',$id)->orderBy('created_at','desc')->take(100)->get();
        $this->horse_id = $id;
        $this->mileage = $this->horse->mileage;
        $this->fuel_tank_capacity = $this->horse->fuel_tank_capacity;
        }
    }

    public function updatedSelectedTrip($id)
    {
        if (!is_null($id) ) {
        $trip = Trip::find($id);
        $this->fuel_trip = Fuel::where('trip_id',$id)
        ->where('fillup', 1)->first();

        if (isset($this->fuel_trip)) {
           $this->fillup = 0;
        }
        $this->selected_trip = $trip;
        $this->horse = $trip->horse;
        $this->driver = $trip->driver;
        $this->selectedHorse = $this->horse->id;
        $this->selectedDriver = $this->driver->id;
        $this->distance = $trip->distance;
        $this->fuel_tank_capacity = $this->horse->fuel_tank_capacity;
        $this->fuel_consumption = $this->horse->fuel_consumption;
        // $this->quantity = $this->fuel_consumption * $this->distance;
        }
    }

    public function updatedSelectedContainer($id)
    {
        if (!is_null($id) ) {
            $this->container = Container::find($id);
            $this->selected_container = Container::find($id);
            $top_ups = TopUp::where('container_id',$id)->where('rate','!=', NULL)->where('rate','!=',"")->where('currency_id',$this->container->currency_id)->get();
            
            $topups_price_total = TopUp::where('container_id',$this->container->id)->where('rate','!=', NULL)->where('rate','!=',"")->where('rate', 'REGEXP', '^[0-9]+$')->where('currency_id',$this->container->currency_id)->get()->sum('rate');
            
            $topups_count = $top_ups->count();
            
            if ((isset($topups_count) && $topups_count > 0) && (isset($topups_price_total) && $topups_price_total > 0)) {
                $this->unit_price = number_format($topups_price_total/$topups_count,2);
            }
        
            $this->container_balance = $this->container ? $this->container->balance : "";
           
            $this->currency_id = $this->container->currency_id;
           
            }
    }

    public function updatedSelectedVehicle($id)
    {
        if (!is_null($id) ) {
            $this->vehicle = Vehicle::find($id);
            $this->selected_vehicle = Vehicle::find($id);
            $this->trips = Trip::with('vehicle','destination')->where('authorization','approved')->where('vehicle_id',$id)->orderBy('created_at','desc')->take(100)->get();
            $this->vehicle_id = $id;
            $this->mileage = $this->vehicle->mileage;
            $this->fuel_tank_capacity = $this->vehicle->fuel_tank_capacity;
        }
    }
 
    public function updatedSelectedCategory($id)
    {
        if (!is_null($id) ) {
        $this->category_values = CategoryValue::where('category_id', $id)->get();
        }
    }
    public function updatedSelectedCategoryValue($id)
    {
        if (!is_null($id) ) {
        $this->assets = Asset::where('category_value_id', $id)->get();
        }
    }


    public function topup($id){

        $fuel = Fuel::find($id);
        $this->fillup = 0;
        $this->type = $fuel->type;
        $this->user_id = $fuel->user_id;
        $this->selectedHorse = $fuel->horse_id;
        $this->employee_id = $fuel->employee_id;
        $this->selectedVehicle = $fuel->vehicle_id;
        $this->trips = Trip::orderBy('created_at','desc')->get();
        $this->selectedTrip = $fuel->trip_id;
        $this->asset_id = $fuel->asset_id;
        $this->driver_id = $fuel->driver_id;
        $this->type = $fuel->type;
        $this->fuel_id = $fuel->id;

        if($this->type == "Asset"){
            if ($fuel->asset_id) {
                $this->selectedCategory = Asset::find($fuel->asset_id)->category_id;
                $this->selectedCategoryValue = Asset::find($fuel->asset_id)->category_value_id;
            }
        }

        $this->dispatchBrowserEvent('show-fuelTopupModal');
    }

    public function storeTopup(){
        
        try{

            $fuel = new Fuel;
            $fuel->user_id = Auth::user()->id;
            $fuel->order_number = $this->orderNumber();
            $fuel->employee_id = $this->employee_id;
            $fuel->vehicle_id = $this->selectedVehicle ? $this->selectedVehicle : NULL;
            $fuel->horse_id = $this->selectedHorse ? $this->selectedHorse : NULL;
            $fuel->asset_id = $this->asset_id ? $this->asset_id : NULL;
    
            if (isset($this->selectedTrip)) {
                $trip = Trip::find($this->selectedTrip);
                $fuel->trip_id = $trip->id;
                $fuel->driver_id = $trip->driver_id ? $trip->driver_id : Null;
            }
          
            $fuel->container_id = $this->selectedContainer;
            $fuel->date = $this->date;
            $fuel->unit_price = $this->unit_price;
            $fuel->transporter_price = $this->transporter_price;
            $fuel->transporter_total = $this->transporter_total;
            $fuel->profit = $this->fuel_profit;
            $fuel->quantity = $this->quantity;
            $fuel->currency_id = $this->currency_id;
            $fuel->amount = $this->amount;
            $fuel->odometer = $this->mileage;
            $fuel->fillup = $this->fillup;
            $fuel->type = $this->type;
            $fuel->comments = $this->comments;
            $fuel->save();

            if (isset($this->selectedTrip)) {
            $fuel_expense = Expense::where('name','Fuel Topup')->get()->first();
        
            $trip_expense = new TripExpense;
            $trip_expense->user_id = Auth::user()->id;
            $trip_expense->trip_id = $this->selectedTrip;
            $trip_expense->fuel_id = $fuel->id;
            if (isset($fuel_expense)) {
                $trip_expense->expense_id = $fuel_expense->id;
            }
            $trip_expense->currency_id = $this->currency_id;
            $trip_expense->category = $this->category;
            $trip_expense->amount = $this->amount;
            $trip_expense->exchange_rate = $this->exchange_rate;
            $trip_expense->exchange_amount = $this->exchange_amount;
           
            if (isset($this->category)  && isset($this->amount) && isset($this->currency_id)) {

                if ($this->currency_id == Auth::user()->employee->company->currency_id) {
                    if ($this->category == "Transporter") {
                        $this->total_transporter_expenses = $this->total_transporter_expenses + $this->amount;
                    }
                    elseif ($this->category == "Customer") {
                        $this->total_customer_expenses = $this->total_customer_expenses + $this->amount;
                    }
                    elseif ($this->category == "Self") {
                        $this->total_expenses = $this->total_expenses + $this->amount;
                    }
                }else{
                    if ($this->category == "Transporter") {
                        $this->total_transporter_expenses = $this->total_transporter_expenses + $this->exchange_amount;
                     }
                     elseif ($this->category == "Customer") {
                         $this->total_customer_expenses = $this->total_customer_expenses + $this->exchange_amount;
                     }
                     elseif ($this->category == "Self") {
                         $this->total_expenses = $this->total_expenses + $this->exchange_amount;
                     }
                }
            }
            $trip_expense->save();
        }
          
            // $employees = Employee::whereHas('departments', function ($query) {
            //     return $query->where('name', '=', "Transport & Logistics");
            // })->whereHas('ranks', function ($query) {
            //     return $query->where('name', '=', 'Management');
            // })->orWhereHas('ranks', function ($query) {
            //     return $query->where('name', '=', 'Director');
            // })->get();
           
            
            // if ($employees->count()>0) {
             
            //     $category = "Fuel Order";
            //     foreach ($employees as $employee) {
            //         if ($employee->email) {
            //             $company = Auth::user()->employee->company;
            //             Mail::to($employee->email)->send(new PendingNotificationEmails($company, $category, $fuel->order_number));
            //         }
                   
            //     }
            // }

         

            $this->dispatchBrowserEvent('hide-fuelTopupModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Fuel Topup Created Successfully!!"
            ]);

            return redirect(request()->header('Referer'));
    }
        catch(\Exception $e){
        $this->dispatchBrowserEvent('hide-fuelTopupModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something went wrong while creating a fuel topup!!"
        ]);
    }
    }

    public function updated($value){
        $this->validateOnly($value);
    }

    protected $rules = [
        'selectedVehicle' => 'required',
        'selectedHorse' => 'required',
        // 'driver_id' => 'required',
        'selectedContainer' => 'required',
        // 'selectedTrip' => 'required',
        'selectedCategory' => 'required',
        'asset_id' => 'required',
        'date' => 'required',
        'mileage' => 'required',
        'quantity' => 'required',
        'amount' => 'required',
        'fillup' => 'required',
        'type' => 'required',
        'invoice_number' => 'nullable',
        'file' => 'nullable|file|mimes:docx,doc,pdf,xls,xlsx,pptx|max:10000',
        'comments' => 'nullable',
    ];

    private function resetInputFields(){

        $this->selectedVehicle = "";
        $this->selectedHorse = "";
        $this->selectedTrip = "";
        $this->selectedContainer = "";
        $this->selectedCategory = "";
        $this->selectedCategoryValue = "";
        $this->asset_id = "";
        $this->date = "";
        $this->quantity = "";
        $this->unit_price = "";
        $this->transporter_price = "";
        $this->transporter_total = "";
        $this->fuel_profit = "";
        $this->mileage = "";
        $this->fillup = "";
        $this->type = "";
        $this->invoice_number = "";
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
        $fuel = Fuel::orderBy('id', 'desc')->first();
        if(!$fuel){
        $order_number =  $initials .'FO'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else{
        $number = $fuel->id + 1 ;
        $order_number = $initials .'FO'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
        return $order_number;
    }


    public function store(){
    // try{

        $fuel = new Fuel;
        $fuel->user_id = Auth::user()->id;
        $fuel->order_number = $this->orderNumber();
        $fuel->employee_id = $this->employee_id;
        $fuel->vehicle_id = $this->selectedVehicle ? $this->selectedVehicle : NULL;
        $fuel->horse_id = $this->selectedHorse ? $this->selectedHorse : NULL;
        $fuel->asset_id = $this->asset_id ? $this->asset_id : NULL;

        if (isset($this->selectedTrip)) {
            $trip = Trip::find($this->selectedTrip);
            $fuel->trip_id = $trip->id;
            $fuel->driver_id = $trip->driver_id ? $trip->driver_id : Null;
        }
      
        $fuel->container_id = $this->selectedContainer;
        $fuel->date = $this->date;
        $fuel->unit_price = $this->unit_price;
        $fuel->transporter_price = $this->transporter_price;
        $fuel->transporter_total = $this->transporter_total;
        $fuel->profit = $this->fuel_profit;
        $fuel->quantity = $this->quantity;
        $fuel->currency_id = $this->currency_id;
        $fuel->amount = $this->amount;
        $fuel->odometer = $this->mileage;
        $fuel->fillup = $this->fillup;
        $fuel->type = $this->type;
        $fuel->comments = $this->comments;
        $fuel->save();

        $this->dispatchBrowserEvent('hide-fuelModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Fuel Order Created Successfully!!"
        ]);
        
        return redirect(request()->header('Referer'));

     

        
    //  }   
  
    //     catch(\Exception $e){
    //     // Set Flash Message
    //     $this->dispatchBrowserEvent('hide-fuelModal');
    //     $this->dispatchBrowserEvent('alert',[

    //         'type'=>'error',
    //         'message'=>"Something went wrong while creating fuel order!!"
    //     ]);
    // }


    }

    public function edit($id){
    $fuel = Fuel::find($id);
    $this->user_id = $fuel->user_id;
    $this->selectedHorse = $fuel->horse_id;
    $this->employee_id = $fuel->employee_id;
    $this->selectedVehicle = $fuel->vehicle_id;
    $this->selectedTrip = $fuel->trip_id;
    $this->trips = Trip::orderBy('created_at','desc')->get();
    $this->currency_id = $fuel->currency_id;
    $this->asset_id = $fuel->asset_id;
    $this->selectedContainer = $fuel->container_id;
    $this->container = Container::find($fuel->container_id);
    $this->selected_container = Container::find($fuel->container_id);
    $this->container_balance = $this->container ? $this->container->balance : "";
    $this->driver_id = $fuel->driver_id;
    $this->date = $fuel->date;
    $this->fillup = $fuel->fillup;
    $this->type = $fuel->type;
    $this->mileage = $fuel->odometer;
    $this->comments = $fuel->comments;
    $this->amount = $fuel->amount;
    $this->unit_price = $fuel->unit_price;
    $this->transporter_price = $fuel->transporter_price;
    $this->transporter_total = $fuel->transporter_total;
    $this->fuel_profit = $fuel->profit;
    $this->quantity = $fuel->quantity;
    $this->fuel_id = $fuel->id;
    if ($fuel->asset_id) {
        $this->selectedCategory = Asset::find($fuel->asset_id)->category_id;
        $this->selectedCategoryValue = Asset::find($fuel->asset_id)->category_value_id;
    }
    $this->dispatchBrowserEvent('show-fuelEditModal');

    }


    public function update()
    {
        try{

        if ($this->fuel_id) {
            $trip = Trip::find($this->selectedTrip);
            $fuel = Fuel::find($this->fuel_id);
            $fuel->user_id = Auth::user()->id;
            $fuel->employee_id = $this->employee_id;
            $fuel->vehicle_id = $this->selectedVehicle ? $this->selectedVehicle : NULL;
            $fuel->horse_id = $this->selectedHorse ? $this->selectedHorse : NULL;
            $fuel->asset_id = $this->asset_id ? $this->asset_id : NULL;

            if (isset($trip)) {
                $fuel->driver_id = $trip->driver_id;
                $fuel->trip_id = $trip->id;
            }
           
            $fuel->container_id = $this->selectedContainer;
            $fuel->date = $this->date;
            $fuel->unit_price = $this->unit_price;
            $fuel->transporter_price = $this->transporter_price;
            $fuel->transporter_total = $this->transporter_total;
            $fuel->profit = $this->fuel_profit;
            $fuel->quantity = $this->quantity;
            $fuel->currency_id = $this->currency_id;
            $fuel->amount = $this->amount;
            $fuel->odometer = $this->mileage;
            $fuel->fillup = $this->fillup;
            $fuel->type = $this->type;
            $fuel->comments = $this->comments;

            $fuel->update();

            if ($fuel->horse) {
                $horse = Horse::find($fuel->horse_id);
                $horse->fuel_balance = $horse->fuel_balance + $fuel->quantity;
                $current_mileage = $vehicle->mileage;
                if ($fuel->odometer >  $current_mileage) {
                    $horse->mileage = $fuel->odometer;
                }
              
                $horse->update();
            }
            if ($fuel->vehicle) {
                $vehicle = Vehicle::find($fuel->vehicle_id);
                $vehicle->fuel_balance = $vehicle->fuel_balance + $fuel->quantity;
                $current_mileage = $vehicle->mileage;
                if ($fuel->odometer >  $current_mileage) {
                    $vehicle->mileage = $fuel->odometer;
                }
              
                $vehicle->update();

            }
            $this->dispatchBrowserEvent('hide-fuelEditModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Fuel Order Updated Successfully!!"
            ]);
            return redirect(request()->header('Referer'));

        }
    }
        catch(\Exception $e){
        $this->dispatchBrowserEvent('hide-fuelEditModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'error',
            'message'=>"Something went wrong while updating fuel order!!"
        ]);
    }
    }



    public function dateRange(){
 
      
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }


    public function render()
    {

        // $this->container = Container::find($this->selectedContainer);
        $this->containers = Container::where('balance','>',0)->orderBy('name','asc')->latest()->get();
      
        if(($this->unit_price != null) && ($this->quantity != null)){
            $this->amount = $this->unit_price * $this->quantity;
        }
       

        if(($this->transporter_price != null) && ($this->quantity != null)){
            $this->transporter_total = $this->transporter_price * $this->quantity;
        }
        if(isset($this->transporter_total) && isset($this->amount) ){
            $this->fuel_profit = $this->transporter_total - $this->amount;
        }

        $this->selected_horse = Horse::find($this->horse_id);
        if ( $this->selected_horse) {
            $this->fuel_tank_capacity = $this->selected_horse->fuel_tank_capacity;
            // $this->mileage = $this->selected_horse->mileage;
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
                    return view('livewire.fuels.index',[
                        'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make'
                        ])->whereBetween($this->fuel_filter,[$this->from, $this->to] )
                        ->where('order_number','like', '%'.$this->search.'%')
                        ->orWhere('quantity','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('user', function ($query) {
                            return $query->where(DB::raw("concat(name, ' ', surname)"), 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('vehicle', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('asset.product.brand', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('container', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_ref', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('order_number','desc')->paginate(10),
                        'unit_price' => $this->unit_price,
                        'amount' => $this->amount,
                        'fuel_profit' => $this->fuel_profit,
                        'transporter_total' => $this->transporter_total,
                        'type' => $this->type,
                        'containers' => $this->containers,
                        'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                        'mileage'=>$this->mileage,
                        'selected_horse'=>$this->selected_horse,
                        'fuel_filter'=>$this->fuel_filter,
                    ]);
                }elseif(isset($this->container_id)){
                    return view('livewire.fuels.index',[
                        'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                        ])->where('container_id',$this->container_id)->whereBetween($this->fuel_filter,[$this->from, $this->to] )->orderBy('order_number','desc')->paginate(10),
                        'unit_price' => $this->unit_price,
                        'amount' => $this->amount,
                        'fuel_profit' => $this->fuel_profit,
                        'transporter_total' => $this->transporter_total,
                        'type' => $this->type,
                        'containers' => $this->containers,
                        'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                        'mileage'=>$this->mileage,
                        'selected_horse'=>$this->selected_horse,
                        'fuel_filter'=>$this->fuel_filter,

                    ]);
                }else {
                    return view('livewire.fuels.index',[
                        'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                        ])->whereBetween($this->fuel_filter,[$this->from, $this->to] )->orderBy('order_number','desc')->paginate(10),
                        'unit_price' => $this->unit_price,
                        'amount' => $this->amount,
                        'type' => $this->type,
                        'fuel_profit' => $this->fuel_profit,
                        'transporter_total' => $this->transporter_total,
                        'containers' => $this->containers,
                        'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                        'mileage'=>$this->mileage,
                        'selected_horse'=>$this->selected_horse,
                        'fuel_filter'=>$this->fuel_filter,

                    ]);
                }
               
            }
            elseif (isset($this->search)) {
               
                return view('livewire.fuels.index',[
                    'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                    ])->whereMonth('created_at', date('m'))
                    ->whereYear('created_at', date('Y'))
                    ->where('order_number','like', '%'.$this->search.'%')
                    ->orWhere('quantity','like', '%'.$this->search.'%')
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('user', function ($query) {
                        return $query->where(DB::raw("concat(name, ' ', surname)"), 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('vehicle', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('asset.product.brand', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('container', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_ref', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('order_number','desc')->paginate(10),
                    'unit_price' => $this->unit_price,
                    'amount' => $this->amount,
                    'fuel_profit' => $this->fuel_profit,
                    'transporter_total' => $this->transporter_total,
                    'type' => $this->type,
                    'containers' => $this->containers,
                    'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                    'mileage'=>$this->mileage,
                    'selected_horse'=>$this->selected_horse,
                    'fuel_filter'=>$this->fuel_filter,
                ]);
            }elseif(isset($this->container_id)){
                return view('livewire.fuels.index',[
                    'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                    ])->where('container_id',$this->container_id)->whereMonth($this->fuel_filter, date('m'))
                    ->whereYear($this->fuel_filter, date('Y'))->orderBy('order_number','desc')->paginate(10),
                    'unit_price' => $this->unit_price,
                    'amount' => $this->amount,
                    'fuel_profit' => $this->fuel_profit,
                    'transporter_total' => $this->transporter_total,
                    'type' => $this->type,
                    'containers' => $this->containers,
                    'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                    'mileage'=>$this->mileage,
                    'selected_horse'=>$this->selected_horse,
                    'fuel_filter'=>$this->fuel_filter,
                ]);
            }
            else {
               
                return view('livewire.fuels.index',[
                    'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                    ])->whereMonth($this->fuel_filter, date('m'))
                    ->whereYear($this->fuel_filter, date('Y'))->orderBy('order_number','desc')->paginate(10),
                    'unit_price' => $this->unit_price,
                    'amount' => $this->amount,
                    'fuel_profit' => $this->fuel_profit,
                    'transporter_total' => $this->transporter_total,
                    'type' => $this->type,
                    'containers' => $this->containers,
                    'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                    'mileage'=>$this->mileage,
                    'selected_horse'=>$this->selected_horse,
                    'fuel_filter'=>$this->fuel_filter,
                ]);
              
            }
        }else {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.fuels.index',[
                        'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                        ])->whereBetween($this->fuel_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
                        ->where('order_number','like', '%'.$this->search.'%')
                        ->orWhere('quantity','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('user', function ($query) {
                            return $query->where(DB::raw("concat(name, ' ', surname)"), 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('vehicle', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('asset.product.brand', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('container', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_ref', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('order_number','desc')->paginate(10),
                        'unit_price' => $this->unit_price,
                        'amount' => $this->amount,
                        'type' => $this->type,
                        'containers' => $this->containers,
                        'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                        'mileage'=>$this->mileage,
                        'selected_horse'=>$this->selected_horse,
                        'fuel_filter'=>$this->fuel_filter,
                    ]);
                }elseif(isset($this->container_id)){
                    return view('livewire.fuels.index',[
                        'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                        ])->where('container_id',$this->container_id)->where('user_id',Auth::user()->id)->whereBetween($this->fuel_filter,[$this->from, $this->to] )->orderBy('order_number','desc')->paginate(10),
                        'unit_price' => $this->unit_price,
                        'amount' => $this->amount,
                        'fuel_profit' => $this->fuel_profit,
                        'transporter_total' => $this->transporter_total,
                        'type' => $this->type,
                        'containers' => $this->containers,
                        'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                        'mileage'=>$this->mileage,
                        'selected_horse'=>$this->selected_horse,
                        'fuel_filter'=>$this->fuel_filter,

                    ]);
                }
                else{
                    return view('livewire.fuels.index',[
                        'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make'
                        ])->whereBetween($this->fuel_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('order_number','desc')->paginate(10),
                        'unit_price' => $this->unit_price,
                        'amount' => $this->amount,
                        'fuel_profit' => $this->fuel_profit,
                        'transporter_total' => $this->transporter_total,
                        'type' => $this->type,
                        'containers' => $this->containers,
                        'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                        'mileage'=>$this->mileage,
                        'selected_horse'=>$this->selected_horse,
                        'fuel_filter'=>$this->fuel_filter,
                    ]);
                }
              
               
            }
            elseif (isset($this->search)) {
                return view('livewire.fuels.index',[
                    'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                    ])->whereMonth($this->fuel_filter, date('m'))
                    ->whereYear($this->fuel_filter, date('Y'))->where('order_number','like', '%'.$this->search.'%')->where('user_id',Auth::user()->id)
                    ->where('order_number','like', '%'.$this->search.'%')
                    ->orWhere('quantity','like', '%'.$this->search.'%')
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('user', function ($query) {
                        return $query->where(DB::raw("concat(name, ' ', surname)"), 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('vehicle', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('asset.product.brand', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('container', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_ref', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('order_number','desc')->paginate(10),
                    'unit_price' => $this->unit_price,
                    'amount' => $this->amount,
                    'fuel_profit' => $this->fuel_profit,
                    'transporter_total' => $this->transporter_total,
                    'type' => $this->type,
                    'containers' => $this->containers,
                    'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                    'mileage'=>$this->mileage,
                    'selected_horse'=>$this->selected_horse,
                    'fuel_filter'=>$this->fuel_filter,
                ]);
            }elseif(isset($this->container_id)){
                return view('livewire.fuels.index',[
                    'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                    ])->where('container_id',$this->container_id)->where('user_id',Auth::user()->id)->whereMonth($this->fuel_filter, date('m'))
                    ->whereYear($this->fuel_filter, date('Y'))->orderBy('order_number','desc')->paginate(10),
                    'unit_price' => $this->unit_price,
                    'amount' => $this->amount,
                    'fuel_profit' => $this->fuel_profit,
                    'transporter_total' => $this->transporter_total,
                    'type' => $this->type,
                    'containers' => $this->containers,
                    'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                    'mileage'=>$this->mileage,
                    'selected_horse'=>$this->selected_horse,
                    'fuel_filter'=>$this->fuel_filter,
                ]);
            }
            else {
                
                return view('livewire.fuels.index',[
                    'fuels' => Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
                    ])->whereMonth($this->fuel_filter, date('m'))
                    ->whereYear($this->fuel_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('order_number','desc')->paginate(10),
                    'unit_price' => $this->unit_price,
                    'amount' => $this->amount,
                    'fuel_profit' => $this->fuel_profit,
                    'transporter_total' => $this->transporter_total,
                    'type' => $this->type,
                    'containers' => $this->containers,
                    'fuel_tank_capacity'=>$this->fuel_tank_capacity,
                    'mileage'=>$this->mileage,
                    'selected_horse'=>$this->selected_horse,
                    'fuel_filter'=>$this->fuel_filter,
                ]);

            }

        }
       
    }
}
