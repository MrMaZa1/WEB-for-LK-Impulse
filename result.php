<?php 
include('config.php');
function getIP() {
	if(isset($_SERVER['HTTP_X_REAL_IP'])) return $_SERVER['HTTP_X_REAL_IP'];
	return $_SERVER['REMOTE_ADDR'];
}
if (!in_array(getIP(),array('136.243.38.147','136.243.38.149','136.243.38.150','136.243.38.151','136.243.38.189'))) {
	die("hacking attempt!");
}else{
if(isset($_POST['SIGN'])){
	$result = $mysqli->getRow("SELECT * FROM `buy` WHERE `id` = ?i", $_REQUEST['MERCHANT_ORDER_ID']);
	//$sql = mysql_query("SELECT * FROM `buy` WHERE `id` = '".$_REQUEST['MERCHANT_ORDER_ID']."'");
	if(!empty($result)){
		$sign = md5($_REQUEST['MERCHANT_ID'].':'.$_REQUEST['AMOUNT'].':'.$secret2.':'.$_REQUEST['MERCHANT_ORDER_ID']);
		if($_POST['SIGN'] == $sign){
			$mysqli->query("UPDATE `buy` SET `status`=?i WHERE `id` = ?i", 1, $_REQUEST['MERCHANT_ORDER_ID']);
			//$sql = mysql_query("UPDATE `buy` SET `status`='1' WHERE `id` = '".$_REQUEST['MERCHANT_ORDER_ID']."'");
			$sql2 = $mysqli->getRow("SELECT * FROM `lk` WHERE `auth` = ?s", $result['steamid');
			//$sql2 = mysql_query("SELECT * FROM `lk` WHERE `auth` = '".$result['steamid']."'");
			$summ = (int)$result['summ']; 
			if(!empty($sql2)){
				$mysqli->query("UPDATE `lk` SET `cash`=`cash` + ?i,`all_cash`=`all_cash` + ?i WHERE `auth` = ?s", $summ, $summ, $result['steamid']);
				//$sql = mysql_query("UPDATE `lk` SET `cash`=`cash`+".$summ.",`all_cash`=`all_cash`+".$summ." WHERE `auth` = '".$result['steamid']."'");
			}else{
				$mysqli->query("INSERT INTO `lk`(`auth`, `name`, `cash`, `all_cash`) VALUES ('','USER',?i,?i)", $summ, $summ);
				//$sql = mysql_query("INSERT INTO `lk`(`auth`, `name`, `cash`, `all_cash`) VALUES ('','USER','".$summ."','".$summ."'");
			}
		}
	}
}else{
	die("hacking attempt!");
}
}; ?>