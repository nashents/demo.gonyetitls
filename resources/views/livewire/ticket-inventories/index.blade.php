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
                    @if (isset($wsdepartment_head) ||  (in_array('Admin', $role_names) && in_array('Stores', $department_names))|| in_array('Super Admin', $role_names))
                          <a href="" data-toggle="modal" data-target="#ticket_inventoryModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Repair(s)</a>
                    @endif
                        <br>
                        <br>
                        <br>
                    <table id="partsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                        <thead >
                         <tr>
                            <th class="th-sm">Item
                            </th>
                            <th class="th-sm">Quantities
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
                        @if ($ticket_inventories->count()>0)
                        <tbody>
                           @foreach ($ticket_inventories as  $ticket_inventory)
                            <tr>
                                <td>
                                    @if ($ticket_inventory->inventory)
                                    {{$ticket_inventory->inventory->product ? $ticket_inventory->inventory->product->name : ""}}
                                    {{$ticket_inventory->inventory ? $ticket_inventory->inventory->part_number : ""}}
                                    @endif
                                </td>  
                                <td>{{$ticket_inventory->weight}} {{$ticket_inventory->measurement}}</td>
                                <td>{{$ticket_inventory->currency ? $ticket_inventory->currency->name : ""}}</td>
                                <td>{{$ticket_inventory->qty}}</td>
                                <td>{{ $ticket_inventory->currency ? $ticket_inventory->currency->symbol : "" }}{{number_format($ticket_inventory->amount,2)}}</td>
                                <td>{{ $ticket_inventory->currency ? $ticket_inventory->currency->symbol : "" }}{{number_format($ticket_inventory->subtotal,2)}}</td>
                                 
                                <td class="w-10 line-height-35 table-dropdown">
                                    <div class="dropdown">
                                        <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                            <i class="fa fa-bars"></i>
                                            <span class="caret"></span>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li><a href="#"  wire:click="edit({{$ticket_inventory->id}})" ><i class="fa fa-edit color-success"></i> Edit</a></li>
                                            <li><a href="#" data-toggle="modal" data-target="#ticket_inventoryDeleteModal{{ $ticket_inventory->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                        </ul>
                                    </div>
                                    @include('ticket_inventories.delete')
                            </td> 
                            </tr>
                            @endforeach
                        </tbody>
                        @else
                        <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                     @endif
                      </table>

                      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="ticket_inventoryModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Repairs Used <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                                </div>
                                <form wire:submit.prevent="addProducts()" >
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="horse">Products<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedInventory.0" class="form-control" required>
                                           <option value="" selected>Select Product</option>
                                            @foreach ($inventories as $inventory)
                                                <option value="{{$inventory->id}}">{{$inventory->inventory_number}} | {{$inventory->product->brand ? $inventory->product->brand->name: ""}} {{$inventory->product ? $inventory->product->name: ""}} 
                                                    @if ($inventory->part_number)
                                                    | {{$inventory->part_number}}
                                                    @endif
                                                </option>
                                            @endforeach
                                       </select>
                                        @error('selectedInventory.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="purchase_date">Weight/Litreage/Quantity<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="weight.0" placeholder="Enter Amounts Needed" required>
                                                @error('weight.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country">Measurement<span class="required" style="color: red">*</span></label>
                                               <select wire:model.debounce.300ms="measurement.0" class="form-control" required>
                                                   <option value="">Select Measurement</option>
                                                   <option value="Litre(s)">Litre(s)</option>
                                                   <option value="Gram(s)">Gram(s)</option>
                                                   <option value="Kg()s)">Kg(s)</option>
                                                   <option value="Ton(s)">Ton(s)</option>
                                                   <option value="Piece(s)">Piece(s)</option>
                                                   <option value="Item(s)">Item(s)</option>
                                               </select>
                                                @error('measurement.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                        </div>
                                    </div>
                                   
                                    @foreach ($inputs as $key => $value)
                                    <div class="form-group">
                                        <label for="horse">Products<span class="required" style="color: red">*</span></label>
                                        <select wire:model.debounce.300ms="selectedInventory.{{$value}}" class="form-control" required>
                                            <option value="" selected>Select Product</option>
                                             @foreach ($inventories as $inventory)
                                                 <option value="{{$inventory->id}}">{{$inventory->inventory_number}} {{$inventory->product->brand ? $inventory->product->brand->name: ""}} {{$inventory->product ? $inventory->product->name: ""}}</option>
                                             @endforeach
                                        </select>
                                         @error('selectedInventory.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                     </div>
                                    <div class="row">
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="purchase_date">Weight/Litreage/Quantity<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="weight.{{$value}}" placeholder="Enter Amounts Needed" required>
                                                @error('weight.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                            </div>
                                        <div class="col-md-5">
                                            <div class="form-group">
                                                <label for="country">Measurement<span class="required" style="color: red">*</span></label>
                                               <select wire:model.debounce.300ms="measurement.{{$value}}" class="form-control" required>
                                                   <option value="">Select Measurement</option>
                                                   <option value="Litre(s)">Litre(s)</option>
                                                   <option value="Gram(s)">Gram(s)</option>
                                                   <option value="Kg()s)">Kg(s)</option>
                                                   <option value="Ton(s)">Ton(s)</option>
                                                   <option value="Piece(s)">Piece(s)</option>
                                                   <option value="Item(s)">Item(s)</option>
                                               </select>
                                                @error('measurement.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
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
                                                <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i> Product</button>
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
                      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="ticket_inventoryEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Edit Repairs/Spares <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                                </div>
                                <form wire:submit.prevent="update()" >
                                <div class="modal-body">
                                    <div class="form-group">
                                        <label for="horse">Products<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="selectedInventory" class="form-control" required>
                                           <option value="" selected>Select Product</option>
                                            @foreach ($inventories as $inventory)
                                                <option value="{{$inventory->id}}">{{$inventory->inventory_number}} | {{$inventory->product->brand ? $inventory->product->brand->name: ""}} {{$inventory->product ? $inventory->product->name: ""}} 
                                                    @if ($inventory->part_number)
                                                    | {{$inventory->part_number}}
                                                    @endif
                                                </option>
                                            @endforeach
                                       </select>
                                        @error('selectedInventory') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="purchase_date">Weight/Litreage/Quantity<span class="required" style="color: red">*</span></label>
                                            <input type="number" step="any" min="1" class="form-control" wire:model.debounce.300ms="weight" placeholder="Enter Amounts Needed" required>
                                                @error('weight') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                            </div>
                                            </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="country">Measurement<span class="required" style="color: red">*</span></label>
                                               <select wire:model.debounce.300ms="measurement" class="form-control" required>
                                                   <option value="">Select Measurement</option>
                                                   <option value="Litre(s)">Litre(s)</option>
                                                   <option value="Gram(s)">Gram(s)</option>
                                                   <option value="Kg()s)">Kg(s)</option>
                                                   <option value="Ton(s)">Ton(s)</option>
                                                   <option value="Piece(s)">Piece(s)</option>
                                                   <option value="Item(s)">Item(s)</option>
                                               </select>
                                                @error('measurement') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
