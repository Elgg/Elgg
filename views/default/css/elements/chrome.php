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
	border-radius: 4px;
}

h2 {
	padding-bottom:5px;
}

.elgg-quiet {
	color: #666;
}

.elgg-loud {
	color: #0054A7;
}

.elgg-photo {
	border: 1px solid #ccc;
	padding: 3px;
	background-color: white;
}

.elgg-comments {
	margin-top: 25px;
}
.elgg-comments > form {
	margin-top: 15px;
}

/* ***************************************
	BORDERS AND SEPARATORS
*************************************** */
.elgg-border-plain {
	border: 1px solid #eeeeee;
}
.elgg-divide-top {
	border-top: 1px solid #CCCCCC;
}
.elgg-divide-bottom {
	border-bottom: 1px solid #CCCCCC;
}
.elgg-divide-left {
	border-left: 1px solid #CCCCCC;
}
.elgg-divide-right {
	border-right: 1px solid #CCCCCC;
}

/* ***************************************
	USER INPUT DISPLAY RESET
*************************************** */
.elgg-output {
	margin-top: 10px;
}
.elgg-output ul, ol {
	margin: 0 1.5em 1.5em 0;
	padding-left: 1.5em;
}
.elgg-output ul {
	list-style-type: disc;
}
.elgg-output ol {
	list-style-type: decimal;
}
.elgg-output table {
	border: 1px solid #ccc;
}
.elgg-output table td {
	border: 1px solid #ccc;
	padding: 3px 5px;
}
.elgg-output img {
	max-width: 100%;
}
