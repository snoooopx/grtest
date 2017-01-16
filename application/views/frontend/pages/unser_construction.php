<!DOCTYPE html>
<html>
<head>
	<title>Under Construction</title>
	<style>
	   { margin: 0; padding: 0; }
		body {
	        background: url("<?php echo base_url('application/assets/img/gazelles.jpg'); ?>") no-repeat center center fixed; 
	        -webkit-background-size: cover;
	        -moz-background-size: cover;
	        -o-background-size: cover;
	        background-size: cover;
		}
		</style>
</head>
<body>
<?php 
	$logged_in_client = $this->session->userdata('fclient_logged_in');
	if (isset($logged_in_client)) 
	{
		echo '<a href="'.site_url('logout').'">Выйти</a>';
		echo "<h1>Hi ".$logged_in_client['email']."</h1>";
	}
	else
	{
		echo '<a href="'.site_url('login').'">Логин</a>';
		echo " | ";
		echo '<a href="'.site_url('useregistration').'">Регистрация</a>';
	}
 ?>
 <pre>
<?php print_r($logged_in_client); ?>	
</pre>
</body>
</html>