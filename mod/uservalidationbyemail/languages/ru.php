<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'email:validate:subject' => "%s подтвердите свой email для %s!",
	'email:validate:body' => "Перед тем, как начать пользоваться %s, вы должны подтвердить email адрес.

Подтвердите ваш email адрес, нажав на эту ссылку:

%s

Если вы не можете перейти по ссылке, то вручную скопируйте и вставьте ее в браузер.",
	'email:confirm:success' => "Вы подтвердили свой email адрес",
	'email:confirm:fail' => "Ваш email адрес не может быть проверен...",

	'uservalidationbyemail:emailsent' => "Email отправлен <em>%s</em>",
	'uservalidationbyemail:registerok' => "Чтобы активировать ваш аккаунта, подтвердите ваш email адрес, нажав на ссылку, которую мы вам только что отправили.",
	'uservalidationbyemail:login:fail' => "Ваш аккаунт не подтвержден поэтому вы не можете войти. Проверьте почту, мы отправили вам запрос на подтверждение повторно.",

	'uservalidationbyemail:admin:resend_validation' => 'Повторно отправить подтверждение',
	'uservalidationbyemail:confirm_resend_validation' => 'Отправить повторно проверку email %s?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Переслать подтверждение отмеченным пользователям?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Неизвестные пользователи',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Не удается повторно отправить запрос на подтверждение.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Не удается повторно отправить запросы на подтверждение отмеченным пользователям.',

	'uservalidationbyemail:messages:resent_validation' => 'Запрос на подтверждение переслан.',
	'uservalidationbyemail:messages:resent_validations' => 'Запросы отправлены всем отмеченным пользователям.',
);
