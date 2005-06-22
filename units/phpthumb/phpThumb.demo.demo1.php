<?php
$GDversion['string']  = 'unknown';
$GDversion['numeric'] = 0;
if (!@include_once('phpthumb.functions.php')) {
	die('failed to open "phpthumb.functions.php"');
}
if (include_once('phpthumb.class.php')) {
	$phpThumb = new phpThumb();
	$phpthumb_version = $phpThumb->phpthumb_version;
	unset($phpThumb);
	$GDversion['string']  = phpthumb_functions::gd_version(true);
	$GDversion['numeric'] = phpthumb_functions::gd_version(false);
} else {
	die('failed to open "phpthumb.class.php"');
}
?>

<html>
<head>
	<title>Demo of phpThumb() - thumbnails created by PHP</title>
	<link rel="stylesheet" type="text/css" href="/style.css" title="style sheet">
</head>
<body bgcolor="#C5C5C5">

This is a demo of <a href="http://phpthumb.sourceforge.net"><b>phpThumb()</b></a> (current version: v<?php echo @$phpthumb_version; ?>)<br>
A dynamic demo where you can set <strike>all</strike> some of the parmeters and see their effect is <a href="phpThumb.demo.demo2.php">here</a>.<br>
<br>
<!--
<b>Note:</b> none of the images on this page are cached, so they may take a few seconds to load, but they represent the actual script in action.<br>
<br>
-->
<b>Note:</b> this server is working on GD "<?php
echo $GDversion['string'].'"';
if ($GDversion['numeric'] >= 2) {
	echo ', so images should be of optimal quality.';
} else {
	echo ', so images (especially watermarks) do not look as good as they would on GD v2. <blockquote><b>A static demo of what this page should look like can be seen <a href="static.html">here</a>.</b></blockquote>';
}
?><br>
<br>
<table border="5" cellspacing="0" cellpadding="3" width="500">
	<tr>
		<td colspan="4">
			<b>Illustration of potential difference between GD1.x and GD2.x</b><br>
			In most cases the thumbnails produced by phpThumb() on GD v1.x are perfectly
			acceptable, but in some cases it may look ugly. Diagonal lines and reducing a
			very large source image increase chance for bad results (the house/sky picture
			has both problems). Here are three static examples:
		</td>
	</tr>
	<tr>
		<td><b>GD v2.0.15</b></td>
		<td><img src="PHP-GD2-kayak.jpg"  width="200" height="133" border="0" alt="kayak.jpg generated with phpThumb() on GD v2.0.15"></td>
		<td><img src="PHP-GD2-bottle.jpg" width="100" height="152" border="0" alt="bottle.jpg generated with phpThumb() on GD v2.0.15"></td>
		<td><img src="PHP-GD2-sky.jpg"    width="200" height="150" border="0" alt="sky.jpg generated with phpThumb() on GD v2.0.15"></td>
	</tr>
	<tr>
		<td><b>GD v1.6.2</b></td>
		<td><img src="PHP-GD1-kayak.jpg"  width="200" height="133" border="0" alt="kayak.jpg generated with phpThumb() on GD v1.6.2"></td>
		<td><img src="PHP-GD1-bottle.jpg" width="100" height="152" border="0" alt="bottle.jpg generated with phpThumb() on GD v1.6.2"></td>
		<td><img src="PHP-GD1-sky.jpg"    width="200" height="150" border="0" alt="sky.jpg generated with phpThumb() on GD v1.6.2"></td>
	</tr>
</table>

<hr size="1">
<a href="#showpic">phpThumb.demo.showpic.php demo here</a><br>
<hr size="1">
<table border="5" align="center" width="500" cellpadding="5"><tr><td>
	<b>The following images have the textured background behind them to illustrate transparency effects.
	Note that some browsers, notably Internet Explorer, are incapable of displaying alpha-channel PNGs.
	See my page on the <a href="http://www.silisoftware.com/png_alpha_transparency/" target="_blank">PNG transparency problem</a>.
	Other modern browsers such as <a href="http://www.mozilla.org">Mozilla/Firefox</a> display alpha-transparent PNGs with no problems.</b>
</td></tr></table><br>

<!--
<xmp><img src="disk.jpg"></xmp>
Original image (not thumbnailed) - original dimensions = 500x500px<br>
<img border="0" src="disk.jpg"><br>
<br>
<hr size="1">
-->

<?php

$img_square    = 'disk.jpg';
$img_landscape = 'loco.jpg';
$img_portrait  = 'pineapple.jpg';
$img_unrotated = 'monkey.jpg';
$img_watermark = 'watermark.png';
$img_levels    = 'bunnies.jpg';

$png_alpha  = 'Note: PNG output is 32-bit with alpha transparency, subject to <a href="http://www.silisoftware.com/png_alpha_transparency/" target="_blank">PNG transparency problem</a> in Internet Explorer';
$only_gd2   = '<br>(only works with GD v2.0+, this server is running GD "<i>'.$GDversion['string'].'</i>" so it <b>will '.(($GDversion['numeric'] >= 2) ? '' : 'not').'</b> work';
$only_php42 = '<br>(only works with PHP v4.2.0+, this server is running PHP v'.phpversion().' so it <b>will '.(version_compare(phpversion(), '4.2.0', '>=') ? '' : 'not').'</b> work)';

echo 'The source images, without manipulation:<ul>';
echo '<li><a href="'.$img_square.'">'.$img_square.'</a></li>';
echo '<li><a href="'.$img_landscape.'">'.$img_landscape.'</a></li>';
echo '<li><a href="'.$img_portrait.'">'.$img_portrait.'</a></li>';
echo '</ul><hr>';

$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=200'), 'description' => 'width=200px');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=200&q=10'), 'description' => 'width=200px, JPEGquality=10%');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=200&f=png'), 'description' => 'width=200px, format=PNG');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=800&aoe=1'), 'description' => 'width=800px, AllowOutputEnlargement enabled');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=250&sx=125&sy=140&sw=130&sh=65&aoe=1'), 'description' => 'section from (125x140 - 255x190) cropped and enlarged by 200%, AllowOutputEnlargement enabled');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=200&fltr[]=wmi|'.$img_watermark.'|BL'), 'description' => 'width=200px, watermark (bottom-left, 75% opacity)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_square.'&w=200&fltr[]=wmi|'.$img_watermark.'|*|25'), 'description' => 'width=200px, watermark (tiled, 25% opacity)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_watermark.'&bg=00FFFF&f=png', 'phpThumb.php?src='.$img_watermark.'&bg=00FFFF&f=jpeg'), 'description' => 'source image (GIF) transpancy with transparent output (PNG) vs. specified background color (JPEG)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=usm|80|0.5|3'), 'description' => 'normal vs. unsharp masking at default settings'.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=blur|1', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=blur|5'), 'description' => 'normal vs. blur at default (1) and heavy (5)'.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_portrait.'&w=100&h=100&far=1&fltr[]=bord|3|0|0|FF0000&bg=0000FF&f=png'), 'description' => '3px red border, fixed-size thumbnail despite aspect ratio, blue background, PNG output');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_portrait.'&w=150&ar=L', 'phpThumb.php?src='.$img_landscape.'&w=150&ar=L'), 'description' => 'auto-rotate counter-clockwise to landscape from portrait & lanscape'.$only_php42);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_unrotated.'&w=150&h=150', 'phpThumb.php?src='.$img_unrotated.'&w=150&h=150&ar=x'), 'description' => 'original image vs. auto-rotated based on EXIF data'.$only_php42);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&ra=30&bg=0000FF', 'phpThumb.php?src='.$img_landscape.'&w=200&ra=30&f=png'), 'description' => 'Rotated 30° (counter-clockwise), width=200px, blue background vs. transparent background'.$only_php42);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&h=300&far=1&bg=CCCCCC', 'phpThumb.php?src='.$img_landscape.'&w=200&h=300&iar=1'), 'description' => 'Normal resize behavior (left) vs. Forced non-proportional resize (right)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=150&h=150&zc=1', 'phpThumb.php?src='.$img_portrait.'&w=150&h=150&zc=1'), 'description' => 'Zoom-Crop');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=bord|2|20|10|009900&f=png'), 'description' => '2px border, curved border corners (20px horizontal radius, 10px vertical radius)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=ric|50|20&f=png'), 'description' => 'curved border corners (20px vertical radius, 50px horizontal radius)<br>'.$png_alpha.''.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=ds|75', 'phpThumb.php?src='.$img_landscape.'&w=200', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=ds|-100'), 'description' => 'desaturated 75% vs. normal vs. boosted saturation 100%'.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=clr|25|00FF00'), 'description' => 'colorized 25% to green (#00FF00)'.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=gray', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=sep'), 'description' => 'grayscale vs. sepia'.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=gam|0.6', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=gam|1.6'), 'description' => 'Gamma corrected to 0.8 vs. 1.6');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=mask|mask06.png&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=mask|mask04.png&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=mask|mask05.png&f=jpeg&bg=9900CC&q=100'), 'description' => 'Assorted alpha masks (seen below) applied<br>'.$png_alpha.'<br>JPEG/GIF output is flattened to "bg" background color'.$only_gd2.'<br><img src="mask06.png"> <img src="mask04.png"> <img src="mask05.png">');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=drop|5|10|000000|225&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=mask|mask06.png&fltr[]=drop|5|10|000000|225&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=drop|5|10|000000|225&fltr[]=elip&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=elip&fltr[]=drop|5|10|000000|225&f=png'), 'description' => 'Drop shadow. Note how the order in which filters are applied matters.');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=elip&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=elip&f=jpeg&bg=00FFFF'), 'description' => 'Elipse<br>'.$png_alpha.'<br>JPEG/GIF output is flattened to "bg" background color'.$only_gd2);
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=flip|x', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=flip|y', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=flip|xy'), 'description' => 'flipped on X, Y and X+Y axes');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=bvl|10|FFFFFF|000000', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=bvl|10|000000|FFFFFF'), 'description' => '10px bevel edge filter');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=fram|3|2|CCCCCC|FFFFFF|000000', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=fram|3|2|CC9966|333333|CCCCCC'), 'description' => '3+2px frame filter');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=neg'), 'description' => 'Negative filter (inverted color)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=th|105', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=mask|mask04.png&fltr[]=th|105&f=png'), 'description' => 'Threshold filter; showing preserved alpha channel');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_levels.'&w=200', 'phpThumb.php?src='.$img_levels.'&w=200&fltr[]=lvl'), 'description' => 'original vs. Levels filter (default settings)');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=300&fltr[]=hist|rgb', 'phpThumb.php?src='.$img_levels.'&w=200&fltr[]=hist|*'), 'description' => 'histograms of RGB vs grayscale');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_portrait.'&w=200&fltr[]=over|frame1.png|0', 'phpThumb.php?src='.$img_portrait.'&w=200&fltr[]=over|frame2.png|1'), 'description' => 'Overlay vs. Underlay<br><br>Original over/under images:<br><table border="0"><tr><td style="padding: 20px; background-image: url(lrock011.jpg);"><img src="frame1.png"> <img src="frame2.png"></td></tr></table>');
$Examples[] = array('getstrings' => array('phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=wmt|phpThumb|18|B|FF0000|loki.ttf|100|5|20&f=png', 'phpThumb.php?src='.$img_landscape.'&w=200&fltr[]=wmt|copyright+2004|3|BR|00FFFF||50&f=png'), 'description' => 'Text overlay, TTF and built-in fonts');
$Examples[] = array('getstrings' => array('phpThumb.php?new=FF0000&w=100&h=50&fltr[]=bvl|10&fltr[]=wmt|hello|14|C|00FFFF|arial.ttf&f=png', 'phpThumb.php?new=FF0000|25&w=150&h=50&fltr[]=bvl|10&fltr[]=wmt|25%+opaque|14|C|0066FF|arial.ttf&f=png'), 'description' => 'Image created with "new", red background, bevel, TTF text');

$Examples[] = array('getstrings' => array('phpThumb.php?src=winnt.bmp&w=200'), 'description' => 'BMP source, width=200px');
$Examples[] = array('getstrings' => array('phpThumb.php?src=1024-none.tiff&w=200'), 'description' => 'TIFF source, width=200px');
$Examples[] = array('getstrings' => array('phpThumb.php?src=globe.wmf&w=200'), 'description' => 'WMF source, width=200px');
//$Examples[] = array('getstrings' => array(''), 'description' => '');

foreach ($Examples as $key => $ExamplesArray) {
	echo '<a href="#" name="'.$key.'" title="click to get URL link for example #'.$key.'" onClick="prompt(\'Here is the to example #'.$key.'\', \'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'].'#'.$key.'\'); return false;">#</a>';
	echo '<table border="0"><tr><td style="padding: 20px; background-image: url(lrock011.jpg);">';
	foreach ($ExamplesArray['getstrings'] as $GETstring) {
		echo '<a href="'.$GETstring.'&down='.urlencode($GETstring).'.jpg">';
		echo '<img border="0" src="'.$GETstring.'">';
		echo '</a> ';
	}
	echo '</td></tr></table>';
	echo '<xmp><img src="'.implode('">'."\n".'<img src="', $ExamplesArray['getstrings']).'"></xmp>';
	echo $ExamplesArray['description'].'<br>';
	echo '<br><br><hr size="1">';
}
?>

<a name="showpic"></a>
<b>Demo of <i>phpThumb.demo.showpic.php</i></b><br>
<br>
Small picture (500x333), window opened at wrong size (640x480):<br>
<a href="javascript:void(0);" onClick="window.open('phpThumb.demo.showpic.php?src=kayak.jpg&title=This+is+a+small+picture', 'showpic1', 'width=640,height=480,resizable=no,status=no,menubar=no,toolbar=no,scrollbars=no');">
<img src="phpThumb.php?src=kayak.jpg&w=100" border="2"></a><br>
<br>
Big picture (2272x1704), window opened at wrong size (640x480):<br>
<a href="javascript:void(0);" onClick="window.open('phpThumb.demo.showpic.php?src=big.jpg&title=This+is+a+big+picture', 'showpic2', 'width=640,height=480,resizable=yes,status=no,menubar=no,toolbar=no,scrollbars=no');">
<img src="phpThumb.php?src=big.jpg&w=100" border="2"></a><br>
<br>

</body>
</html>