<?php

namespace App\Http\Controllers;



use App\Models\Quotation;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Mail\SendingQuotationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;

class QuotationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public $email;


    public function index()
    {
        return view('quotations.index');
    }
    public function deleted()
    {
        return view('quotations.delete');
    }

    public function preview($id){
        $quotation = Quotation::find($id);
        $quotation_products = $quotation->quotation_products;
        $company = $quotation->company;
        return view('quotations.preview')->with([
            'quotation' => $quotation,
            'company' => $company,
            'quotation_products' => $quotation_products,]);
    }

    public function print($id){
        $quotation = Quotation::find($id);
        $quotation_products = $quotation->quotation_products;
        $company = $quotation->company;
        return view('quotations.print')->with([
            'quotation' => $quotation,
            'company' => $company,
            'quotation_products' => $quotation_products,]);
    }
    public function email($id){
        $quotation = Quotation::find($id);
        $quotation_products = $quotation->quotation_products;
        $company = $quotation->company;
        $this->email = $quotation->customer ? $quotation->customer->email : "";
         
        Mail::to($this->email)->send(new SendingQuotationMail($quotation, $quotation_products, $company));
        return redirect()->back();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Email Sent Successfully!!"
        ]);
    }

    public function generatePDF(Quotation $quotation){
        $data = [
            'quotation' => $quotation,
            'quotation_products' => $quotation->quotation_product,
            'company' =>  $company = $quotation->company
        ];
        $pdf = PDF::loadView('quotations.quotation', $data);

        return $pdf->download('quotation.pdf');

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('quotations.create');
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
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function show(Quotation $quotation)
    {
        return view('quotations.show')->with('quotation',$quotation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function edit(Quotation $quotation)
    {
        return view('quotations.edit')->with('quotation', $quotation);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Quotation $quotation)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quotation  $quotation
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quotation $quotation)
    {
        $quotation_products = $quotation->quotation_products;
        if ($quotation_products->count()>0) {
            foreach ($quotation_products as $quotation_product) {
                $quotation_product->delete();
            }
        }
        $quotation->delete();
        Session::flash('success','Quotation Deleted Successfully');
        return redirect()->back();

    }
}
