<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ($allow['update'] OR $allow['delete'] ): ?>
	    <div style="width:90px;" >
	    <?php if ( $allow['read'] ): ?>
	           <a class="btn btn-default btn-xs details" data-toggle="tooltip" data-placement="top" title="View Details" href=""><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>
	        <?php endif ?>
	        <?php if ( $allow['update'] ): ?>
	           <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Edit" href=""><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
	        <?php endif ?>
	        <?php if ( $allow['delete'] ): ?>
	     	 <a class="btn btn-danger btn-xs delete" href="#"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>
	        <?php endif ?>
      </div>
 <?php endif ?>
</script>

<!-- Department Edit Modal -->
<script type="text/template" id="tmplDepEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditDepartment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
	    <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Department: <b><%= depName %></b></h4>
	    </div>
	    <!--/.modal header -->
        <?php echo form_open_multipart('job_titles/#'); ?>
        <!-- .modal-body -->
		<div class="modal-body" id="mdl_edit_modal_body_class">
			<!-- Department Name  -->
			<div class="form-group">
				<label for="depName">Name</label>
				<input type="text" class="form-control" id="depName" name="depName" placeholder="Department Name" required="" autofocus="autofocus" value="<%= depName %>">
			</div>
			<!-- Department Head  -->
			<div class="form-group">
	            <label for="depHeadId">Head</label>
	            <select class="form-control" id="depHeadId" name="depHeadId" required="">
					<?php if (isset($user_list)): ?>
		              <?php foreach ($user_list as $user): ?>
		                <option value="<?php echo $user["id"]; ?>"><?php echo $user['name'] . " " . $user['middle'] . " " . $user['sname']; ?></option>
		              <?php endforeach ?>
					<?php endif ?>
	            </select>
	        </div>
	        <!-- Company  -->
          	<div class="form-group">
	          	<label for="depCompanyId">Company</label>
	            <select class="form-control" id="depCompanyId" name="depCompanyId" required="">
	                <option value="<?php echo $company_list['id']; ?>"><?php echo $company_list['name']; ?></option>
	            </select>
	        </div>
        </div>
        <!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtDepEdit" id="sbmtDepEdit" class="btn btn-primary">Save</button>
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
	        <p><b><%=depName%></b></p>
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