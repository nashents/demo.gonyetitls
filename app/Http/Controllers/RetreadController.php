<?php

namespace App\Http\Controllers;

use App\Models\Retread;
use Illuminate\Support\Facades\Session;
use App\Http\Requests\StoreRetreadRequest;
use App\Http\Requests\UpdateRetreadRequest;

class RetreadController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('retreads.index');
    }
    public function orders()
    {
        return view('retreads.orders');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('retreads.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreRetreadRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreRetreadRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retread  $retread
     * @return \Illuminate\Http\Response
     */
    public function show(Retread $retread)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Retread  $retread
     * @return \Illuminate\Http\Response
     */
    public function edit(Retread $retread)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateRetreadRequest  $request
     * @param  \App\Models\Retread  $retread
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateRetreadRequest $request, Retread $retread)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retread  $retread
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retread $retread)
    {
        $retread->delete();
        Session::flash('success','Retread Order Successfully Deleted');
        return redirect()->back();
    }
}
