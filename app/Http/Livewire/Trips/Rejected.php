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
use App\Models\CashFlow;
use App\Models\GatePass;
use App\Models\Container;
use App\Mail\FuelOrderMail;
use App\Models\BillExpense;
use App\Models\Transporter;
use App\Models\LoadingPoint;
use Livewire\WithPagination;
use App\Mail\TripUpdatesMail;
use App\Models\TransportOrder;
use App\Mail\TransportOrderMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Rejected extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';

    public $selectedRows = [];
    public $selectPageRows = false;
    
    public $authorize;
    public $comments;
    public $trip_id;

    public $company;
    public $trip;
    public $trip_number;

    public $driver_id;
    public $mileage;
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
    public $checked_by;
    public $start_date;
    public $transporter_id;
    public $subtotal;
    public $total = 0;
    public $total_transporter_expenses = 0;

    public $clearing_agent;
    public $boarder;
    public $route;
    public $truck_stops;
    public $search;
    protected $queryString = ['search'];

    //fuel order variables
    public $fuels;
    public $fuel_id;
    public $order_number;
    public $date;
    public $fullname;
    public $supplier_name;
    public $supplier_email;
    public $email;
    public $regnumber;
    public $fuel_type;
    public $fuel_order_quantity;
    public $driver;
    public $horse;
    public $delivery_point;
    public $fuel;

  
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
    public $fuel_supplier_email;
   
    public $end_date;
 
    public $rate;
    public $freight;
    public $distance;
    public $trip_status;

 private function resetInputFields(){
     $this->authorize = "";
     $this->comments = "";
 }

 public function dateRange(){
 
    // $this->resetPage();
}
public function updatingSearch()
{
    $this->resetPage();
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
   

    public function mount(){
        $this->resetPage();
        $this->trip_filter = 'created_at';
      }

      public function authorize($id){
        $trip = Trip::find($id);
        $this->trip_id = $trip->id;
        $this->trip = $trip;
        $this->mileage = $trip->starting_mileage;
        $this->email = ($trip->customer ? $trip->customer->email : "");
        $this->customer_updates = $trip->customer_updates;
        if (Auth::user()->company) {
            $this->company = Auth::user()->company;
        } elseif (Auth::user()->employee->company) {
            $this->company = Auth::user()->employee->company;
        }

        $this->dispatchBrowserEvent('show-authorizationModal');
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
        $gate_pass = GatePass::orderBy('id', 'desc')->first();
        if(!$gate_pass){
        $gate_pass_number =  $initials .'GP'. str_pad(1, 5, "0", STR_PAD_LEFT);
        }else{
        $number = $gate_pass->id + 1 ;
        $gate_pass_number = $initials .'GP'. str_pad($number, 5, "0", STR_PAD_LEFT);
        }
        return $gate_pass_number;
    }


    public function customerUpdates($trip_id, $customer_updates){
        $trip = Trip::find($trip_id);
        if($customer_updates == 1){
            $trip->customer_updates = 0;
            $trip->update();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>" Customer Updates Unset Successfully!!"
            ]);
        }elseif($customer_updates == 0){
            $trip->customer_updates = 1; 
            $trip->update();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>" Customer Updates Set Successfully!!"
            ]);
        }
       
    }

      public function update(){
        try {
       
        $trip = Trip::find($this->trip_id);
        $trip->authorized_by_id = Auth::user()->id;
        $trip->authorization = $this->authorize;
        $trip->reason = $this->comments;
        $trip->update();

            if ($trip->authorization == 'rejected') {
                if ($this->authorize == 'approved') {  

                    $gate_pass = new GatePass;
                    $gate_pass->user_id = Auth::user()->id;
                    $gate_pass->gate_pass_number = $this->gate_passNumber();
                    if (Auth::user()->employee->branch) {
                        $gate_pass->branch_id = Auth::user()->employee->branch ? Auth::user()->employee->branch->id : "";
                    }
                    $gate_pass->type = "Trip";
                    $gate_pass->trip_id = $trip->id;
                    $gate_pass->driver_id = $trip->driver_id ? $trip->driver_id : null;
                    $gate_pass->horse_id = $trip->horse_id ? $trip->horse_id : null;
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
        
                            if(isset($trip_expense->fuel_id)){
                                $fuel = Fuel::find($trip_expense->fuel_id);
                                if($fuel->container->purchase_type == "Once Off Buy"){
                                    $bill = new Bill;
                                    $bill->user_id = Auth::user()->id;
                                    $bill->bill_number = $this->billNumber();
                                    $bill->trip_id = $trip->id;
                                    $bill->fuel_id = $trip_expense->fuel_id;
                                    $bill->trip_expense_id = $trip_expense->id;
                                    $bill->horse_id = $trip->horse_id;
                                    $bill->driver_id = $trip->driver_id;
                                    $bill->category = "Trip Expense";
                                    $bill->bill_date = date("Y-m-d");
                                    $bill->currency_id = $trip_expense->currency_id;
                                    $bill->total = $trip_expense->amount;
                                    $bill->balance = $trip_expense->amount;
                
                                    $bill->authorized_by_id = Auth::user()->id;
                                    $bill->authorization = $this->authorize;
                                    $bill->comments = $this->comments;
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
                            }else{
                                $bill = new Bill;
                                $bill->user_id = Auth::user()->id;
                                $bill->bill_number = $this->billNumber();
                                $bill->trip_id = $trip->id;
                                $bill->fuel_id = $trip_expense->fuel_id;
                                $bill->trip_expense_id = $trip_expense->id;
                                $bill->horse_id = $trip->horse_id;
                                $bill->driver_id = $trip->driver_id;
                                $bill->category = "Trip Expense";
                                $bill->bill_date = date("Y-m-d");
                                $bill->currency_id = $trip_expense->currency_id;
                                $bill->total = $trip_expense->amount;
                                $bill->balance = $trip_expense->amount;
        
                                $bill->authorized_by_id = Auth::user()->id;
                                $bill->authorization = $this->authorize;
                                $bill->comments = $this->comments;
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
                    }
                    
                        if ($this->trip->transporter_agreement == TRUE) {   
                            
                            foreach($this->trip->trip_expenses as $expense){
        
                                if (isset($expense->category)  && isset($expense->amount) && isset($expense->currency_id)) {
        
                                    if ($expense->currency_id == Auth::user()->employee->company->currency_id) {
                                        if ($expense->category == "Transporter") {
                                            $this->total_transporter_expenses = $this->total_transporter_expenses + $expense->amount;
                                        }
                                       
                                    }else{
                                        if ($expense->category == "Transporter") {
                                            $this->total_transporter_expenses = $this->total_transporter_expenses + $expense->exchange_amount;
                                         }
                                        
                                    }
                                }
                            }
        
                            $bill = new Bill;
                            $bill->user_id = Auth::user()->id;
                            $bill->bill_number = $this->billNumber();
                            $bill->trip_id = $trip->id;
                            $bill->category = "Transporter";
                            $bill->transporter_id = $trip->transporter_id;
                            $bill->bill_date = $trip->start_date;
                            $bill->currency_id = $trip->currency_id;
                            $bill->total = $trip->transporter_freight -  $this->total_transporter_expenses;
                            $bill->balance = $trip->transporter_freight -  $this->total_transporter_expenses;
        
                            $bill->authorized_by_id = Auth::user()->id;
                            $bill->authorization = $this->authorize;
                            $bill->comments = $this->comments;
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
                            $bill_expense->amount = $trip->transporter_freight -  $this->total_transporter_expenses;
                            $bill_expense->subtotal = $trip->transporter_freight -  $this->total_transporter_expenses;
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
                
                               Mail::to($this->loading_point_email)->send(new TransportOrderMail($data, $company));
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
        
        
                        if ($this->trip->fuel_order == True) {
        
                            $fuel = Fuel::find($this->trip->fuel->id);
                            $fuel->authorization = $this->authorize;
                            $fuel->authorized_by_id = Auth::user()->id;
                            $fuel->comments = $this->comments;
                            $fuel->update();
                    
                            if ($this->authorize == "approved") {
        
                                // sending fuel order email to supplier
                                $trip = $fuel->trip;
        
                                if ($fuel->horse) {
                                    $horse = Horse::find($fuel->horse_id);
                                    $horse->fuel_balance = $horse->fuel_balance + $fuel->quantity;
                                    $current_mileage = $horse->mileage;
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
                                $this->fuel_order_date = $fuel->datze;
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
        
                                $container = Container::find($fuel->container_id);
                                if($container->balance >= $fuel->quantity){
                                    $container->balance = $container->balance - $fuel->quantity;
                                    $container->update();
                                }
                              
                            }
                        }
        
                        if ($trip->fuel) {
        
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
                    if ($trip->fuel) {
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

        }
    }
    catch(\Exception $e){
    // Set Flash Message
    $this->dispatchBrowserEvent('alert',[
        'type'=>'error',
        'message'=>"Something goes wrong while updating trip!!"
    ]);
}
      }


      public function showBulkyAuthorize(){
        $this->dispatchBrowserEvent('show-bulkyAuthorizationModal');
      }

      public function updatedSelectPageRows($value){

        if ($value) {
            $this->selectedRows = $this->trips->pluck('id')->map(function ($id){
                return (string) $id;
            });
        }else {
            $this->reset(['selectedRows','selectPageRows']);
        }
     
      }

      public function authorizeSelectedRows(){
           $selected_trips = Trip::WhereIn('id',$this->selectedRows)->get();
           
           if (isset($selected_trips)) {
                foreach($selected_trips as $trip){
                
                    $this->trip_id = $trip->id;
                    $this->trip = $trip;
                    $this->mileage = $trip->starting_mileage;
                    $this->email = $trip->customer ? $trip->customer->email : "";
                    $this->customer_updates = $trip->customer_updates;

                    $this->company = Auth::user()->employee->company;

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
                        $gate_pass->driver_id = $trip->driver_id ? $trip->driver_id : null;
                        $gate_pass->horse_id = $trip->horse_id ? $trip->horse_id : null;
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

                                if(isset($trip_expense->fuel_id)){
                                    $fuel = Fuel::find($trip_expense->fuel_id);
                                    if($fuel->container->purchase_type == "Once Off Buy"){
                                        $bill = new Bill;
                                        $bill->user_id = Auth::user()->id;
                                        $bill->bill_number = $this->billNumber();
                                        $bill->trip_id = $trip->id;
                                        $bill->fuel_id = $trip_expense->fuel_id;
                                        $bill->trip_expense_id = $trip_expense->id;
                                        $bill->horse_id = $trip->horse_id;
                                        $bill->driver_id = $trip->driver_id;
                                        $bill->category = "Trip Expense";
                                        $bill->bill_date = date("Y-m-d");
                                        $bill->currency_id = $trip_expense->currency_id;
                                        $bill->total = $trip_expense->amount;
                                        $bill->balance = $trip_expense->amount;
                    
                                        $bill->authorized_by_id = Auth::user()->id;
                                        $bill->authorization = $this->authorize;
                                        $bill->comments = $this->comments;
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
                                }else{
                                    $bill = new Bill;
                                    $bill->user_id = Auth::user()->id;
                                    $bill->bill_number = $this->billNumber();
                                    $bill->trip_id = $trip->id;
                                    $bill->fuel_id = $trip_expense->fuel_id;
                                    $bill->trip_expense_id = $trip_expense->id;
                                    $bill->horse_id = $trip->horse_id;
                                    $bill->driver_id = $trip->driver_id;
                                    $bill->category = "Trip Expense";
                                    $bill->bill_date = date("Y-m-d");
                                    $bill->currency_id = $trip_expense->currency_id;
                                    $bill->total = $trip_expense->amount;
                                    $bill->balance = $trip_expense->amount;
            
                                    $bill->authorized_by_id = Auth::user()->id;
                                    $bill->authorization = $this->authorize;
                                    $bill->comments = $this->comments;
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
                        }
            
                        if ($this->trip->transporter_agreement == TRUE) {   

                            foreach($this->trip->trip_expenses as $expense){

                                if (isset($expense->category)  && isset($expense->amount) && isset($expense->currency_id)) {
        
                                    if ($expense->currency_id == Auth::user()->employee->company->currency_id) {
                                        if ($expense->category == "Transporter") {
                                            $this->total_transporter_expenses = $this->total_transporter_expenses + $expense->amount;
                                        }
                                       
                                    }else{
                                        if ($expense->category == "Transporter") {
                                            $this->total_transporter_expenses = $this->total_transporter_expenses + $expense->exchange_amount;
                                         }
                                        
                                    }
                                }
                            }
        
                            $bill = new Bill;
                            $bill->user_id = Auth::user()->id;
                            $bill->bill_number = $this->billNumber();
                            $bill->trip_id = $trip->id;
                            $bill->category = "Transporter";
                            $bill->transporter_id = $trip->transporter_id;
                            $bill->bill_date = $trip->start_date;
                            $bill->currency_id = $trip->currency_id;
                            $bill->total = $trip->transporter_freight -  $this->total_transporter_expenses;
                            $bill->balance = $trip->transporter_freight -  $this->total_transporter_expenses;

                            $bill->authorized_by_id = Auth::user()->id;
                            $bill->authorization = $this->authorize;
                            $bill->comments = $this->comments;
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
                            $bill_expense->amount = $trip->transporter_freight -  $this->total_transporter_expenses;
                            $bill_expense->subtotal = $trip->transporter_freight -  $this->total_transporter_expenses;
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
                
                            Mail::to($this->loading_point_email)->send(new TransportOrderMail($data, $company));
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

                        if ($this->trip->fuel_order == True) {

                            $fuel = Fuel::find($this->trip->fuel->id);
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
        
        
                               
                                // sending fuel order email to supplier
                                $trip = $fuel->trip;
        
                                if ($fuel->horse) {
                                    $horse = Horse::find($fuel->horse_id);
                                    $horse->fuel_balance = $horse->fuel_balance + $fuel->quantity;
                                    $current_mileage = $horse->mileage;
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
                                $this->fuel_order_date = $fuel->datze;
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
                        
            
                    }
                    //the end bulky actions


                }

                if ($this->authorize == 'approved') {
                    if ($trip->fuel) {
                        $this->dispatchBrowserEvent('hide-authorizationModal');
                        $this->resetInputFields();
                        $this->reset(['selectPageRows','selectedRows']);
                        $this->dispatchBrowserEvent('alert',[
                            'type'=>'success',
                            'message'=>" All Selected Trip(s) & Fuel Order(s) Approved Successfully!!"
                        ]);
                      
                        return redirect()->route('trips.approved');
                        }else {
                        $this->dispatchBrowserEvent('hide-authorizationModal');
                        $this->resetInputFields();
                        $this->reset(['selectPageRows','selectedRows']);
                        $this->dispatchBrowserEvent('alert',[
                            'type'=>'success',
                            'message'=>"All Selected Trip(s) Approved Successfully!!"
                        ]);
                        return redirect()->route('trips.approved');
                        }
                }else {
                    if ($trip->fuel) {
                        $this->dispatchBrowserEvent('hide-authorizationModal');
                        $this->resetInputFields();
                        $this->reset(['selectPageRows','selectedRows']);
                        $this->dispatchBrowserEvent('alert',[
                            'type'=>'success',
                            'message'=>" All Selected Trip(s) & Fuel Order(s) Rejected Successfully!!"
                        ]);
                        return redirect()->route('trips.rejected');
                    }else{

                        $this->dispatchBrowserEvent('hide-authorizationModal');
                        $this->resetInputFields();
                        $this->reset(['selectPageRows','selectedRows']);
                        $this->dispatchBrowserEvent('alert',[
                            'type'=>'success',
                            'message'=>"All Selected Trip(s) Rejected Successfully!!"
                        ]);
                        return redirect()->route('trips.rejected');
                    }

            }


            
           }

      }

      public function getTripsProperty(){
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
                    return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                    'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereBetween($this->trip_filter,[$this->from, $this->to] )
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
                    ->orderBy('created_at','desc')->paginate(10);
                }else {
                    return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                    'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereBetween($this->trip_filter,[$this->from, $this->to] )->orderBy('created_at','desc')->paginate(10);
                }
               
            }
            elseif (isset($this->search)) {
               
                return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereMonth('created_at', date('m'))
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
                ->orderBy('created_at','desc')->paginate(10);
            }
            else {
               
                return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereMonth('created_at', date('m'))
                ->whereYear($this->trip_filter, date('Y'))->orderBy('created_at','desc')->paginate(10);
              
            }
        }else {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                    'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereBetween($this->trip_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
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
                    ->orderBy('created_at','desc')->paginate(10);
                }else{
                    return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                    'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereBetween($this->trip_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10);
                }
              
               
            }
            elseif (isset($this->search)) {

                return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereMonth($this->trip_filter, date('m'))
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
                ->orderBy('created_at','desc')->paginate(10);
            }
            else {
                
                return Trip::query()->with(['customer:id,name' ,'transporter:id,name','horse','horse.horse_model','horse.horse_make',
                'loading_point:id,name','offloading_point:id,name','invoice_items','trip_documents'])->where('authorization','rejected')->whereMonth($this->trip_filter, date('m'))
                ->whereYear($this->trip_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('created_at','desc')->paginate(10);

            }

        }
      }


    public function render()
    {
       
        return view('livewire.trips.rejected',[
            'trips' => $this->trips,
            'trip_filter' => $this->trip_filter
        ]);
   
    }
}
