<?php
/**
 * This will hold the required keyword functions to display frontpage content
 **/
 
function parse_frontpage($frontContents){
	echo htmlspecialchars_decode($frontContents, ENT_NOQUOTES);
}