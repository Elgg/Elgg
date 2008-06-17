CREATE TABLE `prefix_comments` (
  ident SERIAL PRIMARY KEY,
  object_id int(11) NOT NULL default '0',
  object_type varchar(128) NOT NULL default '',
  owner int(11) NOT NULL default '0',
  postedname varchar(128) NOT NULL default '',
  body text  NOT NULL default '',
  posted int(11) NOT NULL default '0'
)

CREATE INDEX prefix_comments_object_id_idx ON prefix_comments (object_id);
CREATE INDEX prefix_comments_object_type_idx ON prefix_comments (object_type);
CREATE INDEX prefix_comments_owner_idx ON prefix_comments (owner);

CREATE TABLE `prefix_watchlist` (
  ident SERIAL PRIMARY KEY,
  owner int(11) NOT NULL default '0',
  object_id int(11) NOT NULL default '0',
  object_type varchar(128) NOT NULL default '',
  KEY owner (owner),
  KEY object_id (object_id),
  KEY object_type (object_type)
)

CREATE INDEX prefix_watchlist_object_id_idx ON prefix_watchlist (object_id);
CREATE INDEX prefix_watchlist_object_type_idx ON prefix_watchlist (object_type);
CREATE INDEX prefix_watchlist_owner_idx ON prefix_watchlist (owner);
