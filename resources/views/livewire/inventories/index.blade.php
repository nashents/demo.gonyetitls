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
                                <a href="{{route('inventories.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Inventory</a>
                                <a href="" data-toggle="modal" data-target="#inventoryImportModal" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-upload"></i>Import</a>
                            </div>

                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="inventoriesTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Inventory#
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">Product
                                    </th>
                                    <th class="th-sm">Part#
                                    </th>
                                    <th class="th-sm">Serial#
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
                                @if ($inventories->count()>0)
                                <tbody>
                                    @foreach ($inventories as $inventory)
                                  <tr>
                                    <td>{{$inventory->inventory_number}}</td>
                                    <td>{{$inventory->purchase_date}}</td>
                                    <td>{{$inventory->product->brand ? $inventory->product->brand->name : ""}} {{$inventory->product ? $inventory->product->name : ""}}</td>
                                    <td>{{$inventory->part_number}}</td>
                                    <td>{{$inventory->serial_number}}</td>
                                    <td>{{$inventory->currency ? $inventory->currency->name : ""}}</td>
                                    <td>
                                        @if ($inventory->rate)
                                            {{$inventory->currency ? $inventory->currency->symbol : ""}}{{number_format($inventory->rate,2)}}  
                                        @endif
                                    </td>
                                    <td><span class="badge bg-{{$inventory->status == 1 ? "success" : "danger"}}">{{$inventory->status == 1 ? "Instore" : "Out Of stock"}}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('inventories.show',$inventory->id )}}"  ><i class="fa fa-eye color-default"></i> View</a></li>
                                                <li><a href="{{route('inventories.edit',$inventory->id )}}"  ><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#inventoryDeleteModal{{ $inventory->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('inventories.delete')
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

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="inventoryImportModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-upload"></i>Import Inventory Product(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form action="{{route('inventories.import')}}" method="POST" enctype="multipart/form-data">
                    {{ csrf_field() }}
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Upload Inventory Products Excel File</label>
                        <input type="file" class="form-control" name="file" placeholder="Upload Loading Points File" >
                        @error('file') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button  onClick="this.form.submit(); this.disabled=true; this.value='Sending…'; "  class="btn bg-success btn-wide btn-rounded"><i class="fa fa-upload"></i>Upload</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>



</div>

