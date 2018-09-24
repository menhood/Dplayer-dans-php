<?php
//读取（GET）弹幕
  $getdans=$_GET['id'];
  $fgetpath='dans/'.$getdans.'.json';
  if (!empty($getdans)){
  $fget=fopen($fgetpath,'a');
  fclose($fget);
  $data = file_get_contents($fgetpath);
  header('Content-Type:application/json');
  echo '{"code":0,"version":2,"danmaku":['.$data.']}';
  }

//接受（POST）数组，写入
//判断并获取元数据
$postdansinput = file_get_contents('php://input');
$postdansarr=json_decode($postdansinput,true);//转码为php数组
$fpath='dans/'.$postdansarr['player'].'.json';
$postdansarr['player']=array($postdansarr['player']);
$idnum=file_get_contents('idnum.json');//获取id
$idnum=$idnum+1;//自增
$inputdata=array($postdansarr['time'],0,$postdansarr['color'],$postdansarr['author'],$postdansarr['text']);//修改存储格式
$postdans=json_encode($inputdata, JSON_UNESCAPED_UNICODE);//转回json数组，保持UNICODE编码
$postdans=stripslashes($postdans);//去除转义字符
$lastdata=file_get_contents($fpath).','.$postdans;//添加逗号分隔
$lastdata=ltrim($lastdata,',');//去除左边的逗号

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
echo '{"code":0,"data":"OK"}';//返回POST状态信息
?>
