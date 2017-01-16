<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="loading">Loading&#8230;</div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"> </i> / <?php echo ucfirst($active_section); ?></a></li>
        <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
        <li class="active">Edit Timesheet</li>
    </ol>
  </div>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box box-primary">
      <!-- <div class="box-header with-border"></div>  -->
      <div class="box-body">

        <!-- dCreateTimesheet -->
        <div id="dEditTimesheet">
         <div class="modal-body">
           <?php echo form_open_multipart('Timesheets/#'); ?>

             <div class="row">
                <label><span for class="glyphicon glyphicon-th"></span>Timesheet <span id="tsEditInfo"></span></label>
              <span id="tsPeriod"></span>
              <br>
              <!-- <label for="tsGrandTotal">Grand Total</label>
              <span id="tsGrandTotal">0</span> -->
              <br/>
              <br/>
                    <!-- main -->
              <div id="mainEditTs" class="table-responsive">
                <table id="tblEditTs" class="table table-hover table-condensed">
                  <thead>
                    <tr>
                      <td> <h3>Main</h3></td>
                      <td colspan="11"></td>
                    </tr>
                    <tr>
                      <th>Type</th>
                      <th>Project</th>
                      <th>Sub Project</th>
                      <th>Manager</th>
                      <th id="tsWDTh1" style="text-align:center;"> WD1 </th>
                      <th id="tsWDTh2" style="text-align:center;"> WD2 </th>
                      <th id="tsWDTh3" style="text-align:center;"> WD3 </th>
                      <th id="tsWDTh4" style="text-align:center;"> WD4 </th>
                      <th id="tsWDTh5" style="text-align:center;"> WD5 </th>
                      <th id="tsWDTh6" style="text-align:center;"> WD6 </th>
                      <th id="tsWDTh7" style="text-align:center;"> WD7 </th>
                      <th style="text-align:center;">Note</th>
                    </tr>
                  </thead>
                  <tbody id="tblEditTsMainBody">
                  </tbody>
                  <!-- <tfoot id="tblEditTsMainFooter"> -->
                    <tr id="tblEditTsMainFooter">
                      <td colspan="4"></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD1"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD2"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD3"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD4"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD5"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD6"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD7"> 0 </span></td>
                      <td style="text-align:right;">Total: <span id="tsTotal"> 0 </span></td>
                    </tr>
                  <!-- </tfoot> -->
                    <thead>
                      <tr>
                        <td> <h3>Absence</h3></td>
                        <td colspan="11"></td>
                      </tr>
                      <tr>
                        <th>Type</th>
                        <th colspan="3"></th>
                        <th id="tsWDTh1" style="text-align:center;"> WD1 </th>
                        <th id="tsWDTh2" style="text-align:center;"> WD2 </th>
                        <th id="tsWDTh3" style="text-align:center;"> WD3 </th>
                        <th id="tsWDTh4" style="text-align:center;"> WD4 </th>
                        <th id="tsWDTh5" style="text-align:center;"> WD5 </th>
                        <th id="tsWDTh6" style="text-align:center;"> WD6 </th>
                        <th id="tsWDTh7" style="text-align:center;"> WD7 </th>
                        <th style="text-align:center;">Note</th>
                      </tr>
                    </thead>
                    <tbody id="tblEditTsAbsenceBody">
                   
                   
                    </tbody>
                    <tfoot  id="tblEditTsAbsenceFooter">
                    <tr>
                      <td></td>
                      <td colspan="3"></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD1"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD2"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD3"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD4"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD5"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD6"> 0 </span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD7"> 0 </span></td>
                      <td style="text-align:right;">Total:<span id="tsTotal"> 0 </span></td>
                    </tr>
                    <tr>
                      <td colspan="11"></td>
                      <td style="text-align:right;">Grand Total:<span id="tsGrandTotal"> 0 </span></td>
                    </tr>
                    </tfoot>
                </table>
              </div>
                </div><!--/.row-->
        <!-- /# dCreateTimesheet bellow div-->
        </div>
      </div><!-- /.box-body -->
      <div class="box-footer">
      <!-- <button name="sbmtTimesheetSave" type="button" id="sbmtTimesheetSave" class="btn btn-primary">Save</button> -->
      <button name="sbmtTimesheetSaveAndSubmit" type="button" id="sbmtTimesheetSaveAndSubmit" class="btn btn-primary">Submit Edit</button>
      <a class="btn btn-default" href="<?php echo site_url($active_page); ?>">Close</a>
      </div> <!-- /.box-footer-->

     <?php echo form_close(); ?>
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
