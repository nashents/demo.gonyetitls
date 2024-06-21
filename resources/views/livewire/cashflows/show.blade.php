<div>
    <div class="row mt-30">
        <!-- /.col-md-3 -->

        <div class="col-md-10 col-md-offset-1">
            <ul class="nav nav-tabs nav-justified" role="tablist">
                <li role="presentation" class="active"><a href="#basic" aria-controls="basic" role="tab" data-toggle="tab">Transaction Details</a></li>
                <li role="presentation"><a href="#documents" aria-controls="documents" role="tab" data-toggle="tab">Documents</a></li>
            </ul>
            <div class="tab-content bg-white p-15">
                <div role="tabpanel" class="tab-pane active" id="basic">
                <table class="table table-striped">
                    <tbody class="text-center line-height-35">

                        <tr>
                            <th class="w-10 text-center line-height-35">Account</th>
                            <td class="w-20 line-height-35">
                                {{$cashflow->account ? $cashflow->account->name : ""}}  {{$cashflow->account->currency ? $cashflow->account->currency->name : ""}}
                                </td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Date</th>
                            <td class="w-20 line-height-35">
                                {{$cashflow->date}}
                                </td>
                        </tr>
                        @if ($cashflow->horse)
                        <tr>
                            <th class="w-10 text-center line-height-35">Horse</th>
                            <td class="w-20 line-height-35">
                                {{ucfirst($cashflow->horse->horse_make ? $cashflow->horse->horse_make->name : "")}} {{ucfirst($cashflow->horse->horse_model ? $cashflow->horse->horse_model : "")}} {{ucfirst($cashflow->horse->registration_number)}}
                                </td>
                        </tr>
                        @endif
                        @if ($cashflow->trip)
                        <tr>
                            <th class="w-10 text-center line-height-35">Trip</th>
                            <td class="w-20 line-height-35">{{ucfirst($cashflow->trip->trip_number)}} From: {{App\Models\Destination::find($cashflow->trip->from) ? App\Models\Destination::find($cashflow->trip->from)->country->name : ""}} {{App\Models\Destination::find($cashflow->trip->from) ? App\Models\Destination::find($cashflow->trip->from)->city : ""}} To: {{App\Models\Destination::find($cashflow->trip->to) ? App\Models\Destination::find($cashflow->trip->to)->country->name : ""}} {{App\Models\Destination::find($cashflow->trip->to) ? App\Models\Destination::find($cashflow->trip->to)->city : ""}}</td>
                        </tr>
                        @endif
                        @if ($cashflow->type)
                        <tr>
                            <th class="w-10 text-center line-height-35">Type</th>
                            <td class="w-20 line-height-35">
                                <span class="label label-{{$cashflow->type == "Income" ? "success" : "danger"}} label-rounded">{{$cashflow->type}}</span>
                                </td>
                        </tr>
                        @endif
                      @if ($cashflow->category)
                      <tr>
                        <th class="w-10 text-center line-height-35">Category</th>

                        <td class="w-20 line-height-35"> <span class="label label-primary label-rounded">{{$cashflow->category}}</span></td>

                    </tr>
                      @endif
                      @if ($cashflow->transaction_type)
                      <tr>
                        <th class="w-10 text-center line-height-35">Transaction Type</th>

                        <td class="w-20 line-height-35"> {{$cashflow->transaction_type}}</td>

                        </tr>
                      @endif
                     @if ($cashflow->transaction_category)
                     <tr>
                        <th class="w-10 text-center line-height-35">Transaction Category</th>

                        <td class="w-20 line-height-35"> {{$cashflow->transaction_category}}</td>

                        </tr>
                     @endif
                     
                        <tr>
                            <th class="w-10 text-center line-height-35">Currency</th>
                            <td class="w-20 line-height-35">{{$cashflow->currency ? $cashflow->currency->name : ""}}</td>
                        </tr>
                        <tr>
                            <th class="w-10 text-center line-height-35">Amount</th>
                            <td class="w-20 line-height-35">
                                {{$cashflow->currency ? $cashflow->currency->symbol : ""}}{{number_format($cashflow->amount,2)}}
                                </td>
                        </tr>
                        @if ($cashflow->description)
                        <tr>
                           <th class="w-10 text-center line-height-35">Description</th>
   
                           <td class="w-20 line-height-35"> {{$cashflow->description}}</td>
   
                           </tr>
                        @endif
                        @if ($cashflow->notes)
                        <tr>
                           <th class="w-10 text-center line-height-35">Notes</th>
   
                           <td class="w-20 line-height-35"> {{$cashflow->notes}}</td>
   
                           </tr>
                        @endif
                    
                    </tbody>
                </table>
                </div>
                <div role="tabpanel" class="tab-pane" id="documents">
                    @livewire('documents.index', ['id' => $cashflow->id,'category' =>'cash_flow'])
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
    @section('extra-js')
    <script>
    $(document).ready( function () {
        $('#cashflowsTable').DataTable();
    } );
    </script>
    <script>
    $(document).ready( function () {
        $('#documentsTable').DataTable();
    } );
    </script>
@endsection
    

</div>
