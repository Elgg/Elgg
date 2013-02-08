<?php
/**
 * CKEditor CSS
 *
 * Overrides on the default CKEditor skin
 * Gives the textarea and buttons rounded corners
 * 
 * The rules are extra long in order to have enough
 * weight to override the CKEditor rules
 */
?>
/* CKEditor */

.elgg-page .cke_skin_BootstrapCK-Skin {
	border: 1px solid #CCC;
	padding: 0px;
	border-radius: 5px;
}

.elgg-page .cke_skin_BootstrapCK-Skin .cke_toolbar {
	border-radius: 5px;
}

.elgg-page .cke_skin_BootstrapCK-Skin .cke_toolbar .cke_toolgroup {
	margin: 0;
}

.elgg-page .cke_skin_BootstrapCK-Skin .cke_contents {
	background-color: white;
}

.elgg-page .cke_skin_BootstrapCK-Skin .cke_contents iframe {
	border: 0px;
}

.elgg-page .cke_skin_BootstrapCK-Skin .cke_bottom .cke_wordcount {
	float: left;
	margin-top: 5px;
	margin-right: 10px;
	padding-top: 1px;
	padding-left: 5px;
	font-family: Arial,Helvetica,Tahoma,Verdana,Sans-Serif;
	font-size: 12px;
}

.elgg-page .cke_skin_BootstrapCK-Skin .cke_bottom .cke_path {
	float: right;
}
.mceLast .mceStatusbar {
	padding-left: 5px;
}
