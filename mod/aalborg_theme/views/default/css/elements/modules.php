/* <style> /**/

/* ***************************************
	Modules
*************************************** */
.elgg-module {
	overflow: hidden;
	margin-bottom: 20px;
}

/* Aside */
.elgg-module-aside .elgg-head {
	border-bottom: 1px solid #DCDCDC;

	margin-bottom: 5px;
	padding-bottom: 5px;
}

/* Info */
.elgg-module-info > .elgg-head {
	background-color: #F0F0F0;
	padding: 10px;
	margin-bottom: 10px;
	height: auto;
	overflow: hidden;
	box-shadow: inset 0 0 1px #FFFFFF;
}
.elgg-module-info > .elgg-head * {
	color: #444;
}

/* Popup */
.elgg-module-popup {
	background-color: #FFF;
	border: 1px solid #DCDCDC;
	z-index: 9999;
	margin-bottom: 0;
	padding: 5px;
	border-radius: 3px;
	box-shadow: 4px 4px 4px rgba(0, 0, 0, 0.5);
}
.elgg-module-popup > .elgg-head {
	margin-bottom: 5px;
}
.elgg-module-popup > .elgg-head * {
	color: #0054A7;
}

/* Dropdown */
.elgg-module-dropdown {
	background-color: #FFF;
	border: 1px solid #DEDEDE;
	border-radius: 0 0 3px 3px;
	display:none;
	width: 240px;
	padding: 20px;
	margin-right: 0;
	z-index: 100;
	position: absolute;
	right: 0;
	top: 100%;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}

/* Featured */
.elgg-module-featured {
	border: 1px solid #DCDCDC;
	border-radius: 3px;
}
.elgg-module-featured > .elgg-head {
	background-color: #F0F0F0;
	padding: 10px;
	height: auto;
	overflow: hidden;
	border-bottom: 1px solid #DCDCDC;
	box-shadow: inset 0 0 1px #FFFFFF;
}
.elgg-module-featured > .elgg-head * {
	color: #666;
}
.elgg-module-featured > .elgg-body {
	padding: 10px;
}

/* ***************************************
	Widgets
*************************************** */
.elgg-widgets {
	min-height: 30px;
}
.elgg-widget-add-control {
	text-align: right;
	margin: 0 5px 15px;
}
.elgg-widgets-add-panel {
	padding: 10px;
	margin: 0 5px 15px;
	background: #DEDEDE;
	border: 2px solid #ccc;
}
<?php //@todo location-dependent style: make an extension of elgg-gallery ?>
.elgg-widgets-add-panel li {
	float: left;
	margin: 2px 10px;
	width: 200px;
	padding: 4px;
	background-color: #CCC;
	border: 2px solid #B0B0B0;
	font-weight: bold;
}
.elgg-widgets-add-panel li a {
	display: block;
}
.elgg-widgets-add-panel .elgg-state-available {
	color: #333;
	cursor: pointer;
}
.elgg-widgets-add-panel .elgg-state-available:hover {
	background-color: #BCBCBC;
}
.elgg-widgets-add-panel .elgg-state-unavailable {
	color: #888;
}

.elgg-module-widget {
	border: 1px solid #DCDCDC;
	margin: 0 10px 15px;
	position: relative;
}
.elgg-module-widget:hover {
	box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
}
.elgg-module-widget > .elgg-head {
	background-color: #F0F0F0;
	padding: 10px 0;
	height: auto;
	overflow: hidden;
	box-shadow: inset 0 0 1px #FFFFFF;
}
.elgg-module-widget > .elgg-head h3 {
	float: left;
	padding: 0 45px 0 30px;
	color: #666;
}
.elgg-module-widget.elgg-state-draggable .elgg-widget-handle {
	cursor: move;
}
a.elgg-widget-collapse-button {
	color: #C5C5C5;
}
a.elgg-widget-collapse-button:hover,
a.elgg-widget-collapsed:hover {
	color: #9D9D9D;
	text-decoration: none;
}
a.elgg-widget-collapse-button:before {
	content: "\25BC";
}
a.elgg-widget-collapsed:before {
	content: "\25BA";
}
.elgg-module-widget > .elgg-body {
	background-color: #FFF;
	width: 100%;
	overflow: hidden;
	border-top: 1px solid #DCDCDC;
}
.elgg-widget-edit {
	display: none;
	width: auto;
	padding: 10px;
	border-bottom: 1px solid #DCDCDC;
	background-color: #F9F9F9;
}
.elgg-widget-content {
	padding: 10px;
}
.elgg-widget-placeholder {
	border: 1px dashed #DEDEDE;
	margin-bottom: 15px;
}
