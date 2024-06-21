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
                                <div class="panel-title">
                                    <a href="#" data-toggle="modal" data-target="#requisitionModal"  class="btn btn-default"><i class="fa fa-plus-square-o"></i>Requisition</a>
                                </div>
                            </div>
                            <div class="panel-body p-20" style="overflow-x:auto; width:100%; height:100%;">
                                <div class="panel-title">
                                    <div class="row">
                                    
                                    <div class="col-lg-3">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      Filter By
                                      </span>
                                      <select wire:model.debounce.300ms="requisition_filter" class="form-control" aria-label="..." >
                                        <option value="created_at">Requisition Created At</option>
                                  </select>
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    @if ($requisition_filter == "created_at")
                                    <div class="col-lg-2" style="margin-right: 7px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      From
                                      </span>
                                      <input type="date" wire:model.debounce.300ms="from" wire:change="dateRange()" class="form-control" aria-label="...">
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                                    <div class="col-lg-2" style="margin-left: 30px">
                                        <div class="input-group">
                                            <span class="input-group-addon">
                                      To
                                      </span>
                                      <input type="date" wire:model.debounce.300ms="to" wire:change="dateRange()" class="form-control" aria-label="...">
                                        </div>
                                        <!-- /input-group -->
                                    </div>
                              
                                    @endif
                                   
                                    <!-- /input-group -->
                                </div>
                              
                               
                                </div>
                                <br>
                                <div class="col-md-3" style="float: right; padding-right:0px">
                                    <div class="form-group">
                                        <input type="text" wire:model.debounce.300ms="search" class="form-control" placeholder="Search requisitions...">
                                    </div>
                                </div>
                                <table  class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                    <thead >
                                        <th class="th-sm">Requisition#
                                        </th>
                                        <th class="th-sm">RequestedBy
                                        </th>
                                        <th class="th-sm">Dpt
                                        </th>
                                        <th class="th-sm">Subject
                                        </th>
                                        <th class="th-sm">Date
                                        </th>
                                        <th class="th-sm">Currency
                                        </th>
                                        <th class="th-sm">Total
                                        </th>
                                        <th class="th-sm">Status
                                        </th>
                                        <th class="th-sm">Authorization
                                        </th>
                                        <th class="th-sm">Actions
                                        </th>

                                      </tr>
                                    </thead>
                                    @if (isset($requisitions))
                                    <tbody>
                                        @forelse ($requisitions as $requisition)
                                      <tr>
                                        <td>{{ucfirst($requisition->requisition_number)}}</td>
                                        <td>{{ucfirst($requisition->employee ? $requisition->employee->name : "")}} {{ucfirst($requisition->employee ? $requisition->employee->surname : "")}}</td>
                                        <td>{{ucfirst($requisition->department ? $requisition->department->name : "")}}</td>
                                        <td>{{$requisition->subject}}</td>
                                        <td>{{$requisition->date }}</td>
                                        <td>{{$requisition->currency ? $requisition->currency->symbol : "" }}{{number_format($requisition->total,2)}}</td>
                                        <td>{{$requisition->currency ? $requisition->currency->name : "" }}</td>
                                        <td><span class="label label-{{($requisition->status == 'Paid') ? 'success' : (($requisition->status == 'Partial') ? 'warning' : 'danger') }}">{{ $requisition->status }}</span></td>
                                        <td><span class="badge bg-{{($requisition->authorization == 'approved') ? 'success' : (($requisition->authorization == 'rejected') ? 'danger' : 'warning') }}">{{($requisition->authorization == 'approved') ? 'approved' : (($requisition->authorization == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                        <td class="w-10 line-height-35 table-dropdown">
                                            <div class="dropdown">
                                                <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                    <i class="fa fa-bars"></i>
                                                    <span class="caret"></span>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li><a href="{{route('requisitions.show', $requisition->id)}}"><i class="fa fa-eye color-default"></i>View</a></li>
                                                    @if ($requisition->authorization == "pending" || $requisition->authorization == "rejected")
                                                    <li><a href="#" wire:click="edit({{ $requisition->id }})"><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                    <li><a href="#" data-toggle="modal" data-target="#requisitionDeleteModal{{$requisition->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                                    @endif
                                                   

                                                </ul>
                                            </div>
                                            @include('requisitions.delete')

                                    </td>
                                      </tr>
                                      @empty
                                        <tr>
                                            <td colspan="10">
                                                <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                                                    No Requisitions Found ....
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
                                        @if (isset($requisitions))
                                            {{ $requisitions->links() }} 
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
        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="requisitionModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Add Requisition <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="store()" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">RequestedBy<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="employee_id" class="form-control" required >
                                       <option value="">Select Employee</option>
                                       @foreach ($employees as $employee)
                                       <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->surname }}</option>
                                       @endforeach
                                   </select>
                                    @error('employee_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Departments<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="department_id" class="form-control" required >
                                       <option value="">Select Department</option>
                                       @foreach ($departments as $department)
                                       <option value="{{ $department->id }}">{{ $department->name }}</option>
                                       @endforeach
                                      
                                   </select>
                                    @error('department_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Subject</label>
                                    <input type="text" min="1" class="form-control" wire:model.debounce.300ms="subject" placeholder="Enter Requisition Subject" />
                                    @error('subject') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Trips</label>
                                   <select wire:model.debounce.300ms="trip_id" class="form-control" >
                                       <option value="">Select Trip</option>
                                       @foreach ($trips as $trip)
                                       <option value="{{ $trip->id }}">{{ $trip->trip_number }} | {{ $trip->horse ? $trip->horse->registration_number : "" }} | {{ $trip->customer ? $trip->customer->name : "" }} | {{ $trip->loading_point ? $trip->loading_point->name : "" }} - {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}</option>
                                       @endforeach
                                      
                                   </select>
                                    @error('trip_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Date<span class="required" style="color: red">*</span></label>
                                    <input type="date" min="1" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Date" required />
                                    @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Currencies<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="currency_id" class="form-control" required >
                                       <option value="">Select Currency</option>
                                       @foreach ($currencies as $currency)
                                       <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                       @endforeach
                                   </select>
                                    @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Expense Categories<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="selectedExpenseCategory.0" class="form-control" required >
                                       <option value="">Select Expense Category</option>
                                       @foreach ($accounts as $account)
                                       <option value="{{ $account->id }}">{{ $account->name }}</option>
                                       @endforeach
                                   </select>
                                   <small>  <a href="{{ route('accounts.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense Category</a></small> 
                                   @error('selectedExpenseCategory.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Expenses<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="expense_id.0" class="form-control" required >
                                       <option value="">Select Expense</option>
                                       @foreach ($expenses as $expense)
                                       <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                                       @endforeach
                                   </select>
                                   <small>  <a href="{{ route('expenses.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense</a></small> 
                                    @error('expense_id.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                       
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Qty<span class="required" style="color: red">*</span></label>
                                    <input type="number" min="1" class="form-control" wire:model.debounce.300ms="qty.0" placeholder="Enter Qty" required />
                                    @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                    <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="amount.0" placeholder="Enter Amount" required />
                                    @error('amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        @foreach ($inputs as $key => $value)
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Expense Categories<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="selectedCategory.{{ $value }}" class="form-control" required >
                                       <option value="">Select Expense Category</option>
                                       @foreach ($accounts as $account)
                                       <option value="{{ $account->id }}">{{ $account->name }}</option>
                                       @endforeach
                                   </select>
                                    @error('selectedCategory.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Expenses<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="expense_id.{{ $value }}" class="form-control" required >
                                       <option value="">Select Expense</option>
                                       @foreach ($expenses as $expense)
                                       <option value="{{ $expense->id }}">{{ $expense->name }}</option>
                                       @endforeach
                                   </select>
                                    @error('expense_id.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">  
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="name">Qty<span class="required" style="color: red">*</span></label>
                                        <input type="number" class="form-control" wire:model.debounce.300ms="qty.{{ $value }}" placeholder="Enter Qty" required />
                                        @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-5">
                                    <div class="form-group">
                                        <label for="name">Amount<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" class="form-control" wire:model.debounce.300ms="amount.{{ $value }}" placeholder="Enter Amount" required />
                                        @error('amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for=""></label>
                                    <button class="btn btn-danger btn-rounded xs" style="margin-top:23px"  wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Item</button>
                            </div>
                        </div>
                    </div>
                        <div class="form-group">
                            <label for="name">Notes</label>
                            <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="4"></textarea>
                            @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div class="btn-group" role="group">
                            <button type="button" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                            <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
                        </div>
                        <!-- /.btn-group -->
                    </div>
                </form>
                </div>
            </div>
        </div>
        <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="requisitionEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="modal4Label"><i class="fas fa-plus"></i> Edit Requisition <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                    </div>
                    <form wire:submit.prevent="update()" >
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">RequestedBy<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="employee_id" class="form-control" required >
                                       <option value="">Select Employee</option>
                                       @foreach ($employees as $employee)
                                       <option value="{{ $employee->id }}">{{ $employee->name }} {{ $employee->surname }}</option>
                                       @endforeach
                                   </select>
                                    @error('employee_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Departments<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="department_id" class="form-control" required >
                                       <option value="">Select Department</option>
                                       @foreach ($departments as $department)
                                       <option value="{{ $department->id }}">{{ $department->name }}</option>
                                       @endforeach
                                      
                                   </select>
                                    @error('department_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Subject</label>
                                    <input type="text" min="1" class="form-control" wire:model.debounce.300ms="subject" placeholder="Enter Requisition Subject" />
                                    @error('subject') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Trips</label>
                                   <select wire:model.debounce.300ms="trip_id" class="form-control" >
                                       <option value="">Select Trip</option>
                                       @foreach ($trips as $trip)
                                       <option value="{{ $trip->id }}">{{ $trip->trip_number }} | {{ $trip->horse ? $trip->horse->registration_number : "" }} | {{ $trip->customer ? $trip->customer->name : "" }} | {{ $trip->loading_point ? $trip->loading_point->name : "" }} - {{ $trip->offloading_point ? $trip->offloading_point->name : "" }}</option>
                                       @endforeach
                                      
                                   </select>
                                    @error('trip_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="name">Date<span class="required" style="color: red">*</span></label>
                                    <input type="date" min="1" class="form-control" wire:model.debounce.300ms="date" placeholder="Enter Date" required />
                                    @error('date') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="country">Currencies<span class="required" style="color: red">*</span></label>
                                   <select wire:model.debounce.300ms="currency_id" class="form-control" required >
                                       <option value="">Select Currency</option>
                                       @foreach ($currencies as $currency)
                                       <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                       @endforeach
                                   </select>
                                    @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="name">Notes<span class="required" style="color: red">*</span></label>
                            <textarea class="form-control" wire:model.debounce.300ms="description" cols="30" rows="4"></textarea>
                            @error('description') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
