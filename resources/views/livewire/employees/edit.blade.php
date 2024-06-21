<div>
    <section class="section">
        <x-loading/>
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 col-md-offset-1">
                    <div class="panel">
                        <div class="panel-heading">
                            <div class="panel-title">
                                <h5>Edit Employee</h5>
                            </div>
                        </div>
                        <div class="panel-body">

                            <form wire:submit.prevent="update()" class="p-20" enctype="multipart/form-data">
                                <h5 class="underline mt-n">Employee Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="middlename">Name<span class="required" style="color: red">*</span></label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="name" placeholder="Enter Name" required>
                                            @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="middlename">Middle Name</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="middle_name" placeholder="Enter Middle Name">
                                            @error('middle_name') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="middlename">Surname<span class="required" style="color: red">*</span></label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="surname" placeholder="Enter Surname" required>
                                            @error('surname') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                         <div class="form-group">
                                            <label for="gender">Gender<span class="required" style="color: red">*</span></label>
                                                 <div class="col-sm-10">
                                                    <div class="radio">
                                                        <label>
                                                        <input type="radio" wire:model.debounce.300ms="gender" id="optionsRadios1" value="Male" required>
                                                        Male
                                                        </label>
                                                    </div>
                                                    <div class="radio">
                                                        <label>
                                                        <input type="radio"  wire:model.debounce.300ms="gender" id="optionsRadios2" value="Female" required>
                                                        Female
                                                        </label>
                                                    </div>
                                                </div>
                                                @error('gender') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="contact13">DOB</label>
                                            <input type="date" class="form-control" wire:model.debounce.300ms="dob"  placeholder="Enter DOB " >
                                            @error('dob') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>
                                <h5 class="underline mt-30">Username</h5>
                                <div class="mb-10">
                                   <input type="checkbox" wire:model.debounce.300ms="use_email_as_username" class="line-style" />
                                   <label for="one" class="radio-label">User email as username</label>
                                   @error('use_email_as_username') <span class="text-danger error">{{ $message }}</span>@enderror
                               </div>
                               <div class="row">
                                   <div class="col-md-6">
                                       <div class="form-group">
                                           <label for="email">Email</label>
                                           <input type="email" class="form-control" wire:model.debounce.300ms="email" placeholder="Enter Email" />
                                           @error('email') <span class="text-danger error">{{ $message }}</span>@enderror
                                       </div>
                                   </div>
                                   <div class="col-md-6">
                                       <div class="form-group">
                                           <label for="contact13">Phonenumber</label>
                                           <input type="text" class="form-control" wire:model.debounce.300ms="phonenumber" placeholder="Enter Phonenumber " />
                                           @error('phonenumber') <span class="text-danger error">{{ $message }}</span>@enderror
                                       </div>
                                   </div>
                                   <!-- /.col-md-6 -->
                               </div>
                               <div class="row">
                                   <div class="col-md-6">
                                       <div class="form-group">
                                           <label for="exampleInputEmail13">ID Number</label>
                                           <input type="text" class="form-control" wire:model.debounce.300ms="idnumber" placeholder="Enter ID Number" />
                                           @error('idnumber') <span class="text-danger error">{{ $message }}</span>@enderror
                                       </div>
                                   </div>
                                 
                                   <div class="col-md-6">
                                       <div class="form-group">
                                           <label for="exampleInputEmail13">Branch</label>
                                          <select wire:model.debounce.300ms="branch_id" class="form-control" >
                                              <option value="" selected> Select Branch</option>
                                              @foreach ($branches as $branch)
                                                  <option value="{{$branch->id}}">{{$branch->name}}</option>
                                              @endforeach
                                          </select>
                                          <small>  <a href="{{ route('branches.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Branch</a></small> 
                                          @error('branch_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                       </div>
                                   </div>

                                   <!-- /.col-md-6 -->
                               </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail13">Department<span class="required" style="color: red">*</span></label>
                                           <select wire:model.debounce.300ms="selectedDepartment" class="form-control" required>
                                               <option value="" > Select Department</option>
                                               @foreach ($departments as $department)
                                               <option value="{{$department->id}}" >{{$department->name}}</option>
                                                @endforeach
                                           </select>
                                           <small>  <a href="{{ route('departments.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Department</a></small> 
                                           @error('selectedDepartment') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail13">Job Title</label>
                                           <select wire:model.debounce.300ms="job_title" class="form-control" >
                                               <option value="" selected > Select Job Title</option>
                                               @if (!is_null($selectedDepartment))
                                               @foreach ($job_titles as $job_title)
                                                    <option value="{{$job_title->title}}">{{$job_title->title}}</option>
                                                @endforeach
                                               @endif

                                           </select>
                                           <small>  <a href="{{ route('job_titles.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Job Title</a></small> 
                                           @error('job_title') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>


                                    <!-- /.col-md-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="exampleInputEmail1">Rank<span class="required" style="color: red">*</span></label>
                                            <select wire:model.debounce.300ms="rank_id" class="form-control"  required>
                                                <option value="" selected>Select Rank</option>
                                                @foreach ($ranks as $rank)
                                                @if ($rank->name == "HOD")
                                                @else
                                                <option value="{{$rank->id}}">{{$rank->name}}</option> 
                                                @endif
                                                @endforeach
                                            </select>
                                            @error('rank_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">

                                            <label for="exampleInputEmail1">Role <span class="required" style="color: red">*</span></label>

                                            <select wire:model.debounce.300ms="role_id" class="form-control"  required>
                                                <option value="" selected>Select Role</option>
                                                @foreach ($roles as $role)
                                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                                @endforeach
                                            </select>
                                            @error('role_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Employment Start Date</label>
                                            <input type="date" class="form-control" wire:model.debounce.300ms="start_date" placeholder="Enter Employee Start Date" />
                                            @error('start_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Employement End Date</label>
                                            <input type="date" class="form-control" wire:model.debounce.300ms="end_date" placeholder="Enter Employee End Date" />
                                            @error('end_date') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="start_date">Leave Accrual Rate / Month</label>
                                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="accrual_rate" placeholder="Enter Leave Accrual Rate" />
                                            @error('accrual_rate') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>

                                    <div class="col-md-3">
                                        <div class="form-group">
                                            <label for="leave_days">Available Leave Days</label>
                                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="leave_days" placeholder="Available Leave Days " />
                                            @error('leave_days') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="start_date">Next Of Kin</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="next_of_kin" placeholder="Enter Next Of Kin">
                                            @error('next_of_kin') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="relationship">Relationship</label>
                                            <select wire:model.debounce.300ms="relationship" class="form-control">
                                                <option value="">Selected Relationship</option>
                                                <option value="Husband">Husband</option>
                                                <option value="Wife">Wife</option>
                                                <option value="Father">Father</option>
                                                <option value="Mother">Mother</option>
                                                <option value="Daughter">Daughter</option>
                                                <option value="Son">Son</option>
                                                <option value="Brother">Brother</option>
                                                <option value="Sister">Sister</option>
                                                <option value="Nephew">Nephew</option>
                                                <option value="Niece">Niece</option>
                                                <option value="Other">Other</option>
                                            </select>
                                            @error('relationship') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="contact">Contact</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="contact" placeholder="Enter Contact">
                                            @error('contact') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>
                                <h5 class="underline mt-30">Address Details</h5>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="exampleInputEmail13">Countries</label>
                                           <select wire:model.debounce.300ms="selectedCountry" class="form-control" >
                                               <option value=""> Select Country</option>
                                               @foreach ($countries as $country)
                                                   <option value="{{$country->id}}">{{$country->name}}</option>
                                               @endforeach
                                           </select>
                                           <small>  <a href="{{ route('countries.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Country</a></small> 
                                           @error('selectedCountry') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="province">Province/States</label>
                                           <select wire:model.debounce.300ms="province_id"  class="form-control" >
                                               <option value="" selected>Select Province</option>
                                               @foreach ($provinces as $province)
                                               <option value="{{ $province->id }}">{{ $province->name }}</option>
                                               @endforeach 
                                           </select>
                                           <small>  <a href="{{ route('provinces.index') }}" target="_blank"><i class="fa fa-plus-square-o"></i> New Province</a></small> 
                                           @error('province_id') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="city">City</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="city" placeholder="Enter City/Town" >
                                            @error('city') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->
                                </div>
                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="form-group">
                                            <label for="suburb">Suburb</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="suburb" placeholder="Enter Suburb" >
                                            @error('suburb') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    <!-- /.col-md-6 -->

                                    <div class="col-md-8">
                                        <div class="form-group">
                                            <label for="street_address">Street Address</label>
                                            <input type="text" class="form-control" wire:model.debounce.300ms="street_address" placeholder="Enter Street Address" >
                                            @error('street_address') <span class="text-danger error">{{ $message }}</span>@enderror
                                        </div>
                                    </div>
                                    </div>
                                    {{-- <div class="row">
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="currency_id">Currencies</label>
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
                                                <label for="salary">Salary</label>
                                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="salary" placeholder="Enter Salary" />
                                                @error('salary') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="form-group">
                                                <label for="frequency">Payment Frequency</label>
                                                <select wire:model.debounce.300ms="frequency" class="form-control">
                                                    <option value="">Select Frequency</option>
                                                    <option value="Daily">Daily</option>
                                                    <option value="Weekly">Weekly</option>
                                                    <option value="Monthly">Monthly</option>
                                                </select>
                                                @error('frequency') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                    </div> --}}
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="suburb">Contract Duration</label>
                                                <input type="number" step="any" min="0" class="form-control" wire:model.debounce.300ms="duration" placeholder="Duration in years" >
                                                @error('duration') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                        <!-- /.col-md-6 -->

                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="expiration">Expiration Date</label>
                                                <input type="date" class="form-control" wire:model.debounce.300ms="expiration" placeholder="Enter Expiration Date" >
                                                @error('expiration') <span class="text-danger error">{{ $message }}</span>@enderror
                                            </div>
                                        </div>
                                      
                                        </div>
                                    <div class="row">
                                    <div class="col-md-12">
                                        <div class="btn-group pull-right mt-10" >
                                           <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                                            <button type="submit" class="btn bg-success btn-wide btn-rounded" > <i class="fa fa-refresh"></i>Update</button>
                                        </div>
                                    </div>
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
