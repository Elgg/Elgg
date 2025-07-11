resource "kubernetes_persistent_volume_claim" "db_pvc" {
  metadata {
    name = "db-pvc"
  }
  spec {
    access_modes = ["ReadWriteOnce"]
    storage_class_name = "default-storageclass"
    resources {
      requests = {
        storage = "20Gi"
      }
    }
  }
}

resource "kubernetes_persistent_volume_claim" "elgg_pvc" {
  metadata {
    name = "elgg-pvc"
  }
  spec {
    access_modes = ["ReadWriteOnce"]
    storage_class_name = "default-storageclass"
    resources {
      requests = {
        storage = "20Gi"
      }
    }
  }
}
