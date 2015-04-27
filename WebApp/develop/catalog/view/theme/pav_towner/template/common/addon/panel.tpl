<?php 
$themeName = basename( dirname(dirname(dirname(dirname(__FILE__)))) );
$t = dirname(dirname(dirname(dirname(__FILE__))));
$output = array();
if( is_dir($t.'/image/pattern/') ) {
	$files = glob($t.'/image/pattern/*.png');
	foreach( $files as $dir ){
		$output[] = str_replace(".png","",basename( $dir ) );
	}			
}
$directories = glob( DIR_TEMPLATE.$this->config->get('config_template').'/skins/*', GLOB_ONLYDIR);

 
?>

<div id="pav-paneltool" class="hidden-phone">
	<div class="pn-action pull-right"><i class="icon-cog"></i></div>
	<div class="panel-inner">
		<div>
			<h4>Panel Tool</h4>
			<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" method="post">
				<div class="group-input">
					<label>Theme</label>
					<select name="userparams[skin]">
						<option value=""><?php echo $this->language->get('default');?></option>
						<?php foreach( $directories as $skin ) {  $skin = basename($skin) ; ?>
 						<option value="<?php echo ($skin);?>" <?php if( $helper->getParam('skin') == $skin ) { ?> selected="selected" <?php } ?>><?php echo ($skin);?></option>
						<?php } ?>
					</select>
				</div>
				<div class="group-input">
					<label>Body Pattern</label>
					<div class="box-patterns clearfix">	
						<?php foreach( $output as $pattern )  { ?>
						<div class="<?php echo $pattern;?>"></div>
						<?php } ?>
					</div>
					<input name="userparams[body_pattern]" type="hidden" id="userparams_body_pattern" value="<?php echo $themeConfig['body_pattern'];?>"/>
				</div>
				
				<p>
					<button type="submit" name="btn-save" class="btn btn-small" value="Apply">Apply</button>
					<a href="<?php echo $this->url->link('common/home',"pavreset=?");?>" class="btn btn-small"><span>Reset</span></a>
				</p>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
	$("#pav-paneltool .pn-action").click( function(){
		$(this).toggleClass("open");
		$("#pav-paneltool").toggleClass('panel-open');
	} );
	$( ".box-patterns div").click( function(){
		var _c =  $("body").attr("class").replace(/pattern(\d+)/i,"");
		$("body").attr( "class", _c );
		$( ".box-patterns div").removeClass("active");
		$(this).addClass( "active" );
		$("body").addClass( $(this).attr("class") );
		$("#userparams_body_pattern").val( $(this).attr("class") );
	} );
	
	if( $("#userparams_body_pattern").val() ){
		$( ".box-patterns").find( "." + $("#userparams_body_pattern").val() ).addClass( "active" );
	}
</script>