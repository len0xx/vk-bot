# vk-bot-logic
В этом файле описаны принципы обработки поступившего сообщения для определения намерений пользователя. Другими словами, здесь будут описаны алгоритмы преобразования текстового сообщения в массив, состоящий из слов, который будет более понятен нашему боту.

**Например:**
Сообщения пользователя `Привет, Спейси, что такое чёрная дыра?` после обработки превращается в `['привет', 'спейси', 'что', 'такое', 'чёрная', 'дыра']`.

Обрабатывать такое сообщение будет гораздо проще так как в нём нет лишних символов (таких как точка, запятая, вопросительный знак и т.д.). Также в обработанном сообщении все слова приведены в нижний регистр, что значительно повышает вероятность того, что бот поймёт, о чём его спросил пользователь.