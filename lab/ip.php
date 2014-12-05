<?php
require'config.php';
require'saetv2.ex.class.php';
/**
*取得客户真个操作体系
*
*@accessprivate
*@returnvoid
*/
function get_os(){
	global $_SERVER;
	$ua = strtolower($_SERVER['HTTP_USER_AGENT']);
	$os = false;
	if(preg_match('/win/', $ua) && strpos($ua, '95')){
		$os = 'Windows 95';
	}elseif(preg_match('/win9x/', $ua) && strpos($ua, '4.90')){
		$os = 'Windows ME';
	}elseif(preg_match('/win/', $ua) && preg_match('/98/', $ua)){
		$os = 'Windows 98';
	}elseif(preg_match('/win/', $ua) && preg_match('/nt 6.0/', $ua)){
		$os = 'Windows Vista';
	}elseif(preg_match('/win/', $ua) && preg_match('/nt 6.1/', $ua)){
		$os = 'Windows 7';
	}elseif(preg_match('/win/', $ua) && preg_match('/nt 5.1/', $ua)){
		$os = 'Windows XP';
	}elseif(preg_match('/win/', $ua) && preg_match('/nt 5/', $ua)){
		$os = 'Windows 2000';
	}elseif(preg_match('/win/', $ua) && preg_match('/nt/', $ua)){
		$os = 'Windows NT';
	}elseif(preg_match('/win/', $ua) && ereg('32', $ua)){
		$os = 'Windows 32';
	}elseif(preg_match('/linux/', $ua)){
		$os = 'Linux';
	}elseif(preg_match('/unix/', $ua)){
		$os = 'Unix';
	}elseif(preg_match('/sun/', $ua) && preg_match('/os/', $ua)){
		$os = 'SunOS';
	}elseif(preg_match('/bm/', $ua) && preg_match('/os/', $ua)){
		$os = 'IBM OS/2';
	}elseif(preg_match('/Mac/', $ua) && preg_match('/PC/', $ua)){
		$os = 'Macintosh';
	}elseif(preg_match('/PowerPC/', $ua)){
		$os = 'Power PC';
	}elseif(preg_match('/AIX/', $ua)){
		$os = 'AIX';
	}elseif(preg_match('/HPUX/', $ua)){
		$os = 'HPUX';
	}elseif(preg_match('/NetBSD/', $ua)){
		$os = 'NetBSD';
	}elseif(preg_match('/BSD/', $ua)){
		$os = 'BSD';
	}elseif(preg_match('/OSF1/', $ua)){
		$os = 'OSF1';
	}elseif(preg_match('/RIX/', $ua)){
		$os = 'IRIX';
	}elseif(preg_match('/FreeBSD/', $ua)){
		$os = 'FreeBSD';
	}elseif(preg_match('/teleport/', $ua)){
		$os = 'teleport';
	}elseif(preg_match('/flashget/', $ua)){
		$os = 'flashget';
	}elseif(preg_match('/webzip/', $ua)){
		$os = 'webzip';
	}elseif(preg_match('/offline/', $ua)){
		$os = 'offline';
	}else{
		$os = 'Unknown';
	}
	return $os;
}
/**
*取得阅读器名称和版本
*
*@accesspublic
*@returnstring
*/
function getbrowser(){
	$ua = $_SERVER['HTTP_USER_AGENT'];
	if(preg_match('/OmniWeb\/(v*)([^\s|;]+)/', $ua, $regs)){
		$browser = 'OmniWeb';
		$browser_ver = $regs[2];
	}elseif(preg_match('/Netscape([0-9.]{0,})\/([^\s]+)/', $ua, $regs)){
		$browser = 'Netscape';
		$browser_ver = $regs[2];
	}elseif(preg_match('/Chrome\/([0-9.]{0,})/', $ua, $regs)){
		$browser = 'Chrome';
		$browser_ver = $regs[1];
	}elseif(preg_match('/Safari\/([0-9.]{0,})/', $ua, $regs)){
		$browser = 'Safari';
		$browser_ver = $regs[1];
	}elseif(preg_match('/MSIE\s([^\s|;]+)/', $ua, $regs)){
		$browser = 'InternetExplorer';
		$browser_ver = $regs[1];
	}elseif(preg_match('/Opera[\s|\/]([^\s]+)/', $ua, $regs)){
		$browser = 'Opera';
		$browser_ver = $regs[1];
	}elseif(preg_match('/NetCaptor\s([^\s|;]+)/', $ua, $regs)){
		$browser = '(InternetExplorer'.$regs[1].')NetCaptor';
		$browser_ver = $regs[1];
	}elseif(preg_match('/Maxthon/', $ua, $regs)){
		$browser = '(InternetExplorer'.$regs[1].')Maxthon';
		$browser_ver = '';
	}elseif(preg_match('/FireFox\/([0-9.]{0,})/', $ua, $regs)){
		$browser = 'FireFox';
		$browser_ver = $regs[1];
	}elseif(preg_match('/Lynx\/([^\s]+)/', $ua, $regs)){
		$browser = 'Lynx';
		$browser_ver = $regs[1];
	}else{
		$browser = 'Unknowbrowser';
		$browser_ver = 'Unknown';
	}
	return $browser.'/'.$browser_ver;
}
echo get_os();
echo getbrowser();
