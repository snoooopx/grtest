<div class="content-wrapper"><!-- Content Wrapper. Contains page content -->

<div class="loading">Loading&#8230;</div>
<!-- Content Header (Page header) -->
<section class="content-header">
<?php if ($allow['read']): ?>
 <div>
  <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-home"></i><?php echo ucfirst($active_section); ?></a></li>
      <li><a href="<?php echo site_url($active_page); ?>"><?php echo ucfirst($active_page); ?></a></li>
      <li class="active">Profile</li>
  </ol>
</div>
</section>
<!-- Main content -->
<section class="content">
  <div class="row">
    
    <div class="col-md-9">

      <div class="nav-tabs-custom">
        <!-- profile nav tabs -->
        <ul class="nav nav-tabs">
          <!-- About Tab -->
          <li class="active"><a href="#about" id="aboutTab" data-toggle="tab" aria-expanded="true">About</a></li>
        </ul>

        <!-- tab contents -->
        <div class="tab-content">
          <!-- About tab Pane -->
          <div class="tab-pane active" id="about">
            <div class="modal-header">
              <h3 class="modal-title" style="text-align:center;background-color:lightgrey;" ><?php echo $client_profile['name']; ?></h3>
            </div>
            <!-- .modal-body -->
            <div class="modal-body">
            
            
            <div class="row">
            
            <div class="col-md-6">
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Client Abbreviation">
                <span class="input-group-addon"><i class="fa fa-hashtag" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['abbr']; ?>">
              </div>
              <br>
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Departments">
                <span class="input-group-addon"><i class="fa fa-building" aria-hidden="true"></i></span>
                <textarea
                 class="form-control"  readonly="" ><?php echo str_replace(',', ', ', $client_profile['dep_names']); ?></textarea>
              </div>
            </div>
           
           <div class="col-md-6">
            <div class="input-group" data-toggle="tooltip" data-placement="top" title="Business Sectors">
              <span class="input-group-addon"><i class="fa fa-briefcase" aria-hidden="true"></i></span>
              <textarea class="form-control"  readonly="" ><?php echo str_replace(',', ', ', $client_profile['sec_names']); ?></textarea>
              </div>
              <br>
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Contact Person">
                <span class="input-group-addon"><i class="fa fa-user" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['contact_person']; ?>">
              </div>
              <br>
            </div>

            <div class="col-md-6">
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="E-Mail">
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['email']; ?>">
              </div>
              <br>
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Phone">
                <span class="input-group-addon"><i class="fa fa-phone" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['phone']; ?>">
              </div>
               <br>
            </div>

            <div class="col-md-6">
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Address">
                <span class="input-group-addon"><i class="fa fa-map-marker" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['address']; ?>">
              </div>
              <br>
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Bank Account">
                <span class="input-group-addon"><i class="fa fa-university" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['bank_acc']; ?>">
              </div>
              <br>
            </div>

            <div class="col-md-6">
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Reg Num">
                <span class="input-group-addon"><i class="fa fa-registered" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['reg_num']; ?>">
              </div>
              <br>
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Tax Code">
                <span class="input-group-addon"><i class="fa fa-asterisk" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['tin']; ?>">
              </div>
              <br>
            </div>

            <div class="col-md-6">
              <div class="input-group" data-toggle="tooltip" data-placement="top" title="Visibility">
                <span class="input-group-addon"><i class="fa fa-eye<?php echo $client_profile['is_visible']==1 ? "": "-slash" ;?>" aria-hidden="true"></i></span>
                <input type="text" class="form-control"  readonly="" value="<?php echo $client_profile['is_visible']==1 ? "Visible in Timesheets": "Not Visible in Timesheets" ;?>">
              </div>
              <br>
            </div>
             </div><!--/.row-->
              
            </div><!-- /.modal-body -->
                
            </div><!-- /.tab-pane About-->
       <!--  <div class="box"> -->
          </div>
          <!-- /.tab-pane -->
        </div>
        <!-- /.tab-content -->
      </div>
      <!-- /.nav-tabs-custom -->
    </div>
    <!-- /.col -->
  </div>
  <!-- /.row -->

<?php else: ?>
<h1>You Don`t Have permission To View This page...</h1>
<?php endif ?>
</section>
<!-- /.content -->
</div>
<!-- /.content-wrapper -->
</div>
<!-- ./wrapper -->