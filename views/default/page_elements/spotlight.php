<?php
/**
 * Elgg spotlight
 * The spotlight area that displays across the site
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 *
 */
?>

<div id="layout_spotlight">
<div id="wrapper_spotlight">

<div class="collapsable_box no_space_after">
	<div class="collapsable_box_header">
<?php

	$closed = false;
	if ($_SESSION['user'] instanceof ElggUser) {
		if ($_SESSION['user']->spotlightclosed) {

			$closed = true;

		}
	}
	if ($closed) {
?>
		<a href="javascript:void(0);" class="toggle_box_contents" onClick="$.post('<?php echo elgg_add_action_tokens_to_url("{$vars['url']}action/user/spotlight?closed=false"); ?>')">+</a>
<?php
		} else {
?>
		<a href="javascript:void(0);" class="toggle_box_contents" onClick="$.post('<?php echo elgg_add_action_tokens_to_url("{$vars['url']}action/user/spotlight?closed=true"); ?>')">-</a>
<?php

		}

?>
		<h1><?php echo elgg_echo("spotlight"); ?></h1>
	</div>
	<div class="collapsable_box_content" <?php if ($closed) echo "style=\"display:none\"" ?>>
<?php

	$context = get_context();
	if (!empty($context) && elgg_view_exists("spotlight/{$context}")) {
		echo elgg_view("spotlight/{$context}");
	} else {
		echo elgg_view("spotlight/default");
	}
?>
	</div><!-- /.collapsable_box_content -->
</div><!-- /.collapsable_box -->

</div><!-- /#wrapper_spotlight -->
</div><!-- /#layout_spotlight -->
