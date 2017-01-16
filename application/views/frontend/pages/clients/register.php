<section class="login">
            <div class="container">
                <div class="row">
                    <h1 class="login-title">РЕГИСТРАЦИЯ</h1>
                    <?php   echo (isset($info))? '<div class="alert alert-danger" role="alert">'.$info.'</div>':'' ?>
                    <div class="col-md-8 col-sm-12 col-xs-12">
                        <?php echo form_open("useregistrationsubmit", array('id'=>'userRegister', 'name'=>'userRegister', 'class'=>'form', 'role'=>'form')); ?>
                            <div class="login-form">
                                <div class="row">
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Имя*</label>
                                        <input class="form-control" name="name1" id="name1" type="text" value="<?php echo set_value('name1'); ?>"/>
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Фамилия</label>
                                        <input class="form-control" name="name2" id="name2" type="text" value="<?php echo set_value('name2'); ?>"/>
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Эл-почта*</label>
                                        <input class="form-control" name="email" id="email" value="<?php echo set_value('email'); ?>" type="email" />
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Телефон*</label>
                                        <input class="form-control" name="phone" id="phone" value="<?php echo set_value('phone'); ?>" type="text" />
                                    </div>
                                   <!--  <div class="col-xs-6 col-md-6">
                                        <label for="">Город</label>
                                        <input class="form-control" name="city" id="city" value="<?php echo set_value('city'); ?>" type="text" />
                                    </div>
                                     <div class="col-xs-6 col-md-6">
                                        <div class="select-area">
                                           <select class="form-control" name="age" id="age">
                                                <option value="" selected="selected">Возраст</option> 
                                                <option value="001">18</option>
                                                <option value="002">19</option>
                                                <option value="002">20</option>
                                            </select>
                                            <select class="form-control" name="gender">
                                                <option value="" selected="selected">Пол</option>
                                                <option value="male">Муж</option>
                                                <option value="female">Жен</option>
                                            </select>
                                        </div>
                                    </div> -->
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Пароль*</label>
                                        <input class="form-control" name="password" id="password" type="password" />
                                    </div>
                                    <div class="col-xs-6 col-md-6">
                                        <label for="">Повторить пароль*</label>
                                        <input class="form-control" name="confirmPassword" id="confirmPassword" type="password" />
                                    </div>
                                </div>
                            </div>
                            <div class="text-center login-btn">
                                 <button class="btn btn-default" name="btnSubmit" type="submit">РЕГИСТРАЦИЯ</button>
                            </div>
                        <?php echo form_close(); ?>
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