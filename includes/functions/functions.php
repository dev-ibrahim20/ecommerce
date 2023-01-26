 <?php
/*
** Get All Function v2.0
** Function To Get All Records From Any Table
*/

function getALLFrom($field, $table, $where = NULL, $and = NULL, $orderfield, $ordering = "DESC") {
	global $con;

	$getAll = $con->prepare("SELECT $field FROM $table $where $and ORDER BY $orderfield $ordering");
	$getAll->execute();

	$all = $getAll->fetchAll();
	return $all;
}



/*
** Get Categories Function v1.0
** Function To Get Categories From Database
*/

function getCat() {
	global $con;

	$getCat = $con->prepare("SELECT * FROM categories ORDER BY ID ASC");
	$getCat->execute();

	$cats = $getCat->fetchAll();
	return $cats;
}



/*
** Get AD items Function v1.0
** Function To Get AD Items From Database
*/

function getItems($where, $value, $approve = NULL) {
	global $con;

	if($approve == NULL) {
		$sql = 'AND Approve = 1';
	}else {
		$sql = NULL;
	}

	$getItems = $con->prepare("SELECT * FROM items WHERE $where = ? $sql ORDER BY item_ID DESC");
	$getItems->execute(array($value));

	$items = $getItems->fetchAll();
	return $items;
}


/*
** Check If User Is Not Activated
** Function To Check The RegStatus Of The Users
*/



function checkUserStatus($user) {

	global $con;

    $stmtx = $con->prepare("SELECT Username, RegStatus
                            FROM 
                                  users
                            WHERE
                                  Username = ?
                            AND
                                  RegStatus = 0");
    $stmtx->execute(array($user));
    $status = $stmtx->rowCount();

    return $status;
}

















/*
** Title Function v1.0
**	Title Function That Echo Page In Case The Page
** 	Has The Variable $pageTitle And Echo Defult Title Other Pages
*/

function getTitle(){
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

function redirectHome($theMsg, $url = null, $seconds = 3){
	if ($url === null) {
		$url = 'index.php';
		$link = 'Homepage';
	}elseif ($url == 'back') {

		$url = isset($_SERVER['HTTP_REFERER']) && $_SERVER['HTTP_REFERER'] !== '' ? $_SERVER['HTTP_REFERER'] : 'index.php';
		$link = 'Previous Page';
	}else {
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


function checkItem($select, $from, $value) {
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

function countItems($item, $table) {

	global $con;

    $stmt2 = $con->prepare("SELECT COUNT($item) FROM $table");

    $stmt2->execute();
    return $stmt2->fetchColumn();
}

function ibrahem($item, $table, $value) {
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

function getLatest($select, $table, $order, $limit = 5) {
	global $con;

	$getStmt = $con->prepare("SELECT $select FROM $table ORDER BY $order DESC LIMIT $limit");
	$getStmt->execute();

	$rows = $getStmt->fetchAll();
	return $rows;
}