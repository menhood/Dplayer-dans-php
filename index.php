<?php
//读取（GET）弹幕
  $getdans=$_GET['id'];
  $fgetpath='dans/'.$getdans.'.json';
  if (!empty($getdans)){
  $fget=fopen($fgetpath,'a');
  fclose($fget);
  $data = file_get_contents($fgetpath);
  header('Content-Type:application/json');
  echo '{"code":1,"danmaku":['.$data.']}'; 
  }

//接受（POST）数组，写入
//判断并获取元数据
/*if (isset($GLOBALS['HTTP_RAW_POST_DATA'])) {
    $postdans = $GLOBALS['HTTP_RAW_POST_DATA'];
} else {
    $postdans = file_get_contents('php://input');
}*/
$postdansinput = file_get_contents('php://input');
$postdansarr=json_decode($postdansinput,true);//转码为php数组
$fpath='dans/'.$postdansarr['player'].'.json';
$postdansarr['player']=array($postdansarr['player']);
$idnum=file_get_contents('idnum.json');//获取id
$idnum=$idnum+1;//自增
//定义对象
$idstr='_id';
$referer='referer';
$ip='ip';
$ver='__v';
$addstr->$idstr= md5(strval($idnum).$_SERVER['HTTP_REFERER']);//id为md5加密的数字加请求网址
$addstr->$referer=$_SERVER['HTTP_REFERER'];//请求网址
$addstr->$ip=$_SERVER['REMOTE_ADDR'];//请求ip
$addstr->$ver=0;//不晓得是什么参数，先乱填上
//对象转数组函数
function object_to_array($obj){
	$_arr=is_object($obj)?get_object_vars($obj):$obj;
	foreach($_arr as $key=>$val){
		$val=(is_array($val))||is_object($val)?object_to_array($val):$val;
		$arr[$key]=$val;
	}
	return $arr;
}
$addstr=object_to_array($addstr);//转数组
$postdans=$addstr+$postdansarr;//添加数组到原数组头部
$postdans=json_encode($postdans, JSON_UNESCAPED_UNICODE);//转回json数组，保持UNICODE编码
$postdans=stripslashes($postdans);//去除转义字符
$lastdata=file_get_contents($fpath).','.$postdans;//添加逗号分隔
$lastdata=ltrim($lastdata,',');//去除左边的逗号
/*
$fw=fopen($fpath,'w');//打开文件
fwrite($fw,$lastdata);//写入弹幕数据
fclose($fw);//关闭文件
$fidnum=fopen('idnum.json','w');//打开计数文件
fwrite($fidnum,$idnum);//写入计数
fclose($fidnum);//关闭计数文件
*/

//并发写入问题
if ($fw = fopen ( $fpath, 'w' )) {
   $startTime = microtime ();
   do {
      $canWrite = flock ( $fw, LOCK_EX );
      if (! $canWrite)
      usleep ( round ( rand ( 0, 100 ) * 1000 ) );
   } while ( (! $canWrite) && ((microtime () - $startTime) < 1000) );
   if ($canWrite) {
      fwrite ( $fw, $lastdata );
   }
   fclose ( $fw );
}
if ($fidnum = fopen ( 'idnum.json','w' )) {
   $startTime = microtime ();
   do {
      $canWrite = flock ( $fidnum, LOCK_EX );
      if (! $canWrite)
      usleep ( round ( rand ( 0, 100 ) * 1000 ) );
   } while ( (! $canWrite) && ((microtime () - $startTime) < 1000) );
   if ($canWrite) {
      fwrite ( $fidnum, $idnum );
   }
   fclose ( $fidnum );
}
?>
