<div class="box categoryblock">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-category">
    <!--<?php print_r($brands); ?>-->
    <ul>
    <?php foreach ($brands as $brand) { ?>
    <li>
    <?php if($brand['brand_id'] == $brand_id) {?>
    <a class="active" href="<?php echo $brand['href']; ?>" title="<?php echo $brand['seo_title']; ?>"><?php echo $brand['name']; ?></a>
    <?php } else {?>
    <a href="<?php echo $brand['href']; ?>" title="<?php echo $brand['seo_title']; ?>"><?php echo $brand['name']; ?></a>
    <?php }?>
    </li>
    <?php }?>
    </ul>
    </div>
  </div>
</div>
