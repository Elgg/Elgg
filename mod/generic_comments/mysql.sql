CREATE TABLE `prefix_comments` (
  ident int(11) NOT NULL auto_increment COMMENT '-> comment number',
  object_id int(11) NOT NULL COMMENT '-> ident of object being commented on',
  object_type varchar(128) NOT NULL COMMENT '-> type of object being commented on',
  owner int(11) NOT NULL COMMENT '-> ident of user posting comment, if any',
  postedname varchar(128) NOT NULL COMMENT '-> name of user posting comment',
  body text  NOT NULL COMMENT '-> text of comment',
  posted int(11) NOT NULL COMMENT '-> time comment was posted',
  PRIMARY KEY (ident),
  KEY object_id (object_id),
  KEY object_type (object_type),
  KEY owner (owner)
) ENGINE=MyISAM  ;

CREATE TABLE `prefix_watchlist` (
  ident int(11) NOT NULL auto_increment COMMENT '-> watchlist number',
  owner int(11) NOT NULL COMMENT '-> watchlist owner',
  object_id int(11) NOT NULL COMMENT '-> id of object being watched',
  object_type varchar(128) NOT NULL COMMENT '-> type of object being watched',
  PRIMARY KEY (ident),
  KEY owner (owner),
  KEY object_id (object_id),
  KEY object_type (object_type)
) ENGINE=MyISAM ;