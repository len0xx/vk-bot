<?php

//
// "Config" file 
// this file contains VKAPI functions
//

// A function that returns the "event" element decoded from JSON
function _callback_getEvent() {
    return json_decode(file_get_contents('php://input'), true);
}

// A function for exiting the script with $data message
function _callback_response($data) {
    exit($data);
}

// Three functions for writing log files
function _log_write($message) {
    $trace = debug_backtrace();
    $function_name = isset($trace[2]) ? $trace[2]['function'] : '-';
    $mark = date("H:i:s") . ' [' . $function_name . ']';
    $log_name = 'log/log_' . date("j-n-Y") . '.txt';
    file_put_contents($log_name, $mark . " : " . $message . "\n", FILE_APPEND);
}

function log_msg($message) {
    if (is_array($message)) $message = json_encode($message);
    _log_write('[INFO] ' . $message);
}

function log_error($message) {
    if (is_array($message)) $message = json_encode($message);
    _log_write('[ERROR] ' . $message);
}

// VKAPI call function
function _vkApi_call($method, $params = array()) {
    $params['v'] = BOT_VKAPI_VERSION;
  
    $query = http_build_query($params);
    $url = VK_API_ENDPOINT.$method;
  
    $curl = curl_init($url);
    // Send a request using POST method
    curl_setopt($curl, CURLOPT_POST, 1);
    curl_setopt($curl, CURLOPT_POSTFIELDS, $query);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    $error = curl_error($curl);
    if ($error) {
            log_error($error);
      throw new Exception("Failed {$method} request");
    }
  
    curl_close($curl);
  
    $response = json_decode($json, true);
    if (!$response || !isset($response['response'])) {
            log_error($json);
      throw new Exception("Invalid response for {$method} request");
    }
  
    return $response['response'];
}

function replaceColor($color) {
    switch ($color) {
        case 'red':
            $color = 'negative';
            break;
        case 'green':
            $color = 'positive';
            break;
        case 'white':
            $color = 'default';
            break;
        case 'blue':
            $color = 'primary';
            break;
    }
    return $color;
}

function buttonLink($text, $link, $payload = null) {
    return ['open_link', $payload, $text, $link];
}

function buttonLocation($payload = null) {
    return ['location', $payload];
}

function buttonPayToGroup($group_id, $amount, $description = null, $data = null, $payload = null) {
    return ['vkpay', $payload, 'pay-to-group', $group_id, $amount, $description, $data];
}

function buttonPayToUser($user_id, $amount, $description = null, $payload = null) {
    return ['vkpay', $payload, 'pay-to-user', $user_id, $amount, $description];
}

function buttonDonateToGroup($group_id, $payload = null) {
    return ['vkpay', $payload, 'transfer-to-group', $group_id];
}

function buttonDonateToUser($user_id, $payload = null) {
    return ['vkpay', $payload, 'transfer-to-user', $user_id];
}

function buttonApp($text, $app_id, $owner_id = null, $hash = null, $payload = null) {
    return ['open_app', $payload, $text, $app_id, $owner_id, $hash];
}

function buttonText($text, $color, $payload = null) {
    return ['text', $payload, $text, $color];
}

// This function creates a keyboard
function generateKeyboard($buttons = [], $inline = False, $one_time = False) {
    $keyboard = [];
    $i = 0;
    foreach ($buttons as $button_str) {
        $j = 0;
        foreach ($button_str as $button) {
            $keyboard[$i][$j]["action"]["type"] = $button[0];
            if ($button[1] != null)
                $keyboard[$i][$j]["action"]["payload"] = json_encode($button[1], JSON_UNESCAPED_UNICODE);
            switch ($button[0]) {
                case 'text': {
                    $color = replaceColor($button[3]);
                    $keyboard[$i][$j]["color"] = $color;
                    $keyboard[$i][$j]["action"]["label"] = $button[2];
                    break;
                }
                case 'vkpay': {
                    $keyboard[$i][$j]["action"]["hash"] = "action={$button[2]}";
                    $keyboard[$i][$j]["action"]["hash"] .= ($button[3] < 0) ? "&group_id=".$button[3]*-1 : "&user_id={$button[3]}";
                    $keyboard[$i][$j]["action"]["hash"] .= (isset($button[4])) ? "&amount={$button[4]}" : '';
                    $keyboard[$i][$j]["action"]["hash"] .= (isset($button[5])) ? "&description={$button[5]}" : '';
                    $keyboard[$i][$j]["action"]["hash"] .= (isset($button[6])) ? "&data={$button[6]}" : '';
                    $keyboard[$i][$j]["action"]["hash"] .= "&aid=1";
                    break;
                }
                case 'open_app': {
                    $keyboard[$i][$j]["action"]["label"] = $button[2];
                    $keyboard[$i][$j]["action"]["app_id"] = $button[3];
                    if(isset($button[4]))
                        $keyboard[$i][$j]["action"]["owner_id"] = $button[4];
                    if(isset($button[5]))
                        $keyboard[$i][$j]["action"]["hash"] = $button[5];
                    break;
                }
                case 'open_link': {
                    $keyboard[$i][$j]["action"]["label"] = $button[2];
                    $keyboard[$i][$j]["action"]["link"] = $button[3];
                }
            }
            $j++;
        }
        $i++;
    }
    $keyboard = ["one_time" => $one_time, "buttons" => $keyboard, 'inline' => $inline];
    $keyboard = json_encode($keyboard, JSON_UNESCAPED_UNICODE);
    return $keyboard;
}

?>