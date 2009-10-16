<?php
/**
 * Elgg 2 column left sidebar with boxes
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */
?>

<!-- left sidebar -->
<div id="two_column_left_sidebar_boxes">

	<?php if (isset($vars['area1'])) echo $vars['area1']; ?>
	<?php if (isset($vars['area3'])) echo $vars['area3']; ?>

</div><!-- /two_column_left_sidebar -->

<!-- main content -->
<div id="two_column_left_sidebar_maincontent_boxes">

<?php if (isset($vars['area2'])) echo $vars['area2']; ?>

</div><!-- /two_column_left_sidebar_maincontent -->

