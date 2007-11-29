
CREATE TABLE prefix_widget_data (
  ident SERIAL PRIMARY KEY,
  widget integer NOT NULL,
  name varchar(128) collate utf8_unicode_ci NOT NULL,
  value text collate utf8_unicode_ci NOT NULL
) ;

CREATE INDEX prefix_widget_data_idx ON prefix_widget_data (widget);

CREATE TABLE prefix_widgets (
  ident SERIAL PRIMARY KEY,
  owner integer NOT NULL,
  type varchar(128) collate utf8_unicode_ci NOT NULL,
  location varchar(128) collate utf8_unicode_ci NOT NULL,
  location_id integer NOT NULL,
  wcolumn integer NOT NULL,
  display_order integer NOT NULL,
  access varchar(128) collate utf8_unicode_ci NOT NULL
) ;

CREATE INDEX prefix_widgets_idx ON prefix_widgets (owner,display_order,access);