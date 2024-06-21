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
                            <div class="panel-title" >
                                My Loan Applications
                             </div>
                             <br>
                            <div class="panel-title">
                                <a href="" data-toggle="modal" data-target="#loanModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Loan</a>
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
                                    <th class="th-sm">MP
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
                                        @if ($loan->payment_per_month)
                                            {{$loan->currency ? $loan->currency->symbol : ""}}{{number_format($loan->payment_per_month,2)}}        
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
                                                <li><a href="{{ route('loans.show',$loan->id) }}" ><i class="fa fa-eye color-default"></i> View</a></li>
                                                @if ($loan->authorization == "pending")
                                                <li><a href="#" data-toggle="modal" data-target="#loanEditModal" wire:click.prevent="edit({{$loan->id}})"><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#loanDeleteModal{{$loan->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                @endif
                                              
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

                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->


                <!-- /.col-md-6 -->


                <!-- /.col-md-6 -->

            </div>
            <!-- /.row -->

        </div>
        <!-- /.container-fluid -->
    </section>


    <!-- Modal -->
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="loanModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Apply Loan <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="name" required disabled>
                                @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surname">Surname<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="surname" required disabled>
                                @error('surname') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_type">Loan Type<span class="required" style="color: red">*</span></label>
                              <select wire:model.debounce.300ms="loan_type_id" class="form-control" required >
                                  <option value="" selected>Select Loan Type</option>
                                  @foreach ($loan_types as $loan_type)
                                      <option value="{{$loan_type->id}}">{{$loan_type->name}}</option>
                                  @endforeach
                              </select>
                                @error('loan_type_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_type">Currencies<span class="required" style="color: red">*</span></label>
                              <select wire:model.debounce.300ms="currency_id" class="form-control" required >
                                  <option value="" selected>Select Currency</option>
                                  @foreach ($currencies as $currency)
                                      <option value="{{$currency->id}}">{{$currency->name}}</option>
                                  @endforeach
                              </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_days">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="$" required/>
                                @error('amount') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="date"  required />
                                @error('date') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to">Period<span class="required" style="color: red">*</span></label>
                               <select class="form-control" wire:model.debounce.300ms="period" required>
                                <option value="">Select Period</option>
                                <option value="1">1 Month</option>
                                <option value="2">2 Month</option>
                                <option value="3">3 Month</option>
                                <option value="4">4 Month</option>
                                <option value="5">5 Month</option>
                                <option value="6">6 Month</option>
                                <option value="7">7 Month</option>
                                <option value="8">8 Month</option>
                                <option value="9">9 Month</option>
                                <option value="10">10 Month</option>
                                <option value="11">11 Month</option>
                                <option value="12">12 Month</option>
                                <option value="13">13 Month</option>
                                <option value="14">14 Month</option>
                                <option value="15">15 Month</option>
                                <option value="16">16 Month</option>
                                <option value="17">17 Month</option>
                                <option value="18">18 Month</option>
                                <option value="19">19 Month</option>
                                <option value="20">20 Month</option>
                                <option value="21">21 Month</option>
                                <option value="22">22 Month</option>
                                <option value="23">23 Month</option>
                                <option value="24">24 Month</option>
                               </select>
                                @error('period') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Interest<span class="required" style="color: red">*</span></label>
                                <input type="number" class="form-control" wire:model.debounce.300ms="interest" placeholder="%" required disabled/>
                                @error('interest') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
           

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Total<span class="required" style="color: red">*</span></label>
                                <input type="number" class="form-control" wire:model.debounce.300ms="total" placeholder="$" required disabled/>
                                @error('total') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Monthly Payment<span class="required" style="color: red">*</span></label>
                                <input type="number" class="form-control" wire:model.debounce.300ms="payment_per_month" placeholder="$" required disabled/>
                                @error('payment_per_month') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Purpose of loan</label>
                               <textarea class="form-control" wire:model.debounce.300ms="purpose"  cols="30" rows="5" required ></textarea>
                                @error('purpose') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- /.col-md-6 -->
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Apply</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="loanEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Loan Application <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Name<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="name" required disabled>
                                @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="surname">Surname<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="surname" required disabled>
                                @error('surname') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_type">Loan Type<span class="required" style="color: red">*</span></label>
                              <select wire:model.debounce.300ms="loan_type_id" class="form-control" required >
                                  <option value="" selected>Select Loan Type</option>
                                  @foreach ($loan_types as $loan_type)
                                      <option value="{{$loan_type->id}}">{{$loan_type->name}}</option>
                                  @endforeach
                              </select>
                                @error('loan_type_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_type">Currencies<span class="required" style="color: red">*</span></label>
                              <select wire:model.debounce.300ms="currency_id" class="form-control" required >
                                  <option value="" selected>Select Currency</option>
                                  @foreach ($currencies as $currency)
                                      <option value="{{$currency->id}}">{{$currency->name}}</option>
                                  @endforeach
                              </select>
                                @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="loan_days">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount" placeholder="$" required/>
                                @error('amount') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="date"  required />
                                @error('date') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to">Period<span class="required" style="color: red">*</span></label>
                               <select class="form-control" wire:model.debounce.300ms="period" required>
                                <option value="">Select Period</option>
                                <option value="1">1 Month</option>
                                <option value="2">2 Month</option>
                                <option value="3">3 Month</option>
                                <option value="4">4 Month</option>
                                <option value="5">5 Month</option>
                                <option value="6">6 Month</option>
                                <option value="7">7 Month</option>
                                <option value="8">8 Month</option>
                                <option value="9">9 Month</option>
                                <option value="10">10 Month</option>
                                <option value="11">11 Month</option>
                                <option value="12">12 Month</option>
                                <option value="13">13 Month</option>
                                <option value="14">14 Month</option>
                                <option value="15">15 Month</option>
                                <option value="16">16 Month</option>
                                <option value="17">17 Month</option>
                                <option value="18">18 Month</option>
                                <option value="19">19 Month</option>
                                <option value="20">20 Month</option>
                                <option value="21">21 Month</option>
                                <option value="22">22 Month</option>
                                <option value="23">23 Month</option>
                                <option value="24">24 Month</option>
                               </select>
                                @error('period') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Interest<span class="required" style="color: red">*</span></label>
                                <input type="number" class="form-control" wire:model.debounce.300ms="interest" placeholder="%" required disabled/>
                                @error('interest') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
           

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Total<span class="required" style="color: red">*</span></label>
                                <input type="number" class="form-control" wire:model.debounce.300ms="total" placeholder="$" required disabled/>
                                @error('total') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="name">Monthly Payment<span class="required" style="color: red">*</span></label>
                                <input type="number" class="form-control" wire:model.debounce.300ms="payment_per_month" placeholder="$" required disabled/>
                                @error('payment_per_month') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Purpose of loan</label>
                               <textarea class="form-control" wire:model.debounce.300ms="purpose"  cols="30" rows="5" required ></textarea>
                                @error('purpose') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- /.col-md-6 -->
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
