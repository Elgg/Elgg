<?php
return array(
	'email:validate:subject' => "%s пожалуйста, подтвердите свой email для %s!",
	'email:validate:body' => "Hi %s,

Before you can start you using %s, you must confirm your email address.

Please confirm your email address by clicking on the link below:

%s

If you can't click on the link, copy and paste it to your browser manually.

%s
%s",
	'email:confirm:success' => "Вы подтвердили свой email адрес!",
	'email:confirm:fail' => "Ваш email адрес не может быть проверен...",

	'uservalidationbyemail:emailsent' => "Email отправлен <em>%s</em>",
	'uservalidationbyemail:registerok' => "Для активации вашего аккаунта, пожалуйста подтвердите ваш email адрес нажав на ссылку в письме, которое мы вам только что отправили.",
	'uservalidationbyemail:login:fail' => "Ваш аккаунт не подтвержден поэтому вы не можете войти. Проверьте почту, мы отправили вам запрос на подтверждение повторно.",

	'uservalidationbyemail:admin:resend_validation' => 'Отправить повторно подтверждение',
	'uservalidationbyemail:confirm_resend_validation' => 'Повторно отправить проверку email %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Переслать подтверждение отмеченным пользователям?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Неизвестные пользователи',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Не могу повторно отправить запрос на подтверждение.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Не могу повторно отправить запрос на подтверждение отмеченным пользователям.',

	'uservalidationbyemail:messages:resent_validation' => 'Запрос на подтверждение переслан.',
	'uservalidationbyemail:messages:resent_validations' => 'Запрос отправлен всем отмеченным пользователям.'
);
