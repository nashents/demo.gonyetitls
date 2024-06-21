<?php

namespace App\Http\Livewire\Trips;

use App\Models\Trip;
use App\Models\Horse;
use App\Models\Driver;
use App\Models\Country;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\TripStatus;
use App\Exports\PodTracker;
use App\Exports\TripsExport;
use App\Models\DeliveryNote;
use App\Models\TripDocument;
use App\Models\TripLocation;
use Livewire\WithPagination;
use Maatwebsite\Excel\Excel;
use Livewire\WithFileUploads;
use App\Exports\TripsReportExport;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Index extends Component
{
    use WithFileUploads;
    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    private $trips;
    public $trip_id;
    public $status;
    public $measurement;
    public $trip_filter;
    public $from;
    public $to;
    public $sea;
    public $loaded;
    public $loaded_date;
    public $offloaded;
    public $offloaded_date;
    public $payment_status;
    public $selectedStatus = NULL;
    public $selectedDeliveryNote = NULL;
    public $intransit_trips;

    public $date;
    public $countries;
    public $country_id;
    public $horse_id;
    public $city;
    public $description;
    public $suburb;
    public $street_address;

    public $search;
    protected $queryString = ['search'];

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
    public function updated($value){
        $this->validateOnly($value);
    }




    public $company;
    public $trip;
    public $trip_number;

    public $driver_id;

 
    public $trailer_regnumbers;
    public $trailer_reg_numbers;
    public $collection_point;
    public $deliver_point;
    public $weight;
    public $cargo;
 
    public $litreage;
    public $quantity;
    public $authorized_by;
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
    public $authorize;
    public $comments;
    public $customer_total;
    public $transporter_total;
    public $currencies;
    public $loaded_quantity;
    public $loaded_litreage;
    public $loaded_litreage_at_20;
    public $loaded_weight;
    public $loaded_rate;
    public $loaded_freight;
    public $ending_mileage;

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
   
  
  
    public $trip_status_date;
    public $trip_status_description;
  
    public $freight_calculation;
    public $total_customer_expenses = 0;
    public $total_transporter_expenses = 0;
    public $cost_of_sales = 0;
    public $grossprofit;
    public $turnover = 0;
    public $cargo_type;
   
        
    public function exportPodTrackerExcel(Excel $excel){

        return $excel->download(new PodTracker($this->from, $this->to, $this->trip_filter, $this->search), 'pod_tracking_' .time().'.xlsx');
    }
    
    public function exportTripsCSV(Excel $excel){

        return $excel->download(new TripsReportExport($this->from, $this->to, $this->trip_filter, $this->search), 'trips_' .time().'.csv', Excel::CSV);
    }

    public function exportTripsPDF(Excel $excel){

        return $excel->download(new TripsReportExport($this->from, $this->to, $this->trip_filter, $this->search), 'trips_' .time().'.pdf', Excel::DOMPDF);
    }
    public function exportTripsExcel(Excel $excel){
        return $excel->download(new TripsReportExport($this->from, $this->to, $this->trip_filter, $this->search), 'trips_' .time().'.xlsx');
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

    public function mount(){
        $this->resetPage();
        $this->trip_filter = "created_at";
        $this->intransit_trips = Trip::where('trip_status','!=','offloaded')->where('authorization','approved')->orderBy('created_at','desc')->get();
        $this->countries = Country::all();
     
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

      public function status($id){
      
     
        $trip = Trip::withTrashed()->find($id);
        $this->trip_id = $trip->id;
        $this->trip_status = $trip->trip_status;
        $this->selectedStatus = $trip->trip_status;
        $this->currency_id = $trip->currency_id;
        $this->turnover = $trip->freight;
        $this->cost_of_sales = $trip->transporter_freight;
        $this->trip_status_date = $trip->trip_status_date;
        if (isset( $this->selectedStatus) && ($this->selectedStatus == "Offloaded" || $this->selectedStatus == "Loaded")) {
            $this->selectedDeliveryNote = TRUE;
        }
        $this->trip_status_description = $trip->trip_status_description;
        $this->customer_updates = $trip->customer_updates;

        $delivery_note = $trip->delivery_note;



        $this->freight_calculation = $trip->freight_calculation;
        $this->cargo_type = $trip->cargo ? $trip->cargo->type : "";
        $this->ending_mileage = $trip->ending_mileage;

  
      
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
            $total_weight = $trip_destinations->where('weight','!=','')->where('weight','!=',Null)->sum('weight');
            if (isset($total_weight) && is_null($this->offloaded_weight)) {
                $this->offloaded_weight = $total_weight;
            }
            $total_quantity = $trip_destinations->where('quantity','!=','')->where('quantity','!=',Null)->sum('quantity');
            if (isset($total_quantity) && is_null($this->offloaded_quantity)) {
                $this->offloaded_quantity = $total_quantity;
            }
            $total_litreage = $trip_destinations->where('litreage','!=','')->where('litreage','!=',Null)->sum('litreage');
            if (isset($total_litreage) && is_null($this->offloaded_litreage)) {
                $this->offloaded_litreage = $total_litreage;
            }
            $total_litreage_at_20 = $trip_destinations->where('litreage_at_20','!=','')->where('litreage_at_20','!=',Null)->sum('litreage_at_20');
            if (isset($total_litreage_at_20) && is_null($this->offloaded_litreage_at_20)) {
                $this->offloaded_litreage_at_20 = $total_litreage_at_20;
            }
        }


        $this->dispatchBrowserEvent('show-statusModal');
      }


      public function update(){

        $trip = Trip::withTrashed()->find($this->trip_id);
        $trip->trip_status = $this->selectedStatus;
        $trip->trip_status_date = $this->trip_status_date;
        $trip->trip_status_description = $this->trip_status_description;
        $trip->ending_mileage = $this->ending_mileage;
        $trip->update();

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
                   

                    $total_payments = $bill->payments->where('amount','!=','')->where('amount','!=',Null)->sum('amount');

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
    //   return redirect(request()->header('Referer'));

    }


      public function editLocations(){
        $this->intransit_trips = Trip::where('trip_status','!=','offloaded')->where('authorization','approved')->orderBy('created_at','desc')->get();
        $this->dispatchBrowserEvent('show-locationsEditModal');
      }


    //   public function summary(){
    //     $company = Auth::user()->employee->company;
    //     return view('livewire.trips.summary',)->with([
    //         'trips' => $this->trips,
    //         'company' => $company,
    //         'from' => $this->from,
    //         'to' => $this->to,
    //       ]);

    //   }

      public function updateTripStatus(){

        if (isset($this->status)) {
            foreach ($this->status as $key => $value) {
              
                $trip = Trip::withTrashed()->find($key);
             
                if (isset($this->status[$key])) {
                    $trip->trip_status = $this->status[$key];

                    if ($trip->trip_status != "Offloaded" && $this->status[$key] == "Offloaded") {
                        
                        $horse = Horse::withTrashed()->find($trip->horse_id);
                        if (isset($horse)) {
                            $horse->status = 1;
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
                            $horse->update();
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
                }
                if (isset($this->date[$key])) {
                    $trip->trip_status_date = $this->date[$key];
                }
                
                if (isset($this->description[$key])) {
                    $trip->trip_status_description = $this->description[$key];
                }
               
                $trip->update();
        
                $trip_status = new TripStatus;
                $trip_status->user_id = Auth::user()->id;
                $trip_status->trip_id = $trip->id;

                if (isset($this->status[$key])) {
                    $trip_status->status = $this->status[$key];
                }
                if (isset($this->date[$key])) {
                    $trip_status->date = $this->date[$key];
                }
                if (isset($this->description[$key])) {
                    $trip_status->description = $this->description[$key];
                }

                $trip_status->save();
    

                $trip_location = new TripLocation;
                $trip_location->user_id = Auth::user()->id;
                $trip_location->trip_id = $trip->id;
                $trip_location->horse_id = $trip->horse_id;
                $trip_location->driver_id = $trip->driver_id;
           
                if (isset($this->country_id[$key])) {
                    $trip_location->country_id = $this->country_id[$key];
                }
                if (isset($this->description[$key])) {
                    $trip_location->description = $this->description[$key];
                }
                $trip_location->save();

                $this->dispatchBrowserEvent('hide-locationsEditModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Trip Statuses Updated Successfully!! "
                ]);
            }
        }
      }

      public function document($id){
        $trip = Trip::find($id);
        $this->trip_id = $trip->id;
        $this->dispatchBrowserEvent('show-tripDocumentsModal');
      }

      public function updateDocuments(){
        if (isset($this->title)) {
            foreach ($this->title as $key => $value) {
                if(isset($this->file[$key])){
                    $file = $this->file[$key];
                    // get file with ext
                    $fileNameWithExt = $file->getClientOriginalName();
                    //get filename
                    $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                    //get extention
                    $extention = $file->getClientOriginalExtension();
                    //file name to store
                    $fileNameToStore= $filename.'_'.time().'.'.$extention;
                    $file->storeAs('/documents', $fileNameToStore, 'my_files');

                }
                $document = new TripDocument;
                $document->user_id = Auth::user()->id;
                $document->trip_id = $this->trip_id;
                $document->title = $this->title[$key];
                $document->filename = $fileNameToStore;
                $document->save();

              }
        }
        $this->dispatchBrowserEvent('hide-tripDocumentsModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Trip Document(s) Uploaded Successfully!! "
        ]);
        return redirect()->route('trips.show', $this->trip_id);
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
        elseif ($this->freight_calculation == "rate_weight_distance") {
            if (($this->offloaded_rate != null && $this->offloaded_rate != "") && (($this->offloaded_weight != null && $this->offloaded_weight != "") || ($this->offloaded_litreage_at_20 != null && $this->offloaded_litreage_at_20 != "")) && ($this->offloaded_distance != null && $this->offloaded_distance != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_weight * $this->offloaded_distance;
                }elseif($this->cargo_type == "Liquid"){
                    $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_litreage_at_20 * $this->offloaded_distance ;
                }
            }
            
        }
        elseif ($this->freight_calculation == "rate_distance") {
            if (($this->offloaded_rate != null && $this->offloaded_rate != "")  && (($this->offloaded_distance != null && $this->offloaded_distance != "") )) {
                $this->offloaded_freight = $this->offloaded_rate * $this->offloaded_distance;
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
        elseif ($this->freight_calculation == "rate_weight_distance") {
            if (($this->transporter_offloaded_rate != null && $this->transporter_offloaded_rate != null)  && (($this->offloaded_weight != null && $this->offloaded_weight != "") || ($this->offloaded_litreage_at_20 != null && $this->offloaded_litreage_at_20 != "")) && ($this->offloaded_distance != null && $this->offloaded_distance != "")) {
                if ($this->cargo_type == "Solid") {
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_weight * $this->offloaded_distance;
                }elseif($this->cargo_type == "Liquid"){
                    $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_litreage_at_20 * $this->offloaded_distance;
                } 
            } 
        }
        elseif ($this->freight_calculation == "rate_distance") {
            if (($this->transporter_offloaded_rate != null && $this->transporter_offloaded_rate != "")  && (($this->offloaded_distance != null && $this->offloaded_distance != "") )) {
                $this->transporter_offloaded_freight = $this->transporter_offloaded_rate * $this->offloaded_distance;
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
                        return view('livewire.trips.index',[
                            'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                            'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereBetween($this->trip_filter,[$this->from, $this->to] )
                            ->where('trip_number','like', '%'.$this->search.'%')
                            ->orWhere('trip_status','like', '%'.$this->search.'%')
                            ->orWhere('authorization','like', '%'.$this->search.'%')
                            ->orWhereHas('horse', function ($query) {
                                return $query->where('registration_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('customer', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('delivery_note', function ($query) {
                                return $query->where('offloaded_date', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('cargo', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('horse', function ($query) {
                                return $query->where('registration_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('horse', function ($query) {
                                return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('user.employee', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('transporter', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('loading_point', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('offloading_point', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('trip_documents', function ($query) {
                                return $query->where('document_number', 'like', '%'.$this->search.'%');
                            })
                            ->orderBy('created_at','desc')->paginate(10),
                            'trip_filter' => $this->trip_filter
                        ]);
                    }else {
                        return view('livewire.trips.index',[
                            'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                            'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereBetween($this->trip_filter,[$this->from, $this->to] )->orderBy('created_at','desc')->paginate(10),
                            'trip_filter' => $this->trip_filter
                        ]);
                    }
                   
                }
                elseif (isset($this->search)) {
                   
                    return view('livewire.trips.index',[
                        'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                        'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereMonth('created_at', date('m'))
                        ->whereYear('created_at', date('Y'))
                        ->where('trip_number','like', '%'.$this->search.'%')
                        ->orWhere('trip_status','like', '%'.$this->search.'%')
                        ->orWhere('authorization','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('customer', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('cargo', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('delivery_note', function ($query) {
                            return $query->where('offloaded_date', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('user.employee', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('transporter', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('loading_point', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip_documents', function ($query) {
                            return $query->where('document_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('offloading_point', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('created_at','desc')->paginate(10),
                        'trip_filter' => $this->trip_filter
                    ]);
                }
                else {
                   
                    return view('livewire.trips.index',[
                        'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                        'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereMonth('created_at', date('m'))
                        ->whereYear($this->trip_filter, date('Y'))->orderBy('created_at','desc')->paginate(10),
                        'trip_filter' => $this->trip_filter
                    ]);
                  
                }
            }else {
                if (isset($this->from) && isset($this->to)) {
                    if (isset($this->search)) {
                        return view('livewire.trips.index',[
                            'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                            'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereBetween($this->trip_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
                            ->where('trip_number','like', '%'.$this->search.'%')
                            ->orWhere('trip_status','like', '%'.$this->search.'%')
                            ->orWhere('authorization','like', '%'.$this->search.'%')
                            ->orWhereHas('horse', function ($query) {
                                return $query->where('registration_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('customer', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('cargo', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('delivery_note', function ($query) {
                                return $query->where('offloaded_date', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('user.employee', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('trip_documents', function ($query) {
                                return $query->where('document_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('transporter', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('loading_point', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('horse', function ($query) {
                                return $query->where('registration_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('horse', function ($query) {
                                return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                            })
                            ->orWhereHas('offloading_point', function ($query) {
                                return $query->where('name', 'like', '%'.$this->search.'%');
                            })
                            ->orderBy('created_at','desc')->paginate(10),
                            'trip_filter' => $this->trip_filter
                        ]);
                    }else{
                        return view('livewire.trips.index',[
                            'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                            'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereBetween($this->trip_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10),
                            'trip_filter' => $this->trip_filter
                        ]);
                    }
                  
                   
                }
                elseif (isset($this->search)) {
                    return view('livewire.trips.index',[
                        'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                        'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereMonth($this->trip_filter, date('m'))
                        ->whereYear($this->trip_filter, date('Y'))->where('trip_number','like', '%'.$this->search.'%')->where('user_id',Auth::user()->id)
                        ->where('trip_number','like', '%'.$this->search.'%')
                        ->orWhere('trip_status','like', '%'.$this->search.'%')
                        ->orWhere('authorization','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('customer', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('delivery_note', function ($query) {
                            return $query->where('offloaded_date', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('cargo', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('user.employee', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('transporter', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip_documents', function ($query) {
                            return $query->where('document_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('fleet_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('loading_point', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('offloading_point', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('created_at','desc')->paginate(10),
                        'trip_filter' => $this->trip_filter
                    ]);
                }
                else {
                    
                    return view('livewire.trips.index',[
                        'trips' => Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                        'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->whereMonth($this->trip_filter, date('m'))
                        ->whereYear($this->trip_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10),
                        'trip_filter' => $this->trip_filter
                    ]);

                }
    
            }
       

      
    }
}
