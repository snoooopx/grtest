<div id="dCreateAttrGroup">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateAttrGroup" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Тип Десерта</h4>
        </div>
        <?php echo form_open_multipart('attrgroups/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">

         <div class="row">

          <div class="col-md-6">
            <div class="form-group">
              <label for="attrgroupName">Название</label>
              <input type="text" class="form-control input-sm" id="attrgroupName" name="attrgroupName" placeholder="Название Десерта *" required="required">
            </div>
          </div><!--/.col-md-12-->

          <div class="col-md-6">
            <div class="form-group">
              <label for="attrgroupDescription">Описание</label>
              <input type="text" class="form-control input-sm" id="attrgroupDescription" name="attrgroupNote" placeholder="Описание Десерта" required="required">
            </div>
          </div><!--/.col-md-12-->
        </div><!--/.row-->

        <div class="row">
          <div class="col-md-6">
            <div class="form-group">
              <label for="attrgroupIsEnabled">Включено</label>
              <!-- <input type="text" class="form-control input-sm" id="attrgroupName" name="attrgroupName" placeholder="Название Десерта *" required="required"> -->
              <select class="form-control input-sm" id="attrgroupIsEnabled">
                <option value="0" selected="">No</option>
                <option value="1">Yes</option>
              </select>
            </div>
          </div>
        </div><!--/.row-->


        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtAttrGroupCreate" type="button" id="sbmtAttrGroupCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateattrgroup -->