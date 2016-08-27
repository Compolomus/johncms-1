<?php

defined('_IN_JOHNCMS') or die('Error: restricted access');

// Подробная информация, контактные данные
$textl = htmlspecialchars($user['name']) . ': ' . _t('Information');
require('../incfiles/head.php');
echo '<div class="phdr"><a href="?user=' . $user['id'] . '"><b>' . _t('Profile') . '</b></a> | ' . _t('Information') . '</div>';

if ($user['id'] == $user_id || ($rights >= 7 && $rights > $user['rights'])) {
    echo '<div class="topmenu"><a href="?act=edit&amp;user=' . $user['id'] . '">' . _t('Edit') . '</a></div>';
}

echo '<div class="user"><p>' . functions::display_user($user, array('iphide' => 1,)) . '</p></div>' .
    '<div class="list2"><p>' .
    '<h3><img src="../images/contacts.png" width="16" height="16" class="left" />&#160;' . _t('Personal info') . '</h3>' .
    '<ul>';

if (file_exists('../files/users/photo/' . $user['id'] . '_small.jpg')) {
    echo '<a href="../files/users/photo/' . $user['id'] . '.jpg"><img src="../files/users/photo/' . $user['id'] . '_small.jpg" alt="' . $user['name'] . '" border="0" /></a>';
}

echo '<li><span class="gray">' . _t('Name') . ':</span> ' . (empty($user['imname']) ? '' : $user['imname']) . '</li>' .
    '<li><span class="gray">' . _t('Birthday') . ':</span> ' . (empty($user['dayb']) ? '' : sprintf("%02d", $user['dayb']) . '.' . sprintf("%02d", $user['monthb']) . '.' . $user['yearofbirth']) . '</li>' .
    '<li><span class="gray">' . _t('City, Country') . ':</span> ' . (empty($user['live']) ? '' : $user['live']) . '</li>' .
    '<li><span class="gray">' . _t('About myself') . ':</span> ' . (empty($user['about']) ? '' : '<br />' . functions::smileys(bbcode::tags($user['about']))) . '</li>' .
    '</ul></p><p>' .
    '<h3><img src="../images/mail.png" width="16" height="16" class="left" />&#160;' . _t('Contacts') . '</h3><ul>' .
    '<li><span class="gray">' . _t('Phone number') . ':</span> ' . (empty($user['mibile']) ? '' : $user['mibile']) . '</li>' .
    '<li><span class="gray">E-mail:</span> ';

if (!empty($user['mail']) && $user['mailvis'] || $rights >= 7 || $user['id'] == $user_id) {
    echo $user['mail'] . ($user['mailvis'] ? '' : '<span class="gray"> [' . _t('hidden') . ']</span>');
}

echo '</li>' .
    '<li><span class="gray">ICQ:</span> ' . (empty($user['icq']) ? '' : $user['icq']) . '</li>' .
    '<li><span class="gray">Skype:</span> ' . (empty($user['skype']) ? '' : $user['skype']) . '</li>' .
    '<li><span class="gray">Jabber:</span> ' . (empty($user['jabber']) ? '' : $user['jabber']) . '</li>' .
    '<li><span class="gray">' . _t('Site') . ':</span> ' . (empty($user['www']) ? '' : bbcode::tags($user['www'])) . '</li>' .
    '</ul></p></div>' .
    '<div class="phdr"><a href="?user=' . $user['id'] . '">' . _t('Back') . '</a></div>';