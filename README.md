[![license](https://img.shields.io/github/license/mashape/apistatus.svg)](https://github.com/Sylvestrecao/blogSymfony)
# blogSymfony
This is my blog using Symfony 3. I used this project to learn the framework. A big thank to David Miller for letting us use his bootstrap template from "startbootstrap.com".
For this project, I used the "Clean Blog" template. Go check it at this link : https://github.com/BlackrockDigital/startbootstrap-clean-blog

Installation
---------
```bash
composer install
php bin/console doctrine:database:create
php bin/console doctrine:schema:update --force
php bin/console doctrine:fixtures:load
php bin/console server:run
```