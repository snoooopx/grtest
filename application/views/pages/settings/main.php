<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="loading">Loading&#8230;</div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"> </i> / <?php echo $allow['section_name']; ?></a></li>
        <li class="active"><?php echo $allow['subsection_name']; ?></li>
    </ol>
  </div>
  </section>
  <!-- Main content -->
  <section class="content" id="dSettings">
    <!-- Default box -->
    <div class="box box-primary">
      <!-- <div class="box-header with-border"></div>  -->
      <div class="box-body">
        <div class="row">
          <div class="col-md-3">
            
              <?php echo form_open("settings/save", array('id'=>'frmSocials', 'name'=>'frmSocials', 'class'=>'form', 'role'=>'form')); ?>  
              <h3>social</h3>
              <label>Facebook account</label>
              <input class="form-control input-sm" type="text" name="fb"  value="<?php echo $settings['fb_account']; ?>">
              <label>instagram account</label>
              <input class="form-control input-sm" type="text" name="inst" value="<?php echo $settings['inst_account']; ?>">
              <label>twitter account</label>
              <input class="form-control input-sm" type="text" name="twt"  value="<?php echo $settings['twt_account']; ?>">
              <input type="submit" class="form-control btn btn-primary" name="submitSocial" id="submitSocial">
              <!-- <button type="submit" class="form-control btn btn-primary" name="submitSocial" id="submitSocial">Сохранить</button> -->
            <?php echo form_close(); ?>
          </div>
          <div class="col-md-3">
            <?php echo form_open("settings/save", array('id'=>'frmOpening', 'name'=>'frmOpening', 'class'=>'form', 'role'=>'form')); ?>  
              <h3>Opening Hours</h3>
              <label>Описание</label>
              <textarea name="openingHours"><?php echo $settings['opening_hours']; ?></textarea>
              <button type="submit" class="form-control btn btn-primary" name="submitOpeningHours">Сохранить</button>
            <?php echo form_close(); ?>
          </div>
          <div class="col-md-3">
            
            <?php echo form_open("settings/save", array('id'=>'frmCompanyInfo', 'name'=>'frmCompanyInfo', 'class'=>'form', 'role'=>'form')); ?>  
              <h3>Info</h3>
              <label>name</label>
              <input class="form-control input-sm" type="text" name="name" value="<?php echo $settings['company_name']; ?>">
              <label>address</label>
              <input class="form-control input-sm" type="text" name="address" value="<?php echo $settings['company_address']; ?>">
              <label>phone</label>
              <input class="form-control input-sm" type="text" name="phone" value="<?php echo $settings['company_phone']; ?>">
              <label>email</label>
              <input class="form-control input-sm" type="text" name="email" value="<?php echo $settings['company_email']; ?>">
              <button type="submit" class="form-control btn btn-primary" name="submitInfo">Сохранить</button>
            <?php echo form_close(); ?>
          </div>
          <div class="col-md-3">
            
            <?php echo form_open("settings/save", array('id'=>'frmAboutUs', 'name'=>'frmAboutUs', 'class'=>'form', 'role'=>'form')); ?>  
              <h3>О Нас</h3>
              <label>Кратко</label>
              <textarea class="form-control input-sm" name="aboutUsShort"><?php echo $settings['company_aboutus_short']; ?></textarea>
              <label>Детально</label>
              <textarea class="form-control input-sm" name="aboutUsLong"><?php echo $settings['company_aboutus_long']; ?>"</textarea>
              <button type="submit" class="form-control btn btn-primary" name="submitAboutUs">Сохранить</button>
            <?php echo form_close(); ?>
          </div>
        </div>

        <hr>

        <div class="row">
          
          <?php echo form_open("settings/save", array('id'=>'frmShpZones', 'name'=>'frmShpZones', 'class'=>'form', 'role'=>'form')); ?>  
           <?php foreach ($shipping['zones'] as $key=>$zone): ?>
              <div class="col-md-3">
                  <h3>Shipping Zones</h3>
                      <h2>Зона <?php echo $key; ?></h2>
                      <label>zone name</label>
                      <input class="form-control input-sm" type="text" name="name"  value="<?php echo $zone['name']; ?>">
                      <label>Min Price</label>
                      <input class="form-control input-sm" type="text" name="minPrice"  value="<?php echo $zone['min_price']; ?>">
                      <label>Цена доставки день в день</label>
                      <input class="form-control input-sm" type="text" name="sameDayPrice"  value="<?php echo $zone['same_day_price']; ?>">
                      <label>Цена доставки на следуюший день</label>
                      <input class="form-control input-sm" type="text" name="nextDayPrice"  value="<?php echo $zone['next_day_price']; ?>">
                      <label>Описание</label>
                      <input class="form-control input-sm" type="text" name="description"  value="<?php echo $zone['description']; ?>">

                 <!--  <input type="submit" class="form-control btn btn-primary" name="submitZones" id="submitZones"> -->
              </div>
            <?php endforeach ?>
        <?php echo form_close(); ?>
          <div class="col-md-3">
            
            <?php echo form_open("settings/save", array('id'=>'frmShpTypes', 'name'=>'frmShpTypes', 'class'=>'form', 'role'=>'form')); ?>  
              <h3>Shipping Types</h3>
              <label>Название</label>
              <?php foreach ($shipping['types'] as $type): ?>
                <!-- <input class="form-control input-sm" type="text" name="name" value="<?php echo $type['type']; ?>"> -->
                <p><?php echo $type['type']; ?></p>
              <?php endforeach ?>
            <?php echo form_close(); ?>
          </div>
          <div class="col-md-3">
            <?php echo form_open("settings/save", array('id'=>'frmShpPeriods', 'name'=>'frmShpPeriods', 'class'=>'form', 'role'=>'form')); ?>  
              <h3>Shipping Periods</h3>
              <?php foreach ($shipping['periods'] as $period): ?>
                <p><?php echo $period['period']; ?></p>
              <?php endforeach ?>
            <?php echo form_close(); ?>
          </div>
          <div class="col-md-3">
            
          </div>
        </div>
      
      </div><!-- /.box-body -->
      
      <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
