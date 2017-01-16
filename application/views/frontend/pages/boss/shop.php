    <section class="menu">
            <div class="container">
                <div class="row">
                    <div class="col-md-3 sidebar-menu">
                        <ul>
                            <li>
                                <a href="<?php echo site_url('shop/sets'); ?> " class="<?php echo ($desert_type=='')? 'active':'' ?>">
                                    Все Десерты
                                </a>
                            </li>
                            <li>
                                <a href="<?php echo site_url('shop/sets/newsweets'); ?> " class="<?php echo ($desert_type=='newsweets')? 'active':'' ?>">
                                    Новинки
                                </a>
                            </li>
                            <?php 
                                  if($desert_type == ''){
                                      $active_section_name = 'Все Десерты'; 
                                  } elseif($desert_type == 'newsweets'){
                                      $active_section_name = 'Новинки'; 
                                  }
                            ?>
                            <?php foreach ($desert_types as $desert): ?>
                                <?php 
                                    // find active page and get name
                                    if ($desert_type == $desert['id']) {
                                        $active = 'active';
                                        $active_section_name = $desert['name'];
                                    } else {
                                        $active = '';
                                    }
                                 ?>
                                <li>
                                    <a href="<?php echo site_url('shop/sets/'.$desert['id']); ?> " class="<?php echo $active;?>">
                                        <?php echo $desert['name']; ?>
                                    </a>
                                </li>
                            <?php endforeach ?>
                        </ul>
                    </div>
                    <div class="col-md-9 menu-products">
                        <div>
                            <h2 class="products-title"><?php echo $active_section_name; ?></h2>

                            <?php foreach ($set_list as $set): ?>
                                <?php if ($set['type'] == 'static'): ?>
                                     <div class="col-sm-4 col-md-4">
                                        <div class="thumbnail">
                                            <div class="thumb-item-img">
                                                <img src="<?php echo base_url('application/assets/img/gallery/'.$set['featured_image']); ?>" alt="<?php echo $set['name']; ?>">
                                            </div>
                                            <div class="caption">
                                                <h4><?php echo $set['name']; ?> <span>(<?php echo $set['defined_count']; ?> шт.)</span></h4>
                                                <div class="product-price">
                                                    <!-- <div class="input-group spinner">
                                                        <input type="text" class="form-control" value="2">
                                                        <div class="input-group-btn-vertical">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                            <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                        </div>
                                                    </div>-->
                                                    <h3><?php echo number_format($set['price'],0,'',',') . ' ' . $set['mmt_name']; ?><!-- <span class="fa fa-star-o"></span> --></h3>
                                                </div>
                                                <a href="<?php echo site_url('shop/shopitem/'.$set['id']) ?>" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php elseif($set['type'] == 'custom'): ?>
                                    <div class="col-sm-4 col-md-4">
                                        <div class="thumbnail">
                                            <div class="thumb-item-img">
                                            <img src="<?php echo base_url('application/assets/img/gallery/'.$set['featured_image']); ?>" alt="<?php echo $set['name']; ?>">
                                            </div>
                                            <div class="caption">
                                                <h4><?php echo $set['name']; ?> <!--<span>(<?php echo $set['defined_count']; ?> шт.)--></span></h4>
                                                <div class="product-price">
                                                    <!-- <div class="input-group spinner">
                                                        <input type="text" class="form-control" value="2">
                                                        <div class="input-group-btn-vertical">
                                                            <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                            <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                        </div>
                                                    </div>-->
                                                    <h3><?php echo number_format($set['price'],0,'',',') . ' ' . $set['mmt_name']; ?><!-- <span class="fa fa-star-o"></span> --></h3>
                                                </div>
                                                <a href="<?php echo site_url('shop/shopitem/'.$set['id']) ?>" class="btn btn-primary" role="button">Создать</a>
                                            </div>
                                        </div>
                                    </div>
                                <?php endif ?>
                            <?php endforeach ?>

                                        <!-- <div class="col-sm-4 col-md-4 create-your-set">
                                            <div class="thumbnail">
                                                
                                                    <a href="<?php echo site_url('shop/shopitem/'.$set['id']) ?>"><img src="<?php echo base_url('application/assets/img/gallery/'.$set['featured_image']); ?>" alt=""></a>

                                                    <h2><?php echo $set['name']; ?> <span>(<?php echo $set['defined_count']; ?> шт.)</span></h2>
                                            </div>
                                                    <a href="<?php echo site_url('shop/shopitem/'.$set['id']) ?>" class="btn btn-primary" role="button">Создать</a>
                                        </div> -->







<!-- 
                            <div class="col-sm-4 col-md-4 create-your-set">
                                <div class="thumbnail">
                                    <a href=""><img src="assets/img/menu/s1.png" alt=""></a>
                                    <h2>составь свой сет</h2>
                                </div>
                            </div>
                           
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/1.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(24 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб. <span class="fa fa-star-o"></span></h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                          
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/2.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(100 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб. <span class="fa fa-star-o"></span></h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                           
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/3.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(6 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб. <span class="fa fa-star-o"></span></h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/1.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(24 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб. <span class="fa fa-star-o"></span></h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/1.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(24 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб. <span class="fa fa-star-o"></span></h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                             <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/3.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(6 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3> 50 руб. <span class="fa fa-star-o"></span></h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/1.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(24 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб.</h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-4 col-md-4">
                                <div class="thumbnail">
                                    <img src="assets/img/menu/1.png" alt="">
                                    <div class="caption">
                                        <h4>Круглые макаронс <span>(24 шт.)</span></h4>
                                        <div class="product-price">
                                            <div class="input-group spinner">
                                                <input type="text" class="form-control" value="2">
                                                <div class="input-group-btn-vertical">
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-up"></i></button>
                                                    <button class="btn btn-default" type="button"><i class="fa fa-caret-down"></i></button>
                                                </div>
                                            </div>
                                            <h3>50 руб.</h3>
                                        </div>
                                        <a href="#" class="btn btn-primary" role="button">В КОРИЗНУ</a>
                                    </div>
                                </div>
                            </div> -->
                       <!--      <div class="col-md-12 col-sm-12 text-center products-pagination">
                                 <ul>
                                    <li><a href="#">1</a></li>
                                    <li><a href="#" class="active">2</a></li>
                                    <li><a href="#">3</a></li>
                                    <li><a href="#">...</a></li>
                                </ul>
                            </div> -->
                        </div>
                    </div>
                </div>
                <div class="text-center order-info">
                    <p>* Уважаемые покупатели, заказы на индивидуальные наборы  принимаются за 3 дня. Если все же вам пирожные нужны срочно, можете позвонить по номеру 89154536686 и мы обязательно придумаем подходящий вариант для Вас!</p>
                </div>
            </div>
        </section>

   