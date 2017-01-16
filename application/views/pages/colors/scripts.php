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

<!-- Color Edit Modal -->
<script type="text/template" id="tmplColorEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditColor" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
      <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Редактировать Цвет: <b><%= name %></b></h4>
        </div><!--/.modal header -->
        <?php echo form_open_multipart('colors/#'); ?>
        <!-- .modal-body -->
        <div class="modal-body">
          <div class="row">

            <div class="col-md-6">
              <div class="form-group">
                <label for="colorNameEdit">Название</label>
                <input type="text" class="form-control input-sm" id="colorNameEdit" name="colorNameEdit" placeholder="Название Цвета *" required="required" value="<%= name %>" >
              </div>
            </div><!--/.col-md-6-->
            
             <div class="col-md-6">
            <div class="form-group">
              <label for="colorHexEdit">Выберите Цвет</label>
              <div id="colorHexEdit" class="input-group colorpicker-component">
                  <input id="colorHexInput" type="text" value="<%= hex %>" class="form-control input-sm" readonly=""/>
                  <span class="input-group-addon"><i></i></span>
              </div>
            </div>
          </div><!--/.col-md-6-->

            <div class="col-md-6">
              <div class="form-group">
                <label for="colorNoteEdit">Описание</label>
                <input type="text" class="form-control input-sm" id="colorNoteEdit" name="colorNoteEdit" placeholder="Описание Цвета" value="<%= description %>">
              </div>
            </div><!--/.col-md-6-->

          </div><!--/.row-->
          
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtColorEdit" id="sbmtColorEdit" class="btn btn-primary">Сохранить</button>
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