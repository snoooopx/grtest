     <section class="make-order user-account">
            <div class="container">
                <div class="row">
                    <div class="col-md-2 col-sm-2 col-xs-12">
                        <div class="user-account-title">
                           <?php echo $profile_sidebar; ?>
                        </div>
                    </div>
                    <div class="col-md-10 col-sm-10 col-xs-12">
                        <div class="table-content">
                            <?php if (isset($orders) && !empty($orders)): ?>
                                
                            <table class="products-desc">
                                <thead>
                                    <tr>
                                        <th>Создан</th>
                                        <th>Но. Заказа</th>
                                        <th>Доставка</th>
                                        <!-- <th>КОЛ.</th> -->
                                        <th>Место доставки</th>
                                        <th>Цена</th>
                                        <th>Статус</th>
                                        <th>Подробности</th>
                                    </tr>   
                                </thead>    
                                <tbody>
                                  <?php foreach ($orders as $o): ?>
                                      <tr>
                                          <td><?php echo $o['created']; ?></td>
                                          <td><?php echo $o['order_id']; ?></td>
                                          <td>
                                              <p><?php echo $o['shp_type']; ?></p>
                                              <p>В <?php echo $o['shp_date']; ?></p>
                                              <p><?php echo $o['shp_period']; ?></p>
                                          </td>
                                          <td class="product-delivery-map"><?php echo $o['shp_city'].' '.$o['shp_street'].' '.$o['shp_bld'].' '.$o['shp_apt']; ?></td>
                                          <td class="product-price">
                                              <p><?php echo number_format($o['total']+$o['shp_price'],0,'',','); ?></p>
                                              <p><?php echo $o['coupon_code']; ?></p>
                                          </td>
                                          <td class="product-status">
                                              <?php echo $o['order_status'] ?>
                                             <!--  <span><img src="assets/img/products/hourglass.png"></span> -->
                                          </td>
                                          <td>
                                            <a href="<?php echo base_url('profile/vieworder/'.$o['id']);?>">Посмотреть</a
                                          </td>

                                      </tr>
                                  <?php endforeach ?>
                                </tbody>
                            </table> 
                        <?php else: ?>
                            <h4>Вы ешё никаких покупок не сделали</h4>
                            <a href="<?php echo site_url('shop'); ?>">Перейти в онлайн бутик</a>
                        <?php endif ?>
                        </div>
                    </div>
                </div>
                
            </div>
        </section>