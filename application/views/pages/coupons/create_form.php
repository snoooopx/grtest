<div id="dCreateCoupon">
  <!-- Modal -->
  <div class="modal fade" id="mdlCreateCoupon" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Создать/Редактировть Набор</h4>
        </div>
       
        <?php echo form_open_multipart('#',array('id' =>'couponSubmit' ,'name' =>'couponSubmit' )); ?>
        <!-- modal-body -->
        <div class="modal-body">
              <div class="box-body">
                <div class="row">
                 <div class="col-md-9">
                  <div class="row">
                    <div class="col-md-4">
                      <div class="form-group">
                        <label for="couponName">Название</label>
                        <input type="text" class="form-control input-sm" id="couponName" name="couponName" placeholder="Название Набора *" required="required" value="<%= (typeof(info)!='undefined')?info.name:'' %>" >
                      </div>
                    </div><!--/.col-md-6-->
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="couponSKU">SKU</label>
                        <input type="text" class="form-control input-sm" id="couponSKU" name="couponSKU" placeholder="SKU" value="<%= (typeof(info)!='undefined')?info.sku:'' %>">
                      </div>
                    </div><!--/.col-md-6-->
                  </div><!--/.row-->
                        
                    <div class="row">
                      <div class="col-md-12">
                        <div class="form-group">
                          <label for="couponDescription">Описание</label>
                          <textarea id="couponDescription"><%= (typeof(info)!='undefined')?info.description:''%></textarea>
                          <!-- <input type="text" class="form-control input-sm" id="couponNote" name="couponNote" placeholder="Описание Набора" required="required"> -->
                        </div>
                      </div><!--/.col-md-12-->
                    </div><!--/.row-->

                    <div class="row">
                     <div class="col-md-3">
                      <div class="form-group">
                        <label for="couponType">Тип</label>
                        <select class="form-control input-sm" id="couponType">
                          <option value="static" selected="">Стандартный Набор</option>
                          <option value="custom">Клиентский набор</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="couponCount">Кол. Дес.(шт.)</label>
                        <select class="form-control input-sm" id="couponCount">
                          <option value="3">3</option>
                          <option value="4">4</option>
                          <option value="5">5</option>
                          <option value="6">6</option>
                          <option value="8">8</option>
                          <option value="10">10</option>
                          <option value="12">12</option>
                          <option value="16">16</option>
                          <option value="24">24</option>
                          <option value="50">50</option>
                          <option value="100">100</option>
                        </select>
                      </div>
                    </div><!--/.col-md-2-->
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="couponPrice">Цена</label>
                        <input type="text" class="form-control input-sm" id="couponPrice" name="couponPrice" placeholder="0.00"  value="<%= (typeof(info)!='undefined')?info.price:''%>">
                      </div>
                    </div><!--/.col-md-3-->
                    <div class="col-md-2">
                      <div class="form-group">
                        <label for="couponMMT">Валюта</label></br>
                        <select class="form-control input-sm" id="couponMMT" name="couponMMT">
                          <?php if ( isset($mmt_list) && $mmt_list !== false ): ?>
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
                    <div class="col-md-3">
                      <div class="form-group">
                        <label for="couponIsEnabled">Активно</label>
                        <select class="form-control input-sm" id="couponIsEnabled">
                          <option value="0" selected="">Нет</option>
                          <option value="1">Да</option>
                        </select>
                      </div>
                    </div>
                  </div><!-- ./ row -->
                   
                 </div><!-- col-md-9 -->

                 <div class="col-md-3">
                 
                 </div><!-- col-md-3 -->
              </div><!-- row1 -->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtCoupon" type="button" id="sbmtCoupon" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</div>
<!-- /# dCreateCoupon -->