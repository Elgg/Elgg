<?php

	/**
	 * Activity viewer
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008-2009
	 * @link http://elgg.org/
	 */

		require_once(dirname(dirname(__FILE__)) . "/engine/start.php");
		set_context('search');
		$area2 = list_entities("","",0,10,false);
		set_context('entities');
		$body = elgg_view_layout('two_column_left_sidebar',$area1, $area2);
		page_draw("",$body);

?>