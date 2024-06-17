output "acr_login_server" {
  value = azurerm_container_registry.acr.login_server
}

output "acr_admin_username" {
  value = azurerm_container_registry.acr.admin_username
}

output "acr_admin_password" {
  value = azurerm_container_registry.acr.admin_password
  sensitive = true
}

output "public_ip_address" {
  value = azurerm_public_ip.pub-ip.ip_address
}

output "vm_db_private_ip" {
  value = azurerm_network_interface.nic1.private_ip_address
}