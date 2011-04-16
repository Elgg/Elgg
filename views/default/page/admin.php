<?php
/**
 * Elgg pageshell for the admin area
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title'] The page title
 * @uses $vars['body'] The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

$messages = $vars['sysmessages'];

$notices_html = '';
$notices = elgg_get_admin_notices();
if ($notices) {
	foreach ($notices as $notice) {
		$notices_html .= elgg_view_entity($notice);
	}

	$notices_html = "<div class=\"admin_notices\">$notices_html</div>";
}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<?php echo elgg_view('page/elements/head', $vars); ?>
</head>
<body>
	<div class="elgg-page elgg-page-admin">
		<div class="elgg-inner">
			<div class="elgg-page-header">
				<div class="elgg-inner clearfix">
					<?php echo elgg_view('admin/header'); ?>
				</div>
			</div>
			<div class="elgg-page-messages">
				<?php echo elgg_view('page/elements/messages', array('object' => $messages)); ?>
				<?php echo $notices_html; ?>
			</div>
			<div class="elgg-page-body">
				<div class="elgg-inner">
					<?php echo $vars['body']; ?>
				</div>
			</div>
			<div class="elgg-page-footer">
				<div class="elgg-inner">
					<?php echo elgg_view('admin/footer'); ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo elgg_view('page/elements/foot'); ?>
</body>

</html>