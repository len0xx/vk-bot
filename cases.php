<?php

//
// "Cases" file 
// this file searches for certain phrases in users' message and decides what reply to send him
//

// Default phrases
$defaults = ["Я не понимаю Вас", "К сожалению, я не могу Вас понять", "Кажется, я не понимаю, о чём Вы", "Кажется, я не понимаю, о чём Вы 🤔"];
$greets = ["Привет, ".BOT_USER_FIRST_NAME."! 👋", "Здравствуй, ".BOT_USER_FIRST_NAME."!"];
$howAreYou = ["У меня всё прекрасно! 🌝", "У меня всё прекрасно!", "Всё хорошо, спасибо! 🙂", "Всё хорошо, спасибо!", "Хорошо!", "Всё просто отлично!", "У меня всё замечательно! 🙂", "У меня всё замечательно!", "Всё как в сказке 🌟", "Лучше всех, не поверите! 🙃", "Лучше всех, не поверите!", "Как всегда, то есть хорошо", "Отлично! Чего и Вам желаю"];
$thanks = ["Не за что, обращайтесь ещё!", "Да не за что, я всегда рад помочь! 😉", "Всегда пожалуйста! 😉"];
$iknowlot = [
    "Я могу ответить на многие вопросы по астрономии",
    "Я могу рассказать Вам много интересного про космос",
    "Я знаю множество интересных вещей про дальние уголки космоса"
];

// An array with for replying to an un
$unknownMsgReply = [
    "default" => "Я не понимаю Вас",
    "default2" => "К сожалению, я не могу Вас понять",
    "default3" => "Кажется, я не понимаю, о чём Вы",
    "default4" => "Кажется, я не понимаю, о чём Вы 🤔",
    "photo" => "Я ещё не научился распознавать, что находится на фотографиях, поэтому не могу Вам ничего ответить",
    "video" => "Пока что я не могу Вам ничего ответить на видео сообщения",
    "doc" => "К сожалению, сейчас я не могу распознавать, что содержится в документах",
    "graffiti" => "К сожалению, я ещё не умею распознавать граффити и Memoji и не знаю, как ответить Вам на это сообщение",
    "market" => "Я не могу ответить на такое сообщение",
    "audio_message" => "Надо признаться, пока что я не способен распознавать Вашу речь и не могу ответить Вам на это сообщение",
    "wall" => "К сожалению, я не могу сейчас ничего ответить на этот пост",
    "geo" => "Надо признаться, я не могу знать, что находится в этой геолокации, соответственно и ответить ничего не могу",
    "podcast" => "Подкасты — это, определенно, очень интересный формат, однако я, к сожалению, пока что не научился их слушать",
    "audio" => "Увы, но в данный момент я не умею слушать аудиозаписи",
    "link" => "К моему сожалению, пока что я не умею понимать, что находится в ссылках",
    "sticker" => "Кажется, я не понимаю, что значит этот стикер 🤔"
];

// Processing all the possible cases and choosing the valid reply variant
// incase of undefined case, returning the default reply

switch (BOT_MESSAGE_ATTACHMENT_TYPE) {
    case "photo":
        tron_send($unknownMsgReply["photo"]);
    break;

    case "video":
        tron_send($unknownMsgReply["video"]);
    break;

    case "doc":
        tron_send($unknownMsgReply["doc"]);
    break;

    case "graffiti":
        tron_send($unknownMsgReply["graffiti"]);
    break;

    case "market":
        tron_send($unknownMsgReply["market"]);
    break;

    case "audio_message":
        tron_send($unknownMsgReply["audio_message"]);
    break;

    case "wall":
        tron_send($unknownMsgReply["wall"]);
    break;

    case "geo":
        tron_send($unknownMsgReply["geo"]);
    break;

    case "podcast":
        tron_send($unknownMsgReply["podcast"]);
    break;

    case "audio":
        tron_send($unknownMsgReply["audio"]);
    break;

    case "link":
        tron_send($unknownMsgReply["link"]);
    break;

    case "sticker":
        switch (BOT_STICKER_ID) {
            case "271_8748":
            case "325_10699":
                tron_send("Алоха, ".BOT_USER_FIRST_NAME."!");
            break;

            case "500_17616":
            case "522_18463":
            case "151_4710":
            case "111_3462":
            case "94_3003":
            case "500_17616":
            case "546_19442":
            case "1_21":
                tron_send(getr_few($greets, 1));
            break;

            case "192_6164":
            case "271_8755":
                tron_send(getr_few($thanks, 1));
            break;

            default:
                tron_send($unknownMsgReply["sticker"]);
            break;
        }
    break;

    default:
    if (!BOT_MESSAGE_EMPTY || BOT_MESSAGE_ATTACHMENT_TYPE == 'fwd_messages' && count($event['object']['message']['fwd_messages']) == 1) {
        if (has("or", ["привет", "здравствуйте", "ку"])) tron_send(getr_few($greets, 1));
        elseif (has("and_uo", ["как", "тебя", "зовут"])) tron_send("Меня зовут Трон");
        elseif (has("and", ["какая", "сейчас", "дата"]) || has("and", ["какая", "сегодня", "дата"])) tron_send("Сегодня " . curDate("day"));
        elseif (has("and", ["сколько", "время"]) || has("and", ["сколько", "времени"])) tron_send("Сейчас " . curDate("time"));
        elseif (has("and", ["что", "ты", "умеешь"]) || has("and", ["что", "ты", "знаешь"])) tron_send("Пока что я умею немного — всего лишь отвечать на некоторые вопросы, например:", "", inlineKeyboard(["Сколько сейчас времени?", "Какая сегодня дата?"]));
        elseif (has("or", ["start", "начать"])) tron_send("Добро пожаловать, воспользуйся меню", "", getKeyboard("menu"));
        elseif (has("and_uo", ["меню"]) && has("not", ["уменьшить", "увеличить"])) tron_send("Меню открыто:", "", getKeyboard("menu"));
        elseif (has("and", ["уменьшить", "меню"])) tron_send("Меню было уменьшено:", "", getKeyboard("small_menu"));
        elseif (has("and", ["увеличить", "меню"])) tron_send("Полное меню:", "", getKeyboard("menu"));
        else {
            tron_send(getr_few($defaults, 1));
        }
    } else {
        tron_send(getr_few($defaults, 1));
    }
    break;
}

?>