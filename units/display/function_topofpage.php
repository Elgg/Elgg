<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title><?php echo $parameter; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="/css/main.css" rel="stylesheet" type="text/css" />
<?php

	if (sizeof($data['display:topofpage:headers']) > 0) {
		
		foreach($data['display:topofpage:headers'] as $header) {
			
			echo $header . "\n";
			
		}
		
	}

?>
</head>

<body>
<table width="100%"  border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td height="70" id="pagetop"><!-- <img src="/images/logos/orange_small.png" alt="ELGG" width="242" height="70" /> -->
			<a href="<?php echo url; ?>"><img src="/images/logos/purplecrayon.gif" alt="Elgg" width="227" height="70" border="0" /></a>
	</td>
  </tr>
<?php

	run("display:menus");

?>
  <tr>
    <td id="mainbody">
