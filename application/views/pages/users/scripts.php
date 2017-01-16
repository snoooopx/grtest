<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ( $allow['read'] OR $allow['update'] OR $allow['delete'] ): ?>
       <div style="align:right;width:90px;" >
        <!-- Read -->
        <?php if ( $allow['read'] ): ?>
           <a style="align:right;"" class="btn btn-default btn-xs details" data-toggle="tooltip" data-placement="top" title="View Profile" href=""><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>
        <!-- Edit -->
        <?php if ( $allow['update'] ): ?>
           <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Edit" href=""><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>

        <!-- Delete -->
        <?php if ( $allow['delete'] ): ?>
          <a class="btn btn-danger btn-xs delete" href="#"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>
       <!--  </ul> -->
      </div>
 <?php endif ?>
</script>

<!-- User Edit Modal -->
<script type="text/template" id="tmplUserEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditUser" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
	    <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit User: <b><%= name %></b></h4>
	    </div><!--/.modal header -->
        <?php echo form_open_multipart('users/#'); ?>
        <!-- .modal-body -->
		<div class="modal-body">
		 <div class="row">

          <div class="col-md-4">
            <div class="form-group">
              <label for="userNameEdit">Name</label>
              <input type="text" class="form-control input-sm" id="userNameEdit" name="userNameEdit" placeholder="User Name *"required="required" value="<%= name %>" >
            </div>
            
          </div><!--/.col-md-4-->
          
          <div class="col-md-3 col-xs-3">
            <div class="form-group">
              <label for="userMiddleEdit">Initials</label>
              <input type="text" class="form-control input-sm" id="userMiddleEdit" name="userMiddleEdit" placeholder="User Initials" value="<%= middle %>">
            </div>
          </div><!--/.col-md-4-->

          <div class="col-md-4 ">
            <div class="form-group">
              <label for="userSnameEdit">Surname</label>
              <input type="text" class="form-control input-sm" id="userSnameEdit" name="userSnameEdit" placeholder="User Surname" value="<%= sname %>">
            </div>
          </div><!--/.col-md-4-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="userLoginEdit">Login</label>
              <input type="text" class="form-control input-sm" id="userLoginEdit" name="userLoginEdit" placeholder="Login *" required="required" value="<%= login %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userEmailEdit">Email</label>
              <input type="text" class="form-control input-sm" id="userEmailEdit" name="userEmailEdit" placeholder="Email" required="required" value="<%= email %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userPhoneEdit">Phone</label>
              <input type="text" class="form-control input-sm" id="userPhoneEdit" name="userPhoneEdit" placeholder="Phone" value="<%= phone %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userAddressEdit">Address</label>
              <input type="text" class="form-control input-sm" id="userAddressEdit" name="userAddressEdit" placeholder="Address" value="<%= address %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userSexEdit">Sex</label>
              <select class="form-control input-sm" id="userSexEdit" name="userSexEdit" required="" >
                  <option value="" disabled="" selected=""><i>Select</i></option>
                  <option value="m">M</option>
                  <option value="f">F</option>
              </select>
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            
          </div>
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="userPasswordEdit">Password</label>
              <input type="password" class="form-control input-sm" id="userPasswordEdit" name="userPasswordEdit">
            </div>
          </div><!--/.col-md-6-->          

          <div class="col-md-6">
            <div class="form-group">
              <label for="userPasswordConfirmEdit">Confirm Password</label>
              <input type="password" class="form-control input-sm" id="userPasswordConfirmEdit" name="userPasswordConfirm">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="userIsActiveEdit">Login Allowed
              <input type="checkbox" value="1" class="checkbox" id="userIsActiveEdit" name="userIsActiveEdit" <%= isActive==1 ?"checked":"" %>>
            </div>
          </div><!--/.col-md-6-->
         
        </div><!--/.row-->
			
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtUserEdit" id="sbmtUserEdit" class="btn btn-primary">Save</button>
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



<script type="text/template" id="tmpUserPermRow">
    <td> <%= section_name %></td>
    <td> <input size="2" type="text" id="section_seq" name="section_seq" value="<%= section_seq %>"> </td>
    <td> <%= subsection_name %> </td>
    <td> <input size="2" type="text" id="subsection_seq" name="subsection_seq" value="<%= subsection_seq %>"> </td>
    <td> <input type="checkbox" id="r" name="r" value="<%= r %>"> </td>
    <td> <input type="checkbox" id="c" name="c" value="<%= c %>"> </td>
    <td> <input type="checkbox" id="u" name="u" value="<%= u %>"> </td>
    <td> <input type="checkbox" id="d" name="d" value="<%= d %>"> </td>
    <td> <input type="checkbox" id="check_all" name="check_all">  </td>
</script>