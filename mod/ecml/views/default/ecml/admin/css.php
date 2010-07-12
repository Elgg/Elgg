<?php
/**
* ECML CSS
*/
?>

.ecml_admin_table {
	width:100%;
}
.ecml_admin_table td, th {
	border: 1px solid gray;
	text-align: center;
	padding: 5px;
}
.ecml_admin_table th, .ecml_keyword_desc {
	font-weight: bold;
}
.ecml_row_odd {
	background-color: #EEE;
}
.ecml_row_even {

}
.ecml_restricted {
	color: #555;
}


/* ecml embed web services list */
.ecml_web_service_list {
	margin:0;
	padding:0;
}
.ecml_web_service_list li {
	list-style: none;
	margin:0 0 0 0;
	padding:0;
	/* display:inline; */

}
.ecml_web_service_list li a {
	display:block;
	float:left;
	height:41px;
	width:152px;
	background-position: center center;
	background-repeat: no-repeat;
	text-indent: -900em;
	border:2px solid transparent;
	margin:0 20px 10px 0;
}
.ecml_web_service_list li a.selected {
	border-color:#999999;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
}
.ecml_web_service_list li a.youtube {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_youtube.gif);
	width:102px;
}
.ecml_web_service_list li a.slideshare {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_slideshare.gif);
}
.ecml_web_service_list li a.vimeo {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_vimeo.gif);	
	width:130px;
}
.ecml_web_service_list li a.googlemaps {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_googlemaps.gif);	
}
.ecml_web_service_list li a.blip_tv {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_bliptv.gif);
	width:102px;	
}
.ecml_web_service_list li a.dailymotion {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_dailymotion.gif);	
}
.ecml_web_service_list li a.livevideo {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_livevideo.gif);	
	width:166px;
}
.ecml_web_service_list li a.redlasso {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/logo_redlasso.gif);
	background-position:center 4px;
	width:130px;
}

.embed_content_section {
	border:none;
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	background-color:#EEEEEE;
	margin:10px 0 10px 0;
	padding:5px;
	width:auto;
}
.ecml_generated_code {
	color:#666666;
	font-size:85%;
	font-style:normal;
	line-height: 1.3em;
	margin:5px 0;
}
.ecml_embed_instructions,
.ecml_embed_preview {
	background-image: url(<?php echo $vars['url']; ?>_graphics/elgg_sprites.png);
	background-repeat: no-repeat;
	background-position: 0px -367px;
	padding-left: 12px;
}
.ecml_embed_instructions.open,
.ecml_embed_preview.open {
	background-position: 0px -388px;
}
#embed_service_url {
	-webkit-border-radius: 5px;
	-moz-border-radius: 5px;
	background-color:#EEEEEE;
	margin:10px 0 10px 0;
	padding:5px;
	width:auto;
}
#web_services_resource {
	width:670px;
}
#url_status {
	width:30px;
	height:30px;
	float:right;
}
#url_status.success {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/url_status.png);
	background-repeat: no-repeat;
	background-position: left top;
}
#url_status.failure {
	background-image: url(<?php echo $vars['url']; ?>mod/ecml/graphics/url_status.png);
	background-repeat: no-repeat;
	background-position: left bottom;
}
#embed_ecml_keyword_help,
#ecml_preview {
	padding:10px;
}
#ecml_preview object,
#ecml_preview embed,
#ecml_preview iframe {
	width: auto;
	max-height:240px;
}
#embed_submit {
	margin:10px 0 0 0;
}



