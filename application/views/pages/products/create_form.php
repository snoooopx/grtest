<div id="dCreateProduct">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateProduct" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать Новый Продукт</h4>
        </div>
        <?php echo form_open_multipart('Products/#'); ?>
        <!-- modal-body -->
        <div class="modal-body">
        
        <div class="row">
         
          <div class="col-md-9">
            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="productName">Название</label>
                  <input type="text" class="form-control input-sm" id="productName" name="productName" placeholder="Название*" required="required" >
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="productSKU">SKU</label>
                  <input type="text" class="form-control input-sm" id="productSKU" name="productCode" placeholder="Product SKU *" required="required" >
                </div>
              </div><!--/.col-md-6-->
              
              
              <div class="col-md-12">
                <div class="form-group">
                  <label for="productDescription">Описание</label>
                  <textarea id="productDescription"></textarea>
                </div>
              </div><!--/.col-md-12-->

              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="productDesert">Тип Десерта</label>
                  <select class="form-control input-sm" id="productDesert" name="productDesert">
                    <option selected="" disabled="" value="">Выберите Тип Десерта</option>
                    <?php if ( isset($desert_list['items']) ): ?>
                        <?php foreach ( $desert_list['items'] as $desert ): ?>
                          <option value="<?php echo $desert['id']; ?>"><?php echo $desert['name']; ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="productFlavor">Вкус</label>
                  <select class="form-control input-sm" id="productFlavor" name="productFlavor">
                    <option selected="" disabled="" value="">Выберите Вкус</option>
                    <?php if ( isset($flavor_list['items']) ): ?>
                        <?php foreach ( $flavor_list['items'] as $flavor ): ?>
                          <option value="<?php echo $flavor['id']; ?>"><?php echo $flavor['name']; ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="productColor">Цвет</label>
                  <select class="form-control input-sm" id="productColor" name="productColor">
                    <option selected="" disabled="" value="">Выберите Цвет</option>
                    <?php if ( isset($color_list['items']) ): ?>
                        <?php foreach ( $color_list['items'] as $color ): ?>
                          <option value="<?php echo $color['id']; ?>"><?php echo $color['name']; ?></option>
                        <?php endforeach ?>
                    <?php endif ?>
                  </select>
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="productWeight">Вес</label>
                  <input type="text" class="form-control input-sm" id="productWeight" name="productWeight" placeholder="0">
                </div>
              </div><!--/.col-md-6-->
              
                <div class="col-md-3">
                  <div class="form-group">
                    <label for="productPrice">Цена</label>
                    <input type="text" class="form-control input-sm" id="productPrice" name="productPrice" placeholder="0.00" required="">
                  </div>
                </div><!--/.col-md-3-->

                <div class="col-md-3">
                  <div class="form-group">
                    <label for="productMMT">Валюта</label></br>
                    <select class="form-control input-sm" id="productMMT" name="productMMT">
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
                  <label for="productUseInSet">Исполвзовать В Сете</label>
                  <select class="form-control input-sm" id="productUseInSet">
                    <option value="0" selected="">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div><!--/.col-md-6-->
              
              <div class="col-md-6">
                <div class="form-group">
                  <label for="productShowInGallery">Показать В Галерее</label>
                  <select class="form-control input-sm" id="productShowInGallery">
                    <option value="0" selected="">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="productIsActive">Aктивно</label>
                  <select class="form-control input-sm" id="productIsActive">
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
                      <label for="productAvatarBBImg">Аватар (Build_А_Box)</label>
                      <input type="hidden" class="form-control input-sm" id="productAvatarBBName" name="productAvatarBBName" data-filename="">
                      <img id="productAvatarBBImg" width="195" 
                           data-dir="<?php echo base_url("application/assets"); ?>" 
                           data-hash=""
                           data-filename="";
                           data-defimgdef="<?php echo $avatar_default_image; ?>" 
                           src="<?php echo base_url($gallery_directory.'/'.$avatar_default_image); ?>">  
                      <button type="button" class="form-control btn btn-primary btn-sm" data-toggle="modal" data-target="#productFileUpload">
                        ВЫбрать файл
                      </button>
                    </div>
                </div>
              </div>

              <div class="row">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="productFeaturedImgImg">Главнoe изображение</label>
                  <!--   <input type="hidden" id="productFeaturedImgName" name="productFeaturedImgName" data-filename=""> -->
                    <img id="productFeaturedImgImg" width="195" 
                         data-dir="<?php echo base_url("application/assets"); ?>" 
                         data-hash=""
                         data-filename="";
                         data-defimgdef="<?php echo $featured_default_image; ?>" 
                         src="<?php echo base_url($gallery_directory.'/'.$featured_default_image); ?>">  
                     <button type="button" class=" form-control btn btn-primary btn-sm" data-toggle="modal" data-target="#productFileUpload">
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
          <button name="sbmtProductCreate" type="button" id="sbmtProductCreate" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->


</div>
 
