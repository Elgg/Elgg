<?php
return array(
/**
 * Sites
 */

	'item:site' => 'Sites',

/**
 * Sessions
 */

	'login' => "ورود",
	'loginok' => "شما وارد سیستم شده اید",
	'loginerror' => "امکان ورود شما نیست. لطفا ورودی ها را کنترل کنید و دوباره سعی کنید.",
	'login:empty' => "نام کاربری/ایمیل و کلمه عبور اجباری هستند",
	'login:baduser' => "امکان بارگزاری اطلاعات کاربری شما نیست.",
	'auth:nopams' => "خطای داخلی. هیچ متدی برای ورود کاربر نصب نشده است.",

	'logout' => "خروج",
	'logoutok' => "شما از سیستم خارج شده اید.",
	'logouterror' => "امکان خروج شما نیست. لطفا دوباره سعی کنید.",
	'session_expired' => "نشست کاربری شما باطل شده است. لطفا صفحه را  <a href='javascript:location.reload(true)'>بارگزاری مجدد</a> کنید.",
	'session_changed_user' => "شما به عنوان یک کاربر دیگر وارد سیستم شده اید. باید صفحه را  <a href='javascript:location.reload(true)'>بارگزاری مجدد</a> کنید.",

	'loggedinrequired' => "برای مشاهده صفحه درخواست شده باید وارد شوید.",
	'adminrequired' => "برای مشاهده این صفحه شما باید مدیر سیستم باشید.",
	'membershiprequired' => "برای مشاهده این صفحه شما باید عضو این گروه باشید.",
	'limited_access' => "شما مجوز دسترسی به این صفحه را ندارید.",
	'invalid_request_signature' => "آدرس این صفحه منقضی شده یا نامعتبر است",

/**
 * Errors
 */

	'exception:title' => "خطای مهلک.",
	'exception:contact_admin' => 'خطای غیرقابل برگشتی رخ داده است. با مدیر سیستم تماس بگیرید و این اطلاعات را در اختیار وی قرار دهید:',

	'actionundefined' => "فعالیت مورد درخواست (%s) در این سیستم تعریف نشده است.",
	'actionnotfound' => "فایل فعالیت برای %s پیدا نشد.",
	'actionloggedout' => "متاسفانه برای انجام این عملیات باید وارد سیستم شده باشید.",
	'actionunauthorized' => 'شما مجوز انجام این عملیات را ندارید.',

	'ajax:error' => 'در حال ارسال درخواست آجاکسی خطای ناخواسته ای رخ داد. ممکن است که ارتباط با سرور قطع شده باشد.',
	'ajax:not_is_xhr' => 'شما نمی توانید به نمایه های آجاکسی به صورت مستقیم دسترسی داشته باشید.',

	'PluginException:MisconfiguredPlugin' => "%s (guid: %s)یک پلاگین تنظیم نشده است. و غیرفعال می باشد. لطفا در صفحه ویکی Elgg به دنبال علت باشید. (http://learn.elgg.org/)",
	'PluginException:CannotStart' => '%s (guid: %s) امکان شروع شدن ندارد و غیرفعال شده است. به علت: %s',
	'PluginException:InvalidID' => "%s یک کد پلاگین نامعتبر است.",
	'PluginException:InvalidPath' => "%s یک مسیر پلاگین نامعتبر است.",
	'PluginException:InvalidManifest' => 'فایل مانیفست برای پلاگین %s معتبر نیست.',
	'PluginException:InvalidPlugin' => '%s یک پلاگین معتبر نیست.',
	'PluginException:InvalidPlugin:Details' => '%s یک پلاگین معتبر نیست. %s',
	'PluginException:NullInstantiated' => 'پلاگین Elgg نمی تواند مقدار تهی یا خالی باشد. شما باید یک GUID یا یک کد پلاگین و یا یک مسیر کامل را وارد کنید.',
	'ElggPlugin:MissingID' => 'کد پلاگین پیدا نشد (guid %s)',
	'ElggPlugin:NoPluginPackagePackage' => 'بسته پلاگین Elgg برای کد پلاگین %s ناشناخته است (guid %s)',
	'ElggPluginPackage:InvalidPlugin:MissingFile' => 'فایل  درخواست شده %s پیدا نشد.',
	'ElggPluginPackage:InvalidPlugin:InvalidId' => 'پوشه پلاگین بای به %s تغییر نام پیدا کند تا با کد پلاگین که در فایل مانیفست مشخص شده مطابق شود.',
	'ElggPluginPackage:InvalidPlugin:InvalidDependency' => 'فایل مانیفست نوع داده وابسته نامعتبر دارد "%s"',
	'ElggPluginPackage:InvalidPlugin:InvalidProvides' => 'فایل مانیفست نوع داده نامعتبر دارد "%s"',
	'ElggPluginPackage:InvalidPlugin:CircularDep' => 'یک %s وابستگی %s در پلاگین %s وجود دارد. پلاگین نمی تواند با تامین کننده هایش مغایرت داشته باشد.',
	'ElggPluginPackage:InvalidPlugin:ConflictsWithPlugin' => 'تداخل با پلاگین: %s',
	'ElggPluginPackage:InvalidPlugin:UnreadableConfig' => 'فایل پلاگین "elgg-plugin.php" موجود هست ولی قابل خواندن نیست',
	'ElggPlugin:Exception:CannotIncludeFile' => 'امکان استفاده از %s برای پلاگین %s (guid: %s ) در %s نیست.',
	'ElggPlugin:Exception:IncludeFileThrew' => 'خطای %s برای پلاگین  %s (guid: %s) در  %s رخ داد.',
	'ElggPlugin:Exception:CannotRegisterViews' => 'امکان باز کردن پوشه نمایه های پلاگین %s (guid: %s) در %s نیست.',
	'ElggPlugin:Exception:NoID' => 'هیچ کدی برای پلاگین با guid: %s نیست.',
	'PluginException:NoPluginName' => "نام پلاگین پیدا نشد",
	'PluginException:ParserError' => 'خطا: با استفاده از واسط نسخه %s امکان پردازش فایل مانیفست پلاگین %s نیست.',
	'PluginException:NoAvailableParser' => 'هیچ مفسری برای واسط نسخه %s برای فایل مانیفست پلاگین %sیافت نشد.',
	'PluginException:ParserErrorMissingRequiredAttribute' => "مقدار خصیصه الزامی %s در فایل مانیفست پلاگین %s یافت نشد",
	'ElggPlugin:InvalidAndDeactivated' => '%sیک پلاگین نامعتبر یا غیرفعال است.',
	'ElggPlugin:activate:BadConfigFormat' => 'فایل پلاگین "elgg-plugin.php" آرایه مورد نظر را برنگرداند',
	'ElggPlugin:activate:ConfigSentOutput' => 'خروجی فایل "elgg-plugin.php"',

	'ElggPlugin:Dependencies:Requires' => 'نیازمندی ها',
	'ElggPlugin:Dependencies:Suggests' => 'پیشنهادات',
	'ElggPlugin:Dependencies:Conflicts' => 'ناسازگاریها',
	'ElggPlugin:Dependencies:Conflicted' => 'ناسازگار است',
	'ElggPlugin:Dependencies:Provides' => 'فراهم میکند',
	'ElggPlugin:Dependencies:Priority' => 'اولویت',

	'ElggPlugin:Dependencies:Elgg' => 'نسخه Elgg',
	'ElggPlugin:Dependencies:PhpVersion' => 'نسخه PHP',
	'ElggPlugin:Dependencies:PhpExtension' => 'افزونه PHP : %s',
	'ElggPlugin:Dependencies:PhpIni' => 'تنظیمان php ini  : %s',
	'ElggPlugin:Dependencies:Plugin' => 'پلاگین: %s',
	'ElggPlugin:Dependencies:Priority:After' => 'بعد از %s',
	'ElggPlugin:Dependencies:Priority:Before' => 'قبل از %s',
	'ElggPlugin:Dependencies:Priority:Uninstalled' => '%sنصب نشده است',
	'ElggPlugin:Dependencies:Suggests:Unsatisfied' => 'موجود نیست',

	'ElggPlugin:Dependencies:ActiveDependent' => 'پلاگین های دیگری در لیست %s به عنوان پیش نیاز ها آمده اند. شما باید ابتدا پلاگین های زیر را غیرفعال کنید:%s',

	'ElggMenuBuilder:Trees:NoParents' => 'زیرمنوهایی وجود دارند که هیچ منوی بالاتری ندارند',
	'ElggMenuBuilder:Trees:OrphanedChild' => 'زیرمنوی ]%s[ بدون منوی %s می باشد.',
	'ElggMenuBuilder:Trees:DuplicateChild' => 'منوی %s بیش از یک بار ثبت شده است',

	'RegistrationException:EmptyPassword' => 'کلمه عبور نباید خالی باشد',
	'RegistrationException:PasswordMismatch' => 'کلمه عبور باید مطابقت داشته باشد',
	'LoginException:BannedUser' => 'ورود شما به این سایت منع شده است.',
	'LoginException:UsernameFailure' => 'امکان ورود شما نیست. لطفا نام کاربری/ایمیل و کلمه عبور خود را چک کنید.',
	'LoginException:PasswordFailure' => 'امکان ورود شما نیست. لطفا کلمه عبور و نام کاربری/ایمیل خود را چک کنید.',
	'LoginException:AccountLocked' => 'حساب کاربری شما به خاطر چندین بار ورود ناموفق قفل شده است.',
	'LoginException:ChangePasswordFailure' => 'پسورد فعلی مطابقت ندارد',
	'LoginException:Unknown' => 'به خاطر خطای ناشناخته ای امکان ورود شما به سیستم نیست',

	'UserFetchFailureException' => 'امکان بررسی دسترسی کاربر [%s] نیست، چون این کاربر وجود ندارد.',

	'deprecatedfunction' => 'هشدار: این این کد از تابع منسوخ %s استفاده میکند و با این نسخه از Elgg سازگاری ندارد.',

	'pageownerunavailable' => 'هشدار: صاحب این صفحه %d در دسترس نیست.',
	'viewfailure' => 'خطای داخلی در نمایه %sبه وجود آمد',
	'view:missing_param' => "پارامتر اجباری %s از نمایه %s موجود نیست",
	'changebookmark' => 'لطفا گزیده تان برای این صفحه را تغییر دهید',
	'noaccess' => 'محتوایی که شما قصد نمایش آن را دایرد حذف شده و یا شما اجازه نمایش آن را ندارید.',
	'error:missing_data' => 'در داده های درخواست شما موارد اجباری یافت نشد.',
	'save:fail' => 'در ذخیره داده های شما مشکلی پیش آمد',
	'save:success' => 'داده شما ذخیره شد',

	'error:default:title' => 'متاسفم',
	'error:default:content' => 'متاسفم.... مشکلی پیش آمده',
	'error:400:title' => 'درخواست اشتباه',
	'error:400:content' => 'متاسفانه درخواست نامعتبر یا ناقص است.',
	'error:403:title' => 'ممنوع',
	'error:403:content' => 'متاسفانه شما اجازه دسترسی به صفحه درخواستی را ندارید.',
	'error:404:title' => 'صفحه یافت نشد',
	'error:404:content' => 'متاسفانه امکان یافتن صفحه مورد نظر شما نیست.',

	'upload:error:ini_size' => 'فایلی که قصد بارگزاری آن رادارید خیلی بزرگ است',
	'upload:error:form_size' => 'فایلی که سعی کردید بارگزاری کنید خیلی بزرگ است',
	'upload:error:partial' => 'بارگزاری فایل تکمیل نشد.',
	'upload:error:no_file' => 'هیچ فایلی انتخاب نشده است',
	'upload:error:no_tmp_dir' => 'امکان ذخیره فایل بارگزاری شده نیست.',
	'upload:error:cant_write' => 'امکان ذخیره فایل بارگزاری شده نیست.',
	'upload:error:extension' => 'امکان ذخیره فایل بارگزاری شده نیست.',
	'upload:error:unknown' => 'بارگزاری فایل با شکست مواجه شد.',

/**
 * Table columns
 */
	'table_columns:fromView:admin' => 'مدیر',
	'table_columns:fromView:banned' => 'ممنوع شده',
	'table_columns:fromView:container' => 'ظرف',
	'table_columns:fromView:excerpt' => 'توضیحات',
	'table_columns:fromView:link' => 'نام/عنوان',
	'table_columns:fromView:icon' => 'آیکن',
	'table_columns:fromView:item' => 'آیتم',
	'table_columns:fromView:language' => 'زبان',
	'table_columns:fromView:owner' => 'صاحب',
	'table_columns:fromView:time_created' => 'زمان ایجاد',
	'table_columns:fromView:time_updated' => 'زمان بروزرسانی',
	'table_columns:fromView:user' => 'کاربر',

	'table_columns:fromProperty:description' => 'توضیحات',
	'table_columns:fromProperty:email' => 'ایمیل',
	'table_columns:fromProperty:name' => 'نام',
	'table_columns:fromProperty:type' => 'نوع',
	'table_columns:fromProperty:username' => 'نام کاربری',

	'table_columns:fromMethod:getSubtype' => 'نوع زیرمجموعه',
	'table_columns:fromMethod:getDisplayName' => 'نام/عنوان',
	'table_columns:fromMethod:getMimeType' => 'نوع MIME',
	'table_columns:fromMethod:getSimpleType' => 'نوع',

/**
 * User details
 */

	'name' => "نام قابل نمایش",
	'email' => "آدرس ایمیل",
	'username' => "نام کاربری",
	'loginusername' => "نام کاربری یا ایمیل",
	'password' => "کلمه عبور",
	'passwordagain' => "کلمه عبور (تکرار مجدد)",
	'admin_option' => "این کاربر مدیر است؟",
	'autogen_password_option' => "کلمه عبور امن به صورت خودکار تولید شود؟",

/**
 * Access
 */

	'PRIVATE' => "خصوصی",
	'LOGGED_IN' => "کاربران وارد شده",
	'PUBLIC' => "عمومی",
	'LOGGED_OUT' => "کاربران خارج شده",
	'access:friends:label' => "دوستان",
	'access' => "دسترسی",
	'access:overridenotice' => "نکته: به خاطر قوانین گروه، این محتوا فقط در دسترس اعضای گروه است.",
	'access:limited:label' => "محدود",
	'access:help' => "سطح دسترسی",
	'access:read' => "مجوز دسترسی برای خواندن",
	'access:write' => "مجوز دسترسی برای نوشتن",
	'access:admin_only' => "فقط مدیر",
	'access:missing_name' => "نام سطح دسترس موجود نیست.",
	'access:comments:change' => "این بحث فقط برای مخاطبین قابل نمایش است. دقت کنید که برای چه کسی به اشتراک می گذارید.",

/**
 * Dashboard and widgets
 */

	'dashboard' => "داشبورد",
	'dashboard:nowidgets' => "داشبورد به شما اجازه مشاهده فعالیت و محتوای سایتتان را میدهد.",

	'widgets:add' => 'افزود ابزارک',
	'widgets:add:description' => "برای افزودن ابزارک روی دکمه آن کلیک کنید.",
	'widgets:panel:close' => "بستن پنجره ابزارکها",
	'widgets:position:fixed' => '(موقعیت ثابت در صفحه)',
	'widget:unavailable' => 'شما این ابزارک را قبلا اضافه کرده اید',
	'widget:numbertodisplay' => 'تعداد موارد قابل نمایش',

	'widget:delete' => 'حذف thoughtful',
	'widget:edit' => 'شخصی سازی این ابزارک',

	'widgets' => "ابزار ها",
	'widget' => "ابزارک",
	'item:object:widget' => "ابزار ها",
	'widgets:save:success' => "ابزارک با موفقیت ذخیره شد",
	'widgets:save:failure' => "امکان ذخیره سازی ابزارک نیست",
	'widgets:add:success' => "ابزارک با موفقیت افزوده شد",
	'widgets:add:failure' => "امکان افزودن ابزارک نبود.",
	'widgets:move:failure' => "امکان ذخیره سازی مکان ابزارک جدید نبود.",
	'widgets:remove:failure' => "امکان حذف این ابزارک نیست",

/**
 * Groups
 */

	'group' => "گروه",
	'item:group' => "گروهها",

/**
 * Users
 */

	'user' => "کاربر",
	'item:user' => "کاربران",

/**
 * Friends
 */

	'friends' => "دوستان",
	'friends:yours' => "دوستان شما",
	'friends:owned' => "دوستان %s",
	'friend:add' => "افزودن دوست",
	'friend:remove' => "حذف دوست",

	'friends:add:successful' => "شما با موفقیت %s  را به عنوان دوست اضافه کردید.",
	'friends:add:failure' => "امکان افزودن %s به عنوان دوست نیست.",

	'friends:remove:successful' => "شما با موفقیت %s را از لیست دوستانتان حذف کردید",
	'friends:remove:failure' => "امکان حذف %s از لیست دوستان شما نیست",

	'friends:none' => "در حال حاضر هیچ دوستی ندارید",
	'friends:none:you' => "شما در حال حاضر هیچ دوستی ندارید",

	'friends:none:found' => "هیچ دوستی یافت نشد",

	'friends:of:none' => "هیچ کس این فرد را به عنوان دوست انتخاب نکرده",
	'friends:of:none:you' => "هنوز هیچ کس شما را به عنوان دوست انتخاب نکرده. شروع به انتخاب دوست کنید و پروفایل خود را تکمیل کنید تا بقیه شما را بیابند.",

	'friends:of:owned' => "افرادی که %s را به عنوان دوست انتخاب کرده اند",

	'friends:of' => "دوست",
	'friends:collections' => "مجموعه دوستان",
	'collections:add' => "مجموعه جدید",
	'friends:collections:add' => "مجموعه جدید دوستان",
	'friends:addfriends' => "انتخاب دوستان",
	'friends:collectionname' => "عنوان مجموعه",
	'friends:collectionfriends' => "دوستان این مجموعه",
	'friends:collectionedit' => "ویرایش این مجموعه",
	'friends:nocollections' => "شما هنوز هیچ مجموعه ای ندارید",
	'friends:collectiondeleted' => "مجموعه شما حذف شد",
	'friends:collectiondeletefailed' => "شما امکان حذف این مجموعه را ندارید. شاید به خاطر نداشتن مجوز یا خطای دیگری...",
	'friends:collectionadded' => "مجموعه شما با موفقیت ایجاد شد",
	'friends:nocollectionname' => "شما باید قبل از ایجاد مجموعه نام آن را انتخاب نمایید.",
	'friends:collections:members' => "اعضای مجموعه",
	'friends:collections:edit' => "ویرایش مجموعه",
	'friends:collections:edited' => "مجموعه ذخیره شده",
	'friends:collection:edit_failed' => 'امکان ذخیره مجموعه نیست',

	'friendspicker:chararray' => 'الف ب پ ت ث ج چ ح خ د ذ ر ز ژ س ش ص ض ط ظ ع غ ف ق ک گ ل م ن و ه ی',

	'avatar' => 'شکلک',
	'avatar:noaccess' => "شما امکان ویرایش شکلک این کاربر را ندارید",
	'avatar:create' => 'شکلک خود را ایجاد نمایید',
	'avatar:edit' => 'ویرایش شکلک',
	'avatar:preview' => 'پیش نمایش',
	'avatar:upload' => 'بارگزاری شکلک جدید',
	'avatar:current' => 'شکلک فعلی',
	'avatar:remove' => 'حذف شکلک شما و تنظیم آیکن پیش فرض',
	'avatar:crop:title' => 'ابزار برش شکلک',
	'avatar:upload:instructions' => "شکلک شما در داخل سایت نمایش داده می شود. هرموقع که خواستید می توانید آن را تغییر دهید. (فایل های با فرمت: GIF, JPG یا PNG قابل قبول هستند).",
	'avatar:create:instructions' => 'با کلیک کردن و رسم مربع می توانید قسمت قابل برش شکلک خود را انتخاب نمایید. در قسمت سمت راست نیز پیش نمایش را مشاهده می کنید. بر روی دکمه "ایجاد شکلک" شکل برش زده انتخاب خواهد شد. نسخه برش زده شده به عنوان شکلک شما در داخل سایت نمایش داده خواهد شد.',
	'avatar:upload:success' => 'شکلک با موفقیت بارگزاری شد',
	'avatar:upload:fail' => 'بارگزاری شکلک شکست خورد',
	'avatar:resize:fail' => 'تغییر اندازه شکلک شکست خورد.',
	'avatar:crop:success' => 'برش شکلک با موفقیت انجام شد',
	'avatar:crop:fail' => 'برش شکلک با شکست مواجه شد',
	'avatar:remove:success' => 'شکلک با موفقیت حذف شد',
	'avatar:remove:fail' => 'خذف شکلک ما مشکل مواجه شد',

	'profile:edit' => 'ویرایش پروفایل',
	'profile:aboutme' => "درباره من",
	'profile:description' => "درباره من",
	'profile:briefdescription' => "توضیح مختصر",
	'profile:location' => "مکان",
	'profile:skills' => "مهارت ها",
	'profile:interests' => "علایق",
	'profile:contactemail' => "ایمیل",
	'profile:phone' => "شماره تلفن",
	'profile:mobile' => "شماره تلفن همراه",
	'profile:website' => "وبسایت",
	'profile:twitter' => "نام کاربری تویتر",
	'profile:saved' => "پروفایل شما با موفقیت ذخیره شد",

	'profile:field:text' => 'متن کوتاه',
	'profile:field:longtext' => 'فضای متن طولانی',
	'profile:field:tags' => 'برچسب ها',
	'profile:field:url' => 'آدرس وب',
	'profile:field:email' => 'آدرس ایمیل',
	'profile:field:location' => 'مکان',
	'profile:field:date' => 'تاریخ',

	'admin:appearance:profile_fields' => 'ویرایش اقلام پروفایل',
	'profile:edit:default' => 'ویرایش اقلام پروفایل',
	'profile:label' => "برچسب پروفایل",
	'profile:type' => "نوع پروفایل",
	'profile:editdefault:delete:fail' => 'حذف پروفایل با شکست مواجه شد',
	'profile:editdefault:delete:success' => 'فیلد پروفایل حذف شد',
	'profile:defaultprofile:reset' => 'اقلام پروفایل با موفقیت به مقادیر پیش فرض سیستم تغییر کردند',
	'profile:resetdefault' => 'تغییر اقلام پروفایل به پیش فرض سیستم',
	'profile:resetdefault:confirm' => 'آیا  قصد پاک کردن اقلام خصوصی سازی شده پروفایل را دارید؟',
	'profile:explainchangefields' => "شما می توانید به کمک فرم زیر اقلام پروفایل را تغییر دهید.\n\n به هر فیلد جدید یک برچسب بدهید و نوع آن را مشخص کنید (به عنوان مثال: متن، آدرس، برچسب) و دکمه \"افزودن\" را کلیک کنید. برای تغییر چینش فیلدها آنها را با موس به پایین و بالا هدایت کنید  برای ویرایش برچسب یک فیلد روی متن کنار آن کلیک کنید تا قابل ویرایش شود.\n\n هر موقع که خواستید می توانید این تغییرات را به حالت پیش فرض برگردانید، اما اطلاعاتی که برای این فیلدها وارد شده باشند از دست خواهند رفت.",
	'profile:editdefault:success' => 'فیلد جدید به پروفایل افزوده شد',
	'profile:editdefault:fail' => 'پروفایل پیش فرض امکان ذخیره شدن ندارد.',
	'profile:field_too_long' => 'با خاطر اینکه قسمت %s خیلی طولانی هست امکان ذخیره سازی اطلاعات پروفایل شما نیست.',
	'profile:noaccess' => "شما مجوز ویرایش این پروفایل را ندارید.",
	'profile:invalid_email' => '%s باید یک آدرس ایمیل معتبر باشد',


/**
 * Feeds
 */
	'feed:rss' => 'خوراک RSS این صفحه',
/**
 * Links
 */
	'link:view' => 'مشاهده لینک',
	'link:view:all' => 'مشاهده همه',


/**
 * River
 */
	'river' => "رود",
	'river:friend:user:default' => "%s الان دوست %s هست",
	'river:update:user:avatar' => '%s یک شکلک جدید دارد',
	'river:update:user:profile' => '%s پروفایل خودشان را بروزرسانی کردند',
	'river:noaccess' => 'شما مجوز ویرایش این مورد را ندارید',
	'river:posted:generic' => '%s منتشر شد',
	'riveritem:single:user' => 'یک کاربر',
	'riveritem:plural:user' => 'تعدادی از کاربران',
	'river:ingroup' => 'در گروه %s',
	'river:none' => 'هیچ فعالیتی نیست',
	'river:update' => 'برای %s بروزرسانی شد',
	'river:delete' => 'حذف این فعالیت',
	'river:delete:success' => 'مورد حذف شد',
	'river:delete:fail' => 'امکان حذف این مورد نیست',
	'river:delete:lack_permission' => 'شما مجوز حذف این فعالیت را ندارید',
	'river:can_delete:invaliduser' => 'امکان بررسی قابلیت حذف برای کاربر [%s] وجود ندارد چون کاربر موجود نیست.',
	'river:subject:invalid_subject' => 'کاربر نامعتبر',
	'activity:owner' => 'مشاهده فعالیت',

	'river:widget:title' => "فعالیت",
	'river:widget:description' => "نمایش آخرین فعالیت",
	'river:widget:type' => "نوع فعالیت",
	'river:widgets:friends' => 'فعالیت دوستان',
	'river:widgets:all' => 'همه فعالیت های سایت',

/**
 * Notifications
 */
	'notifications:usersettings' => "تنظیمات اعلان ها",
	'notification:method:email' => 'ایمیل',

	'notifications:usersettings:save:ok' => "تنظیمات اعلان ها با موفقیت ذخیره شد",
	'notifications:usersettings:save:fail' => "در ذخیره تنظیمات اعلان ها مشکلی به وجود آمده است",

	'notification:subject' => 'اعلان های %s',
	'notification:body' => 'مشاهده فعالیت جدید در %s',

/**
 * Search
 */

	'search' => "جستجو",
	'searchtitle' => "جستجو: %s",
	'users:searchtitle' => "جستجوی کاربران: %s",
	'groups:searchtitle' => "جستجو گروهها: %s",
	'advancedsearchtitle' => "%s نتیجه منطبق با %s",
	'notfound' => "هیچ نتیجه ای یافت نشد",
	'next' => "بعدی",
	'previous' => "قبلی",

	'viewtype:change' => "تغییر نوع لیست",
	'viewtype:list' => "نمایش لیستی",
	'viewtype:gallery' => "گالری",

	'tag:search:startblurb' => "آیتمهایی با برچسب شبیه: %s",

	'user:search:startblurb' => "کاربرانی شبیه: %s",
	'user:search:finishblurb' => "برای مشاهده بیشتر اینجا را کلیک کنید",

	'group:search:startblurb' => "گروههای شبیه:%s",
	'group:search:finishblurb' => "برای مشاهده بیشتر اینجا را کلیک کنید",
	'search:go' => 'برو',
	'userpicker:only_friends' => 'فقط دوستان',

/**
 * Account
 */

	'account' => "حساب کاربری",
	'settings' => "تنظیمات",
	'tools' => "ابزارها",
	'settings:edit' => 'ویرایش تنظیمات',

	'register' => "ثبت نام",
	'registerok' => "شما با موفقیت در %s ثبت شدید",
	'registerbad' => "ثبت نام شما به دلیل یک خطای ناشناخته با شکست مواجه شد.",
	'registerdisabled' => "ثبت نام توسط مدیر سیستم غیرفعال شده است.",
	'register:fields' => 'همه فیلدها اجباری هستند',

	'registration:notemail' => 'ظاهرا آدرس ایمیلی که شما معرفی کرده اید معتبر نیست.',
	'registration:userexists' => 'نام کاربری موجود هست',
	'registration:usernametooshort' => 'نام کاربری شما باید حداقل %u کاراکتر باشد.',
	'registration:usernametoolong' => 'نام کاربری شما خیلی طولانی هست. باید حداکثر %u کاراکتر باشد.',
	'registration:passwordtooshort' => 'کلمه عبور باید حداقل %u کاراکتر باشد.',
	'registration:dupeemail' => 'این آدرس ایمیل قبلا عضو شده است.',
	'registration:invalidchars' => 'متاسفم، نام کاربری حاوی کاراکتر %s می باشد که نامعتبر هست. کاراکترهای معتبر به این صورت هستند: %s',
	'registration:emailnotvalid' => 'متاسفانه آدرس ایمیل شما توسط سیستم معتبر شناخته نشد',
	'registration:passwordnotvalid' => 'متاسفانه کلمه عبور شما توسط سیستم معتبر شناخته نشد',
	'registration:usernamenotvalid' => 'متاسفانه کلمه عبور توسط سیستم معتبر شناخته نشد',

	'adduser' => "افزودن کاربر",
	'adduser:ok' => "شما با موفقیت یک کاربر جدید اضافه کردید",
	'adduser:bad' => "امکان ایجاد این کاربر نیست.",

	'user:set:name' => "تنظیمات نام حساب کاربری",
	'user:name:label' => "نام قابل نمایش",
	'user:name:success' => "نام با موفقیت تغییر پیدا کرد",
	'user:name:fail' => "امکان تغییر نام نیست",

	'user:set:password' => "کلمه عبور حساب کاربری",
	'user:current_password:label' => 'کلمه عبور فعلی',
	'user:password:label' => "کلمه عبور جدید",
	'user:password2:label' => "تکرار کلمه عبور",
	'user:password:success' => "کلمه عبور تغییر کرد",
	'user:password:fail' => "امکان تغییر کلمه عبور نیست.",
	'user:password:fail:notsame' => "کلمه عبور و تکرار آن مطابقت ندارد",
	'user:password:fail:tooshort' => "کلمه عبور خیلی کوتاه است",
	'user:password:fail:incorrect_current_password' => 'کلمه عبور فعلی اشتباه وارد شده است',
	'user:changepassword:unknown_user' => 'کاربر نامعتبر',
	'user:changepassword:change_password_confirm' => 'این کلمه عبور را تغییر خواهد داد',

	'user:set:language' => "تنظیمات زبان",
	'user:language:label' => "زبان",
	'user:language:success' => "تنظیمات زبان بروزرسانی شد",
	'user:language:fail' => "تنظیمات زبان ذخیره نشد",

	'user:username:notfound' => 'کاربر %s پیدا نشد',

	'user:password:lost' => 'فراموشی کلمه عبور',
	'user:password:changereq:success' => 'ایمیل حاوی کلمه عبور جدید با موفقیت ارسال شد',
	'user:password:changereq:fail' => 'امکان درخواست کلمه عبور جدید نیست',

	'user:password:text' => 'برای درخواست کلمه عبور جدید نام کاربری یا آدرس ایمیل خود را وارد کنید و دکمه درخواست را کلیک کنید',

	'user:persistent' => 'مرا به یاد بیاور',

	'walled_garden:welcome' => 'خوش آمدید به',

/**
 * Administration
 */
	'menu:page:header:administer' => 'مدیریت',
	'menu:page:header:configure' => 'تنظیم',
	'menu:page:header:develop' => 'توسعه',
	'menu:page:header:default' => 'سایر',

	'admin:view_site' => 'مشاهده سایت',
	'admin:loggedin' => 'وارد شده به عنوان %s',
	'admin:menu' => 'منو',

	'admin:configuration:success' => "تنظیمات شما ذخیره شد",
	'admin:configuration:fail' => "امکان ذخیره تنظیمات شما نیست",
	'admin:configuration:dataroot:relative_path' => 'امکان تنظیم "%s" به عنوان مسیر ذخیره داده ها نیست، مسیر باید مطلق باشد',
	'admin:configuration:default_limit' => 'تعداد موارد نمایش در یک صفحه باید حداقل 1 باشد',

	'admin:unknown_section' => 'قسمت مدیریت معتبر نیست.',

	'admin' => "مدیران",
	'admin:description' => "پنل مدیریت به شما اجازه کنترل همه جوانب سیستم را می دهد. از تنظیمات کاربران گرفته تا رفتار پلاگین ها. یکی از موارد زیر را جهت شروع انتخاب نمایید",

	'admin:statistics' => "آمار",
	'admin:statistics:overview' => 'مرور',
	'admin:statistics:server' => 'اطلاعات سرور',
	'admin:statistics:cron' => 'CRON',
	'admin:cron:record' => 'آخرین cron job  ها',
	'admin:cron:period' => 'دوره زمانی اجرای cron',
	'admin:cron:friendly' => 'آخرین اجرای موفق',
	'admin:cron:date' => 'تاریخ و زمان',
	'admin:cron:msg' => 'پیام',
	'admin:cron:started' => 'فعالیت زمانبندی شده "%s" در %s شروع شد',
	'admin:cron:complete' => 'فعالیت زمانبندی شده "%s" در %s تمام شد',

	'admin:appearance' => 'نحوه نمایش',
	'admin:administer_utilities' => 'ابزار',
	'admin:develop_utilities' => 'ابزار',
	'admin:configure_utilities' => 'ابزار',
	'admin:configure_utilities:robots' => 'Robots.txt',

	'admin:users' => "کاربران",
	'admin:users:online' => 'آنلاین ها',
	'admin:users:newest' => 'جدیدترین',
	'admin:users:admins' => 'مدیران',
	'admin:users:add' => 'افزودن کاربر جدید',
	'admin:users:description' => "این قسمت مدیریت به شما اجازه کنترل تنظیمات سایتتان را میدهد. یکی از موارد زیر را جهت شروع انتخاب نمایید",
	'admin:users:adduser:label' => "برای افزودن کاربر جدید اینجا کلیک کنید..",
	'admin:users:opt:linktext' => "تنظیمات کاربران",
	'admin:users:opt:description' => "تنظیمات کاربران و اطلاعات حساب کاربری",
	'admin:users:find' => 'جستجو',

	'admin:administer_utilities:maintenance' => 'حالت نگهداری سایت',
	'admin:upgrades' => 'ارتقاء ها',

	'admin:settings' => 'تنظیمات',
	'admin:settings:basic' => 'تنظیمات اولیه',
	'admin:settings:advanced' => 'تنظیمات پیشرفته',
	'admin:site:description' => "این قسمت مدیریت به شما اجازه کنترل تنظیمات عمومی سایتتان را میدهد. یکی از موارد زیر را جهت شروع انتخاب نمایید",
	'admin:site:opt:linktext' => "تنظیمات سایت",
	'admin:settings:in_settings_file' => 'این مورد در php.ini تنظیم شده است',

	'admin:legend:security' => 'امنیت',
	'admin:site:secret:intro' => 'Elgg برای ایجاد توکن های امنیتی از یک کلید استفاده میکند',
	'admin:site:secret_regenerated' => "کلید سایت شما دوباره تولید شد",
	'admin:site:secret:regenerate' => "تولید مجدد کلید امنیتی",
	'admin:site:secret:regenerate:help' => "نکته: تولید مجدد کلید امنیتی باعث باطل شدن \"مرا به یاد بیاور\" و \"ایمیل های اعتبارسنجی\" برخی از کاربران می شود.",
	'site_secret:current_strength' => 'امنیت کلید',
	'site_secret:strength:weak' => "ضعیف",
	'site_secret:strength_msg:weak' => "شدیدا پیشنهاد می شود که کلید امنیتی سایتتان را مجددا تولید کنید",
	'site_secret:strength:moderate' => "متوسط",
	'site_secret:strength_msg:moderate' => "شدیدا پیشنهاد می شود که برای امنیت بیشتر کلید امنیتی سایتتان را مجددا تولید کنید",
	'site_secret:strength:strong' => "قوی",
	'site_secret:strength_msg:strong' => "کلید امنیتی شما در حال حاضر به اندازه کافی قوی هست. نیاز به تولید مجدد آن نیست.",

	'admin:dashboard' => 'داشبورد',
	'admin:widget:online_users' => 'کاربران آنلاین',
	'admin:widget:online_users:help' => 'لیست کاربرانی که هم اکنون در سایت هستن',
	'admin:widget:new_users' => 'کاربران جدید',
	'admin:widget:new_users:help' => 'لیست جدیدترین کاربران',
	'admin:widget:banned_users' => 'کاربران اخراج شده',
	'admin:widget:banned_users:help' => 'لیست کاربران اخراج شده',
	'admin:widget:content_stats' => 'آمار محتوا',
	'admin:widget:content_stats:help' => 'محتوای تولید شده توسط کاربرانتان را ردگیری کنید',
	'admin:widget:cron_status' => 'وضعیت cron',
	'admin:widget:cron_status:help' => 'نمایش وضعیت آخرین اجرای cron job',
	'widget:content_stats:type' => 'نوع محتوا',
	'widget:content_stats:number' => 'عدد',

	'admin:widget:admin_welcome' => 'خوش آمدید',
	'admin:widget:admin_welcome:help' => "توضیح مختصری برای محیط مدیریت Elgg",
	'admin:widget:admin_welcome:intro' =>
'به Elgg خوش آمدید. هم اکنون شما به داشبورد مدیریت نگاه میکنید. این داشبورد برای بررسی رخدادهای سایت کاربرد دارد.',

	'admin:widget:admin_welcome:admin_overview' =>
"انتقال در قسمت های مختلف محیط مدیریت در منوی سمت راست موجود می باشد. این منو به سه قسمت دسته بندی شده است

	<dl>
		<dt>مدیریت</dt><dd>کارهای روزانه مانند بررسی محتویات گزارش شده و مشاهده کاربران آنلاین و آمار سایت.</dd>
		<dt>تنظیمات</dt><dd>وظایفی مانند تنظیم نام سایت یا فعال کردن پلاگین ها.</dd>
		<dt>توسعه</dt><dd>برای توسعه دهندگانی که قصد ساخت پلاگین یا طراحی تم دارند (نیاز به پلاگین توسعه دهنده دارد)</dd>
	</dl>
	",

	// argh, this is ugly
	'admin:widget:admin_welcome:outro' => '<br/>حتما از منابعی که در پایین صفحه لینک شده اند استفاده کنید و از Elgg متشکر باشید!',

	'admin:widget:control_panel' => 'پنل مدیریت',
	'admin:widget:control_panel:help' => "اجازه دسترسی راحت به تنظیمات کلی را می دهد",

	'admin:cache:flush' => 'خالی کردن کش',
	'admin:cache:flushed' => "کش سایت خالی شد",

	'admin:footer:faq' => 'سوالات متداول مدیریت',
	'admin:footer:manual' => 'راهنمای مدیریت',
	'admin:footer:community_forums' => 'فاروم جامعه Elgg',
	'admin:footer:blog' => 'وبلاگ Elgg',

	'admin:plugins:category:all' => 'همه پلاگین ها',
	'admin:plugins:category:active' => 'پلاگین های فعال',
	'admin:plugins:category:inactive' => 'پلاگین های غیرفعال',
	'admin:plugins:category:admin' => 'مدیر',
	'admin:plugins:category:bundled' => 'متصل',
	'admin:plugins:category:nonbundled' => 'غیرمتصل',
	'admin:plugins:category:content' => 'محتوا',
	'admin:plugins:category:development' => 'توسعه',
	'admin:plugins:category:enhancement' => 'پیشرفت',
	'admin:plugins:category:api' => 'سرویس/واسط',
	'admin:plugins:category:communication' => 'ارتباط',
	'admin:plugins:category:security' => 'امنیت و هرزنامه',
	'admin:plugins:category:social' => 'اجتماعی',
	'admin:plugins:category:multimedia' => 'چندرسانه ای',
	'admin:plugins:category:theme' => 'تم ها',
	'admin:plugins:category:widget' => 'ابزار ها',
	'admin:plugins:category:utility' => 'ابزار',

	'admin:plugins:markdown:unknown_plugin' => 'پلاگین ناشناخته',
	'admin:plugins:markdown:unknown_file' => 'فایل ناشناخته',

	'admin:notices:could_not_delete' => 'امکان حذف اعلان نیست',
	'item:object:admin_notice' => 'اعلان مدیر',

	'admin:options' => 'گزینه های مدیر',

/**
 * Plugins
 */

	'plugins:disabled' => 'به خاطر اینکه فایلی با نام "disabled" در پوشه ماژول ها هست امکان بارگزاری پلاگین ها نیست.',
	'plugins:settings:save:ok' => "تنظیمات برای پلاگین %s با موفقیت ذخیره شد",
	'plugins:settings:save:fail' => "برای ذخیره تنظیمات پلاگین %s مشکلی به وجود آمد",
	'plugins:usersettings:save:ok' => "تنظیمات کاربر برای پلاگین %s با موفقیت ذخیره شد",
	'plugins:usersettings:save:fail' => "برای ذخیره تنظیمات کاربر برای پلاگین %s مشکلی به وجود آمده است.",
	'item:object:plugin' => 'پلاگین ها',

	'admin:plugins' => "پلاگین ها",
	'admin:plugins:activate_all' => 'فعالسازی همه',
	'admin:plugins:deactivate_all' => 'غیرفعال کردن همه',
	'admin:plugins:activate' => 'فعال کرد',
	'admin:plugins:deactivate' => 'غیرفعال کرد',
	'admin:plugins:description' => "این پنل مدیریت به شما اجازه کنترل و تنظیم ابزار نصب شده روی سایتتان را می دهد",
	'admin:plugins:opt:linktext' => "تنظیم ابزار",
	'admin:plugins:opt:description' => "تنظیم ابزار نصب شده روی سایت",
	'admin:plugins:label:id' => "شناسه",
	'admin:plugins:label:name' => "نام",
	'admin:plugins:label:author' => "نویسنده",
	'admin:plugins:label:copyright' => "حقوق مولف",
	'admin:plugins:label:categories' => 'دسته بندی ها',
	'admin:plugins:label:licence' => "مجوزها",
	'admin:plugins:label:website' => "URL",
	'admin:plugins:label:info' => "اطلاعات",
	'admin:plugins:label:files' => "فایلها",
	'admin:plugins:label:resources' => "منابع",
	'admin:plugins:label:screenshots' => "تصاویر",
	'admin:plugins:label:repository' => "کد",
	'admin:plugins:label:bugtracker' => "گزارش مشکل",
	'admin:plugins:label:donate' => "کمک مالی",
	'admin:plugins:label:moreinfo' => 'اطلاعات بیشتر',
	'admin:plugins:label:version' => 'نسخه',
	'admin:plugins:label:location' => 'مکانمکان',
	'admin:plugins:label:contributors' => 'مولفان',
	'admin:plugins:label:contributors:name' => 'نام',
	'admin:plugins:label:contributors:email' => 'ایمیل',
	'admin:plugins:label:contributors:website' => 'وبسایت',
	'admin:plugins:label:contributors:username' => 'نام کاربری در جامعه',
	'admin:plugins:label:contributors:description' => 'شرح',
	'admin:plugins:label:dependencies' => 'پیش نیازها',

	'admin:plugins:warning:unmet_dependencies' => 'این پلاگین پیش نیازهایی دار که در حال حاضر موجود نسیتند و نمی تواند فعال شود. پیش نیاز ها را در قسمت اطلاعات بیشتر چک کنید',
	'admin:plugins:warning:invalid' => 'این پلاگین نامعتبر هست: %s',
	'admin:plugins:warning:invalid:check_docs' => 'آدرس <a href="http://learn.elgg.org/en/stable/appendix/faqs.html">مستندات Elgg</a>  را برای رفع خطا چک کنید.',
	'admin:plugins:cannot_activate' => 'امکان فعالسازی نیست',
	'admin:plugins:cannot_deactivate' => 'امکان غیرفعال کردن نیست',
	'admin:plugins:already:active' => 'پلاگین (ها)ی انتخاب شده هم اکنون فعال هستند.',
	'admin:plugins:already:inactive' => 'پلاگین (ها) ی انتخاب شده هم اکنون غیرفعال هستند.',

	'admin:plugins:set_priority:yes' => "مرتب شد %s.",
	'admin:plugins:set_priority:no' => "امکان مرتبسازی %s نیست",
	'admin:plugins:set_priority:no_with_msg' => "امکان مرتب سازی %s نیست. خطا:%s",
	'admin:plugins:deactivate:yes' => "%s غیرفعال شد",
	'admin:plugins:deactivate:no' => "امکان غیرفعالسازی %s نیست",
	'admin:plugins:deactivate:no_with_msg' => "امکان غیرفعالسازی %s نیست. علت: %s",
	'admin:plugins:activate:yes' => "%s فعال شد.",
	'admin:plugins:activate:no' => "امکان فعالسازی %s نیست",
	'admin:plugins:activate:no_with_msg' => "امکان فعالسازی %s نیست. علت:%s",
	'admin:plugins:categories:all' => 'همه دسته بندی ها',
	'admin:plugins:plugin_website' => 'وبسایت پلاگین',
	'admin:plugins:author' => '%s',
	'admin:plugins:version' => 'نسخه %s',
	'admin:plugin_settings' => 'تنظیمات پلاگین',
	'admin:plugins:warning:unmet_dependencies_active' => 'این پلاگین فعال شده است ولی همه پیش نیازهای آن درسیستم موجود نیست. ممکن است در استفاده از آن با مشکل مواجه شوید. برای مشاهده جزئیات قسمت "اطلاعات بیشتر" را کلیک کنید.',

	'admin:plugins:dependencies:type' => 'نوع',
	'admin:plugins:dependencies:name' => 'نامنا',
	'admin:plugins:dependencies:expected_value' => 'مقدار مورد نظر',
	'admin:plugins:dependencies:local_value' => 'مقدار واقعی',
	'admin:plugins:dependencies:comment' => 'دیدگاه',

	'admin:statistics:description' => "این یک دید کلی از آمار سایت شماست. برای مشاهده آمار جزئی تر، ویژگی مدیریت حرفه ای نیز موجود است",
	'admin:statistics:opt:description' => "نمایش اطلاعات آمار در مورد کاربران و قسمت های سایت شما",
	'admin:statistics:opt:linktext' => "مشاهده آمار",
	'admin:statistics:label:basic' => "آمار اولیه سایت",
	'admin:statistics:label:numentities' => "موجودیت های سایت",
	'admin:statistics:label:numusers' => "تعداد کاربران",
	'admin:statistics:label:numonline' => "تعداد کاربران آنلاین",
	'admin:statistics:label:onlineusers' => "کاربرانی که هم اکنون آنلاینند",
	'admin:statistics:label:admins'=>"مدیرها",
	'admin:statistics:label:version' => "نسخه Elgg",
	'admin:statistics:label:version:release' => "نسخه",
	'admin:statistics:label:version:version' => "نسخه",

	'admin:server:label:php' => 'PHP',
	'admin:server:label:web_server' => 'وب سرور',
	'admin:server:label:server' => 'سرور',
	'admin:server:label:log_location' => 'محل ثبت لاگ',
	'admin:server:label:php_version' => 'نسخه PHP',
	'admin:server:label:php_ini' => 'آدرس فایل php.ini',
	'admin:server:label:php_log' => 'لاگ php',
	'admin:server:label:mem_avail' => 'حافظه موجود',
	'admin:server:label:mem_used' => 'حافظه استفاده شده',
	'admin:server:error_log' => "لاگ خطای وب سرور",
	'admin:server:label:post_max_size' => 'بیشترین اندازه درخواست POST',
	'admin:server:label:upload_max_filesize' => 'حداکثر حجم قابل بارگزاری',
	'admin:server:warning:post_max_too_small' => '(نکته: مقدار post_max_size باید بیشتر از این مقدار باشد تا از بارگزاری فایل پشتیبانی شود)',
	'admin:server:label:memcache' => 'Memcache',
	'admin:server:memcache:inactive' => '
		Memcacheروی این سرور نصب نشده است  و یا در تنظیمات Elgg فعال نشده است.
		برای بالابردن کارایی پیشنهاد می شود که memcache. را فعال کنید
	',

	'admin:user:label:search' => "جستجوی کاربران:",
	'admin:user:label:searchbutton' => "جستجو",

	'admin:user:ban:no' => "امکان اخراج کاربر نیست",
	'admin:user:ban:yes' => "کاربر اخراج شد",
	'admin:user:self:ban:no' => "شما نمی توانید خودتان را اخراج کنید",
	'admin:user:unban:no' => "امکان رفع اخراج کاربر نیست",
	'admin:user:unban:yes' => "کاربر رفع اخراج شد",
	'admin:user:delete:no' => "امکان حذف کاربر نیست",
	'admin:user:delete:yes' => "کاربر %s حذف شد",
	'admin:user:self:delete:no' => "شما نمی تواید خودتان را حذف کنید",

	'admin:user:resetpassword:yes' => "کلمه عبور تغییر کرد، به کاربر اطلاع رسانی شد",
	'admin:user:resetpassword:no' => "امکان بازنشانی کلمه عبور نیست",

	'admin:user:makeadmin:yes' => "اکنون این کاربر مدیر است",
	'admin:user:makeadmin:no' => "امکان مدیر شدن این کاربر نیست",

	'admin:user:removeadmin:yes' => "کاربر دیگر مدیر نیست",
	'admin:user:removeadmin:no' => "امکان حذف دسترسی مدیر از این کاربر نیست",
	'admin:user:self:removeadmin:no' => "شما نمی توانید مجوز مدیریت خودتان را حذف کنید",

	'admin:appearance:menu_items' => 'موارد منو',
	'admin:menu_items:configure' => 'تنظیم موارد منوی اصلی',
	'admin:menu_items:description' => 'انتخاب کنید که کدام منو به عنوان لینکهای برگزیده نمایش داده شود. موارد استفاده نشده به عنوان "بیشتر" آورده خواهند شد.',
	'admin:menu_items:hide_toolbar_entries' => 'آیا میخواهید این لینکها را از نوار ابزار حذف کنید؟',
	'admin:menu_items:saved' => 'موارد منو ذخیره شد',
	'admin:add_menu_item' => 'افزودن یک آیتم خصوصی سازی شده به منو',
	'admin:add_menu_item:description' => 'نام نمایش و آدرس را پر کنید که منوی شخصی سازی شده به منوی شما اضافه شود',

	'admin:appearance:default_widgets' => 'ابزارک پیشفرض',
	'admin:default_widgets:unknown_type' => 'نوع ابزارک ناشناخته است',
	'admin:default_widgets:instructions' => 'افزودن، حذف، تغییر مکان و تنظیم ابزارک های پیش فرض برای صفحه ابزارک انتخاب شده.
این تغییرات فقط بر روی کاربران جدید سایت اعمال خواهد شد.',

	'admin:robots.txt:instructions' => "ویرایش فایل robots.txt این سایت",
	'admin:robots.txt:plugins' => "پلاگین ها به فایل robots.txt اضافه می شوند",
	'admin:robots.txt:subdir' => "فایل robots.txt کرا نخواهند کرد، به خاطر اینکه Elgg در یک زیرپوشه نصب شده است",
	'admin:robots.txt:physical' => "فایل robots.txt شما کار نخواهد کرد به این خاطر که از قبل یک فایل موجود هست.",

	'admin:maintenance_mode:default_message' => 'سایت در دست تعمیر است',
	'admin:maintenance_mode:instructions' => 'حالت دردست تعمیر باید برای بروزرسانی و تغییرات اساسی سایت استفاده شود.
وقتی این حالت فعال باشد فقط مدیران امکان نمایش و ورود به سایت را دارند.',
	'admin:maintenance_mode:mode_label' => 'حالت نگهداری سایت',
	'admin:maintenance_mode:message_label' => 'پیامی که در هنگام تعمیرات سایت به کاربران نشان داده می شود',
	'admin:maintenance_mode:saved' => 'تنظیمات حالت دردست تعمیر ذخیره شد',
	'admin:maintenance_mode:indicator_menu_item' => 'سایت در درست تعمیر است',
	'admin:login' => 'ورود مدیر',

/**
 * User settings
 */

	'usersettings:description' => "پنل مدیریت کاربران به شما اجازه کنترل همه اطلاعات شخصی را می دهد. از تنظیمات کاربران گرفته تا رفتار پلاگین ها. یکی از موارد زیر را جهت شروع انتخاب نمایید",

	'usersettings:statistics' => "آمار شما",
	'usersettings:statistics:opt:description' => "نمایش اطلاعات آمار در مورد کاربران و قسمت های سایت شما",
	'usersettings:statistics:opt:linktext' => "آمار حساب کاربری",
	
	'usersettings:statistics:login_history' => "تاریخچه ورود",
	'usersettings:statistics:login_history:date' => "تاریخ",
	'usersettings:statistics:login_history:ip' => "آدرس IP",

	'usersettings:user' => "تنظیمات %s",
	'usersettings:user:opt:description' => "این به شما اجازه کنترل تنظیمات کاربر را می دهد",
	'usersettings:user:opt:linktext' => "تغییر تنظیمات شما",

	'usersettings:plugins' => "ابزارها",
	'usersettings:plugins:opt:description' => "تنظیم ابزارهای فعال",
	'usersettings:plugins:opt:linktext' => "تنظیم ابزارها",

	'usersettings:plugins:description' => "این پنل مدیریت به شما اجازه کنترل و تنظیم اطلاعات شخصی برای ابزارهایی که توسط مدیر روی سایت نصب شده را می دهد",
	'usersettings:statistics:label:numentities' => "محتوای شما",

	'usersettings:statistics:yourdetails' => "جزئیات شما",
	'usersettings:statistics:label:name' => "نام کامل",
	'usersettings:statistics:label:email' => "ایمیل",
	'usersettings:statistics:label:membersince' => "عضو از",
	'usersettings:statistics:label:lastlogin' => "آخرین ورود",

/**
 * Activity river
 */

	'river:all' => 'همه فعالیت های سایت',
	'river:mine' => 'فعالیت های من',
	'river:owner' => 'فعالیت های %s',
	'river:friends' => 'فعالیت دوستان',
	'river:select' => 'نماش %s',
	'river:comments:more' => '+%u بیشتر',
	'river:comments:all' => 'نمایش همه %u دیدگاه',
	'river:generic_comment' => 'دیدگاههای %s%s',

	'friends:widget:description' => "نمایش تعدادی از دوستان شما",
	'friends:num_display' => "تعداد دوستان برای نمایش",
	'friends:icon_size' => "اندازه آیکن",
	'friends:tiny' => "ریز",
	'friends:small' => "کوچک",

/**
 * Icons
 */

	'icon:size' => "اندازه آیکن",
	'icon:size:topbar' => "ستون بالا",
	'icon:size:tiny' => "ریز",
	'icon:size:small' => "کوچک",
	'icon:size:medium' => "متوسط",
	'icon:size:large' => "بزرگ",
	'icon:size:master' => "خیلی بزرگ",

/**
 * Generic action words
 */

	'save' => "ذخیره",
	'reset' => 'بازنشانی',
	'publish' => "انتشار",
	'cancel' => "کنسل",
	'saving' => "درحال ذخیره سازی",
	'update' => "بروزرسانی",
	'preview' => "پیش نمایش",
	'edit' => "ویرایش",
	'delete' => "حذف",
	'accept' => "پذیرش",
	'reject' => "رد",
	'decline' => "مخالفت",
	'approve' => "تایید",
	'activate' => "فعالسازی",
	'deactivate' => "غیرفعالسازی",
	'disapprove' => "عدم تایید",
	'revoke' => "لغو کردن",
	'load' => "بارگیری",
	'upload' => "بارگزاری",
	'download' => "دانلود",
	'ban' => "ممنوع کردن",
	'unban' => "لغو ممنوعیت",
	'banned' => "ممنوع شده",
	'enable' => "فعال",
	'disable' => "غیرفعال",
	'request' => "درخواست",
	'complete' => "کامل کرد",
	'open' => 'باز کردن',
	'close' => 'بست',
	'hide' => 'مخفی کردن',
	'show' => 'نمایش',
	'reply' => "پاسخ",
	'more' => 'بیشتر',
	'more_info' => 'اطلاعات بیشتر',
	'comments' => 'نظر',
	'import' => 'درونگذاری',
	'export' => 'استخراج',
	'untitled' => 'بدون عنوان',
	'help' => 'راهنما',
	'send' => 'ارسال',
	'post' => 'ارسال',
	'submit' => 'ارسال',
	'comment' => 'دیدگاه',
	'upgrade' => 'ارتقا',
	'sort' => 'مرتبسازی',
	'filter' => 'فیلتر',
	'new' => 'جدید',
	'add' => 'افزودن',
	'create' => 'ایجاد',
	'remove' => 'حذف',
	'revert' => 'برگشت',

	'site' => 'سایت',
	'activity' => 'فعالیت',
	'members' => 'اعضا',
	'menu' => 'منو',

	'up' => 'بالا',
	'down' => 'پایین',
	'top' => 'بالاترین',
	'bottom' => 'انتها',
	'right' => 'راست',
	'left' => 'چپ',
	'back' => 'برگشت',

	'invite' => "دعوت کرد",

	'resetpassword' => "بازنشانی کلمه عبور",
	'changepassword' => "تغییر کلمه عبور",
	'makeadmin' => "تعیین به عنوان مدیر",
	'removeadmin' => "حذف مدیر",

	'option:yes' => "بلی",
	'option:no' => "خیر",

	'unknown' => 'ناشناخته',
	'never' => 'هرگز',

	'active' => 'فعالسازی',
	'total' => 'جمع کل',

	'ok' => 'تایید',
	'any' => 'هرچیز',
	'error' => 'خطا',

	'other' => 'سایر',
	'options' => 'گزینه ها',
	'advanced' => 'پیشرفته',

	'learnmore' => "برای یادگیری بیشتر اینجا را کلیک کنید",
	'unknown_error' => 'خطای ناشناخته',

	'content' => "مختوا",
	'content:latest' => 'آخرین فعالیت',
	'content:latest:blurb' => 'یا، اینجا را کلیک کنید تا آخرین فعالیت ها در داخل سایت را مشاهده کنید',

	'link:text' => 'مشاهده لینک',

/**
 * Generic questions
 */

	'question:areyousure' => 'آیا مطمئنید؟',

/**
 * Status
 */

	'status' => 'وضعیت',
	'status:unsaved_draft' => 'پیش نویس ذخیره نشده',
	'status:draft' => 'پیش نویس',
	'status:unpublished' => 'منتشر نشده',
	'status:published' => 'منتشرشده',
	'status:featured' => 'ویژه',
	'status:open' => 'باز کردن',
	'status:closed' => 'بسته',

/**
 * Generic sorts
 */

	'sort:newest' => 'جدیدترین',
	'sort:popular' => 'رایج',
	'sort:alpha' => 'به ترتیب حروف الفبا',
	'sort:priority' => 'اولویت',

/**
 * Generic data words
 */

	'title' => "عنوان",
	'description' => "شرح",
	'tags' => "برچسب ها",
	'all' => "همه",
	'mine' => "مال من",

	'by' => 'توسط',
	'none' => 'هیچ',

	'annotations' => "تفسیر",
	'relationships' => "روابط",
	'metadata' => "ابر داده ها",
	'tagcloud' => "برچسب ابر",

	'on' => 'در',
	'off' => 'خاموش',

/**
 * Entity actions
 */

	'edit:this' => 'ویرایش این',
	'delete:this' => 'حذف این',
	'comment:this' => 'دیدگاه در این',

/**
 * Input / output strings
 */

	'deleteconfirm' => "آیا مطمئنید که میخواهید این آیتم را حذف کنید؟",
	'deleteconfirm:plural' => "آیا مطمئنید که میخواهید این آیتم ها را حذف کنید؟",
	'fileexists' => "هم اکنون یک فایل بارگزاری شده است. برای جایگزینی آن را در زیر انتخاب کنید:",

/**
 * User add
 */

	'useradd:subject' => 'حساب کاربری ساخته شد',
	'useradd:body' => '
%s
یک کحساب کاربری برای %s ساخته شد. برای ورود به:

%s

مراجعه کنید و با این مشخصات کاربری واردشوید:

نام کاربری: %s
کلمه عبور: %s

شدیدا پیشنهاد میکنیم هنگامی که وارد شدید، کلمه عبورتان را تغییر دهید.
',

/**
 * System messages
 */

	'systemmessages:dismiss' => "برای پنهان کردن کلیک کنید",


/**
 * Import / export
 */

	'importsuccess' => "ورود داده ها با موفقیت انجام شد",
	'importfail' => "ورود داده ها از OpenDD با شکست مواجه شد",

/**
 * Time
 */

	'friendlytime:justnow' => "هم اکنون",
	'friendlytime:minutes' => "%s دقیقه پیش",
	'friendlytime:minutes:singular' => "یک دقیقه پیش",
	'friendlytime:hours' => "%s ساعت پیش",
	'friendlytime:hours:singular' => "یک ساعت پیش",
	'friendlytime:days' => " %s روز پیش",
	'friendlytime:days:singular' => "دیروز",
	'friendlytime:date_format' => 'j F Y @ g:ia',

	'friendlytime:future:minutes' => "تا %s دقیقه",
	'friendlytime:future:minutes:singular' => "تا یک دقیقه",
	'friendlytime:future:hours' => "تا %s ساعت",
	'friendlytime:future:hours:singular' => "تا یک ساعت",
	'friendlytime:future:days' => "تا %s روز",
	'friendlytime:future:days:singular' => "فردا",

	'date:month:01' => 'ژانویه %s',
	'date:month:02' => 'فوریه %s',
	'date:month:03' => 'مارس %s',
	'date:month:04' => 'آوریل %s',
	'date:month:05' => 'می %s',
	'date:month:06' => 'ژوئن',
	'date:month:07' => 'جولای %s',
	'date:month:08' => 'آگوست %s',
	'date:month:09' => 'سپتامبر %s',
	'date:month:10' => 'اکتبر %s',
	'date:month:11' => 'نوامبر %s',
	'date:month:12' => 'دسامبر %s',
	
	'date:month:short:01' => 'ژانویه %s',
	'date:month:short:02' => 'فوریه %s',
	'date:month:short:03' => 'مارس %s',
	'date:month:short:04' => 'آوریل %s',
	'date:month:short:05' => 'می %s',
	'date:month:short:06' => 'ژوئن %s',
	'date:month:short:07' => 'جولای %s',
	'date:month:short:08' => 'آگوست %s',
	'date:month:short:09' => 'سپتامبر %s',
	'date:month:short:10' => 'اکتبر %s',
	'date:month:short:11' => 'نوامبر %s',
	'date:month:short:12' => 'دسامبر %s',

	'date:weekday:0' => 'یکشنبه',
	'date:weekday:1' => 'دوشنبه',
	'date:weekday:2' => 'سه شنبه',
	'date:weekday:3' => 'چهارشنبه',
	'date:weekday:4' => 'پنج شنبه',
	'date:weekday:5' => 'جمعه',
	'date:weekday:6' => 'شنبه',

	'date:weekday:short:0' => 'یکشنبه',
	'date:weekday:short:1' => 'دوشنبه',
	'date:weekday:short:2' => 'سه شنبه',
	'date:weekday:short:3' => 'چهارشنبه',
	'date:weekday:short:4' => 'پنج شنبه',
	'date:weekday:short:5' => 'جمعه',
	'date:weekday:short:6' => 'شنبه',

	'interval:minute' => 'هر دقیقه',
	'interval:fiveminute' => 'هر پنج دقیقه',
	'interval:fifteenmin' => 'هر پانزده دقیقه',
	'interval:halfhour' => 'هر نیم ساعت',
	'interval:hourly' => 'ساعتی',
	'interval:daily' => 'روزانه',
	'interval:weekly' => 'هفتگی',
	'interval:monthly' => 'ماهانه',
	'interval:yearly' => 'سالانه',
	'interval:reboot' => 'هنگام شروع مجدد',

/**
 * System settings
 */

	'installation:sitename' => "نام سایت شما:",
	'installation:sitedescription' => "توضیح مختصر از سایت (اختیاری):",
	'installation:wwwroot' => "آدرس سایت:",
	'installation:path' => "مسیر کامل نصب Elgg :",
	'installation:dataroot' => "مسیر کامل پوشه داده ها:",
	'installation:dataroot:warning' => "شما باید این پوشه را دستی ایجاد کنید. و باید در مسیری بجز مسیر نصب Elgg باشد.",
	'installation:sitepermissions' => "مجوزهای دسترسی پیشفرض",
	'installation:language' => "زبان پیش فرض سایت شما:",
	'installation:debug' => "کنترل حجم داده هایی که در سرور لاگ می شوند.",
	'installation:debug:label' => "سطح لاگ:",
	'installation:debug:none' => 'لاگ نکن (پیشنهاد می شود)',
	'installation:debug:error' => 'فقط خطاهای بحرانی را لاگ کن',
	'installation:debug:warning' => 'فقط خطاها و هشدارها را لاگ کن',
	'installation:debug:notice' => 'همه  خطاها ، هشدارها و اعلان ها را لاگ کن',
	'installation:debug:info' => 'همه چیز را لاگ کن',

	// Walled Garden support
	'installation:registration:description' => 'ثبت نام کاربران به صورت پیش فرض فعال است. در صورتی که نمیخواهید افراد در سایتتان ثبت نام کنند این گزینه را غیرفعال کنید.',
	'installation:registration:label' => 'اجازه ثبت نام به کاربران جدید',
	'installation:walled_garden:description' => 'با فعال کردن این گزینه افرادی که عضو سایت شما نیستند امکان مشاهده سایت شما را نخواهند داشت و فقط صفحات عمومی را خواهند دید (مانند ثبت نام و ورود)',
	'installation:walled_garden:label' => 'محدود کردن صفحات به کاربران وارد شده',

	'installation:view' => "نمایه پیش فرص سایتتان را انتخاب کنید، در صورت عدم انتخاب نمایه پیش فرض نمایش داده خواهد شد (می توانید حالت پیش فرض را نگه دارید)",

	'installation:siteemail' => "ایمیل سایت (برای ارسال ایمیل های سیستمی استفاده میشود)",
	'installation:default_limit' => "تعداد پیش فرض موارد قابل نمایش در صفحه",

	'admin:site:access:warning' => "این تنظیمات حریم شخصی است که به کاربران هنگام ایجاد محتوای جدید پیشنهاد داده می شود. تغییر دادن آن مجوز دسترسی به محتوا را تغییر نخواهد داد.",
	'installation:allow_user_default_access:description' => "فعال کردن این مورد به کاربران اجاده میدهد که تنظیمان حریم شخصی پیشنهادی خودشان را در مقابل پیشنهاد سیستم استفاده کنند.",
	'installation:allow_user_default_access:label' => "اجازه دادن دسترسی پیشفرض کاربر",

	'installation:simplecache:description' => "ابزار SIMPLE CACHE سرعت سیستم شما را با کش کردن موارد ایستا مانند CSS و JAVASCRIPT ها بالا می برد.",
	'installation:simplecache:label' => "استفاده از SIMPLE CACHE (پیشنهاد می شود)",

	'installation:cache_symlink:description' => "لینک اتصال به پوشه کش، به سرور اجازه می دهد که محتوای ایستای سایت را در آن ذخیره کندکه باعث بالارفتن کارایی سایت می شود.",
	'installation:cache_symlink:label' => "استفاده از لینک پوشه کش (پیشنهاد می شود)",
	'installation:cache_symlink:warning' => "لینک پوشه کش برقرار شد. اگر به هر دلیلی خواستید این لینک را حذف کنید، کل پوشه را از سرور خود حذف نمایید.",
	'installation:cache_symlink:paths' => 'لینک درست فعال شده باید به این لینک باشد: <i>%s</i> to <i>%s</i>',
	'installation:cache_symlink:error' => "به خاطر تنظیمات سرور شما امکان برقرار کردن لینک پوشه کش نیست. لطفا به مستنداد فعالسازی لینک مراجعه نمایید.",

	'installation:minify:description' => "ابزار SIMPLE CACHE همچنین با فشرده سازی JAVASCRIPT و  CSS ها کارایی  و سرعت را بالا می برد ( برای این کار این ابزار باید فعال باشد)",
	'installation:minify_js:label' => "JAVASCRIPT را فشرده کن (پیشنهاد می شود)",
	'installation:minify_css:label' => "CSS را فشرده کن (پیشنهاد می شود)",

	'installation:htaccess:needs_upgrade' => "شما باید فایل .htaccess خود را بروزرسانی کنید که مسیر درج شده در پارامترهای GET به __elgg_uri تغییر کند (شما می توانید از فایل install/config/htaccess.dist به عنوان نمونه استفاده کنید)",
	'installation:htaccess:localhost:connectionfailed' => "Elgg نمی تواند به خودش متصل شود تا مسیرها را تست کند. ابتاد مطمئن شوید که curl روی سرور شما نصب شده است و آدرس آی پی سرور در فایروال محدود نشده است.",

	'installation:systemcache:description' => "کش سیستم زمان بارگیری Elgg را با کش کردن داده ها پایین می آورد.",
	'installation:systemcache:label' => "استفاده از کش سیستم (پیشنهاد می شود)",

	'admin:legend:system' => 'سیستم',
	'admin:legend:caching' => 'کش',
	'admin:legend:content_access' => 'دسترس محتوا',
	'admin:legend:site_access' => 'دسترسی سایت',
	'admin:legend:debug' => 'رفع خطا و لاگ',

	'upgrading' => 'ارتقاء..',
	'upgrade:core' => 'نسخه Elgg شما ارتقاء یافت',
	'upgrade:unlock' => 'ارتقاء را فعال ک',
	'upgrade:unlock:confirm' => "بانک اطلاعاتی برای یک ارتقاء دیگر قفل شده است. اجرای چندین ارتقا به صورت همزمان خطرناک است. فقط در صورتی که ارتقاء دیگری در حال انجام نیست ادامه دهید. بازکردن قفل بانک اطلاعاتی؟؟",
	'upgrade:locked' => "امکان ارتقاء نیست. یک ارتقاء دیگر درحال اجراست. برای حذف قفل ارتقاء از قسمت مدیریت اقدام کنید",
	'upgrade:unlock:success' => "قفل ارتقاء با موفقیت برداشته شد",
	'upgrade:unable_to_upgrade' => 'امکان ارتقاء نیست',
	'upgrade:unable_to_upgrade_info' =>
		'این نسخه امکان ارتقاء ندارد. به خاطر مواردی که در
هسته اصلی Elgg مشاهده شد. این موارد از رده خارج شده اند و نباید وجود داشته باشند.
تا Elgg به درستی کار کند. در صورتی که در هسته Elgg تغییری نداده این می توانید:
به سادگی پوشه views را حذف کنید و آن را با پوشه اصلی جایگزین کنید.
پوشه اصلی را میتوانید از مسیر <a href="http://elgg.org">elgg.org</a> دریافت کنید.

اگر به راهنمای جزئی تر نیاز دارید لطفا به  <a href="http://learn.elgg.org/en/stable/admin/upgrading.html">
⇥⇥مستندات ارتقاء Elgg</a>  مراجعه کنید. در صورتی که به کمک نیاز داشتید در <a href="http://community.elgg.org/pg/groups/discussion/">فاروم پشتیبانی</a>  درخواست کنید.',

	'update:twitter_api:deactivated' => 'واسط تویتر (درگذشته با نام سرویس تویتر شناخته میشد) در حین ارتقاء غیرفعال شد. لطفا در صورت نیاز آن را به صورت دستی فعال کنید.',
	'update:oauth_api:deactivated' => 'واسط OAuth (درگذشته با نام OAuth Lib شناخته میشد) در حین ارتقاء غیرفعال شد. لطفا در صورت نیاز آن را به صورت دستی فعال کنید.',
	'upgrade:site_secret_warning:moderate' => "بهتر است که کلید امنیتی سایتتان را دوباره تولید کنید تا امنیت آن بالا رود. در قسمت تنظیمات پیشرفته این کار را انجام دهید",
	'upgrade:site_secret_warning:weak' => "شدیدا پیشنهاد می شود که کلید امنیتی سایتتان را دوباره تولید کنید تا امنیت آن بالا رود. در قسمت تنظیمات پیشرفته این کار را انجام دهید",

	'deprecated:function' => '%s() از رده خارج شده و جایگزین آن %s() می باشد.',

	'admin:pending_upgrades' => 'این سایت نیاز به چندین مورد ارتقاء دارد و به توجه سریع شما نیاز دارد',
	'admin:view_upgrades' => 'مشاهده ارتقاء های مورد نیاز',
	'item:object:elgg_upgrade' => 'ارتقاءهای سایت',
	'admin:upgrades:none' => 'نسخه شما بروز است',

	'upgrade:item_count' => 'اینجا %s مورد هست که نیاز به ارتقاء دارد',
	'upgrade:warning' => 'هشدار: در سایتهای بزرگ این ارتقاء ممکن است زمان زیادی طول بکشد',
	'upgrade:success_count' => 'ارتقاء یافت',
	'upgrade:error_count' => 'خطاها:',
	'upgrade:river_update_failed' => 'امکان بروزرسانی موارد با کد : %s نیست',
	'upgrade:timestamp_update_failed' => 'امکان بروزرسانی زمان برای مورد با شماره %s نیست',
	'upgrade:finished' => 'ارتقاء پایان یافت',
	'upgrade:finished_with_errors' => 'ارتقاء پایان یافت و چندین خطا رخ داد. صفحه را رفرش کنید و سعی کنید که دوباره عملیات ارتقاء را انجام دهید. اگر خطا مجددا رخ داد لاگ سرور را برای خطاهای احتمالی بررسی کنید. شما میتوانید در  <a href="http://community.elgg.org/groups/profile/179063/elgg-technical-support">گروه پشتیبانی فنی Elgg</a>  به دنبال راه حل  و راهنمایی برای  رفع خطایتان باشید.',

	// Strings specific for the database guid columns reply upgrade
	'admin:upgrades:database_guid_columns' => 'چیدن ستون های GUID بانک اطلاعاتی',
	
/**
 * Welcome
 */

	'welcome' => "خوش آمدید",
	'welcome:user' => '%s خوش آمدید',

/**
 * Emails
 */

	'email:from' => 'از',
	'email:to' => 'به',
	'email:subject' => 'موضوع',
	'email:body' => 'بدنه',

	'email:settings' => "تنظیمات ایمیل",
	'email:address:label' => "آدرس ایمیل",

	'email:save:success' => "آدرس ایمیل جدید ذخیره شد. درخواست تایید ارسال شد",
	'email:save:fail' => "آدرس ایمیل جدید ذخیره نشد.",

	'friend:newfriend:subject' => "%s شما را به عنوان دوست انتخاب کرده است!",
	'friend:newfriend:body' => "%s شما را به عنوان دوست انتخاب کرده است.

برای مشاهده پروفایل ایشان اینجا را کلیک کنید:

%s

لطفا به این ایمیل پاسخ ندهید.",

	'email:changepassword:subject' => "کلمه عبور تغییر کرد!",
	'email:changepassword:body' => "کلمه عبور شما تغییر کرد.",

	'email:resetpassword:subject' => "کلمه عبور بازنشانی شد",
	'email:resetpassword:body' => "سلام %s

کلمه عبور شما به %s بازنشانی شد.",

	'email:changereq:subject' => "درخواست تغییر کلمه عبور",
	'email:changereq:body' => "سلام %s

فردی (از آدرس %s ) درخواست تغییر کلمه عبور را داده است.

اگر این درخواست از سمت شما بوده لینک زیر را کلیک کنید در غیر این صورت این ایمیل را نادیده بگیرید.
%s
",

/**
 * user default access
 */

	'default_access:settings' => "سطح دسترسی پیش فرض شما",
	'default_access:label' => "سطح دسترسی پیش فرض",
	'user:default_access:success' => "سطح دسترسی پیش فرض جدید ذخیره شد.",
	'user:default_access:failure' => "سطح دسترسی جدید امکان ذخیره شدن ندارد.",

/**
 * Comments
 */

	'comments:count' => "%sدیدگاه",
	'item:object:comment' => 'نظر',

	'river:comment:object:default' => '%s دیدگاه برای %s',

	'generic_comments:add' => "دیدگاهی بگذارید",
	'generic_comments:edit' => "ویرایش دیدگاه",
	'generic_comments:post' => "ارسال دیدگاه",
	'generic_comments:text' => "دیدگاه",
	'generic_comments:latest' => "آخرین دیدگاهها",
	'generic_comment:posted' => "دیدگاه شما با موفقیت ارسال شد",
	'generic_comment:updated' => "دیدگاه با موفقیت بروزرسانی شد.",
	'generic_comment:deleted' => "دیدگاه با موفقیت حذف شد",
	'generic_comment:blank' => "متاسفانه شما باید در دیدگاهتان چیزی بنویسید تا امکان ذخیره شدن داشته باشد.",
	'generic_comment:notfound' => "متاسفانه دیدگاه مشخص شده پیدا نشد.",
	'generic_comment:notfound_fallback' => "متاسفانه دیدگاه مورد نظر شما پیدا نشد. اما شما به صفحه ای که دیدگاه از آنجا ثبت شده هدایت می شوید.",
	'generic_comment:notdeleted' => "متاسفانه امکان حذف این دیدگاه نیست.",
	'generic_comment:failure' => "در حین ذخیره دیدگاه خطای ناخواسته ای رخ داد.",
	'generic_comment:none' => 'هیچ دیدگاهی نیست',
	'generic_comment:title' => 'دیدگاههای %s',
	'generic_comment:on' => '%s در %s',
	'generic_comments:latest:posted' => 'منتشر کرد',

	'generic_comment:email:subject' => 'شما یک دیدگاه جدید دارید',
	'generic_comment:email:body' => "شما یک دیدگاه جدید در \"%s\" از \"%s\" دارید. که میگوید:


%s

برای پاسخ به این دیدگاه اینجا را کلیک کنید:

%s

برای مشاهده پروفایل %s اینجا را کلیک کنید:

%s

لطفا به این ایمیل پاسخ ندهید.",

/**
 * Entities
 */

	'byline' => 'توسط %s',
	'byline:ingroup' => 'در گروه%s',
	'entity:default:strapline' => 'ایجاد شده %s توسط %s',
	'entity:default:missingsupport:popup' => 'این مورد به صورت صحیح قابل نمایش نمی باشد. این ممکن است به خاطر این باشد که نیاز به پشتیبانی از پلاگینی هست که دیگر در سیستم نصب نیست.',

	'entity:delete:item' => 'آیتم',
	'entity:delete:item_not_found' => 'مورد یافت نشد',
	'entity:delete:permission_denied' => 'شما مجوز پاک کردن این مورد را ندارید',
	'entity:delete:success' => 'مورد %s حذف شد',
	'entity:delete:fail' => 'امکان حذف %s نیست',

	'entity:can_delete:invaliduser' => 'امکان چک تابع canDelete برای کاربر user_guid [%s]  نیست. به خاطر اینکه کاربر موجود نیست.',

/**
 * Action gatekeeper
 */

	'actiongatekeeper:missingfields' => 'قسمت __token یا  __ts فرم موجود نیست.',
	'actiongatekeeper:tokeninvalid' => "صفحه ای که در حال استفاده آن بودید منقضی شده است.لطفا مجدد تلاش کنید.",
	'actiongatekeeper:timeerror' => 'صفحه ایک ه در حال استفاده آن بودید منقضی شده است. لطفا صفحه را رفرش  و سعی مجدد کنید.',
	'actiongatekeeper:pluginprevents' => 'متاسفانه فرم شما امکان ارسال ندارد. به خاطر یک خطای ناشناخته',
	'actiongatekeeper:uploadexceeded' => 'اندازه فایل(های) بارگزاری شده از حد مجاز که توسط مدیر سیستم تنظیم شده است، فراتر است.',
	'actiongatekeeper:crosssitelogin' => "متاسفانه امکان ورود از دامین دیگر موجود نیست. لطفا مجدد سعی کنید.",

/**
 * Word blacklists
 */

	'word:blacklist' => 'و،همان، پس از آن، اما، او، او، او را، او را، یکی، نه، نیز، در مورد، در حال حاضر، از این رو، با این حال، هنوز هم، به همین ترتیب، در غیر این صورت، بنابراین، برعکس، به جای، در نتیجه، علاوه بر این، با این حال، به جای آن، در عین حال، بر این اساس، این، به نظر می رسد، چه، که، که، هر کس، هر کس',

/**
 * Tag labels
 */

	'tag_names:tags' => 'برچسب ها',

/**
 * Javascript
 */

	'js:security:token_refresh_failed' => 'امکان اتصال %s نیست. شما ممکن است در ذخیره محتوا به مشکل بخورد.لطفا صفحه را رفرش کنید.',
	'js:security:token_refreshed' => 'ارتباط با %s بازیابی شد.',
	'js:lightbox:current' => "تصویر %s از %s",

/**
 * Miscellaneous
 */
	'elgg:powered' => "نیروگرفته توسط Elgg",

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
	//"in" => "Indonesian ",
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

	"field:required" => 'الزامی',

);
