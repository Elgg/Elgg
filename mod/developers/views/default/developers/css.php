/*<style>*/
/*** Elgg Developer Tools ***/
#developers-iframe {
	width: 100%;
	height: 600px;
	border: none;
}
.developers-log {
	background-color: #EBF5FF;
	border: 1px solid #999;
	color: #666;
	padding: 20px;
}
.developers-gear {
	position: fixed;
	z-index: 1000;
	bottom: 0;
	right: 0;
	cursor: pointer;
	padding: 5px 8px;
}

.developers-gear-popup {
	display: flex;
	justify-content: space-evenly;

	> section {
		width: 16em;
	}
	> section.developers-form {
		width: 24em;
	}

	h2 {
		margin-bottom: 10px;
	}

	.elgg-child-menu {
		margin-left: 20px;
		margin-bottom: 10px;
	}

	.elgg-menu-parent,
	.elgg-menu-parent:hover {
		color: #000;
		text-decoration: none;
		cursor: default;
	}

	.elgg-text-help {
		display: none;
	}

	label {
		font-weight: inherit;
	}

	fieldset > div {
		margin-bottom: 5px;
	}

	#developer-settings-form {
		label .elgg-icon-info,
		label .elgg-text-help {
			margin-left: 10px;
			vertical-align: text-top;
			cursor: pointer;
		}

		.elgg-foot {
			margin-top: 15px;
			margin-bottom: 0;
		}
	}
}
