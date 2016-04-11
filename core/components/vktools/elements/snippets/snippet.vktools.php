<?php
$vkPath = MODX_CORE_PATH . 'components/vktools/model/';
$modx->getService('vk', 'vktools.vkTools', $vkPath);
$access_token = '';
$modx->vk->setAccessToken($access_token);
echo $modx->vk->getAuthUrl('messages,photos', true);
echo '<br/>';
print_r($modx->vk->api('users.get', array()));