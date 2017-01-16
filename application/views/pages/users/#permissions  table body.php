 <tbody>
                      <?php if ($user_sections): ?>
                        
                        <?php foreach ($user_sections as $section): ?>
                          <tr <?php echo "bgcolor=#". $section['color']; ?>>
                            <td> <span> <?php echo $section['section_name']; ?> </span> </td>
                            <td> <input size="2" type="text" id="section_seq" name="section_seq" value="<?php echo $section["section_seq"]; ?>"> </td>
                            <td> <span> <?php echo $section['subsection_name']; ?> </span> </td>
                            <td> <input size="2" type="text" id="subsection_seq" name="subsection_seq" value="<?php echo $section["subsection_seq"]; ?>"> </td>
                            <td> <input type="checkbox" id="r" name="r" value="<?php echo $section['r']; ?>" 
                              <?php if ($section['r'] == 1): ?>
                                echo "checked";
                              <?php endif ?>
                            ></td>
                            <td> <input type="checkbox" id="c" name="c" value="<?php echo $section['c']; ?>" 
                              <?php if ($section['c'] == 1): ?>
                                echo "checked";
                              <?php endif ?>
                            ></td>
                            <td> <input type="checkbox" id="u" name="u" value="<?php echo $section['u']; ?>" 
                              <?php if ($section['u'] == 1): ?>
                                echo "checked";
                              <?php endif ?>
                            ></td>
                            <td> <input type="checkbox" id="d" name="d" value="<?php echo $section['d']; ?>" 
                              <?php if ($section['d'] == 1): ?>
                                echo "checked";
                              <?php endif ?>
                            ></td>
                            <td> <input type="checkbox" id="check_all" name="check_all"></td>

                          </tr>
                        <?php endforeach ?>
                      <?php endif ?>
                      </tbody>




<?php if ($self_edit OR $userinfo['id']==1): ?>
  
                       <!-- About tab Pane -->
              <div class="tab-pane active" id="about">
              <?php echo form_open_multipart('users/#'); ?>
                <!-- .modal-body -->
                <div class="modal-body">
                 <div class="row">

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="userNameEdit">Name</label>
                          <input type="text" class="form-control input-sm" id="userNameEdit" name="userNameEdit" placeholder="User Name *"required="required" value="<?php echo $user_profile['name']; ?>" >
                        </div>
                      </div><!--/.col-md-4-->
                      
                      <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                          <label for="userMiddleEdit">Initials</label>
                          <input type="text" class="form-control input-sm" id="userMiddleEdit" name="userMiddleEdit" placeholder="User Initials" value="<?php echo $user_profile['middle']; ?>">
                        </div>
                      </div><!--/.col-md-4-->

                      <div class="col-md-4 ">
                        <div class="form-group">
                          <label for="userSnameEdit">Surname</label>
                          <input type="text" class="form-control input-sm" id="userSnameEdit" name="userSnameEdit" placeholder="User Surname" value="<?php echo $user_profile['sname']; ?>">
                        </div>
                      </div><!--/.col-md-4-->
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userLoginEdit">Login</label>
                          <input type="text" class="form-control input-sm" id="userLoginEdit" name="userLoginEdit" placeholder="Login *" required="required" value="<?php echo $user_profile['login']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userEmailEdit">Email</label>
                          <input type="text" class="form-control input-sm" id="userEmailEdit" name="userEmailEdit" placeholder="Email" required="required" value="<?php echo $user_profile['email']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userPhoneEdit">Phone</label>
                          <input type="text" class="form-control input-sm" id="userPhoneEdit" name="userPhoneEdit" placeholder="Phone" value="<?php echo $user_profile['phone']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userAddressEdit">Address</label>
                          <input type="text" class="form-control input-sm" id="userAddressEdit" name="userAddressEdit" placeholder="Address" value="<?php echo $user_profile['address']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userSexEdit">Sex</label>
                          <select class="form-control input-sm" id="userSexEdit" name="userSexEdit" required="" >
                              <option value="" disabled="" selected=""><i>Select</i></option>
                              <option <?php echo ($user_profile['sex']=="m" ? "selected":""); ?> value="m">M</option>
                              <option <?php echo ($user_profile['sex']=="f" ? "selected":""); ?> value="f">F</option>
                          </select>
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userPositionIdEdit">Job Title</label>
                          <select class="form-control input-sm" id="userPositionIdEdit" name="userPositionIdEdit">
                              <option value="" selected="" disabled="">Select Job title</option>
                              <?php foreach ($job_title_list as $job): ?>
                                <option <?php echo ($user_profile['positionId']==$job['id'] ? "selected":""); ?> value="<?php echo $job['id']; ?>"><?php echo $job['name']; ?></option>
                              <?php endforeach ?>
                          </select>
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userPasswordEdit">Password</label>
                          <input type="password" class="form-control input-sm" id="userPasswordEdit" name="userPasswordEdit">
                        </div>
                      </div><!--/.col-md-6-->          

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userPasswordConfirmEdit">Confirm Password</label>
                          <input type="password" class="form-control input-sm" id="userPasswordConfirmEdit" name="userPasswordConfirm">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userStatusEdit">Status (<small><i>Not Visible in App if Unchecked</i></small>)</label>
                          <input type="checkbox" value="1" class="checkbox" id="userStatusEdit" name="userStatusEdit"  <?php echo ($user_profile['inAppStatus']==1 ? "checked":""); ?>>
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userIsActiveEdit">Login Allowed
                          <input type="checkbox" value="1" class="checkbox" id="userIsActiveEdit" name="userIsActiveEdit"  <?php echo ($user_profile['isActive']==1 ? "checked":""); ?>>
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userAvatarEdit">Change Avatar</label>
                          <input type="hidden" value="" id="userAvatarNameHdnEdit" name="userAvatarNameHdnEdit">
                          <div id="userAvatarEdit" class="dropzone" ></div>
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userAvatarExistingEdit">Avatar</label>
                          <img id="userAvatarExistingEdit" name"userAvatarExistingEdit" class="form-group" src="<?php echo base_url("application/assets/img/avatars/".$user_profile['avatar']); ?>">
                        </div>
                      </div><!--/.col-md-6-->
                    </div><!--/.row-->
                  
                    </div><!-- /.modal-body -->
                    
                    <!-- .modal-footer -->
                    <div class="modal-footer">
                      <button type="submit" name="sbmtUserEdit" id="sbmtUserEdit" class="btn btn-primary">Edit</button>
                    </div><!-- /.modal-footer -->
                    <?php echo form_close(); ?>
                </div><!-- /.tab-pane About-->
              <?php endif ?>