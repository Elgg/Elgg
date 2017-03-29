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
	margin-right: 0.75rem;
}
.elgg-image-block .elgg-body {
	flex: 1;
}

/* ***************************************
	Listing
*************************************** */

.elgg-list-more {
	padding: 0.75em 1.25em;
}
.card .elgg-list-more + .card-block,
.card .elgg-pagination + .card-block {
	border-top: 1px solid #ddd;
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
	padding: 0 1% 2%;
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

.elgg-meta-block .elgg-menu-container + .elgg-menu-container {
    border-top: 1px solid #ddd;
}

/* ***************************************
	River
*************************************** */
.elgg-river-summary {
	font-size: 90%;
}

.elgg-river-summary,
.elgg-river-message,
.elgg-river-attachments,
.elgg-river-responses {
	margin-bottom: 0.5em;
}

/* **************************************
	Comments (from elgg_view_comments)
************************************** */
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