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
                                My Leave Applications
                             </div>
                             <br>
                            <div class="panel-title">
                                <a href="" data-toggle="modal" data-target="#leaveModal" class="btn btn-default"><i class="fa fa-plus-square-o"></i>Apply Leave</a>
                            </div>

                            {{-- <div class="panel-title" style="float: right">
                                <a href="{{route('leaves.export.excel')}}"  class="btn btn-default"><i class="fa fa-download"></i>Excel</a>
                                <a href="{{route('leaves.export.csv')}}"  class="btn btn-default"><i class="fa fa-download"></i>CSV</a>
                                <a href="{{route('leaves.export.pdf')}}"  class="btn btn-default"><i class="fa fa-download"></i>PDF</a>
                                <a href="" data-toggle="modal" data-target="#leavesImportModal" class="btn btn-default"><i class="fa fa-upload"></i>Import</a>
                            </div> --}}

                        </div>
                        <div class="panel-body p-20"style="overflow-x:auto; width:100%; height:100%;">

                            <table id="leavesTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Date Applied
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Leave Type
                                    </th>
                                    <th class="th-sm">From
                                    </th>
                                    <th class="th-sm">To
                                    </th>
                                    <th class="th-sm">Days
                                    </th>
                                    <th class="th-sm">Reason
                                    </th>
                                    <th class="th-sm">HR Status
                                    </th>
                                    <th class="th-sm">HOD Status
                                    </th>
                                    <th class="th-sm">Actions
                                    </th>

                                  </tr>
                                </thead>
                                @if ($leaves->count()>0)
                                <tbody>
                                    @foreach ($leaves as $leave)
                                  <tr>
                                    <td>{{$leave->created_at}}</td>
                                    <td>{{ucfirst($leave->user ? $leave->user->name : "")}} {{ucfirst($leave->user ? $leave->user->surname : "")}}</td>
                                    <td>{{$leave->leave_type ? $leave->leave_type->name : ""}}</td>
                                    <td>{{$leave->from}}</td>
                                    <td>{{$leave->to}}</td>
                                    <td>{{$leave->days}}</td>
                                    <td>{{$leave->reason}}</td>
                                    <td><span class="badge bg-{{($leave->management_decision == 'approved') ? 'success' : (($leave->management_decision == 'rejected') ? 'danger' : 'warning') }}">{{($leave->management_decision == 'approved') ? 'approved' : (($leave->management_decision == 'rejected') ? 'rejected' : 'pending' )}}</span></td>
                                    <td><span class="badge bg-{{($leave->hod_decision == 'approved') ? 'success' : (($leave->hod_decision == 'rejected') ? 'danger' : 'warning') }}">{{($leave->hod_decision == 'approved') ? 'approved' : (($leave->hod_decision == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="#" data-toggle="modal" data-target="#leaveEditModal" wire:click.prevent="edit({{$leave->id}})"><i class="fa fa-edit color-success"></i> Edit</a></li>
                                                <li><a href="#" data-toggle="modal" data-target="#leaveDeleteModal{{$leave->id}}"><i class="fa fa-trash color-danger"></i>Delete</a></li>
                                            </ul>
                                        </div>
                                        @include('leaves.delete')
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
    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="leaveModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-plus"></i> Apply Leave <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Email<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="email" required disabled>
                                @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_days">Available Leave Days<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="available_leave_days" disabled >
                                @error('available_leave_days') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="leave_type">Leave Type<span class="required" style="color: red">*</span></label>
                              <select wire:model.debounce.300ms="leave_type_id" class="form-control" required >
                                  <option value="" selected>Select Leave Type</option>
                                  @foreach ($leave_types as $leave_type)
                                      <option value="{{$leave_type->id}}">{{$leave_type->name}}</option>
                                  @endforeach
                              </select>
                                @error('leave_type_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Start Leave Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="from" required />
                                @error('from') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to">End Leave Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="to" required />
                                @error('to') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Reason for leave<span class="required" style="color: red">*</span></label>
                               <textarea class="form-control" wire:model.debounce.300ms="reason"  cols="30" rows="5" required ></textarea>
                                @error('reason') <span class="text-danger error">{{ $message }}</span>@enderror
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

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="leaveEditModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-edit"></i> Edit Leave Application <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
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
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Email<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="email" required disabled>
                                @error('name') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="leave_days">Available Leave Days<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="available_leave_days" disabled >
                                @error('available_leave_days') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="leave_type">Leave Type<span class="required" style="color: red">*</span></label>
                              <select wire:model.debounce.300ms="leave_type_id" class="form-control" required>
                                  <option value="" selected>Select Leave Type</option>
                                  @foreach ($leave_types as $leave_type)
                                      <option value="{{$leave_type->id}}">{{$leave_type->name}}</option>
                                  @endforeach
                              </select>
                                @error('leave_type_id') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>

                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="name">Start Leave Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="from" required>
                                @error('from') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="to">End Leave Date<span class="required" style="color: red">*</span></label>
                                <input type="date" class="form-control" wire:model.debounce.300ms="to" required >
                                @error('to') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                        <!-- /.col-md-6 -->
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Reason for leave<span class="required" style="color: red">*</span></label>
                               <textarea class="form-control" wire:model.debounce.300ms="reason"  cols="30" rows="5" required></textarea>
                                @error('reason') <span class="text-danger error">{{ $message }}</span>@enderror
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
