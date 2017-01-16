<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ($allow['read'] OR $allow['update'] OR $allow['delete'] ): ?>
      <div style="width:90px;" >
        <?php if ( $allow['read'] ): ?>
           <a class="btn btn-default btn-xs details" data-toggle="tooltip" data-placement="top" title="View Details" href=""><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>

        <?php if ( $allow['update'] ): ?>
           <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Edit" href="#"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>

        <?php if ( $allow['delete'] ): ?>
            <a class="btn btn-danger btn-xs delete" data-toggle="tooltip" data-placement="top" title="Remove" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
        <?php endif ?>
      </div>
 <?php endif ?>
</script>

<!-- Project Edit Modal -->
<script type="text/template" id="tmplProjectEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditProject" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Project: <b><%= name %></b></h4>
      </div><!--/.modal header -->
        <?php echo form_open_multipart('clients/#'); ?>
        <!-- .modal-body -->
    <div class="modal-body">
     <div class="row">

         <div class="col-md-6">
            <div class="form-group">
              <label for="projectNameEdit">Name</label>
              <input type="text" class="form-control input-sm" id="projectNameEdit" name="projectNameEdit" placeholder="Project Name *" required="required" value="<%= name %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectCodeEdit">Code</label>
              <input type="text" class="form-control input-sm" id="projectCodeEdit" name="projectCodeEdit" placeholder="Project Code *" required="required" value="<%= code %>">
            </div>
          </div><!--/.col-md-6-->
          

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectAssignmentEdit">Assignment</label></br>
              <select class="mySumoSelectEdit form-control input-sm" id="projectAssignmentEdit" name="projectAssignmentEdit">
                <option></option>
                <?php if ( $assignment_list['items'] ): ?>
                    <?php foreach ( $assignment_list['items'] as $ass ): ?>
                      <option value="<?php echo $ass['id']; ?>"><?php echo $ass['name']; ?></option>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectClientEdit">Client</label></br>
              <select class="mySumoSelectEdit form-control" id="projectClientEdit" name="projectClientEdit">
                <option></option>
                <?php if ( $client_list['items'] ): ?>
                    <?php foreach ( $client_list['items'] as $clnt ): ?>
                      <option value="<?php echo $clnt['id']; ?>"><?php echo $clnt['name'] . "  |  " . $clnt['abbr']; ?></option>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectAgrSDEdit">Agreement Start Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectAgrSDEdit" name="projectAgrSDEdit" placeholder="Project Agreement Start Date" required="required" value="<%= agrSD %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectAgrEDEdit">Agreement End Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectAgrEDEdit" name="projectAgrEDEdit" placeholder="Project Agreement End Date" required="required" value="<%= agrED %>">
            </div>
          </div><!--/.col-md-6-->

           <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectActSDEdit">Actual Start Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectActSDEdit" name="projectActSDEdit" placeholder="Project Actual Start Date" required="required" value="<%= actSD=='1901-01-01'? '':actSD %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="projectActEDEdit">Actual End Date</label>
              <input type="text" class="myDatePicker form-control input-sm" id="projectActEDEdit" name="projectActEDEdit" placeholder="Project Actual End Date" required="required" value="<%= actED=='1901-01-01'? '':actED %>">
            </div>
          </div><!--/.col-md-6-->
          
         <div class="col-md-6">
            <div class="form-group">
              <label for="projectManagerEdit">Project Manager</label></br>
              <select class="mySumoSelectEdit form-control input-sm" id="projectManagerEdit" name="projectManagerEdit">
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
              <label for="projectTeamEdit">Project Team</label></br>
              <select class="form-control input-sm" id="projectTeamEdit" name="projectTeamEdit" multiple="multiple">
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
              <label for="projectStatusEdit">Project Status</label></br>
              <select class="mySumoSelectEdit form-control input-sm" id="projectStatusEdit" name="projectStatusEdit">
                  <option value="1">Created</option>
                  <option value="2">Started</option>
                  <option value="3">Completed</option>
                  <option value="4">Stopped</option>
              </select>
            </div>
          </div> <!--/.col-md-6 -->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectAptStatusEdit">APT Status(optional)</label></br>
              <select class="mySumoSelectEdit form-control input-sm" id="projectAptStatusEdit" name="projectAptStatusEdit">
                  <option value="1">Not Started</option>
                  <option value="2">Started</option>
                  <option value="3">Finished</option>
              </select>
            </div>
          </div> <!--/.col-md-6 -->

          <div class="col-md-6">
            <div class="form-group">
              <label for="projectNoteEdit">Note</label>
              <input type="text" class="form-control input-sm" id="projectNoteEdit" name="projectNoteEdit" placeholder="Project Note" required="required" value="<%= note %>">
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="projectIsVisibleEdit">Visibility (<small><i>Not Visible in TimeSheets if Unchecked</i></small>)</label>
              <input type="checkbox" value="1" class="checkbox" id="projectIsVisibleEdit" name="projectIsVisibleEdit" <%= is_visible==1 ?"checked":"" %>>
            </div>
          </div><!--/.col-md-6-->
        </div><!--/.row-->
      
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtProjectEdit" id="sbmtProjectEdit" class="btn btn-primary">Save</button>
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


<script type="text/template" id="tmplProjectPlanningRow">
<tr>
  <?php if (isset($project_details)): ?>
    <td><?php echo $project_details['code'] ?></td>
    <td>
      <select id="prjOpeartions">
        <?php if (isset($project_operations)): ?>
          <?php foreach ($project_operations as $oper): ?>
            <option value="<?php echo $oper['id'] ?>"> <?php echo $oper['text'] ?></option>
          <?php endforeach ?>
        <?php endif ?>
      </select>
    </td>
    <td>
      <input type="text" name="prjPlannedTime">
    </td>
    <td>
      <select>
        <option value="<?php echo $project_details['manager_id']; ?>"><?php echo $project_details['manager'] ?> </option>
        <?php if (isset($project_team)): ?>
          <?php foreach ($project_team as $user): ?>
            <option value="<?php echo $user['id']; ?>"><?php echo $user['name'] ?></option>
          <?php endforeach ?>
        <?php endif ?>
      </select>
    </td>
  <?php endif ?>
</tr>
</script>