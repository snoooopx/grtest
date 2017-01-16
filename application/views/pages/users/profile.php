  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">

    <div class="loading">Loading&#8230;</div>
    <!-- Content Header (Page header) -->
    <section class="content-header">
    <?php if ($self_edit OR $userinfo['id']==1 OR $allow['read']): ?>
     <div>
      <ol class="breadcrumb">
          <li><a href="#"><i class="fa fa-home"></i> / <?php echo ucfirst($active_section); ?></a></li>
          <li><a href="<?php echo site_url('backend/'.$active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
          <li class="active">Profile</li>
      </ol>
    </div>
    </section>
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-md-3">
          <!-- Profile Image -->
          <div class="box box-primary">
            <div class="box-body box-profile">
              <h3 class="profile-username text-center"><?php echo $user_profile['name'] . " " . $user_profile['middle'] . " " . $user_profile['sname']; ?></h3>
              
              <ul class="list-group list-group-unbordered">
              
              </ul>
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

          <div id="ursProfileBlock">
            <div class="col-md-9">

              <div class="nav-tabs-custom">
                <!-- profile nav tabs -->
                <ul class="nav nav-tabs">
                 
                  <!-- About Tab -->
                  <li class="active"><a href="#about" id="aboutTab" data-toggle="tab" aria-expanded="true">About</a></li>
                  <?php if ( $self_edit ): ?>
                    <!-- Security Tab -->
                    <li ><a href="#security" id="securityTab" data-toggle="tab">Security</a></li>
                  <?php endif ?>
                  
                  <!-- Permissions Tab -->
                  <?php if ( $userinfo['id']==1 ): ?>
                    <li ><a href="#permissions" id="permissionsTab" data-toggle="tab">Permisions</a></li>

                    <!-- Settings Tab -->
                    <li><a href="#settings" id="settingsTab" data-toggle="tab">Settings</a></li>
                  <?php endif ?>
                </ul>
                <!-- ./ profile nav tabs ul -->

                <!-- tab contents -->
                <div class="tab-content">
                  <!-- About tab Pane -->
                   <?php if ( $self_edit ): ?>
                  <div class="tab-pane" id="security">
                    <!-- .modal-body -->
                    <div class="modal-body">
                    <form method="post" name="frmPassChange" id="frmPassChange">
                     <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="passEdit">Password</label>
                          <input type="password" class="form-control input-sm" id="passEdit" name="passEdit" placeholder="Password" required="required" value="" >
                        </div>
                      </div><!--/.col-md-4-->
                     </div><!--/.row-->

                     <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="confPassEdit">Confirm Password</label>
                          <input type="password" class="form-control input-sm" id="confPassEdit" name="confPassEdit" placeholder="Confirm" required="required" value="">
                        </div>
                      </div><!--/.col-md-4-->
                     </div><!--/.row-->
                     <div class="row">
                      <div class="col-md-6">
                        <div class="form-group">
                          <input type="submit" class="form-control input-sm" id="sbmtPassEdit" name="sbmtPassEdit" value="Submit">
                        </div>
                      </div><!--/.col-md-4-->
                     </div><!--/.row-->

                    </form>
                    </div><!-- /.modal-body -->
                  </div>
                  <!-- /.tab-pane About-->
                  <?php endif ?>
                <?php if ($self_edit OR $userinfo['id']==1 OR $allow['read']): ?>
      
                  <!-- About tab Pane -->
                  <div class="tab-pane active" id="about">
                    <!-- .modal-body -->
                    <div class="modal-body">
                     <div class="row">

                      <div class="col-md-4">
                        <div class="form-group">
                          <label for="userNameEdit">Name</label>
                          <input readonly="" type="text" class="form-control input-sm" id="userNameEdit" name="userNameEdit" placeholder="User Name *"required="required" value="<?php echo $user_profile['name']; ?>" >
                        </div>
                      </div><!--/.col-md-4-->
                      
                      <div class="col-md-3 col-xs-3">
                        <div class="form-group">
                          <label for="userMiddleEdit">Initials</label>
                          <input readonly="" type="text" class="form-control input-sm" id="userMiddleEdit" name="userMiddleEdit" placeholder="User Initials" value="<?php echo $user_profile['middle']; ?>">
                        </div>
                      </div><!--/.col-md-4-->

                      <div class="col-md-4 ">
                        <div class="form-group">
                          <label for="userSnameEdit">Surname</label>
                          <input readonly="" type="text" class="form-control input-sm" id="userSnameEdit" name="userSnameEdit" placeholder="User Surname" value="<?php echo $user_profile['sname'];?>" >
                        </div>
                      </div><!--/.col-md-4-->
                      
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userLoginEdit">Login</label>
                          <input readonly="" type="text" class="form-control input-sm" id="userLoginEdit" name="userLoginEdit" placeholder="Login *" required="required" value="<?php echo $user_profile['login']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userEmailEdit">Email</label>
                          <input readonly="" type="text" class="form-control input-sm" id="userEmailEdit" name="userEmailEdit" placeholder="Email" required="required" value="<?php echo $user_profile['email']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userPhoneEdit">Phone</label>
                          <input readonly=""  type="text" class="form-control input-sm" id="userPhoneEdit" name="userPhoneEdit" placeholder="Phone" value="<?php echo $user_profile['phone']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userAddressEdit">Address</label>
                          <input readonly=""  type="text" class="form-control input-sm" id="userAddressEdit" name="userAddressEdit" placeholder="Address" value="<?php echo $user_profile['address']; ?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userSexEdit">Sex</label>
                          <input readonly=""  type="text" class="form-control input-sm" 
                              value="<?php echo strtoupper($user_profile['sex']);?>">
                        </div>
                      </div><!--/.col-md-6-->

                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="userIsActiveEdit">Login Allowed
                          <input readonly="" disabled="" type="checkbox" value="1" class="checkbox" id="userIsActiveEdit" name="userIsActiveEdit"  <?php echo ($user_profile['isActive']==1 ? "checked":""); ?>>
                        </div>
                      </div><!--/.col-md-6-->

                     </div><!--/.row-->
                    </div><!-- /.modal-body -->
                  </div><!-- /.tab-pane About-->
                  <?php endif ?>

               <!--  <div class="box"> -->
                  <?php if ( $userinfo['id']==1 ): ?>
                  
                  <!-- Settings tab Pane -->
                   <div class="tab-pane" id="settings">
                    <div class="form-group">
                      <?php 
                          if ($user_configs['combinedSidebar'] == '1') {
                             $is_checked = 'checked';
                          } else {
                             $is_checked = '';
                          }
                       ?>
                      
                      <label for="usrCombinedSidebar">Always Combined Sidebar</label>
                      <input type="checkbox" <?php echo $is_checked; ?> class="checkbox" id="usrCombinedSidebar" data-user-id="<?php echo $user_profile['id']; ?>">
                      
                      <?php 
                          if ($user_configs['viewAllReports'] == '1') {
                             $is_checked = 'checked';
                          } else {
                             $is_checked = ''; 
                          }
                       ?>       
                      <label for="usrViewAllReports">View All Reports</label>
                      <input type="checkbox" <?php echo $is_checked; ?> class="checkbox" id="usrViewAllReports" data-user-id="<?php echo $user_profile['id']; ?>">
                    </div>
                   </div>
                   <!-- /.tab-pane Settings-->
                   
                  <!-- Permissions tab Pane -->
                  <div class="tab-pane" id="permissions">
                        <?php echo form_open_multipart('users/#'); ?>
                        <div class="table-responsive">
                        <table id="userPermsTable" class="table table-hover table-condensed" cellspacing="0" width="100%">
                          <thead>
                            <th>Section</th>
                            <th># ID</th>
                            <th>Sub Section</th>
                            <th># ID</th>
                            <th>Read</th>
                            <th>Create</th>
                            <th>Update</th>
                            <th>Delete</th>
                            <th>Select All</th>
                          </thead>
                           <tbody>
                          <?php if ($user_sections): ?>
                            <?php foreach ($user_sections as $section): ?>
                              <tr id="<?php echo $section['id']; ?>" data-user-id="<?php echo $user_profile['id']; ?>" <?php echo "bgcolor=#". $section['color']; ?>>
                                <td> <span id="section_name" data-section-id="<?php echo $section['section_id']; ?>"> <?php echo $section['section_name']; ?> </span> </td>
                                <!-- <td> <input size="2" type="text" id="section_seq" name="section_seq" value="<?php echo $section["section_seq"]; ?>"> </td> -->
                                <td> <span size="2" type="text" id="section_seq" name="section_seq" value=""></span><?php echo $section["section_seq"]; ?> </td>
                                <td> <span id="subsection_name" data-subsection-id="<?php echo $section['subsection_id']; ?>"> <?php echo $section['subsection_name']; ?> </span> </td>
                                <!-- <td> <input size="2" type="text" id="subsection_seq" name="subsection_seq" value="<?php echo $section["subsection_seq"]; ?>"> </td> -->
                                <td> <span size="2" type="text" id="subsection_seq" name="subsection_seq" ><?php echo $section["subsection_seq"]; ?></span> </td>
                                <td> <input type="checkbox" id="r" name="r" value="<?php echo $section['r']; ?>" <?php echo ($section['r'] == 1 ? "checked": "") ?>></td>
                                <td> <input type="checkbox" id="c" name="c" value="<?php echo $section['c']; ?>" <?php echo ($section['c'] == 1 ? "checked": "") ?>></td>
                                <td> <input type="checkbox" id="u" name="u" value="<?php echo $section['u']; ?>" <?php echo ($section['u'] == 1 ? "checked": "") ?>></td>
                                <td> <input type="checkbox" id="d" name="d" value="<?php echo $section['d']; ?>" <?php echo ($section['u'] == 1 ? "checked": "") ?>></td>
                                <td> <input type="checkbox" id="check_all" name="check_all" <?php if (($section['r'] +$section['c'] +$section['u'] +$section['u'])==4){echo "checked";} ?>></td>
                              </tr>
                            <?php endforeach ?>
                          <?php endif ?>
                          </tbody>
                        </table>
                        </div><!--  Responsive Div -->
                        <!-- .modal-footer -->
                        <div class="box-footer">
                          <button type="button" name="sbmtUserPermsEdit" id="sbmtUserPermsEdit" class="btn btn-primary">Save Changes</button>
                        </div><!-- /.modal-footer -->
                  <?php echo form_close(); ?>
                  </div><!-- /.tab-pane Permissions-->
                  <?php endif ?>

                 <!--  </div> -->
                  <!-- /.tab-pane -->
                </div>
                <!-- /.tab-content -->
              </div>
              <!-- /.nav-tabs-custom -->
            </div>
            <!-- /.col -->
          </div>
          <!-- / #ursProfileBlock -->
        </div>
      <!-- /.row -->

    <?php else: ?>
    <h1>You Don`t Have permission To View This page...</h1>
    <?php endif ?>
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->