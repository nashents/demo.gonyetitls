<div>
    <div class="row mt-30">
    <div class="col-md-10 col-md-offset-1">
        <!-- /.row -->
        <ul class="nav nav-tabs nav-justified" role="tablist">
            <li role="presentation"class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Salary Details</a></li>
            <li role="presentation"><a href="#salary_items" aria-controls="salary_items" role="tab" data-toggle="tab">Salary Items</a></li>
        </ul>
        <div class="tab-content bg-white p-15">
            <div role="tabpanel" class="tab-pane active" id="basic">
                <table class="table table-striped">

                    <tbody class="text-center line-height-35 ">

                        <tr>
                            <th class="w-10 text-center line-height-35">Salary#</th>
                            <td class="w-20 line-height-35"> {{$salary->salary_number}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Employee</th>
                            <td class="w-20 line-height-35"> {{$salary->employee ? $salary->employee->name : ""}} {{$salary->employee ? $salary->employee->surname : ""}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Currency</th>
                            <td class="w-20 line-height-35">{{$salary->currency ? $salary->currency->name : ""}}</td>
                        </tr>
                    
                            <tr>
                                <th class="w-10 text-center line-height-35">Basic</th>
                                <td class="w-20 line-height-35">
                                    @if ($salary->basic)
                                    {{ $salary->currency ? $salary->currency->symbol : "" }}{{ number_format($salary->basic,2)}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Gross</th>
                                <td class="w-20 line-height-35">
                                    @if ($salary->gross)
                                    {{ $salary->currency ? $salary->currency->symbol : "" }}{{ number_format($salary->gross,2)}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Net</th>
                                <td class="w-20 line-height-35">
                                    @if ($salary->net)
                                    {{ $salary->currency ? $salary->currency->symbol : "" }}{{ number_format($salary->net,2)}}
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="w-10 text-center line-height-35">Status</th>
                                <td class="w-20 line-height-35"><span class="badge bg-{{$salary->status == 1 ? "success" : "danger"}}">{{$salary->status == 1 ? "Active" : "Inactive"}}</span></td>
                            </tr> 
                          
                    </tbody>
                </table>
              
            </div>
            <div role="tabpanel" class="tab-pane" id="salary_items">
                @livewire('salaries.items', ['id' => $salary->id])
            </div>
              <div class="row">
                <div class="col-md-12">
                    <div class="btn-group pull-right mt-10" >
                       <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                    </div>
                </div>
                </div>

            <!-- /.section-title -->
        </div>
    </div>
    </div>
</div>
