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
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
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
  <div class="login-content content">
		<div class="row-fluid">
			<div class="span6">
				<div class="inner">
				  <h2><?php echo $text_new_customer; ?></h2>
				  <div class="content">
					<p><b><?php echo $text_register; ?></b></p>
					<p><?php echo $text_register_account; ?></p>
					<a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a></div>
				</div>
			</div>
			<div class="span6">
				<div class="inner">
				  <h2><?php echo $text_returning_customer; ?></h2>
				  <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
					<div class="content">
					  <p><?php echo $text_i_am_returning_customer; ?></p>
					  <b><?php echo $entry_email; ?></b><br />
					  <input type="text" name="email" value="<?php echo $email; ?>" />
					  <br />
					  <br />
					  <b><?php echo $entry_password; ?></b><br />
					  <input type="password" name="password" value="<?php echo $password; ?>" />
					  <br />
					  <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
					  <br />
					  <input type="submit" value="<?php echo $button_login; ?>" class="button" />
					  <?php if ($redirect) { ?>
					  <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
					  <?php } ?>
					</div>
				</form>
				</div>
			</div>	
		</div>	
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 
</div> 
<?php if( $SPAN[2] ): ?>
<div class="span<?php echo $SPAN[2];?>">	
	<?php echo $column_right; ?>
</div>
<?php endif; ?>
<?php echo $footer; ?>