<?php

define('CRM_USE_CUSTOM_SERVICES', true);

if (defined('CRM_USE_CUSTOM_SERVICES') && CRM_USE_CUSTOM_SERVICES === true) {
    $fileName = __DIR__ . '/include/crm_services.php';
    if (file_exists($fileName)) {
        require_once($fileName);
    }
}
