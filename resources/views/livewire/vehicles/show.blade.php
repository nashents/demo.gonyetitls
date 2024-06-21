<div>
    <div class="row mt-30">
        @include('includes.messages')
        <div class="col-md-3">

            <div class="panel border-primary no-border border-3-top">
                <div class="panel-heading">
                    <div class="panel-title">
                      <center> <h5>{{$vehicle->vehicle_make ? $vehicle->vehicle_make->name : ""}} {{$vehicle->vehicle_model ? $vehicle->vehicle_model->name : ""}}</h5></center> 
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8 col-md-offset-2">
                            @php
                                $image = $vehicle->vehicle_images->first()
                            @endphp
                            @if ($image)
                            <img src="{{asset('images/uploads/'.$image->filename)}}" alt="Vehicle Avatar" class="img-responsive">
                            @else
                            <img src="{{asset('images/vehicle1.jpg')}}" alt="Vehicle Avatar" class="img-responsive">
                            @endif

                            {{-- <div class="text-center">
                                <button type="button" class="btn btn-primary btn-xs btn-labeled mt-10">Edit Picture<span class="btn-label btn-label-right"><i class="fa fa-pencil"></i></span></button>
                            </div> --}}
                        </div>
                    </div>
                </div>
            </div>

            <div class="panel border-primary no-border border-3-top">
                <div class="panel-heading">
                    <div class="panel-title">
                      <center><h5>{{ $vehicle->registration_number }} {{ $vehicle->fleet_number ? "| ".$vehicle->fleet_number : "" }}</h5></center>  
                    </div>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <table class="table table-striped">
                            <tbody>
                                <tr>
                                    <th>Trips</th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> {{$vehicle->trips->count()}}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Status</th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> <span class="badge bg-{{$vehicle->status == 1 ? "success" : "danger"}}">{{$vehicle->status == 1 ? "Active" : "Inactive"}}</span></small>
                                    </td>
                                </tr>
                                <tr>
                                    <th><a href="#" wire:click="odometer({{$vehicle->id}})"><i class="fa fa-tachometer-alt color-default"></i> Current Mileage</a></th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> {{$vehicle->mileage ? $vehicle->mileage."Kms" : ""}}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th><a href="#" wire:click="nextService({{$vehicle->id}})"><i class="fa fa-wrench color-default"></i> Next Service</a></th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> {{$vehicle->next_service ? $vehicle->next_service."Kms" : ""}}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th><a href="#" wire:click="fuelTankCapacity({{$vehicle->id}})"><i class="fas fa-gas-pump color-warning"></i> Tank Capacity</a></th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i>  {{$vehicle->fuel_tank_capacity ? $vehicle->fuel_tank_capacity."Litres" : ""}}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th><a href="#" wire:click="fuelTank({{$vehicle->id}})"><i class="fas fa-gas-pump color-warning"></i> Tank Balance </a></th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i>  {{$vehicle->fuel_balance ? $vehicle->fuel_balance."Litres" : ""}}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fuel Consumption</th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> {{$vehicle->fuel_consumption}}</small>
                                    </td>
                                </tr>
                                <tr>
                                    <th>Fuel Usage</th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> {{$total_usage}} Litres</small>
                                    </td>
                                </tr>
                                
                                <tr>
                                    <th>No Of Wheels</th>
                                    <td>
                                        <small class="color-success"><i class="fa fa-arrow-right"></i> {{$vehicle->no_of_wheels}}</small>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <!-- /.panel -->

            <div class="panel border-primary no-border border-3-top">
                <div class="panel-heading">
                    <div class="panel-title">
                        <h5>Vehicle Tags</h5>
                    </div>
                </div>
                <div class="panel-body p-20">
                    <span class="label label-success label-rounded label-bordered">{{$vehicle->vehicle_group ? $vehicle->vehicle_group->name : ""}}</span>
                    <span class="label label-danger label-rounded label-bordered">{{$vehicle->vehicle_type ? $vehicle->vehicle_type->name : ""}}</span>
                </div>
            </div>
            <!-- /.panel -->
        </div>
        <!-- /.col-md-3 -->

        <div class="col-md-9">

            <div class="row mb-30">

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat-2 bg-success" href="#">
                        <div class="stat-content">
                            <span>{{$vehicle->mileage ? $vehicle->mileage."Kms" : ""}}</span>
                        </div>
                        <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> MILEAGE</span>
                    </a>
                    <!-- /.dashboard-stat-2 -->
                </div>
    
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat-2 bg-primary" href="#">
                        <div class="stat-content">
    
                            <span class="name">{{($vehicle->next_service ? $vehicle->next_service."Kms" : "")}}</span>
                        </div>
                        <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> NEXT SERVICE</span>
                    </a>
                    <!-- /.dashboard-stat-2 -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->
    
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat-2 bg-danger" href="#">
                        <div class="stat-content">
                            <span class="name">{{ucfirst($vehicle->fuel_type)}}</span>
                        </div>
                        <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> FUEL TYPE</span>
                    </a>
                    <!-- /.dashboard-stat-2 -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->
    
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat-2 bg-warning" href="#">
                        <div class="stat-content">
                            <span >{{$vehicle->fuel_consumption}}</span>
                        </div>
                        <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> FUEL CONSUMPTION</span>
                    </a>
                    <!-- /.dashboard-stat-2 -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->
    
              
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->
    
            </div>
            <!-- /.row -->

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Vehicle Details</a></li>
                <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
                <li role="presentation"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
                <li role="presentation"><a href="#fitness" aria-controls="fitness" role="tab" data-toggle="tab">Reminders</a></li>
                <li role="presentation"><a href="#tyres" aria-controls="tyres" role="tab" data-toggle="tab">Tyres</a></li>
                <li role="presentation"><a href="#trips" aria-controls="trips" role="tab" data-toggle="tab">Trips</a></li>
                <li role="presentation"><a href="#cashflow" aria-controls="cashflow" role="tab" data-toggle="tab">Cashflow</a></li>
                <li role="presentation"><a href="#usage" aria-controls="usage" role="tab" data-toggle="tab">Fuel</a></li>
                <li role="presentation"><a href="#logs" aria-controls="logs" role="tab" data-toggle="tab">Logs</a></li>
                <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab">Service</a></li>
            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">

                        <tbody class="text-center line-height-35 ">
                            <tr>
                                <th class="w-10 text-center line-height-35">Transporter</th>
                                <td class="w-20 line-height-35"> {{$vehicle->transporter ? $vehicle->transporter->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Registration #</th>
                                <td class="w-20 line-height-35"> {{$vehicle->registration_number}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Make</th>
                                <td class="w-20 line-height-35">{{ucfirst($vehicle->vehicle_make ? $vehicle->vehicle_make->name : "")}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Model</th>
                                <td class="w-20 line-height-35">{{ucfirst($vehicle->vehicle_model ? $vehicle->vehicle_model->name : "")}}</td>
                            </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Chasis #</th>
                                    <td class="w-20 line-height-35">{{$vehicle->chasis_number}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Engine #</th>
                                    <td class="w-20 line-height-35">{{$vehicle->engine_number}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Year</th>
                                    <td class="w-20 line-height-35">{{$vehicle->year}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Acquisition Date</th>
                                    <td class="w-20 line-height-35">{{$vehicle->start_date}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Dispose Date</th>
                                    <td class="w-20 line-height-35">{{$vehicle->end_date}}</td>
                                </tr>

                                <tr>
                                    <th class="w-10 text-center line-height-35">Color</th>
                                    <td class="w-20 line-height-35">{{$vehicle->color}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">No of wheels</th>
                                    <td class="w-20 line-height-35">{{$vehicle->no_of_wheels}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Manufatured By</th>
                                    <td class="w-20 line-height-35">{{$vehicle->manufacturer}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Condition</th>
                                    <td class="w-20 line-height-35">{{$vehicle->condition}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Vehicle Type</th>
                                    <td class="w-20 line-height-35"> {{$vehicle->vehicle_type ? $vehicle->vehicle_type->name : ""}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Vehicle Group</th>
                                    <td class="w-20 line-height-35"> {{$vehicle->vehicle_group ? $vehicle->vehicle_group->name : ""}}</td>
                                </tr>

                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="documents">
                    @livewire('documents.index', ['id' => $vehicle->id,'category'=>'vehicle'])
                </div>
                <div role="tabpanel" class="tab-pane" id="images">

                    @livewire('vehicles.images', ['id' => $vehicle->id])
                </div>
                <div role="tabpanel" class="tab-pane" id="fitness">
             @livewire('fitnesses.index', ['id' => $vehicle->id,'category' => "Vehicle"])
                </div>
                <div role="tabpanel" class="tab-pane" id="tyres">
                    <table id="tyre_assignmentsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >
                            <th class="th-sm">Tyre#
                            </th>
                            <th class="th-sm">Name
                            </th>
                            <th class="th-sm">Serial#
                            </th>
                            <th class="th-sm">Specifications
                            </th>
                            <th class="th-sm">Axle
                            </th>
                            <th class="th-sm">Position
                            </th>
                            <th class="th-sm">Odometer
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @if (isset($tyre_assignments))
                            @foreach ($tyre_assignments as $tyre_assignment)
                          <tr>
                            <td>{{$tyre_assignment->tyre_detail ? $tyre_assignment->tyre_detail->tyre_number : ""}}</td>
                            <td>
                                @if ($tyre_assignment->tyre_detail)
                                {{$tyre_assignment->tyre_detail->product ? $tyre_assignment->tyre_detail->product->name : ""}}
                                @endif
                            </td>
                            <td>{{$tyre_assignment->tyre_detail ? $tyre_assignment->tyre_detail->serial_number : ""}}</td>
                            <td>{{$tyre_assignment->tyre_detail ? $tyre_assignment->tyre_detail->width : ""}} / {{$tyre_assignment->tyre_detail ? $tyre_assignment->tyre_detail->aspect_ratio : ""}} R {{$tyre_assignment->tyre_detail ? $tyre_assignment->tyre_detail->diameter : ""}}</td>
                            <td>{{$tyre_assignment->position}}</td>
                            <td>{{$tyre_assignment->axle}}</td>
                            <td>{{$tyre_assignment->starting_odometer}}</td>
                          </tr>
                          @endforeach
                          @else
                          <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                          @endif
                        </tbody>


                      </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="trips">
                    <table id="tripsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >
                            <th class="th-sm">Trip#
                            </th>
                            <th class="th-sm">Driver
                            </th>
                            <th class="th-sm">Customer
                            </th>
                            <th class="th-sm">From
                            </th>
                            <th class="th-sm">To
                            </th>
                            <th class="th-sm">Currency
                            </th>
                            <th class="th-sm">Turnover
                            </th>
                            <th class="th-sm">Status
                            </th>
                          </tr>
                        </thead>
                        <tbody>
    
                            @foreach ($trips as $trip)
                            @php
                            $to = App\Models\Destination::find($trip->to);
                            $from = App\Models\Destination::find($trip->from);
                            @endphp
                          <tr>
                            
                            <td><a href="{{ route('trips.show',$trip->id) }}" style="color: blue">{{$trip->trip_number}}</a></td>
                            <td>
                                @if ($trip->driver)
                                    {{$trip->driver->employee ? $trip->driver->employee->name : ""}} {{$trip->driver->employee ? $trip->driver->employee->surname : ""}}        
                                @endif
                            </td>
                            <td>{{$trip->customer ? $trip->customer->name : ""}}</td>
                            <td>
                                @if ($from)
                                    {{$from->country ? $from->country->name : ""}} {{$from->city}}        
                                @endif
                            </td>
                            <td>
                                @if ($to)
                                    {{$to->country ? $to->country->name : ""}} {{$to->city}}        
                                @endif
                            </td>
                            <td>{{$trip->currency ? $trip->currency->name : ""}}</td>
                            <td>
                                @if ($trip->turnover)
                                {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover,2)}}        
                                @endif
                            </td>
                            @if ($trip->trip_status == "Offloaded")
                            <td class="table-success"><span class="label label-success label-wide">{{$trip->trip_status}}</span></td>
                            @elseif($trip->trip_status == "Scheduled")
                            <td class="table-warning" ><span class="label label-warning label-wide">{{$trip->trip_status}}</span></td>
                            @elseif($trip->trip_status == "Loading Point")
                            <td class="table-gray" ><span class="label label-gray label-wide">{{$trip->trip_status}}</span></td>
                            @elseif($trip->trip_status == "Loaded")
                            <td class="table-info"><span class="label label-info label-wide">{{$trip->trip_status}}</span></td>
                            @elseif($trip->trip_status == "InTransit")
                            <td class="table-primary"><span class="label label-primary label-wide">{{$trip->trip_status}}</span></td>
                            @elseif($trip->trip_status == "OnHold")
                            <td class="table-danger"><span class="label label-danger label-wide">{{$trip->trip_status}}</span></td>
                            @elseif($trip->trip_status == "Offloading Point")
                            <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                            @endif
                          </tr>
                          @endforeach
                        </tbody>
    
    
                      </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="cashflow">
                    <table id="cashflowsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >

                            <th class="th-sm">Date
                            </th>
                            <th class="th-sm">Type
                            </th>
                            <th class="th-sm">Category
                            </th>
                            <th class="th-sm">Currency
                            </th>
                            <th class="th-sm">Amount
                            </th>

                            <th class="th-sm">Comments
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($cashflows as $cashflow)
                          <tr>
                            <td>{{$cashflow->created_at}}</td>
                            <td> <span class="label label-{{$cashflow->type == "Income" ? "success" : "danger"}} label-rounded">{{$cashflow->type}}</span></td>
                            @if ($cashflow->category == "Fuel")
                            <td> <span class="label label-primary label-rounded">{{$cashflow->category}}</span></td>
                            @elseif($cashflow->category == "Tyre")
                            <td> <span class="label label-info label-rounded">{{$cashflow->category}}</span></td>
                            @elseif($cashflow->category == "Trip")
                            <td> <span class="label label-default label-rounded">{{$cashflow->category}}</span></td>
                            @endif
                            <td>{{$cashflow->currency? $cashflow->currency->name : ""}}</td>
                            <td>{{$cashflow->amount}}</td>
                            <td>{{$cashflow->comments}}</td>
                          </tr>
                          @endforeach
                        </tbody>


                      </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="usage">
                    <table id="usageTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >

                            <th class="th-sm">Date
                            </th>
                            <th class="th-sm">Mileage
                            </th>
                            <th class="th-sm">Fuel Type
                            </th>
                            <th class="th-sm">Quantity
                            </th>
                            <th class="th-sm">Station
                            </th>
                            <th class="th-sm">Amount
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @foreach ($usages as $usage)
                          <tr>
                            <td>{{$usage->date}}</td>
                            <td>{{$usage->odometer}}</td>
                            <td>{{$usage->container ? $usage->container->fuel_type : ""}}</td>
                            <td>{{$usage->quantity}}</td>
                            <td>{{$usage->container ? $usage->container->name : ""}}</td>
                            <td>{{$usage->amount}}</td>
                            <td>{{$usage->currency ? $usage->currency->name : ""}}</td>
                          </tr>
                          @endforeach
                        </tbody>


                      </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="logs">
                    <table id="logsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >
                            <th class="th-sm">Log#
                            </th>
                            <th class="th-sm">Employee
                            </th>
                            <th class="th-sm">From
                            </th>
                            <th class="th-sm">To
                            </th>
                            <th class="th-sm">Departure
                            </th>
                            <th class="th-sm">Starting Mileage
                            </th>
                            <th class="th-sm">Arrival
                            </th>
                            <th class="th-sm">Ending Mileage
                            </th>
                            <th class="th-sm">Distance
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @if (isset($logs))
                            @foreach ($logs as $log)
                          <tr>
                            <td>{{$log->log_number}}</td>
                            <td>{{$log->employee ? $log->employee->name : "" }} {{$log->employee ? $log->employee->surname : "" }}</td>
                            <td>{{$log->from}} </td>
                            <td>{{$log->to}} </td>
                            <td>{{$log->departure_datetime}} </td>
                            <td>
                                @if ($log->starting_mileage)
                                {{$log->starting_mileage}}Kms 
                                @endif
                               </td>
                            <td>{{$log->arrival_datetime}} </td>
                            <td>
                                @if ($log->ending_mileage)
                                {{$log->ending_mileage}}Kms
                                @endif
                            </td>
                            <td>
                                @if ($log->distance)
                                {{$log->distance}}Kms
                                @endif
                            </td>
                          </tr>
                          @endforeach
                          @else
                          <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                          @endif
                        </tbody>
    
    
                      </table>
                </div>

                <div role="tabpanel" class="tab-pane" id="service">
                    <table id="bookingsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >
                            <th class="th-sm">Booking#
                            </th>
                            <th class="th-sm">Mechanic
                            </th>
                            <th class="th-sm">Service Type
                            </th>
                            <th class="th-sm">Date
                            </th>
                            <th class="th-sm">Station
                            </th>
                            <th class="th-sm">Odometer
                            </th>
                            <th class="th-sm">Problem
                            </th>
                            <th class="th-sm">Status
                            </th>
                          </tr>
                        </thead>
                        <tbody>
                            @if (isset($vehicle->bookings))
                            @foreach ($vehicle->bookings as $booking)
                          <tr>
                            <td>{{$booking->booking_number}}</td>
                            <td>{{$booking->employee ? $booking->employee->name : ""}} {{$booking->employee ? $booking->employee->surname : ""}}</td>
                            <td>{{$booking->service_type ? $booking->service_type->name : ""}}</td>
                            <td>{{$booking->in_date}} {{$booking->in_time}}</td>
                            <td>{{$booking->station}}</td>
                            <td>
                                @if ($booking->odometer)
                                {{$booking->odometer}}Kms        
                                @endif
                            </td>
                            <td>{{$booking->description}}</td>
                            <td><span class="badge bg-{{$booking->status == 1 ? "success" : "warning"}}">{{$booking->status == 1 ? "Closed" : "Open"}}</span></td>
                          </tr>
                          @endforeach
                          @else
                          <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                          @endif
                        </tbody>
    
    
                      </table>
                </div>

                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group pull-right mt-10" >
                           <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    </div>
                <!-- /.section-title -->
            </div>
        </div>
        <!-- /.col-md-9 -->
    </div>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="fuelTankCapacityModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-edit"></i> Update Fuel Tank Capacity <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="updateFuelTankCapacity()" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Fuel Tank Capacity in Litres<span class="required" style="color: red">*</span></label>
                        <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="fuel_tank_capacity" placeholder="Enter Fuel Tank Capacity in Litres" required />
                        @error('fuel_tank_capacity') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="fuelTankModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-edit"></i> Update Fuel Level <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="updateFuelTank()" >
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Fuel Level in Litres<span class="required" style="color: red">*</span></label>
                        <input type="number" step="any" min="1" max="{{ $vehicle->fuel_tank_capacity }}" class="form-control" wire:model.debounce.300ms="fuel_balance" {{ $vehicle->fuel_tank_capacity ? "" : "disabled" }} placeholder="Enter Fuel Level" required />
                        <small style="color: red">{{ $vehicle->fuel_tank_capacity ? "" : "Please set vehicle fuel tank capacity first before setting vehicle fuel level"  }}</small>
                        @error('fuel_balance') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="odometerModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Update Vehicle Mileage <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="updateOdometer()" >
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Vehicle Mileage<span class="required" style="color: red">*</span></label>
                        <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="mileage" placeholder="Enter Vehicle Mileage" required />
                        @error('mileage') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="nextServiceModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Update Next Service Mileage <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="updateNextService()" >
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Next Service Mileage<span class="required" style="color: red">*</span></label>
                        <input type="number" step="any" min="{{ $vehicle->mileage }}" class="form-control" wire:model.debounce.300ms="next_service" {{ $vehicle->mileage ? "" : "disabled" }} placeholder="Enter vehicle Next Service Mileage" required />
                        <small style="color: red">{{ !isset($vehicle->mileage) ? "Please set vehicle mileage first before setting vehicle next mileage" : ""  }}</small>
                        @error('next_service') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="cargoModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-building-o"></i> Add Cargo <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Name</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Name" >
                                @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="issue">Issue</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="issue" placeholder="Enter Issue Date" >
                                @error('issue') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="expiry">Expiry</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="expiry" placeholder="Enter Expiry Date" >
                                @error('expiry') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>


                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>
</div>
