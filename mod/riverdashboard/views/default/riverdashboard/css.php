<?php
/**
 * Elgg riverdashboard CSS
 *
 */
?>
#riverdashboard-updates {
	border-bottom:1px solid #cccccc;
}
#riverdashboard-updates a.update-link {
	display: inline-table;
	color:white;
	font-weight: bold;
	padding:1px 8px 2px 24px;
	margin-top:9px;
	cursor: pointer;
	background: red url("<?php echo elgg_get_site_url(); ?>mod/riverdashboard/graphics/refresh.png") no-repeat 5px 3px;
	-webkit-border-radius: 10px;
	-moz-border-radius: 10px;
}
#riverdashboard-updates a.update-link:hover {
	background: #4690D6 url("<?php echo elgg_get_site_url(); ?>mod/riverdashboard/graphics/refresh.png") no-repeat 5px -22px;
	color:white;
	text-decoration: none;
}
.riverdashboard-filtermenu {
	margin:10px 0 10px 0;
	float:right;
}

/* RIVER ENTRY */
.river-item {
	border-bottom:1px solid #cccccc;
	padding:7px 0 7px 0;
}
.river-item p {
	margin:0;
}
.river-item .entity-subtext {
	display: block;
	line-height: 1.4em;
}
.river-item-useravatar {
	float:left;
	margin-top:3px;
	margin-left:1px;
}
.river-item-contents {
	margin-left:55px;
}
.river-item-contents a {
	font-weight: bold;
}
.river-content-display {
	border-left:1px solid #DDDDDD;
	font-size:85%;
	line-height:1.5em;
	margin:8px 0 5px 0;
	padding-left:5px;
}
.following-icon {
	width:20px;
	height:25px;
	margin:0 2px 0 2px;
	background: url(<?php echo elgg_get_site_url(); ?>mod/riverdashboard/graphics/follow_icon.png) no-repeat left -7px;
}

/* LATEST COMMENTS IN RIVER */
.river-comments-tabs {
	max-height: 19px;
	overflow:hidden;
}
.river-comments {
	-moz-border-radius-bottomleft:5px;
	-moz-border-radius-bottomright:5px;
	-moz-border-radius-topleft:5px;
	-moz-border-radius-topright:0;
	-webkit-border-top-left-radius:5px;
	-webkit-border-top-right-radius:0;
	-webkit-border-bottom-right-radius:5px;
	-webkit-border-bottom-left-radius:5px;
	background-color: #eeeeee;
}
.river-comment {
	padding:3px;
	border-bottom:1px solid white;
}
.river-comment.penultimate {

}
.river-comment.latest {

}
/* hidden inline comment form */
.river-comment_form.hidden {
	padding:5px;
	height:26px;
}
.river-comment_form.hidden .input-text {
	width:560px;
	padding:3px;
}
#profile-content .river-comment_form.hidden .input-text { /* when activity is displayed on profile page */
	width:535px;
}
.river-comment_form.hidden .submit-button {
	margin:0 0 0 10px;
	float:right;
}
.river-link-divider {
	color:#999999;
	font-style: normal;
}
.river-item-contents .river-comment-form-button,
.river-item-contents .river_user-like-button {
	font-weight:normal;
	font-style: normal;
}
.river-item-contents .river-comment-form-button {
	margin-left: 7px;
}
/* hidden list of users that liked item */
.river-item .elgg-likes-user {
	border-bottom:1px solid white;
	padding:3px;
}
.river-item .elgg-likes-user .entity-listing-icon {
	margin:3px 0 4px 2px;
}
.river-item .elgg-likes-user .entity-listing-info {
	width:635px;
}
#profile-content .river-item .elgg-likes-user .entity-listing-info { /* when likes is displayed on profile page activity stream */
	width:600px;
}
.river-item .elgg-likes-user .entity-metadata {
	margin-top:3px;
}
.river-item p.elgg-likes-owner {
	padding-top:0;
}
.river-item a.river-more-comments {
	display:block;
	float:right;
	padding:1px 7px 1px 7px;
	margin-left:6px;
	text-align:right;
	font-size:85%;
	font-weight:normal;
	width:auto;
	background-color: #eeeeee;
	-moz-border-radius-topleft:4px;
	-moz-border-radius-topright:4px;
	-webkit-border-top-left-radius:4px;
	-webkit-border-top-right-radius:4px;
}
.river-item a.river-more-comments.off {
	background-color: white;
}
.river-item .river-comment-owner-icon {
	float:left;
	margin:3px 8px 4px 2px;
}
.river-item .river-comment-contents {
	margin-left:34px;
}
a.river-more-comments {
	float:right;
	font-size:85%;
	padding-right:7px;
	text-align: right:
}



