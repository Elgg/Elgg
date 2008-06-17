CREATE TABLE prefix_commentwall (
  ident SERIAL PRIMARY KEY,

  wallowner integer NOT NULL,

  comment_owner integer NOT NULL,
  content text NOT NULL,

  posted integer NOT NULL

);
