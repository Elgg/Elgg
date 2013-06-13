<?php
/**
 * Walled garden page shell
 *
 * Used for the walled garden index page
 */

$is_sticky_register = elgg_is_sticky_form('register');
$wg_body_class = 'elgg-body-walledgarden';
if ($is_sticky_register) {
	$wg_body_class .= ' hidden';
}

// Set the content type
header("Content-type: text/html; charset=UTF-8");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
<div class="elgg-page elgg-page-walledgarden">
	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	<div class="<?php echo $wg_body_class; ?>">
		<?php echo $vars['body']; ?>
	</div>
</div>
<?php if ($is_sticky_register): ?>
<script type="text/javascript">
elgg.register_hook_handler('init', 'system', function() {
	$('.registration_link').trigger('click');
});
</script>
<?php endif; ?>
<?php echo elgg_view('page/elements/foot'); ?>
</body>
</html>