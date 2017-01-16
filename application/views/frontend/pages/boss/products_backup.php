<section class="additional-options">
    <div class="container">
        <div class="row">
            <div class="col-md-6 col-sm-6 col-xs-12 additional-img">
                <?php if ( $set['type'] == 'static' ): ?>
                      <div class="single-slider-wrap">
                        <ul id="single-slider">
                            <li data-thumb="<?php echo base_url('application/assets/img/gallery/'.$set['featured_image']); ?>">
                                <img class="img-responsive" src="<?php echo base_url('application/assets/img/gallery/'.$set['featured_image']); ?>" />
                            </li>
                        </ul>
                    </div>
                <?php elseif( $set['type'] == 'custom'): ?>

                    <!-- Left Side Set Choosable product list on Custom Mode -->
                    <div class="col-md-6">
                        <?php if (!empty($set_item_list)): ?>
                            <?php foreach ($set_item_list as $product): ?>
                                <div class="col-md-4">
                                    <a id="<?php echo 'setCustomItem_'.$product['id']; ?>" 
                                        data-prid='<?php echo $product['id']; ?>'
                                        data-prname='<?php echo $product['name']; ?>' href="#"><img class="img-responsive img-thumbnail" src="<?php echo base_url('application/assets/img/gallery/'.$product['custom_box_avatar']); ?> " alt="">
                                    </a>
                                        <p><?php echo $product['name']; ?></p>
                                        <p>Click on img To Add</p>
                                </div>
                            <?php endforeach ?>
                        <?php endif ?>
                    </div>
                <?php else: ?>
                    <h3>Продукт не найден</h3>
                <?php endif ?>

            </div>



            <div class="col-md-6 col-sm-6 col-xs-12 additional-info">
                <!-- <form action="cart/action" id="frmAddToCart" name="frmAddToCart"> -->
                <?php echo  form_open('cart/action',array('id'=>'frmAddToCart', 'name'=>'frmAddToCart'));?>
                    <input type="hidden" name="set" id="set" value="<?php echo $set['id']; ?>">
                    <input type="hidden" name="price" id="price" value="<?php echo $set['price']; ?>">
                    <input type="hidden" name="definedCount" id="definedCount" value="<?php echo $set['defined_count']; ?>">
                    <div class="product-title"><h2> <?php echo $set['name'] . " (". $set['defined_count'] ."шт.)"; ?> <span class="fa fa-star-o"></span></h2></div>
                    <div class="product-price"><p> <span id="spSetPrice"><?php echo number_format($set['price'],0,'',','); ?></span> <?php echo $set['mmt_name']; ?></p></div>
                
                    <div id="prStatusBar" role='alert'></div>
                    <div class="product-quantity">
                         <div class="input-group spinner">
                            <input type="text" id="qty" class="form-control" value="1">
                           <!--  <div class="input-group-btn-vertical">
                                <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                            </div> -->
                        </div>
                    </div>
                    <div class="add-to-cart-btn">
                        <button class="btn btn-primary" type="submit" role="button" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> В КОРЗИНУ">В КОРЗИНУ</button>
                    </div>
                   <!--  <div class="add-to-cart-btn">
                            <button type="button" class="btn btn-primary" id="load" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> В КОРЗИНУ">В КОРЗИНУ</button> -->
                    <div class="additional-desc">
                        <h3>ОПИСАНИЕ</h3>
                         <!-- Set Items -->    
                        <ul id="ulSetProducts">
                            <?php if ($set['type'] == 'static'): ?>
                                <?php if (isset($set_item_list) && $set_item_list): ?>
                                    <?php foreach ($set_item_list as $product): ?>
                                        <li data-prid="<?php echo $product['item_id']; ?>"
                                            data-prname="<?php echo $product['name']; ?>"
                                            data-prqty="<?php echo $product['qty']; ?>">
                                            <?php echo $product['name'] . ' - ' . $product['qty'] . 'шт.' ?>
                                        </li>
                                    <?php endforeach ?>
                                <?php endif  ?>
                            <?php /*elseif($set['type']=='custom'):*/ ?>
                                
                            <?php endif ?>
                        </ul>
                    </div>

                    <div class="add-options">
                        <h3>ДОБАВИТЬ ДОП. ОПЦИИ</h3>
                    </div>

                    <?php foreach ($attr_list as $key => $attrgroup): ?>

                    <?php $first_time = true; ?>
                        <?php foreach ($attrgroup as $attrs): ?>

                            <?php if ($first_time): ?>
                                <div class="postcards">
                                    <div class="slide-content">
                                        <div class="item">
                                        <?php $first_time=false;  ?>
                            <?php endif ?>
                                <div class="postcards-img">
                                    <img style="max-height: 123px;" src="<?php echo site_url('application/assets/img/gallery/'.$attrs['featured_image']); ?>">
                                    <em>
                                        <input type="radio" 
                                                name="<?php echo 'attrgroup_'.$attrs['attrgroup_id']; ?>" 
                                                class="radio" 
                                                id="<?php echo 'radio' . $attrs['attr_id']; ?>"
                                                value="<?php echo $attrs['attr_id']; ?>" 
                                                data-price="<?php echo $attrs['price']; ?>"
                                                data-textallowed="<?php echo $attrs['allow_user_text']; ?>">
                                        <label for="<?php echo 'radio' . $attrs['attr_id']; ?>"></label>
                                    </em>
                                    <p><?php echo $attrs['price'] . ' ' . $attrs['mmt_name']; ?></p>
                                </div>
                        <?php endforeach ?>
                        </div>    
                                    <h3 class="postcards-title"><?php echo $attrs['attrgroup_name']; ?></h3>
                    </div>
                </div>
                <?php endforeach ?>

                <?php form_close(); ?>
            </div>
        </div>
        <div class="text-center order-info">
            <p>* Уважаемые покупатели, заказы на индивидуальные наборы  принимаются за 3 дня. Если все же вам пирожные нужны срочно, можете позвонить по номеру 89154536686 и мы обязательно придумаем подходящий вариант для Вас!</p>
        </div>
    </div>
</section>