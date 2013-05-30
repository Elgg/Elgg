<?php
?>
<div class="elgg-page elgg-page-default">
	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	
	<?php if (elgg_is_logged_in()){ ?>
	<div class="elgg-page-topbar">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/topbar', $vars);; ?>
		</div>
	</div>
	<?php } ?>
	
	<div class="elgg-page-header">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/header', $vars); ?>
		</div>
	</div>
	<div class="elgg-page-body">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/body', $vars); ?>
		</div>
	</div>
	<div class="elgg-page-footer">
		<div class="elgg-inner">
			<?php echo elgg_view('page/elements/footer', $vars);; ?>
		</div>
	</div>
</div>
<?php 
echo elgg_view('page/elements/foot');
