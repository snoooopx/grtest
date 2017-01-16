<!-- dCreateTimesheet -->
<div id="dCreateTimesheet">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateTimesheet" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Timesheet</h4>
        </div>
        <?php echo form_open_multipart('Timesheets/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">
            
            <label for="tsWeek">Week</label>
            <input type="text" id="tsWeek">

            <!-- mainGrid -->
            <div id="gridMain">
              <table></table>
            </div>
            <!-- absenceGrid -->
            <div id="absenceGrid"></div>

        </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtTimesheetSave" type="button" id="sbmtTimesheetSave" class="btn btn-primary">Save</button>
          <button name="sbmtTimesheetSaveAndSubmit" type="button" id="sbmtTimesheetSaveAndSubmit" class="btn btn-primary">Save And Submit</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->

<!-- /# dCreateTimesheet bellow div-->
</div>