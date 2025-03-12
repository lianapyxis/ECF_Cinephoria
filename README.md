# ECF_Cinephoria

Cinephoria est une application web conçue pour offrir aux utilisateurs une expérience de réservation des sièges dans les cinémas de l'entrerpise Cinéphoria.
Cinéphoria intègre diverses fonctionnalités telles que la réservation des sièges dans les séances à venir, l'affichage des informations sur les films et les séances proposées dans les cinémas Cinéphoria, la gestion des contenus de l'application web depuis son backoffice.

Prérequis de déploiement de l'application en local avec Docker :

1) Configurations système nécessaires :
- Système dʼexploitation : Windows/Mac/Linux
- Mémoire : minimum 6 GB de RAM
- Espace disque : minimum 700 MB dʼespace libre

2) Dépendances logicielles :
- Docker (version 27.2.0 ou supérieur)
- Docker Compose (version 2.29.2 ou supérieur)
- Docker Desktop pour Windows/Mac
- Installer le programme "make" pour exécuter le fichier Makefile (par ex. choco install make) pour Windows/Mac

3) Dépendances matérielles :
   Aucune dépendance matérielle spécifique requise pour lʼinstallation standard.

4) Configuration de réseau :
   Ports ouverts nécessaires : 8000, 8080, 3306, 5040, 27017.
   Assurez-vous que le pare-feu est configuré pour permettre le trafic entrant et sortant sur ces ports.

Les étapes à suivre pour déployer l'application avec Docker :

1) Mettre les fichiers ecf_cinephoria_donnees.sql & ecf_cinephoria.damaged_places.json à la racine du projet

2) Créer un fichier .env.local et copier le contenu du fichier .env dans le fichier .env.local

3) Mettre les variables dans le fichier env.local :
   - DATABASE_URL="mysql://root:@db_ecf_cinephoria:3306/ecf_cinephoria?serverVersion=8&charset=utf8mb4"
   - MONGODB_URL=mongodb://mongo:27017/
   - MONGODB_DB=ecf_cinephoria
   - MONGO_INITDB_ROOT_USERNAME=root
   - MONGO_INITDB_ROOT_PASSWORD=password
   - APP_SECRET (à remplir)
   - APP_ENV=dev

4) Dans la racine du projet : entrer dans le terminal : composer install

5) Installation des containeurs : 
   - pour Windows & Mac : lancer Docker Desktop
   - dans la racine du projet entrer dans le terminal : docker-compose build 

5) Entrer dans le terminal dans la racine du projet : make init

6) Phpmyadmin se trouve dans : localhost:5040 ou 0.0.0.0:5040

7) Le projet se trouve dans http://127.0.0.1:8000/


Prérequis de déploiement de l'application en local avec un serveur local :

1) Configurations système nécessaires :
- Système dʼexploitation : Windows/Mac
- Mémoire : minimum 6 GB de RAM
- Espace disque : minimum 350 MB dʼespace libre

2) Dépendances logicielles :
- Base de données : MySQL 8.3.0 ou supérieur, MongoDB 8.0.0 ou supérieur, MongoDB Compass 1.45.3 ou supérieur
- Serveur Web : Apache 2.4
- Framework Backend : PHP 8.1.2 ou supérieur, Symfony 7 ou supérieur
- PHPMYADMIN : Version 5.2.1 ou supérieur
- Composer : Version 2.7.7 ou superieur

3) Dépendances matérielles :
   Aucune dépendance matérielle spécifique requise pour lʼinstallation standard.

4) Configuration de réseau :
   Ports ouverts nécessaires : 80 (HTTP), 3308 (MySQL), 27017 (MongoDB).
   Assurez-vous que le pare-feu est configuré pour permettre le trafic entrant et sortant sur ces ports.

Les étapes à suivre pour déployer l'application en local :

1) Effectuer git pull du repository ou le télécharger dans le format zip et désarchiver son contenu dans un dossier souhaité en local.

2) Créer un fichier .env.local et copier le contenu du fichier .env dans le fichier .env.local

3) Ajouter les variables d'environnement suivantes avec des données remplies (DATABASE, USER, PASSWORD, etc) dans le fichier .env.local :
   - DATABASE_URL="mysql://USER:@localhost:port/DATABASE?serverVersion=8&charset=utf8mb4"
   - MAILER_DSN=MAILSERVICE://USERNAME:PASSWORD@default?verify_peer=0
   - APP_SECRET
   - MONGODB_DB=ecf_cinephoria
   - MONGODB_URL=mongodb://localhost:27017/
   - APP_ENV=dev

4) Enlever les lignes 7,8,9 avec la configuration des options username & password dans le fichier config/packages/doctrine_mongodb.yaml 
   si la connexion à MongoDB en local s'effectue sans login et mot de passe sinon ajouter # MONGO_INITDB_ROOT_USERNAME=root et MONGO_INITDB_ROOT_PASSWORD=password
   dans le fichier .env.local
  
5) Dans le terminal : ouvrir le dossier de l'application et entrer "composer install"
   
6) Créer une base de données dans PHPMYADMIN : ouvrir le dossier de l'application et entrer dans le terminal :
   - php bin/console doctrine:database:create
   - php bin/console doctrine:migrations:migrate (OU importer le fichier "ecf_cinephoria_bdd.sql")
   - importer le fichier "ecf_cinephoria_donnees.sql" dans la base de données créée

7) Créer une base de données "ecf_cinephoria" dans MongoDb via MongoDB Shell ou MongoDB Compass

8) Importer le fichier ecf_cinephoria.damaged_places.json dans la base de données créée
   
9) Nettoyer le cache et deployer l'application :
   - php bin/console cache:clear --env=prod
   - php bin/console cache:clear --env=dev
   - php bin/console server:start

Lors de déploiement de l'application son adresse locale sera affichée dans le terminal (localhost:8000, 127.0.0.1:8000, ...)







