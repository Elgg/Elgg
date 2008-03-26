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

<div id="config">
		<?php echo $vars['config']; ?>
</div>

<hr />

<div id="list">
	<?php echo $vars['commandlist']; ?>
</div>