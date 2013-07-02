<?php

/**
 * Resize an image and save it
 * 
 * Uses the GD extension
 *
 * @access private
 */
class CKEditorImageResizer {

	/** @var int The quality of the JPEG image (0-100) */
	public $jpegQuality = 75;

	/** @var int The amount of compression for PNG (0-9) */
	public $pngCompression = 9;

	/** @var int The largest possible dimension of the final image */
	protected $maximumDimension;

	/**
	 * Constructor
	 * 
	 * @param int $maximumDimension Largest dimension of the resized image
	 */
	public function __construct($maximumDimension) {
		$this->maximumDimension = $maximumDimension;
	}

	/**
	 * Resize an image
	 * 
	 * @param string $srcFilePath  Absolute path of input file
	 * @param string $destFilePath Absolute path of output file
	 * @param string $format       Output format ('jpeg' or 'png')
	 * @return bool
	 */
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

	/**
	 * Read the contents of a file
	 * 
	 * @param string $path Absolute path to a file
	 * @return string|null
	 */
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

	/**
	 * Create a GD image in memory 
	 * 
	 * @param CKEditorImageSize $size The size of the image
	 * @return resource|null
	 */
	protected function create(CKEditorImageSize $size) {
		$image = imagecreatetruecolor($size->width, $size->height);
		if (false === $image) {
			return null;
		}
		return $image;
	}

	/**
	 * Calculate the size of output image
	 * 
	 * @param resource $srcImage GD image to be resized
	 * @return CKEditorImageSize
	 */
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

	/**
	 * Resize a GD image
	 * 
	 * @param resource $srcImage  Input image
	 * @param resource $destImage Output image
	 * @return bool
	 */
	protected function resize($srcImage, $destImage) {
		imagealphablending($srcImage, true);
		imagealphablending($destImage, true);

		$sw = imagesx($srcImage);
		$sh = imagesy($srcImage);
		$dw = imagesx($destImage);
		$dh = imagesy($destImage);
		return imagecopyresampled($destImage, $srcImage, 0, 0, 0, 0, $dw, $dh, $sw, $sh);
	}

	/**
	 * Save the resized image to a file
	 * 
	 * @param resource $image    The GD image to save
	 * @param string   $filePath The absolute path to save to 
	 * @param string   $format   The format ('png' or 'jpeg')
	 * @return bool
	 */
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
