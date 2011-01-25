<?php
/**
 *
 */

/* Colors:

	#4690D6 - elgg light blue
	#0054A7 - elgg dark blue
	#e4ecf5 - elgg v light blue
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
	color:#0054A7;
}
p {
	margin-bottom:15px;
}
p:last-child {
	margin-bottom:0;
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
	white-space: pre-wrap; /* css-3 */
	white-space: -moz-pre-wrap !important; /* Mozilla, since 1999 */
	white-space: -pre-wrap; /* Opera 4-6 */
	white-space: -o-pre-wrap; /* Opera 7 */
	word-wrap: break-word; /* Internet Explorer 5.5+ */
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
/*	border-bottom:1px solid #CCCCCC; */
	padding-bottom:5px;
}


.link {
	cursor:pointer;
}
.small {
	font-size: 90%;
}
.divider {
	border-top:1px solid #cccccc;
}


.radius8 {
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
}


<?php //@todo lists.php ?>
.elgg-tags {
	background-image:url(<?php echo elgg_get_site_url(); ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: left -196px;
	padding:1px 0 0 14px;
	font-size: 85%;
}
.elgg-tags li {
	display: inline;
	margin-right: 5px;
}
.elgg-tags li:after {
	content: ",";
}
.elgg-tags li:last-child:after {
	content: "";
}
.elgg-tagcloud {
	text-align:justify;
}
.elgg-discover .elgg-discoverable {
	display: none;
}
.elgg-discover:hover .elgg-discoverable {
	display: block;
}

<?php //@todo move to helpers.php ?>
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
.elgg-rss {
	float: right;
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