# ECF_Cinephoria

Pour faire l'application fonctionner en local, les extensions suivantes doivent être présentes en local :

- PHP (version 8 minimum)
- MySQL (version 8 minimum)
- Server local basé sur Apache (Wamp, Xampp, Mamp, ...)
- PHPMYADMIN

Les étapes à suivre pour déployer l'application en local :

1) Effectuer git pull du repository ou le télécharger dans le format zip et désarchiver son contenu dans un dossier souhaité en local.

2) Créer un fichier .env.local et copier le contenu du fichier .env dans le fichier .env.local

3) Ajouter les variables d'environnement suivantes avec des données remplies (DATABASE, USER, PASSWORD, etc) dans le fichier .env.local :
   - DATABASE_URL="mysql://USER:@localhost:port/DATABASE?serverVersion=8&charset=utf8mb4"
   - MAILER_DSN=MAILSERVICE://USERNAME:PASSWORD@default?verify_peer=0
   - APP_SECRET
  
4) Dans le terminal : ouvrir le dossier de l'application et entrer "composer install" dans le terminal
   
5) Créer une base de données dans PHPMYADMIN : ouvrir le dossier de l'application et entrer dans le terminal:
   - php bin/console doctrine:database:create
   - php bin/console doctrine:migrations:migrate (OU importer le fichier "ecf_cinephoria_bdd.sql")
   - importer le fichier "ecf_cinephoria_donnees.sql" dans la base de données créée
   
6) Nettoyer le cache et deployer l'application :
   - php bin/console cache:clear --env=prod
   - php bin/console server:start

Lors de déploiement de l'application son adresse locale sera affichée dans le terminal (localhost:8000, 127.0.0.1:8000, ...)







