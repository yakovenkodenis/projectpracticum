<div class="box category no-padding">
  <h3 class="box-heading"><span><?php echo $heading_title; ?></span></h3>
  <div class="box-content">
    <ul class="box-category">
      <?php foreach ($categories as $category) { 
        $class = "";
        if(isset($category["children"]) && !empty($category["children"])){
         $class = "haschild";
       }
       ?>
       <li class="<?php echo $class; ?>">
        <?php if ($category['category_id'] == $category_id) { ?>
        <a href="<?php echo $category['href']; ?>" class="active"><?php echo $category['name']; ?></a>
        <?php } else { ?>
        <a href="<?php echo $category['href']; ?>" class="pav-item-menu"><?php echo $category['name']; ?></a>
        <?php } ?>
        <?php if ($category['children']) { ?>
		<div class="subcat"><a href="javascript:void(0);" class="toggle">+</a></div>
        <ul class="box-sub">
          <?php foreach ($category['children'] as $child) { ?>
          <li>
            <?php if ($child['category_id'] == $child_id) { ?>
            <a href="<?php echo $child['href']; ?>" class="active"> - <?php echo $child['name']; ?></a>
            <?php } else { ?>
            <a href="<?php echo $child['href']; ?>"> <?php echo $child['name']; ?></a>
            <?php } ?>
          </li>
          <?php } ?>
        </ul>
        <?php } ?>
      </li>
      <?php } ?>
    </ul>
	
	<script type="text/javascript">
		$(function(){
			//Toggle Sub Categories
			var activeCat = '.box-category li a.active';
			if($(activeCat).length > 0){
				$(activeCat).parent('li').find('ul').show();	
				$(activeCat).parent('li').find('a.toggle').toggleClass('aToggle');		
			}	
			$('.subcat a').click(function(){
				$('.subcat a').removeClass('aToggle');
				var ul = $(this).parent('div').parent('li').find('ul');
				if(!ul.is(':visible')){
					$('.box-category ul li > ul').slideUp();
					$(this).toggleClass('aToggle');
				}else{			
					$(this).removeClass('aToggle');
				}
				ul.stop(true,true).slideToggle();
			});
		});
	</script>	
  </div>
</div>
