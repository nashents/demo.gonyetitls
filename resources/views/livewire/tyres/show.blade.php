<div>

    <div class="row mt-30">
        <x-loading/>

        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1">

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#order" aria-controls="basic" role="tab" data-toggle="tab"><strong>Tyre Details</strong> </a></li>

            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">
                        <tbody class="text-center line-height-35 ">

                            <tr>
                                <th class="w-10 text-center line-height-35">Tyre Number</th>
                                <td class="w-20 line-height-35">{{$tyre->tyre_number}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">CreatedBy</th>
                                <td class="w-20 line-height-35">{{$tyre->user ? $tyre->user->name : ""}} {{$tyre->user ? $tyre->user->surname : ""}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Date</th>
                                <td class="w-20 line-height-35">{{$tyre->purchase_date}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Vendor</th>
                                <td class="w-20 line-height-35"> {{$tyre->vendor ? $tyre->vendor->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Product</th>
                                <td class="w-20 line-height-35">{{$tyre->product->brand ? $tyre->product->brand->name : ""}} {{$tyre->product ? $tyre->product->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Type</th>
                                <td class="w-20 line-height-35">{{$tyre->type}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Width </th>
                                <td class="w-20 line-height-35">{{$tyre->width}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Aspect Ratio </th>
                                <td class="w-20 line-height-35">{{$tyre->aspect_ratio}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Diameter </th>
                                <td class="w-20 line-height-35">{{$tyre->diameter}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Currency</th>
                                <td class="w-20 line-height-35"> {{$tyre->currency ? $tyre->currency->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Price</th>
                                <td class="w-20 line-height-35">{{$tyre->currency ? $tyre->currency->symbol : ""}}{{number_format($tyre->rate,2)}}</td>
                            </tr>
                           
                         
                            <tr>
                                <th class="w-10 text-center line-height-35">Purchase Date</th>
                                <td class="w-20 line-height-35">{{$tyre->purchase_date}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Purchase Type</th>
                                <td class="w-20 line-height-35">{{$tyre->purchase_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Warrant Expiry Date</th>
                                <td class="w-20 line-height-35">{{$tyre->warranty_exp_date}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Useful Life</th>
                                <td class="w-20 line-height-35">
                                    @if ($tyre->life)
                                         {{$tyre->life}}Year(s)
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Residual Value</th>
                                <td class="w-20 line-height-35">
                                    @if ($tyre->residual_value)
                                    {{$tyre->currency ? $tyre->currency->symbol : ""}}{{number_format($tyre->residual_value,2)}}        
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Description</th>
                                <td class="w-20 line-height-35">{{$tyre->description}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Status</th>
                                <td class="w-20 line-height-35"><span class="badge bg-{{$tyre->status == 1 ? "success" : "danger"}}">{{$tyre->status == 1 ? "Unassigned" : "Assigned"}}</span></td>
                            </tr>
                        </tbody>
                    </table>
                   
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group pull-right mt-10" >
                           <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                            {{-- <button type="submit" wire:click="store({{$inspection->id}})" class="btn bg-success btn-wide btn-rounded" > <i class="fa fa-save"></i>Save</button> --}}
                        </div>
                    </div>
                    </div>

                <!-- /.section-title -->
            </div>
        </div>
        <!-- /.col-md-9 -->
    </div>
</div>
