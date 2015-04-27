<?php require( DIR_TEMPLATE . $this->config->get('config_template') . "/template/common/config.tpl" ); ?>
<?php echo $header; ?>
	<?php if( $SPAN[0] ): ?>
	<div class="span<?php echo $SPAN[0];?>">
	<?php echo $column_left; ?>
	</div>
	<?php endif; ?>
	<div class="span<?php echo $SPAN[1];?>">
		<div id="content"><?php echo $content_top; ?>
		<h1 style="display: none;"><?php echo $heading_title; ?></h1>
		<?php echo $content_bottom; ?>
		</div>
	</div>
	<?php if( $SPAN[2] ): ?>
	<div class="span<?php echo $SPAN[2];?>">	
		<?php echo $column_right; ?>
	</div>
	<?php endif; ?>
<?php echo $footer; ?>