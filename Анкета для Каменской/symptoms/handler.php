<?php

if (isset($_POST['question'])) {
    $file_name = 'form_of_' . date('d.m.Y') . '_for_client_' . $_POST['client'] . '.txt';
    $file = fopen($file_name, "w");
    $questions = $_POST['question'];
    foreach ($questions as $key => $question) {
        fwrite($file, 'question' . '_' . $key . ' => ' . $question . "\n");
    }
    fclose($file);
}
