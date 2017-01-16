<div id="dCreateSet">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateSet" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Набор</h4>
        </div>
        <?php echo form_open_multipart('sets/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-12">
            <div class="form-group">
              <label for="setName">Название</label>
              <input type="text" class="form-control input-sm" id="setName" name="setName" placeholder="Название Набора *" required="required">
            </div>
          </div><!--/.col-md-12-->
          
        </div><!--/.row-->

        <div class="row">
          <div class="col-md-12">
            <div class="form-group">
              <label for="setDescription">Описание</label>
              <textarea id="setDescription"></textarea>
            </div>
          </div><!--/.col-md-12-->
        </div><!--/.row-->

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="setIsEnabled">Включено</label>
              <!-- <input type="text" class="form-control input-sm" id="setName" name="setName" placeholder="Название Набора *" required="required"> -->
              <select class="form-control input-sm" id="setIsEnabled">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="setShowInMenu">Показать В Меню</label>
              <!-- <input type="text" class="form-control input-sm" id="setName" name="setName" placeholder="Название Набора *" required="required"> -->
              <select class="form-control input-sm" id="setShowInMenu">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
          <div class="col-md-6">
            <div class="form-group">
              <label for="setShowInFooter">Показать В Футере</label>
              <!-- <input type="text" class="form-control input-sm" id="setName" name="setName" placeholder="Название Набора *" required="required"> -->
              <select class="form-control input-sm" id="setShowInFooter">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div><!--/.row-->


        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtSetCreate" type="button" id="sbmtSetCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateset -->