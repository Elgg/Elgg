<a name="4.0.1"></a>
### 4.0.1  (2021-10-08)

#### Contributors

* Jerôme Bakker (11)
* Jeroen Dalsem (7)

#### Bug Fixes

* **blog:** use route name as page title ([ead3647b](https://github.com/Elgg/Elgg/commit/ead3647bc5a54828cfd2c126ea247ee6c083143b))
* **comments:** no longer disable save button after form submission ([03651f26](https://github.com/Elgg/Elgg/commit/03651f2698525963fc80c47c34511d4074b3b909))
* **core:** do not respond with redirect on failure of ajax requests ([4222ac92](https://github.com/Elgg/Elgg/commit/4222ac9255885e32d3da0860c675af5356a626a9))
* **notifications:**
  * mute page of a comment shows relevant entities ([e297bda2](https://github.com/Elgg/Elgg/commit/e297bda26702b28a543a86a38d0c51ae720a87af))
  * disable subscribe button if you can't subscribe ([ea612bb4](https://github.com/Elgg/Elgg/commit/ea612bb4cd7343e2751dbe1b7ce7323320980bdd))
  * (un)subscribe buttons only for logged in users ([824291fb](https://github.com/Elgg/Elgg/commit/824291fbff0971bbd1998abf4dd3c128329485e0))
* **site_notifications:** prevent input limit reached in bulk actions ([e83df87c](https://github.com/Elgg/Elgg/commit/e83df87cd7fdb21ae369680e6273785b90ee412b))
* **systemlog:** only log before and after events if there are handlers ([5fb8189a](https://github.com/Elgg/Elgg/commit/5fb8189a42b9dc585191411ee27e851ca5a9a2c7))
* **thewire:** output of the new wire notification in HTML e-mail ([c161429b](https://github.com/Elgg/Elgg/commit/c161429b53c541f858baacd3a5fbefb51d0e9467))
* **views:** correct default behaviour for showing entity menu on PHP 8 ([8f9f3827](https://github.com/Elgg/Elgg/commit/8f9f3827ac81a51e4514616a011b4fdbcd6e822d))


<a name="4.0.0"></a>
## 4.0.0  (2021-09-24)

#### Contributors

* Jerôme Bakker (11)
* Jeroen Dalsem (4)

#### Bug Fixes

* **cli:** allow batch upgrades to run indefinitely ([5b6b4419](https://github.com/Elgg/Elgg/commit/5b6b441909d0d6371b6c2d7ff81f82b7e2eaaeee))
* **i18n:** allowed languages returns filtered language keys ([ff574222](https://github.com/Elgg/Elgg/commit/ff57422266e5b25de59f485c643e213104c48260))
* **upgrade:** content owner subscription is now an async upgrade ([d8abe11c](https://github.com/Elgg/Elgg/commit/d8abe11c4e8033f9ece8a5b8c97d6808c9073c18))


<a name="4.0.0-rc.1"></a>
### 4.0.0-rc.1  (2021-09-10)

#### Contributors

* Jerôme Bakker (59)
* Jeroen Dalsem (3)

#### Features

* **cli:** upgrade command supports force option ([8fb44c7f](https://github.com/Elgg/Elgg/commit/8fb44c7feb66f206ee45dc729e89262cfb8d10a9))
* **delayed_email:** add intro text to notification ([c3750286](https://github.com/Elgg/Elgg/commit/c375028632542ff46bd20b0572b4650c86be569c))
* **developers:** added link to entity on the entity explorer page ([cd1f3753](https://github.com/Elgg/Elgg/commit/cd1f375376409339d0d6b92860333a179030fac3))
* **groups:** groups edit form tabs navigation help ([bd2f94d9](https://github.com/Elgg/Elgg/commit/bd2f94d9cfdfe4ad1f7fa94496b034b363108a66))
* **notifications:** added option to exclude subscribers ([e43ae547](https://github.com/Elgg/Elgg/commit/e43ae54767b50878df609f52dab8ac0734570e19))
* **system_log:** allow elgg_call to disable system logging ([7f85fd18](https://github.com/Elgg/Elgg/commit/7f85fd186689256cd4483d16d7f19a8ffb3dd83d))


#### Performance

* **db:** disable system_log during cleanup of entity properties ([4576dff6](https://github.com/Elgg/Elgg/commit/4576dff6ac1d5a27eeb988c039974d659f06bdd8))


#### Bug Fixes

* **activity:** don't show responses on index page when logged out ([eaa5c28b](https://github.com/Elgg/Elgg/commit/eaa5c28b4cdb0c08f21b84e8d2a029dd933939c8))
* **bookmarks:**
  * don't show filter menu when viewing a bookmark ([1a5c9478](https://github.com/Elgg/Elgg/commit/1a5c9478104940c8e81d2f7331471df91de4a79e))
  * unable to save bookmark on PHP 8 ([81392414](https://github.com/Elgg/Elgg/commit/813924144b68ea3c9e31dd426a8e5ff0f6051e91))
* **db:** use QueryBuilder in query execution ([2f2050cb](https://github.com/Elgg/Elgg/commit/2f2050cb6f08b0cdb80dc97b4290a39c868aede6))
* **delayed_email:** prevent empty notification ([ee6bc376](https://github.com/Elgg/Elgg/commit/ee6bc3768b5f0e6ed30d693bea7f2aa34f15ec3c))
* **developers:** theme sandbox is now only available for admins ([19bcb892](https://github.com/Elgg/Elgg/commit/19bcb892d64d6708924ac226b61a7422283943e0))
* **mail:** correctly encode ( and ) in e-mail headers ([0560c379](https://github.com/Elgg/Elgg/commit/0560c3794bfd6ea946324c9a0fe8a9e05a97714a))
* **notifications:**
  * serialization exception during delayed enqueue ([72b65143](https://github.com/Elgg/Elgg/commit/72b651436e4a6523f622a6b90c8abb77f16890ab))
  * use correct actor for admin notification ([6b8d37a0](https://github.com/Elgg/Elgg/commit/6b8d37a0b652c8a2e312fb000ea72cadbf30ffe5))
* **pagination:**
  * don't updated browser history on ajax appended lists ([823283bd](https://github.com/Elgg/Elgg/commit/823283bd439cb56ead9d91f78e1b0c74a19efd9b))
  * allow control over base url during Ajax requests ([edad75a7](https://github.com/Elgg/Elgg/commit/edad75a789159bc287bb8ebadea33152a7d96163))
  * use correct jquery selector during ajax replace ([e750622b](https://github.com/Elgg/Elgg/commit/e750622b52c27f0245571fc2ea7899a810379447))
* **river:** show river responses again on all pages ([9e90d95b](https://github.com/Elgg/Elgg/commit/9e90d95b28bbf1e63b76a925d07e17f6dd5d460e))
* **session:** prevent PHP warning when regenerating session ([dfe73773](https://github.com/Elgg/Elgg/commit/dfe73773537297c36d42fcabbe98e23de95f7cef))
* **site_notifications:**
  * limit cleanup to 30 minutes ([7229e7e1](https://github.com/Elgg/Elgg/commit/7229e7e1d4df90f79c4e5b4cb9282a83fe584665))
  * prevent deadloop when marking as read ([c4467a2c](https://github.com/Elgg/Elgg/commit/c4467a2c6016488022f481b2fdc48aaafc294062))
* **statistics:** show readable entity type/subtype ([99a68a47](https://github.com/Elgg/Elgg/commit/99a68a47ee4dd84566b22c963a4d7b83c1579ec8))


<a name="4.0.0-beta.3"></a>
### 4.0.0-beta.3  (2021-08-06)

#### Contributors

* Jeroen Dalsem (20)
* Jerôme Bakker (17)

#### Features

* **friends:** added friends_of widget ([05fea8a0](https://github.com/Elgg/Elgg/commit/05fea8a074f7363c02bc7996ee56a98176028974))
* **i18n:** added a config flag to disable browser language detection ([00b612dc](https://github.com/Elgg/Elgg/commit/00b612dc3cf4059a2107cd5a86a7dd4cde566ddf))
* **notifications:**
  * notification events are configurable ([55c7c909](https://github.com/Elgg/Elgg/commit/55c7c909a7cae564eff95debfe9910bda3087f5a))
  * subscribers to owner will receive notifications ([2b0f2bb2](https://github.com/Elgg/Elgg/commit/2b0f2bb2b2d559e063124b880d3d0481664c6bc8))
  * setting to delay notification queue processing ([7942f7e0](https://github.com/Elgg/Elgg/commit/7942f7e0ec78c42a59b6299b567da745d2387536))
* **pagination:** js loaded listdata triggers a change event ([aa478518](https://github.com/Elgg/Elgg/commit/aa478518cb30bb73259c72aa305bdeeb238f373e))
* **views:** allow custom title to be passed to output/friendlytime ([4c88d2d3](https://github.com/Elgg/Elgg/commit/4c88d2d38a6fc3895e87f1ba68d40b6b0389ed8d))


#### Bug Fixes

* **admin:** drag/drop plugin ordering has been fixed ([7b5fe7df](https://github.com/Elgg/Elgg/commit/7b5fe7dff528b9e1b75376fad051101bd22c3b37))
* **email:** added mute link to html email footer ([85268538](https://github.com/Elgg/Elgg/commit/85268538c34ddd7278f0003f7d90cae458313f75))
* **groups:** make sure retrieving group tool option always uses hook ([1e9ae2b6](https://github.com/Elgg/Elgg/commit/1e9ae2b64ec04f52653dc686457a0d7767791dee))
* **install:**
  * make sure user is logged in during installer ([b6248ec7](https://github.com/Elgg/Elgg/commit/b6248ec7c268ab20097873419e58b07839d77cee))
  * allow some vendor files to be included ([3f857850](https://github.com/Elgg/Elgg/commit/3f85785086748b230d10f909c82cdcdfc727c869))
* **likes:** correctly toggle count badge ([fa2be687](https://github.com/Elgg/Elgg/commit/fa2be68759d67a3649b642b5d46a4257e6435421))
* **metadata:** elgg_get_tags uses correct default for tag_names ([4e8d5326](https://github.com/Elgg/Elgg/commit/4e8d53264ebd8a6d402c24a9e82e7026cc23bc0c))
* **phinx:** correctly check for indexes by name during upgrades ([22ccaea9](https://github.com/Elgg/Elgg/commit/22ccaea9408d689b4196dd2b741b0a179b7310dd))
* **plugins:** position and required state of plugins are now asserted ([948927a3](https://github.com/Elgg/Elgg/commit/948927a31e65979991c3d0c332f2587650602003))
* **site_notifications:** moved buttonbank to top of the list ([c158e810](https://github.com/Elgg/Elgg/commit/c158e810024122653a50c574e1165a0e03254f86))
* **tags:** always have tags css available ([3f9b5a63](https://github.com/Elgg/Elgg/commit/3f9b5a63fd69ab429a3ceed92c7fdc937a273d5b))
* **upgrades:** improved completion reporting ([863e2627](https://github.com/Elgg/Elgg/commit/863e262767b539d385858549ef7bcaababe38ca3))
* **views:** prevent incidental incorrect id generation ([c2d77bd1](https://github.com/Elgg/Elgg/commit/c2d77bd191cd1fca72236742f8be58af6e015cbf))
* **webservices:** register correct PAM handlers for API authentication ([51ebcabb](https://github.com/Elgg/Elgg/commit/51ebcabb8c1386c5cdcc6b25c49cd7e4c930e1c6))
* **widgets:** only update title on non empty href string ([7b147a61](https://github.com/Elgg/Elgg/commit/7b147a61ad6a1c10e0a8d6207a85e1143ee8ec20))


<a name="4.0.0-beta.2"></a>
### 4.0.0-beta.2  (2021-07-09)

#### Contributors

* Jeroen Dalsem (13)
* Jerôme Bakker (13)

#### Features

* **discussions:** added quick status toggle entity menu item ([1b78dc00](https://github.com/Elgg/Elgg/commit/1b78dc009b770db09625e2cdbd31c14987e36119))


#### Bug Fixes

* **activity:**
  * always show tabs on activity index page ([3aa6605b](https://github.com/Elgg/Elgg/commit/3aa6605b44167706ba59566c51f5335e1326d6f7))
  * added missing breadcrumbs ([db7e2ce3](https://github.com/Elgg/Elgg/commit/db7e2ce35610c52403a1744842de80a252670f4e))
* **cache:** invalidate caches on plugin (de)activate ([8bca339c](https://github.com/Elgg/Elgg/commit/8bca339c81fcb27918725c1605b7413f0948ffe5))
* **core:**
  * prevent exceptions on session save ([90345bae](https://github.com/Elgg/Elgg/commit/90345bae86c193c01ea052050458591bb3ec9e46))
  * do not draw exception content if there already is output ([91d4f03a](https://github.com/Elgg/Elgg/commit/91d4f03ad073b95d7bc2d98aea72811d13825e60))
  * correctly handle exceptions in error log formatter ([6d6328f1](https://github.com/Elgg/Elgg/commit/6d6328f139702658a63a0307bc7bc6431c382cb7))
* **icons:** update site menu icons ([7292b4c0](https://github.com/Elgg/Elgg/commit/7292b4c0d171d00167a43042c7ca1b96965c7ca2))
* **js:** always process error messages in ajax calls ([cf070072](https://github.com/Elgg/Elgg/commit/cf0700728d148621b7fa2e00f777d6e957a40956))
* **navigation:** prevent pluginsettings menu item name conflicts ([cbe3ef6d](https://github.com/Elgg/Elgg/commit/cbe3ef6deea6ca758e0b320a0e8d482137359db6))
* **notifications:**
  * dont pass recipient user to default message body ([91d45a26](https://github.com/Elgg/Elgg/commit/91d45a26c3269407e406d5694e8413ca4392267b))
  * show correct notification labels ([9a285350](https://github.com/Elgg/Elgg/commit/9a2853507e816c398a8aba1b1cdfc97783c4b742))
* **search:** do not show social and entity menu in search results ([33413407](https://github.com/Elgg/Elgg/commit/33413407b110086d3d1d5ad5b4140bd7d8df0dc9))
* **settings:** english and site language not correctly disabled ([76a099e3](https://github.com/Elgg/Elgg/commit/76a099e3a608694d6041d2e607ad6d32cd530faf))
* **views:** number formatter not always returning expected results ([f5d25392](https://github.com/Elgg/Elgg/commit/f5d253921bd4cea4e0360d3c15ef6c189d2b3798))


<a name="4.0.0-beta.1"></a>
### 4.0.0-beta.1  (2021-06-11)

#### Contributors

* Jeroen Dalsem (193)
* Jerôme Bakker (144)
* RiverVanRain (1)
* dnovikov (1)

#### Features

* **actions:** added generic actions for (un)subscribe and (un)mute ([2f7e1702](https://github.com/Elgg/Elgg/commit/2f7e1702089d4aec3aaf28f64e2921273eec4d3b))
* **comments:** added ability to configure number of comments per page ([100e6881](https://github.com/Elgg/Elgg/commit/100e6881a122abc440fc5a67388bbcfa48c5a61f))
* **config:**
  * added configuration option for sendmail in settings.php ([91f476ba](https://github.com/Elgg/Elgg/commit/91f476ba8d12baed123d03b09c6964fb72402ecb))
  * added proxy settings to the settings.php ([37c1966d](https://github.com/Elgg/Elgg/commit/37c1966d039448164bb9d1c2bdf47225880f9abc))
* **core:**
  * added first login event for when user logs in the first time ([19a2d384](https://github.com/Elgg/Elgg/commit/19a2d3849e3a828349055c3745efce9c4c59f172))
  * users are automaticly subscribed to content they comment on ([a5373f14](https://github.com/Elgg/Elgg/commit/a5373f14f83afecd05ebea2efcb137c782175d31))
  * added PHP 8 support ([11e42800](https://github.com/Elgg/Elgg/commit/11e42800b68ab504d8c59114470676b904c298dd))
  * ElggBatch supports reporting failures ([ba601973](https://github.com/Elgg/Elgg/commit/ba60197378ea83f046f4348224d8dd3c2ca3a5a7))
* **database:** add generic registration function for seeders ([a11047b3](https://github.com/Elgg/Elgg/commit/a11047b3f87c522d73d38011ac2de527edafb8ba))
* **db:** updated to doctrine/dbal 3.1 ([32152f3f](https://github.com/Elgg/Elgg/commit/32152f3feff662e4dc7b9951bd4fe435f4f8e959))
* **discussions:** the site menu item is now always present ([2d19025a](https://github.com/Elgg/Elgg/commit/2d19025a122afe64583ec80fc3dc514c55ced31d))
* **email:**
  * from address will show original sender name ([6fa8fcf0](https://github.com/Elgg/Elgg/commit/6fa8fcf0c5484d1bcb72c8e28ede6658c1fbb5ed))
  * added html formatted mail template ([76b21538](https://github.com/Elgg/Elgg/commit/76b21538336ce32353bd083905a424fa768957c2))
  * added config setting to limit subject length ([4e82113f](https://github.com/Elgg/Elgg/commit/4e82113fbb85f762267f3da4f239dc52c5989319))
  * Elgg\Mail now supports multiple to, cc and bcc recipients ([0f24a524](https://github.com/Elgg/Elgg/commit/0f24a524981f3968500f8d5d50abdd736b3ec534))
* **forms:** entity field config can be requested from a service ([c6b1771a](https://github.com/Elgg/Elgg/commit/c6b1771a160c70927abc7f9ecd38fe8fa8545d8e))
* **git:** extra allowed types for commit messages ([c9f1757c](https://github.com/Elgg/Elgg/commit/c9f1757cd23253c230aeb4e754f9ce97c387421b))
* **groups:**
  * allow group specific plugin settings ([28f7e6e6](https://github.com/Elgg/Elgg/commit/28f7e6e6c219eb88fbe38dadbaf4944089fcccbb))
  * group edit form now uses tabs for different sections ([a9103dee](https://github.com/Elgg/Elgg/commit/a9103dee4db0962d706b31411d320de60e3b07d7))
* **js:**
  * updated to jQuery 3.5.1 ([b9f8a8c5](https://github.com/Elgg/Elgg/commit/b9f8a8c56fdf8e4cdd326fa35ce6f07c02f56959))
  * jquery ui updated to v1.12.1 and can now supports AMD ([4914dc8f](https://github.com/Elgg/Elgg/commit/4914dc8f71de5ee6a9e7e9a9951fca0322b97a9d))
  * allow lightbox to load JS dependencies ([fa00e132](https://github.com/Elgg/Elgg/commit/fa00e132c331382b649d1e52fba49758f291981d))
* **menu:** the title menu will be expanded with the entity menu ([a91a7cc7](https://github.com/Elgg/Elgg/commit/a91a7cc79b9031daf8aa70484632156b4be43af9))
* **menus:** more granular register and prepare hooks ([bc6a5e2f](https://github.com/Elgg/Elgg/commit/bc6a5e2fd199a8bfe06ad6f7946d47286c09dc7f))
* **messages:** allow users to send messages friends only ([94208336](https://github.com/Elgg/Elgg/commit/94208336dd4af572e785313f7438717e5624c753))
* **navigation:** more control over the pagination rendering ([a9b0680c](https://github.com/Elgg/Elgg/commit/a9b0680cccbba4eb744b03be4e1361aaf230d82b))
* **notifications:**
  * plugins can control notification with eventhandler ([87ebad4e](https://github.com/Elgg/Elgg/commit/87ebad4ee667b087bf2d14f2b36ee5f17ea7a173))
  * added mute notification page ([2d724e6e](https://github.com/Elgg/Elgg/commit/2d724e6e04090ce882b7c7772f283075e2dc5014))
  * temporarily disable notifications ([2770d4d9](https://github.com/Elgg/Elgg/commit/2770d4d93a899ba561cc642b9f01f47b9939bcc8))
  * added delayed email to bundle notifications ([d522a53e](https://github.com/Elgg/Elgg/commit/d522a53e8fe8f5a11cd695bbe1b47abdbc675e9c))
  * split salutation and sign-off from message body ([5741a414](https://github.com/Elgg/Elgg/commit/5741a4140eb883c0a9f0b977390183ec0de49d17))
  * more detailed notification subscriptions ([b018d1a8](https://github.com/Elgg/Elgg/commit/b018d1a80cd24dd852dc133cb55e805003305f6a))
* **page_owner:** add page owner detection to route definition ([f52e4227](https://github.com/Elgg/Elgg/commit/f52e422727870dc8751d328eddd2c4a815d30333))
* **pagination:**
  * added 'infinite lists' pagination options ([d8aa00ff](https://github.com/Elgg/Elgg/commit/d8aa00fff3e5bf480c9293208ae4693b5d4f5c02))
  * listings are now updated without page reloads ([1f3322c2](https://github.com/Elgg/Elgg/commit/1f3322c214384d5f92d134d399d1c1403c588cb7))
* **plugins:**
  * added ability to configure notification events in config ([e3e77234](https://github.com/Elgg/Elgg/commit/e3e772340099409f5f4fb792dcc100d9289be29a))
  * added ability to register view options in elgg-plugin.php ([522feeb6](https://github.com/Elgg/Elgg/commit/522feeb65287241d8d4de88ddedf8857f1a2d85e))
  * added ability to register group tools in elgg-plugin.php ([9acc7d88](https://github.com/Elgg/Elgg/commit/9acc7d88e38b163ab7a455c751791d949588bec5))
* **reportedcontent:** added menu item to report entities ([13f07c58](https://github.com/Elgg/Elgg/commit/13f07c585bddc0b99531b38abe463b6eb25d7e1d))
* **router:** public API to get the route of the current request ([8e3736e8](https://github.com/Elgg/Elgg/commit/8e3736e8882e44a3cdd5649dd471de004b568cde))
* **search:** moved result formatting logic into views ([fe63196f](https://github.com/Elgg/Elgg/commit/fe63196ff8b19ee21e26a001069493152bccc577))
* **seeder:**
  * add option to spread creation time of seeded entities ([30385b79](https://github.com/Elgg/Elgg/commit/30385b79baeb100458ad0055c5dc825cc1111d32))
  * force create new entities during seeding ([a21eccf8](https://github.com/Elgg/Elgg/commit/a21eccf81840ef535b8d51ee7238ad003fe932bf))
  * limit (un)seeding to a given content type ([0485722a](https://github.com/Elgg/Elgg/commit/0485722a453ca35f25061138af724f8f32de5dd1))
* **site_notifications:**
  * site notifications are enabled for new users ([51238b15](https://github.com/Elgg/Elgg/commit/51238b150794eb6effe78fe9ba785be37d835a9e))
  * configure cleanup of site notifications ([55ce9da4](https://github.com/Elgg/Elgg/commit/55ce9da49c70d42c7ef46c7dc4db7d8e30d12b2f))
  * split read/unread notifications ([9df9bc19](https://github.com/Elgg/Elgg/commit/9df9bc195cf367f79cfa2c688587b3dc8d6b3884))
  * remove notification when content is removed ([792705a8](https://github.com/Elgg/Elgg/commit/792705a851e257cb5a02a98c15b6d3362a6597d7))
* **subscriptions:** added muting possibility to entity subscriptions ([6ad73185](https://github.com/Elgg/Elgg/commit/6ad7318516165785874f46aa3c98a5c01d3ffd5b))
* **tags:** tags input is now assisted by tagify ([bc9d2566](https://github.com/Elgg/Elgg/commit/bc9d2566184973e5219034f63405678587d1fac4))
* **thewire:**
  * added getParent function to ElggWire entity class ([46f406aa](https://github.com/Elgg/Elgg/commit/46f406aae8beb919846e98d0eaf2c18e9812aaf6))
  * allow database seeding ([70a29ddc](https://github.com/Elgg/Elgg/commit/70a29ddcf2ca333c6674e26d875b096d6382dd0d))
* **users:**
  * newly created users always have a validation status ([04cc3395](https://github.com/Elgg/Elgg/commit/04cc3395bd58df37954a5aaf2bd69cf28117ec4d))
  * uniform storage of notification settings ([6fcccafe](https://github.com/Elgg/Elgg/commit/6fcccafe57646ab37f8fb72d85a2d1252282dee2))
* **views:**
  * added helpers functions for outputting urls ([a23a3ff1](https://github.com/Elgg/Elgg/commit/a23a3ff1055fc40f11e496b816c95a16d6714f8e))
  * added 'show_owner_block' variable to prevent owner_block output ([29cc3323](https://github.com/Elgg/Elgg/commit/29cc33230850a94cfc16e3e4ab10cd5613cf9ba1))
* **webservices:** option to enable / disable API keys ([4c5b33cb](https://github.com/Elgg/Elgg/commit/4c5b33cbf40883dce8e984c146170ad25e8ce308))
* **widgets:** dashboard widgets created on first login ([9bf117d9](https://github.com/Elgg/Elgg/commit/9bf117d95232fad8382253acbbf343931e5d7b92))


#### Performance

* **db:**
  * add index to annotations table ([7c728671](https://github.com/Elgg/Elgg/commit/7c7286712a1439a866e739fdc680b36b21bc2269))
  * add index to annotations table ([af34641a](https://github.com/Elgg/Elgg/commit/af34641aa70060d4c9878019b63f182d133e199c))


#### Bug Fixes

* **collections:** corrected implementation of SeekableIterator ([a7e70382](https://github.com/Elgg/Elgg/commit/a7e70382a6fd61aedfe97e9cadb8c19b4d96f11c))
* **core:** remove_entity_relationships triggers delete event ([73626bee](https://github.com/Elgg/Elgg/commit/73626beecf3219d54106be2f49779e5160779422))
* **css:** wide select inputs do not run out of screen ([6af60e27](https://github.com/Elgg/Elgg/commit/6af60e27f0dcec0b33b4d979b768c5699db945cd))
* **db:**
  * check for entity existence during metadata creation ([0a07fe8d](https://github.com/Elgg/Elgg/commit/0a07fe8d22033eb5b856e36b7e4f1e350fa968ee))
  * check for entity existence during relationship creation ([d23e7351](https://github.com/Elgg/Elgg/commit/d23e7351192ec5be2aa6313f4fa4f84883a37c51))
* **groups:** use guid instead of container_guid in add:group:group route ([57146fbe](https://github.com/Elgg/Elgg/commit/57146fbefb502069f229b10e2cb3d6840e11376f))
* **invite_friends:** friendship delayed until invited friend validated ([2c212dd1](https://github.com/Elgg/Elgg/commit/2c212dd18be177a64974b4f838c675c50245e7b6))
* **js:** userpicker stores match_on information in data attribute ([1b30d349](https://github.com/Elgg/Elgg/commit/1b30d3491efa5211e489afdd792d87fcd1104582))
* **relationship:** saving unchanged relationships won't trigger events ([549ee02c](https://github.com/Elgg/Elgg/commit/549ee02cf911635a59f501cb6e11c1fbc597c3cb))
* **river:** show less duplicate comments ([d172ce94](https://github.com/Elgg/Elgg/commit/d172ce94f5c50c572044120a4ff0ec4687be66c5))
* **site_notifications:** save more notification data ([2603e4cd](https://github.com/Elgg/Elgg/commit/2603e4cdfedae25c9161e4f514aed53fd1c002c6))
* **tests:** testAccessCaching now makes more sense ([466ce221](https://github.com/Elgg/Elgg/commit/466ce2213feeb5ca2fdeeaa5a399de5f516c8b69))


#### Deprecations

* **core:**
  * drop support for handler in elgg_register_title_button ([0529986f](https://github.com/Elgg/Elgg/commit/0529986f4d3de7eda374697eaea431ddb78864d0))
  * forward() has been deprecated ([9497be28](https://github.com/Elgg/Elgg/commit/9497be28a9f5e997ce3a5e47cabc6c9413300a71))
* **database:** use QueryBuilder instead of raw sql queries ([8aa74ef9](https://github.com/Elgg/Elgg/commit/8aa74ef9eddab7dc0c397154d8c83551e451dd69))


#### Breaking Changes

* This update is a major update of the jQuery library. Update your code if
needed. ([b9f8a8c5](https://github.com/Elgg/Elgg/commit/b9f8a8c56fdf8e4cdd326fa35ce6f07c02f56959))
* If you need jquery ui related functionality like sortables, make sure to
add the correct dependencies to your own javascript ([4914dc8f](https://github.com/Elgg/Elgg/commit/4914dc8f71de5ee6a9e7e9a9951fca0322b97a9d))
* You now can only use the elgg/Ajax async module.

fixes #13175 ([5bca9af9](https://github.com/Elgg/Elgg/commit/5bca9af9a10435a16e7e1b94efbe7a76b7263656))
* when validating the container write permissions the
type and subtype of the content need to be provided.

fixes #12684 ([13070985](https://github.com/Elgg/Elgg/commit/13070985e840a78fc8b1814ac67e602b26383d4b))
* **composer:** replace deprecated Zend\Mail with Laminas\Mail ([bef90a8c](https://github.com/Elgg/Elgg/commit/bef90a8ce3fc50c4f7c5c117470355762453e639))
* **core:**
  * admin and banned metadata of a user is now protected ([ef54acd0](https://github.com/Elgg/Elgg/commit/ef54acd0bbd34a66ef3f54c3e64a31acbe0aef65))
  * protected some ElggEntity attributes ([aaa6da50](https://github.com/Elgg/Elgg/commit/aaa6da50ed8cbd5a511ef5ed890b29c980530903))
  * ElggData::save() returns bool ([627c2e7a](https://github.com/Elgg/Elgg/commit/627c2e7a40d38ad66df26d1a397901db14ccf518))
  * move exceptions to own namespace ([88e12e89](https://github.com/Elgg/Elgg/commit/88e12e89c4f7f2b0507a83eb38e8ca43e15dc1c4))
* **i18n:** removed some hardly used i18n lib functions ([5e768e41](https://github.com/Elgg/Elgg/commit/5e768e41b3e1110a49e70f8239264c777135eb5e))
* **icons:** old icon names are no longer supported / converted ([a00b2599](https://github.com/Elgg/Elgg/commit/a00b25999609cc452bd4ceab2fea9abc8da8c74f))
* **menu:** menu vars are required for preparing vertical and dropdown ([10125160](https://github.com/Elgg/Elgg/commit/10125160d3452304232bb879e92fba634695140f))
* **menus:** removed elgg_get_filter_tabs() ([37340f99](https://github.com/Elgg/Elgg/commit/37340f99dd7fcad884cb71dac5349460dcf1b631))
* **notifications:** moved notifications plugin to core ([0f141b73](https://github.com/Elgg/Elgg/commit/0f141b730b1f3e6115522025a03cca437e54d076))
* **web_services:** rewrite of the web services plugin ([fceb9130](https://github.com/Elgg/Elgg/commit/fceb91303819349ad20a67e19d27097c808c642a))
* **widgets:** change default widgets event registration ([2340f47b](https://github.com/Elgg/Elgg/commit/2340f47bcd24b2bb61cfb309336c91aab8273485))


#### Removed

* **core:**
  * support for the composer project as a 'plugin' ([f52f167c](https://github.com/Elgg/Elgg/commit/f52f167cca29be9340fe5f6d77425c4d81f5146f))
  * the Friendable interface has been replaced by a trait ([7f5ea445](https://github.com/Elgg/Elgg/commit/7f5ea4454041b59e19879c11aa5f7de6660d5593))
  * legacy plugin hook / event callback parameters ([ab19e9e9](https://github.com/Elgg/Elgg/commit/ab19e9e901a0105e1c3307f983ec9de82d3e12b9))
  * some hardly used functions have been removed/replaced ([b37e34c0](https://github.com/Elgg/Elgg/commit/b37e34c0fdc9fe1ba4320902c53385abfcecd9b2))
* **developers:** webservices inspection ([588534c0](https://github.com/Elgg/Elgg/commit/588534c0f26e1a078fba78431fb63b0538ade89e))
* **diagnostics:** the plugin has been removed ([4050e21a](https://github.com/Elgg/Elgg/commit/4050e21a9cce845fcdf51c32f4877e3a6308241e))
* **discussions:** no longer add an item to the groups filter menu ([c9d36b82](https://github.com/Elgg/Elgg/commit/c9d36b82fe72282e4256c5339d6499c8732c9263))
* **navigation:** helper view for menu item deps has been removed ([a2ed60fa](https://github.com/Elgg/Elgg/commit/a2ed60fab9f57e7447a0516ce0f0acbe6dd437f3))
* **notifications:**
  * pre Elgg 1.9 notification support is dropped ([949825a9](https://github.com/Elgg/Elgg/commit/949825a9675a79bc4c6355b8967f78668a29e6f3))
  * NotifcationService::getMethodsAsDeprecatedGlobal ([835a7218](https://github.com/Elgg/Elgg/commit/835a72184163e1b901e2bd6e5a143cfedd1ca2f0))
* **plugins:**
  * plugins no longer work with manifest files ([a17c8cec](https://github.com/Elgg/Elgg/commit/a17c8cec1fc22734bd782230939e58516cba6c15))
  * various plugins no longer listen to ECML hooks ([d5d922c5](https://github.com/Elgg/Elgg/commit/d5d922c58948ccb74352b8388547f7a6b42b1c25))
* **system_log:** various lib functions have been removed ([12108c9d](https://github.com/Elgg/Elgg/commit/12108c9dfb81155e8c446916104be666bd072b74), closes [#13089](https://github.com/Elgg/Elgg/issues/13089))
* **tags:** functions related to metadata tag names have been removed ([4ca58372](https://github.com/Elgg/Elgg/commit/4ca58372905cb0391a7a8c25110a7174ecb0e6af))
* **upgrades:** removed obsolete upgrades ([1f0c968c](https://github.com/Elgg/Elgg/commit/1f0c968cb69f5aa63b06831ff35f90a6c227f346))
* **users:** replaced validation methods with service functions ([821ccd95](https://github.com/Elgg/Elgg/commit/821ccd95e10c8e45b96f11de8dad88d1f6bbc23f))
* **views:** removed elgg_prepend_css_urls is no longer available ([53cb8819](https://github.com/Elgg/Elgg/commit/53cb881983b1aae39b9966e9d74de807a808f0b9))


<a name="3.3.21"></a>
### 3.3.21  (2021-08-03)

#### Contributors

* Jeroen Dalsem (1)

#### Bug Fixes

* **http:** always disable cache if cookie is being set ([30c17f06](https://github.com/Elgg/Elgg/commit/30c17f0644265086c7a61671487262fcfdf3cff3))


<a name="3.3.20"></a>
### 3.3.20  (2021-07-09)

#### Contributors

* Jerôme Bakker (4)

#### Bug Fixes

* **admin:** allow admins to be added from the admin listing page ([8d94877f](https://github.com/Elgg/Elgg/commit/8d94877faa4f6d9c34bd738776d546785883176e))
* **database:** use correct port number in Phinx migrations ([0ee77635](https://github.com/Elgg/Elgg/commit/0ee77635cbe9e71545194887bb432150b158da08))


<a name="3.3.19"></a>
### 3.3.19  (2021-06-10)

#### Contributors

* Jerôme Bakker (3)

#### Bug Fixes

* **output:** use correct number seperators ([536e2b26](https://github.com/Elgg/Elgg/commit/536e2b265243a14482d3244b652a9b9188e7bc91))


<a name="3.3.18"></a>
### 3.3.18  (2021-05-18)

#### Contributors

* Jerôme Bakker (3)
* Robert Cochran (1)

#### Documentation

* **install:** mention necessary SELinux changes ([912ca440](https://github.com/Elgg/Elgg/commit/912ca440bb78afa93e73de65ee7ca98e4c64692c))


#### Bug Fixes

* **account:** use consistent user throughout the account settings ([9e59117f](https://github.com/Elgg/Elgg/commit/9e59117f7315b9874c4df8de0507c343ee25d3e1))
* **search:** make sure entity_subtype is a string during search options ([94f110c6](https://github.com/Elgg/Elgg/commit/94f110c6e75fec8e84d146d400a0c7787cff0581))
* **webservices:** correctly evaluate truthy values ([86459670](https://github.com/Elgg/Elgg/commit/86459670aee19ed37bffc46c3eabf9eb39717e64))


<a name="3.3.17"></a>
### 3.3.17  (2021-04-16)

#### Contributors

* Jeroen Dalsem (2)
* Jerôme Bakker (2)
* Nikolai Shcherbin (1)

#### Bug Fixes

* **groups:** don't show 'Invite friends' menu item on the group's members page when 'Friends' plugin is deactivated ([5d2f8a32](https://github.com/Elgg/Elgg/commit/5d2f8a320f6d1a078bcb6f9648ba158358a302b0))
* **river:** river options annotation_ids now work as expected ([e1d61594](https://github.com/Elgg/Elgg/commit/e1d61594d278befae6d1edc300c33f13ccd6c40f))
* **thewire:** do not put unlimited description in notification summary ([657be642](https://github.com/Elgg/Elgg/commit/657be6420180d5638d219cbac5f621df4d1ff18e))


<a name="3.3.16"></a>
### 3.3.16  (2021-02-12)

#### Contributors

* Jerôme Bakker (4)
* Jeroen Dalsem (1)
* Nikolai Shcherbin (1)

#### Bug Fixes

* **cache:** disable filecache if path isn't writeable ([dc807d44](https://github.com/Elgg/Elgg/commit/dc807d44f971470bb29f00404a89c567d91f478f))
* **likes:** don't provide likes data in non default type ajax requests ([07d63d43](https://github.com/Elgg/Elgg/commit/07d63d437ac4bcba1e5b2c2e49d8bbd7175abb22))
* **menu:** don't show 'invite friends' menu item on the group profile when 'Friends' plugin is deactivated ([2783492a](https://github.com/Elgg/Elgg/commit/2783492aaa5b19a8f17e6dfec68a9903112cad16))


<a name="3.3.15"></a>
### 3.3.15  (2021-01-15)

#### Contributors

* Jerôme Bakker (4)

#### Bug Fixes

* **db:** pass previous database exception ([20e07d23](https://github.com/Elgg/Elgg/commit/20e07d238f110a6ae28a9fbaf488ebb3054dcedc))
* **icon:** use different icon size to check in icon remove ([8c42bf4f](https://github.com/Elgg/Elgg/commit/8c42bf4f53a93ce50b817df5be711d903ca37791))
* **icons:** prevent auto generation of icons during entity updates ([918a1193](https://github.com/Elgg/Elgg/commit/918a1193986ee0e689f8c823f0039100e92a8466))


<a name="3.3.14"></a>
### 3.3.14  (2020-12-18)

#### Contributors

* Jeroen Dalsem (8)
* Jerôme Bakker (3)

#### Bug Fixes

* **cache:** keep server cache in a local file storage ([0569862d](https://github.com/Elgg/Elgg/commit/0569862d9f07c4cd454fe0739bd19ff325ab2a81))
* **database:** correctly order metadata ([32fe6955](https://github.com/Elgg/Elgg/commit/32fe6955a31173f64318151b0bbeb11fdad8cace))
* **http:** allow access to client IP behind proxy server ([159e70ee](https://github.com/Elgg/Elgg/commit/159e70ee6e57b929bc5935f091a2bbb9280bc3f3))
* **search:** prevent duplicate extras matches in search results ([1211ae4d](https://github.com/Elgg/Elgg/commit/1211ae4d60d453b9351a62b2dfd02935af3a3ec6))
* **widgets:** correctly update widget title after widget update ([95535d24](https://github.com/Elgg/Elgg/commit/95535d2472bc4ba0d7b2a19e121dd1798c38692b))


<a name="3.3.13"></a>
### 3.3.13  (2020-11-20)

#### Contributors

* Jeroen Dalsem (3)
* Jerôme Bakker (2)

#### Performance

* **users:** entities metadata preloader logic now works for users ([74bfd360](https://github.com/Elgg/Elgg/commit/74bfd3604fd5df474121936f93a16dc04fa89255))


#### Bug Fixes

* **tests:**
  * compare objects that are both serialized and deserialized ([1af78826](https://github.com/Elgg/Elgg/commit/1af78826903c44daa2d351d240871eba37bd30e6))
  * entity preloader is clean before tests ([35a96147](https://github.com/Elgg/Elgg/commit/35a961477417c0df4ccd195e9a579a2f9da0b1fa))


<a name="3.3.12"></a>
### 3.3.12  (2020-10-30)

#### Contributors

* Jeroen Dalsem (4)
* Jerôme Bakker (1)
* Nikolai Shcherbin (1)
* RiverVanRain (1)

#### Bug Fixes

* **admin:** invalid admin section should report 404 not found ([bd4eb40f](https://github.com/Elgg/Elgg/commit/bd4eb40f7c6c9de10c057ff8c2b60b7d36047cd9))
* **developers:** do not log to screen in cli ([b1a06491](https://github.com/Elgg/Elgg/commit/b1a06491ff17e4246e781f0b49a43c62821b9570))
* **github:** make sure we use composer v1 during codecoverage tests ([25022dfd](https://github.com/Elgg/Elgg/commit/25022dfda1b66eda9420191db314f2ffd9d02503))
* **river:** "created", "river" event trigger regression ([718d79c8](https://github.com/Elgg/Elgg/commit/718d79c8d2f82e2333b48c461a71d6823bb65b26))
* **widgets:** content widget shows correct owner content ([c3c663d3](https://github.com/Elgg/Elgg/commit/c3c663d3de152e8cc7607be5af2ba84730e83dda))


<a name="3.3.11"></a>
### 3.3.11  (2020-10-02)

#### Contributors

* Jeroen Dalsem (3)
* Jerôme Bakker (1)

#### Bug Fixes

* **core:** do not save session for serve-file and CLI requests ([cf8ee303](https://github.com/Elgg/Elgg/commit/cf8ee30333d99f3ede516bd54fca135094332fbc))
* **database:** use compatible DBAL version ([c5ca05f1](https://github.com/Elgg/Elgg/commit/c5ca05f1bb3300b41dfaff181f531468cd606a30))
* **likes:** comment listings are now correctly preloaded with likes info ([f348802a](https://github.com/Elgg/Elgg/commit/f348802a2452284d632d5ecfcbf6ffc21e3d8e35))
* **session:** correctly set httponly flag for remember_me cookie ([91034947](https://github.com/Elgg/Elgg/commit/910349475b0c626c42156fcfeccb17ccec1f99b1))


<a name="3.3.10"></a>
### 3.3.10  (2020-09-04)

#### Contributors

* Jerôme Bakker (12)
* Team Webgalli (1)

#### Features

* **htaccess:** added hardening rules to prevent file access ([08ea7f7a](https://github.com/Elgg/Elgg/commit/08ea7f7a8ec41c9a844187b5209822236c8aafe2))


#### Bug Fixes

* **comments:** validate canComment in comments save action ([883be474](https://github.com/Elgg/Elgg/commit/883be4742d33e79f8bf05d8df4b56e9218c84fca))
* **composer:** define correct autoload namespace ([6f874ae2](https://github.com/Elgg/Elgg/commit/6f874ae2bf43b3f9751fe34d4e1d8eba509372c6))
* **developers:** inspect annotations menu no longer crashes ([096118a7](https://github.com/Elgg/Elgg/commit/096118a7a57151715c01a3af30aa5c35fd15c05b))
* **uservalidationbyemail:** show correct login error message ([db3cddd6](https://github.com/Elgg/Elgg/commit/db3cddd6c7a186ed661efbdd11e4364dc723a1c9))


<a name="3.3.9"></a>
### 3.3.9  (2020-08-17)

#### Contributors

* Jerôme Bakker (14)
* Jeroen Dalsem (1)

#### Bug Fixes

* **db:** handle empty dbprefix in join normalization ([fe3d1684](https://github.com/Elgg/Elgg/commit/fe3d1684f2f736fab22ccb034ac0cecef00b13e0))
* **search:** allow only unique field names to be searched ([4e540518](https://github.com/Elgg/Elgg/commit/4e5405188ddb6422784490e381350b790e0c3832))
* **tests:**
  * set config value in correct location ([05c0ff6e](https://github.com/Elgg/Elgg/commit/05c0ff6eba27abca8b94d67c3e9d160f619ce21f))
  * improved access array testing in walled garden mode ([c58a1543](https://github.com/Elgg/Elgg/commit/c58a15432cf42d667162c47c648c2352eb0e0b4e))
  * validate correct widget id for active plugin ([7ddc7743](https://github.com/Elgg/Elgg/commit/7ddc77434c800dc9df33e2a36a74e1d7668a22d5))
  * get correct plugin for deactivation testing ([f058db5d](https://github.com/Elgg/Elgg/commit/f058db5d6cbdff13b28efe74743349956a2a1331))
  * use language keys to validate ([e16b0166](https://github.com/Elgg/Elgg/commit/e16b0166a8ffc83fe16e4cf57e08cf61c38c5221))


<a name="3.3.8"></a>
### 3.3.8  (2020-07-10)

#### Contributors

* Jerôme Bakker (4)
* iionly (1)

#### Bug Fixes

* **cache:** support javascript source map files ([5ec82f20](https://github.com/Elgg/Elgg/commit/5ec82f204e9a9830655dd337d77d355171605633))
* **uservalidationbyemail:** corrected error in EN language file ([5e5adca4](https://github.com/Elgg/Elgg/commit/5e5adca4fa63c94da6dad2c9f403a1cae3ae6c77))
* **webservices:** implement missing cache functions ([deba4203](https://github.com/Elgg/Elgg/commit/deba42037777a4c6d34a62bb882012e7a98fdc2c))


<a name="3.3.7"></a>
### 3.3.7  (2020-06-30)

#### Contributors

* Jeroen Dalsem (2)
* Jerôme Bakker (2)
* RiverVanRain (1)
* Team Webgalli (1)

#### Documentation

* **webservices:** how to generate HMAC headers for authentication ([1a74457f](https://github.com/Elgg/Elgg/commit/1a74457fc5e6a2ef70cb2a563b2b2476b2c68d0e))


#### Bug Fixes

* **comments:**
  * allow group owners to edit comments in their group ([835478f8](https://github.com/Elgg/Elgg/commit/835478f8d6e0ed4f00437bf6f1a9d5c885b2a473))
  * use correct logic to apply default comments list length ([c556969e](https://github.com/Elgg/Elgg/commit/c556969e29f41977163a775a192f7bf939804740))
* **css:** admin menu header ([01e72dd1](https://github.com/Elgg/Elgg/commit/01e72dd1cf49c4ba8eb3c57e126efa9be8d2253a))
* **navigation:** allow configuration of max display items site menu ([cbede32e](https://github.com/Elgg/Elgg/commit/cbede32e168b9a309c133274b3d70de81a7afe78))


<a name="3.3.6"></a>
### 3.3.6  (2020-05-29)

#### Contributors

* Jerôme Bakker (5)
* Jeroen Dalsem (3)

#### Performance

* **likes:** bulk delete likes annotations on entity delete ([0b1d536c](https://github.com/Elgg/Elgg/commit/0b1d536c12b07e9c5dc7976c01344415ebf9790d))


#### Bug Fixes

* **db:** default case_sensitive not applied for single pair in root ([1ecd214c](https://github.com/Elgg/Elgg/commit/1ecd214c7668459688223c00faa60c0c6e5a87c0))
* **notifications:** only register group menu item if member of group ([a3a707ef](https://github.com/Elgg/Elgg/commit/a3a707efdcc2b77c63e7101090f3cdb89801025a))
* **redis:** admin information page now works if authorized ([7db8bbd8](https://github.com/Elgg/Elgg/commit/7db8bbd8854cfc07c48cec74e296dbabf6fd8e91))


<a name="3.3.5"></a>
### 3.3.5  (2020-05-15)

#### Contributors

* Jerôme Bakker (6)
* RiverVanRain (2)
* Dennis Ploeger (1)
* Jeroen Dalsem (1)

#### Features

* **cli:** Adds a --refresh option to the list command (#13201) ([eed99bfa](https://github.com/Elgg/Elgg/commit/eed99bfa660b03875de21fe4780a2a1e688beabf))


#### Bug Fixes

* **file:**
  * validate uploaded file for new files ([a3f4ed52](https://github.com/Elgg/Elgg/commit/a3f4ed52b8305d9e7ad40cd1d282333354a67b32))
  * correctly remove icons when updating a file ([d215defa](https://github.com/Elgg/Elgg/commit/d215defaface1cb4748209b69cd09c6ccee92b5b))
* **friends_collection:** display menu items correctly in full view ([b170b1f4](https://github.com/Elgg/Elgg/commit/b170b1f48928059884defc4ced71b9bc525cfac4))
* **input:** no longer use double submit prevention on widget edit forms ([ced827d5](https://github.com/Elgg/Elgg/commit/ced827d51126918126bd26a0214b7e28c5531d17))
* **mail:** improved handling of email recipients name formatting ([ff8a425a](https://github.com/Elgg/Elgg/commit/ff8a425ac5d3ddb6942d9d8026d0a8f294c4f89a))
* **plugins:** disabled plugins should not get a priority ([b856c449](https://github.com/Elgg/Elgg/commit/b856c4496f3d72b5b3f3cd9be20fe6764a5235ba))
* **views:** prevent PHP notice during registering views ([6b1bf6e4](https://github.com/Elgg/Elgg/commit/6b1bf6e4242bd150669116eeaaa675e94fa8aaaa))


<a name="3.3.4"></a>
### 3.3.4  (2020-04-24)

#### Contributors

* Jerôme Bakker (4)
* Jeroen Dalsem (1)

#### Bug Fixes

* **core:**
  * use correct input for password reset ([5ceaed52](https://github.com/Elgg/Elgg/commit/5ceaed5210b2270f234c74c44a30df824162eba1))
  * log exceptions by default ([3d085449](https://github.com/Elgg/Elgg/commit/3d0854490bd7e1a20e6a1ab72dc04bf5822ae692))
* **developers:** remove entity button in explorer now works ([fed4809a](https://github.com/Elgg/Elgg/commit/fed4809ac389eab149bf4dc3f2ed2bde052367d6))
* **logger:** correctly support legacy value 'OFF' ([df80433c](https://github.com/Elgg/Elgg/commit/df80433c6a6f64066ad7dcb5b4b002bdf3be7fc0))


<a name="3.3.3"></a>
### 3.3.3  (2020-03-27)

#### Contributors

* Jerôme Bakker (4)
* Jeroen Dalsem (2)

#### Bug Fixes

* **livesearch:** use correct relationship options to find groups ([dc82fd9e](https://github.com/Elgg/Elgg/commit/dc82fd9e92a141b27b97a0149d8289764eb45fd8))
* **mail:** additional library for SMTP e-mail support ([d69b90df](https://github.com/Elgg/Elgg/commit/d69b90dfba56e166ddbb255ac2f3a9e9ce433772))


<a name="3.3.2"></a>
### 3.3.2  (2020-03-13)

#### Contributors

* Jeroen Dalsem (6)
* Jerôme Bakker (4)

#### Bug Fixes

* **notifications:** listing of settings now contain a link to the item ([4a2ae1de](https://github.com/Elgg/Elgg/commit/4a2ae1dee0539fabc20a1ad9ebb6b3bff8133021))
* **webservices:** use correct query string for hmac authorisation ([1dc4cae9](https://github.com/Elgg/Elgg/commit/1dc4cae94c8fb0dc43a1930e1995d4c51c186420))


<a name="3.3.1"></a>
### 3.3.1  (2020-02-14)

#### Contributors

* Jeroen Dalsem (6)
* Jerôme Bakker (5)

#### Bug Fixes

* **blog:** double submit protection prevented correct saving ([2460d178](https://github.com/Elgg/Elgg/commit/2460d178f0f1c845a0b9489de482def1d101ffde))
* **file:** correctly set forward url ([10f48139](https://github.com/Elgg/Elgg/commit/10f48139914697e69dea27c5c2af9ddbd34925be))
* **likes:** likes popup will show recent likes first ([9b0bf45d](https://github.com/Elgg/Elgg/commit/9b0bf45d42c70fc374a7381bd632cee1e3a532e3))
* **plugins:**
  * reindex plugins if there is a gap in the priority ([ce2d4bfb](https://github.com/Elgg/Elgg/commit/ce2d4bfb81dedc3fd6414a4190aa07c0066d3b69))
  * default all plugins are listed ([5888aa89](https://github.com/Elgg/Elgg/commit/5888aa89d3d1aab2c29a71074754ed5c8f27199f))
  * correctly flush caches after plugin (de)activation ([7a6465b5](https://github.com/Elgg/Elgg/commit/7a6465b52366e02abab3216772e8f96e523b9dd6))
* **route:** correctly handle route generation for unicode usernames ([58766e37](https://github.com/Elgg/Elgg/commit/58766e37fa252ba3398823963d4cfa98358948a4))
* **views:** input/autocomplete correctly passes match_target ([1fe233d2](https://github.com/Elgg/Elgg/commit/1fe233d2adb18f27f522680037f151394bed6cc3))


<a name="3.3.0"></a>
## 3.3.0  (2020-01-23)

#### Contributors

* Jeroen Dalsem (58)
* Jerôme Bakker (34)
* Josh Santos (1)

#### Features

* **access:** added generic container logic check for group tool option ([eb129203](https://github.com/Elgg/Elgg/commit/eb1292030b8c397a170cd9c26805488fd50ecfed))
* **admin:**
  * plugin list is default filtered by active state ([e3adc687](https://github.com/Elgg/Elgg/commit/e3adc687c091215126e7eb81cbedf4f67f94394c))
  * added site setting to control allowed languages ([663fb447](https://github.com/Elgg/Elgg/commit/663fb447b43c9142f194da2f17b2758b0c14ee10))
* **annotations:** added generic annotation delete action ([1ebeafb4](https://github.com/Elgg/Elgg/commit/1ebeafb44c40e868313509952c3f4f765647ce22))
* **cache:** added more cache interactions ([8dbc51ce](https://github.com/Elgg/Elgg/commit/8dbc51cee3b134496e6d9be311e8bc5622db1a53))
* **cli:**
  * added upgrade:list command to list all upgrades in the system ([364d0016](https://github.com/Elgg/Elgg/commit/364d00166cd6fe92074dcfd0a3a401d95c070468))
  * added command to execute a single upgrade ([ed14adf0](https://github.com/Elgg/Elgg/commit/ed14adf09fdc579eed3b06a05c280b1aad1c67c6))
  * added option to set language for cli command ([d11d0581](https://github.com/Elgg/Elgg/commit/d11d0581daeaccbf8100bf3e28815b4c580c0dd8))
  * all core cli commands use translation keys ([5355f270](https://github.com/Elgg/Elgg/commit/5355f2704ca63f1fafa1ab15ef8f5793d4396611))
  * question defaults are automatically added to question ([b2cdf54a](https://github.com/Elgg/Elgg/commit/b2cdf54a174184a47b35ac0749bed7889f8ebbfb))
* **core:**
  * you can now configure the default sort order of comments ([573d416d](https://github.com/Elgg/Elgg/commit/573d416d9443065d7ea0f8cd5a439fd124d9c063))
  * added mimetype detection service ([54e2574b](https://github.com/Elgg/Elgg/commit/54e2574beb44f68b4dea60b1eaed91663557a85e))
  * container last_action is updated when entities are created ([9f0a706b](https://github.com/Elgg/Elgg/commit/9f0a706b9f6efa762ccd2984214d553e01110efd))
* **css:** walledgarden background image can be set as a css variable ([939bf26e](https://github.com/Elgg/Elgg/commit/939bf26e23076b641d340784f2aa7e9dd12be821))
* **developers:** added entity explorer link to every entity ([0c580432](https://github.com/Elgg/Elgg/commit/0c580432628f4d504e6714cc102d8db7fb3f7f26))
* **discussions:**
  * add page to view discussions in my groups ([81c1cab0](https://github.com/Elgg/Elgg/commit/81c1cab0a5bde9e839ec1a6932fb8cbeaef15287))
  * added setting to allow site wide discussions ([5f4dc826](https://github.com/Elgg/Elgg/commit/5f4dc82618b29432bccafd1b0cc6e0d43fbdba42))
* **github:** added codeclimate coverage action ([e695efe5](https://github.com/Elgg/Elgg/commit/e695efe55f3f41bb8e7bdd737f9646d4a501e04d))
* **i18n:** added user agent language detection ([e5778f5d](https://github.com/Elgg/Elgg/commit/e5778f5d6e16f20bafdd05324109bb56fbe0b512))
* **input:**
  * forms now have the ability to prevent double submit ([f4e21fd2](https://github.com/Elgg/Elgg/commit/f4e21fd2a07da089d9dae7d205adf20d1b2c72a0))
  * added confirm option to input/submit ([60a67b55](https://github.com/Elgg/Elgg/commit/60a67b55b44a16ea0c3e3df0e9330964c7787588))
* **list:** we provide a way back if you reach a page without content ([b3ee54c5](https://github.com/Elgg/Elgg/commit/b3ee54c5c74230d7fc76650e105f0888c4168719))
* **mail:** add smtp support ([ada8bb7d](https://github.com/Elgg/Elgg/commit/ada8bb7d50e685db7bef8df8e9e6f91e990103f1), closes [#12938](https://github.com/Elgg/Elgg/issues/12938))
* **notifications:** unregister specific notification action ([19f3571b](https://github.com/Elgg/Elgg/commit/19f3571b197a2aa5009253da6d38618995ddbaf1))
* **plugins:**
  * you can change/add theme variables in elgg-plugin.php ([f698d00a](https://github.com/Elgg/Elgg/commit/f698d00a8374562cea328a7404f66e6635ecd88b))
  * added the ability to (un)extend views in elgg-plugin.php ([d16fb845](https://github.com/Elgg/Elgg/commit/d16fb845c30ee1a105e86ea60939935bec26d021))
  * add ability to remove all plugin settings ([28cb247d](https://github.com/Elgg/Elgg/commit/28cb247df17619abc3e296b7b994968fc133b70a))
  * cli commands can now be registered in elgg-plugin.php ([d0858cc8](https://github.com/Elgg/Elgg/commit/d0858cc8730d5c686a06b18d4819e202adf7497e))
* **tests:** added MySQL 8 test job ([63235d90](https://github.com/Elgg/Elgg/commit/63235d90600f7a8bd3b558d9a88d839da346d725))
* **views:**
  * added site setting to control if comment box collapses ([043d6789](https://github.com/Elgg/Elgg/commit/043d678920bbcfcd6890f3c390aa19c25ed951bf))
  * info type added to elgg_view_message ([006151fa](https://github.com/Elgg/Elgg/commit/006151fa0a50968555b6b9a372afd031e1ed7da4))
  * seperate annotation view into subparts ([b080fd25](https://github.com/Elgg/Elgg/commit/b080fd25239c9346816c402cbd69888d95eeae0c))
  * input/button now supports icon_alt ([18425695](https://github.com/Elgg/Elgg/commit/18425695a61b42107ca672df7f7727fd6c98e4cf))
  * added ability to elgg_view_page to pass layout vars ([002ba980](https://github.com/Elgg/Elgg/commit/002ba980e1f54d8931f9f1d57c70003b6cd012be))


#### Performance

* **js:** refresh_token now uses a partial boot of the engine ([23846134](https://github.com/Elgg/Elgg/commit/23846134c6bb2d557fa11f4f06a71b9217a157c2))


#### Documentation

* **views:** rewrite of the page structure guide ([a5272a66](https://github.com/Elgg/Elgg/commit/a5272a666137bd4032951baf3b3991cc53a6f290))


#### Bug Fixes

* **blog:** no longer validate container permissions on existing blogs ([e3b1c6c6](https://github.com/Elgg/Elgg/commit/e3b1c6c6d18078e40bf1e553f7d4facc1737343d))
* **cli:** improved error handling during command execution ([c548e95b](https://github.com/Elgg/Elgg/commit/c548e95b8f7a8bfaa526006343f254ddd0174958))
* **core:** no longer default to client file mime type ([73f44c03](https://github.com/Elgg/Elgg/commit/73f44c0315d2fd09bedf63f3674ab499d7717e79))
* **http:** prevent content type sniffing by browsers ([62743b8f](https://github.com/Elgg/Elgg/commit/62743b8faa3a9ff90fe93b0af11fb8dbbab94e5c))
* **js:** prevent clicks until related js is loaded ([9e638c9e](https://github.com/Elgg/Elgg/commit/9e638c9ebbd31972185b85eda3f8d93a7fc6c64b))
* **views:** moved user element to correct subviews ([2a96a19a](https://github.com/Elgg/Elgg/commit/2a96a19a1a25017c194a876b036663cb7517a8ed))


#### Deprecations

* **core:**
  * function generate_action_token is deprecated ([11020308](https://github.com/Elgg/Elgg/commit/110203088870dbfe95f4a0ee4730b740268926ad))
  * functions to get ordered event/hook handlers ([3a47f381](https://github.com/Elgg/Elgg/commit/3a47f3814ca8d7f0503e62259e1ee0919c856d7f))
  * various functions have been deprecated ([0f47534e](https://github.com/Elgg/Elgg/commit/0f47534ec38db4c1360506fed1cc67a6f57aa56e))
* **plugins:**
  * using a start.php in you plugin is deprecated ([b744960f](https://github.com/Elgg/Elgg/commit/b744960f5e54e875455d83611090d2a3f8423edc))
  * function elgg_unset_all_plugin_settings() ([36aa2e41](https://github.com/Elgg/Elgg/commit/36aa2e4198bd587bc6ca7d886893b4cbdf334f78))
* **views:**
  * the function elgg_view_entity_annotations ([5f2b1bf3](https://github.com/Elgg/Elgg/commit/5f2b1bf3c88e22528fc483dc2f74079e9e6369ce))
  * deprecated the usage of some old layout names ([79a373dd](https://github.com/Elgg/Elgg/commit/79a373ddb6603633537fb2183dea2a3a0e94b1b9))


<a name="3.2.4"></a>
### 3.2.4  (2020-01-23)

#### Contributors

* Jeroen Dalsem (5)
* Jerôme Bakker (2)

#### Bug Fixes

* **cron:** allow configuration for custom cron intervals ([8e969a67](https://github.com/Elgg/Elgg/commit/8e969a673086e7deed3eef8cc961c756ed9ce7ad))
* **developers:** set correct default value for menu hooks ([d3bc28d6](https://github.com/Elgg/Elgg/commit/d3bc28d689b89caa95afef6d0438d2d5eef514f6))
* **installer:** escape some special chars in db password ([9923e173](https://github.com/Elgg/Elgg/commit/9923e173b794fb2f9db9eccbcad60a815c6f6dc3))
* **search:** do not try a search with invalid query ([28cd2ead](https://github.com/Elgg/Elgg/commit/28cd2eadf1620ca0a13c397d7948f1fc3a46ce54))
* **user:** user hover menu uses local data array of request params ([6a0f28f2](https://github.com/Elgg/Elgg/commit/6a0f28f20f96402119037a5f241ecd6688ba38ec))


<a name="3.2.3"></a>
### 3.2.3  (2019-12-20)

#### Contributors

* Jerôme Bakker (7)
* Jeroen Dalsem (3)

#### Features

* **groups:** add limited group creation middleware ([9ea474e1](https://github.com/Elgg/Elgg/commit/9ea474e1444accea7eaf425993fed73cb7507fa8))


#### Documentation

* **actions:** updated action documentation ([3e90769f](https://github.com/Elgg/Elgg/commit/3e90769f67b0c19ddf1296b7fdf6097642efe9f8))


#### Bug Fixes

* **icons:** always use default viewtype when using fallback icons ([b2c666b7](https://github.com/Elgg/Elgg/commit/b2c666b70a3f7dd2bc98def976510cb62d9f21bd))
* **search:** search result extras are formatted correctly ([3fd1355d](https://github.com/Elgg/Elgg/commit/3fd1355d1fe999935919c7e81e6a84869536a807))
* **views:** use correct summary classes in relationship elements ([30ef1c87](https://github.com/Elgg/Elgg/commit/30ef1c87d0660d93eb3737dc387378d3b2ca5f8d))


<a name="3.2.2"></a>
### 3.2.2  (2019-12-06)

#### Contributors

* Jeroen Dalsem (8)
* Jerôme Bakker (1)

#### Documentation

* **database:** document the need and usage of manual Phinx migrations ([3d8d5ac4](https://github.com/Elgg/Elgg/commit/3d8d5ac4ada0be58cdb2bb9a61669a5d9d475d02))


#### Bug Fixes

* **core:** plugin reports version of elgg_release ([f86e898a](https://github.com/Elgg/Elgg/commit/f86e898a5c6cc2f17684eee23f625cdf170614b7))
* **css:** vertical align horizontal aligned fields in fieldset ([78bb72f3](https://github.com/Elgg/Elgg/commit/78bb72f3e1d4c7ccb8e72bdb939d112d24c3a329))
* **groups:** added missing gatekeepers to content pages ([ecb9fb8f](https://github.com/Elgg/Elgg/commit/ecb9fb8f0c1e353c533f5a89b1843c79824967f9))
* **input:** htmlawed will no longer check for duplicate ids ([cbad1c25](https://github.com/Elgg/Elgg/commit/cbad1c2568bbe74904047115889d9b6c61664c3e))
* **tests:** moved integration test to correct suite ([8b4e0d40](https://github.com/Elgg/Elgg/commit/8b4e0d406e0803d74c008549c8660ed7ff5488df))
* **theme_sandbox:** changed aside modules to info modules ([ba6bccf4](https://github.com/Elgg/Elgg/commit/ba6bccf45e2474299f3da1e55c9838016e6eb8b8))


<a name="3.2.1"></a>
### 3.2.1  (2019-11-25)

#### Contributors

* Jerôme Bakker (13)
* Jeroen Dalsem (6)
* Dennis Ploeger (1)

#### Features

* **tests:** allow testing of tool protected group pages ([5b465453](https://github.com/Elgg/Elgg/commit/5b465453e7f94b2b62ec921c9d9dbae463235bdd))


#### Documentation

* **cli:** added link to symfony ([dbb3d32c](https://github.com/Elgg/Elgg/commit/dbb3d32c171de4aa89f90ef28fd11b48047f34c3))


#### Bug Fixes

* **admin:** use correct function for unvalidated users notifications ([bbce4ca0](https://github.com/Elgg/Elgg/commit/bbce4ca034cace244d471ca0fa0dd4ea33166e1d))
* **file:**
  * use correct submit label on upload/update of file ([07ef13d1](https://github.com/Elgg/Elgg/commit/07ef13d186dbf07998ce3df8d4d91f904e5468bc))
  * use correct view in old group module ([56a46f94](https://github.com/Elgg/Elgg/commit/56a46f94ec2b57a1956af24875676bf30b5387b3))
  * added group tool gatekeeper to owner resource ([4667d9fe](https://github.com/Elgg/Elgg/commit/4667d9fe089d55d6ce804ee1c5df3415fad9dac4))
* **friends:** supply correct link for friend request notification ([21e231a0](https://github.com/Elgg/Elgg/commit/21e231a09aa994dd619e8fa78883935240dae2ae))
* **groups:**
  * set menu item classes the correct way ([dc161621](https://github.com/Elgg/Elgg/commit/dc1616218a69810bcad3426a260f70c351d9ff46))
  * show a no result text on the group membership request page ([db601b71](https://github.com/Elgg/Elgg/commit/db601b71d954d36c0a5ea7aac5a00f2c1aac40b8))
* **input:** select now supports passing int values to options_values ([4563eed4](https://github.com/Elgg/Elgg/commit/4563eed436aa0a6984b8f2be300cb74e55620a6a))
* **installer:** allow empty database table prefix during installation ([622b47c0](https://github.com/Elgg/Elgg/commit/622b47c02513f054a05c546487ba2f29b938c4bd))
* **seeder:** use available users during seeding ([716e02fb](https://github.com/Elgg/Elgg/commit/716e02fbfa80cbe2259a5011eab722215483b8c3))
* **system_log:** check archive engine availability before changing ([99e3c928](https://github.com/Elgg/Elgg/commit/99e3c928a8d1326c973c4a816ad5adfff552f8c8))


<a name="3.2.0"></a>
## 3.2.0  (2019-10-24)

#### Contributors

* Jerôme Bakker (40)
* Jeroen Dalsem (18)

#### Features

* **admin:** added a site setting to require admin approval of accounts ([2882da61](https://github.com/Elgg/Elgg/commit/2882da617e57830193ef2e33b269417b9b961dc6))
* **core:**
  * added elgg_list_relationships ([9b54398c](https://github.com/Elgg/Elgg/commit/9b54398c732e0e191051deb75e31c777412f8890))
  * added elgg_get_relationships function ([21d3c697](https://github.com/Elgg/Elgg/commit/21d3c697ed842f43108826f205de0da8f05bb6a9))
* **database:** clauses support invokable classes ([75653f24](https://github.com/Elgg/Elgg/commit/75653f24f97029befc963cfd378f1cef28b1dba3))
* **files:** download urls can be configured to not expire ([f61c5578](https://github.com/Elgg/Elgg/commit/f61c55780a9b763c2d51aea1d096cb93b0f84cc2))
* **friends:** add plugin setting to enable the need for friend approval ([25b87858](https://github.com/Elgg/Elgg/commit/25b87858d9c3dc3196e5d399e51940db7944301d))
* **groups:**
  * added option to hide group owner tranfer ([4a072e88](https://github.com/Elgg/Elgg/commit/4a072e88b186772b2096d9e22b6be3b6782e0ea6))
  * allow groups to set default content access ([9f701b77](https://github.com/Elgg/Elgg/commit/9f701b77e21be728ccd087fe80eb8425af2efbf8))
  * membership requests and invitations use relationship views ([afff8d9f](https://github.com/Elgg/Elgg/commit/afff8d9f806dc81fad6473cf0e386ffb214f48f0))
  * add invited users page for group owners ([82310d78](https://github.com/Elgg/Elgg/commit/82310d7845d0aa72d1f135ae9e361d01550b2378))
  * added membership requests tab to group members listing ([019745f0](https://github.com/Elgg/Elgg/commit/019745f07294fc988dcb70b9243d95b54ce353d0))
  * add invite button on group members page ([9e164221](https://github.com/Elgg/Elgg/commit/9e16422127301117cf9165f87174c959252a538a))
  * show user already a member/invited for the group ([57d38cca](https://github.com/Elgg/Elgg/commit/57d38ccaf0cbaff83f4d14c2500b84946f11d43f))
  * member listing now uses elgg_list_relationship ([783f22a6](https://github.com/Elgg/Elgg/commit/783f22a637d24ca634d3ab7bdebe1f185abaac4b))
* **install:** allow user to change language during installation ([5f23eff1](https://github.com/Elgg/Elgg/commit/5f23eff1e5eb2be5409657bddbd31d5ad2414a7f))
* **livesearch:** added ability to set custom item_view ([8c1df6e8](https://github.com/Elgg/Elgg/commit/8c1df6e8a7627c077cfb4172bfab6fc6d2093f94))
* **middleware:** added page owner can edit middleware ([b81fc729](https://github.com/Elgg/Elgg/commit/b81fc7295a4c9b12791a85e382ae29576204e67d))
* **redis:** added the ability to set redis database and password ([e0c9d953](https://github.com/Elgg/Elgg/commit/e0c9d95311ef661664fdd941781d761905ca2f65))
* **request:** allow access to the http request ([7e86f576](https://github.com/Elgg/Elgg/commit/7e86f576dfac4734e806127704dbb3e3a9d67782))
* **security:** allow password requirement to be configured ([ccca6897](https://github.com/Elgg/Elgg/commit/ccca689777eae2cfaff2546a7289418880acdaba))
* **system_log:** allow logbrowser to filter on object id ([de0e6171](https://github.com/Elgg/Elgg/commit/de0e61713084386f1d1f3f11532453536f0e1d3d))
* **upgrades:** track upgrade start time ([f37d03bc](https://github.com/Elgg/Elgg/commit/f37d03bc3440a6e49aa4a1c6d5f25a784ead786c))
* **webservices:**
  * added api token management ([b146d097](https://github.com/Elgg/Elgg/commit/b146d097460da0c32f580953f115f1e0a2140ba8))
  * added webservices listing ([5d2026de](https://github.com/Elgg/Elgg/commit/5d2026ded20c695ea9c52dde08ddae2067ff7235))


#### Documentation

* **routing:** added some extra middleware documentation ([b1c123ca](https://github.com/Elgg/Elgg/commit/b1c123cac20c18429d68b452ebcd80bf892731a9))


#### Bug Fixes

* **access:** allow plugin to register write access array subtypes ([6db1d505](https://github.com/Elgg/Elgg/commit/6db1d505d679d3af74b4a8fd2bed28d8af0703dc))
* **ckeditor:** do no convert html entities in the editor ([e977bf99](https://github.com/Elgg/Elgg/commit/e977bf9972f6fd5d96267921a0be82aa0d2086e1))
* **core:**
  * ElggRelationship int attributes are casted to ints ([d3604557](https://github.com/Elgg/Elgg/commit/d3604557dab4ead9e698f8a2888c5d829236c5ce))
  * make accesscollection save and rename work like documented ([c3acbb1a](https://github.com/Elgg/Elgg/commit/c3acbb1afc4cfc74d7dab34750c4fa55edae7296))
* **css:** buttons in some menus keep correct styling ([3eb49098](https://github.com/Elgg/Elgg/commit/3eb49098840ffe7705ef1058868ef55bcb61d307))
* **embed:** no longer require a plugin with file_uploading provided ([3925c647](https://github.com/Elgg/Elgg/commit/3925c6477abfaf248f4cbceef9e3ce4cb2d993e7))
* **js:** deep merge the elgg.data ([197bfb49](https://github.com/Elgg/Elgg/commit/197bfb49c24d249a844144f17ff5fe5e65d5592d))


#### Deprecations

* **river:** no longer use the enabled property of river items ([59ebfb3d](https://github.com/Elgg/Elgg/commit/59ebfb3d638e80f8f18fade846ffccd0e41ba846))


<a name="3.1.6"></a>
### 3.1.6  (2019-10-23)

#### Contributors

* Jeroen Dalsem (5)
* Jerôme Bakker (1)

#### Documentation

* **database:** document innodb recommended settings ([7cfaf39a](https://github.com/Elgg/Elgg/commit/7cfaf39aa6fe7c9bdee59cc93764f0cd707482e0))


#### Bug Fixes

* **access:** do not add default access level if missing in input/access ([58efbbc1](https://github.com/Elgg/Elgg/commit/58efbbc121221ef6d45a1aefada66fc65361c21d))
* **js:**
  * only trigger open event when new content is loaded ([83f67a81](https://github.com/Elgg/Elgg/commit/83f67a812d11d748a778f6c5e7f7ec6cc0e986a6))
  * if no tabs are selected click the first tab ([66c9e9ba](https://github.com/Elgg/Elgg/commit/66c9e9ba4c5d63cbdc4cb3751c65616aca6d9a8d))
* **views:** only add a link in summary title if entity can provide one ([b9b70f7a](https://github.com/Elgg/Elgg/commit/b9b70f7ae2615c0c2f21c9892de0e6c4831060c2))


<a name="3.1.5"></a>
### 3.1.5  (2019-10-11)

#### Contributors

* Jeroen Dalsem (7)
* Jerôme Bakker (2)

#### Documentation

* **plugins:** added some info about class structures in plugins ([03536970](https://github.com/Elgg/Elgg/commit/0353697086279b5c0caad23a8168cce0ac4a4e9a))
* **webservices:** document the result structure of webservice calls ([9a6221a7](https://github.com/Elgg/Elgg/commit/9a6221a73c85c02786d927c034981f9a806a5903))


#### Bug Fixes

* **core:** allow passing only name or value into name/value pairs ([e2e26677](https://github.com/Elgg/Elgg/commit/e2e26677af1c1d3f4da6b97634748781f5c8ccf5))
* **js:** stop refreshing tokens if session changed ([bb4a7894](https://github.com/Elgg/Elgg/commit/bb4a789479683402d4dfbe53facdfeb4a8c24d16))
* **pages:** correctly validate write permissions ([a3fbc109](https://github.com/Elgg/Elgg/commit/a3fbc10971f4677de4f2a1f6c21a7f428f0e5e7f))


<a name="3.1.4"></a>
### 3.1.4  (2019-09-27)

#### Contributors

* Jerôme Bakker (5)
* Jeroen Dalsem (2)

#### Bug Fixes

* **messageboard:** use the correct annotation_name for listings ([95429878](https://github.com/Elgg/Elgg/commit/95429878cfb6a34625c637feb1991a717f6b599a))
* **tests:** correctly validate response headers ([2ccd1833](https://github.com/Elgg/Elgg/commit/2ccd1833d86a6920c168827f8fe9cd31b2711a5c))


<a name="3.1.3"></a>
### 3.1.3  (2019-09-13)

#### Contributors

* Jeroen Dalsem (4)
* Jerôme Bakker (4)

#### Features

* **ajax:** added option to suppress messages from ajax calls ([2b728fe5](https://github.com/Elgg/Elgg/commit/2b728fe5d3769b188a266cb657b2279c64c570c2))


#### Bug Fixes

* **admin:** do not show ajax success message when removing admin notice ([75bf8cbd](https://github.com/Elgg/Elgg/commit/75bf8cbd5b82b0644b4fb10c402fc982ac0102f7))
* **database:** updated Phinx to prevent Symfony conflicts ([58d778a6](https://github.com/Elgg/Elgg/commit/58d778a65f74552160bbe50be8cf8c02b98aaf61))
* **uservalidationbyemail:** correct forwarding during email confirmation ([e3ccb067](https://github.com/Elgg/Elgg/commit/e3ccb0671fb0512a4448820deb1c6e026d78ffea))


<a name="3.1.2"></a>
### 3.1.2  (2019-08-28)

#### Contributors

* Jerôme Bakker (7)
* Esha Upadhyay (1)

#### Bug Fixes

* **admin:** prevent fatal error on non Apache servers ([3da30342](https://github.com/Elgg/Elgg/commit/3da303427bdcc30f094d4142277fdfdb9130820d))
* **cache:** invalidating simplecache could break symlinked cache ([c8a41062](https://github.com/Elgg/Elgg/commit/c8a41062705b3c0f3f58d8c44577f02746f6d418))
* **comments:** block comments on group content for non members ([6482879a](https://github.com/Elgg/Elgg/commit/6482879af8a79eadd4be2acc36f191d04e1f5858))
* **developers:** link to site settings section ([330676bb](https://github.com/Elgg/Elgg/commit/330676bbb9cb8cd5f4b613241a71454bbf80d59a))
* **discussions:** no more filter menu on discussion detail page ([e8d60f6c](https://github.com/Elgg/Elgg/commit/e8d60f6c562bb2c56aa53e66eb47dda172cffe72))
* **installer:** link to site settings section ([f0e3dbe5](https://github.com/Elgg/Elgg/commit/f0e3dbe5c5c8c18a4fa62adf1c443550f043893e))


<a name="3.1.1"></a>
### 3.1.1  (2019-08-02)

#### Contributors

* Jeroen Dalsem (2)

<a name="3.1.0"></a>
## 3.1.0  (2019-07-25)

#### Contributors

* Jeroen Dalsem (81)
* Jerôme Bakker (23)
* Rohit Gupta (9)
* Ismayil Khayredinov (1)
* Joe Bordes (1)

#### Features

* **admin:**
  * added requirements information about database server ([d9c92dab](https://github.com/Elgg/Elgg/commit/d9c92dabe99a5a3f8723cd9d2f9c8a1872c8c329))
  * add email change option to unvalidated users ([f09ba7ee](https://github.com/Elgg/Elgg/commit/f09ba7ee48fd869a275dbfded004537ecbdda532))
  * add server requirements page ([4e5cd057](https://github.com/Elgg/Elgg/commit/4e5cd057a5e72abf3d84e880d0e3f16f8e6baa77))
  * moved Elgg release to page header ([f55d0f1d](https://github.com/Elgg/Elgg/commit/f55d0f1d705d68ee791dbc7796647b1011f26ca8))
  * add security recommendations page ([e129b307](https://github.com/Elgg/Elgg/commit/e129b307e5d71b5c6f5f8fc8a63e76d07cc0f75c))
  * add performance overview page ([f1321a2f](https://github.com/Elgg/Elgg/commit/f1321a2f688b3953f630edc005208a87f46ba11b))
  * admin user lists now have the ability to search by email ([c34789f4](https://github.com/Elgg/Elgg/commit/c34789f4c8c5e208047d3cab3309846887400746))
  * add admins directly from the administrators page ([78027dda](https://github.com/Elgg/Elgg/commit/78027dda9ebc940a8a9a8728af172f36924d8b5b))
  * basic and advanced settings are merged into one form ([aedaa0e1](https://github.com/Elgg/Elgg/commit/aedaa0e1578f07f34fab0c7fe3356e3800b191be))
* **ckeditor:**
  * updated to ckeditor v4.12.x ([33b44604](https://github.com/Elgg/Elgg/commit/33b446047e106562ecbe0a8c8d538a8e949a12da))
  * updated ckeditor version to 4.11.x ([d6061b3f](https://github.com/Elgg/Elgg/commit/d6061b3f639c01aba56ecf7b611db78205103c56))
* **core:**
  * error resources now have access to the exception ([fac3141e](https://github.com/Elgg/Elgg/commit/fac3141e502bd36148064eb0d072436ff10391af))
  * added function to convert large numbers into short form ([de9d2ef8](https://github.com/Elgg/Elgg/commit/de9d2ef8eb3412e70d5125aff317925c2ad6a4d9))
  * admin notices now have their own class ([a627d4ef](https://github.com/Elgg/Elgg/commit/a627d4ef2fbd081211b09afe392d6f0a4fd448b8))
  * manifest.json is now a cacheable simplecache resource ([ef98f420](https://github.com/Elgg/Elgg/commit/ef98f420e34fac37f9b4bdf859ddad6bf33d3997))
* **db:** allow configuration of the database port number ([058db755](https://github.com/Elgg/Elgg/commit/058db7550fae54f50e38be893912bc46f04c887e))
* **developers:**
  * wrap input and output views ([cafdb455](https://github.com/Elgg/Elgg/commit/cafdb45507c4c631125ec50bc0eb756491c4e9a3))
  * display view location in view wrapping ([e6ba1ecf](https://github.com/Elgg/Elgg/commit/e6ba1ecf1650f65485167e2545e0fd83268ad8c3))
  * added acl information to entity explorer ([9c465a1a](https://github.com/Elgg/Elgg/commit/9c465a1a714327521d3828372e79dca5939f83dd))
* **entities:** added helper function elgg_count_entities ([7e00cbc7](https://github.com/Elgg/Elgg/commit/7e00cbc775d8459478d4af8d5fc66178e129e53f))
* **gatekeeper:** flag to validate user edit access ([8becf0ea](https://github.com/Elgg/Elgg/commit/8becf0eacb3ba8749a84696c147dfc26722b16b1))
* **groups:** support content based on type/subtype in tool module ([fa897bcb](https://github.com/Elgg/Elgg/commit/fa897bcbff02d18441f48065d3e621370da1896b))
* **icons:** add icon cropper ([deb5d212](https://github.com/Elgg/Elgg/commit/deb5d21288ae7d15dd37f1d3f31db8dd4841d6bf))
* **input:** add support for more input types ([048704e2](https://github.com/Elgg/Elgg/commit/048704e20b6b39e6e89eb259c42ff16e6c0ceb7d))
* **menus:** added menu param to set a selected menu item ([74d50561](https://github.com/Elgg/Elgg/commit/74d505614b3130d8c3b1220b6c2505edd8f663ba))
* **notifications:** Elgg\Email knows about sender and recipient ([539437b0](https://github.com/Elgg/Elgg/commit/539437b0379bdb9db5fd8741ac1712489c6a73c7))
* **page_owner:** moved page owner logic to a service ([bc35cf5a](https://github.com/Elgg/Elgg/commit/bc35cf5a71301f45a6454c0b8bc660bf8cd6160d))
* **pages:**
  * page navigation now uses default page menu behaviour ([89976121](https://github.com/Elgg/Elgg/commit/899761219347ac411525f329e971ae250c47275f))
  * replaced treeview js and css with default menu behaviour ([18be2699](https://github.com/Elgg/Elgg/commit/18be2699139feccdfc5cb1fd5d89185872283e75))
* **phinx:** updated phinx version to 0.10.x ([52ebe588](https://github.com/Elgg/Elgg/commit/52ebe5885ece97df257c1cd927c8f4d46e3a364b))
* **plugins:** hooks and events can be declared in elgg-plugin.php ([c1cc12c4](https://github.com/Elgg/Elgg/commit/c1cc12c49049b553d61d54f1b8d78b8f38bb6b11))
* **profile:** new input types for custom profile fields ([59c1a4ba](https://github.com/Elgg/Elgg/commit/59c1a4baaa14602d12a66fab1ff71a20f6464128))
* **router:** add SignedRequestGatekeeper middleware ([54e050a3](https://github.com/Elgg/Elgg/commit/54e050a32094b222adaf54a4d90662814b47c686))
* **routes:**
  * added required plugins param to route config ([8f4c1957](https://github.com/Elgg/Elgg/commit/8f4c195729b7725cb06b5ad0af4052728c8a4960))
  * added route config to mark route as deprecated ([53d8f433](https://github.com/Elgg/Elgg/commit/53d8f433a26a5515ce7f2b5ca3f7ec78b636413a))
* **security:**
  * request confirmation on email change ([53017104](https://github.com/Elgg/Elgg/commit/53017104cc5283179a474b8fb63e7ccb652f8ec8))
  * notify the user about a password change ([8692ac32](https://github.com/Elgg/Elgg/commit/8692ac32c93dc80ee4313ec3a1723bd850c894e8))
* **site_notifications:** topbar menu item now has a unread count badge ([a1d1fddc](https://github.com/Elgg/Elgg/commit/a1d1fddc15c7f4059e48aff8d5ca04f84a05ff38))
* **upgrades:** completed upgrades are sorted by completion time ([beebaecd](https://github.com/Elgg/Elgg/commit/beebaecdacb45e096e57b8a45db83ffc9f8f4c29))
* **users:**
  * unify set/get/delete profile data functions ([906c25b7](https://github.com/Elgg/Elgg/commit/906c25b743575d522b48731b067f4ac66613fc7e))
  * added a site setting to allow users to change the username ([3e2a476e](https://github.com/Elgg/Elgg/commit/3e2a476e5d10feedfc60ea611c019ec8ed15310b))
* **views:**
  * add additional page menu and owner block controls ([5cf80c8c](https://github.com/Elgg/Elgg/commit/5cf80c8c0b55ee87073af61deae50f7e1d98dd43))
  * password inputs now set correct autocomplete behaviour ([929f7bc5](https://github.com/Elgg/Elgg/commit/929f7bc5ef76a848fa57a1fa1743633a453ac31e))
  * show_add_form view var is now supported in responses ([7bd0f0da](https://github.com/Elgg/Elgg/commit/7bd0f0da6e040471a588de11ac4bda8f9340492c))


#### Performance

* **db:** added some extra indexes to the entities table ([0395d99b](https://github.com/Elgg/Elgg/commit/0395d99bb8bed7f16b2f29d4af5b11caf96899ff))


#### Documentation

* **core:**
  * added a spam guide ([2ac20105](https://github.com/Elgg/Elgg/commit/2ac20105e3c5a14e7b88e8c2c2b310981d45eacc))
  * added documentation about the usage of elgg_call ([8beef28f](https://github.com/Elgg/Elgg/commit/8beef28f77afcc136b961741e5df9eb87b6691f0))
  * added upgrade notices page for 3.0 to 3.x ([456e4fba](https://github.com/Elgg/Elgg/commit/456e4fbaf002a59a6a6c75fd435e9c93061c0a69))


#### Bug Fixes

* **admin:** different user counters in admin stats ([73c86726](https://github.com/Elgg/Elgg/commit/73c867262da86b0e7bb23c2e2a80ec9ff3bb94eb))
* **core:**
  * updated PHP version checks to check correct version ([dbb02710](https://github.com/Elgg/Elgg/commit/dbb02710d5e2671cf51c40e3ca94cdeab80f98c0))
  * elgg_call will now also restore when an error is thrown ([54964f59](https://github.com/Elgg/Elgg/commit/54964f59f0f65068ff533c9ee4680ce42405ec6e))
* **css:**
  * spacing between profile-field and widgets ([a281ac45](https://github.com/Elgg/Elgg/commit/a281ac4584ac56f1a313c37774ac323f218c1032))
  * prevent jquery-ui bug related to sortables ([fa840b53](https://github.com/Elgg/Elgg/commit/fa840b532c1c0c65e440ab25852fdf674c2430ff))
* **discussions:** no longer call unavailable sidebar views ([afe83c96](https://github.com/Elgg/Elgg/commit/afe83c96241e38c644bad177b00bdb7444440789))
* **forms:** added missing entity info in widget access input ([8f1770d1](https://github.com/Elgg/Elgg/commit/8f1770d169c995794beec120a45cb8d29eec7623))
* **pages:**
  * no longer register page_nav menu if there is just one item ([a7f7359d](https://github.com/Elgg/Elgg/commit/a7f7359d03db0aabf8d017ef28e47c8206d6105a))
  * removed the pages navigation sidebar from some resources ([08f3df26](https://github.com/Elgg/Elgg/commit/08f3df267ec0c7aac0a32f59d2e728fd943fa958))
  * no longer show history sidebar on revision page ([3c91022d](https://github.com/Elgg/Elgg/commit/3c91022d4c57c13bed61908df766f55890a8d01b))
* **system_log:** correctly fetch non default object classes ([3f0a10d4](https://github.com/Elgg/Elgg/commit/3f0a10d44952c00b048a54b161b9a0032f44ba7a))


#### Deprecations

* **access:**
  * elgg_set_ignore_access is deprecated ([6d0d99ec](https://github.com/Elgg/Elgg/commit/6d0d99ecf79a838ae4827e70c14c3f32af6de4ef))
  * access_show_hidden_entities is deprecated ([33b3e5ac](https://github.com/Elgg/Elgg/commit/33b3e5ac7c80396583e60cab46997f1d3b40665a))
* **actions:** replaced several delete actions with entity/delete ([192d01ac](https://github.com/Elgg/Elgg/commit/192d01ac12e22e50835eeadae2e8cca1b5745ae1))
* **core:**
  * legacy hook/event callback arguments are deprecated ([563f4492](https://github.com/Elgg/Elgg/commit/563f4492e10107f0fbebbb7f7585bd9f905a14e5))
  * various unused lib functions have been deprecated ([792bd362](https://github.com/Elgg/Elgg/commit/792bd362838a100b4a8366707749d7725ea06a03))
  * elgg_instanceof is now deprecated ([2602c801](https://github.com/Elgg/Elgg/commit/2602c801f3241329130e6e90a55662752f29b64d))
  * replaced delete_directory with elgg_delete_directory ([f61471dc](https://github.com/Elgg/Elgg/commit/f61471dc9931cb39c352e0757711e05784c9a160))
* **css:** use elgg_require_css instead of elgg_register_css ([b0c014f3](https://github.com/Elgg/Elgg/commit/b0c014f321cf602b8d395adc14ec6366b8a24ff2))
* **js:** use elgg_require_js instead of elgg_register_js ([e3d4a13c](https://github.com/Elgg/Elgg/commit/e3d4a13c3d24025fb6336887d3f5cf8449743ba7))
* **page_owner:** don't set page_owner via elgg_get_page_owner_guid ([b1089824](https://github.com/Elgg/Elgg/commit/b1089824a63ab4217cf2535ad880c371bfb570c5))
* **plugins:**
  * usage of the views.php file in plugins is deprecated ([95592b04](https://github.com/Elgg/Elgg/commit/95592b048948c96d06bba82f76f1159f843f8163))
  * no longer use the (de)activate.php plugin files ([d89c2474](https://github.com/Elgg/Elgg/commit/d89c24745a52a3f51f04f7d280578a3004d42b3d))
  * plugin screenshots are no longer supported ([0f7fe379](https://github.com/Elgg/Elgg/commit/0f7fe3796ca9a6563599fac734fdc5e1165f8007))
* **tests:** the simpletest cli command is deprecated ([f17a8cd9](https://github.com/Elgg/Elgg/commit/f17a8cd9c6d02455716d9a27dd2606302606676a))
* **thewire:** the route previous:object:thewire is now deprecated ([677d9129](https://github.com/Elgg/Elgg/commit/677d9129cf075405bd0da9394b129b837dcae051))


<a name="3.0.7"></a>
### 3.0.7  (2019-08-02)

#### Contributors

* Jerôme Bakker (2)
* Jeroen Dalsem (1)

#### Bug Fixes

* **js:** improved elgg.normalize_url to handle more site cases ([57af9e2b](https://github.com/Elgg/Elgg/commit/57af9e2bc83091790172eb736477b5483dd0c4b2))
* **routes:** use absolute url as base for route url generation ([244854af](https://github.com/Elgg/Elgg/commit/244854afea723dfbecea94af919e11e448942722))


<a name="3.0.6"></a>
### 3.0.6  (2019-07-24)

#### Contributors

* Jeroen Dalsem (11)
* Jerôme Bakker (9)
* Ismayil Khayredinov (1)

#### Documentation

* **code:** added note about low-level functions that should throw ([03417897](https://github.com/Elgg/Elgg/commit/034178975619dd60697019bd3e5cf0216512c5be))
* **composer:** document composer autoloader optimization ([fee62f05](https://github.com/Elgg/Elgg/commit/fee62f05c8e3d9b568db00e9cbedd159cd6d2bf3))
* **css:** added some best practices about css files and classnaming ([daa55646](https://github.com/Elgg/Elgg/commit/daa55646067a772e76036a4831f85f84ea329c55))


#### Bug Fixes

* **cache:**
  * improved handling of values ([db7c8864](https://github.com/Elgg/Elgg/commit/db7c8864c37ce7d231ef2b2c80fa76e7cbcc7843))
  * prevent timeout during cache flush ([ab8c759b](https://github.com/Elgg/Elgg/commit/ab8c759b865014a477656d0801fd01b6dcbdc32c))
* **ckeditor:** no need to remove plugins as they are not loaded ([55b95e7a](https://github.com/Elgg/Elgg/commit/55b95e7a9dd454e95cb132ffa8fa28ab4d852605))
* **comments:** popup menu will close itself when inline editing comments ([9a7ecc73](https://github.com/Elgg/Elgg/commit/9a7ecc73c8efd3dd3332db68c9e865fec2887874))
* **core:**
  * literal order by clauses are no longer deprecated ([e77e4898](https://github.com/Elgg/Elgg/commit/e77e4898fd0206c7358673c30f44502a3d675fd5))
  * unset on ElggData will always use magic setter ([a0b442ad](https://github.com/Elgg/Elgg/commit/a0b442ad6d4b0538b0302f28dba7c167e52ec16e))
  * always show success message when upgrade has finished ([0afb29d8](https://github.com/Elgg/Elgg/commit/0afb29d8fbf6b89e2082285b19f645ea128a84c4))
* **http:** request validation now correctly reads payload ([c5e18f45](https://github.com/Elgg/Elgg/commit/c5e18f45781ba4feea4f40a2c6f6cfd9518df147))
* **js:** validate arguments in elgg.get_simplecache_url ([91f7c143](https://github.com/Elgg/Elgg/commit/91f7c143c79cfc29c2078c2dd5c38f929378b162))
* **pages:** correctly check who can edit (write) access ([a87ec78f](https://github.com/Elgg/Elgg/commit/a87ec78f4c71d02cdb8975afc8eaea90195ba25c))
* **routes:** route url generation will always return a normalized url ([d0b2503a](https://github.com/Elgg/Elgg/commit/d0b2503a36bfa0a0ed7addddffa6a901bdc7bb7b))


<a name="3.0.5"></a>
### 3.0.5  (2019-07-08)

#### Contributors

* Jerôme Bakker (8)
* Jeroen Dalsem (2)
* Ismayil Khayredinov (1)

#### Documentation

* **compatibility:** explain @internal implications ([5c7b52e5](https://github.com/Elgg/Elgg/commit/5c7b52e5abc503eab2a3f17830005d7363d4a4d1))
* **plugins:** document plugin bootstrap usage ([02ea7a0d](https://github.com/Elgg/Elgg/commit/02ea7a0d4cedac0c1027362bf69cef671eace297))


#### Bug Fixes

* **core:** correctly remove annotations on non saved entities ([20af166e](https://github.com/Elgg/Elgg/commit/20af166e6815b38e1771542cac69b7c3883e004a))
* **http:** non-multipart requests should not fail validation ([c59ae7aa](https://github.com/Elgg/Elgg/commit/c59ae7aa095e663a57ec52e479264296a523297d), closes [#12654](https://github.com/Elgg/Elgg/issues/12654))
* **notifications:** correctly sort the notifiable users ([583fb67f](https://github.com/Elgg/Elgg/commit/583fb67f8038741607f426efd5227880610b0ca1))
* **search:** highlighter no longer messes up output when searching ints ([e3499498](https://github.com/Elgg/Elgg/commit/e34994983dba7de51eb5815e9746cd9e9d40c124))
* **thewire:** full view uses correct entity layout ([64143d58](https://github.com/Elgg/Elgg/commit/64143d58e80bbbaec204727d88575691707f4599))


#### Deprecations

* **groups:** group_acl metadata has been deprecated ([380cfa24](https://github.com/Elgg/Elgg/commit/380cfa249a6d8d57838f2de911dcb0f4c6677588))


<a name="3.0.4"></a>
### 3.0.4  (2019-06-12)

#### Contributors

* Jerôme Bakker (6)
* Jeroen Dalsem (2)

#### Bug Fixes

* **blog:** save draft in correct container ([b32c6139](https://github.com/Elgg/Elgg/commit/b32c6139bdb841a736bbd9b46f7f82a540793ff4))
* **email:** set default email attachment id ([ae8fc0a4](https://github.com/Elgg/Elgg/commit/ae8fc0a436106d511aabfad6e990a58f86855358))
* **install:** minification is enabled for fresh installations ([ae869441](https://github.com/Elgg/Elgg/commit/ae8694419f955f131cea2b6dc953bda8ff49155f))
* **livesearch:** by default no longer include banned users ([c059ff11](https://github.com/Elgg/Elgg/commit/c059ff115d7c2227c30fb5205e1cd1f1a57e8ffa))
* **response:** only set error content if provided ([518231ab](https://github.com/Elgg/Elgg/commit/518231ab7be60696c3476e190dbef8723188e2ab))
* **system_log:** prevent fatal exception when constructing objects ([5105ca6f](https://github.com/Elgg/Elgg/commit/5105ca6f0a0198445aba03599430f9b14e691ae0))


<a name="3.0.3"></a>
### 3.0.3  (2019-05-21)

#### Contributors

* Jerôme Bakker (55)
* Jeroen Dalsem (21)
* Rohit Gupta (1)
* therecluse26 (1)

#### Performance

* **db:** improved preloader queries for performance ([6ec44b7a](https://github.com/Elgg/Elgg/commit/6ec44b7a38f16a0fe61652806cf18897eeff4bf4))
* **entity:** only update private settings if value changes ([ee955db4](https://github.com/Elgg/Elgg/commit/ee955db4417d0c1b770061a59a8e2104e8857b54))


#### Bug Fixes

* **ajax:** reponseFactory prepares reponse ([ff965eab](https://github.com/Elgg/Elgg/commit/ff965eab5b68d7d5836afc248683581ec7a6afdb))
* **cache:**
  * let cache (un)serialize contents (#12615) ([29eeabc5](https://github.com/Elgg/Elgg/commit/29eeabc57fc8eac8102936943bf71c984ebca1b6))
  * updated Stash version to 0.15.* ([3aa057a8](https://github.com/Elgg/Elgg/commit/3aa057a87504badf1ceb4b13640a502719e9e6fb))
  * improved error handling in Stash ([79107e3f](https://github.com/Elgg/Elgg/commit/79107e3f7fad98e0c76d31541468c78c1f2e4692))
* **core:**
  * use correct typehint namespace ([aaeacf36](https://github.com/Elgg/Elgg/commit/aaeacf36c11b5826a003e9a2f50f4facef34d3d3))
  * remove unused action hook listener in BootService ([01ff862c](https://github.com/Elgg/Elgg/commit/01ff862cc103de03e456ff39e98fab96dccaffbf))
  * report correct duration for non sequential timers ([1831589f](https://github.com/Elgg/Elgg/commit/1831589fd45e812e8f0e4a13fd128f6b039a6957))
* **db:** make sure all queries are tracked and logged ([8e6da0c6](https://github.com/Elgg/Elgg/commit/8e6da0c673d812e1b8d7d99b8cf1df520e2dd7d5))
* **email:** don't set duplicate content-type header (#12625) ([5625412c](https://github.com/Elgg/Elgg/commit/5625412ccd035e144c667d9d432a729526596015))
* **gatekeeper:** allow access to content of banned users ([c7c36082](https://github.com/Elgg/Elgg/commit/c7c360823e27b449eff09e388b52024dc4e407af))
* **messages:** added missing translation string ([5c612c1a](https://github.com/Elgg/Elgg/commit/5c612c1aebac0859094c977bbd178c9a65838e4a))
* **metadata:**
  * removed usage of canEditMetadata is MetadataTable::delete ([35c39119](https://github.com/Elgg/Elgg/commit/35c39119bca850674893ae31c1c9231e299ab4de))
  * removed usage of canEditMetadata ([42495a6b](https://github.com/Elgg/Elgg/commit/42495a6b63a9d2b4eddda4791b58b1b4a979c15c))
* **notifications:** prevent php warning when no collections selected ([6efd8f7b](https://github.com/Elgg/Elgg/commit/6efd8f7bb9a719e3d2fd9e01cd7e6e9cd97f9c65))
* **output:** always return string in formatter ([b92a6dbd](https://github.com/Elgg/Elgg/commit/b92a6dbde602abacede71d73e30c04d12fca84c8))
* **pages:** don't show access fields if no edit rights ([33eff4b2](https://github.com/Elgg/Elgg/commit/33eff4b23b99e696b3be79c25cd0d714c2a92d20))
* **plugins:**
  * only reindex plugin priorities with new disabled plugins ([9652c77e](https://github.com/Elgg/Elgg/commit/9652c77e85a988b03c21efe00d10c10ca21080d8))
  * plugin details tabs work again ([f3c9bb3f](https://github.com/Elgg/Elgg/commit/f3c9bb3f296e3108245e2c20ef31ef87a16017c6))
* **request:** upload post max size is now correct validated (#12610) ([5b118806](https://github.com/Elgg/Elgg/commit/5b118806db760aade196efcaafd7128e3e0aee4f))
* **river:** restored ignoring access when bulk deleting river items ([761dc191](https://github.com/Elgg/Elgg/commit/761dc191226495731a47df296b93b7cc1ef6175f))
* **search:** no longer set deprecated search_type tags on tag links (#12611) ([a639fbba](https://github.com/Elgg/Elgg/commit/a639fbbae9b584716fb7e66d105d56e9d62849ab))
* **session:**
  * cookie configuration not read from settings file ([d43d282c](https://github.com/Elgg/Elgg/commit/d43d282c371bfa9c2ac8a27962a6d0985dcef332))
  * session close moved to the latest possible moment ([16c06fc2](https://github.com/Elgg/Elgg/commit/16c06fc285def47efceca7317ecc45751b94fe79))
* **system_log:**
  * filtering in logbrowser could result in no results ([bdf6ec54](https://github.com/Elgg/Elgg/commit/bdf6ec549fedd1656bc97abd111f66486336de73))
  * system_log_get_log accepts single array argument (#12607) ([9641b008](https://github.com/Elgg/Elgg/commit/9641b008de477caaed9d934c577d42b5316afbf4))
* **web_services:** fetch correct api user ([f857b1ef](https://github.com/Elgg/Elgg/commit/f857b1efab0fa2c6abe36e41ace5fd1ce68c3c48))
* **widgets:** return all widgets in case of duplicate order ([e2899cb4](https://github.com/Elgg/Elgg/commit/e2899cb4c7546c43a0db4f9865a005001a468f55))


<a name="3.0.2"></a>
### 3.0.2  (2019-04-17)

#### Contributors

* Jeroen Dalsem (9)
* Jerôme Bakker (6)

#### Performance

* **upgrades:** improved speed of friends acl async upgrade ([004dcdd4](https://github.com/Elgg/Elgg/commit/004dcdd4095720aa5ade00c641cad132cc4e6bf3))


#### Bug Fixes

* **core:**
  * prevent namespace conflict ([526ecf72](https://github.com/Elgg/Elgg/commit/526ecf72ed72e6147226a4c085073f66047d1ceb))
  * use webserver timezone for date ([f0f16685](https://github.com/Elgg/Elgg/commit/f0f166858d8ebafe5091f3c0e00c031f9572b68e))
* **css:**
  * user hover card is now single column layout ([fcff8f90](https://github.com/Elgg/Elgg/commit/fcff8f90d8824a38d9cfbc6bbba24ca33590d290))
  * prevent quick wrapping of title menu items ([d0c07dc6](https://github.com/Elgg/Elgg/commit/d0c07dc6907f21288415c2829d458f3ecf39c084))
* **forms:** added missing entity info in widget access input ([1f92b130](https://github.com/Elgg/Elgg/commit/1f92b130b1ae8858d2786f7ce56c9e09f2788c55))
* **i18n:** make sure system translations are loaded before adding custom ([48ce7e0c](https://github.com/Elgg/Elgg/commit/48ce7e0c41d6c132ee618fe8d65cf0483cd234b9))
* **icons:**
  * do not remove uploaded file when saving as icon ([e669071c](https://github.com/Elgg/Elgg/commit/e669071c9d4a69cd3179e2c813a2659ac70bd946))
  * only fix image orientation when handling icons ([4e690386](https://github.com/Elgg/Elgg/commit/4e690386e92518e368fc60932c0eed0b3be95b7c))
* **upgrades:** friends acl upgrade will now update all entities ([68f12d13](https://github.com/Elgg/Elgg/commit/68f12d13041ba4647e59454772f4641f3c90a57d))


<a name="3.0.1"></a>
### 3.0.1  (2019-04-05)

#### Contributors

* Jerôme Bakker (1)

#### Bug Fixes

* **response:** secure correct url ([72192b60](https://github.com/Elgg/Elgg/commit/72192b60dd04eb8b97e6dfb51ed89310733c87f7))


<a name="3.0.0"></a>
## 3.0.0  (2019-04-05)

#### Contributors

* Jerôme Bakker (60)
* Jeroen Dalsem (54)
* Rohit Gupta (3)
* iionly (1)

#### Features

* **cache:** reset opcache when flushing the system cache ([b3c84901](https://github.com/Elgg/Elgg/commit/b3c849016573f222bf6b0a28e37accedf5bf060f))
* **core:** added server statistics about OPcache ([f48d7b1a](https://github.com/Elgg/Elgg/commit/f48d7b1a9fbeec55ed391b838012c9419765d588))
* **gatekeeper:**
  * improved gatekeeper exceptions ([d8765071](https://github.com/Elgg/Elgg/commit/d876507178c5c5816c9079aedbee4637caf6cd6d))
  * added a logged out gatekeeper middleware ([b9264a93](https://github.com/Elgg/Elgg/commit/b9264a931847ae559fd25d0f8cf3b47490e17dc1))
* **i18n:** output date in locale string ([c2ca5da2](https://github.com/Elgg/Elgg/commit/c2ca5da28e6d9e2af70bb82f812d96db128c55ab))
* **livesearch:** allow to filter out banned users ([c3d631a3](https://github.com/Elgg/Elgg/commit/c3d631a3efa489d2f4fcd8527522f2dfdb33ed4e))
* **security:** added admin setting to set if icons are session bound ([07f070de](https://github.com/Elgg/Elgg/commit/07f070dead59dee8782687b031840303514ba6c6))
* **upgrades:** added an information page about the phinx db upgrades ([5ce9bced](https://github.com/Elgg/Elgg/commit/5ce9bced0444be55ec369fbe9f73f8609d9917ae))


#### Performance

* **db:** added combined index on entities type/subtype ([33b8463c](https://github.com/Elgg/Elgg/commit/33b8463cf1313ad48334e97ffb8907bdb3a059b1))
* **i18n:**
  * improved logic of loading translations ([d615165b](https://github.com/Elgg/Elgg/commit/d615165b78ebf8554e8143e70f4b2b203a13fef1))
  * cache translations in systemcache only when loaded ([ea22727f](https://github.com/Elgg/Elgg/commit/ea22727fa07daffff6e86fc13e2e7f22588d3212))
* **plugins:**
  * preload private settings when fetching plugins from db ([daaab2a2](https://github.com/Elgg/Elgg/commit/daaab2a21de0ecd734254b24a0f47563612b0f82))
  * always set boot plugins ([a70787c8](https://github.com/Elgg/Elgg/commit/a70787c89ceca536ccc54ba472047c3300220f73))
  * only reset plugin priority if dirty ([2d5d8571](https://github.com/Elgg/Elgg/commit/2d5d8571ada91bb6feb717cc18a3a19f067f4100))
* **upgrades:**
  * use direct queries during friends acl upgrade ([6a401bc9](https://github.com/Elgg/Elgg/commit/6a401bc9cfca4279739c158f5434e337600587e7))
  * disable systemlog during execution of an ElggUpgrade ([d94ec941](https://github.com/Elgg/Elgg/commit/d94ec9412c69dca18824e3322617f19eea58979d))


#### Documentation

* **icons:** document recommended additional options for entity icons ([a39bb1c7](https://github.com/Elgg/Elgg/commit/a39bb1c7ff14001be2e98284d4c555825a05f1ea))


#### Bug Fixes

* **account:** don't allow , and : in username ([7049923e](https://github.com/Elgg/Elgg/commit/7049923e0240b2cf478aa5aee7a7e72a4761411a))
* **ajax:** on error response clear system messages ([e3ca2b10](https://github.com/Elgg/Elgg/commit/e3ca2b1026d4631ceb6ce0a73e44ae16cfbbe69d))
* **blog:**
  * use correct route after deleting a blog ([6481b93f](https://github.com/Elgg/Elgg/commit/6481b93f3a363b0fab31c8447a2b5f86dbe9efe9))
  * excerpt no longer limited during save ([f2f1eb7c](https://github.com/Elgg/Elgg/commit/f2f1eb7cc8550dcb95770dbe9af72375d7bc36c7))
  * ordering of archive menu items not consistent ([3ff75438](https://github.com/Elgg/Elgg/commit/3ff7543810a2e3cc777bb6ac0ba4798cbf6b963e))
* **cache:** clear running autoloadermap when flushing the caches ([2ea53a3b](https://github.com/Elgg/Elgg/commit/2ea53a3b9cf3e4e3010279be24d9a194339fb177))
* **comments:** show read more in activity for long comments ([5cca32bf](https://github.com/Elgg/Elgg/commit/5cca32bfa0fd2e80291ce9e3d4613f73f441b96d))
* **core:**
  * fallback to generic error code in ErrorResponse ([9c81a8bb](https://github.com/Elgg/Elgg/commit/9c81a8bb21a5d087a228d0168c411dd21ac4e508))
  * make sure constants are available during db migrations ([d5c8ff47](https://github.com/Elgg/Elgg/commit/d5c8ff472f8ce322e64c2437129971b01776ff07))
  * directory permissions more usable ([5fdf3a86](https://github.com/Elgg/Elgg/commit/5fdf3a8674a7dc8e88431d760f68ff2dc7647796))
  * try to forward to entity collection after deletion ([df08d138](https://github.com/Elgg/Elgg/commit/df08d13878401926a1b26b2fe66171f672339ecf))
* **css:**
  * keep tabs together on smaller screens ([ef0b42f7](https://github.com/Elgg/Elgg/commit/ef0b42f710f0a404a547be8b34177635965f466f))
  * entity navigation not always correctly aligned in all browsers ([64c6a0c0](https://github.com/Elgg/Elgg/commit/64c6a0c013787945980bce63b821dea22054ef03))
  * popped out dropdown always showing ([9597d6c4](https://github.com/Elgg/Elgg/commit/9597d6c4c9b44bf8cdb517cda0b01fb137037603))
  * allow wrapping of elgg-menu-hz menu items if there is no room ([2e4292ca](https://github.com/Elgg/Elgg/commit/2e4292ca39cbea675343b5ce6172eb52eb2baf77))
  * wordbreaking is now allowed everywhere ([994663fd](https://github.com/Elgg/Elgg/commit/994663fd8ed981b6eaff5f5feca1cb4c7962bfd1))
* **database:** support closure group_by clauses ([7da86a40](https://github.com/Elgg/Elgg/commit/7da86a407e5485a63e7695a958ae48431c911478))
* **email:** set content encoding on magic email attachments ([b0ef558a](https://github.com/Elgg/Elgg/commit/b0ef558a2eca10008a52a38d83b07d20d4b83f07))
* **embed:** tabs now working correctly ([8a4b80e5](https://github.com/Elgg/Elgg/commit/8a4b80e58a0d440b4250cc1d655780b2174cfbdf))
* **gatekeeper:** return http 401 status code when not authorized ([4bb770d7](https://github.com/Elgg/Elgg/commit/4bb770d7bc7ba9f14e653d5f00f566a9e62fef11))
* **groups:** add menu item in correct menu section ([436c93a6](https://github.com/Elgg/Elgg/commit/436c93a6bd0fb74b517e30e8f4f66c3df6342637))
* **icons:** increased the default resolution of master icon to 10240px ([e39e5d29](https://github.com/Elgg/Elgg/commit/e39e5d29d5bdb1fc73fdf6775a2fa7879cc85c9a))
* **input:** do not autocomplete input date fields ([d55cf07b](https://github.com/Elgg/Elgg/commit/d55cf07bb6a9191063055718f95d731051356e2b))
* **invitefriends:** route path conflict with friends plugin ([9c645ed0](https://github.com/Elgg/Elgg/commit/9c645ed0fbcb96e1ba4089b1bf2c85ae760c000a))
* **js:**
  * clear system messages when submitting ajax submitted form ([dbc6a913](https://github.com/Elgg/Elgg/commit/dbc6a913d154b06c660e945c5e8d677c46db6734))
  * check if trigger is set before validation if part of comments ([9fdd66d7](https://github.com/Elgg/Elgg/commit/9fdd66d7f427608d0194aa22e60e42f21c8c63c0))
  * provide user feedback when opening user hover menu ([bb280605](https://github.com/Elgg/Elgg/commit/bb28060562dc420493c731b4fc89d97d9b9fd83f))
  * close popups on window scroll ([6fbaf8d4](https://github.com/Elgg/Elgg/commit/6fbaf8d42115495f0433708dcb57486615e9e168))
* **menus:**
  * menu items will recursively sort its children ([e979cd69](https://github.com/Elgg/Elgg/commit/e979cd69591650a4280eeb2df587c0606444609d))
  * prevent section output if no items ([f7868abb](https://github.com/Elgg/Elgg/commit/f7868abb58ae4537fa00c9cf8cb97c45dc10780b))
  * you can now have a link with toggleable features combined ([097b01f7](https://github.com/Elgg/Elgg/commit/097b01f7f4c266aa8eb7e84b097dfeddf861936e))
* **navigation:**
  * always append admin toggle menu item ([41021eda](https://github.com/Elgg/Elgg/commit/41021eda88380c02f758cf674591c83c79571be4))
  * do not require logged in user for filter tab all ([570d7721](https://github.com/Elgg/Elgg/commit/570d772168ff91e442ed7dff8b9c2de583028f11))
  * correctly remove selected state if link item not a tab ([8cd7209a](https://github.com/Elgg/Elgg/commit/8cd7209a1c76998e1d4f93246c915b988b92fb66))
  * improved breadcrumbs for site containers ([578a25c5](https://github.com/Elgg/Elgg/commit/578a25c5bb8d705d362ea161db279b40b9d036b3))
  * entity nav fixed for entities with same time created ([4d66fcc1](https://github.com/Elgg/Elgg/commit/4d66fcc1dd41b7e6f0aeba7dedf650e4d563a6ce))
  * add default user_hover section items to actions section ([387d618b](https://github.com/Elgg/Elgg/commit/387d618b13795d3eef38d546a0a1432a6aab2770))
* **notifications:** validate the notification event ([b8e34723](https://github.com/Elgg/Elgg/commit/b8e3472322c28467696b27118dab79952773a696))
* **plugins:**
  * generateEntities correctly rediscovers disabled plugins ([b62238dd](https://github.com/Elgg/Elgg/commit/b62238ddf62fcf0df64edd65b3f647ea4146716f))
  * rely on magic translations for widgets ([988ec419](https://github.com/Elgg/Elgg/commit/988ec419cd41cd371e64c8f3c0c46ea587591b97))
* **request:**
  * return expected return type ([af805ca1](https://github.com/Elgg/Elgg/commit/af805ca182ad309b132f01fdab84191cb96f96e3))
  * set_input values override request values ([ba1e977d](https://github.com/Elgg/Elgg/commit/ba1e977d89f6e89ddbb52c2cc1721f229f5aa550))
  * use same order as in getParam() ([9ac24c7a](https://github.com/Elgg/Elgg/commit/9ac24c7a029b6c9321fca840eeab4d8922ad9851))
* **rss:**
  * listings have rss content ([07e6338c](https://github.com/Elgg/Elgg/commit/07e6338c0f73e2728632c0809de1a8040b257e31))
  * register rss link in a more logical way and provide control ([9e785825](https://github.com/Elgg/Elgg/commit/9e785825a93ac8004787d6d5a0e27268b0899c31))
  * prevent RSS output if disabled ([cf6af267](https://github.com/Elgg/Elgg/commit/cf6af2671fbac39617cbf4e3aaf9dc8c6a8953b6))
* **scripts:** transifex script adjustments ([0633121c](https://github.com/Elgg/Elgg/commit/0633121cd11a0cedffffa58eddaba57b346bb244))
* **search:**
  * namespace profile fields ([3fc2afcb](https://github.com/Elgg/Elgg/commit/3fc2afcbe1c4b84c9eff03fcae957ee40296d03b))
  * improved search fields normalization ([ec58c6f1](https://github.com/Elgg/Elgg/commit/ec58c6f1f1498ec8408cdb6e1c3b01199dcb34fe))
  * split search field registrations ([cae5e906](https://github.com/Elgg/Elgg/commit/cae5e90649f34d0242dec6f709e19b15993b0eba))
* **session:** close session early when redirecting repsonses ([4149f8d3](https://github.com/Elgg/Elgg/commit/4149f8d3002c269078454beeca341195ce209252))
* **site_notifications:** site notification link js handling works again ([2a62cd6e](https://github.com/Elgg/Elgg/commit/2a62cd6e4fca45fbf3102170178717697b981efa))
* **system_log:** use correct plugin setting for cron jobs ([f6c5d109](https://github.com/Elgg/Elgg/commit/f6c5d1097d02dbb245eac8a01bea3aed77118a94))
* **tags:** support documented elgg_get_metadata features ([4460f948](https://github.com/Elgg/Elgg/commit/4460f9488741756262db45337b8297d3222166a4))
* **tests:** pass test independed of loglevel settings ([2e22b1df](https://github.com/Elgg/Elgg/commit/2e22b1df5fdf06ddcde4bc619bf49417d8c17622))
* **upgrades:**
  * drop site_guid as primary and unique key explicitely before removing site_guid column ([ff6f2069](https://github.com/Elgg/Elgg/commit/ff6f206947c4f63a4448321aaa94f397f43ad7ee))
  * validate database setting before changing ([89989f56](https://github.com/Elgg/Elgg/commit/89989f563c1cb780cb5e745633eef9ca45eb37a0))
  * don't report Batch errors for completed upgrades ([b8e1af6e](https://github.com/Elgg/Elgg/commit/b8e1af6ed64a8bd14bf48112836cbb25a338ac74))
  * don't offer delete link for ElggUpgrades ([5b9d1b08](https://github.com/Elgg/Elgg/commit/5b9d1b08fa9fd2a416da70df5f448fd530dd7d64))
* **users:** set default values ([7757fcd5](https://github.com/Elgg/Elgg/commit/7757fcd5b6b55ec970ae5bad223aa69940332a58))
* **widgets:** check page owner canEdit in can_edit_widget_layout ([e40ffbcc](https://github.com/Elgg/Elgg/commit/e40ffbcc453382a4dd60f87d58996204395a3359))


<a name="3.0.0-rc.2"></a>
### 3.0.0-rc.2  (2018-12-21)

#### Contributors

* Jerôme Bakker (90)
* Jeroen Dalsem (43)
* Ismayil Khayredinov (4)
* iionly (2)

#### Features

* **cli:** seeder can use local image directory ([d9be6784](https://github.com/Elgg/Elgg/commit/d9be6784fa272c702e1e46621d0a2bdb184d4bd2))
* **core:** added helper function to find empty values ([c0eea6e3](https://github.com/Elgg/Elgg/commit/c0eea6e3532ecd2f415504692fd5d955ef5cbe5f))
* **icons:** automaticly detect cropping coordinates during icon upload ([ddcf18e1](https://github.com/Elgg/Elgg/commit/ddcf18e1fda9c2af3c078d8ee985902921e1dd3b))
* **river:** add extra class to river items based on object and action ([1941ad2d](https://github.com/Elgg/Elgg/commit/1941ad2dc9ea97528e414841873ef25dfbe3c9d8))
* **upgrades:**
  * addes ability to run a single upgrade ([6868abf5](https://github.com/Elgg/Elgg/commit/6868abf59e1340062520b0661fc7c3376569a75d))
  * add ability to reset an ElggUpgrade ([b55a53ea](https://github.com/Elgg/Elgg/commit/b55a53ea7a1f69b9a1fd95c0c6bc206bb1fe1e5a))
  * add listing of completed (async) upgrades ([4c547b79](https://github.com/Elgg/Elgg/commit/4c547b7972412d848e88be03e22a2dbbca413a83))
* **views:**
  * implemented helper view to handle entity icon upload ([f13192a9](https://github.com/Elgg/Elgg/commit/f13192a9a7abcaea494c0d466e789c53bc652880))
  * control the list item view for lists ([a39892bd](https://github.com/Elgg/Elgg/commit/a39892bdbb481ef8ed643997936c765b18fcd648))


#### Performance

* **activity:** improved performance of group river filter ([dbfdbd6a](https://github.com/Elgg/Elgg/commit/dbfdbd6ac0340244eee2a3450d552bcf489bff8e))
* **composer:** plugin autoloaders are registered after core autoloaders ([925c5830](https://github.com/Elgg/Elgg/commit/925c5830ba05fb0c7a3789ba0782292b7b98fb4f))
* **plugins:** store path in local class variable ([5614d315](https://github.com/Elgg/Elgg/commit/5614d3153b0772d40b4ce98a86672da37a54c1dc))
* **upgrades:** no longer check filesystem for need to run upgrade ([94248fbc](https://github.com/Elgg/Elgg/commit/94248fbcf7ab512ac375add7da828396408fb8fd))
* **views:** elgg_list_entities will default preload owner and container ([6c84c8b7](https://github.com/Elgg/Elgg/commit/6c84c8b70f59f5ba0d97c57a526852da59850ea7))


#### Documentation

* **upgrade:** simplify the upgrade docs ([7d86f85c](https://github.com/Elgg/Elgg/commit/7d86f85cb37223c57728fce738f379541fd44884))


#### Bug Fixes

* **activity:**
  * correctly select group activity ([13ac5617](https://github.com/Elgg/Elgg/commit/13ac5617498e22419e658e48a07fa8585ac6d55f))
  * group activity uses QueryBuilder instead of sql ([6f7cbb56](https://github.com/Elgg/Elgg/commit/6f7cbb56c9d62a365481cf3fc8ba881ab0fbcc19))
* **admin:** allow removal of hidden users by admins ([4630e3a7](https://github.com/Elgg/Elgg/commit/4630e3a74d90c8f1c36430c2a4ed5abc71fc5df1))
* **annotations:** no longer update entity last_action on annotate ([a85293eb](https://github.com/Elgg/Elgg/commit/a85293eb7d824344474d72cadd955d3642fac85f))
* **blog:** group archive using correct options ([2cd9766c](https://github.com/Elgg/Elgg/commit/2cd9766cf62ea62bb3dcd161ced3bb277b416fc5))
* **blogs:** only generate archive menu for supported pages ([3f9b07ea](https://github.com/Elgg/Elgg/commit/3f9b07ea000b54525caf551fdd5017a7e69e495a))
* **bookmarks:** restored the footer menu item to bookmark a page ([e1214612](https://github.com/Elgg/Elgg/commit/e121461269e242ef9ae3970dad792bf7a447e4e6))
* **ckeditor:** require correct build of ckeditor ([59eb753a](https://github.com/Elgg/Elgg/commit/59eb753a5816ca666c958b6e5e082d34a4523b9d))
* **comments:** ajax loaded comments list updates correctly ([b57f26d2](https://github.com/Elgg/Elgg/commit/b57f26d27a340c8b78b7213174e0615e7ffb24ac))
* **core:**
  * serve file with spaces no longer fail with HMAC mismatch ([39c3b97a](https://github.com/Elgg/Elgg/commit/39c3b97aba94f02e832d8ccacb920154e9444651))
  * entity delete will correctly delete owner/container entities ([62ab9800](https://github.com/Elgg/Elgg/commit/62ab9800948f45099c18ffd6bbb26d8f16ede420))
* **discussions:**
  * river items get correctly updated to comments view ([9697d74e](https://github.com/Elgg/Elgg/commit/9697d74eda76e04e4f92fb90ef67e2d58c81cd01))
  * do not show entity navigation on full view ([f17d1fed](https://github.com/Elgg/Elgg/commit/f17d1fed0af5c7c7132f74cf49156a5d7922f132))
* **docs:** display all code examples with syntax highlighting ([30db1053](https://github.com/Elgg/Elgg/commit/30db10536ad219501c73aa746ea4d07287fd7235))
* **file:** don't use legacy group module extension ([129a5b02](https://github.com/Elgg/Elgg/commit/129a5b0298bb8fdad67498c2e2d926c5307651b2))
* **groups:**
  * allow group icons to be removed ([2b990059](https://github.com/Elgg/Elgg/commit/2b9900592cea5db275821a739c888b7c3723e0cc))
  * group members page not sorting by name ([baa9f684](https://github.com/Elgg/Elgg/commit/baa9f684014ddc300cf9a40812ee440a299f10ae))
  * provide correct subtype to title menu button ([54583645](https://github.com/Elgg/Elgg/commit/5458364596dd4ddfb4f86ad8e6b7355b2d122dcd))
* **icons:**
  * correctly report icon delete result ([75fe08e0](https://github.com/Elgg/Elgg/commit/75fe08e0e4354e5c3f2a962ab8f4195e9f7eb3d3))
  * cropping logic is now controlled by config settings ([78a1b5de](https://github.com/Elgg/Elgg/commit/78a1b5de0b1085575804b71ec86fbb8632ce6156))
  * correctly set max-width and max-height on avatar icons ([a88c2394](https://github.com/Elgg/Elgg/commit/a88c2394e00722ee3aabbaefb850da56ade85496))
  * easier targeting of different styles of fontawesome icons ([febc999f](https://github.com/Elgg/Elgg/commit/febc999f125da32d859d56f3d81b9bb934a941c4))
* **js:**
  * trigger a custom event for FormData preparation ([d2200de5](https://github.com/Elgg/Elgg/commit/d2200de5e07e5e3814623540a39a14d9125b168d))
  * no longer rotate content on ajax reload ([3e1c2911](https://github.com/Elgg/Elgg/commit/3e1c29110a121218eeaae779c14f0efb223b4a57))
  * page/components/tabs JS supports all links ([4d2ee70c](https://github.com/Elgg/Elgg/commit/4d2ee70c80ea7d7ade548695551b70eaf481173e))
* **likes:** correctly replace menu item content ([603d8f05](https://github.com/Elgg/Elgg/commit/603d8f053064bdb4d228c14c77472a26a2d823b4))
* **menus:** sort site menu by menu item text ([2776ea0e](https://github.com/Elgg/Elgg/commit/2776ea0ec84dc71255cc44bf546eae7fe40ee0b3))
* **messages:** no longer strip HTML from notification ([cbb9ec62](https://github.com/Elgg/Elgg/commit/cbb9ec62d1c6eda954f440a2d06cdd8d971763cb))
* **navigation:**
  * breadcrumbs set correct value for empty links ([cbafe2c1](https://github.com/Elgg/Elgg/commit/cbafe2c1fb8b5b5941eea0727d120f8f39a17a7b))
  * make navigations/tabs view support BC tab params ([20bee03e](https://github.com/Elgg/Elgg/commit/20bee03e5974a2d6fc83228a758f777495b089a6))
  * incorrect detection of selected menu items ([4b8e1a79](https://github.com/Elgg/Elgg/commit/4b8e1a793606112c451d04932ab2aedbc02a951c))
  * decouple tabs.js from html position ([bf263b7a](https://github.com/Elgg/Elgg/commit/bf263b7a08f7489296b8d483e2fff7582f20cc15))
  * use ElggMenuItem rendering for tabs ([88bc8f4c](https://github.com/Elgg/Elgg/commit/88bc8f4c426770f8d21b270a89cfa9c6ea1f4e5b))
  * added helper class for parents of selected menu items ([3b45c0e0](https://github.com/Elgg/Elgg/commit/3b45c0e0855b55a1d399428d96db9db856c99ebf))
* **output:** no longer output empty classes with elgg_format_element ([a4353e95](https://github.com/Elgg/Elgg/commit/a4353e9587852089b2689ef741d1d1454ed00c32))
* **plugins:** reduce callstack and optimized cached for boot plugins ([241a74df](https://github.com/Elgg/Elgg/commit/241a74df356f0b0a3e1ec1051f02b22276f0d6b0))
* **rss:** correctly check if rss is disabled in config ([cd58cc72](https://github.com/Elgg/Elgg/commit/cd58cc72481daa6a04e214845f78e23f3c3ff147))
* **schema:** restored lost subtype index on entities table ([07cd4557](https://github.com/Elgg/Elgg/commit/07cd4557ba6246aeec42df7183d90bf8f8bcf30c))
* **search:**
  * allow passing of variables to search result view ([d80684c1](https://github.com/Elgg/Elgg/commit/d80684c190578eeb8184292970691cfc23d82d24))
  * improved normalization (#12210) ([9ffefc36](https://github.com/Elgg/Elgg/commit/9ffefc36b34599e6cec0a80f8779977014e9d836))
  * pagination no longer rebuilds search params ([461c07f6](https://github.com/Elgg/Elgg/commit/461c07f61700da0c9a889af40ba70b3416f4e3ff))
  * allow custom sorting ([05093512](https://github.com/Elgg/Elgg/commit/05093512674aa51f482c6732d11e67e44bfbf6c9))
  * use correct params for search_type menu items ([59e36ad5](https://github.com/Elgg/Elgg/commit/59e36ad5680275a456a0f550b0c44c8881111afc))
  * prevent duplicate subtype registration ([abbfae14](https://github.com/Elgg/Elgg/commit/abbfae143ad0f0b3ebaf1ff5ae630b7d8c79c311))
  * allow entity views to use default search entity view ([cca3b8a9](https://github.com/Elgg/Elgg/commit/cca3b8a91b17a31f1a3f87618dbd049e0941595f))
  * correctly set subtitle in default search entity view ([99a8fb79](https://github.com/Elgg/Elgg/commit/99a8fb7966038416bff82b3bf967fa5466adcb4e))
  * determine search entity view based on entity viewed ([702a3a89](https://github.com/Elgg/Elgg/commit/702a3a89244af958ec3e9f53832c039e4311fbf7))
* **tests:** correctly test Ajax.objectify ([68ff2bf8](https://github.com/Elgg/Elgg/commit/68ff2bf8486e0de3f86f18b74a6c8b905d90c7f6))
* **thewire:** load correct wire posts for thread ([3d03ac5e](https://github.com/Elgg/Elgg/commit/3d03ac5eba57b32019fb571f06a4d2f2d4bd7609))
* **views:**
  * set no results in vars when no_results is true ([ca48d675](https://github.com/Elgg/Elgg/commit/ca48d675e92d80b88c9a5f0cfa26de8ad4ff239e))
  * improved allowed output values in a module ([709e2e72](https://github.com/Elgg/Elgg/commit/709e2e72ba76615832fd1aea7cdff93af9ab74f3))
  * correctly handle non-default list type in entity listing ([6923ebbb](https://github.com/Elgg/Elgg/commit/6923ebbbbc6ffe15ca2f165fe0dac67e0254c620))


#### Deprecations

* **messages:** messages_set_url is replaced by ElggEntity::getURL ([5f3488a2](https://github.com/Elgg/Elgg/commit/5f3488a2fbce49c47dc546cb43b6d8f630cdec16))


<a name="3.0.0-rc.1"></a>
### 3.0.0-rc.1  (2018-07-24)

#### Contributors

* Ismayil Khayredinov (102)
* Jeroen Dalsem (84)
* Jerôme Bakker (74)
* Hao.Chen (1)
* Ismayil Khayredinov (1)

#### Features

* **accounts:** adds new account registration service ([022e26fa](https://github.com/Elgg/Elgg/commit/022e26fa0dfdd8e926f50935e828b4e9be4dbaf5))
* **actions:**
  * controllers/middleware can now share parameter validation state ([deb8e3e6](https://github.com/Elgg/Elgg/commit/deb8e3e6ce89d13378a4981cb34a960fcb641fb7))
  * adds API to easily ajaxify form submission ([8ca2698c](https://github.com/Elgg/Elgg/commit/8ca2698c84007d150b301c1b668f010c827793b7))
  * register, user hook now includes all request data ([f0161ae4](https://github.com/Elgg/Elgg/commit/f0161ae43e3e95447535f91fd7abf31c0ac35719))
  * deprecate action hook in favor of action:validate hook ([428d6669](https://github.com/Elgg/Elgg/commit/428d666928bb01bf0c5698638a3a1cd08cb62460))
* **ajax:** adds elgg/Ajax#forward method ([7fd6e577](https://github.com/Elgg/Elgg/commit/7fd6e577499d6b04123e661e4e72d791832a971d))
* **app:** consistent handling of requests and responses ([af785ffc](https://github.com/Elgg/Elgg/commit/af785ffc098f88240f7cb0b70fdf330fa6011162))
* **assets:** composer asset plugin no longer required ([884379e3](https://github.com/Elgg/Elgg/commit/884379e33dd16b0a1aa62e899d38d408e4bd907e))
* **blog:** added archive sidebar to friends listing ([866e5ab4](https://github.com/Elgg/Elgg/commit/866e5ab439f1427b3c837b9e51a8372a8d3ec101))
* **caches:** add Redis statistics to the admin UI ([3e6f804a](https://github.com/Elgg/Elgg/commit/3e6f804a18fc0adcc3a53018ac2e2bc66ee952f8))
* **cli:**
  * add commands to list, activate and deactivate plugins ([09a4b89a](https://github.com/Elgg/Elgg/commit/09a4b89a1252bba78effc90038633f60b0bfa892))
  * adds database:optimize command ([9ff5ffa8](https://github.com/Elgg/Elgg/commit/9ff5ffa8ca9ef496f628148cf6914cdd8c910ef2))
  * add flush and upgrade commands ([22bd0672](https://github.com/Elgg/Elgg/commit/22bd067267833db2ed6aa6e70e4405a8f614e5b5), closes [#11849](https://github.com/Elgg/Elgg/issues/11849), [#11683](https://github.com/Elgg/Elgg/issues/11683), [#11540](https://github.com/Elgg/Elgg/issues/11540), [#11553](https://github.com/Elgg/Elgg/issues/11553))
* **comments:** the comments form is collapsed if there are comments ([c168a45d](https://github.com/Elgg/Elgg/commit/c168a45d2790f4124fa80ccded3e99e39a040f6a))
* **core:**
  * added a private settings preloader ([eefdcd0d](https://github.com/Elgg/Elgg/commit/eefdcd0d21633dd4737d9f1a864bce91d6e05ab8))
  * persistent login table records get removed after expiration ([9d13932e](https://github.com/Elgg/Elgg/commit/9d13932e8fa7cece8409ed2369174b4aa13ef995))
  * added easy way to add default notfound text to listings ([64aabbb8](https://github.com/Elgg/Elgg/commit/64aabbb85315170877e7efc05800694a36a12978))
* **cron:** log cron output to file ([114890f1](https://github.com/Elgg/Elgg/commit/114890f139eebfbd6dce71082a798a0dfdad876d))
* **css:**
  * centralized z-index css rules ([6575fd2b](https://github.com/Elgg/Elgg/commit/6575fd2b9b485eb5893f8093246f9b86c4a03854))
  * set body background color via CSS variables ([6f4823f5](https://github.com/Elgg/Elgg/commit/6f4823f5770ef8c7ae38af918c7720c037f927a2))
* **data:** normalize data exports and serialization ([4e70b843](https://github.com/Elgg/Elgg/commit/4e70b8431f35df78ca9873abdc687821ef1b14bc), closes [#8708](https://github.com/Elgg/Elgg/issues/8708))
* **db:** query builder now supports EXISTS comparison clause ([eebaaeb2](https://github.com/Elgg/Elgg/commit/eebaaeb2521f65a0a052382cccfa3f9d200c2afc))
* **developers:**
  * screen logging is now written to file ([12644880](https://github.com/Elgg/Elgg/commit/12644880acb60bbfe47e5039e59b16b05bb3ae13), closes [#10787](https://github.com/Elgg/Elgg/issues/10787))
  * add Services inspector ([f2544321](https://github.com/Elgg/Elgg/commit/f2544321190f2ca7aaef61c35a9ec1ed90bc751f))
  * allow extending theme sandbox form preview ([b3fd5bc1](https://github.com/Elgg/Elgg/commit/b3fd5bc1e7e83c3e630709822209763cfbdb403e))
* **entities:** get_entity_dates support all ege options ([57ab421b](https://github.com/Elgg/Elgg/commit/57ab421b1de63c8ce38ca3daaf39549dc62b1054))
* **forms:**
  * option to not show 'Only friends' in userpicker ([e06372ea](https://github.com/Elgg/Elgg/commit/e06372eae8feda67ac57632e976f24e8374b76eb))
  * default all POST forms to multipart/form-data encoding ([6f95cc1d](https://github.com/Elgg/Elgg/commit/6f95cc1d30cb24baee059d50462ab7bec88f1be6))
* **friends:** added add/remove friend action to title menu ([b0069a6f](https://github.com/Elgg/Elgg/commit/b0069a6f25202ad8c1488cd9e80f66a070303a95))
* **groups:**
  * edit/delete links now show in group entity menu ([f860a2a5](https://github.com/Elgg/Elgg/commit/f860a2a502787f64d4e06e60330154cdc7e30ae6))
  * improve usability of group tools ([aa3f36f7](https://github.com/Elgg/Elgg/commit/aa3f36f747fe2f8faff315c2809b1c349cac4810))
  * replaced group owner transfer with userpicker ([e8814f89](https://github.com/Elgg/Elgg/commit/e8814f89b85b7b74371e329d1a38a40be48e3603))
* **hooks:** added elgg_trigger_deprecated_plugin_hook ([8ee35234](https://github.com/Elgg/Elgg/commit/8ee35234a5e98c8569d2ffc4db1eb03264412c9c))
* **icons:**
  * allow use_cookie param to be passed through getIconURL ([abc2f342](https://github.com/Elgg/Elgg/commit/abc2f34292ae3a9eb08e1da3ae421d13274c3e4c))
  * upgrade FontAwesome library to 5.x series ([d679f4ea](https://github.com/Elgg/Elgg/commit/d679f4ea57c5f564db4369e866e189bc0d8ca2f3))
  * replace ajax gif loader with css animations ([c3d12615](https://github.com/Elgg/Elgg/commit/c3d12615241bf1b289e1430c64576e3a9e76a502))
* **imprint:** allow passing additional imprint elements to summary view ([6d8906ad](https://github.com/Elgg/Elgg/commit/6d8906ad5fd222c7bcdca8c83b19d2d7c66a35b7))
* **input:** added input/objectpicker and input/grouppicker ([3f32c53b](https://github.com/Elgg/Elgg/commit/3f32c53b8394488d27cf82baa1829fdbabb73b1d))
* **logger:**
  * decouple exception handling from Application ([b2a420fa](https://github.com/Elgg/Elgg/commit/b2a420fa497046a34826dd93bc54dce16027c422))
  * Logger now uses Monolog ([52c4785c](https://github.com/Elgg/Elgg/commit/52c4785ca84464ea05a104377caa870564406a2a), closes [#6244](https://github.com/Elgg/Elgg/issues/6244), [#11899](https://github.com/Elgg/Elgg/issues/11899))
* **menus:** wrap menu items as collections ([5e96d864](https://github.com/Elgg/Elgg/commit/5e96d86426d5182ee9ac1e89972297347e968611))
* **messages:** add helper functions to get sender/recipient ([14fe0bdc](https://github.com/Elgg/Elgg/commit/14fe0bdc3ce2ea807f8e08ce850b3dd20ca15184))
* **navbar:** properly handle second and third level child menus ([4a7d2088](https://github.com/Elgg/Elgg/commit/4a7d20883cf3ec115e7a608341bd04d59e12d8da))
* **navigation:**
  * entity_navigation menu items now are aware of entity ([b609be0f](https://github.com/Elgg/Elgg/commit/b609be0fe981b1732dcae1f517877dfedabdcb47))
  * added icons to site menu items ([1ddb25f5](https://github.com/Elgg/Elgg/commit/1ddb25f559f4da5c4f85d3504ec385475d8c9f89))
* **output:** adds HTML formatting service ([5d1d94a4](https://github.com/Elgg/Elgg/commit/5d1d94a461c7f34b5f37e750428843f8d10e164c))
* **plugins:** plugins can now define a bootstrap class ([20180468](https://github.com/Elgg/Elgg/commit/20180468e79678a190d7529ab72f784c1dc742d4))
* **request:** add public API to retrieve all request parameters ([6494dd92](https://github.com/Elgg/Elgg/commit/6494dd92e9dad33453f0ea7c2a2f6759d4701b3c))
* **router:** allow HttpException to have context ([3aeecd08](https://github.com/Elgg/Elgg/commit/3aeecd0884f385e775a2adbe8cc5059d574c1e3f))
* **seeder:** seeders can now use progress bar helper ([3845cd55](https://github.com/Elgg/Elgg/commit/3845cd55a373be7a47926fc47b2f3a27465a1cf4))
* **session:** delay session boot until all plugins are loaded ([dd81b847](https://github.com/Elgg/Elgg/commit/dd81b847a97bb229a01efdb8391d5b72f87b443a))
* **simplecache:** allow specifying custom simplecache path ([f0c47749](https://github.com/Elgg/Elgg/commit/f0c47749cf19b8a99ea42c043bc89bb8716ca38d))
* **upgrade:** upgrade service now uses promises ([aa85cf99](https://github.com/Elgg/Elgg/commit/aa85cf99e416e4a21d5d60f5c954118f5abace6a), closes [#11888](https://github.com/Elgg/Elgg/issues/11888), [#11825](https://github.com/Elgg/Elgg/issues/11825))
* **user:** remove persistent cookie data when removing user ([be055496](https://github.com/Elgg/Elgg/commit/be0554966094e707d5e3a5a03314248921f9572d))
* **util:** adds API for managing collections of items ([efbdf71b](https://github.com/Elgg/Elgg/commit/efbdf71b53175d1a97f120a0bdfea3af23b310bf))
* **views:**
  * simplefied redering object summary in full view ([6d15b06a](https://github.com/Elgg/Elgg/commit/6d15b06a6fd0207999238c22c6ed2b2eaf7d9946))
  * added ability to control layout attributes via view vars ([54278764](https://github.com/Elgg/Elgg/commit/54278764567cd5a4952ffd373693facd3d4ebdf1))
* **widgets:** control widget availability by setting a required plugin ([3335b30b](https://github.com/Elgg/Elgg/commit/3335b30b8217b9d0e55943bd951f517140375c3e))


#### Performance

* **core:** load plugin data cache before requesting plugin data ([03383bca](https://github.com/Elgg/Elgg/commit/03383bca5dd0dbf004158a411d2f226264fdd209))
* **entities:** entity preloader now correctly fetches entities ([c379dcd1](https://github.com/Elgg/Elgg/commit/c379dcd1cbdf744857dc3a6327ee84ecce368f26))
* **metadata:** also store entities without metadata in metadata cache ([b291c149](https://github.com/Elgg/Elgg/commit/b291c14945d244e535b9961a05c6fd04ec27bc57))
* **migrations:** improved performance of migrations ([4f00e31e](https://github.com/Elgg/Elgg/commit/4f00e31e28bb03ab82f7c118e38d2c2f36a00414))
* **privatesettings:** getting a single settings uses cached data ([21be3e89](https://github.com/Elgg/Elgg/commit/21be3e8906f8839216576150d6c6dff19ffc107d))


#### Documentation

* **composer:** document Elgg installation using composer ([62c7cdf6](https://github.com/Elgg/Elgg/commit/62c7cdf64ec75037b4963b49b21fb44c2e5d9458))
* **core:** updated function docs to mention metadata casts bool ([437a152b](https://github.com/Elgg/Elgg/commit/437a152befdeb1d9934072b174018bb2eb734030))
* **i18n:** added instruction for translating special docs syntax ([2e544f60](https://github.com/Elgg/Elgg/commit/2e544f60b43bdb79cc45e6bab073fc9eb6c592c8))
* **plugins:** explain Composer support for plugin development ([bfc1d64d](https://github.com/Elgg/Elgg/commit/bfc1d64df514fccdec1c3914e455ca9d9939dd5c))


#### Bug Fixes

* **actions:**
  * registration failure no longer leaves behind partial users ([2548a709](https://github.com/Elgg/Elgg/commit/2548a7091d44948a6e1c75e6f157e067bdc24093))
  * invokable classes can now be used as action controllers ([4d586960](https://github.com/Elgg/Elgg/commit/4d58696009937c6da74b58befd8cfaf519c073b0))
* **ajax:** correctly report HTTP errors ([b0f48470](https://github.com/Elgg/Elgg/commit/b0f48470ac4edb86d8d30f724869c1cf9b1f9bc2), closes [#11911](https://github.com/Elgg/Elgg/issues/11911))
* **blog:** auto save draft refactored to use Ajax API ([00ab2a3f](https://github.com/Elgg/Elgg/commit/00ab2a3fb3a412ca861d8169b6b774b503255fa5))
* **bootdata:** correctly order plugins when loaded from bootcache ([e59c80f6](https://github.com/Elgg/Elgg/commit/e59c80f6971bdb17122264e53e57a8ed0cc825a3))
* **cache:**
  * do not mutate config on temporary system/simple cache disable ([fd6edf1f](https://github.com/Elgg/Elgg/commit/fd6edf1f23bfec3150721c02dc0943aece513d6f), closes [#11954](https://github.com/Elgg/Elgg/issues/11954))
  * refuse to cache unsaved entities ([742e28fd](https://github.com/Elgg/Elgg/commit/742e28fd0bb75b0575eb109a7bd34c5e24e3b7ff))
* **caches:**
  * metadata is now accessible with data cache disabled ([14acc289](https://github.com/Elgg/Elgg/commit/14acc289ebbb8a81d0a67ba29b2e7aef7f785791), closes [#12014](https://github.com/Elgg/Elgg/issues/12014))
  * disable caches during flush and upgrade ([bef2dcf3](https://github.com/Elgg/Elgg/commit/bef2dcf3f854404b15a5a8929d7909aa79cf7cc6), closes [#11940](https://github.com/Elgg/Elgg/issues/11940), [#10616](https://github.com/Elgg/Elgg/issues/10616), [#11205](https://github.com/Elgg/Elgg/issues/11205))
* **ckeditor:** site background should not affect the editor ([7902eb99](https://github.com/Elgg/Elgg/commit/7902eb99fa72989f46c4190a603a49fac0e050c9))
* **core:** delay setting last action for users ([a6ebfe03](https://github.com/Elgg/Elgg/commit/a6ebfe03bf3a105983b04863f7cf905877f9cbbd))
* **css:**
  * don't let elgg-badge influence text alignment ([b22cfc8c](https://github.com/Elgg/Elgg/commit/b22cfc8c644c4f81ebc827f89d522df8f26503e9))
  * added margin to elgg-menu-hz items ([6c7689c1](https://github.com/Elgg/Elgg/commit/6c7689c1fa2220e408ef11bbf38e46c03655035a))
  * lightbox loader now matches ajax loader ([0f155247](https://github.com/Elgg/Elgg/commit/0f1552479e974361adf865409bc939b364361dbd))
  * reset fieldset min-width ([62b13352](https://github.com/Elgg/Elgg/commit/62b133526d9589969a8ec3f051d985c7b869c35f))
  * apply hover-card menu section styling consistently ([f32cf115](https://github.com/Elgg/Elgg/commit/f32cf115b70de48d4dc7d5da1563da91bf52fb88))
  * added bottom margin to fieldsets with a legend ([a69a35c8](https://github.com/Elgg/Elgg/commit/a69a35c8fd81db828ac30faa9cf463becf3b3905))
* **db:**
  * default ordering of entities relies less on internals of MySQL ([16682c2c](https://github.com/Elgg/Elgg/commit/16682c2ce5147bcfaa8194c0fdd31eac2403230f))
  * query counter also counts QueryBuilder queries ([054b4af8](https://github.com/Elgg/Elgg/commit/054b4af8a6fe461e8eb8e311222f24b621bcdbee))
* **developers:**
  * prevent missing language key notice recursion ([a46ddce0](https://github.com/Elgg/Elgg/commit/a46ddce0091494a8601ac28e0edfb7f74bac8697))
  * prevent 'view not found' deadloops ([7846efac](https://github.com/Elgg/Elgg/commit/7846efacdfee543bcd7d877c72bf359ee858aded))
  * register custom logger only in default viewtype ([a79f8dbe](https://github.com/Elgg/Elgg/commit/a79f8dbe98f940cf909d327420321fca362c0539))
  * leave system logging intact ([5ea40d7a](https://github.com/Elgg/Elgg/commit/5ea40d7ac65d1c256e613e64012a84152de37991))
* **forms:** improve appearance of file input with value ([7ff6f165](https://github.com/Elgg/Elgg/commit/7ff6f1650e8684ec23ab9c0b08af620d0b34082c))
* **group_tools:** getting all tools is also passed through hook ([080a8ed8](https://github.com/Elgg/Elgg/commit/080a8ed84a151992240f7cf6329bf9f67b224b8c))
* **groups:**
  * correctly resolve tools when populating form vars ([1b7a755e](https://github.com/Elgg/Elgg/commit/1b7a755e115e0f82cde57f054cd04acf29be9653), closes [#12049](https://github.com/Elgg/Elgg/issues/12049))
  * respect subtype specific permissions in group modules ([3bd90073](https://github.com/Elgg/Elgg/commit/3bd90073b9a5699dba54ad32b5102b5b2171f048))
  * don't allow content access outside of group scope ([435d237c](https://github.com/Elgg/Elgg/commit/435d237ce4ee32fdd5860b99cddf894152451e6e))
  * entity gatekeeper should respect entity access ([94402127](https://github.com/Elgg/Elgg/commit/94402127c33e1922f1a3c8e276e2136bd7b7251e))
  * correctly register site menu item route ([77062a27](https://github.com/Elgg/Elgg/commit/77062a274e2459c945579cca57afe7aae1241bf1))
  * remove excessive info from group listing imprint ([a7d6f17c](https://github.com/Elgg/Elgg/commit/a7d6f17c3ceb7b7197f487dbddd3d7f982909842))
* **i18n:** correctly load plugin translations when caches are off ([4a561903](https://github.com/Elgg/Elgg/commit/4a561903db075b0b9d9958f2d8e7de1888e89407), closes [#11916](https://github.com/Elgg/Elgg/issues/11916))
* **icons:**
  * also check default view if subtype icon is not present ([e7f36c54](https://github.com/Elgg/Elgg/commit/e7f36c542f1f8951ebc23984f6358a50269887bf), closes [#11908](https://github.com/Elgg/Elgg/issues/11908))
  * align use of hyperlinks in nentity icon views ([d3a2b548](https://github.com/Elgg/Elgg/commit/d3a2b548e14bebc8ec4aeca3823d66219828b8de))
* **installer:**
  * fix various installer issues ([65d32149](https://github.com/Elgg/Elgg/commit/65d321499fee02cefe7cc449136f62537af3e5e1), closes [#11935](https://github.com/Elgg/Elgg/issues/11935), [#11553](https://github.com/Elgg/Elgg/issues/11553))
  * installer works again ([ba4d57c6](https://github.com/Elgg/Elgg/commit/ba4d57c6c72c86c0f6369513e62ef94d72d29e61), closes [#11852](https://github.com/Elgg/Elgg/issues/11852))
* **js:**
  * userpicker now works in lightboxes ([797d69fb](https://github.com/Elgg/Elgg/commit/797d69fb8aa9690eb0f1886c7190b2026d4e0455))
  * prevent event bubbling up when requiring confirmation ([c6fa5de2](https://github.com/Elgg/Elgg/commit/c6fa5de2e8d1157c35eb1bf1d7db97a87da63916))
* **layouts:** filter part of the layout can be disabled ([a4840f6c](https://github.com/Elgg/Elgg/commit/a4840f6ce5be0eff93e07a00208e4a29a4d503eb))
* **longtext:** do not render empty tag if there is not output ([d58e0cdc](https://github.com/Elgg/Elgg/commit/d58e0cdcf75e37e4316abafdb487d4a258b9e79b))
* **members:** correctly add menu items to filter tabs ([83789995](https://github.com/Elgg/Elgg/commit/8378999549d62b5e94f73e35853c923829398faa))
* **menu:** correctly auto-detect selected menu items ([7a02a600](https://github.com/Elgg/Elgg/commit/7a02a600229542d81b3a14df73dd4a42ad4489cb))
* **menus:**
  * remove extraneous margin from site menu ([ad1e17af](https://github.com/Elgg/Elgg/commit/ad1e17afb16d97f2f600825736856aa3f3808e33))
  * consistent handling of child menus across navigation system ([1793f51b](https://github.com/Elgg/Elgg/commit/1793f51b8213583be979329620d3fb2c0a9864f7))
* **output:** only escape values that are strings ([11d3e207](https://github.com/Elgg/Elgg/commit/11d3e207a9f54bd15f902e7178919ea4a26eec5b))
* **perm:** allow ownership if editing is allowed ([c5bfb6de](https://github.com/Elgg/Elgg/commit/c5bfb6de377c1027c0be92a0d2aa3a4e259f0c2d), closes [#11213](https://github.com/Elgg/Elgg/issues/11213))
* **plugins:**
  * fetch priority directly from private settings ([67bc0ca5](https://github.com/Elgg/Elgg/commit/67bc0ca52f31c25acfd8831d1def9b389b2f177f))
  * dependency notice was missing some text ([df21f777](https://github.com/Elgg/Elgg/commit/df21f777de15ffbeb8291c36d53679fc6ee88ea5))
  * avoid multiple executions of runtime logic in plugin files ([7a54ef6d](https://github.com/Elgg/Elgg/commit/7a54ef6d8ca44cc384ebff711b7c5b41c7ef7856), closes [#11946](https://github.com/Elgg/Elgg/issues/11946))
* **profile:** set page_owner_guid if no username is set ([30365150](https://github.com/Elgg/Elgg/commit/30365150af533389d70a0ac7c58a68aaf1b8db9e))
* **qb:**
  * search_name_value_pairs now work as expected ([a490255b](https://github.com/Elgg/Elgg/commit/a490255b204458fb02de6772a5ecfe06d166ccd1), closes [#12068](https://github.com/Elgg/Elgg/issues/12068))
  * correctly normalize private settings prefix ([7c12b629](https://github.com/Elgg/Elgg/commit/7c12b629cef5b4afe00fe186bbda5a3ea2808972), closes [#11739](https://github.com/Elgg/Elgg/issues/11739))
  * use correct aliases in query predicates ([d6c3a5bf](https://github.com/Elgg/Elgg/commit/d6c3a5bf4ca6c2dbbac7dc4550850f0b7808796c))
  * set table alias when querying entities table ([fe8ccc62](https://github.com/Elgg/Elgg/commit/fe8ccc628e85aefd80a68e879e264d2ecb00708c))
  * preserve 0 guid when normalizing query options ([85d8023a](https://github.com/Elgg/Elgg/commit/85d8023ae664a24727eba691f8c174ee3674e331), closes [#11992](https://github.com/Elgg/Elgg/issues/11992))
* **router:** correctly detect if request body has been truncated ([6bde4f9e](https://github.com/Elgg/Elgg/commit/6bde4f9eb29e9e75ab137093126064bc9e0b659b))
* **routes:** defer route and action registration until plugins are loaded ([2a2c1e46](https://github.com/Elgg/Elgg/commit/2a2c1e46a8e8c6868cc4075848ed51df2ca492fc))
* **search:** consistent behaviour for search ([57f67578](https://github.com/Elgg/Elgg/commit/57f67578c8a45f0a0ba2304b2196bb57b0d7a880))
* **tests:** corrected return value in getAllForEntity mock ([e26c5b71](https://github.com/Elgg/Elgg/commit/e26c5b71928c8c1e33d4a467d0c2c0e070e8d04b))
* **theme_sandbox:** make sure dummy users have usernames ([95052728](https://github.com/Elgg/Elgg/commit/95052728bc73744aa1c8dad1872f31f68b3eb822))
* **thewire:** corrected delete actions ([7a5991d0](https://github.com/Elgg/Elgg/commit/7a5991d0fc05735ad313fe1acb32183c5b0e1305))
* **upgrades:**
  * make sure all friends are migrated to the new ACL ([0da30367](https://github.com/Elgg/Elgg/commit/0da303673553a1ff1ff75562cd73573b53047ea6))
  * logging is now more meaningful during upgrades ([0f376b60](https://github.com/Elgg/Elgg/commit/0f376b60e16cd9c4ea253f9130c993654eca4839), closes [#11936](https://github.com/Elgg/Elgg/issues/11936))
  * delete entities associated with removed plugins ([33daa7b5](https://github.com/Elgg/Elgg/commit/33daa7b5b836e9d176a4968fce013182c4f75ece), closes [#11289](https://github.com/Elgg/Elgg/issues/11289))
  * remove pending upgrades notice upon completion ([6947fb6a](https://github.com/Elgg/Elgg/commit/6947fb6a8ee133097a2604496b16f88187531ad8), closes [#10647](https://github.com/Elgg/Elgg/issues/10647))
  * activate new plugins on system upgrade ([62746292](https://github.com/Elgg/Elgg/commit/62746292c9448d066dfd75b3fabcd236e0d05481), closes [#10603](https://github.com/Elgg/Elgg/issues/10603), [#11915](https://github.com/Elgg/Elgg/issues/11915), [#11891](https://github.com/Elgg/Elgg/issues/11891), [#11699](https://github.com/Elgg/Elgg/issues/11699))
* **users:**
  * correctly register user as searchable ([1f582ed6](https://github.com/Elgg/Elgg/commit/1f582ed6faeda2d7e08709b37f9a6759cbc2216a))
  * avatar menu js is no longer blocking link only avatar icons ([a2ef492a](https://github.com/Elgg/Elgg/commit/a2ef492aba2f2945b3dc01d8ff30bd63bc7cbc25))
  * get user by username or email is case-insensitive ([00747e8c](https://github.com/Elgg/Elgg/commit/00747e8c6682d1faec872071dd9461d65ee0fb98))
* **views:**
  * boot core views before plugins are loaded ([5bd26442](https://github.com/Elgg/Elgg/commit/5bd2644224ad418ef0c43f9df5a94dabe2f4dd93))
  * allow scalar values in output/text ([13d7656e](https://github.com/Elgg/Elgg/commit/13d7656e386154eef43a3195886e2012d536ba77), closes [#12081](https://github.com/Elgg/Elgg/issues/12081))
  * correctly handle text input for output/url ([af8a2736](https://github.com/Elgg/Elgg/commit/af8a273674047bae678e9ac834d74b097acadc70))
  * online users now show no results text if noone online ([2c472596](https://github.com/Elgg/Elgg/commit/2c4725964baf7608b1c202f494ca600db23f3ca0))
  * make sure the AMD ViewFilter is always applied ([1d7905cc](https://github.com/Elgg/Elgg/commit/1d7905cc4eeb6b502236ea0ce7b6cb889af9634b))
  * do not render empty listing navigation ([3365d51d](https://github.com/Elgg/Elgg/commit/3365d51dbf7a14ef8efbf6540114ac5b2c8b8462))
  * do not render image block if object summary is empty ([0ccfb8bd](https://github.com/Elgg/Elgg/commit/0ccfb8bde51596a6daccea8d07701c75b27c4c5b))
* **widgets:** show a no results text if there are no banned users ([5c503eca](https://github.com/Elgg/Elgg/commit/5c503eca450e7b92b346edecf09739503465c6d8))


#### Deprecations

* **core:**
  * get_entity_dates replaced by elgg_get_entity_dates ([2e8b0530](https://github.com/Elgg/Elgg/commit/2e8b053076dbf9249d087a895fe0ad8166d4a41a))
  * elgg_list_registered_entities is deprecated ([86c13cef](https://github.com/Elgg/Elgg/commit/86c13cef1e2add9798b4842836bd87e15b9ac305))


<a name="3.0.0-beta.3"></a>
### 3.0.0-beta.3  (2018-03-08)

#### Contributors

* Jeroen Dalsem (38)
* Ismayil Khayredinov (29)
* Jerôme Bakker (14)

#### Features

* **actions:**
  * it is possible to return a response in the action hook ([562eb031](https://github.com/Elgg/Elgg/commit/562eb031945c8df9c4f781d96411e6b2de8f4235))
  * individual actions are now routes, support controllers ([32a07bc0](https://github.com/Elgg/Elgg/commit/32a07bc0f32353293e06e20a015964df217b5e9a))
* **ajax:** add support for FormData and multipart form data requests ([46590359](https://github.com/Elgg/Elgg/commit/46590359b9a93aebcc078927407a9c0fc64994f6))
* **cache:** added api to remove single item from systemcache ([7da2f561](https://github.com/Elgg/Elgg/commit/7da2f561d7e62dbae0c4add84ca64b9f938b7c72))
* **di:**
  * add config to public services ([6d1bd17b](https://github.com/Elgg/Elgg/commit/6d1bd17b1355e65cb425ff97b1cc0ffc445e1c72))
  * add system messages to public services ([65b8140f](https://github.com/Elgg/Elgg/commit/65b8140f5b2d3c02aade559712b8c258c34aadf0))
  * add hooks and events to public services ([a9fbebca](https://github.com/Elgg/Elgg/commit/a9fbebcaaa75a40851e377a3d1c81353e6f7555c))
  * add translator to public services ([b46e11f1](https://github.com/Elgg/Elgg/commit/b46e11f19b7d1351d610f7178ccb76bbd0c902d5))
  * make gatekeeper a public service ([8d19fc42](https://github.com/Elgg/Elgg/commit/8d19fc42655a47e799f68bd4a34f609131e7b39c))
  * make session a public service ([5ca2ff8b](https://github.com/Elgg/Elgg/commit/5ca2ff8b9bbc7e363dc069d9e0991c9434a32455))
* **forms:** added not-allowed cursor to disable input elements ([cc09c77d](https://github.com/Elgg/Elgg/commit/cc09c77dbe58487f340106589a7b5e8c8cce3cbc))
* **input:** longtext editor opts now store required status ([fae01474](https://github.com/Elgg/Elgg/commit/fae01474a111c94160f1c1c3f25e256774033352))
* **plugins:** added a settings field that triggers cache flush ([d18c0310](https://github.com/Elgg/Elgg/commit/d18c0310d413a04ae91f833e1fcea3ba7988ca7a))
* **profile:** the user profile page now uses a regular title menu ([57efaa5f](https://github.com/Elgg/Elgg/commit/57efaa5fa7178a8c335b64e9ca1f76cad33584d1))
* **route:** add support for handling routes using files ([c4fafb3b](https://github.com/Elgg/Elgg/commit/c4fafb3bcfad2b7d0e675d7cbb61c0dc7d819249))
* **router:** add support for controllers ([11f84355](https://github.com/Elgg/Elgg/commit/11f84355166e7ab808d36af9c6f041dbcc7d5fce))
* **routes:**
  * elgg_generate_url returns false for unknown routes ([6421c422](https://github.com/Elgg/Elgg/commit/6421c422669d818ca533af5ae3cca4adbc024401))
  * add support for route middleware ([0f199324](https://github.com/Elgg/Elgg/commit/0f199324dd3ccd041cc3628b1cc806b610246ac3))
* **system_log:** refactor system log API into a service ([c5846aa8](https://github.com/Elgg/Elgg/commit/c5846aa8d6cf48836d136e8b9af2cd956d285eeb))
* **widgets:** ElggWidget now has getDisplayName instead of getTitle ([12455356](https://github.com/Elgg/Elgg/commit/12455356afa2ea8ecb69608afdbd2790da56c87e))


#### Bug Fixes

* **actions:**
  * fixes error message on missing action file ([554c2e2e](https://github.com/Elgg/Elgg/commit/554c2e2edac27e8ce603f5a1fabfc6927a544834))
  * defining controller in static config works again ([2622fd1e](https://github.com/Elgg/Elgg/commit/2622fd1e08390e67c2d4611188ecd7db5021fbf0))
* **admin:** correctly set action path for settings forms ([5a021d7d](https://github.com/Elgg/Elgg/commit/5a021d7d4449dec5ac484b5c478802c03cc98f3e))
* **core:**
  * canComment now returns false for ElggSite entities ([6f2a2edb](https://github.com/Elgg/Elgg/commit/6f2a2edb9686eddd13f96b8f96aaa011320d745d))
  * directly call EventsService in shutdown function ([48d74a65](https://github.com/Elgg/Elgg/commit/48d74a65ab630feaf2578b77e9543af11d8417f3))
  * make sure minusername and passwordlength are always in config ([ec3b4e94](https://github.com/Elgg/Elgg/commit/ec3b4e944379ab2f8ce9fa0d6591bd480681321d))
* **css:**
  * make sure jquery positioning has correct information ([290aeee3](https://github.com/Elgg/Elgg/commit/290aeee3873ea363a4a04e2a3280b4a2b0e8b74b))
  * correct behaviour of elgg-level in all browsers ([258e9d36](https://github.com/Elgg/Elgg/commit/258e9d36a3ce6f428019a95c392881bfe3bd688e))
  * walled garden background correctly positioned in IE ([a527ebdd](https://github.com/Elgg/Elgg/commit/a527ebdd3d0b6ae52726f9b5386ea5f2cb41646f))
  * set correct margin on admin content layout ([5accfeaf](https://github.com/Elgg/Elgg/commit/5accfeafe25675387b9eda6c9f491de60f0fb557))
  * only apply module header font color to first level elements ([c180b203](https://github.com/Elgg/Elgg/commit/c180b2038807d4c648923aa4d1aabdb0ed509421))
  * correct alignment of pageheader and module header elements ([5fe78207](https://github.com/Elgg/Elgg/commit/5fe78207dadfdc8ec6e8d11b2ce1dca00d33a003))
* **email:**
  * add sender to Zend mail message envelope ([58c1fdea](https://github.com/Elgg/Elgg/commit/58c1fdea52889d20cae4f68c9d4337e0447aae16))
  * always default to site email ([23ffef3d](https://github.com/Elgg/Elgg/commit/23ffef3d0e9a2b9cefd0727afc727c1eb5b06283))
* **embed:** make embed route pattern greedy ([4346ea9b](https://github.com/Elgg/Elgg/commit/4346ea9b4ff1ee34637c22f60fc664a0774fda1c))
* **forms:**
  * switched styled checkbox without label now is clickable ([2d2c79c5](https://github.com/Elgg/Elgg/commit/2d2c79c58eeb8f7ac63363755c7eb36102cafa08))
  * select alues are now correctly selected with array elements ([d2dcb978](https://github.com/Elgg/Elgg/commit/d2dcb978dd11831493ba8eb732f66e79321da6df))
  * userpicker works again when friends constraint is set ([a4d80c58](https://github.com/Elgg/Elgg/commit/a4d80c582fab3a4a93956f044f5606159e7fd7ce), closes [#11697](https://github.com/Elgg/Elgg/issues/11697))
* **groups:**
  * show group dropdown at correct position ([a781f1dc](https://github.com/Elgg/Elgg/commit/a781f1dcc3253a15787aa0f989151d6fe5d4ff99))
  * only show toggle indicator if there is a submenu ([6aa05764](https://github.com/Elgg/Elgg/commit/6aa057646c021df736dadc1a445c541c3ca26812))
* **js:**
  * ajaxed login form now correctly returns to REFERER ([605089af](https://github.com/Elgg/Elgg/commit/605089af991d4e5e7f639960e70bc1e4444ee4b8))
  * close system messages when opening a lightbox ([67ed9a1e](https://github.com/Elgg/Elgg/commit/67ed9a1edc06a5a7947f173fcdf80af93fafd10f))
* **navigation:** metadata and river menu sections are positioned inline ([30b90d00](https://github.com/Elgg/Elgg/commit/30b90d00e56a290790a37a9d68e5db1e3903d89e))
* **notifications:** set correct subtype for user notifications ([6e30ceac](https://github.com/Elgg/Elgg/commit/6e30ceac9d2b47e412a8b7668cc7e65e8a91282a))
* **profile:** custom profile fields can be saved again ([2d773027](https://github.com/Elgg/Elgg/commit/2d7730271a16d12231852e0dafa63cd6b99a66ce))
* **river:** core should register the river/delete action ([0a4956a4](https://github.com/Elgg/Elgg/commit/0a4956a4a0d38cc562b02d66a5711182b9a73ac6))
* **system_log:** correctly extend login_history view to settings ([1543c201](https://github.com/Elgg/Elgg/commit/1543c20178dc21fcacc97c6ac82ff74b4ef79cb2))
* **views:** numentities statistics view correctly gets user entity ([3de7dca2](https://github.com/Elgg/Elgg/commit/3de7dca2c3b4d5c0cebaf8ca353db60b0ddc26b5))


<a name="3.0.0-beta.2"></a>
### 3.0.0-beta.2  (2018-01-31)

#### Contributors

* Jerôme Bakker (5)
* Ismayil Khayredinov (2)

#### Features

* **db:** use public service container for database operations ([4015b8ce](https://github.com/Elgg/Elgg/commit/4015b8ceafbf0cb4c7a7c05323f032b188d13356))
* **di:** plugins now have access to DI container ([8cdff630](https://github.com/Elgg/Elgg/commit/8cdff63092788fbb9d2527c9e155cf46d7fe1555))


#### Documentation

* **release:** updated release docs ([d035c9d6](https://github.com/Elgg/Elgg/commit/d035c9d6ea4a68849cd2ecfd6692c15082677a21))


#### Bug Fixes

* **activity:** use correct route name for owner_block menu item ([989eb5b2](https://github.com/Elgg/Elgg/commit/989eb5b24ef3a0421589eda8c9968e4fa8b994c2))
* **cli:** correcly load Elgg in cli ([aecd6df7](https://github.com/Elgg/Elgg/commit/aecd6df7644a39f1087cfef0877bcea5ac87b088))
* **views:** correctly register core views ([82423b30](https://github.com/Elgg/Elgg/commit/82423b30f2b2275eacada157b63642b233da964a))


<a name="3.0.0-beta.1"></a>
### 3.0.0-beta.1  (2018-01-29)

#### Contributors

* Jeroen Dalsem (268)
* Ismayil Khayredinov (180)
* Jerôme Bakker (133)
* Steve Clay (123)
* jdalsem (56)
* Juho Jaakkola (7)
* Ismayil Khayredinov (3)
* iionly (2)
* Juho Jaakkola (1)
* Phanoix (1)
* Wouter van Os (1)
* Yaco (1)
* piet0024 (1)
* raghukul01 (1)
* sebz (1)

#### Features

* **access:**
  * the friends access is now an access collection ([eccc9713](https://github.com/Elgg/Elgg/commit/eccc97137d6dbdf57a5a2422cd8d1a7704cb2ac6), closes [#3391](https://github.com/Elgg/Elgg/issues/3391), [#5038](https://github.com/Elgg/Elgg/issues/5038))
  * readable access level can now be filtered ([240d19df](https://github.com/Elgg/Elgg/commit/240d19df4313540d4ee2a03fc38766962fd820cd), closes [#8491](https://github.com/Elgg/Elgg/issues/8491), [#6402](https://github.com/Elgg/Elgg/issues/6402))
* **account:** allow admins to change usernames of users ([2bcecfd0](https://github.com/Elgg/Elgg/commit/2bcecfd0957e1ef2b9467429429a2a644fd91eb7))
* **acl:** added subtype to access collections ([450aaa4c](https://github.com/Elgg/Elgg/commit/450aaa4cf028419eb41a25ca2e26d9559d5a0069))
* **actions:**
  * added $CONFIG->action_time_limit to set a custom execution timeout for all actions #7204 ([3682a3c6](https://github.com/Elgg/Elgg/commit/3682a3c655c35755b5ad2c630d7c85702c08c3b2))
  * user login action now is ajaxed ([82d40345](https://github.com/Elgg/Elgg/commit/82d40345f8ba82031148720a514e3e4e54d38026))
  * added the ability to configure actions in elgg-plugin.php ([299df3cd](https://github.com/Elgg/Elgg/commit/299df3cdbf513a5cef54b33e3fc602ee265ba447))
* **activity:**
  * moved group related pages into the activity plugin ([f8f2ebd3](https://github.com/Elgg/Elgg/commit/f8f2ebd344b4d43acdb2d409e7e08ce1b39426e7))
  * options for river filter can now be provided as view_var ([04888ae4](https://github.com/Elgg/Elgg/commit/04888ae4da8fb6faf2a1f82f119f0ff725283517), closes [#9918](https://github.com/Elgg/Elgg/issues/9918))
* **admin:**
  * add an option to delete all notices ([d5f342d1](https://github.com/Elgg/Elgg/commit/d5f342d1ee33da8520885f155fffe9ad78eaa398))
  * admin area improvements ([b5167124](https://github.com/Elgg/Elgg/commit/b51671244847f9efd418ccfa3e78069e06527475), closes [#10325](https://github.com/Elgg/Elgg/issues/10325), [#11028](https://github.com/Elgg/Elgg/issues/11028))
  * content stats are separated in searchable and other content ([263bd28d](https://github.com/Elgg/Elgg/commit/263bd28dcd3a87a7b3418be5f95acb7b24f8e314), closes [#7862](https://github.com/Elgg/Elgg/issues/7862))
* **blog:**
  * use best practices in rendering resources ([55f8d95b](https://github.com/Elgg/Elgg/commit/55f8d95b66bed81189621f873d779d8c172a3342))
  * archive sidebar is now using a menu to show archive links ([06e7c499](https://github.com/Elgg/Elgg/commit/06e7c4992206874c8b01767b30ec41bf8389df85))
* **bookmarks:** update bookmarks plugin to comply with best practices ([49e4c7ff](https://github.com/Elgg/Elgg/commit/49e4c7ff1432392d71b07312f7751790e72563d6))
* **cache:**
  * boot cache is now enabled by default ([575c6fc5](https://github.com/Elgg/Elgg/commit/575c6fc5c6bcc12414649b07c30890e9fd7a3b26))
  * consolidate caching API ([1aa04eca](https://github.com/Elgg/Elgg/commit/1aa04eca49e71885421da3242ac7997676be8982))
  * added json file types as cacheable ([2f380b2e](https://github.com/Elgg/Elgg/commit/2f380b2ecaabd2611117be649745c8308f24689b), closes [#9041](https://github.com/Elgg/Elgg/issues/9041))
* **ckeditor:**
  * updated to full ckeditor v4.7.3 using composer ([dbedc19b](https://github.com/Elgg/Elgg/commit/dbedc19b9b8d81c45e62bd130690afe86c00eedb))
  * changed style of editor to be more minimalistic ([5c19b59e](https://github.com/Elgg/Elgg/commit/5c19b59eb7da392c990406a5d0e354c3997ac192))
* **cli:**
  * allow to set dbhost with CLI installer ([b452f750](https://github.com/Elgg/Elgg/commit/b452f750214577054dab9b0f9cc36b9a4dc78fac))
  * adds elgg-cli seed and unseed commands ([ded471f1](https://github.com/Elgg/Elgg/commit/ded471f12375a42eb18b5df5a324d6e12d75c4fc))
  * adds elgg-cli command line tool ([65007269](https://github.com/Elgg/Elgg/commit/65007269415c197770e79974babfe64a854bc66e), closes [#6612](https://github.com/Elgg/Elgg/issues/6612))
* **collections:** rewrite friends collections, friendspicker ([ec40d1a5](https://github.com/Elgg/Elgg/commit/ec40d1a5d3f121189ab7c158b9f61307badd28d2), closes [#9092](https://github.com/Elgg/Elgg/issues/9092), [#9026](https://github.com/Elgg/Elgg/issues/9026))
* **comments:**
  * comments are configurable per entity ([c407af05](https://github.com/Elgg/Elgg/commit/c407af05729b2b5a61ac6445b237500144fb8ab3))
  * submitting comments is now ajaxed ([c875b2ce](https://github.com/Elgg/Elgg/commit/c875b2ce1d2ec9873f954949a9995c78f0bb2c8d))
  * offloaded comment creation notification ([78a60a62](https://github.com/Elgg/Elgg/commit/78a60a6208da30cbd04b9e2db7612de3550560f6))
* **components:** a menu can now be added to module header ([cd7ea08a](https://github.com/Elgg/Elgg/commit/cd7ea08a815a201a3702b4bd4efb051725482bee))
* **core:**
  * only show language selection if more than 1 option ([4eb7ab36](https://github.com/Elgg/Elgg/commit/4eb7ab36bf4080646c20fc6db6638505d8ac75e7))
  * elgg_get_config supports a default value if config not set ([6693b8ad](https://github.com/Elgg/Elgg/commit/6693b8ad5ad1f58699dd7167b206a3c39a7e5517))
  * added config to disable RSS feeds and Elgg branding ([e6ae6056](https://github.com/Elgg/Elgg/commit/e6ae6056a5af7ae92b64054269d9fc60689a116e))
  * normalized system messages using elgg_view_message ([1c64d898](https://github.com/Elgg/Elgg/commit/1c64d8984e1ab08f4c0269d79c2a66007e23a66c))
  * added API for temporary ElggFile ([89071ffe](https://github.com/Elgg/Elgg/commit/89071ffe69b2e77c88f76033322a018510d5fff1))
  * orientation of uploaded images will be fixed if possible ([a80306f4](https://github.com/Elgg/Elgg/commit/a80306f4c7758a8f2cd5858a02b73749b26597ba))
  * added the ability to use Imagick as the image processor ([a6629ec3](https://github.com/Elgg/Elgg/commit/a6629ec310953d2642640e448b890659687c09ba))
  * add phpinfo page ([e8e45afa](https://github.com/Elgg/Elgg/commit/e8e45afa1d54260a4a881601b5bdea26d1095bf9))
  * autoregister simplecache views for elgg_load_js/css ([57c29b6e](https://github.com/Elgg/Elgg/commit/57c29b6e8707c8a5ae1e97443628d49170198bc0))
  * reworked the walledgarden layout ([d73e59fa](https://github.com/Elgg/Elgg/commit/d73e59fa906979eae81f815cfd67dd0e5474b14a))
* **cron:** adds cron service ([8941965d](https://github.com/Elgg/Elgg/commit/8941965dc979fe202718c4efb72fe29d197d5bb5))
* **css:**
  * switch to using variables in css files ([d189a199](https://github.com/Elgg/Elgg/commit/d189a19936f93f061225b036434240d8fd8bc6da))
  * CSS is pre-processed by css-crush, all /cache output is filterable ([e5ac8842](https://github.com/Elgg/Elgg/commit/e5ac884239b9584bb1db37665719abc51894a929), closes [#10625](https://github.com/Elgg/Elgg/issues/10625))
* **dashboard:** only show blurb if there are no widgets ([8a45cee7](https://github.com/Elgg/Elgg/commit/8a45cee7ecf98d0fd699fa28c839356d2ff4a27d))
* **database:** plugins can now register their own database seeds ([e167092d](https://github.com/Elgg/Elgg/commit/e167092dd3d1a4f393b0f74ed3f6c166e14b75d4), closes [#11129](https://github.com/Elgg/Elgg/issues/11129))
* **db:**
  * rewrite metadata and annotation getters with QueryBuilder ([74174e7f](https://github.com/Elgg/Elgg/commit/74174e7fc975d441b5fec28677e12bc9f664599d))
  * rewrite elgg_get_entities* using QueryBuilder ([1b80a963](https://github.com/Elgg/Elgg/commit/1b80a9631942dd3a5a5584dad55e42187fb3498d), closes [#5071](https://github.com/Elgg/Elgg/issues/5071), [#6798](https://github.com/Elgg/Elgg/issues/6798))
  * database migrations are now managed by phinx ([55236d98](https://github.com/Elgg/Elgg/commit/55236d98f9570b1acfa6a50ac9cce63a27e45564), closes [#7947](https://github.com/Elgg/Elgg/issues/7947), [#5442](https://github.com/Elgg/Elgg/issues/5442))
  * new installations use utf8mb4 and longtext columns ([5e75e7cf](https://github.com/Elgg/Elgg/commit/5e75e7cfe3c597faff2b587fa281e5415ae2b349), closes [#10863](https://github.com/Elgg/Elgg/issues/10863))
* **developers:**
  * add route inspector ([38372e26](https://github.com/Elgg/Elgg/commit/38372e26827103daf5166fb6f47552ea2a504bc6))
  * theme sandbox now users core layout elements ([b2af689a](https://github.com/Elgg/Elgg/commit/b2af689a2375c58721d6db827419a54775dca960))
  * added settings to block or forward email notifications (#11265) ([5063d9db](https://github.com/Elgg/Elgg/commit/5063d9dbf6691d8c61304109540c4fd38e742c6e))
* **discussion:** replies have been moved to comments ([9549d7e8](https://github.com/Elgg/Elgg/commit/9549d7e8f8e369256bcfacbb3dbc5d7d5cb8c34c))
* **discussions:** update reply form to latest forms API ([98a6aaec](https://github.com/Elgg/Elgg/commit/98a6aaec28dfe0d47b33d5b5f7c190fee8a640b6))
* **email:**
  * email address helper class for formatting ([972a8906](https://github.com/Elgg/Elgg/commit/972a8906c12abec4fd48af8eec3a191c12107efc))
  * support e-mail attachments ([f6f9a025](https://github.com/Elgg/Elgg/commit/f6f9a02543b72d8b7f7be03883a5f2368208c384))
  * more granular API for handling system emails ([43709e79](https://github.com/Elgg/Elgg/commit/43709e796faacb448922994179fe78f5f9a237ba))
  * adds a plugin hook to alter the contents of an email ([c5642515](https://github.com/Elgg/Elgg/commit/c56425159e61ff582da8a9ce310924e3d82397bf))
  * recipient and sender email address formatted ([9c48ccc0](https://github.com/Elgg/Elgg/commit/9c48ccc0a80d0a60806b7f0cad8fa3edd10a62ff))
* **entities:** denormalize entity subtypes ([ed318565](https://github.com/Elgg/Elgg/commit/ed3185653bd2a994b8c09e6cbc062a0a9a93f467))
* **events:** Adds new handler API available for events and hooks ([5f334783](https://github.com/Elgg/Elgg/commit/5f334783ea37544918529b4ad1c1cc17fac80de8))
* **file:**
  * remove elgg:file library ([c54b88a6](https://github.com/Elgg/Elgg/commit/c54b88a6221432383f39ba2a1e7ceed5ef8c9362))
  * default icon sizes are now available for new image thumbs ([cb19affe](https://github.com/Elgg/Elgg/commit/cb19affe5940426747a459c96219f1e2f1e6393b))
* **files:**
  * add download permission checks ([fb8c3e04](https://github.com/Elgg/Elgg/commit/fb8c3e04e4a7bb60972e5ea0e3bb2bfc33f1d279))
  * update file plugin to new file serving API ([1e736aeb](https://github.com/Elgg/Elgg/commit/1e736aebb953051ec1b8a2c165ab3c9595e72b40))
* **forms:**
  * fields can now render custom html ([171e1b32](https://github.com/Elgg/Elgg/commit/171e1b322238d1041a36ee7107db90e6cc71fe9b), closes [#10090](https://github.com/Elgg/Elgg/issues/10090))
  * allow specifying a custom view for fields ([ecfe4ac7](https://github.com/Elgg/Elgg/commit/ecfe4ac787ea73a7884baa71ad7b08e39316af4b))
  * pass all vars prefixed with # to field view ([99bb2d07](https://github.com/Elgg/Elgg/commit/99bb2d0725593f6aa05ff0a8b57bc6fcb4b1e79b))
  * add time input, support DateTime values ([25ddb36c](https://github.com/Elgg/Elgg/commit/25ddb36ca99027dbdbcfa753382a48f00c43e760), closes [#11227](https://github.com/Elgg/Elgg/issues/11227), [#7476](https://github.com/Elgg/Elgg/issues/7476), [#4506](https://github.com/Elgg/Elgg/issues/4506))
  * form_vars and body_vars are now available in the footer ([b823e609](https://github.com/Elgg/Elgg/commit/b823e609ecccdc61dd6dfb40eb942dd8cb0b8a31))
  * input/radio and input/checkboxes support options_values ([908972c0](https://github.com/Elgg/Elgg/commit/908972c024a9a70c4a12158377ab245b1aa305cf), closes [#6043](https://github.com/Elgg/Elgg/issues/6043), [#3483](https://github.com/Elgg/Elgg/issues/3483))
  * forms without a body will no longer output a form element ([d29ce0b3](https://github.com/Elgg/Elgg/commit/d29ce0b3a53a5a1d4c1d82fab87da2f9e5d1dd7d), closes [#8459](https://github.com/Elgg/Elgg/issues/8459))
  * input/select now support optgroups ([10bb273a](https://github.com/Elgg/Elgg/commit/10bb273a56e2a02dea5b83ffbd2da66fb7fa25b4))
  * input/checkbox view support 'switch' styling ([9be6f53f](https://github.com/Elgg/Elgg/commit/9be6f53f3d69ce315cf5a93a72a12a4ae2da97a0))
  * input/longtext now support editor variations ([5f3d6b05](https://github.com/Elgg/Elgg/commit/5f3d6b050b122565c2e47ca93b92c7d7d9130120))
  * buttons are now rendered with a <button> tag ([fc680658](https://github.com/Elgg/Elgg/commit/fc680658c8b12bea1e74fe44f25195eb2936f804))
* **gatekeeper:** implement stricter banned user checks ([d0deb646](https://github.com/Elgg/Elgg/commit/d0deb646bbf7d6771ccf6f602a33096bf5be3714))
* **groups:**
  * improved group title menu and reorganized group stats ([d611eeb5](https://github.com/Elgg/Elgg/commit/d611eeb5d180f44803defe8a03f93121d59e34c9))
  * clearer join/owner status and less prominent leave button ([41fb03ea](https://github.com/Elgg/Elgg/commit/41fb03ea4d29206c4a1deffd06f1535cf851ba84), closes [#8872](https://github.com/Elgg/Elgg/issues/8872))
  * added topbar menu item for pending invitations ([82c60c99](https://github.com/Elgg/Elgg/commit/82c60c99bf8d98a4100dab193d5a5c24c941ac6f))
  * added generic ElggGroup functions for tool availability ([246db29f](https://github.com/Elgg/Elgg/commit/246db29f92f42094c5718ef3045b49ce07d897b2))
  * consolidate API for joining groups ([8d57dfd3](https://github.com/Elgg/Elgg/commit/8d57dfd3e3e50f8aaf92ab7a04b0f918597c959d), closes [#10659](https://github.com/Elgg/Elgg/issues/10659))
  * allow group invitations to be resend ([7206894b](https://github.com/Elgg/Elgg/commit/7206894bd34c44479344580d16e8ed053491211a))
  * validate container permissions when creating a new group ([8a3a8787](https://github.com/Elgg/Elgg/commit/8a3a8787bd865b21119ea9602ac24bdb8d175898))
  * group metadata ownership in now in sync with group ownership ([62a14f27](https://github.com/Elgg/Elgg/commit/62a14f27cfafc7e620b77ecc552dd870d7c4adc3))
  * group ACL name is now always in sync with the group name ([e758ef62](https://github.com/Elgg/Elgg/commit/e758ef62e25d0d58663ba6c8057b9f89d0cb65c9))
  * transfer icon files to a new location ([adbc5198](https://github.com/Elgg/Elgg/commit/adbc519855de0d5d01dc198f77b6044e279969ea), closes [#4683](https://github.com/Elgg/Elgg/issues/4683))
  * update group listings to new layout API ([22658ee5](https://github.com/Elgg/Elgg/commit/22658ee5273d850dfdb581da552e612feb2ae2aa))
* **htmlawed:**
  * now using htmlawed v1.2 with html5 support ([43cf04cd](https://github.com/Elgg/Elgg/commit/43cf04cd13ab19383cc9f771ddf91523783cb825))
  * Expose plugin hook for spec parameter ([9aebac50](https://github.com/Elgg/Elgg/commit/9aebac50b266f9cf56f74cda9bc573bc1cc9de4d))
* **i18n:**
  * added a function to retrieve available languages ([8f9c1092](https://github.com/Elgg/Elgg/commit/8f9c1092a0f02c79cbcc538cd7e9cf3185b85a47))
  * core triggers an event after translations are reloaded ([56812eff](https://github.com/Elgg/Elgg/commit/56812effa22d07cebd5175fae296abbad8b3f1aa), closes [#8119](https://github.com/Elgg/Elgg/issues/8119))
* **icons:**
  * large icons are now default upscaled and square ([86c3a8ee](https://github.com/Elgg/Elgg/commit/86c3a8ee28bc6535b3034fa75fe1e5a7e16a2f2f))
  * lazy generate entity icons on demand ([29e92b67](https://github.com/Elgg/Elgg/commit/29e92b670a5be41a15e1d44064880c7c15c8cd01))
  * make it easier to replace default entity icons ([b1efd68f](https://github.com/Elgg/Elgg/commit/b1efd68f77685b9a40a38a77e7b776c8528b55a9))
* **input:**
  * show default help text about upload limit on input/file ([a15a7ecf](https://github.com/Elgg/Elgg/commit/a15a7ecf937bb869d59447fc9ee887130a9157ae))
  * Adds function to get HTML-escaped input ([f1819935](https://github.com/Elgg/Elgg/commit/f18199353bbb9583f11f3575522e718288c28e8d))
* **install:** installation changes ([57ac75ec](https://github.com/Elgg/Elgg/commit/57ac75ecfec978e8f46ffa6687d9dd78022e776b), closes [#5871](https://github.com/Elgg/Elgg/issues/5871))
* **js:**
  * added ability to register toggleable menu items server side ([e974f3a7](https://github.com/Elgg/Elgg/commit/e974f3a771e2a3423fe9dd813a5cc75e9dccdf9c))
  * all core js is now part of the views system ([761a4bf5](https://github.com/Elgg/Elgg/commit/761a4bf5c679958bc7f91102e32c3ce9ed193a42))
  * lightbox href calls now uses elgg/Ajax ([888969b7](https://github.com/Elgg/Elgg/commit/888969b7755279e8c05c3930cf06b29b435f943e))
* **labels:** adopt entity and collection labelling convention ([da1c1054](https://github.com/Elgg/Elgg/commit/da1c1054e294fb778d112071461c82ac85e77ffa))
* **layout:**
  * alt sidebar width is now fluid ([47a5e79a](https://github.com/Elgg/Elgg/commit/47a5e79a6c8c4da8d0b28e34888553ebe175c336))
  * alt sidebar no longer depends on sidebar ([d20f2d9a](https://github.com/Elgg/Elgg/commit/d20f2d9a36151850b220b88b0f1b0e7a428caa12))
* **layouts:** decompose and centralize layout views ([2e52ed91](https://github.com/Elgg/Elgg/commit/2e52ed9138d6c77010d31cd656fa9366c842789f))
* **lib:**
  * remove support for plugin libraries ([533fb7a9](https://github.com/Elgg/Elgg/commit/533fb7a9584f1e00940722c0151e677f799b7fb6), closes [#11176](https://github.com/Elgg/Elgg/issues/11176))
  * make calls with ignored access easier ([f714da6c](https://github.com/Elgg/Elgg/commit/f714da6cd2e03e1ee96d584e45ec69792088617e), closes [#6694](https://github.com/Elgg/Elgg/issues/6694))
* **lightbox:** add responsive imageless lightbox theme ([02b4a142](https://github.com/Elgg/Elgg/commit/02b4a142ecaeca5dcac758f6d043181f335d1a0a))
* **likes:**
  * like annotation access is no longer linked to entity access ([ab7336b2](https://github.com/Elgg/Elgg/commit/ab7336b20de0f82dca0fbb8ff8ee4dbb8cb93afe))
  * show likes in river for logged out users ([10ef9b53](https://github.com/Elgg/Elgg/commit/10ef9b535f4101afe01110c02e5737b28a0c602c), closes [#10897](https://github.com/Elgg/Elgg/issues/10897))
  * updates are page-wide and can come from any ajax response ([96207369](https://github.com/Elgg/Elgg/commit/962073696d2599ad6f78f6d54e858294ba037457), closes [#9698](https://github.com/Elgg/Elgg/issues/9698))
* **listing:** move access info from menu to byline ([9f31969b](https://github.com/Elgg/Elgg/commit/9f31969b1cc12829d615e8bf8c3ab3a1b653513b))
* **lists:** list component view now handles all data types ([7b7d3349](https://github.com/Elgg/Elgg/commit/7b7d3349b4beac4b1a74a5135b7abf5e000a6c6f))
* **members:**
  * added admin link to create new user on members listing ([1e6b6a68](https://github.com/Elgg/Elgg/commit/1e6b6a68537480ccfe399f51c420f6a5415f4934))
  * added members tabs to member search page ([27274acd](https://github.com/Elgg/Elgg/commit/27274acde2894bc8bc57a831bd91a3af325cfc3a))
  * use filter menu and resource views for the pages ([f4b780ab](https://github.com/Elgg/Elgg/commit/f4b780ab408deb222ad115dde8cb10e7f7776727))
* **menus:**
  * set default menu order to priority ([8b355878](https://github.com/Elgg/Elgg/commit/8b355878d14632a4d3cfec90b0237a2024bf46d6))
  * standardized usage of entity menu and added a social menu ([f7e881db](https://github.com/Elgg/Elgg/commit/f7e881dbb064ff4917fa9f11bf50824e5ae0ee79))
  * added icons to user hover menu items ([ffa267d0](https://github.com/Elgg/Elgg/commit/ffa267d043cecee74d37d6d2e9b532953f844ef3))
  * consolidate child menu behavior and UI ([24218e39](https://github.com/Elgg/Elgg/commit/24218e3910493accf51ca9ecc9bba561ef444610))
  * anchors now support icons and badges ([0af43627](https://github.com/Elgg/Elgg/commit/0af4362756c5b41ffec667571bc858b8eee9f70e))
* **messages:** message presentation uses standard summary/full views ([87a8e834](https://github.com/Elgg/Elgg/commit/87a8e834b468dd3b460e17a64baf239b03118f2e))
* **messaging:** improves admin notices and system messages ([28d297f1](https://github.com/Elgg/Elgg/commit/28d297f126e4522ab70e1aca81309c5a5c58e376), closes [#10917](https://github.com/Elgg/Elgg/issues/10917))
* **navigation:**
  * added view vars to toggle entity/social menu ([07b87da0](https://github.com/Elgg/Elgg/commit/07b87da06fbdc300b83df2d5484395738063b2c0))
  * added icon to generic add title menu item ([239f65ee](https://github.com/Elgg/Elgg/commit/239f65ee4ccae9eaf7eeeece2a357aab7ea28e79))
  * breadcrumbs is now a menu ([b6ba0435](https://github.com/Elgg/Elgg/commit/b6ba04353c027fa7558479e22a03a1d6a5f781ae))
* **notifications:**
  * target URLs for notifications are now set explicitly ([1b73ed89](https://github.com/Elgg/Elgg/commit/1b73ed89c231dbdfed62998732af83b0812c9c85))
  * rewrite notification settings interface ([22afc923](https://github.com/Elgg/Elgg/commit/22afc9236358e336c7a4ed76e3bcb9e88ea67e54))
* **output:**
  * passing href false sets href to javascript:void(0) ([951d5239](https://github.com/Elgg/Elgg/commit/951d5239d44fdcd4ba59cd4d1d623002b21bc382))
  * inline rendering of tags ([12af7270](https://github.com/Elgg/Elgg/commit/12af727037174e2c88d780c130ebf39d24dc1ffe))
* **pages:**
  * use best practices in pages plugin ([3040b569](https://github.com/Elgg/Elgg/commit/3040b5693b83abdd0aef7fbe372a9c48dbdb5e31))
  * migrate page_top subtype into pag ([e88b5707](https://github.com/Elgg/Elgg/commit/e88b5707a60761ea26026109072624bec832ce0e))
  * replaces inline js with an AMD module ([362855d6](https://github.com/Elgg/Elgg/commit/362855d6535916bfe4f01635a58d35368d7f6285))
  * some improvements to resource and form views ([cb4f2733](https://github.com/Elgg/Elgg/commit/cb4f273397d80352e2d2cab1b3df6ba2806b4655))
  * Combine search results output of top pages and subpages ([109923ef](https://github.com/Elgg/Elgg/commit/109923ef77b98690f1e68a33b7ce9a56e108165c))
* **permissions:** permissions for admin users or with ignored access are now universal ([d5c9fdf7](https://github.com/Elgg/Elgg/commit/d5c9fdf7a590ee185518cc170dc0e49575ee2c77), closes [#7999](https://github.com/Elgg/Elgg/issues/7999))
* **php:** require PHP 7.0 ([7aa2b370](https://github.com/Elgg/Elgg/commit/7aa2b3702f8d4d503ef6e3a257bfda1a7ee31288))
* **plugins:**
  * use elgg-plugin for entity class and search registration ([4e088246](https://github.com/Elgg/Elgg/commit/4e088246262861958f9303255356b192e352c1ad))
  * widgets can be added using elgg-plugin.php ([a26c5d5a](https://github.com/Elgg/Elgg/commit/a26c5d5a3baf4fe03805f77312ac964414d74564), closes [#10348](https://github.com/Elgg/Elgg/issues/10348))
  * set default (user)plugin settings in elgg-plugin.php ([a8779635](https://github.com/Elgg/Elgg/commit/a8779635aa957626fe4dcb087f5767cd6232edfe))
  * autoload plugin composer autoloader ([ff63f9b8](https://github.com/Elgg/Elgg/commit/ff63f9b8529177905410e13a53d1692480a1ed10))
  * plugins no longer require a start file ([8a8a0283](https://github.com/Elgg/Elgg/commit/8a8a02835bf8a2f0a813e9d5d65b9e54e9be5fb2))
  * moves UI of friends and activity to plugins ([1a923804](https://github.com/Elgg/Elgg/commit/1a923804bad9c41f2cc391bf1fd9d81c153b64ee))
* **profile:**
  * add generic field output ([95028745](https://github.com/Elgg/Elgg/commit/950287453142e5ef9d8e770d2b74e4c2b846b89c), closes [#10412](https://github.com/Elgg/Elgg/issues/10412))
  * rebuild profile layout ([c0d4189c](https://github.com/Elgg/Elgg/commit/c0d4189cc93299d0a45fa09186111317fac860b0), closes [#11557](https://github.com/Elgg/Elgg/issues/11557))
  * the profile layout page now uses a 2 column widget layout ([9ec0dbc1](https://github.com/Elgg/Elgg/commit/9ec0dbc1c75915245f80c24083c7c7d340f06d7c))
* **river:**
  * remove type/subtype and access columns from river table ([861c20d3](https://github.com/Elgg/Elgg/commit/861c20d37f9b98327684a6c0224f4be24adf36d3), closes [#11346](https://github.com/Elgg/Elgg/issues/11346))
  * creating a river item defaults subject_guid to current user ([6426cdd0](https://github.com/Elgg/Elgg/commit/6426cdd0407bc16deb776588877106795cc53374))
  * elgg_view_river_item has a fallback logic for view ([1c91f716](https://github.com/Elgg/Elgg/commit/1c91f7169d52d41684f28aa7dda7d041ec9b98e6))
  * elgg_delete_river checks permissions and fires events ([892cbee3](https://github.com/Elgg/Elgg/commit/892cbee38428e3b986c964550094b2b2e1929d79))
* **router:** adds routing based on URL templates ([9fbd4a84](https://github.com/Elgg/Elgg/commit/9fbd4a841197252e8065928bfafd3576babfc824), closes [#4820](https://github.com/Elgg/Elgg/issues/4820))
* **routes:**
  * adds gatekeeper service ([dbff5bc8](https://github.com/Elgg/Elgg/commit/dbff5bc872d45400b7c2258d1c7091eabddb334c))
  * use named routes in web_services ([f7a27b62](https://github.com/Elgg/Elgg/commit/f7a27b62f2aaab0bf1945623f4df9f96719befce))
  * use named routes in embed ([bf333ee6](https://github.com/Elgg/Elgg/commit/bf333ee646a9ae29433ae9cec4805319d974a0e0))
  * use named routes in uservalidationbyemail ([065e80fc](https://github.com/Elgg/Elgg/commit/065e80fc842feef3c8e8a8664b3be79580f6f8d3))
  * use named routes in thewire ([cd1cb11f](https://github.com/Elgg/Elgg/commit/cd1cb11f61775185283d123707d31c5de6f33c6a))
  * use named routes in tagcload ([10d6d5a4](https://github.com/Elgg/Elgg/commit/10d6d5a4d315a294d8790358594e2d4ddf22570f))
  * use named routes in site_notifications ([b69e5cbe](https://github.com/Elgg/Elgg/commit/b69e5cbe0174603018e57f7aee82b09907251592))
  * use named routes in search ([6f085cd8](https://github.com/Elgg/Elgg/commit/6f085cd8909debc590b868644bc9392a1512ddbd))
  * use named routes in pages ([3abd9cd2](https://github.com/Elgg/Elgg/commit/3abd9cd2a41a69df938853faa0d06874ef6ce706))
  * use named routes for notifications ([60daa1d4](https://github.com/Elgg/Elgg/commit/60daa1d41d7dfafbffaea4c478fe457d6cfbf00b))
  * use named routes for messages ([6073279d](https://github.com/Elgg/Elgg/commit/6073279dae997bb7bb5a57fd529615a87b523dfc))
  * use named routes in messageboard ([11ee1ed6](https://github.com/Elgg/Elgg/commit/11ee1ed647d1bec627e3283329abd248b8f717cf))
  * use named routes in members ([e7d970a9](https://github.com/Elgg/Elgg/commit/e7d970a9d7b6b594140810f69c0a16a1eb74d49d))
  * use named routes in invitefriends ([c07ddf27](https://github.com/Elgg/Elgg/commit/c07ddf270182309b4c8c9ea240227c7e2e560aa8))
  * use named routes in groups ([adc4d1ab](https://github.com/Elgg/Elgg/commit/adc4d1abe477618ea7e42cd0c93bc9ac0886cfc8))
  * use named routes in friends_collections ([ba3880ea](https://github.com/Elgg/Elgg/commit/ba3880eacbe0b6184e002b3d665c0f608f4915c9))
  * use named routes in friends ([821b4a29](https://github.com/Elgg/Elgg/commit/821b4a29086f1044fde6ac6c28f954f3073647ab))
  * use named routes in expages ([63c5d7a8](https://github.com/Elgg/Elgg/commit/63c5d7a8f8742411767943d0c11747f5ff4ce896))
  * use named routes in developers ([486f0906](https://github.com/Elgg/Elgg/commit/486f0906bfb732500acdca5d626ac0a82e2aa54c))
  * use named routes in dashboard ([effdd372](https://github.com/Elgg/Elgg/commit/effdd372cc619fb95d07f9f79c18ac6ce082a754))
  * use named routes in bookmarks ([64075726](https://github.com/Elgg/Elgg/commit/640757264171c9fa5dafdb6f7fe2b8f70335cf81))
  * add utilities for setting breadcrumbs ([18e9aecc](https://github.com/Elgg/Elgg/commit/18e9aeccd4f1ef092268f2ad800ac822527a8078), closes [#10818](https://github.com/Elgg/Elgg/issues/10818))
  * use named routes in core ([f04c3925](https://github.com/Elgg/Elgg/commit/f04c3925c99817ec836046c3bb89c24128284a2d), closes [#9126](https://github.com/Elgg/Elgg/issues/9126))
* **search:**
  * adds a new core search service ([c359fec2](https://github.com/Elgg/Elgg/commit/c359fec229fb9bea0ee80bc8d274dc2bba089cfe), closes [#7392](https://github.com/Elgg/Elgg/issues/7392), [#11274](https://github.com/Elgg/Elgg/issues/11274), [#7062](https://github.com/Elgg/Elgg/issues/7062))
  * outputting a searchbox is now part of the default sidebar ([fe9eec0d](https://github.com/Elgg/Elgg/commit/fe9eec0d72dd0646b045d3e9d6af7cdfe52ef271))
* **security:** add security settings page ([a7ab8ecb](https://github.com/Elgg/Elgg/commit/a7ab8ecbc86030adf44b623e1e600d229c7c640f))
* **site:** added class function to get site email address ([fe005ba5](https://github.com/Elgg/Elgg/commit/fe005ba55bf721298a2eae49126f65ae33eb8505))
* **standards:**
  * apply new coding standards to entire code base ([5a63b3ca](https://github.com/Elgg/Elgg/commit/5a63b3ca541c799bfe17227f970e113acd9fb27e))
  * update to new Elgg coding standards ([582e0458](https://github.com/Elgg/Elgg/commit/582e0458a05675d7c600f36ccd99be3b461d34e8), closes [#10825](https://github.com/Elgg/Elgg/issues/10825))
* **system_log:** move system log to its own plugin ([39401bee](https://github.com/Elgg/Elgg/commit/39401beead88fdeb32c9f2e60cbd2b0f7d0eb37e))
* **tests:**
  * travis now also test on PHP 7.2 ([5089dcb7](https://github.com/Elgg/Elgg/commit/5089dcb7f86877c96e0a16854bb8ba946506b6d6))
  * migrate simpletest suite to phpunit integration tests ([0c2c756f](https://github.com/Elgg/Elgg/commit/0c2c756f44d313b4fbb8a22371dc2df33a013dac))
  * adds integration and plugin testing bootstrap ([471de772](https://github.com/Elgg/Elgg/commit/471de772c26dab83e30cb4451509704364e80496))
  * adds elgg-cli simpletest command ([7b2d459b](https://github.com/Elgg/Elgg/commit/7b2d459b6223a550a38869ecaed0cc670fb68b0b))
* **theme:**
  * new theme ([521041d3](https://github.com/Elgg/Elgg/commit/521041d340dbc7d9b6dfba3cd0ecef18f38efc7b), closes [#11134](https://github.com/Elgg/Elgg/issues/11134), [#10201](https://github.com/Elgg/Elgg/issues/10201), [#7658](https://github.com/Elgg/Elgg/issues/7658), [#10857](https://github.com/Elgg/Elgg/issues/10857), [#10316](https://github.com/Elgg/Elgg/issues/10316), [#4762](https://github.com/Elgg/Elgg/issues/4762), [#11245](https://github.com/Elgg/Elgg/issues/11245), [#6912](https://github.com/Elgg/Elgg/issues/6912))
  * icons now inherit styles from parent items ([ca43d290](https://github.com/Elgg/Elgg/commit/ca43d29098096bb0761308f5cc9ef6b84e86901f))
  * move aalborg theme into core ([0182128d](https://github.com/Elgg/Elgg/commit/0182128d271490fdaed6798591d4f1d9f44cbe08))
* **ui:** new layout of user hover menu contents ([6fe6b2ad](https://github.com/Elgg/Elgg/commit/6fe6b2ada00b762da53506b1b436b7596da8005c))
* **upgrades:** Introduces a new upgrading feature ([6e221f0e](https://github.com/Elgg/Elgg/commit/6e221f0eae01583c2825896503715cb8afed89d1))
* **users:**
  * added generic unvalidated users page/actions ([faa8fe1c](https://github.com/Elgg/Elgg/commit/faa8fe1c4c5f6999fe6015070b5778d11c7b26c8))
  * added a default page handler for viewing users ([962b3a4a](https://github.com/Elgg/Elgg/commit/962b3a4ae58044f5a1c3dfc99fe12d4666f571d7))
  * trigger events for user (in)validation ([3b4fcbb2](https://github.com/Elgg/Elgg/commit/3b4fcbb2e67a51419ee64becb3d405071e5db689), closes [#10576](https://github.com/Elgg/Elgg/issues/10576))
* **vendor:** jquery-treeview is now bundled in core ([a3cf8272](https://github.com/Elgg/Elgg/commit/a3cf82722c21ae526082cabb126b4fd6e906d2b5))
* **views:**
  * output/date & output/time are now wrapped in a time element ([3d348429](https://github.com/Elgg/Elgg/commit/3d34842930d010ee00570ac96e6cd19dc16d1e32), closes [#11576](https://github.com/Elgg/Elgg/issues/11576))
  * friendly time switches to date format after a few days ([85abca36](https://github.com/Elgg/Elgg/commit/85abca3698fa6a83309cede8428668b6a2cbee76), closes [#9897](https://github.com/Elgg/Elgg/issues/9897))
  * object/elements/imprint/time now support a href on the time ([7db48117](https://github.com/Elgg/Elgg/commit/7db481170068ff92e9a561293b30b817d1761b10))
  * added a default hook callback to prevent view output ([3ede3073](https://github.com/Elgg/Elgg/commit/3ede307305517ee99d7b549858704bb2c116521c))
  * view extensions are handled as normal views ([f35b6118](https://github.com/Elgg/Elgg/commit/f35b6118189856acc13033944feb282afaefd0f3))
  * added a generic entity navigation view for full views ([ccb9a74f](https://github.com/Elgg/Elgg/commit/ccb9a74fc70491ac6287169d764bb501c780f1c5))
  * added option to hide the owner_block menu ([bb7b31c6](https://github.com/Elgg/Elgg/commit/bb7b31c6f9f3a394ff3a631488467083091e5928))
  * a more flexible extendable html page shell ([550aeb89](https://github.com/Elgg/Elgg/commit/550aeb89b0cace60f78bc89dcc7d2147e1cb04c9))
  * make layout header responsive ([f3109ec4](https://github.com/Elgg/Elgg/commit/f3109ec41c1a0dd8d58a407df7dc97dd07712a03))
  * add attachments and responses to full object listing ([7808db76](https://github.com/Elgg/Elgg/commit/7808db76311978b13de3759b6b8445755b835e8f))
  * input/password only populates value if explicitely set ([cde67a2c](https://github.com/Elgg/Elgg/commit/cde67a2cd4af7f033b49c93c90038475294658f3))
* **walledgarden:** router now respects walled garden policies ([e71784d2](https://github.com/Elgg/Elgg/commit/e71784d2872c50d957b4470aaf9e9f16d25d5e88), closes [#7235](https://github.com/Elgg/Elgg/issues/7235), [#9881](https://github.com/Elgg/Elgg/issues/9881))
* **widgets:**
  * using input/number to select number of displayed items in widget edit views ([a60da40a](https://github.com/Elgg/Elgg/commit/a60da40a9d77be9900f0f0b8ea66bc1e3b960d84))
  * widget titles are linkable ([8a850486](https://github.com/Elgg/Elgg/commit/8a8504862664a3b104e6aff561638870535d3cc8))
  * adding widgets now opens in a lightbox with more info ([dc1e84fe](https://github.com/Elgg/Elgg/commit/dc1e84fe451721d877b9d013fc3d7be370c694e1))


#### Performance

* **config:** removes config "siteemail" value ([05184ae2](https://github.com/Elgg/Elgg/commit/05184ae21d99b4e137fc24e458985667469f760e), closes [#9096](https://github.com/Elgg/Elgg/issues/9096))
* **entities:** no count query if no pagination in elgg_list_entities ([dae5566a](https://github.com/Elgg/Elgg/commit/dae5566ad7f7f9049d184e36b160cd8ddf4d9901), closes [#9403](https://github.com/Elgg/Elgg/issues/9403))
* **metadata:** memcache most metadata ([01074610](https://github.com/Elgg/Elgg/commit/01074610db25ef99eb35cd4f993d097380baa983))


#### Documentation

* **i18n:**
  * Adds instructions for setting up Transifex for new major Elgg version ([803bd5ad](https://github.com/Elgg/Elgg/commit/803bd5ad463d99458ae18f6f80f99de7999d44e0))
  * Adds instructions for pulling translations from Transifex ([a18f24a8](https://github.com/Elgg/Elgg/commit/a18f24a8fea5059056f7ca22dc656419b9bab5e8))
* **plugins:** describe the steps to move a plugin to own repo ([9b3a8ee7](https://github.com/Elgg/Elgg/commit/9b3a8ee7f47dbf70e66b18fa7ab67cbb9b3c9622))


#### Bug Fixes

* **cache:** remove trailing slashes in cache symlink paths ([1e05f24e](https://github.com/Elgg/Elgg/commit/1e05f24e009b10807887a3537631e29de5a4d054))
* **ckeditor:** fixes basepath issues on some systems ([a5097efc](https://github.com/Elgg/Elgg/commit/a5097efc75e16fe50c5e604f96f6267712ad8004), closes [#10724](https://github.com/Elgg/Elgg/issues/10724))
* **core:**
  * get_registered_entity_types now returns empty array for type ([cc14cb0e](https://github.com/Elgg/Elgg/commit/cc14cb0e8a866b944f65cece43324e5aa020a2ec))
  * clear entity temp_metadata before setting a new value ([8bd1b296](https://github.com/Elgg/Elgg/commit/8bd1b2962db8caeabf7a254ce4c089d67d35813c))
  * unsetting metadata from unsaved entity now works ([67203cc1](https://github.com/Elgg/Elgg/commit/67203cc1c90e181da5bfb64c7c8bc0e0722d850b))
  * elgg_http_add_url_query_elements keeps '//' protocol intact ([c53d5c6d](https://github.com/Elgg/Elgg/commit/c53d5c6dfff6debfbb39a325da91348d47d258dc), closes [#9874](https://github.com/Elgg/Elgg/issues/9874))
  * error pages respect walled garden pageshell ([0312852a](https://github.com/Elgg/Elgg/commit/0312852a2c56225456f4cf65a9aa359e00ef9e26))
  * favicon.ico page handler now serves an icon ([d8ce2235](https://github.com/Elgg/Elgg/commit/d8ce2235bd586527c32438a58c67a28ac3eb9e97))
  * gatekeepers now forward with a 403 reason ([94ca91f2](https://github.com/Elgg/Elgg/commit/94ca91f2e418790f89d0fd8ab9a65041914da25c))
* **cron:**
  * cron/run endpoint calls intervals reliably ([9c37d927](https://github.com/Elgg/Elgg/commit/9c37d92737366629d0bae455a7dc7642b35232a9))
  * log correct completed time in cron monitor ([e4a66193](https://github.com/Elgg/Elgg/commit/e4a661933cfba0b77e3405a73ff75989afa5a418))
* **css:** elgg-body elements no longer clip form and positioned elements ([afb99a20](https://github.com/Elgg/Elgg/commit/afb99a2047d8b21f1cdd8206410fafccdf313a5c), closes [#5197](https://github.com/Elgg/Elgg/issues/5197))
* **db:** add missing subtype index to river table ([61747836](https://github.com/Elgg/Elgg/commit/61747836168186c5745cd346ca2017c1fd69b4fb), closes [#10896](https://github.com/Elgg/Elgg/issues/10896))
* **developers:** exclude view wrapping for results from the cachehandler ([d8ff5c39](https://github.com/Elgg/Elgg/commit/d8ff5c393594891f7e86404aafcd98ff542e8716))
* **discussions:** fix comments URL fragment ([c2a781f2](https://github.com/Elgg/Elgg/commit/c2a781f295575557d420c71850d950572ad0ce3d))
* **embed:** correct replace icon size with a non thumbnail size ([f0616d8e](https://github.com/Elgg/Elgg/commit/f0616d8ee5b25ae0d0359747707d6c82808c83ea))
* **entities:** memcache no longer returns disabled entities ([78d20ac2](https://github.com/Elgg/Elgg/commit/78d20ac225aafe1c60402fd7455727a525ca44fa), closes [#10970](https://github.com/Elgg/Elgg/issues/10970))
* **expages:** don't log notice if pages aren't created ([cdc28968](https://github.com/Elgg/Elgg/commit/cdc289680a61676bffb86e5ad63ec086bf696492))
* **file:** display owner icon in file summary of the full listing ([42898271](https://github.com/Elgg/Elgg/commit/428982710d51d24e165559acf1e9239bf7001486))
* **forms:** input/checkbox now applies disabled to hidden default value ([d3ea2025](https://github.com/Elgg/Elgg/commit/d3ea20252ee88b809c511672acad7beed2b7995c))
* **groups:** ensure that user has sufficient permissions to update group_acl ([49fed9b6](https://github.com/Elgg/Elgg/commit/49fed9b6c41fbaea9836083b5b0041e257a25cc7))
* **http:** Use Symfony to parse PATH_INFO ([822696b9](https://github.com/Elgg/Elgg/commit/822696b9bf2502eff3ca47549529c7444bbe1edb), closes [#10608](https://github.com/Elgg/Elgg/issues/10608))
* **i18n:**
  * fallback to site language before English ([e9f5d9d3](https://github.com/Elgg/Elgg/commit/e9f5d9d37cd712970c5b41ae2e1da823c39d8070))
  * set language via GET var works for client-side translation ([ee023ef1](https://github.com/Elgg/Elgg/commit/ee023ef1d3785759fbde0fea513d2f9e1cedd042))
* **input:** better handling of invalid UTF-8 characters ([2283a289](https://github.com/Elgg/Elgg/commit/2283a289e2ffd4ac580736a62fa4eb66a5cca2a3), closes [#5790](https://github.com/Elgg/Elgg/issues/5790))
* **install:** don't block install if can't make internal requests ([591c2806](https://github.com/Elgg/Elgg/commit/591c280673aa756acd2a303372866dd1ad189a06))
* **installer:**
  * sanitize dataroot before writing to settings file ([0eeb141d](https://github.com/Elgg/Elgg/commit/0eeb141d644f374999c0a68c23f477f968d678a0))
  * fix installer and add tests ([baa7040d](https://github.com/Elgg/Elgg/commit/baa7040ddf5a2c3db7b56cc5339736dc5d18bb1f), closes [#11433](https://github.com/Elgg/Elgg/issues/11433))
* **js:**
  * popup showing in wrong location in Opera ([164ae44c](https://github.com/Elgg/Elgg/commit/164ae44ce7ec1aabecfa6cc21e15a2fea75f0f79), closes [#6452](https://github.com/Elgg/Elgg/issues/6452))
  * hook trigger calls handlers in expected priority order ([1b0cc64d](https://github.com/Elgg/Elgg/commit/1b0cc64d708d351f1b2dd1a5b25fad1b40ccb7b0))
* **menu:** load AMD dependencies for the user_hover menu on load ([39d2ef20](https://github.com/Elgg/Elgg/commit/39d2ef2009e3bf42dd764fb28f1010990e588cac))
* **menus:**
  * allow rendering entity menu without a dropdown ([ff791563](https://github.com/Elgg/Elgg/commit/ff79156356e4badfffdfa697fa803e3a692b6b92))
  * menu items will always output an anchor ([cba37560](https://github.com/Elgg/Elgg/commit/cba37560f08745ca7edf82b6adc29b2e667af1d7))
  * provide admin link in case JS fails ([948e3bd6](https://github.com/Elgg/Elgg/commit/948e3bd66a5b1cc0f9c448c16820256599d546c4))
* **metadata:** warn devs when saving data longer than column holds ([eef89d26](https://github.com/Elgg/Elgg/commit/eef89d26f7e581131e908e03fc5b3d509b2613a3), closes [#10861](https://github.com/Elgg/Elgg/issues/10861))
* **navigation:**
  * assume default filter if filter value is set without id ([38df55f0](https://github.com/Elgg/Elgg/commit/38df55f02cdba9ff1b092da72c5de56fa6f76ee5))
  * correct container set on title menu button ([77c8f001](https://github.com/Elgg/Elgg/commit/77c8f001e8c5a52ef8f71a43afa79d02855bad63))
* **notifications:**
  * pass correct params to Email class ([a5ef05ad](https://github.com/Elgg/Elgg/commit/a5ef05ad9ab07ab6d5e69b84e93bb9e97998fe32))
  * fixes banned user notification language/name ([065b8496](https://github.com/Elgg/Elgg/commit/065b849633d7529ec4ad889994d8a3d1851e462a))
  * no notifications about private content ([075d2615](https://github.com/Elgg/Elgg/commit/075d2615106861b1b6bd118cf54dba8c8d28e1e0))
* **plugins:**
  * load elgg-plugin.php after classes and translations ([0397c91b](https://github.com/Elgg/Elgg/commit/0397c91b543baaba2a26b46423877da1c24fc80b))
  * start.php is now only required once, other plugin files included as requested ([875ff66c](https://github.com/Elgg/Elgg/commit/875ff66c2e28ed2bb58854fea18ed69cd4ad5283))
  * unfreeze plugin list after (de)activate no plugins ([939adff4](https://github.com/Elgg/Elgg/commit/939adff4a22e876d809bdeeac107bd3bc7153395))
* **river:** populate type and subtype of the river object ([101d0b74](https://github.com/Elgg/Elgg/commit/101d0b746788d9160047955fb47b16d83e5bb3a4))
* **search:**
  * validate comment ownership in format hook ([b39bbc2c](https://github.com/Elgg/Elgg/commit/b39bbc2c08d270dc76a755b538f50e5a9055bf91))
  * search fields are now reset for typeless search ([4f305de0](https://github.com/Elgg/Elgg/commit/4f305de04762af81c8b227154ca067c1cc532b3a), closes [#11483](https://github.com/Elgg/Elgg/issues/11483))
* **upload:** only prepare files if uploaded ([3c6c8f76](https://github.com/Elgg/Elgg/commit/3c6c8f763582f9602b7f6041dbaab5ebae0fc50f))
* **uservalidationbyemail:** email sent page respects walledgarden shell ([b972b0d2](https://github.com/Elgg/Elgg/commit/b972b0d21221d498c5dbceba3adc9a2fb48819c7))
* **views:**
  * clean up of class attribute usage ([6b3edaf2](https://github.com/Elgg/Elgg/commit/6b3edaf2998c101bba6a6b3ad80198d1e012350a), closes [#11468](https://github.com/Elgg/Elgg/issues/11468))
  * do not wrap tabs in a heading ([fb80a68a](https://github.com/Elgg/Elgg/commit/fb80a68af5e86f5c02febf9fce73b2b048e6ac79), closes [#10764](https://github.com/Elgg/Elgg/issues/10764))
  * output/tag shows text if there is no href present ([e7dfa2a0](https://github.com/Elgg/Elgg/commit/e7dfa2a000b4ddf13e0b88560037298a7778bc83))
  * owner links in by line now always point to user ([2a70902b](https://github.com/Elgg/Elgg/commit/2a70902bc17ea0d69e201e061f175a144dbf09df))


#### Deprecations

* **breadcrumbs:** breadcrumbs now use href instead of link ([6e7235a2](https://github.com/Elgg/Elgg/commit/6e7235a27d00e67608295b40fe3bc32ee57a8eea), closes [#10345](https://github.com/Elgg/Elgg/issues/10345))
* **core:**
  * the use of the function create_metadata is deprecated ([a60ed182](https://github.com/Elgg/Elgg/commit/a60ed1824fecebe35635fb1acdb20a92cd7ebad6))
  * the use of the update_metadata function is deprecated ([fb97d13a](https://github.com/Elgg/Elgg/commit/fb97d13a8cb94e8058d3dcd0a43dffa97f7845a6))
  * removed the site_guid entity attribute from datamodel ([45b2dcc4](https://github.com/Elgg/Elgg/commit/45b2dcc4ea1e6da87b10f4e1c8f3f1800b211fa8))
* **metadata:** removed independent metadata functions (#11086) ([d82b9e1d](https://github.com/Elgg/Elgg/commit/d82b9e1d231d8b8e4c2eedde833d815921ad1727), closes [#11075](https://github.com/Elgg/Elgg/issues/11075))
* **plugins:** no longer use getFriendlyName in ElggPlugin ([69976069](https://github.com/Elgg/Elgg/commit/69976069bc2d24915d6b2b512423817c129c16fa))


#### Breaking Changes

* The legacy_urls plugin is no comes bundled with Elgg, if you need it
load it as a composer dependency.

fixes #11097 ([a3cf1141](https://github.com/Elgg/Elgg/commit/a3cf11417fbb2fbeb47b65ef1e83c502cd8ebf57))
* The pagehandler for reportedcontent has been removed in favour of using
an Ajax form view. This can cause problems when loading JS is slow. ([8edbceb1](https://github.com/Elgg/Elgg/commit/8edbceb12015b25514528286818757933d802c1a))
* dropped the expages page handler ([63c5d7a8](https://github.com/Elgg/Elgg/commit/63c5d7a8f8742411767943d0c11747f5ff4ce896))
* If you extended the usersettings form by extending the view
'forms/account/save' you should update to extend
'forms/usersettings/save' ([5c0b8e5e](https://github.com/Elgg/Elgg/commit/5c0b8e5e5a71a2f7f3c38e5c707fad090be44ac3))
* The unvalidated users page and some actions have been
moved to core and are no longer present in the uservalidationbyemail
plugin.

fixes #4561 ([faa8fe1c](https://github.com/Elgg/Elgg/commit/faa8fe1c4c5f6999fe6015070b5778d11c7b26c8))
* The `elgg:bookmarks` PHP library and bookmarklet GIF were removed.
 ([5d4d66f4](https://github.com/Elgg/Elgg/commit/5d4d66f4b7200c59e61d14ad2c843bf681e7e5b6))
* `reverse_order_by` in $options is ignored. ([f1555502](https://github.com/Elgg/Elgg/commit/f1555502be7b177ac7e659138163078595fa169d))
* HTML of system messages have been changed so they are reusable as inline
message boxes. ([1c64d898](https://github.com/Elgg/Elgg/commit/1c64d8984e1ab08f4c0269d79c2a66007e23a66c))
* The groups specific function 'groups_get_group_tool_options' has been
replaced with the generic 'elgg_get_group_tool_options' function. ([246db29f](https://github.com/Elgg/Elgg/commit/246db29f92f42094c5718ef3045b49ce07d897b2))
* The view ```object/widget/edit/num_display``` now uses an ```input/number``` field instead of an ```input/select``` field to set the number of displayed items. Widget edit views might need to be updated if a custom max number (higher than default_limit or 20) is used or if a custom stepsize of selectable item numbers is wanted.
 ([a60da40a](https://github.com/Elgg/Elgg/commit/a60da40a9d77be9900f0f0b8ea66bc1e3b960d84))
* 
The subtype 'page_top' has been removed from the pages plugin. All top
pages are migrated to the subtype 'page'. Related views and helper
functions have been dropped.

fixes: #11329 ([e88b5707](https://github.com/Elgg/Elgg/commit/e88b5707a60761ea26026109072624bec832ce0e))
* The group metadata has been removed in favor of a access collection
subtype. ([450aaa4c](https://github.com/Elgg/Elgg/commit/450aaa4cf028419eb41a25ca2e26d9559d5a0069))
* use the `upgrade`, `system` event instead

ref: #3655 ([eeb21271](https://github.com/Elgg/Elgg/commit/eeb212715f6bfcfba891bbf2674878311755ec6d))
* 
The create_metadata_from_array function is no longer available. Use your
own foreach loop to create multiple metadata fields. ([caf22201](https://github.com/Elgg/Elgg/commit/caf222011c8fb1f9fc8db448284958c3691c47b1))
* The users_entity table no longer exists. Update your queries if
needed. ([3d5901a4](https://github.com/Elgg/Elgg/commit/3d5901a4729bfe25f524a87526ed43991d9dfafa))
* The elgg_get_entities_from_attributes function is no longer usable to
get entities based on attributes. ([2483b670](https://github.com/Elgg/Elgg/commit/2483b670b36c25e1cd827d411751928fcbce77d3))
* The objects_entity table no longer exists. Update your queries if
needed. ([19926b38](https://github.com/Elgg/Elgg/commit/19926b38233f7e1ed3fd6302d8afb5186638bfcf))
* The groups_entity table no longer exists. Update your queries if needed. ([67eaae29](https://github.com/Elgg/Elgg/commit/67eaae299924a6dc860b680e3c8493efa3aac80f))
* icons are no longer generated buring upload, but on
demand. On demand generated icons are based on master not on an original
file. ([29e92b67](https://github.com/Elgg/Elgg/commit/29e92b670a5be41a15e1d44064880c7c15c8cd01))
* The sites_entity table no longer exists. Update your queries if needed. ([74663893](https://github.com/Elgg/Elgg/commit/74663893595072c4fa8fa90aa19ffcbdfb6aa9a3))
* The file_delete function is no longer available. Take a look at
ElggFile->deleteIcon for an alternative. ([55352578](https://github.com/Elgg/Elgg/commit/5535257869a392492c4a318ad5e664a8935f9468))
* Switch to PSR-0 registration of classes or use composer autoload to
register classes.

Fixes #9753 ([5b8beafa](https://github.com/Elgg/Elgg/commit/5b8beafab23ae6df6f42cf14988e0879c44522b1))
* The `groups:my_status` menu is no longer available. Register your menu
items somewhere else. ([68e4eec7](https://github.com/Elgg/Elgg/commit/68e4eec73bbb4405b99b6f116393bc1fe70e9bde))
* The twitter_api plugin has been moved to a separate repository which can
be found at https://github.com/Elgg/twitter_api The plugin will no
longer be actively maintained by the Elgg core team.

fixes: #5927 ([a1c5a1bf](https://github.com/Elgg/Elgg/commit/a1c5a1bfa7d1502b92f9418ed5ceaa8ea24aae79))
* Admin menu items and some admin menu item views are no longer present or
have been moved to other locations. Update usage accordingly. ([0809709c](https://github.com/Elgg/Elgg/commit/0809709cdfc6baa260c48c09a7ba59a7b4542a8d))
* The extras menu is no longer used. Register your menu items to other
menus.

Fixes #7729
Fixes #8718 ([b62d6247](https://github.com/Elgg/Elgg/commit/b62d624743c15b9f243c59afcee68bf055319d2b))
* The profile/status view is no longer called. You can extend/prepend the
profile/fields view if you need a similar feature. Thewire is no longer
adding the last wirepost to the profile. A wire widget could offer
similar features. ([47741728](https://github.com/Elgg/Elgg/commit/47741728733a68035259dd380daa0bcaef6d3d8f))
* As widget edit forms could not be on the current page it is a bad
practice to rely on widget config values to be always available. This PR
corrects this behaviour. If you override core widget content views you
may need to update these views.

Fixes #10244 ([98c96b60](https://github.com/Elgg/Elgg/commit/98c96b60f6e10734d394d89b977f12aac964f2ab))
* Instead of a span with class elgg-non-link now a regular anchor will be
outputted with the class elgg-non-link ([cba37560](https://github.com/Elgg/Elgg/commit/cba37560f08745ca7edf82b6adc29b2e667af1d7))
* `$CONFIG->input` is no longer set or read. Use `set_input`/`get_input`. ([1e7192b8](https://github.com/Elgg/Elgg/commit/1e7192b8d5213f68c28bf470c56603e80eb1c011))
* `elgg_get_admin_notices()` accepts only an array. ([28d297f1](https://github.com/Elgg/Elgg/commit/28d297f126e4522ab70e1aca81309c5a5c58e376))
* Plugins can no longer rely on Elgg to "hide" metadata in queries. All metadata is
assumed to be public. Plugins that read user profile fields in metadata will see
all fields every time, and plugins that write user profile fields in metadata will
have no effect. These plugins should instead access fields via annotations; see
the profile edit actions and forms for reference.
 ([2567640d](https://github.com/Elgg/Elgg/commit/2567640d40747e6333732fc1efebbf33ca7c65be))
* View `river/item` is removed. Use `elgg_view_river_item()`. ([313585a0](https://github.com/Elgg/Elgg/commit/313585a06dec7347911520c6c736a895bc2c0347))
* `.elgg-body` elements by default no longer stretch to fill available space in
a block context. They still clear floats and allow breaking words to wrap text.

Elements matching `.elgg-module`, `.elgg-head`, and `.elgg-menu-hover` no longer
hide overflowing content. and those matching `.elgg-image`, `#profile-owner-block`,
and `elgg-sidebar` (inside layouts) no longer float, but are now positioned with
flexbox. ([afb99a20](https://github.com/Elgg/Elgg/commit/afb99a2047d8b21f1cdd8206410fafccdf313a5c))
* Entities no longer have an `isFullyLoaded()` method. ([231be2aa](https://github.com/Elgg/Elgg/commit/231be2aac7b65a8e8c66e51bfe60a4f19139683b))
* User icons no longer include a `hover-menu` icon that's displayed on
mouseover. The click event is bound to the surrounding anchor.
 ([7601f863](https://github.com/Elgg/Elgg/commit/7601f863cdc396585b356164bfbc9037687d4056))
* `elgg_format_url()` has been removed. Use `elgg_format_element()` or the
"output/text" view for HTML escaping. ([db746843](https://github.com/Elgg/Elgg/commit/db746843aca1a560b5c8b67d1aa3f12e5b7dc16a))
* The view invitefriends/form no longer exists ([d322bbb6](https://github.com/Elgg/Elgg/commit/d322bbb6ff81aca6e83311ce15fbb04fc04d555b))
* Metadata and annotations name and values are no longer normalized.
Metastrings related functions have been removed. ([53fec72e](https://github.com/Elgg/Elgg/commit/53fec72e9134b5454e4885684ac4d7bd0ccb8627))
* The datalists table functionality has been merged into the config table.
Related datalist functions have been replace by their config equals. ([adcc4974](https://github.com/Elgg/Elgg/commit/adcc49740e80c5edbec4b3fbb86bb04ef696d0f8))
* Because of the removal of the multisite concept in entities, this
relationship makes no sense.

Fixes #10473 ([41ffbd9f](https://github.com/Elgg/Elgg/commit/41ffbd9f1ce15c84c8be6bc614820ed3804372a1))
* To be able to still provide support for MySQL 5.5 combined with InnoDB
the FULLTEXT indices have been dropped. This effects how search works
internally. ([44d987a5](https://github.com/Elgg/Elgg/commit/44d987a56c48f391c472ac86890cd722f695a12e))
* This breaks a lot of site_guid related features, like all the
elgg_get_entities functions. Entities will no longer have a site_guid
attribute.

See http://learn.elgg.org/upgrading#Elgg3.0 for more details on all the
deprecated features regarding this change ([45b2dcc4](https://github.com/Elgg/Elgg/commit/45b2dcc4ea1e6da87b10f4e1c8f3f1800b211fa8))
* If you were relying on group entities attribute 'username' you need to
update your code, as this attribute will no longer be magically returned
as 'group:<group_guid>'. ([d562efbf](https://github.com/Elgg/Elgg/commit/d562efbf881d18cabad0683e8443815ab975ca0b))
* If you rely on a class check for your content, please use the PHP
instanceof type operator ([ca56d46d](https://github.com/Elgg/Elgg/commit/ca56d46d8736ba8d6f317111bdee16c73b3c0142))
* This function can no longer be used. Use Elgg\Upgrade\Batch interface
instead. ([39455bd9](https://github.com/Elgg/Elgg/commit/39455bd9a27b15bc7cb9c88d30d108f95292e9df))
* Stock Elgg does not need these files. If you need them, you have to
adapt the config accordingly. ([1f65142e](https://github.com/Elgg/Elgg/commit/1f65142e40935f27524cead60b28b81a0867c175))
* The event `login, user` is removed. ([461e5e76](https://github.com/Elgg/Elgg/commit/461e5e76e3714a5dc1aee8be947eb88629534934))
* You can no longer use the `system, pagesetup` event ([353d522a](https://github.com/Elgg/Elgg/commit/353d522a8bea0aaac207baea6a4a02a0ba1177ee))
* This change applies the best practice to not populate password fields.
If you really need to set the value of a password field, you need to set
$vars['always_empty'] to false. ([cde67a2c](https://github.com/Elgg/Elgg/commit/cde67a2cd4af7f033b49c93c90038475294658f3))
* `htmlawed` is no longer a plugin. See `docs/guides/upgrading.rst`.
 ([da14997a](https://github.com/Elgg/Elgg/commit/da14997a491beeed8bbb9a88398354d1c0166871))
* `messageboard.js` and `elgg.messageboard` are removed. The
`elgg/messageboard` module is no longer inlined on every page. ([4c8c7b68](https://github.com/Elgg/Elgg/commit/4c8c7b68ccf8d8eb878bc0e47a98e53ea75bef09))
* `likes.js` and `elgg.ui.likesPopupHandler` are removed. The `elgg/likes` module
is no longer inlined on every page, but is required by its menu items.
 ([0121cee7](https://github.com/Elgg/Elgg/commit/0121cee7db4a799edc9ecde01168403332a90b44))
* The `password` and `hash` columns are emptied in the `users_entity` table
and no longer used. The attributes are removed from `ElggUser`. The function
`generate_new_password` is also removed. ([200cf6e7](https://github.com/Elgg/Elgg/commit/200cf6e726280391dccd24a9de63a3057cd6a623))
* In `elgg()->getDb()` (the public DB API), method `getTablePrefix()` is no longer
available. Read the `prefix` property instead. ([a69ecc03](https://github.com/Elgg/Elgg/commit/a69ecc0340b65bfed119273fb2d5d14e26e1808d))
* Elgg no longer serves views via the endpoints `js/` and `css/`. Use
`elgg_get_simplecache_url()` to generate static view URLs. ([6b0a4b89](https://github.com/Elgg/Elgg/commit/6b0a4b89b3d063c592a4682dc87b95b7c764bfaf))
* `elgg_get_config('siteemail')` no longer returns the site email address.
 ([05184ae2](https://github.com/Elgg/Elgg/commit/05184ae21d99b4e137fc24e458985667469f760e))
* `$CONFIG` is no longer available as a local variable inside plugin `start.php` files.
 ([c2cd81d9](https://github.com/Elgg/Elgg/commit/c2cd81d953793a0829f9f359148ded7da3885a56))
* `$SESSION` is removed. Use the API given by `elgg_get_session()` ([99048a39](https://github.com/Elgg/Elgg/commit/99048a397e8a9e47a405c5fb182047bb586c2e09))
* If you registered a hook on the forward you need to update your code if
you checked for the 'admin' and/or 'login' reason ([94ca91f2](https://github.com/Elgg/Elgg/commit/94ca91f2e418790f89d0fd8ab9a65041914da25c))
* To ensure your handler is called last, you must give it the highest priority
of all matching handlers. To ensure your handler is called first, you must
give it the lowest priority of all matching handlers. Registering with the
keyword `all` no longer has any effect on calling order. ([1b0cc64d](https://github.com/Elgg/Elgg/commit/1b0cc64d708d351f1b2dd1a5b25fad1b40ccb7b0))
* The pages plugin no longer renders the `input/write_access` view.
 ([8075fdea](https://github.com/Elgg/Elgg/commit/8075fdea4e9a580d4569c4784b42f2305668595c))
* Removes `ElggFile::setFilestore`, `ElggFile::size`, `get_default_filestore`,
`set_default_filestore`, `ElggDiskFilestore::makeFileMatrix`, and the global
var `$DEFAULT_FILE_STORE`.
 ([618c79d3](https://github.com/Elgg/Elgg/commit/618c79d301eda1b265441b14f3bfbda2235ee3fd))
* The `resources/file/download` view is no longer used. ([1e736aeb](https://github.com/Elgg/Elgg/commit/1e736aebb953051ec1b8a2c165ab3c9595e72b40))


<a name="2.3.17"></a>
### 2.3.17  (2021-04-16)

#### Contributors

* Jerôme Bakker (2)


<a name="2.3.16"></a>
### 2.3.16  (2020-12-18)

#### Contributors

* Jerôme Bakker (7)

#### Bug Fixes

* **ci:**
  * move PHPUnit tests from Travis to GitHub actions ([55d6d893](https://github.com/Elgg/Elgg/commit/55d6d8933acde9bb240cb28224f86b984159e1b2))
  * move documentation test build to GitHub action ([25f3f8a6](https://github.com/Elgg/Elgg/commit/25f3f8a638ec000c6e6632b4e19381aacd068af2))
  * move coding style and composer checks to GitHub actions ([5b8956dc](https://github.com/Elgg/Elgg/commit/5b8956dcbe8dd2b2c9bde70bbbd5524d148f6e1e))
  * move lint checks to GitHub actions ([eba126ec](https://github.com/Elgg/Elgg/commit/eba126ec0aa4095ed55621950973d21560d470af))


<a name="2.3.15"></a>
### 2.3.15  (2020-06-25)

#### Contributors

* Jerôme Bakker (2)

<a name="2.3.14"></a>
### 2.3.14  (2019-07-24)

#### Contributors

* Jerôme Bakker (3)
* Jeroen Dalsem (1)

#### Bug Fixes

* **groups:** no error on notification failure during membership request ([2bd72ffc](https://github.com/Elgg/Elgg/commit/2bd72ffcf2e156d6b3e0fd18ded72846279c37d9))
* **http:** check object for toString function ([1cd0809e](https://github.com/Elgg/Elgg/commit/1cd0809eb7c9c31547b69cb306058dee7bfe3ae1))
* **installer:** detect more https scenarios ([05648781](https://github.com/Elgg/Elgg/commit/056487810a7dccfc524c7c0aa8e0183424842307))


<a name="2.3.13"></a>
### 2.3.13  (2019-06-12)

#### Contributors

* Jeroen Dalsem (1)

#### Bug Fixes

* **blog:** show correct last saved date ([b888e7e1](https://github.com/Elgg/Elgg/commit/b888e7e1a3772f205f5a7fe1de62894964ee8e0c))


<a name="2.3.12"></a>
### 2.3.12  (2019-04-16)

#### Contributors

* Jerôme Bakker (1)

#### Bug Fixes

* **widgets:** improved stability of widget title ([904eefc1](https://github.com/Elgg/Elgg/commit/904eefc191081fa055296e18ee22f2cc2e7b01fc))


<a name="2.3.11"></a>
### 2.3.11  (2019-04-04)

#### Contributors

* Jerôme Bakker (4)
* Ismayil Khayredinov (1)

#### Bug Fixes

* **gatekeeper:** more consistency in resource gatekeepers ([60a045a3](https://github.com/Elgg/Elgg/commit/60a045a3b72734321413830c8375e4594622b9e2))
* **livesearch:** prevent PHP warning in switch statement ([44e671d0](https://github.com/Elgg/Elgg/commit/44e671d053b2f69b59876d942c9aeb340048337e))
* **notifications:** fix faulty subscription list mutations ([0edb38d1](https://github.com/Elgg/Elgg/commit/0edb38d1a86c01e9b0c05911fcd7d9421dc33822))
* **walled_garden:** allow access to webapp manifest.json ([73c36a13](https://github.com/Elgg/Elgg/commit/73c36a139c2271b5330fed60964e6a2522863c20))


<a name="2.3.10"></a>
### 2.3.10  (2018-12-21)

#### Contributors

* Jerôme Bakker (5)

#### Bug Fixes

* **js:** input datepicker can be cleared using delete or backspace ([54b76928](https://github.com/Elgg/Elgg/commit/54b769286745e5e6fb0d5255645eab66144c6cc9))


<a name="2.3.9"></a>
### 2.3.9  (2018-11-14)

#### Contributors

* Jerôme Bakker (6)
* Ismayil Khayredinov (1)
* Jeroen Dalsem (1)

#### Performance

* **entities:** limit entity preloading by max entity cache size ([7619c1f7](https://github.com/Elgg/Elgg/commit/7619c1f79ee59eff5e413bb66d576159905fd1cd))


#### Bug Fixes

* **db:** improved handling of duplicate relationship creation ([418e6a81](https://github.com/Elgg/Elgg/commit/418e6a81414e420d406806e505a0f3445f7aa239))
* **developers:** correctly register ajax view ([c188342d](https://github.com/Elgg/Elgg/commit/c188342d718201879988badea7a77bf3f88c03c0))
* **files:** only try to generate thumbs for image uploads ([36de95f3](https://github.com/Elgg/Elgg/commit/36de95f38cc2dd2887ced951fcb1a2d03b7eafc4))
* **output:** correctly output non string tags ([a2722ff2](https://github.com/Elgg/Elgg/commit/a2722ff268ee8966ed67e82b1c728934d708126b))
* **security:** tokenize outgoing no-reply email address ([bed58cd7](https://github.com/Elgg/Elgg/commit/bed58cd75f43f045bdb743be9ee09159727d3307))


<a name="2.3.8"></a>
### 2.3.8  (2018-07-20)

#### Contributors

* Jerôme Bakker (4)

#### Documentation

* **install:** updated installation requirements ([48de11e1](https://github.com/Elgg/Elgg/commit/48de11e130b034fe9db6ad35a3d06d99af54df0b))
* **web_services:** removed outdated webservices documentation ([6372fa8b](https://github.com/Elgg/Elgg/commit/6372fa8b465e092a92588aa582204ff7a676456b))


#### Bug Fixes

* **core:** revert original libxml_use_internal_errors value after use (#12008) ([69c422c9](https://github.com/Elgg/Elgg/commit/69c422c9c64b55bd8c46a110ec205d73fcb91548))


<a name="2.3.7"></a>
### 2.3.7  (2018-05-24)

#### Contributors

* Jerôme Bakker (6)
* Ismayil Khayredinov (1)

#### Bug Fixes

* **developers:** set correct link to simpletest suite ([b2b9c0b4](https://github.com/Elgg/Elgg/commit/b2b9c0b4ce4f0a18870f6b90a55fa926c7e2e66f))
* **friends:** check friendship relationship before change ([ccd6fbbb](https://github.com/Elgg/Elgg/commit/ccd6fbbbb3efd628b871f2548263d0d9ff3ef7ae))
* **pages:** order of parent page selector reflects tree ([1e22a581](https://github.com/Elgg/Elgg/commit/1e22a5811d2ea60da91d1e3a28cbe50d73f1cf57))
* **views:** prevent unwanted information on user listing elements ([2c74c2ac](https://github.com/Elgg/Elgg/commit/2c74c2ac6e630150808fb1fc953bb06c2eeee3f0))


<a name="2.3.6"></a>
### 2.3.6  (2018-03-27)

#### Contributors

* Jerôme Bakker (5)
* Jeroen Dalsem (1)

#### Bug Fixes

* **config:** control bootdata plugin cache ([60b15b76](https://github.com/Elgg/Elgg/commit/60b15b768de84ffde58a35374f766f0e4a1e6606))
* **db:** correctly default subtypes to prevent PHP warning ([c10a6a4f](https://github.com/Elgg/Elgg/commit/c10a6a4fdc78f0474c7ca026c057e89071773838))
* **memcache:** use correct Memcache class ([8b073aad](https://github.com/Elgg/Elgg/commit/8b073aad1a78365d8633ee329607f7218c9b2b65))
* **rss:** correctly list comments ([892672cf](https://github.com/Elgg/Elgg/commit/892672cf590fef47f3f740ed932c3328ad76da57))
* **views:** listing of entities and river no longer count if not needed ([ee6a043e](https://github.com/Elgg/Elgg/commit/ee6a043eb57abb4e93594643b5b484ebe55a239f))
* **walled_garden:** register plugin hook during init ([f9880cbf](https://github.com/Elgg/Elgg/commit/f9880cbf0ace53c27688830ead0bdec531f5c405))

<a name="2.3.5"></a>
### 2.3.5  (2017-12-06)

#### Contributors

* Jerôme Bakker (7)

#### Bug Fixes

* **tests:**
  * correct validation of action path ([232a87b8](https://github.com/Elgg/Elgg/commit/232a87b84f2e40a6e0f5bda3cb52b63fbb81877d))
  * correct registration of view path ([950da0dc](https://github.com/Elgg/Elgg/commit/950da0dce83eb39e859da6dbc67c0b77c8445038))
  * incorrect filename for test registration ([9af357be](https://github.com/Elgg/Elgg/commit/9af357be3ffe5e43045c0cf417585080d7f58fdb))
  * moved incorrect registered test to correct location ([ba7c894d](https://github.com/Elgg/Elgg/commit/ba7c894d6ce0c86b7c8296d8a255da26fdf71766))


<a name="2.3.4"></a>
### 2.3.4  (2017-09-21)

#### Contributors

* Jerôme Bakker (17)
* Ismayil Khayredinov (5)
* Steve Clay (2)
* jdalsem (2)

#### Documentation

* **composer:** explain how dependencies are managed in Elgg ([f6b30d45](https://github.com/Elgg/Elgg/commit/f6b30d45468607c5a5e82677397d2aa012528fad))
* **icon:** use correct functions for saving entity icon ([2e1b6a47](https://github.com/Elgg/Elgg/commit/2e1b6a473750637931abab2c41f14e1b92338756))
* **install:** bootstrapping Elgg in Laravel Homestead ([84399394](https://github.com/Elgg/Elgg/commit/84399394b7c5da657df10e642d775b11f8cc81c9))


#### Bug Fixes

* **composer:** no longer use deprecated class ([d5e8acbf](https://github.com/Elgg/Elgg/commit/d5e8acbfe7401e961c39025184731df44ad0fc1a))
* **core:** correctly manipulate ini setting to return readable bytes ([bc61a3b9](https://github.com/Elgg/Elgg/commit/bc61a3b942b0afd848496b6bba6bbff95d1033a6))
* **developers:** incorrect header title link in theme sandbox ([583badbe](https://github.com/Elgg/Elgg/commit/583badbe22ac85cd5bed7cf9a07dc2bdbb3f2272))
* **email:** improved formatting of email headers ([cc590e6a](https://github.com/Elgg/Elgg/commit/cc590e6af59fa2bc1b40b0a6d4de62b671258d2f))
* **entities:** batch count now works when $options already count set to false ([62ecabed](https://github.com/Elgg/Elgg/commit/62ecabedff179b3f1f2bc2e261dfbfa77d1c9122), closes [#10992](https://github.com/Elgg/Elgg/issues/10992))
* **groups:**
  * group delete button no longer misaligned ([4bdf92d9](https://github.com/Elgg/Elgg/commit/4bdf92d9dd3f9f43620cf1a4aada28b4cbdfd46e))
  * check for existence of custom icon before generating url ([e6270945](https://github.com/Elgg/Elgg/commit/e6270945263c4eae27295f06b641a3c6282eb52a))
* **plugins:**
  * only include plugin files once ([49d4ce50](https://github.com/Elgg/Elgg/commit/49d4ce50139e595505a6b6b9f872d76438646000))
  * issue error about saving array values at correct location ([ef753ebf](https://github.com/Elgg/Elgg/commit/ef753ebf5ecbe3c94f0a969ed89a14e2268a8751))
* **profile:** now able to remove the first custom profile field ([3d7258ec](https://github.com/Elgg/Elgg/commit/3d7258ec3315b48608f85f10160a8e4a22fdb114))
* **river:** comments no longer show full text in river ([e0669219](https://github.com/Elgg/Elgg/commit/e0669219e6cead6b587de695d15f0fdde0d81790))
* **simplecache:** expires and symlinking cache works on nginx ([fe220126](https://github.com/Elgg/Elgg/commit/fe220126435021e9a53dbe6b46a59d3f6907c786), closes [#9054](https://github.com/Elgg/Elgg/issues/9054))


<a name="2.3.3"></a>
### 2.3.3  (2017-05-16)

#### Contributors

* Steve Clay (9)
* Jerôme Bakker (4)
* iionly (2)

#### Documentation

* **ajax:** normalize code whitespace ([e8437621](https://github.com/Elgg/Elgg/commit/e8437621d03c26cd5a247de09e3beefb06c0d5cb))


#### Bug Fixes

* **db:** warn devs about sanitizing array values ([0e7347b8](https://github.com/Elgg/Elgg/commit/0e7347b869142ab68814f59897ff19400c144f69), closes [#10921](https://github.com/Elgg/Elgg/issues/10921))
* **discussions:** ajax reply form is again a textarea ([cb77158b](https://github.com/Elgg/Elgg/commit/cb77158b003cc4408f2138cdcd356407c4b767d5), closes [#10936](https://github.com/Elgg/Elgg/issues/10936))
* **forms:** no label but normal text styling for checkboxes and radio input field options text ([9fdaefeb](https://github.com/Elgg/Elgg/commit/9fdaefeb24e9ed9b8a6d5d3c7cf73795e7dd1850))
* **groups:** group activity widget can be added as default dashboard widget again without error ([1f468ac9](https://github.com/Elgg/Elgg/commit/1f468ac98df04b0cff4edddcef45e545623b5cc9))
* **installer:** now sees settings file in old location ([be80d39e](https://github.com/Elgg/Elgg/commit/be80d39e0687cb9941eb6fbbe8257d475a2c2e89), closes [#10942](https://github.com/Elgg/Elgg/issues/10942))
* **js:** set correct options for each individual lightbox ([a82eab75](https://github.com/Elgg/Elgg/commit/a82eab7580ea96eacf3109eb4fa12721cb44ce9f))
* **menus:**
  * log error if factory missing 'name' or 'text' ([23f68fe2](https://github.com/Elgg/Elgg/commit/23f68fe22276b0a7d74ba499f8309b954316c675))
  * fixes combineMenus() in menu service ([b0708798](https://github.com/Elgg/Elgg/commit/b0708798bf2b114c911df241ab1daf4414feaf1f))
* **routing:** no longer forwards to ajax/file service URLs after login ([af6e2a68](https://github.com/Elgg/Elgg/commit/af6e2a68cfce696a94341e27e09d009004f22bba), closes [#10695](https://github.com/Elgg/Elgg/issues/10695))


<a name="2.3.2"></a>
### 2.3.2  (2017-03-16)

#### Contributors

* Steve Clay (5)
* Ismayil Khayredinov (1)
* Jerôme Bakker (1)
* Matt Beckett (1)

#### Performance

* **db:** improved performance of disable/delete of an entity ([5adf2ecf](https://github.com/Elgg/Elgg/commit/5adf2ecfcb211cc473beadd06d83dbf7da558f14))


#### Documentation

* **security:** explains current password hashing ([d3affbd9](https://github.com/Elgg/Elgg/commit/d3affbd9287c197daba58b26d45bdd086a90f552), closes [#10778](https://github.com/Elgg/Elgg/issues/10778))


#### Bug Fixes

* **install:** explicitly allow .well-known in rewrite rules ([bb35cb9c](https://github.com/Elgg/Elgg/commit/bb35cb9c317c1176542b76592c7e70805a91b9d9))
* **js:** make sure elgg.forward() always reloads the page ([c42b9c9c](https://github.com/Elgg/Elgg/commit/c42b9c9c8fda8508300db347ee6399a75a87eaf7))
* **output:** elgg_normalize_url() again handles multibyte chars and spaces ([62bf31c0](https://github.com/Elgg/Elgg/commit/62bf31c0ccdaab549a7e585a4412443e09821db3), closes [#10771](https://github.com/Elgg/Elgg/issues/10771))
* **twitter_api:** do not feed remote URLs to icon resize API ([bad30edc](https://github.com/Elgg/Elgg/commit/bad30edca34f09d5ce1f8a0d95d717c0f369964d))


#### Deprecations

* **logging:** removes warnings about metadata/annotation value casting ([97b2b51f](https://github.com/Elgg/Elgg/commit/97b2b51fc7bd049c5c8b66579a1921ae1ff84ee3), closes [#10749](https://github.com/Elgg/Elgg/issues/10749))


<a name="2.3.1"></a>
### 2.3.1  (2017-02-14)

#### Contributors

* Steve Clay (8)
* Jerôme Bakker (5)
* Jeroen Dalsem (2)
* Ismayil Khayredinov (1)
* Yanwei Jiang (1)
* iionly (1)

#### Bug Fixes

* **access:** use ignore access only when querying the database ([fb57c02c](https://github.com/Elgg/Elgg/commit/fb57c02c7bc9fed92c848a6ceeac7d9d5a0866fe))
* **admin:** prevents simultaneous plugin (de)activation/reordering ([907c9b67](https://github.com/Elgg/Elgg/commit/907c9b6714c4457dbb86c2aa6e692d20c9a009ea), closes [#10706](https://github.com/Elgg/Elgg/issues/10706))
* **ajax:** elgg/Ajax now uses spinner if 2nd fetch occurs in done handler ([afef3c4e](https://github.com/Elgg/Elgg/commit/afef3c4e2f115b2365c9af179d678e2ba74b9318))
* **comments:** use elgg/Ajax to load inline comment form ([17d93a5b](https://github.com/Elgg/Elgg/commit/17d93a5bd370a325ea21a81680b19b2c0a517437))
* **discussions:** river entries are once again visible to logged out users ([65e6664d](https://github.com/Elgg/Elgg/commit/65e6664de7c3004e6c59a9ab8c637ef47b549568))
* **embed:** Inserting medium thumbnail size again instead of small on embedding images ([aea45030](https://github.com/Elgg/Elgg/commit/aea45030e3618b5c449f5294cc8d18ec40fb01a0))
* **html:** elgg_normalize_url() handles tel: links ([48a51709](https://github.com/Elgg/Elgg/commit/48a51709c956b5a676711a3febb32c65a5df1e0e), closes [#10689](https://github.com/Elgg/Elgg/issues/10689))
* **icons:**
  * detect image format for resizing ([dd9af8a9](https://github.com/Elgg/Elgg/commit/dd9af8a9fb72723e8b1e724c37d3e2343e157116))
  * set correct filename for temp resizing file ([aeed7060](https://github.com/Elgg/Elgg/commit/aeed7060c394284758b899a021a4328c59571fd3))
* **menus:** return to default of sorting menus by text ([9636790f](https://github.com/Elgg/Elgg/commit/9636790fc84c685e2f0c92fd65ea85d8eb63ea19), closes [#10737](https://github.com/Elgg/Elgg/issues/10737))
* **security:** random byte generation improved on some systems ([03285ba7](https://github.com/Elgg/Elgg/commit/03285ba7c7090f4881797bb74c14aaf74b48c47e), closes [#10750](https://github.com/Elgg/Elgg/issues/10750))
* **uservalidationbyemail:** unset emailsent after showing it once ([4e16cc9b](https://github.com/Elgg/Elgg/commit/4e16cc9b093f6f004dc9af426cb9c9acce00aa96))
* **views:**
  * elgg_view_field no longer leaves #type in attributes ([e4e316e9](https://github.com/Elgg/Elgg/commit/e4e316e9e699e0083b85559a3e707af0341eb19f), closes [#10699](https://github.com/Elgg/Elgg/issues/10699))
  * in table lists, rows now have IDs ([e42fa636](https://github.com/Elgg/Elgg/commit/e42fa636ab73102ad55ef60463f1eeb309211f52), closes [#10696](https://github.com/Elgg/Elgg/issues/10696))


<a name="2.3.0"></a>
## 2.3.0  (2016-12-27)

#### Contributors

* Ismayil Khayredinov (4)
* Steve Clay (3)
* Jerôme Bakker (2)
* iionly (2)

#### Documentation

* **core:** Improve docs about creation of cache symlink ([f984a051](https://github.com/Elgg/Elgg/commit/f984a051e3e14cc316f312475396a3222138c2e6))


#### Bug Fixes

* **ajax:** elgg/Ajax view() and form() set $vars as expected ([abf8a9ce](https://github.com/Elgg/Elgg/commit/abf8a9ce87117ab24cb62e937805750eca780de1), closes [#10667](https://github.com/Elgg/Elgg/issues/10667))
* **core:** Check existence of cache symlink without usage of readlink() ([3e4dc6a1](https://github.com/Elgg/Elgg/commit/3e4dc6a1f2e2b20c5e31800e925ca5779a6f40cf))
* **entities:** entity is now loaded from cache during save operations ([009f74da](https://github.com/Elgg/Elgg/commit/009f74dac2ab5c1834ec672a82e5642dc7c3ab75), closes [#10612](https://github.com/Elgg/Elgg/issues/10612))
* **files:** mitigate issues with special chars in file names ([4a7b74ea](https://github.com/Elgg/Elgg/commit/4a7b74ea27b31be159fba9fb5c3dda405da15409))
* **forms:** fieldset with a legend no longer overrides the class ([726cca18](https://github.com/Elgg/Elgg/commit/726cca18e23510ae1b473f3cfd8b408e557a4c83))
* **http:** elgg/Ajax error responses with 200 status use Ajax wrapper ([1cae50cf](https://github.com/Elgg/Elgg/commit/1cae50cf025a75f32500836f3cd885fedb720b9a))
* **notifications:** incorrect use statement no longer throws ([2a6d782b](https://github.com/Elgg/Elgg/commit/2a6d782b2978cf670a89f0fd9cb5b0ce2820a37d))
* **web_services:** handle string params with proper escaping ([702ce46c](https://github.com/Elgg/Elgg/commit/702ce46c44aec2546f953902061166bf3f48a5af))


<a name="2.3.0"></a>
## 2.3.0  (2016-12-27)

#### Contributors

* Ismayil Khayredinov (4)
* Steve Clay (3)
* Jerôme Bakker (2)
* iionly (2)

#### Documentation

* **core:** Improve docs about creation of cache symlink ([f984a051](https://github.com/Elgg/Elgg/commit/f984a051e3e14cc316f312475396a3222138c2e6))


#### Bug Fixes

* **ajax:** elgg/Ajax view() and form() set $vars as expected ([abf8a9ce](https://github.com/Elgg/Elgg/commit/abf8a9ce87117ab24cb62e937805750eca780de1), closes [#10667](https://github.com/Elgg/Elgg/issues/10667))
* **core:** Check existence of cache symlink without usage of readlink() ([3e4dc6a1](https://github.com/Elgg/Elgg/commit/3e4dc6a1f2e2b20c5e31800e925ca5779a6f40cf))
* **entities:** entity is now loaded from cache during save operations ([009f74da](https://github.com/Elgg/Elgg/commit/009f74dac2ab5c1834ec672a82e5642dc7c3ab75), closes [#10612](https://github.com/Elgg/Elgg/issues/10612))
* **files:** mitigate issues with special chars in file names ([4a7b74ea](https://github.com/Elgg/Elgg/commit/4a7b74ea27b31be159fba9fb5c3dda405da15409))
* **forms:** fieldset with a legend no longer overrides the class ([726cca18](https://github.com/Elgg/Elgg/commit/726cca18e23510ae1b473f3cfd8b408e557a4c83))
* **http:** elgg/Ajax error responses with 200 status use Ajax wrapper ([1cae50cf](https://github.com/Elgg/Elgg/commit/1cae50cf025a75f32500836f3cd885fedb720b9a))
* **notifications:** incorrect use statement no longer throws ([2a6d782b](https://github.com/Elgg/Elgg/commit/2a6d782b2978cf670a89f0fd9cb5b0ce2820a37d))
* **web_services:** handle string params with proper escaping ([702ce46c](https://github.com/Elgg/Elgg/commit/702ce46c44aec2546f953902061166bf3f48a5af))


<a name="2.3.0"></a>
## 2.3.0  (2016-11-09)

#### Contributors

* Ismayil Khayredinov (74)
* Steve Clay (34)
* Jeroen Dalsem (18)
* jdalsem (8)
* iionly (6)
* Jerôme Bakker (3)
* Ismayil Khayredinov (2)
* Brett Profitt (1)
* Matt Beckett (1)
* Pete L (1)
* V. Lehkonen (1)

#### Features

* **account:** login history is added to account statistics page ([3e30ab26](https://github.com/Elgg/Elgg/commit/3e30ab261ffd9e9f19f4b733f4c2bc49582931cd))
* **admin:**
  * add memcache stats to server info page ([6b19ced0](https://github.com/Elgg/Elgg/commit/6b19ced0e431bfb948ec422e44e7d3c7374379d9))
  * move plugin toggle buttons to title menu ([5d75f6db](https://github.com/Elgg/Elgg/commit/5d75f6dbbe43584e256f2ed4a9a6cb7d34883ed9))
  * single plugin toggles done via Ajax ([c46ccb80](https://github.com/Elgg/Elgg/commit/c46ccb8099de55141deb4a05ee2b608f771d34df))
  * makes it easier to navigate plugin dependencies ([4caf7769](https://github.com/Elgg/Elgg/commit/4caf7769953d9b1a4bbb505acd21c482cc2b31ba))
* **api:** allow convenience methods to return ElggBatch as a result ([5618d3c5](https://github.com/Elgg/Elgg/commit/5618d3c5f4289dacb16f0f5121665bd8b9ab912b), closes [#6676](https://github.com/Elgg/Elgg/issues/6676))
* **ckeditor:** better control over ckeditor initialization and behavior ([57ededb0](https://github.com/Elgg/Elgg/commit/57ededb0b75c8d6d978536e0dfebc1ab75563e8f), closes [#9391](https://github.com/Elgg/Elgg/issues/9391))
* **comments:** entities can now inherit canComment permissions ([b1614671](https://github.com/Elgg/Elgg/commit/b1614671be25811d3079a8e778d17519af258e13))
* **components:** add inline tabs component with ajax support ([4de1cd28](https://github.com/Elgg/Elgg/commit/4de1cd286d4fd83c6e4159e88bc4ff07ecbed73f))
* **composer:** brings back composer.lock ([0b07d9a8](https://github.com/Elgg/Elgg/commit/0b07d9a84ef631fd5b8e7564cac7db1752f0d41d), closes [#9430](https://github.com/Elgg/Elgg/issues/9430))
* **core:** Use input/number input view for default_limit input field in basic settings form ([3c6bce2d](https://github.com/Elgg/Elgg/commit/3c6bce2d95033340d99d0110c0037cd3b3dd0ca8))
* **css:**
  * input/button with disabled state is now styled as disabled ([3aec56a6](https://github.com/Elgg/Elgg/commit/3aec56a65c053ce71ad0f4034b2b8e36bdd74440))
  * elgg-state-disabled class now is applied to all buttons ([bb70a507](https://github.com/Elgg/Elgg/commit/bb70a50773865eb5574369fce35a2e69d66f45ab))
* **developers:**
  * explorer entity information in developer tools ([251f4067](https://github.com/Elgg/Elgg/commit/251f4067b32589042ba44b18f7c9b9a54b6db7a4))
  * add object full listing to theme sandbox ([85b67b90](https://github.com/Elgg/Elgg/commit/85b67b90bc9e91028fbf1bdf1429fe3dda7525c4))
  * add object summary listing view to theme sandbox ([878dbc8e](https://github.com/Elgg/Elgg/commit/878dbc8e4891a4659d19d4761288fe2b9982964e))
  * add custom attributes to image block sandbox view ([92d86a67](https://github.com/Elgg/Elgg/commit/92d86a6737e7c27dada53efe1b139ec14a530a4e))
* **entities:** container logic is now checked before permissions ([c87dc7d1](https://github.com/Elgg/Elgg/commit/c87dc7d172f233d2429b1e94d165f64316e8a84b), closes [#9695](https://github.com/Elgg/Elgg/issues/9695))
* **events:** added elgg_clear_event_handlers function ([110497b7](https://github.com/Elgg/Elgg/commit/110497b709522589858ca6c2c9c26a0f16a44c42))
* **export:** now triggers a generic to:object hook for annotation and metadata ([5adc6771](https://github.com/Elgg/Elgg/commit/5adc67713847ff60a96aab01ee44ac24b3e75b18))
* **files:** adds new API for handling file uploads ([09499677](https://github.com/Elgg/Elgg/commit/09499677fa11b468c6a0771082d35ecdf73c02d4), closes [#7778](https://github.com/Elgg/Elgg/issues/7778), [#9876](https://github.com/Elgg/Elgg/issues/9876), [#9934](https://github.com/Elgg/Elgg/issues/9934))
* **forms:**
  * replaces elgg_view_input, adds support for fieldsets ([100bd412](https://github.com/Elgg/Elgg/commit/100bd412ca00a1369873c7a33fb6d63f21e2f656))
  * update login form to use new forms API ([ef69171c](https://github.com/Elgg/Elgg/commit/ef69171c7acbeffa0d9419440082eb23c9240f10))
  * update registration form to use new forms API ([5eb8ce25](https://github.com/Elgg/Elgg/commit/5eb8ce2536a8e4782ba4555226b6feaee7950ba8))
  * adds input/number view for numeric values input fields ([b7960635](https://github.com/Elgg/Elgg/commit/b7960635089bb85da0fb1cae5bb539f89a42439b))
  * makes form views extendable by deferring footer rendering ([bbb392e0](https://github.com/Elgg/Elgg/commit/bbb392e0eea27731324fd7223e4dff5739e41703))
* **groups:** break down groups/all page in smaller views ([c6de14c2](https://github.com/Elgg/Elgg/commit/c6de14c2f974f85147b06bc0f085fcc492c6bed2))
* **http:**
  * no longer sends HTTP headers to CLI requests ([d95a5101](https://github.com/Elgg/Elgg/commit/d95a5101793c3d73cfb1f37928316e8d27340fa1))
  * now triggers before and after events for HTTP responses ([42839af3](https://github.com/Elgg/Elgg/commit/42839af35f5c2b574bad430d75d7c5b888943a45))
  * adds API for handling HTTP responses ([bfc860c8](https://github.com/Elgg/Elgg/commit/bfc860c81622350e3c8f7fe7863b18aeaeab34c9))
  * adds a service for signing and validating URLs ([15071018](https://github.com/Elgg/Elgg/commit/150710184b27990dbce31d4d37c87029fff7a6d1), closes [#9884](https://github.com/Elgg/Elgg/issues/9884))
* **images:** adds a new image manipulation service ([9dcd7fb2](https://github.com/Elgg/Elgg/commit/9dcd7fb26397fefdff175cf5c82fa23b526e501d))
* **js:** add support for inline popup modules ([e467a755](https://github.com/Elgg/Elgg/commit/e467a755123d9fe81b33bb86100b9d4af604d84d))
* **lists:** list item views are now aware of their position in the list ([9dab204b](https://github.com/Elgg/Elgg/commit/9dab204b5bbdb6b0df0c627fe237f9624456baab))
* **menus:**
  * elgg_view_menu() can now render menus with custom views ([1cd65c60](https://github.com/Elgg/Elgg/commit/1cd65c60d7b793c899f244c6c3a370c5c6721a99))
  * elgg_view_menu() now accepts an array of menu items ([7a8dad2b](https://github.com/Elgg/Elgg/commit/7a8dad2b0011fba629755ab878c0990f5ce60232))
* **notifications:**
  * refactor notification system for improved usability ([11dd562c](https://github.com/Elgg/Elgg/commit/11dd562cd625ffca797563b4c2b411537e66681a))
  * make it easier to alter core instant notifications ([094d63b2](https://github.com/Elgg/Elgg/commit/094d63b2b32d7166663242f61e656910da4f1f63))
* **passwords:** strengthen change password link with a HMAC signature ([6ad8ff94](https://github.com/Elgg/Elgg/commit/6ad8ff9431c97838ac6aa3aa30e923dbedda8a13))
* **php:** Require PHP 5.6+ ([e35f3ed0](https://github.com/Elgg/Elgg/commit/e35f3ed044100d72dfb3f942aaaead376e7c09dc))
* **plugins:** adds static config file for plugins ([8bf14546](https://github.com/Elgg/Elgg/commit/8bf1454698f23ef73f9645880deb294280b158ef), closes [#5947](https://github.com/Elgg/Elgg/issues/5947))
* **profile:** profile fields can contain more than 250 characters ([2b6a7497](https://github.com/Elgg/Elgg/commit/2b6a7497e1e3a6b63ea5ab884d86f295989e0a90))
* **river:** Adds hook-based permissions for river item delete action ([364d7e94](https://github.com/Elgg/Elgg/commit/364d7e94915c997892ce2a25f4737a174814541e), closes [#8936](https://github.com/Elgg/Elgg/issues/8936))
* **tests:** make it easier to bootstrap PHPUnit ([c3ea0173](https://github.com/Elgg/Elgg/commit/c3ea01730b98fbe15809903c5452822652eb2c84))
* **users:** unifies login and registration URL generation ([9e499f6a](https://github.com/Elgg/Elgg/commit/9e499f6a321aedbb9c2cfb7fa2c2d402bf96b52a), closes [#9896](https://github.com/Elgg/Elgg/issues/9896))
* **uservalidationbyemail:** validation URLs are now signed with a HMAC key ([111f72d8](https://github.com/Elgg/Elgg/commit/111f72d8b68e72fe453fa1f0b2cbdcd5dd116e76))
* **view:** function to get the extensions for a view ([a0f39b3e](https://github.com/Elgg/Elgg/commit/a0f39b3ede113edd5198061e0e11dd5d1d6f4707), closes [#9921](https://github.com/Elgg/Elgg/issues/9921))
* **views:**
  * added elgg_parse_emails to output/longtext ([c1a600ca](https://github.com/Elgg/Elgg/commit/c1a600ca026b2350eaa05bd1f58e1d9a2e197381), closes [#7052](https://github.com/Elgg/Elgg/issues/7052))
  * more flexible output/longtext view ([6229f811](https://github.com/Elgg/Elgg/commit/6229f811fca4ce6e404b534f3c95e2f30dc268bf))
  * lists can be rendered as tables ([d941fa83](https://github.com/Elgg/Elgg/commit/d941fa838aaced7c81f59849fd77b783d9c965df), closes [#7684](https://github.com/Elgg/Elgg/issues/7684), [#9629](https://github.com/Elgg/Elgg/issues/9629))
  * adds function for extracting $vars['class'] more cleanly ([b0dab038](https://github.com/Elgg/Elgg/commit/b0dab038cd65dedc8239c84f53a97aa91269486c))
  * object summary listing now accepts an icon ([09649f57](https://github.com/Elgg/Elgg/commit/09649f576d111b5668819ee6c89cc2baeb61b149))
  * image block wrapper attributes can now be passed with $vars ([8f6a5753](https://github.com/Elgg/Elgg/commit/8f6a5753034aa6ce1e033698b2d14fc7985ffce7))
  * improves usability of object listing views ([8ae5b1da](https://github.com/Elgg/Elgg/commit/8ae5b1da98ec417122d7c70cd18bee51a06892df))
* **walledgarden:** convert walled garden JS to AMD ([890b4a77](https://github.com/Elgg/Elgg/commit/890b4a770cf70033b4571778df5ff4412684b38c))
* **widgets:** added a generic view for selecting 'number to display' ([b845343f](https://github.com/Elgg/Elgg/commit/b845343f31171b2a032db5ab79e0a159bdef8264))


#### Performance

* **db:** no longer queries DB when entity access is predictable ([5c93f07d](https://github.com/Elgg/Elgg/commit/5c93f07d030dc820b7ed99014118ede411414994))


#### Documentation

* **core:** Misc docs fixes ([0c8e1acc](https://github.com/Elgg/Elgg/commit/0c8e1acc82927f92679c3e86ff4fa68f85a371da))
* **tutorials:** updated Blog tutorial ([9a813e86](https://github.com/Elgg/Elgg/commit/9a813e86bd9e17a476d865a965222161d4c839d7))


#### Bug Fixes

* **cli:** Application::run() returns a value for PHP CLI server to serve static files ([a4fa2749](https://github.com/Elgg/Elgg/commit/a4fa2749af496af855301f56d63d396021297b85))
* **comments:** comment redirector URL no longer contain double fragments ([37f578e4](https://github.com/Elgg/Elgg/commit/37f578e49d0fe016f31596c539c5c8d4db98e18c))
* **discussions:** reply form is now only rendered when container permissions are satisfied ([6ac48700](https://github.com/Elgg/Elgg/commit/6ac487000b27572c825b0a44c64359aee8e5e601))
* **entities:** classnames for entity subtypes can be up to 255 chars ([45d7abbd](https://github.com/Elgg/Elgg/commit/45d7abbdb6d060d96e05794fcf579f7dd6803146), closes [#6802](https://github.com/Elgg/Elgg/issues/6802))
* **icons:** cropping mode is now determined by actual cropping coords ([5e4742e8](https://github.com/Elgg/Elgg/commit/5e4742e842be4369575ad0713a635c64729b2fd6))
* **output:** switch to Misd\Linkify library for parsing urls in text ([e2baa855](https://github.com/Elgg/Elgg/commit/e2baa855f9a9512ba3846cc77eb4d86a27cff1d1))
* **pages:** do not show duplicate title on full view of a page ([a049586a](https://github.com/Elgg/Elgg/commit/a049586addd84687c6de9eb0cfa14817a51c298e))


#### Deprecations

* **events:** deprecates the `pagesetup, system` event ([cf77fc07](https://github.com/Elgg/Elgg/commit/cf77fc07806c601d40508fbaf43f6d14b8dceeee))
* **metadata:** metadata access control is deprecated ([a9523d97](https://github.com/Elgg/Elgg/commit/a9523d979431016352a424fd3580ffad717c4d6b))


<a name="2.2.4"></a>
### 2.2.4  (2017-01-27)

#### Contributors

* Steve Clay (2)
* Ismayil Khayredinov (1)
* iionly (1)

#### Bug Fixes

* **ajax:** elgg/Ajax view() and form() set $vars as expected ([abf8a9ce](https://github.com/Elgg/Elgg/commit/abf8a9ce87117ab24cb62e937805750eca780de1), closes [#10667](https://github.com/Elgg/Elgg/issues/10667))
* **core:** Check existence of cache symlink without usage of readlink() ([3e4dc6a1](https://github.com/Elgg/Elgg/commit/3e4dc6a1f2e2b20c5e31800e925ca5779a6f40cf))
* **files:** mitigate issues with special chars in file names ([4a7b74ea](https://github.com/Elgg/Elgg/commit/4a7b74ea27b31be159fba9fb5c3dda405da15409))
* **web_services:** handle string params with proper escaping ([702ce46c](https://github.com/Elgg/Elgg/commit/702ce46c44aec2546f953902061166bf3f48a5af))


<a name="2.2.3"></a>
### 2.2.3  (2016-11-08)

#### Contributors

* Jerôme Bakker (5)
* Steve Clay (4)
* Ismayil Khayredinov (1)
* Jeroen Dalsem (1)
* jdalsem (1)

#### Bug Fixes

* **blog:** correctly check if owner is a group in owner_block menu ([7f253c58](https://github.com/Elgg/Elgg/commit/7f253c5861d1e34a9d170c435dbc701941115c65))
* **cache:** ElggFileCache now handles arbitrary cache keys ([e60b8368](https://github.com/Elgg/Elgg/commit/e60b83683a2acfb61e4e66ce901d4b02e3ce54fa))
* **ckeditor:** ensure basepath is set before CKeditor is loaded ([d60389d2](https://github.com/Elgg/Elgg/commit/d60389d25c033addbb18faf1cd1d0eb43b5b6d7f), closes [#10304](https://github.com/Elgg/Elgg/issues/10304))
* **composer:** composer post-update script no longer crashes ([be4235a0](https://github.com/Elgg/Elgg/commit/be4235a0a75d6bbb8f95f823dd5eff52ed27c2d6))
* **groups:** multiple membership requests don't trigger messages ([287e6448](https://github.com/Elgg/Elgg/commit/287e64489a8a343fabbc6cf6e12dba86ea7a51b7))
* **js:** bind to correct element for inline comment edit ([e15cba9d](https://github.com/Elgg/Elgg/commit/e15cba9db569d7f3b0cc5e8982757cd04b67942d))
* **likes:**
  * notification subject too long ([fc5667dc](https://github.com/Elgg/Elgg/commit/fc5667dcf83887b6a35d45bac91512ca58999843))
  * check for a valid entity in menu setup ([9ae99e84](https://github.com/Elgg/Elgg/commit/9ae99e84f31be25d339d84393000a05527fb377b))
* **profile:** allow admin menu items to be toggled ([ba20ce42](https://github.com/Elgg/Elgg/commit/ba20ce42168edeb72e67694cf88881d558470539))
* **reportedcontent:** show spinner during ajax delete/archive ([5de1c90a](https://github.com/Elgg/Elgg/commit/5de1c90a7b9bd3a0ec0a52da8cb42f9e46abbb7a))


<a name="2.2.2"></a>
### 2.2.2  (2016-10-02)

#### Contributors

* Jerôme Bakker (4)
* Ismayil Khayredinov (2)
* Juho Jaakkola (2)
* Steve Clay (2)
* Jeroen Dalsem (1)
* iionly (1)

#### Documentation

* **events:** prefered use of the shutdown event vs shutdown function ([c62b307d](https://github.com/Elgg/Elgg/commit/c62b307d4898f4774592d52922560fd3380f8a8b))
* **install:** warn composer users they have to "install" twice ([7c8fd239](https://github.com/Elgg/Elgg/commit/7c8fd23982c2b013810c7d1476d53526cfa4cc18))


#### Bug Fixes

* **css:** apply hidden class to menu items ([5281199b](https://github.com/Elgg/Elgg/commit/5281199b852f930b23466b4d211c21874a9bc567))
* **i18n:** validate the translation key ([76d7ac69](https://github.com/Elgg/Elgg/commit/76d7ac69b5932f526dca1b02fb2882701369f118))
* **mysql:** adds MySQL 5.7 compatibility ([3198d84a](https://github.com/Elgg/Elgg/commit/3198d84a5d000a48388bc39e0c08bdc2b1e63ee5), closes [#8121](https://github.com/Elgg/Elgg/issues/8121))
* **views:** no results listing output should show if empty item views ([a3d4f8c8](https://github.com/Elgg/Elgg/commit/a3d4f8c81132cd34b8152d08b18d1bcd6cd2a2e3))


<a name="2.2.1"></a>
### 2.2.1  (2016-09-21)

#### Contributors

* Steve Clay (16)
* iionly (5)
* Ismayil Khayredinov (2)
* Wouter van Os (1)

#### Documentation

* **license:** clarifies dual licensing in LICENSE.txt ([1db4994f](https://github.com/Elgg/Elgg/commit/1db4994f8bab11898153c4c9e6e2898c0549714f))
* **support:** updates support policy and tentative release schedule ([71aab2c6](https://github.com/Elgg/Elgg/commit/71aab2c65c36265af2309954018806b91b40ecd8))


#### Bug Fixes

* **access:** updates no longer mistakenly blocked in some scenarios ([01f4f1df](https://github.com/Elgg/Elgg/commit/01f4f1df12d95c9828c5e7581b5352c7953e8109))
* **boot:**
  * boot cache now respects system cache setting ([f90b1eb1](https://github.com/Elgg/Elgg/commit/f90b1eb162968077be9d125437a8aa2e77e129ae))
  * make sure boot cache updated when subtype data changes ([c80f6e64](https://github.com/Elgg/Elgg/commit/c80f6e64701320fdc83904719ca4bb2305079a5c))
* **core:** boot no longer throws DB exception in some edge cases ([c7c44763](https://github.com/Elgg/Elgg/commit/c7c44763a58b41440fd5d1f321a511468018220f), closes [#10119](https://github.com/Elgg/Elgg/issues/10119))
* **discussions:** removes site "Discussions" menu item added in 2.2.0 ([34678299](https://github.com/Elgg/Elgg/commit/346782993fbb3e5f6e5c56a6b16038cc4f6de275), closes [#9731](https://github.com/Elgg/Elgg/issues/9731))
* **js:**
  * output deprecation messages to admins in browser console only ([a8052f9c](https://github.com/Elgg/Elgg/commit/a8052f9cd5e2cbc120a6d5b7b3664049d569011e))
  * popup no longer reopens after a second click on the trigger ([6dc8012b](https://github.com/Elgg/Elgg/commit/6dc8012b9bb4ca2c374b67f9cc88582bbd832a10), closes [#10063](https://github.com/Elgg/Elgg/issues/10063))
* **likes:** don't emit notice if a listing's `$vars['list_class']` isn't set ([f2882158](https://github.com/Elgg/Elgg/commit/f2882158eed56c42c938b69d7e7caa53485284a5))
* **members:** Don't rely on newest members tab set as default tab in pagehandler for members page ([a78aa354](https://github.com/Elgg/Elgg/commit/a78aa354ffa79f9f8fb90ad8aa3cccdf4554b034))
* **pages:** operations keep track of more than 10 child pages ([bc5f414b](https://github.com/Elgg/Elgg/commit/bc5f414bf597fe440ca096333fafea330746a323))
* **plugins:** Make activate/deactivate all plugins to work also on Firefox ([915865b9](https://github.com/Elgg/Elgg/commit/915865b9009bdbf9b47307ee908883874957cf0b))
* **reportedcontent:** Reported Content admin widget works again ([739259fc](https://github.com/Elgg/Elgg/commit/739259fcb52e7400d1156635345067d381f36b6d), closes [#10151](https://github.com/Elgg/Elgg/issues/10151))
* **river:** ensure unique comment form id ([80e508ae](https://github.com/Elgg/Elgg/commit/80e508ae1dc85db9aebb2b32dab904f1ea2b2674))
* **ui:** hover menus no longer open outside viewport ([edd3740a](https://github.com/Elgg/Elgg/commit/edd3740a7203b8c287ae76b44fae1ec3e535fca1), closes [#10214](https://github.com/Elgg/Elgg/issues/10214))
* **views:**
  * input/select view can select options more reliably ([af103c7e](https://github.com/Elgg/Elgg/commit/af103c7ec700c2d6ca39f29e9ff57f71ef08447c), closes [#10154](https://github.com/Elgg/Elgg/issues/10154))
  * some functions that use views fallback to default viewtype ([5a58317e](https://github.com/Elgg/Elgg/commit/5a58317eeee52e2a2e49df1a2f987c163ec25f93), closes [#10114](https://github.com/Elgg/Elgg/issues/10114))
* **web_services:** create_api_user() and create_user_token() work again ([1ee8fe96](https://github.com/Elgg/Elgg/commit/1ee8fe9613435a637e2460b20e6b591541ae4039))


<a name="2.2.0"></a>
## 2.2.0  (2016-08-05)

#### Contributors

* Juho Jaakkola (3)
* Steve Clay (3)
* jdalsem (2)

#### Features

* **iconservice:** it is possible to save unaltered version of an image ([7157a33f](https://github.com/Elgg/Elgg/commit/7157a33f647a937191c6961d960b4e76d325edd4), closes [#9970](https://github.com/Elgg/Elgg/issues/9970))


#### Bug Fixes

* **js:**
  * add missing elgg/lightbox#resize method ([4f6a0174](https://github.com/Elgg/Elgg/commit/4f6a0174779abc11c6d92e54a107899aa30ef5a3))
  * correctly report success in admin profile field reorder action ([b63396a7](https://github.com/Elgg/Elgg/commit/b63396a7f1ab572925e35b3fb5bb23e3f98e1e3e))


<a name="2.2.0-rc.1"></a>
### 2.2.0-rc.1  (2016-06-16)

#### Contributors

* Ismayil Khayredinov (43)
* Steve Clay (37)
* Jeroen Dalsem (22)
* jdalsem (6)
* Wouter van Os (2)
* Brett Profitt (1)
* Jerôme Bakker (1)
* V. Lehkonen (1)
* lehkonev (1)

#### Features

* **ajax:**
  * better elgg/Ajax handling of form data and URLs ([8795b9f4](https://github.com/Elgg/Elgg/commit/8795b9f43893842f3ecc6ab4e323c8a5bd5be00a), closes [#9534](https://github.com/Elgg/Elgg/issues/9534), [#9564](https://github.com/Elgg/Elgg/issues/9564))
  * Ajax service now loads required AMD modules ([292dc391](https://github.com/Elgg/Elgg/commit/292dc391355b9fc6c5116f54b2d8ce20770469af))
* **avatar:** user avatars are now served by serve-file handler ([a55d746a](https://github.com/Elgg/Elgg/commit/a55d746acd2b81c0bb36e32b147ebc97b6ef5cd6))
* **cache:**
  * allow admin to attempt an automatic symlink to cache ([b06a1cb3](https://github.com/Elgg/Elgg/commit/b06a1cb3e799d3863126bc279d202e25ed96aea9), closes [#8639](https://github.com/Elgg/Elgg/issues/8639), [#8638](https://github.com/Elgg/Elgg/issues/8638))
  * allows specifying cache directory in settings.php ([4b2ed514](https://github.com/Elgg/Elgg/commit/4b2ed514e20c7da8c6a1e40a3886c6671c9b62ef))
* **ckeditor:**
  * improved elgg/ckeditor AMD module ([a0ff70ec](https://github.com/Elgg/Elgg/commit/a0ff70ec3af2c784054e1e2f328c4504458b8978))
  * added editor autogrow plugin ([771abac8](https://github.com/Elgg/Elgg/commit/771abac8a2130a83629fa3be8ba16161c36fc4fa))
  * allowed resizing of editor window ([f43a6565](https://github.com/Elgg/Elgg/commit/f43a65658f44d7ee9b209b97e8da888846223ff9))
* **core:**
  * added a CONFIG flag to control auto-disabling plugins ([17363a50](https://github.com/Elgg/Elgg/commit/17363a50baf32ed0699673356e89e439eff961f0))
  * added a new function to check if system_cache is enabled ([f3bbff32](https://github.com/Elgg/Elgg/commit/f3bbff32ef8c0620ebb9ccd93b82504e8a1f6634))
* **cron:** improved cron logging ([5305b60d](https://github.com/Elgg/Elgg/commit/5305b60dadceb9056b684d7359ffd389b4d50f8b), closes [#9474](https://github.com/Elgg/Elgg/issues/9474))
* **db:**
  * access sql parts are named in the clauses array ([50ffcf24](https://github.com/Elgg/Elgg/commit/50ffcf249cf6da2bd0b07a35b06e15c986c1e80d))
  * allows using parameterized queries in core DB functions ([a9e51682](https://github.com/Elgg/Elgg/commit/a9e516826f8ee8bb763e12b3451aee96436ae985))
* **developers:** add view_vars hook to views inspector ([41e9e1ef](https://github.com/Elgg/Elgg/commit/41e9e1ef9462dedae00b49079ec5b3b4619cfb75))
* **discussions:**
  * added a site menu item for discussion/all ([79809b78](https://github.com/Elgg/Elgg/commit/79809b785b5ee5defa2184c7debd24d580e0e234))
  * allow plugins to use custom discussion reply object class ([ac55f8f4](https://github.com/Elgg/Elgg/commit/ac55f8f48cbc17c45db7ebab52e2c95c7594022c))
* **embed:**
  * adds elgg/embed AMD module ([1f1dad12](https://github.com/Elgg/Elgg/commit/1f1dad12c0ce30ef426893802f46dd1c5f5114e1))
  * adds serve-icon page handler ([e4d09f82](https://github.com/Elgg/Elgg/commit/e4d09f82e5d3a1029864c35a90668dbe5ff596a8), closes [#9582](https://github.com/Elgg/Elgg/issues/9582))
* **entities:** adds user capabilities service ([81f05058](https://github.com/Elgg/Elgg/commit/81f05058223df1657bbd87474d8a68906573ea6c))
* **file:**
  * adds ElggFile::transfer() for reliable renaming of files ([bf50c5d0](https://github.com/Elgg/Elgg/commit/bf50c5d0d1ff5151e87c38438f312e25409bec1e))
  * more consistency in mime and simple type values ([3e09fa15](https://github.com/Elgg/Elgg/commit/3e09fa15865360335ef4d3fde002d7684a582c28), closes [#9614](https://github.com/Elgg/Elgg/issues/9614))
* **files:** update file plugin to new file serving API ([a9d409ee](https://github.com/Elgg/Elgg/commit/a9d409eeb7fdd9c651bde31575cc37d05db86985))
* **filestore:**
  * bootstrap default filestore early in the boot sequence ([c85fa0ee](https://github.com/Elgg/Elgg/commit/c85fa0ee65c04b51f65e4cd9202f8c6382afced6), closes [#9873](https://github.com/Elgg/Elgg/issues/9873))
  * adds API to reliably set file modification time ([476b6d29](https://github.com/Elgg/Elgg/commit/476b6d293c8671d1856443b6804f861dbc539406))
* **forms:**
  * Add new user now has an option to autogenerate the password ([ee4758d3](https://github.com/Elgg/Elgg/commit/ee4758d3c053e5b22827f368ebf1546dc41208a6))
  * input/checkbox is now usable with elgg_view_input() ([82bbf49b](https://github.com/Elgg/Elgg/commit/82bbf49bd9da83fbc160f5c83c241db772d1ae8d), closes [#9808](https://github.com/Elgg/Elgg/issues/9808))
* **gatekeeper:** entity gatekeeper result can now be filtered ([75af2fd5](https://github.com/Elgg/Elgg/commit/75af2fd54d5e5e2be629b205babc486085e685df))
* **groups:**
  * group icons are now handled by the new icon service ([e809f5fd](https://github.com/Elgg/Elgg/commit/e809f5fde978dcb790dc7e81d3341e54fc4b5533))
  * introduced a hook to influence group tool options ([b6617e5e](https://github.com/Elgg/Elgg/commit/b6617e5ea33609ae32dfd9f9af404b42e85609dd))
  * allow the group river to be filtered by content type ([0d8f9364](https://github.com/Elgg/Elgg/commit/0d8f93641ec69b13b81902dee6645cf4fbf8fce1))
  * group avatars now use serve-file handler ([ac57e990](https://github.com/Elgg/Elgg/commit/ac57e99002a1c1490b7dd2f348845cbf7653ed8b))
* **html:**
  * allows cleaner elgg_format_element usage ([425f57d7](https://github.com/Elgg/Elgg/commit/425f57d7d2f49c06631ca0300ba77d1f101fe211), closes [#9766](https://github.com/Elgg/Elgg/issues/9766))
  * moves favicon registration to a hook ([a4a35362](https://github.com/Elgg/Elgg/commit/a4a35362eefbfa8449648b9834f29b9da0a7703c))
* **http:** allow use of X-Sendfile/X-Accel web server feature ([a88db207](https://github.com/Elgg/Elgg/commit/a88db207aa39351eb26855fe9125cdaf287a1e03), closes [#4898](https://github.com/Elgg/Elgg/issues/4898))
* **icons:**
  * udpate file plugin to use new icon service ([2c9f5c0a](https://github.com/Elgg/Elgg/commit/2c9f5c0a47bf81b6291384145b5fe01ff7e17b72))
  * user avatars are now handled by the icon service ([36c8b465](https://github.com/Elgg/Elgg/commit/36c8b465e0f8c39a1f83f74fb9cf3562d1467c17))
  * adds a service for handling entity icons ([72b8a2c7](https://github.com/Elgg/Elgg/commit/72b8a2c78d997277f24e57db834f96f6fd779e92))
* **js:**
  * Adds hooks to pass site and page-level data client-side ([cec6b42b](https://github.com/Elgg/Elgg/commit/cec6b42b2ec6b86bc3530f6233b7d8dfb8c05328), closes [#8997](https://github.com/Elgg/Elgg/issues/8997))
  * elgg/Ajax users get more access to underlying resources ([39a3fbce](https://github.com/Elgg/Elgg/commit/39a3fbced333dc093e45a655d8d417b359ce92c4), closes [#9767](https://github.com/Elgg/Elgg/issues/9767))
  * elgg/spinner now supports optional text to be displayed ([da5c5b06](https://github.com/Elgg/Elgg/commit/da5c5b06cb665ddec7ab10802dd5c499f61474a6))
  * adds elgg/lightbox AMD module, loaded on all pages ([9135ad26](https://github.com/Elgg/Elgg/commit/9135ad26633b76eab3e8a642b14a34487c539a7e), closes [#7895](https://github.com/Elgg/Elgg/issues/7895), [#8309](https://github.com/Elgg/Elgg/issues/8309), [#6991](https://github.com/Elgg/Elgg/issues/6991))
  * user hover menu now uses elgg/popup module ([d0dffca6](https://github.com/Elgg/Elgg/commit/d0dffca6f44009012b5ce3371e4a5a125a5f5344))
  * adds elgg/popup AMD module ([fd75da60](https://github.com/Elgg/Elgg/commit/fd75da601ce6aaac4853ba4406f6141e1d17917c))
  * requiresConfirmation now returns false if not confirmed ([cac5c0fd](https://github.com/Elgg/Elgg/commit/cac5c0fd1fa6b8dd5861cbcf7e76efeb6a855714))
* **menus:**
  * elgg_register_title_button() can now check entity type and subtype ([a0c118ad](https://github.com/Elgg/Elgg/commit/a0c118ad46f3038a027c1d56313b9369c5ec6ae8))
  * required AMD modules can now be defined at item registration ([46c3ead8](https://github.com/Elgg/Elgg/commit/46c3ead835d728b7afec70fc6c0aa01d152e4ea1))
  * adds menu service for more orderly menu construction ([38ecfc6b](https://github.com/Elgg/Elgg/commit/38ecfc6ba9b3aa431e9b553651e26c3dd10bbc18), closes [#9508](https://github.com/Elgg/Elgg/issues/9508))
* **reportedcontent:** only load javascript when needed ([29c39cd7](https://github.com/Elgg/Elgg/commit/29c39cd79b13abc6787e805f83747e035f981b56))
* **river:** convert river JS to AMD modules ([790a1a00](https://github.com/Elgg/Elgg/commit/790a1a008eda55424d857c779ef475c85e59512c))
* **thewire:** allow multiple add forms to exist on the same page ([9f72e287](https://github.com/Elgg/Elgg/commit/9f72e2874912d50c917b466a8a4ac2f512f69aa2))
* **ui:** Allows modifying system messages/errors ([eee183c5](https://github.com/Elgg/Elgg/commit/eee183c549320eff63406cfa825679b7260b2653))
* **views:**
  * view_vars handlers can preset view output ([68fde7b6](https://github.com/Elgg/Elgg/commit/68fde7b6f0002afe01080adda1f7f8699a9cf903))
  * elgg_get_excerpt output now comes from a view ([4d6ec3f2](https://github.com/Elgg/Elgg/commit/4d6ec3f272ebed3ebf6abe2e0439e596e69fc6e1))
  * allows changing relative URLs in CSS files ([70d3aab7](https://github.com/Elgg/Elgg/commit/70d3aab719ade13804428b7b90bd848a28891f91))
  * allow multiple paths in views.php files ([7672d754](https://github.com/Elgg/Elgg/commit/7672d7542f322322b5a4868a8af4e61e8a9a702b))
* **web_services:** allows API function to be given an associative array ([cd80863a](https://github.com/Elgg/Elgg/commit/cd80863a5e91e4184080bdf451c26ee10058febd), closes [#9411](https://github.com/Elgg/Elgg/issues/9411))
* **widgets:**
  * widget types can now be extended with a hook ([3c76194c](https://github.com/Elgg/Elgg/commit/3c76194c02274c09a3097168280950e7d6699456))
  * widget title and description can be autodetected ([3c61e2f0](https://github.com/Elgg/Elgg/commit/3c61e2f0b2b21cac8ab538e5ede76f647f4217ed))
  * added a helper class and factory for defining widgets ([bc56fafd](https://github.com/Elgg/Elgg/commit/bc56fafd81c362964ac0764ec4641023d06560aa))
  * widget layout owner can now be set explicitly ([b3bd2a84](https://github.com/Elgg/Elgg/commit/b3bd2a84e44c0e1f4aff96eb5bcdc5b7b1186417), closes [#7023](https://github.com/Elgg/Elgg/issues/7023))
  * added isset on \ElggWidget objects to check settings ([7b095208](https://github.com/Elgg/Elgg/commit/7b0952088d94f2132917f2b3cdd9f328726595be))
  * added unset on \ElggWidget objects to remove settings ([f99e4f5d](https://github.com/Elgg/Elgg/commit/f99e4f5debea861aede1e3009b11f07f917b0b06))


#### Performance

* **db:** improved session write db query for InnoDB ([3b55226d](https://github.com/Elgg/Elgg/commit/3b55226d43d0feaea75488d19c8b8e8a6fd1d941))
* **reportedcontent:** only load JS if menu item is rendered ([ececa98d](https://github.com/Elgg/Elgg/commit/ececa98d3520483d0d4f956f0461e8d30d2db3f2))


#### Documentation

* **core:** fixes docs for ElggFilestore::seek return value ([fe310c31](https://github.com/Elgg/Elgg/commit/fe310c313aba11e0439be9aaf46a5030c0b4c031))
* **faqs:** fixed typo in IDE section ([a1ed1305](https://github.com/Elgg/Elgg/commit/a1ed130584e65535f47e037bfd9ff7bbf833963b))
* **tutorials:** updated Hello world ([dc5a4ade](https://github.com/Elgg/Elgg/commit/dc5a4ade129f2ef2c7c8defae23e5498946078bf), closes [#9875](https://github.com/Elgg/Elgg/issues/9875))
* **widgets:** updated the widget registration documentation ([3410e1ec](https://github.com/Elgg/Elgg/commit/3410e1ec4f20d0a8dfbf49595b2bf865b76ba5d1))


#### Bug Fixes

* **avatars:** avatars are no longer served with public URLs in a walled garden mode ([4c8a7ced](https://github.com/Elgg/Elgg/commit/4c8a7ced7ec56637dd836f2854df9f276893b876))
* **core:**
  * get class from subclass instead of base ([8b3e17fa](https://github.com/Elgg/Elgg/commit/8b3e17facef15a0349401695408c7b0faf3ed594))
  * allows ElggFile to append files not yet existing ([ac0ba3f2](https://github.com/Elgg/Elgg/commit/ac0ba3f214a24c10255aa3836e11d31a310e3089))
* **file:** ElggFile::delete() now removes target files if filename is a symlink ([facc13fe](https://github.com/Elgg/Elgg/commit/facc13fe13f8719204c85b7f7334acfa5e8f06da))
* **files:** use actual file modification time as an etag value ([17c5dcaf](https://github.com/Elgg/Elgg/commit/17c5dcaf2d505b20913b1689d22906394896e682))
* **js:** ui bindings now wait for system init event to fire ([5794e027](https://github.com/Elgg/Elgg/commit/5794e0275629e14830e6c464460056c4bc6f5bd3))
* **mime:** fall back to detection based on extension for octet-stream ([0b1f4539](https://github.com/Elgg/Elgg/commit/0b1f45396aa0f147a7ae6b957d61fbd2854f5d30))
* **reportedcontent:** forward to address if not submitted in lightbox ([ee63b1d8](https://github.com/Elgg/Elgg/commit/ee63b1d8ea94cdd26f43591cc01fd00747efb9d9))
* **views:** elgg_view_form now accepts class to be an array in form_vars ([4133b516](https://github.com/Elgg/Elgg/commit/4133b51657971a22af6a29f32adfdd69fd533dc5))


#### Deprecations

* **db:** deprecates many methods on the `Application::getDb` object ([2ba9a876](https://github.com/Elgg/Elgg/commit/2ba9a8761875fc1061d74ec699ff79ce4c4df95a))
* **entities:**
  * adds entityCache service and deprecates old global ([9fa45b62](https://github.com/Elgg/Elgg/commit/9fa45b62a03c3b4ec282042ca5cdbebe5ee0f451))
  * deprecate can_write_to_container ([ee473b37](https://github.com/Elgg/Elgg/commit/ee473b371949b475e2cdafbbe53cb93965a22735))
* **file:** new file service deprecates file download and thumbnail handlers ([90925fab](https://github.com/Elgg/Elgg/commit/90925fab4f6e67750e5f21eeb52ecf67711c2a95))
* **groups:**
  * groups/js view deprecated by groups/navigation AMD module ([975014bb](https://github.com/Elgg/Elgg/commit/975014bb1e799be93c04ef493edba8d3649e64ef))
  * new file service deprecated avatar/view resource ([5c535271](https://github.com/Elgg/Elgg/commit/5c535271a8d89b6d012541657c2c3cd091585873))
  * new file service deprecated groupicon page handler ([0721023b](https://github.com/Elgg/Elgg/commit/0721023be4e83de81492254262d5628a0dce5193))


<a name="2.1.3"></a>
### 2.1.3  (2016-08-05)

#### Contributors

* Ismayil Khayredinov (2)
* Steve Clay (1)

#### Bug Fixes

* **output:** attribute formatter now skips arrays with non-scalar values ([fbe1cd34](https://github.com/Elgg/Elgg/commit/fbe1cd3451a8ea2020118c980c1d394304e9766f), closes [#10010](https://github.com/Elgg/Elgg/issues/10010))
* **views:** issue unique IDs in elgg_view_input() ([f20f0603](https://github.com/Elgg/Elgg/commit/f20f0603de19211d4a2ae5597fc182cd83aa3a93), closes [#9955](https://github.com/Elgg/Elgg/issues/9955))


<a name="2.1.2"></a>
### 2.1.2  (2016-06-13)

#### Contributors

* Steve Clay (5)
* Ismayil Khayredinov (4)
* Brett Profitt (1)
* Jerôme Bakker (1)
* iionly (1)

#### Documentation

* **ajax:** fixes constructor usage of elgg/Ajax ([07c7ce49](https://github.com/Elgg/Elgg/commit/07c7ce49538ae8b61b3c48cd1e5a8287dc777e5b), closes [#9533](https://github.com/Elgg/Elgg/issues/9533))


#### Bug Fixes

* **core:**
  * elgg_get_plugin_setting() respects defaults for values that haven't been cached or created. ([1e141d46](https://github.com/Elgg/Elgg/commit/1e141d468c75a64bd9068908380e034765772ea6), closes [#9781](https://github.com/Elgg/Elgg/issues/9781))
  * Elgg again uses the dataroot given in settings.php ([64c23f70](https://github.com/Elgg/Elgg/commit/64c23f703b9515c30089470da2899f105de99333), closes [#9602](https://github.com/Elgg/Elgg/issues/9602))
* **errors:** nested forward 404 calls are less likely to abruptly fail ([068711fa](https://github.com/Elgg/Elgg/commit/068711fad3680f5d9d431759ed895b6a48d78076), closes [#9476](https://github.com/Elgg/Elgg/issues/9476))
* **files:** file service now sends 304 and 403 headers more reliably ([c9af1790](https://github.com/Elgg/Elgg/commit/c9af179092be61e50acc17603a8fbf3dd9e22272), closes [#9571](https://github.com/Elgg/Elgg/issues/9571))
* **js:** deprecate elgg.ui.widgets more reliably ([c25c5211](https://github.com/Elgg/Elgg/commit/c25c5211c307fc0b4c869f3e098c5002494d77cf), closes [#9523](https://github.com/Elgg/Elgg/issues/9523))
* **logger:** logger no longer pollutes serve-file response ([8209a38b](https://github.com/Elgg/Elgg/commit/8209a38b01b89bea65b696db3662962a8f332ebd), closes [#9657](https://github.com/Elgg/Elgg/issues/9657))
* **profile:** able to store more information in tag fields ([0467e3ff](https://github.com/Elgg/Elgg/commit/0467e3ffcc35e0cf88a476dfbbc15a669dbdad80))
* **reportedcontent:**
  * report form opens in lightbox ([6db794ac](https://github.com/Elgg/Elgg/commit/6db794ac689322ba07f884e30766c755a6026968))
  * clicking on reported content links again opens lightbox ([55fa9d5c](https://github.com/Elgg/Elgg/commit/55fa9d5ce2b4a589187e6186e842718e212175c1))
* **site:** allow access to serve-file handler in walled garden mode ([1a8d33a1](https://github.com/Elgg/Elgg/commit/1a8d33a16ee1dca272a5cd0861f657b799f841d7))


<a name="2.1.1"></a>
### 2.1.1  (2016-03-20)

#### Contributors

* Steve Clay (4)
* Jeroen Dalsem (2)
* iionly (1)

#### Documentation

* **contributing:** clarifies release periods and branches for PRs ([b82d1592](https://github.com/Elgg/Elgg/commit/b82d1592d0b584c4e90a6d863eaa7c9803623b5d))
* **groups:** removed discussion reference in groups manifest ([249334ef](https://github.com/Elgg/Elgg/commit/249334ef57f6bc6c1197219040cf583d0938294c))
* **release:** improves docs for release process ([96681b5b](https://github.com/Elgg/Elgg/commit/96681b5ba419ad268df8a1cdcd9860ed95b68bcc))
* **views:** added page/components/list docs to elgg_view_entity_list ([76fea973](https://github.com/Elgg/Elgg/commit/76fea973adc7d2a14033eacea898129539dc7e5a))


#### Bug Fixes

* **core:** do not implode already imploded categories array in plugin object details view ([666333cf](https://github.com/Elgg/Elgg/commit/666333cfbf8c28c571679dbc146720400d0ed995))
* **installer:** no longer redirects in loop during installation ([78d31799](https://github.com/Elgg/Elgg/commit/78d31799843c27fd89f659b38fe5a2954f78296b), closes [#9486](https://github.com/Elgg/Elgg/issues/9486))


<a name="2.1.0"></a>
## 2.1.0  (2016-03-13)

#### Contributors

* Steve Clay (40)
* Ismayil Khayredinov (25)
* Juho Jaakkola (10)
* Jeroen Dalsem (2)
* Hereward Mills (1)
* Wade Benson (1)
* Wouter van Os (1)

#### Features

* **actions:** adds a generic delete action ([4c35fe26](https://github.com/Elgg/Elgg/commit/4c35fe26b26e8f76919b4ac08e9b0246c047497e))
* **ajax:**
  * improves the elgg/Ajax API and adds docs ([4211155e](https://github.com/Elgg/Elgg/commit/4211155eb223fdd3bc67534377757453ba2de398), closes [#9404](https://github.com/Elgg/Elgg/issues/9404))
  * Adds a new elgg/Ajax AMD module with unified API ([2a132ae8](https://github.com/Elgg/Elgg/commit/2a132ae87749f1aec8f9f78d7106cee982e7cce9), closes [#8323](https://github.com/Elgg/Elgg/issues/8323))
* **cron:** allows for a more systematic way of calling cron using one url ([3c947fc1](https://github.com/Elgg/Elgg/commit/3c947fc1621a9f6b0dbe0fb3176b05354e415cd4))
* **discussions:** makes "last reply" text into a link ([9c1d543a](https://github.com/Elgg/Elgg/commit/9c1d543ae865f5958e9705971dd7a2fe1735c9ba))
* **engine:**
  * use elgg_log prior to error_log in custom error handler ([6b483b08](https://github.com/Elgg/Elgg/commit/6b483b081f12f989c9053e80435b2c2df10e2fa5))
  * also log to php error_log when log is shown on screen ([9f630e58](https://github.com/Elgg/Elgg/commit/9f630e58b948ea981ae4bf8893818a343518db2d))
* **entities:** give access to original values of modified attributes ([56ddabbc](https://github.com/Elgg/Elgg/commit/56ddabbcbd450f6726ae23840f3c7a22bf86fafe), closes [#9187](https://github.com/Elgg/Elgg/issues/9187))
* **files:** adds a service for serving files from filestore ([1d6b23c7](https://github.com/Elgg/Elgg/commit/1d6b23c704495603f862d4189fd135992cc71f32))
* **forms:**
  * moves datepicker init to AMD and improves dev usability ([15c2686b](https://github.com/Elgg/Elgg/commit/15c2686b846a82d4a634bcba4e3b7adf99d057b1))
  * elgg_view_input() can now be used to render hidden inputs ([8d996cd1](https://github.com/Elgg/Elgg/commit/8d996cd115e47211e1f631b77f3f269f8fc2674a))
  * allow custom required indicators for field labels ([f29fbb6f](https://github.com/Elgg/Elgg/commit/f29fbb6fb759ac4eb4c353eb3ad335c6655be607))
  * adds elgg_view_input() to the views api ([70b35bd7](https://github.com/Elgg/Elgg/commit/70b35bd731810f15330ae51cff5bffb940ba5601), closes [#6356](https://github.com/Elgg/Elgg/issues/6356))
* **groups:** profile buttons can now be filtered with a hook ([52e82943](https://github.com/Elgg/Elgg/commit/52e82943f64a2dd0883866f0e5ec97230990f319))
* **js:**
  * elgg.ui.toggle now triggers jQuery event ([941b49ad](https://github.com/Elgg/Elgg/commit/941b49adabbe846f83c1fd259baded8d9a19d2aa))
  * adds plugin boot modules and modules based on system events ([924355a7](https://github.com/Elgg/Elgg/commit/924355a7e52c359f430ff1be04ec968286c64480), closes [#7131](https://github.com/Elgg/Elgg/issues/7131), [#7926](https://github.com/Elgg/Elgg/issues/7926))
  * Allow canceling a previous elgg_require_js() call ([375be5ff](https://github.com/Elgg/Elgg/commit/375be5ffddfb9dc598d3d15bf1d6069f2eb88c8c), closes [#9074](https://github.com/Elgg/Elgg/issues/9074))
* **menus:** delete menu item now checks if delete action exists ([84cbb151](https://github.com/Elgg/Elgg/commit/84cbb1518e021cc7d17aee4bc83c30b2d10edfe0))
* **metastrings:** add function to get map of strings to metastring IDs ([8d28a8dd](https://github.com/Elgg/Elgg/commit/8d28a8dd4eacfc427d925166885fa68081f42b4b))
* **notifications:** it's now easier to alter translations for notifications ([4677d482](https://github.com/Elgg/Elgg/commit/4677d482864095ae59b98b191a9a95d659af5ba1))
* **profiler:** allow capture/display of crude profiling data ([6ce01fad](https://github.com/Elgg/Elgg/commit/6ce01fadd8993eb1f834344cc9c2d5aac74f5534), closes [#9293](https://github.com/Elgg/Elgg/issues/9293))
* **routing:** allow more reliable URL path rewriting ([853fc0ef](https://github.com/Elgg/Elgg/commit/853fc0ef65356b72ad19d62331ac6e539ca02b4f), closes [#9388](https://github.com/Elgg/Elgg/issues/9388))
* **rss:** adds functions for adding/removing the RSS link ([ae765e19](https://github.com/Elgg/Elgg/commit/ae765e1907b2967991706bb992ceee6b136bccc1))
* **search:** search hooks now preserve custom joins and wheres ([65041619](https://github.com/Elgg/Elgg/commit/650416192092b3dbbaa63e441a435fce1abd3d93))
* **views:** add attributes to input select options ([63b04d6a](https://github.com/Elgg/Elgg/commit/63b04d6ab7af21c43df97efc04012c7575889cd5))


#### Performance

* **boot:** we order plugins in PHP because MySQL order by CAST is slow ([c4b10c1c](https://github.com/Elgg/Elgg/commit/c4b10c1c51e9205b0e448ce5c4e0b1b494517013), closes [#8183](https://github.com/Elgg/Elgg/issues/8183))
* **files:** ElggFile no longer queries metadata for filestore data ([d9243002](https://github.com/Elgg/Elgg/commit/d92430027393fb6d6657af72867f65e31e713ac0), closes [#9138](https://github.com/Elgg/Elgg/issues/9138))
* **http:** serve-file URLs can respond without booting core ([4f587df0](https://github.com/Elgg/Elgg/commit/4f587df02062c7d8f6b239041789030299af2bd6))


#### Documentation

* **js:** modernizes the JS docs to emphasize AMD usage ([d66cae64](https://github.com/Elgg/Elgg/commit/d66cae64c597bc87b8a10c18d0ae21394cbe6398))


#### Bug Fixes

* **actions:** referrer path is now parsed correctly ([6b1bfe26](https://github.com/Elgg/Elgg/commit/6b1bfe2631b1c367252e6feb967b63211527b098))
* **ajax:** iframe-based submissions can again be recognized as XHR requests ([c25962a0](https://github.com/Elgg/Elgg/commit/c25962a02d29ad00f304918c29f44f05e76186b6), closes [#8735](https://github.com/Elgg/Elgg/issues/8735))
* **files:** files with custom filestore can now be served via file service ([1a2b0ca7](https://github.com/Elgg/Elgg/commit/1a2b0ca7170e7bbe949208de8d8321278cb4843d))
* **forms:**
  * remove extra spacing between longtext field label and menu ([23edb5ad](https://github.com/Elgg/Elgg/commit/23edb5adbf57acc8d4eab14c9d1e2d3604f78f7c))
  * elgg_view_input() now passes input type to the field view ([63013725](https://github.com/Elgg/Elgg/commit/63013725c7f3ac00fb5f0e0699c2ede4422236d6))
* **i18n:** admin-created accounts now get site language instead of admin's language ([561bad37](https://github.com/Elgg/Elgg/commit/561bad37580406b3298f816e12956283d826c602), closes [#9454](https://github.com/Elgg/Elgg/issues/9454))
* **js:**
  * don't show ajax error message when aborting request ([5aea301f](https://github.com/Elgg/Elgg/commit/5aea301f901be1a2c5984264f8ecf19a2138c45c), closes [#9372](https://github.com/Elgg/Elgg/issues/9372))
  * client-side hooks can now handle periods in hook names ([9f70099f](https://github.com/Elgg/Elgg/commit/9f70099f5ecec3deacd666195cb7751eef77ce17), closes [#9160](https://github.com/Elgg/Elgg/issues/9160))
* **menus:** delete menu item is only registered if canDelete is fullfilled ([e13ba511](https://github.com/Elgg/Elgg/commit/e13ba51182fed1deed1774840601733e299d5195))
* **permissions:** All permissions functions handle user fetches consistently ([b875fd33](https://github.com/Elgg/Elgg/commit/b875fd33a5d018fd72b4f062225c1fee18cdceda), closes [#8941](https://github.com/Elgg/Elgg/issues/8941), [#8038](https://github.com/Elgg/Elgg/issues/8038), [#8945](https://github.com/Elgg/Elgg/issues/8945))
* **river:** opening comment form auto-focuses input ([5b68badc](https://github.com/Elgg/Elgg/commit/5b68badcaae36724d3b00406711bef7b30744615))
* **search:**
  * search hooks no longer reset subtypes ([5d6987ce](https://github.com/Elgg/Elgg/commit/5d6987ceee63c0975811196a66f2d7f4a52e661a))
  * hooks no longer reset order_by clauses ([b15b9e94](https://github.com/Elgg/Elgg/commit/b15b9e946c66c3afe2721d2160ea1db742e0a6a8))


#### Deprecations

* **assets:** Deprecates URLs like /js/ and /css/ in favor of simplecache ([91daac90](https://github.com/Elgg/Elgg/commit/91daac904475cd0bc78e464eb6a97ac61d325a0c))
* **config:** deprecates config value "siteemail" ([cdd4bb5f](https://github.com/Elgg/Elgg/commit/cdd4bb5f6dd12252aeb36e9ec025acc2f68e5072))
* **entity:** removes the tables_split and tables_loaded properties ([4d469183](https://github.com/Elgg/Elgg/commit/4d469183f1ca21d2a494b1ac2b65cd95a47581bc))
* **filestore:** deprecates giving files custom filestores ([0050b1db](https://github.com/Elgg/Elgg/commit/0050b1dbf21961ab24f4046c2f2aa62fa7177080), closes [#9352](https://github.com/Elgg/Elgg/issues/9352))


<a name="2.0.4"></a>
### 2.0.4  (2016-06-13)

#### Contributors

* Jeroen Dalsem (13)
* Ismayil Khayredinov (4)
* Steve Clay (2)
* iionly (2)
* jdalsem (1)

#### Bug Fixes

* **ckeditor:** do not draw a menu item if id is missing ([edf382b0](https://github.com/Elgg/Elgg/commit/edf382b0d8dda49bc0938524b494a0e9253bd73a))
* **core:**
  * prevent undefined variable notices advanced caching form ([f3459110](https://github.com/Elgg/Elgg/commit/f345911053179a199b569fc022dc4039b9ee6f5b))
  * view inspector now can use simplecache views again ([6c39e573](https://github.com/Elgg/Elgg/commit/6c39e57367231dbccf818b83ac8da655e7e55e15))
  * prevent inspector producing notices inspecting webservices ([3862ffcd](https://github.com/Elgg/Elgg/commit/3862ffcde22c3837d04aca901d3ba12c9f7a57e6))
  * menu inspector provides id in longtext menu ([61c0a549](https://github.com/Elgg/Elgg/commit/61c0a549818f5efa7eea88ae22c0628487c7ff04))
* **css:** correctly positioned the user hover menu icon ([e5566c1a](https://github.com/Elgg/Elgg/commit/e5566c1add0c3276dcc6ff8fe0046c413e571e54))
* **developers:**
  * replaced get_language with get_current_language ([b6bcc579](https://github.com/Elgg/Elgg/commit/b6bcc5796ebe6da491371619014386dc11dc3003))
  * restores missing event/hook handlers in inspector ([70ca4264](https://github.com/Elgg/Elgg/commit/70ca4264195c3d05f12f241d0f051896468fe3c9), closes [#9527](https://github.com/Elgg/Elgg/issues/9527))
* **discussion:** correctly check permissions before showing reply form ([8e64d44d](https://github.com/Elgg/Elgg/commit/8e64d44df5f647ac4cee4b3e60ba7c4e0739e540))
* **embed:** do not draw a menu item if id is missing ([69ca6b51](https://github.com/Elgg/Elgg/commit/69ca6b5111fc29adf0fafb337fb4c944138c5ce2))
* **i18n:** prevent php notices about language translations missing ([1f9916e7](https://github.com/Elgg/Elgg/commit/1f9916e71ddc35d1261b8572f80da2fb2c75a487))
* **js:** replace deprecated jquery .attr usage with .prop ([a95ecc6c](https://github.com/Elgg/Elgg/commit/a95ecc6c854ebf236eea56666f0116ead91154ba))
* **members:** search page now has pagination ([d42611c2](https://github.com/Elgg/Elgg/commit/d42611c28f5209f5185c6dff2046d7c208db9a94))
* **notifications:**
  * use the correct way to check if checkbox is checked ([4c7b8b65](https://github.com/Elgg/Elgg/commit/4c7b8b653407b4f253ec53d31c830f4296429976))
  * users are again unsubscribed when friendship and membership are deleted ([8990ab53](https://github.com/Elgg/Elgg/commit/8990ab535f0bf6763bc412e94bebd5ec699dcece))
* **pages:** use elgg_extract to prevent php notice fetching parent_name ([c8710c9b](https://github.com/Elgg/Elgg/commit/c8710c9bea9963d5f1425ce670608aeed97b3ba9))
* **views:** passing 'default' to input/checkboxes now works ([efa6395f](https://github.com/Elgg/Elgg/commit/efa6395f575973c0f588028c81acdc5446421970))


<a name="2.0.3"></a>
### 2.0.3  (2016-03-06)

#### Contributors

* Ismayil Khayredinov (3)
* Steve Clay (3)
* Juho Jaakkola (1)
* Niraj Kaushal (1)

#### Bug Fixes

* **comments:** unifies behavior after adding new comment/discussion reply ([8ff2b295](https://github.com/Elgg/Elgg/commit/8ff2b2950c7da783e7cb89f5a6eb9bb9cad54e59), closes [#8130](https://github.com/Elgg/Elgg/issues/8130))
* **discussions:** put new discussion page behind gatekeeper ([a583f65b](https://github.com/Elgg/Elgg/commit/a583f65b6e5051d8d79f49aa16d455b24aebeedc), closes [#9383](https://github.com/Elgg/Elgg/issues/9383))
* **events:** the pagesetup event timing is more like 1.x ([38b12288](https://github.com/Elgg/Elgg/commit/38b122888df42599be0c1ab47333c356822fbb2f))
* **groups:**
  * clarify notification status strings ([20059a89](https://github.com/Elgg/Elgg/commit/20059a89f110b27085bfe42ce742270f7110e46e))
  * page owner is now correctly resolved prior to pagesetup ([9a8ba277](https://github.com/Elgg/Elgg/commit/9a8ba27765c831f29db89ed98678a03572eaaf9a))
* **web_services:** web services again can output xml/php ([9bf27a4b](https://github.com/Elgg/Elgg/commit/9bf27a4b56be335b42d923ac62155cfe7437ddfb), closes [#8053](https://github.com/Elgg/Elgg/issues/8053))


<a name="2.0.2"></a>
### 2.0.2  (2016-02-03)

#### Contributors

* Steve Clay (10)
* Juho Jaakkola (3)
* Ismayil Khayredinov (2)
* Wouter van Os (1)

#### Documentation

* **events:** Clarify scope of HooksRegistrationService::hasHandler ([498abdde](https://github.com/Elgg/Elgg/commit/498abdde342cdf29a32bcd2dfef1c6f3176fc314), closes [#9325](https://github.com/Elgg/Elgg/issues/9325))
* **js:** warn devs that that elgg_define_js() configuration is cached ([a078c030](https://github.com/Elgg/Elgg/commit/a078c030346e5d67aba216631f44a788c0b273cb), closes [#9302](https://github.com/Elgg/Elgg/issues/9302))
* **notifications:** Updated subject variable to body ([0cde3006](https://github.com/Elgg/Elgg/commit/0cde30064f828b94ce0a72a334d2cca3b1adbb85))
* **routing:** clarify use of default_page_owner_handler in core ([5d647d18](https://github.com/Elgg/Elgg/commit/5d647d18056bf1eef644dd30c9920df9a78d2f8d))


#### Bug Fixes

* **installer:**
  * don't fatal trying to rewrite the .htaccess file ([5e74932b](https://github.com/Elgg/Elgg/commit/5e74932b525beb8cdc26353e9e084612cc699e06), closes [#9334](https://github.com/Elgg/Elgg/issues/9334))
  * detect PDO MySQL extension instead of ext/mysql ([98c8e418](https://github.com/Elgg/Elgg/commit/98c8e418482b5aa517aa5198f02043e0dabb0e93), closes [#9313](https://github.com/Elgg/Elgg/issues/9313))
  * installer no longer fails on PHP 7 ([4d796279](https://github.com/Elgg/Elgg/commit/4d796279d6e2be3c2609c408ecd3d875e4062525), closes [#9314](https://github.com/Elgg/Elgg/issues/9314))
* **javascript:** replaces calls to obsolete $.die() method with $.off() ([82a08f56](https://github.com/Elgg/Elgg/commit/82a08f56af29fc1e7dd7822dcaa0171aa9fe8275), closes [#9309](https://github.com/Elgg/Elgg/issues/9309))
* **menus:** menu item labels now match page titles in tool settings ([bc8f8dd3](https://github.com/Elgg/Elgg/commit/bc8f8dd3a6054e7c7c589fe45a0d49dd6bca1653))
* **notifications:** set page context before pagesetup is fired ([d4c86cde](https://github.com/Elgg/Elgg/commit/d4c86cde521956632c73c4858c1d27ad1d0f406d))
* **profile:** don't show removed description field ([9846c4a4](https://github.com/Elgg/Elgg/commit/9846c4a462e1b2648eec03a4285f89b9c7af53f0), closes [#8984](https://github.com/Elgg/Elgg/issues/8984))
* **views:**
  * resources/error view now renders sanely within /admin ([c0b1a703](https://github.com/Elgg/Elgg/commit/c0b1a70336ba501ef2ee8074c39d2bbef5aec98b), closes [#9327](https://github.com/Elgg/Elgg/issues/9327))
  * don't pass null to array arguments (for PHP 7) ([e0d5433f](https://github.com/Elgg/Elgg/commit/e0d5433fd69875e5e9fd8ca823f74e8b7715c5f8), closes [#9318](https://github.com/Elgg/Elgg/issues/9318))
  * locations specified in /engine/views.php are modifiable ([3cc5b5b3](https://github.com/Elgg/Elgg/commit/3cc5b5b3a7d71341b36fb5a1b42a6678f2d8060e), closes [#9308](https://github.com/Elgg/Elgg/issues/9308))


<a name="2.0.1"></a>
### 2.0.1  (2016-01-03)

#### Contributors

* Matt Beckett (4)
* Juho Jaakkola (3)
* Ismayil Khayredinov (2)
* Juho Jaakkola (2)
* Steve Clay (1)

#### Bug Fixes

* **admin:** Allow plugins to extend js/admin but deprecate it ([a5c2abdf](https://github.com/Elgg/Elgg/commit/a5c2abdf9669db75a4d080e274c0fe78851a7cf8), closes [#9238](https://github.com/Elgg/Elgg/issues/9238))
* **collections:** only register collections menu items when logged in ([1b88d43a](https://github.com/Elgg/Elgg/commit/1b88d43a9b0033d7f1681eec4d70644e6396369a), closes [#9249](https://github.com/Elgg/Elgg/issues/9249))
* **comments:** validate array structure before calling elgg_extract() ([1078b65d](https://github.com/Elgg/Elgg/commit/1078b65de759c662318f03e90b50ee3e7fdd2bad))
* **entities:** fix php notice when editing metadata while not-logged-in ([64bb369f](https://github.com/Elgg/Elgg/commit/64bb369fd83be95fa46fc41aa4849d747ea03437), closes [#9256](https://github.com/Elgg/Elgg/issues/9256))
* **groups:** link to membership requests page is visible again ([8e3bb84a](https://github.com/Elgg/Elgg/commit/8e3bb84a8bac739354664b08f9f3bc2324ce9978))
* **install:** Set default timezone on installation ([7d5a2b05](https://github.com/Elgg/Elgg/commit/7d5a2b05d15956c70bd325e246fd0af89f305023), closes [#8845](https://github.com/Elgg/Elgg/issues/8845))
* **js:** prevent multiple togglable menu item bindings ([e7f33013](https://github.com/Elgg/Elgg/commit/e7f330134e28a8fe2753c0943599eb50ea5512df), closes [#9151](https://github.com/Elgg/Elgg/issues/9151))
* **notifications:**
  * default settings now get enabled also for new friends ([e84fc160](https://github.com/Elgg/Elgg/commit/e84fc160b99a66816f70674e497acabfdd73a4bb))
  * notifications about new friends work again ([a23683ee](https://github.com/Elgg/Elgg/commit/a23683ee5e3a06fbb0d1234e5a9fea0ac49947fc))
* **relationships:** prevent sql exception on duplicate relationships race condition ([9e469da9](https://github.com/Elgg/Elgg/commit/9e469da988d288969706ef61970e2044a442d162), closes [#9179](https://github.com/Elgg/Elgg/issues/9179))
* **simplecache:** removes warning about using mkdir() when cache directory exists ([3bae0bf5](https://github.com/Elgg/Elgg/commit/3bae0bf58d7809b430cb4708b13a0b79c2d361c4), closes [#9219](https://github.com/Elgg/Elgg/issues/9219))


<a name="2.0.0"></a>
## 2.0.0  (2015-12-14)

#### Contributors

* Steve Clay (3)
* Juho Jaakkola (3)

#### Documentation

* **notifications:** documents workflow of the asynchronous notification system ([209b6a51](https://github.com/Elgg/Elgg/commit/209b6a51bcb0a76cffefe0e732d1bae216386e31), closes [#7496](https://github.com/Elgg/Elgg/issues/7496))


#### Bug Fixes

* **a11y:** aalborg mobile site menu uses the Font Awesome fa-bars icon ([a6a512e3](https://github.com/Elgg/Elgg/commit/a6a512e30f7298736566977f8d943d7f35be489e), closes [#9110](https://github.com/Elgg/Elgg/issues/9110))


#### Deprecations

* **file:** Deprecates accessing filestore metadata ([363b461d](https://github.com/Elgg/Elgg/commit/363b461d51508ea8b9ba30a89de97c6433a34907))


#### Breaking Changes

* In aalborg_theme, the view `page/elements/navbar` now uses an icon for the
mobile menu selector (formerly an image). The `bars.png` image and supporting
CSS for the 1.12 rendering has been removed.

Fixes #9110 ([a6a512e3](https://github.com/Elgg/Elgg/commit/a6a512e30f7298736566977f8d943d7f35be489e))


<a name="2.0.0-rc.2"></a>
### 2.0.0-rc.2  (2015-11-29)

#### Contributors

* Steve Clay (10)
* Ismayil Khayredinov (4)
* Juho Jaakkola (4)

#### Performance

* **river:** no longer needlessly render river responses ([97df230f](https://github.com/Elgg/Elgg/commit/97df230f4c496d773e50060bf84fef5ae7052b24), closes [#9046](https://github.com/Elgg/Elgg/issues/9046))


#### Bug Fixes

* **files:** make sure method is callable on a concrete object instance ([740d3108](https://github.com/Elgg/Elgg/commit/740d3108a30733d02a98e9aed7516f92033cd8a9), closes [#9010](https://github.com/Elgg/Elgg/issues/9010))
* **i18n:** avoids using mbstring.internal_encoding in PHP >= 5.6 ([c0ff79de](https://github.com/Elgg/Elgg/commit/c0ff79de100cc8e48fd69d01883c946669b5b275), closes [#9031](https://github.com/Elgg/Elgg/issues/9031))
* **likes:** count is updated after liking/unliking ([dae30cb7](https://github.com/Elgg/Elgg/commit/dae30cb71e8d1900bac8730e594ca8d5ea8d0154), closes [#9100](https://github.com/Elgg/Elgg/issues/9100))
* **memcache:** don't store a copy of $CONFIG in file objects ([beb90891](https://github.com/Elgg/Elgg/commit/beb9089129a0a06b36200f3f8d214c7ed8f94f42), closes [#9081](https://github.com/Elgg/Elgg/issues/9081))
* **pages:** removes deprecated notices regarding input/write_access ([fdcab74b](https://github.com/Elgg/Elgg/commit/fdcab74b1e9069736f88f7e9aa36aeb15067b8fe), closes [#8327](https://github.com/Elgg/Elgg/issues/8327))
* **river:** floated river selector no longer breaks layout ([2745c914](https://github.com/Elgg/Elgg/commit/2745c91460915ae47519d79f70aa71736eda3449), closes [#9091](https://github.com/Elgg/Elgg/issues/9091))


#### Breaking Changes

* The report content icon is now a FontAwesome icon, however the GIF used in 1.x
is still available. ([96d258fa](https://github.com/Elgg/Elgg/commit/96d258fa0083b73ce86aa2532838ece3aaa8a30d))
* Plugins that override the ``input/autocomplete`` view will need to include the
source URL in the ``data-source`` attribute of the input element, require the
new ``elgg/autocomplete`` AMD module, and call its ``init`` method. The 1.x
javascript library ``elgg.autocomplete`` is no longer used.
 ([2a0cf9a5](https://github.com/Elgg/Elgg/commit/2a0cf9a5bf628f2be0a13c95226e2b85c57f13a9))


<a name="2.0.0-rc.1"></a>
### 2.0.0-rc.1  (2015-11-07)

#### Contributors

* Steve Clay (12)
* iionly (3)

#### Bug Fixes

* **http:** allows sending gzipped JavaScript on nginx < 1.5.4 ([4c4b8ab7](https://github.com/Elgg/Elgg/commit/4c4b8ab7aee765d09bc59d541693e5a2643bb3ba))
* **likes:** likes preloader and entity menus now consider likability ([de81d7da](https://github.com/Elgg/Elgg/commit/de81d7daf1f49eba179ec6acea4cf633d14ec803), closes [#9065](https://github.com/Elgg/Elgg/issues/9065))
* **views:** input/userpicker API more BC with 1.8 plugins ([0651a5fd](https://github.com/Elgg/Elgg/commit/0651a5fdc075bde1f09f6ee27252a7ba471216f1), closes [#6079](https://github.com/Elgg/Elgg/issues/6079))


#### Breaking Changes

* To allow for usage of the z-index property for elements in the content area without the More menu dropdown being displayed behind these elements the z-index value in the elgg-menu site class has been increased to 50
 ([34af1d71](https://github.com/Elgg/Elgg/commit/34af1d71ab57110128c6d44f2b7af53c7c29c873))


<a name="2.0.0-beta.3"></a>
### 2.0.0-beta.3  (2015-10-04)

#### Contributors

* Steve Clay (6)
* Juho Jaakkola (2)
* iionly (1)

#### Features

* **views:** allow getting all view locations ([7a699f3c](https://github.com/Elgg/Elgg/commit/7a699f3c11dd8668b06323617730dbdfb12a566f), closes [#8947](https://github.com/Elgg/Elgg/issues/8947))


#### Bug Fixes

* **bookmarks:** bookmark pin copies title into form ([50881370](https://github.com/Elgg/Elgg/commit/50881370cac4fd10ca707aea2c83a25659eef03a), closes [#8995](https://github.com/Elgg/Elgg/issues/8995))
* **file:** thumbnails are visible again ([7f46db8e](https://github.com/Elgg/Elgg/commit/7f46db8e4002732d3616a08c6bd82718e4bf3333))
* **site_notifications:** no ajax error without reason on auto-deletion of site notifications ([7aa55a81](https://github.com/Elgg/Elgg/commit/7aa55a819c0824dc97a5752658260cea6b2f1a2f))


<a name="2.0.0-beta.2"></a>
### 2.0.0-beta.2  (2015-09-21)

#### Contributors

* Steve Clay (7)
* Juho Jaakkola (2)
* iionly (2)
* Matt Beckett (1)

#### Documentation

* **upgrading:** Warn site owners about MultiViews and /settings URLs ([0ada89d6](https://github.com/Elgg/Elgg/commit/0ada89d68c69e6185cf3c1165f759780de8967c3), closes [#8806](https://github.com/Elgg/Elgg/issues/8806))


#### Breaking Changes

* Relationship deletions only fire the "delete", "relationship" event. ([9c148994](https://github.com/Elgg/Elgg/commit/9c148994bf14edcbaebf7c097d42f26faf083a5b))


<a name="2.0.0-beta.1"></a>
### 2.0.0-beta.1  (2015-09-06)

#### Contributors

* Juho Jaakkola (4)
* Juho Jaakkola (3)

<a name="2.0.0-alpha.3"></a>
### 2.0.0-alpha.3  (2015-08-23)

#### Contributors

* Evan Winslow (6)
* Jeroen Dalsem (3)
* Juho Jaakkola (3)
* Steve Clay (3)
* Jerôme Bakker (1)
* Juho Jaakkola (1)
* Matt Beckett (1)

#### Features

* **developers:** Always show human-readable translations ([43c19644](https://github.com/Elgg/Elgg/commit/43c19644aa7a30525990c2b24770056273e6c7d0), closes [#8834](https://github.com/Elgg/Elgg/issues/8834))
* **i18n:** abbreviations for months and weekdays ([889617ed](https://github.com/Elgg/Elgg/commit/889617edf01820a4b69b98f4c8bcbf3232b6a16f))
* **views:**
  * added html5 audio support to the file plugin ([e5a32390](https://github.com/Elgg/Elgg/commit/e5a32390885c99d65ebf5a937f0e29abe983e4de))
  * Allow sites to specify views.php at root ([625c1ddd](https://github.com/Elgg/Elgg/commit/625c1dddfc4bc6f65a2f6bd5555b805dcd4a2495))


#### Performance

* **nginx:** Turn on gzip by default ([49f776d3](https://github.com/Elgg/Elgg/commit/49f776d3c3764fed67c21e7121736b27aaa126d4))


#### Bug Fixes

* **cli:** Rewrite `::installDir()` to `Directory\Local::root()` in CLI server ([1e1f446b](https://github.com/Elgg/Elgg/commit/1e1f446b76ef976c35c8c0d4edb4b69a06e531f4))
* **discussions:** Body of discussion notification mail is not empty anymore ([23ab3e51](https://github.com/Elgg/Elgg/commit/23ab3e51e5282b5c54bd8538561e8ea56f13c02e))
* **entities:** Entity creation no longer needlessly checks owner container ([5adf98fd](https://github.com/Elgg/Elgg/commit/5adf98fd83e6c15a6f417b63e02f1fb4f0c3fcb4), closes [#4231](https://github.com/Elgg/Elgg/issues/4231))
* **icons:** sizes of Font awesome icons are now more consistent with old icons ([11386003](https://github.com/Elgg/Elgg/commit/11386003f9793fda1ce11c1ef59de9027dac99ee), closes [#8733](https://github.com/Elgg/Elgg/issues/8733), [#8861](https://github.com/Elgg/Elgg/issues/8861))


#### Breaking Changes

* If a plugin has removed or replaced messages_notifier to hide/alter the
inbox icon, the plugin must instead do the same for the topbar menu handler
(messages_register_topbar).

Fixes #8862 ([67cff474](https://github.com/Elgg/Elgg/commit/67cff4746d38c54905ba6ad3b8cc8f771d50feec))
* When creating within a group, ElggEntity::create used to always separately
check if the current user can use the owner's account as a container. This
made sure that one group member could not post to the group using another
member as owner. This separate check led to confusion, as handlers of the container_permissions_check hook were told that the owner was to be the
container, when it was actually the group.

Here we bypass the separate owner container check if the desired owner_guid
is the logged in user GUID. This eliminates the check under all normal
circumstances but leaves it in place in case a poorly coded plugin allows
the impersonation described above.

This also denies creation if the owner/container GUIDs are set but can't
be loaded. Before, create() would simply bypass the permissions check if
it couldn't load the owner/container.

Fixes #4231 ([5adf98fd](https://github.com/Elgg/Elgg/commit/5adf98fd83e6c15a6f417b63e02f1fb4f0c3fcb4))
* We've removed the "categories" plugin from core.

You may access it at https://github.com/Elgg/categories

Fixes #7584
 ([ba0c12f2](https://github.com/Elgg/Elgg/commit/ba0c12f227e0d2df64722d364af34b6c00e3bfbb))
* The zaudio plugin is no longer part of the bundled plugins. The plugin
has been moved to a seperate repository. You can find it here:
https://github.com/Elgg/zaudio
 ([ace52256](https://github.com/Elgg/Elgg/commit/ace522564c8c09703836591243b3e5e88d15bc6a))


<a name="2.0.0-alpha.2"></a>
### 2.0.0-alpha.2  (2015-08-05)

#### Contributors

* Steve Clay (14)
* Evan Winslow (13)
* Jeroen Dalsem (4)
* Ismayil Khayredinov (3)
* iionly (1)

#### Features

* **core:** Makes several commonly-used functions public ([4b58e4f5](https://github.com/Elgg/Elgg/commit/4b58e4f5db3a1411e891ca34754e9a5c9c4d9681), closes [#7838](https://github.com/Elgg/Elgg/issues/7838))
* **groups:** group owner transfer lists users alphabetically ([a8bc79c2](https://github.com/Elgg/Elgg/commit/a8bc79c262f1ec4c82c2d575c0d72b790c6c4635))
* **hooks:** Adds indication in container permissions hook of checking owner ([298b5231](https://github.com/Elgg/Elgg/commit/298b5231b549739a1a6fc831d249fa708310750d), closes [#8774](https://github.com/Elgg/Elgg/issues/8774))
* **web_services:** filter method output with a plugin hook ([5ff308c5](https://github.com/Elgg/Elgg/commit/5ff308c53f130d0319e4b168972aaaba172d82ec))

#### Documentation

* **releases:** Clarify BC policy for major releases ([a636bf86](https://github.com/Elgg/Elgg/commit/a636bf8610e26e45e8960af3600dcebe16e135b9), closes [#7080](https://github.com/Elgg/Elgg/issues/7080))
* **web_services:** document ws hooks ([5430e032](https://github.com/Elgg/Elgg/commit/5430e032aba0d8496a81e8467b1b643ea88b9cdc))

#### Bug Fixes

* **composer:** Symlink plugins from root mod dir ([436fb4a2](https://github.com/Elgg/Elgg/commit/436fb4a2e29017fe740c2e02be2da8824f63d37d))
* **nginx:** Update rewrite rules for 2.0 ([aa082a5c](https://github.com/Elgg/Elgg/commit/aa082a5cdec3e0158bfcd298994a269f313a28c7), closes [#8750](https://github.com/Elgg/Elgg/issues/8750))
* **profile:** Support composer for icondirect requests ([7610552b](https://github.com/Elgg/Elgg/commit/7610552b22f203f3d2eadb1f20e28b2429e0d234))
* **upgrade:** Point UpgradeService to correct upgrades dir ([b3a31868](https://github.com/Elgg/Elgg/commit/b3a31868a73e32dcc02b922d996b345cd287d786))
* **web_services:** do not leak internal function names via system.api.list method ([9415c413](https://github.com/Elgg/Elgg/commit/9415c4136849028304327e097c84ac707c21d833), closes [#8574](https://github.com/Elgg/Elgg/issues/8574))


#### Deprecations

* **discussion:** Deprecates the elgg:discussion library ([bf741815](https://github.com/Elgg/Elgg/commit/bf741815e36ac72a95adf290764cddf05bc568ab), closes [#8760](https://github.com/Elgg/Elgg/issues/8760))


<a name="2.0.0-alpha.1"></a>
### 2.0.0-alpha.1  (2015-07-07)

#### Contributors

* Steve Clay (64)
* Evan Winslow (55)
* Jeroen Dalsem (28)
* Jerôme Bakker (14)
* Juho Jaakkola (4)
* Ismayil Khayredinov (2)
* Paweł Sroka (2)
* Juho Jaakkola (1)
* Juho Jaakkola (1)

#### Features

* **application:**
  * Allows fetching dataroot statically from Application ([bed2e09b](https://github.com/Elgg/Elgg/commit/bed2e09b31945fd1cca3908a985f84294fd191f2), closes [#8653](https://github.com/Elgg/Elgg/issues/8653))
  * adds elgg() and makes Application a service provider ([d43de92f](https://github.com/Elgg/Elgg/commit/d43de92fe0a10673fd28ed2cb4b209751e9a4d17))
  * Introduces Elgg\Application for loading/booting Elgg ([ae5ece22](https://github.com/Elgg/Elgg/commit/ae5ece225c26650de8386371c5073209de41d2ca))
* **assets:**
  * Get rid of js/ and css/ directories ([881e2128](https://github.com/Elgg/Elgg/commit/881e212864dca61b1e1e1bd4240adacc64456ea2), closes [#8382](https://github.com/Elgg/Elgg/issues/8382))
  * Move all core static assets to views ([c44740d5](https://github.com/Elgg/Elgg/commit/c44740d59be73bf531cd1d311b85c00278fd0c3c), closes [#5105](https://github.com/Elgg/Elgg/issues/5105))
* **comments:** Comment/reply timestamps are now permalinks ([4596e00b](https://github.com/Elgg/Elgg/commit/4596e00b6b9dbc37b8bc14ab844de4a66f9a4fd4), closes [#8407](https://github.com/Elgg/Elgg/issues/8407))
* **composer:** Fully support installing Elgg as a composer dependency ([fceafea6](https://github.com/Elgg/Elgg/commit/fceafea600b1b7067dd19cc005352b04a7921d65), closes [#8431](https://github.com/Elgg/Elgg/issues/8431))
* **discussions:** discussions feature has been moved to its own plugin ([a4e484e6](https://github.com/Elgg/Elgg/commit/a4e484e62db117531763c0487c4b04c098bfb909))
* **groups:**
  * adds alphabetical sorting to all groups page ([ba82c682](https://github.com/Elgg/Elgg/commit/ba82c6827ad6839b64cabf56de8a08695992bc34))
  * sidebar members show recently joined members ([fcf6e19a](https://github.com/Elgg/Elgg/commit/fcf6e19aeabc3895fe316655b74c0d7b73910203))
  * group members page can be sorted on newest members ([2eb5e5ea](https://github.com/Elgg/Elgg/commit/2eb5e5eaac9fee0f06e04d2f5d09d19433552018))
  * Only submitted group profile fields are updated ([c3d11285](https://github.com/Elgg/Elgg/commit/c3d11285cb257fb36a4f79231fb8047d92101847))
* **hooks:** a new function to clear all callbacks for a given hook ([bd511763](https://github.com/Elgg/Elgg/commit/bd5117636c901ea47e07a9f7cefce3ae5854cccd))
* **icons:** use FontAwesome icons ([ea7b17d5](https://github.com/Elgg/Elgg/commit/ea7b17d5575c28ed3013e20bbe4d776e31d174a2))
* **javascript:** admin panel javascript is now loaded asynchronously ([7141da5f](https://github.com/Elgg/Elgg/commit/7141da5fe2038baa6eb5e38faad479da935f44eb))
* **js:** Adds temporary require() shim for deferring inline scripts ([65fddb00](https://github.com/Elgg/Elgg/commit/65fddb002e0231b141acd4e25cd8546da245db0b))
* **likes:** Entities are no longer likable by default ([cb6ebcc9](https://github.com/Elgg/Elgg/commit/cb6ebcc9703ab6c979cff22ca3a6a92025d5eee2), closes [#5996](https://github.com/Elgg/Elgg/issues/5996))
* **members:** add alphabetical member listing ([0ad75efe](https://github.com/Elgg/Elgg/commit/0ad75efe1d5315697fa7950010df2203fd2b4d55))
* **memcache:** support Memcache namespace prefix ([8baae3f3](https://github.com/Elgg/Elgg/commit/8baae3f3658c48a6bac81671513e67a561cbd464))
* **messages:** recipient selection by userpicker ([932e974c](https://github.com/Elgg/Elgg/commit/932e974cdf7c41b40f213f8dec4dd0972a550123))
* **php:** Require PHP 5.5+ ([52da9d98](https://github.com/Elgg/Elgg/commit/52da9d98ea7c711e72b38e1dd7e40effeaef0dc8))
* **plugins:**
  * listing of 'my' content shows content you own ([20e606b7](https://github.com/Elgg/Elgg/commit/20e606b79fcb97d7969c82ad5650b32e47acb225), closes [#4878](https://github.com/Elgg/Elgg/issues/4878))
  * (de)activate_all action defaults to (in)active plugins ([0ea152f0](https://github.com/Elgg/Elgg/commit/0ea152f0ec1ba45702cf0fd3450a683cca0589e1))
  * Improved plugin listing ([bde2c394](https://github.com/Elgg/Elgg/commit/bde2c39490ff0c5a5f322eeceb36e0fc6c657d81), closes [#8412](https://github.com/Elgg/Elgg/issues/8412), [#4158](https://github.com/Elgg/Elgg/issues/4158), [#4301](https://github.com/Elgg/Elgg/issues/4301), [#6778](https://github.com/Elgg/Elgg/issues/6778))
* **profile:** only submitted profile fields are updated ([fd7b8e0a](https://github.com/Elgg/Elgg/commit/fd7b8e0afc8e4dfadaed4b0fbe8919ccd7e0d27f))
* **routing:**
  * Removes /export and all secondary front controllers ([ab3c879f](https://github.com/Elgg/Elgg/commit/ab3c879f8828d4a951a968d1589ca6da9e436447), closes [#8425](https://github.com/Elgg/Elgg/issues/8425), [#5017](https://github.com/Elgg/Elgg/issues/5017))
  * Render blog pages with views ([9f1d9316](https://github.com/Elgg/Elgg/commit/9f1d931679031647c83ca82813f10687ffb9f45d))
  * Render email validation page with view ([b1060e52](https://github.com/Elgg/Elgg/commit/b1060e52f14de33d59cf3106da768d62768c2774))
  * Render twitter_api interstitial with view ([a06a7e27](https://github.com/Elgg/Elgg/commit/a06a7e27f2c45bcbdd5f5b0e0a1332e3f0bf14b3))
  * Render thewire pages with views ([e126b2f6](https://github.com/Elgg/Elgg/commit/e126b2f69ce8a1b03e5ced034be1fb52b172d926))
  * Render tagcloud page with view ([ad0d4cc0](https://github.com/Elgg/Elgg/commit/ad0d4cc08ebd24fe16c6119b09d96a045f482f02))
  * Render site notifications page with view ([5e3cb646](https://github.com/Elgg/Elgg/commit/5e3cb64640bda48be5e1e5e3bd1b712f93e69383))
  * Render search page with view ([aff84bc7](https://github.com/Elgg/Elgg/commit/aff84bc7a189d3c332feba6f3114bebae4595fb5))
  * Render reportedcontent pages with views ([315ce166](https://github.com/Elgg/Elgg/commit/315ce166a394578b55359b50abcae51e182a2087))
  * Render profile pages with views ([125844aa](https://github.com/Elgg/Elgg/commit/125844aa1ac63ee9aaf38ea3ebf1475714ce27d0))
  * Render pages pages with views ([ec060fb6](https://github.com/Elgg/Elgg/commit/ec060fb689c8d206296872a95463f0504aec8ed2))
  * Render notification pages with views ([dcfb1761](https://github.com/Elgg/Elgg/commit/dcfb17617c87febab9e92433230f085beaa94026))
  * Render messages pages with views ([91b52801](https://github.com/Elgg/Elgg/commit/91b52801ad45f480df52f99cd052303a67e23875))
  * Render messageboard pages with views ([c77d4e03](https://github.com/Elgg/Elgg/commit/c77d4e03df82750ada7ad99ebb4ebc36f8d80ad0))
  * Render members pages with views ([75c3fcda](https://github.com/Elgg/Elgg/commit/75c3fcda35854a086fe5602871e94bf90163f2b8))
  * Render groups pages with views ([89651a3a](https://github.com/Elgg/Elgg/commit/89651a3a322d46c9d60e01d152ec432ddf8819d7))
  * Render discussion pages with views ([7dc40a16](https://github.com/Elgg/Elgg/commit/7dc40a16ef8805bac11e7d7ce5d1496bedea5c34))
  * Render file pages with views ([217e4222](https://github.com/Elgg/Elgg/commit/217e422206fd072cba5158a2d90e9f055c3a4dae))
  * Render theme_sandbox shell with view ([42013a5d](https://github.com/Elgg/Elgg/commit/42013a5da449ac293a1dc33768a5566d2883cde6))
  * Render dashboard page with view ([f8530d13](https://github.com/Elgg/Elgg/commit/f8530d132bb2b861f75f70e8f8bb324656639bcc))
  * Render categories page with view ([1ae65c53](https://github.com/Elgg/Elgg/commit/1ae65c539a4d7bc2e64c18a0310a803963c9f467))
  * Render bookmarks pages with views ([6c63b0df](https://github.com/Elgg/Elgg/commit/6c63b0dfcc4f4ca984041b0a07afe149fdb76091))
  * Move all core pages to resource views ([2761e874](https://github.com/Elgg/Elgg/commit/2761e8740ea8420e3d3c885081f3814f1049d425))
  * Use the views system to render the index page ([f1b75bbe](https://github.com/Elgg/Elgg/commit/f1b75bbeffa582f4933f5445d6cb90db2ee67387))
* **views:**
  * added a generic by_line page element for content objects ([1a5bcb64](https://github.com/Elgg/Elgg/commit/1a5bcb64666aac545c3f42a0ec0fabc5afa40ec3))
  * comments form value is now html5 required ([ba9f4301](https://github.com/Elgg/Elgg/commit/ba9f43011ca6306a904adb5e87e5865eb15a18db))
  * login and register form now have html5 required fields ([f8c505ca](https://github.com/Elgg/Elgg/commit/f8c505caadcfd958b98f0daf7d796c05e340f4c1))
  * Allow mapping views dirs via views.php files ([9ba31f20](https://github.com/Elgg/Elgg/commit/9ba31f20e7b278cc472749cb1d2cd1353c3bbec9), closes [#8546](https://github.com/Elgg/Elgg/issues/8546), [#8550](https://github.com/Elgg/Elgg/issues/8550))
  * Nearly all plugin static resources are in views ([b8a8864c](https://github.com/Elgg/Elgg/commit/b8a8864c6a0816d59a39ff42926cceb010bcfa0f))
  * Allow specifying exact view paths via views.php ([f3881cf4](https://github.com/Elgg/Elgg/commit/f3881cf400765cbd91db67afdb59b610fa5d3e84), closes [#6844](https://github.com/Elgg/Elgg/issues/6844), [#8515](https://github.com/Elgg/Elgg/issues/8515), [#8527](https://github.com/Elgg/Elgg/issues/8527))


#### Performance

* **engine:**
  * Viewtype is auto-detected only once ([541a5cd1](https://github.com/Elgg/Elgg/commit/541a5cd10e373829a45a698bdab322cf4d6653b4), closes [#8438](https://github.com/Elgg/Elgg/issues/8438))
  * Reduce method calls when fetching from service provider ([5561fec9](https://github.com/Elgg/Elgg/commit/5561fec93481e4cee179c294424728cf44eb726c))
* **js:** Remove jquery-migrate and upgrade jquery to ^2.1.4 ([8f58da98](https://github.com/Elgg/Elgg/commit/8f58da9868c90a2aee2e998bf552f9bea899d13e))
* **scripts:** Load all scripts in foot regardless of registered location ([c91f1f3e](https://github.com/Elgg/Elgg/commit/c91f1f3e5b0c825e34feae248a1a3ff5a5e2b640), closes [#2718](https://github.com/Elgg/Elgg/issues/2718))
* **simplecache:**
  * Faster serving in case symlink not used ([85c2e888](https://github.com/Elgg/Elgg/commit/85c2e888b5d32d4dbe25bd00c3a3ef139a3f384e))
  * Allow 304 responses even with simplecache off ([14bd68fb](https://github.com/Elgg/Elgg/commit/14bd68fbd7110dea82d0935ef284cc7180bc6d25))
* **views:**
  * only draw menus when they are used ([b9f85e4c](https://github.com/Elgg/Elgg/commit/b9f85e4c06f675a4460e219d7b9368e5a5451581))
  * Allow serving assets directly from filesystem ([c4c5734d](https://github.com/Elgg/Elgg/commit/c4c5734d1706aca5545264e5b14fafc1ee7813db), closes [#8381](https://github.com/Elgg/Elgg/issues/8381))


#### Documentation

* **head:** Remove out-of-date JS docs ([23c3d036](https://github.com/Elgg/Elgg/commit/23c3d03672dac1d32f75e35a5f02731213e43d9a))
* **icons:** Add docs for Font Awesome changes in #8578 ([eb9bcde3](https://github.com/Elgg/Elgg/commit/eb9bcde38bacfde961e47b40d7ed32657e3451c8), closes [#8625](https://github.com/Elgg/Elgg/issues/8625))
* **routing:** Update custom_index to demonstrate latest best practices ([0142a5f0](https://github.com/Elgg/Elgg/commit/0142a5f05da1ed596cd9c5a0ef4776d5837056aa))
* **upgrade:** Clean up docs around PDO upgrade ([fdfa4d7f](https://github.com/Elgg/Elgg/commit/fdfa4d7f310e64820f8ad7f84f94e9da1e00c666))
* **upgrading:** documents comments hook return values ([b9d975f0](https://github.com/Elgg/Elgg/commit/b9d975f091163e1bcb2002bc058e2bb06ad07a2a))


#### Bug Fixes

* **actions:** Misspelled action levels no longer treated as logged_in. ([d936549a](https://github.com/Elgg/Elgg/commit/d936549a7cfb2afad5acf8a0dc407602bfd48a1f), closes [#8337](https://github.com/Elgg/Elgg/issues/8337))
* **admin:** admin.js no longer fails on the plugin text file page ([a4e2ce00](https://github.com/Elgg/Elgg/commit/a4e2ce00ef9207f4265d6417cdc581bb13cb670a))
* **breadcrumbs:** hide the last breadcrumb if it’s not a link ([a1bec58f](https://github.com/Elgg/Elgg/commit/a1bec58f57fd15e44b1c8990c8928a2ff742347c), closes [#6419](https://github.com/Elgg/Elgg/issues/6419))
* **comments:** allow comments hook to return empty strings ([37321a14](https://github.com/Elgg/Elgg/commit/37321a14fa3106d7a8474750948fe2bc16482dcc))
* **database:** Uncallable callback arguments now throw exceptions ([1e65aa10](https://github.com/Elgg/Elgg/commit/1e65aa10b317162e970662e5c2abe07e2c7fa0db), closes [#6937](https://github.com/Elgg/Elgg/issues/6937))
* **events:** All hook/event handlers are now weighted properly ([3e6a2898](https://github.com/Elgg/Elgg/commit/3e6a28984bcbcb8e0698ca716fac924dc25fd10e), closes [#1378](https://github.com/Elgg/Elgg/issues/1378))
* **https:** Drop login-over-https ([c0e81a40](https://github.com/Elgg/Elgg/commit/c0e81a40730090c4d419a5101d375838ea2eac2d), closes [#5729](https://github.com/Elgg/Elgg/issues/5729))
* **output:** fewer view $vars will be output by accident ([4560d389](https://github.com/Elgg/Elgg/commit/4560d389619d8eb950abcc504268cf92edae167c), closes [#8218](https://github.com/Elgg/Elgg/issues/8218))
* **river:** Improves alignment of filter select and nests inside label ([4f903c1e](https://github.com/Elgg/Elgg/commit/4f903c1eccf50106762ca0f57a2e7b2ac6623861))
* **site_notifications:** correctly position site_notifications menu ([22d14acb](https://github.com/Elgg/Elgg/commit/22d14acba615693564a1c5cf485e60e47fe69280))
* **ui:** Eliminates FOUC in Firefox ([8244ae61](https://github.com/Elgg/Elgg/commit/8244ae61959455d3511086042f0985d8d58efa94), closes [#8328](https://github.com/Elgg/Elgg/issues/8328))
* **views:** do not drop elgg-button-submit class when using input/submit ([1e8e3e6f](https://github.com/Elgg/Elgg/commit/1e8e3e6f318fa29d7586f33feb5258e92a15ea16))
* **zaudio:**
  * AudioPlayer now works via AMD module in IE9 ([4b0768b8](https://github.com/Elgg/Elgg/commit/4b0768b8509d1eda82b39cbd5d334d28a259a51a))
  * Convert zaudio JS to AMD modules ([674899e0](https://github.com/Elgg/Elgg/commit/674899e070273bc4915c32dbd21d2d4398d99321), closes [#8283](https://github.com/Elgg/Elgg/issues/8283))


#### Breaking Changes

* To allow likes on your content you must permit the annotation to be created.
Likes provides a new hook “likes:is_likable” to allow easily registering entities
by type:subtype.

Fixes #5996 ([cb6ebcc9](https://github.com/Elgg/Elgg/commit/cb6ebcc9703ab6c979cff22ca3a6a92025d5eee2))
* The plugins blog, bookmarks and file have been changed to have their
content listing for "Mine" and "Friends" list content where they are the
owner of. Previously it only listed content created in their container.
This resulted in group content not showing in 'my' content listings.

Fixes #4878 ([20e606b7](https://github.com/Elgg/Elgg/commit/20e606b79fcb97d7969c82ad5650b32e47acb225))
* The functions blog_get_page_content_read and
blog_get_page_content_friends are no longer available. ([a437f952](https://github.com/Elgg/Elgg/commit/a437f9525c6c96ff34f372e6ee26583f92e51793))
* The file mod/aalborg_theme/graphics/avatar_menu_arrows.png has been removed.

Fixes #8629 ([ee7f14ed](https://github.com/Elgg/Elgg/commit/ee7f14ed803da8a06911a3fec3abd241e0adb823))
* Elgg no longer checks get_input(‘view’) and $CONFIG->view for every call
of elgg_get_viewtype(). elgg_set_viewtype() must be used to change the
global viewtype.

Fixes #8438 ([541a5cd1](https://github.com/Elgg/Elgg/commit/541a5cd10e373829a45a698bdab322cf4d6653b4))
* The forms/admin/plugin/change_state view is no longer available ([796cafe7](https://github.com/Elgg/Elgg/commit/796cafe7f8cef1def47b041dee814e3c7c50d74c))
* Only profile fields that are passed to the profile/edit action via
$_REQUEST will be updated. Omitted fields will not be cleared as it was
before this change.

fixes #8582 ([fd7b8e0a](https://github.com/Elgg/Elgg/commit/fd7b8e0afc8e4dfadaed4b0fbe8919ccd7e0d27f))
* Fields not submitted to the groups/edit action will no longer be acted
upon. Previously they would be set to empty or default values. ([c3d11285](https://github.com/Elgg/Elgg/commit/c3d11285cb257fb36a4f79231fb8047d92101847))
* dropped library elgg.markdown

The Markdown library is available as a composer package, this requires
less maintenance by Elgg developers.

fixes #8597 ([df68d986](https://github.com/Elgg/Elgg/commit/df68d9864f787fa55fa1f9457692f1dbcd5587d6))
* The action widgets/upgrade is no longer available ([c3273d1d](https://github.com/Elgg/Elgg/commit/c3273d1da04e3767f2003d9b30911e2baeb96cb2))
* The deprecated functions $user->getSites(), $user->addToSite(),
$user->removeFromSite(), $user->listFriends() and $user->listGroups()
are removed. ([3bde1718](https://github.com/Elgg/Elgg/commit/3bde171803b79fd9e8f256f6428d634f9f742a27))
* This class is no longer used in Elgg. If you still need this, you need
to provide your own version of this class. ([4819c565](https://github.com/Elgg/Elgg/commit/4819c565be316e2bf4ede594b36a212720887397))
* The deprecated function parameter support for $object->getSites() and
$object->addToSite() are removed. Update to the parameter usage as
defined in the ElggEntity functions. ([5d02672b](https://github.com/Elgg/Elgg/commit/5d02672b3333326359fdb13959c963bc8b2c0f29))
* If you use a reference to the physical Elgg sprites please update your
plugin to use the FontAwesome icons.

Replace the Elgg sprites icons by FontAwesome icons, this allowes for
more icons and easier styling of the icons

fixes #7239 ([ea7b17d5](https://github.com/Elgg/Elgg/commit/ea7b17d5575c28ed3013e20bbe4d776e31d174a2))
* The plugin views are redesigned to display in a lightbox. This completely
removes the views forms/admin/plugins/filter and forms/admin/plugins/sort.
 ([bde2c394](https://github.com/Elgg/Elgg/commit/bde2c39490ff0c5a5f322eeceb36e0fc6c657d81))
* Discussion feature has been pulled from the groups plugin into its
own independent plugin.

The following views are not available anymore:
 - object/groupforumtopic
 - river/object/groupforumtopic/create

Also the [object, groupforumtopic] subtype has been replaced
with the [object, discussion] subtype.

Nothing changes from the group owners' point of view. The discussions
feature is still available as a group tool and all old discussions
are intact.

Fixes: #5994
 ([a4e484e6](https://github.com/Elgg/Elgg/commit/a4e484e62db117531763c0487c4b04c098bfb909))
*
See http://jquery.com/upgrade-guide/1.9/ for how to move off jquery-migrate.

If you'd prefer to just add it back, you can use this code in your plugin's init:

```php
elgg_register_js('jquery-migrate', elgg_get_simplecache_url('jquery-migrate.js'), 'head');
elgg_load_js('jquery-migrate');
```

Also, define a `jquery-migrate.js` view containing the contents of the script.
 ([8f58da98](https://github.com/Elgg/Elgg/commit/8f58da9868c90a2aee2e998bf552f9bea899d13e))
*
Great care has been taken to make this change as backwards-compatible as possible,
so you should not need to update any view references right away. However, you are
certainly encouraged to move your JS and CSS views to their new, canonical
locations.

Certain uses of the `view,$view_name` and `view_vars,$view_name` hooks will not work.
See the docs on "View aliases" for more info.

Refs #8381
Fixes #8382
 ([881e2128](https://github.com/Elgg/Elgg/commit/881e212864dca61b1e1e1bd4240adacc64456ea2))
* The following views, functions and methods have been removed completely.

Removed views:

 - canvas/layouts/*
 - categories
 - categories/view
 - embed/addcontentjs
 - groups/left_column
 - groups/right_column
 - invitefriends/formitems
 - notifications/subscriptions/groupsform

Removed functions:

 - count_unread_messages()
 - delete_entities()
 - delete_object_entity()
 - delete_user_entity()
 - elgg_validate_action_url()
 - extend_view()
 - get_entities()
 - get_entities_from_access_id()
 - get_entities_from_access_collection()
 - get_entities_from_annotations()
 - get_entities_from_metadata()
 - get_entities_from_metadata_multi()
 - get_entities_from_relationship()
 - get_filetype_cloud
 - get_library_files()
 - get_views()
 - is_ip_in_array()
 - list_entities()
 - list_entities_from_annotations()
 - list_group_search()
 - list_registered_entities()
 - list_user_search()
 - menu_item()
 - make_register_object()
 - search_for_group()
 - search_for_object()
 - search_for_site()
 - search_for_user()
 - search_list_objects_by_name()
 - search_list_groups_by_name()
 - search_list_users_by_name()
 - test_ip()

Removed methods:

 - ElggCache::set_variable()
 - ElggCache::get_variable()
 - ElggData::initialise_attributes()
 - ElggData::getObjectOwnerGUID()
 - ElggDiskFilestore::make_directory_root()
 - ElggDiskFilestore::make_file_matrix()
 - ElggDiskFilestore::user_file_matrix()
 - ElggDiskFilestore::mb_str_split()
 - ElggEntity::clearMetadata()
 - ElggEntity::clearRelationships()
 - ElggEntity::clearAnnotations()
 - ElggEntity::getOwner()
 - ElggEntity::setContainer()
 - ElggEntity::getContainer()
 - ElggEntity::getIcon()
 - ElggEntity::setIcon()
 - ElggExtender::getOwner()
 - ElggFileCache::create_file()
 - ElggSite::getCollections()
 - ElggUser::getCollections()
 - ElggUser::getOwner()

Also the following arguments have been dropped:

 - ElggSite::getMembers()
   - 2: $offset
 - elgg_view_entity_list()
   - 3: $offset
   - 4: $limit
   - 5: $full_view
   - 6: $list_type_toggle
   - 7: $pagination
 ([852b2640](https://github.com/Elgg/Elgg/commit/852b2640474d6c4fd6220fbd858256621e89d6b2))
* Several internal data structures are no longer stored within the plugin-
accessible config space; the removed keys are listed in docs/guides/upgrading.rst.
The long-deprecated remove_blacklist() function has also been removed. ([2247ed80](https://github.com/Elgg/Elgg/commit/2247ed808392a16a2fc9e3375de3888427e9db27))
* Relationship additions only fire the “create”, “relationship” event. ([3517bd0f](https://github.com/Elgg/Elgg/commit/3517bd0fef6dd6d4b548baff9a84fbb4b3c70e2a))
* Elgg no longer allows customizing the views template handler.
We don't think this ever really worked in the first place, so
probably no one was using it, but since it's conceivable someone
could be, we're leaving this warning.

Fixes #8440
 ([8ae86f16](https://github.com/Elgg/Elgg/commit/8ae86f16fedc875cf466955f04db360aa9471823))
* If your theme or plugin was overriding or extending the 'css' view,
you should override/extend the 'css/elgg' view instead.
 ([51441706](https://github.com/Elgg/Elgg/commit/514417063bd9a2ae89822050e0f5d6b893248bd1))
* The view js/admin and the namespace elgg.admin are not available anymore.
 ([7141da5f](https://github.com/Elgg/Elgg/commit/7141da5fe2038baa6eb5e38faad479da935f44eb))
* To ensure your handler is called last, you must give it the highest priority
of all matching handlers. To ensure your handler is called first, you must
give it the lowest priority of all matching handlers. Registering with the
keyword “all” no longer has any effect on calling order.
 ([3e6a2898](https://github.com/Elgg/Elgg/commit/3e6a28984bcbcb8e0698ca716fac924dc25fd10e))
* Several views that were deprecated in the 1.x series are being
removed in 2.x. This helps us keep the codebase clean and our
maintenance burden lower.
 ([805ecb1d](https://github.com/Elgg/Elgg/commit/805ecb1de083fd2a16e739afa487a03537bae85e))
* This removes several views related to IE. If you need support for browsers
not listed in our browser support policy, we encourage you to to do so with
feature detection and polyfills rather than conditional comments and user-agent
sniffing.
 ([7fe9329e](https://github.com/Elgg/Elgg/commit/7fe9329e648b82d1ffaa636d593a75b2f54f8c09))
* If you use the class FilePluginFile in your plugin, replace this usage
with ElggFile (for construction). Load files objects with get_entity().

Fixes #7763 ([6be0f97c](https://github.com/Elgg/Elgg/commit/6be0f97cb6f61f2f2de6f269abd2e7554324101c))
* Any code that relies of mysql_* functions (expecting an implicit
connection) will fail. Also any handler functions passed to the
execute_delayed_read/write_query() function will now receive a
Doctrine\DBAL\Driver\Statement instead of an ext/mysql resource.
 ([96453494](https://github.com/Elgg/Elgg/commit/96453494f9ec1ef5152582737cb5d5719ca7e55b))
* We are switching to `Zend\Mail` for sending emails in Elgg 2.0.
It's likely that there are some edge cases that the library
handles differently than Elgg 1.x used to. Take care to test
your email notifications carefully when upgrading to 2.0.

Fixes #5918
 ([e9de196d](https://github.com/Elgg/Elgg/commit/e9de196dfc7291a5870751f65a6ddee0952ef9cf))
* For the best security and performance, serve all pages over HTTPS
by switching the scheme in your site's wwwroot to `https` at
http://yoursite.tld/admin/settings/advanced

Fixes #5729
 ([c0e81a40](https://github.com/Elgg/Elgg/commit/c0e81a40730090c4d419a5101d375838ea2eac2d))
* If you use any inline scripts that depend on scripts in head, you'll need to
change them to external AMD modules and load them with `elgg_require_js`.

Fixes #2718 ([c91f1f3e](https://github.com/Elgg/Elgg/commit/c91f1f3e5b0c825e34feae248a1a3ff5a5e2b640))

<a name="1.12.18"></a>
### 1.12.18  (2019-04-03)

#### Contributors

* Jyoti Raval (1)
* Wouter van Os (1)

#### Bug Fixes

* **core:** revert original libxml_use_internal_errors value after use ([bc30e941](https://github.com/Elgg/Elgg/commit/bc30e941071b25c42101c8843b4918a171044027))

<a name="1.12.17"></a>
### 1.12.17  (2017-09-21)

#### Contributors

* Jerôme Bakker (3)
* Brett Profitt (1)
* Steve Clay (1)

#### Bug Fixes

* **likes:** listing limit no longer breaks likes counts ([abbe2715](https://github.com/Elgg/Elgg/commit/abbe27151654a5cbc22b246d8b26e7a2087a4067), closes [#11160](https://github.com/Elgg/Elgg/issues/11160))

<a name="1.12.16"></a>
### 1.12.16  (2017-05-10)

#### Contributors

* Steve Clay (3)
* iionly (2)
* Jerôme Bakker (1)

#### Documentation

* **admin:** start upgrade by logging in/clearing caches ([cf78468a](https://github.com/Elgg/Elgg/commit/cf78468a95c1f6c202872905be5ece61fe3dd7e1), closes [#10898](https://github.com/Elgg/Elgg/issues/10898))


#### Bug Fixes

* **groups:** remove membership request when user is already member ([4f158e1d](https://github.com/Elgg/Elgg/commit/4f158e1d7f1036b725d04048632bd8b615dc9f4d))
* **htaccess:** removing of obsolete entry in htaccess.dist incompatible with Apache 2.4 ([f2e8efab](https://github.com/Elgg/Elgg/commit/f2e8efab96778f60cfe264114215cb860550f5b8))
* **search:**
  * no longer fatals if comment container hidden ([16a753a9](https://github.com/Elgg/Elgg/commit/16a753a90e50138ebdaa256749d6a571fb3c46d7), closes [#10902](https://github.com/Elgg/Elgg/issues/10902))
  * allows get_sql, access hook to be used correctly ([98835bc4](https://github.com/Elgg/Elgg/commit/98835bc43bc9503be2cb41f2610a5648f079ae0e), closes [#10884](https://github.com/Elgg/Elgg/issues/10884))

<a name="1.12.15"></a>
### 1.12.15  (2017-01-25)

#### Contributors

* Johnny Mast (1)
* jdalsem (1)

#### Bug Fixes

* **views:** corrected syntax error in input/date ([a7277f30](https://github.com/Elgg/Elgg/commit/a7277f307596f19dbc3c8415c9048a20a8493287))

<a name="1.12.14"></a>
### 1.12.14  (2016-11-08)

#### Contributors

* Jerôme Bakker (3)
* Ismayil Khayredinov (1)
* Steve Clay (1)

* **core:**
  * outgoing email should have a message-id header ([9953687f](https://github.com/Elgg/Elgg/commit/9953687fecb570793cd273bfedc98394995de737))
  * _elgg_send_email_notification respects other email handlers ([80bd413d](https://github.com/Elgg/Elgg/commit/80bd413d3dea1d30b1257d30fe877e6c2c1fa7b4))
  * elgg_get_page_owner_entity will return ElggEntity ([9f8e8dda](https://github.com/Elgg/Elgg/commit/9f8e8dda8a1347074472bf2010ee5520ac4e90cd))
* **register:** consistent forwarding upon login ([a62410dd](https://github.com/Elgg/Elgg/commit/a62410dd5b00e4dfc02d725c1fd777c87c73b186))
* **relationships:** ElggRelationship::save returns the ID ([25754c76](https://github.com/Elgg/Elgg/commit/25754c76e50838ceac4c11ddd4d1ba09b03f2727), closes [#10373](https://github.com/Elgg/Elgg/issues/10373))

<a name="1.12.13"></a>
### 1.12.13  (2016-10-02)

#### Contributors

* Jerôme Bakker (3)
* Steve Clay (2)
* Sébastien Lemay (1)

#### Bug Fixes

* **core:** page owner entity only returns users/groups user can see ([0d333100](https://github.com/Elgg/Elgg/commit/0d33310031024aa8940f9d3bbe5cdd80f2a4da57))
* **docs:** Fixed link to 'our supporters page' ([fe144644](https://github.com/Elgg/Elgg/commit/fe144644faa84f9849604abfd4c34efb2df0d165))
* **notifications:** only prefetch subscribers for ElggEntities ([65be05c5](https://github.com/Elgg/Elgg/commit/65be05c567692694e052ae4711450b7c19f2d571))
* **profile:** use correct default access for profile fields ([63a01b6f](https://github.com/Elgg/Elgg/commit/63a01b6f8c9314ddae5819e8194938c988871a60))


<a name="1.12.12"></a>
### 1.12.12  (2016-08-05)

#### Contributors

* Steve Clay (11)
* Ismayil Khayredinov (2)

#### Bug Fixes

* **blog:** pass entity to the object/elements/full ([134c5b83](https://github.com/Elgg/Elgg/commit/134c5b837c476f36ff52ecbe4e1e5b213833df31))
* **groups:** unfeaturing a group no longer leaves useless metadata ([2f3cf28a](https://github.com/Elgg/Elgg/commit/2f3cf28ac0d879fa13a874e99227faad6ef5bb4c))
* **logging:** make clearer exception numbers are timestamps ([9c70596e](https://github.com/Elgg/Elgg/commit/9c70596e591198a72a713218ecdaf780f33539c2), closes [#9924](https://github.com/Elgg/Elgg/issues/9924))
* **plugins:** now checks plugin instances before parsing priorities ([26d21f88](https://github.com/Elgg/Elgg/commit/26d21f886c809517890e8264ee4a6181293cad50))


<a name="1.12.11"></a>
### 1.12.11  (2016-06-13)

#### Contributors

* Ismayil Khayredinov (2)
* Steve Clay (2)
* Matt Beckett (1)
* ura soul (1)

#### Bug Fixes

* **river:** custom joins can now reference default joined tables. ([a6590a9a](https://github.com/Elgg/Elgg/commit/a6590a9a68f534b8ae6bde274bf642bec301f4a3), closes [#8580](https://github.com/Elgg/Elgg/issues/8580))
* **ui:** improves usability of anchors within system messages ([30e3ad6c](https://github.com/Elgg/Elgg/commit/30e3ad6cf32fc467be0d83cbb44481f4c6a92ffa))
* **walled_garden:** favicon.ico page handler is now treated as public ([53f11c43](https://github.com/Elgg/Elgg/commit/53f11c436ec4101fccb2d2a39bd4cdceaeeff779))


<a name="1.12.10"></a>
### 1.12.10  (2016-05-29)

#### Contributors

* Steve Clay (13)
* Jeroen Dalsem (6)
* Ismayil Khayredinov (4)
* Jerôme Bakker (1)
* bruno-infotrad (1)
* iionly (1)
* jdalsem (1)

#### Documentation

* **cron:** added an example how to register a cron hook handler ([4b54a099](https://github.com/Elgg/Elgg/commit/4b54a099e7ddc5cacdf214d5ea383eddc03b255f))
* **i18n:**
  * clarifies limitations of elgg.echo ([83b2c106](https://github.com/Elgg/Elgg/commit/83b2c106d5ac671ee28e28625659392de4d34b0b))
  * recommends an English translation for all new language keys ([facc222b](https://github.com/Elgg/Elgg/commit/facc222b417b710449963d078d294d231c6c2217), closes [#9375](https://github.com/Elgg/Elgg/issues/9375))


#### Bug Fixes

* **annotations:** fixes time-based annotations searches ([6d5e1b78](https://github.com/Elgg/Elgg/commit/6d5e1b781866dc4b79300f57424873049314a6ff), closes [#9785](https://github.com/Elgg/Elgg/issues/9785))
* **autoload:** fixes bugs in class map saving ([18ea55fa](https://github.com/Elgg/Elgg/commit/18ea55fa990fab9724cbbd12365d754df19cbde2))
* **comments:** email subject hook now validates array structure ([5df7f40b](https://github.com/Elgg/Elgg/commit/5df7f40b8cba8f5a61afcfbb4b3d2086d49b54ee), closes [#9772](https://github.com/Elgg/Elgg/issues/9772))
* **core:** remove duplicate tags upon input ([096cf4b8](https://github.com/Elgg/Elgg/commit/096cf4b8b0cb7be801490d9c2cb983b5580c771b))
* **css:** only apply vertical padding on ajax loader of user hover menu ([d9c8326e](https://github.com/Elgg/Elgg/commit/d9c8326eebe01a7197a3505f311b419a900ee097))
* **file:**
  * re-added cannotload language string for file plugin ([6ba7e7b8](https://github.com/Elgg/Elgg/commit/6ba7e7b8c4cebc3b13862094124b1246686bd917))
  * better uploaded file handling and thumbnail serving ([72140cfc](https://github.com/Elgg/Elgg/commit/72140cfc3cdd6f6677eda84399cf6ca117ea44e2), closes [#9612](https://github.com/Elgg/Elgg/issues/9612), [#9267](https://github.com/Elgg/Elgg/issues/9267), [#6677](https://github.com/Elgg/Elgg/issues/6677))
* **forms:**
  * empty plugin settings forms no longer wrapped in a form tag ([5f7dbcd0](https://github.com/Elgg/Elgg/commit/5f7dbcd0d8006f41c230e34fc96ed13e0cd5ad71), closes [#9704](https://github.com/Elgg/Elgg/issues/9704))
  * empty form body no longer wrapped in a form tag ([dc68d04e](https://github.com/Elgg/Elgg/commit/dc68d04ec92a482b561dfd00c0158838bf83dd43))
* **html:** autop no longer ltrims next to a leading inline element ([6785ee88](https://github.com/Elgg/Elgg/commit/6785ee88278859c87a5569428656d6870f69fa3d), closes [#9790](https://github.com/Elgg/Elgg/issues/9790))
* **http:** all unhanded exceptions send 500 status code ([2f45c2ca](https://github.com/Elgg/Elgg/commit/2f45c2ca96120352f0c0e3b8556e103924a3cc82), closes [#9758](https://github.com/Elgg/Elgg/issues/9758))
* **js:**
  * elgg.echo recognizes empty translation strings (does not return key) ([1d32b2c2](https://github.com/Elgg/Elgg/commit/1d32b2c2b35cf965af1d703d74fb5adeb2599336))
  * action token refresh now correctly replaces tokens in urls ([7b4e0db2](https://github.com/Elgg/Elgg/commit/7b4e0db2f8a1357e854a5a94c990e6b32683e12f))
  * do not trigger generic ajax error message for token refresh ([e052481f](https://github.com/Elgg/Elgg/commit/e052481fa31102e78c6a7be9fd5730d730579984))
  * prevent generic ajax error when user aborts the ajax call ([1b5a765f](https://github.com/Elgg/Elgg/commit/1b5a765fae08ada5dd4128ad69ccfb82d0afc2c3))
* **notifications:** smtp thread headers only set if still possible ([f3bb4ac4](https://github.com/Elgg/Elgg/commit/f3bb4ac43b6c14b6367fa17960cd95d2bb6dcb98))
* **site:** adds robots.txt to public pages ([824197b1](https://github.com/Elgg/Elgg/commit/824197b183e98f6910b6bf43a14e5407af8cc09d))
* **users:** mitigate race conditions when deleting/disabling users ([da45affe](https://github.com/Elgg/Elgg/commit/da45affef3fa16106305ab424a51fc69518ca66b))
* **views:**
  * no breadcrumbs in error layout ([b25324a3](https://github.com/Elgg/Elgg/commit/b25324a3214147217cbdbc233a0163d3e55828ed))
  * do not output empty string titles in page/elements/title ([41eecbe3](https://github.com/Elgg/Elgg/commit/41eecbe37d2c54d290b8f96cf8187db2a151d3b5))

<a name="1.12.9"></a>
### 1.12.9  (2016-03-06)

#### Contributors

* Steve Clay (7)
* Ismayil Khayredinov (2)
* Jerôme Bakker (1)
* Juho Jaakkola (1)

#### Bug Fixes

* **cache:** internal Stash pool wrapper works again ([ddc254e4](https://github.com/Elgg/Elgg/commit/ddc254e40f9a30a6473f042b7fb686767447a2f0), closes [#9374](https://github.com/Elgg/Elgg/issues/9374))
* **css:** buttons no longer get cropped in admin context ([298ae0a8](https://github.com/Elgg/Elgg/commit/298ae0a8f90ca2bc3688a3d5cf3d6205a75f14d2))
* **groups:** correctly format the remove user from group menu item ([8fdf21f5](https://github.com/Elgg/Elgg/commit/8fdf21f5303e709593b59a75eb9e24d263ac83d5))
* **http:**
  * cache handler sends 304 responses more reliably ([873be892](https://github.com/Elgg/Elgg/commit/873be8921d4b8ddc2fd6caf4f5bfa8ca05a93379), closes [#9427](https://github.com/Elgg/Elgg/issues/9427))
  * more resources sent with explicit UTF-8 charset ([036a82bd](https://github.com/Elgg/Elgg/commit/036a82bd78da47bb6963a87e5f68b1eef88d85eb), closes [#9345](https://github.com/Elgg/Elgg/issues/9345))
  * make sure all pages/JS/CSS sent with explicit UTF-8 charset ([3dab7d10](https://github.com/Elgg/Elgg/commit/3dab7d100641f5ad3e91353106e7bfd8693d63e0), closes [#9345](https://github.com/Elgg/Elgg/issues/9345))
* **js:** AMD view filter handles short view names without `/` ([c9ca8329](https://github.com/Elgg/Elgg/commit/c9ca83295eb9e95f57ad74983386109ee4d6ab59))
* **search:** eliminate 6 notices in search_users_hook ([87b7011b](https://github.com/Elgg/Elgg/commit/87b7011bd843125cb9db16306478c7ccab0c6f1a))
* **settings:** do not emit errors if form fields are not present ([9f5111c9](https://github.com/Elgg/Elgg/commit/9f5111c97f79611734a9b32317ae0e729928a941))
* **web_services:** reject requests for unavailable formats ([04aeaf7d](https://github.com/Elgg/Elgg/commit/04aeaf7d35ec25a08f011a74bb9d1947b6ac260a), closes [#9410](https://github.com/Elgg/Elgg/issues/9410))


<a name="1.12.8"></a>
### 1.12.8  (2016-01-31)

#### Contributors

* Ismayil Khayredinov (1)
* Juho Jaakkola (1)
* Steve Clay (1)

#### Bug Fixes

* **access:** fixes guid column name in metadata queries ([b3427ccc](https://github.com/Elgg/Elgg/commit/b3427ccc81b0dd40188117a42366f1259cedd1a1))


<a name="1.12.7"></a>
### 1.12.7  (2016-01-03)

#### Contributors

* Steve Clay (4)
* Ismayil Khayredinov (1)
* Juho Jaakkola (1)

#### Bug Fixes

* **logging:** Log messages no longer discarded ([5020c525](https://github.com/Elgg/Elgg/commit/5020c5251f35c8bd83b5b1472eafd34d96a77a35), closes [#9244](https://github.com/Elgg/Elgg/issues/9244))
* **menus:** stricter type validation in menu item registration functions ([c5554a75](https://github.com/Elgg/Elgg/commit/c5554a75bb45acf9f27c13a8d58a0e099063cf26))
* **php:** Suppress mysql_connect() deprecation warnings for core ([40fe0a8f](https://github.com/Elgg/Elgg/commit/40fe0a8f4d36e7a3f0947c9f4148b7ccb8f0a0ee), closes [#9245](https://github.com/Elgg/Elgg/issues/9245))
* **session:** Session is again available in the shutdown event ([2409d346](https://github.com/Elgg/Elgg/commit/2409d346a95a1c5a254f32d51204054a939b7e95), closes [#9243](https://github.com/Elgg/Elgg/issues/9243))


<a name="1.12.6"></a>
### 1.12.6  (2015-12-14)

#### Contributors

* Ismayil Khayredinov (4)
* Juho Jaakkola (1)
* Steve Clay (1)

#### Bug Fixes

* **a11y:** display aalborg mobile site menu toggle in more browsers ([e96f0798](https://github.com/Elgg/Elgg/commit/e96f07987b943a4c72f654ef7896f2e98d7ac23e), closes [#9110](https://github.com/Elgg/Elgg/issues/9110))
* **actions:** send error HTTP header from action forward hook ([d3344de7](https://github.com/Elgg/Elgg/commit/d3344de7ed92d5ee8ecca43e474c6555861f5dad), closes [#9027](https://github.com/Elgg/Elgg/issues/9027))
* **entities:** update attribute when assuming container_guid value ([a21dd95e](https://github.com/Elgg/Elgg/commit/a21dd95ed76a4b6629c69fd7e000aabd2e7cce99), closes [#8981](https://github.com/Elgg/Elgg/issues/8981))
* **menus:** make sure entity passed to user hover menu hook is a user ([f5bbcc65](https://github.com/Elgg/Elgg/commit/f5bbcc652078a317479c8e845a8f4951f37d5435))
* **output:** fixes handling of untrusted URLs in output/url ([217e4df6](https://github.com/Elgg/Elgg/commit/217e4df6ea186660c85310a57e1218eb54ec90d1), closes [#9146](https://github.com/Elgg/Elgg/issues/9146))

#### Breaking changes

* Plugins that customized `.elgg-button-nav` (or the spans inside) will need
to be altered. The `.elgg-icon` CSS is left in place but will be removed in
Elgg 2.0. ([e96f0798](https://github.com/Elgg/Elgg/commit/e96f07987b943a4c72f654ef7896f2e98d7ac23e))


<a name="1.12.5"></a>
### 1.12.5  (2015-11-29)

#### Contributors

* Steve Clay (5)
* Juho Jaakkola (4)
* Ismayil Khayredinov (3)

#### Performance

* **river:** no longer needlessly render river responses ([97df230f](https://github.com/Elgg/Elgg/commit/97df230f4c496d773e50060bf84fef5ae7052b24), closes [#9046](https://github.com/Elgg/Elgg/issues/9046))


#### Bug Fixes

* **files:** make sure method is callable on a concrete object instance ([740d3108](https://github.com/Elgg/Elgg/commit/740d3108a30733d02a98e9aed7516f92033cd8a9), closes [#9010](https://github.com/Elgg/Elgg/issues/9010))
* **i18n:** avoids using mbstring.internal_encoding in PHP >= 5.6 ([c0ff79de](https://github.com/Elgg/Elgg/commit/c0ff79de100cc8e48fd69d01883c946669b5b275), closes [#9031](https://github.com/Elgg/Elgg/issues/9031))
* **memcache:** don't store a copy of $CONFIG in file objects ([beb90891](https://github.com/Elgg/Elgg/commit/beb9089129a0a06b36200f3f8d214c7ed8f94f42), closes [#9081](https://github.com/Elgg/Elgg/issues/9081))
* **pages:** removes deprecated notices regarding input/write_access ([fdcab74b](https://github.com/Elgg/Elgg/commit/fdcab74b1e9069736f88f7e9aa36aeb15067b8fe), closes [#8327](https://github.com/Elgg/Elgg/issues/8327))


<a name="1.12.4"></a>
### 1.12.4  (2015-09-20)

#### Contributors

* Steve Clay (3)
* Juho Jaakkola (2)
* Matt Beckett (2)

#### Documentation

* **entities:** Docs for type/subtype and associated ege() options ([eb0e53fc](https://github.com/Elgg/Elgg/commit/eb0e53fcd1df1ee249d8e79cb1eaafc5249e88e8))
* **releases:** Manually check lang files for PHP errors before release ([040079b7](https://github.com/Elgg/Elgg/commit/040079b74fc30f6d1430ce086aa35681b8188b0a), closes [#8924](https://github.com/Elgg/Elgg/issues/8924))

#### Bug Fixes

* **pages:** Prevent public "Missing access level" in write access field ([4174b774](https://github.com/Elgg/Elgg/commit/4174b77438068ff583c0c9fb8866f00fd26d9421), closes [#8905](https://github.com/Elgg/Elgg/issues/8905))
* **relationships:** can now prevent relationships using event handler ([9a275d9c](https://github.com/Elgg/Elgg/commit/9a275d9c9dce532a8a837a758e5fa241f02e2246), closes [#8927](https://github.com/Elgg/Elgg/issues/8927))


<a name="1.12.3"></a>
### 1.12.3  (2015-09-06)

#### Contributors

* Ismayil Khayredinov (3)
* Juho Jaakkola (1)

#### Bug Fixes

* **files:**
  * $file is not always an object with originalfilename property ([cf0929c2](https://github.com/Elgg/Elgg/commit/cf0929c243b69f4018e77640e1e175f8d93670ea))
  * now uses filename on filestore to detect mime ([ab8086a6](https://github.com/Elgg/Elgg/commit/ab8086a61feb87eb0c8d3a89fc6649c7e603a4b4), closes [#8846](https://github.com/Elgg/Elgg/issues/8846))


<a name="1.12.2"></a>
### 1.12.2  (2015-08-23)

#### Contributors

* Jerôme Bakker (4)
* Evan Winslow (1)
* Juho Jaakkola (1)
* Steve Clay (1)

#### Documentation

* **entities:** Better docs for fetching relationships ([e0d8f793](https://github.com/Elgg/Elgg/commit/e0d8f793daeefb32f9c14e8ff6e15defa5078708))

#### Bug Fixes

* **friends:** site_notifications have a clickable link to the new friend ([55a0f9b0](https://github.com/Elgg/Elgg/commit/55a0f9b0c2c29aca21fb47c8e8b0423140aac262))
* **groups:**
  * membership request accepted has link in site_notifications ([6e0d6f4a](https://github.com/Elgg/Elgg/commit/6e0d6f4ab8217d02f5b294904b4cfff975cc867e))
  * group invite has link in site_notifications ([61a8484d](https://github.com/Elgg/Elgg/commit/61a8484d669835c7ab756ad96436823f78430f5c))
  * membership request has a link in site_notifications ([ba53c509](https://github.com/Elgg/Elgg/commit/ba53c509579988cb8beb753b027cf66b97375596))


<a name="1.12.1"></a>
### 1.12.1  (2015-08-05)

#### Contributors

* Evan Winslow (1)

<a name="1.12.0"></a>
## 1.12.0  (2015-07-07)

#### Contributors

* Steve Clay (23)
* Evan Winslow (11)
* Ismayil Khayredinov (9)
* Miloš (4)
* Jeroen Dalsem (3)
* Jerôme Bakker (2)
* Jon Maul (1)
* Juho Jaakkola (1)

#### Features

* **ajax:**
  * elgg_ajax_gatekeeper for asserting resources as Ajax-only ([4e0e1a5b](https://github.com/Elgg/Elgg/commit/4e0e1a5b9aa277d9fe14f484122cd2c89ba99fa4))
  * Allows fetching form views wrapped by elgg_view_form() ([ee7641c4](https://github.com/Elgg/Elgg/commit/ee7641c4cbff8896d4e9618c206a10a314a37281))
* **annotations:** Adds a more granular permission hook for canAnnotate ([83da5f18](https://github.com/Elgg/Elgg/commit/83da5f1896730045fbf9de313669f8c604c44c8a))
* **cache:** introducing a generic function to reset all caches ([f526c479](https://github.com/Elgg/Elgg/commit/f526c479fd99f50cab008f5fc0b3c18069e83064))
* **comments:** Paging through comments/discussion replies jumps to content ([b75fd8f8](https://github.com/Elgg/Elgg/commit/b75fd8f84dff20354da4c65de2ba0b9df0ac370f))
* **developers:**
  * Devs can show loaded AMD modules in console ([221bdf6a](https://github.com/Elgg/Elgg/commit/221bdf6a2ff41cd6f2ef63169363c4c127b7f993))
  * Adds a quick access icon for some admin settings ([f22567b6](https://github.com/Elgg/Elgg/commit/f22567b69283e77bc23743669acb409130ad73cf))
* **events:**
  * Adds static methods for returning common values ([f080fed1](https://github.com/Elgg/Elgg/commit/f080fed1dfbd1a982cc2dda3110cc74f58ad028d))
  * allows dynamic method callbacks to be unregistered ([08c773ba](https://github.com/Elgg/Elgg/commit/08c773bac7e3566dcf40498e0f68bea042aae9f4), closes [#7750](https://github.com/Elgg/Elgg/issues/7750))
* **lightbox:** More sensible handling of href options ([765fcd05](https://github.com/Elgg/Elgg/commit/765fcd0563239b76b1b2a0bb0c0d3d5d719edf63))
* **views:** Users can jump directly to content via prev/next links ([f90466c8](https://github.com/Elgg/Elgg/commit/f90466c80ba2b9d2ac8ccad3d50921b43897debc))


#### Performance

* **developers:** reduces boot queries when the developers mod is enabled ([03aa096e](https://github.com/Elgg/Elgg/commit/03aa096e3281c260ac25e2147d066a61534aad31))
* **engine:** only update attributes/metadata if value is changed ([8295e70a](https://github.com/Elgg/Elgg/commit/8295e70a2c8381f15158a919a1870d279228bdc0))


#### Documentation

* **ajax:** Correct typo in code example ([d55e4a3a](https://github.com/Elgg/Elgg/commit/d55e4a3a8d3ac1dd424b7c8bb07150cede0a85dd))
* **code:** Clarify interface naming convention ([de03d372](https://github.com/Elgg/Elgg/commit/de03d372554b250f08ad510542dd6ac88138f055), closes [#8293](https://github.com/Elgg/Elgg/issues/8293))
* **misc:** Miscellaneous docs fixes ([06e3557c](https://github.com/Elgg/Elgg/commit/06e3557cd0046bfb601b3345f6f636cdbbb63de9))
* **permissions:** Fix $params key name for permissions_check:annotate ([1af6e3a2](https://github.com/Elgg/Elgg/commit/1af6e3a250ad18b9c94c2d9048b04311e0fe29a2))
* **views:** Better document outgoing elgg_get_view_location() ([0a9059d6](https://github.com/Elgg/Elgg/commit/0a9059d60049693350379456253c3f9ec9783156))
* **web_security:** adds security warnings ([d47fc5ed](https://github.com/Elgg/Elgg/commit/d47fc5ed4cf7b53bd8bfd33190dc06fbf2ae1772))
* **web_services:** document the use of parameters in method declarations ([750e31b9](https://github.com/Elgg/Elgg/commit/750e31b988131c53a90be987daa33229b1573f75))


#### Bug Fixes

* **embed:** embed link no longer leaves the page before events are set up ([f50e9aa3](https://github.com/Elgg/Elgg/commit/f50e9aa3eeafa9ec2d8d21cc6de715352c5bb19d), closes [#8284](https://github.com/Elgg/Elgg/issues/8284))
* **http:** More appropriate exception responses ([e28f37e6](https://github.com/Elgg/Elgg/commit/e28f37e6790edbad04fd6918a52f732202e8ca70), closes [#6228](https://github.com/Elgg/Elgg/issues/6228), [#8360](https://github.com/Elgg/Elgg/issues/8360))
* **legacy_urls:**
  * adds missing forwarder for groups/forum/$guid ([2b555f88](https://github.com/Elgg/Elgg/commit/2b555f886cc4b348d38986c86bc1fb6ad041bac6), closes [#8493](https://github.com/Elgg/Elgg/issues/8493))
  * unset __elgg_uri to prevent infinite loops ([0c7687ac](https://github.com/Elgg/Elgg/commit/0c7687acdf1f92066af0fc9cb32673e8da0f8859), closes [#8494](https://github.com/Elgg/Elgg/issues/8494))
* **profile:** Avatar cropper again can be moved immediately after uploading image ([d8cf51b7](https://github.com/Elgg/Elgg/commit/d8cf51b7d4e718f8a67fa8d26a11e697851820bc), closes [#8449](https://github.com/Elgg/Elgg/issues/8449))
* **relationships:** Invalid relationship names throw properly ([ac976e23](https://github.com/Elgg/Elgg/commit/ac976e23394f0dcba2f6b473b7f63a57082cf5d5))
* **search:** Search treats "0" as a valid query ([af58fa5d](https://github.com/Elgg/Elgg/commit/af58fa5d1adc8a7747ee3fd5d994e5852dc06f47))
* **ui:** prevent button jumping on widget add panel toggle ([088de48d](https://github.com/Elgg/Elgg/commit/088de48dda3632cb57ba9fdc16f239c084cd0fee))
* **web_services:** no longer uses deprecated export global ([3a818d2b](https://github.com/Elgg/Elgg/commit/3a818d2b6c70e170937854be42f43cb496449f62))


#### Deprecations

* **views:**
  * elgg_get_view_location is going away in 2.0 ([b4347fb4](https://github.com/Elgg/Elgg/commit/b4347fb4209dd1a09d5ad0d1ef2d546169aeb5b9))
  * Support for custom template handlers will end soon ([0dc67698](https://github.com/Elgg/Elgg/commit/0dc67698f6def5fa6cea32dd1171d1166e9c4e29))


<a name="1.11.6"></a>
### 1.11.6  (2016-06-12)

#### Contributors

* Steve Clay (3)

#### Breaking Changes

* The JS function `elgg.security.setToken` is now formally marked private and
its parameters are not backwards compatible. ([9d8ddecb](https://github.com/Elgg/Elgg/commit/9d8ddecb90b9e160ad85610592c5808e7e8f0c3f))


<a name="1.11.5"></a>
### 1.11.5  (2015-12-13)

#### Contributors

* Steve Clay (1)
* Juho Jaakkola (1)

#### Bug Fixes

* **views:** Sticky values now get passed into views extending register/extend (Fixes [#8873](https://github.com/Elgg/Elgg/issues/8873))
* **memcache:** don't store a copy of $CONFIG in file objects (Fixes [#9081](https://github.com/Elgg/Elgg/issues/9081))


<a name="1.11.4"></a>
### 1.11.4  (2015-07-07)

#### Contributors

* Ismayil Khayredinov (7)
* Evan Winslow (5)
* Miloš (4)
* Steve Clay (4)
* Jeroen Dalsem (1)

#### Documentation

* **misc:** Miscellaneous docs fixes ([06e3557c](https://github.com/Elgg/Elgg/commit/06e3557cd0046bfb601b3345f6f636cdbbb63de9))
* **permissions:** Fix $params key name for permissions_check:annotate ([1af6e3a2](https://github.com/Elgg/Elgg/commit/1af6e3a250ad18b9c94c2d9048b04311e0fe29a2))
* **web_security:** adds security warnings ([d47fc5ed](https://github.com/Elgg/Elgg/commit/d47fc5ed4cf7b53bd8bfd33190dc06fbf2ae1772))
* **web_services:** document the use of parameters in method declarations ([750e31b9](https://github.com/Elgg/Elgg/commit/750e31b988131c53a90be987daa33229b1573f75))


#### Bug Fixes

* **legacy_urls:**
  * adds missing forwarder for groups/forum/$guid ([2b555f88](https://github.com/Elgg/Elgg/commit/2b555f886cc4b348d38986c86bc1fb6ad041bac6), closes [#8493](https://github.com/Elgg/Elgg/issues/8493))
  * unset __elgg_uri to prevent infinite loops ([0c7687ac](https://github.com/Elgg/Elgg/commit/0c7687acdf1f92066af0fc9cb32673e8da0f8859), closes [#8494](https://github.com/Elgg/Elgg/issues/8494))
* **profile:** Avatar cropper again can be moved immediately after uploading image ([d8cf51b7](https://github.com/Elgg/Elgg/commit/d8cf51b7d4e718f8a67fa8d26a11e697851820bc), closes [#8449](https://github.com/Elgg/Elgg/issues/8449))
* **relationships:** Invalid relationship names throw properly ([ac976e23](https://github.com/Elgg/Elgg/commit/ac976e23394f0dcba2f6b473b7f63a57082cf5d5))
* **ui:** prevent button jumping on widget add panel toggle ([088de48d](https://github.com/Elgg/Elgg/commit/088de48dda3632cb57ba9fdc16f239c084cd0fee))
* **web_services:** no longer uses deprecated export global ([3a818d2b](https://github.com/Elgg/Elgg/commit/3a818d2b6c70e170937854be42f43cb496449f62))


<a name="1.11.3"></a>
### 1.11.3  (2015-06-14)

#### Contributors

* Steve Clay (6)
* Evan Winslow (4)
* Juho Jaakkola (1)
* Julien Boulen (1)
* Marcus Povey (1)
* Matt Beckett (1)

#### Documentation

* **code:** Permit use of `<?=` PHP shortcut since we're on 5.4+ ([453d8dcb](https://github.com/Elgg/Elgg/commit/453d8dcb9f90b0e210a7233aef1172b949133841))
* **hooks:** Clarifies docs for the register, user hook ([b877f61d](https://github.com/Elgg/Elgg/commit/b877f61de13a293f1d32c9dc345cd3cc8a51121a), closes [#8377](https://github.com/Elgg/Elgg/issues/8377))
* **install:** Update cloud9 install instructions ([616f2156](https://github.com/Elgg/Elgg/commit/616f21563dc92613e279bebc40419bf0a6339dde), closes [#8240](https://github.com/Elgg/Elgg/issues/8240))


#### Bug Fixes

* **IDE:** Public APIs no longer marked with @internal ([11ccf71c](https://github.com/Elgg/Elgg/commit/11ccf71c5bb9b7d64ba9e834568275da853c7e65), closes [#7714](https://github.com/Elgg/Elgg/issues/7714))
* **db:** Will now validate invite codes that contain "-" characters. ([6667c05f](https://github.com/Elgg/Elgg/commit/6667c05f35c2dd33453c6e22b1709d10c9f52929))
* **notification:** Removes warning handling the email, system hook ([91daee43](https://github.com/Elgg/Elgg/commit/91daee43b6a5cf388640d592117ef808ce838013), closes [#8333](https://github.com/Elgg/Elgg/issues/8333))
* **relationships:** Restores functionality of `$inverse_relationship` argument for `get_entity_relationships` ([3cc06f11](https://github.com/Elgg/Elgg/commit/3cc06f11816a13dcb688c32ab7cd96054fa8d2a7))
* **spinner:** elgg/spinner delays a bit before displaying ([70cfdd01](https://github.com/Elgg/Elgg/commit/70cfdd01e277915674c7c3bfbd32e1f3eb7c8de7), closes [#8361](https://github.com/Elgg/Elgg/issues/8361))
* **users:** admins are again able to reset user's password ([2b4d599e](https://github.com/Elgg/Elgg/commit/2b4d599ec6bda474de61bde9eff70c1eadab5b0a))


<a name="1.11.2"></a>
### 1.11.2  (2015-05-25)

#### Contributors

* Steve Clay (12)
* Ismayil Khayredinov (5)
* Evan Winslow (2)
* Jeroen Dalsem (2)
* Juho Jaakkola (2)
* Ariel Abrams-Kudan (1)
* Jerôme Bakker (1)
* Juho Jaakkola (1)

#### Performance

* **views:** No longer regenerates the $vars[‘user’] wrapper for each view ([3c40971a](https://github.com/Elgg/Elgg/commit/3c40971ada6c1123db64a2453cc617d9b6fc8635))


#### Documentation

* **ajax:** Adds more complete Ajax docs ([bfbf0ff2](https://github.com/Elgg/Elgg/commit/bfbf0ff212c1738f4884e60f3cb38ed17f11aaa0), closes [#8277](https://github.com/Elgg/Elgg/issues/8277))
* **amd:** Overhauls the AMD docs with a lot more detailed instructions ([e01996ab](https://github.com/Elgg/Elgg/commit/e01996ab241914ffd7d36d49d813275bc6f5827b))
* **auth:** Add basic APIs to the authentication docs ([83d5f214](https://github.com/Elgg/Elgg/commit/83d5f214b73efbf743af9bbbd6f17b772a977a5e))
* **guides:** Alpha-sort the developer guides to make them more scannable ([88a9d130](https://github.com/Elgg/Elgg/commit/88a9d130c4d4f24473f8e7f583b7959e5d35ba63))


#### Bug Fixes

* **ckeditor:** also remove liststyle as a default loaded plugin ([eb8235cb](https://github.com/Elgg/Elgg/commit/eb8235cba756b8bd615c62fd1d0fda374b9fcdcd), closes [#8195](https://github.com/Elgg/Elgg/issues/8195))
* **comments:** Ajax-saved comments show proper server formatting ([6f0f74cb](https://github.com/Elgg/Elgg/commit/6f0f74cb69c59c9587b2116dd011d898c88259a1), closes [#8294](https://github.com/Elgg/Elgg/issues/8294))
* **file:** Default file type icons again available in the theme sandbox ([6892979f](https://github.com/Elgg/Elgg/commit/6892979faf8878652f0bb5652ad0201e9864b3ee))
* **filestore:** Fixed a crash when forms had a file input but no file was provided ([2ada5d5a](https://github.com/Elgg/Elgg/commit/2ada5d5a76b72989a7b56ec9d7ae495639481d44))
* **groups:**
  * do not reassign container on ownership transfer if old container is not an old owner ([57cf337a](https://github.com/Elgg/Elgg/commit/57cf337a2a601d855a16d5e66fb1129eb4a4958d))
  * terminate edit action early if group can not be saved ([3fe10452](https://github.com/Elgg/Elgg/commit/3fe10452163ee151ca3cb98323f1b4823d06f043))
  * do not attempt to populate groups_entity table if base entity fails to save ([f2cbb237](https://github.com/Elgg/Elgg/commit/f2cbb23722aa60ab9449eadd958d98e7b585cab1))
* **menus:** only display location menu item if value is string ([e3a39167](https://github.com/Elgg/Elgg/commit/e3a39167dcbd538e39fc5c4679402d5db01e5c37))
* **mysql:** Use explicit ext/mysql resource in initial query/escaping ([b7abe8eb](https://github.com/Elgg/Elgg/commit/b7abe8eb033832318a61ae0554613670557c1df1), closes [#8208](https://github.com/Elgg/Elgg/issues/8208))
* **notifications:** fixed deprecation notice elgg-require-confirmation ([79bf7d42](https://github.com/Elgg/Elgg/commit/79bf7d4230568cebb60cafac842584f7d0b9f0ec))
* **river:** Activity page for specific user shows that user’s owner block ([5ecfe41d](https://github.com/Elgg/Elgg/commit/5ecfe41d5970af6351148fb12d4ed38d3cf23485), closes [#8257](https://github.com/Elgg/Elgg/issues/8257))
* **session:** Remember me no longer results in occasional fatal errors ([b91620c1](https://github.com/Elgg/Elgg/commit/b91620c1f3d43bb5df4b43add506005259dc7b78), closes [#8104](https://github.com/Elgg/Elgg/issues/8104))
* **ui:** Checkboxes and labels are again separated by a space ([1b62dd20](https://github.com/Elgg/Elgg/commit/1b62dd20df4b62ac851a57b1318d25b2f986978e), closes [#8199](https://github.com/Elgg/Elgg/issues/8199))
* **widgets:** validate get_list,default_widgets hook output ([b1c16311](https://github.com/Elgg/Elgg/commit/b1c16311ea3a129e5b885d34765d6b892b42130e))


<a name="1.11.1"></a>
### 1.11.1  (2015-04-27)

#### Contributors

* Steve Clay (4)
* Brett Profitt (2)
* Ismayil Khayredinov (2)
* Juho Jaakkola (2)
* Jeroen Dalsem (1)
* Per Jensen (1)

#### Documentation

* **support:** Added 1.11's dates of support. ([4bd3144d](https://github.com/Elgg/Elgg/commit/4bd3144dcd258f8b38e3ea97acff87d7c1d5ef51))


#### Bug Fixes

* **aalborg_theme:** removes unwanted margin from elgg-list-river items ([c43371b5](https://github.com/Elgg/Elgg/commit/c43371b59566612dca3230d7a33083e60b4cc319), closes [#8124](https://github.com/Elgg/Elgg/issues/8124))
* **access:** do not use default access if access options are passed to the input view ([36a4d209](https://github.com/Elgg/Elgg/commit/36a4d2090889a3b41d8a5abf2ce0eb19d66c9cd4), closes [#8219](https://github.com/Elgg/Elgg/issues/8219))
* **core:** check for correct minimal php version in installer ([fcff9e5e](https://github.com/Elgg/Elgg/commit/fcff9e5e4d327ebf5301eea38f0e4f9bbb550dbf), closes [#8196](https://github.com/Elgg/Elgg/issues/8196))
* **deprecation:** visible deprecation errors aren't displayed to admin anymore ([2311d666](https://github.com/Elgg/Elgg/commit/2311d6669aae52135aa6deacd7cf5cf60f563409))
* **navigation:** Links to “Comments” again link directly to the comments section ([caea1ab2](https://github.com/Elgg/Elgg/commit/caea1ab262f0d517c3dd0d6111dfaf8b6e8975f8), closes [#8227](https://github.com/Elgg/Elgg/issues/8227))
* **plugins:**
  * Unloadable owner doesn’t WSOD displaying groupforumtopic ([8a082a3c](https://github.com/Elgg/Elgg/commit/8a082a3cdda7ac3e31b06189037f25cbae8c2bc2))
  * Fixes HTML toggle for CKEditor ([a45c4ca4](https://github.com/Elgg/Elgg/commit/a45c4ca45c824d716f103ef55b7fdd62e7d829e7), closes [#8193](https://github.com/Elgg/Elgg/issues/8193))
* **session:** Properly assigns PHP session settings from configuration ([d1ec08f3](https://github.com/Elgg/Elgg/commit/d1ec08f34b7a6520cf577ae30bcdad1c6c8b8427), closes [#8223](https://github.com/Elgg/Elgg/issues/8223))
* **tags:** Corrected cases of tags having leading or trailing spaces. ([67addf48](https://github.com/Elgg/Elgg/commit/67addf489c340f238002946560da958a5ddf0411), closes [#8123](https://github.com/Elgg/Elgg/issues/8123))


<a name="1.11.0"></a>
## 1.11.0  (2015-04-13)

#### Contributors

* Jeroen Dalsem (50)
* Steve Clay (37)
* Ismayil Khayredinov (15)
* Jerôme Bakker (11)
* Juho Jaakkola (6)
* Evan Winslow (3)
* Brett Profitt (2)
* Matt Beckett (2)
* Paweł Sroka (2)
* Mariano Aguero (1)
* Per Jensen (1)
* ray peaslee (1)

#### Features

* **access:** notify users when access change will affect comments ([09691cb1](https://github.com/Elgg/Elgg/commit/09691cb10944a599eda97c65c169da6d9824c218), closes [#8086](https://github.com/Elgg/Elgg/issues/8086))
* **admin:** add a warning when a physical robots.txt is present ([90ec514e](https://github.com/Elgg/Elgg/commit/90ec514ec899a4b0721d1257ddfd208a59ea8bbb))
* **comments:**
  * link in email notification now takes directly to the correct page ([914b492d](https://github.com/Elgg/Elgg/commit/914b492def07ffb703a0a320086e571c20efb640))
  * allows setting comments per page via hook ([879a3ef3](https://github.com/Elgg/Elgg/commit/879a3ef3cba3ab0d1604e51864c4ea4225685383))
  * river comments/discussion replies go to right page ([364894e2](https://github.com/Elgg/Elgg/commit/364894e257a64b3b9f58a5e439017420c85d09d3), closes [#7936](https://github.com/Elgg/Elgg/issues/7936))
* **context:** adds API to get/set the entire context stack ([d7ff355b](https://github.com/Elgg/Elgg/commit/d7ff355b458c909eccc3ef11b64c59df7bf84bff))
* **core:**
  * adds handling of 400 and 403 error codes ([243ca408](https://github.com/Elgg/Elgg/commit/243ca4086e1a870b5f08b3d38690c3c1bbe3cf23))
  * added a new admin widget to monitor cron jobs ([aeb26236](https://github.com/Elgg/Elgg/commit/aeb26236c4d95dc8f6e9164d7648969d818c20a1))
* **db:** remove access collection (membership) when an entity is removed ([f67d04fd](https://github.com/Elgg/Elgg/commit/f67d04fd1d65561fe721fe4881cf672163539bad))
* **developers:**
  * added userpicker with limit 1 to theme sandbox ([6d3ad5cf](https://github.com/Elgg/Elgg/commit/6d3ad5cf0ea1bbebf9316dede2acfe26576a7b0f))
  * show total DB queries in the developer screen log ([defbe1cc](https://github.com/Elgg/Elgg/commit/defbe1cc66b0b4c2dedfa02bc04613f0ea9e7f1a))
  * the inspector pages show a lot more info ([a4384438](https://github.com/Elgg/Elgg/commit/a4384438ca8422aad3cab95ed6d5dc88b0f1024a), closes [#4540](https://github.com/Elgg/Elgg/issues/4540))
  * improved readability of inspect pages ([a3e7f09d](https://github.com/Elgg/Elgg/commit/a3e7f09d016db2ea0027266fec95eaa1cec69a07), closes [#6484](https://github.com/Elgg/Elgg/issues/6484))
* **discussions:** link in email notification now takes directly to the correct page ([4565cc86](https://github.com/Elgg/Elgg/commit/4565cc8605f1426e25be4ee919168d744899e93e))
* **engine:**
  * added a canDelete function to the entity class ([6b12e45d](https://github.com/Elgg/Elgg/commit/6b12e45db14e635a1fca450f441ca0ff0fae20f0))
  * added a trigger to elgg_view_menu to adjust menu vars ([34ad5bee](https://github.com/Elgg/Elgg/commit/34ad5beedde570f8f1210529929c78a26e77a382))
* **externalpages:**
  * replaced tabs with menu on expages edit form ([d4d03d0a](https://github.com/Elgg/Elgg/commit/d4d03d0ad3b9a9d52ce3a2b4d5b6b490ee4c3582))
  * added a link on the edit form to view page on site ([1a6d8d79](https://github.com/Elgg/Elgg/commit/1a6d8d79e28f847604a9614540b75f743edaaf90))
  * added an edit button to the view of an external page ([cd1c58b8](https://github.com/Elgg/Elgg/commit/cd1c58b8695ab6e9064f4cc6eb7db951128b968b))
* **file:**
  * Add upload button to sidebar search pages ([290c498d](https://github.com/Elgg/Elgg/commit/290c498d8ae57033176d3874675ecb3bc0e41271), closes [#8110](https://github.com/Elgg/Elgg/issues/8110))
  * show image thumbnail in a lightbox in full view ([001e27eb](https://github.com/Elgg/Elgg/commit/001e27eb9dfee3fb54bfce38e0ecac4c746b5c0f))
* **groups:** group tool options are now checkboxes ([25532a91](https://github.com/Elgg/Elgg/commit/25532a914963227aa70a10e3f14c3bf92728b92d))
* **i18n:**
  * added function to check if a language key exists ([9684b37c](https://github.com/Elgg/Elgg/commit/9684b37cb2307c195dbedfd510584df561c5dabd))
  * allow option to force language with an url parameter ([afd9ad34](https://github.com/Elgg/Elgg/commit/afd9ad34721a65cd615426bb494829a503982240))
* **icons:** allow ElggEntity::getIconURL to accept an array ([7281ea01](https://github.com/Elgg/Elgg/commit/7281ea018e99c53279df2898d2213816df6bf059))
* **js:**
  * added a hook to the AMD config to control the configuration ([697bb841](https://github.com/Elgg/Elgg/commit/697bb841272dcbbb4d8aa19ff267ae964f86afe8))
  * datepicker will now show month and year selector ([1945c8ba](https://github.com/Elgg/Elgg/commit/1945c8ba8b765ff3d4f230b08da15fa1e13a67e4))
  * adds a fixed Ajax spinner module ([dd1b5bc2](https://github.com/Elgg/Elgg/commit/dd1b5bc23f8d6bca9f6a9395191fff6101fdcded))
* **login_as:** Added login_as plugin as bundled with the core. ([7ca66011](https://github.com/Elgg/Elgg/commit/7ca6601134d43531256d6a55ad032d08ab5e6c8f), closes [#7958](https://github.com/Elgg/Elgg/issues/7958))
* **navigation:** add hook to filter breadcrumbs ([f7cb4878](https://github.com/Elgg/Elgg/commit/f7cb4878be4e3bd277410362740e87972f6a114a), closes [#6419](https://github.com/Elgg/Elgg/issues/6419))
* **plugins:** adds several reported content features ([347683c1](https://github.com/Elgg/Elgg/commit/347683c1b6dba1740193b69054d9e1ad9dd4f96c), closes [#5379](https://github.com/Elgg/Elgg/issues/5379), [#6082](https://github.com/Elgg/Elgg/issues/6082), [#5380](https://github.com/Elgg/Elgg/issues/5380))
* **security:**
  * Adds component to create and validate HMAC tokens ([4c1b0740](https://github.com/Elgg/Elgg/commit/4c1b0740a924c381f9b87bd35e782375daf322b5), closes [#7824](https://github.com/Elgg/Elgg/issues/7824))
  * adds events around site secret regeneration ([25f177a3](https://github.com/Elgg/Elgg/commit/25f177a3892aae6aa9d63d85b50bb1303f466eec), closes [#6252](https://github.com/Elgg/Elgg/issues/6252))
* **ui:** allows highlighting an element whose id is found from the URL ([f7dd696a](https://github.com/Elgg/Elgg/commit/f7dd696a15b07fdf1d02417509ed3788707d7563))
* **views:**
  * added lazy loading of user hover menu ([a0267469](https://github.com/Elgg/Elgg/commit/a02674695e85d5f1f288cec8063f7ad8de1e4bd6))
  * add first and last page number to pagination ([4c9c1209](https://github.com/Elgg/Elgg/commit/4c9c120947e241e7dcf51dab12a21ccf3f4f7b32))
  * added the ability to translation the usersettings title ([00e9efce](https://github.com/Elgg/Elgg/commit/00e9efceaa81482f73eba38ed42874bd109bd0bb))
  * allow providing alternative views for list items ([85c22f35](https://github.com/Elgg/Elgg/commit/85c22f35801baa0ffd8c4b5226bffe903ac54b7a))
  * support for extra variables in elgg_view_icon function ([67006312](https://github.com/Elgg/Elgg/commit/670063129b01d7478f9c14fb540d4622e364806d))
  * move logged in check to topbar view ([08ae23f6](https://github.com/Elgg/Elgg/commit/08ae23f68baceb9c53136b3afb86755410986a18), closes [#6582](https://github.com/Elgg/Elgg/issues/6582))
  * plugin hooks can modify view $vars ([d493bf93](https://github.com/Elgg/Elgg/commit/d493bf933f300466d94e29ff781d86b4c19786f3), closes [#7736](https://github.com/Elgg/Elgg/issues/7736))
  * added support for other entity types to tagcloud block ([db0d9b04](https://github.com/Elgg/Elgg/commit/db0d9b048b2a750487b79ae899008b3035aed611))
  * added container guid support to sidebar comments block ([e70f2c98](https://github.com/Elgg/Elgg/commit/e70f2c9801009c58b50fa0ada44a04542e1580cb))
  * add container guid support to tagcloud block ([de92b4ec](https://github.com/Elgg/Elgg/commit/de92b4ec5608e749b01d7a470a8354721057332f))
  * passes more context info to input/access and access hooks ([437f9649](https://github.com/Elgg/Elgg/commit/437f9649a9204db632cae655bc88f6fbf0880b1c), closes [#4695](https://github.com/Elgg/Elgg/issues/4695))


#### Performance

* **entities:**
  * adds preload_containers option to elgg_get_entities ([65fe534f](https://github.com/Elgg/Elgg/commit/65fe534fb49589bd8b1c5e8ee26a5d00a9db4b33), closes [#7663](https://github.com/Elgg/Elgg/issues/7663))
  * loads more entities with a single query ([31058a09](https://github.com/Elgg/Elgg/commit/31058a094a50055898692f6930b47b18a4027b4f), closes [#7662](https://github.com/Elgg/Elgg/issues/7662), [#7659](https://github.com/Elgg/Elgg/issues/7659))
* **groups:** makes group invitations scalable ([6088b1a7](https://github.com/Elgg/Elgg/commit/6088b1a7b3804c8ac8e43c20fb79cce0fd6d849e))
* **i18n:** only check for admin once during getInstalledTranslations ([38dae267](https://github.com/Elgg/Elgg/commit/38dae2670fa02cedb49197cfc12790f2df3e8245))
* **languages:** improved js caching of languages by using simplecache ([ab17ee54](https://github.com/Elgg/Elgg/commit/ab17ee54dd3e71f8bb230ec3c9a4368c492b7cd1))
* **likes:** ajax load liking users and show in lightbox ([7a371477](https://github.com/Elgg/Elgg/commit/7a3714775d5f8c9c0cd3ffc25ec2765b9a176187))
* **plugins:** removes DB query to determine if a plugin is active ([0ed117d3](https://github.com/Elgg/Elgg/commit/0ed117d31301978f6c7ac9c00238777fd941cc30), closes [#7661](https://github.com/Elgg/Elgg/issues/7661))
* **search:** only query DB for fulltext min and max word length once ([5f6e1176](https://github.com/Elgg/Elgg/commit/5f6e1176f1e06a6632a154a3abdcffe8474a33cf), closes [#6707](https://github.com/Elgg/Elgg/issues/6707))


#### Documentation

* **rst:** documents new list item view parameter ([a4f51701](https://github.com/Elgg/Elgg/commit/a4f517017a7c637a79cc815e3ddb46f2d23073fa))
* **upgrading:** instructs how to enable comment highlighting in custom themes ([60eebdc2](https://github.com/Elgg/Elgg/commit/60eebdc24f34761595589e0911411e5803f78327))
* **views:** improves docs for views ([365f9058](https://github.com/Elgg/Elgg/commit/365f9058f1105486981d8dcb73919c52fb975a14))


#### Bug Fixes

* **access:** show all readable custom access collection names to admins ([fd1637f5](https://github.com/Elgg/Elgg/commit/fd1637f51ad15976494a074c6deb07d12d0fb324))
* **actions:** action scripts can return falsey values without causing errors ([35382fce](https://github.com/Elgg/Elgg/commit/35382fce5bcf0267c28c84cadab34350c22121dd), closes [#7209](https://github.com/Elgg/Elgg/issues/7209))
* **annotations:** simplifies ege* for annotation calculations ([b123f06d](https://github.com/Elgg/Elgg/commit/b123f06d165f77e69240052bfbc3e00b7ebab74f), closes [#7398](https://github.com/Elgg/Elgg/issues/7398), [#4393](https://github.com/Elgg/Elgg/issues/4393))
* **ckeditor:** updated to full ckeditor package version 4.4.7 ([ada19c9d](https://github.com/Elgg/Elgg/commit/ada19c9d49fb0244b7c13b5316e4917532b16e87))
* **comments:** keep comment access_id in sync with container ([066102ab](https://github.com/Elgg/Elgg/commit/066102aba3a75a813c1c579e6fa72145e939283a), closes [#7807](https://github.com/Elgg/Elgg/issues/7807), [#NaN](https://github.com/Elgg/Elgg/issues/NaN))
* **css:** Fix size of button elements in /admin ([6cb602c5](https://github.com/Elgg/Elgg/commit/6cb602c59d5b27c1446e349718cd4a7fefd7d5cc))
* **developers:** append the developers log as late as possible ([5b0d4c65](https://github.com/Elgg/Elgg/commit/5b0d4c652f3e5e014e8400297e387f9958449f81))
* **engine:**
  * eliminated potential deadloops in MenuBuilder::setupTrees ([3e5cf89a](https://github.com/Elgg/Elgg/commit/3e5cf89aee97c9aed046a34f637b616404a5d799))
  * return original val if not a string for string_to_tag_array ([1ef2b9e3](https://github.com/Elgg/Elgg/commit/1ef2b9e376eaa6e326a33dffb54f4f3650063510))
* **file:** always download as attachment when using file download action ([278fe010](https://github.com/Elgg/Elgg/commit/278fe0109766d5b5e7868da87f43d1fe6cee6c94))
* **filestore:** fixes file uploading broken by the transition to Symfony HttpFoundation ([d315aaaa](https://github.com/Elgg/Elgg/commit/d315aaaafeebf65488fce729aa124d8dbb387f9f))
* **i18n:**
  * ckeditor now uses user's own language instead of the site language ([aa63a911](https://github.com/Elgg/Elgg/commit/aa63a9112797b2bd18455aef70b8012256b2d2d9))
  * do not let empty translation arrays disable plugins ([10ba5d89](https://github.com/Elgg/Elgg/commit/10ba5d8932594cc4e35e9edf68c2c031db33b721))
  * make sure that all potential languages are loadable with js ([cfa860e6](https://github.com/Elgg/Elgg/commit/cfa860e6657dc9f14c9bc7fe232a74a829e21eea))
* **js:**
  * only show editor toggle link if editor is initialized ([d18f95cc](https://github.com/Elgg/Elgg/commit/d18f95cc0d445a9aded890eb4ed8dd19330eecf0))
  * increased AMD config waitSeconds to prevent timeout issues ([0bd6aef6](https://github.com/Elgg/Elgg/commit/0bd6aef60bc63b07233aa929058fdfb1feeb65c4))
  * correctly define amd dependencies for input/userpicker ([48f5c00a](https://github.com/Elgg/Elgg/commit/48f5c00ad7cbc538cf0b668b3f71788365b6ba3e))
* **messageboard:**
  * provide correct link to users messageboard ([04b86f56](https://github.com/Elgg/Elgg/commit/04b86f5631be00f5ce8f88b5753430b7dcf498f7))
  * correctly register deletePost on ajax created posts ([a50dbe3e](https://github.com/Elgg/Elgg/commit/a50dbe3e81fee9b991d56b68bb6b1ee6a8fe8e4b))
* **navigation:** strip tags before comparing menu item text ([c021e6a9](https://github.com/Elgg/Elgg/commit/c021e6a9fc47bfae077e4b54951e41d7cbc790fc))
* **notifications:** correctly use elgg_log instead of error_log ([43661c90](https://github.com/Elgg/Elgg/commit/43661c9061e39441b29ed4e972e3513c55cf8013))
* **profile:**
  * moved topbar profile menu registration to profile plugin ([2100c494](https://github.com/Elgg/Elgg/commit/2100c49446fbfbdd93f6bf4ba7190e1db22834a4))
  * adds the prepare hook for the profile page’s owner menu ([1d39ff8d](https://github.com/Elgg/Elgg/commit/1d39ff8ddca75aa107d6297094ebb701ce7fb8bc), closes [#6085](https://github.com/Elgg/Elgg/issues/6085))
* **security:** Eliminates auto-casting within HMAC token building ([2be74f05](https://github.com/Elgg/Elgg/commit/2be74f05b9a4deebb64da287f98d103d9751ee84))
* **tests:** fix failing SystemMessagesServiceTest ([d52515ba](https://github.com/Elgg/Elgg/commit/d52515baf563d8a5ccfb3b23c4e1e93e1f3e3e10))
* **views:**
  * show spinner when ajax loading walled garden forms ([1e503da4](https://github.com/Elgg/Elgg/commit/1e503da43f7bbf5e5fc7a79189d8b428fc6575c6))
  * prevent direct calls to an ajax view ([3b5993bb](https://github.com/Elgg/Elgg/commit/3b5993bb7ce2a2f2d59f25618594b1c89158ef66))
  * changed text of the # more comments text in the river ([f2f3c1dd](https://github.com/Elgg/Elgg/commit/f2f3c1dd05f6dd85313b3ebf2d51911526df1f31))
  * view can only exist if it is string ([4452b614](https://github.com/Elgg/Elgg/commit/4452b614b34e89000d0e9a73ff3fce5871a09a6e))
  * check item instance before rendering it ([f927f462](https://github.com/Elgg/Elgg/commit/f927f46243f40e608299cceeec5fc289d22f38f0))
  * always show all system messages (success and error) ([01156baa](https://github.com/Elgg/Elgg/commit/01156baa8baeef53e8b5c34079f387421e1d610a))
  * added avatar classnames to menu item for consistency ([d803c1aa](https://github.com/Elgg/Elgg/commit/d803c1aa5b40174e538a1f28ac4dae78f48137eb))
  * sidebar comments block data should be consistent with page ([f9e6efb2](https://github.com/Elgg/Elgg/commit/f9e6efb2529cc12002d3b122cf60930751225af8))
  * switch tagcloud blocks to list tags based on container_guid ([7915a668](https://github.com/Elgg/Elgg/commit/7915a668ef6de1cba4ca9f74f785d7831ba68297))

<a name="1.10.6"></a>
### 1.10.6  (2015-12-13)

#### Contributors

* Jerôme Bakker (5)
* Juho Jaakkola (2)
* Evan Winslow (2)
* Mariano Aguero (1)
* akudan (1)
* Steve Clay (1)
* Jeroen Dalsem (1)

#### Bug Fixes

* **views:** Sticky values now get passed into views extending register/extend ([e241e82e](https://github.com/Elgg/Elgg/commit/e241e82eef3ac57e8cffdfdad164fe49372ddfd7), closes [#8873](https://github.com/Elgg/Elgg/issues/8873))
* **memcache:** don't store a copy of $CONFIG in file objects ([beb90891](https://github.com/Elgg/Elgg/commit/beb9089129a0a06b36200f3f8d214c7ed8f94f42)), closes [#9081](https://github.com/Elgg/Elgg/issues/9081))
* **messageboard:** provide correct link to users messageboard (Fixes [#8170](https://github.com/Elgg/Elgg/issues/8170))
* **notifications:** correctly use elgg_log instead of error_log (Fixes [#8039](https://github.com/Elgg/Elgg/issues/8039))
* **i18n:**
  * ckeditor now uses user's own language instead of the site language
  * do not let empty translation arrays disable plugins (Fixes [#8116](https://github.com/Elgg/Elgg/issues/8116))

#### Chores

* **notification:** no more typehint errors when sending a notificationas an ElggGroup (Fixes [#7949](https://github.com/Elgg/Elgg/issues/7949))
* **thewire:** improved error handling when removing a wire post (Fixes [#7003](https://github.com/Elgg/Elgg/issues/7003))
* **core:** catch login exceptions during password change (Fixes [#7948](https://github.com/Elgg/Elgg/issues/7948))

<a name="1.10.5"></a>
### 1.10.5  (2015-04-05)

#### Contributors

* Per Jensen (1)
* Steve Clay (1)

#### Bug Fixes

* **aalborg_theme:** moves unextend/extend view into init ([3c5fb39b](https://github.com/Elgg/Elgg/commit/3c5fb39ba2c65127c5fc57f6e27eef5ac6127c92), closes [#8105](https://github.com/Elgg/Elgg/issues/8105))


<a name="1.10.4"></a>
### 1.10.4  (2015-03-22)

#### Contributors

* Evan Winslow (3)
* Jerôme Bakker (2)
* Juho Jaakkola (2)
* Matt Beckett (1)
* Paweł Sroka (1)

#### Bug Fixes

* **core:** don't trigger delete event when you can't edit the entity ([83c69c09](https://github.com/Elgg/Elgg/commit/83c69c09c1a163ae30507043a9c4eaaf9e627d89))
* **groups:**
  * respect previous modifications to the write access in group context ([11b55041](https://github.com/Elgg/Elgg/commit/11b55041df54f9c2d193427e7c0acf6a7175882b))
  * Hides group profile fields that don't have a value ([2bb13db8](https://github.com/Elgg/Elgg/commit/2bb13db8d96bd5a2307c009717476a67cc2698cd))


<a name="1.10.3"></a>
### 1.10.3  (2015-03-08)

#### Contributors

* Juho Jaakkola (5)
* Jeroen Dalsem (4)
* Ismayil Khayredinov (1)
* Jerôme Bakker (1)
* Matt Beckett (1)
* Cim (1)
* Rodrigo (1)
* Evan Winslow (1)

#### Documentation

* **helpers:** Adds missing underscores to elgg_get_loggedin_user_* functions ([02ef5d7b](https://github.com/Elgg/Elgg/commit/02ef5d7bf6aa70153d5ec9fb9aac1340cad87741))
* **views:** documented the difference between page/elements/foot and footer ([001be7e4](https://github.com/Elgg/Elgg/commit/001be7e4c19a63932abd1740071f17bdd20bc2b4))


#### Bug Fixes

* **upgrade:** reset system cache before upgrade ([468d1c40](https://github.com/Elgg/Elgg/commit/468d1c407ed1912bfdc5f059ba42c2d7af77f951), closes [#6249](https://github.com/Elgg/Elgg/issues/6249))
* **uservalidationbyemail:** only forward to emailsent page if email sent ([7d8cd3b8](https://github.com/Elgg/Elgg/commit/7d8cd3b83bc32648df3702d25f713f8a63bd399d))
* **views:**
  * always add the user guid param to the usersettings/save form ([9e1661d4](https://github.com/Elgg/Elgg/commit/9e1661d4189bc089e632b8ed9a30aabd80155730))
  * always submit element when there are no userpicker values ([61e295c9](https://github.com/Elgg/Elgg/commit/61e295c9c34e5e8a869f14610e32aa958d9a4720))


<a name="1.10.2"></a>
### 1.10.2  (2015-02-21)

#### Contributors

* Jeroen Dalsem (16)
* Steve Clay (6)
* Evan Winslow (2)
* Jerôme Bakker (2)
* Ismayil Khayredinov (1)
* Juho Jaakkola (1)

#### Performance

* **stats:** more efficient get_entity_statistics() ([f5ac3602](https://github.com/Elgg/Elgg/commit/f5ac3602048767761c3b843ca1becea6dbf26582))


#### Documentation

* **install:** Move environment-specific instructions to their own pages ([1b750298](https://github.com/Elgg/Elgg/commit/1b750298f4df5a585cabe521827a8071b95d2807), closes [#7834](https://github.com/Elgg/Elgg/issues/7834))


#### Bug Fixes

* **datepicker:** Prevents month navigation links from overlapping with other elements ([fb1596da](https://github.com/Elgg/Elgg/commit/fb1596daf1a8d18771e7a241392ad90edaf82619), closes [#7542](https://github.com/Elgg/Elgg/issues/7542))
* **groups:** also delete original icon when deleting group ([b8d1612e](https://github.com/Elgg/Elgg/commit/b8d1612ece88a52bb20e7009d2c7dffc2002dac5))
* **js:**
  * correctly init datepicker when ajax loaded ([aecc0047](https://github.com/Elgg/Elgg/commit/aecc0047f196355295e911e116475489acd84988))
  * fixes aalborg site menu by restoring 1.9 toggle behavior ([8ece7dd8](https://github.com/Elgg/Elgg/commit/8ece7dd89b1fabe11fb0983f9be1e5887a76e583), closes [#7790](https://github.com/Elgg/Elgg/issues/7790))
  * catch global ajax errors and report to the user ([dd52baeb](https://github.com/Elgg/Elgg/commit/dd52baebfcd2020aa77b14371fd986319ce4dfb9))
* **likes:**
  * only allow likes to be deleted by owner ([b47f0166](https://github.com/Elgg/Elgg/commit/b47f01661d6bd74453e54b27fa581753b3931305))
  * correctly register like button for ajax action ([d56b239d](https://github.com/Elgg/Elgg/commit/d56b239dbefeb6dd5bbf2d1c4ca5542d2b597302))
* **messages:** forward to inbox after deleting a message ([015baf62](https://github.com/Elgg/Elgg/commit/015baf6246f808fed1ff4d6163b0bf6f77d3242b))
* **metadata:** metadata values returned in more reliable order ([36517715](https://github.com/Elgg/Elgg/commit/36517715959773bb02d0aa57e1bd0ac012eb527b), closes [#5603](https://github.com/Elgg/Elgg/issues/5603))
* **plugins:** pages with no annotation no longer cause fatal errors ([ffdb908d](https://github.com/Elgg/Elgg/commit/ffdb908dd2ffa0a909f20519275f480e20f997a8), closes [#7793](https://github.com/Elgg/Elgg/issues/7793))
* **profile:** consider potential split db in profile icondirect ([bd8f3aed](https://github.com/Elgg/Elgg/commit/bd8f3aedc8290a580d7c8a43ffb29d286a2bf24f))
* **search:**
  * prevent search form submit if empty query ([becd5ba2](https://github.com/Elgg/Elgg/commit/becd5ba293013c73eef91640019671a13d8119f0))
  * correctly split search words on multiple spaces ([2bde4af1](https://github.com/Elgg/Elgg/commit/2bde4af1e612671b958a8bf7aa846934d4c015d1))
  * only query user metadata if there are profile fields ([6cdafa10](https://github.com/Elgg/Elgg/commit/6cdafa10c32c0ccfa26942d7006319a5a0dde0db))
* **ui:** using site menu too early no longer results in 404 page ([b11acee5](https://github.com/Elgg/Elgg/commit/b11acee5c555a926d62c48e18d561ca875762e3a), closes [#7861](https://github.com/Elgg/Elgg/issues/7861))
* **views:**
  * use named keys when registering meta tags and links in head ([2cbaa770](https://github.com/Elgg/Elgg/commit/2cbaa770458886d6d30dc7ed446bfe00729e1e8a))
  * improved check on non existing array keys ([bfc65a68](https://github.com/Elgg/Elgg/commit/bfc65a68a70a8b098ff2a3e3287915499f84e8b5))
  * adds excerpt to comments and discussions in activity context ([4e09115a](https://github.com/Elgg/Elgg/commit/4e09115addb1ea40e005abb36b10b1056e839f12))
  * deprecated notice no longer shows up in the wrong version ([3fcbee3f](https://github.com/Elgg/Elgg/commit/3fcbee3fad08ea1d3aaaa40d2c3865092cea3f7b))
  * use correct page offset for divisors that have a modulo ([cdc85dca](https://github.com/Elgg/Elgg/commit/cdc85dca62d339934721db27a75ff6210b4b4170))


<a name="1.10.1"></a>
### 1.10.1  (2015-01-26)

#### Contributors

* Steve Clay (10)
* Jerôme Bakker (2)
* Ismayil Khayredinov (1)
* Juho Jaakkola (1)

#### Documentation

* **routing:** Adds RST docs on routing ([fc3b0642](https://github.com/Elgg/Elgg/commit/fc3b064278841b55bb2fff1d641debf75b9d3484), closes [#7337](https://github.com/Elgg/Elgg/issues/7337))


#### Bug Fixes

* **blog:** correctly handle the archive listing if there is no archive ([71fbf79f](https://github.com/Elgg/Elgg/commit/71fbf79f4c7a977ec119b8f866e3b4d51c5c3860))
* **css:** add missing selector prefix ([af3f003d](https://github.com/Elgg/Elgg/commit/af3f003d66996aa96392947e4bccf2679284a0d4))
* **http:** don’t allow plugins to bypass a forward() call ([ac2d9f1e](https://github.com/Elgg/Elgg/commit/ac2d9f1e17e9ece15f485d395d90ef2b36141838), closes [#7637](https://github.com/Elgg/Elgg/issues/7637))
* **notifications:**
  * email replies again have “Re:” in subjects ([632c57d3](https://github.com/Elgg/Elgg/commit/632c57d39a22fa0a7977a58cad6dd4df2fc296ed))
  * correctly unregister the default notifications save function ([f2adb5e2](https://github.com/Elgg/Elgg/commit/f2adb5e2ad426400bfc5de68720e44372c764eb4))
* **plugins:**
  * discussion replies no longer missing/reversed order on river ([78af4b69](https://github.com/Elgg/Elgg/commit/78af4b6906061cad774cbe1f9b0b65002cd40345), closes [#7801](https://github.com/Elgg/Elgg/issues/7801), [#7668](https://github.com/Elgg/Elgg/issues/7668))
  * re-hides the likes button for groups ([f57d6ef8](https://github.com/Elgg/Elgg/commit/f57d6ef8532d72c2b9e531368c6a1a6b59ce35fa), closes [#7724](https://github.com/Elgg/Elgg/issues/7724))
  * eliminates notices for missing GET keys in profile icon ([98fb967d](https://github.com/Elgg/Elgg/commit/98fb967d0f3352245920f63b00c3295a31f02db4))
  * Eliminates deprecation notice on file plugin objects ([a0240add](https://github.com/Elgg/Elgg/commit/a0240added0159387e90a60d514a61d599eebb9d), closes [#7761](https://github.com/Elgg/Elgg/issues/7761))
* **travis:** eliminates composer install failures ([f96ea171](https://github.com/Elgg/Elgg/commit/f96ea17150a7b7b5910aaba10add973d017a6f6f))


<a name="1.10.0"></a>
## 1.10.0  (2015-01-11)

#### Contributors

* Paweł Sroka (12)
* Per Jensen (3)
* Steve Clay (3)
* Evan Winslow (2)
* Arsalan Shah (1)
* Juho Jaakkola (1)

#### Features

* **security:** No longer ship with vendors that have security advisories ([b193ebcf](https://github.com/Elgg/Elgg/commit/b193ebcf2cbfff13732303d3601a0d504c9f94a4), closes [#7738](https://github.com/Elgg/Elgg/issues/7738))
* **test:** Added rewrite rules for builtin PHP cli server execution ([a0ff98e2](https://github.com/Elgg/Elgg/commit/a0ff98e21175a010b15f3d98517aaa2be95c114b))


#### Documentation

* **nginx:** Added sample nginx configuration and moved sample configs to install/config/ ([dbcd7548](https://github.com/Elgg/Elgg/commit/dbcd754839796ea00fd711cb149c9c94eff8e2da))
* **requirements:** Updated PHP version used in docs as requirement to 5.4 ([5f4f8eea](https://github.com/Elgg/Elgg/commit/5f4f8eead513495a474b4eba4698c3c66795b0a0))


#### Bug Fixes

* **CSS:** adds margin between a file's text and image ([261e92b4](https://github.com/Elgg/Elgg/commit/261e92b40bad46455ec68bf2cfc695cef5cbf0dc), closes [#7712](https://github.com/Elgg/Elgg/issues/7712))
* **amd:** Added coverage tests and fixed minor bugs. ([6250fd76](https://github.com/Elgg/Elgg/commit/6250fd76ca72bc196788da2a4f83f562f99a5d42))
* **blog:** adds missing class to preview button ([be3b559b](https://github.com/Elgg/Elgg/commit/be3b559b20da20c08940c5a2623f5b817f0c3f12), closes [#7706](https://github.com/Elgg/Elgg/issues/7706))
* **http:** Send a minimal 404 header for /favicon.ico requests ([b8c8a280](https://github.com/Elgg/Elgg/commit/b8c8a280457e34c43b9bf8a83f51e845339202e7), closes [#7261](https://github.com/Elgg/Elgg/issues/7261))
* **settings:** It's again possible to set user specific setting for a plugin ([80e0c904](https://github.com/Elgg/Elgg/commit/80e0c904abafea70eb64b037f0e5d6f8144e7344))


<a name="1.10.0-rc.1"></a>
### 1.10.0-rc.1  (2014-12-15)

#### Contributors

* Evan Winslow (64)
* Steve Clay (30)
* Jeroen Dalsem (24)
* Per Jensen (10)
* Jerôme Bakker (9)
* Paweł Sroka (7)
* Ismayil Khayredinov (5)
* Matt Beckett (3)
* Juho Jaakkola (1)
* Jeff Tilson (1)
* Bruno (1)
* Satheesh PM (1)
* Sem (1)

#### Features

* **aalborg_theme:**
  * adds support for alert messages ([2e410f71](https://github.com/Elgg/Elgg/commit/2e410f71fed8cec4bd7235ffa76d327295d55302))
  * adds visual difference between submit and action buttons ([691470e6](https://github.com/Elgg/Elgg/commit/691470e64361eb1aa9cc7fd5b81a582df9d2ec2f), closes [#6929](https://github.com/Elgg/Elgg/issues/6929))
  * comments use triangle indicator instead of text label ([b2d55926](https://github.com/Elgg/Elgg/commit/b2d55926b53c794aab6fb82ad3ddbf9c596c73d8))
  * adds support for .elgg-button-special and button sizes ([2ca0dedd](https://github.com/Elgg/Elgg/commit/2ca0dedd1bc0cee901108ad6324871e2b4983886), closes [#2954](https://github.com/Elgg/Elgg/issues/2954))
* **admin:** Use elgg_view_menu to generate admin header ([411a9f39](https://github.com/Elgg/Elgg/commit/411a9f39abeb74c132d46edae9b6025bab01f1c6))
* **cache:** allows using ints as keys in Cache\Pool ([c36ec89d](https://github.com/Elgg/Elgg/commit/c36ec89d476232a8e5fd2e0b19bb07235e12a58a))
* **core:**
  * support for querying based on relationship create time ([db27abbd](https://github.com/Elgg/Elgg/commit/db27abbdcdb474e9b1d998b666b9e9f8c204b114))
  * adds edit avatar to admin section of user hover menu ([a003d840](https://github.com/Elgg/Elgg/commit/a003d8401b8898ff3829d1a0ac48efc1e2eaec18))
* **developers:** inspect menu item now has children for faster access ([314616d1](https://github.com/Elgg/Elgg/commit/314616d12060c32ce187e913a847cfe174c0f07b))
* **discussion:** Added some extension points at discussion sidebars. These changes allow 3rd party plugins to extend discussion sidebar, and add there features like a subscribe  ([db46100a](https://github.com/Elgg/Elgg/commit/db46100a570cc7b8d1dc2794da3d09bf751241c4))
* **file:** display file upload limit on file upload form ([09001b9d](https://github.com/Elgg/Elgg/commit/09001b9d1789756cb4d39c4b382268da30868533))
* **filestore:**
  * add elgg_get_file_simple_type() to core api ([69e54e4c](https://github.com/Elgg/Elgg/commit/69e54e4c737906638ad71b92a3a3ffcf908b7acc))
  * add a hook to fix detected mimetype ([4ddc7843](https://github.com/Elgg/Elgg/commit/4ddc7843474ede4e7b304c3d5b5d5a70ad638d99))
* **groups:** Added featured groups as a tab along with groups, popular, discussions ([f77356e3](https://github.com/Elgg/Elgg/commit/f77356e3eb93d4fa4a84457b40432913d7ed2fae))
* **js:** extended the usability of rel="toggle" ([1d89418e](https://github.com/Elgg/Elgg/commit/1d89418ed6cc6b564a9c478579df060f872d5e7d))
* **messages:** improved UI for messages listing ([46821a62](https://github.com/Elgg/Elgg/commit/46821a62b8b64a23036fa4a54325ef53a65df912))
* **php:** Require PHP 5.4+ ([42b76d37](https://github.com/Elgg/Elgg/commit/42b76d37429439b2d1473b824c2ccd8edd24009b), closes [#7090](https://github.com/Elgg/Elgg/issues/7090))
* **plugins:** added default param to elgg_get_plugin_user_setting ([13000c98](https://github.com/Elgg/Elgg/commit/13000c98a632d8dc2836c765a017b7df77060303))
* **requirements:** PHP 5.3.3+ is now required ([3a555512](https://github.com/Elgg/Elgg/commit/3a555512f2208d01cf191c5d0603090b8bbd9186), closes [#6165](https://github.com/Elgg/Elgg/issues/6165))
* **router:** add original params to route hook ([1b1026c3](https://github.com/Elgg/Elgg/commit/1b1026c3386a0f4affa6c28c1bdbf5756a8e92bc))
* **settings:** adds setting for default number of items per page ([d1d0a4e1](https://github.com/Elgg/Elgg/commit/d1d0a4e15bbbb3c784535f5d9e3b511a2cacdbfd), closes [#2650](https://github.com/Elgg/Elgg/issues/2650))
* **site_notifications:** option to mass delete site notifications … ([c28eaac7](https://github.com/Elgg/Elgg/commit/c28eaac764b468b6df526bc155b31e16f2afe879))
* **users:** Username character blacklist can now be altered via plugin hook ([7dc63eb2](https://github.com/Elgg/Elgg/commit/7dc63eb280c22982488984a1288c0e88c93c44eb), closes [#6189](https://github.com/Elgg/Elgg/issues/6189))
* **usersettings:** every user setting has its own menu item ([6c1631d1](https://github.com/Elgg/Elgg/commit/6c1631d17e2ef3d2669af9b080bcfd9338062789))
* **views:**
  * output/tag supports all output/url options ([d0c9c855](https://github.com/Elgg/Elgg/commit/d0c9c855fabb9a1d55f05169db522ed9bc10dd8a))
  * allows rendering empty results using an anonymous function ([a8f15ffa](https://github.com/Elgg/Elgg/commit/a8f15ffa76f96aebc70ff131bdb5b6e25af6bdfd))
  * output readable access level for any access_id ([c9c2e12c](https://github.com/Elgg/Elgg/commit/c9c2e12c889d8640f577c61bcaa10c1b98a25211), closes [#7133](https://github.com/Elgg/Elgg/issues/7133))
* **webapp:** Add support for a basic WebApp Manifest file ([27c9ef4a](https://github.com/Elgg/Elgg/commit/27c9ef4ab36c6b0123fa181b5b457d714e8e07a7), closes [#7493](https://github.com/Elgg/Elgg/issues/7493))


#### Performance

* **annotations:** increased performance of egef_annotations ([96e6bd37](https://github.com/Elgg/Elgg/commit/96e6bd37e71b957c2ffdebd5f1ec672e3ece05ae), closes [#6638](https://github.com/Elgg/Elgg/issues/6638))
* **db:** Disabled SQL DISTINCT in more cases. ([98a99c83](https://github.com/Elgg/Elgg/commit/98a99c836bdfb9ae09174c43a847123b8f95a709))
* **entities:** preloads owners when drawing lists of entities/likes ([82088d5e](https://github.com/Elgg/Elgg/commit/82088d5e40eb3025ed02a31f02f085dd6a2cda42), closes [#5949](https://github.com/Elgg/Elgg/issues/5949))
* **likes:** reduces number of queries when showing likes in lists ([90991256](https://github.com/Elgg/Elgg/commit/909912564d0425b050b24ae49cb90aba0727a2c8), closes [#6941](https://github.com/Elgg/Elgg/issues/6941))
* **session:** speed up elgg_is_admin_user() ([aed21337](https://github.com/Elgg/Elgg/commit/aed21337da1f65fd06084ce3ddb44584d1011b2c))
* **sql:** allows removing DISTINCT from some MySQL queries ([293317f2](https://github.com/Elgg/Elgg/commit/293317f214861e2ec66b09956668b99e29941d4e), closes [#4594](https://github.com/Elgg/Elgg/issues/4594))
* **views:** remove unneeded view calls in river/elements/body view ([4ef23b61](https://github.com/Elgg/Elgg/commit/4ef23b6105dbf0be2144756e939e1e9699cd0737))


#### Documentation

* **requirements:** Document new rolling support policy for browsers ([9ce72099](https://github.com/Elgg/Elgg/commit/9ce720998a364873ba712dfb93c0ec053f33bec0), closes [#5932](https://github.com/Elgg/Elgg/issues/5932))


#### Bug Fixes

* **ckeditor:** ckeditor now prevents image drag/drop/paste in editor ([47fecbea](https://github.com/Elgg/Elgg/commit/47fecbea50bb05dbb3a3010e8ad175e9cebb1fc0))
* **config:** path is derived from PHP, not database ([b756cbb4](https://github.com/Elgg/Elgg/commit/b756cbb4b71264c398ea15317005b944ffdc881b))
* **css:**
  * strings together elgg-button and button sizes ([3cbe5877](https://github.com/Elgg/Elgg/commit/3cbe58775759391b4dc81656b3d824e5e361adac))
  * removes link color from "comments" header in river, default theme ([f140ffb9](https://github.com/Elgg/Elgg/commit/f140ffb9fc0630f47ce3ffcd17d88dcf4cdcbe08), closes [#7137](https://github.com/Elgg/Elgg/issues/7137))
  * removes padding and margin from elgg-menu-entity items ([e732645b](https://github.com/Elgg/Elgg/commit/e732645b7f82aabc6870be9cb9fee8fc5f8bd6a2))
* **db:** elgg_get_metastring_id should always create an id ([423f1f6d](https://github.com/Elgg/Elgg/commit/423f1f6d13d5b8ca7cd136a4fcb2371a268311df))
* **deprecation:** deprecation warnings for 1.10 now work as expected ([3d8ada59](https://github.com/Elgg/Elgg/commit/3d8ada590ed9528b1411c6204603e4ac945aa7c5))
* **discussion:** also search in discussion replies when searching discussion topics ([604697f3](https://github.com/Elgg/Elgg/commit/604697f3a0ad8031be8d5c839502077706fd03e4))
* **entities:** system files removed for all entities on delete ([800d1f36](https://github.com/Elgg/Elgg/commit/800d1f3684420f969572bb5e61b5c1e0424fb59a), closes [#7130](https://github.com/Elgg/Elgg/issues/7130))
* **forms:** Login and account forms widened and centered by default ([5fc81511](https://github.com/Elgg/Elgg/commit/5fc81511babbff9e6e9428fc24725026c92b3022), closes [#6456](https://github.com/Elgg/Elgg/issues/6456))
* **groups:**
  * replaced deprecated entity loading with new method ([a8f73627](https://github.com/Elgg/Elgg/commit/a8f73627d57007146c337babdb3f1b03d4d5c72e))
  * adds wrapper to the message, This discussion is closed ([a336db85](https://github.com/Elgg/Elgg/commit/a336db856c9086b22042590077e69979ba40bec9))
* **pages:** add canEdit to page deletion permission check ([454deb63](https://github.com/Elgg/Elgg/commit/454deb638554533820afebf301c5fb9bc270358e))
* **river:**
  * allow everyone to look at everyones activity page ([f15e7ff8](https://github.com/Elgg/Elgg/commit/f15e7ff8df4e3a4b4f2ccd545fe0395e5213ea3f))
  * rss layout supports mulitple installations in the same host ([2e7262b4](https://github.com/Elgg/Elgg/commit/2e7262b4d5dbebbcb24aa1a2dc841bc30007a67f))
* **search:** respect entity type/subtype instead of params type/subtype ([758263a3](https://github.com/Elgg/Elgg/commit/758263a3c75521fae44d65f84b578d834178282e))
* **session:** correctly sets cookie params for sessions ([565dd08c](https://github.com/Elgg/Elgg/commit/565dd08c884cdb05b40d157472da8a84b0e606a6))
* **upgrade:** no longer try to process upgrade files from before installation version ([15c6f109](https://github.com/Elgg/Elgg/commit/15c6f10949732f68888f1b2674cd869fbee1e69a))
* **views:**
  * allows passing base_url through gallery view ([fb32d683](https://github.com/Elgg/Elgg/commit/fb32d68331f2f02c943c3eb271bb0af43539da5b), closes [#7669](https://github.com/Elgg/Elgg/issues/7669))
  * show different text on widgetpanel toggle button when opened ([b4e63b45](https://github.com/Elgg/Elgg/commit/b4e63b45c980fcfb39e5252ed4f8c47b99d8e935))
  * elgg_view_menu_item shows no link for items with null href ([a64432cf](https://github.com/Elgg/Elgg/commit/a64432cfd3e655ddf0a0c832519d3ba690d4c955))
  * move function and menu items out of file typecloud view ([e28bcd9e](https://github.com/Elgg/Elgg/commit/e28bcd9e038e60a3f15230d80472dae280b1ce38))
  * makes admin panel mobile friendly ([a8d9eeca](https://github.com/Elgg/Elgg/commit/a8d9eeca42e27f7f4e2289deba875d15667fd34b), closes [#6742](https://github.com/Elgg/Elgg/issues/6742))


#### Deprecations

* **access:** deprecates elgg_get_access_object() and refactors access lib ([d19cf2bf](https://github.com/Elgg/Elgg/commit/d19cf2bf564b56d01deb37f538fb1acc7e52aea9))
* **filestore:** deprecate file_get_simple_type() and file_get_general_file_type() ([c6042cbe](https://github.com/Elgg/Elgg/commit/c6042cbe650b0c65a6a79fd08ad24ecc071afd00))
* **plugins:** formally deprecates use of $CONFIG in start.php ([ee8f2edc](https://github.com/Elgg/Elgg/commit/ee8f2edc50c1f9bcf2c1186f3214cb43c5e4270e))
* **view:** deprecate output/confirmlink for consolidated output/url with 'confirm' option ([6e5e3910](https://github.com/Elgg/Elgg/commit/6e5e3910f11fae3dcec6c30fcfbe999e59cdfddb), closes [#5810](https://github.com/Elgg/Elgg/issues/5810))
* **views:** deprecates use of the core/settings/tools view ([239b730f](https://github.com/Elgg/Elgg/commit/239b730fa6f79d31dde4b7b42948fbb41b9bb533))


#### Breaking Changes

* The CSSMin class included via minify was renamed to CSSmin.
If you were referring to it with capital M, you'll have to
change that to lower-case m.

However, note that Elgg's dependencies are not considered
public API, so this notice is only a courtesy. Please explicitly
declare your dependencies on third party vendors, even ones that
you know Elgg already includes. We may remove or update them at any time.
 ([c3b0d8bc](https://github.com/Elgg/Elgg/commit/c3b0d8bcf700e978833d1785c23ae0dbefa2280c))
* If you are checking out Elgg directly from GitHub,
you will need to run `composer install` after `git checkout` to
get your installation to a working state.
 ([2e60327f](https://github.com/Elgg/Elgg/commit/2e60327f4d349e98035c9b2e27451f3b1787b47e))


<a name="1.9.8"></a>
### 1.9.8  (2015-01-11)

#### Contributors

* Juho Jaakkola (1)
* Matt Beckett (1)
* Steve Clay (1)
* iionly (1)

#### Bug Fixes

* **css:** Correct z-index for autocomplete form field when opened in lightbox ([e993141f](https://github.com/Elgg/Elgg/commit/e993141fb010f7cba6d9d134029a719ba625e0d5))
* **notifications:** subject of comment notification email always starts with "Re: " ([b5175b56](https://github.com/Elgg/Elgg/commit/b5175b56280c0903fc28ab1caa0106bf730343ef), closes [#7743](https://github.com/Elgg/Elgg/issues/7743))
* **profile:** admin defined profile fields are once again back-compatible ([8e577be4](https://github.com/Elgg/Elgg/commit/8e577be4aa77305f55e18394e2572d6d28fa5278), closes [#7634](https://github.com/Elgg/Elgg/issues/7634))


<a name="1.9.7"></a>
### 1.9.7  (2014-12-14)

#### Contributors

* Jerôme Bakker (5)
* iionly (3)
* Jeroen Dalsem (2)
* Juho Jaakkola (2)
* Matt Beckett (1)

#### Documentation

* **design:** added the data model image from docs ([680c3cf8](https://github.com/Elgg/Elgg/commit/680c3cf817314d338eeb275e04500872e1560b6a))
* **general:** moved pronuncation file from docs ([3718dac7](https://github.com/Elgg/Elgg/commit/3718dac70236b46d88fb3a781160bed39c14d62d))
* **support:** added documentation about the support policies of Elgg ([bdd7855c](https://github.com/Elgg/Elgg/commit/bdd7855c51ed0f86361936866185b14730ecd76c))


#### Bug Fixes

* **core:** deprecation notices thrown at login/logout even if there's no valid reason ([d22a6406](https://github.com/Elgg/Elgg/commit/d22a64062b989d80ac9016962e977b467d728e88))
* **likes:** Uses getDisplayName() instead of assuming the object has value in title property ([7ece624f](https://github.com/Elgg/Elgg/commit/7ece624f8e089aa3fb62c4d4108ab3a5612dfcbe))
* **notifications:** Verifies that a notification method is registered before using it ([4eddf313](https://github.com/Elgg/Elgg/commit/4eddf313abea3eddc5a7e286e6b8707e0ad79a75), closes [#7647](https://github.com/Elgg/Elgg/issues/7647))
* **pageowner:** allow unsetting of page owner guid ([a57e1fbe](https://github.com/Elgg/Elgg/commit/a57e1fbecb4d5fb215c9a71c2f0c827975514959))
* **uservalidationbyemail:** usage of deprecated ['login', 'user'] event ([23939b80](https://github.com/Elgg/Elgg/commit/23939b8023aceda1a7b22907d4dd60f1f104cf4a))
* **views:** correctly close the comment form contents ([0420bd00](https://github.com/Elgg/Elgg/commit/0420bd00947fd01623dedb46846612563ac929a5))


<a name="1.9.6"></a>
### 1.9.6  (2014-12-01)

#### Contributors

* Jerôme Bakker (74)
* Paweł Sroka (7)
* Jeroen Dalsem (2)
* Brett Profitt (1)
* Juho Jaakkola (1)
* iionly (1)

#### Documentation

* **admin:**
  * moved the finding plugins page from docs ([d054a5fd](https://github.com/Elgg/Elgg/commit/d054a5fd9d24077100df645c39cdde047f38531f))
  * moved the plugin order page from docs ([dfb68cb0](https://github.com/Elgg/Elgg/commit/dfb68cb0ee6d71fa4b03c518e1ab56a3fb9d7ba8))
  * moved the getting help page from docs ([2546fc9f](https://github.com/Elgg/Elgg/commit/2546fc9f6edcbc3cc83b7238ec272ae8d94fa04c))
  * moved duplicate installation from docs ([53dfaca8](https://github.com/Elgg/Elgg/commit/53dfaca85a65ebba492bef45947d935c037eb0f8))
  * moved backup and restore page from docs ([e66a2432](https://github.com/Elgg/Elgg/commit/e66a2432ba833209ca2be7e4af019872c3eaf5e4))
* **design:** moved the Loggable page from docs ([02f68068](https://github.com/Elgg/Elgg/commit/02f6806848b223f755d2734353648cfce0a59e4b))
* **faq:**
  * moved the Javascript not working page from docs ([94a00252](https://github.com/Elgg/Elgg/commit/94a00252d2c3e88ba32cceb8e4ce906090406b12))
  * moved the Deprecation warnings page from docs ([66374e0f](https://github.com/Elgg/Elgg/commit/66374e0fb6f2d9d12e5b48ef2b8674967440a025))
  * moved the No images page from docs ([2b261c8f](https://github.com/Elgg/Elgg/commit/2b261c8f6917f4cb89bed2bea403fbe164f04279))
  * moved the File is missing an owner page from docs ([46f71887](https://github.com/Elgg/Elgg/commit/46f718874c72939466b39a7d86443b1be08a0c17))
  * moved the Copy a plugin page from docs ([a0b4b27a](https://github.com/Elgg/Elgg/commit/a0b4b27a4337ac000b790ca7a3f934dcce4fad7e))
  * moved the session length page from docs ([c337b834](https://github.com/Elgg/Elgg/commit/c337b8347306e1c8418864990b6e14075bee34af))
  * moved Emails don't support non-Latin characters from docs ([c6001fba](https://github.com/Elgg/Elgg/commit/c6001fbade057dedddbefb6370cdf8184c06b19a))
  * moved the What variables are reserved by Elgg page from docs ([2d5a2a16](https://github.com/Elgg/Elgg/commit/2d5a2a16c8706677f9926696456d3ae049a63423))
  * moved the IE login problem page from docs ([7445c19c](https://github.com/Elgg/Elgg/commit/7445c19c8fc9109fb960e6956b066b45d42f587c))
  * moved the page not found page from docs ([d0435c55](https://github.com/Elgg/Elgg/commit/d0435c551138ee547e3e64b840ca820f46d53f13))
  * move the Should I edit the database manually page from docs ([d04a1383](https://github.com/Elgg/Elgg/commit/d04a13837c2d7534f8f0ade0fd5cbb146c61c3ce))
  * moved the css is missing page from docs ([5b54b38c](https://github.com/Elgg/Elgg/commit/5b54b38c0cbf3c4b5d9864e82281394018d5502e))
  * moved the filtering page from docs ([68baa0e6](https://github.com/Elgg/Elgg/commit/68baa0e6183d542b41328e957bf672baf4f84f4b))
  * moved the When I upload a photo or change my profile picture I get a white screen page from docs ([ffbdd0d2](https://github.com/Elgg/Elgg/commit/ffbdd0d27eea20a40953861c704459b5e670dfe6))
  * moved the security faq from docs ([627ff4f5](https://github.com/Elgg/Elgg/commit/627ff4f5b2af42613edc965b86972dd249570e91))
  * moved the 500 - Internal Server Error page from docs ([68a8ce19](https://github.com/Elgg/Elgg/commit/68a8ce19b8ceeab3b0e94698cd9c0c2955865649))
  * moved the What events are triggered on every page load page from docs ([be493213](https://github.com/Elgg/Elgg/commit/be493213962c66ac1f3a554fc4cdb92c5b3a1335))
  * moved the Using a test site page from docs ([dc2fe2a7](https://github.com/Elgg/Elgg/commit/dc2fe2a7b1d9317aad1a83ce1d6377faf6038b37))
  * moved the Https login turned on accidently page ([aeb32f65](https://github.com/Elgg/Elgg/commit/aeb32f65e95e8e61ec371f867a7fee00356e16f1))
  * moved the debug mode page from docs ([6b2d18e3](https://github.com/Elgg/Elgg/commit/6b2d18e380d3f1720438e8e8ba7810e47e8b0863))
  * split the faq page into different files for readability ([bb1de6a6](https://github.com/Elgg/Elgg/commit/bb1de6a6d24d4be3cb1a160baf69e6bd8d2e66c4))
  * moved Manually add user from docs ([0fa6c070](https://github.com/Elgg/Elgg/commit/0fa6c07035e1d38f639413137bba87a056276678))
  * moved How do I change PHP settings using .htaccess from docs ([0defcaaa](https://github.com/Elgg/Elgg/commit/0defcaaa788253738683c0cdfeb8615df182e9d6))
  * moved how does registration work page from docs ([13ac44d3](https://github.com/Elgg/Elgg/commit/13ac44d3685e5b77d29dc7568432ce222b002ae6))
  * moved How do I find the code that does x from docs ([ef30d048](https://github.com/Elgg/Elgg/commit/ef30d048e86c3f798b3d9243829e4c40aabe7855))
  * move the I don't like the wording of something in Elgg page from docs ([9c13832b](https://github.com/Elgg/Elgg/commit/9c13832bb48a3f7878256c1872bcf2995eac33f8))
  * moved the Changing registration page from docs ([6d7e4f48](https://github.com/Elgg/Elgg/commit/6d7e4f48edc68e86f08b478fbac3328c2b469495))
  * moved the changing profile fields page from docs ([151d25a0](https://github.com/Elgg/Elgg/commit/151d25a033c3f891c4de8073e2dc43a93df12688))
  * moved What should I use to edit php code from docs ([375869d1](https://github.com/Elgg/Elgg/commit/375869d1945d078a3fc17f432f461d289538bfd9))
* **features:** added a link to the Elgg showcas page ([334d2010](https://github.com/Elgg/Elgg/commit/334d20101928554a076afbd5126a609d0e0e7920))
* **general:** adds contents indexes to long pages ([ebf316c9](https://github.com/Elgg/Elgg/commit/ebf316c9964b4d76e8c1f7377fd833aa1f8a7c09))
* **guides:**
  * moved Walled Garden page from docs ([4100ccef](https://github.com/Elgg/Elgg/commit/4100ccef8cefd81415ae0003f204189af56c74ba))
  * moved the Accessibility Guidelines from docs ([5b687a42](https://github.com/Elgg/Elgg/commit/5b687a42c32461a3ed5cbfe8b030f603c05581b8))
  * moved the systemlog page from docs ([de73bb22](https://github.com/Elgg/Elgg/commit/de73bb2240716ca78fa68b48064157221f64fec5))
  * moved the How to restrict where widgets can be used from docs ([17ec2d35](https://github.com/Elgg/Elgg/commit/17ec2d358767ef979211ae63332a0a42663c9d1d))
  * moved the javascript hooks page from docs ([71551797](https://github.com/Elgg/Elgg/commit/715517971431e6f9f02e844ab5717cb5d5f80e54))
  * moved the PluginDependencies from docs ([e37d79a2](https://github.com/Elgg/Elgg/commit/e37d79a2790e646478fe48292f9f4c61221f9ee0))
  * moved hmac authentication page from docs ([7b37f083](https://github.com/Elgg/Elgg/commit/7b37f083d2c44ceb4f7638407b3dd5960b3232e6))
  * moved don't modify core page from docs ([c99e0008](https://github.com/Elgg/Elgg/commit/c99e0008aaa90e5346a73e579351bcdd288c7789))
  * moved the ajax page from docs ([b758c731](https://github.com/Elgg/Elgg/commit/b758c731faf11673aed40f213074ebed0eb7429c))
  * moved Engine/Controllers/BestPractices from docs ([26f77b0d](https://github.com/Elgg/Elgg/commit/26f77b0d83dd2459f36828e19790a46c154a185f))
  * move plugin coding guidelines from docs ([9c4ee9f9](https://github.com/Elgg/Elgg/commit/9c4ee9f996564833fcbb5b729d2fe11d660c28d5))
  * moved the gatekeeper page from docs ([686fb7b2](https://github.com/Elgg/Elgg/commit/686fb7b25a7cc177b65685d8bf89e0b8864a7c8d))
  * moved simplecache from docs ([a2d9b474](https://github.com/Elgg/Elgg/commit/a2d9b474bb347ca4de10068366208bc4ee993de4))
  * moved the authentication page from docs ([0e928075](https://github.com/Elgg/Elgg/commit/0e9280755f3cb7c6e2f4da12788411e2b475f953))
  * moved the permissions check documentation from docs ([d9a6a88a](https://github.com/Elgg/Elgg/commit/d9a6a88a7d3274fb04a2d5aa25c674e3041f7e06))
  * moved the plugin (user)settings documentation from docs ([bf2d984d](https://github.com/Elgg/Elgg/commit/bf2d984d34976c856a9deecf48c0a7efe17334cb))
  * moved the context documentation from docs ([87bd91f9](https://github.com/Elgg/Elgg/commit/87bd91f90340ccb1286292260ffe5ec9db70c762))
  * moved the helper functions page from docs ([96d7d374](https://github.com/Elgg/Elgg/commit/96d7d3745e8811d2c11a0fa3e7ddca4b16d2c1bd))
  * moved the page handler documentation from docs ([e327d354](https://github.com/Elgg/Elgg/commit/e327d3549c1ae63ad949f1a38f1dbdaf37ace106))
* **guids:** moved page ownership from docs ([223d668a](https://github.com/Elgg/Elgg/commit/223d668afd0ae1b165ed3d608cd6f1c7d22a12b2))
* **pdf:** Added LaTeX build testing to Travis ([021a95c5](https://github.com/Elgg/Elgg/commit/021a95c559784786b50905f8381f7973f270c843))
* **plugins:**
  * moved the System diagnostics page from docs ([df2062a7](https://github.com/Elgg/Elgg/commit/df2062a7bfbeb772b9bd87c65caa6e67939fca6c))
  * moved the diagnostics page from docs ([b69c978c](https://github.com/Elgg/Elgg/commit/b69c978c71fc155a95772b3b14dc811e0c758218))
  * completed the list of bundled plugins ([2a886a84](https://github.com/Elgg/Elgg/commit/2a886a8420b3441634a04b86195e1d0ac352283b))
  * moved the thewire plugin description from docs ([5443e715](https://github.com/Elgg/Elgg/commit/5443e7157a8cb5759ebdf2b647a66ffadeee15f7))
  * moved the blog plugin description from docs ([722d1202](https://github.com/Elgg/Elgg/commit/722d12029a9d710edac349d99f74610c9df78966))
  * moved the messages plugin description from docs ([450c00b5](https://github.com/Elgg/Elgg/commit/450c00b59caad755a4909d846e02d9c8104eecc8))
  * moved the messageboard plugin description from docs ([5d06e409](https://github.com/Elgg/Elgg/commit/5d06e409effd4779ab2dba327fd2ec51594f652b))
  * moved the pages plugin description from docs ([47f9d2c8](https://github.com/Elgg/Elgg/commit/47f9d2c8f416a860e052f9caa8260650cc3416f1))
  * moved the profile plugin description from docs ([3fd4168c](https://github.com/Elgg/Elgg/commit/3fd4168cec942a144e8fe149d2ac1662e995156f))
  * moved the groups plugin description from docs ([0e1a6bdb](https://github.com/Elgg/Elgg/commit/0e1a6bdb5194e2c2cf1d832664db64081bc3f856))
  * moved the file plugin description from docs ([140fb7ba](https://github.com/Elgg/Elgg/commit/140fb7bafc6fba9d6fde9f5f1d86d322492beaf8))
  * moved the dashboard plugin description from docs ([2b17c2ce](https://github.com/Elgg/Elgg/commit/2b17c2ce31fe3b5d45394058b269607370d29f9d))
  * moved the plugin skeleton documentation ([d8ae89c7](https://github.com/Elgg/Elgg/commit/d8ae89c75d1e6d75c73cdd053cf2b616d43dc29a))
* **travis:** Added validation of translated docs sources for es language to Travis ([40d284e1](https://github.com/Elgg/Elgg/commit/40d284e171900e314fcd47149eb2f75200866660))
* **views:** moved the page structure best practices page from docs ([c441a3f1](https://github.com/Elgg/Elgg/commit/c441a3f111d626bfb64bab41e8ca448e4e97237e))


#### Bug Fixes

* **core:** getFilenameOnFilestore() returns empty string if an ElggFile object has no filename set ([a03591e7](https://github.com/Elgg/Elgg/commit/a03591e7252ea89502a0fb60e604cea2d372f971))
* **docs:**
  * Fixed docs elements not allowing LaTeX builds to succeed. ([659d5796](https://github.com/Elgg/Elgg/commit/659d5796b0bdf7a21b0d08d6c552554b953158f2))
  * Fixed docs syntax for the PDF builds. ([e3683683](https://github.com/Elgg/Elgg/commit/e36836838da991719e6fc174a490f20cde53af1f))
* **forms:** Removes icon and title links from autocomplete results ([aff7e69e](https://github.com/Elgg/Elgg/commit/aff7e69ea37268c4e9d069d1c35cbbd95e9b30d5), closes [#5583](https://github.com/Elgg/Elgg/issues/5583))
* **livesearch:** removed custom queries with ege* functions ([d3656fa2](https://github.com/Elgg/Elgg/commit/d3656fa20c3584417b4791b08dbe061a072e1514))
* **plugins:** trigger plugin hooks when saving plugin settings ([19c31361](https://github.com/Elgg/Elgg/commit/19c31361557a04d047618f33e9d1ad8906d73dad), closes [#6820](https://github.com/Elgg/Elgg/issues/6820), [#7502](https://github.com/Elgg/Elgg/issues/7502))
* **session:** Explicitly closing the session in the shutdown hook to work around APC session problems. ([7dbe7c6d](https://github.com/Elgg/Elgg/commit/7dbe7c6d54ec337f3c2e0a05ae7dd5c3cd562363), closes [#7186](https://github.com/Elgg/Elgg/issues/7186))
* **views:** allow numeric 0 values to show on user and group profile ([edee47e5](https://github.com/Elgg/Elgg/commit/edee47e5c6fecd01d36edf58dfec84080356f32e))


<a name="1.9.5"></a>
### 1.9.5  (2014-11-17)

#### Contributors

* Jeroen Dalsem (18)
* Brett Profitt (7)
* Steve Clay (7)
* Evan Winslow (3)
* Ismayil Khayredinov (3)
* Juho Jaakkola (3)
* Per Jensen (3)
* Jerôme Bakker (2)
* Paweł Sroka (2)
* Stian Liknes (2)
* Diego Andrés Ramírez Aragón (1)
* Matt Beckett (1)
* iionly (1)

#### Performance

* **db:** correctly re-enable query cache after ElggBatch run ([a8c3fbd9](https://github.com/Elgg/Elgg/commit/a8c3fbd972d0c641e1fd5f7f58f8c8504f4fdaf1))
* **river:** only fetch comments if comment_count > 0 ([db64e16d](https://github.com/Elgg/Elgg/commit/db64e16d4a9640c8b9f61dcb7ee9308031c992d7))


#### Documentation

* **actions:** Migrated actions section from old Getting Started docs and cleaned up related sections. ([d47a980f](https://github.com/Elgg/Elgg/commit/d47a980f67f8965666d87a094032a7a7039c376e))
* **admin:** Migrated Getting Started guide from wiki. ([11e589f6](https://github.com/Elgg/Elgg/commit/11e589f66e1a1321e96813168bb73fb6c53d550b))
* **all:**
  * Cleanup docs. ([914fa69a](https://github.com/Elgg/Elgg/commit/914fa69aa94fc854150d061c5563f8af495966e6))
  * Added getting started for developers. ([848d0d51](https://github.com/Elgg/Elgg/commit/848d0d5114c350cafa93281c68fe1ca1aeeefc6d))
* **coding:** improves docs for commits/amending/standards ([e8166d78](https://github.com/Elgg/Elgg/commit/e8166d78c664aa6cfee1c6ba3da53fd350c85dad))
* **database:** updated and expanded information on entity icons ([7bb60185](https://github.com/Elgg/Elgg/commit/7bb601858851d37d371f6386199b787c95282ba4))
* **events:** Updated event list and cleaned up existing event docs. ([433ed90c](https://github.com/Elgg/Elgg/commit/433ed90cc5bcc2d9bdce47c92418fe1aa9845322))
* **faqs:** Started migrating some FAQs. ([cd3afdcc](https://github.com/Elgg/Elgg/commit/cd3afdcc6915453e2ec10a42ed6bf494991a4771))
* **hook:** Updated hooks docs. ([327ecb48](https://github.com/Elgg/Elgg/commit/327ecb48894d20b2c85be448180c52c5e67e222a))
* **menus:** improve docs for menu item factory ([61751db6](https://github.com/Elgg/Elgg/commit/61751db6ccd2acf5c276b1abe036872a0a7a2e52))
* **notifications:** documentation for the notifications system ([ac12ac99](https://github.com/Elgg/Elgg/commit/ac12ac990e9d950e8165fefb6b8c2f54026f7343), closes [#7308](https://github.com/Elgg/Elgg/issues/7308))
* **style:** documents trailing whitespace policy and script ([798810c7](https://github.com/Elgg/Elgg/commit/798810c70b4afba55182c52c684bbce08a57cbda))


#### Bug Fixes

* **aalborg:** More robust grid reflows for smaller screens ([8d8155e7](https://github.com/Elgg/Elgg/commit/8d8155e7948869325ae5886ea188b77e8d08f4d3), closes [#7393](https://github.com/Elgg/Elgg/issues/7393))
* **access:** always display readable access level for ACCESS_* constants ([a74421f9](https://github.com/Elgg/Elgg/commit/a74421f9c0d7cb06a19021d9e673b8a51a56cb8d), closes [#6801](https://github.com/Elgg/Elgg/issues/6801))
* **core:**
  * renaming to _elgg_namespace_plugin_private_setting forgotten in unsetAllSettings ([782b75f2](https://github.com/Elgg/Elgg/commit/782b75f2767482025110b4d7f902bd18d6937e72))
  * prevent sql exception when metastring is interpreted as very large number in egef_metadata ([bab43d60](https://github.com/Elgg/Elgg/commit/bab43d60b920d4cd2c33a9a61c72ececb2143d38), closes [#7009](https://github.com/Elgg/Elgg/issues/7009))
* **css:** markdown code blocks should not should nested borders ([8c736c2f](https://github.com/Elgg/Elgg/commit/8c736c2f28ce98223399a50453087025f52931c4))
* **friends:** show friends collections menu item in friend context ([5073deeb](https://github.com/Elgg/Elgg/commit/5073deebda5743d4934cdfaf43f340865d5418c2))
* **i18n:**
  * Commit docs/*.mo files on release so docs can be translated ([8ca2b6b6](https://github.com/Elgg/Elgg/commit/8ca2b6b6a1ebb72df64b9d919ecc52bb4af4aa98), closes [#7034](https://github.com/Elgg/Elgg/issues/7034))
  * improved removing profile field delete failure notice ([a6f561e2](https://github.com/Elgg/Elgg/commit/a6f561e2e7ad01df67a46e46f83ebaf5fb2386b5))
  * grammar fix in upgrade warning ([f5d4d35f](https://github.com/Elgg/Elgg/commit/f5d4d35f772caaa641a368b3251bb81686b91403))
* **login:** also allow login by email in maintenance mode ([4258bc3d](https://github.com/Elgg/Elgg/commit/4258bc3d6b8b39c9f5d5a9013b1397236d430251))
* **menu:** only show access entity menu item if logged in ([714b0834](https://github.com/Elgg/Elgg/commit/714b08340a697f79a44f55fccedfeda33afb059d))
* **menus:** allow max depth of 20 to prevent losing menu items ([d3e33db3](https://github.com/Elgg/Elgg/commit/d3e33db30a1560e81514d75ed30044f849b41fd3))
* **pagination:** removes hard-coded arrows from php file ([eb136ef1](https://github.com/Elgg/Elgg/commit/eb136ef1a580b7fb1172379ff79baa908c05b00b), closes [#5298](https://github.com/Elgg/Elgg/issues/5298))
* **plugins:** trigger plugin hooks when saving plugin settings ([5afadfc8](https://github.com/Elgg/Elgg/commit/5afadfc8ba6ba480d029a9fdc649f952f61c42b4), closes [#6820](https://github.com/Elgg/Elgg/issues/6820))
* **relationships:** distinct ege* results when relationship_guid is not set ([4d87b950](https://github.com/Elgg/Elgg/commit/4d87b950891fa80545ef680d10e2e68b1b6801cb), closes [#5775](https://github.com/Elgg/Elgg/issues/5775))
* **release:** Corrected release script Windows system compatibility. ([00012389](https://github.com/Elgg/Elgg/commit/0001238921a5c1a1bc9e7ad65aabc22158ba6530))
* **upgrade:**
  * Rechecks that all annotation comments have been migrated to entities ([7d81094c](https://github.com/Elgg/Elgg/commit/7d81094c10d60e613723b6eac0995dfdd350c1be), closes [#7486](https://github.com/Elgg/Elgg/issues/7486))
  * Ensure that `$CONFIG` is always available to upgrade scripts ([c102a713](https://github.com/Elgg/Elgg/commit/c102a7138180b3fa04ec78aacbdcacbe53da150e), closes [#7457](https://github.com/Elgg/Elgg/issues/7457))
* **uservalidationbyemail:** makes emailsent page public ([70bbdd65](https://github.com/Elgg/Elgg/commit/70bbdd652ce3485ab3151e696f22bc8cad966785), closes [#7334](https://github.com/Elgg/Elgg/issues/7334))
* **views:**
  * Revert erroneous changes made to input/userpicker ([e4008c65](https://github.com/Elgg/Elgg/commit/e4008c657a1680c47015ce632c47c470f138a562))
  * input/userpicker can now remove all users on edit ([4cf113ab](https://github.com/Elgg/Elgg/commit/4cf113ab60f6e3c5e0d445f70fdd8cd530917642), closes [#6982](https://github.com/Elgg/Elgg/issues/6982))
  * comma separating links to text files in plugin list ([4e9b8ad1](https://github.com/Elgg/Elgg/commit/4e9b8ad125e5025ed967e83c4a6cb47c71186cb1), closes [#7420](https://github.com/Elgg/Elgg/issues/7420))
  * usersettings form now has correct userguid set ([2c204200](https://github.com/Elgg/Elgg/commit/2c204200da2be41d981b437080582e5297e1cd19))
  * use elgg-button-action class on all cancel buttons ([857df27a](https://github.com/Elgg/Elgg/commit/857df27a176da9e1afb4888c9cc9c1e793218394))
  * prevent output of empty heading when there is no page title ([c3f7f225](https://github.com/Elgg/Elgg/commit/c3f7f225bf5c6e009aa22c7af10ae17cbac018da))
  * add apple-touch-icon ([3e4d2164](https://github.com/Elgg/Elgg/commit/3e4d2164eefa65e74773a224feb08770de2e69ad), closes [#6176](https://github.com/Elgg/Elgg/issues/6176))
* **widgets:** determine default values for num display in content view ([bd20730d](https://github.com/Elgg/Elgg/commit/bd20730d9cc6b925dc17e34d4e1ac41f58336a4c))


<a name="1.9.4"></a>
### 1.9.4  (2014-10-20)

#### Contributors

* Juho Jaakkola (6)
* Jeroen Dalsem (4)
* Steve Clay (4)
* Per Jensen (3)
* Stian Liknes (3)
* Jerôme Bakker (1)

#### Documentation

* **plugins:** Information on activation/deactivation ([4e58ad4d](https://github.com/Elgg/Elgg/commit/4e58ad4d88861819fe17bb0a4be498905907125b))
* **web_services:** Documentation for Elgg 1.9 ([7cf0f8fd](https://github.com/Elgg/Elgg/commit/7cf0f8fd8a75defed22de8a184bbba3a09f6c3f8))


#### Bug Fixes

* **aalborg_theme:** display search when logged out ([31d3d190](https://github.com/Elgg/Elgg/commit/31d3d1905a3c6426838b2c67f28c1aa14c2a76e1))
* **bookmarks:** fixes more link in group bookmarks widget ([adb46369](https://github.com/Elgg/Elgg/commit/adb463699a35cbe18c4b48408f554ce7d2395264), closes [#6583](https://github.com/Elgg/Elgg/issues/6583))
* **css:**
  * stop CSS from overwriting the width and height added in CKEditor ([428234c0](https://github.com/Elgg/Elgg/commit/428234c0dd407758f3ca1e3917c6a0e8636311fb), closes [#7269](https://github.com/Elgg/Elgg/issues/7269))
  * removed datepicker fixed width causing visual bug ([803e05f5](https://github.com/Elgg/Elgg/commit/803e05f5719fcaffac4e42272f2da344d8f8f745))
  * prevent select box from overflowing its container ([3b7e94d5](https://github.com/Elgg/Elgg/commit/3b7e94d53e1d4315a942247d64eedaa576b323ba), closes [#7290](https://github.com/Elgg/Elgg/issues/7290))
* **database:** More robust sql script execution. ([0c5ed4f2](https://github.com/Elgg/Elgg/commit/0c5ed4f220906823f4bdc9f76f7b54c49fd32826))
* **db:** query cache properly handles more callable types ([b8e58304](https://github.com/Elgg/Elgg/commit/b8e5830418ec1a336afcae383008385853d074fa))
* **discussion:** discussion replies respect previous subscribers ([d699fe63](https://github.com/Elgg/Elgg/commit/d699fe63f5aa5ba68f5b8935fc47c69f726bd475))
* **i18n:**
  * improved change password email subject and body text ([ade6d1c1](https://github.com/Elgg/Elgg/commit/ade6d1c1776b6de328abe8c638988f745425a017))
  * translate notification messages to the recipient's language ([071b2989](https://github.com/Elgg/Elgg/commit/071b298985599792da762791e659dbfca1124590), closes [#7241](https://github.com/Elgg/Elgg/issues/7241), [#NaN](https://github.com/Elgg/Elgg/issues/NaN))
  * allow core to load translations for a specific language on-demand ([6417d213](https://github.com/Elgg/Elgg/commit/6417d213c1fc7e4944714bcd718783ac95dec4f9))
* **install:** prevent WSOD caused by site default language not being defined early enough ([3b9dc902](https://github.com/Elgg/Elgg/commit/3b9dc902c6c56c98e274238536f3f7159f1ae483))
* **search:** keep container param intact when navigating search results ([3dd87ec1](https://github.com/Elgg/Elgg/commit/3dd87ec19de40d03fba53b704c84c1cadb745dfd))


<a name="1.9.3"></a>
### 1.9.3  (2014-10-06)

#### Contributors

* Juho Jaakkola (3)
* Jeroen Dalsem (1)
* Steve Clay (1)

#### Documentation

* **js:** Adds docs for more JS functions, improves docs for elgg.echo ([fa0d0fa8](https://github.com/Elgg/Elgg/commit/fa0d0fa873d674083f199a6f588d39edf2dc048c))
* **menus:** document how to use menus ([18ac4008](https://github.com/Elgg/Elgg/commit/18ac4008bf3f32663df4bffca7a211dc11d15b20))


#### Bug Fixes

* **icons:** some elgg icons were not using internal view ([493e5c9f](https://github.com/Elgg/Elgg/commit/493e5c9fd0402b14428e23f3dec9c33e841de247))


<a name="1.9.2"></a>
### 1.9.2  (2014-09-21)

#### Contributors

* Juho Jaakkola (8)
* Steve Clay (2)

#### Documentation

* **manifest:** document how to use manifest.xml ([f4fa7487](https://github.com/Elgg/Elgg/commit/f4fa7487f6befdeb09ee891a4867ebbd99fac688))
* **river:** adds documentation on how to use the river ([d8be198c](https://github.com/Elgg/Elgg/commit/d8be198c1f6b549856c61f316653634c468c229f))
* **upgrade:** clarifies upgrade instructions about updating Elgg codebase ([6a8fec02](https://github.com/Elgg/Elgg/commit/6a8fec02857f3df7dadc3a7876d936689f319138), closes [#7225](https://github.com/Elgg/Elgg/issues/7225))
* **views:** adds documentation for the views system ([ff6cf55b](https://github.com/Elgg/Elgg/commit/ff6cf55be3f85d3d00cd2d4cf511adb8f66e8462))


#### Bug Fixes

* **install:** confirm that settings.php exists and is readable before including it ([aaa828ed](https://github.com/Elgg/Elgg/commit/aaa828edd980bc7b3cb45fec67c78f6581195bc3))
* **style:** ordered list markers now always visible ([ecccafc3](https://github.com/Elgg/Elgg/commit/ecccafc356349372e60e7ba7e9075ad1f4b2e0a9), closes [#7206](https://github.com/Elgg/Elgg/issues/7206))
* **upgrades:** now stores ElggUpgrade by paths instead of full URLs ([39cf72f0](https://github.com/Elgg/Elgg/commit/39cf72f0a25e1d383dc8310f92e10572f9204e30), closes [#6838](https://github.com/Elgg/Elgg/issues/6838))


<a name="1.9.1"></a>
### 1.9.1  (2014-09-12)

#### Contributors

* Juho Jaakkola (2)

#### Bug Fixes

* **upgrade:** fixes erroneous values in the list of processed upgrades ([c6ebbdb2](https://github.com/Elgg/Elgg/commit/c6ebbdb28442927e2254b3a8942ae53eae9c01e7), closes [#7198](https://github.com/Elgg/Elgg/issues/7198))


<a name="1.9.0"></a>
## 1.9.0  (2014-09-07)

#### Contributors

* Juho Jaakkola (3)
* Ismayil Khayredinov (1)
* Matt Beckett (1)

#### Bug Fixes

* **embed:** embed jquery target is now searched for instead of assuming last class ([cfe605d4](https://github.com/Elgg/Elgg/commit/cfe605d48ef96e855015d2cb0b08dfb1d2e26347))
* **i18n:** system cache now supports regional designators in language codes ([735ceb4e](https://github.com/Elgg/Elgg/commit/735ceb4e3feb0ccbf34fd7b59d3133d8a956eaac), closes [#7187](https://github.com/Elgg/Elgg/issues/7187))
* **messages:** use recipient's language in the notification ([ee88054f](https://github.com/Elgg/Elgg/commit/ee88054f215fee8260ad698425025c667207aad0), closes [#6902](https://github.com/Elgg/Elgg/issues/6902))


<a name="1.9.0-rc.7"></a>
### 1.9.0-rc.7  (2014-08-25)

#### Contributors

* Steve Clay (5)
* Juho Jaakkola (3)
* Paweł Sroka (2)
* Per Jensen (2)
* Brett Profitt (1)

#### Bug Fixes

* **aalborg_theme:** broken layout on small screens ([a2e88157](https://github.com/Elgg/Elgg/commit/a2e88157fce471e96151b4f508d8f218a78ff620), closes [#7175](https://github.com/Elgg/Elgg/issues/7175))
* **access:**
  * has_access_to_entity() now respects ACLs also when set to be ignored ([bac9a80a](https://github.com/Elgg/Elgg/commit/bac9a80a4bbc425688a2bbbcbf9cdb6f961f6068), closes [#7159](https://github.com/Elgg/Elgg/issues/7159))
  * get_access_array() works correctly when logged out ([7fb67a29](https://github.com/Elgg/Elgg/commit/7fb67a2929605bcc040067ddbf61c8a7dedfe798))
* **css:** removes padding and margin from elgg-menu-entity items ([04c5e61f](https://github.com/Elgg/Elgg/commit/04c5e61f27ce12f16aab05dcc97db4225abe9655))
* **discussion:** Fixes inline edit of replies temporarily changing applied styles to the text ([fa8572cb](https://github.com/Elgg/Elgg/commit/fa8572cbf7812c5c7eb97fc7b0e698a39cc6341e), closes [#6879](https://github.com/Elgg/Elgg/issues/6879))
* **notification:** avoids fatal error if notification event lacks object ([5dfa343d](https://github.com/Elgg/Elgg/commit/5dfa343dd452033808c03dc9ebdc26515660b532), closes [#7157](https://github.com/Elgg/Elgg/issues/7157))
* **output:** elgg_normalize_url no longer mistakes querystrings for domains ([505d249b](https://github.com/Elgg/Elgg/commit/505d249b926e78ec622e7cee3d58680fa6d26459))
* **profile:** Making banned users more obvious when using custom profile fields. ([c8c7098a](https://github.com/Elgg/Elgg/commit/c8c7098a77e9a8a8347e7f64771e19d4f5c87aee))


<a name="1.9.0-rc.6"></a>
### 1.9.0-rc.6  (2014-08-11)

#### Contributors

* Juho Jaakkola (6)
* Evan Winslow (5)
* Ismayil Khayredinov (5)
* Brett Profitt (2)
* Jerôme Bakker (2)
* Per Jensen (1)

#### Features

* **ckeditor:** add "clear formatting" button ([0f5525df](https://github.com/Elgg/Elgg/commit/0f5525df336e567366de26dbf14dd0cba243ed6a), closes [#7105](https://github.com/Elgg/Elgg/issues/7105))
* **likes:** improves compatibility with notification plugins ([ccfb65c3](https://github.com/Elgg/Elgg/commit/ccfb65c322853dec1d4600690848b1a8ea90783f))
* **notifications:** site_notification about an annotation can now have an URL ([124190eb](https://github.com/Elgg/Elgg/commit/124190ebf38f4466de39561f3fd3c60156649681), closes [#7055](https://github.com/Elgg/Elgg/issues/7055))


#### Documentation

* **all:**
  * improves formatting and comprehensibility of docs ([de3837be](https://github.com/Elgg/Elgg/commit/de3837be898975a3cf21021935fd98ee428a980b))
  * fixes typos and improves readability ([a7ac76ce](https://github.com/Elgg/Elgg/commit/a7ac76ce1d7714e473701bb9e5c28ef2274a7dd0))
* **contribute:** Updated recommendations on which branch to submit against ([b84269ce](https://github.com/Elgg/Elgg/commit/b84269ce05350c49bcfe90b126f405f69d5075ea), closes [#6964](https://github.com/Elgg/Elgg/issues/6964))
* **cron:** adds RST documentation about cron jobs ([65b10fd8](https://github.com/Elgg/Elgg/commit/65b10fd848e3ccf43467a97a207fd2ec6dd4403e))
* **js:** corrects function name to shim AMD modules ([091c8b2e](https://github.com/Elgg/Elgg/commit/091c8b2ef76e87aaf869f3cbc4b63861a4c1f29a), closes [#7072](https://github.com/Elgg/Elgg/issues/7072))
* **notifications:** Adds docs for  'object', 'action' and 'summary' params used by notify_user() ([ad00612f](https://github.com/Elgg/Elgg/commit/ad00612f7b0443f0148c229b23d1e3b4f56ae462))


#### Bug Fixes

* **embed:**
  * Checking for lightbox and embed before loading JS libs when requested through AJAX. ([e8c1b4fd](https://github.com/Elgg/Elgg/commit/e8c1b4fd8b24d3d20addb9590eda80af1a013834))
  * Manually load CSS/JS libs for embed when editing comments on the activity page. ([6cc585c6](https://github.com/Elgg/Elgg/commit/6cc585c61b09a639f4a2e2388a144f1468877c1c), closes [#6422](https://github.com/Elgg/Elgg/issues/6422))
* **groups:** removes ACCESS_PUBLIC from visibility options if walled garden is enabled ([70c911ee](https://github.com/Elgg/Elgg/commit/70c911ee5cfe71931a966e85bfa46f35c10e8a62))
* **js:** elgg.normalize_url no longer modifies urls that begin with a recognized scheme ([b6dc613e](https://github.com/Elgg/Elgg/commit/b6dc613e1b5f565b9bc5bfb470e64a2923d8d49e), closes [#6000](https://github.com/Elgg/Elgg/issues/6000))
* **notification:** extract notification summary from $params ([c966fcae](https://github.com/Elgg/Elgg/commit/c966fcae3b7f0165c931e2ce5822fa8fec67875b), closes [#6885](https://github.com/Elgg/Elgg/issues/6885))
* **pages:** Stop registering undefined upgrade event callback ([53eba1e0](https://github.com/Elgg/Elgg/commit/53eba1e019dee04669610d57474615e8d757bcda), closes [#6780](https://github.com/Elgg/Elgg/issues/6780))
* **views:** respect icon_sizes config values when rendering icons ([54858e97](https://github.com/Elgg/Elgg/commit/54858e97dabb04d5ef4d0e91ae73d1ac6bc6eabc))
* **walled_garden:** ACCESS_PUBLIC no longer available in group context ([7c4ec694](https://github.com/Elgg/Elgg/commit/7c4ec694c5748c0ac42bfa6dca76e927cd46c775))


<a name="1.9.0-rc.5"></a>
### 1.9.0-rc.5  (2014-07-10)

#### Contributors

* Matt Beckett (3)
* Jerôme Bakker (1)

#### Bug Fixes

* **core:** output/iframe made to the w3c standard ([cb25d684](https://github.com/Elgg/Elgg/commit/cb25d68478ba78115d027d587981542467dee842))
* **river:** add enabled col to river table, update on enable/disable of referenced entities ([eb041ebd](https://github.com/Elgg/Elgg/commit/eb041ebd822eb461a008ef3da93ee35d613af973), closes [#6022](https://github.com/Elgg/Elgg/issues/6022))
* **upgrade:** use correct table prefixes on river upgrade script ([1c5c2b63](https://github.com/Elgg/Elgg/commit/1c5c2b632c790cef0d1e401f3f5493da785c13ec), closes [#7033](https://github.com/Elgg/Elgg/issues/7033))


<a name="1.9.0-rc.4"></a>
### 1.9.0-rc.4  (2014-07-10)

#### Contributors

* Evan Winslow (9)
* Paweł Sroka (6)
* Matt Beckett (3)
* Jeroen Dalsem (2)
* Paul Shepel (2)
* Steve Clay (2)
* Adrián Chaves Fernández (Gallaecio) (1)
* JoseLGM (1)
* Per Jensen (1)

#### Features

* **discussions:** Added email SMTP headers for better thread grouping. ([91755a86](https://github.com/Elgg/Elgg/commit/91755a86b7ea89db71e29d632c23120b9938e87b), closes [#6894](https://github.com/Elgg/Elgg/issues/6894))


#### Documentation

* **i18n:** internationalized the documentation ([ff5fd9be](https://github.com/Elgg/Elgg/commit/ff5fd9bee7ff956cf6089bfb7d15847406f205b4), closes [#5899](https://github.com/Elgg/Elgg/issues/5899))
* **upgrading:** Added upgrade instructions for 1.8 to 1.9 ([001e3ffa](https://github.com/Elgg/Elgg/commit/001e3ffa46688f4210e284458b5f72db106453aa), closes [#5900](https://github.com/Elgg/Elgg/issues/5900))


#### Bug Fixes

* **aalborg_theme:** selected page menu does not collapse sub menu ([53f696ce](https://github.com/Elgg/Elgg/commit/53f696ce36b6555ebc1766d77429f04242b7c88d), closes [#6979](https://github.com/Elgg/Elgg/issues/6979))
* **collections:**  make urls work regardless of username ([76827f22](https://github.com/Elgg/Elgg/commit/76827f22f7092608f02a560d7dc2bda93f6ca994), closes [#6059](https://github.com/Elgg/Elgg/issues/6059))
* **core:** Added missing options array support for ElggUser methods ([30d98c67](https://github.com/Elgg/Elgg/commit/30d98c67c1a097b57ad3e684c40d53edff312603), closes [#6994](https://github.com/Elgg/Elgg/issues/6994))
* **deprecation:** the deprecation wrapper correctly handles array access ([264fc5f2](https://github.com/Elgg/Elgg/commit/264fc5f2adcad7d4ed2a9d748add58a24437b39b), closes [#7017](https://github.com/Elgg/Elgg/issues/7017), [#6917](https://github.com/Elgg/Elgg/issues/6917))
* **discussion:** no longer show entity menu items on non-discussions ([d3c7c953](https://github.com/Elgg/Elgg/commit/d3c7c9535beedcd563ac3f1ae6e98e01a68e29d2), closes [#6508](https://github.com/Elgg/Elgg/issues/6508))
* **file:**
  * destroy output buffer before sending file ([007021ff](https://github.com/Elgg/Elgg/commit/007021ff67727e618d02a3fda05d78175f7ec082))
  * download adds header Content-Length ([8375eb09](https://github.com/Elgg/Elgg/commit/8375eb09d9daeddc818d99db481880eca4f24de6))
* **groups:** give feedback if a user cannot be added to a group ([07cddc61](https://github.com/Elgg/Elgg/commit/07cddc615b7791d9bff677f7efdd7e88ef7aac40), closes [#6081](https://github.com/Elgg/Elgg/issues/6081))
* **install:** Make installer usable on smartphones ([b528d988](https://github.com/Elgg/Elgg/commit/b528d98894061fcf3a162882418e98f72794c8c7))
* **members:** prevent members search with empty query ([12f7b88f](https://github.com/Elgg/Elgg/commit/12f7b88f28a60ff8348bc122884c7e8d6c183e4f))
* **notifications:** Corrected html entities handling for email subject and body ([4bfb849e](https://github.com/Elgg/Elgg/commit/4bfb849ecd0ec9ece59e445149dd252dfc352d32), closes [#6905](https://github.com/Elgg/Elgg/issues/6905))
* **release:** Corrected release script Windows system compatibility ([18f78403](https://github.com/Elgg/Elgg/commit/18f78403564e1432ff66d486d959c9e1a76fbd5e))
* **router:** Can return 'handler' param in `'route', $identifier` hook again ([6e09758f](https://github.com/Elgg/Elgg/commit/6e09758fe9bf43dd7ef8b648cc2afd7701f4d651), closes [#6696](https://github.com/Elgg/Elgg/issues/6696))
* **rss:** River entries include their full correct summaries again ([96679d8b](https://github.com/Elgg/Elgg/commit/96679d8b774048a5be7fde1da216cedbf6516253), closes [#6901](https://github.com/Elgg/Elgg/issues/6901))
* **thewire:** More effective textarea change detection ([e07f6975](https://github.com/Elgg/Elgg/commit/e07f697594996fc931a3cd1d2849480d83ff60f2))
* **ui:** Corrected bad stretching of non-square, large avatars. Now upscaling by width. ([71ea155b](https://github.com/Elgg/Elgg/commit/71ea155bf9188abc9683a81d7f2df38da4bc0104), closes [#5602](https://github.com/Elgg/Elgg/issues/5602))
* **upgrade:** test for ability to connect to localhost if rewrite test fails ([7c49e4ce](https://github.com/Elgg/Elgg/commit/7c49e4ceee996f53ef0120df4ff0c2850c63652b), closes [#6888](https://github.com/Elgg/Elgg/issues/6888))


<a name="1.9.0-rc.3"></a>
### 1.9.0-rc.3  (2014-06-23)

#### Contributors

* Evan Winslow (4)
* Paweł Sroka (1)
* Per Jensen (1)
* RiverVanRain (1)

#### Bug Fixes

* **a11y:**
  * Add semantic structure to installer page layout ([f446e6f1](https://github.com/Elgg/Elgg/commit/f446e6f1ad328fb8573b947fd7a2f0d52cb31955))
  * Use HTML5 form features on install forms ([434efa22](https://github.com/Elgg/Elgg/commit/434efa22228fa1217553951b68ccdff0959ed3a7))
  * Label form fields in installer correctly ([dff254a9](https://github.com/Elgg/Elgg/commit/dff254a9417525660234b7aab5f165cbf11b7bde))
* **aalborg_theme:** removes unwanted  margins ([b972402d](https://github.com/Elgg/Elgg/commit/b972402da3822abf59fdee5f6126a53f52c1fe48))
* **replies:** Show reply's link on river ([5fc031a5](https://github.com/Elgg/Elgg/commit/5fc031a574543f914ff0694b447a7ab399f0a2e5))


<a name="1.9.0-rc.2"></a>
### 1.9.0-rc.2  (2014-06-09)

#### Contributors

* Evan Winslow (11)
* Per Jensen (4)
* Paweł Sroka (2)
* Jeroen Dalsem (1)
* John Supplee (1)

#### Bug Fixes

* **UserPicker:** no messages in userpicker due to lack of i18n ([7d7a7d5e](https://github.com/Elgg/Elgg/commit/7d7a7d5eedb22d6370c3adb5118da27523c6e4fc))
* **aalborg_theme:**
  * emphasizes sidebar navigation ([6ae2148c](https://github.com/Elgg/Elgg/commit/6ae2148c6a7b8fde1ead97f3d90dc1a039ebf44f), closes [#6874](https://github.com/Elgg/Elgg/issues/6874))
  * Support fullscreen mode if user adds app to homescreen ([2a193078](https://github.com/Elgg/Elgg/commit/2a193078f86bc700311df5f95369b8bdd7110336), closes [#6896](https://github.com/Elgg/Elgg/issues/6896))
  * show dashboard menu item only when logged in ([c3e0fcb8](https://github.com/Elgg/Elgg/commit/c3e0fcb8a0f2928ba8ada6ce9fff677765d701c8))
  * inconsistency between owner-block and page menu ([f54048a5](https://github.com/Elgg/Elgg/commit/f54048a5511cf8054006d474a18ebdcfef233b6e))
  * only pass body_attrs if they are set ([6ab77862](https://github.com/Elgg/Elgg/commit/6ab77862ee899a54d59086d8cf625846210fea4b))
* **deprecated:** Corrected invalid deprecation notice and added more details to few others ([5d78e2b1](https://github.com/Elgg/Elgg/commit/5d78e2b13d886bfb02ec13a014116adb4aa123df), closes [#6869](https://github.com/Elgg/Elgg/issues/6869))
* **docs:** Inline refs use @link instead of @see ([50b0e39e](https://github.com/Elgg/Elgg/commit/50b0e39e8ed3e0bdc916327848c7a0e40ca426b0))


<a name="1.9.0-rc.1"></a>
### 1.9.0-rc.1  (2014-05-19)

#### Contributors

* Cash Costello (689)
* Steve Clay (226)
* Evan Winslow (150)
* Paweł Sroka (136)
* Sem (91)
* Brett Profitt (68)
* Jeroen Dalsem (59)
* Juho Jaakkola (54)
* Per Jensen (23)
* Ismayil Khayredinov (23)
* RiverVanRain (16)
* Matt Beckett (12)
* hellekin (12)
* Jerôme Bakker (8)
* Aday Talavera (7)
* Jeff Tilson (7)
* Marcus Povey (5)
* Rasmus Lerdorf (5)
* Brad Smith (5)
* Hayden Shaw (3)
* Ben Werdmuller (3)
* András Szepesházi (2)
* slyhne (2)
* Facyla (2)
* ManUtopiK (1)
* Emmanuel (1)
* Centillien (1)
* twentyfiveautumn (1)
* Janek Lasocki-Biczysko (1)
* Ash Ward (1)
* Arsalan Shah (1)
* Angel Gabriel Vargas Beltran (1)
* Tantek Çelik (1)
* Team Webgalli (1)
* bwoodnz (1)
* Danny Navarro (1)
* EC2 Default User (1)
* Kody Peterson (1)
* Liang Lee (1)

#### Features

* **admin:** Admin notices are removed when the actions requested actions has been taken. ([e6a46a84](https://github.com/Elgg/Elgg/commit/e6a46a84fa7c9b051fb85ec03ff0774f7708ab74), closes [#6453](https://github.com/Elgg/Elgg/issues/6453))
* **amd:** added some utils to Elgg_Amd_Config class ([c45d4d18](https://github.com/Elgg/Elgg/commit/c45d4d184abc7c8058cb40ea52f9ef48220290b3))
* **comments:** Added separate edit page for generic comments ([a5c73b6e](https://github.com/Elgg/Elgg/commit/a5c73b6e7bd6eb12ad669c9afd3ee27ba8996349), closes [#6666](https://github.com/Elgg/Elgg/issues/6666))
* **core:**
  * better registration of usersettings handlers ([6469d55d](https://github.com/Elgg/Elgg/commit/6469d55dab3c424307c7091cfc6133b14b7cc670))
  * allow custom local scripts to trigger on uncaught exceptions #6586 ([7e0794ca](https://github.com/Elgg/Elgg/commit/7e0794ca184ecb308ea51b2dfd61041dcc128c17))
  * Allowing upgrade.php to forward to custom URLs. ([e5c11d8c](https://github.com/Elgg/Elgg/commit/e5c11d8c5ba681a73dec20c963d58a6b55555b99), closes [#6442](https://github.com/Elgg/Elgg/issues/6442))
* **externalpages:** page layout changed to one_column ([909536f9](https://github.com/Elgg/Elgg/commit/909536f976af289560b5e474a4b0d0c1332db140))
* **graphics:** make logos transparent ([2fc838c0](https://github.com/Elgg/Elgg/commit/2fc838c011932dba4add9b12aa043d425bb9fc3f))
* **lightbox:** added binding for elgg-lightbox-photo CSS class ([6eb22a2d](https://github.com/Elgg/Elgg/commit/6eb22a2ddfea2ed1bd2bb7e47ac559f154987e0f))
* **output:** Added second parameter to elgg_strip_tags. ([39f8d80c](https://github.com/Elgg/Elgg/commit/39f8d80c6d8845194b8b7d928545534d39b7e574))
* **search:** Allows filtering/reordering types returned in search ([5eebf1e6](https://github.com/Elgg/Elgg/commit/5eebf1e60c0f0974479f7d531293c1b01b1daa3e), closes [#6118](https://github.com/Elgg/Elgg/issues/6118))
* **ui:**
  * adds fallback png favicons ([5168a576](https://github.com/Elgg/Elgg/commit/5168a576e6437438dcbe202c83721d68073e8a1a))
  * add svg favicon ([6c84d2f3](https://github.com/Elgg/Elgg/commit/6c84d2f394530bcaceb377e734c075c227923cb7))
* **upgrade:** Added ElggUpgrade object. ([3aae56b4](https://github.com/Elgg/Elgg/commit/3aae56b4c3f41c171e5e6eb0678b63e16d59da19))
* **users:** making nicer lost password process ([d7c6f850](https://github.com/Elgg/Elgg/commit/d7c6f850415b42a6ebaee254060874ff310d9de7), closes [#5886](https://github.com/Elgg/Elgg/issues/5886))
* **uservalidationbyemail:** forwarding to an info page after registration ([6fbb8c93](https://github.com/Elgg/Elgg/commit/6fbb8c935d29c891ef5ba07470a74ea3e0f7815c), closes [#6247](https://github.com/Elgg/Elgg/issues/6247))
* **ux:** Failed file uploads give better error messages. ([8eb652c2](https://github.com/Elgg/Elgg/commit/8eb652c2fce56dfb86c5f9180cb9ab7913648d1a), closes [#6593](https://github.com/Elgg/Elgg/issues/6593))
* **views:** bypasses minification for views like -min/.min ([0462bdff](https://github.com/Elgg/Elgg/commit/0462bdff6179c8c196861fb2cd2a1cbfd210559a), closes [#6260](https://github.com/Elgg/Elgg/issues/6260))


#### Performance

* **groups:** remove redundant filter of user-owner group acls ([a65df346](https://github.com/Elgg/Elgg/commit/a65df34610d983d2dad7fcb0dc443e0baebbc11f), closes [#6434](https://github.com/Elgg/Elgg/issues/6434))
* **upgrade:**
  * ajaxifies data directory migration ([031b77fc](https://github.com/Elgg/Elgg/commit/031b77fc7c5b0db57d7eb8b34d06e6f9e075d706), closes [#6202](https://github.com/Elgg/Elgg/issues/6202))
  * speeds up migrating remember me codes ([52f9fa4c](https://github.com/Elgg/Elgg/commit/52f9fa4c7c9bad28140596809b26a30a2b286abd), closes [#6204](https://github.com/Elgg/Elgg/issues/6204))


#### Documentation

* **aalborg_theme:** document change of content order ([0ed207d9](https://github.com/Elgg/Elgg/commit/0ed207d904fe5c53c3926bc11e45aae5321f85f9), closes [#5787](https://github.com/Elgg/Elgg/issues/5787))
* **about:** Add contributing, history, releases, and values docs ([1b67f575](https://github.com/Elgg/Elgg/commit/1b67f575d2e50e7e34d4ad252a07c181b33b0fbb))
* **admin:** Created new admin section for admin-specific docs ([abc55ef1](https://github.com/Elgg/Elgg/commit/abc55ef1b1443eaf364dd48dc0cd5510d097c9c9))
* **all:** Cut fluff ([bae2d199](https://github.com/Elgg/Elgg/commit/bae2d199addd85aeffcf6f5c642a5d59264b8e74))
* **amd:** Separated design and guides for AMD docs. ([d0ebcb7a](https://github.com/Elgg/Elgg/commit/d0ebcb7aa70add70f1b08b9a1dbcee89ac043e60))
* **appendix:** move about => appendix; add contribute guide ([118bfa16](https://github.com/Elgg/Elgg/commit/118bfa1613ad5aba4359e199798fbfff222ce038))
* **autoloader:** more accurate docs for autoloader ([5fdbc181](https://github.com/Elgg/Elgg/commit/5fdbc181b315c76762fce92db23cb19bc8d54d0d))
* **changelog:**
  * Fully updated CHANGELOG.md (to 1.9.0-dev) ([31d757dd](https://github.com/Elgg/Elgg/commit/31d757dd2d5a79f5952adafeef37a86ab283aeed), closes [#5798](https://github.com/Elgg/Elgg/issues/5798))
  * Better markdown formatting ([9e66e428](https://github.com/Elgg/Elgg/commit/9e66e428e28007f103da07381f67e6ab337fe6ea))
  * Move CHANGES.txt to CHANGELOG.md ([d13673c9](https://github.com/Elgg/Elgg/commit/d13673c951e746d2ff47c00cb631dae4d939469d))
* **code:**
  * fix whitespace warnings ([110a6844](https://github.com/Elgg/Elgg/commit/110a6844244af85f98e30981deefd3f23c93c9eb))
  * add docs on testing and cleanup ([d9f2cdca](https://github.com/Elgg/Elgg/commit/d9f2cdcab947d41240fb90273eba4a359fe5a2b0))
  * merge coding_standards into contribute/code.rst ([b752e6dd](https://github.com/Elgg/Elgg/commit/b752e6ddda0a7e9698e4fc0cbfea6800d3a58df7))
* **config:** document custom exception handling ([7dde7bf4](https://github.com/Elgg/Elgg/commit/7dde7bf41108827e20c275023254c441eee4bd0d))
* **contribute:**
  * add instructions for translators ([feb16f6a](https://github.com/Elgg/Elgg/commit/feb16f6a2d0de590b9272c6366a230a1393ad7d7))
  * add instructions for rewriting commit messages ([4e5d6e3c](https://github.com/Elgg/Elgg/commit/4e5d6e3c4e7b361bcf0af5b7c6d3d023b00d0711))
  * clean up PR instructions ([16308a46](https://github.com/Elgg/Elgg/commit/16308a46f12725933bd99ac3f88ea273b75d2335))
  * expanded contributors section ([b969080c](https://github.com/Elgg/Elgg/commit/b969080cf810a9f6eed7f808dd7dccbd7a464744))
* **events:** Reword docs for hooks ([3cb690fe](https://github.com/Elgg/Elgg/commit/3cb690fe8a6fde38662f6d5627fb8885aa609265))
* **fix:** Fixing MD links for new docs. ([04c399f3](https://github.com/Elgg/Elgg/commit/04c399f3ffd30aee41eeb2d8549c23d8f0e1b0a4))
* **guides:** add web services dev guide ([400a2453](https://github.com/Elgg/Elgg/commit/400a2453bd1e73f542dfd9266df06a754d471478))
* **hooks:** Corrects docs syntax error ([69ae152d](https://github.com/Elgg/Elgg/commit/69ae152db7ef43f324144a305a1e89cb8f2e6a1c))
* **i18n:** Add minimal i18n documentation ported from wiki ([6b0e58df](https://github.com/Elgg/Elgg/commit/6b0e58df5ec94fea3286ec6293770e8ee2340a59))
* **js:** use proper title markup ([77146f46](https://github.com/Elgg/Elgg/commit/77146f4675d69155a070a3dbf9cbdc68f781f7f5))
* **moved:** use :orphan: instead of lying toctree ([6544176d](https://github.com/Elgg/Elgg/commit/6544176d3cb7edf63517294bd65f827e457a841b))
* **outline:** make titles consistent with each other ([d554e9b0](https://github.com/Elgg/Elgg/commit/d554e9b0c8bef16caeacb17f495145b7236f7198))
* **performance:** add suggestions for performance and scalability ([76e3ecd1](https://github.com/Elgg/Elgg/commit/76e3ecd166e14b68b49829a3b0116984a335f542))
* **plugins:** added instructions on upgrading plugins for Elgg 1.9 ([b1c501c4](https://github.com/Elgg/Elgg/commit/b1c501c4704a3b30baff40e6b8fdfc9f28631892))
* **release:** add release process workflow ([5df29847](https://github.com/Elgg/Elgg/commit/5df29847b7a1293df0857e5bc4ee05d6dc65a4c8))
* **releases:** add commit access as requirement ([9821089c](https://github.com/Elgg/Elgg/commit/9821089ca8280e425ddb282c83b4b6cf1859206d))
* **rewrite:** Finish migration from what we had in GDocs ([ce8c40b3](https://github.com/Elgg/Elgg/commit/ce8c40b385131e79304df52e56a636c02ea5bcaf))
* **security:** Add security rst docs ([fc52baf3](https://github.com/Elgg/Elgg/commit/fc52baf37a51f2e9982b02f99d9562be21b2077e))
* **themes:** converts theming docs to rst ([fddd7686](https://github.com/Elgg/Elgg/commit/fddd76861b19fb7162f7f5cae0b789dcca0eb859))
* **tutorials:** Add blog, indexpage, widget, and wysiwyg ([faafc2e2](https://github.com/Elgg/Elgg/commit/faafc2e26afcab8bc0ef5a464d66664314fcc892))
* **updates:** Updating contributing docs to point to feedback and planning group and to mentio ([c10f09f8](https://github.com/Elgg/Elgg/commit/c10f09f868a3b7b6b00c2e9fe829284894fe125f))
* **views:** Updated docs for elgg_extend_view() to address uniqueness of extended views. ([06c95e4b](https://github.com/Elgg/Elgg/commit/06c95e4be9aa14329c380fc3a792e01a8aa5527e), closes [#6661](https://github.com/Elgg/Elgg/issues/6661))


#### Bug Fixes

* **a11y:** keep focus outlines by default ([56add7a6](https://github.com/Elgg/Elgg/commit/56add7a6eba3a28bd9dc2e7af22fb03d2b9a145d), closes [#6319](https://github.com/Elgg/Elgg/issues/6319))
* **aalborg_theme:** standardize padding on input and a buttons ([dbc510d7](https://github.com/Elgg/Elgg/commit/dbc510d79935f3ccbad8bbf3c825cc617851a50f))
* **admin:**
  * show plugin settings menu on markdown page ([19e3e8d3](https://github.com/Elgg/Elgg/commit/19e3e8d36c9612c041017127d852dd8881ddbf55))
  * fixed javascript error on toggle simple cache checkbox ([0533f2a3](https://github.com/Elgg/Elgg/commit/0533f2a3aac63d8b67a25525739777edc30e7437), closes [#6529](https://github.com/Elgg/Elgg/issues/6529))
* **amd:** removed elgg_require_js for backwards compatibility ([76584089](https://github.com/Elgg/Elgg/commit/76584089bee2b3246c736edb6b250e149acf906f), closes [#6496](https://github.com/Elgg/Elgg/issues/6496))
* **autocomplete:** use group summary instead of profile view ([82c6871c](https://github.com/Elgg/Elgg/commit/82c6871cd8daf9c06872ae2e09dda601324e8075))
* **ckeditor:**
  * create a new input element was re-enabling other input fields ([04ab5b65](https://github.com/Elgg/Elgg/commit/04ab5b656f0ee2333009d69ef844ef7c4dd96238))
  * makes sure basepath is set early enough ([9b84b0c2](https://github.com/Elgg/Elgg/commit/9b84b0c21178fa2310305946e1d40db9a47ac296))
* **comments:**
  * give comment authors edit privileges ([68c6ded7](https://github.com/Elgg/Elgg/commit/68c6ded7f6a1527fac0bb1f31e00cd780e27c5ed), closes [#6724](https://github.com/Elgg/Elgg/issues/6724))
  * fixes cancel button and forward URL on edit comment page ([2b76dad7](https://github.com/Elgg/Elgg/commit/2b76dad7ce82e497eca91e8f8aef67859e1e528f))
  * makes ElggComment E_STRICT compliant ([3f5f4728](https://github.com/Elgg/Elgg/commit/3f5f4728bb1dcd4a70cefdffd1ef9e18dfdd12be))
* **core:**
  * prevents upgrade scripts from attempting to create the same ElggUpgrade more tha ([3d5fc912](https://github.com/Elgg/Elgg/commit/3d5fc912e329e9033bbf06499c809581fc3386a0), closes [#6824](https://github.com/Elgg/Elgg/issues/6824))
  * no longer strip slashes on $_FILES and $_SERVER ([4a32796b](https://github.com/Elgg/Elgg/commit/4a32796b6bb1d217eef27c6f8e89f484db5e671a), closes [#6777](https://github.com/Elgg/Elgg/issues/6777))
  * Updated upgrade file for remember me cookies for 1.9's new table. ([c4b53e4f](https://github.com/Elgg/Elgg/commit/c4b53e4ff12d9e0b4256e770c0d786519aaf5700), closes [#6629](https://github.com/Elgg/Elgg/issues/6629))
  * MenuBuilder sortByName should use strnatcmp ([9f373b3e](https://github.com/Elgg/Elgg/commit/9f373b3eecaa9228b91f297af6df52f1bdce8d10))
  * Not redirecting in plugin and site entity views. ([1c027648](https://github.com/Elgg/Elgg/commit/1c0276481f3724ff2accf29d9f8f0063450d65cb), closes [#4439](https://github.com/Elgg/Elgg/issues/4439))
  * fault in ElggPlugin contructor ([41053468](https://github.com/Elgg/Elgg/commit/41053468ec381a4480b409d07d547d6c46a24ec4))
* **css:**
  * selected page menu does not collapse sub menu ([f9af6a66](https://github.com/Elgg/Elgg/commit/f9af6a668b7c2802886de632212ca32d76541e40))
  * add hover classes to hover icons ([fb2e9a36](https://github.com/Elgg/Elgg/commit/fb2e9a367661d0a4e4b21eb3ff368239db67001e), closes [#6737](https://github.com/Elgg/Elgg/issues/6737))
  * reposition powered by elgg ([9b3d43fc](https://github.com/Elgg/Elgg/commit/9b3d43fc7f9953e0a70be0839d1ed3dc809ba5d1))
  * prevent stretching images in IE8 ([61ac1874](https://github.com/Elgg/Elgg/commit/61ac1874ea8132fffa8dc73be789d2c42fb68f0f))
  * Added CSS for elgg-state-selected menu items in theme sandbox. ([e5741ce5](https://github.com/Elgg/Elgg/commit/e5741ce5250e1c721eca44ca25271475d057fc08))
* **developer_tools:** Added all of the defined icons to the theme preview. ([d0ccfc1b](https://github.com/Elgg/Elgg/commit/d0ccfc1b86f45479959bb4623b846bbdd8742714))
* **docs:**
  * Fixed links in mediawiki format instead of reST. Removed reference to Transifex ([b5c9f419](https://github.com/Elgg/Elgg/commit/b5c9f419ae18cb12f8d5ade56e1445d017eba2b9))
  * fix a couple of typos ([e34f57d7](https://github.com/Elgg/Elgg/commit/e34f57d752bf299bd540b11378caba346dfa865a))
* **embed:**
  * show error when when uploading too large file in embed form ([a661c65c](https://github.com/Elgg/Elgg/commit/a661c65c636272fd2a82265b0b5deffbc508ec85), closes [#4591](https://github.com/Elgg/Elgg/issues/4591))
  * file embedding wasn't working for textareas ([4f1ffdec](https://github.com/Elgg/Elgg/commit/4f1ffdecfb0d9b86ece36495c6ad1c0e3c8f6d1e), closes [#6160](https://github.com/Elgg/Elgg/issues/6160))
  * Allows embedding files from the containing group ([d5aea243](https://github.com/Elgg/Elgg/commit/d5aea243b7655efda4924f8b5ff9fa7a6c2aebea))
  * make pagination in embed colorbox usable ([4aeafa70](https://github.com/Elgg/Elgg/commit/4aeafa704dce751f24fbeea0b12f7cac8a64891f))
  * make tabs in colorbox popup usable ([16ca1fd1](https://github.com/Elgg/Elgg/commit/16ca1fd15386284f1753a4c6ec2083847c9152d1))
* **entities:** reverts to 1.8 behavior of ElggEntity->subtype reads ([2fa7c6ce](https://github.com/Elgg/Elgg/commit/2fa7c6cefd5429cb2c4b554eb55670335a9d7eec), closes [#5920](https://github.com/Elgg/Elgg/issues/5920))
* **events:** makes the plugin_id parameter reliable for plugin events ([8b62fb8e](https://github.com/Elgg/Elgg/commit/8b62fb8e4569c863618166d31636d532962624d8))
* **gatekeeper:** stop treating being logged out as an error state ([03df35cd](https://github.com/Elgg/Elgg/commit/03df35cdfb722cc9cd7063feb7f137e2cf2ac5d7))
* **git:** Igorning revert commits in Travis and in commit-message git hook. ([36acbbf0](https://github.com/Elgg/Elgg/commit/36acbbf0e2765c0ee909846fab4297f2851091b0))
* **groups:**
  * Run discussion reply migration regardless if groups plugin is enabled. ([02023f45](https://github.com/Elgg/Elgg/commit/02023f45066e48d9330e2f3c74d4baaf2401627c), closes [#6729](https://github.com/Elgg/Elgg/issues/6729))
  * check if handler is set in params before calling it ([9e2bcb6d](https://github.com/Elgg/Elgg/commit/9e2bcb6d6d23c321ef9c3b8bf44071a31df42832))
  * forces content accessibility to members_only for invisible groups ([47a8c7ab](https://github.com/Elgg/Elgg/commit/47a8c7ab02bcc3115a1eb5312125513923039429))
  * fix the group acl id in write access array ([de2b6a68](https://github.com/Elgg/Elgg/commit/de2b6a6881140c71523ce381148d3c9fe4806f94))
  * displays correct group content access options for each content access mode ([b99f475a](https://github.com/Elgg/Elgg/commit/b99f475ab18d8d0fdd7f11b440935505bc95e5d8))
* **hooks:**
  * handlers returning null/undefined don't change returnvalue in javascript ([b1af0b6d](https://github.com/Elgg/Elgg/commit/b1af0b6df31b7a1a3b87a908ca7d3752ddc2b15f), closes [#6531](https://github.com/Elgg/Elgg/issues/6531))
  * return values defaults to null in javascript ([ff095943](https://github.com/Elgg/Elgg/commit/ff09594344ed24fd3867c73a9114d7cc7fe134da), closes [#6499](https://github.com/Elgg/Elgg/issues/6499))
  * remove event handler registration ([dbcf8b48](https://github.com/Elgg/Elgg/commit/dbcf8b484a4d96d062033ef158814555102dafbf), closes [#6410](https://github.com/Elgg/Elgg/issues/6410))
* **htmlawed:** Setting the params argument to a defaut of null in htmlawed_filter_tags(). ([d337ceee](https://github.com/Elgg/Elgg/commit/d337ceee75062e33d91a4a3a57e2917638aa60f2), closes [#6614](https://github.com/Elgg/Elgg/issues/6614))
* **http:** makes HTTP request/parameter bag PHP 5.2 compatible ([21719be9](https://github.com/Elgg/Elgg/commit/21719be93708b98b1744d1230eb0a1fcbd22ad1a))
* **install:**
  * assume port 443 means HTTPS is in use ([df76005c](https://github.com/Elgg/Elgg/commit/df76005c34e0f3014a89f7fdfce26c43f98927be), closes [#6190](https://github.com/Elgg/Elgg/issues/6190))
  * Put all themes at bottom of plugins list during installation. ([ec5458d1](https://github.com/Elgg/Elgg/commit/ec5458d1f68b5f8843bb4147bb1748788135579a), closes [#6530](https://github.com/Elgg/Elgg/issues/6530))
  * fatal exception during installation ([7993273d](https://github.com/Elgg/Elgg/commit/7993273dc82ee9da8c3a09912bb659dca297132e))
* **invitefriends:**
  * make invite form sticky ([74b1556c](https://github.com/Elgg/Elgg/commit/74b1556c630105a6bf800fbf865743e343af9e79))
  * check if registration is allowed before display menu item and pages ([c83630ce](https://github.com/Elgg/Elgg/commit/c83630ceee2fb4bb0093d98dbfa1638ccf687b16), closes [#6400](https://github.com/Elgg/Elgg/issues/6400))
* **js:** Separated elgg_load_js() and elgg_define_js(). ([a73838d9](https://github.com/Elgg/Elgg/commit/a73838d98bcefc2c16004933220965fc4011ce7a))
* **lightbox:**
  * setting fancybox's z-index for colorbox ([51231f46](https://github.com/Elgg/Elgg/commit/51231f468e657bb12449b9fa9de918b7055f96cb))
  * make lightbox scrollbar look better ([aeaafa6d](https://github.com/Elgg/Elgg/commit/aeaafa6d2700c4b7f7ba12c2f3734232317cd368))
  * CKEditor was weird in lightbox ([0e4e3dd1](https://github.com/Elgg/Elgg/commit/0e4e3dd1c95c205b2e66323ea82d54127b682f73))
  * Correctly applies color box options on each element ([b2950027](https://github.com/Elgg/Elgg/commit/b2950027a3dbaf87fadbe44ddd41de0bf175f8a5), closes [#6107](https://github.com/Elgg/Elgg/issues/6107))
  * lightbox wasn't shown when generated from ajax view. ([937c8d1e](https://github.com/Elgg/Elgg/commit/937c8d1eb63f11dfc185fe99fb32a637b18a65c8), closes [#6304](https://github.com/Elgg/Elgg/issues/6304))
* **memcache:** Better logging of save/hits/misses ([6448bb95](https://github.com/Elgg/Elgg/commit/6448bb95497db21923542a10983915023c1c2d32), closes [#6243](https://github.com/Elgg/Elgg/issues/6243))
* **pages:** memory leaks in large page trees ([ab6ef0df](https://github.com/Elgg/Elgg/commit/ab6ef0dff9e8797304dd3e01c967cfad27328484), closes [#6477](https://github.com/Elgg/Elgg/issues/6477))
* **plugins:** Stops junk log entries created on plugins page ([f76312fd](https://github.com/Elgg/Elgg/commit/f76312fd2043d5ea6ecd53b0d9ccaab2f7313055), closes [#6066](https://github.com/Elgg/Elgg/issues/6066))
* **rewrite_tester:** more reliably sniffs active rewrite rules ([3090bf08](https://github.com/Elgg/Elgg/commit/3090bf08c9395fe5e8267951fdf502af3de8a770), closes [#6656](https://github.com/Elgg/Elgg/issues/6656))
* **routing:**
  * prevent upgrade if .htaccess needs updating ([1fdbf2dc](https://github.com/Elgg/Elgg/commit/1fdbf2dc5c4829edd42cfbd23ab0128172fc1d93), closes [#6521](https://github.com/Elgg/Elgg/issues/6521))
  * URL-decodes path segments like Elgg 1.8 ([6de77faa](https://github.com/Elgg/Elgg/commit/6de77faaa76fa369de4ea453244a23206f47b781), closes [#6218](https://github.com/Elgg/Elgg/issues/6218))
* **session:**
  * session unavailable in shutdown functions ([3d6c33e4](https://github.com/Elgg/Elgg/commit/3d6c33e48867c0f4d84bee94f13744481071a5ac))
  * fixes remember me functionality broken in 1.8.19 merge ([659ea108](https://github.com/Elgg/Elgg/commit/659ea1085d26f617f73dc10f2f7f16bb368508f0))
* **site_notifications:** added correct key for menu item ([186e7174](https://github.com/Elgg/Elgg/commit/186e71749da8a3aeeb23eb045e9564872475106d))
* **tests:** Corrected the way the commit message tests are run. ([9e0183f4](https://github.com/Elgg/Elgg/commit/9e0183f4dc7fa925f623ae26a6e8ca7656092fda), closes [#6507](https://github.com/Elgg/Elgg/issues/6507))
* **thewire:**
  * Restores functionality of JS max length limit parametrization ([66e478f5](https://github.com/Elgg/Elgg/commit/66e478f56d059cf9b29f6264203ce947eae070b3), closes [#6646](https://github.com/Elgg/Elgg/issues/6646))
  * Fix word count JS events tapping ([d3e3a30b](https://github.com/Elgg/Elgg/commit/d3e3a30bce30a230255e0e45423419b6a66a89de))
* **ui:**
  * mispositioned editor when editing a comment that contains a floated image ([5f52eb75](https://github.com/Elgg/Elgg/commit/5f52eb75c532f420ed085b9d41e73bef6bc102ec), closes [#6576](https://github.com/Elgg/Elgg/issues/6576))
  * use correct logo in favicon ([1c98fdac](https://github.com/Elgg/Elgg/commit/1c98fdaca4b08ed3b38f7305dcc414c5d14276d9), closes [#6446](https://github.com/Elgg/Elgg/issues/6446))
  * Added CSS to make the site menu show up correctly in theme sandbox. ([e7ac3aeb](https://github.com/Elgg/Elgg/commit/e7ac3aeb500e12c54941ccfa5cb77d6cbf143d02))
* **upgrade:**
  * Corrects query to clear admin remember me cookies ([7ee022b6](https://github.com/Elgg/Elgg/commit/7ee022b6c15daa06ea0cda4b54c616158dd46082))
  * Adds an admin notice when a new ElggUpgrade object is created ([84959e75](https://github.com/Elgg/Elgg/commit/84959e75ff4e7e3aa52a56d9a91009afbf31db58))
  * Corrected the way ignore access and show hidden entities is applied ([ccec25ac](https://github.com/Elgg/Elgg/commit/ccec25ac07fd9f20ee02d7fdf1102ecebfb60038))
  * Added upgrade to deactivate TinyMCE and activate CKEditor. ([b6970f1c](https://github.com/Elgg/Elgg/commit/b6970f1cb93f09e8ce6a083f33949da4bfd19433), closes [#6653](https://github.com/Elgg/Elgg/issues/6653))
  * Fixed a typo in the comments upgrade that broke the ajax upgrade. ([fa0340ad](https://github.com/Elgg/Elgg/commit/fa0340ada24c53ca18a7b0d3c3c90ef90ba6419f))
  * Only running comment migration timestamp fix if comments exist. ([5901995d](https://github.com/Elgg/Elgg/commit/5901995ddfc7c111c4030cbdf14aea0b0bcf8284), closes [#6621](https://github.com/Elgg/Elgg/issues/6621))
  * Correctly settings container guids' last_action times during comment migration. ([9df2367c](https://github.com/Elgg/Elgg/commit/9df2367c792f31aaefbdaa2d99b28a0bff31319d), closes [#6528](https://github.com/Elgg/Elgg/issues/6528))
  * Setting time_updated and last_action for migrated comment and discussion entitie ([ed7cf3bc](https://github.com/Elgg/Elgg/commit/ed7cf3bcca5c899618dca2279962ebc3b43893ea), closes [#6395](https://github.com/Elgg/Elgg/issues/6395))
* **uservalidationbyemail:** do not show email sent page to logged in users. ([5534a576](https://github.com/Elgg/Elgg/commit/5534a57686460824400967ccb2e3fab11b4fa6c2), closes [#6649](https://github.com/Elgg/Elgg/issues/6649))
* **ux:** Server-side validation for email fields in profile edit action. ([7d70c6df](https://github.com/Elgg/Elgg/commit/7d70c6df6be3b1444da397de9e0f5afecb3e3d11))
* **vendors:** corrected version for requirejs ([22cf6d64](https://github.com/Elgg/Elgg/commit/22cf6d64bb687ff5899b38228441612f63d200ec), closes [#6735](https://github.com/Elgg/Elgg/issues/6735))
* **views:**
  * removes notices from views used in theme sandbox ([9141ecd1](https://github.com/Elgg/Elgg/commit/9141ecd12e8975ae5e90318c27e93022e52ab339))
  * fix typo in $attrs var name ([224a7729](https://github.com/Elgg/Elgg/commit/224a7729426b67b10db38eceb05678135b1176d6))
  * only pass body_attrs if they are set ([3749dda1](https://github.com/Elgg/Elgg/commit/3749dda1411437bc8029b1facfe5922059a247f1))
  * check if body_attrs are set before attempting to format them ([baf2df93](https://github.com/Elgg/Elgg/commit/baf2df9355a5fc63679ad1aa80f363d00a51572b), closes [#6298](https://github.com/Elgg/Elgg/issues/6298))
  * Using sitedescription in meta description tag. ([66f06919](https://github.com/Elgg/Elgg/commit/66f06919735e3de97b8262cc13c7044df755795b))
  * Correct default title for confirmlink ([dd1e83c3](https://github.com/Elgg/Elgg/commit/dd1e83c3da61f8fb0dd75152a899d8ca8e8ce7a6), closes [#6375](https://github.com/Elgg/Elgg/issues/6375))



## Performance
* Using dataroot and simplecache_enabled if set in settings.php
* Changes simplecache caching so that it is performed on demand
* Adds support for simplecache minification of CSS and JavaScript
* Adds ability to enable the query cache after being disabled
* Don't call getter after a previous count call returned 0 items
* Make sure Apache2 is configured so .ico can be cached
* Adds deflate Apache filter to SVG images
* Log display no longer emit deprecation warnings and uses fewer queries
* speeds up user location upgrade
* Progress toward HHVM compatibility

## UI changes
* Lots of spit and polish
* New responsive theme - aalborg_theme
* Drops support for IE6
* Replaces fancybox lightbox with colorbox
* Replaces Tinymce editor with CKEditor
* Liking and friending use ajax
* Removes topbar Elgg logo and made "powered by" themable
* Allows keeping group content limited to the group
* Site notifications moved into separate plugin from messages
* Shows owner block when viewing own content
* Focus styles for accessible keyboard navigation
* Improved theme sandbox
* Session expired message
* Ajaxified the discussion reply edit form.
* Alphabetize friends/friends-of, group notifications, group owned/member lists
* Added support for greying out the label of disabled input
* Added more microformats to the profile page
* Automatically configure autocorrect and autocapitalize for input views
* Using unified language strings for several plugins
* Adds focus outlines to all focusable elements

## Admin changes
* Adds new notification system
* Makes the wire message length configurable
* Changes user directories use GUIDs rather than join date
* Adds banned user widget
* Adds legacy_url plugin for supporting legacy URLs
* Adds robots.txt configuration
* Adds maintenance mode
* Added automatic configuration of RewriteBase during fresh install.

## New developer features
* HTML5
* New mysql-based async queue
* AMD modules using require.js
* New notification system
* New class loader that is PSR-0 compliant
* Improves control over cookies
* Adds plugin manifest fields (id, php_version, contributors)
* Static files recognized as views
* Adds support for multi-select
* JSON rendered through views system rather than using global
* Links in login box use menu system
* Upgrades jQuery and includes the jquery migrate plugin
* Widgets can set their titles
* New JavaScript unit test library
* Front page and actions go through page handling system
* Group edit form easier to extend
* More specific list item classes
* Page layouts more standardized with same elements
* Allows customizing colorbox instances
* Views system recognizes static files as views in addition to PHP files
* Adds ability to turn off query cache
* Can change time_created if set explicitly
* Allows update event to alter attributes and checks canEdit() on DB copy
* add more specific list item classes
* moved elgg_view_icon html to own view for more flexibility
* Allow body attributes
* Eases extending the input/view view
* Split group edit form into seperate parts
* Moved group_activity widget from dashboard to groups plugin
* Adds warnings for uncallable handlers in hooks/events.
* Members list pages (tabs/content/titles) can now be extended via plugins
* Adds configuration support for remember me cookie

## API changes
* Comments and discussion replies are entities
* New notification system
* Changes elgg_register_widget_type() to expect contexts to be an array
* New session API accessible via elgg_get_session()
* Moves many functions into methods on ElggEntity and related classes
* Adds support for returning translations as arrays from language files
* Adds ElggEntity::getDisplayName()
* Adds ElggEntity::toObject()
* Adds target_guid to the river
* Adds elgg_get_entities_from_attributes()
* Adds ElggMenuItem::addItemClass()
* Adds elgg_get_menu_item()
* Adds elgg_format_element() for creating HTML elements
* ElggFile::getSize() replaces ElggFile::size()
* Defaults to full_view = false in elgg_list_entities* functions
* Allows views to be accessed via URL and cacheable
* Columns added to entity query functions are available in returned entities
* Separates some events into :before/:after
* Adds elgg_entity_gatekeeper()
* get_online_users() and find_active_users() now use $options arrays
* Adds default option to elgg_get_plugin_setting
* namespaced the gatekeeper functions (but made it optional)
* Added URL fragment (#anchors) support to elgg_http_build_url
* made elgg_unregister_menu_item() more useful

## New hooks/events
* plugin hook: simple_type, file
* plugin hook: default, access
* plugin hook: login:forward, user
* plugin hook: layout, page
* plugin hook: shell, page
* plugin hook: head, page
* plugin hook: get_sql, access
* plugin hook: maintenance:allow, url
* notifications plugin hooks
* event: init:cookie, name

## Deprecated functionality
* calendar library (was not maintained)
* web services library (now plugin distributed with Elgg)
* export, import, and opendd libraries (see ElggEntity:toObject())
* location library
* xml library
* Split logout event to before/after events
* Split login event to before/after events
* Added a deprecate notice to the elgg_view_icon use of a boolean
* Deprecated get_annotation_url() in favor of ElggAnnotation::getURL()
* Deprecated full_url() in favor of current_page_url()
* Deprecated "class" in ElggMenuItem::factory in favor of "link_class"
* Deprecated passing null to ElggRelationship constructor
* Deprecated .elgg-autofocus in favor of HTML5 autofocus
* Deprecated ElggUser::countObjects (part of Friendable interface)
* Deprecated favicon view in favor of head, page plugin hook
* Deprecated analytics view in favor of page/elements/foot
* Deprecated availability of $vars keys (url, config) and $CONFIG
* Deprecated ElggEntity::get()/set() in favor of property access
* Deprecated cron, reboot event
* Deprecated add_to_river() in favor of elgg_create_river_item()
* Renames many functions to begin with "elgg_" (with deprecated versions)

## Removed functionality
* xml-rpc library (now plugin: https://github.com/Elgg/xml-rpc)
* xml, php, and ical views (now plugin: https://github.com/Elgg/data_views)
* foaf views (now plugin: https://github.com/Elgg/semantic_web)
* Default entity page handler

## Documentation
* Shiny new rST docs (hosted at http://learn.elgg.org)
* Various improvements to source code comments

## Security Enhancements
* Using SSL for setting password when https login enabled
* Make several views files non-executable

## Bugfixes
* HTMLawed Strips html comments and CDATA from input
* Hundreds of miscellaneous fixes
* users can edit metadata that they created by default
* removes special check to allow access override
* if no container, default to false for writing to container
* fixes default user access
* returning false to create events forces delete regardless of access
* Fix json and xml views broken by wrap view of developer tools
* Do not use link with file icon when using full_view.
* made page shells consistent for $vars parameters
* show owner block also if looking at owned pages
* Pagination uses HTTP referrer as default base_url for Ajax requests
* Added several missing translation strings
* standardizes layouts so that they all have title buttons and the same basic sections
* entity list limit respects passed limits and just provides defaults
* fixes setting page owner due to routing change
* Fixed batch install usage of createHtaccess
* fixed typo that prevented context for front page from being set
* Make sure empty string return is interpreted as "handling" the list hook
* replaced double search box with a single box and a single searchhook
* Login, user event code can use elgg_get_logged_in_user_*()
* Make sure user has access to both river object and target
* Uses correct default value for find_active_users 'seconds' parameter
* Added jquery map file and unminified version to make Chrome dev tools happy and not throw 404 error
* Corrects container write permissions bug
* Sends correct Content-Length with profile icon
* Getting correct client IP behind proxy.
* Fixed old function name for batch metastring operations
* allow full access to the metadata API through setMetadata() rather than requiring use of create_metadata()
* catching when the base entity is not created due to permissions override
* message if no results found
* all link should reset entity type/subtype
* forces lastcache to be an int
* Many more miscellaneous improvements...


# v1.8.19 (March 12, 2014)

## Contributing Developers
* Brett Profitt
* Centillien
* Evan Winslow
* Ismayil Khayredinov
* Jerome Bakker
* Juho Jaakkola
* Matt Beckett
* RiverVanRain
* Sem
* Steve Clay
* pattyland

## Security enhancements
* Implements stronger remember me cookie strategy to prevent brute force attacks.

## Bugfixes
* Fixed numerous PHP warnings.
* Groups: Corrected breadcrumb for group discussion pages.
* Fixed RSS validation for the River RSS feed.

## Improvements
* Moved Site Secret update to configure -> advanced.
* Added more comprehensive tests for HTMLAwed.

## Documentation
* Added better deprecation warnings for use of certain attributes in views.


# v1.8.18 (January 11, 2014)

## Contributing Developers
* Juho Jaakkola
* Steve Clay

## Bugfixes
* Fixes notify_user() broken in 1.8.17


# v1.8.17 (January 1, 2014)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Ed Lyons
* Evan Winslow
* Jeroen Dalsem
* Jerome Bakker
* Juho Jaakkola
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Security Fixes
* Specially-crafted request could return the contents of sensitive files.
* Reflected XSS attack was possible against 1.8 systems.
* The cryptographic key used for various purposes may have been generated with weak entropy, particularly on Windows.

## Bugfixes
* URLs with non-ASCII usernames again work
* Floated images are now properly cleared in content areas
* The activity page title now matches the document title
* Search again supports multiple comments on the same entity
* Blog archive sidebar now reverse chronological
* URLs with matching parens can now be auto-linked
* Log browser links for users now work
* Disabling over 50 objects should no longer result in an infinite loop
* Radio/checkbox inputs no longer have border radius (for IE10)
* User picker: the Only Friends checkbox again works
* Group bookmarklet no longer shown to non-members
* Widget reordering fixed when moving across columns
* Refuse to deactivate plugins needed as dependencies

## Enhancements
* Group member listings are ordered by name
* The system_log table can now store IPv6 addresses
* Web services auth_gettoken() now accepts email address
* List functions: no need to specify pagination for unlimited queries
* Htmlawed was upgraded to 1.1.16


# v1.8.16 (June 25, 2013)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Jeff Tilson
* Jerome Bakker
* Paweł Sroka
* Steve Clay

## Security Fixes
* Fixed avatar removal bug (thanks to Jerome Bakker for the first report of this)

## Bugfixes
* Fixed infinite loop when deleting/disabling an entity with > 50 annotations
* Fixed deleting log tables in log rotate plugin
* Added full text index for groups if missing
* Added workaround for IE8 and jumping user avatar
* Fixed pagination for members pages
* Fixed several internal cache issues
* Plus many more bug fixes


# v1.8.15 (April 23, 2013)

## Contributing Developers
* Cash Costello
* Ismayil Khayredinov
* Jeff Tilson
* Juho Jaakkola
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay
* Tom Voorneveld

## Bugfixes
* Not displaying http:// on profiles when website isn't set
* Fixed pagination display issue for small screens
* Not hiding subpages of top level pages that have been deleted
* Stop corrupting JavaScript views with elgg deprecation messages
* Fixed out of memory error due to query cache
* Fixed bug preventing users authorizing Twitter account access
* Fixed friends access level for editing pages
* Fixed uploading files within the embed dialog

## Enhancements
* Added browser caching of language JS files
* Adding nofollow on user posted URLs for spam deterrence (thanks to Hellekin)
* Auto-registering views for simplecache when their URL is requested
* Display helpful message for those who have site URL configuration issues
* Can revert to a previous revision with pages plugin
* Site owners can turn off posting wire messages to Twitter
* Search results are sorted by relevance

## Removed Functionality
* Twitter widget due to changes in Twitter API and terms of service
* OAuth API plugin due to conflicts with the Twitter API plugin


# v1.8.14 (March 12, 2013)

## Contributing Developers
* Aday Talavera
* Brett Profitt
* Cash Costello
* Ed Lyons
* German Bortoli
* Hellekin Wolf
* iionly
* Jerome Bakker
* Luciano Lima
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Security Fixes
* Fixed a XSS vulnerability when accepting URLs on user profiles
* Fixed bug that exposed subject lines of messages in inbox
* Added requirement for CSRF token for login

## Bugfixes
* Strip html tags from tag input
* Fixed several display issues for IE7
* Fixed several issues with blog drafts
* Fixed repeated token timeout errors
* Fixed JavaScript localization for non-English languages

## Enhancements
* Web services fall back to json if the viewtype is invalid


# v1.8.13 (January 29, 2013)

## Contributing Developers
* Cash Costello
* Juho Jaakkola
* Kevin Jardine
* Krzysztof Różalski
* Steve Clay

## Security Fixes
* Added validation of Twitter usernames in Twitter widget

## Bugfixes
* CLI usages with walled garden fixed
* Upgrading from < 1.8 to 1.8 fixed
* Default widgets fixed
* Quotes in object titles no longer result in "qout" in URLs
* List of my groups is ordered now
* Language string river:comment:object:default is defined now
* Added language string for comments: generic_comment:on

## Enhancements
* Added confirm dialog for resetting profile fields (adds language string profile:resetdefault:confirm)


# v1.8.12 (January 4th, 2013)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Jerome Bakker
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Bugfixes
* Added an AJAX workaround for the rewrite test.
* Code cleanup to prevent some notices and warnings.
* Removed "original_order" in menu item anchor tags.
* Site menu's selected item correctly persists through content pages.
* Static caches rewritten and improved to prevent stale data being returned.
* Installation: Invalid characters in admin username are handled correctly.
* Messages: Fixed inbox link in email notifications.
* The Wire: Fixed objects not displaying correctly when upgrading from 1.7.

## Enhancements
* Performance improvements and improved caching in entity loading.
* Added upgrade locking to prevent concurrent upgrade attempts.
* Replaced xml_to_object() and autop() with GPL / MIT-compatible code.
* Error messages (register_error()) only fade after being clicked.
* Groups: Added a sidebar entry to display membership status and a link to
 group notification settings.
* Groups: Added pending membership and invitation requests to the sidebar.
* Groups: Better redirection for invisible and closed groups.
* Search: User profile fields are searched.
* Pages: Subpages can be reassigned to new parent pages.
* Twitter: Login with twitter supports persistent login and correctly forwards
 after login.


# v1.8.11 (December 5th, 2012)

## Bugfixes
* Fixed fatal error in group creation form


# v1.8.10 (December 4th, 2012)

## Contributing Developers
* Krzysztof Różalski
* Lars Hærvig
* Paweł Sroka
* RiverVanRain
* Sem
* Steve Clay

## Security Enhancements
* Cached metadata respects access restrictions to fix problems with profile
 field display.
* Group RSS feeds are restricted to valid entities

## Enhancements
* UX: Added a list of Administrators in the admin area
* UX: Limiting message board activity stream entries to excerpts
* Performance: Prefetching river entries
* Performance: Plugin entities are cached

## Bugfixes
* Removed superfluous commas in JS files to fix IE compatibility.
* API: Fixed Twitter API.
* Performance: Outputting valid ETags and expires headers.


# v1.8.9 (November 11, 2012)

## Contributing Developers
* Brett Profitt
* Cash Costello
* Evan Winslow
* Jeroen Dalsem
* Jerome Bakker
* Matt Beckett
* Paweł Sroka
* Sem
* Steve Clay

## Security Enhancements
* Sample CLI installer cannot break site
* Removed XSS vulnerabilities in titles and user profiles

## Enhancements
* UX: A group's owner can transfer ownership to another member
* UX: Search queries persist in the search box
* Several (X)HTML validation improvements
* Improved performance via more aggressive entity and metadata caching
* BC: 1.7 group profile URLs forward correctly

## Bugfixes
* UX: Titles containing HTML tokens are never mangled
* UX: Empty user profile values saved properly
* UX: Blog creator always mentioned in activity stream (not user who published it)
* UI: Fixed ordering of registered menu items in some cases
* UI: Embed dialog does not break file inputs
* UI: Datepicker now respects language
* UI: More reliable display of access input in widgets
* UI: Group edit form is sticky
* UI: Site categories are sticky in forms
* API: Language fallback works in Javascript
* API: Fallback to default viewtype if invalid one given
* API: Notices reported for missing language keys
* Memcache now safe to use; never bypasses access control
* BC: upgrade shows comments consistently in activity stream


# v1.8.8 (July 11, 2012)

## Contributing Developers
* Cash Costello
* Miguel Rodriguez
* Sem

## Enhancements
* Added a delete button on river items for admins

## Bugfixes
* Fixed the significant bug with htmlawed plugin that caused duplicate tags


# v1.8.7 (July 10, 2012)

## Contributing Developers
* Cash Costello
* Evan Winslow
* Ismayil Khayredinov
* Jeroen Dalsem
* Jerome Bakker
* Matt Beckett
* Miguel Rodriguez
* Paweł Sroka
* Sem
* Steve Clay

## Enhancements
* Better support for search engine friendly URLs
* Upgraded htmlawed (XSS filtering)
* Internationalization support for TinyMCE
* Public access not available for walled gardens
* Better forwarding and messages when they cannot view content because logged out

## Bugfixes
* Fatal errors due to type hints downgraded to warnings
* Group discussion reply notifications work again
* Sending user to inbox when deleting a message
* Fixed location profile information when it is an array
* Over 30 other bug fixes.


# v1.8.6 (June 18, 2012)

## Contributing Developers
* Cash Costello
* Evan Winslow
* Ismayil Khayredinov
* Jeff Tilson
* Jerome Bakker
* Paweł Sroka
* Sem
* Steve Clay

## Enhancements
* New ajax spinner
* Detecting docx, xlsx, and pptx files in file plugin
* Showing ajax spinner when uploading file with embed plugin

## Bugfixes
* Fixed some language caching issues.
* Users can add sub-pages to another user's page in a group.
* Over 30 other bug fixes.


# v1.8.5 (May 17, 2012)

## Contributing Developers
* Brett Profitt
* Evan Winslow
* Sem
* Steve Clay
* Jeroen Dalsem
* Jerome Bakker

## Security Enhancements
* Fixed possible XSS vulnerability if using a crafted URL.
* Fixed exploit to bypass new user validation if using a crafted form.
* Fixed incorrect caching of access lists that could allow plugins
to show private entities to non-admin and non-owning users. (Non-exploitable)

## Bugfixes
* Twitter API: New users are forwarded to the correct page after creating
             an account with Twitter.
* Files: PDF files are downloaded as "inline" to display in the browser.
* Fixed possible duplication errors when writing metadata with multiple values.
* Fixed possible upgrade issue if using a plugin uses the system_log hooks.
* Fixed problems when enabling more than 50 metadata or annotations.

## API
* River entries' timestamps use elgg_view_friendly_time() and can be
 overridden with the friendly time output view.


# v1.8.4 (April 24, 2012)

## Contributing Developers
* Adayth Talavera
* Brett Profitt
* Cash Costello
* Evan Winslow
* Ismayil Khayredinov
* Janek Lasocki-Biczysko
* Jerome Baker
* Sem
* Steve Clay
* Webgalli

## Security Enhancements
* Fixed an issue in the web services auth.get_token endpoint that
would give valid auth tokens to invalid credentials. Thanks to
Christian for reporting this!
* Fixed an that could show which plugins are loaded on a site.

## Enhancements
* UI: All bundled plugins' list pages display a no content message if there is nothing to list.
* UI: Site default access is limited to core access levels.
* UI: Showing a system message to the admin if plugins are disabled with the "disabled"
magic file.
* UI: Added transparent backgrounds for files and pages icons.
* External (Site) Pages: If in Wall Garden mode, Site Pages use the Walled Garden
theme when logged out.
* UI: Database errors only show the query to admin users.
* UI: Cannot set the data path to a relative path in installation or site settings.
* UI: Cleaned up notifications for bundled plugins.
* UI: Hiding crop button if no avatar is uploaded.
* UI: Bundled plugins are displayed with a gold border in the plugin admin area.
* UI: Can see all the categories a plugin belongs to.
* Web Services: Multiple tokens allowed for users.
* API: More efficient entity loading.
* API: Added IP address to system log.
* API: Languages are cached.
* API: ElggBatch supports disabling offsets for callbacks that delete entities.
* API: Cleaned up the boot process.
* API: Fixed situation in which the cache isn't properly cleared if a file can't be unlinked.

## Bugfixes
* UI: Tags display in the case they were saved.
* UI: Friendly titles keep -s.
* UI: Removed pagination in friends widget.
* UI: Profile settings actions correctly displays error messages as errors.
* UI: Tag search works for tags with spaces.
* UI: Fixed river display for friending that happens during registration.
* Groups: Link for managing join requests is restored in the sidebar.
* Walled Garden: Cron and web services endpoints are exposed as public sites.
* The Wire: UTF usernames are correctly linked with @ syntax.
* The Wire: No longer selecting the "Mine" tab for users who aren't you.
* Blogs: Notifications restored.
* Message Board: Fixed delete.
* Groups: Forwarding to correct page if trying to access closed group.
* API: entities loaded via elgg_get_entities_from_relationship() have the correct time_created.
* API: Deleting entities recursively works when code is logged out.
* API: Fixed multiple uses of deprecated functions.


# v1.8.3 (January 12, 2012)

## Enhancements
* Adds a white list for ajax views
* Improved navigation tab options
* Added group specific search
* Added button for reverting avatar
* Improved documentation for core class attributes
* Adds a server info page under administer -> statistics
* Improving caching of icons and js/css
* Deprecation notices not displayed to non-admin users

## Bugfixes
* Fixed upgrade scripts for blog posts and groups forum posts
* Can now delete invitations to invisible groups
* Fixed several widget bugs
* Fixed access level on add to group river item
* Fixed recursive entity enabling
* Fixed limit on pages in sidebar navigation
* Fixed deletion of large numbers of annotations


# v1.8.2 (December 21, 2011)

## Enhancements
* Added a 404 page
* Widgets controls now using the menu system
* Admins can edit users' account information
* Embed uploader supports uploading into groups
* Add a control panel widget for easy access to cache flushing and upgrading
* Comments now have a unqiue URL with fragment identifier
* JavaScript language files are cacheable
* jQuery form script only loaded when required

## Bugfixes
* Fixed default widgets
* Fixed activity filtering
* Embedding an image now inserts a medium sized image
* Search plugin only uses mbstring extension if enabled
* Site pages links returned to footer
* Fixed URL creation for users with non-ASCII characters in username
* The wire username parsing supports periods in usernames
* Returned the posting area to the main wire page
* Fixed layout issue on pages with a fragment identifier in URL
* Added support for call elgg_load_js() in header and footer
* Fixed user picker
* Fixed uservalidationbyemail plugin ignoring the actions of other plugins
* Fixed bug preventing the creation of admin users
* Fixed deleting a widget with JavaScript disabled
* Fixed many bugs in the unit/integration tests


# v1.8.1 (November 16, 2011)

## Enhancements
* Completed styling of user validation admin page
* Adding rel=nofollow for non-trusted links
* Added direct icon loading for profile avatars in profile plugin
* Improved the structure of content views to make styling easier
* Updated version of jQuery to 1.6.4
* Added basic support for icon size customization
* Added a toggle for gallery/list view in file plugin
* Added support for passing CSS classes to icon views
* Added support for non http URLs to Elgg's normalize functions
* Added better support for the 404 forward if a page handler does handle a request

## Bugfixes
* Fixed autocomplete and userpicker
* Fixed RSS and web service-related view types
* Fixed walled garden display issues
* Added work around for IE/TinyMCE/embed insert problem
* Implemented ElggUser.isAdmin() JavaScript method
* Fixed the date views and JavaScript datepicker
* Fixed horizontal radio buttons styling
* Modules only display header if there is content for it


# v1.8.1b (October 11, 2011)

## Enhancements
* New group activity widget for user dashboard.
* Added more sprites.
* version.php information cached instead of loaded 100s of times.
* Added class elgg-autofocus to add focus on inputs when the page loads.
* Admins can edit user avatars again.
* Added a filter for non-bundled plugins in plugin admin.
* Improvements to admin area theme.

## Bugfixes
* Fixed site dropdown menu for IE.
* ElggEntity->deleteMetadata() no longer deletes all metadata ever if
called on an unsaved entity.
* Fixed Embed plugin.
* Fixed activate and deactivate all plugins.
* Fixed URL for group membership request in notification email.
* Fixed log browser plugin's admin area display.
* Fixed RSS icon not showing up on some pages.
* Fixed river entries for forum posts that were lost if upgrading from 1.7.
* Better displaying of errors when activating, deactivating, or
reordering plugins.
* Fixed Developer Plugin's inspection tool.
* Fixed avatar cropping on IE 7.
* Bookmarks plugin accepts URLs with dashes.
* "More" menu item on site menu hidden if items are manually specified.
* Fixed hover menu floating if unrestrained.
* JS init, system fired when DOM and languages are read.
* Fixed the date picker input view.
* Fixed stack overflow when calling elgg_view() from a pagesetup
event.
* Menu links no longer have empty titles and confirm attributes.
* Fixed crash when attempting to change password to an invalid value.
* Fixed "More groups" link for groups widget.
* Fixed output/confirmlink to use a default question if not specified.
* Added missing language strings. Also added "new", "add", and "create".
* Registered security token refresh page as external to avoid token refresh
problems on Walled Garden sites.
* Displaying more accurate message if uploading an avatar fails.
* "Leave group" button doesn't display for group owners.
* Request group membership button displays only when logged in.
* Fixed the number of displayed items for Bookmarks widget.
* Fixed fallback to deprecated views for widgets.

## API Changes
* Menus names must be unique to the entire menu, not just section.
* Input views that encode text use the option 'encode_text'.
* Added ElggPlugin->getFriendlyName().
* elgg_view_icon() accepts a class.
* Added hook output:before, page.
* Added hook output:before, layout.
* elgg_get_entities() and related functions return false if passed
valid options with invalid values.
* Can disable the user hover menu by passing hover => false to
elgg_view_icon(). Previously it was override => true.
* Embed plugin uses menu system. See readme for embed plugin.
* Manifest attributes are no longer translated via elgg_echo().
* Fixed livesearch ajax endpoint.
* Fixed site unit test.
* Unit tests tidy up after themselves better.
* forward() throws an exception if headers are already sent.
* Better errors if adding a user through admin area fails.
* Localized profile fields.
* Added 'is_trusted' parameter output/url to avoid escaping and filtering.
Defaults to false.
* Added elgg_unregister_action()
* Fixed ElggPriorityList::rewind().
* Fixed forwarding after login for login-protected pages.
* get_site_by_url() respects class inheritance for subclassing ElggSite.

## Internal changes
* Updated deprecated uses of internalname/id.
* Using wwwroot instead of www_root because of inconsistencies.


# v1.8.0 (Jackie) (September 5th, 2011)

## Notes
Elgg 1.8 contains the most changes in Elgg since the transition from Elgg
0.9 to Elgg 1.0. The core team tried to make the transition as smooth as
possible, but in the interest of following standards and simplifying the
development process for core and third party developers, we have made
changes that will require updating plugins. We believe these changes
will help Elgg development be easier for everyone.

It is unreasonable and unhelpful to list the full details of all changes in
this file. Instead, we will list the high level, overarching changes to
systems. If you are interested in the specifics, Elgg 1.8's source code is
highly documented and serves as a good guide and the git commit log can
provide excruciating details of what has changed between 1.7 and 1.8.

Please post your feedback, questions, and comments to the community site
at http://community.elgg.org. As always, thank you for using Elgg!

--The Elgg Core Development Team

A tip about updating plugins:

It's not difficult to update 1.7 plugins for 1.8. There is a detailed
document outlining this process on the wiki:
http://learn.elgg.org/en/stable/guides/upgrading.html#from-1-7-to-1-8

The basic process is:

1. Clean up the plugin to make sure it conforms to coding standards,
 official structure, and best practices.
2. Update any uses of deprecated functions. Functions deprecated in 1.7 will
 produce visible notices in 1.8!
3. Use the new manifest format.
4. Use the new menu functions.
5. Use the new JS features.
6. Update the views to use core CSS helper functions and classes instead of
 writing your own.

The documentation directory and the wiki has more information.

## User-visible changes
* New default theme.
* New installation.
* Separate and updated admin interface.
* Updated plugin themes.

## Generic API changes
* Improved the markup and CSS.
* Restructured and simplified the views layouts.
* Added a new menu system.
* Added new CSS and JS file registration functions.
* Added a JS engine.
* Added a breadcrumb system.
* Added a sticky forms system.

## New plugins
* Dashboard - The activity stream is now the default index page. A 1.7-style
dashboard is provided through the dashboard plugin.
* Developers Plugins - Developer tools.
* Likes - Allows users to "like" other users' content.
* oAuth API - A generic, reusable oAuth library.
* Tag Cloud - A widget-based tag cloud generator.
* Twitter API - A generic Twitter library that allows signin with Twitter
and pushing content to tweets. Replaces twitter_service.

## Deprecated plugins
* captcha - Captchas have long since stopped being useful as a deterrent
against spam.
* crontrigger - Real cron should be used.
* default_widgets - This functionality is now part of core.
* friends - This functionality is now part of core.
* riverdashboard - Displaying the river (activity stream) is default in
core. The original dashboard can be restored by the new Dashboard plugin.
* twitter_service - Replaced by Twitter API.

Elgg 1.8.0.1 was released immediately after 1.8.0 to correct a problem in
installation.

