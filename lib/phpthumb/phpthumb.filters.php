<?php
//////////////////////////////////////////////////////////////
///  phpThumb() by James Heinrich <info@silisoftware.com>   //
//        available at http://phpthumb.sourceforge.net     ///
//////////////////////////////////////////////////////////////
///                                                         //
// phpthumb.filters.php - image processing filter functions //
//                                                         ///
//////////////////////////////////////////////////////////////

class phpthumb_filters {

	var $phpThumbObject = null;

	function phpthumb_filters() {
		return true;
	}

	function ApplyMask(&$gdimg_mask, &$gdimg_image) {
		if (phpthumb_functions::gd_version() < 2) {
			$this->DebugMessage('Skipping ApplyMask() because gd_version is "'.phpthumb_functions::gd_version().'"', __FILE__, __LINE__);
			return false;
		}
		if (phpthumb_functions::version_compare_replacement(phpversion(), '4.3.2', '>=')) {

			$this->DebugMessage('Using alpha ApplyMask() technique', __FILE__, __LINE__);
			if ($gdimg_mask_resized = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg_image), ImageSY($gdimg_image))) {

				ImageCopyResampled($gdimg_mask_resized, $gdimg_mask, 0, 0, 0, 0, ImageSX($gdimg_image), ImageSY($gdimg_image), ImageSX($gdimg_mask), ImageSY($gdimg_mask));
				if ($gdimg_mask_blendtemp = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg_image), ImageSY($gdimg_image))) {

					$color_background = ImageColorAllocate($gdimg_mask_blendtemp, 0, 0, 0);
					ImageFilledRectangle($gdimg_mask_blendtemp, 0, 0, ImageSX($gdimg_mask_blendtemp), ImageSY($gdimg_mask_blendtemp), $color_background);
					ImageAlphaBlending($gdimg_mask_blendtemp, false);
					ImageSaveAlpha($gdimg_mask_blendtemp, true);
					for ($x = 0; $x < ImageSX($gdimg_image); $x++) {
						for ($y = 0; $y < ImageSY($gdimg_image); $y++) {
							//$RealPixel = phpthumb_functions::GetPixelColor($gdimg_mask_blendtemp, $x, $y);
							$RealPixel = phpthumb_functions::GetPixelColor($gdimg_image, $x, $y);
							$MaskPixel = phpthumb_functions::GrayscalePixel(phpthumb_functions::GetPixelColor($gdimg_mask_resized, $x, $y));
							$MaskAlpha = 127 - (floor($MaskPixel['red'] / 2) * (1 - ($RealPixel['alpha'] / 127)));
							$newcolor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_mask_blendtemp, $RealPixel['red'], $RealPixel['green'], $RealPixel['blue'], $MaskAlpha);
							ImageSetPixel($gdimg_mask_blendtemp, $x, $y, $newcolor);
						}
					}
					ImageAlphaBlending($gdimg_image, false);
					ImageSaveAlpha($gdimg_image, true);
					ImageCopy($gdimg_image, $gdimg_mask_blendtemp, 0, 0, 0, 0, ImageSX($gdimg_mask_blendtemp), ImageSY($gdimg_mask_blendtemp));
					ImageDestroy($gdimg_mask_blendtemp);

				} else {
					$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
				}
				ImageDestroy($gdimg_mask_resized);

			} else {
				$this->DebugMessage('ImageCreateFunction() failed', __FILE__, __LINE__);
			}

		} else {
			// alpha merging requires PHP v4.3.2+
			$this->DebugMessage('Skipping ApplyMask() technique because PHP is v"'.phpversion().'"', __FILE__, __LINE__);
		}
		return true;
	}


	function Bevel(&$gdimg, $width, $hexcolor1, $hexcolor2) {
		$width     = ($width     ? $width     : 5);
		$hexcolor1 = ($hexcolor1 ? $hexcolor1 : 'FFFFFF');
		$hexcolor2 = ($hexcolor2 ? $hexcolor2 : '000000');

		ImageAlphaBlending($gdimg, true);
		for ($i = 0; $i < $width; $i++) {
			$alpha = round(($i / $width) * 127);
			$color1[$i] = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor1, false, $alpha);
			$color2[$i] = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor2, false, $alpha);

			ImageLine($gdimg,                   $i,                   $i,                   $i, ImageSY($gdimg) - $i, $color1[$i]); // left
			ImageLine($gdimg,                   $i,                   $i, ImageSX($gdimg) - $i,                   $i, $color1[$i]); // top
			ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i, ImageSX($gdimg) - $i,                   $i, $color2[$i]); // right
			ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i,                   $i, ImageSY($gdimg) - $i, $color2[$i]); // bottom
		}
		return true;
	}


	function Blur(&$gdimg, $radius=0.5) {
		// Taken from Torstein Hønsi's phpUnsharpMask (see phpthumb.unsharp.php)

		$radius = round(max(0, min($radius, 50)) * 2);
		if (!$radius) {
			return false;
		}

		$w = ImageSX($gdimg);
		$h = ImageSY($gdimg);
		if ($imgBlur = ImageCreateTrueColor($w, $h)) {
			// Gaussian blur matrix:
			//	1	2	1
			//	2	4	2
			//	1	2	1

			// Move copies of the image around one pixel at the time and merge them with weight
			// according to the matrix. The same matrix is simply repeated for higher radii.
			for ($i = 0; $i < $radius; $i++)	{
				ImageCopy     ($imgBlur, $gdimg, 0, 0, 1, 1, $w - 1, $h - 1);            // up left
				ImageCopyMerge($imgBlur, $gdimg, 1, 1, 0, 0, $w,     $h,     50.00000);  // down right
				ImageCopyMerge($imgBlur, $gdimg, 0, 1, 1, 0, $w - 1, $h,     33.33333);  // down left
				ImageCopyMerge($imgBlur, $gdimg, 1, 0, 0, 1, $w,     $h - 1, 25.00000);  // up right
				ImageCopyMerge($imgBlur, $gdimg, 0, 0, 1, 0, $w - 1, $h,     33.33333);  // left
				ImageCopyMerge($imgBlur, $gdimg, 1, 0, 0, 0, $w,     $h,     25.00000);  // right
				ImageCopyMerge($imgBlur, $gdimg, 0, 0, 0, 1, $w,     $h - 1, 20.00000);  // up
				ImageCopyMerge($imgBlur, $gdimg, 0, 1, 0, 0, $w,     $h,     16.666667); // down
				ImageCopyMerge($imgBlur, $gdimg, 0, 0, 0, 0, $w,     $h,     50.000000); // center
				ImageCopy     ($gdimg, $imgBlur, 0, 0, 0, 0, $w,     $h);
			}
			return true;
		}
		return false;
	}


	function BlurGaussian(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_GAUSSIAN_BLUR)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_GAUSSIAN_BLUR)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		$this->DebugMessage('FAILED: phpthumb_filters::BlurGaussian($gdimg) [using phpthumb_filters::Blur() instead]', __FILE__, __LINE__);
		return phpthumb_filters::Blur($gdimg, 0.5);
	}


	function BlurSelective(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_SELECTIVE_BLUR)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_SELECTIVE_BLUR)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::BlurSelective($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	function Brightness(&$gdimg, $amount=0) {
		if ($amount == 0) {
			return true;
		}
		$amount = max(-255, min(255, $amount));

		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_BRIGHTNESS, $amount)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_BRIGHTNESS, '.$amount.')', __FILE__, __LINE__);
			// fall through and try it the hard way
		}

		$scaling = (255 - abs($amount)) / 255;
		$baseamount = (($amount > 0) ? $amount : 0);
		for ($x = 0; $x < ImageSX($gdimg); $x++) {
			for ($y = 0; $y < ImageSY($gdimg); $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				foreach ($OriginalPixel as $key => $value) {
					$NewPixel[$key] = round($baseamount + ($OriginalPixel[$key] * $scaling));
				}
				$newColor = ImageColorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	function Contrast(&$gdimg, $amount=0) {
		if ($amount == 0) {
			return true;
		}
		$amount = max(-255, min(255, $amount));

		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_CONTRAST, $amount)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_CONTRAST, '.$amount.')', __FILE__, __LINE__);
			// fall through and try it the hard way
		}

		if ($amount > 0) {
			$scaling = 1 + ($amount / 255);
		} else {
			$scaling = (255 - abs($amount)) / 255;
		}
		for ($x = 0; $x < ImageSX($gdimg); $x++) {
			for ($y = 0; $y < ImageSY($gdimg); $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				foreach ($OriginalPixel as $key => $value) {
					$NewPixel[$key] = min(255, max(0, round($OriginalPixel[$key] * $scaling)));
				}
				$newColor = ImageColorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
	}


	function Colorize(&$gdimg, $amount, $targetColor) {
		$amount      = (is_numeric($amount)                          ? $amount      : 25);
		$targetColor = (phpthumb_functions::IsHexColor($targetColor) ? $targetColor : 'gray');

		if ($amount == 0) {
			return true;
		}

		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if ($targetColor == 'gray') {
				$targetColor = '808080';
			}
			$r = substr($targetColor, 0, 2);
			$g = substr($targetColor, 2, 2);
			$b = substr($targetColor, 4, 2);
			if (ImageFilter($gdimg, IMG_FILTER_COLORIZE, $r, $g, $b)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_COLORIZE)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}

		// overridden below for grayscale
		if ($targetColor != 'gray') {
			$TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
			$TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
			$TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));
		}

		for ($x = 0; $x < ImageSX($gdimg); $x++) {
			for ($y = 0; $y < ImageSY($gdimg); $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				if ($targetColor == 'gray') {
					$TargetPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);
				}
				foreach ($TargetPixel as $key => $value) {
					$NewPixel[$key] = round(max(0, min(255, ($OriginalPixel[$key] * ((100 - $amount) / 100)) + ($TargetPixel[$key] * ($amount / 100)))));
				}
				//$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
				$newColor = ImageColorAllocate($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue']);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	function Crop(&$gdimg, $left=0, $right=0, $top=0, $bottom=0) {
		if (!$left && !$right && !$top && !$bottom) {
			return true;
		}
		$oldW = ImageSX($gdimg);
		$oldH = ImageSY($gdimg);
		if (($left   > 0) && ($left   < 1)) { $left   = round($left   * $oldW); }
		if (($right  > 0) && ($right  < 1)) { $right  = round($right  * $oldW); }
		if (($top    > 0) && ($top    < 1)) { $top    = round($top    * $oldH); }
		if (($bottom > 0) && ($bottom < 1)) { $bottom = round($bottom * $oldH); }
		$right  = min($oldW - $left - 1, $right);
		$bottom = min($oldH - $top  - 1, $bottom);
		$newW = $oldW - $left - $right;
		$newH = $oldH - $top  - $bottom;

		if ($imgCropped = ImageCreateTrueColor($newW, $newH)) {
			ImageCopy($imgCropped, $gdimg, 0, 0, $left, $top, $newW, $newH);
			if ($gdimg = ImageCreateTrueColor($newW, $newH)) {
				ImageCopy($gdimg, $imgCropped, 0, 0, 0, 0, $newW, $newH);
				ImageDestroy($imgCropped);
				return true;
			}
			ImageDestroy($imgCropped);
		}
		return false;
	}


	function Desaturate(&$gdimg, $amount, $color='') {
		if ($amount == 0) {
			return true;
		}
		return phpthumb_filters::Colorize($gdimg, $amount, (phpthumb_functions::IsHexColor($color) ? $color : 'gray'));
	}


	function DropShadow(&$gdimg, $distance, $width, $hexcolor, $angle, $fade) {
		if (phpthumb_functions::gd_version() < 2) {
			return false;
		}
		$distance = ($distance ? $distance : 10);
		$width    = ($width    ? $width    : 10);
		$hexcolor = ($hexcolor ? $hexcolor : '000000');
		$angle    = ($angle    ? $angle    : 225);
		$fade     = ($fade     ? $fade     : 1);

		$width_shadow  = cos(deg2rad($angle)) * ($distance + $width);
		$height_shadow = sin(deg2rad($angle)) * ($distance + $width);

		$scaling = min(ImageSX($gdimg) / (ImageSX($gdimg) + abs($width_shadow)), ImageSY($gdimg) / (ImageSY($gdimg) + abs($height_shadow)));

		for ($i = 0; $i < $width; $i++) {
			$WidthAlpha[$i] = (abs(($width / 2) - $i) / $width) * $fade;
			$Offset['x'] = cos(deg2rad($angle)) * ($distance + $i);
			$Offset['y'] = sin(deg2rad($angle)) * ($distance + $i);
		}

		$tempImageWidth  = ImageSX($gdimg)  + abs($Offset['x']);
		$tempImageHeight = ImageSY($gdimg) + abs($Offset['y']);

		if ($gdimg_dropshadow_temp = phpthumb_functions::ImageCreateFunction($tempImageWidth, $tempImageHeight)) {

			ImageAlphaBlending($gdimg_dropshadow_temp, false);
			ImageSaveAlpha($gdimg_dropshadow_temp, true);
			$transparent1 = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_dropshadow_temp, 0, 0, 0, 127);
			ImageFill($gdimg_dropshadow_temp, 0, 0, $transparent1);

			for ($x = 0; $x < ImageSX($gdimg); $x++) {
				for ($y = 0; $y < ImageSY($gdimg); $y++) {
					$PixelMap[$x][$y] = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				}
			}
			for ($x = 0; $x < $tempImageWidth; $x++) {
				for ($y = 0; $y < $tempImageHeight; $y++) {
					//for ($i = 0; $i < $width; $i++) {
					for ($i = 0; $i < 1; $i++) {
						if (!isset($PixelMap[$x][$y]['alpha']) || ($PixelMap[$x][$y]['alpha'] > 0)) {
							if (isset($PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha']) && ($PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha'] < 127)) {
								$thisColor = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor, false, $PixelMap[$x + $Offset['x']][$y + $Offset['y']]['alpha']);
								ImageSetPixel($gdimg_dropshadow_temp, $x, $y, $thisColor);
							}
						}
					}
				}
			}

			ImageAlphaBlending($gdimg_dropshadow_temp, true);
			for ($x = 0; $x < ImageSX($gdimg); $x++) {
				for ($y = 0; $y < ImageSY($gdimg); $y++) {
					if ($PixelMap[$x][$y]['alpha'] < 127) {
						$thisColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_dropshadow_temp, $PixelMap[$x][$y]['red'], $PixelMap[$x][$y]['green'], $PixelMap[$x][$y]['blue'], $PixelMap[$x][$y]['alpha']);
						ImageSetPixel($gdimg_dropshadow_temp, $x, $y, $thisColor);
					}
				}
			}

			ImageSaveAlpha($gdimg, true);
			ImageAlphaBlending($gdimg, false);
			//$this->is_alpha = true;
			$transparent2 = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0, 0, 0, 127);
			ImageFilledRectangle($gdimg, 0, 0, ImageSX($gdimg), ImageSY($gdimg), $transparent2);
			ImageCopyResampled($gdimg, $gdimg_dropshadow_temp, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg), ImageSX($gdimg_dropshadow_temp), ImageSY($gdimg_dropshadow_temp));

			ImageDestroy($gdimg_dropshadow_temp);
		}
		return true;
	}


	function EdgeDetect(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_EDGEDETECT)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_EDGEDETECT)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::EdgeDetect($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	function Elipse($gdimg) {
		if (phpthumb_functions::gd_version() < 2) {
			return false;
		}
		// generate mask at twice desired resolution and downsample afterwards for easy antialiasing
		if ($gdimg_elipsemask_double = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg) * 2, ImageSY($gdimg) * 2)) {
			if ($gdimg_elipsemask = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg), ImageSY($gdimg))) {

				$color_transparent = ImageColorAllocate($gdimg_elipsemask_double, 255, 255, 255);
				ImageFilledEllipse($gdimg_elipsemask_double, ImageSX($gdimg), ImageSY($gdimg), (ImageSX($gdimg) - 1) * 2, (ImageSY($gdimg) - 1) * 2, $color_transparent);
				ImageCopyResampled($gdimg_elipsemask, $gdimg_elipsemask_double, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg), ImageSX($gdimg) * 2, ImageSY($gdimg) * 2);

				phpthumb_filters::ApplyMask($gdimg_elipsemask, $gdimg);
				ImageDestroy($gdimg_elipsemask);
				return true;

			} else {
				$this->DebugMessage('$gdimg_elipsemask = phpthumb_functions::ImageCreateFunction() failed', __FILE__, __LINE__);
			}
			ImageDestroy($gdimg_elipsemask_double);
		} else {
			$this->DebugMessage('$gdimg_elipsemask_double = phpthumb_functions::ImageCreateFunction() failed', __FILE__, __LINE__);
		}
		return false;
	}


	function Emboss(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_EMBOSS)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_EMBOSS)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::Emboss($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	function Flip(&$gdimg, $x=false, $y=false) {
		if (!$x && !$y) {
			return false;
		}
		if ($tempImage = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg), ImageSY($gdimg))) {
			if ($x) {
				ImageCopy($tempImage, $gdimg, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg));
				for ($x = 0; $x < ImageSX($gdimg); $x++) {
					ImageCopy($gdimg, $tempImage, ImageSX($gdimg) - 1 - $x, 0, $x, 0, 1, ImageSY($gdimg));
				}
			}
			if ($y) {
				ImageCopy($tempImage, $gdimg, 0, 0, 0, 0, ImageSX($gdimg), ImageSY($gdimg));
				for ($y = 0; $y < ImageSY($gdimg); $y++) {
					ImageCopy($gdimg, $tempImage, 0, ImageSY($gdimg) - 1 - $y, 0, $y, ImageSX($gdimg), 1);
				}
			}
			ImageDestroy($tempImage);
		}
		return true;
	}


	function Frame(&$gdimg, $frame_width, $edge_width, $hexcolor_frame, $hexcolor1, $hexcolor2) {
		$frame_width    = ($frame_width    ? $frame_width    : 5);
		$edge_width     = ($edge_width     ? $edge_width     : 1);
		$hexcolor_frame = ($hexcolor_frame ? $hexcolor_frame : 'CCCCCC');
		$hexcolor1      = ($hexcolor1      ? $hexcolor1      : 'FFFFFF');
		$hexcolor2      = ($hexcolor2      ? $hexcolor2      : '000000');

		$color_frame = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor_frame);
		$color1      = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor1);
		$color2      = phpthumb_functions::ImageHexColorAllocate($gdimg, $hexcolor2);
		for ($i = 0; $i < $edge_width; $i++) {
			// outer bevel
			ImageLine($gdimg,                   $i,                   $i,                   $i, ImageSY($gdimg) - $i, $color1); // left
			ImageLine($gdimg,                   $i,                   $i, ImageSX($gdimg) - $i,                   $i, $color1); // top
			ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i, ImageSX($gdimg) - $i,                   $i, $color2); // right
			ImageLine($gdimg, ImageSX($gdimg) - $i, ImageSY($gdimg) - $i,                   $i, ImageSY($gdimg) - $i, $color2); // bottom
		}
		for ($i = 0; $i < $frame_width; $i++) {
			// actual frame
			ImageRectangle($gdimg, $edge_width + $i, $edge_width + $i, ImageSX($gdimg) - $edge_width - $i, ImageSY($gdimg) - $edge_width - $i, $color_frame);
		}
		for ($i = 0; $i < $edge_width; $i++) {
			// inner bevel
			ImageLine($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, $color2); // left
			ImageLine($gdimg,                   $frame_width + $edge_width + $i,                   $frame_width + $edge_width + $i, ImageSX($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color2); // top
			ImageLine($gdimg, ImageSX($gdimg) - $frame_width - $edge_width - $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, ImageSX($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, $color1); // right
			ImageLine($gdimg, ImageSX($gdimg) - $frame_width - $edge_width - $i, ImageSY($gdimg) - $frame_width - $edge_width - $i,                   $frame_width + $edge_width + $i, ImageSY($gdimg) - $frame_width - $edge_width - $i, $color1); // bottom
		}
		return true;
	}


	function Gamma(&$gdimg, $amount) {
		if (number_format($amount, 4) == '1.0000') {
			return true;
		}
		return ImageGammaCorrect($gdimg, 1.0, $amount);
	}


	function Grayscale(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_GRAYSCALE)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_GRAYSCALE)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		return phpthumb_filters::Colorize($gdimg, 100, 'gray');
	}


	function HistogramAnalysis(&$gdimg, $calculateGray=false) {
		$ImageSX = ImageSX($gdimg);
		$ImageSY = ImageSY($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				@$Analysis['red'][$OriginalPixel['red']]++;
				@$Analysis['green'][$OriginalPixel['green']]++;
				@$Analysis['blue'][$OriginalPixel['blue']]++;
				@$Analysis['alpha'][$OriginalPixel['alpha']]++;
				if ($calculateGray) {
					$GrayPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);
					@$Analysis['gray'][$GrayPixel['red']]++;
				}
			}
		}
		$keys = array('red', 'green', 'blue', 'alpha');
		if ($calculateGray) {
			$keys[] = 'gray';
		}
		foreach ($keys as $dummy => $key) {
			ksort($Analysis[$key]);
		}
		return $Analysis;
	}


	function HistogramStretch(&$gdimg, $band='*', $min=-1, $max=-1) {
		// equivalent of "Auto Contrast" in Adobe Photoshop

		$Analysis = phpthumb_filters::HistogramAnalysis($gdimg, true);
		$keys = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'alpha', '*'=>'gray');
		if (!isset($keys[$band])) {
			return false;
		}
		$key = $keys[$band];

		// If the absolute brightest and darkest pixels are used then one random
		// pixel in the image could throw off the whole system. Instead, count up/down
		// from the limit and allow 0.1% of brightest/darkest pixels to be clipped to min/max
		$clip_threshold = ImageSX($gdimg) * ImageSX($gdimg) * 0.001;
		if ($min >= 0) {
			$range_min = min($min, 255);
		} else {
			$countsum = 0;
			for ($i = 0; $i <= 255; $i++) {
				$countsum += @$Analysis[$key][$i];
				if ($countsum >= $clip_threshold) {
					$range_min = $i - 1;
					break;
				}
			}
			$range_min = max($range_min, 0);
		}
		if ($max >= 0) {
			$range_max = max($max, 255);
		} else {
			$countsum = 0;
			$threshold = ImageSX($gdimg) * ImageSX($gdimg) * 0.001; // 0.1% of brightest and darkest pixels can be clipped
			for ($i = 255; $i >= 0; $i--) {
				$countsum += @$Analysis[$key][$i];
				if ($countsum >= $clip_threshold) {
					$range_max = $i + 1;
					break;
				}
			}
			$range_max = min($range_max, 255);
		}
		$range_scale = (($range_max == $range_min) ? 1 : (255 / ($range_max - $range_min)));
		if (($range_min == 0) && ($range_max == 255)) {
			// no adjustment neccesary - don't waste CPU time!
			return true;
		}

		$ImageSX = ImageSX($gdimg);
		$ImageSY = ImageSY($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				if ($band == '*') {
					$new['red']   = min(255, max(0, ($OriginalPixel['red']   - $range_min) * $range_scale));
					$new['green'] = min(255, max(0, ($OriginalPixel['green'] - $range_min) * $range_scale));
					$new['blue']  = min(255, max(0, ($OriginalPixel['blue']  - $range_min) * $range_scale));
					$new['alpha'] = min(255, max(0, ($OriginalPixel['alpha'] - $range_min) * $range_scale));
				} else {
					$new = $OriginalPixel;
					$new[$key] = min(255, max(0, ($OriginalPixel[$key] - $range_min) * $range_scale));
				}
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $new['red'], $new['green'], $new['blue'], $new['alpha']);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}

		return true;
	}


	function HistogramOverlay(&$gdimg, $bands='*', $colors='', $width=0.25, $height=0.25, $alignment='BR', $opacity=50, $margin=5) {
		$Analysis = phpthumb_filters::HistogramAnalysis($gdimg, true);

		$histW = round(($width > 1) ? min($width, ImageSX($gdimg)) : ImageSX($gdimg) * $width);
		$histH = round(($width > 1) ? min($width, ImageSX($gdimg)) : ImageSX($gdimg) * $width);
		if ($gdHist = ImageCreateTrueColor($histW, $histH)) {
			$color_back = phpthumb_functions::ImageColorAllocateAlphaSafe($gdHist, 0, 0, 0, 127);
			ImageFilledRectangle($gdHist, 0, 0, $histW, $histH, $color_back);
			ImageAlphaBlending($gdHist, false);
			ImageSaveAlpha($gdHist, true);

			if ($gdHistTemp = ImageCreateTrueColor(256, 100)) {
				$color_back_temp = phpthumb_functions::ImageColorAllocateAlphaSafe($gdHistTemp, 255, 0, 255, 127);
				ImageAlphaBlending($gdHistTemp, false);
				ImageSaveAlpha($gdHistTemp, true);
				ImageFilledRectangle($gdHistTemp, 0, 0, ImageSX($gdHistTemp), ImageSY($gdHistTemp), $color_back_temp);

				$DefaultColors = array('r'=>'FF0000', 'g'=>'00FF00', 'b'=>'0000FF', 'a'=>'999999', '*'=>'FFFFFF');
				$Colors = explode(';', $colors);
				$BandsToGraph = array_unique(preg_split('//', $bands));
				$keys = array('r'=>'red', 'g'=>'green', 'b'=>'blue', 'a'=>'alpha', '*'=>'gray');
				foreach ($BandsToGraph as $key => $band) {
					if (!isset($keys[$band])) {
						continue;
					}
					$PeakValue = max($Analysis[$keys[$band]]);
					$thisColor = phpthumb_functions::ImageHexColorAllocate($gdHistTemp, phpthumb_functions::IsHexColor(@$Colors[$key]) ? $Colors[$key] : $DefaultColors[$band]);
					$tempHeight = ImageSY($gdHistTemp);
					for ($x = 0; $x <= 255; $x++) {
						ImageLine($gdHistTemp, $x, $tempHeight - 1, $x, $tempHeight - 1 - round(@$Analysis[$keys[$band]][$x] / $PeakValue * $tempHeight), $thisColor);
					}
					ImageLine($gdHistTemp, 0, $tempHeight - 1, 255, $tempHeight - 1, $thisColor);
					ImageLine($gdHistTemp, 0, $tempHeight - 2, 255, $tempHeight - 2, $thisColor);
				}
				ImageCopyResampled($gdHist, $gdHistTemp, 0, 0, 0, 0, ImageSX($gdHist), ImageSY($gdHist), ImageSX($gdHistTemp), ImageSY($gdHistTemp));
				ImageDestroy($gdHistTemp);
			} else {
				return false;
			}

			phpthumb_filters::WatermarkOverlay($gdimg, $gdHist, $alignment, $opacity, $margin);
			ImageDestroy($gdHist);
			return true;
		}
		return false;
	}


	function ImageBorder(&$gdimg, $border_width, $radius_x, $radius_y, $hexcolor_border) {
		$border_width = ($border_width ? $border_width : 1);
		$radius_x     = ($radius_x     ? $radius_x     : 0);
		$radius_y     = ($radius_y     ? $radius_y     : 0);

		$output_width  = ImageSX($gdimg);
		$output_height = ImageSY($gdimg);

		list($new_width, $new_height) = phpthumb_functions::ProportionalResize($output_width, $output_height, $output_width - max($border_width * 2, $radius_x), $output_height - max($border_width * 2, $radius_y));
		$offset_x = ($radius_x ? $output_width  - $new_width  - $radius_x : 0);
		$offset_y = ($radius_y ? $output_height - $new_height - $radius_y : 0);

//header('Content-Type: image/png');
//ImagePNG($gdimg);
//exit;
		if ($gd_border_canvas = phpthumb_functions::ImageCreateFunction($output_width, $output_height)) {

			ImageSaveAlpha($gd_border_canvas, true);
			ImageAlphaBlending($gd_border_canvas, false);
			$color_background = phpthumb_functions::ImageColorAllocateAlphaSafe($gd_border_canvas, 255, 255, 255, 127);
			ImageFilledRectangle($gd_border_canvas, 0, 0, $output_width, $output_height, $color_background);

			$color_border = phpthumb_functions::ImageHexColorAllocate($gd_border_canvas, (phpthumb_functions::IsHexColor($hexcolor_border) ? $hexcolor_border : '000000'));

			for ($i = 0; $i < $border_width; $i++) {
				ImageLine($gd_border_canvas,             floor($offset_x / 2) + $radius_x,                      $i, $output_width - $radius_x - ceil($offset_x / 2),                         $i, $color_border); // top
				ImageLine($gd_border_canvas,             floor($offset_x / 2) + $radius_x, $output_height - 1 - $i, $output_width - $radius_x - ceil($offset_x / 2),    $output_height - 1 - $i, $color_border); // bottom
				ImageLine($gd_border_canvas,                    floor($offset_x / 2) + $i,               $radius_y,                      floor($offset_x / 2) +  $i, $output_height - $radius_y, $color_border); // left
				ImageLine($gd_border_canvas, $output_width - 1 - $i - ceil($offset_x / 2),               $radius_y,    $output_width - 1 - $i - ceil($offset_x / 2), $output_height - $radius_y, $color_border); // right
			}

			if ($radius_x && $radius_y) {

				// PHP bug: ImageArc() with thicknesses > 1 give bad/undesirable/unpredicatable results
				// Solution: Draw multiple 1px arcs side-by-side.

				// Problem: parallel arcs give strange/ugly antialiasing problems
				// Solution: draw non-parallel arcs, from one side of the line thickness at the start angle
				//   to the opposite edge of the line thickness at the terminating angle
				for ($thickness_offset = 0; $thickness_offset < $border_width; $thickness_offset++) {
					ImageArc($gd_border_canvas, floor($offset_x / 2) + 1 +                 $radius_x,              $thickness_offset - 1 + $radius_y, $radius_x * 2, $radius_y * 2, 180, 270, $color_border); // top-left
					ImageArc($gd_border_canvas,                     $output_width - $radius_x - 1 - ceil($offset_x / 2),              $thickness_offset - 1 + $radius_y, $radius_x * 2, $radius_y * 2, 270, 360, $color_border); // top-right
					ImageArc($gd_border_canvas,                     $output_width - $radius_x - 1 - ceil($offset_x / 2), $output_height - $thickness_offset - $radius_y, $radius_x * 2, $radius_y * 2,   0,  90, $color_border); // bottom-right
					ImageArc($gd_border_canvas, floor($offset_x / 2) + 1 +                 $radius_x, $output_height - $thickness_offset - $radius_y, $radius_x * 2, $radius_y * 2,  90, 180, $color_border); // bottom-left
				}
				if ($border_width > 1) {
					for ($thickness_offset = 0; $thickness_offset < $border_width; $thickness_offset++) {
						ImageArc($gd_border_canvas, floor($offset_x / 2) + $thickness_offset + $radius_x,                                      $radius_y, $radius_x * 2, $radius_y * 2, 180, 270, $color_border); // top-left
						ImageArc($gd_border_canvas, $output_width - $thickness_offset - $radius_x - 1 - ceil($offset_x / 2),                                      $radius_y, $radius_x * 2, $radius_y * 2, 270, 360, $color_border); // top-right
						ImageArc($gd_border_canvas, $output_width - $thickness_offset - $radius_x - 1 - ceil($offset_x / 2),                     $output_height - $radius_y, $radius_x * 2, $radius_y * 2,   0,  90, $color_border); // bottom-right
						ImageArc($gd_border_canvas, floor($offset_x / 2) + $thickness_offset + $radius_x,                     $output_height - $radius_y, $radius_x * 2, $radius_y * 2,  90, 180, $color_border); // bottom-left
					}
				}

			}
			$this->phpThumbObject->ImageResizeFunction($gd_border_canvas, $gdimg, floor(($output_width - $new_width) / 2), round(($output_height - $new_height) / 2), 0, 0, $new_width, $new_height, $output_width, $output_height);

			ImageDestroy($gdimg);
			$gdimg = phpthumb_functions::ImageCreateFunction($output_width, $output_height);
			ImageSaveAlpha($gdimg, true);
			ImageAlphaBlending($gdimg, false);
			$gdimg_color_background = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 255, 255, 255, 127);
			ImageFilledRectangle($gdimg, 0, 0, $output_width, $output_height, $gdimg_color_background);

			ImageCopy($gdimg, $gd_border_canvas, 0, 0, 0, 0, $output_width, $output_height);
			//$gdimg = $gd_border_canvas;
			ImageDestroy($gd_border_canvas);
			return true;


		} else {
			$this->DebugMessage('FAILED: $gd_border_canvas = phpthumb_functions::ImageCreateFunction('.$output_width.', '.$output_height.')', __FILE__, __LINE__);
		}
		return false;
	}


	function MeanRemoval(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_MEAN_REMOVAL)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_MEAN_REMOVAL)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::MeanRemoval($gdimg) [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	function Negative(&$gdimg) {
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_NEGATE)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_NEGATE)', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		$ImageSX = ImageSX($gdimg);
		$ImageSY = ImageSY($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, (~$currentPixel['red'] & 0xFF), (~$currentPixel['green'] & 0xFF), (~$currentPixel['blue'] & 0xFF), $currentPixel['alpha']);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	function RoundedImageCorners(&$gdimg, $radius_x, $radius_y) {
		// generate mask at twice desired resolution and downsample afterwards for easy antialiasing
		// mask is generated as a white double-size elipse on a triple-size black background and copy-paste-resampled
		// onto a correct-size mask image as 4 corners due to errors when the entire mask is resampled at once (gray edges)
		if ($gdimg_cornermask_triple = phpthumb_functions::ImageCreateFunction($radius_x * 6, $radius_y * 6)) {
			if ($gdimg_cornermask = phpthumb_functions::ImageCreateFunction(ImageSX($gdimg), ImageSY($gdimg))) {

				$color_transparent = ImageColorAllocate($gdimg_cornermask_triple, 255, 255, 255);
				ImageFilledEllipse($gdimg_cornermask_triple, $radius_x * 3, $radius_y * 3, $radius_x * 4, $radius_y * 4, $color_transparent);

				ImageFilledRectangle($gdimg_cornermask, 0, 0, ImageSX($gdimg), ImageSY($gdimg), $color_transparent);

				ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0,                           0,     $radius_x,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
				ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple,                           0, ImageSY($gdimg) - $radius_y,     $radius_x, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
				ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple, ImageSX($gdimg) - $radius_x, ImageSY($gdimg) - $radius_y, $radius_x * 3, $radius_y * 3, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);
				ImageCopyResampled($gdimg_cornermask, $gdimg_cornermask_triple, ImageSX($gdimg) - $radius_x,                           0, $radius_x * 3,     $radius_y, $radius_x, $radius_y, $radius_x * 2, $radius_y * 2);

				phpthumb_filters::ApplyMask($gdimg_cornermask, $gdimg);
				ImageDestroy($gdimg_cornermask);
				$this->DebugMessage('RoundedImageCorners('.$radius_x.', '.$radius_y.') succeeded', __FILE__, __LINE__);
				return true;

			} else {
				$this->DebugMessage('FAILED: $gdimg_cornermask = phpthumb_functions::ImageCreateFunction('.ImageSX($gdimg).', '.ImageSY($gdimg).')', __FILE__, __LINE__);
			}
			ImageDestroy($gdimg_cornermask_triple);

		} else {
			$this->DebugMessage('FAILED: $gdimg_cornermask_triple = phpthumb_functions::ImageCreateFunction('.($radius_x * 6).', '.($radius_y * 6).')', __FILE__, __LINE__);
		}
		return false;
	}


	function Saturation(&$gdimg, $amount, $color='') {
		if ($amount == 0) {
			return true;
		} elseif ($amount > 0) {
			$amount = 0 - $amount;
		} else {
			$amount = abs($amount);
		}
		return phpthumb_filters::Desaturate($gdimg, $amount, $color);
	}


	function Sepia(&$gdimg, $amount, $targetColor) {
		$amount      = (is_numeric($amount) ? max(0, min(100, $amount)) : 50);
		$targetColor = (phpthumb_functions::IsHexColor($targetColor) ? $targetColor : 'A28065');

		if ($amount == 0) {
			return true;
		}

		$TargetPixel['red']   = hexdec(substr($targetColor, 0, 2));
		$TargetPixel['green'] = hexdec(substr($targetColor, 2, 2));
		$TargetPixel['blue']  = hexdec(substr($targetColor, 4, 2));

		$ImageSX = ImageSX($gdimg);
		$ImageSY = ImageSY($gdimg);
		for ($x = 0; $x < $ImageSX; $x++) {
			for ($y = 0; $y < $ImageSY; $y++) {
				$OriginalPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$GrayPixel = phpthumb_functions::GrayscalePixel($OriginalPixel);

				// http://www.gimpguru.org/Tutorials/SepiaToning/
				// "In the traditional sepia toning process, the tinting occurs most in
				// the mid-tones: the lighter and darker areas appear to be closer to B&W."
				$SepiaAmount = ((128 - abs($GrayPixel['red'] - 128)) / 128) * ($amount / 100);

				foreach ($TargetPixel as $key => $value) {
					$NewPixel[$key] = round(max(0, min(255, $GrayPixel[$key] * (1 - $SepiaAmount) + ($TargetPixel[$key] * $SepiaAmount))));
				}
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, $NewPixel['red'], $NewPixel['green'], $NewPixel['blue'], $OriginalPixel['alpha']);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	function Smooth(&$gdimg, $amount=6) {
		$amount = min(25, max(0, $amount));
		if ($amount == 0) {
			return true;
		}
		if (phpthumb_functions::version_compare_replacement(phpversion(), '5.0.0', '>=') && phpthumb_functions::gd_is_bundled()) {
			if (ImageFilter($gdimg, IMG_FILTER_SMOOTH, $amount)) {
				return true;
			}
			$this->DebugMessage('FAILED: ImageFilter($gdimg, IMG_FILTER_SMOOTH, '.$amount.')', __FILE__, __LINE__);
			// fall through and try it the hard way
		}
		// currently not implemented "the hard way"
		$this->DebugMessage('FAILED: phpthumb_filters::Smooth($gdimg, '.$amount.') [function not implemented]', __FILE__, __LINE__);
		return false;
	}


	function Threshold(&$gdimg, $cutoff) {
		$cutoff = min(255, max(0, ($cutoff ? $cutoff : 128)));
		for ($x = 0; $x < ImageSX($gdimg); $x++) {
			for ($y = 0; $y < ImageSY($gdimg); $y++) {
				$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$grayPixel = phpthumb_functions::GrayscalePixel($currentPixel);
				if ($grayPixel['red'] < $cutoff) {
					$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0x00, 0x00, 0x00, $currentPixel['alpha']);
				} else {
					$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg, 0xFF, 0xFF, 0xFF, $currentPixel['alpha']);
				}
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	function ImageTrueColorToPalette2(&$image, $dither, $ncolors) {
		// http://www.php.net/manual/en/function.imagetruecolortopalette.php
		// zmorris at zsculpt dot com (17-Aug-2004 06:58)
		$width  = ImageSX($image);
		$height = ImageSY($image);
		$image_copy = ImageCreateTrueColor($width, $height);
		//ImageCopyMerge($image_copy, $image, 0, 0, 0, 0, $width, $height, 100);
		ImageCopy($image_copy, $image, 0, 0, 0, 0, $width, $height);
		ImageTrueColorToPalette($image, $dither, $ncolors);
		ImageColorMatch($image_copy, $image);
		ImageDestroy($image_copy);
		return true;
	}

	function ReduceColorDepth(&$gdimg, $colors=256, $dither=true) {
		$colors = max(min($colors, 256), 2);
		// ImageTrueColorToPalette usually makes ugly colors, the replacement is a bit better
		//ImageTrueColorToPalette($gdimg, $dither, $colors);
		phpthumb_filters::ImageTrueColorToPalette2($gdimg, $dither, $colors);
		return true;
	}


	function WhiteBalance(&$gdimg, $targetColor='') {
		if (phpthumb_functions::IsHexColor($targetColor)) {
			$targetPixel = array(
				'red'   => hexdec(substr($targetColor, 0, 2)),
				'green' => hexdec(substr($targetColor, 2, 2)),
				'blue'  => hexdec(substr($targetColor, 4, 2))
			);
		} else {
			$Analysis = phpthumb_filters::HistogramAnalysis($gdimg, false);
			$targetPixel = array(
				'red'   => max(array_keys($Analysis['red'])),
				'green' => max(array_keys($Analysis['green'])),
				'blue'  => max(array_keys($Analysis['blue']))
			);
		}
		$grayValue = phpthumb_functions::GrayscaleValue($targetPixel['red'], $targetPixel['green'], $targetPixel['blue']);
		$scaleR = $grayValue / $targetPixel['red'];
		$scaleG = $grayValue / $targetPixel['green'];
		$scaleB = $grayValue / $targetPixel['blue'];

		for ($x = 0; $x < ImageSX($gdimg); $x++) {
			for ($y = 0; $y < ImageSY($gdimg); $y++) {
				$currentPixel = phpthumb_functions::GetPixelColor($gdimg, $x, $y);
				$newColor = phpthumb_functions::ImageColorAllocateAlphaSafe(
					$gdimg,
					max(0, min(255, round($currentPixel['red']   * $scaleR))),
					max(0, min(255, round($currentPixel['green'] * $scaleG))),
					max(0, min(255, round($currentPixel['blue']  * $scaleB))),
					$currentPixel['alpha']
				);
				ImageSetPixel($gdimg, $x, $y, $newColor);
			}
		}
		return true;
	}


	function WatermarkText(&$gdimg, $text, $size, $alignment, $hex_color='000000', $ttffont='', $opacity=100, $margin=5, $angle=0, $bg_color=false, $bg_opacity=0, $fillextend='') {
		// text watermark requested
		if (!$text) {
			return false;
		}
		ImageAlphaBlending($gdimg, true);

		$metaTextArray = array(
			'^Fb' =>       $this->phpThumbObject->getimagesizeinfo['filesize'],
			'^Fk' => round($this->phpThumbObject->getimagesizeinfo['filesize'] / 1024),
			'^Fm' => round($this->phpThumbObject->getimagesizeinfo['filesize'] / 1048576),
			'^X'  => $this->phpThumbObject->getimagesizeinfo[0],
			'^Y'  => $this->phpThumbObject->getimagesizeinfo[1],
			'^x'  => ImageSX($gdimg),
			'^y'  => ImageSY($gdimg),
			'^^'  => '^',
		);
		$text = strtr($text, $metaTextArray);

		$text = str_replace("\r\n", "\n", $text);
		$text = str_replace("\r",   "\n", $text);
		$textlines = explode("\n", $text);

		if (@is_readable($ttffont) && is_file($ttffont)) {

			$opacity = 100 - intval(max(min($opacity, 100), 0));

			$this->DebugMessage('Using TTF font "'.$ttffont.'"', __FILE__, __LINE__);

			$TTFbox = ImageTTFbBox($size, $angle, $ttffont, $text);

			$min_x = min($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
			$max_x = max($TTFbox[0], $TTFbox[2], $TTFbox[4], $TTFbox[6]);
			//$text_width = round($max_x - $min_x + ($size * 0.5));
			$text_width = round($max_x - $min_x);

			$min_y = min($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
			$max_y = max($TTFbox[1], $TTFbox[3], $TTFbox[5], $TTFbox[7]);
			//$text_height = round($max_y - $min_y + ($size * 0.5));
			$text_height = round($max_y - $min_y);

			$TTFboxChar = ImageTTFbBox($size, $angle, $ttffont, 'jH');
			$char_min_y = min($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
			$char_max_y = max($TTFboxChar[1], $TTFboxChar[3], $TTFboxChar[5], $TTFboxChar[7]);
			$char_height = round($char_max_y - $char_min_y);

			switch ($alignment) {
				case 'T':
					$text_origin_x = round((ImageSX($gdimg) - $text_width) / 2);
					$text_origin_y = $char_height + $margin;
					break;

				case 'B':
					$text_origin_x = round((ImageSX($gdimg) - $text_width) / 2);
					$text_origin_y = ImageSY($gdimg) + $TTFbox[1] - $margin;
					break;

				case 'L':
					$text_origin_x = $margin;
					$text_origin_y = round((ImageSY($gdimg) - $text_height) / 2) + $char_height;
					break;

				case 'R':
					$text_origin_x = ImageSX($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
					$text_origin_y = round((ImageSY($gdimg) - $text_height) / 2) + $char_height;
					break;

				case 'C':
					$text_origin_x = round((ImageSX($gdimg) - $text_width) / 2);
					$text_origin_y = round((ImageSY($gdimg) - $text_height) / 2) + $char_height;
					break;

				case 'TL':
					$text_origin_x = $margin;
					$text_origin_y = $char_height + $margin;
					break;

				case 'TR':
					$text_origin_x = ImageSX($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
					$text_origin_y = $char_height + $margin;
					break;

				case 'BL':
					$text_origin_x = $margin;
					$text_origin_y = ImageSY($gdimg) + $TTFbox[1] - $margin;
					break;

				case 'BR':
				default:
					$text_origin_x = ImageSX($gdimg) - $text_width  + $TTFbox[0] - $min_x + round($size * 0.25) - $margin;
					$text_origin_y = ImageSY($gdimg) + $TTFbox[1] - $margin;
					break;
			}
			$letter_color_text = phpthumb_functions::ImageHexColorAllocate($gdimg, $hex_color, false, $opacity * 1.27);

			if ($alignment == '*') {

				$text_origin_y = $char_height + $margin;
				while (($text_origin_y - $text_height) < ImageSY($gdimg)) {
					$text_origin_x = $margin;
					while ($text_origin_x < ImageSX($gdimg)) {
						ImageTTFtext($gdimg, $size, $angle, $text_origin_x, $text_origin_y, $letter_color_text, $ttffont, $text);
						$text_origin_x += ($text_width + $margin);
					}
					$text_origin_y += ($text_height + $margin);
				}

			} else {

				//ImageRectangle($gdimg, $text_origin_x + $min_x, $text_origin_y + $TTFbox[1], $text_origin_x + $min_x + $text_width, $text_origin_y + $TTFbox[1] - $text_height, $letter_color_text);
				if (phpthumb_functions::IsHexColor($bg_color)) {
					$text_background_alpha = round(127 * ((100 - min(max(0, $bg_opacity), 100)) / 100));
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($gdimg, $bg_color, false, $text_background_alpha);
				} else {
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($gdimg, 'FFFFFF', false, 127);
				}
				$x1 = $text_origin_x + $min_x;
				$y1 = $text_origin_y + $TTFbox[1];
				$x2 = $text_origin_x + $min_x + $text_width;
				$y2 = $text_origin_y + $TTFbox[1] - $text_height;
				$x_TL = eregi('x', $fillextend) ?               0 : min($x1, $x2);
				$y_TL = eregi('y', $fillextend) ?               0 : min($y1, $y2);
				$x_BR = eregi('x', $fillextend) ? ImageSX($gdimg) : max($x1, $x2);
				$y_BR = eregi('y', $fillextend) ? ImageSY($gdimg) : max($y1, $y2);
				//while ($y_BR > ImageSY($gdimg)) {
				//	$y_TL--;
				//	$y_BR--;
				//	$text_origin_y--;
				//}
				ImageFilledRectangle($gdimg, $x_TL, $y_TL, $x_BR, $y_BR, $text_color_background);
				ImageTTFtext($gdimg, $size, $angle, $text_origin_x, $text_origin_y, $letter_color_text, $ttffont, $text);

			}
			return true;

		} else {

			$size = min(5, max(1, $size));
			$this->DebugMessage('Using built-in font (size='.$size.') for text watermark'.($ttffont ? ' because $ttffont !is_readable('.$ttffont.')' : ''), __FILE__, __LINE__);

			$text_width  = 0;
			$text_height = 0;
			foreach ($textlines as $dummy => $line) {
				$text_width   = max($text_width, ImageFontWidth($size) * strlen($line));
				$text_height += ImageFontHeight($size);
			}
			if ($img_watermark = phpthumb_functions::ImageCreateFunction($text_width, $text_height)) {
				ImageAlphaBlending($img_watermark, false);
				if (phpthumb_functions::IsHexColor($bg_color)) {
					$text_background_alpha = round(127 * ((100 - min(max(0, $bg_opacity), 100)) / 100));
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($img_watermark, $bg_color, false, $text_background_alpha);
				} else {
					$text_color_background = phpthumb_functions::ImageHexColorAllocate($img_watermark, 'FFFFFF', false, 127);
				}
				ImageFilledRectangle($img_watermark, 0, 0, ImageSX($img_watermark), ImageSY($img_watermark), $text_color_background);

				if ($angle && function_exists('ImageRotate')) {
					// using $img_watermark_mask is pointless if ImageRotate function isn't available
					if ($img_watermark_mask = phpthumb_functions::ImageCreateFunction($text_width, $text_height)) {
						$mask_color_background = ImageColorAllocate($img_watermark_mask, 0, 0, 0);
						ImageAlphaBlending($img_watermark_mask, false);
						ImageFilledRectangle($img_watermark_mask, 0, 0, ImageSX($img_watermark_mask), ImageSY($img_watermark_mask), $mask_color_background);
						$mask_color_watermark = ImageColorAllocate($img_watermark_mask, 255, 255, 255);
					}
				}

				$text_color_watermark = phpthumb_functions::ImageHexColorAllocate($img_watermark, $hex_color);
				foreach ($textlines as $key => $line) {
					switch ($alignment) {
						case 'C':
						case 'T':
						case 'B':
							$x_offset = round(($text_width - (ImageFontWidth($size) * strlen($line))) / 2);
							break;

						case 'L':
						case 'TL':
						case 'BL':
							$x_offset = 0;
							break;

						case 'R':
						case 'TR':
						case 'BR':
						default:
							$x_offset = $text_width - (ImageFontWidth($size) * strlen($line));
							break;
					}
					ImageString($img_watermark, $size, $x_offset, $key * ImageFontHeight($size), $line, $text_color_watermark);
					if ($angle && $img_watermark_mask) {
						ImageString($img_watermark_mask, $size, $x_offset, $key * ImageFontHeight($size), $text, $mask_color_watermark);
					}
				}
				if ($angle && $img_watermark_mask) {
					$img_watermark      = ImageRotate($img_watermark,      $angle, $text_color_background);
					$img_watermark_mask = ImageRotate($img_watermark_mask, $angle, $mask_color_background);
					phpthumb_filters::ApplyMask($img_watermark_mask, $img_watermark);
				}
				phpthumb_filters::WatermarkOverlay($gdimg, $img_watermark, $alignment, $opacity, $margin);
				ImageDestroy($img_watermark);
				return true;
			}

		}
		return false;
	}


	function WatermarkOverlay(&$gdimg_dest, &$img_watermark, $alignment='*', $opacity=50, $margin=5) {
		if (is_resource($gdimg_dest) && is_resource($img_watermark)) {
			$watermark_source_x        = 0;
			$watermark_source_y        = 0;
			$img_source_width          = ImageSX($gdimg_dest);
			$img_source_height         = ImageSY($gdimg_dest);
			$watermark_source_width    = ImageSX($img_watermark);
			$watermark_source_height   = ImageSY($img_watermark);
			$watermark_opacity_percent = max(0, min(100, $opacity));
			if ($margin < 1) {
				$watermark_margin_percent = 1 - $margin;
			} else {
				$watermark_margin_percent = (100 - max(0, min(100, $margin))) / 100;
			}
			$watermark_margin_x = round((1 - $watermark_margin_percent) * $img_source_width);
			$watermark_margin_y = round((1 - $watermark_margin_percent) * $img_source_height);
			switch ($alignment) {
				case '*':
					if ($gdimg_tiledwatermark = phpthumb_functions::ImageCreateFunction($img_source_width, $img_source_height)) {

						ImageAlphaBlending($gdimg_tiledwatermark, false);
						ImageSaveAlpha($gdimg_tiledwatermark, true);
						$text_color_transparent = phpthumb_functions::ImageColorAllocateAlphaSafe($gdimg_tiledwatermark, 255, 0, 255, 127);
						ImageFill($gdimg_tiledwatermark, 0, 0, $text_color_transparent);

						// set the tiled image transparent color to whatever the untiled image transparency index is
//						ImageColorTransparent($gdimg_tiledwatermark, ImageColorTransparent($img_watermark));

						// a "cleaner" way of doing it, but can't handle the margin feature :(
//						ImageSetTile($gdimg_tiledwatermark, $img_watermark);
//						ImageFill($gdimg_tiledwatermark, 0, 0, IMG_COLOR_TILED);
//						break;

//						ImageFill($gdimg_tiledwatermark, 0, 0, ImageColorTransparent($gdimg_tiledwatermark));
						// tile the image as many times as can fit
						for ($x = $watermark_margin_x; $x < ($img_source_width + $watermark_source_width); $x += round($watermark_source_width + ((1 - $watermark_margin_percent) * $img_source_width))) {
							for ($y = $watermark_margin_y; $y < ($img_source_height + $watermark_source_height); $y += round($watermark_source_height + ((1 - $watermark_margin_percent) * $img_source_height))) {
								ImageCopy(
									$gdimg_tiledwatermark,
									$img_watermark,
									$x,
									$y,
									0,
									0,
									min($watermark_source_width,  $img_source_width  - $x - ((1 - $watermark_margin_percent) * $img_source_width)),
									min($watermark_source_height, $img_source_height - $y - ((1 - $watermark_margin_percent) * $img_source_height))
								);
							}
						}

						$watermark_source_width  = ImageSX($gdimg_tiledwatermark);
						$watermark_source_height = ImageSY($gdimg_tiledwatermark);
						$watermark_destination_x = 0;
						$watermark_destination_y = 0;

						ImageDestroy($img_watermark);
						$img_watermark = $gdimg_tiledwatermark;
					}
					break;

				case 'T':
					$watermark_destination_x = round((($img_source_width  / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
					$watermark_destination_y = $watermark_margin_y;
					break;

				case 'B':
					$watermark_destination_x = round((($img_source_width  / 2) - ($watermark_source_width / 2)) + $watermark_margin_x);
					$watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
					break;

				case 'L':
					$watermark_destination_x = $watermark_margin_x;
					$watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
					break;

				case 'R':
					$watermark_destination_x = round(($img_source_width - $watermark_source_width)  * $watermark_margin_percent);
					$watermark_destination_y = round((($img_source_height / 2) - ($watermark_source_height / 2)) + $watermark_margin_y);
					break;

				case 'C':
					$watermark_destination_x = round(($img_source_width  / 2) - ($watermark_source_width  / 2));
					$watermark_destination_y = round(($img_source_height / 2) - ($watermark_source_height / 2));
					break;

				case 'TL':
					$watermark_destination_x = $watermark_margin_x;
					$watermark_destination_y = $watermark_margin_y;
					break;

				case 'TR':
					$watermark_destination_x = round(($img_source_width - $watermark_source_width)  * $watermark_margin_percent);
					$watermark_destination_y = $watermark_margin_y;
					break;

				case 'BL':
					$watermark_destination_x = $watermark_margin_x;
					$watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
					break;

				case 'BR':
				default:
					$watermark_destination_x = round(($img_source_width  - $watermark_source_width)  * $watermark_margin_percent);
					$watermark_destination_y = round(($img_source_height - $watermark_source_height) * $watermark_margin_percent);
					break;
			}
			ImageAlphaBlending($gdimg_dest, false);
			ImageSaveAlpha($gdimg_dest, true);
			ImageSaveAlpha($img_watermark, true);
			phpthumb_functions::ImageCopyRespectAlpha($gdimg_dest, $img_watermark, $watermark_destination_x, $watermark_destination_y, 0, 0, $watermark_source_width, $watermark_source_height, $watermark_opacity_percent);

			return true;
		}
		return false;
	}


	function DebugMessage($message, $file='', $line='') {
		if (is_object($this->phpThumbObject)) {
			return $this->phpThumbObject->DebugMessage($message, $file, $line);
		}
		return false;
	}
}

?>