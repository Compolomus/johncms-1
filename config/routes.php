<?php

declare(strict_types=1);

/*
 * This file is part of JohnCMS Content Management System.
 *
 * @copyright JohnCMS Community
 * @license   https://opensource.org/licenses/GPL-3.0 GPL-3.0
 * @link      https://johncms.com JohnCMS Project
 */

use FastRoute\RouteCollector;
use Johncms\System\Users\User;

return function (RouteCollector $map, User $user) {
    // An example of a route where the URL has optional index.php
    // $map->addRoute(['GET', 'POST'], '/users[/[index.php]]', 'modules/users/index.php');

    $map->get('/', 'modules/homepage/index.php');                                                     // Home Page
    $map->get('/rss[/]', 'modules/rss/index.php');                                                    // RSS
    $map->addRoute(['GET', 'POST'], '/album[/]', 'modules/album/index.php');                          // Photo Album
    $map->addRoute(['GET', 'POST'], '/community/[{action}/[{mod}/]]', 'modules/community/index.php'); // Users community
    $map->addRoute(['GET', 'POST'], '/downloads[/]', 'modules/downloads/index.php');                  // Downloads
    $map->addRoute(['GET', 'POST'], '/forum[/]', 'modules/forum/index.php');                          // Forum
    $map->addRoute(['GET', 'POST'], '/guestbook[/[{action}]]', 'modules/guestbook/index.php');        // Guestbook, mini-chat
    $map->addRoute(['GET', 'POST'], '/help[/]', 'modules/help/index.php');                            // Help
    $map->addRoute(['GET', 'POST'], '/library[/]', 'modules/library/index.php');                      // Articles Library
    $map->addRoute(['GET', 'POST'], '/language[/]', 'modules/language/index.php');                    // Language switcher
    $map->addRoute(['GET', 'POST'], '/login[/]', 'modules/login/index.php');                          // Login / Logout
    $map->addRoute(['GET', 'POST'], '/mail[/]', 'modules/mail/index.php');                            // Personal Messages
    $map->addRoute(['GET', 'POST'], '/news/[{action}/[{id:\d+}]]', 'modules/news/index.php');         // News
    $map->addRoute(['GET', 'POST'], '/online/[{action}/]', 'modules/online/index.php');               // Online site activity
    $map->addRoute(['GET', 'POST'], '/profile/skl.php', 'modules/profile/skl.php');                   // Restore Password
    $map->addRoute(['GET', 'POST'], '/profile[/]', 'modules/profile/index.php');                      // User Profile
    $map->addRoute(['GET', 'POST'], '/redirect/', 'modules/redirect/index.php');                      // Redirect on link
    $map->addRoute(['GET', 'POST'], '/registration[/]', 'modules/registration/index.php');            // New users registration

    if ($user->isValid()) {
        $map->addRoute(['GET', 'POST'], '/notifications[/]', 'modules/notifications/index.php');      // Notifications
    }

    if ($user->isValid() && $user->rights >= 6) {
        $map->addRoute(['GET', 'POST'], '/admin/[{action}/]', 'modules/admin/index.php');                      // Administration
    }
};
