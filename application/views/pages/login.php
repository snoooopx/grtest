<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <title>TMS | Log in</title>
  <!-- Tell the browser to be responsive to screen width -->
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <!-- Bootstrap 3.3.5 -->
  <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bootstrap/bootstrap.min.css'); ?>">
  
  <!-- Font Awesome -->
  <link rel="stylesheet" href="<?php echo base_url('application/assets/css/font-awesome/css/font-awesome.min.css'); ?>">
  <!-- Ionicons -->
  <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="<?php echo base_url('application/assets/css/dist/AdminLTE.min.css'); ?>">

  <!-- AdminLTE Skins. Choose a skin from the css/skins
       folder instead of downloading all of them to reduce the load. -->
  <link rel="stylesheet" href="<?php echo base_url('application/assets/css/dist/skins/_all-skins.min.css'); ?>">
  <!-- iCheck -->
 <!--  <link rel="stylesheet" href="../../plugins/iCheck/square/blue.css"> -->

  <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
  <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
  <!--[if lt IE 9]>
  <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
  <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
  <![endif]-->
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <div class="login-logo">

  <!-- <a href="#"><b>BDO Armenia</b>TMS</a> -->
  <a href="#"><img src="<?php echo base_url('application/assets/img/logo/logo.png'); ?>" height="60"></a>
  </div>
  
  <!-- /.login-logo -->
  <div class="login-box-body">
    <p class="login-box-msg">Sign in to start your session</p>

    <?php echo validation_errors('<p style="color:red;">','</p>'); ?>
    <?php  if( isset( $not_active ) ) echo '<p style="color:red;">' . $not_active . '</p>'; ?>
     
      <?php echo form_open(site_url("backend/users/validate_login")); ?>  
      
        <div class="form-group has-feedback">
          <input type="login" name="login" id="login" class="form-control" placeholder="Login">
          <span class="glyphicon glyphicon-user form-control-feedback"></span>
        </div>
        <div class="form-group has-feedback">
          <input type="password" name="password" id="password" class="form-control" placeholder="Password">
          <span class="glyphicon glyphicon-lock form-control-feedback"></span>
        </div>
        <div class="row">
          <!-- /.col -->
          <div class="col-xs-4">
            <button type="submit" class="btn btn-primary btn-block btn-flat">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      <?php echo form_close(); ?>
  </div>
  <!-- /.login-box-body -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="<?php echo base_url('application/assets/js/plugins/jquery.min.js'); ?>"></script>
<!-- Bootstrap -->
<script src="<?php echo base_url('application/assets/js/plugins/bootstrap.min.js'); ?>"></script>
<!-- iCheck -->
<!-- <script src="../../plugins/iCheck/icheck.min.js"></script> -->
<script>
  /*$(function () {
    $('input').iCheck({
      checkboxClass: 'icheckbox_square-blue',
      radioClass: 'iradio_square-blue',
      increaseArea: '20%' // optional
    });
  });*/
</script>
</body>
</html>
