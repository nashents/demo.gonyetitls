<?php

namespace App\Http\Controllers;

use App\Models\Driver;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class DriverController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('drivers.index');
    }

    public function age()
    {
        return view('drivers.age');
    }

    public function archived()
    {
        return view('drivers.archived');
    }

    public function reports(){
        return view('drivers.reports');
    }

    public function deleted()
    {
        return view('drivers.deleted');
    }

    public function archive($id){
        $driver = Driver::find($id);
        $driver->archive = 1;
        $driver->update();
        Session::flash('success','Driver Archived Successfully!!');
        return redirect(route('drivers.archived'));
    }

    public function deactivate(Driver $driver){
        $driver->status = 0 ;
        $driver->update();
        Session::flash('success','Driver Successfully Deactivated');
        return redirect(route('drivers.index'));
    }

    public function activate(Driver $driver){
        $driver->status = 1 ;
        $driver->update();
        Session::flash('success','Driver Successfully Activated');
        return redirect(route('drivers.index'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('drivers.create');
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
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function show(Driver $driver)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function edit(Driver $driver)
    {
        return view('drivers.edit')->with('driver',$driver);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Driver $driver)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Driver  $driver
     * @return \Illuminate\Http\Response
     */
    public function destroy(Driver $driver)
    {
        $employee = $driver->employee;
      
        if ($employee) {
            $user = $employee->user;
            if ($user) {
                $user->delete();
            }
            $employee->delete();
        }
        $driver->delete();
        Session::flash('success','Driver Deleted Successfully!!');
        return redirect()->back();

    }
}
