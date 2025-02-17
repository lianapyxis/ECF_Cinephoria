# ECF_Cinephoria

Cinephoria est une application web conçue pour offrir aux utilisateurs une expérience de réservation des sièges dans les cinémas de l'entrerpise Cinéphoria.
Cinéphoria intègre diverses fonctionnalités telles que la réservation des sièges dans les séances à venir, l'affichage des informations sur les films et les séances proposées dans les cinémas Cinéphoria, la gestion des contenus de l'application web depuis son backoffice.

Prérequis de déploiement de l'application en local :

1) Configurations système nécessaires :
- Système dʼexploitation : Windows/Mac
- Mémoire : minimum 6 GB de RAM
- Espace disque : minimum 350 MB dʼespace libre

2) Dépendances logicielles :
- Base de données : MySQL 8.3.0 ou supérieur, MongoDB 8.0.0 ou supérieur
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
  
4) Dans le terminal : ouvrir le dossier de l'application et entrer "composer install"
   
5) Créer une base de données dans PHPMYADMIN : ouvrir le dossier de l'application et entrer dans le terminal :
   - php bin/console doctrine:database:create
   - php bin/console doctrine:migrations:migrate (OU importer le fichier "ecf_cinephoria_bdd.sql")
   - importer le fichier "ecf_cinephoria_donnees.sql" dans la base de données créée
   
6) Nettoyer le cache et deployer l'application :
   - php bin/console cache:clear --env=prod
   - php bin/console cache:clear --env=dev
   - php bin/console server:start

Lors de déploiement de l'application son adresse locale sera affichée dans le terminal (localhost:8000, 127.0.0.1:8000, ...)







