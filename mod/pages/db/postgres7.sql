CREATE TABLE prefix_pages (
 	ident SERIAL PRIMARY KEY,
	name varchar(128) NOT NULL default '',
	uri varchar(128) NOT NULL default '',
	parent integer NOT NULL default '0',
	weight integer NOT NULL default '0',
	title varchar(128) NOT NULL default '',
	content text NOT NULL default '',
	owner integer NOT NULL default '-1',
	access varchar(20) NOT NULL default 'PUBLIC'
);

CREATE INDEX prefix_pages_parent_idx ON prefix_pages (parent,owner);
CREATE UNIQUE INDEX prefix_pages_pk ON prefix_pages(name,uri,owner);
