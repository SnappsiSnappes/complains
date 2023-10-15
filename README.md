<details>
<summary>Скриншоты (нажмите, чтобы развернуть)</summary>

![image](https://github.com/SnappsiSnappes/complains/assets/111605401/4963745e-2874-4505-a403-e11e8d4e7e3e)
![image](https://github.com/SnappsiSnappes/complains/assets/111605401/58749dfd-ccd5-4d2f-85a3-89ad9b4229ec)
![image](https://github.com/SnappsiSnappes/complains/assets/111605401/d281070a-d825-4528-9b19-0d8944f1997b)
![image](https://github.com/SnappsiSnappes/complains/assets/111605401/c30b952e-609b-4d6f-9a04-e6b5d27ccb7e)
![image](https://github.com/SnappsiSnappes/complains/assets/111605401/e718722e-b078-4abc-8fae-d9b518d788be)


</details>



# Краткое описание
Веб-приложение, разработанное на чистом PHP. Основная идея заключается в следующем:
- Существуют 3 роли: менеджер, администратор и гость.
- Менеджер загружает жалобу, заполняет форму и прикладывает файлы. При этом он:
  1) Может удалить свою жалобу.
  2) Может комментировать свою жалобу и удалять свой комментарий.
  3) Может просматривать список своих жалоб и опубликованных жалоб.
- Администратор принимает решение о публикации.
- Гость может просмотреть опубликованные жалобы, комментировать их и зарегистрироваться, чтобы стать менеджером.

## Важные детали
1) Существуют две компании, каждая со своим администратором и почтовым ящиком.
2) Администраторы видят только те жалобы, которые относятся к их компании. Разделение происходит на основе данных, предоставленных менеджером (в данном случае, по полю в форме 'Договор С').
3) Администраторы могут отправить текст и файлы из жалобы себе на почту, нажав на кнопку. Почтовый ящик выбирается по тому же принципу, что и разделение жалоб.
4) Администратор видит все списки жалоб, может комментировать жалобы и удалять комментарии гостя.
5) Администратор может отклонить жалобу с указанием причины отклонения. В этом случае пользователь увидит сообщение.

## Возможности
- Весь проект настроен на Docker.
- Используется phpMyAdmin.
- Авторизация реализована с использованием реляционной базы данных.
- Роли с дозированным контролем доступа.
- Хранение файлов взаимодействует с базой данных. Сами файлы хранятся в папке img, а названия файлов хранятся в базе данных.
- Комментарии хранятся в базе данных.
- Frontend разработан на Bootstrap 5.3, backend - на MySQL через PHP PDO.
- Есть переключатель темной темы.
- Реализована пагинация объектов жалоб и комментариев.
- Поиск жалоб по номеру.
- В некоторых местах применяется ajax.

## Запуск
1) Запустите Docker Compose командой `sudo docker-compose up` или просто `docker-compose`.
2) Откройте http://localhost:8077.
   - Войдите в систему с логином 'admin' и паролем 'admin'.
3) phpMyAdmin доступен по адресу http://localhost:8078/.
  - Сервер: db_service_complains
  - root
  - root


## Чтобы отредактировать функционал под себя, нужно изменить следующие файлы:

setter.php
строчки 175,176, 185,186  - изменить названия company1, company2  под свои 2 компании.
От этого файла идёт запись в БД.

navbar.php
строчки 45, 49 - изменить названия навбаров

getter.php
строчки 130/131/133 изменить рабочие почты для отправки Email. 

database.php
76 строчка - нужно указать имя компании, совпадает из файла setter. 
86 строчка - тоже самое



