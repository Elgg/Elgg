<?php
/**
 * @uses $vars['language']
 */
global $CONFIG;

$language = $vars['language'];

echo json_encode($CONFIG->translations[$language]);