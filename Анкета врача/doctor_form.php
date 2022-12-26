<?php

if (isset($_POST['question'])) {
    $doctor_id = intval(filter_input(INPUT_POST, 'doctor', FILTER_SANITIZE_SPECIAL_CHARS)) ?? NULL;
    $file_name = 'doctor_' . $doctor_id . '.txt';
    $file = fopen($file_name, "w");
    $questions = $_POST['question'];
    foreach ($questions as $key => $question) {
        if (is_array($question)) {
            $message = '';
            foreach ($question as $qst_key => $qst) {
                $qst = addslashes(htmlspecialchars(trim($qst)));
                $message .= $qst . '; ';
            }
            fwrite($file, 'question' . '_' . $key . ' => ' . $message . "\n");
        } else {
            $question = addslashes(htmlspecialchars(trim($question)));
            fwrite($file, 'question' . '_' . $key . ' => ' . $question . "\n");
        }
    };
    fclose($file);
}
