<div id="dCreateColor">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateColor" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Цвет</h4>
        </div>
        <?php echo form_open_multipart('colors/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="colorName">Название</label>
              <input type="text" class="form-control input-sm" id="colorName" name="colorName" placeholder="Название Цвета *" required="required">
            </div>
          </div><!--/.col-md-6-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="colorHex">Выберите Цвет</label>
              <div id="colorHex" class="input-group colorpicker-component">
                  <input id="colorHexInput" type="text" value="#00AABB" class="form-control input-sm" readonly="" />
                  <span class="input-group-addon"><i></i></span>
              </div>
            </div>
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="colorNote">Описание</label>
              <input type="text" class="form-control input-sm" id="colorNote" name="colorNote" placeholder="Описание Цвета" required="required">
            </div>
          </div><!--/.col-md-6-->
          
         </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtColorCreate" type="button" id="sbmtColorCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreatecolor -->