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
                                {{-- <div class="panel-title">
                                    <a href="{{route('tickets.create')}}"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>ticket</a>
                                </div> --}}
                            </div>
                            <div class="panel-body p-20" style="overflow-x:auto; width:100%; height:100%;">

                                <table  class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">

                                    <thead >
                                        <tr>
                                        <th class="th-sm">Booking#
                                        </th>
                                        <th class="th-sm">Inspection#
                                        </th>
                                        <th class="th-sm">Ticket For
                                        </th>
                                        <th class="th-sm">AssignedTo
                                        </th>
                                        <th class="th-sm">Service Type
                                        </th>
                                        <th class="th-sm">In Date
                                        </th>
                                        <th class="th-sm">In Time
                                        </th>
                                        <th class="th-sm">Out Date
                                        </th>
                                        <th class="th-sm">Out Time
                                        </th>
                                        <th class="th-sm">Station
                                        </th>
                                        <th class="th-sm">Status
                                        </th>
                                        <th class="th-sm">Actions
                                        </th>
                                      </tr>
                                    </thead>
                                    @if (isset($tickets))
                                   
                                    <tbody>
                                        @forelse ($tickets as $ticket)
                                            
                                     
                                       
                                      <tr>
                                        <td>
                                            @if (isset($ticket->booking))
                                                <a href="{{route('bookings.show',$ticket->booking->id)}}" style="color: blue">{{$ticket->booking->booking_number}}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($ticket->inspection))
                                                <a href="{{route('bookings.show',$ticket->inspection->id)}}" style="color: blue">{{$ticket->inspection->inspection_number}}</a>
                                            @endif
                                        </td>
                                        <td>
                                            @if (isset($ticket->booking->horse))
                                            Horse | {{ucfirst($ticket->booking->horse->horse_make ? $ticket->booking->horse->horse_make->name : "")}} {{ucfirst($ticket->booking->horse->horse_model ? $ticket->booking->horse->horse_model->name : "" )}} {{ucfirst($ticket->booking->horse->registration_number)}}
                                            @elseif(isset($ticket->booking->vehicle))
                                            Vehicle | {{ucfirst($ticket->booking->vehicle->vehicle_make->name)}} {{ucfirst($ticket->booking->vehicle->vehicle_model->name)}} {{ucfirst($ticket->booking->vehicle->registration_number)}}
                                            @elseif(isset($ticket->booking->trailer))
                                            Trailer | {{ucfirst($ticket->booking->trailer->make)}} {{ucfirst($ticket->booking->trailer->model)}} {{ucfirst($ticket->booking->trailer->registration_number)}}
                                            @endif
                                           </td>
                                           <td>
                                            @if (isset($ticket->booking->employees) && $ticket->booking->employees->count()>0)
                                                @foreach ($ticket->booking->employees as $mechanic)
                                                    {{ $mechanic->name }} {{ $mechanic->surname }}
                                                    <br>
                                                @endforeach
                                            @elseif(isset($ticket->booking->vendor))
                                                {{ucfirst($ticket->booking->vendor->name)}}  
                                            @endif
                                        </td>
                                        <td>{{$ticket->service_type->name}}</td>
                                        <td>{{$ticket->in_date}}</td>
                                        <td>{{$ticket->in_time}}</td>
                                        <td>{{$ticket->out_date}}</td>
                                        <td>{{$ticket->out_time}}</td>
                                        <td>{{$ticket->station}}</td>
                                        <td><span class="badge bg-{{$ticket->status == 1 ? "warning" : "success"}}">{{$ticket->status == 1 ? "Open" : "Closed"}}</span></td>
                                        <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('tickets.show', $ticket->id)}}"><i class="fa fa-eye color-default"></i>View</a></li>
                                                    <li><a href="{{route('tickets.preview',$ticket->id)}}"   ><i class="fas fa-file-invoice color-primary"></i> Preview</a></li>
                                                    @if ($ticket->status == 1)
                                                    <li><a href="#"  wire:click="showTicket({{$ticket->id}})"><i class="fa fa-window-close color-success"></i> Close Ticket</a></li>
                                                    @endif
                                                    {{-- <li><a href="{{route('tickets.edit', $ticket->id)}}"><i class="fa fa-edit color-success"></i> Edit</a></li> --}}
                                                    {{-- <li><a href="#" data-toggle="modal" data-target="#ticketDeleteModal{{$ticket->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li> --}}
                                                </ul>
                                            </div>
                                            @include('tickets.delete')

                                    </td>
                                      </tr>
                                      @empty
                                      <tr>
                                        <td colspan="11">
                                            <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                                No Tickets Found ....
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
                                        @if (isset($tickets))
                                            {{ $tickets->links() }} 
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

        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="closeTicketModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fas fa-window-close"></i> Close Ticket {{ $ticket->ticket_number }}<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                        <p> Assigned to | @foreach ($ticket->employees as $employee)
                            {{ $employee->name }} {{ $employee->surname }},
                        @endforeach</p>
                    </div>
                    <form wire:submit.prevent="closeTicket()" >
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="name">Decision</label>
                        <select class="form-control" wire:model.debounce.300ms="status" required>
                            <option value="">Select Decision</option>
                            <option value="0">Close</option>
                            <option value="1">Open</option>
                        </select>
                            @error('status') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                        <div class="form-group">
                            <label for="reason">Comments</label>
                           <textarea wire:model.debounce.300="comments" class="form-control" cols="30" rows="5"></textarea>
                            @error('comments') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
