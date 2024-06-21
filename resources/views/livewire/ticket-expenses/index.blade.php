<div>
    @php
        $departments = Auth::user()->employee->departments;
        foreach($departments as $department){
            $department_names[] = $department->name;
        }
        $roles = Auth::user()->roles;
        foreach($roles as $role){
            $role_names[] = $role->name;
        }
        $wsdepartment = App\Models\Department::where('name','Workshop')->first();
        if (isset($wsdepartment)) {
            $wsdepartment_head = App\Models\DepartmentHead::where('department_id',$wsdepartment->id)->where('employee_id',Auth::user()->employee->id)->first();
        }
        $stdepartment = App\Models\Department::where('name','Workshop')->first();
        if (isset($stdepartment)) {
            $stdepartment_head = App\Models\DepartmentHead::where('department_id',$stdepartment->id)->where('employee_id',Auth::user()->employee->id)->first();
        }
        $fndepartment = App\Models\Department::where('name','Finance')->first();
        if (isset($fndepartment)) {
            $fndepartment_head = App\Models\DepartmentHead::where('department_id',$fndepartment->id)->where('employee_id',Auth::user()->employee->id)->first();
        }
    @endphp
    @if (isset($fndepartment_head) ||  (in_array('Admin', $role_names) && in_array('Finance', $department_names)) || in_array('Super Admin', $role_names))
        <a href="" data-toggle="modal" data-target="#ticket_expenseModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Expense</a>
    @endif
        <br>
        <br>
        <br>
    <table id="expensesTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
        <thead >
         <tr>
            <th class="th-sm">Expense Account
            </th>
            <th class="th-sm">Currency
            </th>
            <th class="th-sm">Qty
            </th>
            <th class="th-sm">Amount
            </th>
            <th class="th-sm">Subtotal
            </th>
            <th class="th-sm">Actions
            </th>
          </tr>
        </thead>
        @if ($ticket_expenses->count()>0)
        <tbody>
           @foreach ($ticket_expenses as  $ticket_expense)
            <tr>
                <td>
                    {{$ticket_expense->expense->account ? $ticket_expense->expense->account->name : ""}} {{$ticket_expense->expense ? $ticket_expense->expense->name : ""}}
                </td>  
                <td>{{$ticket_expense->currency ? $ticket_expense->currency->name : ""}}</td>
                <td>{{$ticket_expense->qty}}</td>
                <td>{{$ticket_expense->currency ? $ticket_expense->currency->symbol : ""}}{{number_format($ticket_expense->amount,2)}}</td>
                <td>{{$ticket_expense->currency ? $ticket_expense->currency->symbol : ""}}{{number_format($ticket_expense->subtotal,2)}}</td> 
                <td class="w-10 line-height-35 table-dropdown">
                    <div class="dropdown">
                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <i class="fa fa-bars"></i>
                            <span class="caret"></span>
                        </button>
                        <ul class="dropdown-menu">
                            <li><a href="#"  wire:click="edit({{$ticket_expense->id}})" ><i class="fa fa-edit color-success"></i> Edit</a></li>
                            <li><a href="#" data-toggle="modal" data-target="#ticket_expenseDeleteModal{{ $ticket_expense->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                           
                        </ul>
                    </div>
                    @include('ticket_expenses.delete')
                   
            </td> 
            </tr>
            @endforeach
        </tbody>
        @else
        <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
     @endif
      </table>

      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="ticket_expenseModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Expenses / Services <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horse">Expense Accounts</label>
                               <select wire:model.debounce.300ms="selectedAccount" class="form-control">
                                   <option value="" selected>Select Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{$account->id}}">{{$account->name}} </option>
                                    @endforeach
                               </select>
                                @error('selectedAccount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horse">Currencies<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                   <option value="" selected>Select Currency</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                               </select>
                                @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                      
                    </div>
                   

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horse">Items<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="selectedProduct.0" class="form-control" required>
                                   <option value="" selected>Select Item</option>
                                    @foreach ($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                               </select>
                                @error('selectedProduct.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_date">Qty<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty.0" placeholder="Qty" required>
                                @error('qty.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_date">Price<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="amount.0" placeholder="Price" required>
                                @error('amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                      
                    </div>
                   
                    @foreach ($inputs as $key => $value)
                    
                    <div class="row">
                        <div class="col-md-5">
                            <div class="form-group">
                                <label for="horse">Items<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="selectedProduct.{{$value}}" class="form-control" required>
                                   <option value="" selected>Select Item</option>
                                    @foreach ($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                               </select>
                                @error('selectedProduct.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                      
                        <div class="col-md-2">
                            <div class="form-group">
                                <label for="purchase_date">Qty<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty.{{$value}}" placeholder="Qty" required>
                                @error('qty.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_date">Price<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="amount.{{$value}}" placeholder="Price" required>
                                @error('amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                            <div class="col-md-1" style="margin-top:25px">
                                <div class="form-group">
                                    <button class="btn btn-danger btn-rounded xs"   wire:click.prevent="remove({{$key}})" > <i class="fa fa-times" ></i></button>
                                </div>
                            </div>
                      
                    </div>
            
                    @endforeach
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Item</button>
                            </div>
                        </div>
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
      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="ticket_expenseEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Edit Expense / Service <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                <div class="modal-body">

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horse">Expense Accounts</label>
                               <select wire:model.debounce.300ms="selectedAccount" class="form-control">
                                   <option value="" selected>Select Expense Account</option>
                                    @foreach ($accounts as $account)
                                        <option value="{{$account->id}}">{{$account->name}} </option>
                                    @endforeach
                               </select>
                                @error('selectedAccount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="horse">Currencies<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="currency_id" class="form-control" required>
                                   <option value="" selected>Select Currency</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{$currency->id}}">{{$currency->name}}</option>
                                    @endforeach
                               </select>
                                @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                   

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="horse">Items<span class="required" style="color: red">*</span></label>
                               <select wire:model.debounce.300ms="selectedProduct" class="form-control" required>
                                   <option value="" selected>Select Item</option>
                                    @foreach ($products as $product)
                                        <option value="{{$product->id}}">{{$product->name}}</option>
                                    @endforeach
                               </select>
                                @error('selectedProduct') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_date">Quantity<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="qty" placeholder="Enter Qty" required>
                                @error('qty') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                        <div class="col-md-3">
                            <div class="form-group">
                                <label for="purchase_date">Price<span class="required" style="color: red">*</span></label>
                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Unit price" required>
                                @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            </div>
                      
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
