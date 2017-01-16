 <!-- Page Content -->
    <div class="container">

        <div class="row">
            <div class="col-md-3">
                <p class="lead">Profile</p>
                <div class="list-group">
                    <a href="<?php echo site_url('profile'); ?> " class="list-group-item active">Личные данные</a>
                    <a href="<?php echo site_url('profile/orders'); ?>" class="list-group-item">Покупки</a>
                </div>
            </div>  

            <div class="col-md-9">
                <div class="row">
                    <div id="updateStatus"></div>
                </div>
                <div class="row">
                <pre>
                    <?php print_r($orders); ?>
                </pre>
                </div>
                <hr>
                
            </div>


        </div>


    </div>
    <!-- /.container -->

   