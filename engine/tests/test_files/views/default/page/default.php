<?php
elgg_set_http_header("Content-type: text/html; charset=UTF-8");
?>

<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<title><?= elgg_extract('title', $vars, '') ?>
	</head>
	<body>
		<?= elgg_extract('body', $vars, '') ?>
	</body>
</html>
