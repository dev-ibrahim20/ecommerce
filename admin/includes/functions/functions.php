<?php
/*
** Title Function v1.0
**	Title Function That Echo Page In Case The Page
** 	Has The Variable $pageTitle And Echo Defult Title Other Pages
*/

function getTitle()
{
	global $pageTitle;

	if (isset($pageTitle)) {
		echo $pageTitle;
	} else {
		echo 'Default';
	}
}

/*
** Home Redirect Function v2.0
** This Function Accept Parameters
** $theMsg = Echo Message [ Error | Success | Warning  ]
** $url = The Link You Want To Redirect To
** $seconds = Seconds Before Redirecting
*/

function redirectHome($theMsg, $url = null, $seconds = 3)
{
	if ($url === null) {
		$url = 'index.php';
		$link = 'Homepage';
	} elseif ($url == 'back') {

		$url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
		$link = 'Previous Page';
	} else {
		$url !== null;
		$link = 'Your Redirected To';
	}
	echo $theMsg;
	echo "<div class='alert alert-info'>You Will Be Redirected To $link After $seconds Seconds</div>";

	header("refresh:$seconds;url=$url");
	exit();
}


/*
** Check Items Function v1.0
** Function To Check Item In Database [ Function Accept Parameters ]
** $select = The Item To Select [ Example: user, item, category ]
** $from = The Table To Select From [ Example: users, items, categories ]
** $value = The Value Of Select [ Example: Osama, Box, Electronics ]
*/


function checkItem($select, $from, $value)
{
	global $con;

	$stmtement = $con->prepare("SELECT $select FROM $from WHERE $select = ?");

	$stmtement->execute(array($value));

	$count = $stmtement->rowCount();

	return $count;
}

/*
** Count Number Of Items Function v1.0
** Function To Count Number Of Items Rows
** $item = The Item To Count
** $table = The Table To Choose From
*/

function countItems($item, $table)
{

	global $con;

	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

	$stmt2->execute();
	return $stmt2->fetchColumn();
}

function ibrahem($item, $table, $value)
{
	global $con;

	$stmt2 = $con->prepare("SELECT COUNT($item) FROM $table WHERE $item = ?");
	$stmt2->execute(array($value));

	$count = $stmt2->fetchColumn();
	return $count;
}

/*
** Get Latest Records Function v1.0
** Function To Get Items From Database [ Users, Items, Comments]
** $select = Field To Select
** $table = The Table To Choose From
** $order = THe Desc Ordering
** $limit = Number Of Records To Get
*/

function getLatest($select, $table, $order, $limit = 5)
{
	global $con;

	$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$getStmt->execute();

	$rows = $getStmt->fetchAll();
	return $rows;
}
