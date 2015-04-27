<?php 
/******************************************************
 * @package Pav Megamenu module for Opencart 1.5.x
 * @version 1.0
 * @author http://www.pavothemes.com
 * @copyright	Copyright (C) Feb 2013 PavoThemes.com <@emai:pavothemes@gmail.com>.All rights reserved.
 * @license		GNU General Public License version 2
*******************************************************/
?>
<?php if( count($modules) ) : ?>
<div class="content-top">
<?php foreach ($modules as $module) { ?>
<?php echo $module; ?>
<?php } ?>
</div>
<?php endif; ?>