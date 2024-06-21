<div>
    {{-- <blockquote class="blockquote-reverse mt-20"> --}}
        @if (Auth::user()->employee)
        @if ($trip->authorization == "approved")
        <a href="" data-toggle="modal" data-target="#tripExpenseModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Trip Expense</a>
        @endif
        <br>
        <br>
        @endif
        <x-loading/>
        <table id="tripExpensesTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
            <thead >
                <th class="th-sm">Name
                </th>
                <th class="th-sm">Category
                </th>
                <th class="th-sm">Currency
                </th>
                <th class="th-sm">Amount
                </th>
                <th class="th-sm">Conversion
                </th>
                @if (Auth::user()->category == "employee" || Auth::user()->category == "admin")
                <th class="th-sm">Actions
                </th>
                @endif
              </tr>
            </thead>

            <tbody>
                @forelse ($trip_expenses as $trip_expense)
              <tr>
                <td>{{$trip_expense->expense ? $trip_expense->expense->name : ""}}</td>
                <td>{{$trip_expense->category}}</td>
                <td>{{ $trip_expense->currency ? $trip_expense->currency->name : ""}}</td>
                <td>
                    @if ($trip_expense->amount)
                    {{ $trip_expense->currency ? $trip_expense->currency->symbol : "" }}{{number_format($trip_expense->amount,2)}}
                    @endif
                </td>
                <td>
                    @if (isset($trip_expense->exchange_rate))
                        Currency conversion: {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }} {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->symbol : "" }}{{ number_format($trip_expense->exchange_amount,2)}} at {{ $trip_expense->exchange_rate}} 
                    @endif
                </td>
                @if (Auth::user()->category == "employee" || Auth::user()->category == "admin")
                <td class="w-10 line-height-35 table-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#" wire:click="edit({{$trip_expense->id}})"><i class="fa fa-edit color-success"></i> Edit</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#expenseDeleteModal{{$trip_expense->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                        </ul>
                    </div>
                    @include('trips.expenses.delete')

            </td>
            @endif
            </tr>
            @empty
            <tr>
                <td colspan="6">
                    <div style="text-align:center; text-color:grey; padding-top:5px; padding-bottom:5px; font-size:17px">
                        No Trip Expenses Captured....
                    </div>
                   
                </td>
              </tr>  
            @endforelse
      
            </tbody>
            <br>
            <thead >
                <th class="th-sm">Currency
                </th>
                <th class="th-sm">Total
                </th>
              </tr>
            </thead>
            <tbody>
                @foreach ($currencies as $currency)
                @php
                    $total = App\Models\TripExpense::where('currency_id',$currency->id)
                                                    ->where('trip_id',$trip->id)->sum('amount');
                @endphp
                @if ($total > 0)
                <tr>
                    <td>{{ $currency->name }}</td>
                    <td>{{ $currency->symbol }}{{ $total }}</td>
                    </tr>
                @endif
             
              @endforeach
      
            </tbody>

          </table>
    {{-- </blockquote> --}}
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="tripExpenseModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="expense">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Trip Expense(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="title">Expense(s)<span class="required" style="color: red">*</span></label>
                           <select wire:model.debounce.300ms="expense_id" class="form-control" required>
                               <option value="">Select Expense</option>
                               @foreach ($expenses as $expense)
                                   <option value="{{$expense->id}}">{{$expense->name}}</option>
                               @endforeach
                           </select>
                            @error('expense_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            <small>  <a href="{{ route('expenses.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Expense</a></small> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">    
                            <label for="title">Categories<span class="required" style="color: red">*</span></label>                              
                            <select class="form-control" wire:model.debounce.300ms="category"  required>
                                <option value="">Select Category</option>
                               <option value="Customer">Customer</option>
                               <option value="Self">Self</option>
                               <option value="Transporter">Transporter</option>
                            </select>
                            @error('category') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                    </div>
                    <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="currency_id">Currencies<span class="required" style="color: red">*</span></label>
                           <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                               <option value="">Select Currency</option>
                               @foreach ($currencies as $currency)
                                   <option value="{{$currency->id}}">{{$currency->name}}</option>
                               @endforeach
                           </select>
                            @error('z') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="amount">Amount<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" class="form-control"  wire:model.debounce.300ms="amount" placeholder="Enter Amount" required/>
                            @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>

                        </div>

                        @if (!is_null($currency_id))
                        @if (Auth::user()->employee->company)
                            @if ($currency_id != Auth::user()->employee->company->currency_id)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">Conversion Rate<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate"  placeholder="Exchange Rate">
                                        @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">Expense Amount {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}<span class="required" style="color: red">*</span></label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_amount" placeholder="Converted Expense Amount">
                                        @error('exchange_amount') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div> 
                                </div>
                            </div>
                            @endif
                        @endif
                    @endif  

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
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="tripExpenseEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="expense">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Trip Expense(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="title">Expenses<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="expense_id" class="form-control" required>
                                   <option value="">Select Expense</option>
                                   @foreach ($expenses as $expense)
                                       <option value="{{$expense->id}}">{{$expense->name}}</option>
                                   @endforeach
                               </select>
                                @error('expense_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                        <div class="form-group">   
                            <label for="title">Categories<span class="required" style="color: red">*</span></label>                     
                            <select class="form-control" wire:model.debounce.300ms="category"  required>
                                <option value="">Select Category</option>
                               <option value="Customer">Customer</option>
                               <option value="Self">Self</option>
                               <option value="Transporter">Transporter</option>
                            </select>
                            @error('category') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="currency_id">Currencies<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                   <option value="">Select Currency</option>
                                   @foreach ($currencies as $currency)
                                       <option value="{{$currency->id}}">{{$currency->name}}</option>
                                   @endforeach
                               </select>
                                @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="amount">Amount<span class="required" style="color: red">*</span></label>
                                <input type="number" step="any" class="form-control"  wire:model.debounce.300ms="amount" placeholder="Enter Amount" required/>
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        </div>

                        @if (!is_null($currency_id))
                        @if (Auth::user()->employee->company)
                            @if ($currency_id != Auth::user()->employee->company->currency_id)
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">Conversion Rate</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_rate"  placeholder="Exchange Rate">
                                        @error('exchange_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div> 
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="customer">Expense Amount {{ Auth::user()->employee->company->currency ? Auth::user()->employee->company->currency->name : "" }}</label>
                                        <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="exchange_amount" placeholder="Converted Expense Amount">
                                        @error('exchange_amount') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div> 
                                </div>
                            </div>
                            @endif
                        @endif
                    @endif  

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
@section('extra-js')
{{-- <script>
    $(document).ready( function () {
        $('#tripExpensesTable').DataTable();
    } );
    </script> --}}
@endsection