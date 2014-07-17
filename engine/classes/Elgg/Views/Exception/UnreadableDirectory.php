<?php
namespace Elgg\Views\Exception;

class UnreadableDirectory extends \Exception {
    public $path;
    
    /**
     * @param string $path
     */
    public function __construct($path) {
        $this->path = $path;
    }
}