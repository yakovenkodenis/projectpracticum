<?php if (!isset($redirect)) { ?>
<div class="checkout-product">
  <table>
    <thead class="hidden-phone">
      <tr>
        <td class="name"><?php echo $column_name; ?></td>
        <td class="model"><?php echo $column_model; ?></td>
        <td class="quantity"><?php echo $column_quantity; ?></td>
        <td class="price"><?php echo $column_price; ?></td>
        <td class="total"><?php echo $column_total; ?></td>
      </tr>
    </thead>
    <tbody>
      <?php foreach ($products as $product) { ?>
      <tr>
        <td class="name">
		<span class="phone hidden-desktop hidden-tablet"><strong><?php echo $column_name; ?><strong></span>
		<div class="avg-right"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
          <?php foreach ($product['option'] as $option) { ?>
          <br />
          &nbsp;<small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
          <?php } ?>
		  </div></td>
        <td class="model">
			<span class="phone hidden-desktop hidden-tablet"><?php echo $column_model; ?></span>
			<span class="avg-right"><?php echo $product['model']; ?></span></td>
        <td class="quantity">
		<span class="phone hidden-desktop hidden-tablet"><?php echo $column_quantity; ?></span><span class="avg-right"><?php echo $product['quantity']; ?></span></td>
        <td class="price">
		<span class="phone hidden-desktop hidden-tablet"><?php echo $column_price; ?></span><span class="avg-right"><?php echo $product['price']; ?></span></td>
        <td class="total">
		<span class="phone hidden-desktop hidden-tablet"><?php echo $column_total; ?></span><span class="avg-right"><?php echo $product['total']; ?></span></td>
      </tr>
      <?php } ?>
      <?php foreach ($vouchers as $voucher) { ?>
      <tr>
        <td class="name"><?php echo $voucher['description']; ?></td>
        <td class="model"></td>
        <td class="quantity">1</td>
        <td class="price"><?php echo $voucher['amount']; ?></td>
        <td class="total"><?php echo $voucher['amount']; ?></td>
      </tr>
      <?php } ?>
    </tbody>
    <tfoot>
      <?php foreach ($totals as $total) { ?>
      <tr>
        <td colspan="4" class="price"><b><?php echo $total['title']; ?>:</b></td>
        <td class="total"><?php echo $total['text']; ?></td>
      </tr>
      <?php } ?>
    </tfoot>
  </table>
</div>
<div class="payment"><?php echo $payment; ?></div>
<?php } else { ?>
<script type="text/javascript"><!--
location = '<?php echo $redirect; ?>';
//--></script> 
<?php } ?>