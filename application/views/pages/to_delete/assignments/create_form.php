<!--/# dCreateAssignment -->
<div id="dCreateAssignment">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateAssignment" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Assignment</h4>
        </div>
        <?php echo form_open_multipart('assignments/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentName">Name</label>
              <input type="text" class="form-control input-sm" id="assignmentName" name="assignmentName" placeholder="Assignment Name *"required="required" >
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentDescription">Description</label>
              <input type="text" class="form-control input-sm" id="assignmentDescription" name="assignmentDescription" placeholder="Assignment Description" required="required">
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentOperations">Operations</label><br/>
              <select class="form-control input-sm" id="assignmentOperations" name="operationAssignments" multiple="multiple">
              <?php if (isset($operation_list)): ?>
                <?php if ($operation_list): ?>
                    <?php foreach ($operation_list as $oper): ?>
                      <option value="<?php echo $oper['id']; ?>"><?php echo $oper['name']; ?></option>
                    <?php endforeach ?>
                <?php endif ?>
              <?php endif ?>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentDepartments">Departments</label><br/>
              <select class="form-control input-sm" id="assignmentDepartments" name="assignmentDepartments" multiple="multiple">
              <?php if (isset($department_list) && $department_list !== false): ?>
                  <?php foreach ($department_list as $dep): ?>
                    <option value="<?php echo $dep['id']; ?>"><?php echo $dep['depName']; ?></option>
                  <?php endforeach ?>
              <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentIsVisible">Visibility (<small><i>Not Visible in App if Unchecked</i></small>)</label>
              <input type="checkbox" value="1" class="checkbox" id="assignmentIsVisible" name="assignmentIsVisible">
            </div>
          </div><!--/.col-md-6-->

        </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtAssignmentCreate" type="button" id="sbmtAssignmentCreate" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateAssignment -->