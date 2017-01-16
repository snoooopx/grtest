<!--dCreateProject -->
<div id="dCreateProject">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateProject" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Project</h4>
        </div>
        <?php echo form_open_multipart('Projects/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectName">Name</label>
              <input type="text" class="form-control input-sm" id="projectName" name="projectName" placeholder="Project Name *"required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectCode">Code</label>
              <input type="text" class="form-control input-sm" id="projectCode" name="projectCode" placeholder="Project Code *"required="required" >
            </div>
          </div><!--/.col-md-6-->
          

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectAssignment">Assignment</label></br>
              <select class="form-control input-sm" id="projectAssignment" name="projectAssignment">
                <option></option>
                <?php if ( isset($assignment_list['items']) ): ?>
                    <?php foreach ( $assignment_list['items'] as $ass ): ?>
                      <option value="<?php echo $ass['id']; ?>"><?php echo $ass['name']; ?></option>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectClient">Client</label></br>
              <select class="form-control" id="projectClient" name="projectClient">
                <option></option>
                <?php if ( isset($client_list['items']) ): ?>
                    <?php foreach ( $client_list['items'] as $clnt ): ?>
                      <option value="<?php echo $clnt['id']; ?>"><?php echo $clnt['name'] . "  |  " . $clnt['abbr'];; ?></option>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectAgrSD">Agreement Start Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectAgrSD" name="projectAgrSD" placeholder="Project Agreement Start Date" required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectAgrED">Agreement End Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectAgrED" name="projectAgrED" placeholder="Project Agreement End Date" required="required" >
            </div>
          </div><!--/.col-md-6-->

           <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectActSD">Actual Start Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectActSD" name="projectActSD" placeholder="Project Actual Start Date" required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectActED">Actual End Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectActED" name="projectActED" placeholder="Project Actual End Date" required="required" >
            </div>
          </div><!--/.col-md-6-->
          
         <div class="col-md-6">
            <div class="form-group">
              <label for="projectManager">Project Manager</label></br>
              <select class="form-control input-sm" id="projectManager" name="projectManager">
                <option></option>
                <?php if ( $user_list ): ?>
                    <?php foreach ( $user_list as $user ): ?>
                     <?php if ($user['id']!= 1): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['fullName']; ?></option>
                     <?php endif ?>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

           <div class="col-md-6">
            <div class="form-group">
              <label for="projectTeam">Project Team</label></br>
              <select class="form-control input-sm" id="projectTeam" name="projectTeam" multiple="multiple">
                <?php if ( $user_list ): ?>
                    <?php foreach ( $user_list as $user ): ?>
                      <?php if ( $user['id'] != $user['head_id'] && $user['id'] != 1 ): ?>
                        <option value="<?php echo $user['id']; ?>"><?php echo $user['fullName']; ?></option>
                      <?php endif ?>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

           <div class="col-md-6">
            <div class="form-group">
              <label for="projectStatus">Project Status</label></br>
              <select class="form-control input-sm" id="projectStatus" name="projectStatus">
                  <option value="1" selected="selected">Created</option>
                  <option value="2">Started</option>
                  <option value="3">Completed</option>
                  <option value="4">Stopped</option>
              </select>
            </div>
          </div> <!--/.col-md-6 -->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectNote">Note</label>
              <input type="text" class="form-control input-sm" id="projectNote" name="projectNote" placeholder="Project Note"required="required" >
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="projectIsVisible">Visibility (<small><i>Not Visible in TimeSheets if Unchecked</i></small>)</label>
              <input type="checkbox" value="1" class="checkbox" id="projectIsVisible" name="projectIsVisible">
            </div>
          </div><!--/.col-md-6-->

        </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtProjectCreate" type="button" id="sbmtProjectCreate" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateProject -->