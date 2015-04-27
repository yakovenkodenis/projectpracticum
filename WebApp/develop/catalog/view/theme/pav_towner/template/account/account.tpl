<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>

<?php if( $SPAN[0] ): ?>
	<div class="span<?php echo $SPAN[0];?>">
	<?php echo $column_left; ?>
	</div>
<?php endif; ?>

		<div class="span<?php echo $SPAN[1];?>">
			<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
		<?php } ?>
			<div id="content"><?php echo $content_top; ?>

		
    <div class="breadcrumb">
    	 <div class="breadcrumb-inner">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
    </div>
   </div>

		    <h1 class="title-category"><?php echo $heading_title; ?></h1>
			  <div class="content">
			  <h2><?php echo $text_my_account; ?></h2>
			  <div class="content">
				<ul>
				  <li><a href="<?php echo $edit; ?>"><?php echo $text_edit; ?></a></li>
				  <li><a href="<?php echo $password; ?>"><?php echo $text_password; ?></a></li>
				  <li><a href="<?php echo $address; ?>"><?php echo $text_address; ?></a></li>
				  <li><a href="<?php echo $wishlist; ?>"><?php echo $text_wishlist; ?></a></li>
				</ul>
			  </div>
			  <h2><?php echo $text_my_orders; ?></h2>
			  <div class="content">
				<ul>
				  <li><a href="<?php echo $order; ?>"><?php echo $text_order; ?></a></li>
				  <li><a href="<?php echo $download; ?>"><?php echo $text_download; ?></a></li>
				  <?php if ($reward) { ?>
				  <li><a href="<?php echo $reward; ?>"><?php echo $text_reward; ?></a></li>
				  <?php } ?>
				  <li><a href="<?php echo $return; ?>"><?php echo $text_return; ?></a></li>
				  <li><a href="<?php echo $transaction; ?>"><?php echo $text_transaction; ?></a></li>
				</ul>
			  </div>
			  <h2><?php echo $text_my_newsletter; ?></h2>
			  <div class="content">
				<ul>
				  <li><a href="<?php echo $newsletter; ?>"><?php echo $text_newsletter; ?></a></li>
				</ul>
			  </div>
		</div>  
		</div>
  <?php echo $content_bottom; ?></div>
 
	<?php if( $SPAN[2] ): ?>
	<div class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</div>
	<?php endif; ?>
<?php echo $footer; ?> 