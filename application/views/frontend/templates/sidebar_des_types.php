<div class="col-md-3 sidebar-menu">
    <ul>
    	<li>
    		<a href="<?php echo site_url('shop/sets'); ?> " class="<?php echo ($desert_type=='')? ' active ':'' ?>">
    			Все
    		</a>
    	</li>
	    <?php foreach ($desert_types as $desert): ?>
	        <li>
	        	<a href="<?php echo site_url('shop/sets/'.$desert['id']); ?> " class="<?php echo ($desert_type==$desert['id'])? ' active ':'' ?>">
	        		<?php echo $desert['name']; ?>
	       		</a>
	       	</li>
	    <?php endforeach ?>
    </ul>
</div>