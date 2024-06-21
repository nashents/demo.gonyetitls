<?php

namespace App\Http\Livewire\TicketInventories;

use Livewire\Component;
use App\Models\Inventory;
use App\Models\TicketInventory;
use App\Models\InventoryDispatch;
use App\Models\InventoryRequisition;
use Illuminate\Support\Facades\Auth;

class Index extends Component
{
    public $ticket;
    public $ticket_id;
    public $inventories;
    public $selectedInventory;
    public $ticket_inventories;
    public $ticket_inventory_id;
    public $qty;
    public $amount;
    public $inventory_qty;
    public $weight;
    public $measurement;
    public $subtotal;
    public $usage;
    public $previous_weight;

    public $inputs = [];
    public $i = 1;
    public $n = 1;

    public function add($i)
    {
        $i = $i + 1;
        $this->i = $i;
        array_push($this->inputs ,$i);
    }

    public function remove($i)
    {
        unset($this->inputs[$i]);
    }
    public function mount($ticket){
        $this->ticket = $ticket;
        $this->inventories = Inventory::where('status',1)->latest()->get();
        $this->ticket_inventories = TicketInventory::where('ticket_id', $this->ticket->id)->latest()->get();
    }


    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'selectedInventory' => 'required',
        'qty' => 'required',
    ];

    private function resetInputFields(){
        $this->selectedInventory = '';
        $this->qty = '';
    }


    public function addProducts(){
        // try{

        foreach ($this->selectedInventory as $key => $value) {

            $inventory = Inventory::find($this->selectedInventory[$key]);

            $ticket_inventory = new  TicketInventory;
            $ticket_inventory->ticket_id = $this->ticket->id;
            $ticket_inventory->inventory_id =  $this->selectedInventory[$key];
            $ticket_inventory->vehicle_id =$this->ticket->vehicle_id;
            $ticket_inventory->currency_id =$inventory->currency_id;
            $ticket_inventory->horse_id = $this->ticket->horse_id;
            $ticket_inventory->trailer_id =$this->ticket->trailer_id;
            $ticket_inventory->qty =  1;
            $ticket_inventory->weight =  $this->weight[$key];
            $ticket_inventory->measurement =  $this->measurement[$key];

            if ((isset($this->weight[$key]) && $this->weight[$key] > 0) && (isset($inventory->weight) && $inventory->weight ) && (isset($inventory->rate) && $inventory->rate > 0 )) {
                $amount = (($this->weight[$key] / $inventory->weight) * $inventory->rate);
                $ticket_inventory->amount = (($this->weight[$key] / $inventory->weight) * $inventory->rate);
                $ticket_inventory->subtotal = $amount * 1;
            }
           
            $ticket_inventory->save();

            $requisition = new InventoryRequisition;
            $requisition->user_id = Auth::user()->id;
            $requisition->ticket_inventory_id = $ticket_inventory->id;
            $requisition->inventory_id = $this->selectedInventory[$key];
            $requisition->weight = $this->weight[$key];
            $requisition->measurement = $this->measurement[$key];
            $requisition->ticket_id = $this->ticket->id;
            $requisition->vehicle_id =$this->ticket->vehicle_id;
            $requisition->horse_id = $this->ticket->horse_id;
            $requisition->trailer_id =$this->ticket->trailer_id;
            $requisition->qty = 1;
            $requisition->save();
         
            $dispatch = new InventoryDispatch;
            $dispatch->user_id = Auth::user()->id;
            $dispatch->inventory_requisition_id = $requisition->id;
            $dispatch->inventory_id = $this->selectedInventory[$key];
            $dispatch->ticket_inventory_id = $ticket_inventory->id;
            $dispatch->weight = $this->weight[$key];
            $dispatch->measurement = $this->measurement[$key];
            $dispatch->horse_id = $this->ticket->horse_id;
            $dispatch->vehicle_id =$this->ticket->vehicle_id;
            $dispatch->trailer_id = $this->ticket->trailer_id;
            $dispatch->part_number = $inventory->part_number;
            $dispatch->save();
            
            if ((isset($inventory->balance) && $inventory->balance > 0) && (isset($this->weight[$key]) && $this->weight[$key] > 0)) {
                $inventory->balance = $inventory->balance - $this->weight[$key];
                if ($inventory->balance <= 0) {
                    $inventory->status = 0;
                }
                $inventory->update();
            }
           
        }


        $this->dispatchBrowserEvent('hide-ticket_inventoryModal');
        $this->resetInputFields();
        $this->dispatchBrowserEvent('alert',[
            'type'=>'success',
            'message'=>"Spares/Repairs Added Successfully!!"
        ]);

    //     }
    //     catch(\Exception $e){
    //     // Set Flash Message
    //     $this->dispatchBrowserEvent('alert',[
    //         'type'=>'error',
    //         'message'=>"Something goes wrong while creating cargo!!"
    //     ]);
    // }
    }

    public function edit($id){
        $ticket_inventory = TicketInventory::find($id);
        $this->selectedInventory = $ticket_inventory->inventory_id;
        $this->tickect_id = $ticket_inventory->ticket_id;
        $this->horse_id = $ticket_inventory->horse_id;
        $this->vehicle_id = $ticket_inventory->vehicle_id;
        $this->currency_id = $ticket_inventory->currency_id;
        $this->trailer_id = $ticket_inventory->trailer_id;
        $this->previous_weight = $ticket_inventory->weight;
        $this->weight = $ticket_inventory->weight;
        $this->measurement = $ticket_inventory->measurement;
        $this->ticket_inventory_id = $ticket_inventory->id;
        $this->dispatchBrowserEvent('show-ticket_inventoryEditModal');
    }

    public function update(){

        $inventory = Inventory::find($this->selectedInventory);

        $ticket_inventory =  TicketInventory::find($this->ticket_inventory_id);
        $ticket_inventory->ticket_id = $this->ticket->id;
        $ticket_inventory->inventory_id =  $this->selectedInventory;
        $ticket_inventory->vehicle_id =$this->ticket->vehicle_id;
        $ticket_inventory->currency_id =$inventory->currency_id;
        $ticket_inventory->horse_id = $this->ticket->horse_id;
        $ticket_inventory->trailer_id =$this->ticket->trailer_id;
        $ticket_inventory->qty =  1;
        $ticket_inventory->weight =  $this->weight;
        $ticket_inventory->measurement =  $this->measurement;

        if ((isset($this->weight) && $this->weight > 0) && (isset($inventory->weight) && $inventory->weight ) && (isset($inventory->rate) && $inventory->rate > 0 )) {
            $inventory_weight = $inventory->weight + $this->previous_weight;
            $amount = (($this->weight /  $inventory_weight) * $inventory->rate);
            $ticket_inventory->amount = (($this->weight / $inventory_weight) * $inventory->rate);
            $ticket_inventory->subtotal = $amount * 1;
        }
       
        $ticket_inventory->update();

        $requisition = InventoryRequisition::find($ticket_inventory->inventory_requisition->id);
        $requisition->user_id = Auth::user()->id;
        $requisition->ticket_inventory_id = $ticket_inventory->id;
        $requisition->inventory_id = $this->selectedInventory;
        $requisition->weight = $this->weight;
        $requisition->measurement = $this->measurement;
        $requisition->ticket_id = $this->ticket->id;
        $requisition->vehicle_id =$this->ticket->vehicle_id;
        $requisition->horse_id = $this->ticket->horse_id;
        $requisition->trailer_id =$this->ticket->trailer_id;
        $requisition->qty = 1;
        $requisition->update();
     
        $dispatch = InventoryDispatch::find($ticket_inventory->inventory_dispatch->id);
        $dispatch->user_id = Auth::user()->id;
        $dispatch->inventory_requisition_id = $requisition->id;
        $dispatch->inventory_id = $this->selectedInventory;
        $dispatch->ticket_inventory_id = $ticket_inventory->id;
        $dispatch->weight = $this->weight;
        $dispatch->measurement = $this->measurement;
        $dispatch->horse_id = $this->ticket->horse_id;
        $dispatch->vehicle_id =$this->ticket->vehicle_id;
        $dispatch->trailer_id = $this->ticket->trailer_id;
        $dispatch->part_number = $inventory->part_number;
        $dispatch->update();
        
        if ((isset($inventory->balance) && $inventory->balance > 0) && (isset($this->weight) && $this->weight > 0)) {
            $balance = $inventory->balance + $this->previous_weight;
            $inventory->balance =  $balance - $this->weight;
            if ($inventory->balance <= 0) {
                $inventory->status = 0;
            }else{
                $inventory->status = 1;
            }
            $inventory->update();
        }
       
    }

    public function render()
    {
        $this->ticket_inventories = TicketInventory::where('ticket_id', $this->ticket->id)->latest()->get();
        return view('livewire.ticket-inventories.index',[
            'ticket_inventories' => $this->ticket_inventories,
            'inventory_quantity' => $this->inventory_qty,
        ]);
    }
}
