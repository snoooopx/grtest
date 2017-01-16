<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>bakery</title>
     <!-- Bootstrap 3.3.5 -->
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/bootstrap/bootstrap.min.css'); ?>">
    <!-- Font Awesome -->
    <!-- JQUERy UI -->
     <link rel="stylesheet" href="<?php echo base_url('application/assets/js/plugins/jq-ui/jquery-ui.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/font-awesome/css/font-awesome.min.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/owl.carousel.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/lightslider.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url('application/assets/js/plugins/lightbox/css/lightbox.min.css'); ?>">
  
    <link rel="stylesheet" href="<?php echo base_url('application/assets/css/style.css'); ?>">
</head>
<body>
    <div class="navbar-wrapper header">
        <nav class="navbar navbar-inverse navbar-static-top" role="navigation" >
            <div class="navbar-header">
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <a class="navbar-brand" href="#"><img src="<?php echo site_url('application/assets/img/logo.png'); ?>"></a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav top">
                    <li><?php echo (isset($settings['company_phone']) && !empty($settings['company_phone']))?$settings['company_phone'].'<span>|</span>':'' ?></li>
                    <li>
                        <?php 
                            $logged_in_client = $this->session->userdata('fclient_logged_in');
                            if (isset($logged_in_client)) 
                            {
                                /*'Привет <a href="' . site_url('profile') .'">'. ucfirst($logged_in_client['fname']) */
                            echo '<a href="' . site_url('profile') .'">Личный кабинет</a> &nbsp|&nbsp ' 
                                      . '<a href="' . site_url('logout')  .'">Выйти</a>'; 
                            }
                            else
                            {
                            echo '<a href="'.site_url('login').'">Логин </a>';
                            echo " | ";
                            echo '<a href="'.site_url('useregistration').'">Регистрация</a>';
                            }
                      ?>
                        <a href="<?php echo site_url('checkout/cart'); ?>" class="cart-bag">
                            <img src="<?php echo base_url('application/assets/img/social-info/bag.png'); ?>">
                             <span class="cart-order-quantity"><?php echo (isset($cart_items_count))?$cart_items_count:0 ?></span>
                        </a>
                     </li>
                </ul>
                <?php (!isset($active_page)?$active_page='':$active_page); ?>
                <ul class="nav navbar-nav">
                    <li><a href="<?php echo site_url('home'); ?>"           class="<?php echo ($active_page=='home')? 'active':'' ?>">Главная</a></li>
                    <li><a href="<?php echo site_url('shop/sets');?>"      class="<?php echo ($active_page=='shop')? 'active':'' ?>">Онлайн Бутик</a></li>
                    <li><a href="<?php echo site_url('events');?>"         class="<?php echo ($active_page=='events')? 'active':'' ?>">Мероприятия</a></li>
                    <!-- <li><a href="<?php echo site_url('');?>"              class="<?php echo ($active_page=='feedbacks')? 'active':'' ?>">Отзывы</a></li> -->
                    <li><a href="<?php echo site_url('delivery'); ?>"  class="<?php echo ($active_page=='delivery')? 'active':'' ?>">Заказ и Доставка</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div class="main">