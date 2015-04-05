<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sitios',

/**
 * Sessions
 */

	'login' => "Acceder",
	'loginok' => "Accedeu correctamente.",
	'loginerror' => "Non foi posíbel autenticalo. Probe de novo e asegúrese de indicar os seus datos de acceso correctamente.",
	'login:empty' => "Debe introducir o seu nome de usuario ou conta de correo electrónico e mailo seu contrasinal.",
	'login:baduser' => "Non foi posíbel cargar a súa conta de usuario.",
	'auth:nopams' => "Produciuse un erro interno. Non hai ningún método de autenticación de usuarios instalado.",

	'logout' => "Saír",
	'logoutok' => "Pechouse a súa sesión.",
	'logouterror' => "Non foi posíbel pechar a súa sesión. Inténteo de novo.",
	'session_expired' => "A sesión caducou. Actualize a páxina para acceder.",

	'loggedinrequired' => "Identifíquese para poder ver a páxina.",
	'adminrequired' => "Debe ser administrador para poder ver a páxina.",
	'membershiprequired' => "Debe pertencer ao grupo para ver a páxina.",
	'limited_access' => "Non ten permiso para ver a páxina.",


/**
 * Errors
 */

	'exception:title' => "Erro moi grave.",
	'exception:contact_admin' => 'Produciuse un erro do que non é posíbel recuperarse, e quedou rexistrado. Válgase da seguinte información para contactar co administrador do sitio:',

	'actionundefined' => "A acción solicitada (%s) non está definida no sistema.",
	'actionnotfound' => "Non se atopou o ficheiro para a acción «%s».",
	'actionloggedout' => "Non pode realizar a acción sen antes identificarse.",
	'actionunauthorized' => 'Non ten permisos para realizar a acción.',
	
	'ajax:error' => 'Produciuse un erro inesperado durante a execución dunha chamada mediante AJAX. Pode que se perdese a conexión co servidor.',

	'PluginException:MisconfiguredPlugin' => "O complemento %s (guid: %s) está mal configurado e desactivouse. Consulte o wiki de Elgg (http://learn.elgg.org/) para intentar determinar a causa do problema.",
	'PluginException:CannotStart' => 'O complemento «%s» (GUID: %s) non pode iniciarse e foi desactivado. Motivo: %s',
	'PluginException:InvalidID' => "«%s» non é un identificador de complemento válido.",
	'PluginException:InvalidPath' => "«%s» non é unha ruta de complemento válida.",
	'PluginException:InvalidManifest' => 'O ficheiro de manifesto do complemento «%s» non é válido.',
	'PluginException:InvalidPlugin' => 'O complemento «%s» non é válido.',
	'PluginException:InvalidPlugin:Details' => 'O complemento «%s» non é válido: %s',
	'PluginException:NullInstantiated' => '«ElggPlugin» non pode iniciarse con valor nulo. Debe asignarlle como valor un identificador único (GUID), un identificador de complemento ou unha ruta completa.',
	'ElggPlugin:MissingID' => 'O complemento carece de identificador (GUID: %s).',
	'ElggPlugin:NoPluginPackagePackage' => 'Ao complemento con identificador «%s» fáltalle «ElggPluginPackage» (GUID: %s).',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Falta o ficheiro «%s».',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Debe cambiar o nome do cartafol do complemento a «%s» para que coincida co identificador do seu manifesto.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'O manifesto contén un tipo de dependencia, «%s», que non é válido.',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'O manifesto contén un tipo de subministración (provides), «%s», que non é válido.',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Hai unha dependencia de tipo «%s», «%s», no complemento «%s», , que non é válida. Os complementos non poden nin estar en conflicto nin requerir unha dependencia que eles mesmos subministran.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Non é posíbel incluír «%s» para o complemento «%s» (GUID: %s) en «%s».',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Non é posíbel abrir o cartafol de vistas para o complemento «%s» (GUID: %s) en «%s».',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Non é posíbel rexistrar os idiomas do complemento «%s» (GUID: %s) en «%s».',
	'ElggPlugin:Exception:NoID' => 'O complemento con GUID «%s» carece de identificador.',
	'PluginException:NoPluginName' => "Non se atopou o nome do complemento.",
	'PluginException:ParserError' => 'Produciuse un erro ao analizar o manifesto con versión %s da API no complemento «%s».',
	'PluginException:NoAvailableParser' => 'Non é posíbel atopar un analizador para a versión %s da API de manifesto no complemento «%s».',
	'PluginException:ParserErrorMissingRequiredAttribute' => "O atributo «%s» non está presente no manifesto do complemento «%s», e trátase dun atributo obrigatorio.",
	'ElggPlugin:InvalidAndDeactivated' => 'O complemento «%s» non é válido e foi desactivado.',

	'ElggPlugin:Dependencies:Requires' => 'Require',
	'ElggPlugin:Dependencies:Suggests' => 'Suxire',
	'ElggPlugin:Dependencies:Conflicts' => 'Incompatíbel con',
	'ElggPlugin:Dependencies:Conflicted' => 'En conflito con',
	'ElggPlugin:Dependencies:Provides' => 'Subministra',
	'ElggPlugin:Dependencies:Priority' => 'Prioridade',

	'ElggPlugin:Dependencies:Elgg' => 'Versión de Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Versión de PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Extensión de PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Opción de PHP: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Complemento: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Despois de «%s»',
	'ElggPlugin:Dependencies:Priority:Before' => 'Antes de «%s»',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '«%s» non está instalada',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Falta',
	
	'ElggPlugin:Dependencies:ActiveDependent' => 'Hai outros complementos que teñen «%s» entre as súas dependencias. Antes de desactivar este complemento debe desactivar estes outros: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Atopáronse entradas de menú sen menús pai que liguen con eles.',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'O pai da entrada de menú «%s» non existe. O pai é «%s».',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Atopouse un rexistro duplicado da entrada de menú «%s».',

	'RegistrationException:EmptyPassword' => 'Os campos de contrasinal non poden estar baleiros.',
	'RegistrationException:PasswordMismatch' => 'Os contrasinais deben coincidir.',
	'LoginException:BannedUser' => 'Foi expulsado do sitio e non pode acceder.',
	'LoginException:UsernameFailure' => 'Non foi posíbel autenticalo. Asegúrese de que introduciu o nome de usuario ou conta de correo electrónico correctos, así como o contrasinal.',
	'LoginException:PasswordFailure' => 'Non foi posíbel autenticalo. Asegúrese de que introduciu o nome de usuario ou conta de correo electrónico correctos, así como o contrasinal.',
	'LoginException:AccountLocked' => 'Bloqueouse a súa conta debido aos repetidos intentos fallidos de acceso.',
	'LoginException:ChangePasswordFailure' => 'O contrasinal actual introducido non coincide co contrasinal actual real.',
	'LoginException:Unknown' => 'Non foi posíbel autenticalo debido a un erro descoñecido.',

	'deprecatedfunction' => 'Aviso: Este código fai uso da función «%s», que está obsoleta, e non é compatíbel con esta versión de Elgg.',

	'pageownerunavailable' => 'Aviso: Non é posíbel acceder ao propietario da páxina, «%d».',
	'viewfailure' => 'Produciuse un erro interno na vista «%s».',
	'view:missing_param' => "Falta o parámetro obrigatorio «%s» na vista %s.",
	'changebookmark' => 'Cambie o seu marcador para esta páxina.',
	'noaccess' => 'O contido ao que intentaba acceder eliminouse, ou non ten permisos para velo.',
	'error:missing_data' => 'Faltaban datos na súa solicitude.',
	'save:fail' => 'Produciuse un erro ao intentar gardar os seus datos.',
	'save:success' => 'Gardáronse os seus datos.',

	'error:default:title' => 'Ups…',
	'error:default:content' => 'Ups… algo non foi ben.',
	'error:404:title' => 'Non se atopou a páxina',
	'error:404:content' => 'Non foi posíbel atopar a páxina que solicitou.',

	'upload:error:ini_size' => 'O ficheiro que intentou enviar é grande de máis.',
	'upload:error:form_size' => 'O ficheiro que intentou enviar é grande de máis.',
	'upload:error:partial' => 'Non se completou o envío do ficheiro.',
	'upload:error:no_file' => 'Non seleccionou ningún ficheiro.',
	'upload:error:no_tmp_dir' => 'Non é posíbel gardar o ficheiro enviado.',
	'upload:error:cant_write' => 'Non é posíbel gardar o ficheiro enviado.',
	'upload:error:extension' => 'Non é posíbel gardar o ficheiro enviado.',
	'upload:error:unknown' => 'Non foi posíbel enviar o ficheiro.',


/**
 * User details
 */

	'name' => "Nome para mostrar",
	'email' => "Conta de correo",
	'username' => "Nome de usuario",
	'loginusername' => "Nome de usuario ou conta de correo",
	'password' => "Contrasinal",
	'passwordagain' => "Contrasinal (repítaa para asegurarse)",
	'admin_option' => "Converter o usuario en administrador?",

/**
 * Access
 */

	'PRIVATE' => "Privado",
	'LOGGED_IN' => "Usuarios rexistrados",
	'PUBLIC' => "Público",
	'LOGGED_OUT' => "Usuarios anónimos",
	'access:friends:label' => "Contactos",
	'access' => "Acceso",
	'access:overridenotice' => "Nota: Debido ás políticas de grupos, só os membros do grupo poden acceder a este contido.",
	'access:limited:label' => "Limitado",
	'access:help' => "O nivel de acceso",
	'access:read' => "Lectura",
	'access:write' => "Escritura",
	'access:admin_only' => "Só os administradores",
	'access:missing_name' => "O nome do nivel de acceso non existe.",
	'access:comments:change' => "Actualmente esta discusión só a poden ver certas persoas. Pense ben con quen decide compartila.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Taboleiro",
	'dashboard:nowidgets' => "O seu taboleiro permítelle facer un seguimento da actividade e contidos que lle interesen do sitio.",

	'widgets:add' => 'Engadir trebellos',
	'widgets:add:description' => "Prema o botón de calquera dos seguintes trebellos para engadilo á páxina.",
	'widgets:panel:close' => "Pechar o panel de trebellos",
	'widgets:position:fixed' => '(posición fixa na páxina)',
	'widget:unavailable' => 'Xa engadiu ese trebello.',
	'widget:numbertodisplay' => 'Número de elementos para mostrar',

	'widget:delete' => 'Retirar «%s»',
	'widget:edit' => 'Personalizar o trebello',

	'widgets' => "Trebellos",
	'widget' => "Trebello",
	'item:object:widget' => "Trebellos",
	'widgets:save:success' => "O trebello gardouse correctamente.",
	'widgets:save:failure' => "Non foi posíbel gardar o trebello.",
	'widgets:add:success' => "O trebello engadiuse correctamente.",
	'widgets:add:failure' => "Non foi posíbel engadir o trebello.",
	'widgets:move:failure' => "Non foi posíbel almacenar a nova posición do trebello.",
	'widgets:remove:failure' => "Non foi posíbel retirar o trebello.",

/**
 * Groups
 */

	'group' => "Grupo",
	'item:group' => "Grupos",

/**
 * Users
 */

	'user' => "Usuari",
	'item:user' => "Usuarios",

/**
 * Friends
 */

	'friends' => "Contactos",
	'friends:yours' => "Contactos seus",
	'friends:owned' => "Contactos de %s",
	'friend:add' => "Engadir un contacto",
	'friend:remove' => "Retirar dos contactos",

	'friends:add:successful' => "Engadiuse a %s á súa lista de contactos.",
	'friends:add:failure' => "Non foi posíbel engadir a %s á súa lista de contactos.",

	'friends:remove:successful' => "Retirouse a %s da súa lista de contactos.",
	'friends:remove:failure' => "Non foi posíbel retirar a %s da súa lista de contactos.",

	'friends:none' => "Non ten ningún contacto de momento.",
	'friends:none:you' => "Aínda non ten ningún contacto.",

	'friends:none:found' => "Non se atopou ningún contacto.",

	'friends:of:none' => "Ninguén ten a este usuario de contacto de momento.",
	'friends:of:none:you' => "Ninguén o engadiu a vostede de contacto de momento. Comece por engadir contido e completar o seu perfil para axudar a que a xente o atope.",

	'friends:of:owned' => "Xente que ten a %s de contacto",

	'friends:of' => "Contactos de",
	'friends:collections' => "Coleccións de contactos",
	'collections:add' => "Nova colección",
	'friends:collections:add' => "Nova colección de contactos",
	'friends:addfriends' => "Escolla contactos",
	'friends:collectionname' => "Nome da colección",
	'friends:collectionfriends' => "Contactos na colección",
	'friends:collectionedit' => "Editar a colección",
	'friends:nocollections' => "Aínda non ten ningunha colección.",
	'friends:collectiondeleted' => "Eliminouse a súa colección.",
	'friends:collectiondeletefailed' => "Non foi posíbel eliminar a colección. Ou non ten permisos para eliminala, ou produciuse algún outro problema.",
	'friends:collectionadded' => "Creouse a colección.",
	'friends:nocollectionname' => "Ten que porlle nome á colección para poder creala.",
	'friends:collections:members' => "Membros da colección",
	'friends:collections:edit' => "Editar a colección",
	'friends:collections:edited' => "Gardar a colección",
	'friends:collection:edit_failed' => 'Non foi posíbel gardar a colección.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNÑOPQRSTUVWXYZ',

	'avatar' => 'Imaxe de perfi',
	'avatar:noaccess' => "Non ten permisos para editar a imaxe de perfil deste usuario.",
	'avatar:create' => 'Crear a imaxe de perfil',
	'avatar:edit' => 'Editar a imaxe de perfil',
	'avatar:preview' => 'Vista previa',
	'avatar:upload' => 'Enviar unha nova imaxe de perfil',
	'avatar:current' => 'Imaxe de perfil actual',
	'avatar:remove' => 'Eliminar a súa imaxe de perfil e usar a icona predeterminada',
	'avatar:crop:title' => 'Ferramenta para recortar a imaxe de perfil',
	'avatar:upload:instructions' => "A súa imaxe de perfil móstrase en varias partes do sitio. Pode cambiala sempre que queira. Acéptanse os seguintes formatos de imaxe: GIF, JPG e PNG.",
	'avatar:create:instructions' => 'Prema e arrastre na imaxe para formar un cadrado. O contido do cadrado será a súa imaxe de perfil. A caixa da dereita mostrará unha vista previa. Cando estea contento co resultado, prema «Crear a imaxe de perfil».',
	'avatar:upload:success' => 'Enviouse da imaxe de perfil.',
	'avatar:upload:fail' => 'Non foi posíbel enviar a imaxe de perfil.',
	'avatar:resize:fail' => 'Non foi posíbel axustar o tamaño da imaxe de perfil.',
	'avatar:crop:success' => 'Recortouse a imaxe de perfil.',
	'avatar:crop:fail' => 'Non foi posíbel recortar a imaxe de perfil.',
	'avatar:remove:success' => 'Eliminouse a imaxe de perfil.',
	'avatar:remove:fail' => 'Non foi posíbel eliminar a imaxe de perfil.',

	'profile:edit' => 'Editar o perfil',
	'profile:aboutme' => "Sobre min",
	'profile:description' => "Sobre min",
	'profile:briefdescription' => "Descrición curta",
	'profile:location' => "Lugar",
	'profile:skills' => "Habilidades",
	'profile:interests' => "Intereses",
	'profile:contactemail' => "Correo de contacto",
	'profile:phone' => "Teléfono",
	'profile:mobile' => "Móbil",
	'profile:website' => "Sitio web",
	'profile:twitter' => "Usuario de Twitter",
	'profile:saved' => "Gardouse o seu perfil.",

	'profile:field:text' => 'Texto curto',
	'profile:field:longtext' => 'Zona de texto longo',
	'profile:field:tags' => 'Etiquetas',
	'profile:field:url' => 'Enderezo we',
	'profile:field:email' => 'Enderezo de correo',
	'profile:field:location' => 'Lugar',
	'profile:field:date' => 'Data',

	'admin:appearance:profile_fields' => 'Editar os campos do perfi',
	'profile:edit:default' => 'Editar os campos do perfil.',
	'profile:label' => "Nome do perfil",
	'profile:type' => "Tipo de perfil",
	'profile:editdefault:delete:fail' => 'Non foi posíbel eliminar o campo do perfil.',
	'profile:editdefault:delete:success' => 'Eliminouse o campo de perfil',
	'profile:defaultprofile:reset' => 'Restablecéronse os campos de perfil predeterminados do sistema.',
	'profile:resetdefault' => 'Restablecer os campos de perfil predeterminados do sistema',
	'profile:resetdefault:confirm' => 'Está seguro de que quere eliminar os campos de perfil personalizados?',
	'profile:explainchangefields' => "Use o seguinte formulario para substituír os campos de perfil actuais por campos personalizados. \n\n Déalle un nome para mostrar ao novo campo de perfil, como por exemplo «Equipo favorito», e seleccione o tipo de campo (e. g. texto, URL, etiquetas), e prema o botón de «Engadir». Para cambiar a orde dos campos, arrastre a icona de movemento que hai canda o nome para mostrar do campo. Para cambiar o nome para mostrar do campo, prema o texto do nome para mostrar, de xeito que se poña en modo de edición. \n\n Pode restablecer a configuración predeterminada do perfil en calquera momento, pero perderá toda a información que os usuarios introducisen nos campos personalizados dos seus perfís.",
	'profile:editdefault:success' => 'Engadiuse o novo campo de perfil.',
	'profile:editdefault:fail' => 'Non foi posíbel gardar o perfil predeterminado.',
	'profile:field_too_long' => 'Non é posíbel gardar a información do seu perfil porque a sección «%s» é longa de máis.',
	'profile:noaccess' => "Non ten permisos para editar este perfil.",
	'profile:invalid_email' => '«%s» debe ser un enderezo de correo electrónico válido.',


/**
 * Feeds
 */
	'feed:rss' => 'Fonte de novas RSS da páxina.',
/**
 * Links
 */
	'link:view' => 'Ver a ligazón',
	'link:view:all' => 'Velo todo',


/**
 * River
 */
	'river' => "Onda",
	'river:friend:user:default' => "%s e %s son agora contactos.",
	'river:update:user:avatar' => '%s ten unha nova imaxe de perfil.',
	'river:update:user:profile' => '%s actualizou o seu perfil.',
	'river:noaccess' => 'Non ten permisos para ver isto.',
	'river:posted:generic' => '%s publicou',
	'riveritem:single:user' => 'un usuario',
	'riveritem:plural:user' => 'algúns usuarios',
	'river:ingroup' => 'no grupo %s',
	'river:none' => 'Non hai actividade.',
	'river:update' => 'Actualización de %s',
	'river:delete' => 'Retirar isto',
	'river:delete:success' => 'O elemento retirouse da onda.',
	'river:delete:fail' => 'Non foi posíbel retirar o elemento da onda.',
	'river:subject:invalid_subject' => 'Usuario non válido',
	'activity:owner' => 'Ver a actividade',

	'river:widget:title' => "Actividade",
	'river:widget:description' => "Mostrar a actividade recente",
	'river:widget:type' => "Tipo de actividade",
	'river:widgets:friends' => 'Actividade dos contactos',
	'river:widgets:all' => 'Actividade global',

/**
 * Notifications
 */
	'notifications:usersettings' => "Configuración das notificacións",
	'notification:method:email' => 'Correo',

	'notifications:usersettings:save:ok' => "Gardouse a configuración das notificacións.",
	'notifications:usersettings:save:fail' => "Non foi posíbel gardar a configuración das notificacións.",

	'notification:subject' => 'Notificación sobre %s',
	'notification:body' => 'Ver a nova actividade en %s',

/**
 * Search
 */

	'search' => "Buscar",
	'searchtitle' => "Buscar: %s",
	'users:searchtitle' => "Buscando usuarios: %s",
	'groups:searchtitle' => "Buscando grupos: %s",
	'advancedsearchtitle' => "%s con resultados para %s",
	'notfound' => "Non se atopou ningún resultado.",
	'next' => "Seguinte",
	'previous' => "Anterior",

	'viewtype:change' => "Cambiar o tipo de lista",
	'viewtype:list' => "Vista de lista",
	'viewtype:gallery' => "Galería",

	'tag:search:startblurb' => "Elementos con etiquetas que coinciden con «%s»:",

	'user:search:startblurb' => "Usuarios que coinciden con «%s»:",
	'user:search:finishblurb' => "Prema aquí para ver máis.",

	'group:search:startblurb' => "Grupos que coinciden con «%s»:",
	'group:search:finishblurb' => "Prema aquí para ver máis.",
	'search:go' => 'Ir',
	'userpicker:only_friends' => 'Só contactos',

/**
 * Account
 */

	'account' => "Conta",
	'settings' => "Configuración",
	'tools' => "Ferramentas",
	'settings:edit' => 'Cambiar a configuración',

	'register' => "Registrarse",
	'registerok' => "Rexistrouse para «%s».",
	'registerbad' => "Non foi posíbel rexistralo debido a un erro descoñecido.",
	'registerdisabled' => "O administrador do sistema desactivou o rexistro.",
	'register:fields' => 'Todos os campos son obrigatorios.',

	'registration:notemail' => 'A conta de correo electrónico que forneceu non parece correcta.',
	'registration:userexists' => 'Ese nome de usuario xa existe.',
	'registration:usernametooshort' => 'O seu nome de usuario debe ter polo menos %u caracteres.',
	'registration:usernametoolong' => 'O seu nome de usuario é longo de máis. Non pode sobrepasar os %u caracteres.',
	'registration:passwordtooshort' => 'O contrasinal debe ter un mínimo de %u caracteres.',
	'registration:dupeemail' => 'Ese enderezo de correo electrónico xa está rexistrado.',
	'registration:invalidchars' => 'O seu nome de usuario contén caracteres como «%s» que non están permitidos. Non pode usar ningún dos seguintes caracteres: %s',
	'registration:emailnotvalid' => 'O seu enderezo de correo electrónico non está permitido.',
	'registration:passwordnotvalid' => 'O seu contrasinal non está permitido.',
	'registration:usernamenotvalid' => 'O seu nome de usuario non é válido.',

	'adduser' => "Engadir o usuario",
	'adduser:ok' => "Engadiuse o usuario.",
	'adduser:bad' => "Non foi posíbel crear o usuario.",

	'user:set:name' => "Configuración do nome da conta",
	'user:name:label' => "Nome para mostrar",
	'user:name:success' => "Cambiouse o nome para mostrar.",
	'user:name:fail' => "Non foi posíbel cambiar o nome para mostrar.",

	'user:set:password' => "Contrasinal da conta",
	'user:current_password:label' => 'Contrasinal actual',
	'user:password:label' => "Contrasinal novo",
	'user:password2:label' => "Contrasinal novo (repítao)",
	'user:password:success' => "Cambiouse o contrasinal.",
	'user:password:fail' => "Non foi posíbel cambiar o contrasinal.",
	'user:password:fail:notsame' => "Os contrasinais non coinciden.",
	'user:password:fail:tooshort' => "O contrasinal é curto de máis.",
	'user:password:fail:incorrect_current_password' => 'O contrasinal actual introducido non é correcto.',
	'user:changepassword:unknown_user' => 'O usuario non é válido.',
	'user:changepassword:change_password_confirm' => 'Isto cambiará o seu contrasinal.',

	'user:set:language' => "Configuración do idioma",
	'user:language:label' => "Idioma",
	'user:language:success' => "Actualizouse a configuración do idioma.",
	'user:language:fail' => "Non foi posíbel gardar a configuración do idioma.",

	'user:username:notfound' => 'Non se atopou o nome de usuario «%s».',

	'user:password:lost' => 'Perdín o contrasinal',
	'user:password:changereq:success' => 'Solicitou un novo contrasinal, recibirá un correo en breves.',
	'user:password:changereq:fail' => 'Non foi posíbel solicitar un novo contrasinal.',

	'user:password:text' => 'Para solicitar un novo contrasinal, escriba o seu nome de usuario ou a súa conta de correo electrónico e prema o botón de «Solicitar».',

	'user:persistent' => 'Lembrar.',

	'walled_garden:welcome' => 'Reciba a nosa benvida a',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrar',
	'menu:page:header:configure' => 'Configurar',
	'menu:page:header:develop' => 'Desenvolver',
	'menu:page:header:default' => 'Outro',

	'admin:view_site' => 'Ver o sitio',
	'admin:loggedin' => 'Accedeu como %s',
	'admin:menu' => 'Menú',

	'admin:configuration:success' => "Gardouse a configuración.",
	'admin:configuration:fail' => "Non foi posíbel gardar a configuración.",
	'admin:configuration:dataroot:relative_path' => 'Non é posíbel facer de «%s» a raíz de datos porque non se trata dunha ruta absoluta.',
	'admin:configuration:default_limit' => 'O número de elementos por páxina debe ser como mínimo 1.',

	'admin:unknown_section' => 'Sección de administración incorrecta.',

	'admin' => "Administración",
	'admin:description' => "O panel de administración permítelle controlar todos os aspectos do sistema, desde a xestión de usuarios ata o comportamento dos complementos. Escolla unha das seguintes opcións para comezar.",

	'admin:statistics' => "Estatísticas",
	'admin:statistics:overview' => 'Resumo',
	'admin:statistics:server' => 'Información do servidor',
	'admin:statistics:cron' => 'Cro',
	'admin:cron:record' => 'Últimas tarefas de Cro',
	'admin:cron:period' => 'Período de Cron',
	'admin:cron:friendly' => 'Completado por última vez',
	'admin:cron:date' => 'Data e hora',

	'admin:appearance' => 'Aparencia',
	'admin:administer_utilities' => 'Utilidades',
	'admin:develop_utilities' => 'Itilidades',
	'admin:configure_utilities' => 'Utilidades',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Usuarios",
	'admin:users:online' => 'Conectados agora',
	'admin:users:newest' => 'Últimos',
	'admin:users:admins' => 'Administradores',
	'admin:users:add' => 'Engadir un usuario',
	'admin:users:description' => "Este panel de administrador permítelle controlar a configuración dos usuarios do sitio. Escolla unha das seguintes opcións para comezar.",
	'admin:users:adduser:label' => "Prema aquí para engadir un usuario…",
	'admin:users:opt:linktext' => "Configurar os usuarios…",
	'admin:users:opt:description' => "Configurar a información dos usuarios e contas.",
	'admin:users:find' => 'Atopar',

	'admin:administer_utilities:maintenance' => 'Modo de mantemento',
	'admin:upgrades' => 'Anovacións',

	'admin:settings' => 'Configuración',
	'admin:settings:basic' => 'Configuración básica',
	'admin:settings:advanced' => 'Configuración avanzada',
	'admin:site:description' => "Este panel de administración permítelle controlar a configuración global do sitio. Escolla unha das seguintes opcións para comezar.",
	'admin:site:opt:linktext' => "Configurar o sitio…",
	'admin:settings:in_settings_file' => 'A opción está configurada en «settings.php».',

	'admin:legend:security' => 'Seguranza',
	'admin:site:secret:intro' => 'Elgg usa unha chave para crear códigos de seguridade para varios fins.',
	'admin:site:secret_regenerated' => "Xerouse un novo segredo para o sitio.",
	'admin:site:secret:regenerate' => "Xerar un novo segredo para o sitio",
	'admin:site:secret:regenerate:help' => "Nota: Ao xerar un novo segredo para o sitio, invalidaranse os códigos das cookies para lembrar as sesións, dos correos de validación, dos códigos de invitación, etc. Isto pode afectar a algúns usuarios.",
	'site_secret:current_strength' => 'Seguranza da chave',
	'site_secret:strength:weak' => "Feble",
	'site_secret:strength_msg:weak' => "Recomendámoslle encarecidamente que volva xerar o segredo do sitio.",
	'site_secret:strength:moderate' => "Moderada",
	'site_secret:strength_msg:moderate' => "Recomendámoslle que volva xerar o segredo do sitio para maior seguranza.",
	'site_secret:strength:strong' => "Forte",
	'site_secret:strength_msg:strong' => "O segredo do sitio é forte dabondo. Non é necesario que o volva xerar.",

	'admin:dashboard' => 'Taboleiro',
	'admin:widget:online_users' => 'Usuarios conectados',
	'admin:widget:online_users:help' => 'Lista os usuarios que están actualmente no sitio.',
	'admin:widget:new_users' => 'Usuarios novos',
	'admin:widget:new_users:help' => 'Lista os últimos usuarios en rexistrarse.',
	'admin:widget:banned_users' => 'Usuarios expulsados',
	'admin:widget:banned_users:help' => 'Lista os usuarios expulsados.',
	'admin:widget:content_stats' => 'Estatísticas do contido',
	'admin:widget:content_stats:help' => 'Fai un seguimento do contido que crean os usuarios.',
	'admin:widget:cron_status' => 'Estado das tarefas programadas',
	'admin:widget:cron_status:help' => 'Mostra o estado das últimas tarefas programadas que se executaron.',
	'widget:content_stats:type' => 'Tipo de contido',
	'widget:content_stats:number' => 'Número',

	'admin:widget:admin_welcome' => 'Benvida',
	'admin:widget:admin_welcome:help' => "Unha breve introdución á zona de administración de Elgg",
	'admin:widget:admin_welcome:intro' =>
'Reciba a nosa benvida a Elgg. O que ten diante agora mesmo é o taboleiro de administración. Resulta útil para facer un seguimento do que está a acontecer no sitio.',

	'admin:widget:admin_welcome:admin_overview' =>
"O menú da dereita permite navegar pola zona de administración, e está organizado nas seguintes seccións:
	<dl>
		<dt>Administrar</dt><dd>Tarefas diarias como revisar contido denunciado, comprobar quen está conectado, e ver as estatísticas.</dd>
		<dt>Configurar</dt><dd>Tarefas ocasionais, como definir o nome do sitio ou activar un complemento.</dd>
		<dt>Desenvolver</dt><dd>Para desenvolvedores que están a traballar en complementos ou temas. Necesítase un complemento de desenvolvedor.</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Asegúrese de botarlle unha ollada aos recursos dispoñíbeis na parte inferior da páxina, e grazas por usar Elgg!',

	'admin:widget:control_panel' => 'Panel de contro',
	'admin:widget:control_panel:help' => "Facilita o acceso a controis habituais.",

	'admin:cache:flush' => 'Baleirar as cachés',
	'admin:cache:flushed' => "Baleiráronse as cachés do sitio.",

	'admin:footer:faq' => 'P+F sobre administración',
	'admin:footer:manual' => 'Manual de administración',
	'admin:footer:community_forums' => 'Foros da comunidade de Elgg',
	'admin:footer:blog' => 'Bitácora de Elgg',

	'admin:plugins:category:all' => 'Todos os complementos',
	'admin:plugins:category:active' => 'Complementos activos',
	'admin:plugins:category:inactive' => 'Complementos inactivos',
	'admin:plugins:category:admin' => 'Administración',
	'admin:plugins:category:bundled' => 'Empaquetado',
	'admin:plugins:category:nonbundled' => 'Non empaquetado',
	'admin:plugins:category:content' => 'Contido',
	'admin:plugins:category:development' => 'Desenvolvemento',
	'admin:plugins:category:enhancement' => 'Melloras',
	'admin:plugins:category:api' => 'Servizo ou API',
	'admin:plugins:category:communication' => 'Comunicación',
	'admin:plugins:category:security' => 'Seguranza e vandalismo',
	'admin:plugins:category:social' => 'Social',
	'admin:plugins:category:multimedia' => 'Son e vídeo',
	'admin:plugins:category:theme' => 'Temas',
	'admin:plugins:category:widget' => 'Trebellos',
	'admin:plugins:category:utility' => 'Utilidades',

	'admin:plugins:markdown:unknown_plugin' => 'Complemento descoñecido.',
	'admin:plugins:markdown:unknown_file' => 'Ficheiro descoñecido.',

	'admin:notices:could_not_delete' => 'Non foi posíbel eliminar a nota.',
	'item:object:admin_notice' => 'Nota dos administradores',

	'admin:options' => 'Opcións de administración',

/**
 * Plugins
 */

	'plugins:disabled' => 'Non se están a cargar os complementos porque no cartafol «mod» hai un ficheiro chamado «disable» (desactivar).',
	'plugins:settings:save:ok' => "Gardouse a configuración do complemento «%s».",
	'plugins:settings:save:fail' => "Non foi posíbel gardar a configuración do complemento «%s».",
	'plugins:usersettings:save:ok' => "Gardouse a configuración do usuario para o complemento «%s».",
	'plugins:usersettings:save:fail' => "Non foi posíbel gardar a configuración do usuario para o complemento «%s».",
	'item:object:plugin' => 'Complementos',

	'admin:plugins' => "Complementos",
	'admin:plugins:activate_all' => 'Activalos todos',
	'admin:plugins:deactivate_all' => 'Desactivalos todos',
	'admin:plugins:activate' => 'Activar',
	'admin:plugins:deactivate' => 'Desactivar',
	'admin:plugins:description' => "Este panel de administración permítelle controlar e configurar ferramentas instaladas no sitio.",
	'admin:plugins:opt:linktext' => "Configurar as ferramentas…",
	'admin:plugins:opt:description' => "Configurar as ferramentas instaladas no sitio.",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Dereitos de autor",
	'admin:plugins:label:categories' => 'Categorías',
	'admin:plugins:label:licence' => "Licenza",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:repository' => "Código",
	'admin:plugins:label:bugtracker' => "Informar dun problema",
	'admin:plugins:label:donate' => "Doar",
	'admin:plugins:label:moreinfo' => 'Máis información',
	'admin:plugins:label:version' => 'Versión',
	'admin:plugins:label:location' => 'Lugar',
	'admin:plugins:label:contributors' => 'Colaboradores',
	'admin:plugins:label:contributors:name' => 'Nome',
	'admin:plugins:label:contributors:email' => 'Corre',
	'admin:plugins:label:contributors:website' => 'Sitio web',
	'admin:plugins:label:contributors:username' => 'Usuario na comunidade',
	'admin:plugins:label:contributors:description' => 'Descrición',
	'admin:plugins:label:dependencies' => 'Dependencias',

	'admin:plugins:warning:elgg_version_unknown' => 'O ficheiro de manifesto deste complemento está obsoleto, e non lista ningunha versión de Elgg compatíbel coa do sitio. O máis seguro é que non funcione.',
	'admin:plugins:warning:unmet_dependencies' => 'O complemento ten dependencias que non están satisfeitas, e polo tanto non pode activarse. Prema «Máis información» para ver a lista de dependencias.',
	'admin:plugins:warning:invalid' => 'O complemento non é válido: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Na <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">documentación de Elss</a> atopará consellos para evitar e solucionar problemas.',
	'admin:plugins:cannot_activate' => 'Non pode activarse',

	'admin:plugins:set_priority:yes' => "Cambiouse a posición de «%s».",
	'admin:plugins:set_priority:no' => "Non foi posíbel cambiar a posición de «%s».",
	'admin:plugins:set_priority:no_with_msg' => "Non foi posíbel cambiar a posición de «%s». Produciuse un erro: %s",
	'admin:plugins:deactivate:yes' => "Desactivouse «%s».",
	'admin:plugins:deactivate:no' => "Non foi posíbel desactivar «%s».",
	'admin:plugins:deactivate:no_with_msg' => "Non foi posíbel desactivar «%s». Produciuse un erro: %s",
	'admin:plugins:activate:yes' => "Activouse «%s».",
	'admin:plugins:activate:no' => "Non foi posíbel activar «%s».",
	'admin:plugins:activate:no_with_msg' => "Non foi posíbel actiar «%s». Produciuse un erro: %s",
	'admin:plugins:categories:all' => 'Todas as categorías',
	'admin:plugins:plugin_website' => 'Sitio web do complemento',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Versión %s',
	'admin:plugin_settings' => 'Configuración do complement',
	'admin:plugins:warning:unmet_dependencies_active' => 'O complemento está activo pero algunhas das súas dependencias non están satisfeitas. Pode que lle dea problemas. Prema «Máis información» para obter máis detalles.',

	'admin:plugins:dependencies:type' => 'Tipo',
	'admin:plugins:dependencies:name' => 'Nome',
	'admin:plugins:dependencies:expected_value' => 'Valor esperad',
	'admin:plugins:dependencies:local_value' => 'Valor real',
	'admin:plugins:dependencies:comment' => 'Comentari',

	'admin:statistics:description' => "Isto é un resumo das estatísticas do sitio. Se quere estatísticas máis detalladas, existe unha funcionalidade de administración profesional.",
	'admin:statistics:opt:description' => "Ver información estatística sobre usuarios e obxectos do sitio.",
	'admin:statistics:opt:linktext' => "Ver as estatísticas…",
	'admin:statistics:label:basic' => "Estatísticas básicas do sitio",
	'admin:statistics:label:numentities' => "Entidades do sitio",
	'admin:statistics:label:numusers' => "Número de usuarios",
	'admin:statistics:label:numonline' => "Número de usuarios conectados",
	'admin:statistics:label:onlineusers' => "Usuarios conectados",
	'admin:statistics:label:admins'=>"Administradores",
	'admin:statistics:label:version' => "Versión de Elgg",
	'admin:statistics:label:version:release' => "Publicación",
	'admin:statistics:label:version:version' => "Versión",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Servidor web',
	'admin:server:label:server' => 'Servidor',
	'admin:server:label:log_location' => 'Ruta do rexistro',
	'admin:server:label:php_version' => 'Versión de PHP',
	'admin:server:label:php_ini' => 'Ruta do ficheiro de configuración de PHP',
	'admin:server:label:php_log' => 'Rexistro de PHP',
	'admin:server:label:mem_avail' => 'Memoria dispoñíbel',
	'admin:server:label:mem_used' => 'Memoria en uso',
	'admin:server:error_log' => "Rexistro de erros do servidor web",
	'admin:server:label:post_max_size' => 'Tamaño máximo das solicitudes POST',
	'admin:server:label:upload_max_filesize' => 'Tamaño máximo de envío',
	'admin:server:warning:post_max_too_small' => '(Nota: post_max_size debe ser maior que este valor para permitir envíos deste tamaño)',

	'admin:user:label:search' => "Atopar usuarios:",
	'admin:user:label:searchbutton' => "Buscar",

	'admin:user:ban:no' => "Non é posíbel expular ao usuario",
	'admin:user:ban:yes' => "O usuario foi expulsado.",
	'admin:user:self:ban:no' => "Non é posíbel expulsarse a un mesmo",
	'admin:user:unban:no' => "Non foi posíbel readmitir o usuario.",
	'admin:user:unban:yes' => "Readmitiuse o usuario.",
	'admin:user:delete:no' => "Non foi posíbel eliminar o usuario.",
	'admin:user:delete:yes' => "Eliminouse o usuario %s.",
	'admin:user:self:delete:no' => "Non é posíbel eliminarse a un memso.",

	'admin:user:resetpassword:yes' => "Restableceuse o contrasinal, informouse ao usuario.",
	'admin:user:resetpassword:no' => "Non foi posíbel restablecer o contrasinal.",

	'admin:user:makeadmin:yes' => "O usuario convertiuse en administrador.",
	'admin:user:makeadmin:no' => "Non foi posíbel converter o usuario en administrador.",

	'admin:user:removeadmin:yes' => "O usuario perdeu os privilexios de administrador",
	'admin:user:removeadmin:no' => "Non foi posíbel quitarlle ao usuario os privilexios de administrador.",
	'admin:user:self:removeadmin:no' => "Non é posíbel quitarse os privilexios de administrador a un mesmo.",

	'admin:appearance:menu_items' => 'Elementos do menú',
	'admin:menu_items:configure' => 'Configurar os elementos do menú principal.',
	'admin:menu_items:description' => 'Escolla os elementos de menú que quere mostrar como ligazóns destacadas. Os elementos que non use engadiranse a «Máis» ao final da lista.',
	'admin:menu_items:hide_toolbar_entries' => 'Quere eliminar as ligazóns do menú da barra de ferramentas?',
	'admin:menu_items:saved' => 'Gardáronse os elementos do menú.',
	'admin:add_menu_item' => 'Engadir un elemento de menú personalizado',
	'admin:add_menu_item:description' => 'Complete os campos de nome para mostrar e URL para engadir elementos personalizados al menú de navegación.',

	'admin:appearance:default_widgets' => 'Trebellos predeterminados',
	'admin:default_widgets:unknown_type' => 'Tipo de trebello descoñecido',
	'admin:default_widgets:instructions' => 'Engada, retire, coloque e configure os trebellos predeterminados para a páxina de trebellos seleccionada.
Os cambios só afectarán a novos usuarios.',

	'admin:robots.txt:instructions' => "Edite a continuación o ficheiro «robots.txt» do sitio.",
	'admin:robots.txt:plugins' => "Os complementos están a engadir o seguinte contido ao ficheiro «robots.txt».",
	'admin:robots.txt:subdir' => "A ferramenta de «robots.txt» non vai funcionar porque Elgg está instalado nun subdirectorio.",

	'admin:maintenance_mode:default_message' => 'O sitio está pechado por mantemento',
	'admin:maintenance_mode:instructions' => 'Use o modo de mantemento para anovar o sitio ou realizar outros cambios importantes.
		No modo de mantemento só os administradores poden acceder ao sitio.',
	'admin:maintenance_mode:mode_label' => 'Modo de mantemento',
	'admin:maintenance_mode:message_label' => 'Mensaxe que se mostra aos usuarios cando o sitio está no modo de mantemento.',
	'admin:maintenance_mode:saved' => 'Gardouse a configuración do modo de mantemento.',
	'admin:maintenance_mode:indicator_menu_item' => 'O sitio está en modo de mantemento.',
	'admin:login' => 'Acceso para administradores',

/**
 * User settings
 */
		
	'usersettings:description' => "O panel de configuración de usuarios permítelle controlar a súa configuración persoal, desde a xestión de usuarios ata o comportamento dos complementos. Escolla unha das seguintes opcións para comezar.",

	'usersettings:statistics' => "Estatísticas súas",
	'usersettings:statistics:opt:description' => "Ver información estatística sobre usuarios e obxectos do sitio.",
	'usersettings:statistics:opt:linktext' => "Estatísticas das contas",

	'usersettings:user' => "Configuración de %s",
	'usersettings:user:opt:description' => "Isto permítelle controlar a configuración dos usuarios",
	'usersettings:user:opt:linktext' => "Cambiar a súa configuración",

	'usersettings:plugins' => "Ferramentas",
	'usersettings:plugins:opt:description' => "Configurar as súas ferramentas activas",
	'usersettings:plugins:opt:linktext' => "Configurar as súas ferramentas",

	'usersettings:plugins:description' => "Este panel permítelle controlar e cambiar a súa configuración persoal das ferramentas instaladas polo administrador do sistema.",
	'usersettings:statistics:label:numentities' => "Contido persoal",

	'usersettings:statistics:yourdetails' => "Detalles persoais",
	'usersettings:statistics:label:name' => "Nome complet",
	'usersettings:statistics:label:email' => "Correo",
	'usersettings:statistics:label:membersince' => "Membro desde",
	'usersettings:statistics:label:lastlogin' => "Último acces",

/**
 * Activity river
 */
		
	'river:all' => 'Actividade global',
	'river:mine' => 'Actividade persoal',
	'river:owner' => 'Actividade de %s',
	'river:friends' => 'Actividade dos contactos',
	'river:select' => 'Mostrar %s',
	'river:comments:more' => '+%u máis',
	'river:comments:all' => 'Ver os %u comentarios',
	'river:generic_comment' => 'deixou un comentario en %s %s',

	'friends:widget:description' => "Mostra algúns dos seus contactos.",
	'friends:num_display' => "Número de contactos a mostrar.",
	'friends:icon_size' => "Tamaño das iconas",
	'friends:tiny' => "ananas",
	'friends:small' => "pequenas",

/**
 * Icons
 */

	'icon:size' => "Tamaño das iconas",
	'icon:size:topbar' => "Barra superior",
	'icon:size:tiny' => "Enanas",
	'icon:size:small' => "Pequenas",
	'icon:size:medium' => "Medianas",
	'icon:size:large' => "Grandes",
	'icon:size:master' => "Xigantes",
		
/**
 * Generic action words
 */

	'save' => "Gardar",
	'reset' => 'Restablecer',
	'publish' => "Publicar",
	'cancel' => "Cancelar",
	'saving' => "Gardando…",
	'update' => "Actualizar",
	'preview' => "Vista previa",
	'edit' => "Editar",
	'delete' => "Eliminar",
	'accept' => "Aceptar",
	'reject' => "Rexeitar",
	'decline' => "Refusar",
	'approve' => "Aprobar",
	'activate' => "Activar",
	'deactivate' => "Desactivar",
	'disapprove' => "Desaprobar",
	'revoke' => "Revogar",
	'load' => "Cargar",
	'upload' => "Enviar",
	'download' => "Descargar",
	'ban' => "Expulsar",
	'unban' => "Readmitir",
	'banned' => "Expulsado",
	'enable' => "Activar",
	'disable' => "Desactivar",
	'request' => "Solicitar",
	'complete' => "Completar",
	'open' => 'Abrir',
	'close' => 'Pechar',
	'hide' => 'Agochar',
	'show' => 'Mostrar',
	'reply' => "Responder",
	'more' => 'Máis',
	'more_info' => 'Máis información',
	'comments' => 'Comentarios',
	'import' => 'Importar',
	'export' => 'Exportar',
	'untitled' => 'Sen títul',
	'help' => 'Axuda',
	'send' => 'Enviar',
	'post' => 'Publicar',
	'submit' => 'Entregar',
	'comment' => 'Deixar un comentario',
	'upgrade' => 'Anovar',
	'sort' => 'Ordenar',
	'filter' => 'Filtrar',
	'new' => 'Nov',
	'add' => 'Engadir',
	'create' => 'Crear',
	'remove' => 'Eliminar',
	'revert' => 'Reverter',

	'site' => 'Siti',
	'activity' => 'Actividade',
	'members' => 'Membros',
	'menu' => 'Menú',

	'up' => 'Subir',
	'down' => 'Baixar',
	'top' => 'Arriba',
	'bottom' => 'Abaixo',
	'right' => 'Dereita',
	'left' => 'Esquerda',
	'back' => 'Volver',

	'invite' => "Convidar",

	'resetpassword' => "Restablecer o contrasinal",
	'changepassword' => "Cambiar o contrasinal",
	'makeadmin' => "Facer administrador",
	'removeadmin' => "Quitar os privilexios de administrador",

	'option:yes' => "Si",
	'option:no' => "Non",

	'unknown' => 'Descoñecido',
	'never' => 'Nunca',

	'active' => 'Activo',
	'total' => 'Total',
	
	'ok' => 'Aceptar',
	'any' => 'Calquera',
	'error' => 'Erro',
	
	'other' => 'Outro',
	'options' => 'Opcións',
	'advanced' => 'Avanzada',

	'learnmore' => "Prema aquí para saber máis",
	'unknown_error' => 'Erro descoñecido',

	'content' => "Contido",
	'content:latest' => 'Última actividade',
	'content:latest:blurb' => 'Tamén pode premer aquí para ver os últimos contidos de todo o sitio.',

	'link:text' => 'Ver a ligazón',
	
/**
 * Generic questions
 */

	'question:areyousure' => 'Está seguro?',

/**
 * Status
 */

	'status' => 'Estado',
	'status:unsaved_draft' => 'Borrador sen gardar',
	'status:draft' => 'Borrador',
	'status:unpublished' => 'Sen publicar',
	'status:published' => 'Publicado',
	'status:featured' => 'Destacado',
	'status:open' => 'Aberto',
	'status:closed' => 'Pechado',

/**
 * Generic sorts
 */

	'sort:newest' => 'Último',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alfabético',
	'sort:priority' => 'Prioridade',
		
/**
 * Generic data words
 */

	'title' => "Títul",
	'description' => "Descrición",
	'tags' => "Etiquetas",
	'all' => "Tod",
	'mine' => "Propio",

	'by' => 'de',
	'none' => 'nada',

	'annotations' => "Anotacións",
	'relationships' => "Relacións",
	'metadata' => "Metadatos",
	'tagcloud' => "Nube de etiquetas",

	'on' => 'Activado',
	'off' => 'Desactivado',

/**
 * Entity actions
 */
		
	'edit:this' => 'Editar ist',
	'delete:this' => 'Eliminar ist',
	'comment:this' => 'Deixar un comentario nist',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Está seguro de que quere eliminar isto?",
	'deleteconfirm:plural' => "Está seguro de que quere eliminar estes elementos?",
	'fileexists' => "Xa se subiu un ficheiro. Para substituílo, seleccióneo a continuación:",

/**
 * User add
 */

	'useradd:subject' => 'Creouse a conta de usuario',
	'useradd:body' => '
%s.

Creouse unha conta de usuario para vostede en %s. Pode acceder desde:

%s

E identificarse cos seguintes datos:

Username: %s
Password: %s

Despois de acceder, recomendámoslle encarecidamente que cambie o seu contrasinal.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Prema para descartar",


/**
 * Import / export
 */
		
	'importsuccess' => "Importáronse os datos",
	'importfail' => "Non foi posíbel importar os datos mediante OpenDD.",

/**
 * Time
 */

	'friendlytime:justnow' => "hai un intre",
	'friendlytime:minutes' => "hai %s minutos",
	'friendlytime:minutes:singular' => "hai un minuto",
	'friendlytime:hours' => "hai %s horas",
	'friendlytime:hours:singular' => "hai unha hora",
	'friendlytime:days' => "hai %s días",
	'friendlytime:days:singular' => "onte",
	'friendlytime:date_format' => 'j de F de Y ás g:i a',
	
	'friendlytime:future:minutes' => "en %s minutos",
	'friendlytime:future:minutes:singular' => "nun minuto",
	'friendlytime:future:hours' => "en %s horas",
	'friendlytime:future:hours:singular' => "nunha hora",
	'friendlytime:future:days' => "en %s días",
	'friendlytime:future:days:singular' => "mañá",

	'date:month:01' => '%s de xaneir',
	'date:month:02' => '%s de febreiro',
	'date:month:03' => '%s de marzo',
	'date:month:04' => '%s de abril',
	'date:month:05' => '%s de mai',
	'date:month:06' => '%s de xuño',
	'date:month:07' => '%s de xull',
	'date:month:08' => '%s de agost',
	'date:month:09' => '%s de setembr',
	'date:month:10' => '%s de outubro',
	'date:month:11' => '%s de novembr',
	'date:month:12' => '%s de decembr',

	'date:weekday:0' => 'Doming',
	'date:weekday:1' => 'Luns',
	'date:weekday:2' => 'Martes',
	'date:weekday:3' => 'Mércores',
	'date:weekday:4' => 'Xoves',
	'date:weekday:5' => 'Venres',
	'date:weekday:6' => 'Sábad',
	
	'interval:minute' => 'Cada minuto',
	'interval:fiveminute' => 'Cada cindo minutos',
	'interval:fifteenmin' => 'Cada cuarto de hora',
	'interval:halfhour' => 'Cada media hora',
	'interval:hourly' => 'Cada hora',
	'interval:daily' => 'Cada día',
	'interval:weekly' => 'Cada semana',
	'interval:monthly' => 'Cada mes',
	'interval:yearly' => 'Cada an',
	'interval:reboot' => 'Ao reiniciar',

/**
 * System settings
 */

	'installation:sitename' => "Nome do sitio:",
	'installation:sitedescription' => "Descrición curta do sitio (opcional):",
	'installation:wwwroot' => "URL do sitio:",
	'installation:path' => "Ruta completa da instalación de Elgg:",
	'installation:dataroot' => "Ruta completa do cartafol dos datos:",
	'installation:dataroot:warning' => "Debe crear este cartafol manualmente. Debería estar fóra do cartafol de instalación de Elgg.",
	'installation:sitepermissions' => "Permisos de acceso predeterminados:",
	'installation:language' => "Idioma predeterminado:",
	'installation:debug' => "Controle a cantidade de información que se garda no rexistro do servidor.",
	'installation:debug:label' => "Nivel de rexistro:",
	'installation:debug:none' => 'Desactivar o rexistro (recomendado)',
	'installation:debug:error' => 'Só rexistrar erros críticos',
	'installation:debug:warning' => 'Rexistrar erros e avisos',
	'installation:debug:notice' => 'Rexistrar erros, avisos e notas',
	'installation:debug:info' => 'Rexistralo todo',

	// Walled Garden support
	'installation:registration:description' => 'O rexistro de usuarios está activado de maneira predeterminada. Pode desactivalo se non quere que a xente se rexistre pola súa conta.',
	'installation:registration:label' => 'Permitir o rexistro de novos usuarios',
	'installation:walled_garden:description' => 'Active isto para evitar que usuarios anónimos poidan acceder a páxinas do sitio que non estean marcadas como públicas, como pode ser o caso das páxinas de acceso e rexistro de contas.',
	'installation:walled_garden:label' => 'Restrinxir as páxinas a usuarios rexistrados.',

	'installation:httpslogin' => "Active isto para que os usuarios accedan mediante unha conexión cifrada (HTTPS). Para activar esta opción, o seu servidor ten que estar configurado para permitir conexións cifradas.",
	'installation:httpslogin:label' => "Activar o acceso mediante conexións cifradas.",
	'installation:view' => "Indique a vista que se usará como vista predeterminada do sitio, ou non indique nada para usar a vista predeterminada. En caso de dúbida, deixe o campo baleiro.",

	'installation:siteemail' => "Enderezo de correo electrónico do sitio (co que enviar as mensaxes do sistema):",
	'installation:default_limit' => "Número predeterminado de elementos por páxina.",

	'admin:site:access:warning' => "Esta é a configuración de intimidade que se lles suxire aos usuarios cando crean novo contido. Cambiala non afecta ao contido xa creado.",
	'installation:allow_user_default_access:description' => "Active isto para permitirlle aos usuarios definir a súa propia configuración de intimidade predeterminada.",
	'installation:allow_user_default_access:label' => "Permitir o acceso predeterminado de usuarios.",

	'installation:simplecache:description' => "A caché simple almacena contido estático, incluídos ficheiros CSS e JavaScript, mellorando así o rendemento.",
	'installation:simplecache:label' => "Usar a caché simple (recomendado).",

	'installation:minify:description' => "A caché simple tamén pode comprimir os ficheiros CSS e JavaScript para mellorar o rendemento. Para activar esta opción active primeiro a caché simple.",
	'installation:minify_js:label' => "Comprimir o código JavaScript (recomendado).",
	'installation:minify_css:label' => "Comprimir o código CSS (recomendado).",

	'installation:htaccess:needs_upgrade' => "Debe actualizar o seu ficheiro «.htaccess» para que a ruta se insira no parámetro GET «__elgg_uri» (pode basearse no modelo que hai en «install/config/htaccess.dist»).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg non pode conectarse a si mesmo para probar as regras de substitución correctamente. Comprobe que o programa «curl» funciona e que non existen restricións de enderezos IP que impidan as conexións locais.",
	
	'installation:systemcache:description' => "A caché do sistema almacena datos en ficheiros para diminuír o tempo de carga de Elgg",
	'installation:systemcache:label' => "Usar a caché do sistema (recomendado).",

	'admin:legend:system' => 'Sistema',
	'admin:legend:caching' => 'Caché',
	'admin:legend:content_access' => 'Acceso a contidos',
	'admin:legend:site_access' => 'Acceso ao sitio',
	'admin:legend:debug' => 'Depuración e rexistr',

	'upgrading' => 'Anovando…',
	'upgrade:db' => 'Anovouse a base de datos',
	'upgrade:core' => 'Anovouse a instalación de Elgg.',
	'upgrade:unlock' => 'Desbloquear a anovación',
	'upgrade:unlock:confirm' => "A base de datos está bloqueada para outra anovación. Realizar varias anovacións ao mesmo tempo é perigoso. Non continúe salvo que estea certo de que non hai ningunha outra anovación en marcha. Quere desbloquear a anovació?",
	'upgrade:locked' => "Non é posíbel anovar, hai outra anovación en marcha. Pode desbloquear esta anovación desde a sección de administración.",
	'upgrade:unlock:success' => "Desbloqueouse a anovación.",
	'upgrade:unable_to_upgrade' => 'Non é posíbel anovar.',
	'upgrade:unable_to_upgrade_info' =>
		'Non é posíbel anovar a instalación porque se detectaron vistas obsoletas no cartafol principal («core») de vistas de Elgg. Para que Elgg funcione correctamente debe eliminar esas vistas. Se non fixo cambios no cartafol principal de vistas, pode eliminar o cartafol e substituílo polo do último paquete de Elgg, que pode descargar de <a href="http://elgg.org">elgg.org</a>.<br /><br />

Atopará instrucións detalladas na <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">documentación de anovación de Elgg</a> (en inglés). Se necesita axuda, publique unha mensaxe nos <a href="http://community.elgg.org/pg/groups/discussion/">foros de asistencia da comunidade</a> (en inglés).',

	'update:twitter_api:deactivated' => 'A API de Twitter (outrora coñeciza como «Servizo de Twitter») desactivouse durante a anovación. Actívea de novo manualmente.',
	'update:oauth_api:deactivated' => 'A API de OAuth (outrora coñecida como «OAuth Lib») desactivouse durante a anovación. Actívea de novo manualmente.',
	'upgrade:site_secret_warning:moderate' => "Recomendámoslle que volva xerar a chave do sitio para mellorar a seguranza do sistema. Pode facelo desde «Configurar → Configuración → Avanzada».",
	'upgrade:site_secret_warning:weak' => "Recomendámoslle encarecidamente que volva xerar a chave do sitio para mellorar a seguranza do sistema. Pode facelo desde «Configurar → Configuración → Avanzada».",

	'deprecated:function' => 'A función «%s()» está obsoleta, substitúaa por «%s()».',

	'admin:pending_upgrades' => 'O sitio ten anovacións pendentes que requiren da súa atención inmediata.',
	'admin:view_upgrades' => 'Ver as anovacións pendentes.',
 	'admin:upgrades' => 'Anovacións',
	'item:object:elgg_upgrade' => 'Anovacións do sitio.',
	'admin:upgrades:none' => 'A instalación está actualizada.',

	'upgrade:item_count' => 'É necesario anovar <b>%s</b> elementos.',
	'upgrade:warning' => '<b>Advertencia:</b> En sitios grandes, esta anovación pode levar bantante tempo.',
	'upgrade:success_count' => 'Anovado:',
	'upgrade:error_count' => 'Erros:',
	'upgrade:river_update_failed' => 'Non foi posíbel actualizar a entrada da onda para o elemento con identificador «%s».',
	'upgrade:timestamp_update_failed' => 'Non foi posíbel actualizar as datas para o elemento con identificador «%s».',
	'upgrade:finished' => 'Rematou a anovación',
	'upgrade:finished_with_errors' => '<p>Rematou a anovación pero producíronse erros. Actualice a páxina e probe a anovar outra vez.</p></p><br />

Se o erro persiste, comprobe o rexistro de erros do servidor, a ver se pode indetificar a causa. Para pedir axuda para solucionar o erro, acuda ao <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">grupo de asistencia técnica</a> na comunidade de Elgg.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Anovación dos comentarios',
	'upgrade:comment:create_failed' => 'Non foi posíbel converter o identificador de comentario «%s» nunha entidade.',
	'admin:upgrades:commentaccess' => 'Anovación do acceso aos comentarios',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Anovación do cartafol de datos',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Anovación da resposta de conversa',
	'discussion:upgrade:replies:create_failed' => 'Non foi posíbel converter o identificador de resposta de conversa «%s» nunha entidade.',

/**
 * Welcome
 */

	'welcome' => "Benvida",
	'welcome:user' => 'Ola, %s',

/**
 * Emails
 */
		
	'email:from' => 'De',
	'email:to' => 'A',
	'email:subject' => 'Asunt',
	'email:body' => 'Corpo',
	
	'email:settings' => "Configuración do corre",
	'email:address:label' => "Enderezo de corre",

	'email:save:success' => "Gardouse o novo enderezo de correo. Envióuselle unha solicitude de verificación.",
	'email:save:fail' => "Non foi posíbel gardar o novo enderezo de correo.",

	'friend:newfriend:subject' => "%s engadiuno como contacto.",
	'friend:newfriend:body' => "%s engadiuno como contacto.

Para ver o seu perfil, visite:

%s

Non responda a esta mensaxe.",

	'email:changepassword:subject' => "Cambiouse o contrasinal.",
	'email:changepassword:body' => "Ola, %s.

Cambiouse o seu contrasinal.",

	'email:resetpassword:subject' => "Restableceuse o seu contrasinal",
	'email:resetpassword:body' => "Ola, %s.

Restableceuse o seu contrasinal, que agora é «%s».",

	'email:changereq:subject' => "Solicitude de cambio de contrasinal.",
	'email:changereq:body' => "Ola, %s.

Alguén (desde o enderezo IP %s) solicitou cambiar o contrasinal da súa conta.

Se foi vostede, prema a ligazón embaixo. En caso contrario, ignore esta mensaxe.

%s
",

/**
 * user default access
 */

	'default_access:settings' => "Nivel de acceso predeterminado persoal",
	'default_access:label' => "Acceso predeterminado",
	'user:default_access:success' => "Gardouse o seu novo nivel de acceso predeterminado.",
	'user:default_access:failure' => "Non foi posíbel gardar o seu novo nivel de acceso predeterminado.",

/**
 * Comments
 */

	'comments:count' => "%s comentarios",
	'item:object:comment' => 'Comentarios',

	'river:comment:object:default' => '%s deixou un comentario en %s',

	'generic_comments:add' => "Deixar un comentario",
	'generic_comments:edit' => "Editar o comentario",
	'generic_comments:post' => "Publicar o comentario",
	'generic_comments:text' => "Deixar un comentario",
	'generic_comments:latest' => "Últimos comentarios",
	'generic_comment:posted' => "Publicouse o comentario:",
	'generic_comment:updated' => "Actualizouse o comentario.",
	'generic_comment:deleted' => "Eliminouse o comentario.",
	'generic_comment:blank' => "Non pode enviar un comentario baleiro",
	'generic_comment:notfound' => "Non se atopou o comentario indicado.",
	'generic_comment:notfound_fallback' => "Non se atopou o comentario indicado, esta é a páxina onde se deixou o comentario.",
	'generic_comment:notdeleted' => "Non foi posíbel eliminar o comentario.",
	'generic_comment:failure' => "Non foi posíbel gardar o comentario, produciuse un erro inesperado",
	'generic_comment:none' => 'Non hai comentarios',
	'generic_comment:title' => 'Comentario de %s',
	'generic_comment:on' => '%s en %s',
	'generic_comments:latest:posted' => 'publicou un',

	'generic_comment:email:subject' => 'Ten un comentario novo',
	'generic_comment:email:body' => "Ten un comentario novo en «%s» por parte de «%s». Di así:


%s


Para responder ou ver a súa publicación en:

%s

Pode ver o perfil de %s en:

%s

Non responda a esta mensaxe.",

/**
 * Entities
 */
	
	'byline' => 'De %s',
	'entity:default:strapline' => 'Creou %s de %s',
	'entity:default:missingsupport:popup' => 'Non é posíbel mostrar correctamente esta entidade. Pode que o motivo sexa que necesita dun complemento que xa non está instalado.',

	'entity:delete:success' => 'Eliminouse a entidade %s',
	'entity:delete:fail' => 'Non foi posíbel eliminar a entidade %s.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Ao formulario fáltanlle os campos «__token» ou «__ts».',
	'actiongatekeeper:tokeninvalid' => "A páxina que estaba a usar caducou. Vólvao intentar.",
	'actiongatekeeper:timeerror' => 'A páxina que estaba a usar caducou. Actualize a páxina e vólvao intentar.',
	'actiongatekeeper:pluginprevents' => 'Algunha razón descoñecida impediu enviar o seu formulario.',
	'actiongatekeeper:uploadexceeded' => 'O tamaño dos ficheiros enviados supera o límite definido polo administrador do sitio.',
	'actiongatekeeper:crosssitelogin' => "Non se permite acceder desde un dominio distinto. Vólvao intentar.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'e, o, a, os, as, entón, pero, mais, ela, el, un, non, tamén, sobre, agora, porén, aínda, igualmente, senón, máis, isto, iso, aquilo, parece, que, quen, cuxa, cuxo, cuxas, cuxos',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Etiquetas',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Non foi posíbel contactar con %s. Pode que teña problemas ao gardar contidos. Actualize a páxina.',
	'js:security:token_refreshed' => 'Restableceuse a conexión con %s.',
	'js:lightbox:current' => "Imaxe número %s de %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Construído con Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazo",
	"af" => "Afrikaans",
	"am" => "Amáric",
	"ar" => "Árabe",
	"as" => "Asamés",
	"ay" => "Aimará",
	"az" => "Acerbaixano",
	"ba" => "Baskir",
	"be" => "Bielorruso",
	"bg" => "Búlgaro",
	"bh" => "Biari",
	"bi" => "Bislamá; bichlamar",
	"bn" => "Bengalí; bangla",
	"bo" => "Tibetano",
	"br" => "Bretón",
	"ca" => "Catalán",
	"cmn" => "Chinés mandarín", // ISO 639-3
	"co" => "Cors",
	"cs" => "Checho",
	"cy" => "Galés",
	"da" => "Dinamarqués",
	"de" => "Alemán",
	"dz" => "Bhutani",
	"el" => "Grego",
	"en" => "Inglés",
	"eo" => "Esperant",
	"es" => "Castelán",
	"et" => "Estonian",
	"eu" => "Éuscara",
	"fa" => "Persa",
	"fi" => "Finlandés",
	"fj" => "Fidxian",
	"fo" => "Feroés",
	"fr" => "Francés",
	"fy" => "Frisón; frisi",
	"ga" => "Irlandés",
	"gd" => "Gaélico-escocés",
	"gl" => "Galeg",
	"gn" => "Guaraní",
	"gu" => "Guxaratí; guzerate",
	"he" => "Hebráico",
	"ha" => "Hausa",
	"hi" => "Hindi; híndi; indi",
	"hr" => "Croata",
	"hu" => "Húngaro",
	"hy" => "Armenio",
	"ia" => "Interlingua",
	"id" => "Indonesi",
	"ie" => "Lingua occidental",
	"ik" => "Inupiaq",
	//"in" => "Indonesian",
	"is" => "Islandés",
	"it" => "Italiano",
	"iu" => "Inuktitut",
	"iw" => "Hebráico (obsoleto)",
	"ja" => "Xaponés",
	"ji" => "Yiddish; iídiche (obsoleto)",
	"jw" => "Xavanés",
	"ka" => "Xeorxiano",
	"kk" => "Casaco",
	"kl" => "Groenlandés",
	"km" => "Khmer",
	"kn" => "Kannada",
	"ko" => "Coreano",
	"ks" => "Cachemir",
	"ku" => "Curdo",
	"ky" => "Quirguiz",
	"la" => "Latín",
	"ln" => "Lingala",
	"lo" => "Laosiano",
	"lt" => "Lituan",
	"lv" => "Letón",
	"mg" => "Malgaxe",
	"mi" => "Maorí",
	"mk" => "Macedoni",
	"ml" => "Malaiala",
	"mn" => "Mongo",
	"mo" => "Moldavi",
	"mr" => "Marata",
	"ms" => "Malaio",
	"mt" => "Maltés",
	"my" => "Birmano",
	"na" => "Nauru",
	"ne" => "Nepali; nepalés",
	"nl" => "Neerlandés",
	"no" => "Noruegués",
	"oc" => "Occitan",
	"om" => "Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polaco",
	"ps" => "Pashto",
	"pt" => "Portugués",
	"pt_br" => 'Portugués do Brasil',
	"qu" => "Quechua",
	"rm" => "Retorromance",
	"rn" => "Rundi",
	"ro" => "Romanés",
	"ru" => "Rus",
	"rw" => "Ruandés",
	"sa" => "Sánscrito",
	"sd" => "Sindhi",
	"sg" => "Sango",
	"sh" => "Serbocroata",
	"si" => "Sinhalés; cingalês",
	"sk" => "Eslovaco",
	"sl" => "Esloven",
	"sm" => "Samoa",
	"sn" => "Chona",
	"so" => "Somaí",
	"sq" => "Albanés",
	"sr" => "Serbi",
	"ss" => "Swati",
	"st" => "Sotho do norte",
	"su" => "Sudanés",
	"sv" => "Sueco",
	"sw" => "Swahili",
	"ta" => "Támil",
	"te" => "Telugú",
	"tg" => "Taxico",
	"th" => "Tailandés",
	"ti" => "Tigriña",
	"tk" => "Turcomán; turkmeno",
	"tl" => "Tagalo",
	"tn" => "Tswana",
	"to" => "Tonganés",
	"tr" => "Turc",
	"ts" => "ChiTsonga; XiTsonga; ShiTsonga",
	"tt" => "Tártaro",
	"tw" => "Twi",
	"ug" => "Uigure",
	"uk" => "Ucraíno",
	"ur" => "Urdu",
	"uz" => "Usbeco; uzbek",
	"vi" => "Vietnamita",
	"vo" => "Volapük",
	"wo" => "Wólof",
	"xh" => "Xhosa",
	//"y" => "Yiddish; iídiche",
	"yi" => "Yiddish; iídiche",
	"yo" => "Ioruba; yoruba",
	"za" => "Zhuang; chuang",
	"zh" => "Chinés",
	"zu" => "Zulú",
);
