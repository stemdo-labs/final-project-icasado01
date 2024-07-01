resource_group_name           = "rg-icasado"
location                      = "UK South"

# VNET

vnet_name                    = "vnet_finlab"
vnet_address_space           = [ "10.0.0.0/16" ]

#subnets = {
#  "subnet-a" = {
#    subnet_address_prefixes = ["10.0.0.0/24"]
#  }
#  "subnet-b" = {
#    subnet_address_prefixes = ["10.0.1.0/24"]
#  }
#  "subnet-aks" = {
#    subnet_address_prefixes = ["10.0.2.0/24"]
#  }
#}

virtual_machine = {
  "vm-db" = {
    location = "West Europe",
    vm_size  = "Standard_B1ms",
    username = "vm1"
    password = "Nc1234*"
    network_interface_ids = 0
  }
  "vm-backup" = {
    location = "West Europe",
    vm_size  = "Standard_B1ms",
    username = "vm2"
    password = "Nc1234*"
    network_interface_ids = 1
  }
}