<?php
namespace \Elgg\Filesystem\Adapter\Gaufrette;


class AwsS3 extends Gaufrette\Adapter\AwsS3 {
	
	public function writeStream($key, $content) {
		$this->ensureBucketExists();
        $options = $this->getOptions($key, array('Body' => $content));

        /**
         * If the ContentType was not already set in the metadata, then we autodetect
         * it to prevent everything being served up as binary/octet-stream.
         */
        if (!isset($options['ContentType']) && $this->detectContentType) {
            $finfo = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($content);

            $options['ContentType'] = $mimeType;
        }

        try {
            $this->service->putObject($options);
            return strlen($content);
        } catch (\Exception $e) {
            return false;
        }
	}
}
