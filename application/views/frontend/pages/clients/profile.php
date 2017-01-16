<section class="make-order user-account login">
    <div class="container">
        <div class="row">
            <div class="col-md-3 col-sm-3 col-xs-12">
                <div class="user-account-title">
                    <ul>
                        <li><a href="<?php echo site_url('profile'); ?>" class="<?php echo ($active_user_section == 'profile')?'active':'' ?>">личные данные</a></li>
                        <li><a href="<?php echo site_url('profile/orders'); ?>" class="<?php echo ($active_user_section == 'userorders')?'active':'' ?>">покупки</a></li>
                    </ul>
                </div>
            </div>
            <div class="col-md-9 col-sm-9 col-xs-12">
                 <div class="login-form">

                    <div id="updateStatus"></div>
                    <?php echo form_open("profile/clactions", array('id'=>'clientForm', 'name'=>'clientForm', 'class'=>'form', 'role'=>'form')); ?>  
                        <div class="row">
                            <input type="hidden" name="client_id" value="<?php echo $clientinfo['id']; ?>">
                            <div class="col-xs-6 col-md-6">
                                <label for="">Имя*</label>
                                <input class="form-control" name="name1" id="name1" type="text" value="<?php echo $clientinfo['fname']; ?>" />
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <label for="">Фамилия*</label>
                                <input class="form-control" name="name2" id="name2" type="text" value="<?php echo $clientinfo['sname']; ?>" />
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <label for="">Тел. Номер*</label>
                                <input class="form-control" name="phone" type="text" value="<?php echo $clientinfo['phone']; ?>"/>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <label for="">Эл-почта*</label>
                                <input class="form-control" name="email" id="email" type="email" value="<?php echo $clientinfo['email']; ?>" />
                            </div>
                            
                            <div class="col-xs-6 col-md-6">
                                <label for="">Город</label>
                                <input class="form-control" name="city" type="text" value="<?php echo (isset($cl_addr_list[0]))? $cl_addr_list[0]['city_id']:''; ?>"/>
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <label for="">Улица</label>
                                <input class="form-control" name="street" type="text" value="<?php echo (isset($cl_addr_list[0]))? $cl_addr_list[0]['street']:''; ?>" />
                            </div>
                             <div class="col-xs-6 col-md-6">
                                <label for="">Дом</label>
                                <input class="form-control" name="bld" type="text" value="<?php echo (isset($cl_addr_list[0]))? $cl_addr_list[0]['bld']:''; ?>" />
                            </div>
                            <div class="col-xs-6 col-md-6">
                                <label for="">Кв., Офис и т.д.</label>
                                <input class="form-control" name="apt" type="text" value="<?php echo (isset($cl_addr_list[0]))? $cl_addr_list[0]['apt']:''; ?>" />
                            </div>
                            <!-- <div class="col-xs-6 col-md-6">
                                <label for="">Адрес*</label>
                                <input class="form-control" name="address" type="text" value="<?php echo (isset($cl_addr_list[0]))? $cl_addr_list[0]['address']:''; ?>" />
                            </div> -->

                        </div>
                        
                        <hr>
                        
                        <div class="row">
                            <div class="col-xs-6 col-md-6">
                                <label for="password">Пароль</label>
                                <input class="form-control" name="password" id="password" type="password" />
                            </div>
                               <div class="col-xs-6 col-md-6">
                                <label for="confirmPassword">Подтвердить Пароль</label>
                                <input class="form-control" name="confirmPassword" id="confirmPassword" type="password" />
                            </div>
                        </div>

                        <div class="text-center save-btn">
                             <button class="btn btn-default" type="submit" name="btnSubmit">Сохранить</button>
                        </div>
                    <?php echo form_close(); ?>
                        


 

                </div>
            </div>
        </div>
    </div>
</section>