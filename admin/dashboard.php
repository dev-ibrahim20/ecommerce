<?php
session_start();
if (isset($_SESSION['Username'])) {
    $pageTitle = 'Dashboard';
    include 'init.php';
        /* Start Dashboard Page */
    $latestUsers = 5; // Number Of Latest Users
    $UsersLatest = getLatest("*", "users", "UserID", $latestUsers); // Latest Users Array


    $latesItem = 5; // Number Of Latest Items
    $ItemLatest = getLatest("*", "items", "item_ID", $latesItem); // Latest Items Array

    $latestComment = 5;

        ?>

        <div class="container home-stats text-center">
            <h1>Dashboard</h1>
            <div class="row">
                <div class="col-md-3">
                    <div class="stat st-members">
                        <i class="fa fa-users"></i>
                        <div class="info">
                            Total Members
                            <span><a href="members.php"><?=ibrahem('GroupID', 'users', 0) ?></a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-pending">
                        <i class="fa fa-user-plus"></i>
                        <div class="info">
                            Pending Members
                            <span><a href="members.php?do=Manage&page=pending">
                                <?=ibrahem("RegStatus", "users", 0) ?>
                            </a></span>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-items">
                        <i class="fa fa-tag"></i>
                        <div class="info">
                            Total Items
                            <span><a href="items.php"><?= countItems("item_ID", "items") ?></a></span>
                        </div>
                   </div>
                </div>
                <div class="col-md-3">
                    <div class="stat st-comments">
                        <i class="fa fa-comment"></i>
                        <div class="info">
                            Total Comments
                            <span><a href="comments.php"><?= countItems("c_id", "comments") ?></a></span>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="latest">
            <div class="container">
                <!-- Start Latest Users & items -->
                <div class="row">
                    <!-- Start Latest Users -->
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-users"></i> Latest <?= $latestUsers; ?> Registerd Users
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                    if(! empty($UsersLatest)) {
                                        foreach ($UsersLatest as $user) {
                                            echo '<li>';
                                            echo $user['Username']; 
                                            echo '<a href="members.php?do=Edit&userid=' . $user['UserID'] . '"><span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>Edit';
                                                if ($user['RegStatus'] == 0) {
                                                    echo '<a href="members.php?do=Activate&userid=' . $user['UserID'] . '" class="btn btn-info pull-right"><i class="fa fa-check"></i> Activate</a>';
                                                }
                                            echo '</span></a>';
                                            echo '</li>';
                                        }
                                    }else {
                                        echo 'There\'s No Members To Show';
                                    }        
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <!-- End Latest Users -->
                    <!-- Start Latest items -->
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-tag"></i> Latest <?= $latesItem; ?> Items
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <ul class="list-unstyled latest-users">
                                    <?php
                                    if(! empty($ItemLatest)) {
                                        foreach ($ItemLatest as $item) {
                                            echo '<li>';
                                            echo $item['Name']; 
                                            echo '<a href="items.php?do=Edit&itemid=' . $item['item_ID'] . '"><span class="btn btn-success pull-right">';
                                                echo '<i class="fa fa-edit"></i>Edit';
                                                if ($item['Approve'] == 0) {
                                                    echo '<a href="items.php?do=Approve&itemid=' . $item['item_ID'] . '" class="btn btn-info pull-right"><i class="fa fa-check"></i> Approved</a>';
                                                }
                                            echo '</span></a>';
                                            echo '</li>';
                                        }
                                    } else { 
                                        echo 'There\'s No itemsm To Show';
                                    }    
                                    ?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Latest items -->
                <!-- Start Latest Comment  -->
                <div class="row">
                    <div class="col-sm-6">
                        <div class="panel panel-default">
                            <div class="panel-heading">
                                <i class="fa fa-comments-o"></i> Latest <?= $latestComment; ?> Comments
                                <span class="toggle-info pull-right">
                                    <i class="fa fa-plus fa-lg"></i>
                                </span>
                            </div>
                            <div class="panel-body">
                                <?php
                                    $stmt = $con->prepare("SELECT 
                                                                comments.*, users.Username As Member
                                                            FROM 
                                                                comments
                                                            INNER JOIN
                                                                users
                                                            ON
                                                                users.UserID = comments.user_id
                                                            ORDER BY
                                                                c_id DESC
                                                            LIMIT
                                                                $latestComment");
                                    $stmt->execute();
                                    $comments = $stmt->fetchAll();

                                    if ( ! empty($comments)) {

                                        foreach ($comments as $comment) {
                                            echo "<div class='comment-box'>";
                                                echo '<a href="comments.php?do=Edit&comid=' . $comment['c_id'] . '"><span class="member-n">' . $comment['Member'] . '</span></a>';
                                                echo '<p class="member-c">' . $comment['comment'] . '</p>';
                                            echo "</div>";
                                        }
                                    }else {
                                        echo 'There\'s No commentsm To Show';
                                    }   
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Latest Comment  -->
            </div>
        </div>

        <?php
        /* End Dashboard Page */ 

    include $tpl . 'footer.php';
}else {
    header('Location: index.php');
    exit();
}