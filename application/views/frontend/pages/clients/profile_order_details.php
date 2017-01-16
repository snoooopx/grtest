<section class="make-order user-account">
    <div class="container">
        <div class="row">
            <div class="col-md-2 col-sm-2 col-xs-12">
                <div class="user-account-title">
                   <?php echo $profile_sidebar; ?>
                </div>
            </div>
            <div class="col-md-10 col-sm-10 col-xs-12">
                <!-- info row -->
                <div class="row">
                    <div class="col-sm-4">
                        <table class="table table-condensed">
                            <tbody>
                                <tr>
                                    <td>Ордер</td>
                                    <td>#<?php echo $order['info'][0]['order_id'];?></td>
                                </tr>
                                <tr>
                                    <td>Создан</td>
                                    <td><?php echo $order['info'][0]['created']; ?></td>
                                </tr>
                                <tr>
                                    <td>Имя</td>
                                    <td><?php echo $order['info'][0]['client_name'];?></td>
                                </tr>
                                <tr>
                                    <td>Почта</td>
                                    <td><?php echo $order['info'][0]['client_email'];?></td>
                                </tr>
                                <tr>
                                    <td>Тел</td>
                                    <td><?php echo $order['info'][0]['client_phone'];?></td>
                                </tr>
                                <tr>
                                    <td>Адрес</td>
                                    <td><?php echo $order['info'][0]['shp_city'] ; ?>
                                 <?php echo $order['info'][0]['shp_street'] ; ?>
                                 <?php echo $order['info'][0]['shp_bld'] ; ?>
                                 <?php echo $order['info'][0]['shp_apt'] ; ?></td>
                                </tr>
                            </tbody>
                        </table>

                    </div>
                    <div class="col-sm-2"></div>
                    <!-- /.col -->
                    <div class="col-sm-4">
                         <table class="table table-condensed">
                            <tbody>
                                <tr>
                                    <td>Статус</td>
                                    <td><?php echo $order['info'][0]['order_status']; ?></td>
                                </tr>
                                <tr>
                                    <td>Тип платежа</td>
                                    <td><?php echo $order['info'][0]['pmt_name']; ?></td>
                                </tr>
                                <tr>
                                    <td>Метод доставки</td>
                                    <td><?php echo $order['info'][0]['shp_type']; ?></td>
                                </tr>
                                <?php if($order['info'][0]['shp_type_id'] == 1):?>
                                <tr>
                                    <td>Зона</td>
                                    <td><?php echo $order['info'][0]['shp_zone']; ?></td>
                                </tr>
                                <?php endif?>
                                <tr>
                                    <td>Период</td>
                                    <td><?php echo $order['info'][0]['shp_period']; ?></td>
                                </tr>
                            </tbody>
                        </table>
                 </div>
                 </div>
                 <hr>
                 <div class="table-content" >
                    <?php if (isset($order) && !empty($order['items']) && !empty($order['attrs']) ): ?>
                     <form method="post" action="#">
                         <table id="tblCheckout" class="products-desc">
                            <thead>
                                <th>Набор</th>
                                <th>Описание</th>
                                <th>Цена</th>
                                <th>Кол.</th>
                                <th>ОБЩАЯ ЦЕНА</th>
                            </thead>
                             <tbody>
                                 <?php $total =0; ?>
                                 <?php foreach ($order['items'] as $set): ?>
                                     <tr id="<?php echo $set['set_id'] ?>">
                                        
                                        <!-- Set name / image / attributes -->
                                        <td class="product-img">
                                            <img style="max-width:100px;" src="<?php echo site_url('application/assets/img/gallery/'.$set['featured_image']); ?>">
                                            <div class="product-name-attr">
                                                <h4><?php echo $set['set_name'] ; ?> <span class="fa fa-star-o"></span></h4>
                                                <hr>
                                                <p><u><i>Аттрибуты</i></u></p>
                                                <?php foreach ($order['attrs'] as $attrs): ?>
                                                     <?php if ($attrs['set_id']==$set['set_id']): ?>
                                                        <p class="product-attribute"><?php echo $attrs['attr_name'] . ' - ' .$attrs['unit_price'].$attrs['mmt_name']; ?></p>
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
                                            <span > <?php echo number_format($set['unit_price'],0,'',','); ?></span> 
                                        </td>
                                        <?php // Set Qty ?>
                                        <td class="product-quantity"> 
                                            <div class="input-group spinner">
                                                <span><?php echo $set['qty']; ?></span>
                                            </div>
                                            
                                        </td>

                                        <!-- Set Total Price -->
                                        <td class="product-total-price"> 
                                            <?php echo number_format($set['unit_price']*$set['qty'],0,'',','); ?>
                                        </td>
                                    </tr>   
                                     
                                    <?php $total+= $set['qty']*$set['unit_price']; ?>
                                    <?php endforeach ?>
                             </tbody>
                            
                             <tfoot>
                                 <?php 
                                    $addon2 = '';
                                    $coupon_code            = $order['info'][0]['coupon_code'];
                                    $coupon_discount_type   = $order['info'][0]['coupon_type'];
                                    $coupon_discount_value  = $order['info'][0]['coupon_discount'];
                                 
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
                                 <tr>
                                     <td colspan="3"></td>
                                     <td>Всего</td>
                                     <td><b><span> <?php echo number_format($total,0,'',',') . " руб."; ?> </span></b></td>
                                 </tr>

                                 <!-- Coupon in Footer -->
                                 <tr>
                                     <td colspan="3"></td>
                                     <td> Купон <b><span> <?php echo $coupon_code; ?> </span></b></td>
                                     <td><b><span>-<?php echo $coupon_discount_value . $addon2; ?></span></b></td>
                                 </tr>

                                 <!-- Shipping in Footer -->
                                 <tr>
                                     <td colspan="3"></td>
                                     <td> Доставка </td>
                                     <td><b><span><?php echo $order['info'][0]['shp_price'] . " руб.";?> </span></b></td>
                                 </tr>

                                 <!-- Grand Total In Footer -->
                                 <tr id="trCartGrandTotal">
                                     <td colspan="3"></td>
                                     <td>Общая сумма</td>
                                     <td><b><span><?php echo number_format($grand_total + $order['info'][0]['shp_price'],0,'',',') . " руб."; ?></span></b></td>
                                 </tr>
                             </tfoot>
                         </table>
                     
                    <?php else: ?>

                        <div class="text-center decorated-order">
                            <h3>Такого ордера нету!!!</h3>
                            <a href="<?php echo site_url('shop'); ?>">Переити В Онлайн Бутик</a>
                        </div>

                    <?php endif ?>
                </div>  
                
            </div>
        </div>
    </div>
</section>