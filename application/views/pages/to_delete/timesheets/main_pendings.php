<div class="content-wrapper"><!--Content Wrapper. Contains page content -->
<div class="loading">Loading&#8230;</div>
	<!-- Content Header (Page header) -->
	<section class="content-header">
	<div>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-home"> </i> / <?php echo ucfirst($active_section); ?></a></li>
	        <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
	        <li class="active">Pending Timesheets</li>
		</ol>
	</div>
	</section>
	<!-- Main content -->
	<section class="content">
		<!-- Default box -->
		<div class="box box-primary">
			<!-- <div class="box-header with-border"></div>  -->
			<div class="box-body">
				<!-- Div For BBJS View -->
				<div id="tsPendingsDiv">
					<!-- <button id="testbtn">press me</button> -->
<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
	<div class="table-responsive">

	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingOne">
	      <h4 class="panel-title">
	        <a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
	          Timesheets to accept <span class="badge">
          <?php 
	          if ( isset($notify) && isset($notify['pending_list']) && !empty($notify['pending_list']) )
	          {
	            	echo count($notify['pending_list']['items']);
	          }
	          else
	          {
            		echo '0';
	          }
            ?>
	            </span>
	        </a>
	      </h4>
	    </div>
    	<div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
     	 <div class="panel-body">
			<table id="tblTsPendings" class="table table-hover table-condensed" style="width:100%;">
				<thead>
					<th>#</th>
					<th>User</th>
					<th>Created</th>
					<th>Week</th>
					<th>Total</th>
					<th>Readiness</th>
					<th style="text-align:center;">Actions</th>
				</thead>
				<tbody>
				<?php if ( isset($notify) && isset($notify['pending_list']['items']) && !empty($notify['pending_list']['items']) ): ?>
					<?php foreach ($notify['pending_list']['items'] as $key => $pendings): ?>
						<?php 
							if ($pendings['readiness'] > 0)
							{
								$readiness_label = '<span class="label label-danger">Not Ready</span>';
								$readiness = false;
							}
							else
							{
								$readiness_label = '<span class="label label-success">Ready</span>';
								$readiness = true;
							}
							
						?>

						<tr id="<?php echo $pendings['id']; ;?>">
							<td id="numbering"><?php echo $key+1; ?></td>
							<td> <?php echo '<span id="tsPendingUserId" data-userid="'.$pendings['user_id'].'">'.$pendings['user'].'</span>'; ?></td>
							<td> <?php echo $pendings['created']; ?></td>
							<td> <b><?php echo $pendings['ts_year'] .' W#'.$pendings['w_no']; ?></b></td>
							<td> <?php echo $pendings['total']; ?> </td>
							<td> <?php echo $readiness_label; ?> </td>
							<td style="text-align:right; width:89px ">
								<a href="" class="btn btn-info btn-xs tsFullView" data-toggle="tooltip" data-placement="top" title="Details" name="tsFullView"><i class="fa fa-info-circle" aria-hidden="true"></i></a>
								<!-- <button class="btn btn-info btn-xs tsFullView" data-toggle="tooltip" data-placement="top" title="Details" name="tsFullView"><i class="fa fa-info-circle" aria-hidden="true"></i></button> -->
								<button class="btn btn-success btn-xs tsFullAccept" 
												data-toggle="tooltip" data-placement="top" title="Accept" 
												name="tsFullAccept"><i class="fa fa-check" aria-hidden="true"></i></button>
								<a role="button" 
									class="btn btn-danger btn-xs tsFullReject" 
									tabindex="0" 
									data-toggle="popover" 
									data-placement="top" 
									data-trigger="manual"
									title="Return To Correct" 
									data-html=true
									name="tsFullReject"
									data-content='<input type="text" class="tsFullRejectComment" name="tsFullRejectComment">
												<button class="btn btn-xs btn-success tsFullRejectAcceptBtn" name="tsFullRejectAcceptBtn"><i class="fa fa-check" aria-hidden="true"></i></button>'>
								<i class="fa fa-minus-circle" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<tr>
						<td colspan="6" style="text-align:center;">No Pending Timehseets to Accept...</td>
					</tr>
				<?php endif ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="6"></td>
					</tr>
				</tfoot>
			</table>
		 </div>
		</div>		
	</div>

	<div class="panel panel-default">
		<div class="panel-heading" role="tab" id="headingTwo">
	      <h4 class="panel-title">
	        <a class="collapsed" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
	         Projects to accept <span class="badge">
	        <?php if ( isset($notify) && isset($notify['pending_project_list']) && !empty($notify['pending_project_list']) )
	          {
	            	echo count($notify['pending_project_list']);
	          }
	          else
	          {
	          	echo '0';
	          }
	        ?>
	        	</span>
	        </a>
	      </h4>
	    </div>
	    <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
	      <div class="panel-body">
			<table id="tblTsPendings" class="table table-hover table-condensed" style="width:100%;">
				<thead>
					<th>#</th>
					<th>User</th>
					<th>Created</th>
					<th>Week</th>
					<th>Activity</th>
					<th>Proj. Code</th>
					<th>Operation</th>
					<th>Total</th>
					<th style="text-align:center;">Actions</th>
				</thead>
				<tbody>
				<?php if ( isset($notify) && isset($notify['pending_project_list']) && !empty($notify['pending_project_list']) ): ?>
					<?php foreach ($notify['pending_project_list'] as $key => $pendings): ?>
						<tr id="<?php echo $pendings['ts_id']; ;?>"
								data-tsid="<?php echo $pendings['ts_id']; ;?>"
								data-projectid="<?php echo $pendings['project_id']; ;?>"
								data-operationid="<?php echo $pendings['operation_id']; ;?>">
							<td id="numbering"><?php echo $key+1; ?></td>
							<td><?php echo '<span id="tsPendingUserId" data-userid="'.$pendings['user_id'].'">'.$pendings['fullName'].'</span>'; ?></td>
							<td> <?php echo $pendings['created']; ?></td>
							<td> <b> <?php echo $pendings['ts_year'] .' W#'.$pendings['w_no']; ?></b></td>
							<td> <?php echo $pendings['activity']; ?> </td>
							<td> <?php echo $pendings['code']; ?> </td>
							<td> <?php echo $pendings['operation']; ?> </td>
							<td> <b> <?php echo $pendings['wd1']
											+$pendings['wd2']
											+$pendings['wd3']
											+$pendings['wd4']
											+$pendings['wd5']
											+$pendings['wd6']
											+$pendings['wd7']; ?>
							</b>
							</td>
							<td style="text-align:right; width:89px ">
						
								<a tabindex="0" role="button" class="btn btn-info btn-xs tsRowView" 
												
												data-toggle="popover" 
												data-placement="top" 
												data-trigger="manual" 
												data-html="true"

												data-content='				<table class="table table-condensed table-hover table-bordered">
																<thead>
																	<th>Mo</th><th>Tu</th><th>We</th>
																	<th>Th</th><th>Fr</th><th>Sa</th><th>Su</th>
																</thead>
																<tbody>
																	<tr>
																		<td><?php echo $pendings["wd1"]; ?></td>
																		<td><?php echo $pendings["wd2"]; ?></td>
																		<td><?php echo $pendings["wd3"]; ?></td>
																		<td><?php echo $pendings["wd4"]; ?></td>
																		<td><?php echo $pendings["wd5"]; ?></td>
																		<td><?php echo $pendings["wd6"]; ?></td>
																		<td><?php echo $pendings["wd7"]; ?></td>
																	</tr>
																</tbody>
															</table>'
												name="tsRowView"><i class="fa fa-info-circle" aria-hidden="true"></i>
								</a>

								<button class="btn btn-success btn-xs tsRowAccept" 
												data-toggle="tooltip" data-placement="top" title="Accept" 
												name="tsRowAccept"><i class="fa fa-check" aria-hidden="true"></i></button>
								<a role="button" 
									tabindex="-1" class="btn btn-danger btn-xs tsRowReject" 
									data-toggle="popover" 
									data-placement="top"
									data-trigger="manual"
									title="Return To Correct"
									data-html=true
									data-content='<input type="text" class="tsRowRejectComment" name="tsRowRejectComment">
												<button class="btn btn-xs btn-success tsRowRejectAcceptBtn" name="tsRowRejectAcceptBtn"><i class="fa fa-check" aria-hidden="true"></i></button>'
												name="tsRowReject">
								<i class="fa fa-minus-circle" aria-hidden="true"></i>
								</a>
							</td>
						</tr>
					<?php endforeach ?>
				<?php else: ?>
					<tr>
						<td colspan="9" style="text-align:center;">No Pending Projects To Accept...</td>
					</tr>
				<?php endif ?>
				</tbody>
				<tfoot>
					<tr>
						<td colspan="9"></td>
					</tr>
				</tfoot>
			</table>
	      </div>
	    </div>

	</div>

<!-- 	<?php echo '################### TEST ####################'; ?>
	<pre>
	<?php print_r($notify); ?>
	</pre> -->
	</div><!-- responsive table div -->
</div>
				</div><!-- ./ #tsPendingsDiv -->
			</div><!-- /.box-body -->
			
		<div class="box-footer">
		</div> <!-- /.box-footer-->
	</div><!-- /.box -->
</section><!-- /.content -->
</div><!-- /.content-wrapper -->