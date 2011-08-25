<?php 
/**
 * Elgg Twitter CSS
 * 
 * @package ElggTwitter
 */    
?>

#twitter_widget {
	margin:0 10px 0 10px;
}
#twitter_widget ul {
	margin:0;
	padding:0;
}
#twitter_widget li {
	list-style-image:none;
	list-style-position:outside;
	list-style-type:none;
	margin:0 0 5px 0;
	padding:0;
	overflow-x: hidden;
	border: 2px solid #dedede;
	-webkit-border-radius: 8px;
	-moz-border-radius: 8px;
	border-radius: 8px;
}
#twitter_widget li span {
	color:#666666;
	background:white;
	
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	padding:5px;
	display:block;
}
p.visit_twitter a {
	background:url(<?php echo elgg_get_site_url(); ?>mod/twitter/graphics/twitter16px.png) left no-repeat;
	padding:0 0 0 20px;
	margin:0;
}
p.twitter_username .input-text {
	width:200px;
}
.visit_twitter {
	background:white;
	
	-webkit-border-radius: 8px; 
	-moz-border-radius: 8px;
	border-radius: 8px;
	
	padding:2px;
	margin:0 0 5px 0;
}
#twitter_widget li > a {
	display:block;
	margin:0 0 0 4px;
}
#twitter_widget li span a {
	display:inline !important;
}