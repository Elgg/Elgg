<?php

exec("composer global show fxp/composer-asset-plugin", $output, $returnVal);

if ($returnVal) {
	echo "************************************************************************" . PHP_EOL;
	echo "In order to install Elgg using composer, you must first run the command:" . PHP_EOL;
	echo "composer global require fxp/composer-asset-plugin" . PHP_EOL;
	echo "************************************************************************" . PHP_EOL;
	echo PHP_EOL;
}

exit($returnVal);