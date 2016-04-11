<?php

/**
 * Class vkToolsMainController
 */
abstract class vkToolsMainController extends modExtraManagerController {
	/** @var vkTools $vkTools */
	public $vkTools;

	/**
	 * @return void
	 */
	public function initialize() {
		$corePath = $this->modx->getOption('vktools_core_path', null, $this->modx->getOption('core_path') . 'components/vktools/');
		require_once $corePath . 'model/vktools/vktools.class.php';

		$this->vkTools = new vkTools($this->modx);
		parent::initialize();
	}

	/**
	 * @return array
	 */
	public function getLanguageTopics() {
		return array('vktools:default');
	}


	/**
	 * @return bool
	 */
	public function checkPermissions() {
		return true;
	}
}