@extends('layouts.app')
@section('content')

@section('extra-css')
    @if (Auth::user()->employee)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->employee->company->logo)!!}">
    @elseif (Auth::user()->company)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->company->logo)!!}">
    @elseif (Auth::user()->transporter)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->transporter->company->logo)!!}">
    @elseif (Auth::user()->customer)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->customer->company->logo)!!}">
    @elseif (Auth::user()->agent)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->agent->company->logo)!!}">
    @endif
@endsection
@section('title')
    Trip | @if (Auth::user()->employee)
    {{Auth::user()->employee->company->name}}
    @elseif (Auth::user()->company)
    {{Auth::user()->company->name}}
    @elseif (Auth::user()->transporter)
    {{Auth::user()->transporter->company->name}}
    @elseif (Auth::user()->customer)
    {{Auth::user()->customer->company->name}}
    @elseif (Auth::user()->agent)
    {{Auth::user()->agent->company->name}}
    @endif
@endsection

@section('body-class')
<body class="top-navbar-fixed">
@endsection
            <!-- ========== TOP NAVBAR ========== -->
           @include('includes.navbar')

            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                  @include('includes.sidebar')
                    <!-- /.left-sidebar -->

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">Trip Details </h4>

                                </div>
                                <!-- /.col-md-6 -->

                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
            							<li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> Home</a></li>
            							<li><a href="{{route('trips.index')}}"><i class="fa fa-list"></i> Trips</a></li>
            							<li class="active"><i class="fa fa-eye"></i> Trip</li>
            						</ul>
                                </div>
                                <!-- /.col-md-6 -->

                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->

                            <section class="section">
                                <div class="container-fluid">
                                    @include('includes.messages')
                                    <div class="row mt-15">
                                        <div class="col-md-12">
                                            <div class="panel">
                                                <div class="panel-heading">
                                                    <div class="panel-title">
                                                        <h5>Trip Number {{$trip->trip_number}} <small>{{$trip->start_date}}</small></h5>
                                                    </div>
                                                </div>
                                                <div class="panel-body">
                                                    <!-- Nav tabs -->
                                                    <ul class="nav nav-tabs border-bottom border-primary" role="tablist">
                                                        <li role="presentation" class="active"><a class="" href="#trip" aria-controls="trip" role="tab" data-toggle="tab">Trip Details</a></li>
                                                        <li role="presentation"><a class="" href="#destinations" aria-controls="destinations" role="tab" data-toggle="tab">Offloading Points</a></li>
                                                        <li role="presentation"><a class="" href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Trip Documents</a></li>
                                                        <li role="presentation"><a class="" href="#expenses" aria-controls="expenses" role="tab" data-toggle="tab">Trip Expenses</a></li>
                                                        <li role="presentation"><a class="" href="#delivery_note" aria-controls="delivery_note" role="tab" data-toggle="tab">Offloading Details</a></li>
                                                        <li role="presentation"><a class="" href="#locations" aria-controls="locations" role="tab" data-toggle="tab">Location Updates</a></li>
                                                        <li role="presentation"><a class="" href="#breakdowns" aria-controls="breakdowns" role="tab" data-toggle="tab">Incident(s)</a></li>
                                                    </ul>


                                                    <!-- Tab panes -->
                                                    <div class="tab-content bg-white pt-30">
                                                        <div role="tabpanel" class="tab-pane active" id="trip">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Trip Details</h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body overflow-x-auto">
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n" >
                                                                                    Trip#
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{$trip->trip_number}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @php
                                                                                $initial_fuel = $trip->fuels->where('fillup',1)->first();
                                                                            @endphp
                                                                            @if ($initial_fuel)
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Fuel Order#
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                 <a href="{{ route('fuels.show',$initial_fuel->id) }}" style="color:blue">{{$initial_fuel->order_number}}</a>
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                          
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Transporter
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    <a href="{{ route('transporters.show',$trip->transporter->id) }}" style="color:blue">   {{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</a>
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Trip Type
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{$trip->trip_type ? $trip->trip_type->name : ""}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->cd3_number)
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        CD3#
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->cd3_number}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                            @if ($trip->cd1_number)
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        CD1#
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->cd1_number}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                            @if ($trip->manifest_number)
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Manifest#
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->manifest_number}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                            @if (isset($trip->borders) && $trip->borders->count()>0)
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Boarder Post
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @foreach ($trip->borders as $border)
                                                                                    {{$border->name}} <br>
                                                                                    @endforeach
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                           
                                                                            @if (isset($trip->clearing_agents) && $trip->clearing_agents->count()>0)
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Clearing Agent
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @foreach ($trip->clearing_agents as $clearing_agent)
                                                                                        <a href="{{ route('clearing_agents.show',$clearing_agent->id) }}" style="color:blue">{{$clearing_agent->name}}</a> <br>
                                                                                    @endforeach
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                          
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Trip Tracking Group
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->trip_group)
                                                                                    <a href="{{ route('trip_groups.show', $trip->trip_group->id) }}">  {{$trip->trip_group ? $trip->trip_group->name : "no group"}}</a>
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->broker)
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                   Broker
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                 <a href="{{ route('brokers.show',$trip->broker->id) }}" style="color:blue">{{ucfirst($trip->broker ? $trip->broker->name : "")}}
                                                                                </div></a>   
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Customer
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    <a href="{{ route('customers.show',$trip->customer->id) }}" style="color:blue"> {{$trip->customer ? $trip->customer->name : ""}}</a>
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->consignee)
                                                                                <div class="col-xs-12 p-n" >
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Consignee
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        <a href="{{ route('consignees.show',$trip->consignee->id) }}" style="color:blue"> {{$trip->consignee ? $trip->consignee->name : ""}}</a>
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                           
                                                                            <!-- /.col-xs-12 -->
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Mode Of Transport
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->horse)
                                                                                    Horse | <a href="{{ route('horses.show',$trip->horse->id) }}" style="color:blue">      {{$trip->horse->horse_make ? $trip->horse->horse_make->name : "" }} {{$trip->horse->horse_model ? $trip->horse->horse_model->name : "" }} {{$trip->horse->registration_number}}</a>
                                                                                    @elseif($trip->vehicle)
                                                                                    Vehicle | <a href="{{ route('vehicles.show',$trip->vehicle->id) }}" style="color:blue">      {{$trip->vehicle->vehicle_make ? $trip->vehicle->vehicle_make->name : "" }} {{$trip->vehicle->vehicle_model ? $trip->vehicle->vehicle_model->name : "" }} {{$trip->vehicle->registration_number}}</a>
                                                                                    @endif


                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Trailer(s)
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->trailers->count()>0)
                                                                                    [ @foreach ($trip->trailers as $trailer)
                                                                                    {{$trailer->make}} {{$trailer->model}} ({{$trailer->registration_number}}),
                                                                                    @endforeach
                                                                                    ]
                                                                                    @endif


                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <!-- /.col-xs-12 -->
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                   Driver
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->driver)
                                                                                    <a href="{{ route('employees.show',$trip->driver->employee->id) }}" style="color:blue">  {{$trip->driver->employee ? $trip->driver->employee->name : ""}} {{ $trip->driver->employee ? $trip->driver->employee->surname : ""}}</a>
                                                                                    @else
                                                                                    
                                                                                    @endif
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                          
                                                                        <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                            <div class="col-xs-6 p-n">
                                                                                From
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                                @if (isset($from))
                                                                                {{$from->country ? $from->country->name : ""}} {{$from->city}}
                                                                                @endif
                                                                                
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        <div class="col-xs-12 p-n">
                                                                            <div class="col-xs-6 p-n">
                                                                               To
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                                @if (isset($to))
                                                                                    {{$to->country ? $to->country->name : ""}} {{$to->city}}
                                                                                @endif  
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        @if ($trip->loading_point)
                                                                        <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                            <div class="col-xs-6 p-n">
                                                                               Loading Point
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                                <a href="{{ route('loading_points.show',$trip->loading_point->id) }}" style="color:blue">{{$trip->loading_point ? $trip->loading_point->name : ""}}</a>
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        @endif
                                                                        @if ($trip->offloading_point)
                                                                        <div class="col-xs-12 p-n">
                                                                            <div class="col-xs-6 p-n">
                                                                               Offloading Point
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                                <a href="{{ route('offloading_points.show',$trip->offloading_point->id) }}" style="color:blue">{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</a>
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                            <div class="col-xs-6 p-n">
                                                                               Route
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                                {{$trip->route ? $trip->route->name : ""}}
                                                                                @if ($trip->route)
                                                                                   | Rank {{$trip->route ? $trip->route->rank : ""}}
                                                                                @endif
                                                                              
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        @if ($trip->truck_stops->count()>0)
                                                                        <div class="col-xs-12 p-n">
                                                                            <div class="col-xs-6 p-n">
                                                                               Recommended Truck Stops
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                              [ @foreach ($trip->truck_stops as $truck_stop)
                                                                              <i class="fas fa-map-pin" style="color:red"></i> {{ $truck_stop->name }}
                                                                               @endforeach]
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        @endif
                                                                        @if ($trip->distance)
                                                                        <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                            <div class="col-xs-6 p-n">
                                                                              Approx Distance
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                               
                                                                                    {{$trip->distance}}Kms
                                                                              
                                                                               
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        @endif
                                                                        <div class="col-xs-12 p-n" >
                                                                            <div class="col-xs-6 p-n">
                                                                              Estimated Trip Fuel
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                            <div class="col-xs-6 p-n">
                                                                                @if ($trip->trip_fuel)
                                                                                    {{$trip->trip_fuel}}Litres
                                                                                @endif
                                                                                
                                                                            </div>
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Trip Start Date
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @php
                                                                                    $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                                                                    @endphp
                                                                                    @if ((preg_match($pattern, $trip->start_date)) )
                                                                                        {{ \Carbon\Carbon::parse($trip->start_date)->format('d M Y g:i A')}}
                                                                                    @else
                                                                                    {{$trip->start_date}}
                                                                                    @endif    
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Estimated Trip End Date
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @php
                                                                                    $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                                                                    @endphp
                                                                                    @if ((preg_match($pattern, $trip->end_date)) )
                                                                                        {{ \Carbon\Carbon::parse($trip->end_date)->format('d M Y g:i A')}}
                                                                                    @else
                                                                                    {{$trip->end_date}}
                                                                                    @endif    
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->delivery_note)
                                                                            @if ($trip->delivery_note->offloaded_date)
                                                                            
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Offloaded Date
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                    @php
                                                                                    $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                                                                    @endphp
                                                                                    @if ((preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                                                        {{ \Carbon\Carbon::parse($trip->delivery_note->offloaded_date)->format('d M Y g:i A')}}
                                                                                    @else
                                                                                    {{$trip->delivery_note->offloaded_date}}
                                                                                    @endif  
                                                                                    
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @endif
                                                                            @if (isset($trip->start_date) && isset($trip->end_date))
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Standard Trip Duration
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @php
                                                                                     $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                                                                    @endphp
                                                                                    @if ((preg_match($pattern, $trip->start_date)) && (preg_match($pattern, $trip->end_date)) )
                                                                                    {{ \Carbon\Carbon::parse($trip->start_date)->diffInDays($trip->end_date) }} Day(s)
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if (isset($trip->start_date) && isset($trip->delivery_note->offloaded_date))
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Actual Trip Duration
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @php
                                                                                    $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                                                                   @endphp
                                                                                   @if ((preg_match($pattern, $trip->start_date)) && (preg_match($pattern, $trip->delivery_note->offloaded_date)) )
                                                                                   {{ \Carbon\Carbon::parse($trip->start_date )->diffInDays($trip->delivery_note->offloaded_date) }} Day(s)
                                                                                   @endif
                                                                                  
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if ($trip->starting_mileage)
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Starting Mileage
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                        {{$trip->starting_mileage}} Kms
                                                                                   
                                                                                    
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if ($trip->ending_mileage)
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Ending Mileage
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                   
                                                                                        {{$trip->ending_mileage}} Kms
                                                                                  
                                                                                    
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if ($trip->distance)
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Approximate Distance
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                   
                                                                                        {{$trip->distance}} Kms
                                                                                  
                                                                                    
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @php
                                                                                if ((isset($trip->starting_mileage) && $trip->starting_mileage > 0) && (isset($trip->ending_mileage) && $trip->ending_mileage > 0)) {
                                                                                    $actual_mileage =   $trip->ending_mileage - $trip->starting_mileage;
                                                                                }
                                                                            @endphp
                                                                              @if (isset($actual_mileage))
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Acutual Distance
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                   
                                                                                  
                                                                                        {{$actual_mileage}}Kms
                                                                                  
                                                                                    
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            
                                                                          
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Trip Status
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->trip_status == "Offloaded")
                                                                                    <td class="table-success"><span class="label label-success label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "Scheduled")
                                                                                    <td class="table-warning" ><span class="label label-warning label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "Loading Point")
                                                                                    <td class="table-default" ><span class="label label-default label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "Loaded")
                                                                                    <td class="table-info"><span class="label label-info label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "InTransit")
                                                                                    <td class="table-primary"><span class="label label-primary label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "OnHold")
                                                                                    <td class="table-danger"><span class="label label-danger label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "Offloading Point")
                                                                                    <td class="table-default"><span class="label label-default label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @elseif($trip->trip_status == "Cancelled")
                                                                                    <td class="table-default"><span class="label label-default label-wide">{{$trip->trip_status}}</span></td>
                                                                                    @endif

                                                                                    @if ($trip->trip_status_date)
                                                                                        @php
                                                                                            $pattern = '/^\d{4}-\d{2}-\d{2}T\d{2}:\d{2}$/';
                                                                                        @endphp
                                                                                        @if ((preg_match($pattern, $trip->trip_status_date)) )
                                                                                            On {{ \Carbon\Carbon::parse($trip->trip_status_date)->format('d M Y g:i A')}}
                                                                                        @else
                                                                                            On {{$trip->trip_status_date}}
                                                                                        @endif  
                                                                                    @endif
                                                                                  

                                                                                    {{-- {{$trip->trip_status}} --}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->trip_status_description)
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Trip Status Description
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->trip_status_description}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div> 
                                                                            @endif
                                                                       
                                                                            <div class="col-xs-12 p-n"  >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Cargo
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{$trip->cargo ? $trip->cargo->name : ""}}
                                                                                    @if ($trip->cargo_details)
                                                                                        |  {{ $trip->cargo_details }}
                                                                                    @endif
                                                                                   
                                                                                    @if (isset($trip->cargo))
                                                                                    @if ($trip->cargo->risk == "High")
                                                                                    <span class="label label-danger"> {{ $trip->cargo ? $trip->cargo->risk : "" }}</span><i> Risk</i>
                                                                                    @elseif($trip->cargo->risk == "Medium")
                                                                                    <span class="label label-warning"> {{ $trip->cargo ? $trip->cargo->risk : "" }}</span><i> Risk</i>
                                                                                    @elseif($trip->cargo->risk == "Low")
                                                                                    <span class="label label-success"> {{ $trip->cargo ? $trip->cargo->risk : "" }}</span><i> Risk</i>
                                                                                    @endif 
                                                                                    @endif
                                                                                 
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if (isset($trip->weight))
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Weight
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->weight ? $trip->weight." tons" : ""}} 
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                            
                                                                           
                                                                            
                                                                            @if (isset($trip->cargo))
                                                                            @if ($trip->cargo->type == "Solid")
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Quantity
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->quantity}} {{$trip->measurement}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>

                                                                            @elseif($trip->cargo->type == "Liquid")
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Litreage @ Ambient Temperature
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->litreage}} {{$trip->measurement}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                <div class="col-xs-12 p-n" >
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Litreage @ 20 Degrees
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->litreage_at_20}} {{$trip->measurement}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>

                                                                            @endif
                                                                            @endif

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
                                                                             @if (Auth::user()->employee->company->rates_managed_by_finance == 1)
                                                                                @if (in_array('Finance', $department_names) ||  in_array('Super Admin', $role_names))
                                                                            
                                                                                @if (!Auth::user()->driver)
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Freight Calculation Method
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if ($trip->freight_calculation == "rate_weight_distance")
                                                                                            Rate * Weight * Distance
                                                                                        @elseif ($trip->freight_calculation == "flat_rate")
                                                                                            Flat Rate
                                                                                        @elseif ($trip->freight_calculation == "rate_distance")
                                                                                        Rate * Distance
                                                                                        @elseif ($trip->freight_calculation == "rate_weight")
                                                                                            Rate * Weight/Litreage
                                                                                        @endif
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @if ($trip->currency)
                                                                                <div class="col-xs-12 p-n" >
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Currency
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->currency->name}}
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if ($trip->rate)
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Customer Rate
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->rate,2)}}
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if ($trip->freight)
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Customer Freight
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->freight,2)}}
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if (isset($trip->exchange_rate) && isset($trip->exchange_customer_freight))
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Customer Freight Conversion
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_customer_freight,2)}} at {{ $trip->exchange_rate}} 
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif

                                                                           
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3" >
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Turn Over
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if ($trip->turnover)
                                                                                        {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover,2)}}
                                                                                        @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @if (isset($trip->exchange_rate) && isset($trip->exchange_customer_turnover))
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Turnover Conversion
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_customer_turnover,2)}} at {{ $trip->exchange_rate}} 
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if ($trip->transporter_rate)
                                                                                <div class="col-xs-12 p-n"  >
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Transporter Rate
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                     
                                                                                        {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->transporter_rate,2)}}
                                                                                      
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if ($trip->transporter_freight)
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Transporter Freight
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                     
                                                                                        {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->transporter_freight,2)}}
                                                                                      
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if (isset($trip->exchange_rate) && (isset($trip->exchange_transporter_freight) && $trip->exchange_transporter_freight > 0))
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Transporter Freight Conversion
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_transporter_freight,2)}} at {{ $trip->exchange_rate}} 
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                            
                                                                     
                                                                               
                                                                                @if ($trip->cost_of_sales)
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Cost Of Sales
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->cost_of_sales,2)}}
                                                                                      
                                                                                      
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if (isset($trip->exchange_rate) && isset($trip->exchange_transporter_cost_of_sales))
                                                                                <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                     Cost Of Sales Conversion
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                        Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_transporter_cost_of_sales,2)}} at {{ $trip->exchange_rate}} 
                                                                                       
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Gross Profit
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if ($trip->turnover)
                                                                                        {{ $trip->currency ? $trip->currency->symbol : "" }}{{number_format($trip->turnover,2)}}
                                                                                        @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @php
                                                                                $total_transporter_expenses = 0;
                                                                                $total_customer_expenses = 0;
                                                                                $total_expenses = 0;
                                                                                    foreach ($trip->trip_expenses as $expense) {
                                                                                        if ($expense->currency_id == Auth::user()->employee->company->currency_id) {
                                                                                            if ($expense->category == "Transporter") {
                                                                                                $total_transporter_expenses = $total_transporter_expenses + $expense->amount;
                                                                                            }
                                                                                            elseif ($expense->category == "Customer") {
                                                                                                $total_customer_expenses = $total_customer_expenses + $expense->amount;
                                                                                            }
                                                                                            elseif ($expense->category == "Self") {
                                                                                                $total_expenses = $total_expenses + $expense->amount;
                                                                                            }
                                                                                        }else{
                                                                                            if ($expense->category == "Transporter") {
                                                                                                $total_transporter_expenses = $total_transporter_expenses + $expense->exchange_amount;
                                                                                            }
                                                                                            elseif ($expense->category == "Customer") {
                                                                                                $total_customer_expenses = $total_customer_expenses + $expense->exchange_amount;
                                                                                            }
                                                                                            elseif ($expense->category == "Self") {
                                                                                                $total_expenses = $total_expenses + $expense->exchange_amount;
                                                                                            }
                                                                                        }
                                                                                    }
                                                                                @endphp
                                                                               
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Trip Expenses
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                      
                                                                                      @if ($total_transporter_expenses)
                                                                                         Total Transporter Expenses: {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : ""}} {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : ""}}{{number_format($total_transporter_expenses,2)}} <br>
                                                                                      @endif
                                                                                      @if ($total_customer_expenses)
                                                                                         Total Customer Expenses: {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : ""}} {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : ""}}{{number_format($total_customer_expenses,2)}} <br>
                                                                                      @endif
                                                                                      @if ($total_expenses)
                                                                                         Total Self Expenses: {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : ""}} {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : ""}}{{number_format($total_expenses,2)}} <br>
                                                                                      @endif
    
                                                                                      
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>

                                                                                @php
                                                                                    if (isset($trip->cost_of_sales) && $trip->cost_of_sales != "" && $trip->cost_of_sales > 0) {
                                                                                        if (isset($trip->turnover) && $trip->turnover != "" && $trip->turnover > 0) {
                                                                                            $net_profit = $trip->turnover - $trip->cost_of_sales;
                                                                                
                                                                                            if((isset($net_profit) && $net_profit > 0) && (isset($trip->turnover) && $trip->turnover > 0)){
                                                                                                $markup_percentage = (($net_profit/$trip->cost_of_sales) * 100);
                                                                                                $net_profit_percentage = (($net_profit/$trip->turnover) * 100);
                                                                                            }
                                                                                        } 
                                                                                    }else {
                                                                                        $net_profit = $trip->turnover ;
                                                                                        $net_profit_percentage = 100 ;
                                                                                        $markup_percentage = 100 ;
                                                                                    }

                                                                                @endphp

                                                                             
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Net Profit
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if ($net_profit)
                                                                                        {{ $trip->currency ? $trip->currency->symbol : "" }}{{number_format($net_profit,2)}}
                                                                                        @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>

                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                    Net Profit Percentage
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if (isset($net_profit_percentage))
                                                                                       {{number_format($net_profit_percentage,2)}}%
                                                                                        @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                              

                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Markup Percentage
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if (isset($markup_percentage))
                                                                                        {{number_format($markup_percentage,2)}}%
                                                                                        @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>

                                                                           
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Payment Status
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        @if ($trip->payment_status == "Pending")
                                                                                        <span class="label label-danger label-wide">{{$trip->payment_status}}</span>
                                                                                        @elseif($trip->payment_status == "Partial Payment")
                                                                                        <span class="label label-warning label-wide">{{$trip->payment_status}}</span>
                                                                                        @elseif($trip->payment_status == "Half Payment")
                                                                                        <span class="label label-info label-wide">{{$trip->payment_status}}</span>
                                                                                        @elseif($trip->payment_status == "Full Payment")
                                                                                        <span class="label label-success label-wide">{{$trip->payment_status}}</span>
                                                                                        @endif
    
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @endif
                                                                                @if ($trip->agent)
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                      Agent
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        <a href="{{ route('agents.show',$trip->agent->id) }}" style="color:blue">   {{$trip->agent->name}} {{$trip->agent->surname}}</a>
    
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                @if ($trip->commission)
                                                                                    <div class="col-xs-12 p-n">
                                                                                        <div class="col-xs-6 p-n">
                                                                                           Agent Commission
                                                                                        </div>
                                                                                        <!-- /.col-md-6 -->
                                                                                        <div class="col-xs-6 p-n">
                                                                                            {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->commission->amount,2)}} @ {{$trip->commission ? $trip->commission->commission : ""}}%
                                                                                        </div>
                                                                                        <!-- /.col-md-6 -->
                                                                                    </div>
                                                                                @endif
                   
                                                                                    
                                                                                @endif
                                                                                
                                                                                @endif
                                                                            
                                                                            @else 
                                                                            
                                                                            @if (!Auth::user()->driver)
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                Freight Calculation Method
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->freight_calculation == "rate_weight_distance")
                                                                                        Rate * Weight * Distance
                                                                                    @elseif ($trip->freight_calculation == "flat_rate")
                                                                                        Flat Rate
                                                                                    @elseif ($trip->freight_calculation == "rate_distance")
                                                                                    Rate * Distance
                                                                                    @elseif ($trip->freight_calculation == "rate_weight")
                                                                                        Rate * Weight/Litreage
                                                                                    @endif
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->currency)
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Currency
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{$trip->currency->name}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Customer Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->rate)
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->rate,2)}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                 Customer Freight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->freight)
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->freight,2)}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if (isset($trip->exchange_rate) && isset($trip->exchange_customer_freight))
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                 Customer Freight Conversion
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                    Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_customer_freight,2)}} at {{ $trip->exchange_rate}} 
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            
                                                                            @if ($trip->transporter_rate)
                                                                            <div class="col-xs-12 p-n"  >
                                                                                <div class="col-xs-6 p-n">
                                                                                  Transporter Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                 
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->transporter_rate,2)}}
                                                                                  
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if ($trip->transporter_freight)
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Transporter Freight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                 
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->transporter_freight,2)}}
                                                                                  
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if (isset($trip->exchange_rate) && (isset($trip->exchange_transporter_freight) && $trip->exchange_transporter_freight > 0))
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                 Transporter Freight Conversion
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                    Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_transporter_freight,2)}} at {{ $trip->exchange_rate}} 
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                          

                                                                            <div class="col-xs-12 p-n"  >
                                                                                <div class="col-xs-6 p-n">
                                                                                 Turn Over
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->turnover)
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->turnover,2)}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            @if ($trip->cost_of_sales)
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                 Cost Of Sales
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->cost_of_sales,2)}}
                                                                                  
                                                                                  
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if (isset($trip->exchange_rate) && isset($trip->exchange_transporter_cost_of_sales))
                                                                            <div class="col-xs-12 p-n"  style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                 Cost Of Sales Conversion
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                    Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip->exchange_transporter_cost_of_sales,2)}} at {{ $trip->exchange_rate}} 
                                                                                   
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif

                                                                            
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Gross Profit
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->turnover)
                                                                                    {{ $trip->currency ? $trip->currency->symbol : "" }}{{number_format($trip->turnover,2)}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @php
                                                                            $total_transporter_expenses = 0;
                                                                            $total_customer_expenses = 0;
                                                                            $total_expenses = 0;
                                                                                foreach ($trip->trip_expenses as $expense) {
                                                                                    if ($expense->currency_id == Auth::user()->employee->company->currency_id) {
                                                                                        if ($expense->category == "Transporter") {
                                                                                            $total_transporter_expenses = $total_transporter_expenses + $expense->amount;
                                                                                        }
                                                                                        elseif ($expense->category == "Customer") {
                                                                                            $total_customer_expenses = $total_customer_expenses + $expense->amount;
                                                                                        }
                                                                                        elseif ($expense->category == "Self") {
                                                                                            $total_expenses = $total_expenses + $expense->amount;
                                                                                        }
                                                                                    }else{
                                                                                        if ($expense->category == "Transporter") {
                                                                                            $total_transporter_expenses = $total_transporter_expenses + $expense->exchange_amount;
                                                                                        }
                                                                                        elseif ($expense->category == "Customer") {
                                                                                            $total_customer_expenses = $total_customer_expenses + $expense->exchange_amount;
                                                                                        }
                                                                                        elseif ($expense->category == "Self") {
                                                                                            $total_expenses = $total_expenses + $expense->exchange_amount;
                                                                                        }
                                                                                    }
                                                                                }
                                                                            @endphp
                                                                           
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                Trip Expenses
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  
                                                                                  @if ($total_transporter_expenses)
                                                                                     Total Transporter Expenses: {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : ""}} {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : ""}}{{number_format($total_transporter_expenses,2)}} <br>
                                                                                  @endif
                                                                                  @if ($total_customer_expenses)
                                                                                     Total Customer Expenses: {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : ""}} {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : ""}}{{number_format($total_customer_expenses,2)}} <br>
                                                                                  @endif
                                                                                  @if ($total_expenses)
                                                                                     Total Self Expenses: {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : ""}} {{Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : ""}}{{number_format($total_expenses,2)}} <br>
                                                                                  @endif

                                                                                  
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            @php
                                                                                if (isset($trip->cost_of_sales) && $trip->cost_of_sales != "" && $trip->cost_of_sales > 0) {
                                                                                    if (isset($trip->turnover) && $trip->turnover != "" && $trip->turnover > 0) {
                                                                                        $net_profit = $trip->turnover - $trip->cost_of_sales;
                                                                            
                                                                                        if((isset($net_profit) && $net_profit > 0) && (isset($trip->turnover) && $trip->turnover > 0)){
                                                                                            $markup_percentage = (($net_profit/$trip->cost_of_sales) * 100);
                                                                                            $net_profit_percentage = (($net_profit/$trip->turnover) * 100);
                                                                                        }
                                                                                    } 
                                                                                }else {
                                                                                    $net_profit = $trip->turnover ;
                                                                                    $net_profit_percentage = 100 ;
                                                                                    $markup_percentage = 100 ;
                                                                                }

                                                                            @endphp

                                                                         
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                Net Profit
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($net_profit)
                                                                                    {{ $trip->currency ? $trip->currency->symbol : "" }}{{number_format($net_profit,2)}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                Net Profit Percentage
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($net_profit_percentage))
                                                                                    {{number_format($net_profit_percentage,2)}}%
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                          

                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Markup Percentage
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($markup_percentage))
                                                                                    {{number_format($markup_percentage,2)}}%
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Payment Status
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if ($trip->payment_status == "Pending")
                                                                                    <span class="label label-danger label-wide">{{$trip->payment_status}}</span>
                                                                                    @elseif($trip->payment_status == "Partial Payment")
                                                                                    <span class="label label-warning label-wide">{{$trip->payment_status}}</span>
                                                                                    @elseif($trip->payment_status == "Half Payment")
                                                                                    <span class="label label-info label-wide">{{$trip->payment_status}}</span>
                                                                                    @elseif($trip->payment_status == "Full Payment")
                                                                                    <span class="label label-success label-wide">{{$trip->payment_status}}</span>
                                                                                    @endif

                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if ($trip->agent)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Agent
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    <a href="{{ route('agents.show',$trip->agent->id) }}" style="color:blue">   {{$trip->agent->name}} {{$trip->agent->surname}}</a>

                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->commission)
                                                                                <div class="col-xs-12 p-n">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Commission
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                        {{$trip->commission ? $trip->commission->commission : ""}}%
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                           
                                                                            @if ($trip->commission)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Amount
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                   
                                                                                    {{$trip->currency ? $trip->currency->symbol : ""}}{{number_format($trip->commission->amount,2)}}
                                                                                   

                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                                
                                                                            @endif

                                                                            @endif
                                                                            <!-- end of finance department-->

                                                                            @if ($trip->comments)
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Trip Comments
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{$trip->comments}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @if (isset($trip->authorized_by_id))
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Authorized By
                                                                                </div>
                                                                                @php
                                                                                    $user = App\Models\User::find($trip->authorized_by_id);
                                                                                @endphp
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                  {{ $user->name }} {{ $user->surname }}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Authorization
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    <span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span>
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->reason)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                  Authorization Comments
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{$trip->reason}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                           
                                                                       
                                                                            <br>
                                                                            <br>
                                                                            <!-- /.col-xs-12 -->
                                                                          

                                                                        </div>



                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                    <!-- /.panel -->

                                                                </div>

                                                            </div>

                                                        </div>
                                                        <!-- /.tab-pane -->


                                                        <div role="tabpanel" class="tab-pane" id="destinations">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Trip Offloading Points</h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body overflow-x-auto">
                                                                           @livewire('trips.destinations', ['id' => $trip->id])
                                                                        </div>
                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                    <!-- /.panel -->

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div role="tabpanel" class="tab-pane" id="documents">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Trip Document(s)</h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body overflow-x-auto">
                                                                           @livewire('trips.documents', ['id' => $trip->id])

                                                                        </div>
                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                    <!-- /.panel -->

                                                                </div>

                                                            </div>
                                                        </div>
                                                   
                                                        <div role="tabpanel" class="tab-pane" id="expenses">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Trip Expenses</h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body overflow-x-auto">
                                                                           @livewire('trips.expenses', ['id' => $trip->id])
                                                                        </div>
                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                     
                                                        <div role="tabpanel" class="tab-pane" id="delivery_note">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Offloading Details</h5>
                                                                            </div>
                                                                        </div>
                                                                        @php
                                                                            if ($trip->cargo) {
                                                                                $cargo_type = $trip->cargo->type;
                                                                            }
                                                                          
                                                                        @endphp
                                                                       
                                                                        @if ($trip->delivery_note)
                                                                            @if (isset($cargo_type))      
                                                                        <div class="panel-body overflow-x-auto">
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Loading Date
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_date))
                                                                                {{$trip->delivery_note->loaded_date}}
                                                                                @else
                                                                                No Loading Date Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($cargo_type == "Solid")
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Loaded Quantity
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_quantity))
                                                                                {{$trip->delivery_note->loaded_quantity}}  {{$trip->delivery_note->measurement}}
                                                                                @else
                                                                                No Loaded Quantity Recorded
                                                                                @endif
                                                                            </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @elseif ($cargo_type == "Liquid")
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Loaded Litreage @ Ambient
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_litreage))
                                                                                {{$trip->delivery_note->loaded_litreage}}  {{$trip->delivery_note->measurement}}
                                                                                @else
                                                                                No Loaded Litreage @ Ambient Recorded
                                                                                @endif
                                                                            </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Loaded Litreage @ 20
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_litreage_at_20))
                                                                                {{$trip->delivery_note->loaded_litreage_at_20}}  {{$trip->delivery_note->measurement}}
                                                                                @else
                                                                                No Loaded Litreage @ 20 Recorded
                                                                                @endif
                                                                            </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                           
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Loaded Weight 
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_weight))
                                                                                {{$trip->delivery_note->loaded_weight ? $trip->delivery_note->loaded_weight." tons" : ""}}
                                                                                @else
                                                                                No Loaded Weight Recorded
                                                                                @endif
                                                                            </div>
                                                                                <!-- /.col-md-6 -->
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
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                   Customer Loading Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_rate))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }}  {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_rate}}
                                                                                    @else
                                                                                    No Customer Loading Rate Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Transporter Loading Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_rate))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }}  {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_rate}}
                                                                                    @else
                                                                                    No Transporter  Loading Rate Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                   Customer Loading Freight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_freight))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_freight}}
                                                                                    @else
                                                                                    No Customer Loading Freight Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                   Transporter Loading Freight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_freight))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_freight}}
                                                                                    @else
                                                                                    No Transporter  Loading Freight Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @endif
                                                                            @else   
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                   Customer Loading Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_rate))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }}  {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_rate}}
                                                                                    @else
                                                                                    No Customer Loading Rate Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Transporter Loading Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_rate))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }}  {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_rate}}
                                                                                    @else
                                                                                    No Transporter  Loading Rate Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                   Customer Loading Freight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_freight))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_freight}}
                                                                                    @else
                                                                                    No Customer Loading Freight Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                   Transporter Loading Freight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->loaded_freight))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->loaded_freight}}
                                                                                    @else
                                                                                    No Transporter  Loading Freight Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @endif
                                                                            @endif
                                                                            <div class="col-xs-12 p-n" > 
                                                                                <div class="col-xs-6 p-n">
                                                                                    Offloading Date
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->offloaded_date))
                                                                                    {{$trip->delivery_note->offloaded_date}}
                                                                                    @else
                                                                                    No Offloading Date Recorded
                                                                                    @endif

                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($cargo_type == "Solid")
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Offloaded Quantity 
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->offloaded_quantity))
                                                                                        {{$trip->delivery_note->offloaded_quantity}}  {{$trip->delivery_note->measurement}}
                                                                                        @else
                                                                                        No Offloaded Quantity Recorded
                                                                                    @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @elseif($cargo_type == "Liquid")
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Offloaded Litreage @ Ambient
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->offloaded_litreage))
                                                                                        {{$trip->delivery_note->offloaded_litreage}}  {{$trip->delivery_note->measurement}}
                                                                                        @else
                                                                                        No Offloaded Litreage @ Ambient Recorded
                                                                                    @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                                <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                    <div class="col-xs-6 p-n">
                                                                                        Offloaded Litreage @ 20
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                    <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->offloaded_litreage_at_20))
                                                                                        {{$trip->delivery_note->offloaded_litreage_at_20}}  {{$trip->delivery_note->measurement}}
                                                                                        @else
                                                                                        No Offloaded Litreage @ 20 Recorded
                                                                                    @endif
                                                                                    </div>
                                                                                    <!-- /.col-md-6 -->
                                                                                </div>
                                                                            @endif
                                                                           
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Offloaded Weight
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->offloaded_weight))
                                                                                    {{$trip->delivery_note->offloaded_weight ? $trip->delivery_note->offloaded_weight." tons" : "" }}
                                                                                    @else
                                                                                    No Offloaded Weight Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
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
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Customer Offloading Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->offloaded_rate))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->offloaded_rate}}
                                                                                    @else
                                                                                    No Customer Offloading Rate Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Transporter Offloading Rate
                                                                                </div>
                                                                            
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->transporter_offloaded_rate))
                                                                                        {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->transporter_offloaded_rate}}
                                                                                    @else
                                                                                        No Transporter Offloading Rate Recorded
                                                                                    @endif
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Customer Offloading Freight
                                                                                </div>
                                                                               
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->offloaded_freight))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->offloaded_freight}}
                                                                                    @else
                                                                                    No Customer Offloading Freight Recorded
                                                                                @endif
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Transporter Offloading Freight
                                                                                </div>
                                                                             
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->transporter_offloaded_freight))
                                                                                        {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->transporter_offloaded_freight}}
                                                                                    @else
                                                                                        No Transporter Offloading Freight Recorded
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            @endif
                                                                            @endif
                                                                            @else   
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Customer Offloading Rate
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->offloaded_rate))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->offloaded_rate}}
                                                                                    @else
                                                                                    No Customer Offloading Rate Recorded
                                                                                @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Transporter Offloading Rate
                                                                                </div>
                                                                            
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->transporter_offloaded_rate))
                                                                                        {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->transporter_offloaded_rate}}
                                                                                    @else
                                                                                        No Transporter Offloading Rate Recorded
                                                                                    @endif
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            @endif
                                                                            <div class="col-xs-12 p-n">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Customer Offloading Freight
                                                                                </div>
                                                                               
                                                                                <div class="col-xs-6 p-n">
                                                                                @if (isset($trip->delivery_note->offloaded_freight))
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->offloaded_freight}}
                                                                                    @else
                                                                                    No Customer Offloading Freight Recorded
                                                                                @endif
                                                                                </div>
                                                                                
                                                                            </div>
                                                                            @if ($trip->transporter_agreement == TRUE)
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3">
                                                                                <div class="col-xs-6 p-n">
                                                                                    Transporter Offloading Freight
                                                                                </div>
                                                                             
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->transporter_offloaded_freight))
                                                                                        {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{$trip->delivery_note->transporter_offloaded_freight}}
                                                                                    @else
                                                                                        No Transporter Offloading Freight Recorded
                                                                                    @endif
                                                                                </div>
                                                                            </div>
                                                                            @endif
                                                                            @endif
                                                                            <div class="col-xs-12 p-n" style="background-color: #D3D3D3" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Comments
                                                                                </div>
                                                                             
                                                                                <div class="col-xs-6 p-n">
                                                                                    @if (isset($trip->delivery_note->comments))
                                                                                   {{ $trip->delivery_note->comments }}
                                                                                   @else 
                                                                                   No comments recorded
                                                                                   @endif
                                                                                </div>
                                                                            </div>
                                                                            <hr>
                                                                            <br>
                                                                            <h5 class="underline mt-30">Trip Loss Details</h5>
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Weight Loss
                                                                                </div>
                                                                                @php
                                                                                    if ((isset($trip->delivery_note->loaded_weight) && $trip->delivery_note->loaded_weight > 0 ) && ( isset($trip->delivery_note->offloaded_weight) && $trip->delivery_note->offloaded_weight > 0 )) {
                                                                                        $weight_loss = $trip->delivery_note->loaded_weight - $trip->delivery_note->offloaded_weight;
                                                                                    }else {
                                                                                        $weight_loss = Null;
                                                                                    }
                                                                                @endphp
                                                                                <!-- /.col-md-6 -->
                                                                                @if (isset($weight_loss) && $weight_loss > 0)
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                 {{ $weight_loss }} Tons
                                                                                </div>
                                                                                @elseif (isset($weight_loss) && $weight_loss == 0)
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{ $weight_loss }} Tons
                                                                                   </div>
                                                                                @else
                                                                                <div class="col-xs-6 p-n">
                                                                                No Offloaded Weight Recorded
                                                                                </div>
                                                                                @endif
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Allowable Weight Loss
                                                                                </div>
                                                                               
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                    {{ $trip->allowable_loss_weight ? $trip->allowable_loss_weight."Tons" : "" }} 
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Chargeable Weight Loss
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                @php
                                                                                    if ((isset($weight_loss) && $weight_loss > 0) && (isset($trip->allowable_loss_weight) && $trip->allowable_loss_weight > 0)) {
                                                                                     $chargeable_weight_loss =   $weight_loss - $trip->allowable_loss_weight;
                                                                                    }
                                                                                @endphp
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                    @if (isset($chargeable_weight_loss))
                                                                                        {{  $chargeable_weight_loss ?  $chargeable_weight_loss." Tons" : "" }}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            
                                                                            
                                                                           @if ($cargo_type == "Solid")
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Quantity Loss
                                                                                </div>
                                                                                @php
                                                                                    if ((isset($trip->delivery_note->loaded_quantity) && $trip->delivery_note->loaded_quantity > 0 ) && (isset($trip->delivery_note->offloaded_quantity) && $trip->delivery_note->offloaded_quantity > 0) ) {
                                                                                        $quantity_loss = $trip->delivery_note->loaded_quantity - $trip->delivery_note->offloaded_quantity;
                                                                                    }else {
                                                                                        $quantity_loss = Null;
                                                                                    }
                                                                                @endphp
                                                                                <!-- /.col-md-6 -->
                                                                                @if (isset($quantity_loss) && $quantity_loss > 0)
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                 {{ $quantity_loss }} {{$trip->delivery_note->measurement}}
                                                                                </div>
                                                                                @elseif (isset($quantity_loss) && $quantity_loss == 0)
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{ $quantity_loss }} {{$trip->delivery_note->measurement}}
                                                                                   </div>
                                                                                @else
                                                                                <div class="col-xs-6 p-n">
                                                                                No Offloading Quantity Recorded
                                                                                </div>
                                                                                @endif
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Allowable Quantity Loss
                                                                                </div>
                                                                               
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                    @if (isset($trip->allowable_loss_quantity))
                                                                                        {{ $trip->allowable_loss_quantity ? $trip->allowable_loss_quantity : "" }} {{$trip->delivery_note->measurement}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Chargeable Quantity Loss
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                @php
                                                                                    if ((isset($quantity_loss) && $quantity_loss > 0) && (isset($trip->allowable_loss_quantity) && $trip->allowable_loss_quantity > 0)) {
                                                                                     $chargeable_quantity_loss =   $quantity_loss - $trip->allowable_loss_quantity;
                                                                                    }
                                                                                @endphp
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                    @if (isset($chargeable_quantity_loss))
                                                                                        {{  $chargeable_quantity_loss ?  $chargeable_quantity_loss : "" }} {{$trip->delivery_note->measurement}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>

                                                                        @elseif($cargo_type == "Liquid")
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Litreage Loss @ Ambient Temperature
                                                                                </div>
                                                                                @php
                                                                                    if ((isset($trip->delivery_note->loaded_litreage) && $trip->delivery_note->loaded_litreage > 0) && (isset($trip->delivery_note->offloaded_litreage) && $trip->delivery_note->offloaded_litreage > 0)) {
                                                                                        $litreage_loss = $trip->delivery_note->loaded_litreage - $trip->delivery_note->offloaded_litreage;
                                                                                    }else{
                                                                                        $litreage_loss = Null;
                                                                                    }
                                                                                @endphp
                                                                                <!-- /.col-md-6 -->
                                                                                @if (isset($litreage_loss) && $litreage_loss > 0)
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                 {{ $litreage_loss }} {{$trip->delivery_note->measurement}}
                                                                                </div>
                                                                                @elseif (isset($litreage_loss) && $litreage_loss == 0)
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{ $litreage_loss }} {{$trip->delivery_note->measurement}}
                                                                                   </div>
                                                                                @else
                                                                                <div class="col-xs-6 p-n">
                                                                                No Offloaded Litreage recorded
                                                                                </div>
                                                                                @endif
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Litreage Loss @ 20 Degrees
                                                                                </div>
                                                                                @php
                                                                                    if ((isset($trip->delivery_note->loaded_litreage_at_20) && $trip->delivery_note->loaded_litreage_at_20 > 0 ) && (isset($trip->delivery_note->offloaded_litreage_at_20) && $trip->delivery_note->offloaded_litreage_at_20 > 0)) {
                                                                                        $litreage_at_20_loss = $trip->delivery_note->loaded_litreage_at_20 - $trip->delivery_note->offloaded_litreage_at_20;
                                                                                    }else {
                                                                                        $litreage_at_20_loss = Null;
                                                                                    }
                                                                                @endphp
                                                                                <!-- /.col-md-6 -->
                                                                                @if (isset($litreage_at_20_loss) && $litreage_at_20_loss > 0)
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                 {{ $litreage_at_20_loss }} {{$trip->delivery_note->measurement}}
                                                                                </div>
                                                                                @elseif (isset($litreage_at_20_loss) && $litreage_at_20_loss == 0)
                                                                                <div class="col-xs-6 p-n">
                                                                                    {{ $litreage_at_20_loss }} {{$trip->delivery_note->measurement}}
                                                                                   </div>
                                                                                @else
                                                                                <div class="col-xs-6 p-n">
                                                                                No Offloaded Litreage recorded
                                                                                </div>
                                                                                @endif
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Allowable Litreage Loss
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                    {{ $trip->allowable_loss_litreage ? $trip->allowable_loss_litreage : "" }} {{$trip->delivery_note->measurement}}
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                            <div class="col-xs-12 p-n" >
                                                                                <div class="col-xs-6 p-n">
                                                                                    Chargeable Litreage Loss
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                                @php
                                                                                    if ((isset($litreage_at_20_loss) && $litreage_at_20_loss > 0) && (isset($trip->allowable_loss_litreage) && $trip->allowable_loss_litreage > 0)) {
                                                                                     $chargeable_litreage_loss =   $litreage_at_20_loss - $trip->allowable_loss_litreage;
                                                                                    }
                                                                                @endphp
                                                                                <div class="col-xs-6 p-n" style="color: red">
                                                                                    @if (isset($chargeable_litreage_loss))
                                                                                        {{  $chargeable_litreage_loss ?  $chargeable_litreage_loss : "" }} {{$trip->delivery_note->measurement}}
                                                                                    @endif
                                                                                </div>
                                                                                <!-- /.col-md-6 -->
                                                                            </div>
                                                                        @endif
                                                                         <div class="col-xs-12 p-n">
                                                                            <div class="col-xs-6 p-n">
                                                                                Freight Loss
                                                                            </div>
                                                                            @php
                                                                                if ((isset($trip->delivery_note->loaded_freight) && $trip->delivery_note->loaded_freight > 0) && (isset($trip->delivery_note->offloaded_freight) && $trip->delivery_note->offloaded_freight > 0)) {
                                                                                    $freight_loss = $trip->delivery_note->loaded_freight - $trip->delivery_note->offloaded_freight;
                                                                                }else {
                                                                                    $freight_loss = Null;
                                                                                }
                                                                            @endphp
                                                                            <!-- /.col-md-6 -->
                                                                            @if (isset($freight_loss) && $freight_loss > 0)
                                                                            <div class="col-xs-6 p-n" style="color: red">
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{ number_format($freight_loss,2) }}
                                                                            </div>
                                                                            @elseif (isset($freight_loss) && $freight_loss == 0)
                                                                            <div class="col-xs-6 p-n">
                                                                                {{ $trip->currency ? $trip->currency->name : "" }} {{ $trip->currency ? $trip->currency->symbol : "" }}{{ number_format($freight_loss,2) }}
                                                                               </div>
                                                                            @else
                                                                            <div class="col-xs-6 p-n">
                                                                                No Offloaded Freight Recorded
                                                                                </div>
                                                                            @endif
                                                                            <!-- /.col-md-6 -->
                                                                        </div>
                                                                        </div>
                                                                        @endif
                                                                        @endif
                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                    <!-- /.panel -->

                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div role="tabpanel" class="tab-pane" id="locations">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Trip Location Update(s)</h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body overflow-x-auto">
                                                                           @livewire('trips.locations', ['id' => $trip->id])
                                                                        </div>
                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                    <!-- /.panel -->
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div role="tabpanel" class="tab-pane" id="breakdowns">
                                                            <div class="col-md-12 p-n">
                                                                <div class="col-md-12">
                                                                    <div class="panel panel-info">
                                                                        <div class="panel-heading">
                                                                            <div class="panel-title">
                                                                                <h5>Trip Incident(s)</h5>
                                                                            </div>
                                                                        </div>
                                                                        <div class="panel-body overflow-x-auto">
                                                                           @livewire('trips.breakdowns', ['id' => $trip->id])
                                                                        </div>
                                                                        <!-- /.panel-body -->
                                                                    </div>
                                                                    <!-- /.panel -->
                                                                </div>

                                                            </div>
                                                        </div>
                                                        <div class="row">
                                                            <div class="col-md-12">
                                                                <div class="btn-group pull-right mt-10" >
                                                                    @livewire('trips.show',['id'=>$trip->id])
                                                                </div>
                                                               
                                                            </div>
                                                            </div>
                                                       
                                                    </div>
                                                    <!-- /.tab-content -->
                                                </div>
                                                <!-- /.panel-body -->
                                            </div>
                                            <!-- /.panel -->
                                        </div>
                                        <!-- /.col-md-12 -->
                                    </div>
                                    <!-- /.row -->
                                </div>
                                <!-- /.container-fluid -->
                            </section>
                 
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->


                    </div>
                    <!-- /.main-page -->


                    <!-- /.right-sidebar -->

                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->


        <!-- ========== PAGE JS FILES ========== -->

@endsection
@section('extra-js')
<script>
    window.addEventListener("load", window.print());
  </script>
  <script>
    $(document).ready( function () {
        $('#documentsTable').DataTable();
    } );
    </script>
  <script>
    $(document).ready( function () {
        $('#tripExpensesTable').DataTable();
    } );
    </script>
  <script>
    $(document).ready( function () {
        $('#paymentsTable').DataTable();
    } );
    </script>
  <script>
    $(document).ready( function () {
        $('#trip_locationsTable').DataTable();
    } );
    </script>
  <script>
    $(document).ready( function () {
        $('#trip_destinationsTable').DataTable();
    } );
    </script>
@endsection
