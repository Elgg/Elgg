<?php
	/**
	 * Elgg groups plugin language pack
	 * 
	 * @package ElggGroups
	 * @license http://www.gnu.org/licenses/old-licenses/gpl-2.0.html GNU Public License version 2
	 * @author Curverider Ltd
	 * @copyright Curverider Ltd 2008
	 * @link http://elgg.com/
	 */

	$russian = array(
	
		/**
		 * Menu items and titles
		 */
			
			'groups' => 'Группы',//"Groups",
			'groups:owned' => 'Твои собственные группы',//"Groups you own",
			'groups:yours' => 'Твои группы',//"Your groups",
			'groups:user' => 'Группы %s',//"%s's groups",
			'groups:all' => 'Группы всего сайта',//"All site groups",
			'groups:new' => 'Создать новую группу',//"Create a new group",
			'groups:edit' => 'Редактировать группу',//"Edit group",

			'groups:icon' => 'Иконка группы (оставьте пустым чтобы не менять)',//'Group icon (leave blank to leave unchanged)',
			'groups:name' => 'Имя группы',//'Group name',
			'groups:username' => 'Краткое имя группы (отображается в URL, цифро-буквы только)',//'Group short name (displayed in URLs, alphanumeric characters only)',
			'groups:description' => 'Описание',//'Description',
			'groups:briefdescription' => 'Краткое описание',//'Brief description',
			'groups:interests' => 'Интересы',//'Interests',
			'groups:website' => 'Веб сайт',//'Website',
			'groups:members' => 'Члены группы',//'Group members',
			'groups:membership' => 'Членство',//"Membership",
			'groups:access' => 'Права доступа',//"Access permissions",
			'groups:owner' => 'Владелец',//"Owner",
			'groups:widget:num_display' => 'Число групп для отображения',//'Number of groups to display',
			'groups:widget:membership' => 'Групповое членство',//'Group membership',
			'groups:widgets:description' => 'Отображать ли твои группы в твоем профиле',//'Display the groups you are a member of on your profile',// TODO
			'groups:noaccess' => 'Нет доступа в группу',//'No access to group',
			'groups:cantedit' => 'Вы не можете редактировать эту группу',//'You can not edit this group',
			'groups:saved' => 'Группа сохранена',//'Group saved',
	
			'groups:joinrequest' => 'Запросить членство',//'Request membership',
			'groups:join' => 'Присоединиться к группе',//'Join group',
			'groups:leave' => 'Покинуть группу',//'Leave group',
			'groups:invite' => 'Пригласить друзей',//'Invite friends',
			'groups:inviteto' => "Пригласить друзей в '%s'",//"Invite friends to '%s'",
			'groups:nofriends' => 'У Вас не осталось друзей кто еще не приглашен в эту группу.',//"You have no friends left who have not been invited to this group.",

			'groups:group' => 'Группа',//"Group",
			
			'item:object:groupforumtopic' => 'Темы форума',//"Forum topics",
	
			/*
			  Group forum strings
			*/
			
			'groups:forum' => 'Форум группы',
			'groups:addtopic' => 'Добавить тему',
			'groups:forumlatest' => 'Последние форумы',
			'groups:latestdiscussion' => 'Последние обсуждения',
			'groupspost:success' => 'Ваш комментарий успешно отослан',
			'groups:alldiscussion' => 'Последние обсуждения',
			'groups:edittopic' => 'Редактировать тему',

			'groups:topicmessage' => 'Сообщение',
			'groups:topicstatus' => 'Статус темы',
			'groups:reply' => 'Отослать комментарий',
			'groups:topic' => 'Тема',
			'groups:posts' => 'Сообщения',
			'groups:lastperson' => 'Последний подключившийся',
			'groups:when' => 'Когда',
			'grouptopic:notcreated' => 'Нет тем.',
			'groups:topicopen' => 'Открытые',
			'groups:topicclosed' => 'Закрытые',
			'groups:topicresolved' => 'Решенные',
			'grouptopic:created' => 'Тема создана.',
			'groupstopic:deleted' => 'Тема удалена.',
			'groups:topicsticky' => 'Прилепленная',
			'groups:topicisclosed' => 'Эта тема закрыта.',
			'groups:topiccloseddesc' => 'Эта тема закрыта и в неё больше нельзя писать.',
	
			'groups:privategroup' => 'Эта группа приватная, запрашиваем членство.',//'This group is private, requesting membership.',
			'groups:notitle' => 'Группы должны иметь заголовок.',//'Groups must have a title',
			'groups:cantjoin' => 'Не могу присоединиться к группе',//'Can not join group',
			'groups:cantleave' => 'Не могу покинуть группу',//'Could not leave group',
			'groups:addedtogroup' => 'Успешно добавлен пользователь в группу',//'Successfully added the user to the group',
			'groups:joinrequestnotmade' => 'Запрос на присоединение не может быть сделан',//'Join request could not be made',
			'groups:joinrequestmade' => 'Запрос на присоединение к группе успешно сделан',//'Request to join group successfully made',
			'groups:joined' => 'Успешно присоединились к группе!',//'Successfully joined group!',
			'groups:left' => 'Успешно покинули группу',//'Successfully left group',
			'groups:notowner' => 'Простите, но Вы не владелец этой группы.',//'Sorry, you are not the owner of this group.',
			'groups:alreadymember' => 'Вы уже член этой группы!',//'You are already a member of this group!',
			'groups:userinvited' => 'Пользователь приглашен.',//'User has been invited.',
			'groups:usernotinvited' => 'Пользователь не может быть приглашен.',//'User could not be invited.',
	
			'groups:invite:subject' => '%s был приглашен присоединиться к %s!',//"%s you have been invited to join %s!",// TODO
			/*'groups:invite:body' => "Hi %s,
You have been invited to join the '%s' group, click below to confirm:
%s",/**/
			'groups:invite:body' => "Здравствуйте %s,

Вы были приглашены присоединиться к группе '%s', для подтверждения кликните по ссылке:

%s",

			'groups:welcome:subject' => 'Добро пожаловать в группу %s!',//"Welcome to the %s group!",
			/*'groups:welcome:body' => "Hi %s!
You are now a member of the '%s' group! Click below to begin posting!
%s",/**/
			'groups:welcome:body' => "Привет %s!
		
Теперь Вы член группы '%s'! Кликните по ссылке чтобы начать писать!

%s",



	
			'groups:request:subject' => '%s попросил(а) присоединиться к %s',//"%s has requested to join %s",
			/*'groups:request:body' => "Hi %s,
%s has requested to join the '%s' group, click below to view their profile:
%s
or click below to confirm request:
%s",/**/
			'groups:request:body' => "Привет %s,

%s попросил(а) присоединиться к группе '%s', кликните ниже для просмотра их профилей:

%s

или кликните по ссылке для подтверждения запроса:

%s",
	
			'groups:river:member' => 'теперь член группы',//'is now a member of',
	
			'groups:nowidgets' => 'Виджеты для данной группы не определены.',
			//TODO: wtf is widget ?
	
	
			'groups:widgets:members:title' => 'Члены группы',//'Group members',
			'groups:widgets:members:description' => 'Список членов группы.',//'List the members of a group.',
			'groups:widgets:members:label:displaynum' => 'Список членов группы.',//'List the members of a group.',
			'groups:widgets:members:label:pleaseedit' => 'Пожалуйста, настройте этот виджет.',
	
			'groups:widgets:entities:title' => 'Объекты в группе',//"Objects in group",
			'groups:widgets:entities:description' => 'Список объектов, сохраненных в этой группе',//"List the objects saved in this group",
			'groups:widgets:entities:label:displaynum' => 'Список объектов группы.',//'List the objects of a group.',
			'groups:widgets:entities:label:pleaseedit' => 'Пожалуйста, настройте этот виджет.',
		
			'groups:forumtopic:edited' => 'Тема форума была успешно отредактирована.',//'Forum topic successfully edited.',
	);
					
	add_translation("ru", $russian);
?>
