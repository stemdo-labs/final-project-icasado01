[all]
vm-db ansible_host=${private_ip}

[all:vars]
ansible_user=vm1
ansible_ssh_private_key_file=~/.ssh/id_rsa
