<?php
return array(

	/**
	 * Menu items and titles
	 */
	'groups' => "Группы",
	'groups:owned' => "Ваши собственные группы",
	'groups:owned:user' => 'Группы владельца %s ',
	'groups:yours' => "Группы, в которых состоите",
	'groups:user' => "Группы пользователя %s",
	'groups:all' => "Все группы на сайте",
	'groups:add' => "Создать группу",
	'groups:edit' => "Редактировать группу",
	'groups:delete' => 'Удалить группу',
	'groups:membershiprequests' => 'Управление запросами приглашения',
	'groups:membershiprequests:pending' => 'Управление запросами приглашений (%s)',
	'groups:invitations' => 'Приглашения группы',
	'groups:invitations:pending' => 'Приглашения группы (%s)',

	'groups:icon' => 'Иконка группы',
	'groups:name' => 'Название группы',
	'groups:description' => 'Описание',
	'groups:briefdescription' => 'Краткое описание',
	'groups:interests' => 'Интересы',
	'groups:website' => 'Сайт',
	'groups:members' => 'Участники',

	'groups:members_count' => '%s members',

	'groups:members:title' => 'Участники %s',
	'groups:members:more' => "Показать всех участников группы",
	'groups:membership' => "Ограничения в членстве",
	'groups:content_access_mode' => "Доступность контента группы",
	'groups:content_access_mode:warning' => "Внимание: Изменение этой настройки не поменяет права доступа на существующий контент группы.",
	'groups:content_access_mode:unrestricted' => "Не ограничено - Доступ зависит от настроек доступа записи",
	'groups:content_access_mode:membersonly' => "Только участникам - Не участники группы не смогут получить доступ к контенту группы",
	'groups:access' => "Доступ к группе",
	'groups:owner' => "Владелец",
	'groups:owner:warning' => "Внимание: если вы измените это значение, вы больше не будете владельцем группы.",
	'groups:widget:num_display' => 'Число отображаемых групп',
	'widgets:a_users_groups:name' => 'Group membership',
	'widgets:a_users_groups:description' => 'Display the groups you are a member of on your profile',

	'groups:noaccess' => 'Нет доступа к группе',
	'groups:cantcreate' => 'Вы не можете создать группу. Только админы могут.',
	'groups:cantedit' => 'Вы не можете редактировать эту группу',
	'groups:saved' => 'Сохранено',
	'groups:save_error' => 'Группа не может быть сохранена',
	'groups:featured' => 'Избранные группы',
	'groups:makeunfeatured' => 'Убрать из избранного',
	'groups:makefeatured' => 'Добавить в избранные',
	'groups:featuredon' => 'Сделано.',
	'groups:unfeatured' => 'Убрано.',
	'groups:featured_error' => 'Ошибка.',
	'groups:nofeatured' => 'Нет избранных групп',
	'groups:joinrequest' => 'Попросить членство',
	'groups:join' => 'Вступить в группу',
	'groups:leave' => 'Покинуть группу',
	'groups:invite' => 'Пригласить в группу',
	'groups:invite:title' => 'Пригласить в группу',
	'groups:invite:friends:help' => 'Search for a friend by name or username and select the friend from the list',
	'groups:invite:resend' => 'Resend the invitations to already invited users',

	'groups:nofriendsatall' => 'Некого приглашать!',
	'groups:group' => "Группа",
	'groups:search:tags' => "Интересы",
	'groups:search:title' => "Искать группу по интересам: '%s'",
	'groups:search:none' => "Ничего не найдено!",
	'groups:search_in_group' => "Поиск в этой группе",
	'groups:acl' => "Группа: %s",
	'groups:acl:in_context' => 'Group members',

	'groups:notfound' => "Группа не найдена",
	
	'groups:requests:none' => 'Пока нет никаких запросов членства.',

	'groups:invitations:none' => 'There are no oustanding invitations at this time.',

	'groups:open' => "открытая группа",
	'groups:closed' => "закрытая группа",
	'groups:member' => "участников",
	'groups:search' => "Search for groups",

	'groups:more' => 'Еще группы',
	'groups:none' => '---',

	/**
	 * Access
	 */
	'groups:access:private' => 'Закрыто - только по приглашениям',
	'groups:access:public' => 'Открыто - заходи, проходи народ!',
	'groups:access:group' => 'Group members only',
	'groups:closedgroup' => "Это закрытая группа.",
	'groups:closedgroup:request' => 'В этой групп вход только по приглашениям. Чтобы получить доступ, нажмите "Попросить членство" в ссылках меню.',
	'groups:closedgroup:membersonly' => "Это закрытая группа и просмотр содержимого возможен только для участников.",
	'groups:opengroup:membersonly' => "Содержание этой группы доступно только участникам.",
	'groups:opengroup:membersonly:join' => 'Чтобы стать участником группы, нажмите "Вступить в группу"',
	'groups:visibility' => 'Кто может просматривать группу?',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Группы',

	'groups:notitle' => 'Группы должны иметь название.',
	'groups:cantjoin' => 'Простите, невозможно вступить в группу.',
	'groups:cantleave' => 'Простите, невозможно покинуть группу.',
	'groups:removeuser' => 'Удалить из группы',
	'groups:cantremove' => 'Извините, не могу удалить из группы',
	'groups:removed' => '%s удален(а) из группы',
	'groups:addedtogroup' => 'Пользователь добавлен в группу.',
	'groups:joinrequestnotmade' => 'Простите, запрос не может быть осуществлен.',
	'groups:joinrequestmade' => 'Запрос осуществлен.',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'Вы вступили в группу!',
	'groups:left' => 'Вы покинули группу',
	'groups:userinvited' => 'Пользователь приглашен.',
	'groups:usernotinvited' => 'Простите, пользователь не может быть приглашен.',
	'groups:useralreadyinvited' => 'Пользователь уже был приглашен',
	'groups:invite:subject' => "%s you have been invited to join %s!",
	'groups:joinrequest:remove:check' => 'Удалить запрос приглашения?',
	'groups:invite:remove:check' => 'Удалить приглашение?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group.

Click below to view your invitations:
%s",

	'groups:welcome:subject' => "Добро пожаловать в группу %s!",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group.

Click below to begin posting!
%s",

	'groups:request:subject' => "%s попросил вступить в группу %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group.

Click below to view their profile:
%s

or click below to view the group's join requests:
%s",

	'river:group:create' => '%s created the group %s',
	'river:group:join' => '%s joined the group %s',

	'groups:allowhiddengroups' => 'Хотите вступить в подпольную группу?',
	'groups:whocancreate' => 'Кто может создавать новые группы?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Group and group contents deleted',
	'groups:notdeleted' => 'Group could not be deleted',
	'groups:deletewarning' => "Вы уверены, что хотите удалить эту группу? Возврата не будет!",

	'groups:invitekilled' => 'Приглашение удалено.',
	'groups:joinrequestkilled' => 'Запрос приглашения отклонен.',
	'groups:error:addedtogroup' => "Не удалось добавить %s в группу",
	'groups:add:alreadymember' => "%s уже состоит в этой группе",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Профили группы',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Transfer group icons to new location',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade aligns will align group plugin with the requirements of the new API.',
);
