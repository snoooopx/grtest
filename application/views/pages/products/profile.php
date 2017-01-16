<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">

<div class="loading">Loading&#8230;</div>
<!-- Content Header (Page header) -->
<section class="content-header">
<?php if ($allow['read']): ?>
 <div>
  <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-home"></i><?php echo ucfirst($active_section); ?></a></li>
      <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
      <li class="active">Details - <?php echo $project_details['name']; ?></li>
  </ol>
</div>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    
    <div class="col-md-9">

      <div class="nav-tabs-custom">
        <!-- profile nav tabs -->
        <ul class="nav nav-tabs">
          <!-- Info Tab -->
          <li class="active"><a href="#info" id="infoTab" data-toggle="tab">Info</a></li>
          <!-- Info Tab -->
          <li class=""><a href="#planning" id="planningTab" data-toggle="tab">Planning</a></li>
        </ul>

        <!-- tab contents -->
        <div class="tab-content">
          <!-- Info tab Pane -->
          <div class="tab-pane active" id="info">
          <?php echo form_open_multipart('users/#'); ?>
            <!-- .modal-body -->
            <div class="modal-body">
             <div class="row">

                  <div class="col-md-9">
                    <div class="form-group">
                      <label for="projectNameEdit">Creation Date</label>
                      <p><?php echo $project_details['creation_date']; ?> </p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectNameEdit">Name</label>
                      <p><?php echo $project_details['name']; ?> </p>
                    </div>
                  </div><!--/.col-md-6-->
                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectCodeEdit">Code</label>
                      <p><?php echo $project_details['code']; ?></p>
                    </div>
                  </div><!--/.col-md-6-->

                   <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectAssignmentEdit1">Assignment</label>
                      <p>
                      <?php echo $project_details['assignment']; ?>
                      </p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectClientEdit1">Client</label>
                      <p>
                      <?php echo $project_details['client']; ?>
                      </p>
                    </div>
                  </div><!--/.col-md-6-->
                  
                  <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="projectAgrSD">Agreement Start Date</label>
                      <p>
                      <?php echo $project_details['agrSD']; ?>  
                      </p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="projectAgrED">Agreement End Date</label>
                      <p>
                      <?php echo $project_details['agrED']; ?>  
                      </p>
                    </div>
                  </div><!--/.col-md-6-->

                    <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="projectActSD">Actual Start Date</label>
                      <p>
                        <?php
                          if ($project_details['actSD'] !== '1901-01-01') 
                          {
                              echo $project_details['actSD']; 
                          } 
                          else
                          {
                              echo "0000-00-00";
                          }
                        ?>  
                      </p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6 ">
                    <div class="form-group">
                      <label for="projectActED">Actual End Date</label>
                      <p>
                      <?php
                          if ($project_details['actED'] !== '1901-01-01') 
                          {
                              echo $project_details['actED']; 
                          } 
                          else
                          {
                              echo "0000-00-00";
                          }
                        ?>  
                      </p>
                    </div>
                  </div><!--/.col-md-6-->

                  
                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectManagerEdit">Project Manager</label>
                      <p><?php echo $project_details['manager']; ?></p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectTeamEdit">Team</label>
                      <p>
                        <?php 
                          if (empty($project_details['team_names'])) 
                          {
                            echo "No Team members yet.";
                          }
                          else
                          {
                            echo $project_details['team_names'];
                          }
                         ?>
                      </p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectStatusEdit">Status</label>
                      <p><?php echo $project_details['project_status']; ?></p>
                    </div>
                  </div><!--/.col-md-6-->

                   <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectAptStatusEdit">APT Status</label>
                      <p><?php echo $project_details['project_status']; ?></p>
                    </div>
                  </div><!--/.col-md-6-->

                  <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectNoteEdit">Note</label>
                      <p><?php echo $project_details['note']; ?></p>
                    </div>
                  </div><!--/.col-md-6-->

                   <div class="col-md-6">
                    <div class="form-group">
                      <label for="projectIsVisibleEdit">Visibility (<small><i>Not Visible in Timesheets if Unchecked</i></small>)</label>
                      <p><?php echo $project_details['is_visible']==1 ? "Visible": "Not Visible in Timesheets" ;?></p>
                    </div>
                  </div><!--/.col-md-6-->
             </div><!--/.row-->
              
            </div><!-- /.modal-body -->
                
             <?php echo form_close(); ?>
       
          </div>
          <!-- /.tab-pane info -->
          <div class="tab-pane" id="planning">
            <?php 
            $project_operations = array(array('project' => 'audit','operation'=>'lololo'),
                                        array('project' => 'audit','operation'=>'trolor'));
                                        
            $project_user_list = array( array('id' => 1,'name'=>'gazan'),
                                        array('id' => 2,'name'=>'vayrag'),
                                        array('id' => 3,'name'=>'gazatapor')
                                        
                                      );
             ?>
            <!-- form start -->
            <form role="form">
              <div class="box-body">
                <table id="prjPlanning" class="table table-bordered">
                  <thead>
                    <th>Project</th>
                    <th>Operation</th>
                    <th>Hours</th>
                    <th>User</th>
                  </thead>
                  <tbody>

                    
                  </tbody>
                  <tfoot>
                    <tr>
                      <td><button type="button" class="btn btn-info btn-sm"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                      <td colspan="3"></td>
                    </tr>
                  </tfoot>
                </table>
              </div>
              <!-- /.box-body -->

              <div class="box-footer">
              <?php if (true): ?>
                <button type="button" class="btn btn-primary">Submit</button>
              <?php endif ?>

              </div>
            </form>
          
          </div>
          <!-- /.tab-pane Planning -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
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