<div id="dCreateAttribute">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateAttribute" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Аттрибут</h4>
        </div>
        <?php echo form_open_multipart('Attributes/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">
        
        <div class="row">
         
          <div class="col-md-9">
            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeName">Название</label>
                  <input type="text" class="form-control input-sm" id="attributeName" name="attributeName" placeholder="Название*" required="required" >
                </div>
              </div><!--/.col-md-6-->
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeGroup">Тип Аттрибута</label>
                  <select class="form-control input-sm" id="attributeGroup" name="attributeGroup">
                    <option selected="" disabled="" value="">Выберите Тип Аттрибута</option>
                    <?php if ( isset($attrgroup_list['items']) ): ?>
                        <?php foreach ( $attrgroup_list['items'] as $attrgroup ): ?>
                          <option value="<?php echo $attrgroup['id']; ?>"><?php echo $attrgroup['name']; ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeDescription">Описание</label>
                  <input type="text" class="form-control input-sm" name="attributeDescription" id="attributeDescription" placeholder="Описание">
                </div>
              </div><!--/.col-md-12-->
              

              <div class="col-md-3">
                <div class="form-group">
                  <label for="attributePrice">Цена</label>
                  <input type="text" class="form-control input-sm" id="attributePrice" name="attributePrice" placeholder="0.00" required="">
                </div>
              </div><!--/.col-md-3-->

              <div class="col-md-3">
                <div class="form-group">
                  <label for="attributeMMT">Валюта</label></br>
                  <select class="form-control input-sm" id="attributeMMT" name="attributeMMT">
                    <?php if ( $mmt_list !== false ): ?>
                      <?php foreach ( $mmt_list as $mmt ): ?>
                        <?php $is_selected=''; ?>
                        <?php if ($mmt['sort_order'] == 1): ?>
                          <?php $is_selected='selected=""'; ?>
                        <?php endif ?>

                        <option  <?php echo $is_selected; ?> value="<?php echo $mmt['id']; ?>"><?php echo $mmt['name'] . ' ('.$mmt['sign'] .')'; ?></option>

                      <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>
              </div><!--/.col-md-3-->

               <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeAllowUserText">Пользоватеь Может Добавить Текст</label>
                  <select class="form-control input-sm" id="attributeAllowUserText">
                    <option value="0" selected="">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div>
              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeIsActive">Aктивно</label>
                  <select class="form-control input-sm" id="attributeIsActive">
                    <option value="0" selected="">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div><!--/.col-md-6-->

          </div><!-- /.col-md-9 -->
            
          <div class="col-md-3">
              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="attributeFeaturedImgImg">Главнoe изображение</label>
                  <!--   <input type="hidden" id="attributeFeaturedImgName" name="attributeFeaturedImgName" data-filename=""> -->
                    <img id="attributeFeaturedImgImg" width="195" 
                         data-dir="<?php echo base_url("application/assets"); ?>" 
                         data-hash=""
                         data-filename="";
                         data-defimgdef="<?php echo $featured_default_image; ?>" 
                         src="<?php echo base_url($gallery_directory.'/'.$featured_default_image); ?>">  
                     <button type="button" class=" form-control btn btn-primary btn-sm" data-toggle="modal" data-target="#attributeFileUpload">
                        Выбрать файл
                      </button>
                  </div>
                </div>
              </div>
          </div><!--/.col-md-3-->
        
        </div><!-- /.row -->
      </div><!-- /.modal-body -->
        
       <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtAttributeCreate" type="button" id="sbmtAttributeCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->


</div>
 
