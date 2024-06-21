<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <div class="panel">
                        <div class="panel-heading">
                            <div>
                                @include('includes.messages')
                            </div>
                            <div class="panel-title">
                                <a href="{{route('assets.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Asset</a>
                            </div>

                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="assetsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Asset#
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Product
                                    </th>
                                    <th class="th-sm">SN#
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Price
                                    </th>
                                    <th class="th-sm">Status
                                    </th>
                                    <th class="th-sm">Action
                                    </th>
                                  </tr>
                                </thead>
                                @if ($assets->count()>0)
                                <tbody>
                                    @foreach ($assets as $asset)
                                  <tr>
                                    <td>{{$asset->asset_number}}</td>
                                    <td>{{$asset->purchase_date}}</td>
                                    <td>{{$asset->product ? $asset->product->name : ""}} {{$asset->product ? $asset->product->model : ""}} </td>
                                    <td>{{$asset->serial_number}}</td>
                                    <td>{{$asset->currency ? $asset->currency->name : ""}}</td>
                                    <td>
                                        @if ($asset->rate)
                                        {{$asset->currency ? $asset->currency->symbol : ""}}{{number_format($asset->rate,2)}}        
                                        @endif
                                    </td>
                                    <td>
                                        @if ($asset->asset_assignments->count()>0)
                                        <a href="{{ route('asset_assignments.show',$asset->asset_assignments->first()->id) }}" target="_blank">  <span class="badge bg-{{$asset->status == 1 ? "success" : "danger"}}">{{$asset->status == 1 ? "Instore" : "Dispatched"}}</span></a>
                                       
                                        @else   
                                        <span class="badge bg-{{$asset->status == 1 ? "success" : "danger"}}">{{$asset->status == 1 ? "Instore" : "Dispatched"}}</span>
                                        @endif
                                    </td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('assets.show',$asset->id )}}"  ><i class="fa fa-eye color-default"></i> View</a></li>
                                                <li><a href="{{route('assets.edit',$asset->id )}}"  ><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#assetDeleteModal{{ $asset->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('assets.delete')
                                </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                                    <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                                 @endif
                              </table>

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>





</div>

