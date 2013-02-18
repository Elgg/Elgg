<?php
/**
 * Elgg Entity export.
 * Displays an entity as JSON
 *
 * @package Elgg
 * @subpackage Core
 */

echo json_encode($vars['entity']->toObject());
