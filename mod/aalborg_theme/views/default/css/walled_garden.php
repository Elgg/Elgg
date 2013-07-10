<?php
/**
 * Walled garden CSS
 */

?>
/* <style> /**/

.elgg-body-walledgarden {
	margin: 100px auto 0;
	position: relative;
	width: 530px;
}
.elgg-module-walledgarden {
	position: absolute;
	top: 0;
	left: 0;

    background-color: #FFF;
    border: 1px solid #DEDEDE;
    padding: 10px;

	border-radius: 3px;
	box-shadow: 1px 3px 5px rgba(0, 0, 0, 0.25);
}
.elgg-module-walledgarden > .elgg-head {
    padding: 20px 20px 0 20px;
}
.elgg-module-walledgarden > .elgg-body {
    padding: 0 20px;
}
.elgg-module-walledgarden > .elgg-foot {
    padding: 0 20px 20px 20px;
}
.elgg-menu-walled-garden {
	margin: 10px 0;
}
.elgg-walledgarden-single > .elgg-body {
	padding: 0 18px;
}
.elgg-body-walledgarden h3 {
	font-size: 1.5em;
	line-height: 1.1em;
	padding-bottom: 5px;
}
.elgg-heading-walledgarden {
	line-height: 1.1em;
}
.elgg-module-walledgarden .elgg-output img {
	width: 100%;
	height: auto;
}

@media (max-width: 600px) {
	.elgg-page-walledgarden {
		padding: 20px;
	}
    .elgg-body-walledgarden {
    	margin: 40px auto 0;
        width: auto;
    }
}
