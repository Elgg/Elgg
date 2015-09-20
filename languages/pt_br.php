<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sites',

/**
 * Sessions
 */

	'login' => "Entrar",
	'loginok' => "Você se conectou com sucesso.",
	'loginerror' => "Ocorreu um erro na tentativa se conectar. Isto pode ter ocorrido pois você não validou sua conta, as informações que você forneceu estão incorretas ou você tentou se conectar muitas vezes. Certifique-se de que seus dados estão corretos e tente novamente.",
	'login:empty' => "Login/email e senha são necessários.",
	'login:baduser' => "Não foi possível carregar sua conta de usuário.",
	'auth:nopams' => "Erro interno. Nenhum método de autenticação está instalado.",

	'logout' => "Sair",
	'logoutok' => "Você se desconectou com sucesso.",
	'logouterror' => "Não foi possível desconectar com sucesso. Por favor, tente novamente.",
	'session_expired' => "Sua sessão expirou. Por favor, atualize sua página para realizar novamente o login.",

	'loggedinrequired' => "Você deve estar conectado para ver esta página.",
	'adminrequired' => "Você deve ser um administrador para ver esta página.",
	'membershiprequired' => "Você deve fazer parte da comunidade para ver está página.",
	'limited_access' => "Você não possui permiss�o para ver esta página.",


/**
 * Errors
 */

	'exception:title' => "Erro crítico.",
	'exception:contact_admin' => 'Um erro irrecuperável ocorreu e foi registrado no \'log\'.  Entre em contato com o administrador com a seguinte informação:',

	'actionundefined' => "A ação requerida (%s) não está definida no sistema.",
	'actionnotfound' => "A arquivo de ação <i>(action file)</i> para %s não foi encontrado.",
	'actionloggedout' => "Desculpe, você não pode executar esta ação enquando desconectado.",
	'actionunauthorized' => 'Você não está autorizado a executar esta ação',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s) é um plugin mal configurado. Ele foi desativado. Por favor veja o Wiki Elgg para possíveis causas (http://docs.elgg.org/wiki/).",
	'PluginException:CannotStart' => '%d (guid:%s) não pode ser iniciado. Razão: %s',
	'PluginException:InvalidID' => "%s é um identificador (ID) inválido para o plugin.",
	'PluginException:InvalidPath' => "%s é um caminho inválido para o plugin.",
	'PluginException:InvalidManifest' => 'Arquivo do manifesto inválido para o plugin %s',
	'PluginException:InvalidPlugin' => '%s não é um plugin válido.',
	'PluginException:InvalidPlugin:Details' => '%s não é um plugin válido: %s',
	'PluginException:NullInstantiated' => 'ElggPlugin não pode ser iniciado com valor nulo. Você deve passar um valor de GUID, um identificador de plugin (ID), ou um caminho (full path).',
	'ElggPlugin:MissingID' => 'Identificador (ID) do plugin perdido (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => '\'ElggPluginPackage\' no identificador (ID) do plugin perdido %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Arquivo perdido %s no pacote.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Este diretorio do plugin deve ser renomeado para "%s" para validar o ID existente no manifesto.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'O manifesto contem um tipo de dependência inválida %s',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'O manifesto contem tipos fornecidos inválidos %s',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Existe uma dependência %s inválida "%s" no plugin %s. Plugins não podem ter conflito ou requerer algo que eles proveem!',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Não foi possível incluir %s para o plugin %s (guid: %s) em %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Não foi possível abrir visões do diretório %s para o plugin %s (guid: %s) em %s. Verifique permissões!',
	'ElggPlugin:Exception:CannotRegisterLanguages' => 'Não foi possível registrar linguagens para o plugin %s (guid: %s) em %s. Verifique permissões!',
	'ElggPlugin:Exception:NoID' => 'Sem identificador (ID) para o plugin guid %s!',
	'PluginException:NoPluginName' => "O nome do plugin não foi encontrado",
	'PluginException:ParserError' => 'Erro analisando <i>(Error parsing)</i> o manifesto com API versão %s no plugin %s.',
	'PluginException:NoAvailableParser' => 'Não foi possível encontrar um analisador <i>(parser)</i> para o manifesto da API versão %s no plugin %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Perda do atributo '%s' requerido no manifesto do plugin %s.",
	'ElggPlugin:InvalidAndDeactivated' => '%s representa um plugin invalido e foi desativado.',

	'ElggPlugin:Dependencies:Requires' => 'Requisitos',
	'ElggPlugin:Dependencies:Suggests' => 'Sugestões',
	'ElggPlugin:Dependencies:Conflicts' => 'Conflitos',
	'ElggPlugin:Dependencies:Conflicted' => 'Em conflitos',
	'ElggPlugin:Dependencies:Provides' => 'Fornecidos <i>(provides)</i>',
	'ElggPlugin:Dependencies:Priority' => 'Prioridade',

	'ElggPlugin:Dependencies:Elgg' => 'Versão do Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Versão do PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Extensões do PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Configurações do arquivo \'php.ini\':%s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugin: %s ',
	'ElggPlugin:Dependencies:Priority:After' => 'Após %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Antes %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%s não está instalado',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'Perdido',
	
	'ElggPlugin:Dependencies:ActiveDependent' => 'Existem outros plugins que listam %s como dependencia. Você deve desabilitar os seguintes plugins antes de desabilitar o atual: %s',


	'RegistrationException:EmptyPassword' => 'Os campos de senha não podem estar vazios',
	'RegistrationException:PasswordMismatch' => 'Senhas devem ser iguais',
	'LoginException:BannedUser' => 'Você foi banido deste site e não pode realizar seu login',
	'LoginException:UsernameFailure' => 'Não foi possível realizar seu login. Por favor, verifique seu login/email e senha.',
	'LoginException:PasswordFailure' => 'Não foi possível realizar seu login. Por favor, verifique seu login/email e senha.',
	'LoginException:AccountLocked' => 'Sua conta foi bloqueado por ter muitas tentativas erradas de login.',
	'LoginException:ChangePasswordFailure' => 'Falha na verificação da senha atual.',
	'LoginException:Unknown' => 'Não foi possível realizar seu login por causa de um erro desconhecido.',

	'deprecatedfunction' => 'Aviso: este código usa uma função obsoleta \'%s\' e  não é compatível com esta versão do Elgg',

	'pageownerunavailable' => 'Aviso: o proprietário da página %d não está acessível!',
	'viewfailure' => 'Existe uma falha interna na visão %s',
	'view:missing_param' => "O parametro necess�rio '%s' esta perdido na visão %s",
	'changebookmark' => 'Por favor, verifique seu favorito para esta página',
	'noaccess' => 'Este conteúdo foi removido ou você não possui permissão para visualização.',
	'error:missing_data' => 'Existem dados perdidos na sua requisição',
	'save:fail' => 'Ocorreu um erro ao salvar seus dados',
	'save:success' => 'Seus dados foram salvos',

	'error:default:title' => 'Oops...',
	'error:default:content' => 'Oops.... algo deu errado.',
	'error:404:title' => 'Pagina nao encontrada',
	'error:404:content' => 'Desculpe. Não foi possível encontrar a página solicitada por você.',

	'upload:error:ini_size' => 'O arquivo que você est� tentando enviar eh muito grande.',
	'upload:error:form_size' => 'O arquivo que você est� tentando enviar eh muito grande.',
	'upload:error:partial' => 'O envio do arquivo não foi completo.',
	'upload:error:no_file' => 'Nenhum arquivo foi selecionado.',
	'upload:error:no_tmp_dir' => 'Não foi possivel salvar o arquivo recebido.',
	'upload:error:cant_write' => 'Não foi possivel salvar o arquivo recebido.',
	'upload:error:extension' => 'Não foi possivel salvar o arquivo recebido.',
	'upload:error:unknown' => 'O envio do arquivo falhou.',


/**
 * User details
 */

	'name' => "Nome de exibição",
	'email' => "Endereço de Email",
	'username' => "Nome de usuário",
	'loginusername' => "Login ou endereço de email",
	'password' => "Senha",
	'passwordagain' => "Senha (para verificação)",
	'admin_option' => "Fazer deste usuário um administrador?",

/**
 * Access
 */

	'PRIVATE' => "Privado",
	'LOGGED_IN' => "Usuários conectados",
	'PUBLIC' => "Público",
	'LOGGED_OUT' => "Usuários desconectados",
	'access:friends:label' => "Amigos",
	'access' => "Acesso",
	'access:overridenotice' => "Nota: Conforme as diretrizes da comunidade, este conteudo somente esta disponivel para membros da comunidade.",
	'access:limited:label' => "Limitado",
	'access:help' => "O nível de acesso",
	'access:read' => "Acesso de leitura",
	'access:write' => "Acesso de escrita",
	'access:admin_only' => "Somente administradores",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Painel",
	'dashboard:nowidgets' => "Seu painel lhe permite acompanhar as atividades e conteudos do site que interessam a voce.",

	'widgets:add' => 'Adicionar dispositivos',
	'widgets:add:description' => "Escolha qualquer dispositivo abaixo para adiciona-lo a pagina.",
	'widgets:position:fixed' => '(Posição fixa na página)',
	'widget:unavailable' => 'Você já adicionou este dispositivo <i>(widget)</i>',
	'widget:numbertodisplay' => 'Número de itens para apresentar',

	'widget:delete' => 'Remover %s',
	'widget:edit' => 'Personalize este dispositivo',

	'widgets' => "Dispositivo",
	'widget' => "Dispositivo",
	'item:object:widget' => "Dispositivos",
	'widgets:save:success' => "O dispositivo foi salvo com sucesso.",
	'widgets:save:failure' => "Não foi possível salvar seu dispositivo, por favor tente novamente.",
	'widgets:add:success' => "O dispositivo foi adicionado com sucesso.",
	'widgets:add:failure' => "Não foi possível adicionar este dispositivo.",
	'widgets:move:failure' => "Não foi possível armazenar a posição do novo dispositivo.",
	'widgets:remove:failure' => "Não foi possíve remover este dispositivo",

/**
 * Groups
 */

	'group' => "Comunidade",
	'item:group' => "Comunidades",

/**
 * Users
 */

	'user' => "Usuário",
	'item:user' => "Usuários",

/**
 * Friends
 */

	'friends' => "Amigos",
	'friends:yours' => "Seus amigos",
	'friends:owned' => "Amigos de %s",
	'friend:add' => "Adicionar amigo",
	'friend:remove' => "Apagar amigo",

	'friends:add:successful' => "Você adicionou %s como amigo.",
	'friends:add:failure' => "Não foi possível adicionar %s como amigo. Por favor, tente novamente.",

	'friends:remove:successful' => "Você apagou %s de sua lista de amigos com sucesso.",
	'friends:remove:failure' => "Não foi possível apagar %s de sua lista de amigos. Por favor, tente novamente.",

	'friends:none' => "Este usuário ainda não adicionou amigos.",
	'friends:none:you' => "Você ainda não adicionou ninguém como amigo! Pesquise por seus interesses para começar a encontrar pessoas para seguir.",

	'friends:none:found' => "Não foram encontrados amigos.",

	'friends:of:none' => "Ninguém adicionou esse usuário como amigo ainda.",
	'friends:of:none:you' => "Ninguém adicionou você como amigo até agora. Começe a adicionar conteúdos e preencher seu perfil para que as pessoas possam encontrá-lo!",

	'friends:of:owned' => "Pessoas que adicionaram %s como amigo",

	'friends:of' => "Amigos de",
	'friends:collections' => "Grupos de amigos",
	'collections:add' => "Nova coleção",
	'friends:collections:add' => "Novo grupo de amigos",
	'friends:addfriends' => "Adicionar amigo",
	'friends:collectionname' => "Nome do grupo",
	'friends:collectionfriends' => "Amigos no grupo",
	'friends:collectionedit' => "Editar este grupo",
	'friends:nocollections' => "Você ainda não possui nenhum grupo.",
	'friends:collectiondeleted' => "Seu grupo foi apagado.",
	'friends:collectiondeletefailed' => "Não foi possível apagar seu grupo. Ou você não possui permissão ou algum outro problema ocorreu.",
	'friends:collectionadded' => "Seu grupo foi criado com sucesso",
	'friends:nocollectionname' => "Você deve especificar um nome para seu grupo antes de criá-lo.",
	'friends:collections:members' => "Participantes do grupo",
	'friends:collections:edit' => "Editar grupo",
	'friends:collections:edited' => "Grupo salvo",
	'friends:collection:edit_failed' => 'Não foi possível salvar a coleção.',

	'friendspicker:chararray' => 'ABCDEFGHIJKLMNOPQRSTUVWXYZ ',

	'avatar' => 'Imagem <i>(Avatar)</i>',
	'avatar:noaccess' => "Voce nao possui permissao para editar a imagem deste usuario",
	'avatar:create' => 'Crie sua imagem <i>(Avatar)</i>',
	'avatar:edit' => 'Edite sua imagem <i>(Avatar)</i>',
	'avatar:preview' => 'Prévia',
	'avatar:upload' => 'Envie um novo avatar',
	'avatar:current' => 'Avatar atual',
	'avatar:remove' => 'Remova seu avatar e selecione o icone padrão',
	'avatar:crop:title' => 'Ferramentas para manipular avatar',
	'avatar:upload:instructions' => "Seu avatar é apresentado no site. Você pode alterá-lo quanto desejar. (Formatos de arquivos aceitos: GIF, JPG ou PNG)",
	'avatar:create:instructions' => 'Clique e arraste um quadrado abaixo para manipular o seu avatar. Uma previsão aparecerá na caixa à direita.  Quando estiver satisfeito com a previsão, clique \'Crie sua imagem\'.  Este avatar definido será utilizado em todo site como seu avatar.',
	'avatar:upload:success' => 'Avatar foi enviado com sucesso',
	'avatar:upload:fail' => 'O envio do avatar falhou',
	'avatar:resize:fail' => 'Alteração do tamanho do avatar falhou',
	'avatar:crop:success' => 'Manipulação do avatar realizada com sucesso',
	'avatar:crop:fail' => 'Manipulação do avatar falhou',
	'avatar:remove:success' => 'Remoção com sucesso do avatar',
	'avatar:remove:fail' => 'Remoção do avatar falhou',

	'profile:edit' => 'Editar perfil',
	'profile:aboutme' => "Sobre mim",
	'profile:description' => "Sobre mim",
	'profile:briefdescription' => "Descrição resumida",
	'profile:location' => "Localização",
	'profile:skills' => "Habilidades",
	'profile:interests' => "Interesses",
	'profile:contactemail' => "Contato por email",
	'profile:phone' => "Telefone",
	'profile:mobile' => "Telefone Celular",
	'profile:website' => "Página WEB (website)",
	'profile:twitter' => "Nome no Twitter",
	'profile:saved' => "Seu perfil foi salvo com sucesso.",

	'profile:field:text' => 'Texto curto',
	'profile:field:longtext' => 'Area de texto larga',
	'profile:field:tags' => 'Descritores (tags)',
	'profile:field:url' => 'Endereço WEB',
	'profile:field:email' => 'Endereço de email',
	'profile:field:location' => 'Localização',
	'profile:field:date' => 'Data',

	'admin:appearance:profile_fields' => 'Editar campos no perfil',
	'profile:edit:default' => 'Editar campos no perfil',
	'profile:label' => "Título do perfil",
	'profile:type' => "Tipo do perfil",
	'profile:editdefault:delete:fail' => 'Falhou remoção do item de campo padrão  do perfil',
	'profile:editdefault:delete:success' => 'Item padrão do perfil apagado!',
	'profile:defaultprofile:reset' => 'Perfil padrão do sistema reiniciado',
	'profile:resetdefault' => 'Reinicia perfil padrão',
	'profile:resetdefault:confirm' => 'Voce tem certeza que deseja apagar seus dados do perfil?',
	'profile:explainchangefields' => "Você pode substituir os campos do perfil pelos seus próprios utilizando o formulário abaixo. \n\n Forneça o novo título do campo do perfil, por exemplo, 'Time favorito', então selecione o tipo de campo (por exemplo, texto, url, descritores), e clique no botão 'Adicionar'.  Para reordenar os campos arraste o item próximo ao título do campo.  Para editar um campo de título - clique no texto do título para pode escrever nele.  \n\n A qualquer tempo você pode voltar atrás para os campos padronizados, mas você perderá qualquer informação que tiver sido registrada no perfil.",
	'profile:editdefault:success' => 'Item foi adicionado com sucesso ao perfil padrão',
	'profile:editdefault:fail' => 'Não foi possíve salvar o perfil padrão',
	'profile:field_too_long' => 'Não foi possível salvar sua informação de perfil por ser a seção "%s" muito longa.',
	'profile:noaccess' => "Você não possui permissão para editar este perfil.",
	'profile:invalid_email' => '%s deve ser um email valido.',


/**
 * Feeds
 */
	'feed:rss' => 'Inscreva-se para receber atualizações (RSS)',
/**
 * Links
 */
	'link:view' => 'ver endereço',
	'link:view:all' => 'Ver todos',


/**
 * River
 */
	'river' => "Fluxo",
	'river:friend:user:default' => "%s agora possui amizade com %s",
	'river:update:user:avatar' => '%s possui um novo avatar',
	'river:update:user:profile' => '%s atualizou seu perfil',
	'river:noaccess' => 'Você não possui permissão para visualizar este item.',
	'river:posted:generic' => '%s postado',
	'riveritem:single:user' => 'um usuário',
	'riveritem:plural:user' => 'alguns usuários',
	'river:ingroup' => 'na comunidade %s',
	'river:none' => 'Sem atividades.',
	'river:update' => 'Atualizado para %s',
	'river:delete' => 'Retira este item de atividade',
	'river:delete:success' => 'Item do fluxo foi removido',
	'river:delete:fail' => 'Item do fluxo nao pode ser removido',
	'river:subject:invalid_subject' => 'Usuario invalido',
	'activity:owner' => 'Visualiza atividade',

	'river:widget:title' => "Atividade",
	'river:widget:description' => "Mostra últimas atividades",
	'river:widget:type' => "Tipos de atividades",
	'river:widgets:friends' => 'Atividades dos amigos',
	'river:widgets:all' => 'Atividade de todo o site',

/**
 * Notifications
 */
	'notifications:usersettings' => "Configurações das notificações",
	'notification:method:email' => 'Email ',

	'notifications:usersettings:save:ok' => "Suas configurações de notificações foram salvas com sucesso.",
	'notifications:usersettings:save:fail' => "Houve um problema ao tentar salvar suas configurações de notificações.",

	'notification:subject' => 'Notifica��o sobre %s',
	'notification:body' => 'Visualiza a nova atividade em %s',

/**
 * Search
 */

	'search' => "Pesquisar",
	'searchtitle' => "Pesquisar: %s",
	'users:searchtitle' => "Pesquisando por usuários: %s",
	'groups:searchtitle' => "Pesquisando por comunidades: %s",
	'advancedsearchtitle' => "%s com resultados que combinam com %s",
	'notfound' => "Nenhum resultado encontrado.",
	'next' => "Próxima",
	'previous' => "Anterior",

	'viewtype:change' => "Alterar o tipo de lista",
	'viewtype:list' => "Visualização da lista",
	'viewtype:gallery' => "Galeria",

	'tag:search:startblurb' => "Itens cujas tags correspondem com: '%s':",

	'user:search:startblurb' => "Usuários que combinam com '%s':",
	'user:search:finishblurb' => "Para ver mais, clique aqui.",

	'group:search:startblurb' => "Comunidades que combinam com '%s':",
	'group:search:finishblurb' => "Para ver mais, clique aqui.",
	'search:go' => 'Pesquisar',
	'userpicker:only_friends' => 'Apenas amigos',

/**
 * Account
 */

	'account' => "Conta",
	'settings' => "Configurações",
	'tools' => "Ferramentas",
	'settings:edit' => 'Editar configurações',

	'register' => "Registrar",
	'registerok' => "Você se registrou como %s com sucesso.",
	'registerbad' => "Não foi possível completar seu registro devido a erro desconhecido.",
	'registerdisabled' => "Novos registros foram desabilitados pelo administrador do sistema.",
	'register:fields' => 'Todos campos são obrigatórios',

	'registration:notemail' => 'O email fornecido por você parece não ser um email válido.',
	'registration:userexists' => 'Este nome de usuário já existe',
	'registration:usernametooshort' => 'Seu nome de usuário deve conter no mínimo %u caracteres.',
	'registration:usernametoolong' => 'Seu nome de usuário é muito grande. Deve ter um máximo de %u caracteres.',
	'registration:passwordtooshort' => 'A senha deve conter no mínimo %u caracteres.',
	'registration:dupeemail' => 'Este email já está registrado no sistema.',
	'registration:invalidchars' => 'Seu nome de usuário contém caracteres inválidos. Os seguintes caracteres sao invalidos: %s',
	'registration:emailnotvalid' => 'O email utilizado é inválido neste sistema.',
	'registration:passwordnotvalid' => 'A senha utilizada é inválida neste sistema.',
	'registration:usernamenotvalid' => 'O nome de usuário utilizado é inválido neste sistema.',

	'adduser' => "Adicionar usuário",
	'adduser:ok' => "Você adicionou um novo usuário com sucesso.",
	'adduser:bad' => "O novo usuário não pode ser criado.",

	'user:set:name' => "Configura��o do nome da conta",
	'user:name:label' => "Seu apelido",
	'user:name:success' => "Seu nome foi alterado com sucesso.",
	'user:name:fail' => "Não foi possível alterar seu nome.",

	'user:set:password' => "Senha",
	'user:current_password:label' => 'Senha atual',
	'user:password:label' => "Nova senha",
	'user:password2:label' => "Nova senha (confirmação)",
	'user:password:success' => "Senha alterada",
	'user:password:fail' => "Não foi possível alterar sua senha",
	'user:password:fail:notsame' => "Os dois campos de senha não conferem!",
	'user:password:fail:tooshort' => "Senha muito curta!",
	'user:password:fail:incorrect_current_password' => 'A senha atual digitada não está correta.',
	'user:changepassword:unknown_user' => 'Usuari invalido.',
	'user:changepassword:change_password_confirm' => 'Isto ira alterar sua senha.',

	'user:set:language' => "Configurações de idioma",
	'user:language:label' => "Seu idioma",
	'user:language:success' => "Suas configurações de idioma foram atualizadas com sucesso.",
	'user:language:fail' => "Não foi possível salvar suas configurações de idioma.",

	'user:username:notfound' => 'Nome de usuário (%s) não encontrado.',

	'user:password:lost' => 'Recuperar senha',
	'user:password:changereq:success' => 'Requisicao de senha atendida, email enviado',
	'user:password:changereq:fail' => 'Nao foi possivel requesitar nova senha.',

	'user:password:text' => 'Para gerar uma nova senha, insira seu nome de usuário abaixo e pressione o botão de solicitação.',

	'user:persistent' => 'Lembrar de mim',

	'walled_garden:welcome' => 'Bem-vindo para ',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Administrador',
	'menu:page:header:configure' => 'Configuração',
	'menu:page:header:develop' => 'Desenvolvimento',
	'menu:page:header:default' => 'Outro',

	'admin:view_site' => 'Ver site',
	'admin:loggedin' => 'Entrou como %s',
	'admin:menu' => 'Menu ',

	'admin:configuration:success' => "Suas configurações foram salvas com sucesso.",
	'admin:configuration:fail' => "Não foi possível salvar suas configurações.",
	'admin:configuration:dataroot:relative_path' => 'Não foi possível estabelecer "%s" como o diretório de dados por não ser definido um caminho absoluto.',

	'admin:unknown_section' => 'Seção de Administração inválida',

	'admin' => "Administração",
	'admin:description' => "O painel de administração permite a você controlar todas as características do sistema, desde gerência de usuários até como os plugins se comportam. Escolha uma opção abaixo para começar.",

	'admin:statistics' => "Estatísticas",
	'admin:statistics:overview' => 'Visão global',
	'admin:statistics:server' => 'Informação do Servidor',
	'admin:statistics:cron' => 'Atividades programadas (Cron)',
	'admin:cron:record' => 'Ultimos trabalhos programados',
	'admin:cron:period' => 'Periodo de atividades programadas',
	'admin:cron:friendly' => 'Ultimos completos',
	'admin:cron:date' => 'Data e tempo',

	'admin:appearance' => 'Aparência',
	'admin:administer_utilities' => 'Utilidades',
	'admin:develop_utilities' => 'Utilidades',
	'admin:configure_utilities' => 'Utilidades',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Usuários",
	'admin:users:online' => 'Atualmente online',
	'admin:users:newest' => 'Mais novo',
	'admin:users:admins' => 'Administradores',
	'admin:users:add' => 'Adiciona novo usuário',
	'admin:users:description' => "Este painel do administrador permite a você controlar as configurações do seu site. Escolha uma das opções abaixo para iniciar.",
	'admin:users:adduser:label' => "Clique aqui para adicionar um novo usuário...",
	'admin:users:opt:linktext' => "Configurar usuários...",
	'admin:users:opt:description' => "Configurar usuários e informações de contas.",
	'admin:users:find' => 'Pesquisar',

	'admin:administer_utilities:maintenance' => 'Modo de Manutencao',
	'admin:upgrades' => 'Atualizacoes',

	'admin:settings' => 'Configurações',
	'admin:settings:basic' => 'Configurações Básicas',
	'admin:settings:advanced' => 'Configurações Avançadas',
	'admin:site:description' => "Este painel do administrador permite a você controlar as configurações do seu site. Escolha uma das opções abaixo para iniciar.",
	'admin:site:opt:linktext' => "Configurar o site...",
	'admin:settings:in_settings_file' => 'EStas configura��es estao no arquivo settings.php',

	'admin:legend:security' => 'Seguranca',
	'admin:site:secret:intro' => 'Elgg usa uma chave para criar tokens de seguranca para varios propositos.',
	'admin:site:secret_regenerated' => "Sua seguranca foi regenerada.",
	'admin:site:secret:regenerate' => "Regenerar seguranca do site",
	'admin:site:secret:regenerate:help' => "Nota: Regenerar sua seguranca do site pode resultar em dificuldades para alguns usuarios por invalidar tokens usados em cookies \"lembrar de mim\", requisicoes de validacao de e-mail , codigos em convites, etc.",
	'site_secret:current_strength' => 'Forca da chave',
	'site_secret:strength:weak' => "Fraca",
	'site_secret:strength_msg:weak' => "Recomenda-se fortemente que voce regenere a seguranca do site.",
	'site_secret:strength:moderate' => "Moderado",
	'site_secret:strength_msg:moderate' => "Recomenda-se que voce regenere a seguranca do seu site para aprimorar sua seguranca.",
	'site_secret:strength:strong' => "Forte",
	'site_secret:strength_msg:strong' => "Sua seguranca do site eh suficientemente forte. Nao existe necessidade de regenera-la.",

	'admin:dashboard' => 'Painel',
	'admin:widget:online_users' => 'Usuários online',
	'admin:widget:online_users:help' => 'Lista de usuários atualmente no site',
	'admin:widget:new_users' => 'Novos usuários',
	'admin:widget:new_users:help' => 'Lista de usuários mais recentes',
	'admin:widget:banned_users' => 'Usuários banidos',
	'admin:widget:banned_users:help' => 'Lista de usuários banidos',
	'admin:widget:content_stats' => 'Estatísticas de Conteúdo',
	'admin:widget:content_stats:help' => 'Manter o controle do conteúdo criado pelos seus usuários',
	'widget:content_stats:type' => 'Tipo de conteúdo',
	'widget:content_stats:number' => 'Números',

	'admin:widget:admin_welcome' => 'Bem-vindo',
	'admin:widget:admin_welcome:help' => "Uma pequena introdução à área de administração do Elgg",
	'admin:widget:admin_welcome:intro' =>
'Bem-vindo ao Elgg!  A partir de agora você estará observando o painel do administrador.  Ele será útil para acompanhar o que acontece no site.',

	'admin:widget:admin_welcome:admin_overview' =>
"A navegação na área do administrador acontece pelo menu a direita.  Ele está organizado em três seções:
	<dl>
		<dt>Administrador</dt><dd>Atividades diárias como monitoramento de conteúdo denunciado, verificação de quem está online, e visualização de estatíticas.
</dd>
<dt>Configuração</dt><dd>Atividades ocasionais como configuração do nome do site ou ativação de plugin.</dd>
<dt>Desenvolvimento</dt><dd>Para desenvolvedores que estejam criando plugins ou desenhando temas <i>(themes)</i>. (Requer um desenvolvedor de plugin.)
</dd>
     </dl>
     ",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => 'Tenha certeza de verificar os recursos disponíveis através do link abaixo e obrigado por utilizar Elgg!',

	'admin:widget:control_panel' => 'Painel de Controle',
	'admin:widget:control_panel:help' => "Provimento de acesso fácil aos controles comuns",

	'admin:cache:flush' => 'Limpa o cache',
	'admin:cache:flushed' => "O cache do site foi limpo",

	'admin:footer:faq' => 'Administração das Perguntas Frequentes (FAQ)',
	'admin:footer:manual' => 'Manual de Administração',
	'admin:footer:community_forums' => 'Foruns na Comunidade Elgg',
	'admin:footer:blog' => 'Blog sobre Elgg',

	'admin:plugins:category:all' => 'Todos plugins',
	'admin:plugins:category:active' => 'Ativar plugins',
	'admin:plugins:category:inactive' => 'Desativar plugins',
	'admin:plugins:category:admin' => 'Administrador',
	'admin:plugins:category:bundled' => 'Incluido <i>(Bundled)</i>',
	'admin:plugins:category:nonbundled' => 'Nao incluido <i>(Non-bundled)</i>',
	'admin:plugins:category:content' => 'Conteúdo',
	'admin:plugins:category:development' => 'Desenvolvimento',
	'admin:plugins:category:enhancement' => 'Aperfeiçoamentos',
	'admin:plugins:category:api' => 'Serviço/API',
	'admin:plugins:category:communication' => 'Comunicação',
	'admin:plugins:category:security' => 'Segurança e \'SPAM\'',
	'admin:plugins:category:social' => 'Social ',
	'admin:plugins:category:multimedia' => 'Multimídia',
	'admin:plugins:category:theme' => 'Temas',
	'admin:plugins:category:widget' => 'Dispositivos',
	'admin:plugins:category:utility' => 'Utilidades',

	'admin:plugins:markdown:unknown_plugin' => 'Plugin desconhecido.',
	'admin:plugins:markdown:unknown_file' => 'Arquivo desconhecido.',

	'admin:notices:could_not_delete' => 'Noticia nao pode ser apagada.',
	'item:object:admin_notice' => 'Administracao de noticias',

	'admin:options' => 'Opções do administrador',

/**
 * Plugins
 */

	'plugins:disabled' => 'Dispositivos não foram carregados por causa da existência de um arquivo denominado "disabled" no diretório \'mod\'.',
	'plugins:settings:save:ok' => "Configurações para o plugin %s foram salvas com sucesso.",
	'plugins:settings:save:fail' => "Houve um problema ao salvar as configurações do plugin %s.",
	'plugins:usersettings:save:ok' => "Configurações do usuário para o plugin %s foram salvas com sucesso.",
	'plugins:usersettings:save:fail' => "Houve um problema ao salvar as configurações do usuárioo do plugin %s.",
	'item:object:plugin' => 'Configurações do Plugin',

	'admin:plugins' => "Administrar ferramentas",
	'admin:plugins:activate_all' => 'Ativar todos',
	'admin:plugins:deactivate_all' => 'Desativar todos',
	'admin:plugins:activate' => 'Ativar',
	'admin:plugins:deactivate' => 'Desativar',
	'admin:plugins:description' => "Este painel do administrador permite a você controlar e configurar as ferramentas instaladas no seu site.",
	'admin:plugins:opt:linktext' => "Configurar ferramentas...",
	'admin:plugins:opt:description' => "Configurar as ferramentas instaladas no site. ",
	'admin:plugins:label:author' => "Autor",
	'admin:plugins:label:copyright' => "Direitos Autorais",
	'admin:plugins:label:categories' => 'Categorias',
	'admin:plugins:label:licence' => "Licença",
	'admin:plugins:label:website' => "URL ",
	'admin:plugins:label:repository' => "Codigo",
	'admin:plugins:label:bugtracker' => "Questao relatada",
	'admin:plugins:label:donate' => "Doacao",
	'admin:plugins:label:moreinfo' => 'mais informações',
	'admin:plugins:label:version' => 'Versão',
	'admin:plugins:label:location' => 'Localização',
	'admin:plugins:label:contributors' => 'Colaboradores',
	'admin:plugins:label:contributors:name' => 'Nome',
	'admin:plugins:label:contributors:email' => 'E-mail',
	'admin:plugins:label:contributors:website' => 'Website',
	'admin:plugins:label:contributors:username' => 'Nome da comunidade',
	'admin:plugins:label:contributors:description' => 'Descricao',
	'admin:plugins:label:dependencies' => 'Dependências',

	'admin:plugins:warning:elgg_version_unknown' => 'Este plugin usa um arquivo de manifesto antigo e não define uma versão compatível do Elgg.  Ele provavelmente não funcionará!
',
	'admin:plugins:warning:unmet_dependencies' => 'Este plugin usa dependências desencontradas e não foi possível ativaá-lo.  Verifique as dependências lendo sobre mais informações. 
',
	'admin:plugins:warning:invalid' => ' Este plugin não é válido: %s.',
	'admin:plugins:warning:invalid:check_docs' => 'Verifiqye  <a href="http://docs.elgg.org/Invalid_Plugin">a documentacao do Elgg</a> para dicas de solu��o de problemas.',
	'admin:plugins:cannot_activate' => 'não foi possível ativar',

	'admin:plugins:set_priority:yes' => "Reordenado %s",
	'admin:plugins:set_priority:no' => "Não foi possível reordenar %s",
	'admin:plugins:set_priority:no_with_msg' => "Não foi possível reordenar %s. Erro: %s",
	'admin:plugins:deactivate:yes' => "Desativado %s",
	'admin:plugins:deactivate:no' => "Não foi possível desativar %s",
	'admin:plugins:deactivate:no_with_msg' => "Não foi possível desativar %s. Erro: %s",
	'admin:plugins:activate:yes' => "Ativados %s",
	'admin:plugins:activate:no' => "Não foi possível ativar %s",
	'admin:plugins:activate:no_with_msg' => "Não foi possível ativar %s. Erro: %s",
	'admin:plugins:categories:all' => 'Todas categorias',
	'admin:plugins:plugin_website' => 'Website do plugin',
	'admin:plugins:author' => '%s ',
	'admin:plugins:version' => 'Versão %s',
	'admin:plugin_settings' => 'Configurações do plugin',
	'admin:plugins:warning:unmet_dependencies_active' => 'Este plugin foi ativado mas possui pendências.  Você deve encontrar problemas.  Veja "mais informações" abaixo para detalhes.',

	'admin:plugins:dependencies:type' => 'Tipo',
	'admin:plugins:dependencies:name' => 'Nome',
	'admin:plugins:dependencies:expected_value' => 'Valor testado',
	'admin:plugins:dependencies:local_value' => 'Valor atual',
	'admin:plugins:dependencies:comment' => 'Comentário',

	'admin:statistics:description' => "Este é um sumário de estatísticas sobre o site. Se você deseja estatísticas mais detalhadas, um recurso de gerenciamento profissional está disponível.",
	'admin:statistics:opt:description' => "Exibir informações estatísticas sobre usuários e objetos no site.",
	'admin:statistics:opt:linktext' => "Exibir estatísticas...",
	'admin:statistics:label:basic' => "Estatísticas básicas do site",
	'admin:statistics:label:numentities' => "Entidades no site",
	'admin:statistics:label:numusers' => "Número de usuários",
	'admin:statistics:label:numonline' => "Número de usuários conectados",
	'admin:statistics:label:onlineusers' => "Usuários conectados agora",
	'admin:statistics:label:admins'=>"Administradores",
	'admin:statistics:label:version' => "Versão do Elgg",
	'admin:statistics:label:version:release' => "Liberação <i>(Release)</i>",
	'admin:statistics:label:version:version' => "Versão",

	'admin:server:label:php' => 'PHP ',
	'admin:server:label:web_server' => 'Servidor WEB',
	'admin:server:label:server' => 'Servidor',
	'admin:server:label:log_location' => 'Localização dos registros',
	'admin:server:label:php_version' => 'Versão do PHP',
	'admin:server:label:php_ini' => 'Localização do arquivo \'ini.php\'',
	'admin:server:label:php_log' => 'Log do PHP',
	'admin:server:label:mem_avail' => 'Memória livre',
	'admin:server:label:mem_used' => 'Memória utilizada',
	'admin:server:error_log' => "Registro de erro do servidor WEB",
	'admin:server:label:post_max_size' => 'Tamanho máximo dos emails (POST)',
	'admin:server:label:upload_max_filesize' => 'Tamanho máximo dos arquivos enviados (UPLOAD)',
	'admin:server:warning:post_max_too_small' => '(Nota: tamanho máximo dos emails deve ser maior que este valor para suportar envio de arquivos deste tamanho)',

	'admin:user:label:search' => "Encontrar usuários:",
	'admin:user:label:searchbutton' => "Pesquisar",

	'admin:user:ban:no' => "Não foi possível banir o usuário",
	'admin:user:ban:yes' => "Usuário banido.",
	'admin:user:self:ban:no' => "Você não pode banir sua própria conta",
	'admin:user:unban:no' => "Não foi possível desbanir o usuário",
	'admin:user:unban:yes' => "Usuário desbanido.",
	'admin:user:delete:no' => "Não foi possível apagar o usuário",
	'admin:user:delete:yes' => "Usuário %s foi apagado",
	'admin:user:self:delete:no' => "Você não pode apagar sua própria conta",

	'admin:user:resetpassword:yes' => "Senha zerada, usuário notificado.",
	'admin:user:resetpassword:no' => "A senha não pôde ser zerada.",

	'admin:user:makeadmin:yes' => "O usuário agora é um administrador.",
	'admin:user:makeadmin:no' => "Não foi possível tornar este usuário um administrador.",

	'admin:user:removeadmin:yes' => "O usuário não é mais um administrador.",
	'admin:user:removeadmin:no' => "Não foi possível apagar os privilégios de administrador deste usuário.",
	'admin:user:self:removeadmin:no' => "Você não pode remover seus próprios privilégios de administrador.",

	'admin:appearance:menu_items' => 'Itens do Menu',
	'admin:menu_items:configure' => 'Configure os itens do menu principal',
	'admin:menu_items:description' => 'Selecione qual item do menu você deseja apresentar como link em destaque.  Itens não utilizados serão adicionados como \'MAIS" no final da lista.',
	'admin:menu_items:hide_toolbar_entries' => 'Remover link da barra de ferramentas no menu?',
	'admin:menu_items:saved' => 'Itens do menu salvos.',
	'admin:add_menu_item' => 'Adicionar um item customizado no menu',
	'admin:add_menu_item:description' => 'Preencha o Nome e endereço (URL) para adicionar itens customizados no seu menu de navegação.',

	'admin:appearance:default_widgets' => 'Dispositivos padrão',
	'admin:default_widgets:unknown_type' => 'Tipo de dispositivo desconhecido',
	'admin:default_widgets:instructions' => 'Adicione, remova, posicione, e defina os dispositivos padrão para a página de seleção de dispositivos.  Estas alterações somente serão aplicadas aos novos usuarios do site.',

	'admin:robots.txt:instructions' => "Editar o arquivo robots.txt abaixo",
	'admin:robots.txt:plugins' => "Dispositivos (Plugins) estao adicionando o seguinte ao arquivo robots.txt",
	'admin:robots.txt:subdir' => "A ferramenta robots.txt nao estara ativa porque o Elgg esta instalado em um sub-diretorio",

	'admin:maintenance_mode:default_message' => 'Este site esta inativo para atividades de manutencao',
	'admin:maintenance_mode:instructions' => 'Modo de manutencao pode ser usado para atualizacoes e outras grandes alteracoes no site.
		Quanto ativado, somente administradores podem logar e navegar no site.',
	'admin:maintenance_mode:mode_label' => 'Modo de manutencao',
	'admin:maintenance_mode:message_label' => 'Mensagem apresentada aos usuarios quando o modo de manutencao esta ativo',
	'admin:maintenance_mode:saved' => 'As configuracoes do modo de manutencao foram salvas.',
	'admin:maintenance_mode:indicator_menu_item' => 'O site esta no modo de manutencao.',
	'admin:login' => 'Login do Admininistrador',

/**
 * User settings
 */
		
	'usersettings:description' => "O Painel de configurações do usuário permite a você controlar suas informações pessoais, desde configurar o usuário até como os plugins se comportam. Escolha uma opção abaixo para iniciar.",

	'usersettings:statistics' => "Suas estatísticas.",
	'usersettings:statistics:opt:description' => "Ver informações estatísticas sobre usuários e objetos em seu site.",
	'usersettings:statistics:opt:linktext' => "Estatisticas de conta",

	'usersettings:user' => "Configurações de %s",
	'usersettings:user:opt:description' => "Isto permite a você controlar as configurações de usuários.",
	'usersettings:user:opt:linktext' => "Altere suas configurações",

	'usersettings:plugins' => "Ferramentas",
	'usersettings:plugins:opt:description' => "Configure as características (se tiver alguma) para suas ferramentas ativas.",
	'usersettings:plugins:opt:linktext' => "Configure suas ferramentas",

	'usersettings:plugins:description' => "Este painel permite a você controlar e configurar as informações pessoais referentes às ferramentas instaladas pelo administrador do sistema.",
	'usersettings:statistics:label:numentities' => "Seu conteúdo",

	'usersettings:statistics:yourdetails' => "Seus detalhes",
	'usersettings:statistics:label:name' => "Nome completo",
	'usersettings:statistics:label:email' => "Endereço de email",
	'usersettings:statistics:label:membersince' => "Participante desde",
	'usersettings:statistics:label:lastlogin' => "último acesso em",

/**
 * Activity river
 */
		
	'river:all' => 'Toda atividade do site',
	'river:mine' => 'Minhas atividades',
	'river:owner' => 'Atividade de %s',
	'river:friends' => 'Atividades do amigos',
	'river:select' => 'Mostrar %s',
	'river:comments:more' => '+%u mais',
	'river:generic_comment' => 'fez um comentário em %s %s',

	'friends:widget:description' => "Exibe alguns de seus amigos.",
	'friends:num_display' => "Número de amigos a serem exibidos",
	'friends:icon_size' => "Tamanho do ícone",
	'friends:tiny' => "minúsculo",
	'friends:small' => "pequeno",

/**
 * Icons
 */

	'icon:size' => "Tamanho do icone",
	'icon:size:topbar' => "Barra Superior",
	'icon:size:tiny' => "Minúsculo",
	'icon:size:small' => "Pequeno",
	'icon:size:medium' => "Medio",
	'icon:size:large' => "Grande",
	'icon:size:master' => "Extra Grande",
		
/**
 * Generic action words
 */

	'save' => "Salvar",
	'reset' => 'Reiniciar',
	'publish' => "Publicar",
	'cancel' => "Cancelar",
	'saving' => "Salvando ...",
	'update' => "Atualizar",
	'preview' => "Prévia",
	'edit' => "Editar",
	'delete' => "Apagar",
	'accept' => "Aceitar",
	'reject' => "Rejeitar",
	'decline' => "Recusar",
	'approve' => "Aprovar",
	'activate' => "Ativar",
	'deactivate' => "Desativar",
	'disapprove' => "Desaprovar",
	'revoke' => "Revogar",
	'load' => "Carregar",
	'upload' => "Enviar",
	'download' => "Download",
	'ban' => "Banir",
	'unban' => "Banir (desfazer)",
	'banned' => "Banido",
	'enable' => "Ativar",
	'disable' => "Desativar",
	'request' => "Solicitar",
	'complete' => "Completo",
	'open' => 'Abrir',
	'close' => 'Fechar',
	'hide' => 'Ocultar',
	'show' => 'Mostrar',
	'reply' => "Responder",
	'more' => 'Mais',
	'more_info' => 'Mais informacao',
	'comments' => 'Comentários',
	'import' => 'Importar',
	'export' => 'Exportar',
	'untitled' => 'Sem título',
	'help' => 'Ajuda',
	'send' => 'Enviar',
	'post' => 'Enviar',
	'submit' => 'Enviar',
	'comment' => 'Comentário',
	'upgrade' => 'Atualizar',
	'sort' => 'Sortear',
	'filter' => 'Filtrar',
	'new' => 'Novo',
	'add' => 'Adicionar',
	'create' => 'Criar',
	'remove' => 'Remover',
	'revert' => 'Reverter',

	'site' => 'Site ',
	'activity' => 'Atividades',
	'members' => 'Pessoas',
	'menu' => 'Menu',

	'up' => 'Acima',
	'down' => 'Abaixo',
	'top' => 'Topo',
	'bottom' => 'Final',
	'right' => 'Direita',
	'left' => 'Esquerda',
	'back' => 'Retorna',

	'invite' => "Convidar",

	'resetpassword' => "Reiniciar senha",
	'changepassword' => "Altera senha",
	'makeadmin' => "Tornar administrador",
	'removeadmin' => "Apagar administrador",

	'option:yes' => "Sim",
	'option:no' => "Não",

	'unknown' => 'Desconhecido',
	'never' => 'Nunca',

	'active' => 'Ativo',
	'total' => 'Total ',
	
	'ok' => 'OK',
	'any' => 'Qualquer',
	'error' => 'Erro',
	
	'other' => 'Outro',
	'options' => 'Opcoes',
	'advanced' => 'Avancado',

	'learnmore' => "Clique aqui para aprender mais.",
	'unknown_error' => 'Erro desconhecido',

	'content' => "conteúdo",
	'content:latest' => 'últimas atividades',
	'content:latest:blurb' => 'Alternativamente, clique aqui para exibir os últimos conteúdos do site.',

	'link:text' => 'ver link',
	
/**
 * Generic questions
 */

	'question:areyousure' => 'Você tem certeza?',

/**
 * Status
 */

	'status' => 'Estado',
	'status:unsaved_draft' => 'Rascunho nao salvo',
	'status:draft' => 'Rascunho',
	'status:unpublished' => 'Retirar publicacao',
	'status:published' => 'Publicado',
	'status:featured' => 'Destacado',
	'status:open' => 'Aberto',
	'status:closed' => 'Fechado',

/**
 * Generic sorts
 */

	'sort:newest' => 'Mais recente',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alfabetica',
	'sort:priority' => 'Prioridade',
		
/**
 * Generic data words
 */

	'title' => "Título",
	'description' => "Descrição",
	'tags' => "Descritores <i>(Tags)</i>",
	'spotlight' => "Destaque",
	'all' => "Todos",
	'mine' => "Minhas",

	'by' => 'por',
	'none' => 'nenhum',

	'annotations' => "Anotações",
	'relationships' => "Relacionamentos",
	'metadata' => "Metadados",
	'tagcloud' => "Nuvem de palavras",
	'tagcloud:allsitetags' => "Todos descritores (tags) do site",

	'on' => 'Ligado',
	'off' => 'Desligado',

/**
 * Entity actions
 */
		
	'edit:this' => 'Editar',
	'delete:this' => 'Apagar',
	'comment:this' => 'Comentar',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Você tem certeza de que deseja apagar este item?",
	'deleteconfirm:plural' => "Você tem certeza que deseja apagar este itens?",
	'fileexists' => "Um arquivo já foi enviado. Para substituí-lo, selecione-o abaixo:",

/**
 * User add
 */

	'useradd:subject' => 'Conta de usuário criada',
	'useradd:body' => '
%s,

Uma conta de usuário foi criada para você em %s. Para entrar, visite:

%s

Para conectar-se, utilize as informações de usuário abaixo:

Nome de usuário: %s
Senha: %s

Assim que você se conectar, nós recomendamos fortemente que você altere sua senha.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "Clique para desfazer",


/**
 * Import / export
 */
		
	'importsuccess' => "Importação de dados realizada com sucesso",
	'importfail' => "OpenDD falhou ao importar dados.",

/**
 * Time
 */

	'friendlytime:justnow' => "agora",
	'friendlytime:minutes' => "%s minutos atrás",
	'friendlytime:minutes:singular' => "um minuto atrás",
	'friendlytime:hours' => "%s horas atrás",
	'friendlytime:hours:singular' => "uma hora atrás",
	'friendlytime:days' => "%s dias atrás",
	'friendlytime:days:singular' => "ontem",
	'friendlytime:date_format' => 'j F Y  @ g:ia',
	
	'friendlytime:future:minutes' => "em %s minutos",
	'friendlytime:future:minutes:singular' => "em um minute",
	'friendlytime:future:hours' => "em %s horas",
	'friendlytime:future:hours:singular' => "em uma hora",
	'friendlytime:future:days' => "em %s dias",
	'friendlytime:future:days:singular' => "amanha",

	'date:month:01' => '%s de Janeiro',
	'date:month:02' => '%s de Fevereiro',
	'date:month:03' => '%s de Março',
	'date:month:04' => '%s de Abril',
	'date:month:05' => '%s de Maio',
	'date:month:06' => '%s de Junho',
	'date:month:07' => '%s de Julho',
	'date:month:08' => '%s de Agosto',
	'date:month:09' => '%s de Setembro',
	'date:month:10' => '%s de Outubro',
	'date:month:11' => '%s de Novembro',
	'date:month:12' => '%s de Dezembro',

	'date:weekday:0' => 'Domingo',
	'date:weekday:1' => 'Segunda',
	'date:weekday:2' => 'Terca',
	'date:weekday:3' => 'Quarta',
	'date:weekday:4' => 'Quinta',
	'date:weekday:5' => 'Sexta',
	'date:weekday:6' => 'Sabado',
	
	'interval:minute' => 'A cada minuto',
	'interval:fiveminute' => 'A cada 5 minutos',
	'interval:fifteenmin' => 'A cada 15 minutos',
	'interval:halfhour' => 'A cada meia hora',
	'interval:hourly' => 'A cada hora',
	'interval:daily' => 'Diariamente',
	'interval:weekly' => 'Semanalmente',
	'interval:monthly' => 'Mensalmente',
	'interval:yearly' => 'Anualmente',
	'interval:reboot' => 'No reboot',

/**
 * System settings
 */

	'installation:sitename' => "O nome do seu site:",
	'installation:sitedescription' => "Descrição resumida do seu site (opcional):",
	'installation:wwwroot' => "O URL do site:",
	'installation:path' => "O caminho completo da instalação do Elgg:",
	'installation:dataroot' => "O caminho completo do diretório de dados:",
	'installation:dataroot:warning' => "Você pode criar o diretório manualmente.  Ele deve estar em um diretório diferente da sua instalação do Elgg.",
	'installation:sitepermissions' => "A permissão padrão de acesso:",
	'installation:language' => "A linguagem padrão do seu site:",
	'installation:debug' => "Controle da quantidade de informacao escrita nos registros do servidor",
	'installation:debug:label' => "Nivel dos registros (log level):",
	'installation:debug:none' => 'Desligue o modo de depuração (recomendado)',
	'installation:debug:error' => 'Apresentar somente erros críticos',
	'installation:debug:warning' => 'Registra erros e avisos',
	'installation:debug:notice' => 'Registre todos erros, avisos e notícias',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'Registro de usuários está habilitado por padrão.  Desabilite se você não deseja que novos usuário sejam registrados por sua vontade própria.',
	'installation:registration:label' => 'Permite que novos usuários se registrem.',
	'installation:walled_garden:description' => 'Permite ao site funcionar como uma rede privada.  Não permitirá  que pessoas sem registro vejam qualquer página do site que não esteja especificamente marcada como pública.',
	'installation:walled_garden:label' => 'Páginas restritas para pessoas registradas.',

	'installation:httpslogin' => "Permite isso para permitir que os logins dos usuários seja realizado sobre HTTPS (ambiente seguro). Você deve ter o serviço 'https' habilitado no seu servidor para isto funcionar.",
	'installation:httpslogin:label' => "Habilitar logins usando HTTPS ",
	'installation:view' => "Entre a visão que será utilizada por padrão pelo site ou deixe em branco para a visão padrão do sistema (se tiver dúvida, deixe com o padrão):",

	'installation:siteemail' => "Endereço de email do Site (usado quando enviadas as mensagens de email do sistema)",

	'admin:site:access:warning' => "Este eh a configuracao de privacidade sugerida aos usuarios quando eles criam conteudo. Alteracoes nisso nao alteram o acesso ao conteudo.",
	'installation:allow_user_default_access:description' => "Se marcado, usuários individuais poderão definir seu próprio nível de acesso que pode sobrepor o nível de acesso do sistema.",
	'installation:allow_user_default_access:label' => "Permite acesso padrão aos usuários",

	'installation:simplecache:description' => "O 'cache' simples aumenta a performance por armazenar conteúdos estáticos como os arquivos CSS e JavaScript.",
	'installation:simplecache:label' => "Usar 'cache' simples (recomendado)",

	'installation:minify:description' => "O cache simples pode aumentar a performance por comprimir os arquivos CSS e JavaScript (requer que o cache simples esteja ativado)",
	'installation:minify_js:label' => "JavaScript comprimido (recomendado)",
	'installation:minify_css:label' => "CSS comprimido (recommendado)",

	'installation:htaccess:needs_upgrade' => "Voce deve atualizar seu arquivo .htaccess para que o caminho seja injetado no parametro GET __elgg_uri (voce pode usar o arquivo htaccess_dist como guia).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg nao pode se conectar para o autoteste das regras de escrita. Verifique se o curl esta funcionando e se nao existe nenhuma restricao de acesso local ao seu IP.",
	
	'installation:systemcache:description' => "O sistema de armazenamento (cache) diminui o tempo de carregamento do Elgg por guardar na memória os arquivos.",
	'installation:systemcache:label' => "Utilizar o sistema de armazenamento (cache) - RECOMENDÁVEL",

	'admin:legend:caching' => 'Armazenando (caching)',
	'admin:legend:content_access' => 'Acesso ao Conteudo',
	'admin:legend:site_access' => 'Acesso ao Site',
	'admin:legend:debug' => 'Depurando e Logando',

	'upgrading' => 'Atualizando...',
	'upgrade:db' => 'Seu banco de dados foi atualizado.',
	'upgrade:core' => 'Sua instalação do Elgg foi atualizada.',
	'upgrade:unlock' => 'Atualizacao desbloqueada',
	'upgrade:unlock:confirm' => "O banco de dados esta bloqueado por outra atualizacao. Atualizacoes concorrentes sao perigosas. Voce deve continuar apenas se voce sabe que nao exista outra atualizacao ocorrendo.  Desbloquear?",
	'upgrade:locked' => "Atualizacao nao possivel. Outra atualizacao esta ocorrendo. Para liberar o bloqueio da atualizacao, visite a secao do administrador.",
	'upgrade:unlock:success' => "Sucesso no desbloqueio da atualizacao.",
	'upgrade:unable_to_upgrade' => 'Não foi possíve atualizar.',
	'upgrade:unable_to_upgrade_info' =>
		'Esta instalação não pode ser atualizada por conta das visões legadas <i>(legacy views)</i> que foram detectadas no núcleo, no diretório de visões do Elgg.  Estas visões estão obsoletas e precisam ser removidas para o Elgg funcionar adequadamente.  Se você nao fez alteracoes no nucleo do Elgg (core Elgg) voce pode simplesmente apagar o diretorio de visoes (view directory) e substituir pelo diretorio presente na ultima distribuicao do pacote Elgg obtido a partir de <a href="http://elgg.org">elgg.org</a>.<br /><br />
           Se voce precisa de informacoes detalhadas, por favor, visite <a href="http://docs.elgg.org/wiki/Upgrading_Elgg">
		Documentacao de atualizacao do Elgg</a>.  Se voce deseja ajuda, por favor envie mensagem para <a href="http://community.elgg.org/pg/groups/discussion/">Forum da Comunidade de Suporte</a>.',

	'update:twitter_api:deactivated' => 'O API  do Twitter (antigo \'Twitter Service\') foi desabilitado durante a atualização.  Por favor se desejar habilite manualmente este plugin.',
	'update:oauth_api:deactivated' => 'O API OAuth (antigo \'OAuth lib\') foi desabilitado durante a atualização.  Por favor se desejar habilite manualmente este plugin.',
	'upgrade:site_secret_warning:moderate' => "Voce eh encorajado a regenerar sua chave de seguranca do site para aumentar a seguranca do seu sistema. Veja Configure &gt; Configuracao &gt; Avancado",
	'upgrade:site_secret_warning:weak' => "Voce eh fortemente encorajado a regenerar sua chave de seguranca do site para aumentar a seguranca do seu sistema. Veja Configure &gt; Configuracao &gt; Avancado",

	'ElggUpgrade:error:url_invalid' => 'Valor invalido para o caminho URL.',
	'ElggUpgrade:error:url_not_unique' => 'Atualizacoes de caminhos URL devem ser unicas.',
	'ElggUpgrade:error:title_required' => 'Objetos ElggUpgrade devem ter titulo.',
	'ElggUpgrade:error:description_required' => 'Objetos ElggUpgrade devem ter descricao.',
	'ElggUpgrade:error:upgrade_url_required' => 'Objetos ElggUpgrade devem ter caminho URL atualizado.',

	'deprecated:function' => '%s() foi substituido por %s()',

	'admin:pending_upgrades' => 'O site possui atualizacoes pendentes que dependem da sua atencao imediata.',
	'admin:view_upgrades' => 'Visualizar atualizacoes pendentes.',
 	'admin:upgrades' => 'Atualizacoes',
	'item:object:elgg_upgrade' => 'Atualizacoes do Site',
	'admin:upgrades:none' => 'Sua instalacao em dia com as atualizacoes!',

	'upgrade:item_count' => 'Existem <b>%s</b> itens que necessitam atualizacao.',
	'upgrade:warning' => '<b>Aviso:</b> em sites grandes esta atualizacao pode tomar um tempo longo!',
	'upgrade:success_count' => 'Atualizado:',
	'upgrade:error_count' => 'Erros:',
	'upgrade:river_update_failed' => 'Falha para atualizacao da entrada de fluxo identificada (ID) por %s',
	'upgrade:timestamp_update_failed' => 'Falha para atualizar os tempos para item identificado (ID) por %s',
	'upgrade:finished' => 'Atualizacao finalizada',
	'upgrade:finished_with_errors' => '<p>Atualizacao finalizada com erros. Renove (refresh) a pagina e tente atualizar novamente.</p></p><br />Se ocorrer erros, verifique registro de erros (error log) do servidor para identificar as causas possiveis. Voce pode procurar ajuda para correcao dos erros em <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support"> Grupo de suporte tecnico</a> na comunidade Elgg.</p>',

	// Strings specific for the comments upgrade
	'admin:upgrades:comments' => 'Comentarios de atualizacao',
	'upgrade:comment:create_failed' => 'Falha para converter um comentario id %s para uma entidade.',

	// Strings specific for the datadir upgrade
	'admin:upgrades:datadirs' => 'Atualiza diretorio de dados',

	// Strings specific for the discussion reply upgrade
	'admin:upgrades:discussion_replies' => 'Atualiza resposta a discussoes',
	'discussion:upgrade:replies:create_failed' => 'Falha para converter respostas a discussoes id %s para uma entidade.',

/**
 * Welcome
 */

	'welcome' => "Seja bem vindo",
	'welcome:user' => 'Seja bem vindo %s',

/**
 * Emails
 */
		
	'email:from' => 'De',
	'email:to' => 'Para',
	'email:subject' => 'Assunto',
	'email:body' => 'Corpo',
	
	'email:settings' => "Configurações de Email",
	'email:address:label' => "Endereco de email",

	'email:save:success' => "Novo email gravado, verificação necessária.",
	'email:save:fail' => "Seu novo email não pôde ser gravado.",

	'friend:newfriend:subject' => "%s adicionou-o como amigo!",
	'friend:newfriend:body' => "%s adicionou-o como amigo!

Para ver o perfil dela(e), clique aqui:

%s

Por favor, não responda a este email.",

	'email:changepassword:subject' => "Senha alterada!",
	'email:changepassword:body' => "Ola %s,

Sua senha foi alterada.",

	'email:resetpassword:subject' => "Gerar nova senha!",
	'email:resetpassword:body' => "Olá %s,

Sua nova senha foi gerada e ela é: %s",

	'email:changereq:subject' => "Solicitar troca de senha",
	'email:changereq:body' => "Olá %s,

Alguém (do endereço de IP %s) solicitou uma nova senha para esta conta.

Se você fez esta solicitação, clique no link abaixo, caso contrário por favor ignore este email.

%s
 
",

/**
 * user default access
 */

	'default_access:settings' => "Seu nível de acesso padrão",
	'default_access:label' => "Acesso padrão",
	'user:default_access:success' => "Seu novo nível de acesso padrão foi salvo.",
	'user:default_access:failure' => "Seu novo nível de acesso padrão não pode ser salvo.",

/**
 * Comments
 */

	'comments:count' => "%s comentários",
	'item:object:comment' => 'Comentários',

	'river:comment:object:default' => '%s comentado em %s',

	'generic_comments:add' => "Adicionar comentário",
	'generic_comments:edit' => "Editar comentário",
	'generic_comments:post' => "Comentário da mensagem",
	'generic_comments:text' => "Comentário",
	'generic_comments:latest' => "Últimos comentários",
	'generic_comment:posted' => "Seu comentário foi enviado com sucesso.",
	'generic_comment:updated' => "O comentário foi atualizado com sucesso.",
	'generic_comment:deleted' => "Seu comentário foi apagado com sucesso.",
	'generic_comment:blank' => "Você deve adicionar algum conteúdo antes de salvar seu comentário.",
	'generic_comment:notfound' => "Não foi possível encontrar o item específico.",
	'generic_comment:notdeleted' => "Não foi possível apagar este comentário.",
	'generic_comment:failure' => "Um erro inesperado ocorreu na tentativa de adicionar seu comentário, por favor, tente novamente.",
	'generic_comment:none' => 'Sem comentários',
	'generic_comment:title' => 'Comentado por %s',
	'generic_comment:on' => '%s em %s',
	'generic_comments:latest:posted' => 'postado um(a)',

	'generic_comment:email:subject' => 'Você possui um novo comentário!',
	'generic_comment:email:body' => "Você possui um novo comentário em seu item \"%s\" de %s. Ele diz:


%s


Para responder ou ver o original, clique aqui:

%s

Para ver o perfil de %s, clique aqui:

%s

Não responda a este email.",

/**
 * Entities
 */
	
	'byline' => 'Por %s',
	'entity:default:strapline' => 'Criado %s por %s',
	'entity:default:missingsupport:popup' => 'Esta entidade não pôde ser exibida corretamente. Isto deve ter ocorrido pois requer o suporte de um plugin que não está mais instalado.',

	'entity:delete:success' => 'Entidade %s foi apagada',
	'entity:delete:fail' => 'Entidade %s não pode ser apagada',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Formulário perdido <i>(is missing __token or __ts fields)</i>',
	'actiongatekeeper:tokeninvalid' => "A pagina que voce estava usando expirou. Por favor, tente novamente.",
	'actiongatekeeper:timeerror' => 'A página que você está utilizando expirou.  Por favor, atualize e tente novamente.',
	'actiongatekeeper:pluginprevents' => 'Um extensão bloqueou a submissão deste formulário.',
	'actiongatekeeper:uploadexceeded' => 'O tamanho dos arquivos enviados excede o limite estabelecido pelo administrador do site.',
	'actiongatekeeper:crosssitelogin' => "Desculpe, login d dominio diferente nao esta permitido. Por favor, tente novamente.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'e, a, o, então, mas, ela, seu, sua, dele, dela, um, não, também, cerca de, agora, por isso, entretanto, ainda, da mesma forma, caso contrário, portanto, inversamente, ao invés, conseqüentemente,consequentemente, além disso, no entanto, em vez disso, enquanto isso, em conformidade, isto, este, esta, parece, o quê,o que, quem, cujo, quem, quem',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Descritores (Tags)',
	'tags:site_cloud' => 'Núvem de palavras do site',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Não foi possível contato com %s.  Você pode ser problemas salvando conteúdo. Por favor, atualizae sua pagina.',
	'js:security:token_refreshed' => 'Conexão para %s reativada!',
	'js:lightbox:current' => "imagens %s de %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Feito com Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar ",
	"ab" => "Abkházia",
	"af" => "Afrikaans ",
	"am" => "Américo",
	"ar" => "Árabe",
	"as" => "Assamese ",
	"ay" => "Aymara ",
	"az" => "Azerbaijão",
	"ba" => "Bashkir ",
	"be" => "Bielo-russia",
	"bg" => "Búlgaro",
	"bh" => "Bihari ",
	"bi" => "Bislama ",
	"bn" => "Bengali; Bangla ",
	"bo" => "Tibetana",
	"br" => "Breton ",
	"ca" => "Catalão",
	"cmn" => "Mandarin Chines", // ISO 639-3
	"co" => "Corso",
	"cs" => "Checa",
	"cy" => "Galês",
	"da" => "Dinamarquês",
	"de" => "Alemão",
	"dz" => "Bhutani ",
	"el" => "Grego",
	"en" => "Inglês",
	"eo" => "Esperanto ",
	"es" => "Espanhol",
	"et" => "Estoniano",
	"eu" => "Basco",
	"fa" => "Persa",
	"fi" => "Finlandesa",
	"fj" => "Fiji ",
	"fo" => "Ilhas Faroé",
	"fr" => "Francês",
	"fy" => "Frisian ",
	"ga" => "Irlandês",
	"gd" => "Scots / Gaelic ",
	"gl" => "Galego",
	"gn" => "Guarani ",
	"gu" => "Guzerate",
	"he" => "Hebraica",
	"ha" => "Hausa ",
	"hi" => "Hindi ",
	"hr" => "Croata",
	"hu" => "Húngaro",
	"hy" => "Armênio",
	"ia" => "Interlingua ",
	"id" => "Indonésio",
	"ie" => "Interlingue ",
	"ik" => "Inupiak ",
	//"in" => "Indonésio ",
	"is" => "Islandês",
	"it" => "Italiano",
	"iu" => "Inuktitut ",
	"iw" => "Hebraico (obsolete)",
	"ja" => "Japanês",
	"ji" => "Iídiche (obsolete)",
	"jw" => "Javanês",
	"ka" => "Georgiano",
	"kk" => "Cazaque",
	"kl" => "Groelândia",
	"km" => "Cambojano",
	"kn" => "Canará",
	"ko" => "Coreano",
	"ks" => "Caxemira",
	"ku" => "Curdo",
	"ky" => "Kirghiz ",
	"la" => "Latim",
	"ln" => "Lingala ",
	"lo" => "Laosiano",
	"lt" => "Lituano",
	"lv" => "Letão/Lettish",
	"mg" => "Malagasy ",
	"mi" => "Maori ",
	"mk" => "Macedonian ",
	"ml" => "Malayalam ",
	"mn" => "Mongol",
	"mo" => "Moldava",
	"mr" => "Marathi ",
	"ms" => "Malay ",
	"mt" => "Maltês",
	"my" => "Birmenês",
	"na" => "Nauru ",
	"ne" => "Nepalês",
	"nl" => "Holandês",
	"no" => "Norueguês",
	"oc" => "Occitano",
	"om" => "(Afan) Oromo ",
	"or" => "Oriya ",
	"pa" => "Punjabi ",
	"pl" => "Polonês",
	"ps" => "Pashto / Pushto ",
	"pt" => "Português",
	"pt_br" => 'Portugues Brasileiro',
	"qu" => "Quechua ",
	"rm" => "Rhaeto-Romance ",
	"rn" => "Kirundi ",
	"ro" => "Romeno",
	"ru" => "Russo",
	"rw" => "Kinyarwanda ",
	"sa" => "Sânscrito",
	"sd" => "Sindi",
	"sg" => "Sangro ",
	"sh" => "Servo-Croata",
	"si" => "Singhalês ",
	"sk" => "Eslováquia",
	"sl" => "Esloveno",
	"sm" => "Samoan ",
	"sn" => "Shona ",
	"so" => "Somalis",
	"sq" => "Albanesa",
	"sr" => "Sérvio",
	"ss" => "Siswati ",
	"st" => "Sesotho ",
	"su" => "Sundanês",
	"sv" => "Sueco",
	"sw" => "Suaíli",
	"ta" => "Tamil ",
	"te" => "Tegulu ",
	"tg" => "Tajik ",
	"th" => "Tailandês",
	"ti" => "Tigrinya ",
	"tk" => "Turcomenistão",
	"tl" => "Tagalog ",
	"tn" => "Setswana ",
	"to" => "Tonga ",
	"tr" => "Turco",
	"ts" => "Tsonga ",
	"tt" => "Tártaro",
	"tw" => "Twi ",
	"ug" => "Uigur ",
	"uk" => "Ucraniano",
	"ur" => "Urdu ",
	"uz" => "Usbeque",
	"vi" => "Vietnamita",
	"vo" => "Volapuk ",
	"wo" => "Wolof ",
	"xh" => "Xhosa ",
	//"y" => "Ií­diche ",
	"yi" => "Ií­diche",
	"yo" => "Iorubá",
	"za" => "Zuang ",
	"zh" => "Chinês",
	"zu" => "Zulu ",
);
