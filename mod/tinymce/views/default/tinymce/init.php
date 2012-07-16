<?php
// initialize tinymce if loaded via ajax
if (elgg_is_xhr()) {
?>

<script>
$(document).ready( function() {
	$('.elgg-menu-item-tinymce-toggler a[href="#<?php echo $vars['id']; ?>"]').click();
});
</script>

<?php
}