<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>TMS <?php //echo $active_page; ?></title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bootstrap/bootstrap.min.css'); ?>">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/font-awesome/css/font-awesome.min.css'); ?>">
    <!-- Ionicons -->
    <!-- <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css"> -->
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/dist/AdminLTE.css'); ?>">

    <!-- AdminLTE Skins. Choose a skin from the css/skins
         folder instead of downloading all of them to reduce the load. -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/dist/skins/_all-skins.min.css'); ?>">
    <!-- BackGrid CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bb/backgrid.css'); ?>">
    <!-- BackGrid Filter CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bb/backgrid-filter.min.css'); ?>">
    <!-- BackGrid Paginator CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bb/backgrid-paginator.min.css'); ?>">
    <!-- BackGrid select all min CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bb/backgrid-select-all.min.css'); ?>">
    <!-- ColorPicker CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bootstrap_col_pick/css/bootstrap-colorpicker.min.css'); ?>">
    <!-- Fine Uploader -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/js/plugins/fine-uploader/fine-uploader-gallery.css'); ?>">
    <!-- Selectize -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/js/plugins/select2/select2.min.css'); ?>">
    <!-- My CSS -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/my.css'); ?>">

    
    

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
  </head>
  <?php 
    $logged_in_user = $this->session->userdata('logged_in');

    if (isset($logged_in_user['combinedSidebar']) && $logged_in_user['combinedSidebar'] == 1) 
    {
      $sidebar = ' sidebar-collapse '; 
    }
    else
    {
      $sidebar = '';
    }
   ?>
  <body class="hold-transition skin-red sidebar-mini <?php echo $sidebar; ?>">
    <!-- Site wrapper -->
    <div class="wrapper">

      <header class="main-header">
        <!-- Logo -->
        <a href="<?php echo site_url('backend/dashboard'); ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini"><b>MB</b></span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="<?php echo base_url('application/assets/img/logo/logo.png'); ?>" height="17">&nbspMacBaker</span>
        </a>
        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
            <!-- <span class="icon-bar">pipec</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span> -->
          </a>
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <?php
                $ongoings = check_ongoing_orders();
                $news = 0;
                $inprogress =0;
                if ( isset($ongoings) && isset($ongoings['news']) && isset($ongoings['inprogress']) )
                {
                    $news =  $ongoings['news'];
                    $inprogress =$ongoings['inprogress'];
                }
              ?>
              <!-- Notifications: style can be found in dropdown.less -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <i class="fa fa-bell-o"></i>
                  <span class="label label-success">
                    <?php echo $news+$inprogress; ?>
                  </span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">
                        У вас (<?php echo $news+$inprogress; ?>) уведомления
                  </li>
                  <li>
                    <!-- inner menu: contains the actual data -->
                    <ul class="menu">
                        <!-- Pneding Orders-->
                        <!-- #################################################-->
                        <li>
                            <a href="#"><i class="fa fa-hourglass-o" aria-hidden="true"></i>&nbsp <span>Заказы Новые - </span> <?php echo $news; ?></a>
                        </li>
                        <li>
                            <a href=""><i class="fa fa-hourglass-end" aria-hidden="true"></i>&nbsp <span>Заказы В Процессе </span> <?php echo $ongoings['inprogress']; ?></a>
                        </li>
                    </ul>
                  </li>
                  <li class="footer"><a href="<?php echo site_url('backend/orders'); ?>">Посмотреть все</a></li>
                </ul>
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <span class="hidden-xs"><?php echo "Привет " . ucfirst($userinfo['login']); ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- User image -->
                  <li class="user-header">
                    <p style="font-size:1.1em">
                      <?php echo $userinfo['name'] . " " . $userinfo['middle'] . " " . $userinfo['sname']; ?>
                    </p>
                  </li>
                  <!-- Menu Footer-->
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo site_url("backend/userprofile/". $userinfo["id"]); ?>" class="btn btn-default btn-flat"><i class="fa fa-user" aria-hidden="true"></i> Профиль</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo site_url('backend/userlogout'); ?>" class="btn btn-default btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i> Выйти</a>
                    </div>
                  </li>
                </ul>
              </li>
            </ul>
          </div>
        </nav>
      </header>