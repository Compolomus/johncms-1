<?php

defined('_IN_JOHNCMS') or die('Error: restricted access');
require_once("../incfiles/head.php");

if ($_GET['id'] == "") {
    echo "ERROR<br/><a href='index.php?'>Back</a><br/>";
    require_once('../incfiles/end.php');
    exit;
}

/** @var PDO $db */
$db = App::getContainer()->get(PDO::class);

$ms = $db->query("SELECT * FROM `download` WHERE `id` = '" . $id . "'")->fetch();

if ($ms['type'] != "file") {
    echo "ERROR<br/><a href='index.php?'>Back</a><br/>";
    require_once('../incfiles/end.php');
    exit;
}

if (isset($_SESSION['rat']) && $_SESSION['rat'] == $id) {
    echo $lng_dl['already_rated'] . "<br/><a href='index.php?act=view&amp;file=" . $id . "'>" . $lng['back'] . "</a><br/>";
    require_once('../incfiles/end.php');
    exit;
}

if (isset($_POST['rat'])
    && ctype_digit($_POST['rat'])
    && $_POST['rat'] > 0
    && $_POST['rat'] < 11
) {
    $rat = intval($_POST['rat']);

    if (!empty($ms['soft'])) {
        $tmp = unserialize($ms['soft']);
        $rating['vote'] = $tmp['vote'] + $rat;
        $rating['count'] = $tmp['count'] + 1;
    } else {
        $rating['vote'] = $rat;
        $rating['count'] = 1;
    }

    $_SESSION['rat'] = $id;
    $db->exec("UPDATE `download` SET `soft` = " . $db->quote(serialize($rating)) . " WHERE `id` = " . $id);
}

echo $lng_dl['vote_adopted'] . "<br/><a href='index.php?act=view&amp;file=" . $id . "'>" . $lng['back'] . "</a><br/>";
