<div>
    <section class="section">
        <div class="container-fluid">
            <section class="section ">
                <div class="container-fluid">
                    <div class="row">

                        <div class="col-md-12">
                            <div class="panel panel-danger">
                                <div class="panel-heading">
                                    <div class="panel-title">
                                        <h5>Trip Management</h5>
                                    </div>
                                </div>
                                <div class="panel-body overflow-x-auto">
                                    <div class="panel-title">
                                        <h5>Latest 5 records</h5>
                                    </div>
                 
            <table id="tripsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%" style=" width:100%; height:100%;">
                <thead >
                    <th class="th-sm">Trip#
                    </th>
                    <th class="th-sm">Customer
                    </th>
                    <th class="th-sm">Transporter
                    </th>
                    <th class="th-sm">Horse
                    </th>
                    <th class="th-sm">LP
                    </th>
                    <th class="th-sm">OP
                    </th>
                    <th class="th-sm">Status
                    </th>
                    <th class="th-sm">Invoice
                    </th>
                    <th class="th-sm">PODS
                    </th>
                    <th class="th-sm">Authorization
                    </th>
                    <th class="th-sm">Actions
                    </th>

                  </tr>
                </thead>
                @if ($trips->count()>0)
                <tbody>
                    @foreach ($trips as $trip)
                    @if ($trip->trip_status == "Offloaded")
                  <tr style="background-color: #5cb85c">
                    @php
                        $from = App\Models\Destination::find($trip->from);
                        $to = App\Models\Destination::find($trip->to);
                        $date = Carbon\Carbon::parse($trip->start_date);
                    @endphp
                    <td>{{ucfirst($trip->trip_number)}}</td>
                    <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                    @if ($trip->horse)
                    <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                    @else
                        <td></td>
                    @endif
                    
                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                    <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
                   
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
                     <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
                     <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                     <td class="w-10 line-height-35 table-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                            </ul>
                        </div>
                        @include('trips.delete')

                </td>
                  </tr>
                  @elseif($trip->trip_status == "Scheduled")
                  <tr style="background-color: #f0ad4e" >
                    @php
                        $from = App\Models\Destination::find($trip->from);
                        $to = App\Models\Destination::find($trip->to);
                        $date = Carbon\Carbon::parse($trip->start_date);
                    @endphp
                    <td>{{ucfirst($trip->trip_number)}}</td>
                    <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                    @if ($trip->horse)
                    <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                    <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
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
                    <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
                    
                    <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                     <td class="w-10 line-height-35 table-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                            </ul>
                        </div>
                     

                </td>
                  </tr>
                  @elseif($trip->trip_status == "Loading Point")
                  <tr  style="background-color: #adb5bd" >
                    @php
                    $from = App\Models\Destination::find($trip->from);
                    $to = App\Models\Destination::find($trip->to);
                    $date = Carbon\Carbon::parse($trip->start_date);
                @endphp
                <td>{{ucfirst($trip->trip_number)}}</td>
                <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                @if ($trip->horse)
                <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                @else
                    <td></td>
                @endif
                <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
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
                <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
               
                <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                 <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                 <td class="w-10 line-height-35 table-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                        </ul>
                    </div>
                    @include('trips.delete')

            </td>
                  </tr>
                  @elseif($trip->trip_status == "Loaded")
                  <tr  style="background-color: #5bc0de" >
                    @php
                        $from = App\Models\Destination::find($trip->from);
                        $to = App\Models\Destination::find($trip->to);
                        $date = Carbon\Carbon::parse($trip->start_date);
                    @endphp
                    <td>{{ucfirst($trip->trip_number)}}</td>
                    <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                    @if ($trip->horse)
                    <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                    <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
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
                    <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
                   
                    <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                     <td class="w-10 line-height-35 table-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                            </ul>
                        </div>
                        @include('trips.delete')

                </td>
                  </tr>
                  @elseif($trip->trip_status == "InTransit")
                  <tr  style="background-color: #1976D2">
                    @php
                        $from = App\Models\Destination::find($trip->from);
                        $to = App\Models\Destination::find($trip->to);
                        $date = Carbon\Carbon::parse($trip->start_date);
                    @endphp
                    <td>{{ucfirst($trip->trip_number)}}</td>
                    <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                    @if ($trip->horse)
                    <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                    <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
                   
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
                    <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
                    <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                     <td class="w-10 line-height-35 table-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                            </ul>
                        </div>
                        @include('trips.delete')

                </td>
                  </tr>
                  @elseif($trip->trip_status == "OnHold")
                  <tr  style="background-color: #d9534f">
                    @php
                        $from = App\Models\Destination::find($trip->from);
                        $to = App\Models\Destination::find($trip->to);
                        $date = Carbon\Carbon::parse($trip->start_date);
                    @endphp
                    <td>{{ucfirst($trip->trip_number)}}</td>
                    <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                    @if ($trip->horse)
                    <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                    @else
                        <td></td>
                    @endif
                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                    <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
                  
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
                    <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
                    <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                     <td class="w-10 line-height-35 table-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                            </ul>
                        </div>
                        @include('trips.delete')

                </td>
                  </tr>
                  @elseif($trip->trip_status == "Offloading Point")
                  <tr  style="background-color: #82B1FF">
                    @php
                        $from = App\Models\Destination::find($trip->from);
                        $to = App\Models\Destination::find($trip->to);
                        $date = Carbon\Carbon::parse($trip->start_date);
                    @endphp
                    <td>{{ucfirst($trip->trip_number)}}</td>
                    <td>{{ucfirst($trip->customer ? $trip->customer->name : "")}}</td>
                    <td>{{ucfirst($trip->transporter ? $trip->transporter->name : "")}}</td>
                   @if ($trip->horse)
                   <td>{{ucfirst($trip->horse->horse_make ? $trip->horse->horse_make->name : "")}} {{ucfirst($trip->horse->horse_model ? $trip->horse->horse_model->name : "")}} ({{ucfirst($trip->horse ? $trip->horse->registration_number : "")}})</td>
                   @else
                       <td></td>
                   @endif
                    <td>{{$trip->loading_point ? $trip->loading_point->name : ""}}</td>
                    <td>{{$trip->offloading_point ? $trip->offloading_point->name : ""}}</td>
                   
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
                    <td> <span class="label label-{{isset($trip->invoice) ? "success" : "warning"}}"> {{isset($trip->invoice) ? "issued" : "pending"}}</span></td>
                    <td> <span class="label label-{{isset($trip->delivery_note->offloaded) ? "success" : "warning"}}"> {{isset($trip->delivery_note->offloaded) ? "submitted" : "pending"}}</span></td>
                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                     <td class="w-10 line-height-35 table-dropdown">
                        <div class="dropdown">
                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <i class="fa fa-bars"></i>
                                <span class="caret"></span>
                            </button>
                            <ul class="dropdown-menu">
                                <li><a href="{{route('trips.third_parties.show', $trip->id)}}"><i class="fas fa-eye color-default"></i>View</a></li>
                            </ul>
                        </div>
                        @include('trips.delete')

                </td>
                  </tr>
                  @endif
                  @endforeach
                </tbody>
                @else
                <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                @endif

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
            {{-- <div class="row">
                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-primary" href="{{route('branches.index')}}">
                        <span class="number counter">{{$branch_count}}</span>
                        <span class="name">Branches</span>
                        <span class="bg-icon"><i class="fa fa-building-o"></i></span>
                    </a>

                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-danger" href="{{route('departments.index')}}">
                        <span class="number counter">{{$department_count}}</span>
                        <span class="name">Departments</span>
                        <span class="bg-icon"><i class="fa fa-building"></i></span>
                    </a>
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

                <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
                    <a class="dashboard-stat bg-warning" href="{{route('employees.index')}}">
                        <span class="number counter">{{$employee_count}}</span>
                        <span class="name">Employees</span>
                        <span class="bg-icon"><i class="fa fa-users"></i></span>
                    </a>
                </div>
                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->


                <!-- /.col-lg-3 col-md-3 col-sm-6 col-xs-12 -->

            </div> --}}



            <!-- /.row -->
        </div>
        <!-- /.container-fluid -->
    </section>
</div>
