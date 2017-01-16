<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
 <?php if ($allow['read'] OR $allow['update'] OR $allow['delete'] ): ?>
       <div style="align:right;width:90px;" >
        <!-- Read -->
        <?php if ( $allow['read'] ): ?>
           <a style="align:right;"" class="btn btn-default btn-xs details" data-toggle="tooltip" data-placement="top" title="View Client Profile" href=""><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>
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

<!-- Client Edit Modal -->
<script type="text/template" id="tmplClientEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditClient" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Edit Client: <b><%= name %></b></h4>
      </div><!--/.modal header -->
        <?php echo form_open_multipart('clients/#'); ?>
        <!-- .modal-body -->
    <div class="modal-body">
     <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientNameEdit">Name</label>
              <input type="text" class="form-control input-sm" id="clientNameEdit" name="clientNameEdit" placeholder="Client Name *"required="required" value="<%= name %>" >
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6 col-xs-3">
            <div class="form-group">
              <label for="clientAbbrEdit">Abbreviation</label>
              <input type="text" class="form-control" id="clientAbbrEdit" name="clientAbbrEdit" placeholder="Client Abbr." value="<%= abbr %>">
            </div>
          </div><!--/.col-md-4-->

           <div class="col-md-6">
            <div class="form-group">
              <label for="clientDepartmentsEdit">Client Departments</label>
              <select class="form-control" id="clientDepartmentsEdit" name="clientDepartmentsEdit" multiple="multiple">
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
              <label for="clientSectorsEdit">Client Sectors</label>
              <select class="" id="clientSectorsEdit" name="clientSectorsEdit" multiple="multiple">
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
              <label for="clientContactEdit">Contact Person</label>
              <input type="text" class="form-control input-sm" id="clientContactEdit" name="clientContactEdit" placeholder="Client Contact" value="<%= contact_person %>">
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="clientEmailEdit">Email</label>
              <input type="text" class="form-control input-sm" id="clientEmailEdit" name="clientEmailEdit" placeholder="Email" required="required" value="<%= email %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientPhoneEdit">Phone</label>
              <input type="text" class="form-control input-sm" id="clientPhoneEdit" name="clientPhoneEdit" placeholder="Phone" value="<%= phone %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientAddressEdit">Address</label>
              <input type="text" class="form-control input-sm" id="clientAddressEdit" name="clientAddressEdit" placeholder="Address" value="<%= address %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientAccEdit">Bank Account</label>
              <input type="text" class="form-control input-sm" id="clientAccEdit" name="clientAccEdit" placeholder="Acc" value="<%= bank_acc %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientRegNumEdit">Reg Num</label>
              <input type="text" class="form-control input-sm" id="clientRegNumEdit" name="clientRegNumEdit" placeholder="RegNum" value="<%= reg_num %>">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="clientTinEdit">Tax Code</label>
              <input type="text" class="form-control input-sm" id="clientTinEdit" name="clientTinEdit" placeholder="Tin" value="<%= tin%>">
            </div>
          </div><!--/.col-md-6-->

           <div class="col-md-6">
            <div class="form-group">
              <label for="clientIsVisibleEdit">Visibility (<small><i>Not Visible in App if Unchecked</i></small>)</label>
              <input type="checkbox" value="1" class="checkbox" id="clientIsVisibleEdit" name="clientIsVisibleEdit" <%= is_visible==1 ?"checked":"" %>>
            </div>
          </div><!--/.col-md-6-->

        </div><!--/.row-->
      
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtClientEdit" id="sbmtClientEdit" class="btn btn-primary">Save</button>
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