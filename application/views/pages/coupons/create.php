<div class="content-wrapper"><!-- Content Wrapper. Contains page content -->

<div class="loading">Loading&#8230;</div>
<!-- Content Header (Page header) -->

<?php if ($allow['read']): ?>
 <div>
  <ol class="breadcrumb">
      <!-- <li><a href="#"><i class="fa fa-home"></i><?php echo ucfirst($active_section); ?></a></li>
      <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
      <li class="active">></li> -->

      <li><a href="#"><i class="fa fa-home"></i> / <?php echo $allow['section_name']; ?></a></li>
      <li><a href="<?php echo site_url('backend/'.$active_page); ?>"><?php echo $allow['subsection_name']; ?></a></li>
      <li class="active">Действия</li>
  </ol>
</div>

<section class="content">
  <div id="couponfields1" class="box box-danger">

  </div>

<?php else: ?>
<h1>You Don`t Have permission To View This page...</h1>
<?php endif ?>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<!-- ./wrapper