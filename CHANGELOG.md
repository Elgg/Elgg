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

