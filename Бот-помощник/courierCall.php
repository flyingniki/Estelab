<?php

if ($step == 1) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_title', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Название:[/b] {$messageFromUser}. Далее заполните откуда забрать:",
        'attach' => $attach,
    );
} elseif ($step == 2) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_from', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Откуда забрать:[/b] {$messageFromUser}. Далее заполните куда доставить:",
        'attach' => $attach,
    );
} elseif ($step == 3) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_to', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Куда доставить:[/b] {$messageFromUser}. Далее заполните контакт отправителя:",
        'attach' => $attach,
    );
} elseif ($step == 4) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_sender_contact', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Контакт отправителя:[/b] {$messageFromUser}. Далее заполните телефон отправителя:",
        'attach' => $attach,
    );
} elseif ($step == 5) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_sender_phone', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Телефон отправителя:[/b] {$messageFromUser}. Далее заполните контакт получателя:",
        'attach' => $attach,
    );
} elseif ($step == 6) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_recipient_contact', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Контакт получателя:[/b] {$messageFromUser}. Далее заполните телефон получателя:",
        'attach' => $attach,
    );
} elseif ($step == 7) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_recipient_phone', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Телефон получателя:[/b] {$messageFromUser}. Далее заполните дату забора в формате 'ДД.ММ.ГГГГ ЧЧ:ММ':",
        'attach' => $attach,
    );
} elseif ($step == 8) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_pickup_date', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Дата забора:[/b] {$messageFromUser}. Далее заполните вес, кг:",
        'attach' => $attach,
    );
} elseif ($step == 9) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_weight', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Вес:[/b] {$messageFromUser}. Далее заполните габариты в формате NNxNxNN, см:",
        'attach' => $attach,
    );
} elseif ($step == 10) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_dimensions', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Габариты:[/b] {$messageFromUser}. Далее выберите тип доверенности:\n
         [send=2240]нет[/send] \n 
         [send=2241]КЛВ[/send] \n 
         [send=2242]ИП[/send] \n 
         [send=2243]Эстелаб[/send] \n 
         [send=2244]ИНЭЛКО[/send] \n 
         [send=2245]Лидер[/send] \n",
        'attach' => $attach,
    );
} elseif ($step == 11) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_procuration', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Доверенность:[/b] {$messageFromUser}. Далее заполните объявленную стоимость, руб.:",
        'attach' => $attach,
    );
} elseif ($step == 12) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_declared_value', $messageFromUser);
    updateEntityItem($entityCode, $currentItemId, 'general_step', $step + 1);
    // $arItemsInfo = getEntityItems($entityCode);
    // $itemsInfo = $arItemsInfo['result'];
    // writeToLog($itemsInfo, 'itemsNewInfo');
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Объявленная стоимость, руб.:[/b] {$messageFromUser}. Далее заполните комментарий:",
        'attach' => $attach,
    );
} elseif ($step == 13) {
    updateEntityItem($entityCode, $currentItemId, 'courier_call_comment', $messageFromUser);
    $arItemsInfo = getEntityItems($entityCode);
    $itemsInfo = $arItemsInfo['result'];
    writeToLog($itemsInfo, 'Внесенные данные:');
    $title = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_title'];
    $from = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_from'];
    $to = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_to'];
    $sender_contact = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_sender_contact'];
    $sender_phone = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_sender_phone'];
    $recipient_contact = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_recipient_contact'];
    $recipient_phone = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_recipient_phone'];
    $pickup_date = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_pickup_date'];
    $weight = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_weight'];
    $dimensions = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_dimensions'];
    $procuration = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_procuration'];
    $declared_value = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_declared_value'];
    $comment = $itemsInfo[0]['PROPERTY_VALUES']['courier_call_comment'];
    $attach[] = array("MESSAGE" => '[send=меню]Вернуться в начало[/send]');
    $arResult = array(
        'report' => "[b]Внесенные данные:[/b]\n
                        [b]Название:[/b] {$title}\n
                        [b]Откуда забрать:[/b] {$from}\n
                        [b]Куда доставить:[/b] {$to}\n
                        [b]Контакт отправителя:[/b] {$sender_contact}\n
                        [b]Телефон отправителя:[/b] {$sender_phone}\n
                        [b]Контакт получателя:[/b] {$recipient_contact}\n
                        [b]Телефон получателя:[/b] {$recipient_phone}\n
                        [b]Дата забора:[/b] {$pickup_date}\n
                        [b]Вес:[/b] {$weight}\n
                        [b]Габариты:[/b] {$dimensions}\n
                        [b]Доверенность:[/b] {$procuration}\n
                        [b]Объявленная стоимость:[/b] {$declared_value}\n
                        [b]Комментарий:[/b] {$comment}\n
                        [b]Если все верно, [send=вношу данные для вызова курьера]вносим[/send][/b]",
        'attach' => $attach,
    );
}
