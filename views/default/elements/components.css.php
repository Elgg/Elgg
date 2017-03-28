/* <style> /**/
	
/**
 * Layout Object CSS
 *
 * Image blocks, lists, tables, gallery, messages
 */

/* ***************************************
	Image Block
*************************************** */
.elgg-image-block .elgg-image {
	margin-right: 10px;
}
.elgg-image-block .elgg-body {
	flex: 1;
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

.elgg-listing-full-header {
    padding: 15px;
    border-bottom: 1px solid #ddd;
    margin-bottom: 15px;
}
.elgg-listing-full-responses {
    margin-top: 2em;
}
.elgg-listing-summary-title {
	font-weight: normal;
	overflow: hidden;
    max-width: 100%;
    word-break: break-word;
	margin-bottom: 0;
}

.elgg-listing-time,
.elgg-listing-access {
    white-space: nowrap;
}

.elgg-listing-summary-content {
    margin-top: 1em;
}

.elgg-no-results {
    padding: .75rem 1.25rem;
    margin: 0;
}

/* ***************************************
	Gallery
*************************************** */
.elgg-gallery {
	border: none;
	margin: 0;
	list-style: none;
	padding: 0;
}
.elgg-gallery > li {
	display: inline-block;
	margin: 3px;
}

/* ***************************************
	Owner Block
*************************************** */
.elgg-owner-block > .elgg-head a {
    display: inline-block;
    width: 100%;
}

.elgg-owner-block > .elgg-head .card-block {
    border-bottom: 1px solid #ddd;
	text-align: center;
}

.elgg-owner-block > .elgg-head .card-title {
    margin: 15px auto 0;
    font-size: 1.1em;
}

/* ***************************************
	Meta block
*************************************** */
.elgg-meta-block {
    margin: 0;
}

.elgg-meta-block .elgg-output-field {
    padding: 1em;
}

.elgg-meta-block .elgg-menu-container + .elgg-menu-container {
    border-top: 1px solid #ddd;
}

/* ***************************************
	River
*************************************** */
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
	border-left: 1px solid #ddd;
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
.elgg-river-layout .elgg-river-selector {
	float: right;
	margin: 13px 0 18px;
}
.elgg-river-selector * {
	margin-left: 5px;
	vertical-align: middle;
}

.elgg-river-comments {
	position: relative;
	margin: 20px 0 0 0;
	border-top: 1px solid #ddd;
}
.elgg-river-comments > li {
    border-color: #ddd;
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
    border-color: #ddd;
    border-style: solid;
    border-width: 0 1px 1px 1px;
	padding: 5px 10px;
	font-size: 85%;
}

.elgg-river-item form {
    border-color: #ddd;
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
.elgg-comments .elgg-list {
    margin-bottom: 2em;
}

/* Comment highlighting that automatically fades away */
.elgg-comments .elgg-state-highlight,
.elgg-river-comments .elgg-state-highlight {
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

<?= elgg_view('elements/components/tags.css', $vars) ?>