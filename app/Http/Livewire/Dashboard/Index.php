<?php

namespace App\Http\Livewire\Dashboard;

use Carbon\Carbon;
use App\Models\Bill;
use App\Models\Fuel;
use App\Models\Rank;
use App\Models\Trip;
use App\Models\Tyre;
use App\Models\Agent;
use App\Models\Asset;
use App\Models\Horse;
use App\Models\Leave;
use App\Models\Branch;
use App\Models\Driver;
use App\Models\Ticket;
use App\Models\Vendor;
use App\Models\Booking;
use App\Models\Invoice;
use App\Models\Service;
use App\Models\Trailer;
use App\Models\Vehicle;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Customer;
use App\Models\Employee;
use App\Models\Container;
use App\Models\Inventory;
use App\Models\Allocation;
use App\Models\Assignment;
use App\Models\Department;
use App\Models\Destination;
use App\Models\FuelRequest;
use App\Models\Transporter;
use App\Models\DepartmentHead;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{

    public $transporter_count;
    public $transporters ;
    public $agents ;
    public $agent_count ;
    public $department_count ;
    public $rank ;
    public $hods;
    public $trip_count;
    public $horse_count ;
    public $destinations_count ;
    public $trailer_count ;
    public $vendor_count;
    public $vehicle_count ;
    public $assignment_count ;
    public $branch_count ;
    public $customer_count ;
    public $bill_count ;
    public $invoice_count ;
    public $requisition_count;
    public $destination_count;
    public $employee_count ;
    public $driver_count ;
    public $leave_count ;
    public $tyre_count ;
    public $service_count ;
    public $asset_count ;
    public $inventory_count ;
    public $booking_count ;
    public $ticket_count;
    public $fuel_supplier_count ;
    public $fuel_order_count ;
    public $transport_order_count ;
    public $recent_employees ;
    public $containers ;
    public $allocations ;
    public $myallocations ;
    public $petrol_quantity ;
    public $diesel_quantity ;
    public $currency ;
    public $currency_id ;
    public $trips ;
    
    public $litreage_moved;
    public $months;
   

    

    public function mount(){

        // $currentMonth = Carbon::now();
        // $this->current_month = $currentMonth->month;
        // $this->monthName = Carbon::createFromFormat('m', 6)->format('M');

        if (isset(Auth::user()->employee->company->curreny)) {
            $this->currency_id = Auth::user()->employee->company->curreny->id;
            $this->currency = Auth::user()->employee->company->curreny->name;
        }else{
            $this->currency_id = 1;
            $this->currency = "USD";
        }

        // $currentMonth = Carbon::now();

        // for ($i = 0; $i < $currentMonth->month; $i++) {

        //     $month = $currentMonth->copy()->subMonths($i);
        //     $this->litreage_moved = DB::table('trips')
        //         ->whereYear('created_at', '=', date('Y'))
        //         ->whereMonth('created_at', '=', $month->month)
        //         ->where('litreage_at_20', '!=', Null)
        //         ->where('litreage_at_20', '!=', "")
        //         ->sum('litreage_at_20');

        //     $this->months = Carbon::createFromFormat('m', $month->month)->format('M');

        // }
       

        // $this->jan_distance = Trip::whereYear('created_at', date('Y'))
        // ->whereMonth('created_at', 1)
        // ->where('starting_mileage', '!=', Null)
        // ->where('starting_mileage', '!=', "")
        // ->where('ending_mileage', '!=', Null)
        // ->where('ending_mileage', '!=', "")
        // ->selectRaw('SUM(ending_mileage - starting_mileage) as total')
        // ->value('total');


        $this->jan_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->feb_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->mar_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->apr_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->may_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->jun_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->jul_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->aug_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->sept_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
         ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->oct_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->nov_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));

        $this->dec_distance = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)
        ->where('starting_mileage', '!=', Null)
        ->where('starting_mileage', '!=', "")
        ->where('ending_mileage', '!=', Null)
        ->where('ending_mileage', '!=', "")
        ->sum(DB::raw('ending_mileage - starting_mileage'));


        $this->jan_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->feb_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->mar_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->apr_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->may_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->jun_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->jul_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->aug_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->sep_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->oct_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->nov_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');
        $this->dec_sales = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)->where('freight',"!=",Null)->where('freight',"!=","")->sum('freight');

        

        $this->jan_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)->get()->count();
        $this->feb_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)->get()->count();
        $this->mar_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)->get()->count();
        $this->apr_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)->get()->count();
        $this->may_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)->get()->count();
        $this->jun_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)->get()->count();
        $this->jul_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)->get()->count();
        $this->aug_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)->get()->count();
        $this->sep_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)->get()->count();
        $this->oct_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)->get()->count();
        $this->nov_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)->get()->count();
        $this->dec_trips = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)->get()->count();
       
      
        
        
        $this->jan_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->feb_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->mar_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->apr_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->may_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->jun_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->jul_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->aug_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->sept_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->oct_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->nov_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
        $this->dec_litreage = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)->where('litreage_at_20',"!=",Null)->where('litreage_at_20',"!=","")->sum('litreage_at_20');
       
        

        $this->jan_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->feb_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->mar_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->apr_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->may_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->jun_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->jul_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->aug_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->sept_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->oct_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->nov_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');
        $this->dec_weight = Trip::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)->where('weight',"!=",Null)->where('weight',"!=","")->sum('weight');


        $this->jan = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)->where('currency_id', $this->currency_id)->sum('total');
        $this->feb = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)->where('currency_id', $this->currency_id)->sum('total');
        $this->mar = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)->where('currency_id', $this->currency_id)->sum('total');
        $this->apr = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)->where('currency_id', $this->currency_id)->sum('total');
        $this->may = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)->where('currency_id', $this->currency_id)->sum('total');
        $this->jun = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)->where('currency_id', $this->currency_id)->sum('total');
        $this->jul = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)->where('currency_id', $this->currency_id)->sum('total');
        $this->aug = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)->where('currency_id', $this->currency_id)->sum('total');
        $this->sep = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)->where('currency_id', $this->currency_id)->sum('total');
        $this->oct = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)->where('currency_id', $this->currency_id)->sum('total');
        $this->nov = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)->where('currency_id', $this->currency_id)->sum('total');
        $this->dec = Invoice::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)->where('currency_id', $this->currency_id)->sum('total');
    
    
    
        $this->jan_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 1)->where('currency_id', $this->currency_id)->sum('total');
        $this->feb_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 2)->where('currency_id', $this->currency_id)->sum('total');
        $this->mar_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 3)->where('currency_id', $this->currency_id)->sum('total');

        $this->apr_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 4)->where('currency_id', $this->currency_id)->sum('total');

        $this->may_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 5)->where('currency_id', $this->currency_id)->sum('total');
        $this->jun_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 6)->where('currency_id', $this->currency_id)->sum('total');
        $this->jul_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 7)->where('currency_id', $this->currency_id)->sum('total');
        $this->aug_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 8)->where('currency_id', $this->currency_id)->sum('total');
        $this->sep_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 9)->where('currency_id', $this->currency_id)->sum('total');
        $this->oct_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 10)->where('currency_id', $this->currency_id)->sum('total');
        $this->nov_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 11)->where('currency_id', $this->currency_id)->sum('total');
        $this->dec_expense = Bill::whereYear('created_at', date('Y'))
        ->whereMonth('created_at', 12)->where('currency_id', $this->currency_id)->sum('total');
    
        
        $this->transporter_count = Transporter::all()->count();
        $this->transporters = Transporter::latest()->take(5)->get();
        $this->agents = Agent::latest()->take(5)->get();
        $this->trips = Trip::latest()->take(5)->get();
        $this->agent_count = Agent::all()->count();
        $this->department_count = Department::all()->count();
        $this->rank = Rank::where('name','HOD')->first();
        $this->hods = DepartmentHead::all();
        $this->trip_count = Trip::whereYear('created_at',date('Y'))->count();
        $this->horse_count = Horse::all()->count();
        $this->destinations_count = Destination::all()->count();
        $this->trailer_count = Trailer::all()->count();
        $this->vendor_count = Vendor::all()->count();
        $this->vehicle_count = Vehicle::all()->count();
        $this->assignment_count = Assignment::all()->count();
        $this->branch_count = Branch::all()->count();
        $this->customer_count = Customer::all()->count();
        $this->bill_count = Bill::all()->count();
        $this->invoice_count = Invoice::all()->count();
        $this->requisition_count = FuelRequest::all()->count();
        $this->destination_count = Destination::all()->count();
        $this->employee_count = Employee::doesntHave('driver')->get()->count();
        $this->driver_count = Driver::all()->count();
        $this->leave_count = Leave::whereYear('created_at',date('Y'))->count();
        $this->tyre_count = Tyre::whereYear('created_at',date('Y'))->count();
        $this->service_count = Service::all()->count();
        $this->asset_count = Asset::whereYear('created_at',date('Y'))->count();
        $this->inventory_count = Inventory::whereYear('created_at',date('Y'))->count();
        $this->booking_count = Booking::whereYear('created_at',date('Y'))->count();
        $this->ticket_count = Ticket::whereYear('created_at',date('Y'))->count();
        $this->fuel_supplier_count = Container::all()->count();
        $this->fuel_order_count = Fuel::whereYear('created_at',date('Y'))->count();
        $this->transport_order_count = TransportOrder::whereYear('created_at',date('Y'))->count();
        $this->recent_employees = Employee::latest()->take('5')->get();
        $this->containers = Container::latest()->take('5')->get();
        $this->allocations = Allocation::latest()->take('5')->get();
        $this->myallocations = Allocation::where('employee_id', Auth::user()->employee->id)->latest()->take('5')->get();
        $this->petrol_quantity = Container::where('fuel_type','Petrol')->sum('balance');
        $this->diesel_quantity = Container::where('fuel_type','Diesel')->sum('balance');
    }




    public function render()
    {
        return view('livewire.dashboard.index');
    }
}
