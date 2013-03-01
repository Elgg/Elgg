<?php

/**
 * Resize an image and save it
 *
 * @access private
 */
class CKEditorImageResizer {

	protected $maximumDimension;

	public $jpegQuality = 75;
	public $pngCompression = 9;

	public function __construct($maximumDimension) {
		$this->maximumDimension = $maximumDimension;
	}

	public function process($srcFilePath, $destFilePath, $format = 'jpeg') {
		$srcImage = $this->read($srcFilePath);
		if (!$srcImage) {
			return false;
		}

		$destSize = $this->calculateSize($srcImage);
		$destImage = $this->create($destSize);
		if (!$destImage) {
			imagedestroy($srcImage);
			return false;
		}

		if (!$this->resize($srcImage, $destImage)) {
			return false;
		}
		imagedestroy($srcImage);

		$result = $this->save($destImage, $destFilePath, $format);
		imagedestroy($destImage);
		return $result;
	}

	protected function read($path) {
		$contents = file_get_contents($path);
		if (false === $contents) {
			return null;
		}

		$image = @imagecreatefromstring($contents);
		if (!is_resource($image)) {
			return null;
		}

		return $image;
	}

	protected function create(CKEditorImageSize $size) {
		$image = imagecreatetruecolor($size->width, $size->height);
		if (false === $image) {
			return null;
		}
		return $image;
	}

	protected function calculateSize($srcImage) {
		$size = new CKEditorImageSize();
		$srcWidth = imagesx($srcImage);
		$srcHeight = imagesy($srcImage);

		$widthRatio = $this->maximumDimension / $srcWidth;
		$heightRatio = $this->maximumDimension / $srcHeight;
		$ratio = min(1, $widthRatio, $heightRatio);
		$size->width = (int)floor($ratio * $srcWidth);
		$size->height = (int)floor($ratio * $srcHeight);
		return $size;
	}

	protected function resize($srcImage, $destImage) {
		imagealphablending($srcImage, true);
		imagealphablending($destImage, true);

		$sw = imagesx($srcImage);
		$sh = imagesy($srcImage);
		$dw = imagesx($destImage);
		$dh = imagesy($destImage);
		return imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $dw, $dh, $sw, $sh);
	}

	protected function save($image, $filePath, $format) {
		$saveFunction = "image$format";

		$args = array($image, $filePath);
		if ($format == 'jpeg') {
			array_push($args, $this->jpegQuality);
		} else {
			// png - compression high
			array_push($args, $this->pngCompression);
		}
		return call_user_func_array($saveFunction, $args);
	}
}
