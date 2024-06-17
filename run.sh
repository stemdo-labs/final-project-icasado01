COMMANDS="sudo apt update; sudo apt upgrade -y; sudo apt install ansible -y"

# BUILD IMAGE

echo "Building docker image"
docker build -t acrfinlab.azurecr.io/final-project:icasado .
echo " "
echo "---"
echo " "

# LOGIN TO ACR
echo "Login to ACR"
az acr login -n acrfinlab
echo " "
echo "---"
echo " "

# PUSH IMAGE TO ACR

echo "Pushing image to ACR"
docker push acrfinlab.azurecr.io/final-project:icasado
echo " "
echo "---"
echo " "

# SET AKS

echo "Setting AKS"
az aks get-credentials --resource-group rg-icasado-dvfinlab --name finlab-aks --overwrite-existing
echo " "
echo "---"
echo " "

# RUN SECRET SCRIPT

echo "Running secret script"
sh ./terraform/create-acr-secrets.sh
echo " "
echo "---"
echo " "

# DEPLOY KUBERNETES

echo "Deploying kubernetes"
kubectl apply -f deployment.yaml
echo " "
echo "---"
echo " "

# 

scp ./ansible/* vm2@$PUBLIC_IP /home/vm2/ansible/

#DEPLOY ANSIBLE
cd terraform/
ssh "vm2@$PUBLIC_IP" << EOF
   $COMMANDS
EOF