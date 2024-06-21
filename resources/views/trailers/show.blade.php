@extends('layouts.app')
@section('content')

@section('extra-css')
    @if (Auth::user()->employee)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->employee->company->logo)!!}">
    @elseif (Auth::user()->company)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->company->logo)!!}">
    @elseif (Auth::user()->transporter)
    <link rel="shortcut icon" type = "image/png" href="{!! asset('images/uploads/'.Auth::user()->transporter->company->logo)!!}">
    @endif
@endsection
@section('title')
    Trailers|@if (Auth::user()->employee)
    {{Auth::user()->employee->company->name}}
    @elseif (Auth::user()->company)
    {{Auth::user()->company->name}}
    @elseif (Auth::user()->transporter)
    {{Auth::user()->transporter->company->name}}
    @endif
@endsection

@section('body-id')
<body class="top-navbar-fixed">
@endsection

            <!-- ========== TOP NAVBAR ========== -->
            @if (!Auth::user()->employee && Auth::user()->transporter)
            @include('includes.third_party_navbar')
            @else   
            @include('includes.navbar')
            @endif
          

            <!-- ========== WRAPPER FOR BOTH SIDEBARS & MAIN CONTENT ========== -->
            <div class="content-wrapper">
                <div class="content-container">

                    <!-- ========== LEFT SIDEBAR ========== -->
                    @if (!Auth::user()->employee && Auth::user()->transporter)
                    @include('includes.third_party_sidebar')
                    @else   
                    @include('includes.sidebar')
                    @endif
                    
                    

                    <div class="main-page">
                        <div class="container-fluid">
                            <div class="row page-title-div">
                                <div class="col-md-6">
                                    <h4 class="title">Trailer Details </h4>

                                </div>
                                <!-- /.col-md-6 -->

                                <!-- /.col-md-6 text-right -->
                            </div>
                            <!-- /.row -->
                            <div class="row breadcrumb-div">
                                <div class="col-md-6">
                                    <ul class="breadcrumb">
            							<li><a href="{{route('dashboard.index')}}"><i class="fa fa-home"></i> Home</a></li>
            							<li><a href="{{route('trailers.index')}}"><i class="fas fa-list"></i> Trailers</a></li>
            							<li class="active"><i class="fas fa-eye"></i>Trailer Details</li>
            						</ul>
                                </div>
                                <!-- /.col-md-6 -->

                                <!-- /.col-md-6 -->
                            </div>
                            <!-- /.row -->

                            <div class="row mt-30">
                                <div class="col-md-3">

                                    <div class="panel border-primary no-border border-3-top">
                                        <div class="panel-heading">
                                            <div class="panel-title">
                                                <center><h5>{{$trailer->make}} {{$trailer->model}}</h5></center>
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <div class="col-md-8 col-md-offset-2">
                                                    @php
                                                    if ($trailer->trailer_images->count()>0) {
                                                        $image = $trailer->trailer_images->first();
                                                    }
                                                    @endphp
                                                    @if (isset($image))
                                                    <img src="{{asset('images/uploads/'.$image->filename)}}" alt="trailer Avatar" class="img-responsive">
                                                    @else
                                                    <img src="{{asset('images/trailer.jpg')}}" alt="Trailer Avatar" class="img-responsive">
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
                                              <center> <h5>{{ $trailer->registration_number }} {{ $trailer->fleet_number ? "| ".$trailer->fleet_number : "" }}</h5></center> 
                                            </div>
                                        </div>
                                        <div class="panel-body">
                                            <div class="row">
                                                <table class="table table-striped">
                                                	<tbody>
                                                		<tr>
                                                			<th>Trips</th>
                                                			<td>
                                                                <small class="color-success"><i class="fa fa-arrow-right"></i> {{$trailer->trips->count()}}</small>
                                                            </td>
                                                		</tr>
                                                		<tr>
                                                			<th>Status</th>
                                                			<td>
                                                                <small class="color-success"><i class="fa fa-arrow-right"></i> <span class="badge bg-{{$trailer->status == 1 ? "success" : "danger"}}">{{$trailer->status == 1 ? "Active" : "Inactive"}}</span></small>
                                                            </td>
                                                		</tr>
                                                		<tr>
                                                			<th>No Of Wheels</th>
                                                			<td>
                                                                <small class="color-success"><i class="fa fa-arrow-right"></i> {{ $trailer->no_of_wheels }}</small>
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
                                                <h5>Trailer Tags</h5>
                                            </div>
                                        </div>
                                        <div class="panel-body p-20">
                                            <span class="label label-danger label-rounded label-bordered">{{$trailer->trailer_type ? $trailer->trailer_type->name : ""}}</span>
                                        </div>
                                    </div>
                                    <!-- /.panel -->
                                </div>
                                <!-- /.col-md-3 -->

                                <div class="col-md-9">


                                    <ul class="nav nav-tabs nav-justified" role="tablist">
                                		<li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Basic Info</a></li>
                                		<li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Document(s)</a></li>
                                		<li role="presentation"><a href="#images" aria-controls="images" role="tab" data-toggle="tab">Image(s)</a></li>
                                		<li role="presentation"><a href="#fitness" aria-controls="fitness" role="tab" data-toggle="tab">Reminder(s)</a></li>
                                		<li role="presentation"><a href="#tyres" aria-controls="tyres" role="tab" data-toggle="tab">Tyre(s)</a></li>
                                        <li role="presentation"><a href="#trips" aria-controls="trips" role="tab" data-toggle="tab">Trip(s)</a></li>
                                        <li role="presentation"><a href="#service" aria-controls="service" role="tab" data-toggle="tab">Service</a></li>
                                	</ul>
                                    <div class="tab-content bg-white p-15">
                                		<div role="tabpanel" class="tab-pane active" id="basic">
                                            <table class="table table-striped">

                                                <tbody class="text-center line-height-35 ">
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Trailer#</th>
                                                        <td class="w-20 line-height-35"> {{$trailer->trailer_number}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Transporter</th>
                                                        <td class="w-20 line-height-35"> {{$trailer->transporter ? $trailer->transporter->name : ""}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Fleet#</th>
                                                        <td class="w-20 line-height-35"> {{$trailer->fleet_number}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Registration#</th>
                                                        <td class="w-20 line-height-35"> {{$trailer->registration_number}}</td>
                                                    </tr>
                                                    @php
                                                      
                                                        $trailer_link = App\Models\TrailerLink::where('trailer_a',$trailer->id)
                                                                                               ->orWhere('trailer_b',$trailer->id)->get()->first();
                                                    @endphp
                                                      @if (isset($trailer_link))  
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Trailer Link</th>
                                                            <td class="w-20 line-height-35"> {{App\Models\Trailer::find($trailer_link->trailer_a)->registration_number}} <i class="fas fa-link"></i> {{App\Models\Trailer::find($trailer_link->trailer_b)->registration_number}} </td>
                                                        </tr>
                                                     @endif
                                                  
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Make</th>
                                                        <td class="w-20 line-height-35">{{ucfirst($trailer->make)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Model</th>
                                                        <td class="w-20 line-height-35">{{ucfirst($trailer->model)}}</td>
                                                    </tr>
                                                    <tr>
                                                        <th class="w-10 text-center line-height-35">Chasis Number</th>
                                                        <td class="w-20 line-height-35">{{$trailer->chasis_number}}</td>
                                                    </tr>

                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Year</th>
                                                            <td class="w-20 line-height-35">{{$trailer->year}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Acquisition Date</th>
                                                            <td class="w-20 line-height-35">{{$trailer->start_date}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Dispose Date</th>
                                                            <td class="w-20 line-height-35">{{$trailer->end_date}}</td>
                                                        </tr>

                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Color</th>
                                                            <td class="w-20 line-height-35">{{$trailer->color}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Manufatured By</th>
                                                            <td class="w-20 line-height-35">{{$trailer->manufacturer}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Condition</th>
                                                            <td class="w-20 line-height-35">{{$trailer->condition}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Suspension</th>
                                                            <td class="w-20 line-height-35">{{$trailer->suspension_type}}</td>
                                                        </tr>
                                                        <tr>
                                                            <th class="w-10 text-center line-height-35">Trailer Type</th>
                                                            <td class="w-20 line-height-35"> {{$trailer->trailer_type ? $trailer->trailer_type->name : ""}}</td>
                                                        </tr>

                                                </tbody>
                                            </table>
                                		</div>
                                		<div role="tabpanel" class="tab-pane" id="documents">

                                            @livewire('documents.index', ['id' => $trailer->id,'category'=>'trailer'])
                                		</div>
                                		<div role="tabpanel" class="tab-pane" id="images">

                                            @livewire('trailers.images', ['id' => $trailer->id])
                                		</div>
                                        <div role="tabpanel" class="tab-pane" id="fitness">
                                            @livewire('fitnesses.index', ['id' => $trailer->id, 'category' => "Trailer"])
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
                                            @livewire('trailers.trips', ['id' => $trailer->id])
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
                                                    @if (isset($trailer->bookings))
                                                    @foreach ($trailer->bookings as $booking)
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
                                	</div>
                                </div>
                                <!-- /.col-md-9 -->
                            </div>
                            <!-- /.row -->
                        </div>
                        <!-- /.container-fluid -->


                    </div>


                </div>
                <!-- /.content-container -->
            </div>
            <!-- /.content-wrapper -->


        <!-- ========== PAGE JS FILES ========== -->

@endsection
@section('extra-js')
    <script>
    $(document).ready( function () {
        $('#tripsTable').DataTable();
    } );
    </script>
    <script>
        $(document).ready( function () {
            $('#fitnessesTable').DataTable();
        } );
        </script>
    <script>
        $(document).ready( function () {
            $('#tyre_assignmentsTable').DataTable();
        } );
        </script>
         <script>
            $(document).ready( function () {
                $('#cashflowsTable').DataTable();
            } );
            </script>
         <script>
            $(document).ready( function () {
                $('#bookingsTable').DataTable();
            } );
            </script>
         <script>
            $(document).ready( function () {
                $('#documentsTable').DataTable();
            } );
            </script>
@endsection
