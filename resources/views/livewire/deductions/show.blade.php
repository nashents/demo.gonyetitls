<div>
    <div class="row mt-30">
    
        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1" >

            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Deduction Details</a></li>

            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                    <table class="table table-striped">

                        <tbody class="text-center line-height-35 ">

                                <tr>
                                    <th class="w-10 text-center line-height-35">CreatedBy</th>
                                    <td class="w-20 line-height-35">{{$deduction->user ? $deduction->user->name : ""}} {{$deduction->user ? $deduction->user->surname : ""}} </td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Type</th>
                                    <td class="w-20 line-height-35">{{$deduction->type}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Name</th>
                                    <td class="w-20 line-height-35">{{$deduction->name}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Description</th>
                                    <td class="w-20 line-height-35">{{$deduction->description}}</td>
                                </tr>
                                <tr>
                                    <th class="w-10 text-center line-height-35">Status</th>
                                    <td class="w-20 line-height-35"><span class="badge bg-{{$deduction->status == 1 ? "success" : "danger"}}">{{$deduction->status == 1 ? "Active" : "Inactive"}}</span></td>
                                </tr>
                             
                        </tbody>
                    </table>
                </div>
              
                <div class="row">
                    <div class="col-md-12">
                        <div class="btn-group pull-right mt-10" >
                           <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                        </div>
                    </div>
                    </div>

            </div>
        </div>
        <!-- /.col-md-9 -->
    </div>
   
</div>
