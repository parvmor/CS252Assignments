<?php
require 'vendor/autoload.php';
$client = new MongoDB\Client("mongodb://172.17.0.2:27017");
$collection = $client->cases->cases;
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width" />
        <title>Police Station Queries</title>
    </head>
    <body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
?>
<?php
if ($_REQUEST["_query"] == 1) {
    $ops = array(
        array(
            '$group' => array(
                '_id' => '$ZONE_NAME',
                'count' => array(
                    '$sum' => 1,
                ),
            ),
        ),
        array(
            '$sort' => array(
                'count' => -1,
            ),
        ),
    );
    $result = $collection->aggregate($ops);
    foreach ($result as $res) {
        echo 'District with most crime: ' . $res['_id'] . '. Total number of crimes: ' . $res['count'];
        break;
    }
} else if ($_REQUEST["_query"] == 2) {
    $ops = array(
        array(
            '$match' => array(
                'Status' => 'Pending',
            ),
        ),
        array(
            '$group' => array(
                '_id' => '$PS',
                'count' => array(
                    '$sum' => 1,
                ),
            ),
        ),
        array(
            '$sort' => array(
                'count' => -1,
            ),
        ),
    );
    $result = $collection->aggregate($ops);
    foreach ($result as $res) {
        echo 'Police Station with most crime report pending: ' . $res['_id'] . '. Total number of crime reports pending: ' . $res['count'];
        break;
    }
} else if ($_REQUEST["_query"] == 3) {
    $ops = array(
        array(
            '$unwind' => '$Act_Section'
        ),
        array(
            '$group' => array(
                '_id' => '$Act_Section',
                'count' => array(
                    '$sum' => 1,
                ),
            ),
        ),
        array(
            '$sort' => array(
                'count' => -1,
            ),
        ),
    );
    $result = $collection->aggregate($ops);
    foreach ($result as $res) {
        echo 'Crime laws most broken: ' . $res['_id'] . ' with ' . $res['count'] . ' breaks.';
        break;
    }
} else if ($_REQUEST["_query"] == 4) {
    $ops = array(
        array(
            '$unwind' => '$Act_Section'
        ),
        array(
            '$group' => array(
                '_id' => '$Act_Section',
                'count' => array(
                    '$sum' => 1,
                ),
            ),
        ),
        array(
            '$sort' => array(
                'count' => 1,
            ),
        ),
    );
    $result = $collection->aggregate($ops);
    foreach ($result as $res) {
        echo 'Crime laws least broken: ' . $res['_id'] . ' with ' . $res['count'] . ' breaks.';
        break;
    }
} else {
    die("Invalid Query!");
}
?>
<?php } else {?>
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="1" />
            <label for="query1">Query 1: District with most crime.</label>
            <input type="submit" value="Submit Query 1" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="2" />
            <label for="query2">Query 2: Police Station with most crime report pending.</label>
            <input type="submit" value="Submit Query 2" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="3" />
            <label for="query3">Query 3: Crime law most broken.</label>
            <input type="submit" value="Submit Query 3" />
        </form>
        <br />
        <br />
        <form action="<?php echo $_SERVER['SCRIPT_FILENAME'];?>" method="post">
            <input type="hidden" name="_query" id="_query" value="4" />
            <label for="query4">Query 4: Crime law least broken.</label>
            <input type="submit" value="Submit Query 4" />
        </form>
<?php } ?>

    </body>
</html>
