terraform {
  required_providers {
    azurerm = {
      source  = "hashicorp/azurerm"
      version = "3.103.0"
    }
  }
}

provider "azurerm" {
  features {}
}

# RECURSOS DE RED

resource "azurerm_virtual_network" "vnet" {
  name                = var.vnet_name
  resource_group_name = var.resource_group_name
  location            = var.location
  address_space       = var.vnet_address_space
}

resource "azurerm_subnet" "subnets" {
  depends_on = [ azurerm_virtual_network.vnet ]
  for_each = var.subnets
  name = each.key
  virtual_network_name = var.vnet_name
  address_prefixes = each.value["subnet_address_prefixes"]
  resource_group_name = var.resource_group_name
}

resource "azurerm_network_interface" "nic1" {
  depends_on = [ azurerm_virtual_network.vnet ]
  name                = "vm-db-nic"
  location            = var.location
  resource_group_name = var.resource_group_name

  ip_configuration {
    name                          = "internal"
    subnet_id                     =  azurerm_subnet.subnets["subnet-a"].id
    private_ip_address_allocation = "Dynamic"
  }
}

resource "azurerm_network_interface" "nic2" {
  depends_on = [ azurerm_virtual_network.vnet ]
  name                = "vm-backup-nic"
  location            = var.location
  resource_group_name = var.resource_group_name

  ip_configuration {
    name                          = "internal"
    subnet_id                     = azurerm_subnet.subnets["subnet-b"].id
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
    username              = each.value.username
    public_key            = file("~/.ssh/id_rsa.pub")
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

resource "azurerm_kubernetes_cluster" "aks" {
  depends_on = [ azurerm_container_registry.acr ]
  name = "finlab-aks"
  location = var.location
  resource_group_name = var.resource_group_name
  dns_prefix = "dns-aks"
  sku_tier = "Standard"
  default_node_pool {
    name = "default"
    node_count = 1
    vm_size = "Standard_B2s"
  }
  identity {
    type = "SystemAssigned"
  }
}

# Crea un inventario de Terraform cada vez que se lanza

data "template_file" "inventory" {
  template = file("${path.module}/inventory.tpl")

  vars = {
    private_ip =  azurerm_network_interface.nic1.private_ip_address

  }
}

resource "local_file" "inventory" {
  content  = data.template_file.inventory.rendered
  filename = "${path.module}/../app/bdModel.php"
}

# BdModel

data "template_file" "bdModel" {
  template = file("${path.module}/bdModel.tpl")

  vars = {
    public_ip =  azurerm_public_ip.pub-ip.ip_address

  }
}