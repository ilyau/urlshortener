История пользователя.

Я, как пользователь, могу вставить любую ссылку в форму и получить короткую.
Я, как пользователь, могу указать в браузере короткую ссылку и попасть на адрес по соответсвующей длинной ссылке.
Я, как пользователь, открываю главную страницу и вижу список всех ссылок, которые я “укоротил” с помощью формы.

Системные требования:

- PHP 5.5
- MariaDb 5.5

Перед запуском выполнить команды (или запустить файл update.example.bat):

Создание db (или взять из /db.sql):

php app/console doctrine:database:create
php app/console doctrine:schema:update --force

then

rm -rf ./app/cache/prod/*
rm -rf ./app/cache/dev/*
php app/console assetic:dump
php app/console assets:install --symlink web
composer install

и запустить тесты:

windows: bin\phpunit.bat -c app 
unix:    bin/phpunit -c app


Особенности:

1. Задача реализована на фреймворке Symfony2. В результате мы имеем знакомую структуру расположения моделей, видов и контроллеров, мы можем использовать сервис в другом проекте на Symfony2, при выполнении задания основной акцент был уделен бизнес-логике.

2. Стили описаны в LESS и компилируются с помощью leafo/lessphp, подключенном через Composer

3. В проекте нет клиентского кода на JS, что позволяет оценивать только бекенд-сторону задания

4. Сообщения об ошибках передаются в сессии через $session->getFlashBag()

5. Введенная ссылка проверяется на валидность UrlConstraint

6. Основной код находится в:
\src\Acme\UrlshortenerBundle\Controller\DefaultController.php
\src\Acme\UrlshortenerBundle\Entity\Url.php
\src\Acme\UrlshortenerBundle\Resources\views\Default\index.html.twig
\src\Acme\UrlshortenerBundle\Service\UrlshortenerService.php

7. Автотесты находятся в \src\Acme\UrlshortenerBundle\Tests
Один функциональный и один модульный.

Тесты используют НЕ fixtures, а реальную db!

для запуска в папке с проектом набрать:

windows: bin\phpunit.bat -c app 
unix:    bin/phpunit -c app

8. Табы 4 пробела, Конец строки Unix

9. Можно сделать пагинацию истории, но я не делал 
