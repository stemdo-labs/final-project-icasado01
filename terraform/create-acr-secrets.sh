#!/bin/bash

# Obtén los outputs de Terraform y elimina espacios en blanco adicionales
ACR_LOGIN_SERVER=$(terraform output -raw acr_login_server | xargs)
ACR_ADMIN_USERNAME=$(terraform output -raw acr_admin_username | xargs)
ACR_ADMIN_PASSWORD=$(terraform output -raw acr_admin_password | xargs)
export PUBLIC_IP=$(terraform output -raw public_ip_address | xargs)
DB_PRIVATE_IP=$(terraform output -raw vm_db_private_ip  | xargs)

# Verifica que las variables no estén vacías
if [[ -z "$ACR_LOGIN_SERVER" || -z "$ACR_ADMIN_USERNAME" || -z "$ACR_ADMIN_PASSWORD" || -z "$PUBLIC_IP" ]]; then
  echo "Error: No se pudieron obtener todas las credenciales o la dirección IP pública desde Terraform."
  exit 1
fi

# Crear el secreto en Kubernetes
kubectl create secret docker-registry acr-secret \
  --docker-server="$ACR_LOGIN_SERVER" \
  --docker-username="$ACR_ADMIN_USERNAME" \
  --docker-password="$ACR_ADMIN_PASSWORD"

# Verifica que el secreto se creó correctamente
if [ $? -eq 0 ]; then
  echo "Secreto de Docker creado exitosamente."
else
  echo "Error al crear el secreto de Docker."
fi