<?php
require_once("./conf.php");

function openSql($host,$user,$pwd,$db)
{ 
	$sqlIns=mysqli_connect($host,$user,$pwd);
	if (mysqli_connect_errno()) {
    	printf("Connect failed: %s\n", mysqli_connect_error());
    	exit();
	}
	mysqli_select_db($sqlIns,$db);
	return $sqlIns;
}

function doSql($sqlIns,$sql)
{
	$ret = array();
	return mysqli_query($sqlIns,$sql);
	/*
	while(NULL!= ($res = mysqli_fetch_row($store)))
	{
		array_push($ret,$res);
	}
	return $ret;
	*/
}

function doQuerySql($sqlIns,$sql)
{
	$ret = array();
	return mysqli_query($sqlIns,$sql);

	while(NULL!= ($res = mysqli_fetch_row($store)))
	{
		array_push($ret,$res);
	}
	return $ret;
}


function closeSql($sqlIns)
{
	mysqli_close($sqlIns);
}


function uptWeight($arrIn)
{
	$sqlArr = array();
	foreach($arrIn as $pid => $weight)
	{
		$sql = sprintf("update info_dynamic set weigth = %d where pid = %d",$weight, $pid);		
		array_push($sqlArr,$sql);
	}


function getTypeCount($sqlIns,$type)
{
	$sql = sprintf("select count from info_status where uniq = %d",$type);
	$res = doQuerySql($sqlIns,$sql);	
	echo("DEBUG: getType count :");
	var_dump($res);
	$count = $res[0];
	return $count;
}


//each page is 12, make sure the type is correct , now not support delete, check type in webpage! 
function doInertType($sqlIns,$arrIn,$weight)
{
	$sqlArr = array();
	$typeCountArr = array();
	//1-9 (actual is 1-8)

	for($i=1;$i<=9;$i++)
	{
		$typeCount = getTypeCount($sqlIns,$i);	
		$typeCountArr[$i] = $typeCount;
	}

	foreach($arrIn as $pid => $type)
	{
		$nowPageCount =  $typeCountArr[$type]+1;
		$pageIndex = -1;
		if($nowPageCount>0)
			$pageIndex = floor(($nowPageCount-1)/12)
		if($pageIndex<0)
		{
			echo("FATAL ERR: inertType()");
			exit(1);
		}		 
	
		$sql = sprintf("insert into info_type_%d (pid,page_index,weigth) VALUES (%d,%d,%d) ON DUPLICATE KEY UPDATE page_index=%d, weigth=%d;",$type, $pid, $pageIndex,$weight,$pageIndex,$weight );	
		doSql($sqlIns,$sql);
		$typeCountArr[$type] =  $nowPageCount;	
	}

//update status
	for($i=1;$i<=9;$i++)
    {   
   		$sql = sprintf("update info_status set count = %d where uniq = %d",$typeCountArr[$i],$i); 
		doSql($sqlIns,$sql);
	}   

}





function uptType($arrIn)
{
	$split = $GLOBALS["sqlSplit"];
	$sqlArr = array();
	foreach($arrIn as $pid => $type)
	{
		$sql = sprintf("update info_static_%d set type = %d where pid = %d",floor($pid/$split) ,$type, $pid);		
		array_push($sqlArr,$sql);
	}
	return $sqlArr;
}










?>
