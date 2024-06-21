<div>
    <div class="row mt-30">
    
        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1" >

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Requisition Details</a></li>
                <li role="presentation"><a href="#items" aria-controls="items" role="tab" data-toggle="tab">Requisition Items</a></li>

            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">

                        <tbody class="text-center line-height-35 ">
                            <tr>
                                <th class="w-10 text-center line-height-35">Requisition#</th>
                                <td class="w-20 line-height-35">{{$requisition->requisition_number}}</td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">CreatedBy</th>
                                <td class="w-20 line-height-35">{{$requisition->user ? $requisition->user->name : ""}} {{$requisition->user ? $requisition->user->surname : ""}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">RequestedBy</th>
                                <td class="w-20 line-height-35">{{$requisition->employee ? $requisition->employee->name : ""}} {{$requisition->employee ? $requisition->employee->surname : ""}} </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Department</th>
                                <td class="w-20 line-height-35">{{$requisition->department ? $requisition->department->name : ""}}  </td>
                            </tr>
                            @if ($requisition->trip)
                            <tr>
                                <th class="w-10 text-center line-height-35">Trip</th>
                                <td class="w-20 line-height-35">{{$requisition->trip ? $requisition->trip->trip_number : ""}}</td>
                            </tr>
                            @endif
                            
                          
                                <tr>
                                    <th class="w-10 text-center line-height-35">Subject</th>
                                    <td class="w-20 line-height-35">{{$requisition->subject}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Date</th>
                                    <td class="w-20 line-height-35">{{$requisition->date}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Currency</th>
                                    <td class="w-20 line-height-35">{{$requisition->currency ? $requisition->currency->name : ""}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Total</th>
                                    <td class="w-20 line-height-35">{{$requisition->currency ? $requisition->currency->symbol : ""}}{{number_format($requisition->total,2)}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Authorization</th>
                                    <td class="w-20 line-height-35"><span class="badge bg-{{($requisition->authorization == 'approved') ? 'success' : (($requisition->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($requisition->authorization == 'approved') ? 'approved' : (($requisition->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                </tr>
                                @if ($requisition->authorized_by_id)
                                <tr>
                                    <th class="w-10 text-center line-height-35">AuthorizedBY</th>
                                    <td class="w-20 line-height-35">{{App\Model\User::find($requisition->authorized_by_id)->name}} {{App\Model\User::find($requisition->authorized_by_id)->surname}}</td>
                                </tr>
                                @endif
                                
                             
                        </tbody>
                    </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="items">
                  @livewire('requisitions.items', ['id' => $requisition->id])
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
