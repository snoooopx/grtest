<!-- Left side column. contains the sidebar -->
<aside class="main-sidebar">
  <!-- sidebar: style can be found in sidebar.less -->
  <section class="sidebar sidebar-collapse">
    <!-- sidebar menu: : style can be found in sidebar.less -->
    <ul class="sidebar-menu">
    	<?php foreach ($sidebar as $sec_link => $sections): ?>
      <!---->
      <!-- Section Tree -->
      <!---->
      <!-- Cheching for Section That Has no Subsections -->
      <?php if ( array_key_exists($sec_link, $sections) ): ?>
        <li class="">
          <a href="<?php echo site_url('backend/'.strtolower($sec_link)); ?>">
            <i class="<?php echo $sections['info']['section_icon']; ?>"></i> 
            <span><?php echo ucfirst($sections['info']['section_name']); ?></span> 
            <!-- <small class="label pull-right bg-green">News</small> -->
          </a>
        </li> 
      <?php continue; ?>                 
      <?php endif ?>
      <li class="treeview">
        <a href="<?php echo site_url('backend/'.strtolower($sec_link)); ?>">
          <i class="<?php echo $sections['info']['section_icon']; ?>"></i> 
          <span><?php echo ucfirst($sections['info']['section_name']); ?></span> 
          <i class="fa fa-angle-left pull-right"></i>
        </a>
        <!---->
        <!-- Sub Section Tree -->
        <!---->
        <ul class="treeview-menu">
          <?php foreach ($sections as $key=>$value): ?>
         	<?php if ( $key == 'info' ) continue; ?>
  			       <li><a href="<?php echo  site_url('backend/'.strtolower($value['subsection_link'])); ?>"><?php echo $value['subsection_name'] ?></a></li>	
          <?php endforeach ?>
  		  </ul>
      </li>
 	  <?php endforeach ?>
  </section>
</aside>
