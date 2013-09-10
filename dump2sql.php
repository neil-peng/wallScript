<?php
	require_once("./mmsql.php");
	$mcIns = new Memcache;
    $mcIns->connect($mcIp,$mcPort);
    $res = $mcIns->get("wUptList");
	if(is_array($res))
	{	
		$res = array_unique($res);
		$uptWArr = array();
		foreach($res as $eachPid)
		{
			$key = "uptW_".$eachPid;
			$newW = $mcIns->get($key);
			$mcIns->delete($key);
			if($newW!=false)
				$uptWArr[$eachPid] = $newW; 
		}
	}

    $res = $mcIns->get("tUptList");
	if(is_array($res))
	{	
		$res = array_unique($res);
		$uptTArr = array();
		foreach($res as $eachPid)
		{
			$key = "uptT_".$eachPid;
			$newW = $mcIns->get($key);
			$mcIns->delete($key);
			if($newW!=false)
				$uptTArr[$eachPid] = $newW;
		}
	}

	$mcIns->close();

	echo("modify weight list\n");
	var_dump($uptWArr);
	echo("modify type list\n");	
	var_dump($uptTArr);

	$sqlIns = openSql($sqlHost,$sqlUser,$sqlPwd,$sqlDb);
	
	$sqlW = uptWeight($uptWArr);
	$sqlT = uptType($uptTArr);
	
	echo("upt weight \n");
	foreach($sqlW as $sql )
	{
		echo($sql."\n");
		var_dump(doSql($sqlIns, $sql));	
	}

	echo("upt type \n");
	foreach($sqlT as $sql)
	{
		echo($sql."\n");
		var_dump(doSql($sqlIns, $sql));	
	}
/*
//call  doInertType($sqlIns,$arrIn,$weight), update the typetable
	foreach($arrIn as $pid => $type)
	{

	}	

*/
	closeSql($sqlIns); 


?>
