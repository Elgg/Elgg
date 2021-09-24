<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	
	'relationship:friendrequest' => "%s запросил дружбу с %s",
	'relationship:friendrequest:pending' => "%s хочет быть вашим другом",
	'relationship:friendrequest:sent' => "Вы запросили дружбу с %s",
	
	// plugin settings
	'friends:settings:request:description' => "По умолчанию, любой пользователь может подружиться с любым другим пользователем, это похоже на отслеживание активности другого пользователя.
После включения запросов на дружбу, когда пользователь A хочет дружить с пользователем B, пользователь B должен подтвердить запрос. После подтверждения пользователь A будет дружить с пользователем B, а пользователь B будет дружить с пользователем A.",
	'friends:settings:request:label' => "Включить запросы на дружбу",
	'friends:settings:request:help' => "Пользователи должны одобрить запрос на добавление в друзья, и дружба станет обоюдной",
	
	'friends:owned' => "Друзья пользователя %s",
	'friend:add' => "Добавить друга",
	'friend:remove' => "Удалить друга",
	'friends:menu:request:status:pending' => "Запрос на дружбу рассматривается",

	'friends:add:successful' => "Вы успешно добавили %sв друзья",
	'friends:add:duplicate' => "Вы уже друзья с %s",
	'friends:add:failure' => "Не удается добавить %s в друзья.",
	'friends:request:successful' => 'Запрос на дружбу был отправлен пользователю %s',
	'friends:request:error' => 'Произошла ошибка при отправке запроса на дружбу с %s',

	'friends:remove:successful' => "Вы успешно удалили %sиз своих друзей",
	'friends:remove:no_friend' => "Вы и %s не являетесь друзьями",
	'friends:remove:failure' => "Не удается удалить %sиз ваших друзей",

	'friends:none' => "Пока нет друзей.",
	'friends:of:owned' => "Люди, которые добавили %sв друзья",

	'friends:of' => "В друзьях",
	
	'friends:request:pending' => "Запросы на дружбу",
	'friends:request:pending:none' => "Запросы на дружбу не найдены.",
	'friends:request:sent' => "Отправленные запросы на дружбу",
	'friends:request:sent:none' => "Запросы на дружбу не отправлялись",
	
	'friends:num_display' => "Количество друзей",
	
	'widgets:friends:name' => "Друзья",
	'widgets:friends:description' => "Отображает некоторых ваших друзей",
	
	'widgets:friends_of:name' => "В друзьях",
	'widgets:friends_of:description' => "Отображает у кого вы в друзьях",
	
	'friends:notification:request:subject' => "%s хочет быть вашим другом!",
	'friends:notification:request:message' => "%s запросил дружбу с вами на %s.

Нажмите здесь,чтобы посмотреть запрос на дружбу:
%s",
	
	'friends:notification:request:decline:subject' => "%s отклонил ваш запрос на дружбу",
	'friends:notification:request:decline:message' => "%s отклонил ваш запрос на дружбу.",
	
	'friends:notification:request:accept:subject' => "%s принял ваш запрос на дружбу",
	'friends:notification:request:accept:message' => "%s принял ваш запрос на дружбу.",
	
	'friends:action:friendrequest:revoke:fail' => "При отмене запроса на дружбу произошла ошибка. Повторите попытку.",
	'friends:action:friendrequest:revoke:success' => "Запрос на дружбу был отменен",
	
	'friends:action:friendrequest:decline:fail' => "При отклонении запроса на дружбу произошла ошибка. Повторите попытку.",
	'friends:action:friendrequest:decline:success' => "Запрос на дружбу был оклонен",
	
	'friends:action:friendrequest:accept:success' => "Запрос на дружбу был принят",
	
	// notification settings
	'friends:notification:settings:description' => 'Настройки уведомлений по умолчанию для пользователей, которых вы добавляете в друзья',
);
