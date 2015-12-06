<?php

exec("composer global show -i fxp/composer-asset-plugin", $output, $returnVal);

if ($returnVal) {
	echo "************************************************************************\n";
	echo "In order to install Elgg using composer, you must first run the command:\n";
	echo "composer global require fxp/composer-asset-plugin\n ";
	echo "************************************************************************\n";
	echo "\n";
}

exit($returnVal);