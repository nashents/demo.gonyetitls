<?php

namespace App\Http\Controllers;

use App\Models\SellItem;
use App\Http\Requests\StoreSellItemRequest;
use App\Http\Requests\UpdateSellItemRequest;

class SellItemController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreSellItemRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreSellItemRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\SellItem  $sellItem
     * @return \Illuminate\Http\Response
     */
    public function show(SellItem $sellItem)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\SellItem  $sellItem
     * @return \Illuminate\Http\Response
     */
    public function edit(SellItem $sellItem)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateSellItemRequest  $request
     * @param  \App\Models\SellItem  $sellItem
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateSellItemRequest $request, SellItem $sellItem)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\SellItem  $sellItem
     * @return \Illuminate\Http\Response
     */
    public function destroy(SellItem $sellItem)
    {
        //
    }
}
