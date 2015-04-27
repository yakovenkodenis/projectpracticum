<?php 
	$span = floor(12/$cols); 
?>

<div class="box pav-block bloglatest">
	<h3 class="box-heading"><span><?php echo $heading_title; ?></span></h3>
		<?php if( !empty($blogs) ) { ?>
		<div class="pavblog-latest clearfix">
			<?php foreach( $blogs as $key => $blog ) { ?>
			<?php if( $key++%$cols == 0 ) { ?>
			<div class="row-fluid">
			<?php } ?>
				<div class="span<?php echo $span;?> pavblock">
					<div class="blog-item">					
						<div class="blog-body clearfix">
							
							<div class="image clearfix">
								<?php if( $blog['thumb']  )  { ?>
									<img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" alt="<?php echo $blog['title'];?>"/>
								<?php } ?>
							</div>
							<div class="blog-title">
								<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a>
							</div>

							<div class="description">
								<?php echo utf8_substr( $blog['description'],0, 140 );?>...
							</div>

							<div class="">
								<a href="<?php echo $blog['link'];?>" class="readmore"><?php echo $this->language->get('text_readmore');?></a>
							</div>
							
						</div>	
					</div>
				</div>
			<?php if( ( $key%$cols==0 || $key == count($blogs)) ){  ?>			
			</div>
			<?php } ?>
			<?php } ?>
		</div>
		<?php } ?>
	</div>

