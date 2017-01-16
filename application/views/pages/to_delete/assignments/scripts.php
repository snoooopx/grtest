<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ($allow['update'] OR $allow['delete'] ): ?>
    <div class="btn-group">
        <!-- <button type="button" class="btn btn-default">Action</button> -->
        <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
          <span class="caret"></span>
          <span class="sr-only">Toggle Dropdown</span>
        </button>
        <ul class="dropdown-menu" role="menu">
        <?php if ($allow['update']): ?>
           <li><a class="edit" href="#">Edit</a></li>
           <li class="divider"></li>
        <?php endif ?>

        <?php if ($allow['delete']): ?>
          <li><a class="delete" href="#">Delete</a></li>
        <?php endif ?>
        </ul>
      </div>
 <?php endif ?>
</script>

<!-- Assignment Edit Modal -->
<script type="text/template" id="tmplAssignmentEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditAssignment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Assignment: <b><%= name %></b></h4>
      </div><!--/.modal header -->
        <?php echo form_open_multipart('assignments/#'); ?>
        <!-- .modal-body -->
    <div class="modal-body">
     <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentNameEdit">Name</label>
              <input type="text" class="form-control input-sm" id="assignmentNameEdit" name="assignmentNameEdit" placeholder="Assignment Name *"required="required" value="<%= name %>" >
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentDescriptionEdit">Description</label>
              <input type="text" class="form-control input-sm" id="assignmentDescriptionEdit" name="assignmentDescriptionEdit" placeholder="Description" value="<%= description %>">
            </div>
          </div><!--/.col-md-6-->
           <div class="col-md-6">
            <div class="form-group">
              <label for="assignmentOperationsEdit">Operations</label><br/>
              <select class="form-control input-sm" id="assignmentOperationsEdit" name="operationAssignments" multiple="multiple">
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
              <label for="assignmentDepartmentsEdit">Departments</label><br/>
              <select class="form-control" id="assignmentDepartmentsEdit" name="assignmentDepartmentsEdit" multiple="multiple">
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
              <label for="assignmentIsVisibleEdit">Visibility (<small><i>Not Visible in App if Unchecked</i></small>)</label>
              <input type="checkbox" value="1" class="checkbox" id="assignmentIsVisibleEdit" name="assignmentIsVisibleEdit" <%= is_visible==1 ?"checked":"" %>>
            </div>
          </div><!--/.col-md-6-->

        </div><!--/.row-->
      
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtAssignmentEdit" id="sbmtAssignmentEdit" class="btn btn-primary">Save</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</script>

<!-- Delete Confirmation Modal -->
<script type="text/template" id="tmplDeleteNote">
	<div id="mdlDeleteConfirm" class="modal fade" tabindex="-1" role="dialog">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	        <h4 class="modal-title">Realy Delete:</h4>
	      </div>
	      <?php echo form_open_multipart('#'); ?>
	      <div class="modal-body">
	        <p><b><%=name%></b></p>
	      </div>
	      <div class="modal-footer">
	       <button id="confirmDelete" name="confirmDelete" type="button" class="btn btn-primary">Confirm</button>
	       <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
	      </div>
	      <?php echo form_close(); ?>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</script>