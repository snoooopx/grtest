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
				<a id="btnCreateTimesheet" href="./tsactions/c" class="btn btn-primary">Create</a>
				<!-- <button id="btnCreateTimesheet" type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdlCreateTimesheet">Create</button> -->
			<?php endif ?>
			<!-- <div class="box-header with-border"></div>  -->
			<div class="box-body">
				<div id="tsModalDiv"></div>
				<div id="tsDelConfModalDiv"></div>
				<div class="table-responsive">
				<div id="tsAdvancedFilters"></div>
					<div class=""><div id="gridTimesheets" ></div></div>
				</div>
			</div><!-- /.box-body -->
			<div class="box-footer">
			</div> <!-- /.box-footer-->
		</div><!-- /.box -->
	</section><!-- /.content -->
</div><!-- /.content-wrapper -->
		 
