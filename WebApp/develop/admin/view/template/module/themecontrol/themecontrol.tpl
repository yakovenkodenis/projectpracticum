<?php echo $header; ?>

<?php 
	$modules_tpl = '';
 
	$modules_tpl2 = dirname(__FILE__).'/modules_'.trim($this->getTheme()).'.tpl';
	$modules_tpl1 = DIR_TEMPLATE.'module/themecontrol/modules.tpl';
	$modules_tpl3 = DIR_CATALOG.'view/theme/'.$this->getTheme().'/template/common/admin/modules.tpl';
	if( file_exists($modules_tpl3) ){
		 $modules_tpl = $modules_tpl3;
	} else if( file_exists($modules_tpl2) ){
		$modules_tpl = $modules_tpl2;
	}elseif( file_exists($modules_tpl1) ){
		$modules_tpl = $modules_tpl1;
	} 


?>
<div id="content">
<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="sform">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box"  id="themepanel">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
	  
      <div class="buttons">
	  <a class="button button-action btn-save" rel=""><?php echo $button_save; ?></a>
	  <a id="button_save_keep" class="button button-action" rel="save-edit"><?php echo $button_save_keep; ?></a>
	  
	  <a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
		
	 <div class="entry-theme">
		<b class="label"> <?php echo $this->getLang("text_default_theme");?></b>
		<select name="themecontrol[default_theme]">
			<?php foreach( $templates as $template ): ?>
			<?php  $selected= $template == $module['default_theme']?'selected="selected"':'';	?>
			<option value="<?php echo $template;?>" <?php echo $selected; ?>><?php echo $template; ?></option>
			<?php endforeach; ?>
		</select>
		
		 - <a rel="" class="green" href="http://www.pavothemes.com/guides/<?php echo $module['default_theme']?>" id="btn-guide"><?php echo $this->language->get('UserGuide');?></a>

		<?php if( isset($first_installation) )  { ?>
			<div class="label" style="float:right"><?php echo $this->language->get("text_first_installation"); ?></div>
		<?php } ?>
	  </div>
	  
		<div class="ibox">
      
	  
	
		 <div id="tabs" class="htabs">
			
			<a href="#tab-general"><?php echo $tab_general; ?></a>
			<a href="#tab-pages-layout"><?php echo $this->language->get('tab_modules_pages');?></a>
			<a href="#tab-font"><?php echo $tab_font; ?></a>
			<?php if(  $modules_tpl ){ ?>
			<a href="#tab-imodules"><?php echo $this->language->get('tab_internal_modules');?></a>
			<?php } ?>
			<a href="#tab-modules"><?php echo $this->language->get('tab_modules_layouts');?></a>
			<?php if( isset($samples) )  { ?>
			<a href="#tab-datasample"><?php echo $this->language->get('tab_datasample');?></a>
			<?php } ?>
			<a href="#tab-customcode"><?php echo $this->language->get('tab_customcode');?></a>
			<a href="#tab-support"><?php echo $this->language->get('tab_information'); ?> </a>
		 </div>
		 <input type="hidden" name="themecontrol[layout_id]" value="1">
		  <input type="hidden" name="themecontrol[position]" value="1">


		<div id="tab-contents">
				
				<div id="tab-pages-layout">
		  			 
	  				 <div id="my-tab-pageslayout" class="vtabs">
	  					<a href="#tab-pcategory" onclick="return false;">Category</a>
	  					<a href="#tab-pproduct" onclick="return false;">Product</a>
	  					<a href="#tab-pcontact" onclick="return false;">Contact</a>
	  				 </div> 
	  				 <div class="page-tabs-wrap">
			  			 <div class="clearfix" id="tab-pcategory">
			  			 	<div class="tab-inner">
			  			 		<table class="form">
			  			 			<tr>
			  			 				<td><?php echo $this->language->get('text_product_display_mode'); ?></td>
			  			 				<td>

			  			 					<select name="themecontrol[cateogry_display_mode]">
			  			 						<?php foreach( $cateogry_display_modes as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['cateogry_display_mode']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  			 					</select>
			  			 				</td>
			  			 			</tr>	
			  			 			<tr>
			  			 				<td><?php echo $this->language->get('text_max_product_row'); ?></td>
			  			 				<td>

			  			 					<select name="themecontrol[cateogry_product_row]">
			  			 						<?php foreach( $product_rows as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['cateogry_product_row']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  			 					</select>
			  			 				</td>
			  			 			</tr>	
			  			 			 
			  			 			<tr>
			  			 				<td><?php echo $this->language->get('text_show_product_zoom');?></td>
			  			 				<td>
			  			 					<select name="themecontrol[category_pzoom]">
			  			 						<?php foreach( $yesno  as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['category_pzoom']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  			 					</select>
			  			 				</td>
			  			 			</tr>	 
			  			 		</table>
			  			 	</div>
			  			 </div>
			  			  <div class="clearfix" id="tab-pproduct">
			  				<div class="tab-inner">
			  					<table class="form">
			  						<tr>
			  							<td><?php echo $this->language->get('text_enable_productzoom'); ?></td>
			  							<td>
			  								<select name="themecontrol[product_enablezoom]">
			  									<?php foreach( $yesno  as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['product_enablezoom']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  								</select>
			  							</td>
			  						</tr>
			  						<tr>
			  							<td><?php echo $this->language->get('text_product_zoomgallery'); ?></td>
			  							<td>
			  								<select name="themecontrol[product_zoomgallery]">
			  									<?php foreach( $product_zoomgallery  as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['product_zoomgallery']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  								</select>
			  							</td>
			  						</tr>	
			  						<tr>
			  							<td><?php echo $this->language->get('text_product_zoommode'); ?></td>
			  							<td>
			  								<select name="themecontrol[product_zoommode]">
			  									<?php foreach( $product_zoom_modes  as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['product_zoommode']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  								</select>
			  							</td>
			  						</tr>
			  						<tr>
			  							<td><?php echo $this->language->get('text_product_zoomlenssize'); ?></td>
			  							<td>
			  								<input value=<?php echo $module['product_zoomlenssize'];?> name="themecontrol[product_zoomlenssize]"/> 
			  							</td>
			  						</tr>
			  						<tr>
			  							<td><?php echo $this->language->get('text_product_zoomeasing'); ?></td>
			  							<td>
			  								<select name="themecontrol[product_zoomeasing]">
			  									<?php foreach( $yesno  as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['product_zoomeasing']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  								</select>
			  							</td>
			  						</tr>
			  						
			  						<tr>
			  							<td><?php echo $this->language->get('text_product_zoomlensshapes'); ?></td>
			  							<td>
			  								<select name="themecontrol[product_zoomlensshape]">
			  									<?php foreach( $product_zoomlensshapes  as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['product_zoomlensshape']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  								</select>
			  							</td>
			  						</tr>

			  						<tr>
			  			 				<td><?php echo $this->language->get('text_product_related_column'); ?></td>
			  			 				<td>

			  			 					<select name="themecontrol[product_related_column]">
			  			 						<?php foreach( $product_rows as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['product_related_column']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  			 					</select>
			  			 				</td>
			  			 			</tr>	
			  			 			<tr>
			  			 				<td colspan="1"><h4><?php echo $this->language->get('text_add_product_tab');?></h4></td>
			  			 				<td>
			  			 					<select name="themecontrol[enable_product_customtab]">
			  			 						<?php foreach( $yesno as $k=>$v ) { ?>
			  			 					 		<option value="<?php echo $k;?>"  <?php if( $k==$module['enable_product_customtab']){ ?> selected="selected" <?php } ?>><?php echo $v;?></option>
			  			 						<?php }  ?>	
			  			 					</select>
			  			 				</td>
			  			 			</tr>
			  			 			<tr>
			  			 				
			  			 				<td colspan="2">
			  			 					 
			  			 					<table class="form">
			  			 					<?php   foreach( $languages as $language ) {  

			  			 							 $customtab_name = isset($module['product_customtab_name'][$language['language_id']])
			  			 							 				?$module['product_customtab_name'][$language['language_id']] :"";
			  			 							 $customtab_content = isset($module['product_customtab_content'][$language['language_id']])?$module['product_customtab_content'][$language['language_id']]:"";				
			  			 					 ?>
			  			 						<tr>
			  			 						<td>  <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name'];?> 	</td>
			  			 						<td>
			  			 						 	<p>
			  			 							 <label> <?php echo $this->language->get('entry_customtab_name');?></label>	</p>

					  			 					 <p><input size="80" type="text"  name="themecontrol[product_customtab_name][<?php echo $language['language_id'];?>]" value="<?php echo $customtab_name;?>"/></p>
					  			 					 
					  			 					 <label> <?php echo $this->language->get('entry_customtab_content');?> 	
			  			 							<textarea id="customtab-content-<?php echo $language['language_id']; ?>"  style="width:90%; height:300px" name="themecontrol[product_customtab_content][<?php echo $language['language_id'];?>]"><?php echo $customtab_content;?></textarea>
			  			 						 	</td>
			  			 						</tr>
			  			 					<?php } ?>	
			  			 					</table>
			  			 				</td>
			  			 			</tr>
			  					</table>
			  				</div>
			  			 </div>

			  			 <div id="tab-pcontact">
			  			 	<div class="tab-inner">
			  			 		
			  			 		<table class="form">
			  			 			<tr>
			  			 				<td class="" colspan="2"><h4><?php echo $this->language->get('text_contact_googlemap'); ?></h4></td>
			  			 			</tr>
			  			 			<tr>
			  			 				<td><?php echo $this->language->get('location_address'); ?><span class="help"><?php echo $this->language->get("help_location_address"); ?></span></td>
			  			 				<td>
			  			 					<input id="searchTextField" name="themecontrol[location_address]" type="text" value="<?php echo isset($module['location_address'])?$module['location_address']:''; ?>" placeholder="<?php echo $this->language->get('text_location_address'); ?>" autocomplete="on" runat="server" size="60"/>
			  			 				</td>
			  			 			</tr>
			  			 			<tr>
			  			 				<td><?php echo $this->language->get('location_latitude'); ?></td>
			  			 				<td>
			  			 					<input id="location_latitude" name="themecontrol[location_latitude]" value="<?php echo isset($module['location_latitude'])?$module['location_latitude']:''; ?>" size="30"/>
										</td>
			  			 			</tr>
			  			 			<tr>
			  			 				<td><?php echo $this->language->get('location_longitude'); ?></td>
			  			 				<td><input id="location_longitude" name="themecontrol[location_longitude]" value="<?php echo isset($module['location_longitude'])?$module['location_longitude']:''; ?>" size="30"/></td>
			  			 			</tr>
			  			 			<tr>




			  			 			<tr>
			  			 				<td class="" colspan="2"><h4><?php echo $this->language->get('text_contact_html'); ?></h4></td>
			  			 			</tr>
			  			 			<?php foreach( $languages as $language ) {  ?>
			  			 			<tr>
			  			 				<td>
			  			 					<?php 
			  			 						$contact_customhtml = isset($module['contact_customhtml'][$language['language_id']])?
			  			 						$module['contact_customhtml'][$language['language_id']]:""; 
			  			 					 ?>
			  			 					 <img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
			  			 					 <?php echo $language['name'];?> 
			  			 				</td>
			  			 				<td>
			  			 					<textarea id="contact_customhtml-<?php echo $language['language_id'];?>" style="width:90%; height:300px" name="themecontrol[contact_customhtml][<?php echo $language['language_id'];?>]"><?php echo $contact_customhtml;?></textarea>
			  			 				</td>
			  			 			<tr>
			  			 			<?php } ?>	
			  			 		</table>	
			  			 	</div>	
			  			 </div>

			  			
			  			</div>  <div class="clear clearfix"></div>
				</div>  

				<div id="tab-general">
					<div class="tab-inner">
						<table class="form">
							<tr>
								<td><?php echo 'Default Theme'; ?></td>
								<td>
									<div class="group-options theme-skins clear">
										<select name="themecontrol[skin]">
											<option value="">default</option>
										<?php foreach( $skins as $skin ): ?>
											<option value="<?php echo $skin;?>" <?php if( $skin==$module['skin']){ ?> selected="selected" <?php } ?>><?php echo $skin;?></option>
										<?php endforeach;?>
										</select>
										
										<div class="clear"></div>
									</div>
						
								</td>
							</tr>
						
							<tr>
								<td><?php echo $this->getLang('entry_theme_width');?></td>
								<td>
									<input  name="themecontrol[theme_width]" value="<?php echo $module['theme_width'];?>">
									<p><i><?php echo $this->language->get('text_explain_theme_width');?></i></p>
								</td>
							</tr>
							<tr class="highlight">
							<td><?php echo $this->getLang('entry_enable_copyright');?></td>
							<td>
								<select name="themecontrol[enable_custom_copyright]">
								
								<?php foreach( $yesno as $v=>$op ): ?>
									<option value="<?php echo $v;?>" <?php if( $v==$module['enable_custom_copyright']){ ?> selected="selected" <?php } ?>><?php echo $op;?></option>
								<?php endforeach;?>
								</select>
							</td>
						</tr>
							<tr>
								<td><?php echo $this->getLang('copyright');?></td>
								<td>
									<textarea cols="40" rows="3" name="themecontrol[copyright]"><?php echo $module['copyright'];?></textarea>
								</td>
							</tr>
							<tr>
								<td><?php echo $this->getLang('entry_responsive');?></td>
								<td>
									<select name="themecontrol[responsive]">
										<option value="0" <?php if( $module['responsive'] == 0 ){ echo 'selected="selected"';} ;?>><?php echo $this->getLang('no');?></option>
										<option value="1" <?php if( $module['responsive'] == 1 ){ echo 'selected="selected"';} ;?>><?php echo $this->getLang('yes');?></option>
									</select>
								</td>
							</tr>
							<tr>
								<td><?php echo $this->getLang('entry_enable_footer_center');?></td>
								<td>
									<select name="themecontrol[enable_footer_center]">
										<option value="0" <?php if( $module['enable_footer_center'] == 0 ){ echo 'selected="selected"';} ;?>><?php echo $this->getLang('no');?></option>
										<option value="1" <?php if( $module['enable_footer_center'] == 1 ){ echo 'selected="selected"';} ;?>><?php echo $this->getLang('yes');?></option>
									</select>
								</td>
							</tr>
							
							<tr>
								<td><?php echo $this->getLang('entry_enable_paneltool');?></td>
								<td>
									<select name="themecontrol[enable_paneltool]">
										<option value="0" <?php if( $module['enable_paneltool'] == 0 ){ echo 'selected="selected"';} ;?>><?php echo $this->getLang('no');?></option>
										<option value="1" <?php if( $module['enable_paneltool'] == 1 ){ echo 'selected="selected"';} ;?>><?php echo $this->getLang('yes');?></option>
									</select>
								</td>
							</tr>
							
							<tr>
								<td>
									<label>Body Pattern</label>	
								</td>
								<td>
									<div class="group-input">
										<div class="box-patterns clearfix">	
											<div class="none" style="background:#FFF"></div>
											<?php foreach( $patterns as $pattern )  { ?>
											<div class="<?php echo str_replace(".png","",$pattern);?>" style="background:url(<?php echo $theme_url."image/pattern/".$pattern; ?>)"></div>
											<?php } ?>
										</div>
										<input name="themecontrol[body_pattern]" type="hidden" id="userparams_body_pattern" value="<?php echo $module['body_pattern'];?>"/>
										<script type="text/javascript">
											$( ".box-patterns div").click( function(){
												$("#userparams_body_pattern").val(  $(this).attr("class") );
												$( ".box-patterns div").removeClass( "active" );
												$(this).addClass( "active" );
											} );
											if( $("#userparams_body_pattern").val() ){ 
												$( ".box-patterns").find("."+ $("#userparams_body_pattern").val() ).addClass( "active" );
											}
										</script>
									</div>
									
								</td>
							</tr>
							<tr>
								<td><?php echo $this->language->get("entry_use_custom_background");?></td>
								<td> 
								<select name="themecontrol[use_custombg]">
									<?php foreach( $yesno as $v=>$op ): ?>
									<option value="<?php echo $v;?>" <?php if( $v==$module['use_custombg']){ ?> selected="selected" <?php } ?>><?php echo $op;?></option>
									<?php endforeach;?>
								</select>
								</td>
							</tr>
							<tr>
								<td><?php echo $this->language->get('entry_customize_background');?></td>
								<td>
									<?php $image = $module['bg_image'];	?>
									<div class="image">
										<img src="<?php echo $bg_thumb; ?>" alt="" id="thumb" />
										<input type="hidden" name="themecontrol[bg_image]" value="<?php echo $image; ?>" id="image" />
										<br />
										<a onclick="image_upload('image', 'thumb');"><?php echo $this->language->get("text_browse"); ?></a>&nbsp;&nbsp;|&nbsp;&nbsp;
										<a onclick="$('#thumb').attr('src', '<?php echo $no_image; ?>'); $('#image').attr('value', '');"><?php echo $this->language->get("text_clear"); ?></a>
									</div>
									
									<div class="clearfix"><br>
									<label><?php echo $this->language->get('text_css_background_repeat');?></label>
									 <select name="themecontrol[bg_repeat]">
										<?php foreach( $bg_repeat as $bgr ) { ?>
											<option value="<?php echo $bgr;?>" <?php if( $bgr==$module['bg_repeat']){ ?> selected="selected" <?php } ?>><?php echo $bgr; ?></option>
										<?php } ?>
									 </select>
									</div>
									<div class="clearfix"><br>
									<label><?php echo $this->language->get('text_css_background_position');?></label>
									 <select name="themecontrol[bg_position]">
										<?php foreach( $bg_position as $bgp ) { ?>
											<option value="<?php echo $bgp;?>" <?php if( $bgp==$module['bg_position']){ ?> selected="selected" <?php } ?> ><?php echo $bgp; ?></option>
										<?php } ?>
									 </select>
									</div>
								</td>
							</tr>
						</table>
					</div>
				</div>
				
				<?php if(  $modules_tpl ){ ?>
				<div id="tab-imodules">
					<p><?php echo $this->language->get('text_explain_internal_modules'); ?></p>
					<?php require( $modules_tpl );?>
				</div>
				<?php } ?>
				<div id="tab-font">
					<table class="form">
						<tr>
							<td><?php echo $this->getLang("fontsize");?></td>
							<td>
								<select name="themecontrol[fontsize]">
								<?php foreach ( $fontsizes as $key => $value ): ?>
									<?php  $selected = $value == $module['fontsize']?'selected="selected"':'';	?>	
									<option value="<?php echo $value;?>" <?php echo $selected; ?>><?php echo  $value; ?></option>
								<?php endforeach; ?>
								</select>
							</td>
						</tr>
						<tr class="highlight">
							<td><?php echo $this->getLang('entry_enable_customfont');?></td>
							<td>
								<select name="themecontrol[enable_customfont]">
								
								<?php foreach( $yesno as $v=>$op ): ?>
									<option value="<?php echo $v;?>" <?php if( $v==$module['enable_customfont']){ ?> selected="selected" <?php } ?>><?php echo $op;?></option>
								<?php endforeach;?>
								</select>
							</td>
						</tr>
					</table>
						<?php  //  echo '<pre>'.print_r( $module,1 );die;?>
					<table class="form">
						<?php for( $i=1; $i<=3;$i++ ){ ?>	
						<tr>
							<td><b><?php echo $this->language->get('entry_font_setting');?></b></td>
							<td>
								<div  class="group-change">
									<select name="themecontrol[type_fonts<?php echo $i;?>]" class="type-fonts">
										<?php foreach( $type_fonts as $font ) {   ?>
										<option value="<?php echo $font[0];?>"<?php if( $module['type_fonts'.$i] == $font[0]) { ?> selected="selected"<?php } ?>><?php echo $font[1];?></option>
										<?php } ?>
									</select>
									
									<table class="form">
											<tr class="items-group-change group-standard">
												<td><?php echo $this->language->get('entry_normal_font');?></td>
												<td>
													<select name="themecontrol[normal_fonts<?php echo $i;?>]">
														<?php foreach( $normal_fonts as $font ) {   ?>
														<option value="<?php echo htmlspecialchars($font[0]);?>"<?php if( $module['normal_fonts'.$i] == htmlspecialchars($font[0])) { ?> selected="selected"<?php } ?>><?php echo $font[1];?></option>
														<?php } ?>
													</select>
												</td>
											</tr>
											<tr class="items-group-change group-google">
												<td><?php echo $this->language->get('entry_body_google_url');?>
												
												</td>
												<td>
													<input type="text" name="themecontrol[google_url<?php echo $i;?>]" size="65" value="<?php echo $module['google_url'.$i];?>"/>
													<p><i><?php echo $this->language->get('text_explain_google_url')?></i></p>
												</td>
											</tr>
											<tr class="items-group-change group-google">
												<td>Google Family:</td>
												<td><input type="text" name="themecontrol[google_family<?php echo $i?>]" size="65" value="<?php echo $module['google_family'.$i];?>"/>
												<p><i><?php echo $this->language->get('text_explain_google_family');?></i></p>
												</td>
											</tr>
									</table>
								</div>
								
							</td>
						</tr>
						
						<tr>
							<td><?php echo $this->language->get('entry_body_selector');?></td>
							<td>
								<textarea name="themecontrol[body_selector<?php echo $i?>]" rows="5" cols="50"><?php echo $module['body_selector'.$i];?></textarea>
								<p><i><?php echo $this->language->get('text_explain_body_selector');?></i></p>
							</td>
						</tr>
					<?php } ?>
					</table>
					
				</div>
	
				<div id="tab-modules">
					<?php include( "layout.tpl" ); ?>
				</div>
				
				
				
				<input type="hidden" name="action_type" id="action_type" value="new">
				<?php if( isset($samples) )  { ?>
			
				<div id="tab-datasample">
				<p class="message">
					<i><?php echo $this->language->get('text_message_datasample_modules');?></i>
				</p>
						<h3>1. <?php echo $this->language->get('text_install_datasample_store');?></h3>
						<div class="storeconfig">
						<a rel="install" href="<?php echo $this->url->link('module/themecontrol/storesample', 'theme='.$module['default_theme'].'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $this->language->get('text_install_sample');?></a>
						</div>
						<h3>2. <?php echo $this->language->get('text_install_datasample_modules');?></h3>
						
						<table class="form">
						<thead>
						<tr>
							<td><b>Modules Name</b></td>
							<td><b>Action</b></td>
						</th>
						</thead>
						<?php foreach( $modulesQuery as  $qmodule => $text_mod ) { ?>
						<tr>
							<td><a target="_blank" href="<?php echo $this->url->link('module/'.$qmodule, 'token=' . $this->session->data['token'], 'SSL');?>"><?php echo $text_mod;?></a></td>
							<td>
								<a rel="install"   href="<?php echo $this->url->link('module/themecontrol/installsample', 'type=query&theme='.$module['default_theme'].'&module='.$qmodule.'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $this->language->get('text_install_sample');?></a> [SQL Query]
							<td>
						</tr>
						<?php } ?>
						<?php 
						foreach( $samples as $sample  ) { ?>
							<tr>
								<td>
								<?php if( isset($sample['existed']) && !$sample['existed'] ) { ?>
								<?php echo $this->language->get('text_module_not_uploaded');?>
								<?php } ?>
								<a target="_blank" href="<?php echo $this->url->link('module/'.$sample['module'], 'token=' . $this->session->data['token'], 'SSL');?>"><?php echo $sample['moduleName'];?></a>
								<?php if( isset($sample['extension_installed']) && !$sample['extension_installed'] ) { ?>
								<?php echo $this->language->get('text_module_not_installed');?>
								<?php } ?>
								</td>
								<td>
									<?php if( $sample['installed'] ) { ?>
										<a rel="override" href="<?php echo $this->url->link('module/themecontrol/installsample', 'theme='.$module['default_theme'].'&module='.$sample['module'].'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $this->language->get('text_override_sample');?></a>
									<?php } else { ?>
										<a rel="install" href="<?php echo $this->url->link('module/themecontrol/installsample', 'theme='.$module['default_theme'].'&module='.$sample['module'].'&token=' . $this->session->data['token'], 'SSL');?>"><?php echo $this->language->get('text_install_sample');?></a>
									<?php } ?>
									
								</td>
							</tr>
						<?php } ?>
						
						
						</table>
						<h3>3. <?php echo $this->language->get('disable_expected_module_in_home_page'); ?></h3>
						<p class="message">
							<i><?php echo $this->language->get('text_message_disable_expected_module_in_home_page');?></i>
						</p>
						<table class="form">
							<?php foreach(  $unexpectedModules as $umodule )  { ?>
							<tr>
								<td>
									<a href="<?php echo $this->url->link('module/'.$umodule['code'], 'token=' . $this->session->data['token'], 'SSL');?>"><?php echo $umodule['title']; ?></a>
								</td>
								<td></td>
							</tr>
							<?php } ?>
						</table>
				</div>
				
				<script type="text/javascript">
					$("#tab-datasample a").click( function(){
						var flag = false; 
						if( $(this).attr('rel') == 'override' ){
							var flag = confirm( '<?php echo $this->language->get('text_message_override_sample'); ?>' );
						}else if( $(this).attr('rel') == 'install' ){
							var flag = confirm( '<?php echo $this->language->get('text_message_install_sample'); ?>' );
						}else {
							return true; 
						}
						if( flag ){
							var $this = $( this );
							$this.html('processing');	
							$.post( $(this).attr('href'), function(data) {
								// $('.result').html(data);
								$this.parent().html('done');
							});
							return false;
						}
						return false; 
					} );		
				</script>
				
				<?php } ?>
				
				<div id="tab-customcode">
					<h4><?php echo $this->language->get('text_customcss'); ?></h4>
					<p><i><?php echo $this->language->get('text_explain_custom_css')?></i></p>
					<textarea name="themecontrol[custom_css]" rows="16" cols="80"><?php echo $module['custom_css'];?></textarea>
					<h4><?php echo $this->language->get('text_customjavascript'); ?></h4>
					<p><i><?php echo $this->language->get('text_explain_custom_js')?></i></p>
					<textarea name="themecontrol[custom_javascript]" rows="16" cols="80"><?php echo $module['custom_javascript'];?></textarea>
				</div>
				
				<div id="tab-support">
					
					<h4>Pavo Base Opencart Theme</h4>
					<p><i>
					We are proud to announce the next release of our Pav Opencart Framework, version 1.1. 
					This release coincides with the new version of Opencart released which is version 1.5.5.1. 
					The Framework built in Bootstrap framework,HTML5 and Css3.It is developed with many features such as Drap and Drop tools to update or sort modules - positions, Custom Fonts, Skins Changer, Responsive Feature, Mega Menu...
					It is as great solution for developer to develop themes more flexiable, professional and save a lot of time.
					Let check what are included?
					
					</i>
					</p>
					
					<h4>About Pavo Opencart Framework</h4>
					<div>
						<p class="pavo-copyright">Pavo is Free Opencart Theme Framework released under license GPL/V2. Powered by <a href="http://www.pavothemes.com" title="PavoThemes - Opencart Theme Clubs">PavoThemes</a></p>
					</div>
					<h4>Supports</h4>
					<div>
						Follow me on <b>twitter </b>or join my <b>facebook </b>page to get noticed about all theme updates and news!
						<ul>
							<li><a href="http://www.pavothemes.com">Forum</a></li>
							<li><a href="http://www.pavothemes.com">Ticket</a></li>
							<li><a href="http://www.pavothemes.com">Contact us</a></li>
							<li>Email: <a href="mailto:pavothemes@gmail.com">pavothemes@gmail.com</a> </li>
							<li>Skype Support: hatuhn</li>
							<li><a href="">YouTuBe</a></li>
						</ul>
					</div>
					<h4>Looking for Themes Based on the framework</h4>
					<ul>
						<li><a href="http://www.pavothemes.com" title="PavoThemes - Opencart Themes Club">View Our Collection</a></li>
					</ul>
					<h4>CheckUpdate</h4>
					<ul>
						<li><a href="http://www.pavothemes.com/updater/?product=pav-framework&list=1" title="PavoThemes - Opencart Themes Club">View Our Collection</a></li>
					</ul>
					<iframe width="560" height="315" src="http://www.youtube.com/embed/fNEepYl3LAk" frameborder="0" allowfullscreen></iframe>
				</div>
	   </div>
    </div></div>
  </div>
  
  
  </form>
  
</div>
<script type="text/javascript" src="view/javascript/ckeditor/ckeditor.js"></script> 
 <script type="text/javascript"><!--

 	<?php foreach ($languages as $language) { ?>
	CKEDITOR.replace('customtab-content-<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	});  

	CKEDITOR.replace('contact_customhtml-<?php echo $language['language_id']; ?>', {
		filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
		filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
	}); 

	<?php } ?>


$('#tabs a').tabs(); 
$('.mytabs a').tabs();
$('#languages a').tabs();
 $('#tab-pages-layout a').tabs();
$('#tabs a').click( function(){
	$.cookie("actived_tab", $(this).attr("href") );
} );

if( $.cookie("actived_tab") !="undefined" ){
	$('#tabs a').each( function(){
		if( $(this).attr("href") ==  $.cookie("actived_tab") ){
			$(this).click();
			return ;
		}
	} );
	
}
$(document).ready( function(){		
		$(".button-action").click( function(){
			$('#action_type').val( $(this).attr("rel") );
			var string = 'rand='+Math.random();
			var hook = '';
			$(".ui-sortable").each( function(){
				if( $(this).attr("data-position") && $(".module-pos",this).length>0) {
					var position = $(this).attr("data-position");
					$(".module-pos",this).each( function(){
						if( $(this).attr("data-id") != "" ){
							hook += "modules["+position+"][]="+$(this).attr("data-id")+"&";
						}				
					} );
					string = string.replace(/\,$/,"");
					hook = hook.replace(/\,$/,"");
				}	
			} );
			var unhook = '';

			$.ajax({
			  type: 'POST',
			  url: '<?php echo str_replace("&amp;","&",$ajax_modules_position);?>',
			  data: string+"&"+hook+unhook,
			  success: function(){
				$('#sform').submit();
				// 	window.location.reload(true);
			  }
			});
		return false; 
	} );
} );

$(".group-change").each( function(){
	var $this = this;
	$(".items-group-change",$this).hide();  //  alert( $(".type-fonts",this).val() );
	$(".group-"+$(".type-fonts",$this).val(), this).show();
	
	$(".type-fonts", this).change( function(){
		$(".items-group-change",$this).hide();
		$(".group-"+$(this).val(), $this).show();
	} );
});


$("#btn-guide").click( function(){
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="'+$(this).attr('href')+'" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: 'Guide For Theme: <?php echo $module["default_theme"]; ?>',
		close: function (event, ui) {},	
		bgiframe: false,
		width: 980,
		height: 560,
		resizable: false,
		modal: true
	});
	return false;
} );
//--></script> 
<script type="text/javascript"><!--
function image_upload(field, thumb) {
	$('#dialog').remove();
	
	$('#content').prepend('<div id="dialog" style="padding: 3px 0px 0px 0px;"><iframe src="index.php?route=common/filemanager&token=<?php echo $token; ?>&field=' + encodeURIComponent(field) + '" style="padding:0; margin: 0; display: block; width: 100%; height: 100%;" frameborder="no" scrolling="auto"></iframe></div>');
	
	$('#dialog').dialog({
		title: '<?php echo $text_image_manager; ?>',
		close: function (event, ui) {
			if ($('#' + field).attr('value')) {
				$.ajax({
					url: 'index.php?route=common/filemanager/image&token=<?php echo $token; ?>&image=' + encodeURIComponent($('#' + field).val()),
					dataType: 'text',
					success: function(data) {
						$('#' + thumb).replaceWith('<img src="' + data + '" alt="" id="' + thumb + '" />');
					}
				});
			}
		},	
		bgiframe: false,
		width: 800,
		height: 400,
		resizable: false,
		modal: false
	});
};
//--></script> 

<script src="http://maps.googleapis.com/maps/api/js?sensor=false&amp;libraries=places" type="text/javascript"></script>
<script type="text/javascript">
	function initialize() {
		var input = document.getElementById('searchTextField');
		var autocomplete = new google.maps.places.Autocomplete(input);
		google.maps.event.addListener(autocomplete, 'place_changed', function () {
			var place = autocomplete.getPlace();

			var lat = place.geometry.location.lat();
			var lon = place.geometry.location.lng();

			document.getElementById('location_latitude').value = lat;
			document.getElementById('location_longitude').value = lon;
		});
	}
    google.maps.event.addDomListener(window, 'load', initialize);
</script>
<?php echo $footer; ?>