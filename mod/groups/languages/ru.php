<?php
/**
 * Translation file
 *
 * Note: don't change the return array to short notation because Transifex can't handle those during `tx push -s`
 */

return array(

	/**
	 * Menu items and titles
	 */
	'add:group:group' => "Создать новую группу",
	
	'groups' => "Группы",
	'groups:owned' => "Свои группы",
	'groups:owned:user' => 'Группы основателя %s ',
	'groups:yours' => "Мои группы",
	'groups:user' => "Группы пользователя %s",
	'groups:all' => "Все группы",
	'groups:add' => "Создать группу",
	'groups:edit' => "Редактировать группу",
	'groups:edit:profile' => "Профиль",
	'groups:edit:access' => "Доступ",
	'groups:edit:tools' => "Инструменты",
	'groups:edit:settings' => "Настройки",
	'groups:membershiprequests' => 'Управление запросами',
	'groups:membershiprequests:pending' => 'Управление запросами (%s)',
	'groups:invitedmembers' => "Управление приглашениями",
	'groups:invitations' => 'Приглашения группы',
	'groups:invitations:pending' => 'Приглашения группы (%s)',
	
	'relationship:invited' => '%2$s был приглашен вступить в группу %1$s',
	'relationship:membership_request' => '%s запросил вступить в группу %s',

	'groups:icon' => 'Аватар группы (оставьте пустым, чтобы не менять)',
	'groups:name' => 'Название группы',
	'groups:description' => 'Описание',
	'groups:briefdescription' => 'Краткое описание',
	'groups:interests' => 'Теги',
	'groups:website' => 'Сайт',
	'groups:members' => 'Участники',

	'groups:members_count' => '%s участников',

	'groups:members:title' => 'Участники группы %s',
	'groups:members:more' => "Все участники группы",
	'groups:membership' => "Ограничения в членстве",
	'groups:content_access_mode' => "Доступность контента группы",
	'groups:content_access_mode:warning' => "Внимание: Изменение этой настройки не поменяет права доступа на существующий контент группы.",
	'groups:content_access_mode:unrestricted' => "Не ограничено - Доступ зависит от настроек доступа контента",
	'groups:content_access_mode:membersonly' => "Только участникам - Не участники группы не смогут получить доступ к контенту группы",
	'groups:access' => "Ограничения доступа",
	'groups:owner' => "Основатель",
	'groups:owner:warning' => "Внимание: если вы измените это значение, вы больше не будете основателем группы.",
	'groups:widget:num_display' => 'Число отображаемых групп',
	'widgets:a_users_groups:name' => 'Членство',
	'widgets:a_users_groups:description' => 'Число отображаемых групп',

	'groups:noaccess' => 'Нет доступа к группе',
	'groups:cantcreate' => 'Вы не можете создать группу. Только админы могут.',
	'groups:cantedit' => 'Вы не можете редактировать эту группу',
	'groups:saved' => 'Сохранено',
	'groups:save_error' => 'Группа не может быть сохранена',
	'groups:featured' => 'Избранные группы',
	'groups:makeunfeatured' => 'Убрать из избранного',
	'groups:makefeatured' => 'Добавить в избранные',
	'groups:featuredon' => '%s теперь рекомендуемая группа.',
	'groups:unfeatured' => '%s удалена из рекомендуемых групп.',
	'groups:featured_error' => 'Ошибка с группой.',
	'groups:nofeatured' => 'Нет избранных групп',
	'groups:joinrequest' => 'Запросить членство',
	'groups:join' => 'Вступить в группу',
	'groups:leave' => 'Покинуть группу',
	'groups:invite' => 'Пригласить друзей',
	'groups:invite:title' => 'Пригласить друзей в группу',
	'groups:invite:friends:help' => 'Найдите друга по имени или имени пользователя и выберите его из списка',
	'groups:invite:resend' => 'Снова отправить приглашения уже приглашенным пользователям',
	'groups:invite:member' => 'Уже участник этой группы',
	'groups:invite:invited' => 'Уже приглашен в эту группу',

	'groups:nofriendsatall' => 'У вас нет друзей, чтобы их пригласить',
	'groups:group' => "Группа",
	'groups:search:title' => "Поиск групп: '%s'",
	'groups:search:none' => "Группы не найдены",
	'groups:search_in_group' => "Поиск в группе",
	'groups:acl' => "Группа: %s",
	'groups:acl:in_context' => 'Участники',

	'groups:notfound' => "Группа не найдена",
	
	'groups:requests:none' => 'Нет текущих запросов членства.',

	'groups:invitations:none' => 'Нет текущих приглашений.',

	'groups:open' => "открытая группа",
	'groups:closed' => "закрытая группа",
	'groups:member' => "участников",
	'groups:search' => "Поиск групп",

	'groups:more' => 'Больше групп',
	'groups:none' => 'Нет групп',

	/**
	 * Access
	 */
	'groups:access:private' => 'Закрыто - Пользователи должны быть приглашены',
	'groups:access:public' => 'Открыто - Любой пользователь может вступить',
	'groups:access:group' => 'Только участники группы',
	'groups:closedgroup' => "Это закрытая группа.",
	'groups:closedgroup:request' => 'Чтобы получить доступ, нажмите "Запросить членство" в меню.',
	'groups:closedgroup:membersonly' => "Это закрытая группа и просмотр содержимого возможен только для участников.",
	'groups:opengroup:membersonly' => "Содержание этой группы доступно только участникам.",
	'groups:opengroup:membersonly:join' => 'Чтобы стать участником группы, нажмите "Вступить в группу" в меню.',
	'groups:visibility' => 'Кто может просматривать группу?',
	'groups:content_default_access' => 'Доступ к контенту группы по умолчанию',
	'groups:content_default_access:help' => 'Здесь мы можете настроить доступ по умолчанию для нового контента в группе. Настройка контента может воспрепятствовать действию выбранной опции.',
	'groups:content_default_access:not_configured' => 'Доступ по умолчанию не настроен, оставьте на усмотрение пользователя',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Группы',

	'groups:notitle' => 'Группы должны иметь название',
	'groups:cantjoin' => 'Не удалось вступить в группу',
	'groups:cantleave' => 'Не удалось покинуть группу',
	'groups:removeuser' => 'Удалить из группы',
	'groups:cantremove' => 'Не удалось удалить пользователя из группы',
	'groups:removed' => '%s удален из группы',
	'groups:addedtogroup' => 'Пользователь добавлен в группу',
	'groups:joinrequestnotmade' => 'Не удалось отправить запрос на вступление в группу',
	'groups:joinrequestmade' => 'Запрос на вступление в группу отправлен',
	'groups:joinrequest:exists' => 'Вы уже запросили членство в этой группе',
	'groups:button:joined' => 'Вступил',
	'groups:button:owned' => 'Основал',
	'groups:joined' => 'Вы вступили в группу!',
	'groups:left' => 'Вы покинули группу',
	'groups:userinvited' => 'Пользователь приглашен.',
	'groups:usernotinvited' => 'Пользователь не может быть приглашен.',
	'groups:useralreadyinvited' => 'Пользователь уже был приглашен',
	'groups:invite:subject' => "%s вы приглашены вступить в группу %s!",
	'groups:joinrequest:remove:check' => 'Хотите удалить этот запрос на вступление в группу?',
	'groups:invite:remove:check' => 'Хотите удалить это приглашение?',
	'groups:invite:body' => "%s пригласил вас вступить в группу '%s'.

Нажмите, чтобы просмотреть ваши приглашения:
%s",

	'groups:welcome:subject' => "Добро пожаловать в группу %s!",
	'groups:welcome:body' => "Вы теперь участник группы '%s'.

Нажмите, чтобы начать публиковать!
%s",

	'groups:request:subject' => "%s попросил вступить в группу %s",
	'groups:request:body' => "%s запросил о вступлении в группу '%s'.

Нажмите, чтобы просмотреть профиль пользователя:
%s

или нажмите тут, чтобы просмотреть все запросы на вступление в группу:
%s",

	'river:group:create' => '%s создал группу %s',
	'river:group:join' => '%s стал участником группы %s',

	'groups:allowhiddengroups' => 'Разрешить приватные (скрытые) группы?',
	'groups:whocancreate' => 'Кто может создавать новые группы?',

	/**
	 * Action messages
	 */

	'groups:invitekilled' => 'Приглашение удалено.',
	'groups:joinrequestkilled' => 'Запрос на вступление в группу удален.',
	'groups:error:addedtogroup' => "Не удалось добавить %s в группу",
	'groups:add:alreadymember' => "%s уже состоит в этой группе",
	
	// Notification settings
	'groups:usersettings:notification:group_join:description' => "Настройки уведомлений по умолчанию для группы при вступлении в новую группу",
	
	'groups:usersettings:notifications:title' => 'Уведомления группы',
	'groups:usersettings:notifications:description' => 'Чтобы получать уведомления, когда новый контент добавляется в группу, членом которой вы являетесь, найдите его ниже и выберите способ(ы) уведомления, который вы хотите использовать.',
);
