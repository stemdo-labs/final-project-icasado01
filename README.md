# Final Project - Icasado
---
El proyecto consiste en la creación de la infraestructura en Azure con Terraform, la creación de la aplicación web con Kubernetes y el uso de automatizaciones para la creación de otros recursos necesarios, como la base de datos con Ansible.
Todo esto, automatizado con el uso de Workflows de GitHub Actions.

## Terraform
Para el despligue de Terraform en Azure, debemos ejecutar el Workflow de Terraform Apply, el cual se encargará del despliegue de todos los recursos necesarios en Azure para el funcionamiento de la aplicación.

## Ansible
Ansible se encargará de configurar una máquina virtual para poder utilizarla como base de datos. Para ello, utilizaremos el Workflow Ansible. Para poder usar este Workflow, es necesario definir una de las máquinas virtuales creadas en azure (vm-backup) como self-runner. Es lo unico que necesitamos hacer dentro de la máquina virtual, ya que el Workflow de Ansible se encarga del resto.

## Kubernetes
El despliegue de Kubernetes se podrá realizar con el Workflow de CD o con el Workflow de CI, este último en caso de realizar un merge a la rama main.

## Base de Datos
La base de datos se creará utilizando phpMyAdmin, que será accesible mediante un service de Kubernetes.
### Tabla "entradas"
![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/d948ac7d-e054-4d74-9bbc-67948140a169)
### Tabla "tipo_entradas"
![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/c36e3e20-475e-4f9f-96aa-a5c02ce3a237)

![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/698d7d81-3ff9-40be-8cea-3010ba265444)

---

## Vista de la aplicación
### Index de la aplicación
![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/7c1d7924-166f-460d-ab16-8bd513c04c28)

![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/ac1dfd04-1077-42a7-876c-a01f0079cb46)

![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/2c618b54-258b-411e-bc89-c6bd893ccc06)

![image](https://github.com/stemdo-labs/final-project-icasado01/assets/166407751/68a3c361-8528-46ba-9c0e-b1a5f21c1c87)








