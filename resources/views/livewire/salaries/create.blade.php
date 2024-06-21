<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>New Salary</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="store()" >
                        <div class="modal-body">
                          
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Salary Number<span class="required" style="color: red">*</span></label>
                                        <input type="text" class="form-control" wire:model.debounce.300ms="salary_number" placeholder="Enter Salary Number" required disabled>
                                        @error('salary_number') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="country">Employees<span class="required" style="color: red">*</span></label>
                                       <select wire:model.debounce.300ms="employee_id" class="form-control" required >
                                           <option value="">Select Employee</option>
                                         @foreach ($employees as $employee)
                                            <option value="{{$employee->id}}">{{$employee->name}} {{$employee->surname}}</option>
                                         @endforeach
                                       </select>
                                        @error('employee_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="">Currencies</label>
                                        <select wire:model.debounce.300ms="currency_id" class="form-control">
                                            <option value="">Select Currency</option>
                                            @foreach ($currencies as $currency)
                                                <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                            @endforeach
                                        </select>
                                        @error('currency_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Basic Salary<span class="required" style="color: red">*</span></label>
                                        <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="basic" placeholder="Enter Basic Salary" required>
                                        @error('basic') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="name">Net Salary<span class="required" style="color: red">*</span></label>
                                        <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="net" placeholder="Enter Net Salary" required disabled>
                                        @error('net') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
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
                                            <div class="col-md-5">
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
                                            <div class="col-md-5">
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
                                </div>
                                <div class="col-md-6">
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
                                            <div class="col-md-5">
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
                                            <div class="col-md-5">
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

                                    <label for="">Loan</label>
                                    <div class="row">  
                                        <div class="col-md-12">
                                        <div class="form-group">
                                            <select wire:model.debounce.300ms="loan_id.0" class="form-control">
                                                <option value="">Select Loan</option>
                                                @foreach ($loans as $loan)
                                                    <option value="{{ $loan->id }}"> {{ $loan->loan_number }} {{ $loan->loan_type ? $loan->loan_type->name : "" }} {{ $loan->employee ? $loan->employee->name : ""}} {{ $loan->employee ? $loan->employee->surname : ""}}</option>
                                                @endforeach
                                            </select>
                                            @error('loan_id.0') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                        </div>
                                    </div>
                                
                                    <div class="row">
                                        @foreach ($loans_inputs as $key => $value)
                                            <div class="col-md-10">
                                                <div class="form-group">
                                                    <select wire:model.debounce.300ms="loan_id.{{ $value }}" class="form-control">
                                                        <option value="">Select Loan </option>
                                                        @foreach ($loans as $loan)
                                                            <option value="{{ $loan->id }}">{{ $loan->loan_number }} {{ $loan->loan_type ? $loan->loan_type->name : "" }} {{ $loan->employee ? $loan->employee->name : ""}} {{ $loan->employee ? $loan->employee->surname : ""}}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>     
                                            <div class="col-md-1">
                                                <div class="form-group">
                                                    <label for=""></label>
                                                    <button class="btn btn-danger btn-rounded xs"   wire:click.prevent="loansRemove({{$key}})"> <i class="fa fa-times"></i></button>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="form-group">
                                                <button class="btn btn-success btn-rounded" style="float: right" wire:click.prevent="loansAdd({{$j}})"> <i class="fa fa-plus"></i>Loan</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                         
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <button type="button" onclick="goBack()" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-arrow-left"></i>Back</button>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-save"></i>Save</button>
                            </div>
                            <!-- /.btn-group -->
                        </div>
                    </form>





                            <!-- /.col-md-12 -->
                        </div>
                    </div>
                </div>
                <!-- /.col-md-6 -->


            </div>

        </div>
        <!-- /.container-fluid -->
    </section>


</div>
