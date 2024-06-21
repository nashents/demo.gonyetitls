<div>

    <div class="row mt-30">
        <x-loading/>

        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1">

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab"><strong>Inventory Details</strong> </a></li>

                {{-- <li role="presentation"><a href="#inventorys" aria-controls="inventorys" role="tab" data-toggle="tab">inventorys</a></li> --}}

            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">
                        <tbody class="text-center line-height-35 ">
                            <tr>
                                <th class="w-10 text-center line-height-35">Inventory#</th>
                                <td class="w-20 line-height-35">{{$inventory->inventory_number}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Vendor</th>
                                <td class="w-20 line-height-35"> {{$inventory->vendor ? $inventory->vendor->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Store</th>
                                <td class="w-20 line-height-35"> {{$inventory->store ? $inventory->store->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Product</th>
                                <td class="w-20 line-height-35">{{$inventory->product ? $inventory->product->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Part Number</th>
                                <td class="w-20 line-height-35">{{$inventory->part_number}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Serial Number</th>
                                <td class="w-20 line-height-35">{{$inventory->serial_number}}</td>
                            </tr>
                            @if (isset($inventory->horse_make))
                            <tr>
                                <th class="w-10 text-center line-height-35">Horse</th>
                                <td class="w-20 line-height-35">{{$inventory->horse_make->name}} {{$inventory->horse_model->name}}</td>
                            </tr>
                            @endif
                            @if (isset($inventory->vehicle_make))
                            <tr>
                                <th class="w-10 text-center line-height-35">Vehicle</th>
                                <td class="w-20 line-height-35">{{$inventory->vehicle_make->name}} {{$inventory->vehicle_model->name}}</td>
                            </tr>
                            @endif
                            <tr>
                                <th class="w-10 text-center line-height-35">Weight/Litreage/Quantity</th>
                                <td class="w-20 line-height-35">{{$inventory->weight}} {{$inventory->measurement}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Balance</th>
                                <td class="w-20 line-height-35">{{$inventory->balance}} {{$inventory->measurement}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Quantity</th>
                                <td class="w-20 line-height-35">{{$inventory->qty}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Currency</th>
                                <td class="w-20 line-height-35">{{$inventory->currency ? $inventory->currency->name : ""}}</td>
                            </tr>

                            <tr>
                                <th class="w-10 text-center line-height-35">Rate</th>
                                <td class="w-20 line-height-35">
                                    @if ($inventory->rate)
                                        {{$inventory->currency ? $inventory->currency->symbol : ""}}{{number_format($inventory->rate,2)}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Condition </th>
                                <td class="w-20 line-height-35">{{$inventory->condition}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Purchase Date</th>
                                <td class="w-20 line-height-35">{{$inventory->purchase_date}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Purchase Type</th>
                                <td class="w-20 line-height-35">{{$inventory->purchase_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Warrant Expiry Date</th>
                                <td class="w-20 line-height-35">{{$inventory->warranty_exp_date}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Useful Life</th>
                                <td class="w-20 line-height-35">
                                    @if ($inventory->life)
                                         {{$inventory->life}}Year(s)
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Residual Value</th>
                                <td class="w-20 line-height-35">
                                    @if ($inventory->residual_value)
                                    {{$inventory->currency ? $inventory->currency->symbol : ""}}{{number_format($inventory->residual_value,2)}}        
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Description</th>
                                <td class="w-20 line-height-35">{{$inventory->description}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Status</th>
                                <td class="w-20 line-height-35"><span class="badge bg-{{$inventory->status == 1 ? "success" : "danger"}}">{{$inventory->status == 1 ? "Active" : "Inactive"}}</span></td>
                            </tr>

                        </tbody>
                    </table>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="btn-group pull-right mt-10" >
                               <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                {{-- <button type="submit" wire:click="store({{$inspection->id}})" class="btn bg-success btn-wide btn-rounded" > <i class="fa fa-save"></i>Save</button> --}}
                            </div>
                        </div>
                        </div>
                </div>



                <!-- /.section-title -->
            </div>
        </div>
        <!-- /.col-md-9 -->
    </div>
</div>
