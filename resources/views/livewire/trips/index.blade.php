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
                            @if (isset($trips))
                            <br>
                            <div class="row">
                                <div class="col-md-4 col-md-offset-4">
                                    <div class="alert-info" role="alert">
                                        @php
                                        $employee_department = Auth::user()->employee->departments->first();
                 
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
                                        <center><strong>Total Trips!</strong> {{ $trips->total() }}</center>
                                        @if (Auth::user()->employee->company->rates_managed_by_finance == 1)
                                            @if (in_array('Finance', $department_names) ||  in_array('Super Admin', $role_names))
                                                @php
                                                    foreach ($trips as $trip) {
                                                        $selected_trips_ids[] = $trip->currency_id;
                                                    }
                                                    $currencies = App\Models\Currency::all();
                                                @endphp
                                                @foreach ($currencies as $currency)
                                                    @if (isset($selected_trips_ids))
                                                        @if (in_array($currency->id, $selected_trips_ids))
                                                            <center><strong>Total Revenue {{ $currency->name }} :</strong> {{ $currency->symbol }}{{ number_format($trips->where('currency_id',$currency->id)->where('freight','!=',Null)->where('freight','!=','')->sum('freight'),2) }}</center>
                                                        @endif
                                                    @endif
                                                @endforeach
                                            @endif
                                        @else
                                            @php
                                                foreach ($trips as $trip) {
                                                    $selected_trips_ids[] = $trip->currency_id;
                                                }
                                                $currencies = App\Models\Currency::all();
                                            @endphp
                                            @foreach ($currencies as $currency)
                                                @if (isset($selected_trips_ids))
                                                    @if (in_array($currency->id, $selected_trips_ids))
                                                    <center><strong>Total Revenue {{ $currency->name }} :</strong> {{ $currency->symbol }}{{ number_format($trips->where('currency_id',$currency->id)->where('freight','!=',Null)->where('freight','!=','')->sum('freight'),2) }}</center>
                                                    @endif
                                                @endif
                                            @endforeach
                                        @endif
                                        
                                    </div>
                                    <!-- /.alert alert-info -->
                                </div>
                                <!-- /.col-md-12 -->
                            </div>
                            @endif
                           

                            <div class="panel-heading">
                                <div>
                                    @include('includes.messages')
                                </div>
                            </div>
                            <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">
                                <div class="panel-title">
                                    <h5>Trips Management</h5>
                                    <div class="row">
                                    <div class="col-lg-3">
                                    <div class="input-group">
                                      <span class="input-group-addon">Filter By</span>
                                      <select wire:model.debounce.300ms="trip_filter" class="form-control" aria-label="..." >
                                        <option value="created_at">Trip Created At</option>
                                        <option value="start_date">Trip Start Date</option>
                                      </select>
                                    </div>
                                        <!-- /input-group -->
                                    </div>
                                    @if ($trip_filter == "created_at")
                                    <div class="col-lg-2" style="margin-right: 7px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      From
                                      </span>
                                      <input type="date" wire:model.debounce.300ms="from" wire:change="dateRange()" class="form-control" aria-label="...">
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    <div class="col-lg-2" style="margin-left: 7px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      To
                                      </span>
                                      <input type="date" wire:model.debounce.300ms="to" wire:change="dateRange()" class="form-control" aria-label="...">
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    @elseif ($trip_filter == "start_date")
                                    <div class="col-lg-2" style="margin-right: 42px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      From
                                      </span>
                                      <input type="datetime-local" wire:model.debounce.300ms="from" wire:change="dateRange()" class="form-control" aria-label="...">
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    <div class="col-lg-2" style="margin-left: 42px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      To
                                      </span>
                                      <input type="datetime-local" wire:model.debounce.300ms="to" wire:change="dateRange()" class="form-control" aria-label="...">
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    @endif
                                   
                                    <!-- /input-group -->
                                </div>
                                <div class="row">
                                    <div class="col-lg-5">
                                    <a href="{{ route('trips.create') }}"  class="btn btn-default btn-wide" aria-haspopup="true" aria-expanded="true"><i class="fa fa-plus-square-o"></i>Trip</a>
                                    <a href="#" wire:click="exportTripsExcel()"  class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>Excel</a>
                                    <a href="#" wire:click="exportTripsCSV()" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>CSV</a>
                                    <a href="#" wire:click="exportTripsPDF()" class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-download"></i>PDF</a>
                                    </div>
                                     <div class="col-lg-2" style="float: right; margin-right:-40px" >
                                   
                                </div>

                                </div>
                               
                                
                                </div>
                                <br>
                                <div class="col-md-5" style="float: right; margin-right:-145px">
                                    <div class="dropdown">
                                        <button class="btn btn-default border-primary btn-rounded btn-wide dropdown-toggle" type="button" id="menu12" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                            <i class="fa fa-bars"></i> Trip Actions
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu bg-gray" aria-labelledby="menu12" style="float: right;" >
                                            <li><a href="#"  wire:click="editLocations()"><i class="fa fa-refresh"></i>Status Updates</a></li>
                                            <li><a href="#" wire:click="exportPodTrackerExcel()"><i class="fa fa-file"></i>POD Tracker</a></li>
                                            <li><a href="{{ route('trip_groups.index') }}"><i class="fa fa-map-marker"></i>Trip Tracking</a></li>
                                            @if (isset($from) && isset($to))
                                                <li><a href="{{ route('trips.summary.range',['from' => $from, 'to' => $to,'trip_filter'=>$trip_filter]) }}" ><i class="fa fa-line-chart"></i>Data Summary</a></li>
                                            @elseif (isset($from) && isset($to) && isset($search))
                                                <li><a href="{{ route('trips.summary.all',['from' => $from, 'to' => $to, 'search' => $search,'trip_filter'=> $trip_filter]) }}" ><i class="fa fa-line-chart"></i>Data Summary</a></li>
                                            @elseif (isset($search))
                                            <li><a href="{{ route('trips.summary.search',['search' => $search,'trip_filter'=>$trip_filter]) }}" ><i class="fa fa-line-chart"></i>Data Summary</a></li>
                                            @else
                                            <li><a href="{{ route('trips.summary',['trip_filter'=>$trip_filter]) }}" ><i class="fa fa-line-chart"></i>Data Summary</a></li>
                                            @endif
                                           
                                        </ul>
                                    </div>
                                    <br>
                                    <div class="form-group">
                                        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search Using: Trip#,Transporter,Customer,Reg#,CreatedBy,POD#,StartDate...">
                                    </div>
                                </div>
                              
                                <table class="table  table-striped table-bordered table-sm table-responsive sortable" cellspacing="0" width="100%" style=" width:100%; height:100%;">
                                    <thead >
                                        <th class="th-sm">Trip#
                                        </th>
                                        <th class="th-sm">CreatedBy
                                        </th>
                                        <th class="th-sm">Departure
                                        </th>
                                        <th class="th-sm">Offloaded
                                        </th>
                                        <th class="th-sm">Customer
                                        </th>
                                        <th class="th-sm">Transporter
                                        </th>
                                        <th class="th-sm">MOT
                                        </th>
                                        <th class="th-sm">From
                                        </th>
                                        <th class="th-sm">To
                                        </th>
                                        <th class="th-sm">Status
                                        </th>
                                        <th class="th-sm">Invoice
                                        </th>
                                        <th class="th-sm">PODS
                                        </th>
                                        <th class="th-sm">Auth
                                        </th>
                                        <th class="th-sm">Actions
                                        </th>

                                      </tr>
                                    </thead>
                                    @if (isset($trips))
                                    <tbody>
                                        @forelse ($trips as $trip)
                                        @php
                                            $from = App\Models\Destination::find($trip->from);
                                            $to = App\Models\Destination::find($trip->to);
                                        @endphp
                                      
                                        @if ($trip->trip_status == "Offloaded")
                                      <tr style="background-color: #5cb85c">
                                      
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                       <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                        
                                        <td>
                                            @if ($trip->delivery_note)
                                          
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                          
                                            
                                            @endif
                                        </td>
                                        <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                       
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                        <td> 
                                            @if ($trip->invoice_items->count()>0)
                                                <span class="label label-success"> issued</span>
                                            @else   
                                            <span class="label label-warning"> pending</span> 
                                            @endif
                                          </td>
                                            
                                         @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                        <td> 
                                            <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                            @if (isset($pod->document_number))
                                            <center>
                                                POD#: {{ $pod->document_number }}
                                            </center>
                                               
                                            @endif
                                           
                                        </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                    @if (Auth::user()->employee)
                                                    <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                    {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                    <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                    @endif
                                                   

                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @elseif($trip->trip_status == "Scheduled")
                                      <tr style="background-color: #f0ad4e" >
                                    
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                        <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                         <td>
                                            @if ($trip->delivery_note)
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                            
                                            @endif
                                        </td>
                                         <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                       
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                       
                                     <td> 
                                        @if ($trip->invoice_items->count()>0)
                                            <span class="label label-success"> issued</span>
                                        @else   
                                        <span class="label label-warning"> pending</span> 
                                        @endif
                                      </td>
                                        
                                        @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                            <td> 
                                                <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                                @if (isset($pod->document_number))
                                                <center>
                                                    POD#: {{ $pod->document_number }}
                                                </center>
                                                   
                                                @endif
                                               
                                            </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                   @if (Auth::user()->employee)
                                                   <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                   {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                   <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                   <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                   @endif
                                               
                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @elseif($trip->trip_status == "Cancelled")
                                      <tr style="background-color: #C4A484" >
                                    
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                        <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                         <td>
                                            @if ($trip->delivery_note)
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                            
                                            @endif
                                        </td>
                                         <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                       
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                       
                                     <td> 
                                        @if ($trip->invoice_items->count()>0)
                                            <span class="label label-success"> issued</span>
                                        @else   
                                        <span class="label label-warning"> pending</span> 
                                        @endif
                                      </td>
                                        
                                        @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                            <td> 
                                                <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                                @if (isset($pod->document_number))
                                                <center>
                                                    POD#: {{ $pod->document_number }}
                                                </center>
                                                   
                                                @endif
                                               
                                            </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                   @if (Auth::user()->employee)
                                                   <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                   {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                   <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                   <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                   @endif
                                               
                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @elseif($trip->trip_status == "Loading Point")
                                      <tr  style="background-color: #adb5bd" >
                                      
                                      <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                    <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                    <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                     <td>
                                        @if ($trip->delivery_note)
                                         @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                        
                                        @endif
                                    </td>
                                     <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                    <td>
                                        @if ($trip->horse)
                                            Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                        @elseif ($trip->vehicle)
                                           Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                        @endif
                                     
                                        @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                        <hr>
                                            @foreach ($trip->trailers as $trailer)
                                                {{ $trailer->registration_number }} 
                                            @endforeach
                                        @endif
                                    </td>
                                    <td>
                                        @if (isset($from))
                                        {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                        @endif
                                        @if ($trip->loading_point)
                                            @if (isset($from))
                                            <hr> 
                                            @endif
                                            {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                        @endif
                                       
                                    </td>
                                    <td>
                                        @if (isset($to))
                                        {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                        @endif
                                        @if ($trip->offloading_point)
                                            @if (isset($to))
                                            <hr>  
                                            @endif
                                            {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                        @endif
                                    </td>
                                    @if ($trip->trip_status == "Offloaded")
                                    <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "Scheduled")
                                    <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                        <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "Loading Point")
                                    <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                        <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "Loaded")
                                    <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "InTransit")
                                    <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "OnHold")
                                    <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "Cancelled")
                                    <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                        <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @elseif($trip->trip_status == "Offloading Point")
                                    <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                        <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                    </td>
                                    @endif
                                    <td> 
                                        @if ($trip->invoice_items->count()>0)
                                            <span class="label label-success"> issued</span>
                                        @else   
                                        <span class="label label-warning"> pending</span> 
                                        @endif
                                      </td>
                                        
                                   
                                    @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                           <td> 
                                            <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                            @if (isset($pod->document_number))
                                            <center>
                                                POD#: {{ $pod->document_number }}
                                            </center>
                                               
                                            @endif
                                           
                                        </td>
                                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                     <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                
                                                @if (Auth::user()->employee)
                                                <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                @endif
                                              

                                            </ul>
                                        </div>
                                        @include('trips.delete')

                                </td>
                                      </tr>
                                      @elseif($trip->trip_status == "Loaded")
                                      <tr  style="background-color: #5bc0de" >
                                      
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                        <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                         <td>
                                            @if ($trip->delivery_note)
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                            
                                            @endif
                                        </td>
                                         <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                        <td> 
                                            @if ($trip->invoice_items->count()>0)
                                                <span class="label label-success"> issued</span>
                                            @else   
                                            <span class="label label-warning"> pending</span> 
                                            @endif
                                          </td>
                                            
                                       
                                        @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                            <td> 
                                                <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                                @if (isset($pod->document_number))
                                                <center>
                                                    POD#: {{ $pod->document_number }}
                                                </center>
                                                   
                                                @endif
                                               
                                            </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                    
                                                    @if (Auth::user()->employee)
                                                    <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                    {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                    <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                    @endif
                                                  

                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @elseif($trip->trip_status == "InTransit")
                                      <tr  style="background-color: #1976D2">
                                       
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                        <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                         <td>
                                            @if ($trip->delivery_note)
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                            
                                            @endif
                                        </td>
                                         <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                        <td> 
                                            @if ($trip->invoice_items->count()>0)
                                                <span class="label label-success"> issued</span>
                                            @else   
                                            <span class="label label-warning"> pending</span> 
                                            @endif
                                          </td>
                                            
                                        @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                            <td> 
                                                <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                                @if (isset($pod->document_number))
                                                <center>
                                                    POD#: {{ $pod->document_number }}
                                                </center>
                                                   
                                                @endif
                                               
                                            </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                    @if (Auth::user()->employee)
                                                    <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                    {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                    <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                    @endif
                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @elseif($trip->trip_status == "OnHold")
                                      <tr  style="background-color: #d9534f">
                                      
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                        <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                         <td>
                                            @if ($trip->delivery_note)
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                            
                                            @endif
                                        </td>
                                         <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                      
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                        <td> 
                                            @if ($trip->invoice_items->count()>0)
                                                <span class="label label-success"> issued</span>
                                            @else   
                                            <span class="label label-warning"> pending</span> 
                                            @endif
                                        </td>
                                            @php
                                                $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                            @endphp
                                            <td> 
                                                <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                                @if (isset($pod->document_number))
                                                <center>
                                                    POD#:{{ $pod->document_number }}
                                                </center>
                                                   
                                                @endif
                                               
                                            </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                   @if (Auth::user()->employee)
                                                   <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                   {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                   <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                   <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                   @endif
                                                   

                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @elseif($trip->trip_status == "Offloading Point")
                                      <tr  style="background-color: #82B1FF">
                                      
                                          <td>
                                            {{ucfirst($trip->trip_number)}}
                                            @if ($trip->trip_ref)
                                            /{{$trip->trip_ref}}
                                            @endif
                                        </td>
                                        <td>  {{ $trip->user->employee ? $trip->user->employee->name : "" }} {{ $trip->user->employee ? $trip->user->employee->surname : "" }}</td>
                                        <td>
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->start_date)) )
                                                {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->start_date}}
                                            @endif    
                                        </td>
                                         <td>
                                            @if ($trip->delivery_note)
                                            @php
                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                            @endphp
                                            @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                            @else
                                            {{$trip->delivery_note->offloaded_date}}
                                            @endif    
                                            
                                            @endif
                                        </td>
                                         <td>
                                            {{ucfirst($trip->customer ? $trip->customer->name : "")}}
                                            @if ($trip->cargo)
                                            <hr>  
                                            {{ucfirst($trip->cargo ? $trip->cargo->name : "")}}
                                            @endif 
                                        </td>
                                        <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                                        <td>
                                            @if ($trip->horse)
                                                Horse | {{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} {{ucfirst($trip->horse ? $trip->horse->registration_number : "")}} {{$trip->horse ? "| ".$trip->horse->fleet_number : ""}}
                                            @elseif ($trip->vehicle)
                                               Vehicle | {{ucfirst($trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "")}} {{ucfirst($trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "")}} {{ucfirst($trip->vehicle ? $trip->vehicle->registration_number : "")}}
                                            @endif
                                         
                                            @if (isset($trip->trailers) && $trip->trailers->count()>0)
                                            <hr>
                                                @foreach ($trip->trailers as $trailer)
                                                    {{ $trailer->registration_number }} 
                                                @endforeach
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($from))
                                            {{$from->country ? $from->country->name : ""}} {{ $from->city }}
                                            @endif
                                            @if ($trip->loading_point)
                                                @if (isset($from))
                                                <hr> 
                                                @endif
                                                {{ $trip->loading_point ? $trip->loading_point->name : "" }}
                                            @endif
                                           
                                        </td>
                                        <td>
                                            @if (isset($to))
                                            {{$to->country ? $to->country->name : ""}} {{ $to->city }}
                                            @endif
                                            @if ($trip->offloading_point)
                                                @if (isset($to))
                                                <hr>  
                                                @endif
                                                {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}
                                            @endif
                                        </td>
                                        @if ($trip->trip_status == "Offloaded")
                                        <td class="table-success" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-success label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Scheduled")
                                        <td class="table-warning" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-warning label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loading Point")
                                        <td class="table-gray" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-gray label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Loaded")
                                        <td class="table-info" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-info label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "InTransit")
                                        <td class="table-primary" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-primary label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "OnHold")
                                        <td class="table-danger" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-danger label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light" style="padding-left: 5px; padding-right: 5px;">
                                            <span class="label label-light label-wide">{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent" style="padding-left: 5px; padding-right: 5px;" >
                                            <span class="label label-accent label-wide" >{{$trip->trip_status}} @if($trip->authorization == "approved")
                                                                                                                    <a href="#" wire:click="status({{$trip->id}})" style="margin-left:2px" ><i class="fa fa-edit" style="color:black"></i></a>
                                                                                                                @endif</span>
                                        </td>
                                        @endif
                                        <td> 
                                            @if ($trip->invoice_items->count()>0)
                                                <span class="label label-success"> issued</span>
                                            @else   
                                            <span class="label label-warning"> pending</span> 
                                            @endif
                                          </td>
                                            
                                        @php
                                            $pod = App\Models\TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                                        @endphp
                                            <td> 
                                                <span class="label label-{{isset($pod) ? "success" : "warning"}}"> {{isset($pod) ? "submitted" : "pending"}}</span>
                                                @if (isset($pod->document_number))
                                                <center>
                                                    POD#: {{ $pod->document_number }}
                                                </center>
                                                   
                                                @endif
                                               
                                            </td>
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('trips.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                                                   @if (Auth::user()->employee)
                                                   <li><a href="{{route('trips.trip_sheet', $trip->id)}}"><i class="fas fa-file color-warning"></i> Trip Sheet</a></li>
                                                   {{-- <li><a href="{{route('invoices.create', $trip->id)}}"><i class="fas fa-file-invoice-dollar color-primary"></i> Invoice</a></li> --}}
                                                        <li><a href="{{route('trips.edit', $trip->id)}}"><i class="fas fa-edit color-success"></i> Edit</a></li>
                                                        <li><a href="#" data-toggle="modal" data-target="#tripDeleteModal{{$trip->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                   @endif
                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @endif
                                      @empty
                                      <tr>
                                        <td colspan="14">
                                            <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                                No Trips Found ....
                                            </div>
                                           
                                        </td>
                                      </tr>
                                      @endforelse
                                    </tbody>
                                    @else
                                    <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                                    @endif

                                  </table>
                                  
                                    <nav class="text-center" style="float: right">
                                        <ul class="pagination rounded-corners">
                                            @if (isset($trips))
                                                {{ $trips->links() }} 
                                            @endif 
                                        </ul>
                                    </nav>    

                                <!-- /.col-md-12 -->
                            </div>
                        </div>
                    </div>


                </div>
                <!-- /.row -->

            </div>
            <!-- /.container-fluid -->
        </section>


        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="locationsEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog  mw-100 w-90" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fas fa-edit"></i>Update Trip Status<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="updateTripStatus()" >
                    <div class="modal-body">
                        <h5 class="underline mt-30" style="color: red">Only authorized intransit trips appear on this list</h5>
                        <table  class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                            <thead>
                              <tr>
                                <th class="th-sm">Trip
                                </th>
                                <th class="th-sm">Date
                                </th>
                                <th class="th-sm">Status
                                </th>
                                <th class="th-sm">Country
                                </th>
                                <th class="th-sm">Description
                                </th>
                              </tr>
                            </thead>
                          
                            <tbody>
                                @if ($intransit_trips->count()>0)
                                @forelse ($intransit_trips as $trip)
                              <tr>
                                @php
                                    $from = App\Models\Destination::find($trip->from);
                                    $to = App\Models\Destination::find($trip->to);
                                @endphp
                                <td>
                                    {{ $trip->trip_number }}/{{ $trip->trip_ref }}<br>
                                    @if (isset($from))
                                    {{$from->country ? $from->country->name : ""}} {{$from->city ? $from->city : ""}} : {{$trip->loading_point ? $trip->loading_point->name : ""}} - 
                                    @endif
                                    @if (isset($to))
                                    {{$to->country ? $to->country->name : ""}} {{$to->city ? $to->city : ""}} : {{$trip->offloading_point ? $trip->offloading_point->name : ""}}
                                    @endif
                                      <br>
                                    @if ($trip->horse)
                                    Horse | {{ $trip->horse->horse_make ? $trip->horse->horse_make->name : "" }} {{ $trip->horse->horse_model ? $trip->horse->horse_model->name : "" }} {{ $trip->horse ? $trip->horse->registration_number : "" }}
                                    @elseif ($trip->vehicle)
                                    Vehicle | {{ $trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "" }} {{ $trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "" }} {{ $trip->vehicle ? $trip->vehicle->registration_number : "" }}
                                    @endif
                                    <br>
                                    @if ($trip->driver)
                                    Driver | {{ $trip->driver->employee ? $trip->driver->employee->name : "" }} {{ $trip->driver->employee ? $trip->driver->employee->surname : "" }} <br>
                                    @endif
                                   
                                    Current Status :   
                                    @if ($trip->trip_status == "Offloaded")
                                    <span class="label label-success label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "Scheduled")
                                    <span class="label label-warning label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "Loading Point")
                                    <span class="label label-default label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "Loaded")
                                    <span class="label label-info label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "InTransit")
                                    <span class="label label-primary label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "OnHold")
                                    <span class="label label-danger label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "Cancelled")
                                    <span class="label label-light label-wide">{{$trip->trip_status}}</span>
                                    @elseif($trip->trip_status == "Offloading Point")
                                    <span class="label label-default label-wide">{{$trip->trip_status}}</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="form-group">
                                        <input type="datetime-local" class="form-control" wire:model.debounce.300ms="date.{{$trip->id}}" wire:key="{{ $trip->id }}">
                                        @error('date.'.$trip->id) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" wire:model.debounce.300ms="status.{{$trip->id}}"  wire:key="{{ $trip->id}}" >
                                            <option value="">Select Status</option>
                                            <option value="Scheduled">Scheduled</option>
                                            <option value="Loading Point">Loading Point</option>
                                            <option value="Loaded">Loaded</option>
                                            <option value="InTransit">InTransit</option>
                                            <option value="Offloading Point">Offloading Point</option>
                                            <option value="Offloaded">Offloaded</option>
                                            <option value="OnHold">OnHold</option>
                                            <option value="Cancelled">Cancelled</option>
                                        </select>
                                        @error('status.'.$trip->id) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </td>
                         
                                <td>
                                    <div class="form-group">
                                        <select class="form-control" wire:model.debounce.300ms="country_id.{{$trip->id}}"  wire:key="{{ $trip->id }}" >
                                            <option value="">Select Country</option>
                                            @foreach ($countries as $country)
                                                <option value="{{ $country->id }}">{{ $country->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('category.'.$trip->id) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </td>
                                <td>
                                    <div class="form-group">
                                        <textarea class="form-control" wire:model.debounce.300ms="description.{{$trip->id}}"  wire:key="{{ $trip->id }}" placeholder="Enter Location Description" cols="30" rows="5"></textarea>
                                        @error('description.'.$trip->id) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </td>     
                              </tr>
                              @empty
                              <tr>
                                <td colspan="5">
                                    <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                        No Active Trips Found ....
                                    </div>
                                   
                                </td>
                              </tr>
                             @endforelse
                           
                             
                              @endif
                            </tbody>
                            
                          </table>
                      
                     
               
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

        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="tripDocumentsModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fas fa-copy"></i> Trip Document(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="updateDocuments()" >
                    <div class="modal-body">
                        <h5 class="underline mt-n">Upload Trip Documents (<i> eg Delivery Note</i>)</h5>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Document Title</label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="title.0" placeholder="Enter File Title eg Identity Card">
                                    @error('title.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="file">File</label>
                                     <small style="color:red;">Accepted file types: doc, docx, pdf, xls </small>
                                    <input type="file" class="form-control" wire:model.debounce.300ms="file.0"  placeholder="Upload File ">
                                    @error('file.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <!-- /.col-md-6 -->
                        </div>
                        @foreach ($inputs as $key => $value)
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="title">Document Title</label>
                                    <input type="text" class="form-control" wire:model.debounce.300ms="title.{{$value}}" placeholder="Enter File Title eg Identity Card">
                                    @error('title.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-5">
                                <div class="form-group">
                                    <label for="file">File</label>
                                    <small style="color:red;">Accepted file types: doc, docx, pdf, xls </small>
                                    <input type="file" class="form-control" wire:model.debounce.300ms="file.{{$value}}"  placeholder="Upload File ">
                                    @error('file.'.$value) <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                </div>
                            </div>
                            <!-- /.col-md-6 -->
                        </div>
                        @endforeach
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Document</button>
                                </div>
                            </div>
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
<!-- Modal -->
<div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="statusModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="modal4Label"><i class="fas fa-edit"></i>Update Trip {{ $trip ? $trip->trip_number : "" }} Status <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
            </div>
            <form wire:submit.prevent="update()" >
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Trip Statuses<span class="required" style="color: red">*</span></label>
                                <select class="form-control" wire:model.debounce.300ms="selectedStatus" required>
                                    <option value="">Select Status</option>
                                    <option value="Scheduled">Scheduled</option>
                                    <option value="Loading Point">Loading Point</option>
                                    <option value="Loaded">Loaded</option>
                                    <option value="InTransit">InTransit</option>
                                    <option value="Offloading Point">Offloading Point</option>
                                    <option value="Offloaded">Offloaded</option>
                                    <option value="OnHold">OnHold</option>
                                    <option value="Cancelled">Cancelled</option>
                                </select>
                                @error('selectedStatus') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Trip Status Date<span class="required" style="color: red">*</span></label>
                         <input type="datetime-local"  class="form-control" wire:model.debounce.300ms="trip_status_date" placeholder="Status Date" required>
                            @error('trip_status_date') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                   
                </div>
                <div class="form-group">
                    <label for="title">Trip Status Description</label>
                    <textarea  class="form-control" wire:model.debounce.300ms="trip_status_description" placeholder="Trip status additional notes.." cols="30" rows="4"></textarea>
                    @error('trip_status_description') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>
            


                @if (!is_null($selectedDeliveryNote))

                @if ($selectedStatus == "Loaded")
                @php
                $measurements = App\Models\Measurement::latest()->get();
                 @endphp

                <h5 class="underline mt-30">Freight Calculation Method</h5>
                <div class="mb-10">
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="flat_rate"  class="line-style" disabled />
                    <label for="one" class="radio-label">Flat Rate</label>
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="rate_weight"  class="line-style" disabled />
                    <label for="one" class="radio-label">Rate * Weight/Litreage</label>
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="rate_weight_distance"  class="line-style" disabled />
                    <label for="one" class="radio-label">Rate * Distance * Weight/Litreage</label>
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="rate_distance"  class="line-style" required disabled/>
                    <label for="one" class="radio-label">Rate * Distance</label>
                    @error('freight_calculation') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>

                <h5 class="underline mt-30">Loading Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Date<span class="required" style="color: red">*</span></label>
                                <input type="datetime-local" class="form-control" wire:model.debounce.300ms="loaded_date" placeholder="Enter Loading Date" required >
                                @error('loaded_date') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Distance</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="distance" placeholder="Enter Trip Distance"  >
                                @error('distance') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @if ($cargo_type == "Solid")
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Weight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_weight" placeholder="Enter Loading Weight" required >
                                @error('loaded_weight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @else 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Weight</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_weight" placeholder="Enter Loading Weight" >
                                @error('loaded_weight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                            @if ($cargo_type == "Solid")
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Quantity<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_quantity" placeholder="Enter Loaded Quantity" required >
                                    @error('loaded_quantity') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer">Measurement<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="measurement" required >
                                      <option value="">Select Measurement</option>
                                      @foreach ($measurements as $measurement)
                                      <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                     @endforeach
                                  </select>
                                    @error('measurement') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            @elseif ($cargo_type == "Liquid")
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Litreage @ Ambient</label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_litreage" placeholder="Enter Loaded Litreage @ Ambient Temperature"  >
                                    @error('loaded_litreage') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Litreage @ 20 Degrees<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_litreage_at_20" placeholder="Enter Loaded Litreage @ 20 Degrees" required >
                                    @error('loaded_litreage_at_20') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer">Measurement<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="measurement" required >
                                      <option value="">Select Measurement</option>
                                      @foreach ($measurements as $measurement)
                                      <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                     @endforeach
                                  </select>
                                    @error('measurement') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            @endif
                           
                       
                       
                    </div>
                    @php
                    $employee_department = Auth::user()->employee->departments->first();

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

                @if (Auth::user()->employee->company->rates_managed_by_finance == 1)
                    @if (in_array('Finance', $department_names) ||  in_array('Super Admin', $role_names))
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Currency<span class="required" style="color: red">*</span></label>
                             <select class="form-control" wire:model.debounce.300ms="currency_id" disabled>
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                             </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="loaded_rate" placeholder="Enter Loading Rate" required >
                                @error('loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_freight" placeholder="Enter Loading Freight" required >
                                @error('loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($trip->transporter_agreement == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="transporter_loaded_rate" placeholder="Enter Transporter Loading Rate" required >
                                @error('transporter_loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="transporter_loaded_freight" placeholder="Enter Transporter Loading Freight" required >
                                @error('transporter_loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @else
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Currency<span class="required" style="color: red">*</span></label>
                             <select class="form-control" wire:model.debounce.300ms="currency_id" disabled>
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                             </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="loaded_rate" placeholder="Enter Loading Rate" required >
                                @error('loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_freight" placeholder="Enter Loading Freight" required >
                                @error('loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($trip->transporter_agreement == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="transporter_loaded_rate" placeholder="Enter Transporter Loading Rate" required >
                                @error('transporter_loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="transporter_loaded_freight" placeholder="Enter Transporter Loading Freight" required >
                                @error('transporter_loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                @elseif ($selectedStatus == "Offloaded")
                @php
                $measurements = App\Models\Measurement::latest()->get();
                 @endphp

                <h5 class="underline mt-30">Freight Calculation Method</h5>
                <div class="mb-10">
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="flat_rate"  class="line-style" disabled />
                    <label for="one" class="radio-label">Flat Rate</label>
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="rate_weight"  class="line-style" disabled />
                    <label for="one" class="radio-label">Rate * Weight/Litreage</label>
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="rate_weight_distance"  class="line-style" disabled />
                    <label for="one" class="radio-label">Rate * Distance * Weight/Litreage</label>
                    <input type="radio" wire:model.debounce.300ms="freight_calculation" value="rate_distance"  class="line-style" required disabled/>
                    <label for="one" class="radio-label">Rate * Distance</label>
                    @error('freight_calculation') <span class="text-danger error">{{ $message }}</span>@enderror
                </div>

                <h5 class="underline mt-30">Loading Details</h5>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Date<span class="required" style="color: red">*</span></label>
                                <input type="datetime-local" class="form-control" wire:model.debounce.300ms="loaded_date" placeholder="Enter Loading Date" required disabled>
                                @error('loaded_date') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Distance</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="distance" placeholder="Enter Trip Distance" disabled>
                                @error('distance') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @if ($cargo_type == "Solid")
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Weight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_weight" placeholder="Enter Loading Weight" required disabled>
                                @error('loaded_weight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @else 
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Weight</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_weight" placeholder="Enter Loading Weight" disabled>
                                @error('loaded_weight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                            @if ($cargo_type == "Solid")
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Quantity<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_quantity" placeholder="Enter Loaded Quantity" required disabled>
                                    @error('loaded_quantity') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="customer">Measurement<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="measurement" required disabled>
                                      <option value="">Select Measurement</option>
                                      @foreach ($measurements as $measurement)
                                      <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                     @endforeach
                                  </select>
                                    @error('measurement') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            @elseif ($cargo_type == "Liquid")
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Litreage @ Ambient</label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_litreage" placeholder="Enter Loaded Litreage @ Ambient Temperature" disabled>
                                    @error('loaded_litreage') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Litreage @ 20 Degrees<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_litreage_at_20" placeholder="Enter Loaded Litreage @ 20 Degrees" required disabled>
                                    @error('loaded_litreage_at_20') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer">Measurement<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="measurement" required disabled>
                                      <option value="">Select Measurement</option>
                                      @foreach ($measurements as $measurement)
                                      <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                     @endforeach
                                  </select>
                                    @error('measurement') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            @endif
                           
                       
                       
                    </div>
                    @php
                    $employee_department = Auth::user()->employee->departments->first();

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

                @if (Auth::user()->employee->company->rates_managed_by_finance == 1)
                    @if (in_array('Finance', $department_names) ||  in_array('Super Admin', $role_names))
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Currency<span class="required" style="color: red">*</span></label>
                             <select class="form-control" wire:model.debounce.300ms="currency_id" disabled>
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                             </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="loaded_rate" placeholder="Enter Loading Rate" required disabled>
                                @error('loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_freight" placeholder="Enter Loading Freight" required disabled>
                                @error('loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($trip->transporter_agreement == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="transporter_loaded_rate" placeholder="Enter Transporter Loading Rate" required disabled>
                                @error('transporter_loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="transporter_loaded_freight" placeholder="Enter Transporter Loading Freight" required disabled>
                                @error('transporter_loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @else
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Currency<span class="required" style="color: red">*</span></label>
                             <select class="form-control" wire:model.debounce.300ms="currency_id" disabled>
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                             </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="loaded_rate" placeholder="Enter Loading Rate" required disabled>
                                @error('loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="loaded_freight" placeholder="Enter Loading Freight" required disabled>
                                @error('loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($trip->transporter_agreement == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="transporter_loaded_rate" placeholder="Enter Transporter Loading Rate" required disabled>
                                @error('transporter_loaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="transporter_loaded_freight" placeholder="Enter Transporter Loading Freight" required disabled>
                                @error('transporter_loaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                   
                    <h5 class="underline mt-30">Offloading Details</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Date<span class="required" style="color: red">*</span></label>
                                <input type="datetime-local" class="form-control" wire:model.debounce.300ms="offloaded_date" placeholder="Enter Offloading Date" required>
                                @error('offloaded_date') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Ending Mileage</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="ending_mileage" placeholder="Enter Ending Mileage" >
                                @error('ending_mileage') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            @if ($freight_calculation == "rate_weight_distance" || $freight_calculation == "rate_distance")
                                <div class="form-group">
                                    <label for="title">Distance<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_distance" placeholder="Enter Trip Distance" required>
                                    @error('offloaded_distance') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            @else 
                            <div class="form-group">
                                <label for="title">Distance</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_distance" placeholder="Enter Trip Distance">
                                @error('offloaded_distance') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                            @endif
                          
                        </div>
                        @if ($cargo_type == "Solid")
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Weight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_weight" placeholder="Enter Offloaded Weight" required>
                                @error('offloaded_weight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @else
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Weight</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_weight" placeholder="Enter Offloaded Weight">
                                @error('offloaded_weight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        @endif
                    </div>
                    <div class="row">
                            @if ($cargo_type == "Solid")
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="title">Quantity<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_quantity" placeholder="Enter Offloaded Quantity" required>
                                    @error('offloaded_quantity') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                                 </div>
                                 <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">Measurement<span class="required" style="color: red">*</span></label>
                                      <select class="form-control" wire:model.debounce.300ms="measurement" required >
                                          <option value="">Select Measurement</option>
                                          @foreach ($measurements as $measurement)
                                          <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                         @endforeach
                                      </select>
                                        @error('measurement') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                            @elseif ($cargo_type == "Liquid")
                           
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Litreage @ Ambient</label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_litreage" placeholder="Enter Offloaded Litreage @ Ambient" >
                                    @error('offloaded_litreage') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="title">Litreage @ 20 Degrees<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_litreage_at_20" placeholder="Enter Offloaded Litreage @ 20" required>
                                    @error('offloaded_litreage_at_20') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="customer">Measurement<span class="required" style="color: red">*</span></label>
                                  <select class="form-control" wire:model.debounce.300ms="measurement" required >
                                      <option value="">Select Measurement</option>
                                      @foreach ($measurements as $measurement)
                                      <option value="{{ $measurement->name }}">{{ $measurement->name }}</option>
                                     @endforeach
                                  </select>
                                    @error('measurement') <span class="text-danger error">{{ $message }}</span>@enderror
                                </div>
                            </div>
                            @endif
                       
                      
                       
                    </div>
                    @php
                    $employee_department = Auth::user()->employee->departments->first();

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

                @if (Auth::user()->employee->company->rates_managed_by_finance == 1)
                    @if (in_array('Finance', $department_names) ||  in_array('Super Admin', $role_names))
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Currency<span class="required" style="color: red">*</span></label>
                             <select class="form-control" wire:model.debounce.300ms="currency_id" disabled>
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                             </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="offloaded_rate" placeholder="Offloading Rate" required >
                                @error('offloaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_freight" placeholder="Offloading Freight" required>
                                @error('offloaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($trip->transporter_agreement == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="transporter_offloaded_rate" placeholder="Enter Transporter Offloading Rate" required>
                                @error('transporter_offloaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="transporter_offloaded_freight" placeholder="Enter Transporter Offloading Freight" required>
                                @error('transporter_offloaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    @else 
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Currency<span class="required" style="color: red">*</span></label>
                             <select class="form-control" wire:model.debounce.300ms="currency_id" disabled>
                                <option value="">Select Currency</option>
                                @foreach ($currencies as $currency)
                                    <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                @endforeach
                             </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="offloaded_rate" placeholder="Offloading Rate" required >
                                @error('offloaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="title">Customer Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="offloaded_freight" placeholder="Offloading Freight" required>
                                @error('offloaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @if ($trip->transporter_agreement == TRUE)
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Rate<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any"  class="form-control" wire:model.debounce.300ms="transporter_offloaded_rate" placeholder="Enter Transporter Offloading Rate" required>
                                @error('transporter_offloaded_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Transporter Freight<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="transporter_offloaded_freight" placeholder="Enter Transporter Offloading Freight" required>
                                @error('transporter_offloaded_freight') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    @endif
                    @endif
                    <div class="form-group">
                        <label for="title">Comments</label>
                        <textarea  class="form-control" wire:model.debounce.300ms="comments" placeholder="Write down additional notes..." cols="30" rows="4"></textarea>
                        @error('comments') <span class="text-danger error">{{ $message }}</span>@enderror
                    </div>
                @endif
               

                @endif


               
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
