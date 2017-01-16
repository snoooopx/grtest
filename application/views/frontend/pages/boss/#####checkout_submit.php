 <div class="col-md-3">
        <?php echo form_open('checkout/cart/submit',array('id'=>'frmCheckout', 'name'=>'frmCheckout')) ?>
        <div class="row">
            <?php if (!$client_id): ?>
              <h5>Войдите в систему для быстрого оформление заказа или Зарегистрируйтесь</h5>
              <a href="<?php echo site_url('login'); ?>" class="btn btn-primary">Войти</a>
              <a href="<?php echo site_url('useregistration'); ?>" class="btn btn-primary">Регистрация</a>
              <h5>Или продолжать оформление заказа как гость</h5>
            <?php endif ?>

           <br/>
           <h4>Данные доставки</h4>
           <hr>
            <?php if ($client_id): ?>
                <select id="addressList">
                    <?php foreach ($client_address_list as $address): ?>
                        <option disabled="" selected="">Выбрат Адрес из списка</option>
                        <option data-fname="<?php echo $clientinfo['fname']; ?>"
                                data-sname="<?php echo $clientinfo['sname']; ?>"
                                data-address="<?php echo $address['address']; ?>"
                                data-phone="<?php echo $clientinfo['phone']; ?>"
                                data-email="<?php echo $clientinfo['email']; ?>"><?php echo $address['address']; ?></option>
                    <?php endforeach ?>
                </select>
                <br/>
                <hr>
            <?php endif ?>
               <!--  <label for="shpFname">Имя</label><br/>shp
                <input type="text" name="shpFname" id="shpFname" placeholder="Имя" value="" readonly=""><br/>
                <label for="shpSname">Фамилия</label><br/>
                <input type="text" name="shpSname" id="shpSname" placeholder="Фамилия" value="" readonly=""><br/> -->
                <label>Адрес</label><br/>  
                <input type="text" name="shpAddress" id="shpAddress"><br/>
                <label>Телефон</label><br/>
                <input type="text" name="shpPhone" id="shpPhone"><br/>
                <label>Почта</label><br/>
                <input type="text" name="shpEmail" id="shpEmail"><br/>
                <br/>

                <label>Зона доставки</label>
                <br/>
                <select name="shpZone" id="shpZone">
                    <option></option>
                    <?php foreach ($shp_zones as $zone): ?>
                        <option value="<?php echo $zone['id']; ?>" 
                                data-zominprice="<?php echo $zone['min_price']; ?>"
                                data-zsamedayprice="<?php echo $zone['same_day_price']; ?>"
                                data-znextdayprice="<?php echo $zone['next_day_price']; ?>"
                                data-description="<?php echo $zone['description'] ?>"><?php echo $zone['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <div id="shpZoneDescription"></div>
                <br/>

                <label>Дата Доставки</label><br/>
                <br/>
                <input type="text" name="shpDate" id="shpDate"><br/>
                <br/>

                <label>Время Доставки</label><br/>
                <br/>
                <select name="shpTime">
                   <?php foreach ($shp_periods as $period): ?>
                        <option value="<?php echo $period['id']; ?>" ><?php echo $period['period']; ?>
                        </option>
                <?php endforeach ?>
                </select>
                <br/>

                <label>Способ доставки</label>
                <br/>
                <select name="shpType">
                <?php foreach ($shp_types as $type): ?>
                        <option value="<?php echo $type['id']; ?>" 
                                data-iseveryday="<?php echo $type['is_everyday']; ?>"><?php echo $type['type']; ?>
                        </option>
                <?php endforeach ?>
                </select>
                <br/>

                <label>способ оплаты</label>
                <br/>
                <select name="shpPayType">
                   <?php foreach ($pay_methods as $pm): ?>
                       <option value="<?php echo $pm['id']; ?>"><?php echo $pm['name']; ?></option>
                   <?php endforeach ?>
                </select>
                <br/>

                <!-- Coupon Check and Apply -->
                 <label>Купон</label>
                 <input type="text" name="tbCoupon" id="tbCoupon" placeholder="Купон" data-toggle="tooltip" data-placement="bottom" title="asdasd">
                 <input type="button" 
                         id="btnCouponApply" 
                         value="Исползовать" 
                         tabindex="0" 
                         class="btn " 
                         data-toggle="popover" 
                         data-placement="top"
                         title="Статус" 
                         data-content="">

                <!-- SUBMIT IT -->
                <button type="submit" name="btnSbmtCart" id="btnSbmtCart" value="submit">Submit</button>
        </div>
        <?php echo form_close(); ?>
     </div>