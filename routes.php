<?php

return [
	'/'                  => 'index',
	'/activity'          => 'site/activity',
	'/avatar'            => 'avatar/view',
	'/avatar/edit'       => 'avatar/edit',
	'/changepassword'    => 'account/change_password',
	'/admin/{segments*}' => 'admin',
	'/ajax/view/{view*}' => 'ajax/view',
	'/ajax/form/{form*}' => 'ajax/form',
	'/cron/{period}'     => 'cron',
	'/forgotpassword'    => 'account/forgotten_password',
	'/login'             => 'account/login',
	'/profile'           => 'user/profile',
	'/refresh_token'     => 'refresh_token',
	'/register'          => 'account/register',
	'/robots.txt'        => 'robots.txt',
];