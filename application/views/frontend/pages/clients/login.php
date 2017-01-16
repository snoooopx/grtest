<section class="login">
            <div class="container">
                <div class="row">
                    <h1 class="login-title">Логин</h1>
                    <div class="col-md-8 col-sm-12 col-xs-12">
                    <?php  if( isset( $info ) ) echo '<div class="alert alert-danger" role="alert">' . $info . '</div>'; ?>
                        <?php echo form_open("logincheck", array('id'=>'loginCheck', 'name'=>'loginCheck', 'class'=>'form', 'role'=>'form')); ?>
                       <!--  <form action="logincheck" method="post" id="loginCheck" name="loginCheck" class="form" role="form"> -->
                            <div class="login-form">
                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Эл-почта*</label>
                                        <input class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" type="email" />
                                    </div>
                                   
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Пароль*</label>
                                        <input class="form-control" name="password" id="password" type="password" />
                                    </div>
                                </div>
                            </div>
                            <div class="text-center login-btn">
                                <button class="btn btn-default" name="btnSubmitLogin" id="btnSubmitLogin" type="submit">Войти</button>
                            </div>
                            <?php echo form_close(); ?>
                        <!-- </form> -->
                    </div>
                    <div class="col-md-4 col-sm-12 col-xs-12">
                        <div class="about-us">
                            <h2>О нас</h2>
                            <p><?php echo isset($settings['company_aboutus_long'])?$settings['company_aboutus_long']:'' ?></p>
                        </div>
                    </div>
                </div>
            </div>
        </section>