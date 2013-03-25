<?php
/**
 * JSON user view
 *
 * @package Elgg
 * @subpackage Core
 */

echo json_encode($vars['entity']->toObject());
