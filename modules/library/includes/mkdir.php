<?php

declare(strict_types=1);

/*
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

defined('_IN_JOHNCMS') || die('Error: restricted access');

if (! $adm) {
    Library\Utils::redir404();
}

if (isset($_POST['submit'])) {
    if (empty($_POST['name'])) {
        echo $view->render('system::app/old_content', [
            'title'   => $textl,
            'content' => $tools->displayError(_t('You have not entered the name'), '<a href="?act=mkdir&amp;id=' . $id . '">' . _t('Repeat') . '</a>'),
        ]);
        exit;
    }

    $lastinsert = $db->query('SELECT MAX(`id`) FROM `library_cats`')->fetchColumn();
    ++$lastinsert;
    $name = $_POST['name'];
    $desc = $_POST['description'];
    $type = (int) ($_POST['type']);
    $stmt = $db->prepare('INSERT INTO `library_cats` (`parent`, `name`, `description`, `dir`, `pos`) VALUES (?, ?, ?, ?, ?)');
    $stmt->execute([$id, $name, $desc, $type, $lastinsert]);

    if ($stmt->rowCount()) {
        echo '<div>' . _t('Section created') . '</div><div><a href="?do=dir&amp;id=' . $id . '">' . _t('To Section') . '</a></div>';
    }
} else {
    echo '<div class="phdr"><strong><a href="?">' . _t('Library') . '</a></strong> | ' . _t('Create Section') . '</div>'
        . '<form action="?act=mkdir&amp;id=' . $id . '" method="post">'
        . '<div class="menu">'
        . '<h3>' . _t('Title') . ':</h3>'
        . '<div><input type="text" name="name" /></div>'
        . '<h3>' . _t('Section description') . ':</h3>'
        . '<div><textarea name="description" rows="4" cols="20"></textarea></div>'
        . '<h3>' . _t('Section type') . '</h3>'
        . '<div><select name="type">'
        . '<option value="1">' . _t('Sections') . '</option>'
        . '<option value="0">' . _t('Articles') . '</option>'
        . '</select></div>'
        . '<div><input type="submit" name="submit" value="' . _t('Save') . '"/></div>'
        . '</div></form>'
        . '<p><a href ="?">' . _t('Back') . '</a></p>';
}
