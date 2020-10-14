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
	'groups' => "Групе",
	'groups:owned' => "Групе чији сам власник",
	'groups:owned:user' => 'Групе чији је %s власник',
	'groups:yours' => "Моје групе",
	'groups:user' => "%s групе",
	'groups:all' => "Све групе",
	'groups:add' => "Направи нову групу",
	'groups:edit' => "Измени групу",
	'groups:delete' => 'Обриши групу',
	'groups:membershiprequests' => 'Управљај захтевима за придруживање',
	'groups:membershiprequests:pending' => 'Управљај захтевима за придруживање (%s)',
	'groups:invitedmembers' => "Manage invitations",
	'groups:invitations' => 'Позиви у групу',
	'groups:invitations:pending' => 'Позиви у групу (%s)',
	
	'relationship:invited' => '%2$s was invited to join %1$s',
	'relationship:membership_request' => '%s requested to join %s',

	'groups:icon' => 'Икона групе (оставте празно ако не желите да мењате)',
	'groups:name' => 'Име групе',
	'groups:description' => 'Опис',
	'groups:briefdescription' => 'Кратак опис',
	'groups:interests' => 'Ознаке',
	'groups:website' => 'Вебсајт',
	'groups:members' => 'Чланови групе',

	'groups:members_count' => '%s members',

	'groups:members:title' => 'Чланови %s',
	'groups:members:more' => "Види све чланове",
	'groups:membership' => "Дозволе чланства у групи",
	'groups:content_access_mode' => "Дозволе на садржај групе",
	'groups:content_access_mode:warning' => "Упозорење: Мењање овог подешавања неће променити дозволе приступа већ посојећег садржаја у групи.",
	'groups:content_access_mode:unrestricted' => "Слободан - Приступ зависи од нивоа приступа садржаја",
	'groups:content_access_mode:membersonly' => "Само чланови - Они који нису чланови не могу да приступе садржају групе",
	'groups:access' => "Дозволе приступа",
	'groups:owner' => "Власник",
	'groups:owner:warning' => "Упозорење: ако промените ову вредност, више нећете бити власник групе.",
	'groups:widget:num_display' => 'Колико група приказати',
	'widgets:a_users_groups:name' => 'Чланство групе',
	'widgets:a_users_groups:description' => 'Прикажи групе у којима сте члан на вашем профилу',

	'groups:noaccess' => 'Немате приступ групи',
	'groups:cantcreate' => 'Не можете да направите групу. Само админ може.',
	'groups:cantedit' => 'Можете да измените ову групу',
	'groups:saved' => 'Група сачувана',
	'groups:save_error' => 'Група се не може сачувати',
	'groups:featured' => 'Издвојене групе',
	'groups:makeunfeatured' => 'Уклони из истакнутих',
	'groups:makefeatured' => 'Истакни',
	'groups:featuredon' => '%s је сад истакнута група.',
	'groups:unfeatured' => '%s је уклоњена из истакнутих група.',
	'groups:featured_error' => 'Погрешна група.',
	'groups:nofeatured' => 'Нема истакнутих група.',
	'groups:joinrequest' => 'Захтевај чланство',
	'groups:join' => 'Приступи групи',
	'groups:leave' => 'Напусти групу',
	'groups:invite' => 'Позови пријатеље',
	'groups:invite:title' => 'Позови пријатеље у ову групу',
	'groups:invite:friends:help' => 'Search for a friend by name or username and select the friend from the list',
	'groups:invite:resend' => 'Resend the invitations to already invited users',
	'groups:invite:member' => 'Already a member of this group',
	'groups:invite:invited' => 'Already invited to this group',

	'groups:nofriendsatall' => 'Немате пријатеље да позовете!',
	'groups:group' => "Група",
	'groups:search:tags' => "Ознака",
	'groups:search:title' => "Потражи групе означене са  '%s'",
	'groups:search:none' => "Нису пронађене одговарајуће групе",
	'groups:search_in_group' => "Претражи у овој групи",
	'groups:acl' => "Група:  %s",
	'groups:acl:in_context' => 'Чланови групе',

	'groups:notfound' => "Група није пронађена",
	
	'groups:requests:none' => 'Тренутно нема захтева за чланство.',

	'groups:invitations:none' => 'Тренутно нема позивница.',

	'groups:open' => "отворена група",
	'groups:closed' => "затворена група",
	'groups:member' => "чланови",
	'groups:search' => "Search for groups",

	'groups:more' => 'Више група',
	'groups:none' => 'Нема група',

	/**
	 * Access
	 */
	'groups:access:private' => 'Затворена - Корисници морају бити позвани',
	'groups:access:public' => 'Отворена - Свако може да се придружи',
	'groups:access:group' => 'Само чланови групе',
	'groups:closedgroup' => "Чланство ове групе је затворено.",
	'groups:closedgroup:request' => 'Да затражите додавање, кликните "Захтевајте чланство" линк менија.',
	'groups:closedgroup:membersonly' => "Чланство ове групе је затворено и садржај је доступан само члановима.",
	'groups:opengroup:membersonly' => "Садржај ове групе је доступан само члановима.",
	'groups:opengroup:membersonly:join' => 'Да постанете члан кликните "Придружи се" мени линк.',
	'groups:visibility' => 'Ко може да види ову групу?',
	'groups:content_default_access' => 'Default group content access',
	'groups:content_default_access:help' => 'Here you can configure the default access for new content in this group. The group content mode can prevent the selected option from being in effect.',
	'groups:content_default_access:not_configured' => 'No default access configured, leave to the user',

	/**
	 * Group tools
	 */

	'admin:groups' => 'Групе',

	'groups:notitle' => 'Група мора да има име.',
	'groups:cantjoin' => 'Није могуће придружити се групи',
	'groups:cantleave' => 'Није успело напуштање групе',
	'groups:removeuser' => 'Уклони из групе',
	'groups:cantremove' => 'Не може се уклонити корисник из групе',
	'groups:removed' => 'Успешно уклоњен %s из групе',
	'groups:addedtogroup' => 'Успешно додат корисник у групу',
	'groups:joinrequestnotmade' => 'Није успело подношење захтева за пријем у групу',
	'groups:joinrequestmade' => 'Захтевано придруживање групи',
	'groups:joinrequest:exists' => 'You already requested membership for this group',
	'groups:button:joined' => 'Joined',
	'groups:button:owned' => 'Owned',
	'groups:joined' => 'Успешно сте се учланили у групу!',
	'groups:left' => 'Успешно сте напустили групу',
	'groups:userinvited' => 'Корисник је позван.',
	'groups:usernotinvited' => 'Није било могће позвати корисника.',
	'groups:useralreadyinvited' => 'Коринсик је већ позван',
	'groups:invite:subject' => "%s позвани сте да се придружите %s!",
	'groups:joinrequest:remove:check' => 'Сигурни сте да желите да уклоните овај захтев за чланство?',
	'groups:invite:remove:check' => 'Сигурни сте да желите да уклоните ову позивницу?',
	'groups:invite:body' => "Hi %s,

%s invited you to join the '%s' group.

Click below to view your invitations:
%s",

	'groups:welcome:subject' => "Добродошли у %s групу!",
	'groups:welcome:body' => "Hi %s!

You are now a member of the '%s' group.

Click below to begin posting!
%s",

	'groups:request:subject' => "%s је захтевао да се придружи групи %s",
	'groups:request:body' => "Hi %s,

%s has requested to join the '%s' group.

Click below to view their profile:
%s

or click below to view the group's join requests:
%s",

	'river:group:create' => '%s created the group %s',
	'river:group:join' => '%s joined the group %s',

	'groups:allowhiddengroups' => 'Да ли желите да дозволите приватне (невидљиве) групе?',
	'groups:whocancreate' => 'Ко може да отвори нову групу?',

	/**
	 * Action messages
	 */
	'groups:deleted' => 'Group and group contents deleted',
	'groups:notdeleted' => 'Group could not be deleted',
	'groups:deletewarning' => "Сигурни сте да желите да обришете ову групу? Не постоји поништење брисања!",

	'groups:invitekilled' => 'Позив је обрисан.',
	'groups:joinrequestkilled' => 'Захтева за члансто је обрисан.',
	'groups:error:addedtogroup' => "Није успело додавање %s у групу",
	'groups:add:alreadymember' => "%s је већ члан ове групе",

	/**
	 * ecml
	 */
	'groups:ecml:groupprofile' => 'Профили групе',

	/**
	 * Upgrades
	 */
	'groups:upgrade:2016101900:title' => 'Transfer group icons to a new location',
	'groups:upgrade:2016101900:description' => 'New entity icon API stores icons in a predictable location on the filestore
relative to the entity\'s filestore directory. This upgrade will align the group plugin with the requirements of the new API.',
);
