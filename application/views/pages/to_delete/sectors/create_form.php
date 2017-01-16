<!--/# dCreateSector -->
<div id="dCreateSector">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateSector" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Business Sector</h4>
        </div>
        <?php echo form_open_multipart('sectors/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

          <div class="form-group">
            <label for="secName">Name</label>
            <input type="text" class="form-control" id="secName" name="secName" placeholder="Business Sector"required="required" autofocus="autofocus">
          </div>

          <div class="form-group">
            <label for="secNote">Note</label>
            <input type="text" class="form-control" id="secNote" name="secNote" placeholder="Note">
          </div>

        </div>
        <!-- /.modal-body -->
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtSecCreate" type="button" id="sbmtSecCreate" class="btn btn-primary">add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateSector -->