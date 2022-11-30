[Demo](https://eep2004aggregator.000webhostapp.com/)

**/config.php** - Настройки приложения

**/web/images** - Директория должна быть с правами на запись

```
Создать веб-приложение "Агрегатор отзывов о товаре" без использования PHP фрейморка.

Приложение состоит из 3 страниц:

1. Главная страница содержит информацию о всех товарах в виде таблицы и ссылкой на страницу добавления нового товара.
1.1. В таблице содержится такая информация:
- название товара (нужно сделать ссылкой на страницу отзывов о товаре пункт-3);
- маленькое изображение;
- дата добавления (генерировать автоматически при записи в БД);
- имя добавившего товар;
- количество отзывов;
1.2. Необходимо реализовать сортировку по всем возможным полям.

2. Страница добавления товара состоит из таких полей:
- название товара;
- изображение товара(возможность вставить ссылку на изображение);
- средняя цена (ее не нужно никак вычислять, это просто input);
- дата добавления товара (генерировать автоматически при записи в БД);
- имя добавившего товар;

3. Страница отзывов о товаре состоит из вывода агрегированных отзывов и возможностью добавления нового.
3.1. Добавление нового отзыва состоит из таких полей:
- имя добавившего;
- оценка(выбор от 1 до 10),
- комментарий,
- дата добавления (генерировать автоматически при записи в БД),
3.2. Вывод агрегированных отзывов должен выглядеть так:
- название товара;
- большая картинка товара;
- средняя оценка (должна вычисляться на основании всех оценок этого товара)
- таблица всех отзывов(имя оставившего отзыв, оценка, комментарий, дата)

4. Язык исполнения - PHP >= 7, БД - MySQL. Дедлайн - 3 рабочих дня.
```
