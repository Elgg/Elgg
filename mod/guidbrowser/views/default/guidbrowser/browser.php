<?php
	$navbar = $vars['prevnext'];
	$entities = $vars['entities'];
?>
<script type="text/javascript" language="javascript">
<!--
function showhide(oid)
{
	var e = document.getElementById(oid);
	if(e.style.display == 'none') {
		e.style.display = 'block';
	} else {
		e.style.display = 'none';
	}
}
// -->
</script>

<div id="browser">
	<div id="entities">
		<?php echo $entities; ?>
	</div>

	<?php echo $navbar; ?>
</div>