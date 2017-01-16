  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientNameEdit">Name</label>
                      <input type="text" disabled="" class="form-control " id="clientNameEdit" name="clientNameEdit" placeholder="Client Name *"required="required" value="<?php echo $client_profile['name']; ?>" >
                    </div>
                  </div><!--/.col-md-6-->
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientAbbrEdit">Abbreviation</label>
                      <input type="text" disabled="" class="form-control" id="clientAbbrEdit" name="clientAbbrEdit" placeholder="Client Abbr." value="<?php echo $client_profile['abbr']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                   <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientDepartmentsEdit1">Departments</label>
                      <textarea disabled="" class="form-control ">
                      <?php echo str_replace(',', ', ', $client_profile['dep_names']); ?>
                      </textarea>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientSectorsEdit1">Sectors</label>
                      <textarea disabled="" class="form-control ">
                      <?php echo str_replace(',', ', ', $client_profile['sec_names']); ?>
                      </textarea>
                    </div>
                  </div><!--/.col-md-6-->
                  
                  <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="clientContactEdit">Contact Person</label>
                      <input disabled="" type="text" class="form-control " id="clientContactEdit" name="clientContactEdit" placeholder="Client Contact" value="<?php echo $client_profile['contact_person']; ?>">
                    </div>
                  </div><!--/.col-md-6-->
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientEmailEdit">Email</label>
                      <input disabled="" type="text" class="form-control " id="clientEmailEdit" name="clientEmailEdit" placeholder="Email" required="required" value="<?php echo $client_profile['email']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientPhoneEdit">Phone</label>
                      <input disabled="" type="text" class="form-control " id="clientPhoneEdit" name="clientPhoneEdit" placeholder="Phone" value="<?php echo $client_profile['phone']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientAddressEdit">Address</label>
                      <input disabled="" type="text" class="form-control " id="clientAddressEdit" name="clientAddressEdit" placeholder="Address" value="<?php echo $client_profile['address']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientAccEdit">Bank Account</label>
                      <input disabled="" type="text" class="form-control " id="clientAccEdit" name="clientAccEdit" placeholder="Acc" value="<?php echo $client_profile['bank_acc']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientRegNumEdit">Reg Num</label>
                      <input disabled="" type="text" class="form-control " id="clientRegNumEdit" name="clientRegNumEdit" placeholder="RegNum" value="<?php echo $client_profile['reg_num']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientTinEdit">Tax Code</label>
                      <input disabled="" type="text" class="form-control " id="clientTinEdit" name="clientTinEdit" placeholder="Tin" value="<?php echo $client_profile['tin']; ?>">
                    </div>
                  </div><!--/.col-md-6-->

                   <div class="col-md-6">
                    <div class="form-group">
                      <label for="clientIsVisibleEdit">Visibility (<small><i>Not Visible in App if Unchecked</i></small>)</label>
                      <input disabled="" type="checkbox" disabled value="1" class="checkbox" id="clientIsVisibleEdit" name="clientIsVisibleEdit" <?php echo $client_profile['is_Visible']=1 ? "checked": "" ;?>>
                    </div>
                  </div><!--/.col-md-6-->