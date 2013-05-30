<?php

$notices_html = '';
$notices = elgg_get_admin_notices();
if ($notices) {
	foreach ($notices as $notice) {
		$notices_html .= elgg_view_entity($notice);
	}

	$notices_html = "<div class=\"elgg-admin-notices\">$notices_html</div>";
}

?>
<div class="elgg-page elgg-page-admin">
	<div class="elgg-inner">
		<div class="elgg-page-header">
			<div class="elgg-inner clearfix">
				<?php echo elgg_view('admin/header', $vars); ?>
			</div>
		</div>
		<div class="elgg-page-messages">
			<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
			<?php echo $notices_html; ?>
		</div>
		<div class="elgg-page-body">
			<div class="elgg-inner">
				<?php echo $vars['body']; ?>
			</div>
		</div>
		<div class="elgg-page-footer">
			<div class="elgg-inner">
				<?php echo elgg_view('admin/footer', $vars); ?>
			</div>
		</div>
	</div>
</div>
<?php 
echo elgg_view('page/elements/foot');
