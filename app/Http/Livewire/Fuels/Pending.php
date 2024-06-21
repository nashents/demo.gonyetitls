<?php

namespace App\Http\Livewire\Fuels;

use App\Models\Bill;
use App\Models\Fuel;
use App\Models\User;
use App\Models\Horse;
use App\Models\Expense;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Container;
use App\Mail\FuelOrderMail;
use App\Models\BillExpense;
use Livewire\WithPagination;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Pending extends Component
{

    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    
    // private $fuels;
    public $fuel_id;
    public $trip_id;
    public $authorize;
    public $comments;

    public $order_number;
    public $date;
    public $fullname;
    public $station_name;
    public $station_email;
    public $email;
    public $regnumber;
    public $authorized_by;
    public $checked_by;
    public $fuel_type;
    public $quantity;
    public $driver;
    public $horse;
    public $vehicle;
    public $collection_point;
    public $delivery_point;
    public $trip;
    public $fuel;

    public $fuel_filter;
    public $from;
    public $to;

    public $selectedRows = [];
    public $selectPageRows = false;

    public function mount(){
        $this->fuel_filter = "created_at";
    }
    public function authorize($id){
        $fuel = Fuel::find($id);
        $this->fuel_id = $fuel->id;
        $this->fuel = $fuel;
        $this->trip_id = $fuel->trip_id;
        $this->trip = $fuel->trip;
        $this->dispatchBrowserEvent('show-fuelAuthorizationModal');
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

    public function showBulkyAuthorize(){
        $this->dispatchBrowserEvent('show-bulkyAuthorizationModal');
      }

    public function updatedSelectPageRows($value){

        if ($value) {
            $this->selectedRows = $this->fuels->pluck('id')->map(function ($id){
                return (string) $id;
            });
        }else {
            $this->reset(['selectedRows','selectPageRows']);
        }
     
      }

      public function getFuelsProperty(){
 
        if (isset($this->from) && isset($this->to)) {
            return Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
            ])->where('authorization','pending')->whereBetween($this->fuel_filter,[$this->from, $this->to] )->orderBy('created_at','desc')->paginate(10);
           
        }else { 
            return Fuel::query()->with(['container:id,name','horse','horse.horse_model','horse.horse_make', 'vehicle','vehicle.vehicle_model','vehicle.vehicle_make',
            ])->where('authorization','pending')->whereMonth('created_at', date('m'))
            ->whereYear($this->fuel_filter, date('Y'))->orderBy('created_at','desc')->paginate(10);
          
        }

    }

      public function authorizeSelectedRows(){

        $selected_fuels = Fuel::WhereIn('id',$this->selectedRows)->get();
        
        if (isset($selected_fuels)) {

             foreach($selected_fuels as $fuel){
        
                
                $fuel->authorized_by_id = Auth::user()->id;
                $fuel->authorization = $this->authorize;
                $fuel->reason = $this->comments;
                $fuel->update();

                if ($this->authorize == "approved") {
        

                    $container = Container::find($fuel->container_id);
        
        
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
                    
                    if(($container->balance >= $fuel->quantity) && $container->purchase_type == "Bulk Buy"){
                        $container->balance = $container->balance - $fuel->quantity;
                        $container->update();
                    }
                    
                    if ($container->purchase_type == "Once Off Buy") {
        
                            $bill = new Bill;
                            $bill->user_id = Auth::user()->id;
                            $bill->bill_number = $this->billNumber();
                            $bill->fuel_id = $fuel->id;
                            $bill->category = "Fuel Order";
                            $bill->bill_date = date("Y-m-d");
                            $bill->currency_id =  $fuel->currency_id;
                            $bill->total =  $fuel->amount;
                            $bill->balance =  $fuel->amount;
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
                   
                    // sending fuel order email to station
                    $trip = $fuel->trip;
                    $this->station_email = $fuel->container ? $fuel->container->email : "";
                    $this->station_name = $fuel->container ? $fuel->container->name : "";
                    $this->date = $fuel->date;
                    $this->order_number = $fuel->order_number;
                    $this->driver = $fuel->driver;
                    $this->horse = $fuel->horse;
                    $this->vehicle = $fuel->vehicle;
                    if (isset($trip)) {
                        $this->collection_point = $trip->loading_point ? $trip->loading_point->name : "";
                        $this->delivery_point = $trip->offloading_point ? $trip->offloading_point->name : "";
                    }
                 
                    $this->fuel_type = $fuel->container ? $fuel->container->fuel_type : "";
                    $this->quantity = $fuel->quantity;
                    $this->authorized_by = Auth::user()->employee->name . ' ' . Auth::user()->employee->surname;
                    $this->checked_by = $fuel->user->employee->name . ' ' . $fuel->user->employee->surname;
                    $this->regnumber = $fuel->horse ? $fuel->horse->registration_number : "";
        
                    if ($this->station_email != "") {

                        if (filter_var($this->station_email, FILTER_VALIDATE_EMAIL)) {

                        $data = array(
                            'station_email'=> $this->station_email,
                            'station_name'=> $this->station_name,
                            'date'=> $this->date,
                            'order_number'=> $this->order_number,
                            'driver'=> $this->driver,
                            'horse'=> $this->horse,
                            'vehicle'=> $this->vehicle,
                            'regnumber'=> $this->regnumber,
                            'authorized_by'=> $this->authorized_by,
                            'checked_by'=> $this->checked_by,
                            'collection_point'=> $this->collection_point,
                            'delivery_point'=> $this->delivery_point,
                            'fuel_type'=> $this->fuel_type,
                            'quantity'=> $this->quantity,
                        );
            
                        if (isset(Auth::user()->company)) {
                            $company = Auth::user()->company;
                            }elseif (isset(Auth::user()->employee->company)) {
                                $company = Auth::user()->employee->company;
                            }
            
                        Mail::to($this->station_email)->send(new FuelOrderMail($data, $company));
            
                        
            
                        } 
                       
                    }
         
                }
            }

            $this->reset(['selectedRows','selectPageRows']);

            if ($this->authorize == "approved") {
                $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Bulk Fuel Order(s) Approved Successfully !!"
                ]);
                return redirect()->route('fuels.approved');
            }else {
    
                $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Bulk Fuel Order(s) Rejected Successfully"
                ]);
                return redirect()->route('fuels.rejected');
            }
     

          

        }

    }


      public function update(){
    //   try{
  
        $fuel = Fuel::find($this->fuel_id);
        if ($fuel->authorization == "approved") {
            $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Fuel Order Approved Already"
            ]);
        }else {

        if ($this->authorize == "approved") {

            $container = Container::find($fuel->container_id);

            $fuel->authorized_by_id = Auth::user()->id;
            $fuel->authorization = $this->authorize;
            $fuel->reason = $this->comments;
            $fuel->update();

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
            
            if(($container->balance >= $fuel->quantity) && $container->purchase_type == "Bulk Buy"){
                $container->balance = $container->balance - $fuel->quantity;
                $container->update();
            }
            
            if($fuel->container->purchase_type == "Once Off Buy" && isset($fuel->trip)){
        
                $bill = new Bill;
                $bill->user_id = Auth::user()->id;
                $bill->bill_number = $this->billNumber();
                $bill->trip_id = $fuel->trip->id;
                $bill->category = "Trip Expense";
                $bill->fuel_id = $fuel->id;
                $bill->bill_date = $fuel->date;
                $bill->currency_id = $fuel->currency_id;
                $bill->total = $fuel->amount;
                $bill->balance = $fuel->amount;
                $bill->save();

                $fuel_expense = Expense::where('name','Fuel Topup')->get()->first();

                $bill_expense = new BillExpense;
                $bill_expense->user_id = Auth::user()->id;
                $bill_expense->bill_id = $bill->id;
                $bill_expense->currency_id = $bill->currency_id;
                if (isset($expense)) {
                    $bill_expense->expense_id = $fuel_expense->id;
                }
                $bill_expense->qty = $fuel->quantity;
                $bill_expense->amount = $fuel->unit_price;
                $bill_expense->subtotal = $fuel->amount;
                $bill_expense->save();
            }


            // sending fuel order email to station
            $trip = $fuel->trip;
            $this->station_email = $fuel->container ? $fuel->container->email : "";
            $this->station_name = $fuel->container ? $fuel->container->name : "";
            $this->date = $fuel->date;
            $this->order_number = $fuel->order_number;
            $this->driver = $fuel->driver;
            $this->horse = $fuel->horse;
            $this->vehicle = $fuel->vehicle;
            if (isset($trip)) {
                $this->collection_point = $trip->loading_point ? $trip->loading_point->name : "";
                $this->delivery_point = $trip->offloading_point ? $trip->offloading_point->name : "";
            }
         
            $this->fuel_type = $fuel->container ? $fuel->container->fuel_type : "";
            $this->quantity = $fuel->quantity;
            $this->authorized_by = Auth::user()->employee->name . ' ' . Auth::user()->employee->surname;
            $this->checked_by = $fuel->user->employee->name . ' ' . $fuel->user->employee->surname;
            $this->regnumber = $fuel->horse ? $fuel->horse->registration_number : "";

            if ($this->station_email != "") {
            if (filter_var($this->station_email, FILTER_VALIDATE_EMAIL)) {
            $data = array(
                'station_email'=> $this->station_email,
                'station_name'=> $this->station_name,
                'date'=> $this->date,
                'order_number'=> $this->order_number,
                'driver'=> $this->driver,
                'horse'=> $this->horse,
                'vehicle'=> $this->vehicle,
                'regnumber'=> $this->regnumber,
                'authorized_by'=> $this->authorized_by,
                'checked_by'=> $this->checked_by,
                'collection_point'=> $this->collection_point,
                'delivery_point'=> $this->delivery_point,
                'fuel_type'=> $this->fuel_type,
                'quantity'=> $this->quantity,
               );

               if (isset(Auth::user()->company)) {
                $company = Auth::user()->company;
                }elseif (isset(Auth::user()->employee->company)) {
                    $company = Auth::user()->employee->company;
                }

               Mail::to($this->station_email)->send(new FuelOrderMail($data, $company));

               $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
               $this->dispatchBrowserEvent('alert',[
                   'type'=>'success',
                   'message'=>"Fuel Order Approved & Email to Station Sent Successfully"
               ]);
               return redirect()->route('fuels.approved');

            } else {
                $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'success',
                    'message'=>"Fuel Order Approved But Email Not Sent."
                ]);
                return redirect()->route('fuels.approved');
            }
               
            }

            $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Fuel Order Approved Successfully !!"
            ]);
            return redirect()->route('fuels.approved');

          
        }else {

            $fuel->authorized_by_id = Auth::user()->id;
            $fuel->authorization = $this->authorize;
            $fuel->reason = $this->comments;
            $fuel->update();

            $this->dispatchBrowserEvent('hide-fuelAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Fuel Order Rejected Successfully"
            ]);
            return redirect()->route('fuels.rejected');
        }
  }
   

// }
// catch(\Exception $e){
//     $this->dispatchBrowserEvent('hide-fuelEditModal');
//     $this->dispatchBrowserEvent('alert',[
//         'type'=>'error',
//         'message'=>"Something went wrong while trying to send email!!"
//     ]);
//     }

      }



  

    
    public function render()
    {
        return view('livewire.fuels.pending',[
            'fuels' => $this->fuels,
            'fuel_filter' => $this->fuel_filter,
        ]);
    }
}
