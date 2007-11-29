CREATE TABLE prefix_content_flags (
  ident SERIAL PRIMARY KEY,
  url varchar(128) NOT NULL default ''
) ;

CREATE INDEX prefix_content_flags_url_idx ON prefix_content_flags (url);


CREATE TABLE prefix_file_folders (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  files_owner integer NOT NULL default '0',
  parent integer NOT NULL default '0',
  name varchar(128) NOT NULL default '',
  access varchar(20) NOT NULL default 'PUBLIC',
  handler varchar(32) NOT NULL default 'elgg'
) ;

CREATE INDEX prefix_file_folders_files_owner_idx ON prefix_file_folders (files_owner);
CREATE INDEX prefix_file_folders_owner_idx ON prefix_file_folders (owner);
CREATE INDEX prefix_file_folders_access_idx ON prefix_file_folders (access);
CREATE INDEX prefix_file_folders_name_idx ON prefix_file_folders (name);


CREATE TABLE prefix_file_metadata (
  ident SERIAL PRIMARY KEY,
  name varchar(255) NOT NULL default '',
  value text NOT NULL,
  file_id integer NOT NULL default '0'
) ;

CREATE INDEX prefix_file_metadata_name_idx ON prefix_file_metadata (name,file_id);


CREATE TABLE prefix_files (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  files_owner integer NOT NULL default '0',
  folder integer NOT NULL default '-1',
  community integer NOT NULL default '-1',
  title varchar(255) NOT NULL default '',
  originalname varchar(255) NOT NULL default '',
  description varchar(255) NOT NULL default '',
  location varchar(255) NOT NULL default '',
  access varchar(20) NOT NULL default 'PUBLIC',
  size integer NOT NULL default '0',
  time_uploaded integer NOT NULL default '0',
  handler varchar(32) NOT NULL default 'elgg'
) ;

CREATE INDEX prefix_files_owner_idx ON prefix_files (owner,folder,access);
CREATE INDEX prefix_files_size_idx ON prefix_files (size);
CREATE INDEX prefix_files_time_uploaded_idx ON prefix_files (time_uploaded);
CREATE INDEX prefix_files_originalname_idx ON prefix_files (originalname);
CREATE INDEX prefix_files_community_idx ON prefix_files (community);
CREATE INDEX prefix_files_files_owner_idx ON prefix_files (files_owner);


CREATE TABLE prefix_friends (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  friend integer NOT NULL default '0',
  status varchar(4) NOT NULL default 'perm'
) ;

CREATE INDEX prefix_friends_owner_idx ON prefix_friends (owner);
CREATE INDEX prefix_friends_friend_idx ON prefix_friends (friend);
CREATE INDEX prefix_friends_status_idx ON prefix_friends (status);


CREATE TABLE prefix_friends_requests (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default 0,
  friend integer NOT NULL default 0
) ;

CREATE INDEX prefix_friends_requests_owner_idx ON prefix_friends_requests (owner);

CREATE TABLE prefix_group_membership (
  ident SERIAL PRIMARY KEY,
  user_id integer NOT NULL default '0',
  group_id integer NOT NULL default '0'
) ;

CREATE INDEX prefix_group_membership_user_id_idx ON prefix_group_membership (user_id,group_id);


CREATE TABLE prefix_groups (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  name varchar(128) NOT NULL default '',
  access varchar(20) NOT NULL default 'PUBLIC'
) ;

CREATE INDEX prefix_groups_owner_idx ON prefix_groups (owner,name);
CREATE INDEX prefix_groups_access_idx ON prefix_groups (access);


CREATE TABLE prefix_icons (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  filename varchar(128) NOT NULL default '',
  description varchar(255) NOT NULL default ''
) ;

CREATE INDEX prefix_icons_owner_idx ON prefix_icons (owner);


CREATE TABLE prefix_invitations (
  ident SERIAL PRIMARY KEY,
  name varchar(128) NOT NULL default '',
  email varchar(128) NOT NULL default '',
  code varchar(128) NOT NULL default '',
  owner integer NOT NULL default '0',
  added integer NOT NULL default '0'
) ;

CREATE INDEX prefix_invitations_code_idx ON prefix_invitations (code);
CREATE INDEX prefix_invitations_email_idx ON prefix_invitations (email);
CREATE INDEX prefix_invitations_added_idx ON prefix_invitations (added);


CREATE TABLE prefix_password_requests (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  code varchar(128) NOT NULL default ''
) ;

CREATE INDEX prefix_password_requests_owner_idx ON prefix_password_requests (owner,code);


CREATE TABLE prefix_profile_data (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  access varchar(20) NOT NULL default 'PUBLIC',
  name varchar(255) NOT NULL default '',
  value text NOT NULL
) ;

CREATE INDEX prefix_profile_data_owner_idx ON prefix_profile_data (owner,access,name);


CREATE TABLE prefix_tags (
  ident SERIAL PRIMARY KEY,
  tag varchar(128) NOT NULL default '',
  tagtype varchar(20) NOT NULL default '',
  ref integer NOT NULL default '0',
  access varchar(20) NOT NULL default 'PUBLIC',
  owner integer NOT NULL default '0'
) ;

CREATE INDEX prefix_tags_owner_idx ON prefix_tags (owner);
CREATE INDEX prefix_tags_tagtype_ref_idx ON prefix_tags (tagtype,ref);
CREATE INDEX prefix_tags_tag_idx ON prefix_tags (tag);
CREATE INDEX prefix_tags_access_idx ON prefix_tags (access);

CREATE TABLE prefix_template_elements (
  ident SERIAL PRIMARY KEY,
  name varchar(128) NOT NULL default '',
  content text NOT NULL,
  template_id integer NOT NULL default '0'
) ;

CREATE INDEX prefix_template_elements_name_idx ON prefix_template_elements (name,template_id);

CREATE TABLE prefix_templates (
  ident SERIAL PRIMARY KEY,
  name varchar(128) NOT NULL default '',
  owner integer NOT NULL default '0',
  public varchar(3) CHECK (public IN ('yes','no')) NOT NULL default 'yes',
  shortname varchar(128) NOT NULL
) ;

CREATE INDEX prefix_templates_name_idx ON prefix_templates (name,owner,public);

-- because this is postgres, we need to reset the sequence since we're hard coding the ids
-- that were used in the template_elements table. yuk.
SELECT setval('prefix_templates_ident_seq', (select max(ident) from prefix_templates));

CREATE TABLE prefix_user_flags (
  ident SERIAL PRIMARY KEY,
  user_id integer NOT NULL default '0',
  flag varchar(64) NOT NULL default '',
  value varchar(64) NOT NULL default ''
) ;

CREATE INDEX prefix_user_flags_user_id_idx ON prefix_user_flags (user_id,flag,value);
INSERT INTO prefix_user_flags (user_id,flag,value) VALUES (1,'admin','1');


CREATE TABLE prefix_users (
  ident SERIAL PRIMARY KEY,
  username varchar(128) NOT NULL default '',
  password varchar(32) NOT NULL default '',
  email varchar(128) NOT NULL default '',
  name varchar(128) NOT NULL default '',
  icon integer NOT NULL default '-1',
  active varchar(3) CHECK (active IN ('yes','no')) NOT NULL default 'yes',
  alias varchar(128) NOT NULL default '',
  code varchar(32) NOT NULL default '',
  icon_quota integer NOT NULL default '10',
  file_quota integer NOT NULL default '1000000000',
  template_id integer NOT NULL default '-1',
  owner integer NOT NULL default '-1',
  user_type varchar(128) NOT NULL default 'person',
  moderation varchar(4) NOT NULL default 'no',
  last_action integer NOT NULL default '0',
  template_name varchar(128) NOT NULL default 'Default_Template'
) ;

CREATE INDEX prefix_users_username_idx ON prefix_users (username,password,name,active);
CREATE INDEX prefix_users_code_idx ON prefix_users (code);
CREATE INDEX prefix_users_icon_idx ON prefix_users (icon);
CREATE INDEX prefix_users_icon_quota_idx ON prefix_users (icon_quota);
CREATE INDEX prefix_users_file_quota_idx ON prefix_users (file_quota);
CREATE INDEX prefix_users_email_idx ON prefix_users (email);
CREATE INDEX prefix_users_template_id_idx ON prefix_users (template_id);
CREATE INDEX prefix_users_community_idx ON prefix_users (owner);
CREATE INDEX prefix_users_user_type_idx ON prefix_users (user_type);
CREATE INDEX prefix_users_moderation_idx ON prefix_users (moderation);
CREATE INDEX prefix_users_last_action_idx ON prefix_users (last_action);
CREATE INDEX prefix_users_name_idx ON prefix_users (name);
INSERT INTO prefix_users
    (username, password, email, name, icon, active, alias, code, icon_quota, file_quota, template_id, owner, user_type, last_action)
    VALUES ('news', '5f4dcc3b5aa765d61d8327deb882cf99', '', 'News', -1, 'yes', '', '', 10, 10000000, -1, -1, 'person', 0);


CREATE TABLE prefix_weblog_comments (
  ident SERIAL PRIMARY KEY,
  post_id integer NOT NULL default '0',
  owner integer NOT NULL default '0',
  postedname varchar(128) NOT NULL default '',
  body text NOT NULL,
  posted integer NOT NULL default '0'
) ;

CREATE INDEX prefix_weblog_comments_owner_idx ON prefix_weblog_comments (owner);
CREATE INDEX prefix_weblog_comments_posted_idx ON prefix_weblog_comments (posted);
CREATE INDEX prefix_weblog_comments_post_id_idx ON prefix_weblog_comments (post_id);
CREATE INDEX prefix_weblog_comments_postedname_idx ON prefix_weblog_comments (postedname);


CREATE TABLE prefix_weblog_posts (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  weblog integer NOT NULL default '-1',
  icon integer NOT NULL default '-1',
  access varchar(20) NOT NULL default 'PUBLIC',
  posted integer NOT NULL default '0',
  title text NOT NULL,
  body text NOT NULL
) ;

CREATE INDEX prefix_weblog_posts_owner_idx ON prefix_weblog_posts (owner,access,posted);
CREATE INDEX prefix_weblog_posts_community_idx ON prefix_weblog_posts (weblog);
INSERT INTO prefix_weblog_posts
    (owner, weblog, access, posted, title, body)
    VALUES (1, 1, 'PUBLIC', 1119422380, 'Hello', 'Welcome to this Elgg installation.');


CREATE TABLE prefix_weblog_watchlist (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL default '0',
  weblog_post integer NOT NULL default '0'
) ;

CREATE INDEX prefix_weblog_watchlist_owner_idx ON prefix_weblog_watchlist (owner);
CREATE INDEX prefix_weblog_watchlist_weblog_post_idx ON prefix_weblog_watchlist (weblog_post);


CREATE TABLE prefix_datalists (
  ident SERIAL PRIMARY KEY,
  name varchar(32) NOT NULL default '',
  value text NOT NULL
) ;

CREATE INDEX prefix_datalists_name_idx ON prefix_datalists (name);


CREATE TABLE prefix_users_alias (
  ident SERIAL PRIMARY KEY,
  installid varchar(32) NOT NULL default '',
  username varchar(32) NOT NULL default '',
  firstname varchar(64) NOT NULL default '',
  lastname varchar(64) NOT NULL default '',
  email varchar(128) NOT NULL default '',
  user_id integer NOT NULL default 0

);

CREATE INDEX prefix_users_alias_username_idx ON prefix_users_alias (username);
CREATE INDEX prefix_users_alias_installid_idx ON prefix_users_alias (installid);
CREATE INDEX prefix_users_alias_user_id_idx ON prefix_users_alias (user_id);

CREATE TABLE prefix_files_incoming (
   ident SERIAL PRIMARY KEY,
   installid varchar(32) NOT NULL default '',
   intentiondate integer NOT NULL default 0,
   size bigint NOT NULL default 0,
   foldername varchar(128) NOT NULL default '',
   user_id integer NOT NULL default 0
);

CREATE INDEX prefix_files_incoming_user_id_idx ON prefix_files_incoming (user_id);

CREATE TABLE prefix_feed_posts (
  ident SERIAL PRIMARY KEY,
  posted varchar(64) NOT NULL default '',
  added integer NOT NULL default 0,
  feed integer NOT NULL default 0,
  title text NOT NULL,
  body text NOT NULL,
  url varchar(255) NOT NULL default ''
);

CREATE INDEX prefix_feed_posts_feed_idx ON prefix_feed_posts (feed);
CREATE INDEX prefix_feed_posts_posted_idx ON prefix_feed_posts (posted,added);
CREATE INDEX prefix_feed_posts_added_idx ON prefix_feed_posts (added);

CREATE TABLE prefix_feed_subscriptions (
  ident SERIAL PRIMARY KEY,
  user_id integer NOT NULL default 0,
  feed_id integer NOT NULL default 0,
  autopost varchar(3) NOT NULL default 'no' check (autopost in ('yes', 'no')),
  autopost_tag varchar(128) NOT NULL default ''
);

CREATE INDEX prefix_feed_subscriptions_feed_idx ON prefix_feed_subscriptions (feed_id);
CREATE INDEX prefix_feed_subscriptions_user_idx ON prefix_feed_subscriptions (user_id);
CREATE INDEX prefix_feed_subscriptions_autopost_idx ON prefix_feed_subscriptions (autopost);

CREATE TABLE prefix_feeds (
  ident SERIAL PRIMARY KEY,
  url varchar(128) NOT NULL default '',
  feedtype varchar(16) NOT NULL default '',
  name text NOT NULL,
  tagline varchar(128) NOT NULL default '',
  siteurl varchar(128) NOT NULL default '',
  last_updated integer NOT NULL default 0
);

CREATE INDEX prefix_feeds_url_idx ON prefix_feeds (url,feedtype);
CREATE INDEX prefix_feeds_last_updated_idx ON prefix_feeds (last_updated);
CREATE INDEX prefix_feeds_siteurl_idx ON prefix_feeds (siteurl);
CREATE INDEX prefix_feeds_tagline_idx ON prefix_feeds (tagline);


-- 
-- Table structure for table messages
-- 

CREATE TABLE prefix_messages (
  ident SERIAL PRIMARY KEY,
  title text NOT NULL default '',
  body text NOT NULL default '',
  from_id integer NOT NULL default -1,
  to_id integer NOT NULL default -1,
  posted integer NOT NULL default 0,
  status varchar(128) NOT NULL default 'unread',
  CHECK (status IN ('read','unread'))
);

CREATE INDEX prefix_messages_to_id_idx ON prefix_messages (from_id,to_id);