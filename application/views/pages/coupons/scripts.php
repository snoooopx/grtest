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




<!-- SET FIELDS -->
<script type="text/template" id="tmplCouponModal">

  <!-- Modal -->
  <div class="modal fade" id="mdlCouponActions<%=(typeof(id)!='undefined')?'_'+id:''%>" role="dialog" aria-labelledby="myModalLabel">
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
                 <div class="col-md-12">
                  <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="couponCode">Название</label>
                        <input type="text" class="form-control input-sm" id="couponCode" name="couponCode" placeholder="Код" required="required" value="<%= (typeof(code)!='undefined')?code:'' %>" >
                      </div>
                    </div><!--/.col-md-6-->
                   
                      <div class="col-md-6">
                        <div class="form-group">
                          <label for="couponDescription">Описание</label>
                          <input type="text" class="form-control input-sm" id="couponDescription" name="couponDescription" placeholder="Описание Купона" required="required" value="<%= (typeof(description)!='undefined')?description:''%>">
                        </div>
                      </div><!--/.col-md-6-->
                    </div><!--/.row-->

                    <div class="row">
                     <div class="col-md-6">
                      <div class="form-group">
                        <label for="couponType">Тип</label>
                        <select class="form-control input-sm" id="couponType">
                          <option value="fix" selected="">Фиксированный (руб.)</option>
                          <option value="percent">Процент (%)</option>
                        </select>
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="couponDiscount">Дисконт</label>
                        <input type="text" class="form-control input-sm" id="couponDiscount" name="couponDiscount" placeholder="0.00"  value="<%= (typeof(discount)!='undefined')?discount:''%>" required="required">
                      </div>
                    </div><!--/.col-md-6-->
                    </div>
                    
                    <div class="row">
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="couponStartDate">Начало</label>
                        <input class="form-control input-sm" type="text" id="couponStartDate" placeholder="YYYY-MM-DD" value="<%= (typeof(start_date)!='undefined')?start_date:''%>" required="required">
                      </div>
                    </div>
                    <div class="col-md-6">
                      <div class="form-group">
                        <label for="couponEndDate">Конец</label>
                        <input class="form-control input-sm" type="text" id="couponEndDate"  placeholder="YYYY-MM-DD" value="<%= (typeof(end_date)!='undefined')?end_date:''%>" required="required">
                      </div>
                    </div>
                    </div>

                    <div class="row">
                    <div class="col-md-6">
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


              </div><!-- row1 -->

        </div><!-- /.modal-body -->
        
        <!-- modal-footer -->
        <div class="modal-footer">
          <button name="sbmtCoupon" type="submit" id="sbmtCoupon" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
</script>


<?php echo (isset($upload_template))? $upload_template:''; ?>