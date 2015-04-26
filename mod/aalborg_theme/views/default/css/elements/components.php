<?php
/**
 * Layout Object CSS
 *
 * Image blocks, lists, tables, gallery, messages
 *
 * @package Elgg.Core
 * @subpackage UI
 */
?>
/* <style> /**/

/* ***************************************
	Image Block
*************************************** */
.elgg-image-block {
	padding: 10px 0;
}
.elgg-image-block .elgg-image {
	float: left;
	margin-right: 8px;
}
.elgg-image-block .elgg-image-alt {
	float: right;
	margin-left: 8px;
}

/* ***************************************
	List
*************************************** */
.elgg-list {
	margin: 5px 0;
	clear: both;
}
.elgg-list > li {
	border-bottom: 1px solid #DCDCDC;
}
.elgg-item h3 a {
	padding-bottom: 4px;
}
.elgg-item > .elgg-subtext {
	margin-bottom: 4px;
}
.elgg-item .elgg-content {
	margin: 10px 0;
}
.elgg-content {
	clear: both;
}

/* ***************************************
	Gallery
*************************************** */
.elgg-gallery {
	border: none;
	margin-right: auto;
	margin-left: auto;
}
.elgg-gallery td {
	padding: 5px;
}
.elgg-gallery-fluid > li {
	float: left;
}
.elgg-gallery-users > li {
	margin: 0 2px;
}

/* ***************************************
	Tables
*************************************** */
.elgg-table {
	width: 100%;
	border-top: 1px solid #DCDCDC;
}
.elgg-table td, .elgg-table th {
	padding: 4px 8px;
	border: 1px solid #DCDCDC;
}
.elgg-table th {
	background-color: #DDD;
}
.elgg-table tr:nth-child(odd), .elgg-table tr.odd {
	background-color: #FFF;
}
.elgg-table tr:nth-child(even), .elgg-table tr.even {
	background-color: #F0F0F0;
}
.elgg-table-alt {
	width: 100%;
	border-top: 1px solid #DCDCDC;
}
.elgg-table-alt th {
	background-color: #EEE;
	font-weight: bold;
}
.elgg-table-alt td, .elgg-table-alt th {
	padding: 6px 0;
	border-bottom: 1px solid #DCDCDC;
}
.elgg-table-alt td:first-child {
	width: 200px;
}
.elgg-table-alt tr:hover {
	background: #E4E4E4;
}

/* ***************************************
	Owner Block
*************************************** */
.elgg-owner-block {
	margin-bottom: 20px;
}

/* ***************************************
	Messages
*************************************** */
.elgg-message {
	color: #FFF;
	display: block;
	padding: 10px 20px;
	cursor: pointer;
	opacity: 0.9;
	box-shadow: 0 1px 3px rgba(0, 0, 0, 0.4);
	border-radius: 3px;
}
.elgg-state-success {
	background-color: #090;
}
.elgg-state-error {
	background-color: #F00;
}
.elgg-state-notice {
	background-color: #5097CF;
}
.elgg-box-error {
	margin-top: 10px;
	padding: 20px;
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
	border-radius: 5px;
}
.elgg-box {
	margin: 10px 0;
	padding: 20px;
	border-radius: 5px;
	box-sizing: border-box;
}
.elgg-box.elgg-state-error {
	color: #B94A48;
	background-color: #F8E8E8;
	border: 1px solid #E5B7B5;
}
.elgg-box.elgg-state-notice {
	color: #3B8BC9;
	background-color: #E7F1F9;
	border: 1px solid #B1D1E9;
}
.elgg-box.elgg-state-success {
	color: #397F2E;
	background-color: #EAF8E8;
	border: 1px solid #AADEA2;
}
.elgg-box.elgg-state-warning {
	color: #6B420F;
	background-color: #FCF8E4;
	border: 1px solid #EDDC7D;
}

/* ***************************************
	River
*************************************** */
.elgg-river-layout .elgg-list-river {
	border-top: 1px solid #DCDCDC;
}
.elgg-list-river > li {
	border-bottom: 1px solid #DCDCDC;
}
.elgg-river-item .elgg-pict {
	margin-right: 20px;
}
.elgg-river-timestamp {
	color: #666;
	font-size: 85%;
	font-style: italic;
	line-height: 1.2em;
}

.elgg-river-attachments,
.elgg-river-message,
.elgg-river-content {
	border-left: 1px solid #DCDCDC;
	margin: 8px 0 5px 0;
	padding-left: 8px;
}
.elgg-river-attachments .elgg-avatar,
.elgg-river-attachments .elgg-icon {
	float: left;
}
.elgg-river-attachments .elgg-icon-arrow-right {
	margin: 3px 8px 0;
}
.elgg-river-layout .elgg-input-dropdown {
	float: right;
	margin: 10px 0;
}

<?php //@todo components.php ?>
.elgg-river-comments {
	position: relative;
	margin: 20px 0 0 0;
	border-top: 1px solid #DCDCDC;
}
.elgg-river-comments > li {
    border-color: #DCDCDC;
    border-style: solid;
    border-width: 0 1px 1px 1px;
	padding: 4px 10px;
}
.elgg-river-comments li .elgg-output {
	padding-right: 5px;
}
.elgg-river-comments .elgg-media {
	padding: 0;
}
.elgg-river-more {
    border-color: #DCDCDC;
    border-style: solid;
    border-width: 0 1px 1px 1px;	
	padding: 5px 10px;
	font-size: 85%;
}

<?php //@todo location-dependent styles ?>
.elgg-river-item form {
    border-color: #DCDCDC;
    border-style: solid;
    border-width: 0 1px 1px 1px;
	padding: 6px;
	height: auto;
}
.elgg-river-item input[type=text] {
	width: 78%;
}
.elgg-river-item input[type=submit] {
	margin: 0 0 0 10px;
}

/* **************************************
	Comments (from elgg_view_comments)
************************************** */
.elgg-comments {
	margin-top: 25px;
}
.elgg-comments .elgg-list {
	position: relative;
    border-top: 1px solid #DCDCDC;
}
.elgg-comments .elgg-list > li {
    border-color: #DCDCDC;
    border-style: solid;
    border-width: 0 1px 1px 1px;
	padding: 4px 10px;
}
.elgg-comments > form {
	margin-top: 15px;
}

/* Comment highlighting that automatically fades away */
.elgg-comments .elgg-state-highlight {
	-webkit-animation: comment-highlight 5s; /* Chrome, Safari, Opera */
	animation: comment-highlight 5s;
}
/* Chrome, Safari, Opera */
@-webkit-keyframes comment-highlight {
	from {background: #dff2ff;}
	to {background: white;}
}
/* Standard syntax */
@keyframes comment-highlight {
	from {background: #dff2ff;}
	to {background: white;}
}

/* **************************************
	Comments triangle
************************************** */
.elgg-comments .elgg-list:after,
.elgg-comments .elgg-list:before,
.elgg-river-comments:after,
.elgg-river-comments:before {
	bottom: 100%;
	left: 30px;
	border: solid transparent;
	content: " ";
	height: 0;
	width: 0;
	position: absolute;
	pointer-events: none;
}
.elgg-comments .elgg-list:after,
.elgg-river-comments:after {
	border-color: rgba(238, 238, 238, 0);
	border-bottom-color: #FFF;
	border-width: 8px;
	margin-left: -8px;
}
.elgg-comments .elgg-list:before,
.elgg-river-comments:before {
	border-color: rgba(220, 220, 220, 0);
	border-bottom-color: #DCDCDC;
	border-width: 9px;
	margin-left: -9px;
}

/* ***************************************
	Image-related
*************************************** */
.elgg-photo {
	border: 1px solid #DCDCDC;
	padding: 3px;
	background-color: #FFF;

	-webkit-box-sizing: border-box;
	-moz-box-sizing: border-box;
	box-sizing: border-box;
	
	max-width: 100%;
	height: auto;
}

/* ***************************************
	Tags
*************************************** */
.elgg-tags {
	font-size: 85%;
}
.elgg-tags > li {
	float:left;
	margin-right: 5px;
}
.elgg-tags li.elgg-tag:after {
	content: ",";
}
.elgg-tags li.elgg-tag:last-child:after {
	content: "";
}
