<div>
    <table id="cashflowsTable" class="table table-striped table-bordered table-sm table-responsive" cellspacing="0" width="100%">
        <thead>
          <tr>
            <th class="th-sm">Date
            </th>
            <th class="th-sm">Type
            </th>
            <th class="th-sm">Category
            </th>
            <th class="th-sm">Currency
            </th>
            <th class="th-sm">Amount
            </th>
            <th class="th-sm">Action
            </th>
          </tr>
        </thead>
        @if ($cashflows->count()>0)
        <tbody>
            @foreach ($cashflows as $cashflow)
          <tr>
            <td>{{$cashflow->date}}</td>
            <td>{{$cashflow->transaction_type}}</td>
            <td>{{$cashflow->transaction_category}}</td>
            <td>{{$cashflow->currency ? $cashflow->currency->name : ""}}</td>
            <td>
                @if (isset($cashflow->amount))
                {{$cashflow->currency ? $cashflow->currency->symbol : ""}}{{number_format($cashflow->amount,2)}}
                @endif
            </td>
            <td class="w-10 line-height-35 table-dropdown">
                <div class="dropdown">
                    <button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fa fa-bars"></i>
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu">
                        <li><a href="{{route('cashflows.show',$cashflow->id)}}"  ><i class="fa fa-eye color-default"></i> View</a></li>
                       {{-- <li><a href="#" wire:click="update({{$cashflow->id}})" ><i class="fas fa-paperclip color-success"></i> Invoice/Receipt</a></li>
                        <li><a href="#" data-toggle="modal" data-target="#cashflowDeleteModal{{ $cashflow->id }}" ><i class="fa fa-trash color-danger"></i>Delete</a></li> --}}
                    </ul>
                </div>
                @include('cashflows.delete')
        </td>
          </tr>
          @endforeach
        </tbody>
        @else
            <img style="padding-left: 35%; padding-top:7%; width:100% height:100%" src="{{asset('images/nodata.png')}}" alt="">
         @endif
      </table>
</div>
