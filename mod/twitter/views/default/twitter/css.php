<?php
 
    /**
	 * Elgg Twitter CSS
	 * 
	 * @package ElggTwitter
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider <info@elgg.com>
	 * @copyright Curverider Ltd 2008-2010
	 * @link http://elgg.com/
	 */
     
?>

#twitter_widget {
    margin:0 10px 0 10px;
}

#twitter_widget ul {
	margin:0;
	padding:0;
}

#twitter_widget li {
	background: url(<?php echo $vars['url']; ?>mod/twitter/graphics/thewire_speech_bubble.gif) no-repeat right bottom;
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
	margin:0 0 5px 0;
	padding:0;
	overflow-x: hidden;
}

#twitter_widget li span {
	color:#666666;
	background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	padding:5px;
	display:block;
}

p.visit_twitter a {
    background:url(<?php echo $vars['url']; ?>mod/twitter/graphics/twitter.png) left no-repeat;
    padding:0 0 0 20px;
    margin:0;
}
.visit_twitter {
	background:white;
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	padding:2px;
	margin:0 0 5px 0;
}

#twitter_widget li a {
	display:block;
	margin:0 0 0 4px;
}

#twitter_widget li span a {
	display:inline !important;
}