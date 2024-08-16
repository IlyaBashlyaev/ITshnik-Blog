<?php
    $connection = new mysqli('localhost', 'root', 'root', 'Blog');

    if (!isset($_COOKIE['id'])) {
        $symbols = '1234567890qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM';
        $onlineUser = true;

        while ($onlineUser) {
            $id = '';
            for ($i = 0; $i < 11; $i++)
                $id .= $symbols[rand(0, 61)];

            $onlineUsers = mysqli_query($connection, "SELECT * FROM `online_users` WHERE `id` = '$id'");
            $onlineUser = mysqli_fetch_assoc($onlineUsers);
        }

        setcookie('id', $id, time() + (60 * 60 * 24 * 365 * 10));
        if (isset($_POST['key'])) {
            if ($_POST['key'] == 'id') {
                $id = $_COOKIE['id'];
                echo $id; exit();
            }
        }

        $connection -> query("INSERT INTO `online_users` (`id`, `lastvisit`) VALUES ('$id', " . time() . ")");
        $connection -> query("INSERT INTO `guests` (`id`, `bought_articles`) VALUES ('$id', JSON_ARRAY())");
    }

    else {
        $id = $_COOKIE['id'];
        if (isset($_POST['key'])) {
            if ($_POST['key'] == 'id') {
                echo $id; exit();
            }
        }

        $onlineUser = $connection -> query("SELECT * FROM `online_users` WHERE `id` = '$id'") -> fetch_assoc();

        if (!$onlineUser)
            $connection -> query("INSERT INTO `online_users` (`id`, `lastvisit`) VALUES ('$id', " . time() . ")");
    }

    $connection -> query('UPDATE `online_users` SET `lastvisit` = ' . time() . " WHERE `id` = '$id'");
    $onlineUsers = $connection -> query("SELECT * FROM `online_users` WHERE `id` NOT LIKE '$id'");

    while ($onlineUser = $onlineUsers -> fetch_assoc()) {
        if ($onlineUser['lastvisit'] <= time() - 2) {
            $connection -> query("DELETE FROM `online_users` WHERE `id` = '" . $onlineUser['id'] . "'");
        }
    }

    $onlineCount = $connection -> query('SELECT * FROM `online_users`') -> num_rows;
    echo $onlineCount;
?>