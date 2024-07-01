variable "resource_group_name" {
  description = "Nombre del grupo de recursos"
  type        = string
}

variable "location" {
  type        = string
  default     = "West Europe"
}

# VNET

variable "vnet_name" {
  type        = string
  description = "Nombre de la Vnet"
  nullable    = false
}

variable "vnet_address_space" {
  type        = list(any)
  description = "Direccion de la Vnet"
}

# SUBNET

#variable "subnets" {
#  type = map(object({
#    subnet_address_prefixes = list(string)
#  }))
#}

#variable "subnet_id" {
#  type = string
#  sensitive = true
#}

# VIRTUAL MACHINES

variable "virtual_machine" {
  type = map(object({
    location          = string
    vm_size           = string
    username          = string
    password          = string
  }))
}
