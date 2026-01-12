<?php
/**
 * CSS reset
 */

// https://necolas.github.io/normalize.css/
echo elgg_view('normalize.css');

?>
/* <style> /**/

/* Some of the reset concepts borrowed from https://bulma.io/ */
html,
body,
p,
ol,
ul,
li,
dl,
dt,
dd,
blockquote,
figure,
fieldset,
legend,
textarea,
pre,
iframe,
hr,
h1,
h2,
h3,
h4,
h5,
h6 {
	margin: 0;
	padding: 0;
}

h1,
h2,
h3,
h4,
h5,
h6 {
	font-size: 100%;
	font-weight: normal;
}

html {
	-webkit-box-sizing: border-box;
	box-sizing: border-box;
}

* {
	-webkit-box-sizing: inherit;
	box-sizing: inherit;
}

*:before, *:after {
	-webkit-box-sizing: inherit;
	box-sizing: inherit;
}

img,
embed,
object,
audio,
video {
	max-width: 100%;
}

iframe {
	border: 0;
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

td,
th {
	padding: 0;
	text-align: left;
}

html {
	background-color: white;
	font-size: var(--elgg-font-size);
	-moz-osx-font-smoothing: grayscale;
	-webkit-font-smoothing: antialiased;
	min-width: 300px;
	overflow-x: hidden;
	overflow-y: scroll;
	text-rendering: optimizeLegibility;
	-webkit-text-size-adjust: 100%;
	-moz-text-size-adjust: 100%;
	-ms-text-size-adjust: 100%;
	text-size-adjust: 100%;
}

/* Smooth scrolling IF user doesn't have a preference due to motion sensitivities */
@media screen and (prefers-reduced-motion: no-preference) {
	html {
		scroll-behavior: smooth;
	}
}

article,
aside,
figure,
footer,
header,
hgroup,
section {
	display: block;
}

body,
button,
input,
select,
textarea {
	font-family: var(--elgg-font-family);

}

code,
pre {
	-moz-osx-font-smoothing: auto;
	-webkit-font-smoothing: auto;
	font-family: monospace;
}

body {
	position: relative;
	color: var(--elgg-text-color-strong);
	background: var(--elgg-body-background-color);
	font-size: 1rem;
	font-weight: 400;
	line-height: 1.5;
}

a {
	cursor: pointer;
	text-decoration: none;
}

a strong {
	color: currentColor;
}

code {
	background-color: var(--elgg-background-color-soft);
	color: #ff3860;
	font-size: 0.875em;
	font-weight: normal;
	padding: 0.25em 0.5em 0.25em;
}

hr {
	background-color: var(--elgg-border-color-mild);
	border: none;
	display: block;
	height: 1px;
	margin: 1.5rem 0;
}

img {
	height: auto;
	max-width: 100%;
}

input[type="checkbox"],
input[type="radio"] {
	vertical-align: baseline;
}

small {
	font-size: 0.875em;
}

span {
	font-style: inherit;
	font-weight: inherit;
}

pre {
	-webkit-overflow-scrolling: touch;
	background-color: var(--elgg-background-color-soft);
	color: var(--elgg-text-color-strong);
	font-size: 0.875em;
	overflow-x: auto;
	padding: 1.25rem 1.5rem;
	white-space: pre;
	word-wrap: normal;
	
	code {
		background-color: transparent;
		color: currentColor;
		font-size: 1em;
		padding: 0;
	}
}

table td,
table th {
	text-align: left;
	vertical-align: top;
}

table th {
	color: #363636;
}

/* Elgg Reset /**/
ol, ul {
	list-style: none;
}

em, i {
	font-style: italic;
}

ins {
	text-decoration: none;
}

strike, del {
	text-decoration: line-through;
}

strong, b {
	font-weight: var(--elgg-font-bold-weight);
}

table {
	border-collapse: collapse;
	border-spacing: 0;
}

caption, th, td {
	text-align: left;
	font-weight: normal;
	vertical-align: top;
}

blockquote:before, blockquote:after,
q:before, q:after {
	content: "";
}

blockquote, q {
	quotes: "" "";
}

a {
	text-decoration: none;
}

button::-moz-focus-inner,
input::-moz-focus-inner {
	border: 0;
	padding: 0;
}

[hidden] {
	display: none !important;
}

fieldset {
	border: none;
	min-width: 0; /* override -webkit-min-content */
}

.ui-sortable {
	overflow: auto; /* fixes positioning if a parent container has position:relative */

	.ui-sortable-handle {
		cursor: move;
	}
}
