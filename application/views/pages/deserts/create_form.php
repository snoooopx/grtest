<div id="dCreateDesert">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateDesert" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Тип Десерта</h4>
        </div>
        <?php echo form_open_multipart('deserts/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-12">
            <div class="form-group">
              <label for="desertName">Название</label>
              <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required">
            </div>
          </div><!--/.col-md-12-->
          
        </div><!--/.row-->

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="desertDescription">Описание</label>
              <textarea id="desertDescription"></textarea>
              <!-- <input type="text" class="form-control input-sm" id="desertNote" name="desertNote" placeholder="Описание Десерта" required="required"> -->
            </div>
          </div><!--/.col-md-12-->
        </div><!--/.row-->

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="desertIsEnabled">Включено</label>
              <!-- <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="desertIsEnabled">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="desertShowInMenu">Показать В Меню</label>
              <!-- <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="desertShowInMenu">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="desertShowInFooter">Показать В Футере</label>
              <!-- <input type="text" class="form-control input-sm" id="desertName" name="desertName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="desertShowInFooter">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div><!--/.row-->


        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtDesertCreate" type="button" id="sbmtDesertCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreatedesert -->