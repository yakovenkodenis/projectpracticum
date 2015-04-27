<?php $id = rand(1,10); $span =  12/$columns; // echo $columns;die;?>
   <div id="pavcarousel<?php echo $id;?>" class="box carousel slide pavcarousel border-inner hidden-tablet hidden-phone">
		<div class="carousel-group">
			<div class="carousel-inner">
				 <?php foreach ($banners as $i => $banner) { ?>
					<?php if($i++%$columns==0) { ?>
					<div class="row-fluid item <?php if(($i-1)==0) {?>active<?php } ?>">
					<?php } ?>
					<div class="span<?php echo $span;?>">
						<div class="item-inner">
							<?php if ($banner['link']) { ?>
							<a href="<?php echo $banner['link']; ?>"><img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" /></a>
							<?php } else { ?>
							<img src="<?php echo $banner['image']; ?>" alt="<?php echo $banner['title']; ?>" />
							<?php } ?>
						</div>
					</div>	
					<?php if( $i%$columns==0 || $i==count($banners)) { ?>
					</div>
					<?php } ?>
				<?php } ?>
			</div>
		</div>
		<?php if( count($banners) > $columns ){ ?>	
		<a class="carousel-control left" href="#pavcarousel<?php echo $id;?>" data-slide="prev">&lsaquo;</a>
		<a class="carousel-control right" href="#pavcarousel<?php echo $id;?>" data-slide="next">&rsaquo;</a>
		<?php } ?>
    </div>
<?php if( count($banners) > 1 ){ ?>
<script type="text/javascript"><!--
 $('#pavcarousel<?php echo $id;?>').carousel({interval:false});
--></script>
<?php } ?>