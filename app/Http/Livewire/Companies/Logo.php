<?php

namespace App\Http\Livewire\Companies;

use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Facades\Image;

class Logo extends Component
{
    use WithFileUploads;

    public $logo;

    public function updated($value){
        $this->validateOnly($value);
    }
    protected $rules = [
        'logo' => 'required|image',
    ];

    private function resetInputFields(){
        $this->logo = '';
    }

    public function upload(){

        if($this->logo){
            $image =  $this->logo;
            $filename = time() . '.' . $image->getClientOriginalExtension();
            Image::make($image)->resize(300,300,function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('/images/uploads/' . $filename));

            $user = Auth::user();
            if (isset($user->employee->company)) {
               $company = $user->employee->company;
               $company->logo = $filename;
               $company->update();
            }elseif (isset($user->company)) {
                $company = $user->company;
                $company->logo = $filename;
                $company->update();
            }
            $this->dispatchBrowserEvent('hide-logoModal');
            $this->resetInputFields();
            $this->dispatchBrowserEvent('alert',[
                'type'=>'success',
                'message'=>"Logo Uploaded Successfully!!"
            ]);
        }
        else{
            return redirect()->back();
        }
    }
    public function render()
    {
        return view('livewire.companies.logo');
    }
}
