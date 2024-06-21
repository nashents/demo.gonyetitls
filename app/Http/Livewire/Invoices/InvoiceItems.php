<?php

namespace App\Http\Livewire\Invoices;

use App\Models\Trip;
use App\Models\Account;
use App\Models\Invoice;
use App\Models\Product;
use Livewire\Component;
use App\Models\Destination;
use App\Models\InvoiceItem;
use App\Models\IncomeStream;

class InvoiceItems extends Component
{
    public $income_streams;
    public $income_stream;
    public $products;
    public $product;
    public $selectedproduct;
    public $income_stream_id;
    public $reason;
    public $qty;
    public $amount;
    public $trips;
    public $subtotal;
    public $subtotal_incl;
    public $item_subtotal;
    public $added_item_subtotal;
    public $deleted_item_subtotal;
    public $edited_item_subtotal;
    public $x;
    public $tax_rate;
    public $tax_accounts;
    public $selectedTax = [];
    public $tax_amount;
    public $total_tax_amount;
    public $invoice_total;
    public $invoice_subtotal;
    public $total;
    public $selectedTrip;
    public $description;
    public $invoice;
    public $invoice_id;
    public $invoice_items;
    public $invoice_item_id;

    public $inputs = [];
    public $i = 1;
    public $n = 1;

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }
    private function resetInputFields(){
        $this->selectedTrip = "" ;
        $this->description = "" ;
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }

    public function mount($id){

        $this->invoice_id = $id;
        $this->invoice = Invoice::find($id);
        $this->invoice_subtotal =  $this->invoice->subtotal;
        $this->invoice_total =   $this->invoice->total;
        $this->total_tax_amount =   $this->invoice->tax_amount;
        $this->tax_accounts = Account::whereHas('account_type', function ($query) {
            return $query->where('name','Sales Taxes');
        })->get();
        $this->trips = Trip::latest()->get();
        $this->products = Product::where('sell',True)->orderBy('name','asc')->get();
        $this->reason = $this->invoice->reason;
        $this->invoice_items = InvoiceItem::withTrashed()->where('invoice_id',$this->invoice_id)->get();

    }

    public function updatedSelectedProduct($id, $key){
        if (!is_null($id)) {
            $product = Product::find($id);
            if (isset($product)) {
                if ($product->sell_price) {
                    $this->amount[$key] = $product->sell_price;
                }
                if ($product->tax_id) {
                    $this->selectedTax[$key] = $product->tax_id;
                    $tax = Account::find($product->tax_id);
                    if (isset($tax)) {
                        $this->tax_rate[$key] = $tax->rate;
                    }
                    
                }  
            }
           
        }
    }

    public function store(){


        if ($this->reason == "Trip") {
     
            if (isset($this->selectedTrip)) {
                foreach ($this->selectedTrip as $key => $value) {
                    
                    $trip = Trip::find($this->selectedTrip[$key]);
                    $cargo = $trip->cargo;
                    $weight = $trip->weight.'tons' ;
                    $cargo_name = $cargo ? $cargo->name : "";
                    if (isset($cargo)) {
                        if ($trip->cargo->type == "Solid") {
                            $cargo_measurement = $trip->quantity.' '. $trip->measurement;
                        }else {
                            $cargo_measurement =  $trip->litreage_at_20.' '. $trip->measurement;
                        }
                        
                    }else{
                        $cargo_measurement = "";
                    }
                  
                  
    
                    if ($trip->horse) {
                        $regnumber = $trip->horse ? $trip->horse->registration_number : "";
                    }elseif ($trip->vehicle) {
                        $regnumber = $trip->vehicle ? $trip->vehicle->registration_number : "";
                    }else{
                        $regnumber = "";
                    }
                    
                    $origin = Destination::find($trip->from);
                    $origin_city = $origin ? $origin->city : "";
                    $destination = Destination::find($trip->to);
                    $destination_city = $destination ? $destination->city : "";
                    $symbol =  $trip->currency ? $trip->currency->symbol : "";
    
                 
    
                    if ($trip->freight_calculation) {
                        if ($trip->freight_calculation == "flat_rate") {
                         
                           $formula = "R";
                           $variables =  $symbol.$trip->rate;
                        }
                        elseif($trip->freight_calculation == "rate_weight"){
                           
                            if ($trip->cargo) {
                                if ($trip->cargo->type == "Solid") {
                                    $formula = "R*W";
                                    $variables = $symbol.$trip->rate.'*'.$trip->weight;
                                }elseif ($trip->cargo->type == "Liquid") {
                                    $formula = "R*L";
                                    $variables = $symbol.$trip->rate.'*'.$trip->litreage_at_20;
                                }
                            }
                          
                        }
                        elseif($trip->freight_calculation == "rate_distance"){
                            $formula = "R*D";
                            $variables = $symbol.$trip->rate.'*'.$trip->distance;
                        }
                        elseif($trip->freight_calculation == "rate_weight_distance"){
                            if ($trip->cargo) {
                                if ($trip->cargo->type == "Solid") {
                                    $formula = "R*W*D";
                                    $variables = $symbol.$trip->rate.'*'.$trip->weight.'*'.$trip->distance;
                                }elseif ($trip->cargo->type == "Liquid") {
                                    $formula = "R*L*D";
                                    $variables = $symbol.$trip->rate.'*'.$trip->litreage_at_20.'*'.$trip->distance;
                                }
                            }
                        }else {
                            $formula = "";
                            $variables = "";
                        }
                    }
                    
                    $lp = $trip->loading_point ? $trip->loading_point->name : "";
                    $op = $trip->offloading_point ? $trip->offloading_point->name : "";
                    $from = $origin_city.' '.$lp ;
                    $to = $destination_city.' '.$op ;
                    $rate = $trip->rate;
                    $quantity = $trip->quantity.' '.$trip->measurement;
                    $litreage = $trip->litreage_at_20.' '.$trip->measurement;
                    $trip_number = $trip->trip_number;
                    $document = TripDocument::where('trip_id',$trip->id)->where('title','POD')->get()->first();
                    $pod_number = $document ? $document->document_number : "";
                  
    
                    if (isset($cargo) && $cargo->type == "Solid") {
                        $cargo_description = $cargo_name .' '.$weight.' '. $quantity;
                    }elseif (isset($cargo) && $cargo->type == "Liquid") {
                        $cargo_description =  $cargo_name.' '.$weight .' '. $litreage;
                    }else {
                        $cargo_description = "";
                    }
                   
                    $load_details = $cargo_description .' '.$formula .' '.$variables;
    
                    $invoice_item = new InvoiceItem;
                    $invoice_item->invoice_id = $invoice->id;

                   if (isset($this->selectedTax[$key])) {
                    $invoice_item->tax_id = $this->selectedTax[$key];
                }
    
                    if (isset($this->tax_rate[$key])) {
                        $invoice_item->tax_rate = $this->tax_rate[$key];
                    }
    
                    if (isset($this->selectedTrip[$key])) {
                        $invoice_item->trip_id = $this->selectedTrip[$key];
                    }
    
                    $invoice_item->trip_details = $load_details .' '.  $from .' to '.  $to .' '.  $regnumber.' '.$pod_number;
    
                    $invoice_item->qty = $this->qty[$key];
                    $invoice_item->amount = $this->amount[$key];
    
                    if ((isset($this->amount[$key]) && isset($this->qty[$key]))) {
                        $this->subtotal = $this->amount[$key]*$this->qty[$key];
                        $invoice_item->subtotal = $this->subtotal;
                    }
                    if (isset($this->tax_rate[$key])) {
                        $this->tax_amount = ($this->subtotal * ($this->tax_rate[$key] / 100 ));
                        $invoice_item->tax_amount =  $this->tax_amount;
                        $this->subtotal_incl =  $this->tax_amount + $this->subtotal ;
                    }else{
                        $this->subtotal_incl = $this->subtotal;
                    }
                   
                    $invoice_item->subtotal_incl = $this->subtotal_incl;
                    $invoice_item->save();
    
                    $this->total_tax_amount =  $this->total_tax_amount + $this->tax_amount;
                    $this->total = $this->total +  $this->subtotal_incl;
    
                }
            }
    
         
            $invoice = Invoice::find($this->invoice->id);
          
            $invoice->tax_amount =  $this->total_tax_amount;
            $invoice->subtotal = $this->invoice_subtotal;
            $invoice->total = $this->invoice_total;
            $invoice->exchange_rate = $this->exchange_rate;
            $invoice->exchange_amount = $this->exchange_amount;
            $invoice->balance = $this->total;
            $invoice->update();

     
        }elseif ($this->reason == "General Invoice") {

            if (isset($this->selectedProduct)) {
                foreach($this->selectedProduct as $key => $value){
                    $invoice_item = new InvoiceItem;
                    $invoice_item->invoice_id = $invoice->id;
    
                    if (isset($this->selectedProduct[$key])) {
                        $invoice_item->product_id = $this->selectedProduct[$key];
                    }
                    if (isset($this->qty[$key])) {
                        $invoice_item->qty = $this->qty[$key];
                    }
                    if (isset($this->amount[$key])) {
                        $invoice_item->amount = $this->amount[$key];
                    }
                     if ((isset($this->amount[$key]) && isset($this->qty[$key]))) {
                        $this->subtotal = $this->amount[$key]*$this->qty[$key];
                        $invoice_item->subtotal = $this->subtotal;
                    }
                    if (isset($this->tax_rate[$key])) {
                        $this->tax_amount = ($this->subtotal * ($this->tax_rate[$key] / 100 ));
                        $invoice_item->tax_amount =  $this->tax_amount;
                        $this->subtotal_incl =  $this->tax_amount + $this->subtotal ;
                    }else{
                        $this->subtotal_incl = $this->subtotal;
                    }
                   
                    $invoice_item->subtotal_incl = $this->subtotal_incl;
                    $invoice_item->save();
    
                    $this->total_tax_amount =  $this->total_tax_amount + $this->tax_amount;
                    $this->invoice_total = $this->invoice_total +  $this->subtotal_incl;
                    $this->invoice_subtotal = $this->invoice_subtotal +  $this->subtotal;
                }
    
            }
            
    
            $invoice = Invoice::find($this->invoice->id);
          
            $invoice->tax_amount =  $this->total_tax_amount;
            $invoice->subtotal = $this->invoice_subtotal;
            $invoice->total = $this->invoice_total;
            $invoice->exchange_rate = $this->exchange_rate;
            $invoice->exchange_amount = $this->exchange_amount;
            $invoice->balance = $this->invoice_total;
            $invoice->update();
        }

       
        $this->dispatchBrowserEvent('hide-addInvoiceItemModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Invoice Item(s) Added Successfully!!"
        ]);
    }


    public function edit($id){
        $this->invoice_item_id = $id;
        $this->invoice_item = InvoiceItem::find($id);
        $this->selectedTrip = $this->invoice_item->trip_id;
        $this->selectedproduct = $this->invoice_item->product_id;
        $this->description = $this->invoice_item->trip_details;
        $this->dispatchBrowserEvent('show-editInvoiceItemModal');
    }

    public function removeShow($invoice_item_id){
        $this->invoice_item = InvoiceItem::find($invoice_item_id);
        $this->dispatchBrowserEvent('show-removeModal');
    }

    public function removeInvoiceItem(){ 
      
        $invoice = $this->invoice_item->invoice;
        $this->deleted_item_subtotal = $this->subtotal - $this->invoice_item->subtotal;

        if ($this->tax_rate != "") {
            $this->total = $this->deleted_item_subtotal + ( $this->deleted_item_subtotal * ($this->tax_rate/100));
            $this->tax_amount = ($this->deleted_item_subtotal * ($this->tax_rate / 100 ));
            $invoice =  Invoice::find($this->invoice->id);
            $invoice->total = $this->total;
            $invoice->subtotal = $this->deleted_item_subtotal;
            $invoice->tax_amount = $this->tax_amount;
            $invoice->update();
        }else{
            $this->total = $this->deleted_item_subtotal;
            $invoice =  Invoice::find($this->invoice->id);
            $invoice->total = $this->total;
            $invoice->subtotal = $this->deleted_item_subtotal;
            $invoice->update();
        }

        $this->invoice_item->delete();

        $this->dispatchBrowserEvent('hide-removeModal');
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Invoice Item Deleted Successfully!!"
        ]);
    }

    public function update(){
        $invoice_item = InvoiceItem::find($this->invoice_item_id);
        $invoice_item->trip_id = $this->selectedTrip;
        $invoice_item->trip_details = $this->description;
        $invoice_item->update();

      
        $this->dispatchBrowserEvent('hide-editInvoiceItemModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Invoice Item Updated Successfully!!"
        ]);
    }

   
    public function render()
    {

        if ((isset($this->exchange_rate) && $this->exchange_rate > 0)  &&  ( isset($this->invoice_total) && $this->invoice_total > 0 )) {

            $this->exchange_amount = $this->exchange_rate * $this->total;

        }

        $this->invoice_items = InvoiceItem::where('invoice_id',$this->invoice_id)->get();
        return view('livewire.invoices.invoice-items',[
            'invoice_items' => $this->invoice_items
        ]);
    }
}
