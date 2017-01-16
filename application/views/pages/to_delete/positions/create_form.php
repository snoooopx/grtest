<!--/# dCreatePosition -->
<div id="dCreatePosition">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreatePosition" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Job Title</h4>
        </div>
        <?php echo form_open_multipart('job_titles/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">
          <div class="form-group">
            <label for="posName">Name</label>
            <input type="text" class="form-control" id="name" name="posName" placeholder="Job Title Name"required="required" autofocus="autofocus">
          </div>

          <div class="form-group">
              <label for="posDep">Department</label>
              <select class="form-control input-sm" id="posDep" name="posDep">
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
            <input type="text" class="form-control" id="note" name="posNote" placeholder="Note">
          </div>
        </div>
        <!-- /.modal-body -->
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmt_pos_create" type="button" id="sbmt_pos_create" class="btn btn-primary">add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreatePosition -->