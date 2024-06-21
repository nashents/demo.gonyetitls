<div>
    <div class="row mt-30">
        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1">
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Bill Details</a></li>
                <li role="presentation"><a href="#expenses" aria-controls="expenses" role="tab" data-toggle="tab">Bill Expenses</a></li>
            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                <table class="table table-striped">
                    <tbody class="text-center line-height-35 ">
                        <tr>
                            <th class="w-10 text-center line-height-35">Bill#</th>
                            <td class="w-20 line-height-35">{{$bill->bill_number}}</td>
                        </tr>
                        @if ($bill->transporter)
                        <tr>
                            <th class="w-10 text-center line-height-35">Transporter</th>
                            <td class="w-20 line-height-35">{{$bill->transporter ? $bill->transporter->name : ""}}</td>
                        </tr>
                        @endif
                        @if ($bill->trip)
                        <tr>
                            <th class="w-10 text-center line-height-35">Trip#</th>
                            <td class="w-20 line-height-35"><a href="{{ route('trips.show',$bill->trip->id) }}" style="color: blue">{{$bill->trip ? $bill->trip->trip_number : ""}}</a> </td>
                        </tr>
                        @endif
                        @if ($bill->horse)
                        <tr>
                            <th class="w-10 text-center line-height-35">Horse</th>
                            <td class="w-20 line-height-35"><a href="{{ route('horses.show',$bill->horse->id) }}" style="color: blue">{{$bill->horse ? $bill->horse->registration_number : ""}}</a> </td>
                        </tr>
                        @endif
                        @if ($bill->driver)
                        <tr>
                            <th class="w-10 text-center line-height-35">Driver</th>
                            <td class="w-20 line-height-35"><a href="{{ route('drivers.show',$bill->driver->id) }}" style="color: blue">{{$bill->driver->employee ? $bill->driver->employee->name : ""}} {{$bill->driver->employee ? $bill->driver->employee->surname : ""}}</a> </td>
                        </tr>
                        @endif
                       
                        @if ($bill->vendor)
                        <tr>
                            <th class="w-10 text-center line-height-35">Vendor</th>
                            <td class="w-20 line-height-35">{{$bill->vendor ? $bill->vendor->name : ""}}</td>
                        </tr>
                        @endif
                        @if ($bill->container)
                        <tr>
                            <th class="w-10 text-center line-height-35">Fueling Station</th>
                            <td class="w-20 line-height-35">{{$bill->container ? $bill->container->name : ""}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">TopUp</th>
                            <td class="w-20 line-height-35">{{$bill->top_up ? $bill->top_up->order_number : ""}}</td>
                        </tr>
                        @endif
                        <tr>
                            <th class="w-10 text-center line-height-35">Bill Date</th>
                            <td class="w-20 line-height-35">{{$bill->bill_date}}</td>
                        </tr>
                        @if ($bill->due_date)
                        <tr>
                            <th class="w-10 text-center line-height-35">Due Date</th>
                            <td class="w-20 line-height-35">{{$bill->due_date}}</td>
                        </tr>
                        @endif
                       
                        <tr>
                            <th class="w-10 text-center line-height-35">Currency</th>
                            <td class="w-20 line-height-35">{{$bill->currency ? $bill->currency->name : ""}}</td>
                        </tr>
                        @if ($bill->exchange_rate)
                        <tr>
                            <th class="w-10 text-center line-height-35">Currency conversion:</th>
                            <td class="w-20 line-height-35"> {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($bill->exchange_amount,2)}} at {{ $bill->exchange_rate}} </td>
                        </tr> 
                        @endif
                        <tr>
                            <th class="w-10 text-center line-height-35">Subtotal</th>
                            <td class="w-20 line-height-35">
                                @if ($bill->subtotal)
                                {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format($bill->subtotal,2)}}  
                                @endif
                              </td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Tax Amount</th>
                            <td class="w-20 line-height-35">
                                @if ($bill->tax_amount)
                                {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format($bill->tax_amount,2)}}  
                                @endif
                              </td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Total</th>
                            <td class="w-20 line-height-35">
                                @if ($bill->total)
                                {{$bill->currency ? $bill->currency->symbol : ""}}{{number_format($bill->total,2)}}  
                                @endif
                              </td>
                        </tr>
                        @if ($bill->exchange_amount)
                        <tr>
                            <th class="w-10 text-center line-height-35">Total in {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}</th>
                            <td class="w-20 line-height-35">{{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{$bill->exchange_amount}}</td>
                        </tr> 
                        @endif
                        <tr>
                            <th class="w-10 text-center line-height-35">Status</th>
                            <td class="w-20 line-height-35"><span class="label label-{{($bill->status == 'Paid') ? 'success' : (($bill->status == 'Partial') ? 'warning' : 'danger') }}">{{ $bill->status }}</span></td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Authorization</th>
                            <td class="w-20 line-height-35"><span class="badge bg-{{($bill->authorization == 'approved') ? 'success' : (($bill->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($bill->authorization == 'approved') ? 'approved' : (($bill->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                        </tr>
                        @php
                        $user = App\Models\User::find($bill->authorized_by_id);
                        @endphp
                        @if (isset($user))
                            <tr>
                                <th class="w-10 text-center line-height-35">Authorized By</th>
                                <td class="w-20 line-height-35">
                                    {{ $user->name }} {{ $user->surname }}
                                </td>
                            </tr>
                        @endif
                        
                        @if ($bill->comments)
                        <tr>
                            <th class="w-10 text-center line-height-35">Authorization Comments</th>
                            <td class="w-20 line-height-35">{{ $bill->comments }}</td>
                        </tr>
                        @endif
                      
                        @if ($bill->notes)
                        <tr>
                            <th class="w-10 text-center line-height-35">Notes</th>
                            <td class="w-20 line-height-35">{{$bill->notes}}</td>
                        </tr>
                        @endif
                       
                        <hr>
                    </tbody>
                </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="expenses">
                    @livewire('bills.expenses', ['id' => $bill->id])
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
