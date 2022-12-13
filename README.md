##### (Optionnel) Avoir accès au container Docker depuis PhpStorm directement
https://www.jetbrains.com/help/phpstorm/configuring-remote-interpreters.html select 'Docker compose'

##### (Optionnel) Pour les utilisateurs de Windows
Docker peut être lent sur Windows, il est recommandé de créer un fichier `.wslconfig` à la racine de votre dossier `C:\Users\userName` et de mettre ceci à l'intérieur :
```
[wsl2] 
memory=4GB      #Limits VM memory in WSL 2 to 4GB 
processors=2    #Makes the WSL 2 VM use two virtual processors
```
Pour plus d'information sur la configuration, voici un lien vers la doc https://docs.microsoft.com/en-us/windows/wsl/wsl-config

PS: Même avec ces modifications, Docker reste lent sur Windows, pour aller plus loin je vous recommande de suivre ce guide [slow-docker-on-windows-wsl2-fast-and-easy-fix-to-improve-performance](https://www.createit.com/blog/slow-docker-on-windows-wsl2-fast-and-easy-fix-to-improve-performance/)

##### Initialiser le project
___
```
docker-compose up -d --build
docker-compose exec php composer install
docker-compose exec php yarn install
docker-compose exec php yarn build
```

##### Lancer le project
___
`docker-compose up -d`

##### Arréter le projet
___
`docker-compose down`

##### Se connecter au terminal du serveur php
___
`docker-compose exec php bash`

### Liens utiles
* Server web http://localhost:8101
* phpmyadmin http://localhost:8102
* Debugger avec Phpstorm (Xdebug) https://www.jetbrains.com/help/phpstorm/debugging-with-phpstorm-ultimate-guide.html#quick-start

