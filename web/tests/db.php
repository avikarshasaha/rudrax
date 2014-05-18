<?php

/*
 * To change this license header, choose License Headers in Project Properties.
* To change this template file, choose Tools | Templates
* and open the template in the editor.
*/
/* Add on 04-23-2014 By Avikarsha*/

function connectDb() {
	// $link = mysql_connect("localhost", "eliphazi_temp", "t;k$~Qs(-5nO") or die("connection problem" . mysql_error());
	// db con
	// mysql_select_db("eliphazi_template", $link);
	// echo "new one";
	return null;//$link;
}

function Db_Connect($db_server, $db_user, $db_password)
{
	global $link;

	$link=mysql_connect($db_server, $db_user, $db_password) or die(mysql_error().'-Could not connect to server.');
	if($link)
	{
		$db=mysql_select_db(DB_NAME, $link) or die(mysql_error().'-Could not connect to database.');
	}
	return $link;
}

function Db_Close()
{
	global $link;
	return mysql_close($link);
}

function Select_Qry($fields,$table,$where_clause,$orderby,$type,$startRow, $PageSize)
{
	$sql_condition="";
	if($where_clause!="")
	{
		$sql_condition.=" WHERE ".$where_clause;
	}
	if($orderby!='')
	{
		$sql_condition.=" ORDER BY ".$orderby;
	}
	if($orderby !='' && $type!='')
	{
		$sql_condition.=" ".$type;
	}
	if($startRow!='' && $PageSize!='')
	{
		$sql_condition.=" LIMIT ".$startRow.",".$PageSize;
	}
	//echo "SELECT ".$fields." FROM ".$table." ".$sql_condition."".'<br>';
	$resCondition = mysql_query("SELECT ".$fields." FROM ".$table." ".$sql_condition."")or die(mysql_error()."Error in selectQry() ");
	if(mysql_num_rows($resCondition) > 0)
	{
		$obCondition = mysql_fetch_array($resCondition);
		$fields="";
		$table="";
		$where_clause="";
		$orderby="";
		$type="";
		#print_r($obCondition);
			
		return $obCondition;
	}
	else
	{
		$fields="";
		$table="";
		$where_clause="";
		$orderby="";
		$type="";
		return false;
	}
}

function Listing_Qry($field,$table,$where_clause)
{
	if($where_clause != "")
	{
		//echo "SELECT ".$field." FROM ".$table." ".$where_clause."<br/>";
		$ListContent = mysql_query("SELECT ".$field." FROM ".$table." ".$where_clause."") or die(mysql_error()."Error in listingQry()");
	}
	else
	{
		$ListContent = mysql_query("SELECT ".$field." FROM ".$table) or die(mysql_error()."Error in listingQry()");
	}
	if(mysql_num_rows($ListContent) > 0)
	{
		while($objContent = mysql_fetch_array($ListContent))
		{
			$arrList[] = $objContent;
		}
		return $arrList;
	}
	else
	{
		return 0;
	}
}

function Delete_Qry($table,$where_clause)
{
	mysql_query("DELETE FROM ".$table." ".$where_clause."") or die(mysql_error()."Error in deleteQry()");
	//print "DELETE FROM ".$table." ".$where_clause."";
	return true;
}

function Truncate_Qry($table)
{
	mysql_query("TRUNCATE TABLE ".$table."") or die(mysql_error()."Error in truncateQry()");
	//print "TRUNCATE TABLE ".$table."";
	//exit;
	return true;
}

function Insert_Qry($table,$value)
{
	//print "INSERT INTO ".$table." SET ".$value."";
	//exit;
	mysql_query("INSERT INTO ".$table." SET ".$value."") or die(mysql_error()."Error in insertQry()");
	return mysql_insert_id();
}

function Update_Qry($table, $set_value, $where_clause)
{
	//print "<br>UPDATE ".$table." SET ".$set_value." ".$where_clause."<br>";
	//exit;
	mysql_query("UPDATE ".$table." SET ".$set_value." ".$where_clause."") or die(mysql_error()."Error in updateQry()");
	return true;
}

function Advance_Qry($query = '', $multi = false)// $multi = true if you want to return multipile rows
{
	global $link;
	//print $query;

	$resultSet = array();
	$result    = mysql_query($query, $link) or die(mysql_error($link)."Error in advanceQry()");

	if(mysql_num_rows($result) > 0)
	{
		if($multi == false)
		{
			return mysql_fetch_array($result);
		}
		else
		{
			while($list = mysql_fetch_array($result))
			{
				$resultSet[] = $list;
			}

			return $resultSet;
		}
	}
	else
	{
		return false;
	}
}
?>