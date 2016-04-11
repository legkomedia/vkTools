<?php

$settings = array();

$tmp = array(
	'app_id' => array(
		'xtype' => 'textfield',
		'value' => '5409119',
		'area' => 'vktools_main',
	),
	'app_secret' => array(
		'xtype' => 'textfield',
		'value' => 'i00xR4gep6AHlqlLdTeK',
		'area' => 'vktools_main',
	),
	'access_token' => array(
		'xtype' => 'textfield',
		'value' => '',
		'area' => 'vktools_main',
	),
    'session_token_mode' => array(
		'xtype' => 'combo-boolean',
		'value' => false,
		'area' => 'vktools_main',
	),
);

foreach ($tmp as $k => $v) {
	/* @var modSystemSetting $setting */
	$setting = $modx->newObject('modSystemSetting');
	$setting->fromArray(array_merge(
		array(
			'key' => 'vktools_' . $k,
			'namespace' => PKG_NAME_LOWER,
		), $v
	), '', true, true);

	$settings[] = $setting;
}

unset($tmp);
return $settings;
