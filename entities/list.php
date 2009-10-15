<?php
/**
 * Activity viewer
 *
 * @package Elgg
 * @subpackage Core
 * @author Curverider Ltd
 * @link http://elgg.org/
 */

require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
set_context('search');
$area2 = list_entities("","",0,10,false);
set_context('entities');
$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
page_draw("",$body);