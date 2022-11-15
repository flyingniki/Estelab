<?php

error_reporting(0);

#####################
### CONFIG OF BOT ###
#####################
define('CLIENT_ID', 'local.63725562a11fd1.26508424');
define('CLIENT_SECRET', '105Y3keftHi7W12sYNzH54DBO1if34hi4pb3fsfjlQbpXPKtHG');
#####################

define('BOT_UNIQUE_NAME', basename(__FILE__, '.php'));
define('DEBUG_FILE_NAME', BOT_UNIQUE_NAME . '.log');
define('USER_ID', $_REQUEST['data']['USER']['ID']);
define('LINK_PREVIEW', 'https://corp.estelab.ru/bitrix/tools/disk/uf.php?action=download&ncc=1&ts=bxviewer&ibxShowImage=1&attachedId=');

$appsConfig = array();
if (file_exists(__DIR__ . '/config.php')) {
    include(__DIR__ . '/config.php');
}
require_once(__DIR__ . '/keyboards.php');
require_once(__DIR__ . '/functions.php');
require_once(__DIR__ . '/myFunctions.php');

$handlerBackUrl = ($_SERVER['SERVER_PORT'] == 443 || $_SERVER["HTTPS"] == "on" ? 'https' : 'http') . "://" . $_SERVER['SERVER_NAME'] .
    (in_array($_SERVER['SERVER_PORT'], array(80, 443)) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];

$bot_ID = array_values($appsConfig)[0]['BOT_ID'];

writeToLog($appsConfig, ' appsConfig ');
$entityCode = 'bot_assistant_entity';

$id_specialist = $_REQUEST['data']['PARAMS']['DIALOG_ID'];
$entityName = 'dialog_' . $id_specialist;

//Получим текущий диалог и его свойства
$currentDialog = getCurrentDialogData($entityCode, $entityName);
$dialogID = $currentDialog['result'][0]['ID'];
$propertyValues = $currentDialog['result'][0]['PROPERTY_VALUES'];

if ($_REQUEST['event'] == 'ONIMCOMMANDADD') {
    require_once(__DIR__ . '/logic_got_command.php');
} elseif ($_REQUEST['event'] == 'ONIMBOTMESSAGEADD') {
    require_once(__DIR__ . '/logic_got_message.php');
} else if ($_REQUEST['event'] == 'ONIMBOTJOINCHAT') {
    require_once(__DIR__ . '/lib/join_chat.php');
} else if ($_REQUEST['event'] == 'ONIMBOTDELETE') {
    require_once(__DIR__ . '/lib/bot_delete.php');
} else if ($_REQUEST['event'] == 'ONAPPINSTALL') {
    writeToLog(__DIR__ . '/lib/bot_install.php');
    require_once(__DIR__ . '/lib/bot_install.php');
}
