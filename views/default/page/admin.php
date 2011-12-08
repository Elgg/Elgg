<?php
/**
 * Elgg pageshell for the admin area
 *
 * @package Elgg
 * @subpackage Core
 *
 * @uses $vars['title']       The page title
 * @uses $vars['body']        The main content of the page
 * @uses $vars['sysmessages'] A 2d array of various message registers, passed from system_messages()
 */

$notices_html = '';
$notices = elgg_get_admin_notices();
if ($notices) {
	foreach ($notices as $notice) {
		$notices_html .= elgg_view_entity($notice);
	}

	$notices_html = "<div class=\"elgg-admin-notices\">$notices_html</div>";
}

// render content before head so that JavaScript and CSS can be loaded. See #4032
$messages = elgg_view('page/elements/messages', array('object' => $vars['sysmessages']));
$header = elgg_view('admin/header', $vars);
$body = $vars['body'];
$footer = elgg_view('admin/footer', $vars);


// Set the content type
header("Content-type: text/html; charset=UTF-8");

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
					<?php echo $header; ?>
				</div>
			</div>
			<div class="elgg-page-messages">
				<?php echo $messages; ?>
				<?php echo $notices_html; ?>
			</div>
			<div class="elgg-page-body">
				<div class="elgg-inner">
					<?php echo $body; ?>
				</div>
			</div>
			<div class="elgg-page-footer">
				<div class="elgg-inner">
					<?php echo $footer; ?>
				</div>
			</div>
		</div>
	</div>
	<?php echo elgg_view('page/elements/foot'); ?>
</body>

</html>