terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "3.103.0"
    }
  }

  backend "azurerm" {
  resource_group_name  = "rg-icasado"
  storage_account_name = "staicasado"
  container_name       = "tfstate"
  key                  = "terraform.tfstate"
}
}

provider "azurerm" {
  features {}
}

# RECURSOS DE RED

data "azurerm_virtual_network" "common-vnet-bootcamp" {
  name                 = "vnet-common-bootcamp"  
  resource_group_name  = "final-project-common"
}

data "azurerm_subnet" "sn-icasado" {
  name                 = "sn-icasado"
  virtual_network_name = data.azurerm_virtual_network.common-vnet-bootcamp.name
  resource_group_name  = "final-project-common"
}

data "azurerm_subnet" "sn-common-aks-project" {
  name                 = "sn-common-aks-project"
  virtual_network_name = data.azurerm_virtual_network.common-vnet-bootcamp.name
  resource_group_name  = "final-project-common"
}

resource "azurerm_network_interface" "nic1" {
  name                = "vm-db-nic"
  location            = var.location
  resource_group_name  = "final-project-common"

  ip_configuration {
    name                          = "internal"
    subnet_id                     = data.azurerm_subnet.sn-icasado.id
    private_ip_address_allocation = "Static"
    private_ip_address            = "10.0.28.4"
  }
}

resource "azurerm_network_interface" "nic2" {
  name                = "vm-backup-nic"
  location            = var.location
  resource_group_name = var.resource_group_name

  ip_configuration {
    name                          = "internal"
    subnet_id                     = data.azurerm_subnet.sn-icasado.id
    private_ip_address_allocation = "Dynamic"
    public_ip_address_id          = azurerm_public_ip.pub-ip.id
  }
}

resource "azurerm_public_ip" "pub-ip" {
  name                    = "pub-ip"
  resource_group_name     = var.resource_group_name
  location                = var.location
  allocation_method       = "Static"
}

# NSG

resource "azurerm_network_security_group" "vm_nsg" {
  name                = "vm-nsg"
  location            = var.location
  resource_group_name = var.resource_group_name
}

resource "azurerm_network_security_rule" "allow_ssh" {
  name                        = "allow-ssh"
  resource_group_name         = var.resource_group_name
  priority                    = 100
  direction                   = "Inbound"
  access                      = "Allow"
  protocol                    = "Tcp"
  source_port_range           = "*"
  destination_port_range      = "22"
  source_address_prefix       = "*"
  destination_address_prefix  = "*"
  network_security_group_name = azurerm_network_security_group.vm_nsg.name
}

resource "azurerm_network_security_rule" "deny_all" {
  name                        = "deny-all"
  resource_group_name         = var.resource_group_name
  priority                    = 200
  direction                   = "Inbound"
  access                      = "Deny"
  protocol                    = "*"
  source_port_range           = "*"
  destination_port_range      = "*"
  source_address_prefix       = "*"
  destination_address_prefix  = "*"
  network_security_group_name = azurerm_network_security_group.vm_nsg.name
}

# VIRTUAL MACHINES

resource "azurerm_linux_virtual_machine" "vms" {
  for_each                = var.virtual_machine
  name                    = each.key
  location                = each.value.location
  resource_group_name     = var.resource_group_name
  admin_username          = each.value.username
  admin_password          = each.value.password
  size                    = each.value.vm_size
  disable_password_authentication = false
  
  admin_ssh_key {
    username   = each.value.username
    public_key = file("./id_rsa.pub")
  }
  
  network_interface_ids   = [
    lookup(local.network_interface_map, each.key)
  ]

  os_disk {
    caching               = "ReadWrite"
    storage_account_type  = "Standard_LRS"
  }
    source_image_reference {
    publisher             = "Canonical"
    offer                 = "0001-com-ubuntu-server-jammy"
    sku                   = "22_04-lts"
    version               = "latest"
  }
}

locals {
  network_interface_map = {
    "vm-db"               = azurerm_network_interface.nic1.id
    "vm-backup"           = azurerm_network_interface.nic2.id
  }
}

# ACR

resource "azurerm_container_registry" "acr" {
  name                = "acrfinlab"
  resource_group_name = var.resource_group_name
  location            = var.location
  sku                 = "Standard"
  admin_enabled       = true
}

# AKS

#resource "azurerm_kubernetes_cluster" "aks" {
#  depends_on = [ azurerm_container_registry.acr ]
#  name = "finlab-aks"
#  location = var.location
#  resource_group_name = var.resource_group_name
#  dns_prefix = "dns-aks"
#  sku_tier = "Standard"
#  default_node_pool {
#    name = "default"
#    node_count = 1
#    vm_size = "Standard_B2s"
#    vnet_subnet_id = var.subnet_aks_id
#  }
#  identity {
#    type = "SystemAssigned"
#  }
#    network_profile {
#    network_plugin     = "azure"
#    service_cidr       = "10.0.3.0/24"
#    dns_service_ip     = "10.0.3.10"
#  }
#}
