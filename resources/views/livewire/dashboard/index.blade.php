<div>
    <section class="section">
        <div class="container-fluid">
            @php
            $departments = Auth::user()->employee->departments;
            foreach($departments as $department){
                $department_names[] = $department->name;
            }
            $roles = Auth::user()->roles;
            foreach($roles as $role){
                $role_names[] = $role->name;
            }
        @endphp
        @if ((in_array('Human Resources', $department_names) || in_array('Super Admin', $role_names)))
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('branches.index')}}">
                        <span class="number counter">{{$branch_count}}</span>
                        <span class="name">Branches</span>
                        <span class="bg-icon"><i class="fa fa-building-o"></i></span>
                    </a>

                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('departments.index')}}">
                        <span class="number counter">{{$department_count}}</span>
                        <span class="name">Departments</span>
                        <span class="bg-icon"><i class="fa fa-building"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('employees.index')}}">
                        <span class="number counter">{{$employee_count}}</span>
                        <span class="name">Employees</span>
                        <span class="bg-icon"><i class="fa fa-users"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-success" href="{{route('drivers.index')}}">
                        <span class="number counter">{{$driver_count}}</span>
                        <span class="name">Drivers</span>
                        <span class="bg-icon"><i class="fas fa-users"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->
                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            </div>
            <br>
            @endif

            @if (in_array('Finance', $department_names) || in_array('Super Admin', $role_names))
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('customers.index')}}">
                        <span class="number counter">{{$customer_count}}</span>
                        <span class="name">Customers</span>
                        <span class="bg-icon"><i class="fa fa-users"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>   
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('invoices.index')}}">
                        <span class="number counter">{{$invoice_count}}</span>
                        <span class="name">Invoices</span>
                        <span class="bg-icon"><i class="fa fa-file"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>   
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('vendors.index')}}">
                        <span class="number counter">{{$vendor_count}}</span>
                        <span class="name">Vendors</span>
                        <span class="bg-icon"><i class="fas fa-users"></i></span>
                    </a>

                </div>

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-success" href="{{route('bills.index')}}">
                        <span class="number counter">{{$bill_count}}</span>
                        <span class="name">Bills</span>
                        <span class="bg-icon"><i class="fa fa-file"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>  
            </div>
        

            @endif 
        <br>

        @if (in_array('Transport & Logistics', $department_names) || in_array('Super Admin', $role_names))
        @if (!Auth::user()->driver)
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('horses.index')}}">
                        <span class="number counter">{{$horse_count}}</span>
                        <span class="name">Horses</span>
                        <span class="bg-icon"><i class="fas fa-truck"></i></span>
                    </a>

                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('trailers.manage')}}">
                        <span class="number counter">{{$trailer_count}}</span>
                        <span class="name">Trailers</span>
                        <span class="bg-icon"><i class="fa fa-trailer"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('vehicles.index')}}">
                        <span class="number counter">{{$vehicle_count}}</span>
                        <span class="name">Vehicles</span>
                        <span class="bg-icon"><i class="fa fa-car"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-success" href="{{route('assignments.index')}}">
                        <span class="number counter">{{$assignment_count}}</span>
                        <span class="name">Assignments</span>
                        <span class="bg-icon"><i class="fa fa-exchange"></i></span>
                    </a>

                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            </div>
            <br>
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('trips.index')}}">
                        <span class="number counter">{{$trip_count}}</span>
                        <span class="name">Trips</span>
                        <span class="bg-icon"><i class="fas fa-road"></i></span>
                    </a>
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

               
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('destinations.index')}}">
                        <span class="number counter">{{$destination_count}}</span>
                        <span class="name">Destinations</span>
                        <span class="bg-icon"><i class="fa fa-map-marker"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->
                    <!-- /.src-code -->
                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('transporters.index')}}">
                        <span class="number counter">{{$transporter_count}}</span>
                        <span class="name">Transporters</span>
                        <span class="bg-icon"><i class="fa fa-truck"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->
                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-success" href="{{route('agents.index')}}">
                        <span class="number counter">{{$agent_count}}</span>
                        <span class="name">Agents</span>
                        <span class="bg-icon"><i class="fa fa-users"></i></span>
                    </a>

                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            </div>
            <br>
            
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('fuels.index')}}">
                        <span class="number counter">{{$fuel_order_count}}</span>
                        <span class="name">Fuel Orders</span>
                        <span class="bg-icon"><i class="fas fa-gas-pump"></i></span>
                    </a>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('containers.manage')}}">
                        <span class="number counter">{{$fuel_supplier_count}}</span>
                        <span class="name">Fueling Stations</span>
                        <span class="bg-icon"><i class="fas fa-gas-pump"></i></span>
                    </a>

                </div>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('fuel_requests.index')}}">
                        <span class="number counter">{{$fuel_supplier_count}}</span>
                        <span class="name">Fuel Requests</span>
                        <span class="bg-icon"><i class="fas fa-plus"></i></span>
                    </a>
                </div>
               
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-success" href="{{route('assets.index')}}">
                        <span class="number counter">{{$asset_count}}</span>
                        <span class="name">Assets</span>
                        <span class="bg-icon"><i class="fas fa-boxes"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
            </div>
            <br>
            @endif
            @endif
          
        @if (in_array('Workshop', $department_names) || in_array('Stores', $department_names) || in_array('Super Admin', $role_names))
            <div class="row">
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('tyres.index')}}">
                        <span class="number counter">{{$tyre_count}}</span>
                        <span class="name">Tyres</span>
                        <span class="bg-icon"><i class="fas fa-ring"></i></span>
                    </a>

                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('inventories.index')}}">
                        <span class="number counter">{{$inventory_count}}</span>
                        <span class="name">Inventory</span>
                        <span class="bg-icon"><i class="fas fa-warehouse"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('bookings.index')}}">
                        <span class="number counter">{{$booking_count}}</span>
                        <span class="name">Booking</span>
                        <span class="bg-icon"><i class="fas fa-edit"></i></span>
                    </a>
                    <!-- /.dashboard-stat -->


                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-success" href="{{route('tickets.index')}}">
                        <span class="number counter">{{$ticket_count}}</span>
                        <span class="name">Tickets</span>
                        <span class="bg-icon"><i class="fas fa-tasks"></i></span>
                    </a>

                    <!-- /.src-code -->
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            </div>
            @endif
          
        </div>
        <!-- /.container-fluid -->
    </section>
    <!-- /.section -->

    @php
    $departments = Auth::user()->employee->departments;
    foreach($departments as $department){
        $department_names[] = $department->name;
    }
    $roles = Auth::user()->roles;
    foreach($roles as $role){
        $role_names[] = $role->name;
    }
    $ranks = Auth::user()->employee->ranks;
    foreach($ranks as $rank){
        $rank_names[] = $rank->name;
    }
    @endphp
      @if ((in_array('Finance', $department_names)) || in_array('Super Admin', $role_names))
    <section class="section pt-10">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>{{$currency}} Revenue Flow Chat</h5>
                            </div>
                        </div>
                        <div class="panel-body p-20">

                            <div id="usd_revenue" class="op-chart"></div>

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>{{$currency}} Expenses Flow Chat</h5>
                            </div>
                        </div>
                        <div class="panel-body p-20">

                            <div id="usd_expenses" class="op-chart"></div>

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>
                <!-- /.col-md-12 -->
            </div>
    
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
@endif
@if (in_array('Transport & Logistics', $department_names) || in_array('Super Admin', $role_names))
@if (!Auth::user()->driver)
<section class="section ">
    <div class="container-fluid">
        <div class="row">

            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5>Trips</h5>
                        </div>
                    </div>
                    <div class="panel-body overflow-x-auto">
                        <div class="panel-title">
                            <h5>Latest 5 records</h5>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Trip#</th>
                                    <th>Horse</th>
                                    <th>Driver</th>
                                    <th>Cargo</th>
                                    <th>Trip</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($trips as $trip)
                                <tr>
                                    <td>{{$trip->trip_number}}</td>
                                    <td>
                                        @if ($trip->horse)
                                        {{$trip->horse ? $trip->horse->registration_number : ""}} {{$trip->horse->fleet_number ? "(".$trip->horse->fleet_number.")" : ""}}
                                        @elseif($trip->vehicle)
                                        {{$trip->vehicle ? $trip->vehicle->registration_number : ""}} {{$trip->vehicle->fleet_number ? "(".$trip->vehicle->fleet_number.")" : ""}}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($trip->driver)
                                            {{$trip->driver->employee ? $trip->driver->employee->name : ""}} {{$trip->driver->employee ? $trip->driver->employee->surname : ""}}
                                        @endif
                                    </td>
                                    <td>{{$trip->cargo ? $trip->cargo->name : ""}}</td>
                                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}} - {{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.panel-body -->

                    <!-- /.src-code -->
                </div>
                <!-- /.panel -->
            </div>

            
          
            <div class="col-md-6">
                <div class="panel">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5>Total trips per month</h5>
                        </div>
                    </div>
                    <div class="panel-body p-20">

                        <div id="total_trips" class="op-chart"></div>

           
                        <!-- /.col-md-12 -->
                    </div>
                </div>
                <!-- /.panel -->
            </div>
            <!-- /.col-md-8 -->

         
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
<section class="section ">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="panel border-primary no-border border-3-top">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5>Litres, Tons & Kilometers Moved </h5>
                        </div>
                    </div>
                    <div class="panel-body">
                        <canvas id="combo-bar-line" class="mb-20"></canvas>
                    </div>
                </div>
                <!-- /.panel -->
            </div>
            <div class="col-md-6">
                <div class="panel panel-danger">
                    <div class="panel-heading">
                        <div class="panel-title">
                            <h5>Transporters</h5>
                        </div>
                    </div>
                    <div class="panel-body overflow-x-auto">
                        <div class="panel-title">
                            <h5>Latest 5 records</h5>
                        </div>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Transporter#</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phonenumber</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($transporters as $transporter)
                                <tr>
                                    <td>{{$transporter->transporter_number}}</td>
                                    <td>{{$transporter->name}}</td>
                                    <td>{{$transporter->email}}</td>
                                    <td>{{$transporter->phonenumber}}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- /.panel-body -->

                    <!-- /.src-code -->
                </div>
                <!-- /.panel -->
            </div>
          

            <!-- /.col-md-8 -->

         
        </div>
        <!-- /.row -->
    </div>
    <!-- /.container-fluid -->
</section>
@endif
@endif
@if (in_array('Human Resources', $department_names) || in_array('Super Admin', $role_names))
    <section class="section ">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-8">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Employees</h5>
                             
                            </div>
                        </div>
                        <div class="panel-body overflow-x-auto">
                            <div class="panel-title">
                                <h5>Latest 5 records</h5>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Employee#</th>
                                        <th>Name</th>
                                        <th>Surname</th>
                                        <th>Job Title</th>
                                        <th>Department</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($recent_employees as $employee)
                                    <tr>
                                        <td>{{$employee->employee_number}}</td>
                                        <td>{{$employee->name}}</td>
                                        <td>{{$employee->surname}}</td>
                                        <td>{{$employee->post}}</td>
                                        <td>
                                            @if ($employee->departments->count()>0)
                                            {{$employee->departments->first()->name}}     
                                            @endif
                                           
                                           </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->

                        <!-- /.src-code -->
                    </div>
                    <!-- /.panel -->
                </div>

                <!-- /.col-md-8 -->

              

                <div class="col-md-4">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Department Heads</h5>
                            </div>
                        </div>
                        <div class="panel-body overflow-x-auto">
                            <div class="panel-title">
                                <h5>Latest 5 records</h5>
                            </div>
                            @if ($hods->count()>0)
                            @foreach ($hods as $hod)
                            @php
                                $employee = App\Models\Employee::find($hod->employee_id);
                                $department = App\Models\Department::find($hod->department_id);
                            @endphp
                            <div class="col-xs-12 p-n">
                                <div class="col-xs-6 p-n">
                                    {{$employee ? $employee->name : "Eployee Record Not Found"}} {{$employee ? $employee->surname : ""}}
                                </div>
                                <!-- /.col-md-6 -->
                                <div class="col-xs-6 p-n">
                                   {{$department ? $department->name : ""}}
                                </div>
                            </div>
                            @endforeach
                            @endif

                        <!-- /.col-xs-12 -->

                        <!-- /.col-xs-12 -->

                        <!-- /.col-xs-12 -->

                        <!-- /.col-xs-12 -->

                            <!-- /.col-xs-12 -->
                        </div>
                        <!-- /.panel-body -->
                    </div>
                </div>
                <!-- /.col-md-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    <section class="section ">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-6">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Employee Labour Turnover</h5>
                            </div>
                        </div>
                        <div class="panel-body p-20">

                            <div id="chart12" class="op-chart"></div>

      
                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                    <!-- /.panel -->
                </div>

                <!-- /.col-md-8 -->

                <div class="col-md-6">

                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Gender Ratio Chart</h5>
                            </div>
                        </div>
                        <div class="panel-body p-20">

                            <div id="chart4" class="op-chart"></div>
                        </div>
                    </div>
                </div>

     
                <!-- /.col-md-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @endif
    @if (in_array('Transport & Logistics', $department_names) || in_array('Super Admin', $role_names))
    @if (!Auth::user()->driver)
    <section class="section">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-danger">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Fuel Distribution</h5>
                            </div>
                        </div>
                        <div class="panel-body p-20">

                            <div id="chart3" class="op-chart"></div>

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Fueling Stations</h5>
                            </div>
                        </div>
                        <div class="panel-body overflow-x-auto">
                            <div class="panel-title">
                                <h5>Latest 5 records</h5>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Station#</th>
                                        <th>Name</th>
                                        <th>Fuel Type</th>
                                        <th>Capacity</th>
                                        <th>Quantity</th>
                                        <th>Balance</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($containers as $container)
                                    <tr>
                                        <td>{{$container->container_number}}</td>
                                        <td>{{$container->name}} </td>
                                        <td>{{$container->fuel_type}}</td>
                                        <td>{{$container->capacity}} l</td>
                                        <td>{{$container->quantity}} l</td>
                                        <td>{{$container->balance}} l</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->

                        <!-- /.src-code -->
                    </div>
                    <!-- /.panel -->
                </div>

            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
    @endif
    @endif
    <section class="section ">
        <div class="container-fluid">
            <div class="row">

                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>My Allocations</h5>
                            </div>
                        </div>
                        <div class="panel-body overflow-x-auto">
                            <div class="panel-title">
                                <h5>Latest 5 records</h5>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Issued</th>
                                        <th>Employee#</th>
                                        <th>Vehicle</th>
                                        <th>Station</th>
                                        <th>Fuel</th>
                                        <th>Quantity</th>
                                        <th>Balance</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($myallocations as $allocation)
                                    <tr>
                                        <td>{{$allocation->created_at}}</td>
                                        <td>{{$allocation->employee ? $allocation->employee->employee_number : ""}}</td>
                                        <td>
                                            @if ($allocation->vehicle)
                                            {{$allocation->vehicle->vehicle_make ? $allocation->vehicle->vehicle_make->name : ""}} {{$allocation->vehicle->vehicle_model ? $allocation->vehicle->vehicle_model->name : ""}} {{$allocation->vehicle->registration_number}}
                                            @endif
                                          
                                        </td>
                                        <td>{{$allocation->container ? $allocation->container->name : ""}}</td>
                                        <td>{{$allocation->fuel_type}}</td>
                                        <td>{{$allocation->quantity}}l</td>
                                        <td>{{$allocation->balance}}l</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->
                       
                        <!-- /.src-code -->
                    </div>
                    <!-- /.panel -->
                </div>
                @if (in_array('Finance', $department_names) || in_array('Super Admin', $role_names))
                <div class="col-md-6">
                    <div class="panel panel-danger">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Fuel Allocations</h5>
                            </div>
                        </div>
                        <div class="panel-body overflow-x-auto">
                            <div class="panel-title">
                                <h5>Latest 5 records</h5>
                            </div>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Issued</th>
                                        <th>Employee#</th>
                                        <th>Name</th>
                                        <th>Vehicle</th>
                                        <th>Station</th>
                                        <th>Fuel</th>
                                        <th>Quantity</th>

                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($allocations as $allocation)
                                    <tr>
                                        <td>{{$allocation->created_at}}</td>
                                        <td>{{$allocation->employee ? $allocation->employee->employee_number : ""}}</td>
                                        <td>{{$allocation->employee ? $allocation->employee->name : ""}} {{ $allocation->employee ? $allocation->employee->surname : ""}}</td>
                                        <td>
                                            @if ($allocation->vehicle)
                                            {{$allocation->vehicle->vehicle_make ? $allocation->vehicle->vehicle_make->name : ""}} {{$allocation->vehicle->vehicle_model ? $allocation->vehicle->vehicle_model->name : ""}} {{$allocation->vehicle->registration_number}}
                                            @endif
                                          
                                        </td>
                                        <td>{{$allocation->container ? $allocation->container->name : ""}}</td>
                                        <td>{{$allocation->fuel_type}}</td>
                                        <td>{{$allocation->quantity}}</td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <!-- /.panel-body -->

                        <!-- /.src-code -->
                    </div>
                    <!-- /.panel -->
                </div>
                @endif
                

                <!-- /.col-md-8 -->

                <!-- /.col-md-4 -->
            </div>
            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</div>

@section('extra-js')

<script src="{{asset('js/prism/prism.js')}}"></script>
<script src="{{asset('js/amcharts/amcharts.js')}}"></script>
<script src="{{asset('js/amcharts/serial.js')}}"></script>
<script src="{{asset('js/amcharts/pie.js')}}"></script>
<script src="{{asset('js/amcharts/plugins/animate/animate.min.js')}}"></script>
<script src="{{asset('js/amcharts/plugins/export/export.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('js/amcharts/plugins/export/export.css')}}" type="text/css" media="all" />
<script src="{{asset('js/amcharts/themes/light.js')}}"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.bundle.js"></script>
<script src="{{asset('js/chartjs/utils.js')}}"></script>
<script src="{{asset('js/chartjs/globalchartjs.js')}}"></script>



<script>
         var chart12 = AmCharts.makeChart("chart12", {
                    "type": "serial",
                    "theme": "light",
                    "fontFamily": "Poppins",
                    "marginTop":0,
                    "marginRight": 80,
                    "dataProvider": [{
                        "year": "2021",
                        "value": {{$resignation_2021}}
                    }, {
                        "year": "2022",
                        "value": {{$resignation_2022}}
                    }, 
                    {
                        "year": "2023",
                        "value": {{$resignation_2023}}
                    },
                    {
                        "year": "2024",
                        "value": {{$resignation_2024}}
                    }
                ],
                    "valueAxes": [{
                        "axisAlpha": 0,
                        "position": "left"
                    }],
                    "graphs": [{
                        "id":"g1",
                        "balloonText": "[[category]]<br><b><span style='font-size:14px;'>[[value]]</span></b>",
                        "bullet": "round",
                        "bulletSize": 8,
                        "lineColor": "#d1655d",
                        "lineThickness": 2,
                        "negativeLineColor": "#637bb6",
                        "type": "smoothedLine",
                        "valueField": "value"
                    }],
                    "chartScrollbar": {
                        "graph":"g1",
                        "gridAlpha":0,
                        "color":"#888888",
                        "scrollbarHeight":55,
                        "backgroundAlpha":0,
                        "selectedBackgroundAlpha":0.1,
                        "selectedBackgroundColor":"#888888",
                        "graphFillAlpha":0,
                        "autoGridCount":true,
                        "selectedGraphFillAlpha":0,
                        "graphLineAlpha":0.2,
                        "graphLineColor":"#c2c2c2",
                        "selectedGraphLineColor":"#888888",
                        "selectedGraphLineAlpha":1

                    },
                    "chartCursor": {
                        "categoryBalloonDateFormat": "YYYY",
                        "cursorAlpha": 0,
                        "valueLineEnabled":true,
                        "valueLineBalloonEnabled":true,
                        "valueLineAlpha":0.5,
                        "fullWidth":true
                    },
                    "dataDateFormat": "YYYY",
                    "categoryField": "year",
                    "categoryAxis": {
                        "minPeriod": "YYYY",
                        "parseDates": true,
                        "minorGridAlpha": 0.1,
                        "minorGridEnabled": true
                    },
                    "export": {
                        "enabled": true
                    }

                });
                chart12.addListener("rendered", zoomChart);
                if(chart12.zoomChart){
                	chart12.zoomChart();
                }

                function zoomChart(){
                    chart12.zoomToIndexes(Math.round(chart12.dataProvider.length * 0.1), Math.round(chart12.dataProvider.length * 0.8));
                }

                 
        

var chart4 = AmCharts.makeChart( "chart4", {
                  "type": "pie",
                  "theme": "light",
                  "fontFamily": "Poppins",
                  "dataProvider": [ {
                    "gender": "Male",
                    "value": {{$males}}
                  }, {
                    "gender": "Female",
                    "value": {{$females}}
                  },  ],
                  "valueField": "value",
                  "titleField": "gender",
                  "outlineAlpha": 0.4,
                  "depth3D": 15,
                  "balloonText": "[[title]]<br><span style='font-size:14px'><b>[[value]]</b> ([[percents]]%)</span>",
                  "angle": 30,
                  "export": {
                    "enabled": true
                  }
                } );


var chart = AmCharts.makeChart("total_trips", {
                  "type": "serial",
                  "theme": "light",
                  "fontFamily": "Poppins",
                  "marginRight": 70,
                  "dataProvider": [{
                    "month": "Jan",
                    "trips": {{$jan_trips}},
                    "color": "#FF0F00"
                  }, {
                    "month": "Feb",
                    "trips": {{$feb_trips}},
                    "color": "#FF6600"
                  }, {
                    "month": "Mar",
                    "trips": {{$mar_trips}},
                    "color": "#FF9E01"
                  }, {
                    "month": "Apr",
                    "trips": {{$apr_trips}},
                    "color": "#FCD202"
                  }, {
                    "month": "May",
                    "trips": {{$may_trips}},
                    "color": "#F8FF01"
                  }, {
                    "month": "Jun",
                    "trips": {{$jun_trips}},
                    "color": "#B0DE09"
                  }, {
                    "month": "Jul",
                    "trips": {{$jul_trips}},
                    "color": "#04D215"
                  }, {
                    "month": "Aug",
                    "trips": {{$aug_trips}},
                    "color": "#0D8ECF"
                  }, {
                    "month": "Sep",
                    "trips": {{$sep_trips}},
                    "color": "#0D52D1"
                  }, {
                    "month": "Oct",
                    "trips": {{$oct_trips}},
                    "color": "#2A0CD0"
                  }, {
                    "month": "Nov",
                    "trips": {{$nov_trips}},
                    "color": "#8A0CCF"
                  }, {
                    "month": "Dec",
                    "trips": {{$dec_trips}},
                    "color": "#CD0D74"
                  }],
                  "valueAxes": [{
                    "axisAlpha": 0,
                    "position": "left",
                    "title": "Total trips per month"
                  }],
                  "startDuration": 1,
                  "graphs": [{
                    "balloonText": "<b>[[category]]: [[value]]</b>",
                    "fillColorsField": "color",
                    "fillAlphas": 0.9,
                    "lineAlpha": 0.2,
                    "type": "column",
                    "valueField": "trips"
                  }],
                  "chartCursor": {
                    "categoryBalloonEnabled": false,
                    "cursorAlpha": 0,
                    "zoomable": false
                  },
                  "categoryField": "month",
                  "categoryAxis": {
                    "gridPosition": "start",
                    "labelRotation": 45
                  },
                  "export": {
                    "enabled": true
                  }

                });


   var chartData = {
            labels: ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul","Aug","Sep","Oct","Nov","Dec"],
            datasets: [ {
                type: 'bar',
                label: 'Litres',
                backgroundColor: window.chartColors.red,
                data: [
                    {{$jan_litreage}}, 
                    {{$feb_litreage}}, 
                    {{$mar_litreage}}, 
                    {{$apr_litreage}}, 
                    {{$may_litreage}}, 
                    {{$jun_litreage}}, 
                    {{$jul_litreage}}, 
                    {{$aug_litreage}}, 
                    {{$sept_litreage}}, 
                    {{$oct_litreage}}, 
                    {{$nov_litreage}}, 
                    {{$dec_litreage}}, 
                ],
                borderColor: 'white',
                borderWidth: 2
            }, {
                type: 'bar',
                label: 'Tons',
                backgroundColor: window.chartColors.green,
                data: [
                    {{$jan_weight}}, 
                    {{$feb_weight}}, 
                    {{$mar_weight}}, 
                    {{$apr_weight}}, 
                    {{$may_weight}}, 
                    {{$jun_weight}}, 
                    {{$jul_weight}}, 
                    {{$aug_weight}}, 
                    {{$sept_weight}}, 
                    {{$oct_weight}}, 
                    {{$nov_weight}}, 
                    {{$dec_weight}}, 
                ]
            },
            {
                type: 'line',
                label: 'Kilometers',
                borderColor: window.chartColors.blue,
                borderWidth: 2,
                fill: false,
                data: [
                    {{$jan_distance}}, 
                    {{$feb_distance}}, 
                    {{$mar_distance}}, 
                    {{$apr_distance}}, 
                    {{$may_distance}}, 
                    {{$jun_distance}}, 
                    {{$jul_distance}}, 
                    {{$aug_distance}}, 
                    {{$sept_distance}}, 
                    {{$oct_distance}}, 
                    {{$nov_distance}}, 
                    {{$dec_distance}}, 
                    
                ]
            }
        ]

        };
        window.onload = function() {
            var ctxcombo = document.getElementById("combo-bar-line").getContext("2d");
            window.myMixedChart = new Chart(ctxcombo, {
                type: 'bar',
                data: chartData,
                options: {
                    responsive: true,
                    title: {
                        display: true,
                        text: 'Litres, Tons & Kilometers'
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: true
                    }
                }
            });
        };

        document.getElementById('randomizeData-combo-bar-line').addEventListener('click', function() {
            chartData.datasets.forEach(function(dataset) {
                dataset.data = dataset.data.map(function() {
                    return randomScalingFactor();
                });
            });
            window.myMixedChart.update();
        });

      
        

</script>



<script>
      $(function($) {

var chart8 = AmCharts.makeChart("chart8", {
              "type": "serial",
              "theme": "light",
              "fontFamily": "Poppins",
              "rotate": true,
              "marginBottom": 50,
              "dataProvider": [{
                "age": "85+",
                "male": -0.1,
                "female": 0.3
              }, {
                "age": "80-54",
                "male": -0.2,
                "female": 0.3
              }, {
                "age": "75-79",
                "male": -0.3,
                "female": 0.6
              }, {
                "age": "70-74",
                "male": -0.5,
                "female": 0.8
              }, {
                "age": "65-69",
                "male": -0.8,
                "female": 1.0
              }, {
                "age": "60-64",
                "male": -1.1,
                "female": 1.3
              }, {
                "age": "55-59",
                "male": -1.7,
                "female": 1.9
              }, {
                "age": "50-54",
                "male": -2.2,
                "female": 2.5
              }, {
                "age": "45-49",
                "male": -2.8,
                "female": 3.0
              }, {
                "age": "40-44",
                "male": -3.4,
                "female": 3.6
              }, {
                "age": "35-39",
                "male": -4.2,
                "female": 4.1
              }, {
                "age": "30-34",
                "male": -5.2,
                "female": 4.8
              }, {
                "age": "25-29",
                "male": -5.6,
                "female": 5.1
              }, {
                "age": "20-24",
                "male": -5.1,
                "female": 5.1
              }, {
                "age": "15-19",
                "male": -3.8,
                "female": 3.8
              }, {
                "age": "10-14",
                "male": -3.2,
                "female": 3.4
              }, {
                "age": "5-9",
                "male": -4.4,
                "female": 4.1
              }, {
                "age": "0-4",
                "male": -5.0,
                "female": 4.8
              }],
              "startDuration": 1,
              "graphs": [{
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "male",
                "title": "Male",
                "labelText": "[[value]]",
                "clustered": false,
                "labelFunction": function(item) {
                  return Math.abs(item.values.value);
                },
                "balloonFunction": function(item) {
                  return item.category + ": " + Math.abs(item.values.value) + "%";
                }
              }, {
                "fillAlphas": 0.8,
                "lineAlpha": 0.2,
                "type": "column",
                "valueField": "female",
                "title": "Female",
                "labelText": "[[value]]",
                "clustered": false,
                "labelFunction": function(item) {
                  return Math.abs(item.values.value);
                },
                "balloonFunction": function(item) {
                  return item.category + ": " + Math.abs(item.values.value) + "%";
                }
              }],
              "categoryField": "age",
              "categoryAxis": {
                "gridPosition": "start",
                "gridAlpha": 0.2,
                "axisAlpha": 0
              },
              "valueAxes": [{
                "gridAlpha": 0,
                "ignoreAxisWidth": true,
                "labelFunction": function(value) {
                  return Math.abs(value) + '%';
                },
                "guides": [{
                  "value": 0,
                  "lineAlpha": 0.2
                }]
              }],
              "balloon": {
                "fixedPosition": true
              },
              "chartCursor": {
                "valueBalloonsEnabled": false,
                "cursorAlpha": 0.05,
                "fullWidth": true
              },
              "allLabels": [{
                "text": "Male",
                "x": "28%",
                "y": "97%",
                "bold": true,
                "align": "middle"
              }, {
                "text": "Female",
                "x": "75%",
                "y": "97%",
                "bold": true,
                "align": "middle"
              }],
             "export": {
                "enabled": true
              }

            });
      })
</script>

<script>
    $(function(){

        // usd_revenue
        var usd_revenue = AmCharts.makeChart( "usd_revenue", {
          "type": "serial",
          "theme": "light",
          "fontFamily": "Poppins",
          "dataProvider": [ {
            "revenue": "{{$jan}}",
            "year": "Jan"
          },{
            "revenue": "{{$feb}}",
            "year": "Feb"
          },{
            "revenue": "{{$mar}}",
            "year": "Mar"
          },{
            "revenue": "{{$apr}}",
            "year": "Apr"
          },{
            "revenue": "{{$may}}",
            "year": "May"
          },{
            "revenue": "{{$jun}}",
            "year": "Jun"
          }, {
            "revenue": "{{$jul}}",
            "year": "Jul"
          }, {
            "revenue": "{{$aug}}",
            "year": "Aug"
          }, {
            "revenue": "{{$sep}}",
            "year": "Sept"
          }, {
            "revenue": "{{$oct}}",
            "year": "Oct"
          }, {
            "revenue": "{{$nov}}",
            "year": "Nov"
          }, {
            "revenue": "{{$dec}}",
            "year": "Dec"
          } ],
          "valueAxes": [ {
            "gridColor": "#FF0000",
            "gridAlpha": 0.2,
            "dashLength": 0,
            "title": "Generated Revenue Per Month ({{$currency}}), {{date('Y')}}"
          } ],
          "gridAboveGraphs": true,
          "startDuration": 1,
          "graphs": [ {
            "balloonText": "[[category]]: <b>[[value]]</b>",
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "revenue"
          } ],
          "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
          },
          "categoryField": "year",
          "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0,
            "tickPosition": "start",
            "tickLength": 20,
            "title": "Months"
          },
          "export": {
            "enabled": true
          }

        } );
        

        var usd_expenses = AmCharts.makeChart( "usd_expenses", {
          "type": "serial",
          "theme": "red",
          "fontFamily": "Poppins",
          "dataProvider": [ {
            "revenue": "{{$jan_expense}}",
            "year": "Jan"
          },{
            "revenue": "{{$feb_expense}}",
            "year": "Feb"
          },{
            "revenue": "{{$mar_expense}}",
            "year": "Mar"
          },{
            "revenue": "{{$apr_expense}}",
            "year": "Apr"
          },{
            "revenue": "{{$may_expense}}",
            "year": "May"
          },{
            "revenue": "{{$jun_expense}}",
            "year": "Jun"
          }, {
            "revenue": "{{$jul_expense}}",
            "year": "Jul"
          }, {
            "revenue": "{{$aug_expense}}",
            "year": "Aug"
          }, {
            "revenue": "{{$sep_expense}}",
            "year": "Sept"
          }, {
            "revenue": "{{$oct_expense}}",
            "year": "Oct"
          }, {
            "revenue": "{{$nov_expense}}",
            "year": "Nov"
          }, {
            "revenue": "{{$dec_expense}}",
            "year": "Dec"
          } ],
          "valueAxes": [ {
            "gridColor": "#FF0000",
            "gridAlpha": 0.2,
            "dashLength": 0,
            "title": "Expenses Per Month ({{$currency}}), {{date('Y')}}"
          } ],
          "gridAboveGraphs": true,
          "startDuration": 1,
          "graphs": [ {
            "balloonText": "[[category]]: <b>[[value]]</b>",
            "fillAlphas": 0.8,
            "lineAlpha": 0.2,
            "type": "column",
            "valueField": "revenue"
          } ],
          "chartCursor": {
            "categoryBalloonEnabled": false,
            "cursorAlpha": 0,
            "zoomable": false
          },
          "categoryField": "year",
          "categoryAxis": {
            "gridPosition": "start",
            "gridAlpha": 0,
            "tickPosition": "start",
            "tickLength": 20,
            "title": "Months"
          },
          "export": {
            "enabled": true
          }

        } );

   

                      // usd_revenue
                      var chart3 = AmCharts.makeChart("chart3", {
                    "type": "pie",
                    "theme": "light",
                    "fontFamily": "Poppins",
                    "innerRadius": "40%",
                    "gradientRatio": [-0.4, -0.4, -0.4, -0.4, -0.4, -0.4, 0, 0.1, 0.2, 0.1, 0, -0.2, -0.5],
                    "dataProvider": [{
                        "Fuel Type": "Petrol",
                        "litres": {{$petrol_quantity}}
                    }, {
                        "Fuel Type": "Diesel",
                        "litres": {{$diesel_quantity}}
                    }],
                    "balloonText": "[[value]]",
                    "valueField": "litres",
                    "titleField": "Fuel Type",
                    "balloon": {
                        "drop": true,
                        "adjustBorderColor": false,
                        "color": "#FFFFFF",
                        "fontSize": 16
                    },
                    "export": {
                        "enabled": true
                    }
                });

    });
</script>

@endsection
