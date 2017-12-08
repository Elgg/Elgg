/* <style> /**/
	
/**
 * Layout Object CSS
 *
 * Image blocks, lists, tables, gallery, messages
 */

/* ***************************************
	Image Block
*************************************** */
.elgg-image-block {
	padding: 10px 0;
	display: flex;
	align-items: flex-start;
}
.elgg-image-block:after {
	display: none;
}
.elgg-image-block .elgg-image {
	margin-right: 8px;
}
.elgg-image-block .elgg-image-alt {
	margin-left: 8px;
	order: 1;
}
.elgg-image-block > .elgg-body {
	flex: 1;
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

.elgg-module > .elgg-body > .elgg-list, /* margin for group modules */
.elgg-module .elgg-widget-content > .elgg-list { /* margin for profile and dashboard widgets */
    margin-top: 0;
}

/* ***************************************
	List Item
*************************************** */
.elgg-listing-imprint > span {
    margin-right: 10px;
}

.elgg-listing-imprint .elgg-icon {
    margin-right: 5px;
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
.elgg-table tr:nth-child(odd) {
	background-color: #FFF;
}
.elgg-table tr:nth-child(even) {
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

<?= elgg_view('elements/components/messages.css') ?>

/* ***************************************
	River
*************************************** */
.elgg-module .elgg-list-river {
	border-top: none;
}
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
	margin-bottom: 15px;
}

/* Comment highlighting that automatically fades away */
.elgg-comments .elgg-state-highlight,
.elgg-river-comments .elgg-state-highlight {
	animation: comment-highlight 5s;
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
	box-sizing: border-box;
	max-width: 100%;
	height: auto;
}

<?= elgg_view('elements/components/tags.css', $vars) ?>

@media (max-width: 820px) {
	.elgg-river-item input[type=text] {
		width: 100%;
	}
	.elgg-river-item input[type=submit] {
		margin: 5px 0 0 0;
	}
}