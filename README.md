# cloud_storage

PHP Skillbox
Финальная работа
Тема: разработка облачного хранилища

Веб-приложение, позволяющее хранить файлы на удалённом сервере,
управлять структурой папок и делиться файлами с другими пользователями.
Web-сервер Apache на Linux, настроен в конфигурационном файле и дополнен настройками из .htaccess.
Реализована единая точка входа и ограничение загрузки файлов.
После добавления пустой базы данных, таблицы создаются автоматически при первом посещении любого из маршрутов.
Для наполнения таблиц данными бд используется подключение к стороннемму сервису.
Для хранения файлов конфигурации используется отдельный файл .env, который обрабатывается библиотекой dotenv.
Подключение к БД осуществляется с помощью паттерна Singleton.
Маршрутизация ограничена роутами из файла routes.php и выдает ошибку 404 в случае если пользователь выходит за границы допустимых маршрутов.
Классы Request и Response обрабатывают запросы и ответы соответственно.
Классы обрабатываются автозагрузчиком.
С помощью шаблона MVC разделяются модели, контроллеры и представления для удобного изменения и масштабирования приложения.

Используемые технологии в проекте
MVC, REST API, MySQL, РНР, Composer, SOLID, Singleton, Registry, REST, Postman, PHPStorm, Ubuntu, Apache, Git.