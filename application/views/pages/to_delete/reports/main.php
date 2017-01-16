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
      <!-- <div class="box-header with-border"></div>  -->
      <div id="dReports">
        <div class="box-body">
          <div class="row">
            <div class="col-md-6">
              <div class="form-group">
                <div class="radio">
                  <label for="rdAdvancedFilter2">
                    <input type="radio" value="uc" class="rdAdvancedFilter" name="rdAdvancedFilter" id="rdAdvancedFilter2"> User Client
                  </label>
                  <br />
                  <label for="rdAdvancedFilter3">
                    <input type="radio" value="cu" class="rdAdvancedFilter" name="rdAdvancedFilter" id="rdAdvancedFilter3"> Client User 
                  </label>
                  <br/>
                  <label for="rdAdvancedFilter4">
                    <input type="radio" value="mx" class="rdAdvancedFilter" name="rdAdvancedFilter" id="rdAdvancedFilter4"> Matrix
                  </label>
                </div>
              </div>
            </div>
          </div>

          <!-- User Client Report Block -->
          <div id="ucBlock" style="display:none;">
            <div class="">
              <div>
                <!-- Select user -->
                <label for="fltrUserUC">User</label>
                <select id="fltrUserUC" class="fltrSearchable">
                  <option value="00" selected="" disabled="">Select User</option>
                  <?php if (isset($user_list) && !empty($user_list)): ?>
                    <?php foreach ($user_list as $user): ?>
                      <option value="<?php echo $user['id']; ?>"> <?php echo $user['name'] .' '. $user['middle'] .' '. $user['sname']; ?></option>
                    <?php endforeach ?>
                  <?php endif ?>
                </select>
                <br />
              </div>
              
              <br />

              <div>
              <!-- Select Client -->
               <label for="fltrClientUC">Client</label>
               <select id="fltrClientUC" class="fltrSearchable">
                <option value="00">Select Client</option>
               </select>
              </div>
            </div>
            
            <br />
             <div class="row">
               <div class="col-md-6">
                <div class="form-group">
                  <label>Period</label>
                  <div class="input-group input-daterange fltrFromToDatePicer col-xs-8">
                      <input type="text" id="fltrFromUC" name="" class="fltrFromToDatePicer form-control col-xs-8" >
                      <span class="input-group-addon">to</span>
                      <input type="text" id="fltrToUC" name="" class="fltrFromToDatePicer form-control col-xs-8">
                  </div>
                </div>
               </div>
              </div>
              
              <div class="row">
                <div class="col-md-3">
                  <button id="repGenUC">Generate</button>
                </div>
              </div>
              <br/>
              
              <table class="table table-hover" id="tblRepUserClient">
                <thead>
                  <th>User</th>
                  <th>Client</th>
                  <th>Project</th>
                  <th>Time (hours)</th>
                </thead>
                <tbody>

                </tbody>
              </table>

            </div><!-- /.ucBlock -->
            
            <!-- Client User Report Block -->
            <div id="cuBlock"  style="display:none;">
            <div class="">

              <!-- Select Client -->
              <div >
                <label for="fltrClientCU">Client</label>
                <select id="fltrClientCU" class="fltrSearchable">
                  <option value="00" selected="">Select Client</option>
                  <?php if (isset($client_list) && !empty($client_list)): ?>
                    <?php foreach ($client_list as $client): ?>
                      <option value="<?php echo $client['id']; ?>"> <?php echo $client['fullName'] . ' | '.$client['abbr']; ?></option>
                    <?php endforeach ?>
                  <?php endif ?>
                </select>
              </div>
              <br/>
              <!-- Select User -->
              <div >
                <label for="fltrUserCU">User</label>
                <select id="fltrUserCU" class="fltrSearchable">
                  <option value="00" selected="" disabled="">Select User</option>
                </select>
              </div>
            
            </div>
            <br/>
            <div class="row">
             <div class="col-md-6">
              <div class="form-group">
                <label>Period</label>
                <div class="input-group input-daterange fltrFromToDatePicer col-xs-8">
                    <input type="text" id="fltrFromCU" name="" class="fltrFromToDatePicer form-control col-xs-8" >
                    <span class="input-group-addon">to</span>
                    <input type="text" id="fltrToCU" name="" class="fltrFromToDatePicer form-control col-xs-8">
                </div>
              </div>
              </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <button id="repGenCU">Generate</button>
              </div>
            </div>
            <br />
            <table class="table table-hover" id="tblRepClientUser">
              <thead>
                <th>Client</th>
                <th>Project</th>
                <th>User</th>
                <th>Time (hours)</th>
              </thead>
              <tbody>
                
              </tbody>
            </table>
          </div>

        

        <!-- matrix  -->
         <div id="mxBlock"  style="display:none;">
            <div class="row">
              <div class="col-md-3">
                <label>Assignment</label>
                <select id="fltrAssignmentMX" class="fltrSearchable">
                  <option value="00">Select Assignment</option>
                  <?php foreach ($ass_list as $ass): ?>
                    <option value="<?php echo $ass['id']; ?>"> <?php echo $ass['name']; ?></option>
                  <?php endforeach ?>
                </select>
              </div>
            </div>
            <br/>
            
            <div class="row">
             <div class="col-md-6">
              <div class="form-group">
                <label>Period</label>
                <div class="input-group input-daterange fltrFromToDatePicer col-xs-8">
                    <input type="text" id="fltrFromMX" name="" class="fltrFromToDatePicer form-control col-xs-8" >
                    <span class="input-group-addon">to</span>
                    <input type="text" id="fltrToMX" name="" class="fltrFromToDatePicer form-control col-xs-8">
                </div>
              </div>
             </div>
            </div>
            <div class="row">
              <div class="col-md-3">
                <button id="repGenMX">Generate</button>
              </div>
              <div class="col-md-3">
              <label>Export to: </label>
              <a class="btn" id="exportExcel" href="#"><i class="fa fa-file-excel-o" aria-hidden="true"></i></a>
            </div>
            </div>
            <br />
            <div class="table-responsive">
              <table id="tblMatrix" class="table table-bordered table-striped table-condensed">
                <thead>
                  
                </thead>
                <tbody>
                  
                </tbody>
              </table>
            </div>


         </div><!-- ./ mxblock -->
        </div><!-- /.box-body -->

        <div class="box-footer">
        </div> <!-- /.box-footer-->
      </div>
    </div><!-- /.box -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
     
