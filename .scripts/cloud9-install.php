<?php

$elggRoot = dirname(__DIR__);

// Install Elgg on Cloud9IDE
`sudo composer self-update`;
`composer global require fxp/composer-asset-plugin:~1.0`;
`composer install`;
`cp $elggRoot/install/config/htaccess.dist .htaccess`;
`cp $elggRoot/engine/settings.cloud9.php settings.php`;
`mysql-ctl start`;
`mkdir ~/elgg-data`;
