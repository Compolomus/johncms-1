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

$total = $db->query('SELECT COUNT(*) FROM `news`')->fetchColumn();
$req = $db->query("SELECT * FROM `news` ORDER BY `time` DESC LIMIT ${start}, " . $user->config->kmess);

/* pagination test  */

function displayPagination(string $url, int $start, int $total, int $kmess): string
{
    $nav = new \Compolomus\Pagination\Pagination(($start / $kmess + 1), $kmess, $total, 2); // 2 как в ждоне, удобнее использовать $page так как старт вычисляется пагинацией

    #echo '<pre>' . print_r($nav->get(), true) . '</pre>';

    $out[] = '<ul class="pagination">' . PHP_EOL . "\t";

    foreach ($nav->get() as $type => $page) {
        #echo $type . ' - ' . $page . PHP_EOL;
        switch ($type) {
            case 'minus':
                $out[] = '<li class="page-item"><a class="page-link" href="?page=' . $page . '">&lt;&lt;</a></li>';
                break;
//            case 'first':
//                $out[] = '<li class="page-item"><a class="page-link" href="?page=' . $page . '">1</a></li>';
//                break;
            case 'plus':
                $out[] = '<li class="page-item"><a class="page-link" href="?page=' . $page . '">&gt;&gt;</a></li>';
                break;
//            case 'last':
//                $out[] = '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
//                break;
            case 'current':
                $out[] = '<li class="page-item active"><a class="page-link" href="#">' . $page . '</a></li>';
                break;
            case 'rightDots':
            case 'leftDots':
                $out[] = '<li class="page-item disabled"><a class="page-link" href="#">' . $page . '</a></li>';
                break;
            default:
                #echo $type . ' - ' . $page . PHP_EOL;
                $out[] = '<li class="page-item"><a class="page-link" href="?page=' . $page . '">' . $page . '</a></li>';
                break;
        }
    }

    $out[] = '</ul>' . PHP_EOL;

    return implode(PHP_EOL . "\t", $out);
}

echo $view->render(
    'news::index',
    [
        'pagination' => displayPagination('?', $start, $total, $user->config->kmess),
        'total'      => $total,
        'list'       =>
            function () use ($req, $tools, $db) {
                while ($res = $req->fetch()) {
                    $text = $tools->checkout($res['text'], 1, 1);
                    $res['text'] = $tools->smilies($text, 1);

                    if (! empty($res['kom'])) {
                        $res_mes = $db->query("SELECT * FROM `forum_topic` WHERE `id` = '" . $res['kom'] . "'");

                        if ($mes = $res_mes->fetch()) {
                            $res['kom_count'] = $mes['post_count'] - 1;
                        } else {
                            $res['kom_count'] = 0;
                        }
                    }

                    yield $res;
                }
            },
    ]
);
