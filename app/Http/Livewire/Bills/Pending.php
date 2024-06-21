<?php

namespace App\Http\Livewire\Bills;

use id;
use App\Models\Bill;
use App\Models\User;
use App\Models\Expense;
use Livewire\Component;
use App\Models\CashFlow;
use App\Models\Container;
use App\Models\BillExpense;
use Livewire\WithPagination;
use App\Mail\invoiceOrderMail;
use App\Models\TransportOrder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class Pending extends Component
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
        $bill = Bill::find($id);
        $this->bill_id = $bill->id;
        $this->bill = $bill;
        $this->dispatchBrowserEvent('show-authorizationModal');
      }

      public function update(){
    //   try{
            $bill = Bill::find($this->bill_id);
            $bill->authorized_by_id = Auth::user()->id;
            $bill->authorization = $this->authorize;
            $bill->comments = $this->comments;
            $bill->update();

        if ($this->authorize == "approved") {

            $this->dispatchBrowserEvent('hide-authorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Bill Approved Successfully"
            ]);
            return redirect()->route('bills.approved');
            
        }else {
            $this->dispatchBrowserEvent('hide-authorizationModal');
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Bill Rejected Successfully"
            ]);
            return redirect()->route('bills.rejected');
        }
// }
// catch(\Exception $e){
//     $this->dispatchBrowserEvent('hide-billEditModal');
//     $this->dispatchBrowserEvent('alert',[
//         'type'=>'error',
//         'message'=>"Something went wrong while trying to authorize an bill!!"
//     ]);
//     }

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
                    return view('livewire.bills.pending',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )
                        ->where('authorization','pending')
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
                    return view('livewire.bills.pending',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->where('authorization','pending')->whereBetween($this->bill_filter,[$this->from, $this->to] )->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                       
                    ]);
                }
               
            }
            elseif (isset($this->search)) {
               
                return view('livewire.bills.pending',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth('created_at', date('m'))
                    ->where('authorization','pending')
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
               
                return view('livewire.bills.pending',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth('created_at', date('m'))
                    ->where('authorization','pending')
                    ->whereYear($this->bill_filter, date('Y'))->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                   
                ]);
              
            }
        }else {
            if (isset($this->from) && isset($this->to)) {
                if (isset($this->search)) {
                    return view('livewire.bills.pending',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereBetween($this->bill_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)
                        ->where('authorization','pending')
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
                    return view('livewire.bills.pending',[
                        'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->where('authorization','pending')->whereBetween($this->bill_filter,[$this->from, $this->to] )->where('user_id',Auth::user()->id)->orderBy('bill_number','desc')->paginate(10),
                        'bill_filter' => $this->bill_filter,
    
                       
                    ]);
                }
              
               
            }
            elseif (isset($this->search)) {
                return view('livewire.bills.pending',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->whereMonth($this->bill_filter, date('m'))
                    ->where('authorization','pending')
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
                
                return view('livewire.bills.pending',[
                    'bills' => Bill::query()->with('invoice','transporter','container','top_up','trip','horse','driver','purchase','currency','payments')->where('authorization','pending')->whereMonth($this->bill_filter, date('m'))
                    ->whereYear($this->bill_filter, date('Y'))->where('user_id',Auth::user()->id)->orderBy('bill_number','desc')->paginate(10),
                    'bill_filter' => $this->bill_filter,

                   
                ]);

            }

        }
   
    }
}
