<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_cf_turnstile {

	protected $sitekey;
	protected $secretkey;
	protected $enabled_reg;
	protected $enabled_login;
	protected $enabled_thread;
	protected $enabled_reply;

	public function __construct() {
		global $_G;
		$this->sitekey = $_G['cache']['plugin']['cf_turnstile']['sitekey'];
		$this->secretkey = $_G['cache']['plugin']['cf_turnstile']['secretkey'];
		$this->enabled_reg = $_G['cache']['plugin']['cf_turnstile']['enabled_reg'];
		$this->enabled_login = $_G['cache']['plugin']['cf_turnstile']['enabled_login'];
		$this->enabled_thread = $_G['cache']['plugin']['cf_turnstile']['enabled_thread'];
		$this->enabled_reply = $_G['cache']['plugin']['cf_turnstile']['enabled_reply'];
	}

	public function global_header() {
		if (!$this->sitekey) return '';
		return '<script src="https://challenges.cloudflare.com/turnstile/v0/api.js?render=explicit" async defer></script>';
	}

	protected function _show_widget() {
		if (!$this->sitekey) return '';
		
		$widget_id = 'cft_' . substr(md5(uniqid(rand(), true)), 0, 8);
		
		return '<style>
					.cf-turnstile-wrapper { 
						margin: 15px 0; 
						width: 100%; 
						display: flex; 
						justify-content: center; 
						clear: both;
					}
					.cf-turnstile-wrapper .cf-turnstile { 
						display: inline-block; 
					}
				</style>
				<div class="cf-turnstile-wrapper">
					<div id="' . $widget_id . '" class="cf-turnstile"></div>
				</div>
				<script>
					var render_' . $widget_id . ' = function() {
						if(typeof turnstile !== "undefined") {
							turnstile.render("#' . $widget_id . '", {
								sitekey: "' . $this->sitekey . '",
								size: "normal"
							});
						} else {
							setTimeout(render_' . $widget_id . ', 100);
						}
					};
					render_' . $widget_id . '();
				</script>';
	}

	protected function _verify() {
		if (!$this->secretkey) return true;
		
		$response = $_POST['cf-turnstile-response'];
		if (!$response) {
			showmessage($this->_t('please_verify'));
		}

		$url = 'https://challenges.cloudflare.com/turnstile/v0/siteverify';
		$data = [
			'secret' => $this->secretkey,
			'response' => $response,
			'remoteip' => $_SERVER['REMOTE_ADDR']
		];

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		$result = json_decode($result, true);
		if (!$result['success']) {
			showmessage($this->_t('verify_fail'));
		}
		return true;
	}

	protected function _t($key) {
		$msg = lang('plugin/cf_turnstile', $key);
		if(strpos($msg, ':') !== false || $msg == $key) {
			$lang = array(
				'verify_fail' => '安全驗證失敗，請重試',
				'please_verify' => '請完成安全驗證 (Cloudflare Turnstile)',
			);
			return isset($lang[$key]) ? $lang[$key] : $msg;
		}
		return $msg;
	}
}

class plugin_cf_turnstile_member extends plugin_cf_turnstile {

	protected $_rendered_reg = false;
	protected $_rendered_login = false;

	// Registration
	public function register_input() {
		if ($this->enabled_reg && !$this->_rendered_reg) {
			$this->_rendered_reg = true;
			return $this->_show_widget();
		}
	}

	public function register_input_verify() {
		if ($this->enabled_reg && submitcheck('regsubmit')) {
			$this->_verify();
		}
	}

	// Login
	public function logging_input() {
		if ($this->enabled_login && !$this->_rendered_login) {
			$this->_rendered_login = true;
			return $this->_show_widget();
		}
	}

	public function logging_method() {
		if ($this->enabled_login && !$this->_rendered_login) {
			$this->_rendered_login = true;
			return $this->_show_widget();
		}
	}

	public function logging_input_verify() {
		if ($this->enabled_login && submitcheck('loginsubmit')) {
			$this->_verify();
		}
	}
}

class plugin_cf_turnstile_forum extends plugin_cf_turnstile {

	protected $_rendered_thread = false;
	protected $_rendered_reply = false;

	public function post_newthread_input() {
		if ($this->enabled_thread && !$this->_rendered_thread) {
			$this->_rendered_thread = true;
			return $this->_show_widget();
		}
	}

	public function post_newthread_verify() {
		if ($this->enabled_thread && submitcheck('topicsubmit')) {
			$this->_verify();
		}
	}

	public function post_reppost_input() {
		if ($this->enabled_reply && !$this->_rendered_reply) {
			$this->_rendered_reply = true;
			return $this->_show_widget();
		}
	}

	public function post_reppost_verify() {
		if ($this->enabled_reply && submitcheck('replysubmit')) {
			$this->_verify();
		}
	}
}
?>
