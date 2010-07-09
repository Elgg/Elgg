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
	margin:0 20px 0 0;
	padding:0;
	display:inline;
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
#embed_ecml_keyword_help,
#ecml_preview {
	padding:10px;
}
#ecml_preview object,
#ecml_preview embed {
	width: auto;
	max-height:240px;
}
#embed_submit {
	margin:10px 0 0 0;
}



