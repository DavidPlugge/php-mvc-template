<?php
    $nav = Router::getMenu('main-menu');
    $userOptionsTemp = Router::getMenu('user-options');
    $userOptions = null;
    foreach ($userOptionsTemp as $name => $link) {
        if (is_array($link)) {
            foreach ($link as $subname => $sublink) {
                $userOptions[$subname] = $sublink;
            }
        } else {
            $userOptions[$name] = $link;
        }
    }
?>

<header class="logo">
    <h1>Plugge</h1>
</header>

<nav class="nav nav-main">
    <a class="nav-toggle" href="#">Menu<i class="fas fa-bars menu-icon"></i></a>
    <ul>
        <?php foreach ($nav as $name => $link) : ?>
            <?php if (is_array($link)) : ?>
                <li class="dropdown">
                    <a class="dropdown-toggle" href="#"><?=$name?></a>
                    <ul class="dropdown-menu">
                        <?php foreach ($link as $subname => $sublink) : ?>
                            <li><a href="<?=$sublink?>"><?=$subname?></a>
                        <?php endforeach; ?>
                    </ul>
                </li>
            <?php else : ?>
                <li><a href="<?=$link?>"><?=$name?></a></li>
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if (isset(currentUser()->username)) : ?>
            <li class="dropdown nav-main-user">
                <a class="dropdown-toggle" href="#"><?=currentUser()->username?><i class="fa fa-user"></i></a>
                <ul class="dropdown-menu">
                    <?php foreach ($userOptions as $name => $sublink) : ?>
                        <li><a href="<?=$sublink?>"><?=$name?></a>
                    <?php endforeach; ?>
                </ul>
            </li>
        <?php else : foreach ($userOptions as $name => $sublink) : ?>
            <li class="nav-main-user"><a href="<?=$sublink?>"><?=$name?></a></li>
        <?php endforeach; endif; ?>
    </ul>
</nav>