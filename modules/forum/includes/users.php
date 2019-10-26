<?php

declare(strict_types=1);

/*
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

require 'system/head.php';

/** @var Psr\Container\ContainerInterface $container */
$container = App::getContainer();

/** @var PDO $db */
$db = $container->get(PDO::class);

/** @var Johncms\Api\UserInterface $systemUser */
$systemUser = $container->get(Johncms\Api\UserInterface::class);

/** @var Johncms\Api\ToolsInterface $tools */
$tools = $container->get(Johncms\Api\ToolsInterface::class);

$topic_vote = $db->query("SELECT COUNT(*) FROM `cms_forum_vote` WHERE `type` = '1' AND `topic` = '${id}'")->fetchColumn();

if ($topic_vote == 0 || $systemUser->rights < 7) {
    echo $tools->displayError(_t('Wrong data'));
    require 'system/end.php';
    exit;
}
    $topic_vote = $db->query("SELECT `name`, `time`, `count` FROM `cms_forum_vote` WHERE `type` = '1' AND `topic` = '${id}' LIMIT 1")->fetch();
    echo '<div  class="phdr">' . _t('Who voted in the poll') . ' &laquo;<b>' . htmlentities($topic_vote['name'], ENT_QUOTES, 'UTF-8') . '</b>&raquo;</div>';
    $total = $db->query("SELECT COUNT(*) FROM `cms_forum_vote_users` WHERE `topic`='${id}'")->fetchColumn();
    $req = $db->query("SELECT `cms_forum_vote_users`.*, `users`.`rights`, `users`.`lastdate`, `users`.`name`, `users`.`sex`, `users`.`status`, `users`.`datereg`, `users`.`id`
    FROM `cms_forum_vote_users` LEFT JOIN `users` ON `cms_forum_vote_users`.`user` = `users`.`id`
    WHERE `cms_forum_vote_users`.`topic`='${id}' LIMIT ${start},${kmess}");
    $i = 0;

    while ($res = $req->fetch()) {
        echo $i % 2 ? '<div class="list2">' : '<div class="list1">';
        echo $tools->displayUser($res, ['iphide' => 1]);
        echo '</div>';
        ++$i;
    }

    if ($total == 0) {
        echo '<div class="menu">' . _t('No one has voted in this poll yet') . '</div>';
    }

    echo '<div class="phdr">' . _t('Total') . ': ' . $total . '</div>';

    if ($total > $kmess) {
        echo '<p>' . $tools->displayPagination('?act=users&amp;id=' . $id . '&amp;', $start, $total, $kmess) . '</p>' .
            '<p><form action="?act=users&amp;id=' . $id . '" method="post">' .
            '<input type="text" name="page" size="2"/>' .
            '<input type="submit" value="' . _t('To Page') . ' &gt;&gt;"/></form></p>';
    }

    echo '<p><a href="?&type=topic&amp;id=' . $id . '">' . _t('Go to Topic') . '</a></p>';

require 'system/end.php';
