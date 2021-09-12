# Contribute to the project

<hr>

Pour contribuer au projet, merci de suivre les instruction suivantes:

1. Réaliser un fork du projet
2. Clonez le fork sur votre machine

```
git clone https://github.com/NicolasHalberstadt/P8-TodoList.git
```

3. Installez les dépendances du projet (voir le fichier
   d'[Installation](https://github.com/NicolasHalberstadt/P8-TodoList/blob/master/README.md))

4. Créez une nouvelle branche pour travailler dessus

```
git checkout -b nom-nouvelle-branch
```

5. Ajoutez la branche en la poussant sur votre fork

```
git push origin nom-nouvelle-branch
```

# Processus de qualité

<hr>

Afin de respecter les processus de qualité mis en place, lancez les tests avec génération d'un rapport de couverture de
code :

```
php bin/phpunit --coverage-html public/test-coverage
```

Pour implémenter de nouveaux tests, se référer à
la [documentation officielle de Symfony](https://symfony.com/doc/4.4/testing.html) 



