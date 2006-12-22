
CREATE TABLE `prefix_dashboard_data` (
  ident SERIAL PRIMARY KEY,
  `widget` integer NOT NULL,
  `name` varchar(128) NOT NULL,
  `value` text NOT NULL
) ;

CREATE INDEX prefix_dashboard_data_idx ON prefix_dashboard_data (widget);

CREATE TABLE `prefix_dashboard_widgets` (
  ident SERIAL PRIMARY KEY,
  `owner` integer NOT NULL,
  `widget_type` varchar(128) NOT NULL,
  `display_order` integer NOT NULL,
  `access` varchar(128) NOT NULL
) ;

CREATE INDEX prefix_dashboard_widgets_idx ON prefix_dashboard_widgets (owner,display_order,access);