<?php
/**
 * Visual styling
 *
 * @package Elgg.Core
 * @subpackage UI
 */

/* Colors:

	#4690D6 - elgg light blue
	#0054A7 - elgg dark blue
	#e4ecf5 - elgg very light blue
*/

?>

a {
	color: #4690D6;
}
a:hover,
a.selected {
	color: #555555;
}
a:hover,
a.selected {
	text-decoration: underline;
}
h1, h2, h3, h4, h5, h6 {
	color: #0054A7;
}
p {
	margin-bottom: 15px;
}
p:last-child {
	margin-bottom: 0;
}

dt {
	font-weight: bold;
}
dd {
	margin: 0 0 1em 1em;
}
pre, code {
	background:#EBF5FF;
	color:#000000;
	overflow:auto;

	overflow-x: auto; /* Use horizontal scroller if needed; for Firefox 2, not needed in Firefox 3 */

	white-space: pre-wrap;
	word-wrap: break-word; /* IE 5.5-7 */
}
code {
	padding:2px 3px;
}
pre {
	padding:3px 15px;
	margin:0px 0 15px 0;
	line-height:1.3em;
}
blockquote {
	padding:3px 15px;
	margin:0px 0 15px 0;
	background:#EBF5FF;
	border:none;
	-webkit-border-radius: 4px;
	-moz-border-radius: 4px;
}

/* ***************************************
	GENERIC SELECTORS
*************************************** */
h2 {
	padding-bottom:5px;
}

.radius8 {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}


<?php 
// @todo add a series of dividers and borders

?>

.divider {
	border-top:1px solid #cccccc;
}

<?php //@todo convert to hyphen-separated ?>
.elgg_hrt {
	border-top: 1px solid #CCCCCC;
}
.elgg_hrb {
	border-bottom: 1px solid #CCCCCC;
}
.elgg-border-plain {
	border: 1px solid #eeeeee;
}

.elgg-text ul, ol {
	margin: 0 1.5em 1.5em 0;
	padding-left: 1.5em;
}
.elgg-text ul {
	list-style-type: disc;
}
.elgg-text ol {
	list-style-type: decimal;
}

.elgg-subtext {
	color: #666666;
	font-size: 85%;
	line-height: 1.2em;
	font-style: italic;
}
