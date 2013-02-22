<?php
/**
 * JSON site view
 *
 * @package Elgg
 * @subpackage Core
 */

echo json_encode($vars['entity']->toObject());
