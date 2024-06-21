<?php

namespace App\Http\Livewire\Trips;

use App\Models\Bill;
use App\Models\Fuel;
use App\Models\Trip;
use App\Models\User;
use App\Models\Horse;
use App\Models\Driver;
use App\Models\Expense;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\Currency;
use App\Models\GatePass;
use App\Models\Container;
use App\Models\TripStatus;
use App\Mail\FuelOrderMail;
use App\Models\BillExpense;
use App\Models\Destination;
use App\Models\Transporter;
use App\Models\TripExpense;
use App\Models\DeliveryNote;
use App\Models\LoadingPoint;
use App\Mail\TripUpdatesMail;
use App\Models\TransportOrder;
use App\Mail\TransportOrderMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redirect;

class Show extends Component
{

    public $trip_id;

    public $company;
    public $trip;
    public $trip_number;

    public $driver_id;
    public $horse_id;
 
    public $trailer_regnumbers;
    public $trailer_reg_numbers;
    public $collection_point;
    public $deliver_point;
    public $weight;
    public $cargo;
    public $measurement;
    public $litreage;
    public $quantity;
    public $authorized_by;
    public $ending_mileage;
    public $checked_by;
    public $start_date;
    public $transporter_id;
    public $subtotal;
    public $total = 0;

    public $clearing_agent;
    public $boarder;
    public $route;
    public $truck_stops;

    //fuel order variables
    public $fuels;
    public $fuel_id;
    public $order_number;
    public $date;
    public $fullname;
    public $station_name;
    public $station_email;
    public $email;
    public $regnumber;
    public $fuel_type;
    public $fuel_order_quantity;
    public $driver;
    public $horse;
    public $delivery_point;
    public $fuel;
    public $mileage;

    public $search;
    protected $queryString = ['search'];

  
    public $customer_updates;
   
    public $customer_id;
   
    public $currency_id;
    public $currency;
    public $trailers;
   
    public $fuel_order_date;
    public $from_destination;
    public $to_destination;
    public $from_destination_country;
    public $to_destination_country;

    public $to;
    public $from;
    public $trip_filter;

    public $offloading_point;
    public $loading_point;
    public $loading_point_email;
    public $customer_email;
    public $fuel_station_email;
   
    public $end_date;
 
    public $rate;
    public $freight;
    public $distance;
    public $trip_status;


    public $trips;
    public $authorize;
    public $comments;

  
    public $status;
    
    public $customer_total;
    public $transporter_total;

  
    public $currencies;
  
  
   
    
   
  
    public $loaded_quantity;
    public $loaded_litreage;
    public $loaded_litreage_at_20;
    public $loaded_weight;
    public $loaded_rate;
    public $loaded_freight;
    public $loaded_date;
    public $offloaded_quantity;
    public $offloaded_distance;
    public $offloaded_litreage;
    public $offloaded_litreage_at_20;
    public $offloaded_weight;
    public $offloaded_rate;
    public $offloaded_freight;
    public $transporter_offloaded_rate;
    public $transporter_offloaded_freight;
    public $transporter_loaded_rate;
    public $transporter_loaded_freight;
    public $offloaded_date;
    public $payment_status;
    public $selectedStatus;
    public $trip_status_date;
    public $trip_status_description;
    public $selectedDeliveryNote;
    public $freight_calculation;
    public $total_customer_expenses = 0;
    public $total_transporter_expenses = 0;
    public $cost_of_sales = 0;
    public $grossprofit;
    public $turnover = 0;
    public $cargo_type;
   

    public $title;
    public $file;

    public $inputs = [];
    public $i = 1;
    public $n = 1;

    public function mount($id){
        $this->trip = Trip::withTrashed()->with(['fuel:id,order_number','transporter:id,name','trip_type:id,name','border:id,name',
        'clearing_agent:id,name','trip_group:id,name','broker:id,name','customer:id,name','horse','horse.horse_make','horse.horse_model',
        'trailers:id,make,model,registration_number','driver.employee:id,name,surname','loading_point:id,name','offloading_point:id,name',
        'route:id,name,rank','truck_stops:id,name','cargo:id,name,group,risk,type','currency:id,name,symbol','agent:id,name','commission:id,commission,amount'])->find($id);
        $this->trip_id = $id;
        $this->selectedStatus = $this->trip->trip_status;
        $this->from = Destination::with('country:id,name')->find($this->trip->from);
        $this->to = Destination::with('country:id,name')->find($this->trip->to);
        $this->customer_total = TripExpense::where('currency_id',$this->trip->currency_id)
        ->where('trip_id',$this->trip->id)
        ->where('category', 'customer')->where('amount','!=','')->where('amount','!=', Null)->sum('amount');
        $this->transporter_total = TripExpense::where('currency_id',$this->trip->currency_id)
        ->where('trip_id',$this->trip->id)
        ->where('category', 'transporter')->where('amount','!=','')->where('amount','!=', Null)->sum('amount');

        $this->currency = Currency::find($this->trip->currency_id); 
        $this->currency_id = $this->trip->currency_id; 
        $this->currencies = Currency::all(); 
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

    public function gate_passNumber(){

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
        $fuel = GatePass::orderBy('id', 'desc')->first();
        if(!$fuel){
        $gate_pass_number =  $initials .'GP'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else{
        $number = $fuel->id + 1 ;
        $gate_pass_number = $initials .'GP'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
        return $gate_pass_number;
    }

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

    public function updatedSelectedStatus($status)
    {
        if (!is_null($status) ) {
            if ($status != $this->trip_status) {    
                $this->trip_status_date = Null;
            }

            if ($status == "Offloaded" || $status == "Loaded") {
                $this->selectedDeliveryNote = TRUE;
            }else {
                $this->selectedDeliveryNote = NULL;
            }
        }

    }
    protected $messages =[

        'title.*.required' => 'Title field is required',
        'file.*.required' => 'File field is required',


    ];

      protected $rules = [

          'title.0' => 'nullable|string',
          'file.0' => 'nullable|file|mimes:docx,doc,pdf,xls,xlsx,pptx|max:10000',
          'title.*' => 'required',
          'file.*' => 'required|file|mimes:docx,doc,pdf,xls,xlsx,pptx|max:10000',
      ];

   


    public function status($id){
     
        $trip = Trip::withTrashed()->find($id);
        $this->trip_id = $trip->id;
        $this->trip_status = $trip->trip_status;
        $this->selectedStatus = $trip->trip_status;
        $this->turnover = $trip->freight;
        $this->cost_of_sales = $trip->transporter_freight;
        $this->currency_id = $trip->currency_id;
        $this->trip_status_date = $trip->trip_status_date;
        if (isset( $this->selectedStatus) && ($this->selectedStatus == "Offloaded" || $this->selectedStatus == "Loaded")) {
            $this->selectedDeliveryNote = TRUE;
        }
        $this->trip_status_description = $trip->trip_status_description;
        $this->customer_updates = $trip->customer_updates;
        $this->freight_calculation = $trip->freight_calculation;
        $this->cargo_type = $trip->cargo ? $trip->cargo->type : "";
        $this->ending_mileage = $trip->ending_mileage ;

  
        $delivery_note = $trip->delivery_note;
        
        if (isset($delivery_note)) {

            $this->measurement = $delivery_note->measurement;
            $this->distance = $delivery_note->distance;
            $this->loaded_quantity = $delivery_note->loaded_quantity;
            $this->loaded_litreage = $delivery_note->loaded_litreage;
            $this->loaded_litreage_at_20 = $delivery_note->loaded_litreage_at_20;
            $this->loaded_weight = $delivery_note->loaded_weight;
            $this->loaded_rate = $delivery_note->loaded_rate;
            $this->loaded_freight = $delivery_note->loaded_freight;
            $this->transporter_loaded_rate = $delivery_note->transporter_loaded_rate;
            $this->transporter_loaded_freight = $delivery_note->transporter_loaded_freight;
            $this->loaded_date = $delivery_note->loaded_date;

            if (!isset($this->transporter_loaded_rate) && !isset($this->transporter_loaded_freight)) {
                $delivery_note->transporter_loaded_rate = $trip->transporter_rate;
                $delivery_note->transporter_loaded_freight = $trip->transporter_freight;
                $this->transporter_loaded_rate = $trip->transporter_rate;
                $this->transporter_loaded_freight = $trip->transporter_freight;
                $delivery_note->update();
            }

            $this->offloaded_quantity = $delivery_note->offloaded_quantity;
            $this->offloaded_litreage = $delivery_note->offloaded_litreage;
            $this->offloaded_litreage_at_20 = $delivery_note->offloaded_litreage_at_20;
            $this->offloaded_weight = $delivery_note->offloaded_weight;
            $this->offloaded_distance = $delivery_note->offloaded_distance;

            if ($delivery_note->status == FALSE) {
                $this->offloaded_rate = $delivery_note->loaded_rate;
                $this->offloaded_freight = $delivery_note->loaded_freight;
                $this->transporter_offloaded_rate = $delivery_note->transporter_loaded_rate;
                $this->transporter_offloaded_freight = $delivery_note->transporter_loaded_freight;
            }else{
                $this->offloaded_rate = $delivery_note->offloaded_rate;
                $this->offloaded_freight = $delivery_note->offloaded_freight;
                $this->transporter_offloaded_rate = $delivery_note->transporter_offloaded_rate;
                $this->transporter_offloaded_freight = $delivery_note->transporter_offloaded_freight;
            }
         
            $this->offloaded_date = $delivery_note->offloaded_date;

           
        }else{
            $delivery_note = new DeliveryNote;
            $delivery_note->user_id = Auth::user()->id;
            $delivery_note->trip_id = $trip->id;
            $delivery_note->measurement = $trip->measurement;
            $delivery_note->distance = $trip->distance;
            $delivery_note->loaded_quantity = $trip->quantity;
            $delivery_note->loaded_litreage = $trip->litreage;
            $delivery_note->loaded_litreage_at_20 = $trip->litreage_at_20;
            $delivery_note->loaded_weight = $trip->weight;
            $delivery_note->loaded_rate = $trip->rate;
            $delivery_note->loaded_freight = $trip->freight;
            $delivery_note->transporter_loaded_rate = $trip->transporter_rate;
            $delivery_note->transporter_loaded_freight = $trip->transporter_freight;
            $delivery_note->loaded_date = $trip->start_date;
            $delivery_note->offloaded_quantity = $this->offloaded_quantity;
            $delivery_note->offloaded_litreage = $this->offloaded_litreage;
            $delivery_note->offloaded_litreage_at_20 = $this->offloaded_litreage_at_20;
            $delivery_note->offloaded_distance = $this->offloaded_distance;
            $delivery_note->offloaded_weight = $this->offloaded_weight;
            $delivery_note->offloaded_rate = $trip->rate;
            $delivery_note->offloaded_freight = $this->offloaded_freight;
            $delivery_note->transporter_offloaded_rate = $trip->transporter_rate;
            $delivery_note->transporter_offloaded_freight = $this->transporter_offloaded_freight;
            $delivery_note->offloaded_date = $this->offloaded_date;
            $delivery_note->comments = $this->comments;
            $delivery_note->save();

            $this->measurement = $delivery_note->measurement;
            $this->distance = $delivery_note->distance;
            $this->loaded_quantity = $delivery_note->loaded_quantity;
            $this->loaded_litreage = $delivery_note->loaded_litreage;
            $this->loaded_litreage_at_20 = $delivery_note->loaded_litreage_at_20;
            $this->loaded_weight = $delivery_note->loaded_weight;
            $this->loaded_rate = $delivery_note->loaded_rate;
            $this->loaded_freight = $delivery_note->loaded_freight;
            $this->transporter_loaded_rate = $delivery_note->transporter_loaded_rate;
            $this->transporter_loaded_freight = $delivery_note->transporter_loaded_freight;
            $this->loaded_date = $delivery_note->loaded_date;
            $this->offloaded_quantity = $delivery_note->offloaded_quantity;
            $this->offloaded_litreage = $delivery_note->offloaded_litreage;
            $this->offloaded_litreage_at_20 = $delivery_note->offloaded_litreage_at_20;
            $this->offloaded_distance = $delivery_note->offloaded_distance;
            $this->offloaded_weight = $delivery_note->offloaded_weight;
            
            if ($delivery_note->status == FALSE) {
                $this->offloaded_rate = $delivery_note->loaded_rate;
                $this->offloaded_freight = $delivery_note->loaded_freight;
                $this->transporter_offloaded_rate = $delivery_note->transporter_loaded_rate;
                $this->transporter_offloaded_freight = $delivery_note->transporter_loaded_freight;
            }else{
                $this->offloaded_rate = $delivery_note->offloaded_rate;
                $this->offloaded_freight = $delivery_note->offloaded_freight;
                $this->transporter_offloaded_rate = $delivery_note->transporter_offloaded_rate;
                $this->transporter_offloaded_freight = $delivery_note->transporter_offloaded_freight;
            }
      

            $this->offloaded_date = $delivery_note->offloaded_date;
            $this->transporter_loaded_rate = $trip->transporter_rate;
            $this->transporter_loaded_freight = $trip->transporter_freight;
        }

        $trip_destinations = $trip->trip_destinations;

        if(isset($trip_destinations)){
            $total_weight = $trip_destinations->where('weight','!=','')->where('weight','!=', Null)->sum('weight');
            if (isset($total_weight) && is_null($this->offloaded_weight)) {
                $this->offloaded_weight = $total_weight;
            }
            $total_quantity = $trip_destinations->where('quantity','!=','')->where('quantity','!=', Null)->sum('quantity');
            if (isset($total_quantity) && is_null($this->offloaded_quantity)) {
                $this->offloaded_quantity = $total_quantity;
            }
            $total_litreage = $trip_destinations->where('litreage','!=','')->where('litreage','!=', Null)->sum('litreage');
            if (isset($total_litreage) && is_null($this->offloaded_litreage)) {
                $this->offloaded_litreage = $total_litreage;
            }
            $total_litreage_at_20 = $trip_destinations->where('litreage_at_20','!=','')->where('litreage_at_20','!=', Null)->sum('litreage_at_20');
            if (isset($total_litreage_at_20) && is_null($this->offloaded_litreage_at_20)) {
                $this->offloaded_litreage_at_20 = $total_litreage_at_20;
            }
        }


        $this->dispatchBrowserEvent('show-statusModal');
      }

      public function paymentStatus($id){
        $trip = Trip::withTrashed()->find($id);
        $this->trip = $trip;
        $this->trip_id = $trip->id;
        $this->payment_status = $trip->payment_status;
        $this->dispatchBrowserEvent('show-paymentStatusModal');
      }

    public function update(){

        $trip = Trip::withTrashed()->find($this->trip_id);
        $trip->trip_status = $this->selectedStatus;
        $trip->trip_status_date = $this->trip_status_date;
        $trip->trip_status_description = $this->trip_status_description;
        $trip->ending_mileage = $this->ending_mileage;
        $trip->update();

        if (isset($trip->vehicle_id)) {
            $vehicle = Vehicle::find($trip->vehicle_id);
            $current_mileage = $vehicle->mileage;
            if($this->ending_mileage > $current_mileage){
                $vehicle->mileage = $this->ending_mileage;
            }
            $vehicle->update();

        }elseif(isset($trip->horse_id)){

            $horse = Horse::find($trip->horse_id);
            $current_mileage = $horse->mileage;
            if($this->ending_mileage > $current_mileage){
                $horse->mileage = $this->ending_mileage;
            }
            $horse->update();
        }

        $trip_status = new TripStatus;
        $trip_status->user_id = Auth::user()->id;
        $trip_status->trip_id = $trip->id;
        $trip_status->status = $this->selectedStatus;
        $trip_status->date = $this->trip_status_date;
        $trip_status->description = $this->trip_status_description;
        $trip_status->save();
        
        if ($this->selectedStatus == "Offloaded" || $this->selectedStatus == "Loaded" || $this->selectedStatus == "Cancelled") {

            $delivery_note = $trip->delivery_note;
            if (isset($delivery_note)) {
                $delivery_note->measurement = $this->measurement;
                $delivery_note->loaded_quantity = $this->loaded_quantity;
                $delivery_note->distance = $this->distance;
                $delivery_note->loaded_litreage = $this->loaded_litreage;
                $delivery_note->loaded_litreage_at_20 = $this->loaded_litreage_at_20;
                $delivery_note->loaded_rate = $this->loaded_rate;
                $delivery_note->loaded_weight = $this->loaded_weight;
                $delivery_note->loaded_freight = $this->loaded_freight;
                $delivery_note->transporter_loaded_rate = $this->transporter_loaded_rate;
                $delivery_note->transporter_loaded_freight = $this->transporter_loaded_freight;
                $delivery_note->loaded_date = $this->loaded_date;
                $delivery_note->offloaded_quantity = $this->offloaded_quantity;
                $delivery_note->offloaded_litreage = $this->offloaded_litreage;
                $delivery_note->offloaded_litreage_at_20 = $this->offloaded_litreage_at_20;
                $delivery_note->offloaded_weight = $this->offloaded_weight;
                $delivery_note->offloaded_rate = $this->offloaded_rate;
                $delivery_note->offloaded_freight = $this->offloaded_freight;
                $delivery_note->offloaded_distance = $this->offloaded_distance;
                $delivery_note->transporter_offloaded_rate = $this->transporter_offloaded_rate;
                $delivery_note->transporter_offloaded_freight = $this->transporter_offloaded_freight;
                $delivery_note->offloaded_date = $this->offloaded_date;
                $delivery_note->comments = $this->comments;
                $delivery_note->status = 1;
                $delivery_note->update();
            }else {
                $delivery_note = new DeliveryNote;
                $delivery_note->user_id = Auth::user()->id;
                $delivery_note->trip_id = $trip->id;
                $delivery_note->measurement = $this->measurement;
                $delivery_note->loaded_quantity = $this->loaded_quantity;
                $delivery_note->distance = $this->distance;
                $delivery_note->loaded_litreage = $this->loaded_litreage;
                $delivery_note->loaded_litreage_at_20 = $this->loaded_litreage_at_20;
                $delivery_note->loaded_rate = $this->loaded_rate;
                $delivery_note->loaded_weight = $this->loaded_weight;
                $delivery_note->loaded_freight = $this->loaded_freight;
                $delivery_note->transporter_loaded_rate = $this->transporter_loaded_rate;
                $delivery_note->transporter_loaded_freight = $this->transporter_loaded_freight;
                $delivery_note->loaded_date = $this->loaded_date;
                $delivery_note->offloaded_quantity = $this->offloaded_quantity;
                $delivery_note->offloaded_litreage = $this->offloaded_litreage;
                $delivery_note->offloaded_litreage_at_20 = $this->offloaded_litreage_at_20;
                $delivery_note->offloaded_weight = $this->offloaded_weight;
                $delivery_note->offloaded_rate = $this->offloaded_rate;
                $delivery_note->offloaded_freight = $this->offloaded_freight;
                $delivery_note->offloaded_distance = $this->offloaded_distance;
                $delivery_note->transporter_offloaded_rate = $this->transporter_offloaded_rate;
                $delivery_note->transporter_offloaded_freight = $this->transporter_offloaded_freight;
                $delivery_note->offloaded_date = $this->offloaded_date;
                $delivery_note->comments = $this->comments;
                $delivery_note->status = 1;
                $delivery_note->save();
            }

            if (Auth::user()->employee->company->offloading_details == TRUE) {
                if (isset($this->offloaded_freight)) {

                    $trip_expenses = $trip->trip_expenses;
                    foreach ($trip_expenses as $expense) {
                        if ($trip->currency_id == $expense->currency_id) {
                            if ($expense->category == "Customer") {
                                $this->total_customer_expenses = $this->total_customer_expenses + $expense->amount;
                            }
                            if ($expense->category == "Transporter") {
                                $this->total_transporter_expenses = $this->total_transporter_expenses + $expense->amount;
                            }
                        }
                    }

                    $trip = Trip::find($trip->id);

                    if (Auth::user()->employee->company->transporter_offloading_details == TRUE) {
                            if ((isset($this->total_transporter_expenses) && $this->total_transporter_expenses > 0) && (isset($this->transporter_offloaded_freight) && $this->transporter_offloaded_freight > 0 )) {
                                $cost_of_sales = $this->transporter_offloaded_freight - $this->total_transporter_expenses;
                                $delivery_note->cost_of_sales_updated_status = 1;
                                $delivery_note->update();
                            }
                    }else {
                        $cost_of_sales = $trip->cost_of_sales;
                    }
                
                    if ($this->total_customer_expenses > 0) {
                        if ((isset($this->offloaded_freight) && $this->offloaded_freight > 0) && (isset($this->total_customer_expenses) && $this->total_customer_expenses > 0)) {
                            $trip->turnover = $this->offloaded_freight +  $this->total_customer_expenses;
                            $turnover = $this->offloaded_freight +  $this->total_customer_expenses;
                            $delivery_note->turnover_updated_status = 1;
                            $delivery_note->update();
                        }
                        
                    }else {
                        $trip->turnover = $this->offloaded_freight;
                        $turnover = $this->offloaded_freight;
                    }
                
                    if ((isset($cost_of_sales) && $cost_of_sales != "" && $cost_of_sales > 0) && (isset($turnover) && $turnover != "" && $turnover > 0)) {
                        $trip->gross_profit = $turnover - $cost_of_sales;
                        $grossprofit = $turnover - $cost_of_sales;
                        $trip->markup_percentage = (($grossprofit/$cost_of_sales) * 100);
                        $trip->gross_profit_percentage = (($grossprofit/$turnover) * 100);
                    }else {
                        $trip->gross_profit = $turnover;
                        $trip->gross_profit_percentage = 100 ;
                        $trip->markup_percentage = 100 ;
                    }
            
                    $trip->update();

                }
            }

        if (Auth::user()->employee->company->transporter_offloading_details == TRUE) {

            if (isset($this->transporter_offloaded_freight)) {
                $trip_expenses = $trip->trip_expenses;
                foreach ($trip_expenses as $expense) {
                    if ($trip->currency_id == $expense->currency_id) {
                        if ($expense->category == "Customer") {
                            $this->total_customer_expenses = $this->total_customer_expenses + $expense->amount;
                        }
                        if ($expense->category == "Transporter") {
                            $this->total_transporter_expenses = $this->total_transporter_expenses + $expense->amount;
                        }
                    }
                }

                $trip = Trip::find($trip->id);
   
                $cost_of_sales = $this->transporter_offloaded_freight;

                $turnover = $trip->turnover;
    
                $trip->cost_of_sales = $cost_of_sales;

                if ((isset($cost_of_sales) && $cost_of_sales != "" && $cost_of_sales > 0) && (isset($turnover) && $turnover != "" && $turnover > 0)) {

                    $trip->gross_profit = $turnover - $cost_of_sales;
                    $grossprofit = $turnover - $cost_of_sales;
                    $trip->markup_percentage = (($grossprofit/$cost_of_sales) * 100);
                    $trip->gross_profit_percentage = (($grossprofit/$turnover) * 100);
                }else {
                    $trip->gross_profit = $turnover;
                    $trip->gross_profit_percentage = 100 ;
                    $trip->markup_percentage = 100 ;
                }
        
                $trip->update();

                if ($trip->transporter_agreement == TRUE) {
                    $transporter = $trip->transporter;
                    $bill = Bill::where('trip_id',$trip->id)
                                ->where('transporter_id',$transporter->id)->get()->first();
                   

                    $total_payments = $bill->payments->sum('amount');

                    $bill->total = $cost_of_sales;
                    if (isset( $total_payments) &&  $total_payments != Null && $total_payments > 0) {
                        $bill->balance = $cost_of_sales - $total_payments ;
                    }else{
                        $bill->balance = $cost_of_sales;
                    }

                    $bill->update();
                  

                   
                }
               
                
            }
        }

        
        if ($this->selectedStatus == "Offloaded"|| $this->selectedStatus == "Cancelled") {
            $horse = Horse::withTrashed()->find($trip->horse_id);
            $vehicle = Vehicle::withTrashed()->find($trip->vehicle_id);
            if (isset($horse)) {
                $horse->status = 1;
                if ($this->selectedStatus == "Offloaded") {
                    if ($horse->mileage != NULL && $trip->distance != NULL) {
                  
                        if ($trip->breakdown_assignments->count() <= 0) {
                            if(($horse->mileage != "" && $horse->mileage != Null && $horse->mileage >= 0) && ($trip->distance != "" && $trip->distance != Null && $trip->distance >= 0)){
                                $horse->mileage = $horse->mileage + $trip->distance; 
                            }
                        }
                      
                    }
                    if ((isset($horse->fuel_balance) && $horse->fuel_balance > 0) && $trip->trip_fuel != NULL) {
                        if ($trip->breakdown_assignments->count() <= 0) {
                            if(($horse->fuel_balance != "" && $horse->fuel_balance != Null && $horse->fuel_balance >= 0) && ($trip->trip_fuel != "" && $trip->trip_fuel != Null && $trip->trip_fuel >= 0)){
                                $horse->fuel_balance = $horse->fuel_balance - $trip->trip_fuel;
                            }
                        }
                      
                    }
                }
          
                $horse->update();
            }
            if (isset($vehicle)) {
                $vehicle->status = 1;
                if ($this->selectedStatus == "Offloaded") {
                    if ($vehicle->mileage != NULL && $trip->distance != NULL) {
                  
                        if ($trip->breakdown_assignments->count() <= 0) {
                            $vehicle->mileage = $vehicle->mileage + $trip->distance; 
                        }
                      
                    }
                    if ((isset($vehicle->fuel_balance) && $vehicle->fuel_balance > 0) && $trip->trip_fuel != NULL) {
                        if ($trip->breakdown_assignments->count() <= 0) {
                            $vehicle->fuel_balance = $vehicle->fuel_balance - $trip->trip_fuel;
                        }
                      
                    }
                }
          
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

        
        if (isset(Auth::user()->company)) {
            $this->company = Auth::user()->company;
            }elseif (isset(Auth::user()->employee->company)) {
                $this->company = Auth::user()->employee->company;
            }
          
                if ($this->customer_updates == TRUE) {
                    $this->customer_email = $this->trip->customer->email;
                    if (isset($this->customer_email) && $this->customer_email != "") {
                    Mail::to($this->customer_email)->send(new TripUpdatesMail($this->trip, $this->company));
                     }
                 }

      }


      $this->dispatchBrowserEvent('hide-statusModal');
      $this->dispatchBrowserEvent('alert',[
          'type'=>'success',
          'message'=>"Trip Status Updated Successfully!!"
      ]);
      return redirect(request()->header('Referer'));

    }

      public function updateStatus(){
        $trip = Trip::withTrashed()->find($this->trip_id);
        $trip->payment_status = $this->payment_status;
        $trip->update();
        $this->dispatchBrowserEvent('hide-paymentStatusModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Trip Payment Status Updated Successfully!!"
        ]);
        return redirect(route('trips.show', $this->trip_id));
      }


      private function resetInputFields(){
        $this->authorize = "";
        $this->comments = "";
    }

      public function authorize($id){
        $trip = Trip::find($id);
        $this->trip_id = $trip->id;
        $this->trip = $trip;
        $this->mileage = $trip->starting_mileage;
        $this->email = $trip->customer ? $trip->customer->email : "";
        $this->customer_updates = $trip->customer_updates;
        if (Auth::user()->company) {
            $this->company = Auth::user()->company;
        } elseif (Auth::user()->employee->company) {
            $this->company = Auth::user()->employee->company;
        }
        $this->dispatchBrowserEvent('show-authorizationModal');
      }

      public function updateAuthorization(){
        // try{
        $trip = Trip::find($this->trip_id);
        $trip->authorized_by_id = Auth::user()->id;
        $trip->authorization = $this->authorize;
        $trip->reason = $this->comments;
        $trip->update();

        if ($this->authorize == 'approved') {  

            $gate_pass = new GatePass;
            $gate_pass->user_id = Auth::user()->id;
            $gate_pass->gate_pass_number = $this->gate_passNumber();
            if (Auth::user()->employee->branch) {
                $gate_pass->branch_id = Auth::user()->employee->branch ? Auth::user()->employee->branch->id : "";
            }
            $gate_pass->type = "Trip";
            $gate_pass->trip_id = $trip->id;
            $gate_pass->driver_id = $trip->driver_id;
            $gate_pass->horse_id = $trip->horse_id;
            $gate_pass->exit = $trip->start_date;
            $trailers = $trip->trailers;

            foreach ($trailers as $trailer) {
                $trailer_ids[] = $trailer->id;
            }

            $gate_pass->save();
            if (isset($trailer_ids)) {
                $gate_pass->trailers()->sync($trailer_ids);
            }
           
            if (isset($trip->vehicle_id)) {
                $vehicle = Vehicle::find($trip->vehicle_id);
                $current_mileage = $vehicle->mileage;
                if($this->mileage > $current_mileage){
                    $vehicle->mileage = $this->mileage;
                }
                $vehicle->update();

            }elseif(isset($trip->horse_id)){

                $horse = Horse::find($trip->horse_id);
                $current_mileage = $horse->mileage;
                if($this->mileage > $current_mileage){
                    $horse->mileage = $this->mileage;
                }
                $horse->update();
            }


            $expenses = Trip::find($this->trip_id)->trip_expenses;
            if($expenses->count()>0){
                foreach ($expenses as $trip_expense) {

                    $bill = new Bill;
                    $bill->user_id = Auth::user()->id;
                    $bill->bill_number = $this->billNumber();
                    $bill->trip_id = $trip->id;
                    $bill->trip_expense_id = $trip_expense->id;
                    $bill->horse_id = $trip->horse_id;
                    $bill->driver_id = $trip->driver_id;
                    $bill->category = "Trip Expense";
                    $bill->bill_date = date("Y-m-d");
                    $bill->currency_id = $trip_expense->currency_id;
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
                }
            }
            
                if ($this->trip->transporter_agreement == TRUE) {   
                    $bill = new Bill;
                    $bill->user_id = Auth::user()->id;
                    $bill->bill_number = $this->billNumber();
                    $bill->trip_id = $trip->id;
                    $bill->category = "Transporter";
                    $bill->transporter_id = $trip->transporter_id;
                    $bill->bill_date = $trip->start_date;
                    $bill->currency_id = $trip->currency_id;
                    $bill->total = $trip->cost_of_sales;
                    $bill->balance = $trip->cost_of_sales;
                    $bill->save();

                    $expense = Expense::where('name','Transporter Payment')->get()->first();

                    $bill_expense = new BillExpense;
                    $bill_expense->user_id = Auth::user()->id;
                    $bill_expense->bill_id = $bill->id;
                    $bill_expense->currency_id = $bill->currency_id;
                    if (isset($expense)) {
                        $bill_expense->expense_id = $expense->id;
                    }
                    $bill_expense->qty = 1;
                    $bill_expense->amount = $trip->cost_of_sales;
                    $bill_expense->subtotal = $trip->cost_of_sales;
                    $bill_expense->save();

                }

       

                if ($trip->trailers->count()>0) {
                    foreach ($trip->trailers as $trailer) {
                        $trailer_regnumbers[] = $trailer->registration_number;
                    }
                    $regnumbers_string = implode(",",$trailer_regnumbers);
                }
                $user = User::find($trip->user_id);

                $transport_order = new TransportOrder;
                $transport_order->user_id = Auth::user()->id;
                $transport_order->trip_id = $trip->id;
                $transport_order->transporter_id = $trip->transporter_id;
                $transport_order->driver_id = $trip->driver_id;
                $transport_order->horse_id = $trip->horse_id;
                if (isset($regnumbers_string)) {
                    $transport_order->trailer_regnumber = $regnumbers_string;
                }
                $transport_order->collection_point = $trip->loading_point ? $trip->loading_point->name : "";
                $transport_order->delivery_point = $trip->offloading_point ? $trip->offloading_point->name : "";
                $transport_order->cargo = $trip->cargo ? $trip->cargo->name : "";
                $transport_order->weight = $trip->weight;
                if (isset($trip->quantity)) {
                    $transport_order->quantity = $trip->quantity;
                }else{
                    $transport_order->litreage = $trip->litreage;
                }
                $transport_order->measurement = $trip->measurement;
               
                $transport_order->date = $trip->start_date;
                $user = $trip->user;
                $name =  $user->employee ? $user->employee->name : "";
                $surname = $user->employee ? $user->employee->surname : "";
                $transport_order->checked_by = $name . ' ' . $surname;
                $transport_order->authorized_by = Auth::user()->employee->name . ' ' .Auth::user()->employee->surname;
                $transport_order->save();
             
                $this->trip_id = $trip->id;
                $this->driver_id = $trip->driver_id;
                $this->horse_id = $trip->horse_id;
                $this->transporter_id = $trip->transporter_id;
                $this->start_date = $trip->start_date;
                $user = $trip->user;
                $name =  $user->employee ? $user->employee->name : "";
                $surname = $user->employee ? $user->employee->surname : "";
                $this->checked_by = $name . ' ' . $surname;
                $auth_name = Auth::user()->employee->name;
                $auth_surname =Auth::user()->employee->surname;
                $this->authorized_by =  $auth_name. ' ' .$auth_surname;
                if (isset($trip->quantity)) {
                    $this->quantity = $trip->quantity;
                }else{
                    $this->litreage = $trip->litreage;
                }
                $this->measurement = $trip->measurement;
                $this->cargo = $trip->cargo ? $trip->cargo->name : "";
                $this->weight = $trip->weight;
                $this->delivery_point = $trip->offloading_point ? $trip->offloading_point->name : "";
                $this->collection_point = $trip->loading_point ? $trip->loading_point->name : "";
                if (isset($regnumbers_string)) {
                    $this->trailer_reg_numbers = $regnumbers_string;
                }
             
                $loading_point = LoadingPoint::find($trip->loading_point_id);
                if ( $loading_point) {
                    $this->loading_point_email =   $loading_point->email;
                }
               

                if ( isset($this->loading_point_email) && $this->loading_point_email != "") {
                    $data = array(
                        'email'=> $this->loading_point_email,
                        'date'=> $this->start_date,
                        'horse'=> Horse::find($this->horse_id),
                        'driver'=> Driver::find($this->driver_id),
                        'transporter'=> Transporter::find($this->transporter_id),
                        'trip'=> Trip::find($this->trip_id),
                        'regnumbers'=> $this->trailer_reg_numbers ? $this->trailer_reg_numbers : "",
                        'authorized_by'=> $this->authorized_by,
                        'checked_by'=> $this->checked_by,
                        'collection_point'=> $this->collection_point,
                        'delivery_point'=> $this->delivery_point,
                        'cargo'=> $this->cargo,
                        'litreage'=> $this->litreage,
                        'quantity'=> $this->quantity,
                        'measurement'=> $this->measurement,
                        'weight'=> $this->weight,
                       );
        
                       if (isset(Auth::user()->company)) {
                        $company = Auth::user()->company;
                        }elseif (isset(Auth::user()->employee->company)) {
                            $company = Auth::user()->employee->company;
                        }
        
                       Mail::to($loading_point->email)->send(new TransportOrderMail($data, $company));
                }
                
                if ($trip->trip_status != "Offloaded") {
                    $horse = Horse::withTrashed()->find($trip->horse_id);
                    $horse->status = 0;
                    $horse->update();
    
                    $driver = Driver::withTrashed()->find($trip->driver_id);
                    $driver->status = 0;
                    $driver->update();
    
                    if ($trip->trailers->count()>0) {
                        foreach ($trip->trailers as $trailer) {
                            $trailer = Trailer::withTrashed()->find($trailer->id);
                            $trailer->status = 0;
                            $trailer->update();
                        }
                    }
    
                }
          


                if ($this->customer_updates == TRUE) {

                    $this->customer_email = $this->trip->customer->email;
                   
                    if (isset($this->customer_email) && $this->customer_email != "") {
                    Mail::to($this->customer_email)->send(new TripUpdatesMail($this->trip, $this->company));
                     }
                 }

                 $initial_fuel = $this->trip->fuels->where('fillup',1)->first();
                if (isset($initial_fuel)) {
                    $fuel = Fuel::find($initial_fuel->id);
                    $fuel->authorization = $this->authorize;
                    $fuel->authorized_by_id = Auth::user()->id;
                    $fuel->comments = $this->comments;
                    $fuel->update();
            
                    if ($this->authorize == "approved") {
                        $container = Container::find($fuel->container_id);
                        if($container->balance >= $fuel->quantity){
                            $container->balance = $container->balance - $fuel->quantity;
                            $container->update();
                        }else {
                            $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
                            $this->dispatchBrowserEvent('alert',[
                                'type'=>'error',
                                'message'=>"Trip Approved but there is insufficient fuel balance from supplier"
                            ]);
                        }


                        $horse = Horse::find($fuel->horse_id);
                        $horse->fuel_balance = $horse->fuel_balance +  $fuel->quantity;
                        $horse->update();

                        // sending fuel order email to supplier
                        $trip = $fuel->trip;

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

                        $user = User::find($trip->user_id);
                        $this->fuel_station_email = $fuel->container ? $fuel->container->email : "";
                        $this->station_name = $fuel->container ? $fuel->container->name : "";
                        $this->fuel_order_date = $fuel->date;
                        $this->order_number = $fuel->order_number;
                        $this->driver = $fuel->driver;
                        $this->horse = $fuel->horse;
                        $this->collection_point = $fuel->trip->loading_point ? $fuel->trip->loading_point->name : "";
                        $this->delivery_point = $fuel->trip->offloading_point ? $fuel->trip->offloading_point->name : "";
                        $this->fuel_type = $fuel->container ? $fuel->container->fuel_type : "";
                        $this->fuel_order_quantity = $fuel->quantity;
                        $this->authorized_by = Auth::user()->employee->name . ' ' . Auth::user()->employee->surname;
                        $this->checked_by = $fuel->user->employee->name . ' ' . $fuel->user->employee->surname;
                        $this->regnumber = $fuel->horse ? $fuel->horse->registration_number : "";
            
                        if (isset($this->fuel_station_email) && $this->fuel_station_email != "") {
                        $data = array(
                            'station_email'=> $this->fuel_station_email,
                            'station_name'=> $this->station_name,
                            'date'=> $this->fuel_order_date,
                            'order_number'=> $this->order_number,
                            'driver'=> $this->driver,
                            'horse'=> $this->horse,
                            'regnumber'=> $this->regnumber,
                            'authorized_by'=> $this->authorized_by,
                            'checked_by'=> $this->checked_by,
                            'collection_point'=> $this->collection_point,
                            'delivery_point'=> $this->delivery_point,
                            'fuel_type'=> $this->fuel_type,
                            'quantity'=> $this->fuel_order_quantity,
                           );
            
                           if (isset(Auth::user()->company)) {
                            $company = Auth::user()->company;
                            }elseif (isset(Auth::user()->employee->company)) {
                                $company = Auth::user()->employee->company;
                            }
            
                           Mail::to($this->fuel_station_email)->send(new FuelOrderMail($data, $company));
                        }
                      
                    }
                }
                if (isset($initial_fuel)) {

                $this->dispatchBrowserEvent('hide-authorizationModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Trip & Fuel Order Approved Successfully!!"
                ]);
               return redirect()->route('trips.approved');
            }else {
                $this->dispatchBrowserEvent('hide-authorizationModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Trip Approved Successfully!!"
                ]);
               return redirect()->route('trips.approved');
            }
            
        }else {
            if (isset($initial_fuel)) {
                $this->dispatchBrowserEvent('hide-authorizationModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Trip & Fuel Order Rejected Successfully!!"
                ]);
                return redirect()->route('trips.rejected');
            }else{

                $this->dispatchBrowserEvent('hide-authorizationModal');
                $this->resetInputFields();
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Trip Rejected Successfully!!"
                ]);
                return redirect()->route('trips.rejected');
            }
          



        }

//     }
//     catch(\Exception $e){
//     // Set Flash Message
//     $this->dispatchBrowserEvent('alert',[
//         'type'=>'error',
//         'message'=>"Something goes wrong while updating trip!!"
//     ]);
// }

      }


    public function render()
    {

        if(isset($this->selectedStatus) && $this->selectedStatus == "Offloaded"){
            $this->offloaded_date = $this->trip_status_date;
        }
        if(isset($this->selectedStatus) && $this->selectedStatus == "Loaded"){
            $this->loaded_date = $this->trip_status_date;
        }
        
        if ($this->freight_calculation == "rate_weight") {
            if (($this->offloaded_rate != null && $this->offloaded_rate != "")  && (($this->offloaded_weight != null && $this->offloaded_weight != "") || ($this->offloaded_litreage_at_20 != null && $this->offloaded_litreage_at_20 != ""))) {
                if ($this->cargo_type == "Solid") {
                    $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_weight;
                }elseif($this->cargo_type == "Liquid"){
                    $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_litreage_at_20;
                }
            }
        }
        elseif ($this->freight_calculation == "rate_distance") {
            if (($this->offloaded_rate != null && $this->offloaded_rate != "")  && (($this->offloaded_distance != null && $this->offloaded_distance != "") )) {
                $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_distance;
            }
        }
        elseif ($this->freight_calculation == "rate_weight_distance") {
            if (($this->offloaded_rate != null && $this->offloaded_rate != "") && (($this->offloaded_weight != null && $this->offloaded_weight != "") || ($this->offloaded_litreage_at_20 != null && $this->offloaded_litreage_at_20 != "")) && ($this->offloaded_distance != null && $this->offloaded_distance != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_weight * $this->offloaded_distance;
                }elseif($this->cargo_type == "Liquid"){
                    $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_litreage_at_20 * $this->offloaded_distance ;
                }
            }
            
        }
        elseif ($this->freight_calculation == "flat_rate") {
            if ($this->offloaded_rate != null && $this->offloaded_rate != "") {
                if ($this->cargo_type == "Solid") {
                    $this->offloaded_freight = $this->offloaded_rate;
                }elseif($this->cargo_type == "Liquid"){
                    $this->offloaded_freight = $this->offloaded_rate ;
                }
            }
            
        }

        if ($this->freight_calculation == "rate_weight") {
            if (($this->transporter_offloaded_rate != null && $this->transporter_offloaded_rate) && (($this->offloaded_weight != null && $this->offloaded_weight != "") || ($this->offloaded_litreage_at_20 != null && $this->offloaded_litreage_at_20 != ""))) {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_weight;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_litreage_at_20;
                } 
            }
        }
        elseif ($this->freight_calculation == "rate_distance") {
            if (($this->transporter_offloaded_rate != null && $this->transporter_offloaded_rate != "")  && (($this->offloaded_distance != null && $this->offloaded_distance != "") )) {
                $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_distance;
            }
        }
        elseif ($this->freight_calculation == "rate_weight_distance") {
            if (($this->transporter_offloaded_rate != null && $this->transporter_offloaded_rate != null)  && (($this->offloaded_weight != null && $this->offloaded_weight != "") || ($this->offloaded_litreage_at_20 != null && $this->offloaded_litreage_at_20 != "")) && ($this->offloaded_distance != null && $this->offloaded_distance != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_weight * $this->offloaded_distance;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_litreage_at_20 * $this->offloaded_distance;
                } 
            } 
        }
        elseif ($this->freight_calculation == "flat_rate") {
            if ($this->transporter_offloaded_rate != null && $this->transporter_offloaded_rate != "") {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate ;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate;
                } 
            }
        }

        $this->trip = Trip::withTrashed()->with(['fuel:id,order_number','transporter:id,name','trip_type:id,name','border:id,name',
        'clearing_agent:id,name','trip_group:id,name','broker:id,name','customer:id,name','horse','horse.horse_make','horse.horse_model',
        'trailers:id,make,model,registration_number','driver.employee:id,name,surname','loading_point:id,name','offloading_point:id,name',
        'route:id,name,rank','truck_stops:id,name','cargo:id,name,group,risk,type','currency:id,name,symbol','agent:id,name','commission:id,commission,amount'])->find($this->trip_id);
        return view('livewire.trips.show',[
            'trip' => $this->trip
        ]);
    }
}
