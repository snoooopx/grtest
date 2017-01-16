<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="loading">Loading&#8230;</div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"> </i> / <?php echo ucfirst($active_section); ?></a></li>
        <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst(str_replace('_',' ', $active_page)); ?></a></li>
        <li class="active">Details</li>
    </ol>
  </div>
  </section>
  <!-- Main content -->
  <section class="content">
      <!-- Default box -->
      <div class="box box-primary">
      <div class="box-body">
      
        
            
        
        <?php if (isset($ts_details) || !empty($ts_details)): ?>
          <section class="content-header">

          <?php 
            $show_buttons = false;

            if ($userinfo['ceo'] == 1) 
            {
              if ($ts_owner[0]['id'] == $ts_owner[0]['head_id']) 
                $show_buttons=true;
            }
            elseif ($userinfo['head_of_dep'] == 1) 
            {
              if (($ts_owner[0]['id'] != $ts_owner[0]['head_id']) && $ts_owner[0]['head_id'] == $userinfo['id']) 
                $show_buttons=true;
            }

            
           ?>


          

            <?php if (($ts_details[0]['status_id'] == 2 && $show_buttons) || $userinfo['is_admin'] == 1 ): ?>
              <div id="tsPendingsDiv">
              <div id="actFrTSDetails">
                <a role='button' 
                  class="btn btn-success btn-xs tsFullAcceptFrDet" 
                  data-userid = <?php echo $ts_details[0]['user_id']; ?>
                  data-tsid = <?php echo $ts_details[0]['ts_id']; ?>
                  name="tsFullAcceptFrDet"><i class="fa fa-check" aria-hidden="true"></i> Accept</a>

                <a role="button" 
                  class="btn btn-danger btn-xs tsFullRejectFrDet" 
                  tabindex="0" 
                  
                  data-toggle="popover" 
                  data-placement="top" 
                  data-trigger="manual"
                  title="Return To Correct" 
                  data-html=true
                  name="tsFullRejectFrDet"
                  data-content='<input type="text" class="tsFullRejectComment" name="tsFullRejectComment">
                                <button class="btn btn-xs btn-success tsFullRejectAcceptBtnFrDet"   
                                        data-userid="<?php echo $ts_details[0]['user_id']; ?>"
                                        data-tsid="<?php echo $ts_details[0]['ts_id']; ?>"
                                        name="tsFullRejectAcceptBtnFrDet"><i class="fa fa-check" aria-hidden="true"></i></button>'>
                <i class="fa fa-minus-circle" aria-hidden="true"></i> Reject
                </a>
              </div>

              <br />
              <br />
            <?php endif ?>
          
        
          <div class="row">
            <div class="col-xs-6">
              <div class="table-responsive">
                <table id="tsUserTSInfo" class="table table-condensed table-hover" style="width:350px;">
                  <thead style="background-color:#fab387;">
                    <td colspan="4"><b>Timesheet Info</b></td>
                  </thead>
                  <tbody>
                    <tr>
                      <td><b>Timehseet  </b></td>
                      <td><?php echo $ts_details[0]['ts_year'] . ' Week #' . $ts_details[0]['w_no'] ;?></td>
                    </tr>
                     <tr>
                      <td><b>Created  </b></td>
                      <td><?php echo $ts_details[0]['created'] ;?></td>
                    </tr>
                    <tr>
                      <td><b>Last Modified  </b></td>
                      <td><?php echo $ts_details[0]['last_modified'] ;?></td>
                    </tr>
                    <tr>
                      <td><b>User  </b></td>
                      <td><?php echo $ts_details[0]['fullName'] ;?></td>
                    </tr>
                    <tr>
                      <td><b>Period  </b></td>
                      <td><?php echo  $ts_details[0]['w_start'] . ' <i class="fa fa-arrow-right" aria-hidden="true"></i> ' . $ts_details[0]['w_end'] ;?></td>
                    </tr>
                    <tr>
                      <td><b>Status  </b></td>
                      <td ><span id="tsUserTSInfoStatus"><?php echo  $ts_details[0]['status'] ;?></span></td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <div class="col-xs-6">
              <div class="table-responsive" style="max-height:200px">
                <table id="tsHistory" class="table table-condensed table-hover">
                  <thead >
                  <tr> <td colspan="5" style="background-color:#fab387;"> <span ><b>Timesheet History</b></span></td></tr>
                  <tr>
                    <th>Action Date</th>
                    <th>Type</th>
                    <th>Performer</th>
                    <th>Status</th>
                    <th>Comment</th>
                  </tr>
                  </thead>
                  <tbody>
                  <?php if ( (isset($ts_details) && !empty($ts_details)) && (isset($ts_history) || !empty($ts_history)) ): ?>

                    <?php foreach ($ts_history as $item): ?>
                        <tr>
                          <td> <?php echo $item['action_date']; ?></td>
                          <td> <?php echo $item['touched_object']; ?></td>
                          <td> <?php echo $item['performer']; ?></td>
                          <td> <?php echo $item['action']; ?></td>
                          <td> <?php echo $item['comment']; ?></td>
                        </tr>
                    <?php endforeach ?>

                  <?php endif ?>
                    
                    <tr></tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          </section>
               <?php $subtotals=[]; 
                      $subtotals['wd1'] = 0;
                      $subtotals['wd2'] = 0;
                      $subtotals['wd3'] = 0;
                      $subtotals['wd4'] = 0;
                      $subtotals['wd5'] = 0;
                      $subtotals['wd6'] = 0;
                      $subtotals['wd7'] = 0;
                      $main_total = 0;
                      $abs_total = 0;
                    ?>
          
              <!-- main -->
              <div id="mainTs" class="table-responsive">
                <table id="tblMainTs" class="table table-hover table-condensed">
                  <thead >
                  <tr>
                    <td> <h3>Main</h3></td>
                    <td colspan="13"></td>
                  </tr>
                    <th>Type</th>
                    <th>Project</th>
                    <th>Sub Project</th>
                    <th>Manager</th>
                    <th>Manager Action</th>
                    <th id="tsWDTh1" style="text-align:center;"> WD1 </th>
                    <th id="tsWDTh2" style="text-align:center;"> WD2 </th>
                    <th id="tsWDTh3" style="text-align:center;"> WD3 </th>
                    <th id="tsWDTh4" style="text-align:center;"> WD4 </th>
                    <th id="tsWDTh5" style="text-align:center;"> WD5 </th>
                    <th id="tsWDTh6" style="text-align:center;background-color:#d9edf7;"> WD6 </th>
                    <th id="tsWDTh7" style="text-align:center;background-color:#d9edf7;"> WD7 </th>
                    <th style="text-align:center;">Proj Total</th>
                    <th style="text-align:center;">Note</th>
                  </thead>
                  <tbody>
                    <?php if ($ts_details): ?>
                      <?php foreach ($ts_details as $row): ?>
                        <?php if ($row['ts_type']==1): ?>
                          <?php 
                            $subtotals['wd1'] += $row['wd1'];
                            $subtotals['wd2'] += $row['wd2'];
                            $subtotals['wd3'] += $row['wd3'];
                            $subtotals['wd4'] += $row['wd4'];
                            $subtotals['wd5'] += $row['wd5'];
                            $subtotals['wd6'] += $row['wd6'];
                            $subtotals['wd7'] += $row['wd7'];
                           ?>
                            <tr>
                              <td><?php echo $row['activity']; ?></td>

                              <?php if ( $row['activity_code'] == 'at1' || $row['activity_code'] == 'at2'): ?>
                              <td><?php echo $row['code']; ?></td>
                              <td><?php echo $row['operation']; ?></td>
                              <?php else: ?>
                              <td colspan="2"><?php echo $row['note']; ?></td>
                              
                              <?php endif ?>
                              
                              <td><?php echo $row['project_manager']; ?></td>
                              <td><?php 
                                      switch ($row['is_accepted']) {
                                        case 0:
                                          echo '<span class="label label-default">#</span>';
                                          break;
                                        case 1:
                                          echo '<span class="label label-warning">Not Accepted</span>';
                                          break;
                                        case 2:
                                          echo '<span class="label label-success">Accepted</span>';
                                          break;
                                        case 3:
                                          echo '<span class="label label-danger">Rejected</span>';
                                          break;
                                        
                                        default:
                                          echo '<span class="label label-default">#</span>';
                                          break;
                                      }
                                  ?>
                                 
                               </td>
                              <td style="text-align:center;<?php echo $row['wd1']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd1']; ?> </td>
                              <td style="text-align:center;<?php echo $row['wd2']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd2']; ?> </td>
                              <td style="text-align:center;<?php echo $row['wd3']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd3']; ?> </td>
                              <td style="text-align:center;<?php echo $row['wd4']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd4']; ?> </td>
                              <td style="text-align:center;<?php echo $row['wd5']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd5']; ?> </td>
                              <td style="text-align:center; background-color:#d9edf7; <?php echo $row['wd6']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd6']; ?> </td>
                              <td style="text-align:center; background-color:#d9edf7; <?php echo $row['wd7']>0 ? "font-weight: bold":""; ?>" > <?php echo $row['wd7']; ?> </td>
                              <td style="text-align:center;background-color:lightgrey;"><b><?php echo number_format($row['wd1']+$row['wd2']+$row['wd3']+$row['wd4']+$row['wd5']+$row['wd6']+$row['wd7'],1); ?></b></td>
                               <?php if ( $row['activity_code'] == 'at3' || $row['activity_code'] == 'at4' || $row['activity_code'] == 'at5'): ?>
                                <td></td>  
                              <?php else: ?>
                              <td style="text-align:center;"><?php echo $row['note']; ?></td>
                              <?php endif ?>      
                            </tr>
                        <?php endif ?>
                      <?php endforeach ?>
                    <?php endif ?>
                        <tr>
                          <td colspan="5"></td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd1'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd2'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd3'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd4'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd5'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd6'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($subtotals['wd7'],1); ?> </b> </td>
                          <td style="text-align:center;background-color:lightgrey;">
                            <span id="tsTotal"><b>
                            <?php 
                              for($i=1; $i<=count($subtotals); $i++ )
                              {
                                $main_total += $subtotals['wd'.$i];
                              }
                              echo number_format($main_total,1);
                            ?></b>
                            </span>
                          </td>
                          <td></td>
                        </tr>
                  </tbody>
                    <?php 
                      $abs_subtotals = [];
                      $abs_subtotals['wd1'] = 0;
                      $abs_subtotals['wd2'] = 0;
                      $abs_subtotals['wd3'] = 0;
                      $abs_subtotals['wd4'] = 0;
                      $abs_subtotals['wd5'] = 0;
                      $abs_subtotals['wd6'] = 0;
                      $abs_subtotals['wd7'] = 0; 
                    ?>
                    <thead >
                      <tr >
                        <td> <h3>Absence</h3></td>
                        <td colspan="13"></td>
                      </tr>
                      <th colspan="2">Type</th>
                      <th colspan="3"></th>
                      <th id="tsWDTh1" style="text-align:center;"> WD1 </th>
                      <th id="tsWDTh2" style="text-align:center;"> WD2 </th>
                      <th id="tsWDTh3" style="text-align:center;"> WD3 </th>
                      <th id="tsWDTh4" style="text-align:center;"> WD4 </th>
                      <th id="tsWDTh5" style="text-align:center;"> WD5 </th>
                      <th id="tsWDTh6" style="text-align:center;background-color:#d9edf7;"> WD6 </th>
                      <th id="tsWDTh7" style="text-align:center;background-color:#d9edf7;"> WD7 </th>
                      <th style="text-align:center;">Abs. Total</th>
                      <th style="text-align:center;">Note</th>
                    </thead>
                    <tbody>
                    <?php if ($ts_details): ?>
                      <?php foreach ($ts_details as $row): ?>
                        <?php if ($row['ts_type']==2): ?>
                          <?php 
                            $abs_subtotals['wd1'] += $row['wd1'];
                            $abs_subtotals['wd2'] += $row['wd2'];
                            $abs_subtotals['wd3'] += $row['wd3'];
                            $abs_subtotals['wd4'] += $row['wd4'];
                            $abs_subtotals['wd5'] += $row['wd5'];
                            $abs_subtotals['wd6'] += $row['wd6'];
                            $abs_subtotals['wd7'] += $row['wd7'];
                           ?>
                            <tr>
                              <td colspan="2"><?php echo $row['absence']; ?></td>
                              <td colspan="3"></td>
                              <td style="text-align:center;<?php echo $row['wd1']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd1']; ?> </b> </td>
                              <td style="text-align:center;<?php echo $row['wd2']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd2']; ?> </b> </td>
                              <td style="text-align:center;<?php echo $row['wd3']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd3']; ?> </b> </td>
                              <td style="text-align:center;<?php echo $row['wd4']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd4']; ?> </b> </td>
                              <td style="text-align:center;<?php echo $row['wd5']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd5']; ?> </b> </td>
                              <td style="text-align:center; background-color:#d9edf7;<?php echo $row['wd6']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd6']; ?> </b> </td>
                              <td style="text-align:center; background-color:#d9edf7;<?php echo $row['wd7']>0 ? "font-weight: bold":""; ?>"> <?php echo $row['wd7']; ?> </b> </td>
                              <td style="text-align:center;background-color:lightgrey;"><b><?php echo number_format($row['wd1']+$row['wd2']+$row['wd3']+$row['wd4']+$row['wd5']+$row['wd6']+$row['wd7'],1); ?></b></td>
                              <td><?php echo $row['note']; ?></td>
                            </tr>
                        <?php endif ?>
                      <?php endforeach ?>
                    <?php endif ?>
                    <tr>
                      <td colspan="5"></td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($abs_subtotals['wd1'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd2'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd3'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd4'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd5'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd6'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd7'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;">
                        <span id="tsTotal"><b>
                        <?php 
                            for($i=1; $i<=count($abs_subtotals); $i++ )
                            {
                              $abs_total += $abs_subtotals['wd'.$i];
                            }
                            echo number_format($abs_total,1);
                        ?></b>
                        </span>
                      </td>
                      <td></td>
                    </tr>
                    <tr>
                      <td colspan="14"></td>
                    </tr>
                    <tr>
                      <td colspan="5"></td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format($abs_subtotals['wd1']+$subtotals['wd1'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd2']+$subtotals['wd2'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd3']+$subtotals['wd3'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd4']+$subtotals['wd4'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd5']+$subtotals['wd5'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd6']+$subtotals['wd6'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"> <b> <?php echo number_format( $abs_subtotals['wd7']+$subtotals['wd7'],1); ?> </b> </td>
                      <td style="text-align:center;background-color:lightgrey;"><span>Grand Total: </span><b><?php echo number_format($main_total + $abs_total,1); ?></b></td>
                      <td></td>
                      
                    </tr>
                    </tbody>
                </table>
              </div>
         
           <?php endif ?>

          </div>
          <!-- /.row -->
          <?php if ($ts_details[0]['status_id'] ==2): ?>
            </div>
            <!-- ./tsPendingsDiv -->
          <?php endif ?>
        </div>
        <!-- box-body-->
      </div>
      <!-- box -->


      <div class="row">

      </div><!-- /.row -->

      </div><!-- /.box-body -->
      
      <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
