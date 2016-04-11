<?php
switch ($modx->event->name) {
    case 'OnMODXInit':
        if($modx->content->key != 'mgr') {
            if ($modx->getOption('vktools_session_token_mode')) {
                $action = $_REQUEST['vktools_action'];
                $token = $_REQUEST['access_token'];
                switch ($action) {
                    case 'setSessionAccessToken':
                        $_SESSION['vktools_access_token'] = $access_token;
                        break;
                    case 'removeSessionAccessToken':
                        unset($_SESSION['vktools_access_token']);
                        break;
                }
            }
        }
        break;
}