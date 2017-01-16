<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ($allow['update'] OR $allow['delete'] ): ?>
    <!-- <div class="btn-group"> -->
	      <!-- <button type="button" class="btn btn-default">Action</button> -->
	      <!-- <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" aria-expanded="false">Action
	        <span class="caret"></span>
	        <span class="sr-only">Toggle Dropdown</span>
	      </button>
	      <ul class="dropdown-menu" role="menu"> -->
        <?php if ($allow['update']): ?>
        	<a class="btn btn-default btn-xs edit" href="#"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
	         <!-- <li></li>
	         <li class="divider"></li> -->
        <?php endif ?>

        <?php if ($allow['delete']): ?>
        	<a class="btn btn-danger btn-xs delete" href="#"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>
	        <!-- <li></li> -->
        <?php endif ?>
	      <!-- </ul> -->
	    <!-- </div> -->
 <?php endif ?>
</script>


<!-- Position Edit Modal -->
<script type="text/template" id="tmplPosEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditPosition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
	    <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Job Title: <b><%= name %></b></h4>
	    </div>
	    <!--/.modal header -->
        <?php echo form_open_multipart('job_titles/#'); ?>
        <!-- .modal-body -->
		<div class="modal-body" id="mdl_edit_modal_body_class">
			<div class="form-group">
				<label for="posName">Name</label>
				<input type="text" class="form-control" id="name" name="posName" placeholder="Job Title Name" required="" autofocus="autofocus" value="<%= name %>">
			</div>

			<div class="form-group">
              <label for="posDepEdit">Department</label>
              <select class="form-control input-sm" id="posDepEdit" name="posDepEdit">
                  <option value="" selected="" disabled="">Select Department</option>
                  <?php if (isset($department_list)): ?>
	                  <?php foreach ($department_list as $dep): ?>
	                    <option value="<?php echo $dep['id']; ?>"><?php echo $dep['depName']; ?></option>
	                  <?php endforeach ?>
                  <?php endif ?>
              </select>
            </div>
            
			<div class="form-group">
				<label for="posNote">Note</label>
				<input type="text" class="form-control" id="note" name="posNote" placeholder="Note" value="<%= note %>">
			</div>
        </div>
        <!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmt_pos_edit" id="sbmt_pos_edit" class="btn btn-primary">Save</button>
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