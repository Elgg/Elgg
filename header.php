<?php

	require("includes.php");
	if (logged_on) {
		header("Location: " . url . "index2.php");
		exit();
	}

?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>ELGG</title>
<link href="main.css" rel="stylesheet" type="text/css" />
</head>
<body bgcolor="#ffffff" leftmargin="0" topmargin="0" marginwidth="0" marginheight="0">
<!-- top search and menu options -->
<table border="0" cellspacing="0" cellpadding="0" width="100%">
  <tr>
	<td width="23" class="leftSpace">
		&nbsp;
	</td>
	<td width="190" class="search">
		<form style="margin: 0px; padding: 0px" name="searchform" action="/search/all.php">
		<script language="JavaScript" type="text/javascript">
			<!--
			function submitthis()
			{
			  document.searchform.submit() ;
			}
			-->
		</script>
		<p>&nbsp;<input name="tag" type="text" value="" size="14">&nbsp;<a href="javascript:submitthis()" style="text-decoration: none">&lt;&lt; search</a>&nbsp;</p>
		</form>
	</td>
	<td align="right"> 
		<div class="topLinks">
			<a href="index.php">home</a>&nbsp;|&nbsp;
			<a href="about.php">about</a>&nbsp;|&nbsp;
			<a href="http://elgg.net/news/weblog/">news</a>&nbsp;|&nbsp;
			<a href="feedback.php">feedback</a>&nbsp;|&nbsp;
			<a href="jobs.php">jobs</a>&nbsp;|&nbsp;
			<a href="tutorials.php">tutorials</a>&nbsp;|&nbsp;
			<a href="contact.php">contact</a>
		</div>
	</td>
  </tr>
</table>
<!-- elgg banner and logo -->
<table border="0" cellspacing="0" cellpadding="0" width="100%" class="bannerSpace">
  <tr>
	<td width="23" class="leftSpace">
		&nbsp;
	</td>
	<td width="190" align="center" class="rightBorder">
		&nbsp;
	</td>
	<td>
		&nbsp;
	</td>
  </tr>
</table>