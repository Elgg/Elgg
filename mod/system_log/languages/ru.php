<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(
	'admin:administer_utilities:logbrowser' => 'Журнал логов',
	'logbrowser:search' => 'Уточнить результаты',
	'logbrowser:user' => 'Имя пользователя для поиска',
	'logbrowser:starttime' => 'Начальное время (например, "в прошлый понедельник", "1 час назад")',
	'logbrowser:endtime' => 'Время окончания',

	'logbrowser:explore' => 'Изучить журнал логов',

	'logbrowser:date' => 'Дата и время',
	'logbrowser:ip_address' => 'IP-адрес',
	'logbrowser:user:name' => 'Пользователь',
	'logbrowser:user:guid' => 'GUID пользователя',
	'logbrowser:object' => 'Тип объекта',
	'logbrowser:object:id' => 'ID объекта',
	'logbrowser:action' => 'Действие',

	'logrotate:period' => 'Как часто следует архивировать системный журнал?',
	'logrotate:retention' => 'Удалять архивные журналы старше x дней',
	'logrotate:retention:help' => 'Количество дней, в течение которых вы хотите хранить архивные журналы в базе данных. Оставьте пустым, чтобы не очищать архивные журналы.',

	'logrotate:logrotated' => "Журнал логов обновлен",
	'logrotate:lognotrotated' => "Ошибка обновления журнала логов",

	'logrotate:logdeleted' => "Журнал логов удален",
	'logrotate:lognotdeleted' => "Журналы логов не удалены",
);
