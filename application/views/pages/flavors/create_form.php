<div id="dCreateFlavor">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateFlavor" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Вкус</h4>
        </div>
        <?php echo form_open_multipart('flavors/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="flavorName">Название</label>
              <input type="text" class="form-control input-sm" id="flavorName" name="flavorName" placeholder="Название Вкуса *" required="required">
            </div>
            
          </div><!--/.col-md-6-->
          
          <div class="col-md-6">
            <div class="form-group">
              <label for="flavorNote">Описание</label>
              <input type="text" class="form-control input-sm" id="flavorNote" name="flavorNote" placeholder="Описание Вкуса" required="required">
            </div>
          </div><!--/.col-md-6-->
          
        </div><!--/.row-->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtFlavorCreate" type="button" id="sbmtFlavorCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateflavor -->