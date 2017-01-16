<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
  <!-- Content Header (Page header) -->
  <section class="content-header">
  <div>
    <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-home"> </i> / <?php echo ucfirst($active_section); ?></a></li>
        <li class="active"><?php echo ucfirst($active_page); ?></li>
    </ol>
  </div>
    <!-- <ol class="breadcrumb">
      <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
      <li><a href="#">Examples</a></li>
      <li class="active">Blank page</li>
    </ol> -->
  </section>

  <!-- Main content -->
  <section class="content">

    <!-- Default box -->
    <div class="box box-primary">
      <!--<div class="box-header with-border">
      </div> -->
      <div class="box-body">
        <!-- left column -->
        <div id="companyCreateTemplate">
          <div class="col-md-6">
            <?php 
                if (!$company_info)
                  {
                    if ( $allow['create'])
                      {
                         echo $company_create_form_html;
                      }
                    else
                      {
                        echo $permission_550;
                      }
                  }
                else
                  {
                    echo $company_info_html;;
                  }
            ?>
          </div><!-- /.col-md-6 -->  
         </div><!-- /.box-body -->
         <!-- <div class="box-footer">
          Footer
        </div> --><!-- /.box-footer-->
      </div><!-- /.box -->
    </div><!-- company create template -->
  </section><!-- /.content -->
</div><!-- /.content-wrapper -->
