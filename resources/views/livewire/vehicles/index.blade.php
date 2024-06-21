<div>
    <style>
        .modal-lg {
        max-width: 80%;
    }
    </style>
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
                                    <a href="{{route('vehicles.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Vehicle</a>
                                    <a href="" data-toggle="modal" data-target="#vehiclesImportModal" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-upload"></i>Import</a>
                                    <a href="#" wire:click="exportVehiclesExcel()"  class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>Excel</a>
                                    <a href="#" wire:click="exportVehiclesCSV()" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>CSV</a>
                                    <a href="#" wire:click="exportVehiclesPDF()" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>PDF</a>
                                </div>
                             

                            </div>
                            <div class="panel-body p-20" style="overflow-x:auto; width:100%; height:100%;">

                                <table id="vehiclesTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                    <thead >
                                        <tr>
                                            <th class="th-sm">Vehicle#
                                            </th>
                                            <th class="th-sm">Fleet#
                                            </th>
                                            <th class="th-sm">Transporter
                                            </th>
                                            <th class="th-sm">Make
                                            </th>
                                            <th class="th-sm">VRN
                                            </th>
                                            <th class="th-sm">Mileage
                                            </th>
                                            <th class="th-sm"><i class="fa fa-arrow-right" aria-hidden="true"></i> Service
                                            </th>
                                            <th class="th-sm">Fitness
                                            </th>
                                            <th class="th-sm">Availability
                                            </th>
                                            <th class="th-sm">Actions
                                            </th>
                                          </tr>
                                    </thead>
                                    @if ($vehicles->count()>0)
                                    <tbody>
                                        @foreach ($vehicles as $vehicle)
                                      <tr>
                                        <td>{{ucfirst($vehicle->vehicle_number)}}</td>
                                        <td>{{ucfirst($vehicle->fleet_number)}}</td>
                                        <td>{{$vehicle->transporter ? $vehicle->transporter->name : ""}}</td>
                                        <td>{{ucfirst($vehicle->vehicle_make ? $vehicle->vehicle_make->name : "")}} {{ucfirst($vehicle->vehicle_model ? $vehicle->vehicle_model->name : "")}}</td>
                                        <td>{{ucfirst($vehicle->registration_number)}}</td>
                                        <td>{{$vehicle->mileage ? $vehicle->mileage."Kms" : ""}}</td>
                                        <td>{{$vehicle->next_service ? $vehicle->next_service."Kms" : ""}}</td>
                                        <td><span class="badge bg-{{$vehicle->service == 0 ? "success" : "danger"}}">{{$vehicle->service == 0 ? "Fit for use" : "In Service"}}</span></td>
                                        <td><span class="badge bg-{{$vehicle->status == 1 ? "success" : "danger"}}">{{$vehicle->status == 1 ? "Available" : "Unavailable"}}</span></td>
                                        <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('vehicles.show', $vehicle->id)}}"><i class="fa fa-eye color-default"></i>View</a></li>
                                                    <li><a href="{{route('vehicles.edit', $vehicle->id)}}"><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#vehicleDeleteModal{{$vehicle->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                    @if ($vehicle->status == 1)
                                                    <li><a href="{{route('vehicles.deactivate', $vehicle->id)}}"  ><i class="fa fa-toggle-on color-danger"></i>Deactivate</a></li>
                                                    @else
                                                    <li><a href="{{route('vehicles.activate', $vehicle->id)}}"  ><i class="fa fa-toggle-off color-success"></i>Activate</a></li>
                                                    @endif
                                                    @if ($vehicle->archive == 0)
                                                    <li><a href="{{route('vehicles.archive', $vehicle->id)}}"  ><i class="fa fa-archive color-primary"></i>Archive</a></li>
                                                    @endif

                                                </ul>
                                            </div>
                                            @include('vehicles.delete')

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

        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="vehiclesImportModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fa fa-upload"></i>Import Vehicles <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form action="{{route('vehicles.import')}}" method="POST" enctype="multipart/form-data">
                        {{ csrf_field() }}
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Upload Vehicle(s) Excel File</label>
                            <input type="file" class="form-control" name="file" placeholder="Upload Vehicle File" >
                            @error('file') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            <button onClick="this.form.submit(); this.disabled=true; this.value='Sending…'; " class="btn bg-success btn-wide btn-rounded"><i class="fa fa-upload"></i>Upload</button>
                        </div>
                        <!-- /.btn-group -->
                    </div>
                </form>
                </div>
            </div>
        </div><!-- Modal -->


    </div>
