     <section class="subscribe-email">
            <div class="email-form">
                <div class="input-fld">
<!--                     <h3>subscribe Email</h3>
                    <p>Get latest news  from Mac Bakery</p>
                    <div class="input-group">
                        <input type="text" class="form-control" placeholder="Enter your Email Address">
                        <span class="fa fa-envelope-o"></span>
                    </div> -->
                </div>
            </div>
            <div class="comment-form text-center">
                <div class="comment-form-img">
                    <a href="#"><img src="<?php echo base_url('application/assets/img/comment-img.png'); ?>" alt=""></a>
                    <h5>HELEN M</h5>
                </div>
                <p>“Contrary to popular belief, Lorem Ipsum is not simply random text. It has roots in a piece of classical Latin literature from 45 BC, making it over 2000 years old. Richard McClintock, a Latin ...“</p>
            </div>
        </section>
    </div>
    <div class="footer">
        <div class="container">
            <div class="row">
                <div class="col-md-4 col-sm-4 col-xs-12 about"> 
                    <h3><span></span>Контакты</h3>
                    <ul>
                        <li><em>A</em><?php echo isset($settings['company_address'])? $settings['company_address']:''  ?></li>
                        <li><em>T</em><?php echo isset($settings['company_phone'])? $settings['company_phone']:''  ?></li>
                        <li><em>M</em><a href="mailto:<?php echo isset($settings['company_email'])? $settings['company_email']:''  ?>"><?php echo isset($settings['company_email'])? $settings['company_email']:''  ?></a></li>
                    </ul>
                </div>
                <div class="col-md-4 col-sm-3 col-xs-12 opening-time"> 
                    <h3><span></span>Мы Открыты</h3>
                    <ul>
                        <li><p><?php echo isset($settings['opening_hours'])?$settings['opening_hours']:'' ?></p></li>
                    <!--     <li>mon- Fri: 8AM-10 pM</li>
                        <li>Sat- 8AM-8 pM</li>
                        <li>Sun- closet</li> -->
                    </ul>
                </div>
                <div class="col-md-2 col-sm-2 col-xs-6 social"> 
                    <h3><span></span>Соцсети</h3>
                    <ul>
                        <?php if ( isset($settings['inst_account']) && !empty($settings['inst_account'])): ?>
                            <li><a href="<?php echo $settings['inst_account']; ?>" target="_blank"><img src="<?php echo base_url('application/assets/img/social-info/instagram.png'); ?>"></a></li>
                        <?php endif ?>
                        <?php if ( isset($settings['fb_account']) && !empty($settings['fb_account'])): ?>
                            <li><a href="<?php echo $settings['fb_account']; ?>" target="_blank"><img src="<?php echo base_url('application/assets/img/social-info/fb.png'); ?>"></a></li>
                        <?php endif ?>
                        <?php if ( isset($settings['twt_account']) && !empty($settings['twt_account'])): ?>
                            <li><a href="<?php echo $settings['twt_account']; ?>" target="_blank"><img src="<?php echo base_url('application/assets/img/social-info/twitter.png'); ?>"></a></li>
                        <?php endif ?>
                    </ul>
                </div>
                <div class="col-md-2 col-sm-3 col-xs-6 account">
                    <h3><span></span>Профиль</h3>
                    <ul>
                        <li><a href="<?php echo site_url('profile'); ?>"><p>Мой Профиль</p></a></li>
                        <li><a href="<?php echo site_url('delivery'); ?>"><p>Заказ и Доставка</p></a></li>
                    </ul>
                </div>
            </div>
            <div class="text-center footer-bottom">copyright 2015 Macbaker. Design by Inna Hovhannisyan <span>all right reserved.</span></div>
        </div>
    </div>  
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
    <script>
        $.fn.bootstrapBtn = $.fn.button.noConflict();
    </script>
    <!-- JQUERy UI -->
    <script src="<?php echo base_url('application/assets/js/plugins/jq-ui/jquery-ui.min.js'); ?>"></script>
    <!-- Font Awesome -->
    <!-- <link rel="stylesheet" href="<?php echo base_url('application/assets/css/font-awesome/css/font-awesome.min.css'); ?>"> -->
    <script src="<?php echo base_url('application/assets/js/plugins/owl.carousel.min.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/plugins/lightslider.js'); ?>"></script>
    <script src="<?php echo base_url('application/assets/js/plugins/lightbox/js/lightbox.min.js'); ?>"></script>
    
    <!-- Actions JS -->
    <script src="<?php echo base_url('application/assets/js/app/actions.js'); ?>"></script>
</body>
</html>