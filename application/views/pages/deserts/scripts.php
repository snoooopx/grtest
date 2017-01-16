<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
<div style="width: 90px;">
   <?php if ($allow['update'] OR $allow['delete'] ): ?>
          
          <?php if ($allow['update']): ?>
            <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Редактировать" href="#"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
          <?php endif ?>

          <?php if ($allow['delete']): ?>
            <a class="btn btn-danger btn-xs delete" data-toggle="tooltip" data-placement="top" title="Удалить" href="#"><i class="fa fa-trash-o fa-lg" aria-hidden="true"></i></a>
          <?php endif ?>
          
   <?php endif ?>
 </div>
</script>

<!-- Desert Edit Modal -->
<script type="text/template" id="tmplDesertEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditDesert" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Редактировать Тип Десерта: <b><%= name %></b></h4>
        </div><!--/.modal header -->
        <?php echo form_open_multipart('Deserts/#'); ?>
        <!-- .modal-body -->
        <div class="modal-body">
          <div class="row">

            <div class="col-md-12">
              <div class="form-group">
                <label for="desertNameEdit">Название</label>
                <input type="text" class="form-control input-sm" id="desertNameEdit" name="desertNameEdit" placeholder="Название Десерта *" required="required" value="<%= name %>" >
              </div>
            </div><!--/.col-md-6-->
          </div><!--/.row-->
              
          <div class="row">
            <div class="col-md-12">
              <div class="form-group">
                <label for="desertDescriptionEdit">Описание</label>
                <textarea id="desertDescriptionEdit"><%= description %></textarea>
                <!-- <input type="text" class="form-control input-sm" id="desertNote" name="desertNote" placeholder="Описание Десерта" required="required"> -->
              </div>
            </div><!--/.col-md-12-->
          </div><!--/.row-->

          <div class="row">
           <div class="col-md-6">
            <div class="form-group">
              <label for="desertIsEnabled">Включено</label>
              <!-- <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="desertIsEnabledEdit">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="desertShowInMenuEdit">Показать В Меню</label>
              <!-- <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="desertShowInMenuEdit">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
              <% $("#desertShowInMenuEdit").val('+show_in_menu+').change(); %>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="desertShowInFooter">Показать В Футере</label>
              <!-- <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="desertShowInFooterEdit">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div><!--/.row-->
          
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtDesertEdit" id="sbmtDesertEdit" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
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
	        <h4 class="modal-title">Вы действительно хотите удалить?</h4>
	      </div>
	      <?php echo form_open_multipart('#'); ?>
	      <div class="modal-body">
	        <p><b><%=name%></b></p>
	      </div>
	      <div class="modal-footer">
	       <button id="confirmDelete" name="confirmDelete" type="button" class="btn btn-primary">Подтвердить</button>
	       <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
	      </div>
	      <?php echo form_close(); ?>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</script>