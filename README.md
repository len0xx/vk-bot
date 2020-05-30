# vk-bot
Я не нашёл хорошего туториала по созданию полноценного и полезного бота для ВКонтакте, поэтому создал его сам.

Рабочий пример можете посмотреть здесь: ***[Бот Спейси](https://vk.me/lnx.space)***.

Вся подробная документация по использованию VK API для ботов [здесь](https://vk.com/dev/bots_docs).

## Файлы
* [config.php](https://github.com/len0xx/vk-bot/blob/master/config.php) — функции VK API
* [data.php](https://github.com/len0xx/vk-bot/blob/master/data.php) — данные для подключения к Callback API и другие константы
* [handler.php](https://github.com/len0xx/vk-bot/blob/master/handler.php) — главный файл, который обрабатывает поступивший запрос
* [tron.php](https://github.com/len0xx/vk-bot/blob/master/tron.php) — функции для обработки текста
* [algorithms.php](https://github.com/len0xx/vk-bot/blob/master/algorithms.php) — файл с выполняемыми функциями
* [cases.php](https://github.com/len0xx/vk-bot/blob/master/cases.php) — алгоритм, обрабатывающий полученное сообщение

**Важно: Перед использованием бота ознакомьтесь с правилами ВКонтакте: [Правила использования бота](https://vk.com/dev/bot_rules)**

## 1. Создание токена
Чтобы начать работу с чатботом ВКонтакте, Вам нужно создать специальный токен сообщества.
Инструкция по созданию вместе со всей остальной нужной информацией есть [здесь](https://vk.com/dev/bots_docs)

После того, как Вы создали токен, его вместе с секретным ключом и строкой для подтверждения нужно записать в файл **data.php**.
Далее Вам нужно подтвердить сервер Callback API, на котором будут храниться файлы бота. Чтобы это сделать перейдите в Настройки сообщества -> Работа с API -> Callback API -> Настройки сервера. Там, после того, как Вы создадите сервер, будет поле, в которое нужно вставить адрес на файл **handler.php** на Вашем сервере.

## 2. Алгоритм обработки текста
Описание алгоритма, с помощью которого бот преобразует полученное сообщение, я решил выделить в отдельный файл, который Вы можете найти [здесь](https://github.com/len0xx/vk-bot/blob/master/logic.md)

## 3. Текстовые команды
Все текстовые команды записываются в файл [cases.php](https://github.com/len0xx/vk-bot/blob/master/cases.php) и определяются с помощью функции ***has()***, которая ищет определенные слова в массиве ***$message***, в котором хранятся слова из сообщения, которое отправил пользователь.

Например, если пользователь отправил сообщение `Что ты умеешь?`, то массив ***$message*** будет выглядеть следующим образом — `['что', 'ты', 'умеешь']`. Подробнее принцип преобразования сообщения в массив описан в [этом файле](https://github.com/len0xx/vk-bot/blob/master/logic.md).
### Принцип работы функции has()
Функция has() описана в файле [tron.php](https://github.com/len0xx/vk-bot/blob/master/tron.php). Эта функция предназначена для поиска определенных слов в сообщении и имеет два аргумента — ***$key*** и ***$words***. В качестве аргумента ***$words***, очевидно, передаётся массив слов, которые нужно найти. И в результате выполнения функции has() на выход выдается значение типа булево — ***true*** или ***false*** (в зависимости от того, были найдены искомые слова в массиве ***$message***).

Первый аргумент ***$key*** описывает метод поиска указанных слов в сообщении. Всего в функции описано 7 методов поиска. 

* Первый метод `or` возвращает значение ***true*** в случае, если хотя бы одно из перечисленных слов в ***$words*** присутствует в массиве ***$message***.

* Второй метод `and_old` возвращает значение ***true*** в случае, если в массиве ***$message*** присутствуют все слова в ***$words*** и при этом соблюдается их порядок (такой же, как в ***$words***).

* Третий метод `and` работает по такому же принципу, что и предыдущий метод, с одним лишь отличием. Отличие это в том, что метод ищет не в точности одинаковые слова. Этот метод укорачивает искомое слово (если его длина > 4) на один последний символ. Например: функция `has("and", ["блокноты"])` вернёт ***true*** не только если в массиве будет слово `блокноты`, но и если там будут слова `блокноте`, `блокнота`, `блокноту` или любые другие вариации этого слова с такой же длиной. Сделано это для того, чтобы игнорировать окончания в русском языке. * Таким образом повышается вероятность того, что пользователь получит того, что хотел. **Важно то, что этот метод так же учитывает порядок слов в сообщении, как и предыдущий**.

* Четвёртый метод `only` возвращает значение ***true*** только в одном единственном случае — если массив ***$message*** содержит только одно слово — слово, переданное в качестве аргумента ***$words***. **Важно то, что это слово передаётся так же в качестве массива. Например — `has("only", ["меню"])`**.

* Пятый метод `not` возвращает значение ***true*** в случае, если в массиве ***$message*** не присутствует ни одного слова из тех, что были переданы в  ***$words***.

* Шестой метод `and_uo` работает по такому же принципу, что и метод `and`, но в этом методе порядок слов в сообщении не учитывается.

Последний, седьмой метод, — несколько особенный метод, поэтому я предпочёл описать его в отдельном файле [logic.md](https://github.com/len0xx/vk-bot/blob/master/logic.md).

Примеры работы функции ***has()***:
```php
BOT_RECIEVED_MESSAGE = "Привет, бот!";
$message = ["привет", "бот"];

has("or", ["привет", "здравствуй", "здравствуйте"]); // Вернёт true


BOT_RECIEVED_MESSAGE = "Обновить меню";
$message = ["обновить", "меню"];

(has("or", ["меню"]) && has("not", ["обновить"])); // Вернёт false т.к. присутствует слово "обновить"
// Этот же код можно описать следующим образом:
has("only", ["меню"]) // Вернёт false т.к. в массиве $message два слова, то есть искомое слово — не единственное


BOT_RECIEVED_MESSAGE = "Покажи картинку космоса";
$message = ["покажи", "картинку", "космоса"];

has("and", ["покажи", "картинки"]); // Вернёт true т.к. метод "and" игнорирует окончание слова
// Если провернуть такую операцию, используя метод "and_old", то будет другой результат:
has("and_old", ["покажи", "картинки"]); // Вернёт false т.к. "картинки" и "картинку" для этого метода — разные слова


BOT_RECIEVED_MESSAGE = "А тебя как зовут?";
$message = ["а", "тебя", "как", "зовут"];

has("and_uo", ["как", "тебя", "зовут"]); // Вернёт true т.к. в этом методе соблюдения порядка слов необязательно
// Однако если использовать в этом случае метод "and", то результат будет отличаться
has("and", ["как", "тебя", "зовут"]); // Вернёт false т.к. здесь обязательно соблюдение порядка
```

## 4. Отправка сообщений
После того, как мы определили, что же нам прислал пользователь, нужно отправить ответное сообщение.

Отправка сообщений выполняется с помощью функции `tron_send()`. У этой функции 3 аргумента — текстовое сообщение, прикрепляемые вложения и клавиатура. При этом, два последних аргумента необязательные.

Пример использования `tron_send()` в связке с `has()`:
```php
if (has("only", ["меню"])) tron_send("Меню открыто:", "", getKeyboard("menu"));
```
По поводу функции `getKeyboard()` читайте [ниже](https://github.com/len0xx/vk-bot#5-%D0%B8%D1%81%D0%BF%D0%BE%D0%BB%D1%8C%D0%B7%D0%BE%D0%B2%D0%B0%D0%BD%D0%B8%D0%B5-%D0%BA%D0%BB%D0%B0%D0%B2%D0%B8%D0%B0%D1%82%D1%83%D1%80%D1%8B)

## 5. Использование клавиатуры
Чтобы облегчить использование бота, стоит использовать клавиатуры ВКонтакте. Если сказать проще, то клавиатуры — это кнопки, которые мы видим внизу открытого диалога с ботом. (Но также клавиатуры могут быть inline, об этом ниже).

Я написал две функции для создания клавиатур: ***getKeyboard($name)*** и ***inlineKeyboard($buttons = [])***. Их отличие в том, что первая возвращает уже созданную клавиатуру с названием ***$name***, а вторая создает inline клавиатуру, используя текст кнопок, описанный в массиве аргумента ***$buttons***.

**Подробнее про клавиатуры ВКонтакте можно почитать [здесь](https://vk.com/dev/bots_docs_3?f=4.%20Bot%20keyboards)**

## 6. Итоги
В итоге мы получаем бота с огромным функционалом. Этот бот лучше обрабатывает полученное от пользователя сообщение, а соответственно КПД такого бота гораздо выше по сравнению с теми, которые используют более простые способы определения текстовых команд. Используя этот код вы можете распознавать различные типы вложений в сообщениях пользователя, пользоваться функционалом клавиатур ВКонтакте, делать рассылки своим пользователям и, в принципе, создавать любого бота, какого захотите.

В файлах этого репозитория лежит уже готовый бот, у которого есть меню и который, в качестве примера, может отвечать на некоторые сообщения, например:
* Сколько сейчас времени?
* Какая сегодня дата?
* Как тебя зовут?

Найти рабочего бота можно здесь: [T R Ø N](https://vk.me/lnx.tron)

А здесь улучшенный пример с гораздо большим функционалом: [S P Λ C Ξ](https://vk.me/lnx.space)
