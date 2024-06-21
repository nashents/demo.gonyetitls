<?php

namespace App\Http\Livewire\Bills;

use App\Models\Bill;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Rejected extends Component
{
    use WithPagination;

    protected $paginationTheme = 'bootstrap';
    public $search;
    protected $queryString = ['search'];
    public $from;
    public $to;
    public $bill_filter;
    private $bills;
    public $bill_id;
    public $trip_id;
    public $authorize;
    public $comments;
    public $bill;


    public function mount(){
        $this->resetPage();
        $this->bill_filter = "created_at";
    }

  
    public function authorize($id){
        $invoice = Invoice::find($id);
        $this->invoice_id = $invoice->id;
        $this->invoice = $invoice;
        $this->dispatchBrowserEvent('show-invoiceAuthorizationModal');
      }

      public function update(){
      try{
            $invoice = Invoice::find($this->invoice_id);
            $invoice->authorized_by_id = Auth::user()->id;
            $invoice->authorization = $this->authorize;
            $invoice->comments = $this->comments;
            $invoice->update();

        if ($this->authorize == "approved") {
            $this->dispatchBrowserEvent('hide-invoiceAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Invoice Approved Successfully"
            ]);
            return redirect()->route('invoices.approved');
        }else {
            $this->dispatchBrowserEvent('hide-invoiceAuthorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Invoice Rejected Successfully"
            ]);
            return redirect()->route('invoices.rejected');
        }
}
catch(\Exception $e){
    $this->dispatchBrowserEvent('hide-invoiceEditModal');
    $this->dispatchBrowserEvent('alert',[
        'type'=>'error',
        'message'=>"Something went wrong while trying to authorize an invoice!!"
    ]);
    }

      }

         
    public function dateRange(){
 
        // $this->resetPage();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
       
        $departments = Auth::user()->employee->departments;
        foreach($departments as $department){
            $department_names[] = $department->name;
        }
        $roles = Auth::user()->roles;
        foreach($roles as $role){
            $role_names[] = $role->name;
        }
        $ranks = Auth::user()->employee->ranks;
        foreach($ranks as $rank){
            $rank_names[] = $rank->name;
        }
        if (in_array('Admin', $role_names) || in_array('Super Admin', $role_names)) {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.bills.rejected',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )
                        ->where('authorization','rejected')
                        ->where('bill_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('bill_date','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('ticket', function ($query) {
                            return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('invoice', function ($query) {
                            return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('transporter', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('container', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('purchase', function ($query) {
                            return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('vendor', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                       
                    ]);
                }else {
                    return view('livewire.bills.rejected',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->where('authorization','rejected')->whereBetween($this->bill_filter,[$this->from, $this->to] )->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                       
                    ]);
                }
               
            }
            elseif (isset($this->search)) {
               
                return view('livewire.bills.rejected',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth('created_at', date('m'))
                    ->where('authorization','rejected')
                    ->whereYear('created_at', date('Y'))
                    ->where('bill_number','like', '%'.$this->search.'%')
                    ->orWhere('status','like', '%'.$this->search.'%')
                    ->orWhere('bill_date','like', '%'.$this->search.'%')
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('ticket', function ($query) {
                        return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('invoice', function ($query) {
                        return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('transporter', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('currency', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('container', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('purchase', function ($query) {
                        return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('vendor', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                   
                ]);
            }
            else {
               
                return view('livewire.bills.rejected',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth('created_at', date('m'))
                    ->where('authorization','rejected')
                    ->whereYear($this->bill_filter, date('Y'))->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                   
                ]);
              
            }
        }else {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.bills.rejected',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
                        ->where('authorization','rejected')
                        ->where('bill_number','like', '%'.$this->search.'%')
                        ->orWhere('status','like', '%'.$this->search.'%')
                        ->orWhere('bill_date','like', '%'.$this->search.'%')
                        ->orWhereHas('horse', function ($query) {
                            return $query->where('registration_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('ticket', function ($query) {
                            return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('currency', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('trip', function ($query) {
                            return $query->where('trip_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('invoice', function ($query) {
                            return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('transporter', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('container', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('purchase', function ($query) {
                            return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                        })
                        ->orWhereHas('vendor', function ($query) {
                            return $query->where('name', 'like', '%'.$this->search.'%');
                        })
                        ->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                       
                    ]);
                }else{
                    return view('livewire.bills.rejected',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->where('authorization','rejected')->whereBetween($this->bill_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                       
                    ]);
                }
              
               
            }
            elseif (isset($this->search)) {
                return view('livewire.bills.rejected',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth($this->bill_filter, date('m'))
                    ->where('authorization','rejected')
                    ->whereYear($this->bill_filter, date('Y'))->where('bill_number','like', '%'.$this->search.'%')->where('user_id',Auth::user()->id)
                    ->where('bill_number','like', '%'.$this->search.'%')
                    ->orWhere('bill_date','like', '%'.$this->search.'%')
                    ->orWhere('status','like', '%'.$this->search.'%')
                   
                    ->orWhereHas('horse', function ($query) {
                        return $query->where('registration_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('currency', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('ticket', function ($query) {
                        return $query->where('ticket_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('trip', function ($query) {
                        return $query->where('trip_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('invoice', function ($query) {
                        return $query->where('invoice_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('transporter', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('container', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('purchase', function ($query) {
                        return $query->where('purchase_number', 'like', '%'.$this->search.'%');
                    })
                    ->orWhereHas('vendor', function ($query) {
                        return $query->where('name', 'like', '%'.$this->search.'%');
                    })
                    ->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                   
                ]);
            }
            else {
                
                return view('livewire.bills.rejected',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->where('authorization','rejected')->whereMonth($this->bill_filter, date('m'))
                    ->whereYear($this->bill_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                   
                ]);

            }

        }
    }
}
