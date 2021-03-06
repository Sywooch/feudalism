<?php

namespace app\components\vkapi;
 
class VkApi {
	var $api_secret;
	var $app_id;
	var $api_url;
	
	function __construct($app_id, $api_secret, $api_url = 'api.vk.com/api.php') {
		$this->app_id = $app_id;
		$this->api_secret = $api_secret;
		if (!strstr($api_url, 'https://')) {
                    $api_url = 'https://'.$api_url;
                }
		$this->api_url = $api_url;
	}
	
	function api($method,$params=false) {
            
                $params = is_array($params) ? $params : []; 
                
		$params['api_id'] = $this->app_id;
		if (!(isset($params['v']))) {
                    $params['v'] = '3.0';
                }
		$params['method'] = $method;
		$params['format'] = 'json';
		$params['random'] = rand(0,10000);
		$params['timestamp'] = time();
		ksort($params);
		$sig = '';
		foreach($params as $k=>$v) {
			$sig .= $k.'='.$v;
		}
		$sig .= $this->api_secret;
		$params['sig'] = md5($sig);
		$query = $this->api_url.'?'.http_build_query($params);
                
		$res = file_get_contents($query);
		return json_decode($res, true);
	}
}
