<?php
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

// 獲取當前插件 ID
$pluginid = $_GET['pluginid'];

// 強制更新資料庫中的名稱與描述，避免 XML 導入時的編碼錯誤
$name = 'Cloudflare Turnstile 驗證碼';
$desc = '為 Discuz! 提供 Cloudflare Turnstile 防垃圾保護，支持註冊、登入、發帖等場景。';

C::t('common_plugin')->update($pluginid, array(
    'name' => $name,
    'description' => $desc
));

$finish = TRUE;
?>
