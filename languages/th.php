<?php
/**
			* Translator: Pisan chueachatchai <webmaster@lungkao.com>
			* Language-Team: Laithai http://elgg.in.th
			* Language: Thai
			* Country: THAILAND
			* Version: 1.0
			* Creation-Date: 2008-09-28 15:19+0700
		 */
	$thai = array(

		/**
		 * Sites
		 */

			'item:site' => 'ไซต์',

		/**
		 * Sessions
		 */

			'login' => "เข้าสู่ระบบ",
			'loginok' => "คุณกำลังเข้าสู่ระบบ",
'loginerror' => "เราไม่สามารถให้คุณเข้าสู่ระบบได้เนื่องจากคุณยังไม่ยืนยันตัวตนผ่านอีเมลที่สมัคร",

			'logout' => "ออกจากระบบ",
			'logoutok' => "คุณออกจากระบบแล้ว",
			'logouterror' => "เราไม่สามารถให้คุณออกจากระบบได้ กรุณาลองใหม่",

		/**
		 * Errors
		 */
			'exception:title' => "ยินดีต้อนรับสู่ Elgg",

			'InstallationException:CantCreateSite' => "ไม่สามารถสร้างภายใต้ชื่อ:%s, Url: %s ได้",

			'actionundefined' => "การกระทำของ (%s) ไม่สามารถทำงานได้",
			'actionloggedout' => "เสียใจด้วย, คุณไม่สามารถทำได้ขณะที่คุณออกจากระบบแล้ว",

			'notfound' => "คุณไม่สามารถเข้าถึงได้ ตรวจสอบว่าคุณมีสิทธิที่จะเข้าใช้หรือไม่",

			'SecurityException:Codeblock' => "คุณไม่ได้รับอนุญาติให้เข้าถึง",
			'DatabaseException:WrongCredentials' => "ระบบไม่สามารถติดต่อฐานข้อมูล %s@%s (pw: %s)ได้",
			'DatabaseException:NoConnect' => "ระบบไม่สามารถเลือกใช้ฐานข้อมูล '%s'ได้, โปรดตรวจสอบว่า",
			'SecurityException:FunctionDenied' => "การเข้าใช้ '%s' ไม่ได้ระบอนุญาติ",
			'DatabaseException:DBSetupIssues' => "ใส่หมายเลขที่ต้องการ: ",
			'DatabaseException:ScriptNotFound' => "ระบบหาสคริป %s ไม่เจอ",

			'IOException:FailedToLoadGUID' => "มีข้อผิดพลาด %s จาก GUID:%d",
			'InvalidParameterException:NonElggObject' => "Passing a non-ElggObject to an ElggObject constructor!",
			'InvalidParameterException:UnrecognisedValue' => "Unrecognised value passed to constuctor.",

			'InvalidClassException:NotValidElggStar' => "GUID:%d ไม่ถูกต้อง %s",

			'PluginException:MisconfiguredPlugin' => "%s ไม่สามารถตั้งค่าปลั๊กอินได้เนื่องจากหาไฟล์ไม่เจอ",

			'InvalidParameterException:NonElggUser' => "ผ่าน non-ElggUser การสร้าง!",

			'InvalidParameterException:NonElggSite' => "ผ่าน non-ElggSite tการสร้างระบบ!",

			'InvalidParameterException:NonElggGroup' => "ผ่าน non-ElggGroup การสร้างกลุ่ม!",

			'IOException:UnableToSaveNew' => "ไม่สามารถบันทึก %s",

			'InvalidParameterException:GUIDNotForExport' => "GUID ไม่สามารถนำออกมาได้",
			'InvalidParameterException:NonArrayReturnValue' => "ผ่าน  non-array พารามิเตอร์",

			'ConfigurationException:NoCachePath' => "ไม่ได้ตั้งค่าพาทของแคช!",
			'IOException:NotDirectory' => "%s ไม่มีไดแรกเทอรี่",

			'IOException:BaseEntitySaveFailed' => "ไม่สามารถบันทึกได้!",
			'InvalidParameterException:UnexpectedODDClass' => "นำเข้า() ผ่านและ unexpected ODD class",
			'InvalidParameterException:EntityTypeNotSet' => "ตั้งค่าการกระทำแล้ว",

			'ClassException:ClassnameNotClass' => "%s ไม่ใช่ %s.",
			'ClassNotFoundException:MissingClass' => "คลาส '%s' หาปลั๊กอินไม่เจอ?",
			'InstallationException:TypeNotSupported' => "ไฟล์ %s ไม่สนับสนุน",

			'ImportException:ImportFailed' => "ไม่สามารถนำเข้า %d",
			'ImportException:ProblemSaving' => "มีปัญหาในการบันทึก %s",
			'ImportException:NoGUID' => "ไม่มี GUID, แต่ก็ไม่เป็นปัญหา",

			'ImportException:GUIDNotFound' => "Entity '%d' ไม่มี",
			'ImportException:ProblemUpdatingMeta' => "ไม่สามารถอัพเดต '%s' ใน '%d'",

			'ExportException:NoSuchEntity' => "ไม่มี GUID:%d",

			'ImportException:NoODDElements' => "ไม่มี OpenDD การนำเข้าผิดพลาด",
			'ImportException:NotAllImported' => "ไม่สามารถนำเข้าได้",

			'InvalidParameterException:UnrecognisedFileMode' => "ไม่สามารถทำได้ที่โหมดไฟล์ '%s'",
			'InvalidParameterException:MissingOwner' => "ทุกไฟล์มีเจ้าของ!",
			'IOException:CouldNotMake' => "ไม่สามารถสร้าง %s",
			'IOException:MissingFileName' => "คุณต้องมีชื่อไฟล์เพื่อใช้ในการเปิด",
            'ClassNotFoundException:NotFoundNotSavedWithFile' => "ไฟลืไม่สามารถจัดเก็บได้!",
			'NotificationException:NoNotificationMethod' => "ไม่ มีบางอย่างไม่สมบูรณ์",
			'NotificationException:NoHandlerFound' => "ไม่มีหัว '%s' หรือเรียกไม่ได้",
			'NotificationException:ErrorNotifyingGuid' => "มีข้อผิดพลาดการประกาศ %d",
			'NotificationException:NoEmailAddress' => "ไม่สามารถเข้าใจ GUID:%d",
			'NotificationException:MissingParameter' => "มีข้อผิดพลาดการร้องขอ, '%s'",

			'DatabaseException:WhereSetNonQuery' => "Where set contains non WhereQueryComponent",
			'DatabaseException:SelectFieldsMissing' => "หาฟิลไม่เจอ",
			'DatabaseException:UnspecifiedQueryType' => "Unrecognised or unspecified query type.",
			'DatabaseException:NoTablesSpecified' => "ไม่มีตารางเพื่อดึงข้อมูล",
			'DatabaseException:NoACL' => "ไม่สามารถเข้าถึงได้",

			'InvalidParameterException:NoEntityFound' => "ไม่มีสิทธิ์ในการเข้ถึงข้อมูล",

			'InvalidParameterException:GUIDNotFound' => "GUID:%s ไม่มี หรือคุณไม่มีสิทธิเข้าถึงข้อมูล",
			'InvalidParameterException:IdNotExistForGUID' => "เสียใจด้วย, '%s' ไม่สามารถออกจาก guid:%d ได้",
			'InvalidParameterException:CanNotExportType' => "เสียใจด้วย, Iไม่สามารถนำออก '%s'ได้",
			'InvalidParameterException:NoDataFound' => "ไม่มีฟิลข้อมูล",
			'InvalidParameterException:DoesNotBelong' => "คุณไม่ได้เป็นสมาชิกของการกระทำนี้",
			'InvalidParameterException:DoesNotBelongOrRefer' => "คุณไม่ได้เป็นสมาชิกของการกระทำนี้",
			'InvalidParameterException:MissingParameter' => "พารามิเตอร์ไม่มี, คุณต้องจัดเตียมสำหรับ GUID.",

			'SecurityException:APIAccessDenied' => "เสียใจด้วย, API ตัวนี้ถูกจำกัดการเข้าถึงโดยผู้ดูแล",
			'SecurityException:NoAuthMethods' => "ไม่สามารถเข้าถึงข้อมูลได้แต่ API ต้องการเข้าถึง",
			'APIException:ApiResultUnknown' => "API ไม่สามารถแสดงผลได้",

			'ConfigurationException:NoSiteID' => "ไม่มี ID ",
			'InvalidParameterException:UnrecognisedMethod' => "ไม่สามารถเรียก '%s'",
			'APIException:MissingParameterInMethod' => "ไม่มีพารามิเตอร์ %s ใน %s",
			'APIException:ParameterNotArray' => "%s ไม่สามารถแสดงได้ ตามลำดับ",
			'APIException:UnrecognisedTypeCast' => "ไม่สามารคำนวณ %s สำหรับ '%s' ใน '%s'",
			'APIException:InvalidParameter' => "ไม่มีพารามิเตอร์ '%s' ใน '%s'.",
			'APIException:FunctionParseError' => "%s(%s) มีข้อผิดพลาด",
			'APIException:FunctionNoReturn' => "%s(%s) ไม่มีค่ากลับมา",
			'SecurityException:AuthTokenExpired' => "หารส่งค่าหมดอายุ",
			'CallException:InvalidCallMethod' => "%s ไม่สามารถเรียก '%s'",
			'APIException:MethodCallNotImplemented' => "การเรียก '%s' ไม่สามารถแสดงได้",
			'APIException:AlgorithmNotSupported' => "Algorithm '%s' ไม่รองรับหรือถูกปิดอยู่",
			'ConfigurationException:CacheDirNotSet' => "ไดแรกเทอรี่แคช 'cache_path'ไม่ได้เตียมไว้ ",
			'APIException:NotGetOrPost' => "การร้องขอ GET หรือ POST",
			'APIException:MissingAPIKey' => "มีข้อผิดพลาดหา X-Elgg-apikey HTTP header",
			'APIException:MissingHmac' => "มีข้อผิดพลาดหา X-Elgg-hmac header",
			'APIException:MissingHmacAlgo' => "มีข้อผิดพลาดหา X-Elgg-hmac-algo header",
			'APIException:MissingTime' => "มีข้อผิดพลาดหา X-Elgg-time header",
			'APIException:TemporalDrift' => "X-Elgg-time is too far in the past or future. Epoch fail.",
			'APIException:NoQueryString' => "No data on the query string",
			'APIException:MissingPOSTHash' => "มีข้อผิดพลาดหา X-Elgg-posthash header",
			'APIException:MissingPOSTAlgo' => "มีข้อผิดพลาดหาX-Elgg-posthash_algo header",
			'APIException:MissingContentType' => "มีข้อผิดพลาดหา content type for post data",
			'SecurityException:InvalidPostHash' => "POST data hash is invalid - Expected %s but got %s.",
			'SecurityException:DupePacket' => "Packet signature already seen.",
			'SecurityException:InvalidAPIKey' => "มีข้อผิดพลาดหา API ไม่เจอ.",
			'NotImplementedException:CallMethodNotImplemented' => "Call method '%s' is currently not supported.",

			'NotImplementedException:XMLRPCMethodNotImplemented' => "XML-RPC method call '%s' not implemented.",
			'InvalidParameterException:UnexpectedReturnFormat' => "Call to method '%s' returned an unexpected result.",
			'CallException:NotRPCCall' => "Call does not appear to be a valid XML-RPC call",

			'PluginException:NoPluginName' => "หาปลั๊กอินไม่เจอ",
	
			'ConfigurationException:BadDatabaseVersion' => "ฐานข้อมูลไม่ได้ตั้งค่าตามที่ Elggต้องการ",
			'ConfigurationException:BadPHPVersion' => "คุณต้องใช้ PHP รุ่น 5. 2ขึ้นไปเพื่อใช้ Elgg.",
			'configurationwarning:phpversion' => "Elgg ต้องการ PHP รุ่น 5.2,คุณสามารถติดตั้งได้ที่รุ่น 5.1.6 แต่ความสามารถบางอย่างจะใช้ไม่ได้",
	
	
			'InstallationException:DatarootNotWritable' => "ที่เก็บ %sไม่สามารถเขียนได้.",
			'InstallationException:DatarootUnderPath' => "ที่เก็บ %sไม่ได้อยู่นอกพาทที่ติดตั้ง",
			'InstallationException:DatarootBlank' => "คุณไม่ได้ใส่ไดเรกเทอรี่เก็บไฟล์",
	
			'SecurityException:authenticationfailed' => "สมาชิกไม่ได้ยืนยัน",
	
			'CronException:unknownperiod' => '%s is not a recognised period.',
		/**
		 * API
		 */
			'system.api.list' => "แสดง API ทั้งหมดของระบบ",
			'auth.gettoken' => " API นี้จะเรียกใช้เมื่อสมาชิกเข้าสู่ระบบ",

		/**
		 * User details
		 */

			'name' => "ชื่อที่จะแสดง",
			'email' => "อีเมล",
			'username' => "ชื่อสมาชิก",
			'password' => "รหัสผ่าน",
			'passwordagain' => "รหัสผ่าน (ใส่อีกครั้งเพื่อยืนยัน)",
			'admin_option' => "ต้องการให้สมาชิกเป็นผู้ดูแล?",

		/**
		 * Access
		 */

			'ACCESS_PRIVATE' => "ส่วนตัว",
			'ACCESS_LOGGED_IN' => "สมาชิกที่เข้าสู่ระบบ",
			'ACCESS_PUBLIC' => "ทั่วไป",
			'PRIVATE' => "ส่วนตัว",
			'LOGGED_IN' => "สมาชิกที่เข้าสู่ระบบ",
			'PUBLIC' => "ทั่วไป",
			'access' => "การเข้าถึง",

		/**
		 * Dashboard and widgets
		 */

			'dashboard' => "พื้นที่ส่วนตัว",
                        'dashboard:configure' => "แก้ไขหน้านี้",
			'dashboard:nowidgets' => "พื้นที่ส่วนตัวของคุณในเว็บนี้ คลิ๊ก 'แก้ไขหน้านี้' เพื่อเพิ่มหรือแก้ไขการแสดงผล",

			'widgets:add' => 'เพิ่มวิดเจ็ต',
			'widgets:add:description' => "เลือกสิ่งที่คุณต้องการใน <b>คลังวิดเจ๊ต</b> ด้านขวา, โดยการลากเข้าสู่พื้นที่แสดงผลสามช่อง, และสามารถลากเปลี่ยนตำแหน่งได้.

หากต้องการลบออกจากพื้นที่แสดงผลให้ลากสิ่งที่ไม่ต้องการเข้าไปที่ <b>คลังวิดเจ๊ต</b>",
			'widgets:position:fixed' => '(จำกัดตำแหน่ง)',

			'widgets' => "วิดเจ็ต",
			'widget' => "วิดเจ็ต",
			'item:object:widget' => "วิดเจ็ต",
			'layout:customise' => "แก้ไขการแสดงผล",
			'widgets:gallery' => "คลังวิดเจ๊ต",
			'widgets:leftcolumn' => "วิดเจ็ตด้านซ้าย",
			'widgets:fixed' => "จำกัดตำแหน่ง",
			'widgets:middlecolumn' => "วิดเจ็ตตรงกลาง",
			'widgets:rightcolumn' => "วิดเจ็ตด้านขวา",
			'widgets:profilebox' => "กล่องโปรไฟล์",
			'widgets:panel:save:success' => "วิดเจ็ตของคุณถูกบันทึกแล้ว",
			'widgets:panel:save:failure' => "มีปัญหาในการบันทึกวิดเจ็ต ลองใหม่อีกครั้ง",
			'widgets:save:success' => "วิดเจ็ตของคุณถูกบันทึกแล้ว",
			'widgets:save:failure' => "มีปัญหาในการบันทึกวิดเจ็ต ลองใหม่อีกครั้ง",


		/**
		 * Groups
		 */

			'group' => "กลุ่ม",
			'item:group' => "กลุ่ม",

		/**
		 * Profile
		 */

			'profile' => "โปรไฟล์",
			'user' => "สมาชิก",
			'item:user' => "สมาชิก",

		/**
		 * Profile menu items and titles
		 */

			'profile:yours' => "โปรไฟล์ของคุณ",
			'profile:user' => "โปรไฟล์ของ %s",

			'profile:edit' => "แก้ไขโปรไฟล์",
			'profile:editicon' => "อัพโหลดรูปโปรไฟล์ใหม่",
			'profile:profilepictureinstructions' => "รูปโปรไฟล์จะถูกแสดง ในหน้าโปรไฟล์ของคุณ<br /> คุณสามารถเปลี่ยนมันได้ตลอดเวลา. (ไฟล์ที่สามารถใส่ได้: GIF, JPG or PNG)",
			'profile:icon' => "รูปโปรไฟล์",
			'profile:createicon' => "สร้่างรูปแทนตัว",
			'profile:currentavatar' => "รูปแทนตัวปัจจุบัน",
			'profile:createicon:header' => "รูปแทนตัว",
			'profile:profilepicturecroppingtool' => "เครื่องมือตัดรูป",
			'profile:createicon:instructions' => "คลิ๊กแล้วลากเป็นสี่เหลี่ยมตรวบริเวณที่คุณต้องการ  สามารถดูสิ่งที่คุณได้ทำตรงกล่งด้านขวา  หากคุณพอดใจแล้วให้คลิ๊ก 'สร้างรูปแทนตัว'ระบบจะไปจัดขนาดของรูปที่เหมาะสมให้เอง ",

			'profile:editdetails' => "แก้ไขรายละเอียด",
			'profile:editicon' => "แก้ไขรูปแทนตัว",

			'profile:aboutme' => "นี่แหละตัวฉัน",
			'profile:description' => "นี่แหละตัวฉัน(เขียนอธิบายตัวคุณ)",
			'profile:briefdescription' => "รายละเอียดย่อ(หรือคติประจำตัว)",
			'profile:location' => "ที่อยู่อาจใส่แค่จังหวัดหรืออำเภอ",
			'profile:skills' => "ความสามารถ",
			'profile:interests' => "ความสนใจ",
			'profile:contactemail' => "อีเมลที่ติดต่อได้",
			'profile:phone' => "โทรศัพท์",
			'profile:mobile' => "มือถือ",
			'profile:website' => "เว็บไซต์",

			'profile:river:update' => "%s อัพเดตโปรไฟล์",
			'profile:river:iconupdate' => "%s อัพเดตรูปโปรไฟล์",

		/**
		 * Profile status messages
		 */

			'profile:saved' => "โปรไฟล์ของคุณถูกบันทึก",
			'profile:icon:uploaded' => "รูปโปรไฟล์ของคุณถูกบันทึก",

		/**
		 * Profile error messages
		 */

			'profile:noaccess' => "คุณไม่มีสิทธิแก้ไขโปรไฟล์",
			'profile:notfound' => "เสียใจด้วย; เราไม่สามารถหาโปรไฟล์นี้ได้.",
			'profile:cantedit' => "เสียใจด้วย; คุณไม่มีสิทธิแก้ไขโปรไฟล์",
			'profile:icon:notfound' => "เสียใจด้วย; มีปัญหาในการอัพโหลดรูปโปรไฟล์",

		/**
		 * Friends
		 */

			'friends' => "เพื่อน",
			'friends:yours' => "เพื่อนของคุณ",
			'friends:owned' => "เพื่อนของ %s",
			'friend:add' => "เพิ่มเป็นเพื่อน",
			'friend:remove' => "บอกเลิกการเป็นเพื่อน",

			'friends:add:successful' => "คุณได้เพิ่ม %s เป็นเพื่อน",
			'friends:add:failure' => "ไม่สามารถเพิ่ม %s เป็นเพื่อนได้ กรุณาลองใหม่อีกครั้ง",

			'friends:remove:successful' => "คุณได้ลบ %s จากการเป็นเพื่อน",
			'friends:remove:failure' => "ไม่สามารถลบ %s จากการเป็นเพื่อนได้ กรุณาลองใหม่อีกครั้ง",

			'friends:none' => "สมาชิกคนนี้ยังไม่เคยเพิ่มใครเป็นเพื่อน",
			'friends:none:you' => "คุณยังไม่มีเพื่อนเลย! ลองค้นหาใครสักคนมาเป็นเพื่อนคุณ",

			'friends:none:found' => "ไม่มีเพื่อนเลย",

			'friends:of:none' => "ไม่มีใครเพิ่มเขาเป็นเพื่อนเลย",
			'friends:of:none:you' => "ยังไม่มีใครเพิ่มคุณเป็นเพื่อนเลยลองเขียนอะไรในบล๊อค หรือเพิ่มไฟล์ดูสิ!",

			'friends:of' => "คนที่กำลังขอเป็นเพื่อน",
			'friends:of:owned' => "ใครบางคนเพิ่ม %s เป็นเพื่อน",

			 'friends:num_display' => "ใส่จำนวนของเพื่อนที่ต้องการแสดง",
			 'friends:icon_size' => "ขนาดของรูป",
			 'friends:tiny' => "ใหญ่สุด",
			 'friends:small' => "เล็กสุด",
			 'friends' => "เพื่อน",
			 'friends:of' => "คนที่กำลังขอเป็นเพื่อน",
			 'friends:collections' => "ประเภทของเพื่อน",
			 'friends:collections:add' => "เพิ่มประเภทของเืพื่อน",
			 'friends:addfriends' => "เพิ่มเพื่อน",
			 'friends:collectionname' => "ชื่อประเภท",
			 'friends:collectionfriends' => "เพื่อนในประเภท",
			 'friends:collectionedit' => "แก้ไขประเภท",
			 'friends:nocollections' => "คุณยังไม่มีประเภท",
			 'friends:collectiondeleted' => "ประเภทของเพื่อนถูกลบ",
			 'friends:collectiondeletefailed' => "ไม่สามารถลบประเภทได้ เนื่องจากคุณไม่มีสิทธิ, หรืออาจมีข้อผิดพลาดบางอย่าง",
			 'friends:collectionadded' => "ประเภทของเพื่อนได้ถูกสร้างแล้ว",
			 'friends:nocollectionname' => "คุณต้องใส่ชื่อ ประเภทของเพื่อน",

	        'friends:river:created' => "%s เพิ่มวิดเจ็ต เพื่อน",
	        'friends:river:updated' => "%s อัพเดตวิดเจ็ต เพื่อน",
	        'friends:river:delete' => "%s ลบวิดเจ็ตเพื่อน",
	        'friends:river:add' => "%s เพิ่มใครบางคนเป็นเพื่อน",

		/**
		 * Feeds
		 */
			'feed:rss' => 'Subscribe to feed',
			'feed:odd' => 'Syndicate OpenDD',

		/**
		 * River
		 */
			'river' => "River",
			'river:relationship:friend' => 'เป็นเพื่อนโดย',

		/**
		 * Plugins
		 */
			'plugins:settings:save:ok' => "การตั้งค่า %s เสร็จสิ้น",
			'plugins:settings:save:fail' => "มีปัญหาในการบันทึก ปลั้กอิน %s ",
			'plugins:usersettings:save:ok' => "การจั้งค่าปลั้กอิน %s เสร็จสิ้น",
			'plugins:usersettings:save:fail' => "มีปัญหาในการบันทึก ปลั้กอิน %s ",
	
			'item:object:plugin' => 'การตั้งค่าปลั๊กอิน',
		
		/**
		 * Notifications
		 */
			'notifications:usersettings' => "ระบบเตือน",
			'notifications:methods' => "โปรดเลือกการกระทำอย่างใดอย่างหนึ่งกับระบบเตือน",

			'notifications:usersettings:save:ok' => "ระบบเตือนถูกบันทึกแล้ว",
			'notifications:usersettings:save:fail' => "มีปัญหากับการบันทึกระบบเตือน",
			
            'user.notification.get' => 'กลับไปยังการตั้งค่าเตือนของสมาชิก',
			'user.notification.set' => 'ตั้งค่าการเตือนสำหรับสมาชิก',
	/**
		 * Search
		 */

			'search' => "ค้นหา",
			'searchtitle' => "ค้นหา: %s",
			'users:searchtitle' => "ค้นหาสำหรับสมาชิก: %s",
			'advancedsearchtitle' => "%s ผลลัพธ์ของ %s",
			'notfound' => "ไม่มีผลลัพธ์ หรือไม่เจออะไรเลย",
			'next' => "ต่อไป",
			'previous' => "ย้อนกลับ",

			'viewtype:change' => "เปลี่ยนรูปแบบการแสดง",
			'viewtype:list' => "แสดงแบบรายการ",
			'viewtype:gallery' => "แกลลอรี่",

			'tag:search:startblurb' => "จำนวนที่เหมือนกันของแท็ก '%s':",

			'user:search:startblurb' => "สมาชิกที่เหมือน '%s':",
			'user:search:finishblurb' => "ดูอื่นๆเพิ่มเติม, คลิ๊กที่นี่",

		/**
		 * Account
		 */

			'account' => "บัญชี",
			'settings' => "ตั้งค่า",
                        'tools' => "เครื่องมือ",
                        'tools:yours' => "เครื่องมือของคุณ",

			'register' => "สมัครสมาชิก",
			'registerok' => "คุณได้ละทะเบียนสำหรับ %s แล้ว เพื่อการยืนยันว่ามีอีเมลจริงกรุณากลับไปตรวจสอบอีเมลแล้วคลิ๊กลิ้งค์ที่เราส่งให้เพื่อยืนยัน",
			'registerbad' => "ไม่สามารถลงทะเบียนได้. ชื่ออาจจะซ้ำ, หรือรหัสผ่านไม่ตรงกัน, หรือชื่อและรหัสผ่านสั้นเกินไป",
			'registerdisabled' => "ระบบไม่ได้เปิดให้มีการสมัครสมาชิกสมัครสมาชิก",

			'registration:notemail' => 'อีเมลไม่ถูกต้อง',
			'registration:userexists' => 'ชื่อสมาชิกนี้มีคนใช้แล้ว',
			'registration:usernametooshort' => 'ชื่อสมาชิกต้องไม่ต่ำกว่า 4 ตัวอักษร',
			'registration:passwordtooshort' => 'รหัสผ่านต้องไม่น้อยกว่า 6 ตัวอักษร',
			'registration:dupeemail' => 'อีเมลนี้ได้มีคนอื่นใช้ในการสมัครแล้ว',
            'registration:invalidchars' => 'เสียใจด้วย, อีเมลของคุณไม่สมบูรณ์',
			'registration:emailnotvalid' => 'เสียใจด้วย, อีเมลไม่มีในระบบ',
			'registration:passwordnotvalid' => 'เสียใจด้วย, รหัสผ่านไม่ตรงกับระบบ',
			'registration:usernamenotvalid' => 'เสียใจด้วย, ชื่อสมาชิกไม่มีในระบบ',

			'adduser' => "เพิ่มสมาชิก",
			'adduser:ok' => "เพิ่มสมาชิกใหม่แล้ว",
			'adduser:bad' => "การเพิ่มสมาชิกใหม่ไม่สามารถทำได้",

			'item:object:reported_content' => "แจ้งผู้ดูแล",

			'user:set:name' => "ตั้งค่าชื่อบัญชีคุณ",
			'user:name:label' => "ชื่อของคุณ",
			'user:name:success' => "การเปลี่ยนชื่อเสร็จสิ้น",
			'user:name:fail' => "ไม่สามารถเปลี่ยนชื่อได้",

			'user:set:password' => "ตั้งค่ารหัสผ่านของบัญชีคุณ",
			'user:password:label' => "รหัสผ่านใหม่",
			'user:password2:label' => "รหัสผ่านใหม่อีกครั้ง",
			'user:password:success' => "เปลี่ยนรหัสผ่านแล้ว",
			'user:password:fail' => "ไม่สามารถเปลี่ยนรหัสผ่านได้",
			'user:password:fail:notsame' => "คุณใส่รหัสผ่านสองครั้งไม่เหมือนกัน!",
			'user:password:fail:tooshort' => "รหัสผ่านสั้นเกินไป!",

			'user:set:language' => "การตั้งค่าภาษา",
			'user:language:label' => "ภาษาของคุณ",
			'user:language:success' => "ภาษาในการใช้งานได้อัพเดตแล้ว",
			'user:language:fail' => "การตั้งค่าภาษาไม่สามารถบันทึกได้",

			'user:username:notfound' => 'ชื่อสมาชิก %s ไม่มี',

			'user:password:lost' => 'ลืมรหัสผ่าน',
			'user:password:resetreq:success' => 'ได้รับการขอรหัสผ่านใหม่แล้ว และถูกส่งไปยังอีเมล',
			'user:password:resetreq:fail' => 'ไม่สามารถขอรหัสผ่านใหม่ได้',

			'user:password:text' => 'เพื่อสร้างรหัสผ่านใหม่, ให้ใส่ชื่อสมาชิกลงไปในช่องด้านล่าง จากนั้นเราจะส่งลิ้งยืนยันไปทางอีเมล ให้คุณคลิ๊กลิ้งที่ส่งไปแล้วคุณจะได้รหัสผ่านใหม่ ',

		/**
		 * Administration
		 */

			'admin:configuration:success' => "การตั้งค่าถูกบันทึก",
			'admin:configuration:fail' => "การตั้งค่าไม่สามารถบันทึกได้",

			'admin' => "ส่วนผู้ดูแล",
			'admin:description' => "หน้านี้คุณสามารถตั้งค่าต่างๆของระบบได้ ",

			'admin:user' => "รายชื่อผู้ดูแลระบบ",
			'admin:user:description' => "หน้านี้คุณสามารถตั้งค่าต่างๆของระบบได้",
			'admin:user:adduser:label' => "คลิ้กเพื่อเพิ่มสมาชิก...",
			'admin:user:opt:linktext' => "แก้ไขสมาชิก...",
			'admin:user:opt:description' => "ตั้งค่ารายละเอียดสมาชิก ",

			'admin:site' => "ตั้งค่าของระบบ",
			'admin:site:description' => "หน้านี้คุณสามารถตั้งค่าต่างๆของระบบได้",
			'admin:site:opt:linktext' => "แก้ไขค่าระบบ...",
			'admin:site:opt:description' => "การตั้งค่าต่างๆของระบบ ",

			'admin:plugins' => "จัดการเครื่องมือ",
			'admin:plugins:description' => "หน้านี้คุณสามารถตั้งค่าต่างๆของปลั๊กอินได้",
			'admin:plugins:opt:linktext' => "ตั้งค่าเครื่องมือ...",
			'admin:plugins:opt:description' => "ตั้งค่าเครื่องมือของระบบ ",
			'admin:plugins:label:author' => "ผู้พัฒนา",
			'admin:plugins:label:copyright' => "ลิขสิทธิ์",
			'admin:plugins:label:licence' => "การอนุญาตลิขสิทธิ์",
			'admin:plugins:label:website' => "URL",
			'admin:plugins:disable:yes' => "ปลั๊กอิน %s ได้ถูกปิดการใช้งานแล้ว",
			'admin:plugins:disable:no' => "ปลั๊กอิน %s ไม่สามารถปิดการใช้งานแล้ว",
			'admin:plugins:enable:yes' => "ปลั๊กอิน %s ได้ถูกเปิดใช้งานแล้ว",
			'admin:plugins:enable:no' => "ปลั๊กอิน %s ไม่สามารถเปิดการใช้งานได้",

			'admin:statistics' => "สถิติ",
			'admin:statistics:description' => "นี่คือหน้ารายงานสำหรับผู้ดูแล",
			'admin:statistics:opt:description' => "ดูสถิติต่างๆของสมาชิกในระบบ",
			'admin:statistics:opt:linktext' => "ดูสถิติ...",
			'admin:statistics:label:basic' => "สถิติพื้นฐาน",
			'admin:statistics:label:numentities' => "การใช้งานทั้งหมด",
			'admin:statistics:label:numusers' => "จำนวนสมาชิก",
			'admin:statistics:label:numonline' => "จำนวนสมาชิกที่เข้าสู่ระบบ",
			'admin:statistics:label:onlineusers' => "สมาชิกที่อยู่ในระบบตอนนี้",
			'admin:statistics:label:version' => "รุ่นของ Elgg",
			'admin:statistics:label:version:release' => "รีรีส",
			'admin:statistics:label:version:version' => "รุ่น",

			'admin:user:label:search' => "หาสมาชิก:",
			'admin:user:label:seachbutton' => "ค้นหา",

			'admin:user:ban:no' => "ไม่สามารถแบนสมาชิกได้",
			'admin:user:ban:yes' => "สมาชิกนี้ถูกแบน",
			'admin:user:unban:no' => "ไม่สามารถบอกเลิกการแบนสมาชิกได้",
			'admin:user:unban:yes' => "เลิกการแบนสมาชิก",
			'admin:user:delete:no' => "ไม่สามารถลบสมาชิกได้",
			'admin:user:delete:yes' => "ลบสมาชิก",

			'admin:user:resetpassword:yes' => "ล้างรหัสผ่าน, แจ้งไปยังสมาชิก",
			'admin:user:resetpassword:no' => "ไม่สามารถล้างรหัสผ่านได้",

			'admin:user:makeadmin:yes' => "สมาชิกเป็นผู้ดูแลแล้ว",
			'admin:user:makeadmin:no' => "ไม่สามารถทำให้สมชิกเป็นผู้ดูแลได้",

		/**
		 * User settings
		 */
			'usersettings:description' => "หน้าจัดการสมาชิก",

			'usersettings:statistics' => "สถิติของคุณ",
			'usersettings:statistics:opt:description' => "ดูสถิติต่างๆที่เกิดขึ้นบนเว็บ",
			'usersettings:statistics:opt:linktext' => "สถิติ",

			'usersettings:user' => "การตั้งค่าของคุณ",
			'usersettings:user:opt:description' => "คุณสามารถตั้งค่าต่างๆได้ที่นี้",
			'usersettings:user:opt:linktext' => "เปลี่ยนการตั้งค่า",

			'usersettings:plugins' => "เครื่องมือ",
			'usersettings:plugins:opt:description' => "ตั้งค่าการทำงานของเครื่องมือ",
			'usersettings:plugins:opt:linktext' => "กำลังตั้งค่าเครื่องมือ...",

			'usersettings:plugins:description' => "คุณสามารถตั้งค่าต่างๆได้ที่นี่",
			'usersettings:statistics:label:numentities' => "การใช้งานต่างๆ",

			'usersettings:statistics:yourdetails' => "รายละเอียดของคุณ",
			'usersettings:statistics:label:name' => "ชื่อจริง",
			'usersettings:statistics:label:email' => "อีเมล",
			'usersettings:statistics:label:membersince' => "เป็นสมาชิกเมือ",
			'usersettings:statistics:label:lastlogin' => "เข้าสู่ระบบล่าสุด",



		/**
		 * Generic action words
		 */

			'save' => "บันทึก",
			'cancel' => "ยกเลิก",
			'saving' => "กำลังบันทึก ...",
			'update' => "อัพเดต",
			'edit' => "แก้ไข",
			'delete' => "ลบ",
			'load' => "โหลด",
			'upload' => "อัพโหลด",
			'ban' => "แบน",
			'unban' => "ยกเลิกการแบน",
			'enable' => "ทำงาน",
			'disable' => "ไม่ทำงาน",
			'request' => "ต้องการ",
			'complete' => "Complete",

			'invite' => "Invite",
	
			'resetpassword' => "ล้างรหัสผ่าน",
			'makeadmin' => "ให้เป็นผู่ดูแล",

			'option:yes' => "ใช่",
			'option:no' => "ไม่",

			'unknown' => 'ไม่รู้',

			'learnmore' => "คลิ๊กเพื่อเรียนรู้ต่อ",

			'content' => "เนื้อหา",
			'content:latest' => 'ความเคลือนไหวล่าสุด',
			'content:latest:blurb' => 'ลองคลิ๊กไปดูความเคลือนไหวล่าสุด',

			'link:text' => 'view link',
	
	
		/**
		 * Generic data words
		 */

			'title' => "ชื่อ",
			'description' => "รายละเอียด",
			'tags' => "แท็ก",
			'spotlight' => "คู่มือ",
			'all' => "ทั้งหมด",

			'by' => 'โดย',

			'annotations' => "หมายเหตุ",
			'relationships' => "ความสัมพันธ์",
			'metadata' => "เมต้าดาต้า",

		/**
		 * Input / output strings
		 */

			'deleteconfirm' => "คุณแน่ใจไหมที่จะลบ?",
			'fileexists' => "ไฟล์ถูกอัพโหลดแล้ว. หากให้ต้องการทับไฟล์เดิมคลิ๊กด้านล่าง:",

		/**
		 * Import / export
		 */
			'importsuccess' => "นำเข้าข้อมูลเรียบร้อย",
			'importfail' => "OpenDD นำเข้าข้อมูลไม่ได้",

		/**
		 * Time
		 */

			'friendlytime:justnow' => "ตอนนี้",
			'friendlytime:minutes' => "%s นาทีที่แล้ว",
			'friendlytime:minutes:singular' => "นาทีที่แล้ว",
			'friendlytime:hours' => "%s ชัวโมงที่แล้ว",
			'friendlytime:hours:singular' => "ชั่วโมงที่แล้ว",
			'friendlytime:days' => "%s วันที่แล้ว",
			'friendlytime:days:singular' => "เมื่อวานนี้",

		/**
		 * Installation and system settings
		 */

			'installation:error:htaccess' => "ระบบต้องการไฟล์ .htaccess คุณต้องมีไฟล์นี้ในโฟลเดอร์เดียวกับโฟลเดอร์ที่ติดตั้ง ระบบสามารถสร้างให้คุณได้อัตโนมัติหากโฟลเดอร์ที่ติดตั้งสามารถเขียนได้

หรือสร้างเองโดยคัดลอกโค๊ดที่เห็นไปบันทักเป็นไฟล์ชื่อ .htaccess

",
			'installation:error:settings' => "Elgg ไม่สามารถหาไฟล์ settings.php ได้นี่เป็นรายละเอียดที่คุณต้องทำ:

1. เปลี่ยนชื่อ engine/settings.example.php เป็น settings.php

2. เปิดไฟล์ settings.php แล้วใส่รายละเอียดการติดต่อฐานข้อมูล MySQL ลงไป ถ้าคุณไมู่้รู้กรุณาติดต่อผู้ดูแล server ของคุณ

หรือ, ใส่รายละเอียดตามด้านล่าง...",

			'installation:error:configuration' => "หากตั้งค่าต่างๆแล้วให้รีโหลดหน้านี้อีกครั้ง",

			'installation' => "การติดตั้ง",
			'installation:success' => "ฐานข้อมูล Elgg ติดตั้งแล้ว",
			'installation:configuration:success' => "ระบบได้ถูกตั้งค่าแล้ว ต่อไปคุณต้องสร้างสมาชิกที่เป็นผู้ดูแลระบบ",

			'installation:settings' => "ตั้งค่าระบบ",
			'installation:settings:description' => "ตอนนี้ระบบได้ถูกติดตั้งแล้ว, คุณต้องใส่รายละเอียดต่างๆของเว็บคุณลงไป",
	
			'installation:settings:dbwizard:prompt' => "ใส่รายละเอียดฐานข้อมูล:",
			'installation:settings:dbwizard:label:user' => "ชื่อผู้ใช้ฐานข้อมูล",
			'installation:settings:dbwizard:label:pass' => "รหัสผ่านฐานข้อมูล",
			'installation:settings:dbwizard:label:dbname' => "ชื่อฐานข้อมูล",
			'installation:settings:dbwizard:label:host' => "ชื่อโอสฐานข้อมูล (ปรกติจะเป็น 'localhost')",
			'installation:settings:dbwizard:label:prefix' => "ชื่อนำหน้าฐานข้อมูล (เช่น 'elgg')",
	
			'installation:settings:dbwizard:savefail' => "ไม่สามารถบันทึก settings.php ได้คุณสามารถคัดลอกรายละเอียดด้านล่างแล้วนำไปใส่ในไฟล์ engine/settings.php",

			'installation:sitename' => "ชื่อ (เช่น \"My social networking site\"):",
			'installation:sitedescription' => "รายละเอียดย่อ (ไม่ใส่ก็ได้)",
			'installation:wwwroot' => " URL, ใส่ / ต่อท้ายด้วย:",
			'installation:path' => "พาทที่ถูกติดตั้ง ใส่ / ต่อท้ายด้วย:",
			'installation:dataroot' => "พาทที่จะเก็บไฟล์ที่อัพโหลด,  ใส่ / ต่อท้ายด้วย:",
			'installation:dataroot:warning' => "คุณสามารถสร้างได้เอง. โดยให้อยู่คนละที่กับที่ติดตั้ง elgg",
			'installation:language' => "ภาษาหลักของเว็บ:",
			'installation:debug' => "ดีบัคเหมาะกับผู้ที่เป็นนักพัฒนา:",
			'installation:debug:label' => "เปิดดีบัค",
			'installation:usage' => "ดีบัคเหมาะกับผู้ที่เป็นนักพัฒนา",
			'installation:usage:label' => "เปิดสถิติ",
			'installation:view' => "ใส่้ ชื่อของแทมเพลตที่ใช้แสดงหากไม่รู้ให้ข้ามไป (ระบบใส่ให้เอง):",

			'installation:siteemail' => "อีเมลของเว็บ (ใช้ในการส่งเมล)",

			'installation:disableapi' => "Elgg comes with an flexible and extendible API that enables applications use certain Elgg features remotely",
			'installation:disableapi:label' => "เปิดการใช้ RESTful API",

			'upgrade:db' => 'ฐานข้อมูลถูกอัพเดต',

		/**
		 * Welcome
		 */

			'welcome' => "ขอต้อนรับ %s",
			'welcome_message' => "ขอต้อนรับสู่การติดตั้งระบบ",

		/**
		 * Emails
		 */
			'email:settings' => "ตั้งค่าอีเมล",
			'email:address:label' => "อีเมลของคุณ",

			'email:save:success' => "อีเมลใหม่ถูกบันทึกแล้ว",
			'email:save:fail' => "อีเมลใหม่บันทึกไม่ได้",

			'email:confirm:success' => "คุณได้ยืนยันอีเมลแล้ว!",
			'email:confirm:fail' => "อีเมลของคุณยังไม่ผ่านการยืนยัน...",

			'friend:newfriend:subject' => "%s เพิ่มคุณเป็นเพื่อน!",
			'friend:newfriend:body' => "%s เพิ่มคุณเป็นเพื่อน!

หากต้องการไปดูคลิ๊ก:

	%s

You cannot reply to this email.",



			'email:resetpassword:subject' => "เปลี่ยนรหัสผ่าน!",
			'email:resetpassword:body' => "สวัสดี %s,

รหัสผ่านของคุณเปลี่ยนเป็น: %s",


			'email:resetreq:subject' => "ตุณได้ขอรหัสผ่านใหม่มา",
			'email:resetreq:body' => "สวัสดี %s,

มีบางคน (จากไอพี %s) ต้องการขอรหัสผ่านใหม่มา

หากคุณต้องการขอรหัสผ่านใหม่ให้คลิ๊กลิ้งค์ด้านล่าง

%s
",


		/**
		 * XML-RPC
		 */
			'xmlrpc:noinputdata'	=>	"การเพิ่มข้อมูลผิดพลาด",

		/**
		 * Comments
		 */

			'comments:count' => "%s ความคิดเห็น",
			'generic_comments:add' => "เพิ่มความคิดเห็น",
			'generic_comments:text' => "ความคิดเห็น",
			'generic_comment:posted' => "คุณได้เพิ่มความคิดเห็นแล้ว",
			'generic_comment:deleted' => "ความคิดเห็นของคุณถูกลบแล้ว",
			'generic_comment:blank' => "เสียใจด้วย; คุณต้องใส่อะไรบางอย่างลงกล่องข้อความเพื่อบันทึก",
			'generic_comment:notfound' => "เสียใจด้วย; เราไม่สามารถหาอะไรบางอย่างเจอ",
			'generic_comment:notdeleted' => "เสียใจด้วย; เราไม่สามารถลบความคิดเห็นได้",
			'generic_comment:failure' => "มีข้อผิดพลาดอะไรบ่างอย่าง หากต้องการแสดงความคิดเห็นให้ลองใหม่อีกครั้ง",

			'generic_comment:email:subject' => 'คุณมีความคิดเห็นใหม่!',
			'generic_comment:email:body' => "คุณได้รับความคิดเห็นใหม่ใน \"%s\" จาก %s. ไปอ่านที่:


%s


หากต้องการตอบกลับคลิีก:

	%s

ต้องการดูโปรไฟล์ของ %s คลิ๊ก:

	%s

คุณไม่ต้องตอบกลับอีเมลนี้",

		/**
		 * Entities
		 */
			'entity:default:strapline' => 'สร้าง %s โดย %s',
			'entity:default:missingsupport:popup' => 'ไม่สามารถแสดงได้ระบบต้องการ ปลั๊กอินบางตัว',

			'entity:delete:success' => 'การกระทำ %s ถูกลบ',
			'entity:delete:fail' => 'การกระทำ %s ไม่สามารถลบได้',


		/**
		 * Action gatekeeper
		 */
			'actiongatekeeper:missingfields' => 'ไม่มีฟิล  __token หรือ __ts',
			'actiongatekeeper:tokeninvalid' => 'Token ไม่เหมือนบนserver.',
			'actiongatekeeper:timeerror' => 'หมดอายุ, โปรดลองใหม่',
			'actiongatekeeper:pluginprevents' => 'ระบบป้องกันการซัพมิต',

		/**
		 * Languages according to ISO 639-1
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
			"qu" => "Quechua",
			"rm" => "Rhaeto-Romance",
			"rn" => "Kirundi",
			"ro" => "Romanian",
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
			"zu" => "Zulu",
	);

	add_translation("th",$thai);

?>
