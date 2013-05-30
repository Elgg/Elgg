<?php
?>
<div class="elgg-page elgg-page-walledgarden">
	<div class="elgg-page-messages">
		<?php echo elgg_view('page/elements/messages', array('object' => $vars['sysmessages'])); ?>
	</div>
	<div class="elgg-body-walledgarden">
		<?php echo $vars['body']; ?>
	</div>
</div>
<?php 
echo elgg_view('page/elements/foot');
