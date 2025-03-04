# Серверная часть веб-приложения «Крестики-нолики»

## **_Тема: Серверная часть веб-приложения «Крестики-нолики»_**

**Курсовую работу выполнил студент группы ИКБО-20-19 Николаев-Аксенов И. С.**

---

**_Инструменты и технологии:_**

 - HTML5
 - CSS3
 - PHP
 - Jetbrains PHPStorm
 - SQL СУБД

**Нормативный документ: инструкция по организации и проведению курсового проектирования СМКО МИРЭА 7.5.1/04.И.05-18.**

**_Перечень вопросов, подлежащих разработке, и обязательного графического материала:_**

1. Провести анализ предметной области разрабатываемого веб-приложения.
2. Обосновать выбор технологий разработки веб-приложения.
3. Разработать архитектуру веб-приложения на основе выбранного паттерна проектирования.
4. Реализовать слой серверной логики веб-приложения с применением выбранной технологии.
5. Реализовать слой логики базы данных.
6. Разработать слой клиентского представления веб-приложения.
7. Создать презентацию по выполненной курсовой работе.

## Запуск веб-приложения
Веб-приложение использует фреймворк Lavavel, сервер Nginx, СУБД SQL Mariadb.  
В корневом каталоге введите: `docker-compose up -d --build site`  
Для ввода команд для composer, npm, artisan введите:  
* `docker-compose run --rm composer {команда}`
* `docker-compose run --rm npm {команда}`
* `docker-compose run --rm artisan {команда}`
