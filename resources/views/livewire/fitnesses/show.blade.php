<div>
    <div class="row mt-30">
    
        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1" >

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Reminder Details</a></li>
            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">

                        <tbody class="text-center line-height-35 ">

                            <tr>
                                <th class="w-10 text-center line-height-35">CreatedBy</th>
                                <td class="w-20 line-height-35">{{$fitness->user ? $fitness->user->name : ""}} {{$fitness->user ? $fitness->user->surname : ""}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Reminder</th>
                                <td class="w-20 line-height-35">{{$fitness->reminder_item ? $fitness->reminder_item->name : ""}}</td>
                            </tr>
                                @if ($fitness->horse)
                                <tr>
                                    <th class="w-10 text-center line-height-35">Reminder For</th>
                                    <td class="w-20 line-height-35"> {{$fitness->horse->horse_make ? $fitness->horse->horse_make->name : ""}} {{$fitness->horse->horse_model ? $fitness->horse->horse_model->name : ""}} {{$fitness->horse ? $fitness->horse->registration_number : ""}}</td>
                                </tr>
                                @elseif ($fitness->vehicle)
                                <tr>
                                    <th class="w-10 text-center line-height-35">Reminder For</th>
                                    <td class="w-20 line-height-35"> {{$fitness->vehicle->vehicle_make ? $fitness->vehicle->vehicle_make->name : ""}} {{$fitness->vehicle->vehicle_model ? $fitness->vehicle->vehicle_model->name : ""}} {{$fitness->vehicle ? $fitness->vehicle->registration_number : ""}}</td>
                                </tr>
                                @elseif($fitness->trailer)  
                                <tr>
                                    <th class="w-10 text-center line-height-35">Reminder For</th>
                                    <td class="w-20 line-height-35"> {{$fitness->trailer ? $fitness->trailer->registration_number : ""}}</td>
                                </tr>  
                                @elseif($fitness->employee)    
                                <tr>
                                    <th class="w-10 text-center line-height-35">Reminder For</th>
                                    <td class="w-20 line-height-35"> {{$fitness->employee ? $fitness->employee->name : ""}} {{$fitness->employee ? $fitness->employee->surname : ""}}</td>
                                </tr>  
                                @endif
                                
                                <tr>
                                    <th class="w-10 text-center line-height-35">Issued @</th>
                                    <td class="w-20 line-height-35">{{$fitness->issued_at}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">First Reminder</th>
                                    <td class="w-20 line-height-35">{{$fitness->first_reminder_at}} <span class="badge bg-{{$fitness->first_reminder_at_status == 1 ? "success" : "warning"}}">{{$fitness->first_reminder_at_status == 1 ? "Sent" : "Not Sent"}}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">2nd Reminder</th>
                                    <td class="w-20 line-height-35">{{$fitness->second_reminder_at}}  <span class="badge bg-{{$fitness->second_reminder_at_status == 1 ? "success" : "warning"}}">{{$fitness->second_reminder_at_status == 1 ? "Sent" : "Not Sent"}}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">3rd Reminder</th>
                                    <td class="w-20 line-height-35">{{$fitness->third_reminder_at}} <span class="badge bg-{{$fitness->third_reminder_at_status == 1 ? "success" : "warning"}}">{{$fitness->third_reminder_at_status == 1 ? "Sent" : "Not Sent"}}</span></td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Expiry Date</th>
                                    <td class="w-20 line-height-35">{{$fitness->expires_at}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Status</th>
                                    <td class="w-20 line-height-35">
                                        @if ($fitness->expires_at >= now()->toDateTimeString())
                                        <span class="badge bg-success">Valid</span>
                                        @else
                                        <span class="badge bg-danger">Expired</span>        
                                        @endif
                                    </td>
                                   
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Closed</th>
                                    <td class="w-20 line-height-35">
                                        @if ($fitness->closed == 0)
                                        <span class="badge bg-success">Open</span>
                                        @else
                                        <span class="badge bg-danger">Closed</span>        
                                        @endif
                                    </td>
                                   
                                </tr>
                                <tr>
                                    <th colspan="2">
                                        <div class="row">
                                            <center>
                                                <a href="#" wire:click="close({{ $fitness->id }})"  class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-remove"></i>Close</a>  <a href="#" wire:click="snooze({{ $fitness->id }})"  class="btn btn-default border-primary btn-rounded btn-wide"><i class="fa fa-bell"></i>Snoose</a>
                                            </center>
                                            
                                        </div>
                                    </th>
                                   
                                    
                                </tr>
                             
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
   
</div>
