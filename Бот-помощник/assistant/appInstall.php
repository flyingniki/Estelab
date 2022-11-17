<?php

// handler for events
$handlerBackUrl = ($_SERVER['SERVER_PORT'] == 443 ? 'https' : 'http') . '://' . $_SERVER['SERVER_NAME'] . (in_array(
    $_SERVER['SERVER_PORT'],
    array(80, 443)
) ? '' : ':' . $_SERVER['SERVER_PORT']) . $_SERVER['SCRIPT_NAME'];
// If your application supports different localizations
// use $_REQUEST['data']['LANGUAGE_ID'] to load correct localization
// register new bot
$result = restCommand('imbot.register', array(
    'CODE'                  => 'AssistantBot',
    // строковой идентификатор бота, уникальный в рамках вашего приложения (обяз.)
    'TYPE'                  => 'B',
    // Тип бота, B - бот, ответы  поступают сразу, H - человек, ответы поступаю с задержкой от 2х до 10 секунд
    'EVENT_MESSAGE_ADD'     => $handlerBackUrl,
    // Ссылка на обработчик события отправки сообщения боту (обяз.)
    'EVENT_WELCOME_MESSAGE' => $handlerBackUrl,
    // Ссылка на обработчик события открытия диалога с ботом или приглашения его в групповой чат (обяз.)
    'EVENT_BOT_DELETE'      => $handlerBackUrl,
    // Ссылка на обработчик события удаление бота со стороны клиента (обяз.)
    'PROPERTIES'            => array( // Личные данные чат-бота (обяз.)
        'NAME'              => 'Бот-помощник',
        // Имя бота (обязательное одно из полей NAME или LAST_NAME)
        'LAST_NAME'         => '',
        // Фамилия бота (обязательное одно из полей NAME или LAST_NAME)
        'COLOR'             => 'AZURE',
        // Цвет бота для мобильного приложения RED,  GREEN, MINT, LIGHT_BLUE, DARK_BLUE, PURPLE, AQUA, PINK, LIME, BROWN,  AZURE, KHAKI, SAND, MARENGO, GRAY, GRAPHITE
        'EMAIL'             => '',
        // Емейл для связи
        'PERSONAL_BIRTHDAY' => '2022-11-11',
        // День рождения в формате YYYY-mm-dd
        'WORK_POSITION'     => 'Помогаю во многих вопросах',
        // Занимаемая должность, используется как описание бота
        'PERSONAL_WWW'      => '',
        // Ссылка на сайт
        'PERSONAL_GENDER'   => 'M',
        // Пол бота, допустимые значения M -  мужской, F - женский, пусто если не требуется указывать
        'PERSONAL_PHOTO'    => '',
        // Аватар бота - base64
    ),
), $_REQUEST["auth"]);

$botId = $result['result'];

$commandAbsenceId = regCommand($botId, $handlerBackUrl, 'absence', 'Отсутствие и переработка', 'some text');
$commandBillPaymentId = regCommand($botId, $handlerBackUrl, 'billPayment', 'Оплата счета', 'some text');

// save params
$appsConfig[$_REQUEST['auth']['application_token']] = array(
    'BOT_ID' => $botId,
    'COMMAND_ABSENCE' => $commandAbsenceId,
    'COMMAND_BILL_PAYMENT' => $commandBillPaymentId,
    'LANGUAGE_ID' => $_REQUEST['data']['LANGUAGE_ID'],
    'AUTH' => $_REQUEST['auth'],
);
saveParams($appsConfig);

// write debug log
writeToLog(array($botId, $commandAbsenceId, $commandBillPaymentId), 'Assistant register');
