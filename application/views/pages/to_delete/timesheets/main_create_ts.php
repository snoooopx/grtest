<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
<div class="loading">Loading&#8230;</div>
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"> </i> / <?php echo ucfirst($active_section); ?></a></li>
        <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
        <li class="active" ><span id="bcrbsActionPage"></span></li>
    </ol>
  </div>
  </section>
  <!-- Main content -->
  <section class="content">
    <!-- Default box -->
    <div class="box box-primary">
      <?php if ( $allow['create']): ?>
        <!-- <a id="btnCreateTimesheet" href="./timesheet_create" class="btn btn-primary">Create</a> -->
        <!-- <button id="btnCreateTimesheet" type="button" class="btn btn-primary" data-toggle="modal" data-target="#mdlCreateTimesheet">Create</button> -->
      <?php endif ?>
      <!-- <div class="box-header with-border"></div>  -->
      <div class="box-body">
      
        <!-- dCreateTimesheet -->
        <div id="dCreateTimesheet" style="display:none;">
         
           <?php echo form_open_multipart('Timesheets/#'); ?>
                <!-- modal-body -->
                <div class="modal-body">

                 <div class="row">
                    <div id="pageStatus">
                      
                    </div>
                    <div id="dDateSelect">
                      
                    </div>
                    
        <!--             <input type="text" id="tsWeek" disabled=""> -->
                    <span id="tsWeekNo"></span>           

                    <span id="tsPeriod"></span>
                    <br/>
                    <br/>
                    
            <div class="table-responsive">
            <table id="tblTsActions" class="table table-hover table-striped table-condensed">
                 <thead>
                    <td colspan="2"> <h3>Main</h3></td>
                    <td colspan="12"></td>
                  </thead>
                  <thead id="tblTsActionsMainHeader">
                    <th style="width:10px;"><i class="fa fa-trash" aria-hidden="true"></i></th>
                    <th>Type</th>
                    <th>Project</th>
                    <th>Sub Project</th>
                    <th>Manager</th>
                    <th id="tsWDTh1" style="text-align:center;"> WD1 </th>
                    <th id="tsWDTh2" style="text-align:center;"> WD2 </th>
                    <th id="tsWDTh3" style="text-align:center;"> WD3 </th>
                    <th id="tsWDTh4" style="text-align:center;"> WD4 </th>
                    <th id="tsWDTh5" style="text-align:center;"> WD5 </th>
                    <th id="tsWDTh6" style="text-align:center; background-color:#d9edf7;"> WD6 </th>
                    <th id="tsWDTh7" style="text-align:center; background-color:#d9edf7;"> WD7 </th>
                    <th>Subtotal</th>
                    <th style="text-align:center;">Note</th>
                  </thead>
                  <tbody id="tblMainTs">
                   
                  </tbody>
                  <tr id="tblMainTsFooter">
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td style="text-align:center;"><span id="tsSubTotaltsWD1">0</span></td>
                    <td style="text-align:center;"><span id="tsSubTotaltsWD2">0</span></td>
                    <td style="text-align:center;"><span id="tsSubTotaltsWD3">0</span></td>
                    <td style="text-align:center;"><span id="tsSubTotaltsWD4">0</span></td>
                    <td style="text-align:center;"><span id="tsSubTotaltsWD5">0</span></td>
                    <td style="text-align:center; background-color:#d9edf7;"><span id="tsSubTotaltsWD6">0</span></td>
                    <td style="text-align:center; background-color:#d9edf7;"><span id="tsSubTotaltsWD7">0</span></td>
                    <td>Total: <span id="tsTotal">0</span></td>
                    <td></td>
                  </tr>

                  <tr style="background-color:white;">
                    <td colspan="14"><button id="addTsMainRow" class="btn btn-sm btn-info" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                    <!-- <td colspan="12"></td> -->
                  </tr>
                 
                  <thead >
                    <tr>
                      <td colspan="2"> <h3>Absence</h3></td>
                      <td colspan="12"></td>
                    </tr>
                  </thead>
                  <tbody id="tblAbsenceTs">
                 
                 
                  </tbody>
                  <!-- <tfoot id="tblTSAbsenceFooter"> -->
                    <tr id="tblAbsenceTsFooter">
                      <td></td>
                      <td></td>
                      <td colspan="3"></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD1">0</span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD2">0</span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD3">0</span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD4">0</span></td>
                      <td style="text-align:center;"><span id="tsSubTotaltsWD5">0</span></td>
                      <td style="text-align:center; background-color:#d9edf7;"><span id="tsSubTotaltsWD6">0</span></td>
                      <td style="text-align:center; background-color:#d9edf7;"><span id="tsSubTotaltsWD7">0</span></td>
                      <td>Total: <span id="tsTotal">0</span></td>
                    </tr>
                    <tr  style="background-color:white;">
                      <td colspan="2"><button id="addTsAbsenceRow" class="btn btn-sm btn-info" type="button"><i class="fa fa-plus" aria-hidden="true"></i></button></td>
                      <td colspan="12"></td>
                    </tr>
                  <!-- </tfoot> -->
                  
                  <tfoot>
                    <tr id="tblGrandTotalFooter" style="background-color:#E8E8EE;">
                      <td colspan="5"></td>
                      <td style="text-align:center;"><span id="tsSubGrandTotaltsWD1">0</span></td>
                      <td style="text-align:center;"><span id="tsSubGrandTotaltsWD2">0</span></td>
                      <td style="text-align:center;"><span id="tsSubGrandTotaltsWD3">0</span></td>
                      <td style="text-align:center;"><span id="tsSubGrandTotaltsWD4">0</span></td>
                      <td style="text-align:center;"><span id="tsSubGrandTotaltsWD5">0</span></td>
                      <td style="text-align:center; background-color:#d9edf7;"><span id="tsSubGrandTotaltsWD6">0</span></td>
                      <td style="text-align:center; background-color:#d9edf7;"><span id="tsSubGrandTotaltsWD7">0</span></td>
                      <td>Grand Total: <span id="tsGrandTotal">0</span></td>
                      <td></td>
                    </tr>
                  </tfoot>
                </table>

</div>






                </div><!--/.row-->

                </div><!-- /.modal-body -->
                
                <!-- modal-footer -->
                <div class="modal-footer">
                  <button name="sbmtTimesheetSave" type="button" id="sbmtTimesheetSave" class="btn btn-primary">Save</button>
                  <button name="sbmtTimesheetSaveAndSubmit" type="button" id="sbmtTimesheetSaveAndSubmit" class="btn btn-primary">Save & Submit</button>
                  <a class="btn btn-default" href="<?php echo site_url($active_page); ?>">Close</a>
                  
                  <!-- <button type="button" class="btn btn-default" data-dismiss="modal">Close</button> -->
                </div>
                <!-- /.modal-footer -->
                <?php echo form_close(); ?>

        <!-- /# dCreateTimesheet bellow div-->
        </div>

      </div><!-- /.box-body -->
      
      <div class="box-footer">
      </div> <!-- /.box-footer-->
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
