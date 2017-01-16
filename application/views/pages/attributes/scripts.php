<!-- File Uploader Modal Create-->
<div class="modal fade" id="attributeFileUpload" tabindex="-1" role="dialog" aria-labelledby="mdlFileUpload">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mdlFileUpload">Загрузить файл</h4>
      </div>
      <div class="modal-body">
      <div id="universal_uploader"></div>
      <div class="row">
        <div class="col-md-6">
         <label for="attributeFeaturedImg">Главнoe изображение</label>
         <div id="attributeFeaturedImg"></div>
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
<!-- File Uploader Modal Edit-->
<div class="modal fade" id="attributeFileUploadEdit" tabindex="-1" role="dialog" aria-labelledby="mdlFileUploadEdit">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title" id="mdlFileUpload">Загрузить файл</h4>
      </div>
      <div class="modal-body">
      <div id="universal_uploader"></div>
      <div class="row">
        <div class="col-md-6">
         <label for="attributeFeaturedImgEdit">Главнoe изображение</label>
         <div id="attributeFeaturedImgEdit"></div>
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
 <?php if ($allow['read'] OR $allow['update'] OR $allow['delete'] ): ?>
        <?php if ( $allow['read'] ): ?>
           <a class="btn btn-default btn-xs details" data-toggle="tooltip" data-placement="top" title="View Details" href= <%="./attributeview/"+id%>><i class="fa fa-info-circle fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>

        <?php if ( $allow['update'] ): ?>
           <a class="btn btn-default btn-xs edit" data-toggle="tooltip" data-placement="top" title="Edit" href="#"><i class="fa fa-pencil-square-o fa-lg" aria-hidden="true"></i></a>
        <?php endif ?>

        <?php if ( $allow['delete'] ): ?>
            <a class="btn btn-danger btn-xs delete" data-toggle="tooltip" data-placement="top" title="Remove" href="#"><i class="fa fa-trash" aria-hidden="true"></i></a>
        <?php endif ?>
 <?php endif ?>
      </div>
</script>

<!-- Attribute Edit Modal -->
<script type="text/template" id="tmplAttributeEditModal">
  <!-- Modal -->
  <div class="modal fade" id="mdlEditAttribute" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog  modal-lg" role="document">
      <div class="modal-content">
      <!-- .modal header -->
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
          <h4 class="modal-title" id="myModalLabel">Редактировать Аттрибут: <b><%= name %></b></h4>
      </div><!--/.modal header -->
        <?php echo form_open_multipart('clients/#'); ?>
        <!-- .modal-body -->
        <div class="modal-body">
         <div class="row">
          <div class="col-md-9">
            
              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeNameEdit">Название</label>
                  <input type="text" class="form-control input-sm" id="attributeNameEdit" name="attributeNameEdit" placeholder="Название*" required="required" value="<%=name%>">
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeGroupEdit">Тип Аттрибута</label></br>
                  <select class="form-control input-sm" id="attributeGroupEdit" name="attributeGroupEdit">
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
                  <label for="attributeDescriptionEdit">Описание</label>
                  <input type="text" class="form-control input-sm" name="attributeDescriptionEdit" id="attributeDescriptionEdit" placeholder="Описание" value="<%=description%>">
                </div>
              </div><!--/.col-md-12-->

              <div class="col-md-3">
                <div class="form-group">
                  <label for="attributePriceEdit">Цена</label>
                  <input type="text" class="form-control input-sm" id="attributePriceEdit" name="attributePriceEdit" placeholder="0.00"  value="<%=price%>">
                </div>
              </div><!--/.col-md-3-->

              <div class="col-md-3">
                <div class="form-group">
                  <label for="attributeMMTEdit">Валюта</label></br>
                  <select class="form-control input-sm" id="attributeMMTEdit" name="attributeMMTEdit">
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
                  <label for="attributeAllowUserTextEdit">Пользоватеь Может Добавить Текст</label>
                  <select class="form-control input-sm" id="attributeAllowUserTextEdit">
                    <option value="0" selected="">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div><!--/.col-md-6-->

              <div class="col-md-6">
                <div class="form-group">
                  <label for="attributeIsActiveEdit">Aктивно</label>
                  <select class="form-control input-sm" id="attributeIsActiveEdit">
                    <option value="0" selected="">No</option>
                    <option value="1">Yes</option>
                  </select>
                </div>
              </div><!--/.col-md-6-->

          </div><!-- /.col-md-9 -->
            
          <div class="col-md-3">
                <div class="col-md-12">
                  <div class="form-group">
                    <label for="attributeFeaturedImgImgEdit">Главнoe изображение</label>
                    <!-- <input type="hidden" id="attributeFeaturedImgNameEdit" name="attributeFeaturedImgName" data-filename=""> -->
                    <img id="attributeFeaturedImgImgEdit" 
                          width="195" 
                          data-dir="<?php echo base_url("application/assets"); ?>" 
                          data-hash=""
                          data-filename=""
                          data-defimgdef="<%=featured_image%>" 
                          src="<?php echo base_url($gallery_directory); ?><%='/'+featured_image%>">  
                     <button type="button" class=" form-control btn btn-primary btn-sm" data-toggle="modal" data-target="#attributeFileUploadEdit">
                        Выбрать файл
                      </button>
                  </div>
                </div>
          </div><!--/.col-md-3-->
         
         </div><!--/.row-->
      
        </div><!-- /.modal-body -->
        <!-- .modal-footer -->
        <div class="modal-footer">
          <button type="submit" name="sbmtAttributeEdit" id="sbmtAttributeEdit" class="btn btn-primary">Сохранить</button>
          <button type="button" class="btn btn-default" data-dismiss="modal">Закрыть</button>
        </div>
        <!-- /.modal-footer -->
        <?php echo form_close(); ?>
      </div>
    </div>
  </div>
  <!-- /.modal -->
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
	       <button id="confirmDelete" name="confirmDelete" type="button" class="btn btn-primary">Удалить</button>
	       <button type="button" class="btn btn-default" data-dismiss="modal">Отмена</button>
	      </div>
	      <?php echo form_close(); ?>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->
</script>

<!--
    Read the "Getting Started Guide" at http://docs.fineuploader.com/quickstart/01-getting-started.html
    if you are not yet familiar with Fine Uploader UI.
    Please see http://docs.fineuploader.com/features/styling.html for information
    on how to customize this template.
-->
<script type="text/template" id="qq-template">
    <div class="qq-uploader-selector qq-uploader qq-gallery" qq-drop-area-text="Drop files here">
        <div class="qq-total-progress-bar-container-selector qq-total-progress-bar-container">
            <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-total-progress-bar-selector qq-progress-bar qq-total-progress-bar"></div>
        </div>
        <div class="qq-upload-drop-area-selector qq-upload-drop-area" qq-hide-dropzone>
            <span class="qq-upload-drop-area-text-selector"></span>
        </div>
        <div class="qq-upload-button-selector qq-upload-button btn">
            <div>Upload a file</div>
        </div>
        <span class="qq-drop-processing-selector qq-drop-processing">
            <span>Processing dropped files...</span>
            <span class="qq-drop-processing-spinner-selector qq-drop-processing-spinner"></span>
        </span>
        <ul class="qq-upload-list-selector qq-upload-list" role="region" aria-live="polite" aria-relevant="additions removals">
            <li>
                <span role="status" class="qq-upload-status-text-selector qq-upload-status-text"></span>
                <div class="qq-progress-bar-container-selector qq-progress-bar-container">
                    <div role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" class="qq-progress-bar-selector qq-progress-bar"></div>
                </div>
                <span class="qq-upload-spinner-selector qq-upload-spinner"></span>
                <div class="qq-thumbnail-wrapper">
                    <img class="qq-thumbnail-selector" qq-max-size="120" qq-server-scale>
                </div>
                <button type="button" class="qq-upload-cancel-selector qq-upload-cancel">X</button>
                <button type="button" class="qq-upload-retry-selector qq-upload-retry">
                    <span class="qq-btn qq-retry-icon" aria-label="Retry"></span>
                    Retry
                </button>

                <div class="qq-file-info">
                    <div class="qq-file-name">
                        <span class="qq-upload-file-selector qq-upload-file"></span>
                        <span class="qq-edit-filename-icon-selector qq-btn qq-edit-filename-icon" aria-label="Edit filename"></span>
                    </div>
                    <input class="qq-edit-filename-selector qq-edit-filename" tabindex="0" type="text">
                    <span class="qq-upload-size-selector qq-upload-size"></span>
                    <button type="button" class="qq-btn qq-upload-delete-selector qq-upload-delete">
                        <span class="qq-btn qq-delete-icon" aria-label="Delete"></span>
                    </button>
                    <button type="button" class="qq-btn qq-upload-pause-selector qq-upload-pause">
                        <span class="qq-btn qq-pause-icon" aria-label="Pause"></span>
                    </button>
                    <button type="button" class="qq-btn qq-upload-continue-selector qq-upload-continue">
                        <span class="qq-btn qq-continue-icon" aria-label="Continue"></span>
                    </button>
                </div>
            </li>
        </ul>

        <dialog class="qq-alert-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">Close</button>
            </div>
        </dialog>

        <dialog class="qq-confirm-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">No</button>
                <button type="button" class="qq-ok-button-selector">Yes</button>
            </div>
        </dialog>

        <dialog class="qq-prompt-dialog-selector">
            <div class="qq-dialog-message-selector"></div>
            <input type="text">
            <div class="qq-dialog-buttons">
                <button type="button" class="qq-cancel-button-selector">Cancel</button>
                <button type="button" class="qq-ok-button-selector">Ok</button>
            </div>
        </dialog>
    </div>
</script>
