<?php

	/**
	 * Elgg CSS
	 * The standard CSS file
	 * 
	 * @package Elgg
	 * @subpackage Core
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.org/
	 * 
	 * @uses $vars['wwwroot'] The site URL
	 */

?>

/*
    Default Elgg CSS
*/

body {
    background:#fff;
    color:#000;
    margin:20px center;
    text-align:center;
}

#container {
    width:990px;
    border-left:2px solid #555;
    border-right:2px solid #555;
    margin:auto;
}

#header {
    margin:0 0 20px 0;
    padding:10px;
    text-align:left;
    position:relative;
    border-bottom:1px solid #555;
}

#topmenu {
    position:absolute;
    top:2px;
    right:10px;
}

#topmenu li {
    display:inline;
    list-style:none;
}

#sidebar_toolbox {
    float:left;
    width:150px;
    background:#efefef;
    padding:5px;
    text-align:left;
    text-size:10px;
    margin:0;
}

#mainContent_nosidebar {
    margin:0 0 0 180px;
    width:750px;
    padding:20px;
}

#login-box {
    text-align:left;
    background:#555;
    border:1px solid #ddd;
    width:300px;
    padding:10px;
    color:#fff;
}

#footer {
    border-top:1px solid #555;
    margin:20px 0 0 0;
    padding:10px;
}