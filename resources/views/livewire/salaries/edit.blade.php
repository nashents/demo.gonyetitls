<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Salary</h5>
                            </div>
                        </div>
                        <div class="panel-body">
                        <form wire:submit.prevent="update()" >
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
                                        <input  type="number" step="any"  class="form-control" wire:model.debounce.300ms="basic" placeholder="Enter Basic Salary" required disabled>
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
                        </div>
                        <div class="modal-footer">
                            <div class="btn-group" role="group">
                                <button type="button" onclick="goBack()" class="btn btn-gray btn-wide btn-rounded" data-dismiss="modal"><i class="fa fa-arrow-left"></i>Back</button>
                                <button type="submit" class="btn bg-success btn-wide btn-rounded"><i class="fa fa-refresh"></i>Update</button>
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
