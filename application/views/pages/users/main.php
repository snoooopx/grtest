<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="loading">Loading&#8230;</div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"> </i> / <?php echo ucfirst($active_section); ?></a></li>
        <li class="active"><?php echo ucfirst($active_page); ?></li>
    </ol>
  </div>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box box-primary">
      <?php if ( $allow['create']): ?>
        <?php echo $user_create_form; ?>
        <button id="btnCreateUser" type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdlCreateUser">Create</button>
      <?php endif ?>
      <!-- <div class="box-header with-border"></div>  -->
      <div class="box-body">
        <div id="userModalDiv"></div>
        <div id="userDelConfModalDiv"></div>
      <div class="table-responsive"><div id="gridUsers" ></div></div>
      </div><!-- /.box-body -->
       <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
