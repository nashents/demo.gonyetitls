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
                            <div class="panel-title">
                               Latest Leave Application Records
                            </div>

                        </div>
                        <div class="panel-body p-20">

                            <table id="leavesTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
                                <thead>
                                  <tr>
                                    <th class="th-sm">Date Applied
                                    </th>
                                    <th class="th-sm">Name
                                    </th>
                                    <th class="th-sm">Leave Type
                                    </th>
                                    <th class="th-sm">Application Date
                                    </th>
                                    <th class="th-sm">HR Status
                                    </th>
                                    <th class="th-sm">HOD Status
                                    </th>
                                    <th class="th-sm">Actions
                                    </th>

                                  </tr>
                                </thead>
                                @php
                                    $ranks = Auth::user()->employee->ranks;
                                    foreach($ranks as $rank){
                                    $rank_names[] = $rank->name;
                                    }
                                    $roles = Auth::user()->roles;
                                    foreach($roles as $role){
                                    $role_names[] = $role->name;
                                    }
                                    $departments = Auth::user()->employee->departments;
                                    foreach($departments as $department){
                                    $department_names[] = $department->name;
                                    }
                                @endphp
                                @if (Auth::user()->employee->department_head)
                                <div class="panel-title">
                                    Head Of Department Decision
                                    <br>
                                 </div>
                                @if ($leaves->count()>0)
                                <tbody>
                                    @foreach ($leaves as $leave)
                                  <tr>
                                    <td>{{$leave->created_at}}</td>
                                    <td>{{ucfirst($leave->user ? $leave->user->name : "")}} {{ucfirst($leave->user ? $leave->user->surname : "")}}</td>
                                    <td>{{$leave->leave_type ? $leave->leave_type->name : ""}}</td>
                                    <td>{{$leave->created_at}}</td>
                                    <td><span class="badge bg-{{($leave->management_decision == '1') ? 'success' : (($leave->management_decision == '0') ? 'danger' : 'warning') }}">{{($leave->management_decision == '1') ? 'approved' : (($leave->management_decision == '0') ? 'rejected' : 'pending' )}}</span></td>
                                    <td><span class="badge bg-{{($leave->hod_decision == '1') ? 'success' : (($leave->hod_decision == '0') ? 'danger' : 'warning') }}">{{($leave->hod_decision == '1') ? 'approved' : (($leave->hod_decision == '0') ? 'rejected' : 'pending') }}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('leaves.show',$leave->id)}}"  ><i class="fa fa-eye color-default"></i>View</a></li>
                                                <li><a href="#"  wire:click="authorize({{$leave->id}})" ><i class="fa fa-gavel color-default"></i> Authorization</a></li>
                                            </ul>
                                        </div>
                                </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                            <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                            @endif
                            @endif
                            @if (in_array('Management',$rank_names))
                            <div class="panel-title">
                                Management Decision
                                <br>
                                <br>
                             </div>
                            @if ($management_leaves->count()>0)
                                <tbody>
                                    @foreach ($management_leaves as $leave)
                                  <tr>
                                    <td>{{$leave->created_at}}</td>
                                    <td>{{ucfirst($leave->user ? $leave->user->name : "")}} {{ucfirst($leave->user ? $leave->user->surname : "")}}</td>
                                    <td>{{$leave->leave_type ? $leave->leave_type->name : ""}}</td>
                                    <td>{{$leave->created_at}}</td>
                                    <td><span class="badge bg-{{($leave->management_decision == 'approved') ? 'success' : (($leave->management_decision == 'rejected') ? 'danger' : 'warning') }}">{{($leave->management_decision == 'approved') ? 'approved' : (($leave->management_decision == 'rejected') ? 'rejected' : 'pending' )}}</span></td>
                                    <td><span class="badge bg-{{($leave->hod_decision == 'approved') ? 'success' : (($leave->hod_decision == 'rejected') ? 'danger' : 'warning') }}">{{($leave->hod_decision == 'approved') ? 'approved' : (($leave->hod_decision == 'rejected') ? 'rejected' : 'pending') }}</span></td>
                                    <td class="w-10 line-height-35 table-dropdown">
                                        <div class="dropdown">
                                            <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                <i class="fa fa-bars"></i>
                                                <span class="caret"></span>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li><a href="{{route('leaves.show',$leave->id)}}"  ><i class="fa fa-eye color-default"></i>View</a></li>
                                                <li><a href="#"  wire:click="authorize({{$leave->id}})" ><i class="fa fa-gavel color-default"></i> Authorization</a></li>
                                            </ul>

                                        </div>
                                </td>
                                  </tr>
                                  @endforeach
                                </tbody>
                                @else
                            <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
                            @endif
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

    <div wire:ignore.self data-backdrop="static" data-keyboard="false" class="modal" id="decisionModal" tabindex="-1" role="dialog" aria-labelledby="modal4Label" data-backdrop-color="blue">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="modal4Label"><i class="fa fa-gavel"></i>Approve | Reject Application<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></h4>
                </div>
                <form wire:submit.prevent="decision">
                <div class="modal-body">
                    @php
                    $ranks = Auth::user()->employee->ranks;
                    foreach($ranks as $rank){
                    $rank_names[] = $rank->name;
                    }
                    $roles = Auth::user()->roles;
                    foreach($roles as $role){
                    $role_names[] = $role->name;
                    }
                    $departments = Auth::user()->employee->departments;
                    foreach($departments as $department){
                    $department_names[] = $department->name;
                    }
                @endphp
                @if (Auth::user()->employee->department_head)
                <div class="panel-title">
                    HOD Decision
                 </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Decision</label>
                              <select wire:model.debounce.300ms="decision" class="form-control" >
                                <option value=""> Select Decision</option>
                                <option value="approved">Approve</option>
                                <option value="rejected">Reject</option>
                              </select>
                                @error('decision') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="name">Reason</label>
                               <textarea wire:model.debounce.300ms="reason" class="form-control"  cols="30" rows="5"></textarea>
                                @error('reason') <span class="text-danger error">{{ $message }}</span>@enderror
                            </div>
                        </div>
                    </div>
                @endif
                @if ((in_array('Management',$rank_names)))
                <div class="panel-title">
                    Management Decision
                 </div>
                 <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Decision</label>
                          <select wire:model.debounce.300ms="decision" class="form-control" >
                            <option value=""> Select Decision</option>
                            <option value="approved">Approve</option>
                            <option value="rejected">Reject</option>
                          </select>
                            @error('decision') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="name">Reason</label>
                           <textarea wire:model.debounce.300ms="reason" class="form-control"  cols="30" rows="5"></textarea>
                            @error('reason') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                    </div>
                </div>
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

</div>
