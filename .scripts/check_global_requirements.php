<?php

exec("composer global show -i fxp/composer-asset-plugin", $output, $returnVal);

if ($returnVal) {
	echo "**************************************************************************\n";
	echo "Did you forget to run `composer global require fxp/composer-asset-plugin`?\n";
	echo "**************************************************************************\n";
	echo "\n";
}

exit($returnVal);