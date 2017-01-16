<!-- general form elements -->
  <!-- <div class="box"> -->
    <div class="box-header with-border">
      <h3 class="box-title">Create Company</h3>
    </div>
    <!-- /.box-header -->
    <!-- form start -->
    <!-- <form role="form" action="#"> -->
    <!-- <?php print_r($upload_status); ?> -->
    <?php foreach ($insert_status as $status): ?>
      <?php echo $status; ?>
    <?php endforeach ?>
    
    <?php echo validation_errors('<p style="color:red;">','</p>') ?>
    
    <?php echo form_open_multipart('./company_insert'); ?>
      <div class="box-body">
        <div class="form-group">
          <label for="companyName">Company Name</label>
          <input type="text" class="form-control" id="companyName" name="companyName" placeholder="Company Name" required="" value="<?php echo set_value('companyName'); ?> ">
        </div>
        <div class="form-group">
          <label for="companyHead">Head of Company</label>
          <select id="companyHead" class="form-control" name="companyHead">
            <option ></option>
          <?php foreach ($user_list as $user): ?>
            <option value="<?php echo $user['id']; ?>" <?php echo set_select( 'companyHead', $user['id'] ); ?> >
            <?php echo $user['name'] . " " 
                     . $user['middle'] . " " 
                     . $user['sname'] . " - " 
                     . $user['position']; 
            ?>
             </option>
          <?php endforeach ?>
          
          </select>
        </div>
        <div class="form-group">
          <label for="companyLogo">Company Logo</label>
          <input type="file" id="companyLogo" name="companyLogo">
        </div>
      </div>
      <!-- /.box-body -->
      <div class="box-footer">
        <button type="submit" name="sbmt_company_create" class="btn btn-primary">Submit</button>
      </div>
    </form>
  </div>
  <!-- /.box -->
           