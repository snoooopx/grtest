<section class="make-order">
    <div class="container">
      <div class="login-info">
    		<div class="row">
           <?php if (!$client_id): ?>
              <div class="col-md-5 col-sm-7 login-info-title">
                <h5>Войдите в систему для быстрого оформление заказа или Зарегистрируйтесь</h5>
              </div>
              <div class="col-md-5 col-sm-5">
                <a href="<?php echo base_url('useregistration');?>" class="checkin-btn">Регистрация</a>
                <a href="<?php echo base_url('login');?>" class="login-info-btn">Войти</a>
              </div>
           <?php endif ?>
        </div>
    	</div>
        <div class="row">
            <div class="col-md-8">
                <div class="loading">Loading&#8230;</div>
                <div class="table-content" >
                    <?php if (isset($cart) && !empty($cart['sets']) && !empty($cart['attrs']) ): ?>
                     <form method="post" action="#">
                         <table id="tblCheckout" class="products-desc">
                            <thead>
                                <th>Набор</th>
                                <th>Описание</th>
                                <th>Цена</th>
                                <th>Кол.</th>
                                <th>ОБЩАЯ ЦЕНА</th>
                                <th>убрать</th>
                            </thead>
                             <tbody>
                                 <?php $total =0; ?>
                                 <?php foreach ($cart['sets'] as $set): ?>
                                     <tr id="<?php echo $set['set_id'] ?>">
                                        
                                        <!-- Set name / image / attributes -->
                                        <td class="product-img">
                                            <img style="max-width:100px;" src="<?php echo site_url('application/assets/img/gallery/'.$set['featured_image']); ?>">
                                            <div class="product-name-attr">
                                                <h4><?php echo $set['set_name'] ; ?> <span class="fa fa-star-o"></span></h4>
                                                <hr>
                                                <p><u><i>Аттрибуты</i></u></p>
                                                <?php foreach ($cart['attrs'] as $attrs): ?>
                                                     <?php if ($attrs['set_id']==$set['set_id']): ?>
                                                        <p class="product-attribute"><?php echo $attrs['name'] . ' - ' .$attrs['unit_price'].$attrs['mmt_name']; ?></p>
                                                     <?php endif ?>
                                                <?php endforeach ?>
                                            </div>
                                        </td>

                                        <!-- Set Products -->
                                        <td>
                                            <ol>
                                                <?php 
                                                    $temp_items = json_decode($set['set_products'], true);
                                                    if (isset($temp_items)) {
                                                        foreach ($temp_items as $product) {
                                                        echo '<li>'.$product['name'] .'-'. $product['qty'] .'шт.'.'</li>';   
                                                        }
                                                    }
                                                ?>
                                            </ol>
                                        </td>
                                        
                                        <!-- Set Unit Price -->
                                        <td class="product-price"> 
                                            <span id="spUnitPrice" data-unitprice="<?php echo $set['unit_price']; ?>"> <?php echo number_format($set['unit_price'],0,'',','); ?></span> 
                                        </td>
                                        <?php // Set Qty ?>
                                        <td class="product-quantity"> 
                                            <div class="input-group spinner">
                                                <input type="text" id="tbQty" class="form-control" value="<?php echo $set['qty']; ?>">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default lnkChangeQtyUp" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default lnkChangeQtyDown" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            
                                        </td>

                                        <!-- Set Total Price -->
                                        <td class="product-total-price"> 
                                            <?php echo number_format($set['unit_price']*$set['qty'],0,'',','); ?>
                                        </td>
                                        <!-- Remove Item Button -->
                                        <td class="product-clear"> 
                                            <button type="button" class="csRemoveCartItem" id="btnRemoveCartItem">X</button>
                                        </td>
                                    </tr>   
                                     
                                    <?php $total+= $set['qty']*$set['unit_price']; ?>
                                    <?php endforeach ?>
                             </tbody>
                            
                             <tfoot>
                                 <?php 
                                    $is_first_time = false; 
                                    $addon2 = '';
                                    $coupon_code = '';
                                    $coupon_discount_type = '';
                                    $coupon_discount_value = '';
                                    //Getting Coupon from attributes
                                    foreach ($cart['attrs'] as $attrs) {
                                        if ( $attrs['type']=='coupon' && !$is_first_time ){
                                            $is_first_time          = true;
                                            $coupon_code            = $attrs['coupon_code'];
                                            $coupon_discount_type   = $attrs['coupon_discount_type'];
                                            $coupon_discount_value  = $attrs['coupon_discount_value'];
                                        }
                                    }
                                 
                                    $grand_total = $total;
                                    // Calculating discount if any
                                    if ($coupon_discount_type == 'percent'){
                                        $grand_total = $total* (1- $coupon_discount_value/100);
                                        $addon2 = '%';
                                    } else if($coupon_discount_type == 'fix') {
                                        $grand_total = $total - $coupon_discount_value;
                                        $addon2 = 'руб.';   
                                    }
                                  ?>
                                  
                                 <!-- Total In Footer -->
                                 <tr id="trCartTotal">
                                     <td colspan="3"></td>
                                     <td>Всего</td>
                                     <td><b><span id="spCartTotal"> <?php echo number_format($total,0,'',','); ?> </span></b></td>
                                     <td>руб.</td>
                                 </tr>

                                 <!-- Coupon in Footer -->
                                 <tr id="trCartCoupon" data-code="<?php echo $coupon_code; ?>">
                                     <td colspan="3"></td>
                                     <td> Купон <b><span id="spCartCouponCode"> <?php echo $coupon_code; ?> </span></b></td>
                                     <td><b>
                                         <span
                                             id="spCartCoupon"
                                             data-discounttype="<?php echo  $coupon_discount_type; ?>"
                                             data-discountvalue="<?php echo $coupon_discount_value; ?>"
                                             >-<?php echo $coupon_discount_value . $addon2; ?>
                                         </span>
                                         </b>
                                     </td>
                                     <td class="product-clear"><button type="button" class="csRemoveCartCoupon" id="btnRemoveCartCoupon" data-loading-text="<i class='fa fa-spinner fa-pulse fa fa-fw'></i>" autocomplete='off'>X</button></td>
                                 </tr>

                                 <!-- Shipping in Footer -->
                                 <tr id="trCartShipping">
                                     <td colspan="3"></td>
                                     <td> Доставка </td>
                                     <td><b><span id="spCartShipping" data-shippingprice='0'> </span></b></td>
                                 </tr>

                                 <!-- Grand Total In Footer -->
                                 <tr id="trCartGrandTotal">

                                     <td> <button type="button" class="btn btn-danger" id="btnClearCart" data-loading-text="Clearing..." autocomplete='off'>Очистить Корзину</button></td>
                                     <td colspan="2"></td>
                                     <td>Общая сумма</td>
                                     <td><b><span id="spCartGrandTotal"><?php echo number_format($grand_total,0,'',','); ?></span></b></td>
                                     <td>руб.</td>
                                 </tr>
                             </tfoot>
                         </table>
                     </form>
                    <?php else: ?>

                        <div class="text-center decorated-order">
                            <h3>Корзина Пуста</h3>
                            <a href="<?php echo site_url('shop'); ?>">Переити В Онлайн Бутик</a>
                        </div>

                    <?php endif ?>
                </div>  
            </div>
            <div class="col-md-4">
            <?php echo form_open('checkout/cart/submit',array('id'=>'frmCheckout', 'name'=>'frmCheckout')) ?>
           <div class="row">
           <div class="additional-info">
                <div id="chktStatusBar" role='alert'></div>
           </div>
           
           <h4>Данные доставки</h4>
           
            <?php if ($client_id): ?>
                <select id="addressList">
                    <?php foreach ($client_address_list as $address): ?>
                        <option disabled="" selected="">Выбрать Адрес из списка</option>
                        <option data-fname="<?php echo $clientinfo['fname']; ?>"
                                data-sname="<?php echo $clientinfo['sname']; ?>"
                                data-city="<?php echo $address['city_id']; ?>"
                                data-street="<?php echo $address['street']; ?>"
                                data-bld="<?php echo $address['bld']; ?>"
                                data-apt="<?php echo $address['apt']; ?>"
                                data-phone="<?php echo $clientinfo['phone']; ?>"
                                data-email="<?php echo $clientinfo['email']; ?>"><?php echo $clientinfo['email']; ?></option>
                    <?php endforeach ?>
                </select>
                <br/>
                <hr>
            <?php endif ?>
               <!--  <label for="shpFname">Имя</label><br/>shp
                <input type="text" name="shpFname" id="shpFname" placeholder="Имя" value="" readonly=""><br/>
                <label for="shpSname">Фамилия</label><br/>
                <input type="text" name="shpSname" id="shpSname" placeholder="Фамилия" value="" readonly=""><br/> -->
                <label>Способ доставки</label>
                <br/>
                <select name="shpType" id="shpType">
                <?php foreach ($shp_types as $type): ?>
                        <option value="<?php echo $type['id']; ?>" 
                                data-shtcode=<?php echo $type['shtcode']; ?>
                                data-iseveryday="<?php echo $type['is_everyday']; ?>"><?php echo $type['type']; ?>
                        </option>
                <?php endforeach ?>
                </select>
                <br/>
                <label>Имя, Фамилия</label><br/>
                <input type="text" name="shpName" id="shpName"><br/>
                <label>Телефон</label><br/>
                <input type="text" name="shpPhone" id="shpPhone"><br/>
                <label>Эл. почта</label><br/>
                <input type="text" name="shpEmail" id="shpEmail"><br/>
                <label>Город</label><br/>  
                <input type="text" name="shpCity" id="shpCity"><br/>
                <label>Улица</label><br/>  
                <input type="text" name="shpStreet" id="shpStreet"><br/>
                <label>Дом</label><br/>  
                <input type="text" name="shpBld" id="shpBld"><br/>
                <label>Кв., Офис и т.д.</label><br/>  
                <input type="text" name="shpApt" id="shpApt"><br/>
                <br/>


                <label>Зона доставки</label>
                <br/>
                <select name="shpZone" id="shpZone">
                    <option value="" data-description=""></option>
                    <?php foreach ($shp_zones as $zone): ?>
                        <option value="<?php echo $zone['id']; ?>" 
                                data-zminprice="<?php echo $zone['min_price']; ?>"
                                data-zsamedayprice="<?php echo $zone['same_day_price']; ?>"
                                data-znextdayprice="<?php echo $zone['next_day_price']; ?>"
                                data-description="<?php echo $zone['description'] ?>"><?php echo $zone['name']; ?>
                        </option>
                    <?php endforeach ?>
                </select>
                <div id="shpZoneDescription"></div>
                <br/>

                <label>Дата <span id="spLblShpDate">Доставки</span></label><br/>
                <br/>
                <input type="text" name="shpDate" id="shpDate" autocomplete="off"><br/>
                <br/>

                <label>Время <span id="spLblShpTime">Доставки</span></label><br/>
                <br/>
                <select name="shpTime">
                   <?php foreach ($shp_periods as $period): ?>
                        <option value="<?php echo $period['id']; ?>" ><?php echo $period['period']; ?>
                        </option>
                <?php endforeach ?>
                </select>
                <br/>

                <label>Способ оплаты</label>
                <br/>
                <select name="shpPayType">
                   <?php foreach ($pay_methods as $pm): ?>
                       <option value="<?php echo $pm['id']; ?>"><?php echo $pm['name']; ?></option>
                   <?php endforeach ?>
                </select>
                <br/>

                <label>Комментарий</label><br/>
                <br/>
                <input type="text" name="shpComment" id="shpComment"><br/>
                <br/>

                <!-- Coupon Check and Apply -->
                 <label>Купон</label><br>
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
                 <?php if ($client_id): ?>
                    <div class="text-center decorated-order">
                        <button type="submit" name="btnSbmtCart" id="btnSbmtCart" class="btn btn-default" type="button"  data-loading-text="<i class='fa fa-spinner fa-spin'></i> Оформление заказа" value="submit">Оформление заказа</button>
                    </div>
                 <?php endif ?>
        </div>
        <?php echo form_close(); ?>
     </div>
        </div>
        
    <div class="text-center order-info">
        <p>* Уважаемые покупатели, заказы на индивидуальные наборы  принимаются за 3 дня. Если все же вам пирожные нужны срочно, можете позвонить по номеру 89154536686 и мы обязательно придумаем подходящий вариант для Вас!</p>
    </div>
</div>

<!-- /.container -->
</section>