<div>
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
                      
                            <table id="loansTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Type
                                    </th>
                                    <th class="th-sm">Date
                                    </th>
                                    <th class="th-sm">AppliedBy
                                    </th>
                                    <th class="th-sm">Currency
                                    </th>
                                    <th class="th-sm">Amount
                                    </th>
                                    <th class="th-sm">Interest
                                    </th>
                                    <th class="th-sm">Period
                                    </th>
                                    <th class="th-sm">Total
                                    </th>
                                    <th class="th-sm">Bal
                                    </th>
                                    <th class="th-sm">Status
                                    </th>
                                    <th class="th-sm">Authorization
                                    </th>
                                    <th class="th-sm">Actions
                                    </th>

                                  </tr>
                                </thead>
                                @if ($loans->count()>0)
                                <tbody>
                                    @foreach ($loans as $loan)
                                  <tr>
                                    <td>{{$loan->loan_type ? $loan->loan_type->name : ""}}</td>
                                    <td>{{$loan->start_date}}</td>
                                    <td>{{ucfirst($loan->user ? $loan->user->name : "")}} {{ucfirst($loan->user ? $loan->user->surname : "")}}</td>
                                    <td>{{$loan->currency ? $loan->currency->name : ""}} </td>
                                    <td>
                                        @if ($loan->amount)
                                             {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->amount,2)}}        
                                        @endif
                                    </td>
                                    <td>{{$loan->interest}}%</td>
                                    <td>{{$loan->period}}Months</td>
                                    <td>
                                        @if ($loan->total)
                                            {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->total,2)}}        
                                        @endif
                                    </td>
                                    <td> 
                                        @if ($loan->balance)
                                            {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->balance,2)}}        
                                        @endif
                                    </td>
                                    <td><span class="label label-{{($loan->status == 'Paid') ? 'success' : (($loan->status == 'Partial') ? 'warning' : 'danger') }}">{{ $loan->status }}</span></td>
                                    <td><span class="badge bg-{{($loan->authorization == 'approved') ? 'success' : (($loan->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($loan->authorization == 'approved') ? 'approved' : (($loan->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('loans.show',$loan->id)}}"  ><i class="fas fa-eye color-default"></i> View</a></li>
                                                {{-- <li><a href="#" wire:click="authorize({{$loan->id}})"><i class="fas fa-gavel color-success"></i> Authorization</a></li> --}}
                                            </ul>
                                        </div>
                                        @include('loans.delete')
                                </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                            <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                            @endif
                              </table>
                        </div>
                    </div>
                </div>

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="authorizationModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fas fa-money"></i> Authorize Loan <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                <div class="modal-body">
                    <div class="form-group">
                        <label for="name">Authorize</label>
                    <select class="form-control" wire:model.debounce.300ms="authorize">
                        <option value="">Select Decision</option>
                        <option value="approved">Approve</option>
                        <option value="rejected">Reject</option>
                    </select>
                        @error('authorize') <span class="error" style="color:red">{{ $message }}</span> @enderror
                    </div>
                    <div class="form-group">
                        <label for="comment">Comment</label>
                        <textarea class="form-control" wire:model.debounce.300ms="comments" cols="30" rows="3"></textarea>
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

