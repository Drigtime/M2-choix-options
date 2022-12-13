##### [IMPORTANT] Pour les utilisateurs de Windows
Pour eviter d'avoir un projet qui tourne à 2 à l'heure, il est important de cloner le projet directement dans votre wsl (ubuntu, debian) a l'endroit que vous voulez
```
wsl -d ubuntu
cd ~
git clone https://github.com/Drigtime/M2-choix-options.git
cd M2-choix-options
sudo chmod 777 project/var/ -R
```

#### Initialiser le project
```
docker-compose up -d --build
docker-compose exec php composer install
docker-compose exec php yarn install
docker-compose exec php yarn build
```
En une commande :
```
docker-compose up -d --build && docker-compose exec php composer install && docker-compose exec php yarn install && docker-compose exec php yarn build
```

#### Lancer le project
```
docker-compose up -d
```

#### Arréter le projet
```
docker-compose down
```

#### Se connecter au terminal du serveur php
```
docker-compose exec php bash
```

### Liens utiles
* Server web http://localhost:8101
* phpmyadmin http://localhost:8102
* Debugger avec Phpstorm (Xdebug) https://www.jetbrains.com/help/phpstorm/debugging-with-phpstorm-ultimate-guide.html#quick-start

Avoir accès au container Docker depuis PhpStorm directement
https://www.jetbrains.com/help/phpstorm/configuring-remote-interpreters.html select 'Docker compose'

