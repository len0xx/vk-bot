<?php

//
// "Algorithms" file
// this file contains all the algorithms the program works with
//

// A function that converts a string message into an array of lowercase words ..
// .. and gets rid of all the extra symbols
$message = tron_сonvert(BOT_RECIEVED_MESSAGE)[0];
$source_message = tron_сonvert(BOT_RECIEVED_MESSAGE)[1]; // Is required for a defect comparison mode

// A function for determining the current date
function curDate($setting) {
    function transformDate($num) {
        if ($num < 10) return "0".$num;
        else return $num;
    };
    $serverDate = getdate();
    $today = getdate($serverDate[0]);
    $monthRu = ["Января", "Февраля", "Марта", "Апреля", "Мая", "Июня", "Июля", "Августа", "Сентября", "Октября", "Ноября", "Декабя"];
    switch ($setting) {
        case "general":
            return transformDate($today['hours']) . ":" . transformDate($today['minutes']) . ":" . transformDate($today['seconds']) . " — " . transformDate($today['mday']) . "/" . transformDate($today['mon']) . "/" . transformDate($today['year']);
        break;
        
        case "day":
            return $today['mday'] . " " . $monthRu[$today['mon'] - 1] . " " . $today['year'] . " года";
        break;
        
        case "time":
            return transformDate($today['hours']) . ":" . transformDate($today['minutes']);
        break;
    }
}

function getKeyboard($mode, $rel = array()) {
    $buttons = [];
    switch ($mode) {
        case "menu":
            array_push($buttons, [buttonText("Что ты умеешь?", 'blue', ['command' => 'ask'])]);
            array_push($buttons, [buttonText("Обновить меню", 'white', ['command' => 'menu']), buttonText("Уменьшить меню", 'white', ['command' => 'menu'])]);
            array_push($buttons, [buttonLink("Узнать больше о космосе", "https://vk.me/lnx.space", ['command' => 'open_space'])]);
            array_push($buttons, [buttonLink("Открыть на GitHub", "https://github.com/len0xx/vk-bot", ['command' => 'open_link'])]);
            return generateKeyboard($buttons);
        break;

        case "small_menu":
            array_push($buttons, [buttonText("Что ты умеешь?", 'blue', ['command' => 'ask'])]);
            array_push($buttons, [buttonText("Увеличить меню", 'white', ['command' => 'menu'])]);
            return generateKeyboard($buttons);
        break;

        case "help":
            array_push($buttons, [buttonText("Что ты умеешь?", 'white', ['command' => 'ask'])]);
            return generateKeyboard($buttons, true);
        break;

        default:
            return "";
        break;
    }
}

function inlineKeyboard($text = "Error: Empty button", $color = 'white') {
    $buttons = [];
    if (is_array($text)) {
        if (count($text) > 0 && count($text) < 6) {
            foreach ($text as $t) {
                if (mb_strlen($t) < 36) array_push($buttons, [buttonText($t, $color, ['command' => 'ask'])]);
                else array_push($buttons, [buttonText("Error: Long text", $color, ['command' => 'error'])]);
            }
        } else array_push($buttons, [buttonText("Error: Too many buttons", $color, ['command' => 'error'])]);
    } else {
        if (mb_strlen($text) < 36) array_push($buttons, [buttonText($text, $color, ['command' => 'ask'])]);
        else array_push($buttons, [buttonText("Error: Long text", $color, ['command' => 'error'])]);
    } return generateKeyboard($buttons, true, false);
}

function sendToID($id, $text, $attachment = "", $keyboard = "") {
    _vkApi_call('messages.send', array(
        'message'  => $text,
        'user_id' => $id,
        'random_id' => rand(-2147483648, 2147483647),
        'attachment' => $attachment,
        'access_token' => BOT_ACCESS_TOKEN,
        'keyboard' => $keyboard
    ));
}

?>