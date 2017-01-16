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
        <!-- <a class="btn btn-primary" href="./orderactions/c">Создать</a> -->
      <?php endif ?>
        <div id="orderModalDiv"></div>
        <div id="orderDelConfModalDiv"></div>
        <div class="table-responsive">
          <div class=""><div id="gridOrders" ></div></div>
        </div>
      </div><!-- /.box-body -->
      
      <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
