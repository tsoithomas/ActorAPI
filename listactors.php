<div class="list-content-container">
<?php
require_once("./sql.php");

$actorsPerPage = 10;
$page = isset($_GET["p"]) ? $_GET["p"] : 1;
$offset = ($page-1) * $actorsPerPage;
$coords = array();

$result = $mysqli->query("SELECT actorname, birthyear, city, `state`, country, lat, lng FROM actors, cities WHERE actors.cityid = cities.cityid LIMIT $offset, 10");
while ($row = $result->fetch_assoc()) {
    $parts = array();
    if ($row["city"] != "")
        array_push($parts, $row["city"]);
    if ($row["state"] != "")
        array_push($parts, $row["state"]);
    if ($row["country"] != "")
        array_push($parts, $row["country"]);
    $fullcity = implode(", ", $parts);
    $coords[] = array("lat"=>$row["lat"], "lng"=>$row["lng"]);

    ?>
    <div class="actor">
        <div class="actorname"><?php echo $row["actorname"];?></div>
        <div class="birthyear">Year of Birth: <?php echo $row["birthyear"];?></div>
        <div class="city"><?php echo $fullcity;?></div>
    </div>
    <?php
}
?>
</div>
<div class="list-pagination-container">
<?php
    $result = $mysqli->query("SELECT COUNT(*) FROM actors");
    list($totalActors) = $result->fetch_row();
    $totalPages = ceil($totalActors / $actorsPerPage);

    if ($page < 3) {
        $startPage = 1;
        $endPage = 5;
    } 
    elseif ($page > $totalPages-2) {
        $startPage = $totalPages-4;
        $endPage = $totalPages;
    } 
    else {
        $startPage = $page-2;
        $endPage = $page+2;
    }
?>
    <a class="page<?php if ($startPage==1) echo " disabled"; ?>" onclick="loadSection('list-container', 'listactors.php?p=<?php echo max($page-5, 1);?>'); loadJS('listmarkers.php?p=<?php echo max($page-5, 1);?>');">≪</a>
<?php
    for ($i=$startPage; $i<=$endPage; $i++) {
        ?><span class="page<?php if ($i==$page) echo " current"; ?>" onclick="loadSection('list-container', 'listactors.php?p=<?php echo $i;?>'); loadJS('listmarkers.php?p=<?php echo $i;?>');"><?php echo $i;?></span><?php
    }
?>
    <a class="page<?php if ($endPage==$totalPages) echo " disabled"; ?>" onclick="loadSection('list-container', 'listactors.php?p=<?php echo min($page+5, $totalPages);?>'); loadJS('listmarkers.php?p=<?php echo min($page+5, $totalPages);?>');">≫</a>
</div>

<script type="module" src="listmarkers.php?p=<?php echo $page;?>"></script>
