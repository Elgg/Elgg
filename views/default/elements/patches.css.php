<?php

/**
 * @warning INTERNAL USE ONLY. DO NOT OVERRIDE THIS VIEW.
 * @access private
 *
 * Patches and features that were included between major releases
 * sometimes require additional styling, but adding them to core CSS files
 * is not always feasible, because those can be replaced by themes.
 *
 * @note Accoring to our BC policy, user-facing changes are not allowed in
 * minor and bugfix releases. Use this file sparsely.
 *
 * @todo Remove in 3.0
 */
?>

.elgg-fieldset-has-legend {
	border: 1px solid #dedede;
	padding: 10px;
}

.elgg-fieldset-horizontal .elgg-field {
    display: inline-block;
    margin: 0 10px 0 0;
}

.elgg-fieldset-horizontal.elgg-justify-right .elgg-field {
    margin: 0 0 0 10px;
}

.elgg-fieldset-horizontal.elgg-justify-center .elgg-field {
    margin: 0 5px;
}

.elgg-justify-center {
	text-align: center;
}

.elgg-justify-right {
	text-align: right;
}

.elgg-justify-left {
	text-align: left;
}
