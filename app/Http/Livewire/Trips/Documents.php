<?php

namespace App\Http\Livewire\Trips;

use Carbon\Carbon;
use App\Models\Trip;
use Livewire\Component;
use App\Models\TripDocument;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;

class Documents extends Component
{
    use WithFileUploads;

    public $trip;
    public $trip_id;
    public $user_id;
    public $documents;
    public $document_id;
    public $rand;

    public $document_number;
    public $title;
    public $file;
    public $date;
    public $filename;



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

    private function resetInputFields(){
        $this->document_number = "" ;
        $this->title = "" ;
        $this->file = null;
        $this->date = "";
        $this->rand++;

    }

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'title.*' => 'required|string',
        'title.0' => 'required|string',
        'file.*' => 'required|file',
        'file.0' => 'required|file',
        'document_number.*' => 'nullable|unique:trip_documents,document_number,NULL,id,deleted_at,NULL|string|min:2',
        'document_number.0' => 'nullable|unique:trip_documents,document_number,NULL,id,deleted_at,NULL|string|min:2',
    ];

    public function mount($id){
    $this->trip = Trip::find($id);
    $this->documents = TripDocument::where('trip_id', $this->trip->id)->latest()->get();
    }

    public function store(){
        try{

            if (isset($this->file) && isset($this->title) && $this->file != "") {
                // if ($this->file->count()>0) {
                foreach ($this->file as $key => $value) {
                  if(isset($this->file[$key])){
                      $file = $this->file[$key];
                      // get file with ext
                      $fileNameWithExt = $file->getClientOriginalName();
                      //get filename
                      $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                      //get extention
                      $extention = $file->getClientOriginalExtension();
                      //file name to store
                      $fileNameToStore= $filename.'_'.time().'.'.$extention;
                      $file->storeAs('/documents', $fileNameToStore, 'my_files');
    
                  }
                  $document = new TripDocument;
                  $document->trip_id = $this->trip->id;
                  if(isset($this->title[$key])){
                  $document->title = $this->title[$key];
                  }
                  if(isset($this->document_number[$key])){
                  $document->document_number = $this->document_number[$key];
                  }
                  if(isset($this->date[$key])){
                  $document->date = $this->date[$key];
                  }
                  if (isset($fileNameToStore)) {
                      $document->filename = $fileNameToStore;
                  }
               
                  $document->save();
    
                }
                       # code...
            //   }
            }

           
    
            $this->dispatchBrowserEvent('hide-documentModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Document(s) Uploaded Successfully!!"
            ]);

        }catch(\Exception $e){
            // Set Flash Message
            $this->dispatchBrowserEvent('alert',[
                'type'=>'error',
                'message'=>"Something went wrong while uploading document(s)!!"
            ]);
        }
    }
    public function edit($id){

        $document = TripDocument::find($id);
        $this->user_id = $document->user_id;
        $this->trip_id = $document->trip_id;
        $this->title = $document->title;
        $this->document_number = $document->document_number;
        $this->filename = $document->filename;
        $this->date = $document->date;
        $this->document_id = $document->id;
        $this->dispatchBrowserEvent('show-documentEditModal');

        }

        public function update()
        {
            if ($this->document_id) {
                try{
                          if(isset($this->file)){
                              $file = $this->file;
                              // get file with ext
                              $fileNameWithExt = $file->getClientOriginalName();
                              //get filename
                              $filename = pathinfo($fileNameWithExt, PATHINFO_FILENAME);
                              //get extention
                              $extention = $file->getClientOriginalExtension();
                              //file name to store
                              $fileNameToStore= $filename.'_'.time().'.'.$extention;
                              $file->storeAs('/documents', $fileNameToStore, 'my_files');
                          }

                          $document = TripDocument::find($this->document_id);
                          $document->trip_id = $this->trip->id;
                          $document->document_number = $this->document_number;
                          $document->title = $this->title;
                          $document->date = $this->date;
                          if (isset($fileNameToStore)) {
                              $document->filename = $fileNameToStore;
                          }
                
                          $document->update();

                          $this->dispatchBrowserEvent('hide-documentEditModal');
                          $this->resetInputFields();
                          $this->dispatchBrowserEvent('alert',[
                              'type'=>'success',
                              'message'=>"Document Updated Successfully!!"
                          ]);
                  
            }catch(\Exception $e){
                // Set Flash Message
                $this->dispatchBrowserEvent('alert',[
                    'type'=>'error',
                    'message'=>"Something went wrong while updating document(s)!!"
                ]);
            }

            }
        }
    public function render()
    {
        $this->documents = TripDocument::where('trip_id', $this->trip->id)->latest()->get();
        return view('livewire.trips.documents',[
            'documents'=> $this->documents
        ]);
    }
}
