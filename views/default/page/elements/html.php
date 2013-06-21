<?php
/**
 * Page shell for all HTML pages
 */

// Set the content type
header("Content-type: text/html; charset=UTF-8");

$lang = get_current_language();

?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $lang; ?>" lang="<?php echo $lang; ?>">
	<head>
<?php echo $vars["head"]; ?>
	</head>
	<body>
<?php echo $vars["body"]; ?>
	</body>
</html>