<?php 

    require '../config.php';
    require '../player/DAO/PlayerDAO.php';

    session_start();

    error_reporting(0);

    if (!isset($_SESSION['user'])) {
        header("Location: ../index.php");
    }
    $flag = isset($_GET['flag']) ? $_GET['flag'] : ''; 
    $user = $_SESSION['user'];
    $dao = new DAO('config.php');
    $matches = $dao->getUserMatches($user['id']);
    $uncofirmedMatches = $dao->getUserUncofirmedMatches($user['id']);
    $plays = $dao->getPlays();
    $clubs = $dao->getClubs();

    $tshirt = $dao ->selectEquipment($user, 3);
    $shorts = $dao ->selectEquipment($user, 2);
    $sneakers = $dao ->selectEquipment($user, 1);
    $racket = $dao ->selectEquipment($user, 4);

    $error = isset($_GET['error']) ? $_GET['error'] : "";
?>

<?php include_once "../partials/template1.php" ?>
<!-- NAVBAR -->
    <div id="navbar_top" class="header container-fluid">
        <div class="container">
            <div class="wrapper">
                <nav class="navbar navbar-expand-lg navbar-dark">
                <a class="navbar-brand" href="#">
                    <img src="./img/logo.png" height="30px" alt="">
                </a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>

                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Home</a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="#">Rang list</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="../controllers/login.php?action=logout">Logout</a>
                        </li>
                    </ul>
                    <form class="form-inline my-2 my-lg-0">
                        <input class="form-control mr-sm-2" type="search" placeholder="Search players" aria-label="Search players" style="width: 300px;">
                    </form>
                </div>
                </nav>
            </div>
        </div>
    </div>
    
    <!-- PROFILE -->
    <form id="profile" action="./controllers/updatePlayer.php" method="POST">
        <div class="container-fluid mt-3">
            <div class="row">
                <div class="col-lg-6 col-md-12 col-sm-12 p-0 left">
                    <div class="left-up row" style="height: 200px;">
                        <div class="ranking mt-4 ml-5 text-center">
                            ranking<br><span id="rank">6</span>
                            <button name="button" value="<?php if($flag=='edit') echo 'save'?>" id="edit"><?php if($flag=='edit') echo 'save'; else echo 'edit';?></button>
                        </div>
                    </div>
                    <div class="left-down row mt-2" style="height: 292px;">
                    <?php if($user['gender'] == 'male') { ?>
                        <img src="./img/male.svg" alt="">
                    <?php } else {?>
                            <img src="./img/female.svg" alt="">
                    <?php } ?>
                    </div>
                </div>
                <div class="col-lg-6 col-md-12 col-sm-12 right" style="height: 500px;">
                    <div class="row name">
                        <input required name="firstName" id="firstName" <?php if($flag!='edit') echo 'readonly'?> type="text" value="<?=explode(' ', $user['fullname'])[0] ?>">
                    </div>
                    <div id="lastNameRow" class="row name">
                        <input required name="lastName" id="lastName" <?php if($flag!='edit') echo 'readonly'?> type="text" value="<?=explode(' ', $user['fullname'])[1] ?>">
                    </div>
                    <div class="row flag">
                        <img src="./img/flag.png" width="60px" height="40px" alt="">
                        <div id="flag-input">serbia</div>
                    </div>
                    <div class="row my-4" style="display: block;">
                        <div class="inline-first" style="display: inline;">
                            <div class="height-age" style="display: flex; flex-direction: row;">
                                <div class="height" style="display: flex; flex-direction: column;">
                                    <div class="height-title">
                                        Height
                                    </div>
                                    <div class="height-value">
                                        <input required name="height" type="number" <?php if($flag!='edit') echo 'readonly'?> value="<?=$user['height'] ?>">
                                    </div>
                                </div>
                                <div class="age" style="display: flex; flex-direction: column;">
                                    <div class="age-title">
                                        Age
                                    </div>
                                    <div class="age-value">
                                        <input required name="age" type="number" <?php if($flag!='edit') echo 'readonly'?> value="<?=$user['age'] ?>">
                                    </div>
                                </div>
                            </div>  
                        </div>
                        <div class="inline-second" style="display: inline;">
                            <div class="plays-birthplace" style="display: flex; flex-direction: row;">
                                <div class="height" style="display: flex; flex-direction: column;">
                                    <div class="plays-title">
                                        Plays
                                    </div>
                                    <div name="plays" class="plays-value">
                                    <select <?php if($flag!='edit') echo 'disabled'?> style="border-radius: 12px; background-color: #5DB080; border: none; color: white;" name="plays" id="plays">
                                        <?php 
                                            foreach ($plays as $play) { ?>
                                                <option value="<?=$play['value']?>" <?php $playValue = $dao->getPlayByValue($play['value']); if ($playValue['value'] == $user['plays']) {echo 'selected';}?>><?=$play['value']?></option>
                                        <?php } ?>
                                    </select>
                                    </div>
                                </div>
                                <div class="age" style="display: flex; flex-direction: column;">
                                    <div class="birthplace-title">
                                        Birthplace
                                    </div>
                                    <div class="birthplace-value">
                                        <input required name="birthplace" type="text" <?php if($flag!='edit') echo 'readonly'?> value="<?=$user['birthplace'] ?>">
                                    </div>
                                </div> 
                            </div>  
                        </div>
                    </div>
                    <div class="row">
                        <span id="club">
                            Current club<br>
                            <div class="club-wrapper">
                                <select <?php if($flag!='edit') echo 'disabled'?> style="border-radius: 12px; background-color: #5DB080; border: none; color: white;" name="club" id="clubSelect">
                                <?php 
                                    foreach ($clubs as $club) { ?>
                                        <option value="<?=$club['name']?>" <?php $clubName = $dao->getClubByName($club['name']); if ($clubName['name'] == $user['club']) {echo 'selected';}?> ><?=$club['name']?></option>
                                <?php } ?>    
                                </select>
                            </div>
                        </span>
                    </div>
                </div>  
            </div>
        </div>
    </form>
    
    <!-- EQUIPMENT -->
    <div class="matches-title container-fluid text-center mt-2">
        <h1 class="text-white py-2">
            USER'S EQUIPMENT
        </h1>
    </div>
    <div id="equipmentWrapper" class="container-fluid pt-4 pb-3">
        <form class="uploadForm col-lg-3" action="./controllers/uploadImage.php?type=3" method="post" enctype="multipart/form-data">
            <div class="equip text-center" id="tshirt">
                <object data="<?= $tshirt['url']?>" width="100px" height="100px" type="image/png">
                    <img class="equipment" src="./img/no-image.png" alt="" width="100px" height="100px">
                </object>    
                <p class="equipmentTitle">T-Shirt</p>
                <input class="uploadInput" type="file" name="img"><br>
                <input type="submit" name="submit" value="Upload">
            </div>
        </form>
        <form class="uploadForm col-lg-3" action="./controllers/uploadImage.php?type=2" method="post" enctype="multipart/form-data">
            <div class="equip text-center" id="tshirt">
                <object data="<?= $shorts['url']?>" width="100px" height="100px" type="image/png">
                    <img class="equipment" src="./img/no-image.png" alt="" width="100px" height="100px">
                </object>   
                <p class="equipmentTitle">Shorts</p>
                <input class="uploadInput" type="file" name="img"><br>
                <input type="submit" name="submit" value="Upload">
            </div>
        </form>
        <form class="uploadForm col-lg-3" action="./controllers/uploadImage.php?type=1" method="post" enctype="multipart/form-data">
            <div class="equip text-center" id="tshirt">
                <object data="<?= $sneakers['url']?>" width="100px" height="100px" type="image/png">
                    <img class="equipment" src="./img/no-image.png" alt="" width="100px" height="100px">
                </object> 
                <p class="equipmentTitle">Sneakers</p>
                <input class="uploadInput" type="file" name="img"><br>
                <input type="submit" name="submit" value="Upload">
            </div>
        </form>
        <form class="uploadForm col-lg-3" action="./controllers/uploadImage.php?type=4" method="post" enctype="multipart/form-data">
            <div class="equip text-center" id="tshirt">
                <object data="<?= $racket['url']?>" width="100px" height="100px" type="image/png">
                    <img class="equipment" src="./img/no-image.png" alt="" width="100px" height="100px">
                </object> 
                <p class="equipmentTitle">Racket</p>
                <input class="uploadInput" type="file" name="img"><br>
                <input type="submit" name="submit" value="Upload">
            </div>
        </form>
    </div>
    <div class="container-fluid errorWrapper text-center">
        <p><?= $error?></p>
    </div>

    <!-- MATCHES -->
    <div class="matches-title container-fluid text-center mt-2">
        <h1 class="text-white py-2">
            MATCHES OVERVIEW
        </h1>
    </div>
    <div class="matches-table container-fluid">
        <table class="table table-light text-center">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" style="width: 100px;">#</th>
                    <th scope="col" style="width: 700px;">Name</th>
                    <th scope="col" style="width: 700px;">Score</th>
                    <th scope="col" style="width: 100px;">1st</th>
                    <th scope="col" style="width: 100px;">2nd</th>
                    <th scope="col" style="width: 100px;">3rd</th>
                    <th scope="col" style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    if (count($matches) == 0)
                    { ?>
                        <tr>
                        <td rowspan="2"></td>
                        <td id="host">No confirmed matches</td>
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td>
                        <td></td>
                    </tr>
                    <?php } else 
                    $counter = 1; 
                    foreach ($matches as $match) {
                        $host = $dao->getUserById($match['host']);
                        $opponentId = $dao->selectUserIdByFullname($match['challenger']);
                        $opponent = $dao->getUserById($opponentId['id']);
                ?>
                <tr>
                    <td rowspan="2"><?=$counter?></td>
                    <td id="host"><?= $host['fullname']?></td>
                    <td><?=$match['hostScore'] ?></td>
                    <td><?=$match['hostFirstSet'] ?></td>
                    <td><?=$match['hostSecondSet'] ?></td>
                    <td><?=$match['hostThirdSet'] ?></td>
                    <td><a class="editBtn" href="./view/editMatch.php?match=<?= $match['id']?>">EDIT</a></td>
                </tr>
                <tr style="border-bottom: 1.2px solid black;">
                    <td id="challenger"><?= $opponent['fullname']?></td>
                    <td><?=$match['challengerScore'] ?></td>
                    <td><?=$match['challengerFirstSet'] ?></td>
                    <td><?=$match['challengerSecondSet'] ?></td>
                    <td><?=$match['challengerThirdSet'] ?></td>
                    <td><a class="deleteBtn" href="./controllers/deleteMatch.php?match=<?= $match['id']?>">DELETE</a></td>
                </tr>
                <?php $counter++; } ?>
            </tbody>
        </table>
        <div class="buttons">
            <a href="./view/newMatch.php"><button style="border: 2px solid #5DB080;">Add Match</button></a>
            <a href="./view/makeReservation.php"><button style="border: 2px solid #5DB080;">Add Reservation</button></a>
        </div>
    </div>

        <!-- UNCOFIRMED MATCHES -->
    <div class="matches-title container-fluid text-center mt-2">
        <h1 class="text-white py-2">
            UNCONFIRMED MATCHES
        </h1>
    </div>
    <div class="matches-table container-fluid">
        <table class="table table-light text-center">
            <thead class="thead-dark">
                <tr>
                    <th scope="col" style="width: 100px;">#</th>
                    <th scope="col" style="width: 700px;">Name</th>
                    <th scope="col" style="width: 700px;">Score</th>
                    <th scope="col" style="width: 100px;">1st</th>
                    <th scope="col" style="width: 100px;">2nd</th>
                    <th scope="col" style="width: 100px;">3rd</th>
                    <th scope="col" style="width: 100px;"></th>
                </tr>
            </thead>
            <tbody>
                <?php 
                    $counter = 1;
                    if (count($uncofirmedMatches) == 0)
                    { ?>
                        <tr>
                        <td rowspan="2"></td>
                        <td id="host">No unconfirmed matches</td>
                        <td></td>
                        <td></td>
                        <td></td> 
                        <td></td>
                        <td></td>
                    </tr>
                    <?php } else 
                    foreach ($uncofirmedMatches as $match) {
                        $host = $dao->getUserById($match['host']);
                        $opponentId = $dao->selectUserIdByFullname($match['challenger']);
                        $opponent = $dao->getUserById($opponentId['id']);
                ?>
                <tr>
                    <td rowspan="2"><?=$counter?></td>
                    <td id="host"><?=$host['fullname']?></td>
                    <td><?=$match['hostScore'] ?></td>
                    <td><?=$match['hostFirstSet'] ?></td>
                    <td><?=$match['hostSecondSet'] ?></td>
                    <td><?=$match['hostThirdSet'] ?></td>
                    <td><a class="editBtn" href="./controllers/confirmMatch.php?action=confirm&match=<?= $match['id']?>">CONFIRM</a></td>
                </tr>
                <tr style="border-bottom: 1.2px solid black;">
                    <td id="challenger"><?=$opponent['fullname']?></td>
                    <td><?=$match['challengerScore'] ?></td>
                    <td><?=$match['challengerFirstSet'] ?></td>
                    <td><?=$match['challengerSecondSet'] ?></td>
                    <td><?=$match['challengerThirdSet'] ?></td>
                    <td><a class="deleteBtn" href="./controllers/confirmMatch.php?action=decline&match=<?= $match['id']?>">DECLINE</a></td>
                </tr>
                <?php $counter++; } ?>
            </tbody>
        </table>
    </div>
<?php include_once "../partials/template2.php" ?>
