  <div class="control-sidebar-bg"></div>
  <footer class="main-footer">
  	<div class="pull-right hidden-xs">
  		<b>Version</b> 0.1
	</div>
	<strong>Copyright &copy; 2016 <a href="#">MacBaker</a>.</strong> All rights reserved.
  </footer>
 </div><!-- ./wrapper -->


    <?php 
        if ( isset( $scripts ) ) 
        {
          echo $scripts; 
        }
    ?>
      
   <!-- jQuery -->
    <script src="<?php echo base_url('application/assets/js/plugins/jquery.min.js'); ?>"></script>
    <!-- Bootstrap -->
    <script src="<?php echo base_url('application/assets/js/plugins/bootstrap.min.js'); ?>"></script>
    <!-- Notify Bootstrap -->
    <script src="<?php echo base_url('application/assets/js/plugins/bootstrap.notify.min.js'); ?>"></script>
    <!-- Underscore -->
    <script src="<?php echo base_url('application/assets/js/plugins/underscore.min.js'); ?>"></script>
    <!-- Backbone -->
    <script src="<?php echo base_url('application/assets/js/plugins/backbone.min.js'); ?>"></script>
    <!-- BackGrid -->
    <script src="<?php echo base_url('application/assets/js/plugins/backgrid.js'); ?>"></script>
    <!-- Backbone Paginator -->
    <script src="<?php echo base_url('application/assets/js/plugins/backbone.paginator.min.js'); ?>"></script>
    <!-- BackGrid Paginator -->
    <script src="<?php echo base_url('application/assets/js/plugins/backgrid.paginator.min.js'); ?>"></script>
    <!-- BackGrid Filter -->
    <script src="<?php echo base_url('application/assets/js/plugins/backgrid-filter.min.js'); ?>"></script>
    <!-- Backbone Select All -->
    <script src="<?php echo base_url('application/assets/js/plugins/backgrid-select-all.min.js'); ?>"></script>
    <!-- ColorPicker -->
    <script src="<?php echo base_url('application/assets/js/plugins/bootstrap-colorpicker.min.js'); ?>"></script>
     <!-- tinyMCE -->
    <script src="<?php echo base_url('application/assets/js/plugins/tinymce/tinymce.min.js'); ?>"></script>
    <!-- jQuery tinyMCE -->
    <script src="<?php echo base_url('application/assets/js/plugins/tinymce/jquery.tinymce.min.js'); ?>"></script>
    <!-- selectize -->
    <script src="<?php echo base_url('application/assets/js/plugins/select2/select2.full.min.js'); ?>"></script>
    <!-- Fine Uploader -->
    <script src="<?php echo base_url('application/assets/js/plugins/fine-uploader/jquery.fine-uploader.min.js'); ?>"></script>
    <script type="text/javascript">
        tinymce.baseURL = "<?php echo base_url('application/assets/js/plugins/tinymce'); ?>";
    </script>
    
    <!-- MVC -->
    <script src="<?php echo base_url('application/assets/js/app/tms_app.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/models/models.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/collections/collections.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/users.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/flavors.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/colors.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/deserts.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/products.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/attrgroups.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/attributes.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/sets.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/coupons.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/clients.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/orders.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/app/views/settings.js'); ?>"></script>

    <script src="<?php echo base_url('application/assets/js/app/router.js'); ?>"></script>
    <!-- AdminLTE App -->
    <script src="<?php echo base_url('application/assets/js/app/app.min.js'); ?>"></script>
 
    <script>
      $(document).ready( function(){
        appRouter = new App.Router();
        Backbone.history.start({ pushState: true, root: '/'+App.myRoot })
      });
    </script>
  </body>
</html>
