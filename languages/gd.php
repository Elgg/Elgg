<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Làraichean',

/**
 * Sessions
 */

	'login' => "Clàraich a-steach",
	'loginok' => "Chaidh do chlàradh a-steach.",
	'loginerror' => "Cha b' urrainn dhuinn do chlàradh a-steach. Thoir sùil air an teisteas agad is feuch ris a-rithist.",
	'login:empty' => "Tha feum air ainm-cleachdaiche no seòladh puist-d agus facal-faire.",
	'login:baduser' => "Cha b' urrainn dhuinn an cunntas cleachdaiche agad a luchdadh.",
	'auth:nopams' => "Mearachd inntearnail. Cha deach modh dearbhaidh nan cleachdaichean a stàladh.",

	'logout' => "Clàraich a-mach",
	'logoutok' => "Chaidh do chlàradh a-mach.",
	'logouterror' => "Cha b' urrainn dhuinn do chlàradh a-mach. Am feuch thu ris a-rithist?",
	'session_expired' => "Dh'fhalbh an ùine air an t-seisean agad. <a href='javascript:location.reload(true)'>Ath-luchdaich</a> an duilleag gus clàradh a-steach.",
	'session_changed_user' => "Chaidh do chlàradh a-steach mar chleachdaiche eile. Bu chòir dhut an duilleag <a href='javascript:location.reload(true)'>ath-luchdadh</a>.",

	'loggedinrequired' => "Feumaidh tu clàradh a-steach gus an duilleag a dh'iarr thu a shealltainn.",
	'adminrequired' => "Feumaidh tu bhith 'nad rianaire gus an duilleag a dh'iarr thu a shealltainn.",
	'membershiprequired' => "Feumaidh tu bhith 'nad bhall dhen bhuidheann seo gus an duilleag a dh'iarr thu a shealltainn.",
	'limited_access' => "Chan eil cead agad gus an duilleag a dh'iarr thu a shealltainn.",
	'invalid_request_signature' => "Chan eil URL na duilleige a tha thu a' feuchainn inntrigeadh dligheach no dh'fhalbh an ùine air",

/**
 * Errors
 */

	'exception:title' => "Mearachd mharbhtach.",
	'exception:contact_admin' => 'Thachair mearachd nach gabh aiseag agus chaidh a chur ris an loga. Cuir fios gu rianaire na làraich leis an fhiosrachadh seo:',

	'actionundefined' => "Cha deach an gnìomh a dh'iarr thu (%s) a mhìneachadh san t-siostam.",
	'actionnotfound' => "Cha deach faidhle gnìomha airson %s a lorg.",
	'actionloggedout' => "Tha sinn duilich ach chan urrainn dhut seo a dhèanamh gun chlàradh a-steach.",
	'actionunauthorized' => 'Chan eil cead agad gus seo a dhèanamh.',

	'ajax:error' => 'Tachair mearachd air nach robh sinn an dùil rè gairm AJAX. Dh\'fhaoidte gun deach an ceangal dhan fhrithealaiche air chall.',
	'ajax:not_is_xhr' => 'Chan urrainn dhut seallaidhean AJAX inntrigeadh gu dìreach',

	'PluginException:MisconfiguredPlugin' => "Cha deach am plugan %s (guid: %s) a rèiteachadh mar bu chòir. Chaidh a chur à comas. Lorg adhbhar san uicidh aig Elgg (http://learn.elgg.org/).",
	'PluginException:CannotStart' => 'Cha ghabh %s (guid: %s) tòiseachadh agus chaidh a chur à comas. Adhbhar: %s',
	'PluginException:InvalidID' => "Chan eil %s 'na ID plugain dligheach.",
	'PluginException:InvalidPath' => "Chan eil %s 'na slighe plugain dhligheach.",
	'PluginException:InvalidManifest' => 'Faidhle manifest mì-dhligheach airson plugan %s',
	'PluginException:InvalidPlugin' => 'Chan eil %s \'na phlugan dligheach.',
	'PluginException:InvalidPlugin:Details' => 'Chan eil %s \'na phlugan dligheach: %s',
	'PluginException:NullInstantiated' => 'Chan fhaod ElggPlugin a bhith gun sònrachadh. Feumaidh tu GUID, ID plugain no slighe shlàn a thoirt seachad.',
	'ElggPlugin:MissingID' => 'Tha ID plugain a dhìth (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'Tha ElggPluginPackage a dhìth air a\' plugan leis an ID %s (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'Tha am faidhle riatanach "%s" a dhìth.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'Feumaidh tu an t-ainm ùr "%s" a chur air pasgan a\' phlugain ach am freagair e ris an ID sa mhanifest aige.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'Tha seòrsa eisimeileachd "%s" sa mhanifest aige nach eil dligheach.',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'Tha seòrsa provides "%s" sa mhanifest aige nach eil dligheach.',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'Tha eisimeileachd %s "%s" mì-dhligheach sa phlugan %s.  Chan fhaod plugain rud iarraidh a bheir iad fhèin seachad no bhith ann an còmhstri leis!',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'Còmhstri le plugan: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'Tha faidhle "elgg-plugin.php" a\' phlugain ann ach cha ghabh a leughadh.',
	'ElggPlugin:Exception:CannotIncludeFile' => 'Cha ghabh %s a chur am broinn plugan %s (guid: %s) air %s.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'Chaidh eisgeachd a thilgeadh le cur %s am broinn a\' phlugain %s (guid: %s) air %s.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'Cha b\' urrainn dhuinn pasgan nan seallaidhean fhosgladh airson plugan %s (guid: %s) air %s.',
	'ElggPlugin:Exception:NoID' => 'Chan eil ID ann airson a\' phlugan le guid %s!',
	'PluginException:NoPluginName' => "Cha deach ainm a' phlugain a lorg",
	'PluginException:ParserError' => 'Mearachd a\' parsadh manifest le tionndadh %s dhen API sa phlugan %s.',
	'PluginException:NoAvailableParser' => 'Cha deach parsair a lorg airson manifest le tionndadh %s an API sa phlugan %s.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "Tha buadh '%s' riatanach sa mhanifest a dhìth air plugan %s.",
	'ElggPlugin:InvalidAndDeactivated' => 'Tha %s \'na phlugan mì-dhligheach agus chaidh a chur à comas.',
	'ElggPlugin:activate:BadConfigFormat' => 'Cha do thill am faidhle plugain "elgg-plugin.php" arraigh a ghabhas sreathachadh.',
	'ElggPlugin:activate:ConfigSentOutput' => 'Chuir am faidhle plugain "elgg-plugin.php" às-chur.',

	'ElggPlugin:Dependencies:Requires' => 'Feumaidh e',
	'ElggPlugin:Dependencies:Suggests' => 'Molaidh e',
	'ElggPlugin:Dependencies:Conflicts' => 'Còmhstrithean',
	'ElggPlugin:Dependencies:Conflicted' => 'Ann an còmhstri',
	'ElggPlugin:Dependencies:Provides' => 'Solairidh e',
	'ElggPlugin:Dependencies:Priority' => 'Prìomhachas',

	'ElggPlugin:Dependencies:Elgg' => 'Tionndadh dhe dh\'Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'Tionndadh dhe PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'Leudachan PHP: %s',
	'ElggPlugin:Dependencies:PhpIni' => 'Roghainn PHP ini: %s',
	'ElggPlugin:Dependencies:Plugin' => 'Plugan: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'Às dèidh %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'Ro %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => 'Cha deach %s a stàldh',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'A dhìth',

	'ElggPlugin:Dependencies:ActiveDependent' => 'Tha plugain eile ann aig a bheil %s \'na eisimeileachd. Feumaidh tu na plugain a leanas a chur à comas mus cuir thu am fear seo à comas: %s',

	'ElggMenuBuilder:Trees:NoParents' => 'Chaidh nithean clàir-thaice a lorg aig nach eil pàrant gus an ceangal ris',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'Chaidh nì clàir-thaice [%s] a lorg agus pàrant a dhìth air[%s]',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'Chaidh an nì clàir-thaice [%s] a chlàradh iomadh turas',

	'RegistrationException:EmptyPassword' => 'Chan fhaod na raointean facail-fhaire a bhith falamh',
	'RegistrationException:PasswordMismatch' => 'Feumaidh an dà fhacal-fhaire a bhith co-ionnann',
	'LoginException:BannedUser' => 'Chaidh do thoirmeasg on làrach seo agus chan urrainn dhut clàradh a-steach',
	'LoginException:UsernameFailure' => 'Cha b\' urrainn dhuinn do chlàradh a-steach. Thoir sùil air an ainm-chleachdaiche no seòladh puist-d agus air an fhacail-fhaire agad.',
	'LoginException:PasswordFailure' => 'Cha b\' urrainn dhuinn do chlàradh a-steach. Thoir sùil air an ainm-chleachdaiche no seòladh puist-d agus air an fhacail-fhaire agad.',
	'LoginException:AccountLocked' => 'Chaidh an cunntas agad a ghlasadh on a tha cus oidhirpean clàraidh a-steach gun soirbheachadh ann.',
	'LoginException:ChangePasswordFailure' => 'Dh\'fhàillig dearbhadh an fhacail-fhaire seo.',
	'LoginException:Unknown' => 'Cha b\' urrainn dhuinn do chlàradh a-steach ri linn mearachd nach b\' aithne dhuinn.',

	'UserFetchFailureException' => 'Cha b\' urrainn dhuinn ceadan a dhearbhadh airson user_guid [%s] o nach eil an cleachdaiche sin ann.',

	'deprecatedfunction' => 'Rabhadh: Tha an còd seo a cleachdadh an fhoincsein "%s" nach molar tuilleadh agus chan eil e co-chòrdail ris an tionndadh seo dhe dh\'Elgg',

	'pageownerunavailable' => 'Rabhadh: Cha ghabh sealbhadair na duilleige %d inntrigeadh!',
	'viewfailure' => 'Dh\'fhàillig rud taobh a-staigh an t-seallaidh %s',
	'view:missing_param' => "Tha am paramadair \"%s\" riatanach a dhìth air an t-sealladh %s",
	'changebookmark' => 'Atharraich an comharra-lìn agad airson na duilleige seo',
	'noaccess' => 'Chaidh an t-susbaint a bha thu airson sealltainn a thoirt air falbh no chan eil cead agad gus a shealltainn.',
	'error:missing_data' => 'Tha dàta a dhìth air an iarrtas agad',
	'save:fail' => 'Dh\'fhàillig le sàbhaladh an dàta agad',
	'save:success' => 'Chaidh an dàta agad a shàbhaladh',

	'error:default:title' => 'Iochd...',
	'error:default:content' => 'Iochd...chaidh rudeigin cearr.',
	'error:400:title' => 'Droch iarrtas',
	'error:400:content' => 'Tha sinn duilich ach chan eil an t-iarrtas dligheach is coileanta.',
	'error:403:title' => 'Toirmisgte',
	'error:403:content' => 'Tha sinn duilich ach chan fhaod thu an duilleag a dh\'iarr thu inntrigeadh.',
	'error:404:title' => 'Cha deach an duilleag a lorg',
	'error:404:content' => 'Tha sinn duilich ach cha do lorg sinn an duilleag a dh\'iarr thu.',

	'upload:error:ini_size' => 'Tha am faidhle a dh\'fheuch thu ri luchdadh suas ro mhòr.',
	'upload:error:form_size' => 'Tha am faidhle a dh\'fheuch thu ri luchdadh suas ro mhòr.',
	'upload:error:partial' => 'Cha deach luchdadh suas an fhaidhle a choileanadh.',
	'upload:error:no_file' => 'Cha deach faidhle a thaghadh.',
	'upload:error:no_tmp_dir' => 'Chan urrainn dhuinn am faidhle air a luchdadh suas a shàbhaladh.',
	'upload:error:cant_write' => 'Chan urrainn dhuinn am faidhle air a luchdadh suas a shàbhaladh.',
	'upload:error:extension' => 'Chan urrainn dhuinn am faidhle air a luchdadh suas a shàbhaladh.',
	'upload:error:unknown' => 'Dh\'fhàillig le luchdadh suas an fhaidhle.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'Rianaire',
	'table_columns:fromView:banned' => 'Toirmisgte',
	'table_columns:fromView:container' => 'Soitheach',
	'table_columns:fromView:excerpt' => 'Tuairisgeul',
	'table_columns:fromView:link' => 'Ainm/Tiotal',
	'table_columns:fromView:icon' => 'Ìomhaigheag',
	'table_columns:fromView:item' => 'Nì',
	'table_columns:fromView:language' => 'Cànan',
	'table_columns:fromView:owner' => 'Sealbhadair',
	'table_columns:fromView:time_created' => 'Air a chruthachach',
	'table_columns:fromView:time_updated' => 'Air ùrachadh',
	'table_columns:fromView:user' => 'Cleachdaiche',

	'table_columns:fromProperty:description' => 'Tuairisgeul',
	'table_columns:fromProperty:email' => 'Post-d',
	'table_columns:fromProperty:name' => 'Ainm',
	'table_columns:fromProperty:type' => 'Seòrsa',
	'table_columns:fromProperty:username' => 'Ainm-cleachdaiche',

	'table_columns:fromMethod:getSubtype' => 'Fo-sheòrsa',
	'table_columns:fromMethod:getDisplayName' => 'Ainm/Tiotal',
	'table_columns:fromMethod:getMimeType' => 'Seòrsa MIME',
	'table_columns:fromMethod:getSimpleType' => 'Seòrsa',

/**
 * User details
 */

	'name' => "Ainm taisbeanaidh",
	'email' => "Seòladh puist-d",
	'username' => "Ainm-cleachdaiche",
	'loginusername' => "Ainm-cleachdaiche no seòladh puist-d",
	'password' => "Facal-faire",
	'passwordagain' => "Facal-faire (a-rithist a chum dearbhaidh)",
	'admin_option' => "A bheil thu airson rianaire a dhèanamh dhen chleachdaiche seo?",
	'autogen_password_option' => "A bheil thu airson facal-faire tèarainte a ghintinn gu fèin-obrachail?",

/**
 * Access
 */

	'PRIVATE' => "Prìobhaideach",
	'LOGGED_IN' => "Cleachdaichean air an clàradh a-steach",
	'PUBLIC' => "Poblach",
	'LOGGED_OUT' => "Cleachdaichean air an clàradh a-mach",
	'access:friends:label' => "Caraidean",
	'access' => "Inntrigeadh",
	'access:overridenotice' => "An aire: Ri linn poileasaidh a' bhuidhinn, chan fhaigh ach buill dhen bhuidheann cothrom air an t-susbaint seo.",
	'access:limited:label' => "Cuingichte",
	'access:help' => "Leibheil inntrigidh",
	'access:read' => "Leughadh a-mhàin",
	'access:write' => "Sgrìobhadh",
	'access:admin_only' => "Rianairean a-mhàin",
	'access:missing_name' => "Tha ainm a dhìth air an leibheil inntrigidh",
	'access:comments:change' => "Chan fhaic ach cuid an còmhradh seo. Bidh cùramach a thaobh cò leis a cho-roinneas tu e.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "Deas-bhòrd",
	'dashboard:nowidgets' => "Leigidh an deas-bhòrd agad leat sùil a chumail air a' ghnìomhachd agus an t-susbaint a tha cudromach dhut air an làrach seo.",

	'widgets:add' => 'Cuir widget ris',
	'widgets:add:description' => "Briog air putan widget sam bith gu h-ìosal gus a chur ris an duilleag.",
	'widgets:panel:close' => "Dùin panail nan widget",
	'widgets:position:fixed' => '(Ionad socraichte air an duilleag)',
	'widget:unavailable' => 'Tha thu air a\' widget seo a chur ris cheana',
	'widget:numbertodisplay' => 'An àireamh dhe nithean ri an sealltainn',

	'widget:delete' => 'Thoir air falbh %s',
	'widget:edit' => 'Gnàthaich a\' widget seo',

	'widgets' => "Widgets",
	'widget' => "Widget",
	'item:object:widget' => "Widgets",
	'widgets:save:success' => "Chaidh a' widget a shàbhaladh.",
	'widgets:save:failure' => "Cha b' urrainn dhuinn a' widget agad a shàbhaladh.",
	'widgets:add:success' => "Chaidh a' widget a chur ris.",
	'widgets:add:failure' => "Cha b' urrainn dhuinn a' widget agad a chur ris.",
	'widgets:move:failure' => "Cha b' urrainn dhuinn ionad ùr a' widget a shàbhaladh.",
	'widgets:remove:failure' => "Cha b' urrainn dhuinn a' widget seo a thoirt air falbh",

/**
 * Groups
 */

	'group' => "Buidheann",
	'item:group' => "Buidhnean",

/**
 * Users
 */

	'user' => "Cleachdaiche",
	'item:user' => "Cleachdaichean",

/**
 * Friends
 */

	'friends' => "Caraidean",
	'friends:yours' => "Do charaidean",
	'friends:owned' => "Na caraidean aig %s",
	'friend:add' => "Cuir caraid ris",
	'friend:remove' => "Cuir caraid air falbh",

	'friends:add:successful' => "Cha thu air %s a chur ri do charaidean.",
	'friends:add:failure' => "Cha b' urrainn dhuinn %s a chur ri do charaidean.",

	'friends:remove:successful' => "Tha thu air %s a thoirt air falbh o do charaidean.",
	'friends:remove:failure' => "Cha b' urrainn dhuinn %s a thoirt air falbh o do charaidean.",

	'friends:none' => "Chan eil caraid ann fhathast.",
	'friends:none:you' => "Chan eil caraid agad fhathast.",

	'friends:none:found' => "Cha deach caraid a lorg.",

	'friends:of:none' => "Cha do chuir duine an cleachdaiche seo ri a charaidean fhathast.",
	'friends:of:none:you' => "Cha do chuir duine ri a charaidean thu fhathast. Tòisich air susbaint a chur ris agus a' phròifil agad a lìonadh ach an lorg daoine thu!",

	'friends:of:owned' => "Daoine a chuir %s ri an caraidean",

	'friends:of' => "Na caraidean aig",
	'friends:collections' => "Cruinneachaidhean charaidean",
	'collections:add' => "Cruinneachadh ùr",
	'friends:collections:add' => "Cruinneachadh charaidean ùr",
	'friends:addfriends' => "Tagh caraidean",
	'friends:collectionname' => "Ainm a' chruinneachaidh",
	'friends:collectionfriends' => "Caraidean sa chruinneachadh",
	'friends:collectionedit' => "Deasaich an cruinneachadh seo",
	'friends:nocollections' => "Chan eil cruinneachadh agad fhathast.",
	'friends:collectiondeleted' => "Chaidh an cruinneachadh agad a sguabadh às.",
	'friends:collectiondeletefailed' => "Cha b' urrainn dhuinn an cruinneachadh a sguabadh às. Tha cead a dhìth ort no thachair duilgheadas eile.",
	'friends:collectionadded' => "Chaidh an cruinneachadh agad a chruthachadh",
	'friends:nocollectionname' => "Feumaidh tu ainm a chur air a' chruinneachadh agad mus gabh a chruthachadh.",
	'friends:collections:members' => "Buill a' chruinneachaidh",
	'friends:collections:edit' => "Deasaich an cruinneachadh",
	'friends:collections:edited' => "Chaidh an cruinneachadh a shàbhaladh",
	'friends:collection:edit_failed' => 'Cha b\' urrainn dhuinn an cruinneachadh a shàbhaladh.',

	'friendspicker:chararray' => 'AÀÁBCDEÈÉFGHIÌÍJKLMNOÒÓPQRSTUÙÚVWXYZ\'-',

	'avatar' => 'Avatar',
	'avatar:noaccess' => "Chan fhaod thu avatar a' chleachdaiche seo a dheasachadh",
	'avatar:create' => 'Cruthaich an avatar agad',
	'avatar:edit' => 'Deasaich an avatar',
	'avatar:preview' => 'Ro-sheall',
	'avatar:upload' => 'Luchdaich suas avatar ùr',
	'avatar:current' => 'An t-avatar làithreach',
	'avatar:remove' => 'Thoir air falbh an t-avatar agad agus suidhich air an ìomhaigheag thùsail e',
	'avatar:crop:title' => 'Inneal bearraidh an avatar',
	'avatar:upload:instructions' => "Thèid an t-avatar agad a shealltainn air feadh na làraich. 'S urrainn dhut atharrachadh cho tric 's a thogras tu. (Na fòrmatan faidhle ris a ghabhas sinn: GIF, JPG no PNG)",
	'avatar:create:instructions' => 'Click and drag a square below to match how you want your avatar cropped. A preview will appear in the box on the right. When you are happy with the preview, click \'Create your avatar\'. This cropped version will be used throughout the site as your avatar.',
	'avatar:upload:success' => 'Chaidh an t-avatar a luchdadh suas',
	'avatar:upload:fail' => 'Dh\'fhàillig le luchdadh suas an avatar',
	'avatar:resize:fail' => 'Dh\'fhàillig le ath-mheudachadh an avatar',
	'avatar:crop:success' => 'Chaidh an t-avatar a bhearradh',
	'avatar:crop:fail' => 'Dh\'fhàillig le bearradh an avatar',
	'avatar:remove:success' => 'Chaidh an t-avatar a thoirt air falbh',
	'avatar:remove:fail' => 'Dh\'fhàillig le toirt air falbh an avatar',

	'profile:edit' => 'Deasaich a\' phròifil',
	'profile:aboutme' => "Mu mo dhèidhinn",
	'profile:description' => "Mu mo dhèidhinn",
	'profile:briefdescription' => "Tuairisgeul goirid",
	'profile:location' => "Àite",
	'profile:skills' => "Sgilean",
	'profile:interests' => "Ùidhean",
	'profile:contactemail' => "Post-d conaltraidh",
	'profile:phone' => "Fòn",
	'profile:mobile' => "Fòn-làimhe",
	'profile:website' => "Làrach-lìn",
	'profile:twitter' => "Ainm-cleachdaiche Twitter",
	'profile:saved' => "Chaidh a' phròifil agad a shàbhaladh.",

	'profile:field:text' => 'Teacsa goirid',
	'profile:field:longtext' => 'Raon-teacsa mòr',
	'profile:field:tags' => 'Tagaichean',
	'profile:field:url' => 'Seòladh-lìn',
	'profile:field:email' => 'Seòladh puist-d',
	'profile:field:location' => 'Àite',
	'profile:field:date' => 'Ceann-là',

	'admin:appearance:profile_fields' => 'Deasaich raointean na pròifil',
	'profile:edit:default' => 'Deasaich raointean na pròifil',
	'profile:label' => "Leubail na pròifil",
	'profile:type' => "Seòrsa na pròifil",
	'profile:editdefault:delete:fail' => 'Dh\'fhàillig le toirt air falbh raon na pròifil',
	'profile:editdefault:delete:success' => 'Chaidh raon na pròifil a sguabadh às',
	'profile:defaultprofile:reset' => 'Chaidh raointean na pròifil ath-shuidheachadh air bun-roghainn an t-siostaim',
	'profile:resetdefault' => 'Ath-shuidhich raointean na pròifil air bun-roghainn an t-siostaim',
	'profile:resetdefault:confirm' => 'A bheil thu cinnteach gu bheil thu airson raointean gnàthaichte a\' phròifil a sguabadh às?',
	'profile:explainchangefields' => "You can replace the existing profile fields with your own using the form below. \n\n Give the new profile field a label, for example, 'Favorite team', then select the field type (eg. text, url, tags), and click the 'Add' button. To re-order the fields drag on the handle next to the field label. To edit a field label - click on the label's text to make it editable. \n\n At any time you can revert back to the default profile set up, but you will lose any information already entered into custom fields on profile pages.",
	'profile:editdefault:success' => 'Chaidh raon ùr a chur ris a\' phròifil',
	'profile:editdefault:fail' => 'Cha b\' urrainn dhuinn a\' phròifil thùsail a shàbhaladh',
	'profile:field_too_long' => 'Chan urrainn dhuinn fiosrachadh na pròifil agad a shàbhaladh on a tha an earrann "%s" ro fhada.',
	'profile:noaccess' => "Chan eil cead agad gus a' phròifil seo a dheasachadh.",
	'profile:invalid_email' => 'Feumaidh %s a bhith \'na sheòladh puist-d dligheach.',


/**
 * Feeds
 */
	'feed:rss' => 'Inbhir RSS airson na duilleige seo',
/**
 * Links
 */
	'link:view' => 'seall an ceangal',
	'link:view:all' => 'Seall na h-uile',


/**
 * River
 */
	'river' => "River",
	'river:friend:user:default' => "Tha %s 'na charaid aig %s a-nis",
	'river:update:user:avatar' => 'Tha avatar ùr aig %s',
	'river:update:user:profile' => 'Dh\'ùraich %s a\' phròifil aige/aice',
	'river:noaccess' => 'Chan eil cead agad gus an nì seo a shealltainn.',
	'river:posted:generic' => 'phostaich %s',
	'riveritem:single:user' => 'cleachdaiche',
	'riveritem:plural:user' => 'cleachdaichean',
	'river:ingroup' => 'sa bhuidheann %s',
	'river:none' => 'Chan eil gnìomhachd ann',
	'river:update' => 'Ùrachadh airson %s',
	'river:delete' => 'Thoir air falbh nì na gnìomhachd seo',
	'river:delete:success' => 'Chaidh nì na gnìomhachd a sguabadh às',
	'river:delete:fail' => 'Cha b\' urrainn dhuinn nì na gnìomhachd a sguabadh às',
	'river:delete:lack_permission' => 'Chan fhad thu nì na gnìomhachd seo a sguabadh às',
	'river:can_delete:invaliduser' => 'Cha b\' urrainn dhuinn canDelete a dhearbhadh airson user_guid [%s] o nach eil an cleachdaiche sin ann.',
	'river:subject:invalid_subject' => 'Cleachdaiche mì-dhligheach',
	'activity:owner' => 'Seall a\' ghnìomhachd',

	'river:widget:title' => "Gnìomhachd",
	'river:widget:description' => "Seall a' ghnìomhachd as ùire",
	'river:widget:type' => "Seòrsa na gnìomhachd",
	'river:widgets:friends' => 'Gnìomhachd charaidean',
	'river:widgets:all' => 'Gnìomhachd na làraich',

/**
 * Notifications
 */
	'notifications:usersettings' => "Roghainnean nam brathan",
	'notification:method:email' => 'Post-d',

	'notifications:usersettings:save:ok' => "Chaidh roghainnean nam brathan a shàbhaladh.",
	'notifications:usersettings:save:fail' => "Thachair duilgheadas le sàbhaladh roghainnean nam brathan.",

	'notification:subject' => 'Brath air %s',
	'notification:body' => 'Seall a\' ghnìomhachd ùr air %s',

/**
 * Search
 */

	'search' => "Lorg",
	'searchtitle' => "Lorg: %s",
	'users:searchtitle' => "A' lorg cleachdaichean: %s",
	'groups:searchtitle' => "A' lorg buidhnean: %s",
	'advancedsearchtitle' => "%s le toradh a fhreagras ri %s",
	'notfound' => "Cha deach toradh a lorg.",
	'next' => "Air adhart",
	'previous' => "Air ais",

	'viewtype:change' => "Atharraich seòrsa na liosta",
	'viewtype:list' => "Sealladh liosta",
	'viewtype:gallery' => "Gailearaidh",

	'tag:search:startblurb' => "Nithean le tagaichean a fhreagras ri \"%s\":",

	'user:search:startblurb' => "Cleachdaichean a fhreagras ri \"%s\":",
	'user:search:finishblurb' => "Briog an-seo gus barrachd dhiubh a shealltainn.",

	'group:search:startblurb' => "Buidhnean a fhreagras ri \"%s\":",
	'group:search:finishblurb' => "Briog an-seo gus barrachd dhiubh a shealltainn.",
	'search:go' => 'Siuthad',
	'userpicker:only_friends' => 'Caraidean a-mhàin',

/**
 * Account
 */

	'account' => "Cunntas",
	'settings' => "Roghainnean",
	'tools' => "Innealan",
	'settings:edit' => 'Deasaich na roghainnean',

	'register' => "Clàraich",
	'registerok' => "Tha thu air clàradh le %s.",
	'registerbad' => "Cha b' urrainn dhuinn do chlàradh ri linn mearachd nach b' aithne dhuinn.",
	'registerdisabled' => "Chaidh an clàradh a chur à comas le rianaire an t-siostaim",
	'register:fields' => 'Tha gach raon riatanach',

	'registration:notemail' => 'Chan eil coltas seòlaidh puist-d air an t-seòladh a chuir thu ann.',
	'registration:userexists' => 'Cha an t-ainm-cleachdaiche seo ann mar-thà',
	'registration:usernametooshort' => 'Feumaidh an t-ainm-cleachdaiche agad a bhith %u caractaran a dh\'fhaid air a char as lugha.',
	'registration:usernametoolong' => 'Tha an t-ainm-cleachdaiche agad ro fhada. Chan fhaod e bhith nas fhaide na %u c(h)aractar(an).',
	'registration:passwordtooshort' => 'Feumaidh am facal-faire a bhith %u caractaran a dh\'fhaid air a char as lugha.',
	'registration:dupeemail' => 'Chaidh an seòladh puist-d seo a chlàradh cheana.',
	'registration:invalidchars' => 'Tha sinn duilich ach tha an caractar %s san ainm-chleachdaiche agad nach eil dligheach. Seo na caractaran a tha mì-dhligheach: %s',
	'registration:emailnotvalid' => 'Tha sinn duilich ach chan eil an seòladh puist-d a chuir thu a-steach dligheach air an t-siostam seo',
	'registration:passwordnotvalid' => 'Tha sinn duilich ach chan eil am facal-faire a chuir thu a-steach dligheach air an t-siostam seo',
	'registration:usernamenotvalid' => 'Tha sinn duilich ach chan eil an t-ainm-cleachdaiche a chuir thu a-steach dligheach air an t-siostam seo',

	'adduser' => "Cuir cleachdaiche ris",
	'adduser:ok' => "Tha thu air cleachdaiche ùr a chur ris.",
	'adduser:bad' => "Cha b' urrainn dhuinn an cleachdaiche ùr a chruthachadh.",

	'user:set:name' => "Roghainnean airson ainm a' chunntais",
	'user:name:label' => "Ainm taisbeanaidh",
	'user:name:success' => "Chaidh an t-ainm-taisbeanaidh atharrachadh air an t-siostam.",
	'user:name:fail' => "Cha b' urrainn dhuinn an t-ainm-taisbeanaidh atharrachadh air an t-siostam.",

	'user:set:password' => "Facal-faire a' chunntais",
	'user:current_password:label' => 'Am facal-faire làithreach',
	'user:password:label' => "Am facal-faire ùr",
	'user:password2:label' => "Am facal-faire ùr a-rithist",
	'user:password:success' => "Chaidh am facal-faire atharrachadh",
	'user:password:fail' => "Cha b' urrainn dhuinn am facal-faire atharrachadh air an t-siostam.",
	'user:password:fail:notsame' => "Chan eil an dà fhacal-faire co-ionnann!",
	'user:password:fail:tooshort' => "Tha am facal-faire ro ghoirid!",
	'user:password:fail:incorrect_current_password' => 'Chan eil am facal-faire làithreach a chuir thu a-steach mar bu chòir.',
	'user:changepassword:unknown_user' => 'Cleachdaiche mì-dhligheach.',
	'user:changepassword:change_password_confirm' => 'Atharraichidh seo am facal-faire agad.',

	'user:set:language' => "Roghainnean cànain",
	'user:language:label' => "Cànan",
	'user:language:success' => "Chaidh na roghainnean cànain ùrachadh.",
	'user:language:fail' => "Cha b' urrainn dhuinn na roghainnean cànain a shàbhaladh.",

	'user:username:notfound' => 'Cha deach an t-ainm-cleachdaiche %s a lorg.',

	'user:password:lost' => 'Chaill mi am facal-faire agam',
	'user:password:changereq:success' => 'Tha thu air facal-faire ùr iarraidh is chaidh post-d a chur thugad',
	'user:password:changereq:fail' => 'Dh\'fhàillig le iarrtas facail-fhaire ùir.',

	'user:password:text' => 'Gus facal-faire ùr iarraidh, cuir a-steach an t-ainm-cleachdaiche no seòladh puist-d agad gu h-ìosal is briog air a\' phutan "Cuir iarrtas a-steach".',

	'user:persistent' => 'Cuimhnich orm',

	'walled_garden:welcome' => 'Fàilte gu',

/**
 * Administration
 */
	'menu:page:header:administer' => 'Dèan rianachd',
	'menu:page:header:configure' => 'Rèitich',
	'menu:page:header:develop' => 'Leasaich',
	'menu:page:header:default' => 'Eile',

	'admin:view_site' => 'Seall an làrach',
	'admin:loggedin' => 'Clàraichte a-steach mar %s',
	'admin:menu' => 'Clàr-taice',

	'admin:configuration:success' => "Chaidh na roghainnean agad a shàbhaladh.",
	'admin:configuration:fail' => "Cha b' urrainn dhuinn na roghainnean agad a shàbhaladh.",
	'admin:configuration:dataroot:relative_path' => 'Cannot set "%s" as the dataroot because it is not an absolute path.',
	'admin:configuration:default_limit' => 'Feumaidh co-dhiù 1 nì a bhith air gach duilleag.',

	'admin:unknown_section' => 'Earrann rianachd mhì-dhligheach.',

	'admin' => "Rianachd",
	'admin:description' => "The admin panel allows you to control all aspects of the system, from user management to how plugins behave. Choose an option below to get started.",

	'admin:statistics' => "Stadastaireachd",
	'admin:statistics:overview' => 'Foir-shealladh',
	'admin:statistics:server' => 'Fiosrachadh an fhrithealaiche',
	'admin:statistics:cron' => 'Cron',
	'admin:cron:record' => 'Na Cron Jobs as ùire',
	'admin:cron:period' => 'Eadaramh Cron',
	'admin:cron:friendly' => 'An coileanadh mu dheireadh',
	'admin:cron:date' => 'Ceann-là is àm',
	'admin:cron:msg' => 'Teachdaireachd',
	'admin:cron:started' => 'Chaidh na Cron jobs airson "%s" a thòiseachadh %s',
	'admin:cron:complete' => 'Chaidh na Cron jobs airson "%s" a choileanadh %s',

	'admin:appearance' => 'Coltas',
	'admin:administer_utilities' => 'Goireasan',
	'admin:develop_utilities' => 'Goireasan',
	'admin:configure_utilities' => 'Goireasan',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "Cleachdaichean",
	'admin:users:online' => 'Air loidhne an-dràsta',
	'admin:users:newest' => 'As ùire',
	'admin:users:admins' => 'Rianairean',
	'admin:users:add' => 'Cuir cleachdaiche ùr ris',
	'admin:users:description' => "Leigidh a' phanail rianachd seo leat roghainnean nan cleachdaichean a stiùireadh air an làrach agad. Tagh roghainn gu h-ìosal gus tòiseachadh.",
	'admin:users:adduser:label' => "Briog an-seo gus cleachdaiche ùr a chur ris...",
	'admin:users:opt:linktext' => "Rèitich na cleachdaichean...",
	'admin:users:opt:description' => "Rèitich fiosrachadh nan cleachdaichean 's nan cunntasan.",
	'admin:users:find' => 'Lorg',

	'admin:administer_utilities:maintenance' => 'Modh obrach-glèidhidh',
	'admin:upgrades' => 'Àrdachaidhean',

	'admin:settings' => 'Roghainnean',
	'admin:settings:basic' => 'Roghainnean bunasach',
	'admin:settings:advanced' => 'Roghainnean adhartach',
	'admin:site:description' => "Leigidh a' phanail rianachd seo leat roghainnean uile-choitcheann a stiùireadh airson na làraich agad. Tagh roghainn gu h-ìosal gus tòiseachadh.",
	'admin:site:opt:linktext' => "Rèitich an làrach...",
	'admin:settings:in_settings_file' => 'Tha an roghainn seo \'ga rèiteachadh ann an settings.php',

	'admin:legend:security' => 'Tèarainteachd',
	'admin:site:secret:intro' => 'Cleachdaidh Elgg iuchair gus tòcanan tèarainteachd a chruthachadh a chum iomadh adhbhair.',
	'admin:site:secret_regenerated' => "Chaidh rùn-dìomhair na làraich agad ath-ghintinn.",
	'admin:site:secret:regenerate' => "Ath-ghin rùn-dìomhair na làraich",
	'admin:site:secret:regenerate:help' => "An aire: Ma dh'ath-ghineas tu rùn-dìomhair na làraich agad, dh'fhaoidte gun cuir seo cuid dhen luchd-cleachdaidh fo mhì-ghoireas on a nì seo na tòcanan a chleachdte ann am briosgaidean \"cuimhnich orm\", iarrtasan dearbhaidh puist-d, còdaichean cuiridh is msaa. mì-dhligheach.",
	'site_secret:current_strength' => 'Neart na h-iuchrach',
	'site_secret:strength:weak' => "Lag",
	'site_secret:strength_msg:weak' => "Ar leinn bu chòir dhut rùn-dìomhair na làraich agad ath-ghintinn.",
	'site_secret:strength:moderate' => "Meadhanach",
	'site_secret:strength_msg:moderate' => "Mholamaid gun ath-ghin thu rùn-dìomhair na làraich agad a chum tèarainteachd nas fhearr.",
	'site_secret:strength:strong' => "Làidir",
	'site_secret:strength_msg:strong' => "Tha rùn-dìomhair na làraich agad làidir gu leòr. Cha leig thu leas ath-ghintinn.",

	'admin:dashboard' => 'Deas-bhòrd',
	'admin:widget:online_users' => 'Cleachdaichean air loidhne',
	'admin:widget:online_users:help' => 'Seallaidh seo na cleachdaichean a tha an làthair an-dràsta',
	'admin:widget:new_users' => 'Cleachdaichean ùra',
	'admin:widget:new_users:help' => 'Seall na cleachdaichean as ùire',
	'admin:widget:banned_users' => 'Cleachdaichean toirmisgte',
	'admin:widget:banned_users:help' => 'Seall na cleachdaichean toirmisgte',
	'admin:widget:content_stats' => 'Stadastaireachd na susbainte',
	'admin:widget:content_stats:help' => 'Cum sùil air an t-susbaint a chruthaich an luchd-cleachdaidh agad',
	'admin:widget:cron_status' => 'Staid Cron',
	'admin:widget:cron_status:help' => 'Seal an staid air an turas mu dheireadh a chaidh Cron jobs a chrìochnachadh',
	'widget:content_stats:type' => 'Seòrsa na susbainte',
	'widget:content_stats:number' => 'Àireamh',

	'admin:widget:admin_welcome' => 'Fàilte',
	'admin:widget:admin_welcome:help' => "A short introduction to Elgg's admin area",
	'admin:widget:admin_welcome:intro' =>
'Welcome to Elgg! Right now you are looking at the administration dashboard. It\'s useful for tracking what\'s happening on the site.',

	'admin:widget:admin_welcome:admin_overview' =>
"Navigation for the administration area is provided by the menu to the right. It is organized into
three sections:
	<dl>
		<dt>Administer</dt><dd>Everyday tasks like monitoring reported content, checking who is online, and viewing statistics.</dd>
		<dt>Configure</dt><dd>Occasional tasks like setting the site name or activating a plugin.</dd>
		<dt>Develop</dt><dd>For developers who are building plugins or designing themes. (Requires a developer plugin.)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br />Be sure to check out the resources available through the footer links and thank you for using Elgg!',

	'admin:widget:control_panel' => 'Panail-smachd',
	'admin:widget:control_panel:help' => "Bheir seo inntrigeadh gun duilgheadas dha stiùireadh cumanta agad",

	'admin:cache:flush' => 'Falamhaich na tasgadain',
	'admin:cache:flushed' => "Chaidh tasgadain na làraich fhalamhachadh",

	'admin:footer:faq' => 'CÀBHA na rianachd',
	'admin:footer:manual' => 'Leabhar-mìneachaidh na rianachd',
	'admin:footer:community_forums' => 'Bòrd-brath coimhearsnachd Elgg',
	'admin:footer:blog' => 'Blog Elgg',

	'admin:plugins:category:all' => 'A h-uile plugan',
	'admin:plugins:category:active' => 'Plugain ghnìomhach',
	'admin:plugins:category:inactive' => 'Plugain neo-ghnìomhach',
	'admin:plugins:category:admin' => 'Rianachd',
	'admin:plugins:category:bundled' => 'San trusgan',
	'admin:plugins:category:nonbundled' => 'Chan ann san trusgan',
	'admin:plugins:category:content' => 'Susbaint',
	'admin:plugins:category:development' => 'Leasachadh',
	'admin:plugins:category:enhancement' => 'Piseachadh',
	'admin:plugins:category:api' => 'Seirbheis/API',
	'admin:plugins:category:communication' => 'Conaltradh',
	'admin:plugins:category:security' => 'Tèarainteachd is spama',
	'admin:plugins:category:social' => 'Sòisealta',
	'admin:plugins:category:multimedia' => 'Ioma-mheadhanach',
	'admin:plugins:category:theme' => 'Ùrlaran',
	'admin:plugins:category:widget' => 'Widgets',
	'admin:plugins:category:utility' => 'Goireasan',

	'admin:plugins:markdown:unknown_plugin' => 'Plugain nach aithne dhuinn.',
	'admin:plugins:markdown:unknown_file' => 'Faidhle nach aithne dhuinn.',

	'admin:notices:could_not_delete' => 'Cha b\' urrainn dhuinn am brath a sguabadh às.',
	'item:object:admin_notice' => 'Brath na rianachd',

	'admin:options' => 'Roghainnean na rianachd',

/**
 * Plugins
 */

	'plugins:disabled' => 'Chan eil na plugain \'gan luchdadh on a tha faile air a bheil "disabled" sa phasgan mod.',
	'plugins:settings:save:ok' => "Chaidh na roghainnean airson a' phlugan %s a shàbhaladh.",
	'plugins:settings:save:fail' => "Cha deach leinn na roghainnean airson a' phlugan %s a shàbhaladh.",
	'plugins:usersettings:save:ok' => "Chaidh roghainnean a' chleachdaiche airson a' phlugan %s a shàbhaladh.",
	'plugins:usersettings:save:fail' => "Cha deach leinn roghainnean a' chleachdaiche airson a' phlugan %s a shàbhaladh.",
	'item:object:plugin' => 'Plugain',

	'admin:plugins' => "Plugain",
	'admin:plugins:activate_all' => 'Gnìomhaich na h-uile',
	'admin:plugins:deactivate_all' => 'Cuir na h-uile à gnìomh',
	'admin:plugins:activate' => 'Gnìomhaich',
	'admin:plugins:deactivate' => 'Cuir à gnìomh',
	'admin:plugins:description' => "Leigidh a' phanail rianachd seo leat na h-innealan a chaidh a stàladh air an làrach agad a stiùireadh 's a rèiteachadh.",
	'admin:plugins:opt:linktext' => "Rèitich na h-innealan...",
	'admin:plugins:opt:description' => "Rèitich na h-innealan a chaidh a stàladh air an làrach.",
	'admin:plugins:label:id' => "ID",
	'admin:plugins:label:name' => "Ainm",
	'admin:plugins:label:author' => "Ùghdar",
	'admin:plugins:label:copyright' => "Còir-lethbhreac",
	'admin:plugins:label:categories' => 'Roinnean-seòrsa',
	'admin:plugins:label:licence' => "Ceadachas",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "Fiosrachadh",
	'admin:plugins:label:files' => "Faidhlichean",
	'admin:plugins:label:resources' => "Goireasan",
	'admin:plugins:label:screenshots' => "Glacaidhean-sgrìn",
	'admin:plugins:label:repository' => "Còd",
	'admin:plugins:label:bugtracker' => "Dèan aithris air duilgheadas",
	'admin:plugins:label:donate' => "Thoir tìodhlac",
	'admin:plugins:label:moreinfo' => 'barrachd fiosrachaidh',
	'admin:plugins:label:version' => 'Tionndadh',
	'admin:plugins:label:location' => 'Àite',
	'admin:plugins:label:contributors' => 'Com-pàirtichean',
	'admin:plugins:label:contributors:name' => 'Ainm',
	'admin:plugins:label:contributors:email' => 'Post-d',
	'admin:plugins:label:contributors:website' => 'Làrach-lìn',
	'admin:plugins:label:contributors:username' => 'Ainm-cleachdaiche na coimhearsnachd',
	'admin:plugins:label:contributors:description' => 'Tuairisgeul',
	'admin:plugins:label:dependencies' => 'Eisimeileachdan',

	'admin:plugins:warning:unmet_dependencies' => 'Tha eisimeileachd aig a\' phlugan seo nach deach a choileanadh agus cha ghabh a ghnìomhachadh. Thoir sùil air na h-eisimeileachdan fo "barrachd fiosrachaidh".',
	'admin:plugins:warning:invalid' => 'Tha am plugan seo mì-dhligheach: %s',
	'admin:plugins:warning:invalid:check_docs' => 'Thoir sùil air <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">docamaideadh Elgg</a> airson taic le fuasgladh dhuilgheadasan.',
	'admin:plugins:cannot_activate' => 'cha ghabh a ghnìomhachadh',
	'admin:plugins:cannot_deactivate' => 'cha ghabh a chur à gnìomh',
	'admin:plugins:already:active' => 'Tha am plugan/na plugain a thagh thu gnìomhach mar-thà.',
	'admin:plugins:already:inactive' => 'Tha am plugan/na plugain a thagh thu neo-ghnìomhach mar-thà.',

	'admin:plugins:set_priority:yes' => "Chaidh òrdugh ùr a chur air %s.",
	'admin:plugins:set_priority:no' => "Cha b' urrainn dhuinn òrdugh ùr a chur air %s.",
	'admin:plugins:set_priority:no_with_msg' => "Cha b' urrainn dhuinn òrdugh ùr a chur air %s. Mearachd: %s",
	'admin:plugins:deactivate:yes' => "Chaidh %s a chur à gnìomh.",
	'admin:plugins:deactivate:no' => "Cha b' urrainn dhuinn %s a chur à gnìomh.",
	'admin:plugins:deactivate:no_with_msg' => "Cha b' urrainn dhuinn %s a chur à gnìomh. Mearachd: %s",
	'admin:plugins:activate:yes' => "Chaidh %s a ghnìomhachadh.",
	'admin:plugins:activate:no' => "Cha b' urrainn dhuinn %s a ghnìomhachadh.",
	'admin:plugins:activate:no_with_msg' => "Cha b' urrainn dhuinn %s a ghnìomhachadh. Mearachd: %s",
	'admin:plugins:categories:all' => 'A h-uile roinn-seòrsa',
	'admin:plugins:plugin_website' => 'Làrach-lìn a\' phlugain',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'Tionndadh %s',
	'admin:plugin_settings' => 'Roghainnean a\' phlugain',
	'admin:plugins:warning:unmet_dependencies_active' => 'Tha am plugan seo gnìomhach ach tha eisimeileachd aige nach deach a choileanadh. Bi an dùil air duilgheadasan. Thoir sùil air "barrachd fiosrachaidh" gu h-ìosal airson mion-fhiosrachadh.',

	'admin:plugins:dependencies:type' => 'Seòrsa',
	'admin:plugins:dependencies:name' => 'Ainm',
	'admin:plugins:dependencies:expected_value' => 'An luach air an robhar an dùil',
	'admin:plugins:dependencies:local_value' => 'An dearbh luach',
	'admin:plugins:dependencies:comment' => 'Beachd',

	'admin:statistics:description' => "Seo foir-shealladh air stadastaireachd na làraich agad. Ma tha thu feumach air stadastaireachd nas mionaidiche, tha gleus rianachd phroifeiseanta ri fhaighinn.",
	'admin:statistics:opt:description' => "Seall stadastaireachd air cleachdaichean is oibseactan na làraich agad.",
	'admin:statistics:opt:linktext' => "Seall stadastaireachd...",
	'admin:statistics:label:basic' => "Stadastaireachd bhunasach na làraich",
	'admin:statistics:label:numentities' => "Eintiteasan na làraich",
	'admin:statistics:label:numusers' => "Àireamh de chleachdaichean",
	'admin:statistics:label:numonline' => "Àireamh de chleachdaichean air loidhne",
	'admin:statistics:label:onlineusers' => "Cleachdaichean air loidhne an-dràsta",
	'admin:statistics:label:admins'=>"Rianairean",
	'admin:statistics:label:version' => "Tionndadh dhe dh'Elgg",
	'admin:statistics:label:version:release' => "Sgaoileadh",
	'admin:statistics:label:version:version' => "Tionndadh",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'Frithealaiche-lìn',
	'admin:server:label:server' => 'Frithealaiche',
	'admin:server:label:log_location' => 'Ionad an loga',
	'admin:server:label:php_version' => 'Tionndadh dhe PHP',
	'admin:server:label:php_ini' => 'Ionad an fhaidhle PHP ini',
	'admin:server:label:php_log' => 'Loga PHP',
	'admin:server:label:mem_avail' => 'A\' chuimhne ri làimh',
	'admin:server:label:mem_used' => 'Cuimhne \'ga cleachdadh',
	'admin:server:error_log' => "Loga mhearachdan an fhrithealaiche-lìn",
	'admin:server:label:post_max_size' => 'Am meud as motha airson POST',
	'admin:server:label:upload_max_filesize' => 'Am meud as motha airson luchdadh suas',
	'admin:server:warning:post_max_too_small' => '(An aire: feumaidh post_max_size a bhith nas motha na an luach seo gus taic a chur ri luchdadh suas air a\' mheud seo)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Cha deach Memcache a shuidheachadh air an fhrithealaiche seo no cha deach a rèiteachadh ann an Elgg config fhathast.
		Mholamaid gun cuir thu memcache an comas agus gun rèitich thu e a chum piseachaidh air an dèanadas.
	',

	'admin:user:label:search' => "Lorg cleachdaichean:",
	'admin:user:label:searchbutton' => "Lorg",

	'admin:user:ban:no' => "Chan urrainn dhuinn an cleachdaiche a thoirmeasg",
	'admin:user:ban:yes' => "Chaidh an cleachdaiche a thoirmeasg.",
	'admin:user:self:ban:no' => "Chan urrainn dhut thu fhèin a thoirmeasg",
	'admin:user:unban:no' => "Chan urrainn dhuinn an cleachdaiche a dhì-thoirmeasg",
	'admin:user:unban:yes' => "Chaidh an cleachdaiche a dì-thoirmeasg.",
	'admin:user:delete:no' => "Chan urrainn dhuinn an cleachdaiche a sguabadh às",
	'admin:user:delete:yes' => "Chaidh an cleachdaiche %s a sguabadh às",
	'admin:user:self:delete:no' => "Chan urrainn dhut thu fhèin a sguabadh às",

	'admin:user:resetpassword:yes' => "Chaidh am facal-faire ath-shuidheachadh 's brath a chur dhan chleachdaiche.",
	'admin:user:resetpassword:no' => "Cha b' urrainn dhuinn am facal-faire ath-shuidheachadh.",

	'admin:user:makeadmin:yes' => "Tha an cleachdaiche 'na rianaire a-nis.",
	'admin:user:makeadmin:no' => "Cha b' urrainn dhuinn rianaire a dhèanamh dhen chleachdaiche seo.",

	'admin:user:removeadmin:yes' => "Chan eil an cleachdaiche 'na rianaire tuilleadh.",
	'admin:user:removeadmin:no' => "Cha b' urrainn dhuinn ceadan rianaire a thoirt air falbh on chleachdaiche seo.",
	'admin:user:self:removeadmin:no' => "Chan urrainn dhut na ceadan rianaire agad fhèin a thoirt air falbh.",

	'admin:appearance:menu_items' => 'Nithean clàir-thaice',
	'admin:menu_items:configure' => 'Rèitich nithean a\' phrìomh chlàir-thaice',
	'admin:menu_items:description' => 'Tagh dè na nithean a thèid a shealltainn \'nan ceanglaichean brosnaichte. Thèid na nithean nach cleachd thu a shealltainn mar "Barrachd" aig bonn na liosta.',
	'admin:menu_items:hide_toolbar_entries' => 'A bheil thu airson ceanglaichean a thoirt air falbh on chlàr-taice bàr-inneal?',
	'admin:menu_items:saved' => 'Chaidh nithean a\' chlàir-thaice a shàbhaladh.',
	'admin:add_menu_item' => 'Cuir nì clàir-thaice gnàthaichte ris',
	'admin:add_menu_item:description' => 'Lìon ainm-taisbeanaidh \'s URL gus nì gnàthaichte a chur ri clàr-taice na seòladaireachd agad.',

	'admin:appearance:default_widgets' => 'Widgets tùsail',
	'admin:default_widgets:unknown_type' => 'Seòrsa dhe widget nach aithne dhuinn',
	'admin:default_widgets:instructions' => 'Add, remove, position, and configure default widgets for the selected widget page.
These changes will only affect new users on the site.',

	'admin:robots.txt:instructions' => "Deasaich faidhle robots.txt na làraich seo gu h-ìosal",
	'admin:robots.txt:plugins' => "Tha plugain a' cur na leanas ris an fhaidhle robots.txt",
	'admin:robots.txt:subdir' => "Chan obraich an t-inneal robots.txt on a chaidh Elgg a stàladh ann am fo-phasgan",
	'admin:robots.txt:physical' => "Chan obraich an t-inneal robots.txt on a tha robots.txt fiosaigeach an làthair",

	'admin:maintenance_mode:default_message' => 'Tha an làrach seo dùinte ri linn obrach-glèidhidh',
	'admin:maintenance_mode:instructions' => 'Maintenance mode should be used for upgrades and other large changes to the site.
		When it is on, only admins can log in and browse the site.',
	'admin:maintenance_mode:mode_label' => 'Maintenance mode',
	'admin:maintenance_mode:message_label' => 'Message displayed to users when maintenance mode is on',
	'admin:maintenance_mode:saved' => 'The maintenance mode settings were saved.',
	'admin:maintenance_mode:indicator_menu_item' => 'The site is in maintenance mode.',
	'admin:login' => 'Admin Login',

/**
 * User settings
 */

	'usersettings:description' => "The user settings panel allows you to control all your personal settings, from user management to how plugins behave. Choose an option below to get started.",

	'usersettings:statistics' => "Your statistics",
	'usersettings:statistics:opt:description' => "View statistical information about users and objects on your site.",
	'usersettings:statistics:opt:linktext' => "Account statistics",
	
	'usersettings:statistics:login_history' => "Login History",
	'usersettings:statistics:login_history:date' => "Date",
	'usersettings:statistics:login_history:ip' => "IP Address",

	'usersettings:user' => "%s's settings",
	'usersettings:user:opt:description' => "This allows you to control user settings.",
	'usersettings:user:opt:linktext' => "Change your settings",

	'usersettings:plugins' => "Tools",
	'usersettings:plugins:opt:description' => "Configure settings (if any) for your active tools.",
	'usersettings:plugins:opt:linktext' => "Configure your tools",

	'usersettings:plugins:description' => "This panel allows you to control and configure the personal settings for the tools installed by your system administrator.",
	'usersettings:statistics:label:numentities' => "Your content",

	'usersettings:statistics:yourdetails' => "Your details",
	'usersettings:statistics:label:name' => "Full name",
	'usersettings:statistics:label:email' => "Email",
	'usersettings:statistics:label:membersince' => "Member since",
	'usersettings:statistics:label:lastlogin' => "Last logged in",

/**
 * Activity river
 */

	'river:all' => 'All Site Activity',
	'river:mine' => 'My Activity',
	'river:owner' => 'Activity of %s',
	'river:friends' => 'Friends Activity',
	'river:select' => 'Show %s',
	'river:comments:more' => '+%u more',
	'river:comments:all' => 'View all %u comments',
	'river:generic_comment' => 'commented on %s %s',

	'friends:widget:description' => "Displays some of your friends.",
	'friends:num_display' => "Number of friends to display",
	'friends:icon_size' => "Icon size",
	'friends:tiny' => "tiny",
	'friends:small' => "small",

/**
 * Icons
 */

	'icon:size' => "Icon size",
	'icon:size:topbar' => "Topbar",
	'icon:size:tiny' => "Tiny",
	'icon:size:small' => "Small",
	'icon:size:medium' => "Medium",
	'icon:size:large' => "Large",
	'icon:size:master' => "Extra Large",

/**
 * Generic action words
 */

	'save' => "Save",
	'reset' => 'Reset',
	'publish' => "Publish",
	'cancel' => "Cancel",
	'saving' => "Saving ...",
	'update' => "Update",
	'preview' => "Preview",
	'edit' => "Edit",
	'delete' => "Delete",
	'accept' => "Accept",
	'reject' => "Reject",
	'decline' => "Decline",
	'approve' => "Approve",
	'activate' => "Activate",
	'deactivate' => "Deactivate",
	'disapprove' => "Disapprove",
	'revoke' => "Revoke",
	'load' => "Load",
	'upload' => "Upload",
	'download' => "Download",
	'ban' => "Ban",
	'unban' => "Unban",
	'banned' => "Banned",
	'enable' => "Enable",
	'disable' => "Disable",
	'request' => "Cuir iarrtas a-steach",
	'complete' => "Complete",
	'open' => 'Open',
	'close' => 'Close',
	'hide' => 'Hide',
	'show' => 'Show',
	'reply' => "Reply",
	'more' => 'More',
	'more_info' => 'More info',
	'comments' => 'Comments',
	'import' => 'Import',
	'export' => 'Export',
	'untitled' => 'Untitled',
	'help' => 'Help',
	'send' => 'Send',
	'post' => 'Post',
	'submit' => 'Submit',
	'comment' => 'Comment',
	'upgrade' => 'Upgrade',
	'sort' => 'Sort',
	'filter' => 'Filter',
	'new' => 'New',
	'add' => 'Add',
	'create' => 'Create',
	'remove' => 'Remove',
	'revert' => 'Revert',

	'site' => 'Site',
	'activity' => 'Activity',
	'members' => 'Members',
	'menu' => 'Menu',

	'up' => 'Up',
	'down' => 'Down',
	'top' => 'Top',
	'bottom' => 'Bottom',
	'right' => 'Right',
	'left' => 'Left',
	'back' => 'Back',

	'invite' => "Invite",

	'resetpassword' => "Reset password",
	'changepassword' => "Change password",
	'makeadmin' => "Make admin",
	'removeadmin' => "Remove admin",

	'option:yes' => "Yes",
	'option:no' => "No",

	'unknown' => 'Unknown',
	'never' => 'Never',

	'active' => 'Active',
	'total' => 'Total',

	'ok' => 'OK',
	'any' => 'Any',
	'error' => 'Error',

	'other' => 'Other',
	'options' => 'Options',
	'advanced' => 'Advanced',

	'learnmore' => "Click here to learn more.",
	'unknown_error' => 'Unknown error',

	'content' => "content",
	'content:latest' => 'Latest activity',
	'content:latest:blurb' => 'Alternatively, click here to view the latest content from across the site.',

	'link:text' => 'view link',

/**
 * Generic questions
 */

	'question:areyousure' => 'Are you sure?',

/**
 * Status
 */

	'status' => 'Status',
	'status:unsaved_draft' => 'Unsaved Draft',
	'status:draft' => 'Draft',
	'status:unpublished' => 'Unpublished',
	'status:published' => 'Published',
	'status:featured' => 'Featured',
	'status:open' => 'Open',
	'status:closed' => 'Closed',

/**
 * Generic sorts
 */

	'sort:newest' => 'Newest',
	'sort:popular' => 'Popular',
	'sort:alpha' => 'Alphabetical',
	'sort:priority' => 'Priority',

/**
 * Generic data words
 */

	'title' => "Title",
	'description' => "Description",
	'tags' => "Tags",
	'all' => "All",
	'mine' => "Mine",

	'by' => 'by',
	'none' => 'none',

	'annotations' => "Annotations",
	'relationships' => "Relationships",
	'metadata' => "Metadata",
	'tagcloud' => "Tag cloud",

	'on' => 'On',
	'off' => 'Off',

/**
 * Entity actions
 */

	'edit:this' => 'Edit this',
	'delete:this' => 'Delete this',
	'comment:this' => 'Comment on this',

/**
 * Input / output strings
 */

	'deleteconfirm' => "Are you sure you want to delete this item?",
	'deleteconfirm:plural' => "Are you sure you want to delete these items?",
	'fileexists' => "A file has already been uploaded. To replace it, select it below:",

/**
 * User add
 */

	'useradd:subject' => 'User account created',
	'useradd:body' => '
%s,

A user account has been created for you at %s. To log in, visit:

%s

And log in with these user credentials:

Username: %s
Password: %s

Once you have logged in, we highly recommend that you change your password.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "click to dismiss",


/**
 * Import / export
 */

	'importsuccess' => "Import of data was successful",
	'importfail' => "OpenDD import of data failed.",

/**
 * Time
 */

	'friendlytime:justnow' => "just now",
	'friendlytime:minutes' => "%s minutes ago",
	'friendlytime:minutes:singular' => "a minute ago",
	'friendlytime:hours' => "%s hours ago",
	'friendlytime:hours:singular' => "an hour ago",
	'friendlytime:days' => "%s days ago",
	'friendlytime:days:singular' => "yesterday",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'friendlytime:future:minutes' => "in %s minutes",
	'friendlytime:future:minutes:singular' => "in a minute",
	'friendlytime:future:hours' => "in %s hours",
	'friendlytime:future:hours:singular' => "in an hour",
	'friendlytime:future:days' => "in %s days",
	'friendlytime:future:days:singular' => "tomorrow",

	'date:month:01' => 'January %s',
	'date:month:02' => 'February %s',
	'date:month:03' => 'March %s',
	'date:month:04' => 'April %s',
	'date:month:05' => 'May %s',
	'date:month:06' => 'June %s',
	'date:month:07' => 'July %s',
	'date:month:08' => 'August %s',
	'date:month:09' => 'September %s',
	'date:month:10' => 'October %s',
	'date:month:11' => 'November %s',
	'date:month:12' => 'December %s',
	
	'date:month:short:01' => 'Jan %s',
	'date:month:short:02' => 'Feb %s',
	'date:month:short:03' => 'Mar %s',
	'date:month:short:04' => 'Apr %s',
	'date:month:short:05' => 'May %s',
	'date:month:short:06' => 'Jun %s',
	'date:month:short:07' => 'Jul %s',
	'date:month:short:08' => 'Aug %s',
	'date:month:short:09' => 'Sep %s',
	'date:month:short:10' => 'Oct %s',
	'date:month:short:11' => 'Nov %s',
	'date:month:short:12' => 'Dec %s',

	'date:weekday:0' => 'Sunday',
	'date:weekday:1' => 'Monday',
	'date:weekday:2' => 'Tuesday',
	'date:weekday:3' => 'Wednesday',
	'date:weekday:4' => 'Thursday',
	'date:weekday:5' => 'Friday',
	'date:weekday:6' => 'Saturday',

	'date:weekday:short:0' => 'Sun',
	'date:weekday:short:1' => 'Mon',
	'date:weekday:short:2' => 'Tue',
	'date:weekday:short:3' => 'Wed',
	'date:weekday:short:4' => 'Thu',
	'date:weekday:short:5' => 'Fri',
	'date:weekday:short:6' => 'Sat',

	'interval:minute' => 'Every minute',
	'interval:fiveminute' => 'Every five minutes',
	'interval:fifteenmin' => 'Every fifteen minutes',
	'interval:halfhour' => 'Every half hour',
	'interval:hourly' => 'Hourly',
	'interval:daily' => 'Daily',
	'interval:weekly' => 'Weekly',
	'interval:monthly' => 'Monthly',
	'interval:yearly' => 'Yearly',
	'interval:reboot' => 'On reboot',

/**
 * System settings
 */

	'installation:sitename' => "The name of your site:",
	'installation:sitedescription' => "Short description of your site (optional):",
	'installation:wwwroot' => "The site URL:",
	'installation:path' => "The full path of the Elgg installation:",
	'installation:dataroot' => "The full path of the data directory:",
	'installation:dataroot:warning' => "You must create this directory manually. It should be in a different directory to your Elgg installation.",
	'installation:sitepermissions' => "The default access permissions:",
	'installation:language' => "The default language for your site:",
	'installation:debug' => "Control the amount of information written to the server's log.",
	'installation:debug:label' => "Log level:",
	'installation:debug:none' => 'Turn off logging (recommended)',
	'installation:debug:error' => 'Log only critical errors',
	'installation:debug:warning' => 'Log errors and warnings',
	'installation:debug:notice' => 'Log all errors, warnings and notices',
	'installation:debug:info' => 'Log everything',

	// Walled Garden support
	'installation:registration:description' => 'User registration is enabled by default. Turn this off if you do not want people to register on their own.',
	'installation:registration:label' => 'Allow new users to register',
	'installation:walled_garden:description' => 'Enable this to prevent non-members from viewing the site except for web pages marked as public (such as login and registration).',
	'installation:walled_garden:label' => 'Restrict pages to logged-in users',

	'installation:view' => "Enter the view which will be used as the default for your site or leave this blank for the default view (if in doubt, leave as default):",

	'installation:siteemail' => "Site email address (used when sending system emails):",
	'installation:default_limit' => "Default number of items per page",

	'admin:site:access:warning' => "This is the privacy setting suggested to users when they create new content. Changing it does not change access to content.",
	'installation:allow_user_default_access:description' => "Enable this to allow users to set their own suggested privacy setting that overrides the system suggestion.",
	'installation:allow_user_default_access:label' => "Allow user default access",

	'installation:simplecache:description' => "The simple cache increases performance by caching static content including some CSS and JavaScript files.",
	'installation:simplecache:label' => "Use simple cache (recommended)",

	'installation:cache_symlink:description' => "The symbolic link to the simple cache directory allows the server to serve static views bypassing the engine, which considerably improves performance and reduces the server load",
	'installation:cache_symlink:label' => "Use symbolic link to simple cache directory (recommended)",
	'installation:cache_symlink:warning' => "Symbolic link has been established. If, for some reason, you want to remove the link, delete the symbolic link directory from your server",
	'installation:cache_symlink:paths' => 'Correctly configured symbolic link must link <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "Due to your server configuration the symbolic link can not be established automatically. Please refer to the documentation and establish the symbolic link manually.",

	'installation:minify:description' => "The simple cache can also improve performance by compressing JavaScript and CSS files. (Requires that simple cache is enabled.)",
	'installation:minify_js:label' => "Compress JavaScript (recommended)",
	'installation:minify_css:label' => "Compress CSS (recommended)",

	'installation:htaccess:needs_upgrade' => "You must update your .htaccess file so that the path is injected into the GET parameter __elgg_uri (you can use install/config/htaccess.dist as a guide).",
	'installation:htaccess:localhost:connectionfailed' => "Elgg cannot connect to itself to test rewrite rules properly. Check that curl is working and there are no IP restrictions preventing localhost connections.",

	'installation:systemcache:description' => "The system cache decreases the loading time of Elgg by caching data to files.",
	'installation:systemcache:label' => "Use system cache (recommended)",

	'admin:legend:system' => 'System',
	'admin:legend:caching' => 'Caching',
	'admin:legend:content_access' => 'Content Access',
	'admin:legend:site_access' => 'Site Access',
	'admin:legend:debug' => 'Debugging and Logging',

	'upgrading' => 'Upgrading...',
	'upgrade:core' => 'Your Elgg installation was upgraded.',
	'upgrade:unlock' => 'Unlock upgrade',
	'upgrade:unlock:confirm' => "The database is locked for another upgrade. Running concurrent upgrades is dangerous. You should only continue if you know there is not another upgrade running. Unlock?",
	'upgrade:locked' => "Cannot upgrade. Another upgrade is running. To clear the upgrade lock, visit the Admin section.",
	'upgrade:unlock:success' => "Upgrade unlocked successfully.",
	'upgrade:unable_to_upgrade' => 'Unable to upgrade.',
	'upgrade:unable_to_upgrade_info' =>
		'This installation cannot be upgraded because legacy views
		were detected in the Elgg core views directory. These views have been deprecated and need to be
		removed for Elgg to function correctly. If you have not made changes to Elgg core, you can
		simply delete the views directory and replace it with the one from the latest
		package of Elgg downloaded from <a href="http://elgg.org">elgg.org</a>.<br /><br />

		If you need detailed instructions, please visit the <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
		Upgrading Elgg documentation</a>.  If you require assistance, please post to the
		<a href="http://community.elgg.org/pg/groups/discussion/">Community Support Forums</a>.',

	'update:twitter_api:deactivated' => 'Twitter API (previously Twitter Service) was deactivated during the upgrade. Please activate it manually if required.',
	'update:oauth_api:deactivated' => 'OAuth API (previously OAuth Lib) was deactivated during the upgrade.  Please activate it manually if required.',
	'upgrade:site_secret_warning:moderate' => "You are encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",
	'upgrade:site_secret_warning:weak' => "You are strongly encouraged to regenerate your site key to improve system security. See Configure &gt; Settings &gt; Advanced",

	'deprecated:function' => '%s() was deprecated by %s()',

	'admin:pending_upgrades' => 'The site has pending upgrades that require your immediate attention.',
	'admin:view_upgrades' => 'View pending upgrades.',
	'item:object:elgg_upgrade' => 'Site upgrades',
	'admin:upgrades:none' => 'Your installation is up to date!',

	'upgrade:item_count' => 'There are <b>%s</b> items that need to be upgraded.',
	'upgrade:warning' => '<b>Warning:</b> On a large site this upgrade may take a significantly long time!',
	'upgrade:success_count' => 'Upgraded:',
	'upgrade:error_count' => 'Errors:',
	'upgrade:river_update_failed' => 'Failed to update the river entry for item id %s',
	'upgrade:timestamp_update_failed' => 'Failed to update the timestamps for item id %s',
	'upgrade:finished' => 'Upgrade finished',
	'upgrade:finished_with_errors' => '<p>Upgrade finished with errors. Refresh the page and try running the upgrade again.</p></p><br />If the error recurs, check the server error log for possible cause. You can seek help for fixing the error from the <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">Technical support group</a> in the Elgg community.</p>',

	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'Align database GUID columns',
	
/**
 * Welcome
 */

	'welcome' => "Welcome",
	'welcome:user' => 'Welcome %s',

/**
 * Emails
 */

	'email:from' => 'From',
	'email:to' => 'To',
	'email:subject' => 'Subject',
	'email:body' => 'Body',

	'email:settings' => "Email settings",
	'email:address:label' => "Email address",

	'email:save:success' => "Chaidh an seòladh puist-d ùr a shàbhaladh. Tha dearbhadh 'ga iarraidh.",
	'email:save:fail' => "New email address could not be saved.",

	'friend:newfriend:subject' => "%s has made you a friend!",
	'friend:newfriend:body' => "%s has made you a friend!

To view their profile, click here:

%s

Please do not reply to this email.",

	'email:changepassword:subject' => "Password changed!",
	'email:changepassword:body' => "Hi %s,

Your password has been changed.",

	'email:resetpassword:subject' => "Password reset!",
	'email:resetpassword:body' => "Hi %s,

Your password has been reset to: %s",

	'email:changereq:subject' => "Iarrtas air atharrachadh facail-fhaire.",
	'email:changereq:body' => "%s, a charaid,

Dh'iarr cuideigin (on t-seòladh IP %s) gun dèid am facal-faire air a' chunntas aca atharrachadh.

Mas e tusa a dh'iarr seo, briog air a' cheangal gu h-ìosal. Mur e, leig seachad am post-d seo.

%s
",

/**
 * user default access
 */

	'default_access:settings' => "Your default access level",
	'default_access:label' => "Default access",
	'user:default_access:success' => "Your new default access level was saved.",
	'user:default_access:failure' => "Your new default access level could not be saved.",

/**
 * Comments
 */

	'comments:count' => "%s comments",
	'item:object:comment' => 'Comments',

	'river:comment:object:default' => '%s commented on %s',

	'generic_comments:add' => "Leave a comment",
	'generic_comments:edit' => "Edit comment",
	'generic_comments:post' => "Post comment",
	'generic_comments:text' => "Comment",
	'generic_comments:latest' => "Latest comments",
	'generic_comment:posted' => "Your comment was successfully posted.",
	'generic_comment:updated' => "The comment was successfully updated.",
	'generic_comment:deleted' => "The comment was successfully deleted.",
	'generic_comment:blank' => "Sorry, you need to actually put something in your comment before we can save it.",
	'generic_comment:notfound' => "Sorry, we could not find the specified comment.",
	'generic_comment:notfound_fallback' => "Sorry, we could not find the specified comment, but we've forwarded you to the page where it was left.",
	'generic_comment:notdeleted' => "Sorry, we could not delete this comment.",
	'generic_comment:failure' => "An unexpected error occurred when saving the comment.",
	'generic_comment:none' => 'No comments',
	'generic_comment:title' => 'Comment by %s',
	'generic_comment:on' => '%s on %s',
	'generic_comments:latest:posted' => 'posted a',

	'generic_comment:email:subject' => 'You have a new comment!',
	'generic_comment:email:body' => "You have a new comment on your item \"%s\" from %s. It reads:


%s


To reply or view the original item, click here:

%s

To view %s's profile, click here:

%s

Please do not reply to this email.",

/**
 * Entities
 */

	'byline' => 'By %s',
	'byline:ingroup' => 'in the group %s',
	'entity:default:strapline' => 'Created %s by %s',
	'entity:default:missingsupport:popup' => 'This entity cannot be displayed correctly. This may be because it requires support provided by a plugin that is no longer installed.',

	'entity:delete:item' => 'Item',
	'entity:delete:item_not_found' => 'Item not found.',
	'entity:delete:permission_denied' => 'You do not have permissions to delete this item.',
	'entity:delete:success' => '%s has been deleted.',
	'entity:delete:fail' => '%s could not be deleted.',

	'entity:can_delete:invaliduser' => 'Cannot check canDelete() for user_guid [%s] as the user does not exist.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'Form is missing __token or __ts fields',
	'actiongatekeeper:tokeninvalid' => "The page you were using had expired. Please try again.",
	'actiongatekeeper:timeerror' => 'The page you were using has expired. Please refresh and try again.',
	'actiongatekeeper:pluginprevents' => 'Sorry. Your form could not be submitted for an unknown reason.',
	'actiongatekeeper:uploadexceeded' => 'The size of file(s) uploaded exceeded the limit set by your site administrator',
	'actiongatekeeper:crosssitelogin' => "Sorry, logging in from a different domain is not permitted. Please try again.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'and, the, then, but, she, his, her, him, one, not, also, about, now, hence, however, still, likewise, otherwise, therefore, conversely, rather, consequently, furthermore, nevertheless, instead, meanwhile, accordingly, this, seems, what, whom, whose, whoever, whomever',

/**
 * Tag labels
 */

	'tag_names:tags' => 'Tags',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'Failed to contact %s. You may experience problems saving content. Please refresh this page.',
	'js:security:token_refreshed' => 'Connection to %s restored!',
	'js:lightbox:current' => "image %s of %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "Powered by Elgg",

/**
 * Languages according to ISO 639-1 (with a couple of exceptions)
 */

	"aa" => "Afar",
	"ab" => "Abkhazian",
	"af" => "Afrikaans",
	"am" => "Amharic",
	"ar" => "Arabic",
	"as" => "Assamese",
	"ay" => "Aymara",
	"az" => "Azerbaijani",
	"ba" => "Bashkir",
	"be" => "Byelorussian",
	"bg" => "Bulgarian",
	"bh" => "Bihari",
	"bi" => "Bislama",
	"bn" => "Bengali; Bangla",
	"bo" => "Tibetan",
	"br" => "Breton",
	"ca" => "Catalan",
	"cmn" => "Mandarin Chinese", // ISO 639-3
	"co" => "Corsican",
	"cs" => "Czech",
	"cy" => "Welsh",
	"da" => "Danish",
	"de" => "German",
	"dz" => "Bhutani",
	"el" => "Greek",
	"en" => "English",
	"eo" => "Esperanto",
	"es" => "Spanish",
	"et" => "Estonian",
	"eu" => "Basque",
	"eu_es" => "Basque (Spain)",
	"fa" => "Persian",
	"fi" => "Finnish",
	"fj" => "Fiji",
	"fo" => "Faeroese",
	"fr" => "French",
	"fy" => "Frisian",
	"ga" => "Irish",
	"gd" => "Scots / Gaelic",
	"gl" => "Galician",
	"gn" => "Guarani",
	"gu" => "Gujarati",
	"he" => "Hebrew",
	"ha" => "Hausa",
	"hi" => "Hindi",
	"hr" => "Croatian",
	"hu" => "Hungarian",
	"hy" => "Armenian",
	"ia" => "Interlingua",
	"id" => "Indonesian",
	"ie" => "Interlingue",
	"ik" => "Inupiak",
	//"in" => "Indonesian",
	"is" => "Icelandic",
	"it" => "Italian",
	"iu" => "Inuktitut",
	"iw" => "Hebrew (obsolete)",
	"ja" => "Japanese",
	"ji" => "Yiddish (obsolete)",
	"jw" => "Javanese",
	"ka" => "Georgian",
	"kk" => "Kazakh",
	"kl" => "Greenlandic",
	"km" => "Cambodian",
	"kn" => "Kannada",
	"ko" => "Korean",
	"ks" => "Kashmiri",
	"ku" => "Kurdish",
	"ky" => "Kirghiz",
	"la" => "Latin",
	"ln" => "Lingala",
	"lo" => "Laothian",
	"lt" => "Lithuanian",
	"lv" => "Latvian/Lettish",
	"mg" => "Malagasy",
	"mi" => "Maori",
	"mk" => "Macedonian",
	"ml" => "Malayalam",
	"mn" => "Mongolian",
	"mo" => "Moldavian",
	"mr" => "Marathi",
	"ms" => "Malay",
	"mt" => "Maltese",
	"my" => "Burmese",
	"na" => "Nauru",
	"ne" => "Nepali",
	"nl" => "Dutch",
	"no" => "Norwegian",
	"oc" => "Occitan",
	"om" => "(Afan) Oromo",
	"or" => "Oriya",
	"pa" => "Punjabi",
	"pl" => "Polish",
	"ps" => "Pashto / Pushto",
	"pt" => "Portuguese",
	"pt_br" => "Portuguese (Brazil)",
	"qu" => "Quechua",
	"rm" => "Rhaeto-Romance",
	"rn" => "Kirundi",
	"ro" => "Romanian",
	"ro_ro" => "Romanian (Romania)",
	"ru" => "Russian",
	"rw" => "Kinyarwanda",
	"sa" => "Sanskrit",
	"sd" => "Sindhi",
	"sg" => "Sangro",
	"sh" => "Serbo-Croatian",
	"si" => "Singhalese",
	"sk" => "Slovak",
	"sl" => "Slovenian",
	"sm" => "Samoan",
	"sn" => "Shona",
	"so" => "Somali",
	"sq" => "Albanian",
	"sr" => "Serbian",
	"sr_latin" => "Serbian (Latin)",
	"ss" => "Siswati",
	"st" => "Sesotho",
	"su" => "Sundanese",
	"sv" => "Swedish",
	"sw" => "Swahili",
	"ta" => "Tamil",
	"te" => "Tegulu",
	"tg" => "Tajik",
	"th" => "Thai",
	"ti" => "Tigrinya",
	"tk" => "Turkmen",
	"tl" => "Tagalog",
	"tn" => "Setswana",
	"to" => "Tonga",
	"tr" => "Turkish",
	"ts" => "Tsonga",
	"tt" => "Tatar",
	"tw" => "Twi",
	"ug" => "Uigur",
	"uk" => "Ukrainian",
	"ur" => "Urdu",
	"uz" => "Uzbek",
	"vi" => "Vietnamese",
	"vo" => "Volapuk",
	"wo" => "Wolof",
	"xh" => "Xhosa",
	//"y" => "Yiddish",
	"yi" => "Yiddish",
	"yo" => "Yoruba",
	"za" => "Zuang",
	"zh" => "Chinese",
	"zh_hans" => "Chinese Simplified",
	"zu" => "Zulu",

	"field:required" => 'Required',

);
