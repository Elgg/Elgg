/**
 * elgg_layout css for Internet Explorer6
 * @uses $vars['wwwroot'] The site URL
*/

* {zoom: 1;} /* trigger hasLayout in IE */

/* main nav drop-down */
#elgg-header {z-index:1;}
.navigation li a:hover ul {display:block; position:absolute; top:21px; left:0;}
.navigation li a:hover ul li a {display:block;}
.navigation li.navigation-more ul li a {width:150px;background-color: #dedede;}

/* @todo check this one */
.elgg-button-delete a { background-position-y: 2px; }
.elgg-button-delete a:hover { background-position-y: -14px; }