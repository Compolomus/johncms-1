<?php

/**
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

declare(strict_types=1);

defined('_IN_JOHNCMS') || die('Error: restricted access');

/**
 * @var PDO $db
 * @var Johncms\System\Utility\Tools $tools
 * @var Johncms\System\Users\User $user
 * @var Johncms\System\View\Render $view
 */

$total = $db->query('SELECT COUNT(*) FROM `users` WHERE `preg` = 1')->fetchColumn();
$req = $db->query("SELECT `id`, `name`, `sex`, `lastdate`, `datereg`, `status`, `rights`, `ip`, `browser`, `rights` FROM `users` WHERE `preg` = 1 ORDER BY `datereg` DESC LIMIT ${start}, " . $user->config->kmess);

echo $view->render(
    'users::users',
    [
        'pagination' => $tools->displayPagination('?', $start, $total, $user->config->kmess),
        'title'      => _t('List of users'),
        'total'      => $total,
        'list'       =>
            function () use ($req) {
                while ($res = $req->fetch()) {
                    yield $res;
                }
            },
    ]
);
