<?php
return array(
	'admin:users:unvalidated' => 'Непровереный',
	
	'email:validate:subject' => "%s пожалуйста, подтвердите свой email для %s!",
	'email:validate:body' => "%s,

Прежде чем вы сможете пользоваться сайтом %s, вы должны подтвердить ваш email адрес.

Пожалуйста, подтвердите ваш email адрес нажав на ссылку:

%s

Если Вы не можете нажать на ссылку, скопируйте ее и вставьте в адресной строке Вашего браузера.

%s
%s
",
	'email:confirm:success' => "Вы подтвердили свой email адрес!",
	'email:confirm:fail' => "Ваш email адрес не может быть проверен...",

	'uservalidationbyemail:emailsent' => "Email отправлен <em>%s</em>",
	'uservalidationbyemail:registerok' => "Для активации вашего аккаунта, пожалуйста подтвердите ваш email адрес нажав на ссылку в письме, которое мы вам только что отправили.",
	'uservalidationbyemail:login:fail' => "Ваш аккаунт не подтвержден поэтому вы не можете войти. Проверьте почту, мы отправили вам запрос на подтверждение повторно.",

	'uservalidationbyemail:admin:no_unvalidated_users' => 'Нет неподтвержденных пользователей.',

	'uservalidationbyemail:admin:unvalidated' => 'Неподтвержденные',
	'uservalidationbyemail:admin:user_created' => 'Зарегистрированный %s',
	'uservalidationbyemail:admin:resend_validation' => 'Отправить повторно подтверждение',
	'uservalidationbyemail:admin:validate' => 'Подтвержденные',
	'uservalidationbyemail:confirm_validate_user' => 'Утвердить %s?',
	'uservalidationbyemail:confirm_resend_validation' => 'Повторно отправить проверку email %s?',
	'uservalidationbyemail:confirm_delete' => 'Удалить %s?',
	'uservalidationbyemail:confirm_validate_checked' => 'Утвердить отмеченных пользователей?',
	'uservalidationbyemail:confirm_resend_validation_checked' => 'Переслать подтверждение отмеченным пользователям?',
	'uservalidationbyemail:confirm_delete_checked' => 'Удалить отмеченных пользователей?',
	
	'uservalidationbyemail:errors:unknown_users' => 'Неизвестные пользователи',
	'uservalidationbyemail:errors:could_not_validate_user' => 'Не могу подтвердить пользователя.',
	'uservalidationbyemail:errors:could_not_validate_users' => 'Не могу подтвердить всех отмеченных пользователей.',
	'uservalidationbyemail:errors:could_not_delete_user' => 'Не могу удалить пользователя.',
	'uservalidationbyemail:errors:could_not_delete_users' => 'Не могу удалить отмеченных пользователей.',
	'uservalidationbyemail:errors:could_not_resend_validation' => 'Не могу повторно отправить запрос на подтверждение.',
	'uservalidationbyemail:errors:could_not_resend_validations' => 'Не могу повторно отправить запрос на подтверждение отмеченным пользователям.',

	'uservalidationbyemail:messages:validated_user' => 'Пользователь подтвержден.',
	'uservalidationbyemail:messages:validated_users' => 'Отмеченные пользователи подтверждены.',
	'uservalidationbyemail:messages:deleted_user' => 'Пользователь удален.',
	'uservalidationbyemail:messages:deleted_users' => 'Отмеченные пользователи удалены.',
	'uservalidationbyemail:messages:resent_validation' => 'Запрос на подтверждение переслан.',
	'uservalidationbyemail:messages:resent_validations' => 'Запрос отправлен всем отмеченным пользователям.'

);
