<?php
	// Language setting

	global $language;
	global $page_owner;

	if (run('userdetails:editor', $page_owner) == "yes") {
		if (!$language) {
			$language = locale;
		}

		// Keep base language, no variations for now
		$lang = substr($language, 0, 2);

		// Loose the trailing slash
		$url = substr(url, 0, -1);

		global $metatags;

		$metatags .= <<< END
		<script language="javascript" type="text/javascript" src="$url/_tinymce/jscripts/tiny_mce/tiny_mce_gzip.php"></script>
		<script language="javascript" type="text/javascript">
		tinyMCE.init({
		language : "$lang",
		mode : "exact",
		convert_urls : false,
		relative_urls : false,
		elements : "new_weblog_post,new_weblog_comment",
		theme : "advanced",
		theme_advanced_buttons1 : "bold,italic,underline,separator,strikethrough,justifyleft,justifycenter,justifyright,justifyfull,bullist,numlist,image,undo,redo,link,unlink,code,fullscreen",
		theme_advanced_buttons2 : "",
		theme_advanced_buttons3 : "",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_path_location : "bottom",
		extended_valid_elements : "a[name|href|target|title|onclick],img[class|src|border=0|alt|title|hspace|vspace|width|height|align|onmouseover|onmouseout|name],hr[class|width|size|noshade],font[face|size|color|style],span[class|align|style]",
		remove_linebreaks: true,
		theme_advanced_source_editor_width : "400",
		theme_advanced_source_editor_height : "300",
		plugins : "fullscreen",
		document_base_url : "$url",
		fullscreen_settings : {
			theme_advanced_path_location : "top"
		}
		
		});
		</script>\n
END;
	}
?>
