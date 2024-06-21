<?php

namespace App\Http\Livewire\Trips;

use App\Models\Fuel;
use App\Models\Rate;
use App\Models\Trip;
use App\Models\Agent;
use App\Models\Cargo;
use App\Models\Horse;
use App\Models\Route;
use App\Models\TopUp;
use App\Models\Border;
use App\Models\Broker;
use App\Models\Driver;
use App\Models\Account;
use App\Models\Expense;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Currency;
use App\Models\Customer;
use App\Models\Distance;
use App\Models\Employee;
use App\Models\TripType;
use App\Models\Allowance;
use App\Models\Consignee;
use App\Models\Container;
use App\Models\FuelCount;
use App\Models\Quotation;
use App\Models\TripCount;
use App\Models\TripGroup;
use App\Models\TruckStop;
use App\Models\Assignment;
use App\Models\Commission;
use App\Models\TripReturn;
use App\Models\Destination;
use App\Models\Measurement;
use App\Models\Transporter;
use App\Models\TripExpense;
use App\Models\DeliveryNote;
use App\Models\LoadingPoint;
use App\Models\ClearingAgent;
use Livewire\WithFileUploads;
use App\Models\AllowanceDriver;
use App\Models\ExpenseCategory;
use App\Models\OffloadingPoint;
use App\Models\TrailerAssignment;
use App\Models\VehicleAssignment;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Mail\PendingNotificationEmails;
use Illuminate\Support\Facades\Session;
use KMLaravel\GeographicalCalculator\Facade\GeoFacade;

class Create extends Component
{
    use WithFileUploads;

    public $trip;
    public $trip_id;
    public $trip_number;
    public $routes;
    public $selectedRoute;
    public $transporters;
    public $selectedTransporter;
    public $trip_groups;
    public $trip_group;
    public $starting_mileage;
    public $ending_mileage;
    public $exchange_rate;
    public $exchange_customer_freight;
    public $exchange_transporter_freight;
    public $exchange_customer_turnover;
    public $exchange_transporter_cost_of_sales;
    public $trip_group_id;
    public $horse_fuel_total;
    public $vehicle_fuel_total;
    public $cd3_number;
    public $cd1_number;
    public $manifest_number;
    public $trip_types;
    public $trip_type_name;
    public $horse_selected;
    public $vehicle_selected;
    public $freight_calculation;
    public $fuel_currency_id;
    public $selectedTripType;
    public $customer_updates = 0;
    public $clearing_agents;
    public $clearing_agent_id;
    public $consignees;
    public $consignee_id;
    public $agents;
    public $agent_id;
    public $brokers;
    public $quantity;
    public $selectedBroker;
    public $customers;
    public $customer_id;
    public $containers;
    public $measurements;
    public $measurement;
    public $container;
    public $container_id;
    public $trip_ref;
    public $with_trailer;
    public $cargo_details;
    public $horses;
    public $horse;
    public $vehicles;
    public $vehicle;
    public $trip_fuel;
    public $fuel_balance;
    public $fuel_tank_capacity;
    public $selectedHorse;
    public $selectedVehicle;
    public $trailers;
    public $trailer_id;
    public $drivers;
    public $driver_id;
  
    public $stops;
    public $currencies;
    public $currency_id;
    public $amount = [];
    public $selectedCargo;
    public $cost_of_sales;
    public $turnover;

    public $lat1;
    public $long1;
    public $lat2;
    public $long2;

    public $borders;
    public $selectedBorder;
    public $quotations;
    public $selectedQuotation;
    public $cargos;
    public $cargo;
    public $cargo_type;
    public $destinations;
    public $destination_id;
    public $selectedFrom;
    public $selectedTo;
    public $offloading_points;
    public $offloading_point_id;
    public $loading_points;
    public $loading_point_id;
    public $start_date;
    public $end_date;
    public $weight;
    public $litreage;
    public $litreage_at_20;
    public $rate;
    public $transporter_rate;
    public $mode_of_transport;
    public $with_customer_rates;
    public $with_transporter_rates;
    public $defined_customer_rates;
    public $selectedDefinedCustomerRate;
    public $defined_transporter_rates;
    public $selectedDefinedTransporterRate;
    public $comments;
    public $freight;
    public $transporter_freight;
    public $profit;
  
    public $net_profit;
    public $truck_stops;
    public $truck_stop_id;

    public $expense_currency_id = [];
    public $expense_exchange_rate;
    public $expense_exchange_amount;
    public $expenses;
    public $expense_id;
    public $expense_name;
    public $category;
    public $total_transporter_expenses = 0;
    public $total_customer_expenses = 0;
    public $total_expenses = 0;

    public $distance;
    public $payment_status;
    public $trip_status;
    public $return_trip;

// Agent & Commission Variables
    public $commission;
    public $commission_amount;
//fuel order variables

//driver allowances 
    public $searchHorse;
    public $searchVehicle;
    public $searchTrailer;
    public $searchDriver;
    
    protected $queryString = ['searchVehicle','searchHorse','searchTrailer','searchDriver'];

    public $allowances;
    public $allowance_id;
    public $allowance;
    public $allowance_title;
    public $allowance_currency_id;
    public $allowance_amount;

    public $allowable_loss_weight;
    public $allowable_loss_litreage;
    public $allowable_loss_quantity;

   
    public $fuel_exchange_rate;
    public $fuel_exchange_amount;
    public $fuel_category;
    public $unit_price = 0;
    public $fuel_amount;
    public $transporter_price = 0;
    public $transporter_total;
    public $fuel_profit;
    public $fuel_quantity = 0 ;
    public $odometer;
    public $container_balance;
    public $date;
    public $fillup = 1;
    public $invoice_number;
    public $fuel_consumption;
    public $fuel_distance;
    public $file;
    public $fuel_comments;
    public $selected_container;
    public $type = 'Trip';

    public $user_id;
    public $selectedContainer;
    public $selectedCategory;
    public $with_cargos = True;
    public $fuel_order = False;
    public $trip_expenses = False;
    public $transporter_agreement = False;

// return trip details

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

public $border_inputs = [];
public $b = 1;
public $c = 1;

public function borderAdd($b)
{
    $b = $b + 1;
    $this->b = $b;
    array_push($this->border_inputs ,$b);
}

public function borderRemove($b)
{
    unset($this->border_inputs[$b]);
}

public $trailer_inputs = [];
public $t = 1;
public $s = 1;

public function trailerAdd($t)
{
    $t = $t + 1;
    $this->t = $t;
    array_push($this->trailer_inputs ,$t);
}

public function trailerRemove($t)
{
    unset($this->trailer_inputs[$t]);
}

public $allowance_inputs = [];
public $x = 1;
public $z = 1;

public function addAllowance($x)
{
    $x = $x + 1;
    $this->x = $x;
    array_push($this->allowance_inputs ,$x);
}

public function removeAllowance($x)
{
    unset($this->allowance_inputs[$x]);
}



        // public function getOptionsProperty()
        // {
        //     return $this->horses =  Horse::where('registration_number', 'like', '%' . $this->searchTerm . '%')->get();
        // }

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
            
            $this->container_balance = $this->container->balance;
            $this->fuel_currency_id = $this->container->currency_id;
            }
    }



    public function updatedSelectedTo($to)
    {
        if (!is_null($this->selectedFrom) ) {
            if (!is_null($to)) {
                $this->routes = Route::with('truck_stops:id,name')->where('status',1)->where('from',$this->selectedFrom)
                                    ->where('to',$to)->orderBy('name','asc')->get();
            $from = Destination::find($this->selectedFrom);
            $to = Destination::find($this->selectedTo);
            // if (isset($from->lat) && isset($from->long) && isset($to->lat) && isset($to->long)) {
            //     $this->lat1 = $from->lat;
            //     $this->long1 = $from->long;
            //     $this->lat2 = $to->lat;
            //     $this->long2 = $to->long;
            //    $this->distance = $this->calculateDistance($from->lat, $from->long, $to->lat, $to->long);    
            // }
            
            }
          
        }
    }
    public function updatedSelectedRoute($id)
    {
        if (!is_null($id)) {
            $this->truck_stops = TruckStop::where('route_id',$id)->latest()->get();
        }
    }
    public function updatedSelectedCargo($id)
    {
            if (!is_null($id)) {
                $this->cargo = Cargo::find($id);
                $this->cargo_type = $this->cargo->type;
            }
    }
    public function updatedSelectedTripType($id)
    {
            if (!is_null($id)) {
                $this->trip_type_name = TripType::find($id)->name;
            }
    }
    // public function updatedSelectedBorder($id)
    // {
    //         if (!is_null($id)) {
    //             $border = Border::find($id);
    //             if (!is_null($border)) {
    //                 $this->clearing_agents = $border->clearing_agents;
    //             }
               
    //         }
    // }

    public function updatedSelectedHorse($id)
    {
        if (!is_null($id) ) {
            $this->horse = Horse::find($id);
            $this->horse_selected = Horse::find($id);
           
            $assignment = Assignment::where('horse_id',$id)
                                    ->where('status', 1)->first();

            $trailer_assignments = $this->horse->trailer_assignments->where('status',1);
                                    
            $this->odometer = $this->horse->mileage;
            $this->fuel_tank_capacity = $this->horse->fuel_tank_capacity;
            $this->starting_mileage = $this->horse->mileage;
            $this->fuel_balance = $this->horse->fuel_balance;
            if (isset( $assignment)) {
                $driver = $assignment->driver;
                $this->driver_id = $driver->id;
            }                        
            if (isset( $trailer_assignments)) {
                foreach ($trailer_assignments as $trailer_assignment) {
                    $this->trailer_id[] = $trailer_assignment->trailer_id;
                }
                
            }                        
           
        }
    }
    public function updatedSelectedVehicle($id)
    {
        if (!is_null($id) ) {
            $this->vehicle = Vehicle::find($id);
            $this->vehicle_selected = Vehicle::find($id);
            $this->selectedVehicle= $id;
           
            $assignment = VehicleAssignment::where('vehicle_id',$id)
                                    ->where('status', 1)->first();
                                    
            $this->odometer = $this->vehicle->mileage;
            $this->fuel_tank_capacity = $this->vehicle->fuel_tank_capacity;
            $this->starting_mileage = $this->vehicle->mileage;
            $this->fuel_balance = $this->vehicle->fuel_balance;
            if (isset( $assignment)) {
                $driver = $assignment->employee;
                $this->driver_id = $driver->id;
            }                        
           
        }
    }

    public function updatedSelectedQuotation($id){
            if (!is_null($id)) {
              $quotation = Quotation::find($id);
              if (isset($quotation)) {
                $this->customer_id = $quotation->customer ? $quotation->customer->id : "";
              }
              
            }
    }
    


  

    public function updatedSelectedTransporter($id)
    {
        if (!is_null($id) ) {
            $this->selectedTransporter = $id;
            $transporter = Transporter::find($id);
            $this->cargos = $transporter->cargos;
            $this->horses = Horse::query()->with('horse_make:id,name','horse_model:id,name')->where('transporter_id',$id)
            ->where('status', 1)
            ->where('service',0)
            ->orderBy('registration_number','asc')->get();
            $this->vehicles = Vehicle::query()->with('vehicle_make:id,name','vehicle_model:id,name')->where('transporter_id',$id)
            ->where('status', 1)
            ->where('service',0)
            ->orderBy('registration_number','asc')->get();
            $this->trailers = Trailer::where('transporter_id',$id)
            ->where('status', 1)
            ->where('service',0)
            ->orderBy('registration_number','asc')->get();
            $this->drivers = Driver::query()->with('employee:id,name,surname')->where('transporter_id',$id)
            ->withAggregate('employee','name')
            ->where('status', 1)
            ->orderBy('employee_name','asc')->get();
          
        }
    }

    public function updatedSelectedBroker($id)
    {
        if (!isset($this->selectedTransporter)) {
            if (!is_null($id) ) {
                $broker = Broker::find($id);
                $this->cargos = $broker->cargos;
                $this->horses = $broker->horses->where('status', 1)
                ->where('service',0);
                $this->trailers = $broker->trailers->where('status', 1)
                ->where('service',0);
                $this->drivers = $broker->drivers->where('status', 1);
            }
        }

    }

    public function calculateExpenseExchangeAmount($key){
        if ((isset($this->expense_exchange_rate[$key]) && $this->expense_exchange_rate[$key] > 0) && (isset($this->amount[$key]) && $this->amount[$key] > 0) ) {
            $this->expense_exchange_amount[$key] = $this->expense_exchange_rate[$key] * $this->amount[$key];
        }
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

        $fuel = Fuel::orderBy('id','desc')->first();

        if (!$fuel) {
            $fuel_number =  $initials .'FO'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $fuel->id + 1;
            $fuel_number =  $initials .'FO'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $fuel_number;
    }
    public function tripNumber(){

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
 
        $trip = Trip::orderBy('id','desc')->first();

        if (!$trip) {
            $trip_number =  $initials .'T'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else {
            $number = $trip->id + 1;
            $trip_number =  $initials .'T'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }

        return  $trip_number;

    }

    private function resetInputFields(){
        $this->allowance_title = '';
    }


   private function calculateDistance($lat1, $lon1, $lat2, $lon2, $unit = 'km'){
        $earthRadius = ($unit == 'km') ? 6371 : 3959; // Radius of the earth in either km or miles

        $latDiff = deg2rad($lat2 - $lat1);
        $lonDiff = deg2rad($lon2 - $lon1);

        $a = sin($latDiff / 2) * sin($latDiff / 2) +
            cos(deg2rad($lat1)) * cos(deg2rad($lat2)) *
            sin($lonDiff / 2) * sin($lonDiff / 2);
        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
        $distance = $earthRadius * $c;

        return $distance;
    }

    // Usage example
    // $lat1 = 52.5200; // Latitude of the first location
    // $lon1 = 13.4050; // Longitude of the first location
    // $lat2 = 48.8566; // Latitude of the second location
    // $lon2 = 2.3522;  // Longitude of the second location

    // $distance = calculateDistance($lat1, $lon1, $lat2, $lon2);
    // echo "Distance: " . round($distance, 2) . " km";

    public function getDistance(){
            // Define your points
    $from = Destination::find($this->selectedFrom);
    $to = Destination::find($this->selectedTo);
    $loading_point = LoadingPoint::find($this->loading_point_id);
    $offloading_point = OffloadingPoint::find($this->offloading_point_id);

    if ((isset($loading_point->lat) && isset($loading_point->long)) && (isset($offloading_point->lat) && isset($offloading_point->long))) {
        $distance =  GeoFacade::setPoint([$loading_point->lat, $loading_point->long])
        // add your options, the default value for the unit is mile.
        ->setOptions(['units' => ['km']])

        // you can set unlimited points.
        // ->setPoint([lat, long])
        // ->setPoint([lat, long])
        ->setPoint([$offloading_point->lat, $offloading_point->long])

        // get the calculated distance.
        // lets suppose you added 6 points
        // this package will return the distance between a first and a second point.
        // a second and a third point.
        // a third and a fourth point and so on.
        // each result will returned  with the index of each point.
        // and you can specify the prefix before the key of each returned,
        // by change the distance_key_prefix key from a config class.
        // for example, the first and second point you add will shown like this:
        // "1-2" => ["km" => 1258.1691302282]
        // the second and third point you add will shown like this:
        // "2-3" => ["km" => 1258.1691302282]
        //  and so on.
        // you can use callback, the callback is return Collection instance of result.
        ->getDistance();
    } else {
        $distance = 0;
    }



    return $distance;
    }
    
    public function storeTripGroup(){
        $trip_group = new TripGroup;
        $trip_group->name = $this->trip_group;
        $trip_group->status = 1;
        $trip_group->save();
        $this->trip_group_id = $trip_group->id;
        $this->dispatchBrowserEvent('hide-trip_groupModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Trip Group Created Successfully Successfully!!"
        ]);
        
    }
    public function storeAllowance(){

        $allowance = new Allowance;
        $allowance->user_id = Auth::user()->id;
        $allowance->name = $this->allowance_title;
        $allowance->save();
        $this->allowance_title = $allowance->name;
        $this->dispatchBrowserEvent('hide-allowanceModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Allowance Created Successfully Successfully!!"
        ]);
        
    }

    public function mount(){
        $this->containers = Container::orderBy('name','asc')->latest()->get();
        $this->transporters = Transporter::with('vehicles:id,registration_number','vehicles.vehicle_make:id,name','vehicles.vehicle_model:id,name','horses:id,registration_number','horses.horse_make:id,name','horses.horse_model:id,name','cargos:id,name','trailers:id,registration_number,make,model','drivers:id','drivers.employee:id,name,surname')->where('authorization','approved')->orderBy('name','asc')->get();
        $this->offloading_points = OffloadingPoint::orderBy('name','asc')->get();
        $this->loading_points = LoadingPoint::orderBy('name','asc')->get();
        $this->trip_groups = TripGroup::where('status',1)->latest()->get();
        $this->defined_customer_rates = Rate::where('category','Customer')->with('loading_point:id,name','offloading_point:id,name')->latest()->get();
        $this->defined_transporter_rates = Rate::where('category','Transporter')->with('loading_point:id,name','offloading_point:id,name')->latest()->get();
        $this->allowances = Allowance::latest()->get();
        $this->quotations = Quotation::orderBy('quotation_number','desc')->get();
        $this->routes = Route::with('truck_stops:id,name')->where('status',1)->orderBy('name','asc')->get();
        $this->measurements = Measurement::orderBy('name','asc')->get();
        $this->freight_calculation = 'flat_rate';
        $this->truck_stops = collect();
        $this->trip_fuel = 0;
        $this->with_customer_rates = "custom";
        $this->with_transporter_rates = "custom";
        $this->horses =  collect();
        $this->vehicles =  collect();
        $this->borders = Border::orderBy('name','asc')->get();
        $this->clearing_agents = ClearingAgent::orderBy('name','asc')->get();
        $this->trailers = collect();
        $this->drivers = collect();
        $this->rate = 0;
        $this->freight = 0;
        $this->transporter_rate = 0;
        $this->transporter_freight = 0;
        $this->agents = Agent::orderBy('name','asc')->get();
        $this->trip_types = TripType::orderBy('name','asc')->get();
        $this->account = Account::with('expenses')->where('name','Trip Expense')->first();
        $this->expenses = Expense::whereHas('account', function($q){
            $q->where('name', 'Trip Expense');
         })->orderBy('name','asc')->get();

         foreach($this->expenses as $expense){
            $this->expense_currency_id[] = $expense->currency_id;
            $this->amount[] = $expense->amount;
         }

        $this->consignees = Consignee::orderBy('name','asc')->get();
        $this->customers = Customer::orderBy('name','asc')->get();
        $this->brokers = Broker::orderBy('name','asc')->latest()->get();
        $this->cargos = collect();
        $this->currencies = Currency::orderBy('name','asc')->get();
        $this->destinations = Destination::with('country')->get()->sortBy('city')->sortBy('country.name');
        // $this->distance = $this->getDistance();
      }


      public function updated($value){
          $this->validateOnly($value);
      }
      protected $messages =[
        'expense_currency_id.*.required' => 'Currency field is required',
        'amount.*.required' => 'Amount field is required',
        'expense_id.*.required' => 'Expense field is required',
        'expense_currency_id.0.required' => 'Currency field is required',
        'amount.0.required' => 'Amount field is required',
        'expense_id.0.required' => 'Expense field is required',
        'customer_id.required' => 'Please select a customer',
        'selectedHorse.required' => 'Please select a horse',
        'selectedVehicle.required' => 'Please select a vehicle',
        'driver_id.required' => 'Please select a driver',
        'selectedCargo.required' => 'Please select your cargo',
        'selectedTo.required' => 'Please select a starting point ',
        'selectedFrom.required' => 'Please select your destination ',

    ];
      protected $rules = [
          'customer_id' => 'required',
          'selectedBroker' => 'nullable',
          'selectedHorse' => 'required',
          'selectedVehicle' => 'required',
          'selectedTransporter' => 'required',
          'trailer_id' => 'required',
          'driver_id' => 'required',
          'selectedTripType' => 'required',
          'selectedCargo' => 'required',
          'currency_id' => 'required',
        //   'loading_point_id' => 'required',
        //   'offloading_point_id' => 'required',
          'selectedFrom' => 'required',
          'selectedTo' => 'required',
          'litreage' => 'required',
          'litreage_at_20' => 'required',
        //   'truck_stops' => 'required',
        //   'truck_stop_id.0' => 'required',
        //   'truck_stop_id.*' => 'required',
          'weight' => 'required',
        //   'rate' => 'required',
        //   'commission_amount' => 'required',
        //   'commission' => 'required',
          'freight' => 'required',
        //   'selectedRoute' => 'required',
        //   'transporter_rate' => 'required',
        //   'transporter_freight' => 'required',
        //   'quantity' => 'required',
        //   'measurement' => 'required',
          'start_date' => 'required',
          'manifest_number' => 'nullable|unique:trips,manifest_number,NULL,id,deleted_at,NULL|string|min:2',
          'cd3_number' => 'nullable|unique:trips,cd3_number,NULL,id,deleted_at,NULL|string|min:2',
          'cd1_number' => 'nullable|unique:trips,cd1_number,NULL,id,deleted_at,NULL|string|min:2',
        //   'end_date' => 'required',
        //   'distance' => 'required',
          'payment_status' => 'required',
          'trip_status' => 'required',
        //   'amount.0' => 'required',
        //   'expense_id.0' => 'required',
        //   'expense_currency_id.0' => 'required',
        //   'amount.*' => 'required',
        //   'expense_currency_id.*' => 'required',


          'selectedContainer' => 'required',
          'selectedCategory' => 'required',
          'date' => 'required',
          'odometer' => 'nullable',
          'fuel_quantity' => 'required',
        //   'fuel_amount' => 'required',
        //   'fillup' => 'required',
        //   'type' => 'required',
        
      ];

     

      public function updatedSelectedDefinedCustomerRate($id){
            if(!is_null($id)){
                $defined_customer_rate = Rate::find($id);
                $this->rate = $defined_customer_rate->rate;
                $this->freight = $defined_customer_rate->freight;
                $this->weight = $defined_customer_rate->weight;
                $this->litreage = $defined_customer_rate->litreage;
                $this->distance = $defined_customer_rate->distance;
                $this->from = $defined_customer_rate->from;
                $this->to = $defined_customer_rate->to;
                $this->loading_point_id = $defined_customer_rate->loading_point_id;
                $this->offloading_point_id = $defined_customer_rate->offloading_point_id;
                $this->currency_id = $defined_customer_rate->currency_id;
            }
      }
      public function updatedSelectedDefinedTransporterRate($id){
        if(!is_null($id)){
            $defined_transporter_rate = Rate::find($id);
            $this->transporter_rate = $defined_transporter_rate->rate;
            $this->transporter_freight = $defined_transporter_rate->freight;
        }
      }



      public function store(){
        // try{

          $trip = new Trip;
          $trip->trip_number = $this->tripNumber();
          $trip->trip_ref = $this->trip_ref;
          $trip->user_id = Auth::user()->id;
          $trip->company_id = Auth::user()->employee->company_id;
          $trip->horse_id = $this->selectedHorse;
          $trip->vehicle_id = $this->selectedVehicle;
          $trip->transporter_id = $this->selectedTransporter;
          $trip->quotation_id = $this->selectedQuotation;
          if (isset($this->trip_group_id) && $this->trip_group_id  !=  "") {
            $trip->trip_group_id = $this->trip_group_id;
          }
          $trip->agent_id = $this->agent_id;
          $trip->customer_updates = $this->customer_updates;
          $trip->transporter_agreement = $this->transporter_agreement;
          $trip->fuel_order = $this->fuel_order;
          $trip->driver_id = $this->driver_id;
          $trip->with_customer_rates = $this->with_customer_rates;
          $trip->with_transporter_rates = $this->with_transporter_rates;
          $trip->broker_id = $this->selectedBroker;
          $trip->customer_id = $this->customer_id;
          $trip->consignee_id = $this->consignee_id;
          $trip->freight_calculation = $this->freight_calculation;
          $trip->currency_id = $this->currency_id;
          $trip->cd3_number = $this->cd3_number;
          $trip->cd1_number = $this->cd1_number;
          $trip->manifest_number = $this->manifest_number;
          $trip->cargo_id = $this->selectedCargo;
          $trip->trip_type_id = $this->selectedTripType;
          $trip->starting_mileage = $this->starting_mileage;
          $trip->ending_mileage = $this->ending_mileage;
          $trip->trip_fuel = $this->trip_fuel;
          $trip->defined_customer_rate_id = $this->selectedDefinedCustomerRate;
          $trip->defined_transporter_rate_id = $this->selectedDefinedTransporterRate;
          $trip->from = $this->selectedFrom;
          $trip->to = $this->selectedTo;
          $trip->offloading_point_id = $this->offloading_point_id;
          $trip->loading_point_id = $this->loading_point_id;
          $trip->start_date = $this->start_date;
          $trip->cargo_details = $this->cargo_details;
          $trip->end_date = $this->end_date;
          $trip->rate = $this->rate;
          $trip->transporter_rate = $this->transporter_rate;
          $trip->allowable_loss_weight = $this->allowable_loss_weight;
          $trip->allowable_loss_litreage = $this->allowable_loss_litreage;
          $trip->allowable_loss_quantity = $this->allowable_loss_quantity;
          $trip->quantity = $this->quantity;
          $trip->litreage = $this->litreage;
          $trip->litreage_at_20 = $this->litreage_at_20;
          $trip->measurement = $this->measurement;
          $trip->weight = $this->weight;
          $trip->freight = $this->freight;
          $trip->transporter_freight = $this->transporter_freight;
          $trip->exchange_rate = $this->exchange_rate;
          $trip->exchange_customer_freight = $this->exchange_customer_freight;
          $trip->exchange_transporter_freight = $this->exchange_transporter_freight;
          $trip->turnover = $this->freight;
          $this->turnover = $this->freight;

          if($this->transporter_agreement == True){
            $trip->cost_of_sales = $this->transporter_freight;
            $this->cost_of_sales = $this->transporter_freight;
          }
       
          $trip->payment_status = $this->payment_status;
          $trip->trip_status = $this->trip_status;
          $trip->trip_status_date = $this->start_date;
          $trip->stops = $this->stops;
          $trip->route_id = $this->selectedRoute;
          $trip->distance = $this->distance;
          $trip->comments = $this->comments;
          $trip->save();

          $this->trip = $trip;
          if (isset($this->trailer_id) && !empty($this->trailer_id) ) {
            $trip->trailers()->sync($this->trailer_id);
          }
          if (isset($this->selectedBorder) && !empty($this->selectedBorder)) {
            $trip->borders()->sync($this->selectedBorder);
          }
          if (isset($this->clearing_agent_id) && !empty($this->clearing_agent_id)) {
            $trip->clearing_agents()->sync($this->clearing_agent_id);
          }
          if (isset($this->truck_stop_id) && !empty($this->truck_stop_id)) {
            $trip->truck_stops()->sync($this->truck_stop_id);
          }
         

          if ($this->trip_status == "Offloaded" || $this->trip_status == "Cancelled") {
            $horse = Horse::withTrashed()->find($trip->horse_id);
            if (isset($horse)) {
                $horse->status = 1;
                $horse->update();
            }
            $vehicle = Vehicle::withTrashed()->find($trip->vehicle_id);
            if (isset($vehicle)) {
                $vehicle->status = 1;
                $vehicle->update();
            }
          
            $driver = Driver::withTrashed()->find($trip->driver_id);
            if (isset($driver)) {
                $driver->status = 1;
                $driver->update();
            }
           

            if ($trip->trailers->count()>0) {
                foreach ($trip->trailers as $trailer) {
                    $trailer = Trailer::withTrashed()->find($trailer->id);
                    $trailer->status = 1;
                    $trailer->update();
                }
            }
            
            $breakdown_assignments = $trip->breakdown_assignments;
            if ($breakdown_assignments->count()>0) {
            
            foreach ($breakdown_assignments as $breakdown_assignment) {
                $horse = Horse::withTrashed()->find($breakdown_assignment->horse_id);
                $horse->status = 1;
                $horse->update();

                $vehicle = Vehicle::withTrashed()->find($breakdown_assignment->vehicle_id);
                $vehicle->status = 1;
                $vehicle->update();
    
                $driver = Driver::withTrashed()->find($breakdown_assignment->driver_id);
                $driver->status = 1;
                $driver->update();
    
                if ($breakdown_assignment->trailers->count()>0) {
                    foreach ($trip->trailers as $trailer) {
                        $trailer = Trailer::withTrashed()->find($trailer->id);
                        $trailer->status = 1;
                        $trailer->update();
                    }
                }
            }
                # code...
            }
        }

          if (isset($this->allowance_id)) {
            foreach ($this->allowance_id as $key => $value) {
                $allowance_driver = new AllowanceDriver;
                $allowance_driver->driver_id = $this->driver_id ? $this->driver_id : Null;
                $allowance_driver->trip_id = $trip->id ? $trip->id : Null;
                if (isset($this->allowance_id[$key])) {
                $allowance_driver->allowance_id = $this->allowance_id[$key] ? $this->allowance_id[$key] : Null;                    
                }
                if (isset($this->allowance_currency_id[$key])) {
                $allowance_driver->currency_id = $this->allowance_currency_id[$key] ? $this->allowance_currency_id[$key] : Null;                    
                }
                if (isset($this->allowance_amount[$key])) {
                $allowance_driver->amount = $this->allowance_amount[$key] ? $this->allowance_amount[$key] : Null;                    
                }
                $allowance_driver->save();
               
            }
            
          }


          $delivery_note = new DeliveryNote;
          $delivery_note->user_id = Auth::user()->id;
          $delivery_note->trip_id = $trip->id;
          $delivery_note->measurement = $this->measurement;
          $delivery_note->distance = $this->distance;
          $delivery_note->loaded_date = $this->start_date;
          
          if (isset($this->trip->cargo)) {
            if ($this->trip->cargo->type == "Liquid") {
                $delivery_note->loaded_litreage = $this->litreage;
                $delivery_note->loaded_litreage_at_20 = $this->litreage_at_20;
              }elseif($this->trip->cargo->type == "Solid") {
                $delivery_note->loaded_quantity = $this->quantity;
              }
          }
     
          $delivery_note->loaded_weight = $this->weight;
          $delivery_note->loaded_rate = $this->rate;
          $delivery_note->loaded_freight = $this->freight;
          $delivery_note->transporter_loaded_rate = $this->transporter_rate;
          $delivery_note->transporter_loaded_freight = $this->transporter_freight;
          $delivery_note->save();

            if ($this->agent_id != "") {
                $commission = new Commission;
                $commission->user_id =  Auth::user()->id ;
                $commission->trip_id =  $trip->id ;
                $commission->agent_id =  $this->agent_id ;
                $commission->commission =  $this->commission ;
                $commission->amount =  $this->commission_amount ;
                $commission->date =  $this->start_date ;
                $commission->status =  1 ;
                $commission->save();
            }

            if ($this->fuel_order == True) {   

                $fuel = $trip->fuels->where('fillup',1)->first();

                $container = Container::find($this->selectedContainer);
        
                $fuel = new Fuel;
                $fuel->user_id = Auth::user()->id;
                $fuel->order_number = $this->orderNumber();
                $fuel->horse_id = $this->selectedHorse ? $this->selectedHorse : Null;
                $fuel->vehicle_id = $this->selectedVehicle ? $this->selectedVehicle : Null;
                $fuel->currency_id = $this->fuel_currency_id;
                $fuel->trip_id = $trip->id;
                if (isset($this->selectedVehicle)) {
                    $fuel->type = "Vehicle";
                }elseif(isset($this->selectedHorse)){
                    $fuel->type = "Horse";
                }
                
                $fuel->driver_id = $this->driver_id ? $this->driver_id : Null;
                $fuel->container_id = $this->selectedContainer ? $this->selectedContainer : Null;
                $fuel->date = $this->date;
                $fuel->unit_price = $this->unit_price;
                $fuel->quantity = $this->fuel_quantity;
                $fuel->amount = $this->fuel_amount;
                $fuel->transporter_price = $this->transporter_price;
                $fuel->transporter_total = $this->transporter_total;
                $fuel->profit = $this->fuel_profit;
                $fuel->odometer = $this->odometer;
                $fuel->category = $this->fuel_category;
                $fuel->exchange_amount = $this->fuel_exchange_amount;
                $fuel->exchange_rate = $this->fuel_exchange_rate;
                $fuel->fillup = 1;
                $fuel->status = 1;
                $fuel->comments = $this->fuel_comments;
                $fuel->save();
        
                $fuel_expense = Expense::where('name','Fuel Topup')->get()->first();
        
                $trip_expense = new TripExpense;
                $trip_expense->user_id = Auth::user()->id;
                $trip_expense->trip_id = $trip->id;
                $trip_expense->fuel_id = $fuel->id;
                if (isset($fuel_expense)) {
                    $trip_expense->expense_id = $fuel_expense->id;
                }
                $trip_expense->currency_id = $this->fuel_currency_id;
                $trip_expense->category = $this->fuel_category;
                $trip_expense->amount = $this->fuel_amount;
                $trip_expense->exchange_rate = $this->fuel_exchange_rate;
                $trip_expense->exchange_amount = $this->fuel_exchange_amount;
               
                if (isset($this->fuel_category)  && isset($this->fuel_amount) && isset($this->fuel_currency_id)) {
    
                    if ($this->fuel_currency_id == Auth::user()->employee->company->currency_id) {
                        if ($this->fuel_category == "Transporter") {
                            $this->total_transporter_expenses = $this->total_transporter_expenses + $this->fuel_amount;
                        }
                        elseif ($this->fuel_category == "Customer") {
                            $this->total_customer_expenses = $this->total_customer_expenses + $this->fuel_amount;
                        }
                        elseif ($this->fuel_category == "Self") {
                            $this->total_expenses = $this->total_expenses + $this->fuel_amount;
                        }
                    }else{
                        if ($this->fuel_category == "Transporter") {
                            $this->total_transporter_expenses = $this->total_transporter_expenses + $this->fuel_exchange_amount;
                         }
                         elseif ($this->fuel_category == "Customer") {
                             $this->total_customer_expenses = $this->total_customer_expenses + $this->fuel_exchange_amount;
                         }
                         elseif ($this->fuel_category == "Self") {
                             $this->total_expenses = $this->total_expenses + $this->fuel_exchange_amount;
                         }
                    }
                }
                $trip_expense->save();
            }


        if ($this->trip_expenses == True) {
          if ($this->expense_id) {
          foreach ($this->expense_id as $key => $value) {

            $trip_expense = new TripExpense;
            $trip_expense->user_id = Auth::user()->id;
            $trip_expense->trip_id = $trip->id;

            if (isset($this->expense_id[$key]) && $this->expense_id[$key] != "") {
                $trip_expense->expense_id = $this->expense_id[$key];
            }
            if (isset($this->expense_currency_id[$key]) && $this->expense_currency_id[$key] != "") {
                $trip_expense->currency_id = $this->expense_currency_id[$key];
            }
            if (isset($this->category[$key]) && $this->category[$key] != "") {
                $trip_expense->category = $this->category[$key];
            }
            if (isset($this->amount[$key]) && $this->amount[$key] != "") {
                $trip_expense->amount = $this->amount[$key];
            }
            if (isset($this->expense_exchange_rate[$key]) && $this->expense_exchange_rate[$key] != "") {
                $trip_expense->exchange_rate = $this->expense_exchange_rate[$key];
            }
            if (isset($this->expense_exchange_amount[$key]) && $this->expense_exchange_amount[$key] != "") {
                $trip_expense->exchange_amount = $this->expense_exchange_amount[$key];
            }
           
            if (isset($this->category[$key])  && isset($this->amount[$key]) && isset($this->expense_currency_id[$key])) {

                if ($this->expense_currency_id[$key] == Auth::user()->employee->company->currency_id) {
                    if ($this->category[$key] == "Transporter") {
                        $this->total_transporter_expenses = $this->total_transporter_expenses + $this->amount[$key];
                    }
                    elseif ($this->category[$key] == "Customer") {
                        $this->total_customer_expenses = $this->total_customer_expenses + $this->amount[$key];
                    }
                    elseif ($this->category[$key] == "Self") {
                        $this->total_expenses = $this->total_expenses + $this->amount[$key];
                    }
                }else{
                    if ($this->category[$key] == "Transporter") {
                        $this->total_transporter_expenses = $this->total_transporter_expenses + $this->expense_exchange_amount[$key];
                     }
                     elseif ($this->category[$key] == "Customer") {
                         $this->total_customer_expenses = $this->total_customer_expenses + $this->expense_exchange_amount[$key];
                     }
                     elseif ($this->category[$key] == "Self") {
                         $this->total_expenses = $this->total_expenses + $this->expense_exchange_amount[$key];
                     }
                }
            }
          

            $trip_expense->save();
         
          }
        
        }

        $trip = Trip::find($trip->id);

        if($trip->currency_id == Auth::user()->employee->company->currency_id){
            if ($this->total_customer_expenses > 0) {
                $this->turnover = $this->turnover +  $this->total_customer_expenses;
                $trip->turnover = $this->turnover;
            }
    
            if ($this->total_transporter_expenses > 0) {
                $cost_of_sales_less_transporter_expenses = $this->cost_of_sales - $this->total_transporter_expenses;
                $this->cost_of_sales = $cost_of_sales_less_transporter_expenses + $this->total_expenses;
                $trip->cost_of_sales = $this->cost_of_sales;
            }
        }else{
            if ($this->total_customer_expenses > 0) {
                $this->exchange_customer_freight = $this->exchange_customer_freight +  $this->total_customer_expenses;
                $trip->turnover = $this->exchange_customer_freight;
            }
    
            if ($this->total_transporter_expenses > 0) {
                $cost_of_sales_less_transporter_expenses = $this->exchange_transporter_freight - $this->total_transporter_expenses;
                $this->exchange_transporter_freight = $cost_of_sales_less_transporter_expenses + $this->total_expenses;
                $trip->cost_of_sales = $this->exchange_transporter_freight;
            }
        }

    

        $trip->update();
       
       
    }

    $trip = Trip::find($trip->id);
    $trip->gross_profit = $this->turnover;

        if (isset($this->cost_of_sales) && $this->cost_of_sales != "" && $this->cost_of_sales > 0) {
            if (isset($this->turnover) && $this->turnover != "" && $this->turnover > 0) {
                $trip->net_profit = $this->turnover - $this->cost_of_sales;
                $this->net_profit = $this->turnover - $this->cost_of_sales;
    
                if((isset($this->net_profit) && $this->net_profit > 0) && (isset($this->turnover) && $this->turnover > 0)){
                    $trip->markup_percentage = (($this->net_profit/$this->cost_of_sales) * 100);
                    $trip->net_profit_percentage = (($this->net_profit/$this->turnover) * 100);
                }
            } 
        }else {

            $trip->net_profit_percentage = 100 ;
            $trip->markup_percentage = 100 ;
        }


        $trip->update();
      



    $employees = Employee::whereHas('departments', function ($query) {
        return $query->where('name', '=', "Transport & Logistics");
    })->whereHas('ranks', function ($query) {
        return $query->where('name', '=', 'Management');
    })->orWhereHas('ranks', function ($query) {
        return $query->where('name', '=', 'Director');
    })->get();
   
    
    if ($employees->count()>0) {
     
        $category = "Trip";
        foreach ($employees as $employee) {
            if ($employee->email) {
                $company = Auth::user()->employee->company;
                Mail::to($employee->email)->send(new PendingNotificationEmails($company, $category, $trip->trip_number));
            }
           
        }
    }
    $this->dispatchBrowserEvent('alert',[
        'type'=>'success',
        'message'=>"Trip Scheduled Successfully!!"
    ]);
    return redirect()->route('trips.index');

        //     }
        //     catch(\Exception $e){
        //     // Set Flash Message
        //     $this->dispatchBrowserEvent('alert',[
        //         'type'=>'error',
        //         'message'=>"Something went wrong while creating trip!!"
        //     ]);
        // }
      }

      
    public function render()
    {

    
        if (isset(Auth::user()->employee->company->allowable_loss_percentage)) {
            if ((($this->weight != null && $this->weight != ""))) {
                $this->allowable_loss_weight =  $this->weight * (Auth::user()->employee->company->allowable_loss_percentage/100);
            }
        }
        if (isset(Auth::user()->employee->company->allowable_loss_percentage)) {
            if ((($this->litreage_at_20 != null && $this->litreage_at_20 != ""))) {
                $this->allowable_loss_litreage =  $this->litreage_at_20 * (Auth::user()->employee->company->allowable_loss_percentage/100);
            }
        }
        if (isset(Auth::user()->employee->company->allowable_loss_percentage)) {
            if ((($this->quantity != null && $this->quantity != ""))) {
                $this->allowable_loss_quantity =  $this->quantity * (Auth::user()->employee->company->allowable_loss_percentage/100);
            }
        }

        if ($this->freight_calculation == "rate_weight") {
            if (($this->rate != null && $this->rate != "")  && (($this->weight != null && $this->weight != "") || ($this->litreage_at_20 != null && $this->litreage_at_20 != ""))) {
                if ($this->cargo_type == "Solid") {
                    $this->freight = $this->rate * $this->weight;
                }elseif($this->cargo_type == "Liquid"){
                    $this->freight = $this->rate * $this->litreage_at_20;
                }
            }
        }
        elseif ($this->freight_calculation == "rate_distance") {
            if (($this->rate != null && $this->rate != "")  && (($this->distance != null && $this->distance != "") )) {
                $this->freight = $this->rate * $this->distance;
            }
        }
        elseif ($this->freight_calculation == "rate_weight_distance") {
          
            if (($this->rate != null && $this->rate != "") && (($this->weight != null && $this->weight != "") || ($this->litreage_at_20 != null && $this->litreage_at_20 != "")) && ($this->distance != null && $this->distance != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->freight = $this->rate * $this->weight * $this->distance;
                }elseif($this->cargo_type == "Liquid"){
                    $this->freight = $this->rate * $this->litreage_at_20 * $this->distance ;
                }
            }
            
        }
        elseif ($this->freight_calculation == "flat_rate") {
            if (($this->rate != null && $this->rate != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->freight = $this->rate;
                }elseif($this->cargo_type == "Liquid"){
                    $this->freight = $this->rate ;
                }
            }
            
        }
    
        if ($this->freight_calculation == "rate_weight") {
            if (($this->transporter_rate != null && $this->transporter_rate != "") && (($this->weight != null && $this->weight != "") || ($this->litreage_at_20 != null && $this->litreage_at_20 != ""))) {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_freight = $this->transporter_rate * $this->weight;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_freight = $this->transporter_rate * $this->litreage_at_20;
                } 
            }
        }
        elseif ($this->freight_calculation == "rate_distance") {
            if (($this->transporter_rate != null && $this->transporter_rate != "")  && (($this->distance != null && $this->distance != "") )) {
                $this->transporter_freight = $this->transporter_rate * $this->distance;
            }
        }
        elseif ($this->freight_calculation == "rate_weight_distance") {
            if (($this->transporter_rate != null && $this->transporter_rate != "") && (($this->weight != null && $this->weight != "") || ($this->litreage_at_20 != null && $this->litreage_at_20 != "")) && ($this->distance != null && $this->distance != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_freight = $this->transporter_rate * $this->weight * $this->distance;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_freight = $this->transporter_rate * $this->litreage_at_20 * $this->distance;
                } 
            }
            
        }
        elseif ($this->freight_calculation == "flat_rate") {
            if (($this->transporter_rate != null && $this->transporter_rate != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_freight = $this->transporter_rate ;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_freight = $this->transporter_rate;
                } 
            }
            
        }


    if ((isset($this->fuel_exchange_rate) && $this->fuel_exchange_rate > 0) && (isset($this->fuel_amount) && $this->fuel_amount > 0) ) {

        $this->fuel_exchange_amount = $this->fuel_exchange_rate * $this->fuel_amount;
        
    }
    
    if ((isset($this->exchange_rate) && $this->exchange_rate > 0) && (isset($this->freight) && $this->freight > 0) ) {

        $this->exchange_customer_freight = $this->exchange_rate * $this->freight;

    }
    if ((isset($this->exchange_rate) && $this->exchange_rate > 0) && (isset($this->transporter_freight) && $this->transporter_freight > 0)) {

        $this->exchange_transporter_freight = $this->exchange_rate * $this->transporter_freight; 
    
    }


    if((isset($this->unit_price) && $this->unit_price != null) && (isset($this->fuel_quantity) && $this->fuel_quantity != null )){
        $this->fuel_amount = $this->unit_price * $this->fuel_quantity;
    }
    if((isset($this->transporter_price) && $this->transporter_price != null) && (isset($this->fuel_quantity) && $this->fuel_quantity != null)){
        $this->transporter_total = $this->transporter_price * $this->fuel_quantity;
    }
    if((isset($this->transporter_total) && ($this->transporter_total >= 0))  && (isset($this->fuel_amount) && ($this->fuel_amount >= 0))){
        $this->fuel_profit = $this->transporter_total - $this->fuel_amount;
    }
    
    $horse = Horse::find($this->selectedHorse);
    if ($horse) {
        $this->fuel_balance = $horse->fuel_balance;
        $this->fuel_tank_capacity = $horse->fuel_tank_capacity;
    }
    $vehicle = Vehicle::find($this->selectedVehicle);
    if ($vehicle) {
        $this->fuel_balance = $vehicle->fuel_balance;
        $this->fuel_tank_capacity = $vehicle->fuel_tank_capacity;
    }
 

    if((isset($this->fuel_balance) && $this->fuel_balance != null) && (isset($this->fuel_quantity) && $this->fuel_quantity != null)){
        $this->horse_fuel_total = $this->fuel_balance + $this->fuel_quantity;     
    }

    
    if((isset($this->fuel_balance) && $this->fuel_balance != null) && (isset($this->fuel_quantity) && $this->fuel_quantity != null)){
        $this->vehicle_fuel_total = $this->fuel_balance + $this->fuel_quantity;       
    }
    
  
 
    $this->trip_groups = TripGroup::where('status',1)->latest()->get();
    $this->allowances = Allowance::latest()->get();
    $this->expenses = Expense::whereHas('account', function($q){
        $q->where('name', 'Trip Expense');
     })->orderBy('name','asc')->get();

    $this->containers = Container::orderBy('name','asc')->latest()->get();
    $this->transporters = Transporter::where('authorization','approved')->orderBy('name','asc')->get();
    $this->offloading_points = OffloadingPoint::orderBy('name','asc')->get();
    $this->loading_points = LoadingPoint::orderBy('name','asc')->get();
    $this->destinations = Destination::with('country')->get()->sortBy('city')->sortBy('country.name');
    $this->currencies = Currency::orderBy('name','asc')->get();
    $this->customers = Customer::orderBy('name','asc')->get();
    $this->consignees = Consignee::orderBy('name','asc')->get();
    $this->agents = Agent::orderBy('name','asc')->get();
    $this->borders = Border::orderBy('name','asc')->get();
    $this->clearing_agents = ClearingAgent::orderBy('name','asc')->get();
    $this->routes = Route::with('truck_stops:id,name')->where('status',1)->orderBy('name','asc')->get();
    
    if ($this->selectedTransporter) {
        $this->cargos = Transporter::find($this->selectedTransporter)->cargos;
    }
    
    $this->expense_currency_id;
    $this->amount;

    // $this->distance = $this->calculateDistance($this->lat1, $this->long1, $this->lat2, $this->long2);  

    if (isset($this->searchHorse)) {
        if (isset($this->selectedTransporter)) {
            $this->horses = Horse::query()->with('horse_make:id,name','horse_model:id,name')->where('transporter_id',$this->selectedTransporter)
                                 ->where('registration_number', 'like', '%'.$this->searchHorse.'%')->where('status', 1)->where('service',0)->get();
        }else{
            $this->horses = Horse::query()->with('horse_make:id,name','horse_model:id,name')->where('registration_number', 'like', '%'.$this->searchHorse.'%')->where('status', 1)->where('service',0)->get();
        }
        
    }
   
    if (isset($this->searchVehicle)) {
        if (isset($this->selectedTransporter)) {
            $this->vehicles = Vehicle::query()->with('vehicle_make:id,name','vehicle_model:id,name')->where('transporter_id',$this->selectedTransporter)
                                 ->where('registration_number', 'like', '%'.$this->searchVehicle.'%')->get();
        }else{
            $this->vehicles = Vehicle::query()->with('vehicle_make:id,name','vehicle_model:id,name')->where('registration_number', 'like', '%'.$this->searchVehicle.'%')->where('status', 1)->where('service',0)->get();
        }
        
    }
    if (isset($this->searchTrailer)) {
        if (isset($this->selectedTransporter)) {
            $this->trailers =  Trailer::where('transporter_id',$this->selectedTransporter)
                                    ->where('registration_number', 'like', '%'.$this->searchTrailer.'%')->where('status', 1)->where('service',0)->get();
        }else {
            $this->trailers = Trailer::where('registration_number', 'like', '%'.$this->searchTrailer.'%')->where('status', 1)->where('service',0)->get();
        }
    }
    if (isset($this->searchDriver)) {
        if (isset($this->selectedTransporter)) {
            $this->drivers = Driver::query()->with('employee:id,name,surname')->where('transporter_id',$this->selectedTransporter)
                                ->whereHas('employee', function ($query) {
                return $query->where('name', 'like', '%'.$this->searchDriver.'%');
            })->where('status', 1)->get();
    }else {
        $this->drivers = Driver::query()->with('employee:id,name,surname')->whereHas('employee', function ($query) {
            return $query->where('name', 'like', '%'.$this->searchDriver.'%');
        })->where('status', 1)->get();
    }
    }
  
    $this->defined_customer_rates = Rate::where('category','Customer')->with('loading_point:id,name','offloading_point:id,name')->latest()->get();
    $this->defined_transporter_rates = Rate::where('category','Transporter')->with('loading_point:id,name','offloading_point:id,name')->latest()->get();

    return view('livewire.trips.create',[
        'freight' => $this->freight,
        'transporter_freight' => $this->transporter_freight,
        'expenses' => $this->expenses,
        'fuel_amount' => $this->fuel_amount,
        'fuel_profit' => $this->fuel_profit,
        'transporter_total' => $this->transporter_total,
        'trip_group_id' => $this->trip_group_id,
        'trip_groups' => $this->trip_groups,
        'allowances' => $this->allowances,
        'containers' => $this->containers,
        'transporters' => $this->transporters,
        'horses' => $this->horses,
        'vehicles' => $this->vehicles,
        'currencies' => $this->currencies,
        'trailers' => $this->trailers,
        'drivers' => $this->drivers,
        'agents' => $this->agents,
        'brokers' => $this->brokers,
        'fuel_balance' => $this->fuel_balance,
        'routes' => $this->routes,
        'horse_fuel_total' => $this->horse_fuel_total,
        'vehicle_fuel_total' => $this->vehicle_fuel_total,
        'starting_mileage' => $this->starting_mileage,
        'truck_stops' => $this->truck_stops,
        'cargos' => $this->cargos,
        'destinations' => $this->destinations,
        'borders' => $this->borders,
        'clearing_agents' => $this->clearing_agents,
        'measurements' => $this->measurements,
        'offloading_points' => $this->offloading_points,
        'loading_points' => $this->loading_points,
        'customers' => $this->customers,
        'consignees' => $this->consignees,
        'container_balance' => $this->container_balance,
        'expense_currency_id' => $this->expense_currency_id,
        'fuel_tank_capacity'=> $this->fuel_tank_capacity,
        'distance' => $this->distance,
        'defined_customer_rates' => $this->defined_customer_rates,
        'defined_transporter_rates' => $this->defined_transporter_rates,
        
   ]);
    }
}
