<?php

use DiDom\Document;
use DiDom\Query;
require_once('vendor/autoload.php');


$entryUrl = "https://www.imdb.com/chart/top/?ref_=nv_mv_250";
$domain = "https://www.imdb.com/";

$actorcount = 1;

# retrieve the entry page
echo "Retrieving entry page...\n";
$entryPage = new Document($entryUrl, true);
$movies = $entryPage->find('.ipc-metadata-list-summary-item .ipc-title-link-wrapper');

# parse for movie links
foreach ($movies as $movie) {
    $movieTitle = $movie->text();
    echo $movieTitle ."\n";
    echo "Retrieving actors...\n";
    $movieUrl = $movie->attr('href');

    # retrieve movie page
    $moviePage = new Document($domain.$movieUrl, true);
    $actors = $moviePage->find('*[^data-testid=title-cast-item__actor]');
    
    # parse for actor links
    foreach ($actors as $actor) {
        $actorName = $actor->text();
        echo "[".$actorcount++."] $actorName\n";
        #echo "Retrieving actors...\n";
        $actorUrl = $actor->attr('href');

        # retrieve movie page
        $actorPage = new Document($domain.$actorUrl, true);
        $birthInfoArr = $actorPage->find('*[data-testid=nm_pd_bl]');

        # skip if no birth info
        if (count($birthInfoArr) == 0) continue;
        $birthInfo = $birthInfoArr[0];

        # retrieve action info
        $birthYearArr = $birthInfo->find('a[href*=/search/name/?birth_year]');
        $birthPlaceArr = $birthInfo->find('.ipc-metadata-list-item__list-content-item');

        # skip if birth year/place not found
        if (count($birthYearArr) == 0 || count($birthPlaceArr) == 0) continue;
        
        # extract birth info
        $birthYear = $birthYearArr[0]->text();
        $birthPlace = $birthPlaceArr[0]->text();

        $birthPlaceParts = explode(",", $birthPlace);
        $birthPlaceParts = array_map("trim", $birthPlaceParts);

        if (count($birthPlaceParts) == 1) {
            list($country) = $birthPlaceParts;
            $city = null;
            $state = null;
        }
        elseif (count($birthPlaceParts) == 2) {
            list($city, $country) = $birthPlaceParts;
            $state = null;
        }
        else {
            list($city, $state, $country) = $birthPlaceParts;
            $country = $birthPlaceParts[count($birthPlaceParts)-1];
            $state = $birthPlaceParts[count($birthPlaceParts)-2];
            $city = implode(", ", array_slice($birthPlaceParts, 0, count($birthPlaceParts)-2));
        }

        echo "$birthYear : $city - $state - $country\n";
    }

}
