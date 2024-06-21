<div>
    <div class="col-md-3">

        <div class="panel border-primary no-border border-3-top">
            <div class="panel-heading">
                <div class="panel-title">
                    <center><h5>{{$horse->horse_make ? $horse->horse_make->name : ""}} {{$horse->horse_model ? $horse->horse_model->name : ""}}</h5></center>
                  
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <div class="col-md-8 col-md-offset-2">
                        @php
                            $image = $horse->horse_images->first()
                        @endphp
                        @if ($image)
                        <img src="{{asset('images/uploads/'.$image->filename)}}" alt="horse Avatar" class="img-responsive">
                        @else
                        <img src="{{asset('images/horse1.png')}}" alt="Horse Avatar" class="img-responsive">
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="panel border-primary no-border border-3-top">
            <div class="panel-heading">
                <div class="panel-title">
                    <center><h5>{{ $horse->registration_number ? $horse->registration_number." | " : "" }} {{ $horse->fleet_number }}</h5></center>
                </div>
            </div>
            <div class="panel-body">
                <div class="row">
                    <table class="table table-striped">
                        <tbody>
                            <tr>
                                <th>Trips</th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i> {{$horse->trips->count()}}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i> <span class="badge bg-{{$horse->status == 1 ? "success" : "danger"}}">{{$horse->status == 1 ? "Active" : "Inactive"}}</span></small>
                                </td>
                            </tr>
                            <tr>
                                <th><a href="#" wire:click="odometer({{$horse->id}})"><i class="fa fa-tachometer-alt color-default"></i> Current Mileage</a></th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i> {{$horse->mileage ? $horse->mileage."Kms" : ""}}</small>
                                </td>
                            </tr>
                            <tr>
                                <th><a href="#" wire:click="nextService({{$horse->id}})"><i class="fa fa-wrench color-default"></i> Next Service</a></th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i> {{$horse->next_service ? $horse->next_service."Kms" : ""}}</small>
                                </td>
                            </tr>
                            <tr>
                                <th><a href="#" wire:click="fuelTankCapacity({{$horse->id}})"><i class="fas fa-gas-pump color-warning"></i> Tank Capacity</a></th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i>  {{$horse->fuel_tank_capacity ? $horse->fuel_tank_capacity."Litres" : ""}}</small>
                                </td>
                            </tr>
                            <tr>
                                <th><a href="#" wire:click="fuelTank({{$horse->id}})"><i class="fas fa-gas-pump color-warning"></i> Tank Balance </a></th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i>  {{$horse->fuel_balance ? $horse->fuel_balance."Litres" : ""}}</small>
                                </td>
                            </tr>
                            <tr>
                                <th>Fuel Consumption</th>
                                <td>
                                    <small class="color-success"><i class="fa fa-arrow-right"></i> {{$horse->fuel_consumption}}</small>
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
                                    <small class="color-success"><i class="fa fa-arrow-right"></i> {{$horse->no_of_wheels}}</small>
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
                    <h5>Horse Tags</h5>
                </div>
            </div>
            <div class="panel-body p-20">
                <span class="label label-success label-rounded label-bordered">{{$horse->horse_group ? $horse->horse_group->name : ""}}</span>
                <span class="label label-danger label-rounded label-bordered">{{$horse->horse_type ? $horse->horse_type->name : ""}}</span>
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
                        <span>{{$horse->mileage ? $horse->mileage."Kms" : ""}}</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> MILEAGE</span>
                </a>
                <!-- /.dashboard-stat-2 -->
            </div>

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-primary" href="#">
                    <div class="stat-content">

                        <span class="name">{{($horse->next_service ? $horse->next_service."Kms" : "")}}</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> NEXT SERVICE</span>
                </a>
                <!-- /.dashboard-stat-2 -->
            </div>
            <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-danger" href="#">
                    <div class="stat-content">
                        <span class="name">{{ucfirst($horse->fuel_type)}}</span>
                    </div>
                    <span class="stat-footer"><i class="fa fa-arrow-up color-success"></i> FUEL TYPE</span>
                </a>
                <!-- /.dashboard-stat-2 -->
            </div>
            <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                <a class="dashboard-stat-2 bg-warning" href="#">
                    <div class="stat-content">
                        <span >{{$horse->fuel_consumption}}</span>
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
            <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Horse Details</a></li>
            <li role="presentation" ><a href="#mechanical" aria-controls="mechanical" role="tab" data-toggle="tab">Mechanical Details</a></li>
            <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
            <li role="presentation"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Images</a></li>
            <li role="presentation"><a href="#fitness" aria-controls="fitness" role="tab" data-toggle="tab">Reminders</a></li>
            <li role="presentation"><a href="#tyres" aria-controls="tyres" role="tab" data-toggle="tab">Tyres</a></li>
            <li role="presentation"><a href="#trips" aria-controls="trips" role="tab" data-toggle="tab">Trips</a></li>
            <li role="presentation"><a href="#cashflow" aria-controls="cashflow" role="tab" data-toggle="tab">Cashflow</a></li>
            <li role="presentation"><a href="#orders" aria-controls="orders" role="tab" data-toggle="tab">Fuel</a></li>
            <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab">Service</a></li>
        </ul>
        <div class="tab-content bg-white p-15">
            <div role="tabpanel" class="tab-pane active" id="basic">
                <table class="table table-striped">

                    <tbody class="text-center line-height-35 ">

                        <tr>
                            <th class="w-10 text-center line-height-35">Transporter</th>
                            <td class="w-20 line-height-35"> {{$horse->transporter ? $horse->transporter->name : ""}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Horse#</th>
                            <td class="w-20 line-height-35"> {{$horse->horse_number}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Fleet#</th>
                            <td class="w-20 line-height-35"> {{$horse->fleet_number}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Registration#</th>
                            <td class="w-20 line-height-35"> {{$horse->registration_number}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Make</th>
                            <td class="w-20 line-height-35">{{ucfirst($horse->horse_make ? $horse->horse_make->name : "")}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Model</th>
                            <td class="w-20 line-height-35">{{ucfirst($horse->horse_model ? $horse->horse_model->name : "")}}</td>
                        </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Chasis#</th>
                                <td class="w-20 line-height-35">{{$horse->chasis_number}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Engine#</th>
                                <td class="w-20 line-height-35">{{$horse->engine_number}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Year</th>
                                <td class="w-20 line-height-35">{{$horse->year}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Acquisition Date</th>
                                <td class="w-20 line-height-35">{{$horse->start_date}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Dispose Date</th>
                                <td class="w-20 line-height-35">{{$horse->end_date}}</td>
                            </tr>
                            
                            <tr>
                                <th class="w-10 text-center line-height-35">Color</th>
                                <td class="w-20 line-height-35">{{$horse->color}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">ManufaturedBy</th>
                                <td class="w-20 line-height-35">{{$horse->manufacturer}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Condition</th>
                                <td class="w-20 line-height-35">{{$horse->condition}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Fuel Measurement</th>
                                <td class="w-20 line-height-35">{{$horse->fuel_measurement}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Horse Type</th>
                                <td class="w-20 line-height-35"> {{$horse->horse_type ? $horse->horse_type->name : ""}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Horse Group</th>
                                <td class="w-20 line-height-35"> {{$horse->horse_group ? $horse->horse_group->name : ""}}</td>
                            </tr>

                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane " id="mechanical">
                <table class="table table-striped">

                    <tbody class="text-center line-height-35 ">

                        <tr>
                            <th class="w-10 text-center line-height-35">Engine Type</th>
                            <td class="w-20 line-height-35"> {{$horse->engine_type }}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Engine#</th>
                            <td class="w-20 line-height-35"> {{$horse->engine_number}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Engine CPL</th>
                            <td class="w-20 line-height-35"> {{$horse->engine_cpl}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Gearbox Type </th>
                            <td class="w-20 line-height-35"> {{$horse->gearbox_type}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Differential Type</th>
                            <td class="w-20 line-height-35">{{$horse->differential_type}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Differential Ratio</th>
                            <td class="w-20 line-height-35">{{$horse->differential_ratio}}</td>
                        </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Comppressor Type</th>
                                <td class="w-20 line-height-35">{{$horse->compressor_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Compressor Size</th>
                                <td class="w-20 line-height-35">{{$horse->compressor_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Universal J Size</th>
                                <td class="w-20 line-height-35">{{$horse->universal_j_size}}</td>
                            </tr>

                            <tr>
                                <th class="w-10 text-center line-height-35">Rear Spring Type</th>
                                <td class="w-20 line-height-35">{{$horse->rear_spring_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Front Spring Type</th>
                                <td class="w-20 line-height-35">{{$horse->front_spring_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Flange Size</th>
                                <td class="w-20 line-height-35">{{$horse->flange_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Steering Box Type</th>
                                <td class="w-20 line-height-35"> {{$horse->steering_box_type }}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Cab Type</th>
                                <td class="w-20 line-height-35"> {{$horse->cab_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Air Dryer System</th>
                                <td class="w-20 line-height-35">{{$horse->air_dryer_system}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">5th Wheel Type</th>
                                <td class="w-20 line-height-35">{{$horse->fifth_wheel_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Starter Type</th>
                                <td class="w-20 line-height-35">{{$horse->starter_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Starter Size</th>
                                <td class="w-20 line-height-35">{{$horse->starter_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Alternator Type</th>
                                <td class="w-20 line-height-35">{{$horse->alternator_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Alternator Size</th>
                                <td class="w-20 line-height-35">{{$horse->alternator_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Fuel Filtering Type</th>
                                <td class="w-20 line-height-35">{{$horse->fuel_filtering_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Kingpin Type</th>
                                <td class="w-20 line-height-35">{{$horse->king_pin_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Fan Belt Size</th>
                                <td class="w-20 line-height-35">{{$horse->fan_belt_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Water Pump Belt Type</th>
                                <td class="w-20 line-height-35">{{$horse->water_pump_belt_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Water Pump Belt Size</th>
                                <td class="w-20 line-height-35">{{$horse->water_pump_belt_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Engine Mounting Type</th>
                                <td class="w-20 line-height-35">{{$horse->engine_mounting_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Steering Reservoir</th>
                                <td class="w-20 line-height-35">{{$horse->braking_system_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Braking System Type</th>
                                <td class="w-20 line-height-35">{{$horse->braking_system_type}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Clutch Size</th>
                                <td class="w-20 line-height-35">{{$horse->clutch_size}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Tnak HRS</th>
                                <td class="w-20 line-height-35">{{$horse->tnak_rhs}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Battery Size</th>
                                <td class="w-20 line-height-35">{{$horse->battery_size}}</td>
                            </tr>

                    </tbody>
                </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="documents">
                @livewire('documents.index', ['id' => $horse->id,'category'=>'horse'])
            </div>
            <div role="tabpanel" class="tab-pane" id="images">

                @livewire('horses.images', ['id' => $horse->id])
            </div>
            <div role="tabpanel" class="tab-pane" id="fitness">
                 @livewire('fitnesses.index', ['id' => $horse->id, 'category' => "Horse"])
            </div>
            <div role="tabpanel" class="tab-pane" id="tyres">
                <table id="tyre_assignmentsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                    <thead >
                        <th class="th-sm">Tyre#
                        </th>
                        <th class="th-sm">Product
                        </th>
                        <th class="th-sm">Serial#
                        </th>
                        <th class="th-sm">Specifications
                        </th>
                        <th class="th-sm">Axle
                        </th>
                        <th class="th-sm">Position
                        </th>
                        <th class="th-sm">Starting Mileage
                        </th>
                        <th class="th-sm">Ending Mileage
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        @if (isset($tyre_assignments))
                        @foreach ($tyre_assignments as $tyre_assignment)
                      <tr>
                        <td>{{$tyre_assignment->tyre ? $tyre_assignment->tyre->tyre_number : ""}}</td>
                        <td>
                            @if ($tyre_assignment->tyre)
                            {{$tyre_assignment->tyre->product ? $tyre_assignment->tyre->product->name : ""}} {{$tyre_assignment->tyre->product->brand ? $tyre_assignment->tyre->product->brand->name : ""}}
                            @endif
                        </td>
                        <td>{{$tyre_assignment->tyre ? $tyre_assignment->tyre->serial_number : ""}}</td>
                        <td>{{$tyre_assignment->tyre ? $tyre_assignment->tyre->width : ""}} / {{$tyre_assignment->tyre ? $tyre_assignment->tyre->aspect_ratio : ""}} R {{$tyre_assignment->tyre ? $tyre_assignment->tyre->diameter : ""}}</td>
                        <td>{{$tyre_assignment->position}}</td>
                        <td>{{$tyre_assignment->axle}}</td>
                        <td>{{$tyre_assignment->starting_odometer}}Kms</td>
                        <td>{{$tyre_assignment->ending_odometer}}Kms</td>
                      </tr>
                      @endforeach
                      @else
                      <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                      @endif
                    </tbody>


                  </table>
            </div>
            <div role="tabpanel" class="tab-pane" id="trips">
                @livewire('horses.trips', ['id' => $horse->id])
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
                        <th class="th-sm">Sub Category
                        </th>
                        <th class="th-sm">Currency
                        </th>
                        <th class="th-sm">Amount
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        @php
                            $cash_flows = $horse->cash_flows
                        @endphp
                        @if (isset($cash_flows))
                        @foreach ($cash_flows as $cashflow)
                      <tr>
                        <td>{{$cashflow->created_at}}</td>
                        <td> <span class="label label-{{$cashflow->type == "Direct" ? "success" : "danger"}} label-rounded">{{$cashflow->type}}</span></td>
                        @if ($cashflow->category == "Fuel")
                        <td> <span class="label label-primary label-rounded">{{$cashflow->category}}</span></td>
                        @elseif($cashflow->category == "Tyre")
                        <td> <span class="label label-info label-rounded">{{$cashflow->category}}</span></td>
                        @elseif($cashflow->category == "Trip")
                        <td> <span class="label label-default label-rounded">{{$cashflow->category}}</span>
                            @if ($cashflow->trip)
                            <a href="{{ route('trips.show',$cashflow->trip->id) }}" style=" color:blue">{{ $cashflow->trip ? $cashflow->trip->trip_number : "" }}</a>        
                            @endif
                         </td>
                        @endif
                        <td> <span class="label label-{{$cashflow->sub_type == "Expense" ? "danger" : "success"}} label-rounded">{{$cashflow->sub_type}}</span></td>
                        <td>{{$cashflow->currency ? $cashflow->currency->name : ""}}</td>
                        <td>
                            @if ($cashflow->amount)
                                 {{$cashflow->currency ? $cashflow->currency->name : ""}}{{number_format($cashflow->amount,2)}}        
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
            <div role="tabpanel" class="tab-pane" id="orders">
                <table id="usageTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                    <thead >
                        <th class="th-sm">Station
                        </th>
                        <th class="th-sm">Date
                        </th>
                        <th class="th-sm">Mileage
                        </th>
                        <th class="th-sm">Fuel Type
                        </th>
                        <th class="th-sm">Quantity
                        </th>
                        <th class="th-sm">Currency
                        </th>
                        <th class="th-sm">Amount
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        @foreach ($usages as $usage)
                      <tr><td>{{$usage->container ? $usage->container->name : ""}}</td>
                        <td>{{$usage->date}}</td>
                        <td>{{$usage->odometer}}Kms</td>
                        <td>{{$usage->container ? $usage->container->fuel_type : ""}}</td>
                        <td>{{$usage->quantity}}Litres</td>
                        <td>{{$usage->currency ? $usage->currency->name : ""}}</td>
                        <td>
                            @if ($usage->amount)
                                 {{$usage->currency ? $usage->currency->symbol : ""}}{{number_format($usage->amount,2)}}        
                            @endif
                        </td>
                       
                      </tr>
                      @endforeach
                    </tbody>


                  </table>
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
                        <td>{{$tyre_assignment->tyre ? $tyre_assignment->tyre->tyre_number : ""}}</td>
                        <td>
                            @if ($tyre_assignment->tyre)
                            {{$tyre_assignment->tyre->product ? $tyre_assignment->tyre->product->name : ""}}
                            @endif
                        </td>
                        <td>{{$tyre_assignment->tyre ? $tyre_assignment->tyre->serial_number : ""}}</td>
                        <td>{{$tyre_assignment->tyre ? $tyre_assignment->tyre->width : ""}} / {{$tyre_assignment->tyre ? $tyre_assignment->tyre->aspect_ratio : ""}} R {{$tyre_assignment->tyre ? $tyre_assignment->tyre->diameter : ""}}</td>
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
            <div role="tabpanel" class="tab-pane" id="service">
                <table id="bookingsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                    <thead >
                        <th class="th-sm">Booking#
                        </th>
                        <th class="th-sm">RequestedBy
                        </th>
                        <th class="th-sm">AssignedTo
                        </th>
                        <th class="th-sm">Type
                        </th>
                        <th class="th-sm">Date
                        </th>
                        <th class="th-sm">Station
                        </th>
                        <th class="th-sm">Mileage
                        </th>
                        <th class="th-sm">Status
                        </th>
                      </tr>
                    </thead>
                    <tbody>
                        @if (isset($horse->bookings))
                        @foreach ($horse->bookings as $booking)
                        <tr>
                            <td>{{$booking->booking_number}}</td>
                            <td>{{ucfirst($booking->employee ? $booking->employee->name : "")}} {{ucfirst($booking->employee ? $booking->employee->surname : "")}}</td>
                            <td>
                                @if (isset($booking->employees) && $booking->employees->count()>0)
                                    @foreach ($booking->employees as $mechanic)
                                        {{ $mechanic->name }} {{ $mechanic->surname }}
                                        <br>
                                    @endforeach
                                @elseif(isset($booking->vendor))
                                    {{ucfirst($booking->vendor->name)}}  
                                @endif
                            </td>
                            <td>{{$booking->service_type ? $booking->service_type->name : ""}}</td>
                            <td>{{$booking->in_date}} {{$booking->in_time}}</td>
                            <td>{{$booking->station}}</td>
                            <td>
                                @if ($booking->odometer)
                                {{$booking->odometer}}Kms        
                                @endif
                            </td>
                            <td><span class="badge bg-{{$booking->status == 1 ? "warning" : "success"}}">{{$booking->status == 1 ? "Open" : "Closed"}}</span></td>
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
                        <input type="number" step="any" min="1" max="{{ $horse->fuel_tank_capacity }}" class="form-control" wire:model.debounce.300ms="fuel_balance" {{ $horse->fuel_tank_capacity ? "" : "disabled" }} placeholder="Enter Fuel Level" required />
                        <small style="color: red">{{ $horse->fuel_tank_capacity ? "" : "Please set horse fuel tank capacity first before setting horse fuel level"  }}</small>
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
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Update Horse Mileage <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="updateOdometer()" >
                <div class="modal-body">

                    <div class="form-group">
                        <label for="name">Horse Mileage<span class="required" style="color: red">*</span></label>
                        <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="mileage" placeholder="Enter Horse Mileage" required />
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
                        <input type="number" step="any"  min="{{ $horse->mileage }}" class="form-control" wire:model.debounce.300ms="next_service" {{ $horse->mileage ? "" : "disabled" }} placeholder="Enter Horse Next Service Mileage" required />
                        <small style="color: red">{{ !isset($horse->mileage) ? "Please set horse mileage first before setting horse next mileage" : ""  }}</small>
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
</div>
