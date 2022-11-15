<?php

// check the event - authorize this event or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']]))
	return false;

// send help message how to use chat-bot. For private chat and for group chat need send different instructions.
restCommand('imbot.message.add', array(
	"DIALOG_ID" => $_REQUEST['data']['PARAMS']['DIALOG_ID'],
	"MESSAGE" => "Привет! Я твой помощник и проводник по этому порталу!",
), $_REQUEST["auth"]);
