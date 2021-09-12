# P8 - ToDo & Co

<hr>

Amélioration et documentation du projet existant "ToDo & Co".

## Installation

<hr>

1. Clonez le projet existant

```shell
git clone https://github.com/NicolasHalberstadt/P8-TodoList.git
```

2. Configurez vos variables d'environnement tel que la connexion à la base de données dans le fichier `.env.local` qui
   devra être créé à la racine du projet en réalisant une copie du fichier `.env` :

```shell
cp .env .env.local
```

3. Téléchargez et installez les dépendances du projet avec [Composer](https://getcomposer.org/download/) :

```shell
composer install
```

4. Créez la base de données si elle n'existe pas déjà :

```shell
php bin/console doctrine:database:create
```

5. Créez les différentes tables de la base de données en appliquant les fichiers de migrations :

```
php bin/console doctrine:migrations:migrate
```

6. (Optionnel) Chargez le jeu de données de base :

```shell
php bin/console doctrine:fixtures:load
```

7. (Optionnel) Lancez le serveur Symfony pour tester le projet localement :

```shell
symfony server:start
```

8. Après l'installation des fixtures, utilisez un de ces comptes pour vous connecter et exploiter les données :

   | Username | Password    |
      | -------- | ----------- |
   | Admin    | compteAdmin |
   | User     | compteUser  |

9. Félicitations 🎉 le projet est installé, vous pouvez désormais commencer à l'utiliser à votre guise !