<?php

/**
 * The base class for vkTools.
 */
class vkTools {

	public $modx;
	public $config = array();
	private $auth_url = 'https://oauth.vk.com/authorize';

	/**
	 * @param modX $modx
	 * @param array $config
	 */
	function __construct(modX &$modx, array $config = array()) {
		$this->modx =& $modx;
		$this->setConfig($config);
	}

	public function setConfig(array $config = array())
	{
		$this->config = $config;
		$session_tokens = $this->modx->getOption('vktools_session_token_mode');
		if($session_tokens && !empty($access_token = $_SESSION['vktools_access_token'])){
			$this->config['access_token'] =  $access_token;
		}else{
			$this->config['access_token'] = $this->modx->getOption('vktools_access_token', '', true);
		}
		$this->config['app_id'] = $this->modx->getOption('vktools_app_id', null, false);
		$this->config['app_secret'] = $this->modx->getOption('vktools_app_secret', null, false);
		$this->config['api_version'] = '5.5';
	}

	public function getAuthUrl($scope = '', $html = false, $display = 'page', $callback_url = 'https://api.vk.com/blank.html')
	{
		$params = array(
			'client_id'     => $this->config['app_id'],
			'display'		=> $display,
			'scope'         => $scope,
			'redirect_uri'  => $callback_url,
			'response_type' => 'token'
		);
		$url = $this->makeUrl($this->auth_url, $params);
		if($html) {
			return '<a href="'.$url.'" target="_blank">'.$url.'</a>';
		}else {
			return $url;
		}
	}

	public function uploadFile($server, $file){

		if (version_compare(phpversion(), '5.5.0', '<')) {
			$image = '@'.$file;
		}else{
			$image = new CURLFile($file);
		}

		$params = array(
			'file' => $image
		);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $server);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: multipart/form-data'));
		curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
		$response = curl_exec($ch);
		curl_close($ch);
		return $this->vkResponse($response);
	}

	public function api($method, $params = array())
	{
		$params['timestamp'] = time();
		$params['api_id']    = $this->config['app_id'];
		$params['random']    = rand(0, 10000);

		if (!is_null($this->config['access_token'])) $params['access_token'] = $this->config['access_token'];
		if (!is_null($this->config['api_version'])) $params['v'] = $this->config['api_version'];

		$url = 'https://api.vk.com/method/' . $method;
		return $this->curlRequest($url, $params);
	}

	public function setSessionAccessToken($access_token){
        $_SESSION['vktools_access_token'] = $access_token;
        return true;
	}

	public function removeSessionAccessToken(){
        unset($_SESSION['vktools_access_token']);
        return true;
	}

	private function curlRequest($url, $params = array())
	{
		$client = $this->modx->getService('rest.modRestCurlClient');
		$result = $client->request($url, '', 'POST', $params, array(
			CURLOPT_USERAGENT => "vkTools/1.0",
			CURLOPT_SSL_VERIFYPEER => false,
			'contentType' => 'vkContent'
		));

		/*
		$ch = curl_init();
		curl_setopt_array($ch,
			array(
				CURLOPT_USERAGENT       => 'vkTools/1.0',
				CURLOPT_RETURNTRANSFER  => true,
				CURLOPT_SSL_VERIFYPEER  => false,
				CURLOPT_POST            => true,
				CURLOPT_POSTFIELDS      => $params,
				CURLOPT_URL             => $url
			)
		);
		$result = curl_exec($ch);
		curl_close($ch);
		*/
		return $this->vkResponse($result);
	}

	private function makeUrl($url, $params)
	{
		$url_string = http_build_query($params);
		$url .= '?' . $url_string;
		return $url;
	}

	private function vkResponse($response){
		$response = json_decode($response,1);
		if(!isset($response['error'])){
			return $response;
		}else{
			$this->modx->log(1, print_r($response['error']));
			return $response;
		}
	}



}