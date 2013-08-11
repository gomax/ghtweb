[GHTWEB](http://ghtweb.ru/) - CMS для Java Lineage2 серверов
==================================================

Установка
---------------------------
1. Скачать [CMS](https://github.com/ghtweb/ghtweb/archive/master.zip)
2. Разархивировать в папку
3. Настроить файл подключения к БД **application\config\database.php**
4. Ввести ключ в файле **application\config\config.php** на **248** строке (будет Ваш уникальный ключ)
5. Залить дамп **ghtweb.sql** (находится в архиве который качали выше) в БД


### Доступ в Админку:
- Нужно авторизироваться в Мастер аккаунте **/login**:
- Логин: **admin**
- Пароль: **123456**
- Затем в URL дописать: **/backend**



Обновлении с версии 4.0.10
---------------------------

1. Скачать [CMS](https://github.com/ghtweb/ghtweb/archive/master.zip)
2. Разархивировать в папку
3. Настроить файл подключения к БД **application\config\database.php**
4. Удалить таблицу **all_items** и залить [эту](https://www.dropbox.com/s/6okd2wjfekesp1p/ghtweb_all_items.zip) таблицу
5. Открыть сайт, CMS сама обновит БД
