<?php

//
// "Data" file 
// this file contains all the data used for connecting with server and sending messages
//

define("TIME", date("H:i:s"));
define("DATE", date("d-m-y"));

// Callback API events
define("CALLBACK_API_EVENT_CONFIRMATION", "confirmation");
define("CALLBACK_API_EVENT_NEW_MESSAGE", "message_new");
define("CALLBACK_API_EVENT_REPLY", "message_reply");
define("CALLBACK_API_EVENT_USER_JOINED", "group_join");
define("CALLBACK_API_EVENT_USER_LEFT", "group_leave");
define("CALLBACK_API_EVENT_TYPING", "message_typing_state");
define("CALLBACK_API_EVENT_NEW_WALL_POST", "wall_post_new");
// VK API method link
define("VK_API_ENDPOINT", "https://api.vk.com/method/");

// Community Tokens
define("BOT_GROUP_ID", '188445631');
define("BOT_CONFIRMATION_TOKEN", '59e602d8');
define("BOT_ACCESS_TOKEN", '52ee9efe62283e2b61e96d008bc3d4000ed4721d545eb02c7eacd31f4e23daa817114eae859094f03fec4');
define("BOT_SECRET_KEY", 'kJNVDusyVS');
define("BOT_VKAPI_VERSION", '5.103');

?>