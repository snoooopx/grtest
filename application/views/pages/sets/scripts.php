 <!-- Set Uploader Modal Create-->
  <div class="modal fade" id="setFileUpload" tabindex="-1" role="dialog" aria-labelledby="mdlFileUpload">
    <div class="modal-dialog" role="document">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="mdlFileUpload">Upload File</h4>
        </div>
        <div class="modal-body">
        <div id="universal_uploader"></div>
        <div class="row">
          <div class="col-md-6">
           <label for="setFeaturedImg">Главнoe изображение</label>
           <div id="setFeaturedImg"></div>
          </div>
        </div>

        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
          <!-- <button type="button" class="btn btn-primary">Закрыть</button> -->
        </div>
      </div>
    </div>
  </div>

<!-- Action Buttons Script Edit/Details/Remove... -->
<script type="text/template" id="action_buttons">
<div style="width: 90px;">
   <?php if ($allow['update'] OR $allow['delete'] ): ?>
          
          <?php if ($allow['update']): ?>
            <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Редактировать" href=<%= "setactions/e/"+id%>><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
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
<script type="text/template" id="tmplSetFields">

<?php echo form_open_multipart('#',array('id' =>'setSubmit' ,'name' =>'setSubmit' )); ?>
<div class="box-header with-border">
  <h3><span></span></h3>
</div>
<div class="box-body">
  <div class="row">
   <div class="col-md-9">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label for="setName">Название</label>
          <input type="text" class="form-control input-sm" id="setName" name="setName" placeholder="Название Набора *" required="required" value="<%= (typeof(info)!='undefined')?info.name:'' %>" >
        </div>
      </div><!--/.col-md-4 -->
      <div class="col-md-2">
        <div class="form-group">
          <label for="setSKU">SKU</label>
          <input type="text" class="form-control input-sm" id="setSKU" name="setSKU" placeholder="SKU" value="<%= (typeof(info)!='undefined')?info.sku:'' %>">
        </div>
      </div><!--/.col-md-2-->
      
       <div class="col-md-2">
        <div class="form-group">
          <label for="setIsNew">Новинка</label>
          <select class="form-control input-sm" id="setIsNew">
            <option value="0" selected="">Нет</option>
            <option value="1">Да</option>
          </select>
        </div>
      </div>
      
    </div><!--/.row-->
          
      <div class="row">
        <div class="col-md-12">
          <div class="form-group">
            <label for="setDescription">Описание</label>
            <textarea id="setDescription"><%= (typeof(info)!='undefined')?info.description:''%></textarea>
            <!-- <input type="text" class="form-control input-sm" id="setNote" name="setNote" placeholder="Описание Набора" required="required"> -->
          </div>
        </div><!--/.col-md-12-->
      </div><!--/.row-->

      <div class="row">
       <div class="col-md-2">
        <div class="form-group">
          <label for="setType">Тип</label>
          <select class="form-control input-sm" id="setType">
            <option value="static" selected="">Стандартный Набор</option>
            <option value="custom">Клиентский набор</option>
          </select>
        </div>
      </div>
      <div class="col-md-2">
        <div class="form-group">
          <label for="setInDesertPage">Показать в.</label></br>
          <select class="form-control input-sm" id="setInDesertPage" name="setInDesertPage">
            <option selected="" disabled="">Страница Десерта</option>
            <?php if ( isset($desert_type_list) && $desert_type_list !== false ): ?>
              <?php foreach ( $desert_type_list as $desert ): ?>
                <option value="<?php echo $desert['id']; ?>"><?php echo $desert['name']; ?></option>
              <?php endforeach ?>
            <?php endif ?>
          </select>
        </div>
      </div><!--/.col-md-2-->
      <div class="col-md-2">
        <div class="form-group">
          <label for="setCount">Кол. Дес.(шт.)</label>
          <select class="form-control input-sm" id="setCount">
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
          <label for="setPrice">Цена</label>
          <input type="text" class="form-control input-sm" id="setPrice" name="setPrice" placeholder="0.00"  value="<%= (typeof(info)!='undefined')?info.price:''%>">
        </div>
      </div><!--/.col-md-3-->
      <div class="col-md-2">
        <div class="form-group">
          <label for="setMMT">Валюта</label></br>
          <select class="form-control input-sm" id="setMMT" name="setMMT">
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
      </div><!--/.col-md-2-->
      <div class="col-md-2">
        <div class="form-group">
          <label for="setIsEnabled">Активно</label>
          <select class="form-control input-sm" id="setIsEnabled">
            <option value="0" selected="">Нет</option>
            <option value="1">Да</option>
          </select>
        </div>
      </div>
    </div><!-- ./ row -->
     
   </div><!-- col-md-9 -->

   <div class="col-md-3">
    <div class="row">
        <div class="col-md-12">
          
          <div class="form-group">
            <label for="setFeaturedImgImg">Главнoe изображение</label>
            <img id="setFeaturedImgImg" 
                  width="100%" 
                  data-dir="<?php echo base_url("application/assets"); ?>" 
                  data-hash=""
                  data-filename=""
                  data-defimgdef="<%= (typeof(info)=='undefined')? 
                                          '<?php echo $featured_default_image; ?>' 
                                          :( typeof(info.featured_image) != 'undefined' && info.featured_image != '' )? 
                                                   info.featured_image
                                                   :'<?php echo $featured_default_image; ?>' 
                                  %>"

                  src="<?php echo base_url($gallery_directory).'/'; ?><%=(typeof(info)=='undefined')? 
                                                                          '<?php echo $featured_default_image; ?>' 
                                                                          :(typeof(info.featured_image) != 'undefined' && info.featured_image != '')?   
                                                                                info.featured_image
                                                                                :'<?php echo $featured_default_image; ?>' %>">  
             <br />
             <br />
             <button type="button" class=" form-control btn btn-primary btn-sm" data-toggle="modal" data-target="#setFileUpload">
                Выбрать файл
              </button>
          </div>
        </div>
    </div>
   </div><!-- col-md-3 -->
  </div><!-- row1 -->

  </br>
  <div class="row">
  <div class="col-md-6">
    <div class="form-group">
      <label for="setItemList">Список Десертов</label>
      <select class="form-control input-sm" id="setItemList" data-placeholder="Виберите Десерт"></select>
      <table class="table table-hover" id="tblSetItems">
        <thead>
          <th>No.</th>
          <th>Название</th>
          <th>Количество</th>
          <th>Цена</th>
          <th>$</th>
          <th>#</th>
        </thead>
        <tbody>

        <% if(typeof(items) != 'undefined') {
              if(items != false){
              _.each(items, function(model,id){%>
                <%= 
                '<tr id="'+model.item_id+'">' 
                  +' <td id="numbering">'+(id+1)+'</td>'
                  +' <td>'+model.name+'</td>'
                  +' <td><input type="text" size="5" id="itemQtyInSet" value="'+model.qty+'"></td>'
                  +' <td>'+model.price+'</td>'
                  +' <td>'+model.mmt_name+'</td>'
                  +' <td><input class="removeSetItem" type="button" value="X"></td>'
                +'</tr>'
                %>
        <%});
        }
        }%>
        
        </tbody>
      </table>
    </div>
  </div><!--/.col-md-6-->
   <div class="col-md-6">
    <div class="form-group">
      <label for="setAttrList">Список Аттрибутов</label>
      <select id="setAttrList" class="form-control input-sm"></select>
      <table class="table table-hover" id="tblSetAttributes">
        <thead>
          <th>No.</th>
          <th>Название</th>
          <th>Группа</th>
          <th>Цена</th>
          <th>$</th>
          <th>#</th>
        </thead>
        <tbody>
          <% if(typeof(attrs) != 'undefined') {
              if(attrs != false){
              _.each(attrs, function(model,id){%>
                <%= 
                '<tr id="'+model.attr_id+'">' 
                  +' <td id="numbering">'+(id+1)+'</td>'
                  +' <td>'+model.name+'</td>'
                  +' <td>'+model.attrgroup_name+'</td>'
                  +' <td><input type="text" size="5" id="setAttrPrice" data-mmtid="'+model.mmt_id+'" value="'+model.price+'"></td>'
                  +' <td>'+model.mmt_name+'</td>'
                  +' <td><input class="removeSetItem" type="button" value="X"></td>'
                +'</tr>'
                %>
                
        <%});
        }
        }%>
        </tbody>
      </table>
    </div>
  </div><!--/.col-md-6-->
  </div><!--  row2 -->
</div><!-- ./box-body -->
<div class="box-footer">
  <button type="submit" name="sbmtSet" id="sbmtSet" class="btn btn-primary">Сохранить</button>
  <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button> -->
  <a class="btn btn-default" href="<?php echo site_url('backend/sets'); ?> ">Закрыть</a>
</div>
<?php echo form_close(); ?>
</script>


<?php echo (isset($upload_template))? $upload_template:''; ?>