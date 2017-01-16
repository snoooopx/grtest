<!--/# dCreateUser -->
<div id="dCreateUser">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Create User</h4>
        </div>
        <?php echo form_open_multipart('Users/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-4">
            <div class="form-group">
              <label for="userName">Name</label>
              <input type="text" class="form-control input-sm" id="userName" name="userName" placeholder="User Name *"required="required" >
            </div>
            
          </div><!--/.col-md-4-->
          
          <div class="col-md-3 col-xs-3">
            <div class="form-group">
              <label for="userMiddle">Initials</label>
              <input type="text" class="form-control input-sm" id="userMiddle" name="userMiddle" placeholder="User Initials" required="required" size="5px">
            </div>
          </div><!--/.col-md-4-->

          <div class="col-md-4 ">
            <div class="form-group">
              <label for="userSname">Surname</label>
              <input type="text" class="form-control input-sm" id="userSname" name="userSname" placeholder="User Surname" required="required" >
            </div>
          </div><!--/.col-md-4-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="userLogin">Login</label>
              <input type="text" class="form-control input-sm" id="userLogin" name="userLogin" placeholder="Login *" required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userEmail">Email</label>
              <input type="text" class="form-control input-sm" id="userEmail" name="userEmail" placeholder="Email" required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userPhone">Phone</label>
              <input type="text" class="form-control input-sm" id="userPhone" name="userPhone" placeholder="Phone"  >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userAddress">Address</label>
              <input type="text" class="form-control input-sm" id="userAddress" name="userAddress" placeholder="Address" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userSex">Sex</label>
              <select class="form-control input-sm" id="userSex" name="userSex" required="">
                  <option value="" disabled="" selected=""><i>Select</i></option>
                  <option value="m">M</option>
                  <option value="f">F</option>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userPassword">Password</label>
              <input type="password" class="form-control input-sm" id="userPassword" name="userPassword" required="required" >
            </div>
          </div><!--/.col-md-6-->          

          <div class="col-md-6">
            <div class="form-group">
              <label for="userPasswordConfirm">Confirm Password</label>
              <input type="password" class="form-control input-sm" id="userPasswordConfirm" name="userPasswordConfirm" required="required" >
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userIsActive">Login Allowed
              <input type="checkbox" value="1" class="checkbox" id="userIsActive" name="userIsActive">
            </div>
          </div><!--/.col-md-6-->

        </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtUserCreate" type="button" id="sbmtUserCreate" class="btn btn-primary">Add</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateUser -->