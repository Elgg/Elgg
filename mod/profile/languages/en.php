<?php

return [
	'profile' => 'Profile',
	'profile:notfound' => 'Sorry. We could not find the requested profile.',
	'profile:upgrade:2017040700:title' => 'Migrate schema of profile fields',
	'profile:upgrade:2017040700:description' => '<p>This migration converts profile fields from metadata to annotations with each name prefixed with "profile:". <strong>Note:</strong> If you have "inactive" profile fields you want migrated, re-create those fields and re-load this page to make sure they get migrated.</p>',
];
