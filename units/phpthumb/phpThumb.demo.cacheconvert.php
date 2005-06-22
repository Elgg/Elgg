<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// See: phpthumb.readme.txt for usage instructions          //
//                                                         ///
//////////////////////////////////////////////////////////////
//                                                          //
// phpThumb.demo.cacheconvert.php                           //
// James Heinrich <info@silisoftware.com>                   //
// 28 June 2004                                             //
//                                                          //
// phpThumb() cache filename converter                      //
// based on an idea by Josh Gruenberg (joshgÿtwcny*rr*com)  //
// Can convert cached files from phpThumb() v1.2.4 and      //
// newer (v1.1.2 introduced caching, but the cached         //
// filenames weren't structured in a manner suitable for    //
// conversion until v1.2.4)                                 //
//                                                          //
//////////////////////////////////////////////////////////////

function RenameFileIfNeccesary($oldfilename) {
	static $FilenameParameters = array('h', 'w', 'sx', 'sy', 'sw', 'sh', 'bw', 'brx', 'bry', 'bg', 'bgt', 'bc', 'usa', 'usr', 'ust', 'wmf', 'wmp', 'wmm', 'wma', 'xto', 'ra', 'ar', 'iar', 'maxb');
	$FileData = array();
	$oldbasefilename = basename($oldfilename);

	$output  = 'Found: <font color="blue"><b>'.htmlentities($oldfilename, ENT_QUOTES).'</b></font><br>';

	if (eregi('^phpThumb_cache\.(.*)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.(jpeg|png|gif)$', $oldbasefilename, $matches)) {

		$output .= '<font color="darkgreen">matched filename structure for v1.2.4 - v1.3.1</font><br>';

		// v1.2.4
		$FileData['src']       = urldecode($matches[1]);
		$FileData['h']         = (($matches[2] > 0) ? $matches[2] : null);
		$FileData['w']         = (($matches[3] > 0) ? $matches[3] : null);
		$FileData['sx']        = (($matches[4] > 0) ? $matches[4] : null);
		$FileData['sy']        = (($matches[5] > 0) ? $matches[5] : null);
		$FileData['sw']        = (($matches[6] > 0) ? $matches[6] : null);
		$FileData['sh']        = (($matches[7] > 0) ? $matches[7] : null);
		$FileData['filemtime'] = $matches[8];
		$FileData['q']         = (($matches[9] == 75) ? null : $matches[9]);
		$FileData['format']    = $matches[10];

	} elseif (eregi('^phpThumb_cache\.(.*)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.(.*)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.([0-9]+)\.(jpeg|png|gif)$', $oldbasefilename, $matches)) {

		$output .= '<font color="darkgreen">matched filename structure for v1.3.2 - v1.3.5</font><br>';

		// v1.3.2:
		$FileData['src']       = urldecode($matches[1]);
		$FileData['h']         = (($matches[2] > 0) ? $matches[2] : null);
		$FileData['w']         = (($matches[3] > 0) ? $matches[3] : null);
		$FileData['sx']        = (($matches[4] > 0) ? $matches[4] : null);
		$FileData['sy']        = (($matches[5] > 0) ? $matches[5] : null);
		$FileData['sw']        = (($matches[6] > 0) ? $matches[6] : null);
		$FileData['sh']        = (($matches[7] > 0) ? $matches[7] : null);
		$FileData['bw']        = (($matches[8] > 0) ? $matches[8] : null);
		$FileData['bg']        = null;           // cached filename value was incorrectly stored
		$FileData['bc']        = null;           // cached filename value was incorrectly stored
		$FileData['usa']       = (($matches[11] > 0) ? $matches[11] : null);
		$FileData['usr']       = (($matches[12] > 0) ? $matches[12] : null);
		$FileData['ust']       = (($matches[13] > 0) ? $matches[13] : null);
		$FileData['wmf']       = null;           // cached filename value was incorrectly stored
		$FileData['wmp']       = null;           // cached filename value was ok, but 'wmf' was incorrectly stored making this useless
		$FileData['wmm']       = null;           // cached filename value was ok, but 'wmf' was incorrectly stored making this useless
		$FileData['wma']       = null;           // cached filename value was incorrectly stored
		$FileData['filemtime'] = $matches[18];
		$FileData['quality']   = $matches[19];
		$FileData['format']    = $matches[20];

	} elseif (eregi('^phpThumb_cache\.(.*)\.([0-9]+)\.([0-9]+)\.(jpeg|png|gif)$', $oldbasefilename, $matches)) {

		$output .= '<font color="darkgreen">matched filename structure for v1.3.6 - v1.4.0</font><br>';

		// v1.3.6:
		$SRCandParameters = $matches[1];
		foreach ($FilenameParameters as $parameter) {
			$SRCandParameters = str_replace('.'.$parameter, "\t".$parameter, $SRCandParameters);
		}
		$SRCandParametersArray = explode("\t", $SRCandParameters);
		$FileData['src'] = urldecode(array_shift($SRCandParametersArray));
		for ($i = 4; $i >= 1; $i--) {
			$MatchedKeys = array();
			foreach ($SRCandParametersArray as $key => $parametervaluepair) {
				if (in_array(substr($parametervaluepair, 0, $i), $FilenameParameters)) {
					$FileData[substr($parametervaluepair, 0, $i)] = substr($parametervaluepair, $i);
					$MatchedKeys[] = $key;
				}
			}
			foreach ($MatchedKeys as $key) {
				unset($SRCandParametersArray[$key]);
			}
			if (empty($SRCandParametersArray)) {
				break;
			}
		}

		$FileData['filemtime'] = $matches[2];
		$FileData['quality']   = $matches[3];
		$FileData['format']    = $matches[4];

	} elseif (eregi('^phpThumb_cache_(.*)_([0-9]+)_([0-9]+)_(jpeg|png|gif)$', $oldbasefilename, $matches)) {

		$output .= '<font color="darkgreen">matched filename structure for v1.4.1 - v1.4.5</font><br>';

		// v1.4.1:
		$SRCandParameters = $matches[1];
		foreach ($FilenameParameters as $parameter) {
			$SRCandParameters = str_replace('_'.$parameter, "\t".$parameter, $SRCandParameters);
		}
		$SRCandParametersArray = explode("\t", $SRCandParameters);
		$FileData['src'] = urldecode(array_shift($SRCandParametersArray));
		for ($i = 4; $i >= 1; $i--) {
			$MatchedKeys = array();
			foreach ($SRCandParametersArray as $key => $parametervaluepair) {
				if (in_array(substr($parametervaluepair, 0, $i), $FilenameParameters)) {
					$FileData[substr($parametervaluepair, 0, $i)] = substr($parametervaluepair, $i);
					$MatchedKeys[] = $key;
				}
			}
			foreach ($MatchedKeys as $key) {
				unset($SRCandParametersArray[$key]);
			}
			if (empty($SRCandParametersArray)) {
				break;
			}
		}

		// unneccesary default values removed in v1.4.6
		if (@$FileData['sx'] == '0') {
			unset($FileData['sx']);
		}
		if (@$FileData['sy'] == '0') {
			unset($FileData['sy']);
		}
		if (@$FileData['bg'] == 'FFFFFF') {
			unset($FileData['bg']);
		}
		if (@$FileData['bc'] == '000000') {
			unset($FileData['bc']);
		}
		if (@$FileData['wmp'] == '50') {
			unset($FileData['wmp']);
		}
		if (@$FileData['wmm'] == '5') {
			unset($FileData['wmm']);
		}
		if (@$FileData['wma'] == 'BR') {
			unset($FileData['wma']);
		}
		if (@$FileData['iar'] == '') {
			unset($FileData['iar']);
		}

		$FileData['filemtime'] = $matches[2];
		$FileData['quality']   = $matches[3];
		$FileData['format']    = $matches[4];


	} elseif (eregi('^phpThumb_cache_(.*)_([0-9]+)_q([0-9]+)_(jpeg|png|gif)$', $oldbasefilename, $matches)) {

		$output .= '<font color="green">matched filename structure for v1.4.6+ (no need to rename)</font><br>';

	} else {

		$output .= '<font color="orange">did not match any know filename structure (although could be from v1.1.2 - v1.2.3) - cannot use this file</font><br>';

	}

	if (!empty($FileData)) {
		// v1.4.6 onwards
		$cache_filename  = 'phpThumb_cache';
		$cache_filename .= '_'.urlencode($FileData['src']);
		foreach ($FilenameParameters as $key) {
			if (isset($FileData[$key])) {
				$cache_filename .= '_'.$key.$FileData[$key];
			}
		}
		$cache_filename .= '_'.$FileData['filemtime'];
		$cache_filename .= '_q'.$FileData['quality'];
		$cache_filename .= '_'.$FileData['format'];

		$output .= 'attempting to rename to "'.htmlentities($cache_filename, ENT_QUOTES).'"<br>';
		if (file_exists(dirname($oldfilename).'/'.$cache_filename)) {

			$output .= '<font color="red">destination file already exists! cannot rename</a><br><br>';
			echo $output;
			return false;

		} elseif (rename($oldfilename, dirname($oldfilename).'/'.$cache_filename)) {

			$output .= '<font color="green">success!</a><br><br>';
			echo $output;
			return true;

		}
		$output .= '<font color="red">failed to rename! (check permissions?)</a><br><br>';
		echo $output;
		return false;
	}
	$output .= '<font color="orange">not renaming this file</font><br><br>';
	echo $output;
	return true;
}


echo '<html><head><title>phpThumb() cache converter</title></head><body style="font-family: sans-serif; font-size: 9pt;">';

if (!empty($_POST['cachedir'])) {
	$cachedir = realpath($_POST['cachedir']);
	$skipped = 0;
	if (is_dir($cachedir)) {
		if ($dir = opendir($cachedir)) {
			echo 'Processing directory <b>'.htmlentities($cachedir).'</b><br><br>';
			while ($fileName = readdir($dir)) {
				if (ereg('^phpThumb_cache', $fileName)) {
					RenameFileIfNeccesary($cachedir.'/'.$fileName);
				} elseif (!is_dir($cachedir.'/'.$fileName)) {
					$skipped++;
				}
			}
		} else {
			echo 'Cannot open directory "<b>'.htmlentities($cachedir).'</b>"<br>';
		}
	} else {
		echo '"<b>'.htmlentities($cachedir).'</b>" is not a directory!<br>';
	}
	if ($skipped > 0) {
		echo '<i>skipped '.$skipped.' files</i><br>';
	}
	echo '<hr>';
}

echo '<form method="post" action="'.$_SERVER['PHP_SELF'].'">';
echo 'Enter the directory you wish to convert from old-style phpThumb() cache filenames to the current naming standard:<br>';
echo '<input type="text" name="cachedir" value="'.@$_POST['cachedir'].'"> ';
echo '<input type="submit" value="Convert">';
echo '</form></body></html>';

?>