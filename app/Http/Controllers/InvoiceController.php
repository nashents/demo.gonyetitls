<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class InvoiceController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('invoices.index');
    }
    public function customerStatements()
    {
        return view('customer_statements.index');
    }
    public function customerStatementsPreview($selectedCustomer = null, $selectedType = null, $from = null, $to = null){
        return view('customer_statements.preview')->with([
            'selectedCustomer' => $selectedCustomer,
            'selectedType' => $selectedType,
            'from' => $from,
            'to' => $to,
            ]);
    }

    public function customerStatementsPrint($selectedCustomer = null, $selectedType = null, $from = null, $to = null){
        $company = Auth::user()->employee->company;
        $customer = Customer::find($selectedCustomer);
        if ( isset($selectedCustomer) && $selectedType == "Outstanding Invoices") {

            $invoices = Invoice::where('customer_id', $selectedCustomer)
            ->where('authorization', 'approved')
            ->where('status', 'Unpaid')
            ->orWhere('customer_id', $selectedCustomer)
            ->where('authorization','approved')
            ->where('status', 'Partial')->get();

            return view('customer_statements.print')->with([
                'selectedCustomer' => $selectedCustomer,
                'selectedType' => $selectedType,
                'from' => $from,
                'to' => $to,
                'invoices' => $invoices,
                'company' => $company,
                'customer' => $customer,
                ]);
    
        }elseif ( isset($selectedCustomer) && $selectedType == "Account Activity") {
            if (isset($from) && isset($to)) {
                $invoices = DB::table('invoices')->select('invoice_number as number','currency_id','date as transaction_date','total as amount','balance','created_at')
                ->where('authorization','approved')
                ->where('customer_id', $selectedCustomer)
                ->where('deleted_at', NULL)
                ->whereBetween('created_at',[$from, $to] );
                // ->orderBy('created_at','asc');
                $results = DB::table('payments')->select('payment_number as number','currency_id','date as transaction_date','amount','balance','created_at')
                ->where('customer_id', $selectedCustomer)
                ->where('deleted_at', NULL)
                ->whereBetween('created_at',[$from, $to] )
                // ->orderBy('created_at','asc')
                ->union($invoices)
                ->get()->sortByDesc('transaction_date');

                // $results = $invoices->union($payments);
            }
            return view('customer_statements.print')->with([
                'selectedCustomer' => $selectedCustomer,
                'selectedType' => $selectedType,
                'from' => $from,
                'to' => $to,
                'invoices' => $invoices,
                'results' => $results,
                'company' => $company,
                'customer' => $customer,
                ]);
        }
       
   
    }

    public function customerStatementsPDF($selectedCustomer = null, $selectedType = null, $from = null, $to = null){
        $company = Auth::user()->employee->company;
        $customer = Customer::find($selectedCustomer);
        if ( isset($selectedCustomer) && $selectedType == "Outstanding Invoices") {
            $invoices = Invoice::where('customer_id', $selectedCustomer)
            ->where('authorization', 'approved')
            ->where('status', 'Unpaid')
            ->orWhere('customer_id', $selectedCustomer)
            ->where('authorization','approved')
            ->where('status', 'Partial')->get();
            
            $data = [
                'selectedCustomer' => $selectedCustomer,
                'selectedType' => $selectedType,
                'from' => $from,
                'to' => $to,
                'invoices' => $invoices,
                'company' => $company,
                'customer' => $customer,
            ];
    
        }elseif ( isset($selectedCustomer) && $selectedType == "Account Activity") {
            if (isset($from) && isset($to)) {
                $invoices = DB::table('invoices')->select('invoice_number as number','currency_id','date as transaction_date','total as amount','balance','created_at')
                ->where('authorization','approved')
                ->where('customer_id', $selectedCustomer)
                ->where('deleted_at', NULL)
                ->whereBetween('created_at',[$from, $to] );
                // ->orderBy('created_at','asc');
                $results = DB::table('payments')->select('payment_number as number','currency_id','date as transaction_date','amount','balance','created_at')
                ->where('customer_id', $selectedCustomer)
                ->where('deleted_at', NULL)
                ->whereBetween('created_at',[$from, $to] )
                // ->orderBy('created_at','asc')
                ->union($invoices)
                ->get()->sortByDesc('transaction_date');

                // $results = $invoices->union($payments);
            }
            $data = [
                'selectedCustomer' => $selectedCustomer,
                'selectedType' => $selectedType,
                'from' => $from,
                'to' => $to,
                'invoices' => $invoices,
                'results' => $results,
                'company' => $company,
                'customer' => $customer,
            ];
          
        }
       
        $pdf = PDF::loadView('customer_statements.customer_statement', $data);

        return $pdf->download('customer_statement.pdf');

    }
    public function rejected()
    {
        return view('invoices.rejected');
    }
    public function pending()
    {
        return view('invoices.pending');
    }
    public function approved()
    {
        return view('invoices.approved');
    }
    
    public function deleted()
    {
        return view('invoices.deleted');
    }

    public function preview($id){
        $invoice = Invoice::find($id);
        $company = $invoice->company;
        $invoice_items = $invoice->invoice_items;
        return view('invoices.preview')->with([
            'invoice' => $invoice,
            'company' => $company,
            'invoice_items' => $invoice_items,
            ]);
    }

    public function print($id){
        $invoice = Invoice::find($id);
        $company = $invoice->company;
        $invoice_items = $invoice->invoice_items;
        return view('invoices.print')->with([
            'invoice' => $invoice,
            'company' => $company,
            'invoice_items' => $invoice_items,

        ]);
    }

    public function generatePDF(Invoice $invoice){
        $company = $invoice->company;
        $invoice_items = $invoice->invoice_items;
        $data = [
            'invoice' => $invoice,
            'company' => $company,
            'invoice_items' => $invoice_items,
        ];
        $pdf = PDF::loadView('invoices.invoice', $data);

        return $pdf->download('invoice.pdf');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('invoices.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function show(Invoice $invoice)
    {
        return view('invoices.show')->with('invoice', $invoice);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function edit(Invoice $invoice)
    {
        return view('invoices.edit')->with('invoice', $invoice);

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Invoice $invoice)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Invoice  $invoice
     * @return \Illuminate\Http\Response
     */
    public function destroy(Invoice $invoice)
    {
        $bills = $invoice->bills;
        if (isset($bills)) {
            foreach ($bills as $bill) {
                $bill_expenses = $bill->bill_expenses;
                if (isset($bill_expenses)) {
                    foreach ($bill_expenses as $bill_expense) {
                        $bill_expense->delete();
                    }
                }
                $bill->delete();
            }
        }
        $invoice_items = $invoice->invoice_items;
        if ($invoice_items->count()>0) {
            foreach ($invoice_items as $invoice_item) {
                $invoice_item->delete();
            }
        }
        $payments = $invoice->payments;
        if ($payments->count()>0) {
            foreach ($payments as $payment) {
                if ($payment) {
                    $cashflow = $payment->cash_flow;

                    if (isset($cashflow)) {
                        $cashflow->delete();
                    }

                    $denominations = $payment->denominations;
                    if (isset($denominations)) {
                        foreach ($denominations as $denomination) {
                            $denomination->delete();
                        }
                    }
                    $receipt = $payment->receipt;
                    if (isset($receipt)) {
                        $receipt->delete();
                    }
                }
                $payment->delete();
            }
        }
        $invoice->delete();
        Session::flash('success','Invoice Deleted Successfully!!');
        return redirect()->back();
    }
}
