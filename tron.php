<?php

//
// TRON file
//

function tron_writeFile($filename, $data) {
    file_put_contents($filename, $data . "\n", FILE_APPEND);
} 

function tron_сonvert($str) {
    mb_internal_encoding("UTF-8");
    global $event;
    if (BOT_MESSAGE_ATTACHMENT_TYPE == 'fwd_messages' && BOT_MESSAGE_EMPTY) $str_a = $event['object']['message']['fwd_messages'][0]['text'];
    else $str_a = $str;
    $str_up = str_split(mb_convert_case($str_a, MB_CASE_LOWER, "UTF-8"));
    $str_up_dupl = $str_up;
    $unedit_str = explode(" ", $str_a);
    for ($i = sizeof($str_up_dupl) - 1; $i >= 0; $i--) {
        if (in_array($str_up_dupl[$i], str_split(",./';:\"\\<>[]{}!@#$%^&*()_+№-=~`|?"))) array_splice($str_up, $i, 1);
        elseif ($str_up_dupl[$i] == "\n") $str_up[$i] = " ";
    } $result1 = explode(" ", implode("", $str_up));
    $result = [];
    foreach ($result1 as $piece) if ($piece !== "" && !startsWith($piece, "club165656295")) array_push($result, $piece);
    return [$result, $unedit_str];
}

function tron_command($command) {
    global $event;
    if (BOT_MESSAGE_ATTACHMENT_TYPE == 'fwd_messages' && BOT_MESSAGE_EMPTY) $msg = $event['object']['message']['fwd_messages'][0]['text'];
    else $msg = BOT_RECIEVED_MESSAGE;
    $flag = false;
    if (!startsWith($msg, "/")) return $flag;
    else {
        $msgArray = explode(" ", $msg);
        if ($msgArray[0] == "/".$command) $flag = true;
    } return $flag;
}

// A function for checking if a word starts with a certain string
function startsWith($string, $startString) { return (substr($string, 0, strlen($startString)) === $startString); }

function tron_in_array($string, $array) {
    $local = false;
    if (mb_strlen($string) > 1) $new_string = mb_substr($string, 0, mb_strlen($string) - 1);
    else $new_string = $string;
    foreach ($array as $element) if (startsWith($element, $new_string) && (mb_strlen($element) - mb_strlen($new_string) < 3)) $local = true;
    return $local;
}

function tron_array_search($string, $array) {
    $pose = false;
    if (mb_strlen($string) > 3) $new_string = mb_substr($string, 0, mb_strlen($string) - 1);
    else $new_string = $string;
    for ($i = 0; $i < count($array); $i++) if (startsWith($array[$i], $new_string)) $pose = $i;
    return $pose;
}

// A function for checking whether the message contains a certain word 
function has($key, $words) {
    global $message, $source_message;
    $trigger = true;
    $new_words = [];
    foreach ($words as $word) $new_words[] = mb_convert_case($word, MB_CASE_LOWER, "UTF-8");
    switch ($key) {
        // Returns true if each word is present in the array and the order of words is observed
        case "and_old":
            foreach ($new_words as $word) if (!in_array($word, $message)) $trigger = false;
            if ($trigger) for ($x = count($new_words) - 1; $x > 0; $x--) if (array_search($new_words[$x], $message) < array_search($new_words[$x - 1], $message)) $trigger = false;
        break;

        // Same as the last one but it uses «TRON» algorithms for searching words
        case "and":
            foreach ($new_words as $word) {
                if (mb_strlen($word) > 4) {
                    if (!tron_in_array($word, $message)) $trigger = false;
                } else if (!in_array($word, $message)) $trigger = false;
            } if ($trigger) for ($x = count($new_words) - 1; $x > 0; $x--) if (tron_array_search($new_words[$x], $message) < tron_array_search($new_words[$x - 1], $message)) $trigger = false;
        break;

        // Same as the last one but correct order is not required
        case "and_uo":
            foreach ($new_words as $word) {
                if (mb_strlen($word) > 4) {
                    if (!tron_in_array($word, $message)) $trigger = false;
                } else if (!in_array($word, $message)) $trigger = false;
            }
        break;

        // Returns true if at least one word is present in the array
        case "or":
            $trigger = false;
            foreach ($new_words as $word) {
                if (mb_strlen($word) > 4) {
                    if (tron_in_array($word, $message)) $trigger = true;
                } else if (in_array($word, $message)) $trigger = true;
            }
        break;

        // Returns true only if the requested word is the only one in the array
        case "only":
            $trigger = false;
            if (in_array($words[0], $message) && count($message) == 1) $trigger = true;
        break;

        // Returns true if requested word(s) is(are) not present in the array
        case "not":
            foreach ($new_words as $word) if (in_array($word, $message)) $trigger = false;
        break;

        // Returns true if requested word(s) is(are) present in the secondary (defect) array
        case "defect":
            foreach ($words as $word) if (!in_array($word, $source_message)) $trigger = false;
        break;

        // If key is invalid, return false then
        default:
            $trigger = false;
        break;
    } return $trigger;
}

// A function that returns a certain amount of randomly chosen elements from given array
function getr_few($arr, $amount) {
    $numbers = [];
    $result = [];
    for ($i = 0; $i < $amount; $i++) {
        $y = rand(0, sizeof($arr) - 1);
        if (!in_array($y, $numbers)) array_push($numbers, $y);
        else {
            if ($y < sizeof($arr) / 2) $y = rand(0, $y);
            else $y = rand($y, sizeof($arr) - 1);
            if (!in_array($y, $numbers)) array_push($numbers, $y);
            else {
                $y = rand(0, sizeof($arr) - 1);
                if (!in_array($y, $numbers)) array_push($numbers, $y);
            }
        } array_push($result, $arr[$numbers[$i]]);
    } if ($amount != 1) return $result;
    else return $result[0];
}

function tron_send($text = "", $attachment = "", $keyboard = "") {
    global $unknownMsgReply;
    if (!mb_strlen($text)) _vkApi_call('messages.send', array(
        'message'  => getr_few([$unknownMsgReply['default'], $unknownMsgReply['default2'], $unknownMsgReply['default3'], $unknownMsgReply['default4']], 1),
        'random_id' => rand(-2147483648, 2147483647),
        'attachment' => $attachment,
        'access_token' => BOT_ACCESS_TOKEN,
        'peer_id' => PEER_ID,
        'keyboard' => $keyboard
    ));
    else _vkApi_call('messages.send', array(
        'message'  => $text,
        'random_id' => rand(-2147483648, 2147483647),
        'attachment' => $attachment,
        'access_token' => BOT_ACCESS_TOKEN,
        'peer_id' => PEER_ID,
        'keyboard' => $keyboard
    ));
    if (in_array($text, $unknownMsgReply)) {
        sleep(1);
        if (rand(1, 2) == 2) _vkApi_call('messages.send', array(
                'message'  => "Чтобы узнать, на какие вопросы я могу ответить, нажмите на кнопку:",
                'random_id' => rand(-2147483648, 2147483647),
                'attachment' => $attachment,
                'access_token' => BOT_ACCESS_TOKEN,
                'peer_id' => PEER_ID,
                'keyboard' => getKeyboard("help")
            ));
    }
}

?>