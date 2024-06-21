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
                                                    <li><a href="#" wire:click="restore({{$vehicle->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
                                                </ul>
                                            </div>
                                           

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
