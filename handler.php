<?php

// 
// "Handler" file is used to handle all the requests
//

if (!isset($_REQUEST)) return;

// Including data, config & tron files
require_once "data.php";
require_once "config.php";
require_once "tron.php";

// Receiving and decoding the request
$event = _callback_getEvent();
try {
    switch ($event['type']) {
        // If it's a confirmation event..
        case CALLBACK_API_EVENT_CONFIRMATION:
            //..then send the server a confirmation token
            if ($event['secret'] == BOT_SECRET_KEY) _callback_response(BOT_CONFIRMATION_TOKEN);
        break;
    
        // If it's a new message..
        case CALLBACK_API_EVENT_NEW_MESSAGE:
            define("BOT_RECIEVED_MESSAGE", $event['object']['message']['text']);
            define("BOT_MSG_ID", $event['object']['message']['id']);
            define("BOT_ATTACHMENTS", $event['object']['message']['attachments']);
            define("BOT_ATTACHMENTS_AMOUNT", count(BOT_ATTACHMENTS));
            if (BOT_ATTACHMENTS_AMOUNT) define("BOT_MESSAGE_ATTACHMENT_TYPE", BOT_ATTACHMENTS[0]['type']);
            elseif (isset($event['object']['message']['fwd_messages']) && count($event['object']['message']['fwd_messages'])) define("BOT_MESSAGE_ATTACHMENT_TYPE", "fwd_messages");
            elseif (isset($event['object']['message']['geo'])) define("BOT_MESSAGE_ATTACHMENT_TYPE", "geo");
            else define("BOT_MESSAGE_ATTACHMENT_TYPE", "none");
            if (BOT_RECIEVED_MESSAGE == "") define("BOT_MESSAGE_EMPTY", true);
            else define("BOT_MESSAGE_EMPTY", false);

            // If it's a sticker then get the sticker id
            if (BOT_MESSAGE_ATTACHMENT_TYPE == 'sticker') define("BOT_STICKER_ID", BOT_ATTACHMENTS[0]['sticker']['product_id'] . "_" . BOT_ATTACHMENTS[0]['sticker']['sticker_id']);
            else define("BOT_STICKER_ID", "none");
            
            // Getting the user ID...
            // .. and peer ID (is required for group chats)
            define("USER_ID", $event['object']['message']['from_id']);
            define("PEER_ID", $event['object']['message']['peer_id']);
            // and the getting information about the user
            $userInfo = _vkApi_call('users.get', array(
                'access_token' => BOT_ACCESS_TOKEN,
                'user_ids' => USER_ID
            ));
            define("BOT_USER_FIRST_NAME", $userInfo[0]['first_name']);
            define("BOT_USER_LAST_NAME", $userInfo[0]['last_name']);
            
            // Including bot files
            require_once "algorithms.php";
            require_once "cases.php";
            // Sending "ok" to the Callback API server
            _callback_response('ok');
    
        break;

        case CALLBACK_API_EVENT_REPLY:
            // Incase it's a message reply notification, send "ok" to the server 
            _callback_response("ok");
        break;
        
        case CALLBACK_API_EVENT_USER_JOINED:
            // Do something here
            _callback_response("ok");
        break;
        
        case CALLBACK_API_EVENT_USER_LEFT:
            // Do something here
            _callback_response("ok");
        break;
        
        case CALLBACK_API_EVENT_NEW_WALL_POST:
            // Do something here
            _callback_response("ok");
        break;
        
        case CALLBACK_API_EVENT_TYPING:
            // Do something here
            _callback_response("ok");
        break;
        
        default:
        // Incase we've received a request with unsupported event type, send a corresponding message
            _callback_response("Unsupported event: \"".$event['type']."\"");
        break;
    }
} catch (Exception $e) {
    log_error($e);
}
?>