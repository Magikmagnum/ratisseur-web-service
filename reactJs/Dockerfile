# Utilisez une image Node.js comme image de base
FROM node:16.10.0-alpine3.14

# Définissez le répertoire de travail dans le conteneur
WORKDIR /app

# Copiez les fichiers package.json et package-lock.json dans le répertoire de travail
COPY package*.json ./

# Installez les dépendances du projet
RUN npm install

# Copiez tous les fichiers de l'application dans le répertoire de travail
COPY . .

# Exposez le port 3000 pour accéder à l'application React.js
EXPOSE 3000

# Commande de démarrage de l'application
CMD ["npm", "start"]


# FROM node:16.10.0-alpine3.14
# WORKDIR /app
# COPY package*.json ./
# RUN npm install
# COPY . .
# CMD ["npm", "start"]