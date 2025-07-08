resource "kubernetes_persistent_volume_claim" "elgg_pvc" {
  metadata {
    name      = var.elgg_pvc_name
    namespace = var.namespace
  }

  spec {
    access_modes = var.elgg_pvc_access_modes

    resources {
      requests = {
        storage = var.elgg_pvc_storage
      }
    }

    storage_class_name = var.storage_class_name
  }
}

resource "kubernetes_persistent_volume_claim" "db_pvc" {
  metadata {
    name      = var.db_pvc_name
    namespace = var.namespace
  }

  spec {
    access_modes = var.db_pvc_access_modes

    resources {
      requests = {
        storage = var.db_pvc_storage
      }
    }

    storage_class_name = var.storage_class_name
  }
}
