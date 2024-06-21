@extends('layouts.main')
@section('title')
Quotation Preview |@if (Auth::user()->employee->company)
{{Auth::user()->employee->company->name}}
@elseif (Auth::user()->company)
{{Auth::user()->company->name}}
@endif
@endsection
@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            @livewire('quotations.preview', ['quotation' => $quotation,
            'company' => $company,
            'quotation_products' => $quotation_products,])
        </div>
    </div>
</div>
@endsection
    
 
    