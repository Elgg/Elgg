resource "kubernetes_secret" "elgg_env" {
  metadata {
    name = "elgg-env"
  }
  type = "Opaque"

  string_data = {
    MYSQL_ROOT_PASSWORD = "root1234"
    MYSQL_DATABASE      = "elggdb"
    MYSQL_USER          = "admin"
    MYSQL_PASSWORD      = "admin1234"
    ELGG_DB_HOST        = "elgg-db"
    DB_NAME             = "elggdb"
    DB_USER             = "admin"
    DB_PASSWORD         = "admin1234"
    PMA_HOST            = "elgg-db"
    PMA_USER            = "admin"
    PMA_PASSWORD        = "admin1234"
    ELGG_SITE_URL       = "http://34.175.206.89/"
    ELGG_SITE_NAME      = "MarjaneNews"
    ELGG_SITE_EMAIL     = "oussamabitaa10@gmail.com"
    ELGG_ADMIN_USER     = "admin"
    ELGG_ADMIN_PASS     = "adminadmin"
    ELGG_ADMIN_EMAIL    = "oussamabitaa10@gmail.com"
  }
}
