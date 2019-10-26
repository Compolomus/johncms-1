<?php

declare(strict_types=1);

/*
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

/** @var Johncms\Api\UserInterface $systemUser */
$systemUser = App::getContainer()->get(Johncms\Api\UserInterface::class);

// Главное меню каталога смайлов
echo '<div class="phdr"><a href="?"><b>' . _t('Information, FAQ') . '</b></a> | ' . _t('Smilies') . '</div>';

if ($systemUser->isValid()) {
    $mycount = ! empty($systemUser->smileys) ? count(unserialize($systemUser->smileys)) : '0';
    echo '<div class="topmenu"><a href="?act=my_smilies">' . _t('My smilies') . '</a> (' . $mycount . ' / ' . $user_smileys . ')</div>';
}

if ($systemUser->rights >= 1) {
    echo '<div class="gmenu"><a href="?act=admsmilies">' . _t('For administration') . '</a> (' . (int)count(glob(ASSETS_PATH . 'emoticons/admin/*.gif')) . ')</div>';
}

$dir = glob(ASSETS_PATH . 'emoticons/user/*', GLOB_ONLYDIR);

foreach ($dir as $val) {
    $cat = strtolower(basename($val));

    if (array_key_exists($cat, smiliesCat())) {
        $smileys_cat[$cat] = smiliesCat()[$cat];
    } else {
        $smileys_cat[$cat] = ucfirst($cat);
    }
}

asort($smileys_cat);
$i = 0;

foreach ($smileys_cat as $key => $val) {
    echo $i % 2 ? '<div class="list2">' : '<div class="list1">';
    echo '<a href="?act=usersmilies&amp;cat=' . urlencode($key) . '">' . htmlspecialchars($val) . '</a>' .
        ' (' . count(glob(ASSETS_PATH . 'emoticons/user/' . $key . '/*.{gif,jpg,png}', GLOB_BRACE)) . ')';
    echo '</div>';
    ++$i;
}

echo '<div class="phdr"><a href="' . $_SESSION['ref'] . '">' . _t('Back') . '</a></div>';
