<!--/# dCreateClient -->
<div id="dCreateClient">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateClient" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create Client</h4>
        </div>
        <?php echo form_open_multipart('Clients/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientName">Name</label>
              <input type="text" class="form-control input-sm" id="clientName" name="clientName" placeholder="Client Name *"required="required" >
            </div>
            
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="clientAbbr">Abbreviation</label>
              <input type="text" class="form-control" id="clientAbbr" name="clientAbbr" placeholder="Client Abbr. *" required="required" size="5px">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientDepartments">Client Departments</label><br/>
              <select class="form-control input-sm" id="clientDepartments" name="clientDepartments" multiple="multiple">
              <?php if ($department_list !== false): ?>
                  <?php foreach ($department_list as $dep): ?>
                    <option value="<?php echo $dep['id']; ?>"><?php echo $dep['depName']; ?></option>
                  <?php endforeach ?>
              <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientSectors">Client Sectors</label><br/>
              <select class="form-control input-sm" id="clientSectors" name="clientSectors" multiple="multiple">
                <?php if ($sector_list['items'] !== false): ?>
                    <?php foreach ($sector_list['items'] as $sec): ?>
                      <option value="<?php echo $sec['id']; ?>"><?php echo $sec['name']; ?></option>
                    <?php endforeach ?>
                <?php endif ?>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6 ">
            <div class="form-group">
              <label for="clientContact">Contact Person</label>
              <input type="text" class="form-control input-sm" id="clientContact" name="clientContact" placeholder="Client Contact" required="required" >
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="clientEmail">Email</label>
              <input type="text" class="form-control input-sm" id="clientEmail" name="clientEmail" placeholder="Email" required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientPhone">Phone</label>
              <input type="text" class="form-control input-sm" id="clientPhone" name="clientPhone" placeholder="Phone"  >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientAddress">Address</label>
              <input type="text" class="form-control input-sm" id="clientAddress" name="clientAddress" placeholder="Address" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientAcc">Bank Account</label>
              <input type="text" class="form-control input-sm" id="clientAcc" name="clientAcc" placeholder="Acc" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientRegNum">Reg Num</label>
              <input type="text" class="form-control input-sm" id="clientRegNum" name="clientRegNum" placeholder="RegNum" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientTin">Tax Code</label>
              <input type="text" class="form-control input-sm" id="clientTin" name="clientTin" placeholder="Tin" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientIsVisible">Visibility (<small><i>Not Visible in App if Unchecked</i></small>)</label>
              <input type="checkbox" value="1" class="checkbox" id="clientIsVisible" name="clientIsVisible">
            </div>
          </div><!--/.col-md-6-->


        </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtClientCreate" type="button" id="sbmtClientCreate" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateClient -->