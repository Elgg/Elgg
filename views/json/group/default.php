<?php
/**
 * JSON group view
 *
 * @package Elgg
 * @subpackage Core
 */

echo json_encode($vars['entity']->toObject());
