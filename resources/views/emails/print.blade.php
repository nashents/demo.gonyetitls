
<!doctype html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport"
        content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <title>Quotation to PDF</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
  <script src="https://kit.fontawesome.com/0154e08647.js" crossorigin="anonymous"></script>
</head>
<style>
body{margin-top:20px;
background-color: #f7f7ff;
}
#invoice {
    padding: 0px;
}

.invoice {
    position: relative;
    background-color: #FFF;
    min-height: 680px;
    padding: 15px
}

.invoice header {
    padding: 10px 0;
    margin-bottom: 20px;
    border-bottom: 1px solid #0d6efd
}

.invoice .company-details {
    text-align: right
}

.invoice .company-details .name {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .contacts {
    margin-bottom: 20px
}

.invoice .invoice-to {
    text-align: left
}

.invoice .invoice-to .to {
    margin-top: 0;
    margin-bottom: 0
}

.invoice .invoice-details {
    text-align: right
}

.invoice .invoice-details .invoice-id {
    margin-top: 0;
    color: #0d6efd
}

.invoice main {
    padding-bottom: 50px
}

.invoice main .thanks {
    margin-top: -100px;
    font-size: 2em;
    margin-bottom: 50px
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #0d6efd;
    background: #e7f2ff;
    padding: 10px;
}

.invoice main .notices .notice {
    font-size: 1.2em
}

.invoice table {
    width: 100%;
    border-collapse: collapse;
    border-spacing: 0;
    margin-bottom: 20px
}

.invoice table td,
.invoice table th {
    padding: 15px;
    background: #eee;
    border-bottom: 1px solid #fff
}

.invoice table th {
    white-space: nowrap;
    font-weight: 400;
    font-size: 16px
}

.invoice table td h3 {
    margin: 0;
    font-weight: 400;
    color: #0d6efd;
    font-size: 1.2em
}

.invoice table .qty,
.invoice table .total,
.invoice table .unit {
    text-align: right;
    font-size: 1.2em
}

.invoice table .no {
    color: #fff;
    font-size: 1.6em;
    background: #0d6efd
}

.invoice table .unit {
    background: #ddd
}

.invoice table .total {
    background: #0d6efd;
    color: #fff
}

.invoice table tbody tr:last-child td {
    border: none
}

.invoice table tfoot td {
    background: 0 0;
    border-bottom: none;
    white-space: nowrap;
    text-align: right;
    padding: 10px 20px;
    font-size: 1.2em;
    border-top: 1px solid #aaa
}

.invoice table tfoot tr:first-child td {
    border-top: none
}
.card {
    position: relative;
    display: flex;
    flex-direction: column;
    min-width: 0;
    word-wrap: break-word;
    background-color: #fff;
    background-clip: border-box;
    border: 0px solid rgba(0, 0, 0, 0);
    border-radius: .25rem;
    margin-bottom: 1.5rem;
    box-shadow: 0 2px 6px 0 rgb(218 218 253 / 65%), 0 2px 6px 0 rgb(206 206 238 / 54%);
}

.invoice table tfoot tr:last-child td {
    color: #0d6efd;
    font-size: 1.4em;
    border-top: 1px solid #0d6efd
}

.invoice table tfoot tr td:first-child {
    border: none
}

.invoice footer {
    width: 100%;
    text-align: center;
    color: #777;
    border-top: 1px solid #aaa;
    padding: 8px 0
}

@media print {
    .invoice {
        font-size: 11px !important;
        overflow: hidden !important
    }
    .invoice footer {
        position: absolute;
        bottom: 10px;
        page-break-after: always
    }
    .invoice>div:last-child {
        page-break-before: always
    }
}

.invoice main .notices {
    padding-left: 6px;
    border-left: 6px solid #0d6efd;
    background: #e7f2ff;
    padding: 10px;
}
</style>
<div class="container">
    <div class="card">
        <div class="card-body">
            <div id="invoice">
                {{-- <div class="toolbar hidden-print">
                    <div class="text-end">
                        <button type="button" onclick="goBack()" class="btn btn-default"><i class="fa fa-arrow-left"></i> Back</button>
                        <a href="{{route('quotations.pdf', $quotation)}}" class="btn btn-dark"><i class="fa fa-print"></i> Print</a>
                        <a href="{{route('quotations.pdf', $quotation)}}" class="btn btn-danger"><i class="fa fa-file-pdf-o"></i> Export as PDF</a>
                    </div>
                    <hr>
                </div> --}}
                <div class="invoice overflow-auto">
                    <div style="min-width: 600px">
                        <header>
                            <div class="row">
                                <div class="col">
                                    <a href="javascript:;">
    												<img src="{{asset('images/tinmac-logo.png')}}" width="80" alt="">
												</a>
                                </div>
                                <div class="col company-details">
                                    <h4 class="name">

                                            {{-- SubHeading {{$quotation->subheading}} --}}

                                    </h4>
                                    <h2 class="name">
                                        <a target="_blank" href="javascript:;">
									Tinmac
									</a>
                                    </h2>
                                    <div>12 New Davies Way, Waterfalls, Harare Zimbabwe</div>
                                    <div>+263 08 6442 88421
                                    </div>
                                    <div>info@tinmac.com</div>
                                </div>
                            </div>
                        </header>
                        <main>
                            <div class="row contacts">
                                <div class="col invoice-to">
                                    <div class="text-gray-light">QUOTATION TO:</div>
                                    <h2 class="to">{{$quotation->customer->name}}</h2>
                                    <div class="address">{{$quotation->customer->street_address}} {{$quotation->customer->suburb}}, {{$quotation->customer->city}}, {{$quotation->customer->country}}</div>
                                    <div class="email"><a href="mailto:{{$quotation->customer->email}}">{{$quotation->customer->email}}</a>
                                    </div>
                                </div>
                                <div class="col invoice-details">
                                    <h1 class="invoice-id">QUOTATION # {{$quotation->quotation_number}}</h1>
                                    <div class="date">Date of Invoice: {{$quotation->date}}</div>
                                    <div class="date">Due Date: {{$quotation->expiry}}</div>
                                </div>
                            </div>
                            <table>
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th class="text-left">DESCRIPTION</th>
                                        <th class="text-right">Weight</th>
                                        <th class="text-right">Rate</th>
                                        <th class="text-right">FREIGHT</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $n = 1;
                                    @endphp
                                    @foreach ($quotation_products as $product)
                                    <tr>
                                        <td class="no">{{$n++}}</td>
                                        <td class="text-left">
                                            <h3>

                                                    {{ucfirst($product->cargo->group)}} {{$product->cargo->name}}

                                            </h3>

                                                {{$product->quantity}} Bags / Litres
									  </td>
                                        <td class="unit">$ {{$product->rate}}</td>
                                        <td class="qty"> {{$product->weight}}</td>
                                        <td class="total">$ {{$product->freight}}</td>
                                    </tr>

                                    @endforeach


                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">SUBTOTAL</td>
                                        <td>${{$quotation->subtotal}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">TAX 14.5%</td>
                                        <td>${{$quotation->vat}}</td>
                                    </tr>
                                    <tr>
                                        <td colspan="2"></td>
                                        <td colspan="2">GRAND TOTAL</td>
                                        <td>${{$quotation->total}}</td>
                                    </tr>
                                </tfoot>
                            </table>
                            <div class="thanks">Thank you!</div>
                            <div class="notices">
                                <div>NOTICE:</div>
                                <div class="notice">{{$quotation->memo}}.</div>
                            </div>
                        </main>
                        <footer>{{$quotation->footer}}.</footer>
                    </div>
                    <!--DO NOT DELETE THIS div. IT is responsible for showing footer always at the bottom-->
                    <div></div>
                </div>
            </div>
        </div>
    </div>
</div>

  <script>
    window.addEventListener("load", window.print());
  </script>
</body>
</html>
