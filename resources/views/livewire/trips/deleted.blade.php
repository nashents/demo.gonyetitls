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
                            <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                                <table  class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%" style=" width:100%; height:100%;">
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
                                        <th class="th-sm">Authorization
                                        </th>
                                        <th class="th-sm">Actions
                                        </th>

                                      </tr>
                                    </thead>
                                    @if (isset($trips))
                                    <tbody>
                                        @forelse($trips as $trip)  
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
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                        @endif
                                       
                                         
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   
                                                    <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
                                                </ul>
                                            </div>
                                          

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
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                        @endif
                                      
                                        
                                        
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   
                                                    <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
                                               
                                                </ul>
                                            </div>
                                            @include('trips.delete')

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
                                    @elseif($trip->trip_status == "Cancelled")
                                    <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                    @elseif($trip->trip_status == "Offloading Point")
                                    <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                    @endif
                                  
                                   
                                    
                                     <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                     <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                               
                                                <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
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
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                        @endif
                                      
                                       
                                        
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   
                                                    <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
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
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                        @endif
                                      
                                        
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   
                                                    <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
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
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                        @endif
                                      
                                        
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   
                                                    <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
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
                                        @elseif($trip->trip_status == "Cancelled")
                                        <td class="table-light"><span class="label label-light label-wide">{{$trip->trip_status}}</span></td>
                                        @elseif($trip->trip_status == "Offloading Point")
                                        <td class="table-accent"><span class="label label-accent label-wide">{{$trip->trip_status}}</span></td>
                                        @endif      
                                      
                                        
                                         <td><span class="label label-{{($trip->authorization == 'approved') ? 'success' : (($trip->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($trip->authorization == 'approved') ? 'approved' : (($trip->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                         <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                   
                                                    <li><a href="#" wire:click="restore({{$trip->id}})"><i class="fas fa-undo color-default"></i> Restore</a></li>
                                                </ul>
                                            </div>
                                            @include('trips.delete')

                                    </td>
                                      </tr>
                                      @endif
                                      @empty
                                      <tr>
                                        <td colspan="9">
                                            <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                                No Deleted Trips Found ....
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

          <!-- Modal -->
          <div data-backdrop="static" data-keyboard="false" class="modal fade" id="tripRestoreModal" tabindex="-1" role="dialog">
            <div class="modal-dialog" role="document">
                <div class="modal-content bg-danger">
                    <div class="modal-body">
                       <center> <strong>Are you sure you want to restore this Trip?</strong> </center>
                    </div>
                    <form wire:submit.prevent="update()">
                    <div class="modal-footer no-border">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn bg-white btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            <button type="submit" class="btn bg-black btn-wide btn-rounded" ><i class="fas fa-undo"></i> Restore</button>
                        </div>
                        <!-- /.btn-group -->
                    </div>
                </form>
                </div>
            </div>
        </div>

    </div>
