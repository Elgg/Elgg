resource "kubernetes_service" "elgg_app" {
  metadata {
    name = "elgg-app"
  }
  spec {
    selector = {
      app = "elgg-app"
    }
    port {
      port        = 80
      target_port = 80
    }
    type = "NodePort"
  }
}

resource "kubernetes_service" "elgg_db" {
  metadata {
    name = "elgg-db"
  }
  spec {
    selector = {
      app = "elgg-db"
    }
    port {
      port        = 3306
      target_port = 3306
    }
    type = "ClusterIP"
  }
}

resource "kubernetes_service" "elgg_phpmyadmin" {
  metadata {
    name = "elgg-phpmyadmin"
  }
  spec {
    selector = {
      app = "elgg-phpmyadmin"
    }
    port {
      port        = 80
      target_port = 80
    }
    type = "NodePort"
  }
}
