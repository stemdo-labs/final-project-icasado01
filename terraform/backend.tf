terraform {
  backend "azurerm" {
    resource_group_name   = "rg-icasado-dvfinlab"
    storage_account_name  = "staicasadodvfinlab"
    container_name        = "tfstate"
    key                   = "terraform.tfstate"
  }
}
