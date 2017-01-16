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
        <?php echo $assignment_create_form; ?>
        <button id="btnCreateAssignment" type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdlCreateAssignment">Create</button>
      <?php endif ?>
      <!-- <div class="box-header with-border"></div>  -->
      <div class="box-body">
        <div id="assignmentModalDiv"></div>
        <div id="assignmentDelConfModalDiv"></div>
        <div class="table-responsive">
          <div class=""><div id="gridAssignments" ></div></div>
        </div>
      </div><!-- /.box-body -->
      
      <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
