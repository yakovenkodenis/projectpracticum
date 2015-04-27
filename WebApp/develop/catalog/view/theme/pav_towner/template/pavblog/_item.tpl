<div class="blog-item">
<?php if( $config->get('cat_show_title') ) { ?>
	<div class="blog-header clearfix">
	
	<h2 class="blog-title">	<a href="<?php echo $blog['link'];?>" title="<?php echo $blog['title'];?>"><?php echo $blog['title'];?></a></h2>
	<?php } ?>
	<div class="blog-meta">
		<?php if( $config->get('cat_show_created') ) { ?>
		<span class="created">
			<span><?php echo $this->language->get("text_created");?> :</span>
			<strong><?php echo date("d-M-Y",strtotime($blog['created']));?></strong>
		</span>
		<?php } ?>
		<?php if( $config->get('cat_show_author') ) { ?>
		<span class="author"><span><?php echo $this->language->get("text_write_by");?></span> <?php echo $blog['author'];?></span>
		<?php } ?>
		<?php if( $config->get('cat_show_category') ) { ?>
		<span class="publishin">
			<span><?php echo $this->language->get("text_published_in");?></span>
			<a href="<?php echo $blog['category_link'];?>" title="<?php echo $blog['category_title'];?>"><?php echo $blog['category_title'];?></a>
		</span>
		<?php } ?>
		
		<?php if( $config->get('cat_show_hits') ) { ?>
		<span class="hits"><span><?php echo $this->language->get("text_hits");?></span> <?php echo $blog['hits'];?></span>
		<?php } ?>
		<?php if( $config->get('cat_show_comment_counter') ) { ?>
		<span class="comment_count"><span><?php echo $this->language->get("text_comment_count");?></span> <?php echo $blog['comment_count'];?></span>
		<?php } ?>
	</div>
	</div>
	<div class="blog-body">
		<?php if( $blog['thumb'] && $config->get('cat_show_image') )  { ?>
		<img src="<?php echo $blog['thumb'];?>" title="<?php echo $blog['title'];?>" alt="<?php echo $blog['title'];?>"/>
		<?php } ?>
		

		<?php if( $config->get('cat_show_description') ) {?>
		<div class="description">
			<?php echo $blog['description'];?>
		</div>
		<?php } ?>
		<?php if( $config->get('cat_show_readmore') ) { ?>
		<div class="blog-readmore"><a href="<?php echo $blog['link'];?>" class="readmore"><?php echo $this->language->get('text_readmore');?></a></div>
		<?php } ?>
	</div>	
</div>