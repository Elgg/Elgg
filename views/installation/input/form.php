<?php
/**
 * Create a form for data submission.
 *
 * @uses $vars['body']   The body of the form (made up of other input/xxx views and html
 * @uses $vars['action'] URL of the action being called
 * @uses $vars['method'] Method (default POST)
 * @uses $vars['id']     Form id
 * @uses $vars['name']   Form name
 */

if (isset($vars['id'])) {
	$id = "id=\"{$vars['id']}\"";
} else {
	$id = '';
}
if (isset($vars['name'])) {
	$name = "name=\"{$vars['name']}\"";
} else {
	$name = '';
}
$body = $vars['body'];
$action = $vars['action'];
if (isset($vars['method'])) {
	$method = $vars['method'];
} else {
	$method = 'POST';
}

$method = strtolower($method);

?>
<form <?php echo "$id $name"; ?> action="<?php echo $action; ?>" method="<?php echo $method; ?>">
<?php echo $body; ?>
</form>