<div>
    <x-loading/>
    {{-- <a href="" data-toggle="modal" data-target="#salary_itemModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Salary Item</a> --}}
    <br>
    <br>
    <table id="salary_detailsTable" class="table  table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
        <thead >
            <th class="th-sm">Type
            </th>
            <th class="th-sm">Item
            </th>
            <th class="th-sm">Currency
            </th>
            <th class="th-sm">Amount
            </th>
            <th class="th-sm">Actions
            </th>
          </tr>
        </thead>

        <tbody>
            @foreach ($salary_details as $salary_detail)
          <tr>
            @if ($salary_detail->salary_item)
            <td>{{$salary_detail->salary_item ? $salary_detail->salary_item->type : ""}}</td>
            <td>{{$salary_detail->salary_item ? $salary_detail->salary_item->name : ""}}</td>
            @elseif ($salary_detail->loan)   
            <td>Deductions</td>
            <td>Loan</td>
            @endif
            <td>{{$salary_detail->salary->currency->name}}</td>
            <td>
                @if ($salary_detail->amount)
                  {{$salary_detail->salary->currency->symbol}}{{number_format($salary_detail->amount,2)}}        
                @endif
            </td>
            <td class="w-10 line-height-35 table-dropdown">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        {{-- @if ($salary->payroll_salary)
                         @else   
                         <li><a href="#" wire:click="edit({{$salary_detail->id }})"><i class="fa fa-edit color-success"></i>Edit</a></li>
                         <li><a href="#" wire:click="removeShow({{ $salary_detail->id }})"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                        @endif   --}}
                    </ul>
                </div>
              {{-- @include('salary_details.delete') --}}
        </td>
        </tr>
          @endforeach
        </tbody>
      </table>

      <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal fade" id="removeModal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content bg-danger">
                <div class="modal-body">
                   <center> <strong>Are you sure you want to delete this Salary Item</strong> </center>
                </div>
                <form wire:submit.prevent="removesalaryItem()" >
                <div class="modal-footer no-border">
                    <div class="btn-group" role="group">
                        <button type="button" class="btn bg-white btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-times"></i>Close</button>
                        <button type="submit" class="btn bg-black btn-wide btn-rounded" ><i class="fa fa-trash"></i>Delete</button>
                    </div>
                    <!-- /.btn-group -->
                </div>
            </form>
            </div>
        </div>
    </div>
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="salary_itemModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="cargo">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Add Salary Item(s) <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="store()" >
                <div class="modal-body">     
                        
                           <label for="">Earnings</label> 
                           <div class="row">  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select wire:model.debounce.300ms="earning_id.0" class="form-control">
                                            <option value="">Select Earning Item</option>
                                            @foreach ($earnings as $earning)
                                                <option value="{{ $earning->id }}">{{ $earning->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('earning_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="earning_amount.0" placeholder="Enter Amount" required>
                                        @error('earning_amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                @foreach ($inputs as $key => $value)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select wire:model.debounce.300ms="earning_id.{{ $value }}" class="form-control">
                                                <option value="">Select Earning Item</option>
                                                @foreach ($earnings as $earning)
                                                    <option value="{{ $earning->id }}">{{ $earning->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('earning_id.'. $value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="earning_amount.{{ $value }}" placeholder="Enter Amount" required>
                                            @error('earning_amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for=""></label>
                                            <button class="btn btn-danger btn-rounded xs"   wire:click.prevent="remove({{$key}})"> <i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="add({{$i}})"> <i class="fa fa-plus"></i>Earning</button>
                                    </div>
                                </div>
                            </div>
                      
                      
                            <label for="">Deductions</label>
                            <div class="row">  
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <select wire:model.debounce.300ms="deduction_id.0" class="form-control">
                                            <option value="">Select Deduction Item</option>
                                            @foreach ($deductions as $deduction)
                                                <option value="{{ $deduction->id }}">{{ $deduction->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('deduction_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="deduction_amount.0" placeholder="Enter Amount" required>
                                        @error('deduction_amount.0') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                        
                            <div class="row">
                                @foreach ($deductions_inputs as $key => $value)
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <select wire:model.debounce.300ms="deduction_id.{{ $value }}" class="form-control">
                                                <option value="">Select Deduction Item</option>
                                                @foreach ($deductions as $deduction)
                                                    <option value="{{ $deduction->id }}">{{ $deduction->name }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                        @error('deduction_id.'. $value) <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="deduction_amount.{{ $value }}" placeholder="Enter Amount" required>
                                            @error('deduction_amount.'.$value) <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-1">
                                        <div class="form-group">
                                            <label for=""></label>
                                            <button class="btn btn-danger btn-rounded xs"   wire:click.prevent="deductionsRemove({{$key}})"> <i class="fa fa-times"></i></button>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="deductionsAdd({{$l}})"> <i class="fa fa-plus"></i>Deduction</button>
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

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="salary_itemEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="cargo">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Salary Item<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="update()" >
                <div class="modal-body">
                   
                        <div class="form-group">
                            <select wire:model.debounce.300ms="salary_item_id" class="form-control">
                                <option value="">Select Salary Item</option>
                                @foreach ($salary_items as $salary_item)
                                    <option value="{{ $salary_item->id }}">{{ $salary_item->name }}</option>
                                @endforeach
                            </select>
                            @error('salary_item_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                   
                        <div class="form-group">
                            <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="amount" placeholder="Enter Amount" required>
                            @error('amount') <span class="error" style="color:red">{{ $message }}</span> @enderror
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
