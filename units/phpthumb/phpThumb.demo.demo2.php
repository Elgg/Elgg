<HTML>
<HEAD>
	<TITLE>Demo of phpThumb() - thumbnails created by PHP</TITLE>
	<LINK REL="stylesheet" TYPE="text/css" HREF="/style.css" TITLE="style sheet">
</HEAD>
<BODY BGCOLOR="#EFEFEF">

This is a live demo of <A HREF="http://phpthumb.sourceforge.net"><B>phpThumb()</B></A><BR>
(See the usual static demo <A HREF="phpThumb.demo.demo1.php">here</A>)<BR>
<BR>
Note: this server is working on GD v1.6, so images (especially watermarks & resizing) do not look as good as they would on GD v2.x<HR>
<?php

if (!empty($_REQUEST['src'])) {
	$GETpairs = array();
	foreach ($_GET as $key => $value) {
		if (strlen($value) > 0) {
			$GETpairs[] = $key.'='.urlencode($value);
		}
	}
	$imageSRC = 'phpThumb.php?'.implode('&', $GETpairs);
	echo '<XMP><IMG SRC="'.$imageSRC.'"></XMP>';
	echo '<IMG SRC="'.$imageSRC.'"><HR>';
}


echo '<TABLE BORDER="1">';
echo '<FORM ACTION="" METHOD="GET">';

echo '<TR><TD><B>Source Image</B></TD><TD>';
$PossibleImages = array('loco.jpg', 'watermark.png', 'bottle.jpg', 'kayak.jpg');
foreach ($PossibleImages as $image) {
	echo '<INPUT TYPE="RADIO" NAME="src" VALUE="'.$image.'"'.((@$_REQUEST['src'] == $image) ? ' CHECKED' : '').'><IMG ALIGN="MIDDLE" SRC="phpThumb.php?src='.$image.'&w=100&h=100"><BR><BR>';
}
echo '</UL></TD></TR>';

echo '<TR><TD><B>Max Width:</B></TD> <TD><INPUT TYPE="TEXT" NAME="w" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['w'], ENT_QUOTES).'">px</TD></TR>';
echo '<TR><TD><B>Max Height:</B></TD><TD><INPUT TYPE="TEXT" NAME="h" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['h'], ENT_QUOTES).'">px</TD></TR>';

echo '<TR><TD><B>Output Image Format</B></TD><TD>';
$PossibleImageFormats = array('jpeg', 'png', 'gif');
foreach ($PossibleImageFormats as $imageformat) {
	echo '<INPUT TYPE="RADIO" NAME="f" VALUE="'.$imageformat.'"'.((@$_REQUEST['f'] == $imageformat) ? ' CHECKED' : '').'>'.$imageformat.'<BR>';
}
echo '</TD></TR>';

echo '<TR><TD><B>JPEG Quality:</B></TD><TD><SELECT NAME="q">';
echo '<OPTION VALUE=""></OPTION>';
for ($i = 1; $i <= 95; $i++) {
	echo '<OPTION VALUE="'.$i.'"'.((@$_REQUEST['q'] == $i) ? ' SELECTED' : '').'>'.$i.'</OPTION>';
}
echo '</SELECT> (default = 75)</TD></TR>';

echo '<TR><TD><B>Crop Left / Top point:</B></TD><TD> <INPUT TYPE="TEXT" NAME="sx" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['sx'], ENT_QUOTES).'"> x <INPUT TYPE="TEXT" NAME="sy" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['sy'], ENT_QUOTES).'"></TD></TR>';
echo '<TR><TD><B>Crop Width / Height:</B></TD><TD>   <INPUT TYPE="TEXT" NAME="sw" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['sw'], ENT_QUOTES).'"> x <INPUT TYPE="TEXT" NAME="sh" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['sh'], ENT_QUOTES).'"></TD></TR>';

//echo '<TR><TD><B>Border Width:</B></TD><TD><SELECT NAME="bw">';
//echo '<OPTION VALUE="">none</OPTION>';
//for ($i = 0; $i <= 50; $i++) {
//	echo '<OPTION VALUE="'.$i.'"'.((@$_REQUEST['bw'] === "$i") ? ' SELECTED' : '').'>'.$i.'</OPTION>';
//}
//echo '</SELECT> (any option except <i>none</i> forces output size to <I>width</I> x <I>height</I></TD></TR>';

//echo '<TR><TD><B>Border Corner Radius:</B></TD><TD><INPUT TYPE="TEXT" NAME="brx" SIZE="2" VALUE="'.htmlentities(@$_REQUEST['brx'], ENT_QUOTES).'"> horizontal (requires border >= 1)<br><INPUT TYPE="TEXT" NAME="bry" SIZE="2" VALUE="'.htmlentities(@$_REQUEST['bry'], ENT_QUOTES).'"> vertical (requires border >= 1)</TD></TR>';
//echo '<TR><TD><B>Border Hex Color:</B></TD>    <TD><INPUT TYPE="TEXT" NAME="bc" SIZE="6" VALUE="'.htmlentities(@$_REQUEST['bc'], ENT_QUOTES).'"> default: 000000 (requires border >= 1)</TD></TR>';
echo '<TR><TD><B>Background Hex Color:</B></TD><TD><INPUT TYPE="TEXT" NAME="bg" SIZE="6" VALUE="'.htmlentities(@$_REQUEST['bg'], ENT_QUOTES).'"> default: FFFFFF (requires border >= 0)</TD></TR>';

//echo '<TR><TD><B>Unsharp Mask Amount:</B></TD><TD>   <INPUT TYPE="TEXT" NAME="usa" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['usa'], ENT_QUOTES).'"> (default = 80, range 50-200)</TD></TR>';
//echo '<TR><TD><B>Unsharp Mask Radius:</B></TD><TD>   <INPUT TYPE="TEXT" NAME="usr" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['usr'], ENT_QUOTES).'"> (default = 0.5, range 0.5-1)</TD></TR>';
//echo '<TR><TD><B>Unsharp Mask Threshold:</B></TD><TD><INPUT TYPE="TEXT" NAME="ust" SIZE="3" VALUE="'.htmlentities(@$_REQUEST['ust'], ENT_QUOTES).'"> (default = 3, range 1-5)</TD></TR>';

//echo '<TR><TD><B>Watermark Image</B></TD><TD>';
//echo '<INPUT TYPE="RADIO" NAME="wmf" VALUE=""'.((@$_REQUEST['wmf'] == '') ? ' CHECKED' : '').'><I>none</I><BR><BR>';
//foreach ($PossibleImages as $image) {
//	echo '<INPUT TYPE="RADIO" NAME="wmf" VALUE="'.$image.'"'.((@$_REQUEST['wmf'] == $image) ? ' CHECKED' : '').'><IMG ALIGN="MIDDLE" SRC="phpThumb.php?src='.$image.'&w=100&h=100"><BR><BR>';
//}
//echo '</UL></TD></TR>';
//
//echo '<TR><TD><B>Watermark Opacity:</B></TD><TD><SELECT NAME="wmp">';
//for ($i = 1; $i <= 100; $i++) {
//	echo '<OPTION VALUE="'.$i.'"';
//	if ((empty($_REQUEST['wmp']) && ($i == 50)) || (@$_REQUEST['wmp'] == $i)) {
//		echo ' SELECTED';
//	}
//	echo '>'.$i.'</OPTION>';
//}
//echo '</SELECT>% (default = 50%)</TD></TR>';
//
//echo '<TR><TD><B>Watermark Alignment</B></TD><TD><SELECT NAME="wma">';
//$PossibleAlignments = array('*'=>'tile', 'T'=>'top', 'B'=>'bottom', 'L'=>'left', 'R'=>'right', 'TL'=>'top-left', 'TR'=>'top-right', 'BL'=>'bottom-left', 'BR'=>'bottom-right');
//foreach ($PossibleAlignments as $key => $value) {
//	echo '<OPTION VALUE="'.$key.'"';
//	if ((empty($_REQUEST['wma']) && ($key == 'BR')) || (@$_REQUEST['wma'] == $key)) {
//		echo ' SELECTED';
//	}
//	echo '>'.$value.'</OPTION>';
//}
//echo '</SELECT></TD></TR>';
//
//echo '<TR><TD><B>Watermark Spacing:</B></TD><TD><SELECT NAME="wmm">';
//for ($i = 0; $i <= 100; $i++) {
//	echo '<OPTION VALUE="'.$i.'"';
//	if ((empty($_REQUEST['wmm']) && ($i == 5)) || (@$_REQUEST['wmm'] == $i)) {
//		echo ' SELECTED';
//	}
//	echo '>'.$i.'</OPTION>';
//}
//echo '</SELECT>% (default = 5%)</TD></TR>';

echo '<TR><TD><B>Extract EXIF Thumbnail only:</B></TD><TD><INPUT TYPE="CHECKBOX" NAME="xto" VALUE="1"'.(@$_REQUEST['xto'] ? ' CHECKED' : '').'> (overrides all processing if EXIF thumbnail is present)</TD></TR>';

//echo '<TR><TD><B>Rotate by angle</B></TD><TD><INPUT TYPE="TEXT" NAME="ra" SIZE="2" MAXLENGTH="3" VALUE="'.htmlentities(@$_REQUEST['ra'], ENT_QUOTES).'">° (counter-clockwise)</TD></TR>';

echo '<TR><TD><B>Force non-proportional resize:</B></TD><TD><INPUT TYPE="CHECKBOX" NAME="iar" VALUE="1"'.(@$_REQUEST['iar'] ? ' CHECKED' : '').'> (stretches image to <I>width</I> x <I>height</I>)</TD></TR>';

echo '</TABLE>';
echo '<INPUT TYPE="SUBMIT" VALUE="Create Image">';
echo '</FORM>';

?>
</BODY>
</HTML>

</BODY>
</HTML>