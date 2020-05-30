# vk-bot
Я не нашёл хорошего туториала по созданию полноценного и полезного бота для ВКонтакте, поэтому создал его сам
Рабочий пример можете посмотреть здесь: [SPACE Бот](https://vk.me/lnx.space)
Вся подробная документация по использованию VK API для ботов [здесь](https://vk.com/dev/bots_docs)

## Файлы
* config.php — функции VK API
* data.php — данные для подключения к VK API и другие константы
* handler.php — главный файл, который обрабатывает поступивший запрос
* tron.php — функции для обработки текста
* algorithms.php — файл с выполняемыми функциями
* cases.php — файл, обрабатывающий полученное сообщение

**Важно: Перед использованием бота ознакомьтесь с правилами: [Правила использования бота](https://vk.com/dev/bot_rules)**

## 1. Создание токена
Чтобы начать работу с чатботом ВКонтакте, Вам нужно создать специальный токен сообщества
Инструкция по созданию вместе со всей остальной нужной информацией есть [здесь](https://vk.com/dev/bots_docs)

После того, как Вы создали токен, его вместе с секретным ключом и строкой для подтверждения нужно записать в файл data.php
Далее Вам нужно подтвердить сервер Callback API, на котором будут храниться файлы бота. Чтобы это сделать перейдите в Настройки сообщества -> Работа с API -> Callback API -> Настройки сервера (Можно просто перейти по ссылке ***https://vk.com/{Адрес Вашего сообщества}?act=api***). Там, после того, как Вы создадите сервер, будет поле, в которое нужно вставить адрес на файл **handler.php** на Вашем сервере.