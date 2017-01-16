<!-- general form elements -->
<!-- <div class="box"> -->
  <div class="box-header with-border">
    <h3 class="box-title">Company Info</h3>
  </div>
  <!-- /.box-header -->
    <div class="box-body">
      <div class="form-group">
        <!-- <label for="companyLogo">Logo</label> -->
        <img src='<?php echo base_url("application/assets/img/logo/".$company_info["logo"]); ?> '>
      </div>
      <div class="form-group">
        <label for="companyName">Name</label>
        <span class="form-control" id="companyName" name="companyName"><?php echo $company_info['name']; ?></span>
      </div>
      <div class="form-group">
        <label for="companyHead">Boss</label>
        <span class="form-control" id="companyHead" name="companyHead"><?php echo $company_info['head']; ?></span>       
      </div>
    </div>
    <!-- /.box-body -->
</div>
<!-- /.box -->
           