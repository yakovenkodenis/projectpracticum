<?php require( DIR_TEMPLATE.$this->config->get('config_template')."/template/common/config.tpl" ); ?>
<?php echo $header; ?>
<?php if( $SPAN[0] ): ?>
  <div class="span<?php echo $SPAN[0];?>">
    <?php echo $column_left; ?>
  </div>
<?php endif; ?> 
<div class="span<?php echo $SPAN[1];?>">
<div id="content"><?php echo $content_top; ?>
   
    <div class="breadcrumb">
       <div class="breadcrumb-inner">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
    </div>
   </div>

    <h1 class="title-category"><?php echo $heading_title; ?></h1>
  <p><?php echo $text_total; ?><b> <?php echo $total; ?></b>.</p>
  <table class="list">
    <thead class="hidden-phone">
      <tr>
        <td class="left"><?php echo $column_date_added; ?></td>
        <td class="left"><?php echo $column_description; ?></td>
        <td class="right"><?php echo $column_amount; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php if ($transactions) { ?>
      <?php foreach ($transactions  as $transaction) { ?>
      <tr>
        <td class="left"><span class="phone hidden-desktop hidden-tablet"><?php echo $column_date_added; ?></span>
                  <div class="avg-right"><?php echo $transaction['date_added']; ?></div></td>
        <td class="left">
          <span class="phone hidden-desktop hidden-tablet"><?php echo $column_description; ?></span>
                  <div class="avg-right"><?php echo $transaction['description']; ?></div></td>
        <td class="right">
          <span class="phone hidden-desktop hidden-tablet"><?php echo $column_amount; ?></span>
                  <div class="avg-right"><?php echo $transaction['amount']; ?></div></td>
      </tr>
      <?php } ?>
      <?php } else { ?>
      <tr>
        <td class="center" colspan="5"><?php echo $text_empty; ?></td>
      </tr>
      <?php } ?>
    </tbody>
  </table>
  <div class="pagination"><?php echo $pagination; ?></div>
  </div>
  <div class="buttons">
    <div class="right"><a href="<?php echo $continue; ?>" class="button"><?php echo $button_continue; ?></a></div>
  </div>
  <?php echo $content_bottom; ?></div>
</div>
<?php if( $SPAN[2] ): ?>
<div class="span<?php echo $SPAN[2];?>">  
  <?php echo $column_right; ?>
</div>
<?php endif; ?>
<?php echo $footer; ?>