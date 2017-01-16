 <!-- Page Content -->
    <div class="container">

        <div class="row">

           <?php echo $sidebar; ?>   

            <div class="col-md-9">

                <div class="row">
                    <?php foreach ($set_list as $set): ?>
                        <div class="col-sm-4 col-lg-4 col-md-4">
                            <div class="thumbnail">
                                <img class="img-responsive" src="<?php echo base_url('application/assets/img/gallery/'.$set['featured_image']); ?>" alt="<?php echo $set['name']; ?>">
                                <div class="caption">
                                    <h4 class="pull-right"><?php echo $set['price'] . ' ' . $set['mmt_name']; ?></h4>
                                    <p><a href="<?php echo site_url('shop/shopitem/'.$set['id']) ?>"><?php echo $set['name'] . '('.$set['defined_count'].' шт.)'; ?></a></p>
                                    <p><a href="<?php echo site_url('shop/shopitem/'.$set['id']) ?>" class="btn btn-primary">В Корзину</a></p>
                                </div>
                            </div>
                        </div>
                    <?php endforeach ?>

                </div>

            </div>

        </div>

    </div>
    <!-- /.container -->
