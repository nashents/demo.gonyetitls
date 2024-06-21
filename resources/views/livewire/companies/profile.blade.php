<div>
    <div class="tab-content bg-white p-15">
        <div role="tabpanel" class="tab-pane active" id="personal">
            <form wire:submit.prevent="update()">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Name<span class="required" style="color: red">*</span></label>
                            <input type="text" class="form-control" wire:model.debounce.300ms="name" required>
                            @error('name') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Trips allowable loss %</label>
                            <input type="number" step="any" class="form-control" wire:model.debounce.300ms="allowable_loss_percentage">
                            @error('allowable_loss_percentage') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                    </div>
                </div>
                   
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email<span class="required" style="color: red">*</span></label>
                                <input type="email" class="form-control"  wire:model.debounce.300ms="email" placeholder="Email used to receive emails" required>
                                @error('email') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div> 
                            <div class="form-group">
                                <label for="email">2nd Email</label>
                                <input type="email" class="form-control"  wire:model.debounce.300ms="second_email" placeholder="2nd Email" >
                                @error('second_email') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div> 
                            <div class="form-group">
                                <label for="email">3rd Email</label>
                                <input type="email" class="form-control"  wire:model.debounce.300ms="third_email" placeholder="3rd Email" >
                                @error('third_email') <span class="error" style="color:red">{{ $message }}</span> @enderror
                                </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="phonenumber">Phonenumber<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="phonenumber" required>
                                @error('phonenumber') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="phonenumber">2nd Phonenumber</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="second_phonenumber" placeholder="2nd Phonenumber">
                                @error('second_phonenumber') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                            <div class="form-group">
                                <label for="phonenumber">3rd Phonenumber</label>
                                <input type="text" class="form-control" wire:model.debounce.300ms="third_phonenumber" placeholder="3rd Phonenumber">
                                @error('third_phonenumber') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">No Reply Email<span class="required" style="color: red">*</span></label>
                                <input type="email" class="form-control"  wire:model.debounce.300ms="noreply" placeholder="Email used to send emails" disabled required>
                                @error('noreply') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">ERP URL<span class="required" style="color: red">*</span></label>
                                <input type="text" class="form-control"  wire:model.debounce.300ms="website" placeholder="ERP URL eg http://www.erp.gonyetilts.co.zw" required disabled>
                                @error('website') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vat">Currencies<span class="required" style="color: red">*</span></label>
                              <select class="form-control" wire:model.debounce.300ms="currency_id" required>
                                    <option value="">Select Trading Currency</option>
                                    @foreach ($currencies as $currency)
                                        <option value="{{ $currency->id }}">{{ $currency->name }}</option>
                                    @endforeach
                              </select>
                                @error('currency_id') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vat">Interest</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="interest" placeholder="%" >
                                @error('interest') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="vat">VAT</label>
                                <input type="number" step="any" class="form-control" wire:model.debounce.300ms="vat" placeholder="%" >
                                @error('vat') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                      
                    </div>
                    <div class="row">
                        <div class="col-md-4">
                            <label for="one" class="radio-label">Vat Number</label>
                            <input type="text" class="form-control"  wire:model.debounce.300ms="vat_number" placeholder="Enter VAT Number">
                            @error('vat_number') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-4">
                            <label for="one" class="radio-label">TIN Number</label>
                            <input type="text" class="form-control"  wire:model.debounce.300ms="tin_number" placeholder="Enter TIN Number" >
                            @error('tin_number') <span class="text-danger error">{{ $message }}</span>@enderror
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="period">Fiscalize Invoices</label>
                                <select class="form-control"wire:model.debounce.300ms="fiscalize">
                                    <option value="" disabled>Choose Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('fiscalize') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                           
                        </div>
                    </div>
                  
                   <div class="row">
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="period">Trip Rates Managed By Finance</label>
                            <select class="form-control"wire:model.debounce.300ms="rates_managed_by_finance">
                                <option value="" disabled>Choose Option</option>
                                <option value="0">No</option>
                                <option value="1">Yes</option>
                            </select>
                            @error('rates_managed_by_finance') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="period">Data Period</label>
                            <select class="form-control"wire:model.debounce.300ms="period">
                                <option value="">Select Data Period</option>
                                <option value="all">All</option>
                                <option value="2024">2024</option>
                                <option value="2023">2023</option>
                                <option value="2022">2022</option>
                                <option value="2021">2021</option>
    
                            </select>
                            @error('period') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="form-group">
                            <label for="color">Color</label>
                            <input type="color" class="form-control"  wire:model.debounce.300ms="color" class="form-control">
                            @error('color') <span class="error" style="color:red">{{ $message }}</span> @enderror
                        </div>
                    </div>
                   </div>
                  
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="country">Country</label>
                                <input type="text" class="form-control"  wire:model.debounce.300ms="country" >
                                @error('country') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div> 
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="city">City/Town</label>
                                <input type="text" class="form-control"  wire:model.debounce.300ms="city" >
                             @error('city') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="suburb">Suburb</label>
                                <input type="text" class="form-control"  wire:model.debounce.300ms="suburb">
                                @error('suburb') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="street_address">Street Address</label>
                                <input type="text" class="form-control"  wire:model.debounce.300ms="street_address">
                                @error('street_address') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period">Use Offloading Values for Customers</label>
                                <select class="form-control"wire:model.debounce.300ms="offloading_details">
                                    <option value="" disabled>Choose Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('offloading_details') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period">Use Offloading Values for Transporters</label>
                                <select class="form-control"wire:model.debounce.300ms="transporter_offloading_details">
                                    <option value="" disabled>Choose Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('transporter_offloading_details') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period">Serialize Invoices By Customer</label>
                                <select class="form-control"wire:model.debounce.300ms="invoice_serialize_by_customer">
                                    <option value="" disabled>Choose Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('invoice_serialize_by_customer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="period">Serialize Quotations By Customer</label>
                                <select class="form-control"wire:model.debounce.300ms="quotation_serialize_by_customer">
                                    <option value="" disabled>Choose Option</option>
                                    <option value="1">Yes</option>
                                    <option value="0">No</option>
                                </select>
                                @error('quotation_serialize_by_customer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <br>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_memo">Quotation Notes / Terms & Conditions</label>
                               <textarea wire:model.debounce.300ms="quotation_memo" cols="30" rows="5" class="form-control" placeholder="Enter quotation notes or terms of service that are visible to your customer" ></textarea>
                               @error('quotation_memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="quotation_footer">Quotation Footer</label>
                               <textarea wire:model.debounce.300ms="quotation_footer" cols="30" rows="5" class="form-control" placeholder="Enter footer for your quotations eg (Tax Information / Thank you note)"></textarea>
                               @error('quotation_footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Invoice Notes  / Terms & Conditions</label>
                               <textarea wire:model.debounce.300ms="invoice_memo" cols="30" rows="5" class="form-control" placeholder="Enter invoice notes or terms of service that are visible to your customer"></textarea>
                               @error('invoice_memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_footer">Invoice Footer</label>
                               <textarea wire:model.debounce.300ms="invoice_footer" cols="30" rows="5" class="form-control" placeholder="Enter footer for your invoices eg (Tax Information / Thank you note)"></textarea>
                               @error('invoice_footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="exampleInputEmail1">Receipt Notes  / Terms & Conditions</label>
                               <textarea wire:model.debounce.300ms="receipt_memo" cols="30" rows="5" class="form-control" placeholder="Enter receipt notes or terms of service that are visible to your customer"></textarea>
                               @error('receipt_memo') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="invoice_footer">Receipt Footer</label>
                               <textarea wire:model.debounce.300ms="receipt_footer" cols="30" rows="5" class="form-control" placeholder="Enter footer for your receipts eg (Tax Information / Thank you note)"></textarea>
                               @error('receipt_footer') <span class="error" style="color:red">{{ $message }}</span> @enderror
                            </div>
                        </div>
                    </div> 
                 
                    <div class="btn-group" role="group" style="float: right;">
                        <button type="submit" class="btn btn-success btn-wide btn-rounded" ><i class="fa fa-refresh"></i>Update</button>
                    </div>
                    <br>
                    <hr>
                    
                   
            </form>
          
        </div>
        <div role="tabpanel" class="tab-pane" id="documents">
            @livewire('documents.index', ['id' => $company->id,'category' =>'company'])
          </div>
        <div role="tabpanel" class="tab-pane" id="bank_accounts">
            @livewire('bank-accounts.index', ['id' => $company->id])
          </div>
      
       
        <div class="row">
            <div class="col-md-12">
                <div class="btn-group pull-right mt-10" >
                   <a onclick="goBack()" class="btn bg-gray btn-wide btn-rounded"><i class="fa fa-arrow-left"></i>Back</a>
                </div>
            </div>
            </div>
    </div>
</div>
