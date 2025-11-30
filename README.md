# Mise en place et lancement du projet "mesvoyages"

Ce fichier liste, dans l'ordre, toutes les commandes à exécuter (Windows PowerShell) pour installer, configurer et lancer l'application localement.

pour démarrer le serveur php afin de visualiser le site :
avoir le dossier vendor via la commande :
composer install
puis avoir un serveur php :
symfony sevrer:start --port=3001
ou alors
php -S localhost:3001 -t public

dans mysql, copier toutes les lignes du fichier create_voyages_db.sql
ou alors ouvrez votre phpmyadmin et importer ce fichier
cela afin de créer la bdd voyages nécessaire pour le bon fonctionnement du site

Important: exécutez ces commandes depuis la racine du projet (`C:\xampp\htdocs\mesvoyages`).

1) Installer les dépendances PHP

```powershell
composer install
```

2) Vérifier/éditer les variables d'environnement

- Le fichier `.env` contient la configuration par défaut (base de données, mailer, etc.). Vérifiez/modifiez `DATABASE_URL`, `MAILER_DSN`, `APP_ENV`, `APP_SECRET` si nécessaire.

Exemple pour utiliser MySQL local (déjà présent dans `.env`):

```powershell
# Vérifier que la base de données existe ou la créer via MySQL/MariaDB
# (adapté à votre environnement MySQL)
# mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS voyages CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
```

3) Installer la CLI Symfony (optionnel mais recommandé)

- Si vous avez la CLI Symfony, utilisez-la pour démarrer le serveur et gérer l'application.
- Installation (option 1 - site officiel): téléchargez depuis https://symfony.com/download
- Option avec `scoop` (si scoop installé):

```powershell
scoop install symfony-cli
```

4) Démarrer le serveur web

- Avec la CLI Symfony (recommandé):

```powershell
symfony server:start --port=3001
```

- Sans la CLI Symfony (fallback - serveur PHP intégré):

```powershell
php -S localhost:3001 -t public
```

5) Commandes utiles pour la base de données (Doctrine)

```powershell
# Créer la base (si vous utilisez MySQL et que DATABASE_URL est bien configuré)
# php bin/console doctrine:database:create

# Exécuter les migrations (si besoin)
# php bin/console doctrine:migrations:migrate

# Charger des fixtures (si le projet en a)
# php bin/console doctrine:fixtures:load
```

6) Gestion des assets

```powershell
# Si nécessaire (la plupart des scripts composer l'ont déjà exécuté):
composer run assets:install
composer run importmap:install
```

7) Vérifier le site

Ouvrez un navigateur et rendez-vous sur `http://localhost:3001`.

Routes principales (exemples à taper après `http://localhost:3001`):

- `/` => Accueil
- `/voyages` => Liste des voyages
- `/voyages?champ=ville&ordre=ASC` => Trier
- `/voyages/voyage/{id}` => Détail (remplacer `{id}`)
- `/contact` => Formulaire de contact
- `/admin` => Espace admin
- `/admin/ajout` => Ajouter un voyage (admin)

Remarques:
- La CLI Symfony n'est pas installée par défaut sur votre machine (erreur détectée lors de l'exécution). Le fallback `php -S` fonctionne, mais la CLI fournit des outils supplémentaires utiles.
- Le projet contient des migrations dans `migrations/` si vous souhaitez migrer la base de données.
- Certains chemins et variables ont des fautes de frappe dans le code (`pubolic/`, `enivronnement/`) — ils fonctionnent tels qu'écrits, mais considérez les corriger si vous modifiez le code.

Si vous voulez, je peux:
- lancer le serveur maintenant (je l'ai déjà démarré avec `php -S` comme fallback),
- tenter d'installer la CLI Symfony pour vous, ou
- exécuter les migrations et vérifier que la base de données est prête.

Bonne utilisation.
