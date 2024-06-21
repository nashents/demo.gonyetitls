<?php

namespace App\Http\Controllers;

use App\Models\Horse;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HorseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('horses.index');
    }
    public function manage()
    {
        return view('horses.manage');
    }
    public function age()
    {
        return view('horses.age');
    }
    public function mileage()
    {
        return view('horses.mileage');
    }
    public function archived()
    {
        return view('horses.archived');
    }

    public function archive($id){
        $horse = Horse::find($id);
        $horse->archive = 1;
        $horse->service = 1;
        $horse->status = 0;
        $horse->update();
        Session::flash('success','Horse Archived Successfully!!');
        return redirect(route('horses.archived'));
    }

    public function reports(){
        return view('horses.reports');
    }

    public function horsesReportPreview($selectedFilter = null, $from = null, $to = null){
        return view('horses.preview')->with([
            'selectedFilter' => $selectedFilter,
            'from' => $from,
            'to' => $to,
            ]);
    }

    public function horsesReportPrint($selectedFilter = null, $from = null, $to = null){
        $company = Auth::user()->employee->company;

        if ( isset($selectedFilter)) {

            if (isset($from) && isset($to)) {
                $horses = Horse::whereBetween('created_at',[$from, $to] )->get();
            }else {
                $horses = Horse::all();
            }

           
          
        }
           
        return view('horses.print')->with([
            'selectedFilter' => $selectedFilter,
            'from' => $from,
            'to' => $to,
            'horses' => $horses,
            'company' => $company,
            ]);
        
   
    }

    public function horsesReportPDF($selectedFilter = null, $from = null, $to = null){
        $company = Auth::user()->employee->company;
        if ( isset($selectedFilter)) {

            if (isset($from) && isset($to)) {
                $horses = Horse::whereBetween('created_at',[$from, $to] )->get();
            }else {
                $horses = Horse::all();
            }

            $data = [
                'selectedFilter' => $selectedFilter,
                'from' => $from,
                'to' => $to,
                'horses' => $horses,
                'company' => $company,
            ];
          
        }
    
       
        $pdf = PDF::loadView('horses.horses', $data);

        return $pdf->download('horses-report.pdf');

    }

    public function deactivate(Horse $horse){
        // $horse = horse::find($id);
        $horse->status = 0 ;
        $horse->update();
        Session::flash('success','Horse Deactivated Successfully!!');
        return redirect(route('horses.index'));
    }
 
    public function service(Horse $horse){
        
        $bookings = $horse->bookings->where('status', 1);

        if (isset($bookings)) {
           foreach ($bookings as $booking) {
            $booking->status = 0;
            $booking->update();

            // $ticket = $booking->ticket;
            // if (isset($ticket)) {
            //     $ticket->closed_by_id = Auth::user()->id;
            //     $ticket->status = 0;
            //     $ticket->update();
            // }

           }
        }
    
        $horse->service = 0 ;
        $horse->update();


        Session::flash('success','Horse Ticket Closed Successfully');
        return redirect(route('horses.index'));
    }

    public function activate(Horse $horse){
        // $horse = horse::find($id);
        $horse->status = 1 ;
        $horse->update();
        Session::flash('success','Horse Activated Successfully!!');
        return redirect(route('horses.index'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('horses.create');
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
     * @param  \App\Models\Horse  $horse
     * @return \Illuminate\Http\Response
     */
    public function show(Horse $horse)
    {
        return view('horses.show')->with('horse',$horse);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Horse  $horse
     * @return \Illuminate\Http\Response
     */
    public function edit(Horse $horse)
    {
        return view('horses.edit')->with('horse',$horse);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Horse  $horse
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Horse $horse)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Horse  $horse
     * @return \Illuminate\Http\Response
     */
    public function destroy(Horse $horse)
    {
        $horse->delete();
        Session::flash('success','Horse deleted successfully!!');
        return redirect()->back();
    }
}
