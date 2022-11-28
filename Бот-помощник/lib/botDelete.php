<?php

// check the event - register this application or not
if (!isset($appsConfig[$_REQUEST['auth']['application_token']])) {
    return false;
}
// unset application variables
unset($appsConfig[$_REQUEST['auth']['application_token']]);
// save params
saveParams($appsConfig);
