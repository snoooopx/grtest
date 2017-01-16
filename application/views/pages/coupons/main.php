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
  <section class="content">
    <!-- Default box -->
    <div class="box box-primary">
      <!-- <div class="box-header with-border"></div>  -->
      <div class="box-body">
      <?php if ( $allow['create']): ?>
        <div id="dCreateCoupon">
        
        </div>

        <!-- <a class="btn btn-primary" href="./couponactions/c">Создать</a> -->
        <button id="btnCreateCoupon" type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdlCouponActions">Создать</button>
      <?php endif ?>
        <div id="couponModalDiv"></div>
        <div id="couponDelConfModalDiv"></div>
        <div class="table-responsive">
          <div class=""><div id="gridCoupons" ></div></div>
        </div>
      </div><!-- /.box-body -->
      
      <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
