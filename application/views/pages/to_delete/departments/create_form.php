<!--/# dCreateDepartment -->
<div id="dCreateDepartment">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateDepartment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Department</h4>
        </div>
        <?php echo form_open_multipart('departments/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">
          <div class="form-group">
            <label for="depName">Name</label>
            <input type="text" class="form-control" id="depName" name="depName" placeholder="Department Name"required="required" autofocus="autofocus">
          </div>
          <div class="form-group">
            <label for="depHeadId">Head</label>
            <select class="form-control" id="depHeadId" name="depHeadId" required="">
                <option value="" disabled="" selected=""><i>Select Department Head</i></option>
              <?php foreach ($user_list as $user): ?>
                <option value="<?php echo $user['id']; ?>"><?php echo $user['name'] . " " . $user['middle'] . " " . $user['sname']; ?></option>
              <?php endforeach ?>
            </select>
          </div>
          <div class="form-group">
          <label for="depCompanyId">Company</label>
            <select class="form-control" id="depCompanyId" name="depCompanyId" required="">
                <option value="<?php echo $company_list['id']; ?>"><?php echo $company_list['name']; ?></option>
            </select>
          </div>
        </div>
        <!-- /.modal-body -->
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtDepCreate" type="button" id="sbmtDepCreate" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateDepartment -->