<?php

namespace App\Http\Controllers;

use App\Models\Trip;
use App\Models\Currency;
use App\Models\Destination;
use App\Models\TripExpense;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class TripController extends Controller
{

   

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('trips.index');
    }
    public function thirdParty()
    {
        return view('third_parties.index');
    }
    public function thirdPartyShow($id)
    {
        $trip = Trip::find($id);
        return view('third_parties.show')->with('trip',$trip);
    }

    public function orders()
    {
        return view('trips.orders');
    }
    public function pending()
    {
        return view('trips.pending');
    }
    public function approved()
    {
        return view('trips.approved');
    }
    public function rejected()
    {
        return view('trips.rejected');
    }
    public function reports(){
        return view('trips.reports');
    }

    public function deleted(){
        return view('trips.deleted');
    }

    public function summary($trip_filter = null){
        $company = Auth::user()->employee->company;
            $from = null;
            $to = null;
            $search = null;
        return view('trips.summary')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }
    public function allSummary($from = null, $to = null, $search = null, $trip_filter = null){
        $company = Auth::user()->employee->company;
        return view('trips.summary')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }
    public function rangeSummary($from = null, $to = null, $trip_filter = null){
        $company = Auth::user()->employee->company;
        $search = null;
        return view('trips.summary')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }

    public function searchSummary($search = null, $trip_filter = null){
        $company = Auth::user()->employee->company;
        $from = null;
        $to = null;
        return view('trips.summary')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }

    public function summaryPrint($trip_filter = null){
        $company = Auth::user()->employee->company;
            $from = null;
            $to = null;
            $search = null;
        return view('trips.summary_print')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }
    public function allSummaryPrint($from = null, $to = null, $search = null, $trip_filter = null){
        $company = Auth::user()->employee->company;
        return view('trips.summary_print')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }
    public function rangeSummaryPrint($from = null, $to = null, $trip_filter = null){
        $company = Auth::user()->employee->company;
        $search = null;
        return view('trips.summary_print')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }

    public function searchSummaryPrint($search = null, $trip_filter = null){
        $company = Auth::user()->employee->company;
        $from = null;
        $to = null;
        return view('trips.summary_print')->with([
            'from' => $from,
            'to' => $to,
            'search' => $search,
            'company' => $company,
            'trip_filter' => $trip_filter,
           
          ]);
    }
  

    public function preview(Trip $trip){
        if (isset(Auth::user()->employee->company)) {
            $company = Auth::user()->employee->company;
        }elseif (isset(Auth::user()->company)) {
          $company =  Auth::user()->company;
        }
        $cargos = $trip->cargos;
        $customer = $trip->customer;
        return view('trips.preview')->with([
            'trip' => $trip,
            'company' => $company,
            'cargos' => $cargos,
            'customer' => $customer,
          ]);
    }
    public function print(Trip $trip){

        if (isset(Auth::user()->employee->company)) {
            $company = Auth::user()->employee->company;
        }elseif (isset(Auth::user()->company)) {
          $company =  Auth::user()->company;
        }
        $cargos = $trip->cargos;
        $customer = $trip->customer;
        return view('trips.print')->with([
            'trip' => $trip,
            'company' => $company,
            'cargos' => $cargos,
            'customer' => $customer,
          ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('trips.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function show(Trip $trip)
    {
        $documents = $trip->trip_documents;
        $selected_trip = Trip::with(['fuel:id,order_number','transporter:id,name','trip_type:id,name','border:id,name',
        'clearing_agent:id,name','trip_group:id,name','broker:id,name','customer:id,name','horse','horse.horse_make','horse.horse_model',
        'trailers:id,make,model,registration_number','driver.employee:id,name,surname','loading_point:id,name','offloading_point:id,name',
        'route:id,name,rank','truck_stops:id,name','cargo','currency:id,name,symbol','agent:id,name','commission:id,commission,amount'])->find($trip->id);
     
        $from = Destination::with('country:id,name')->find($trip->from);
        $to = Destination::with('country:id,name')->find($trip->to);
        $customer_total = TripExpense::where('currency_id',$trip->currency_id)
        ->where('trip_id',$trip->id)
        ->where('category', 'customer')->sum('amount');
        $transporter_total = TripExpense::where('currency_id',$trip->currency_id)
        ->where('trip_id',$trip->id)
        ->where('category', 'transporter')->sum('amount');

        $currency = Currency::find($trip->currency_id); 
        $currencies = Currency::all(); 
        return view('trips.show')->with([
            'trip' => $selected_trip,
            'from' => $from,
            'to' => $to,
            'customer_total' => $customer_total,
            'transporter_total' => $transporter_total,
            'currency' => $currency,
            'currencies' => $currencies,
            'documents' => $documents
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function edit(Trip $trip)
    {
        return view('trips.edit')->with('trip',$trip);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Trip $trip)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Trip  $trip
     * @return \Illuminate\Http\Response
     */
    public function destroy(Trip $trip)
    {

        $horse = $trip->horse;
        $trailers = $trip->trailers;
        $driver = $trip->driver;
        $vehicle = $trip->vehicle;
        
        if (isset($vehicle)) {
            $vehicle->status = 1;
            $vehicle->update();
        }
        
        if (isset($horse)) {
            $horse->status = 1;
            $horse->update();
        }

        if (isset($driver)) {
            $driver->status = 1;
            $driver->update();
        }

        if (isset($trailers)) {
            foreach ($trailers as $trailer) {
                $trailer->status = 1;
                $trailer->update();
            }
        }

        $transportation_order = $trip->transport_order;
        $fuels = $trip->fuels;
        $delivery_note = $trip->delivery_note;
        $cash_flows = $trip->cash_flows;
        $expenses = $trip->trip_expenses;
        $bills = $trip->bills;
        if (isset($transportation_order)) {
            $transportation_order->delete();
        }
        if (isset($fuels)) {
            foreach ($fuels as $fuel) {
                $fuel->delete();
            }
           
        }
        if (isset($delivery_note)) {
            $delivery_note->delete();
        }
        if (isset($cash_flows)) {
            if ($cash_flows->count()>0) {
               foreach ($cash_flows as $cash_flow) {
               $cash_flow->delete();
               }
            }
        }
        if (isset($bills)) {
            if ($bills->count()>0) {
               foreach ($bills as $bill) {
                $bill_expenses = $bill->bill_expenses;
                if (isset($bill_expenses)) {
                  foreach ($bill_expenses as $expense) {
                        $expense->delete();
                  }
                }
               $bill->delete();
               }
            }
        }

        if (isset($expenses)) {
            if ($expenses->count()>0) {
               foreach ($expenses as $expense) {
               $expense->delete();
               }
            }
        }

        $trip->delete();
        Session::flash('success','Trip deleted successfully');
        return redirect()->back();
    }
}
