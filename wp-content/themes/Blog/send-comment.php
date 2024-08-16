<?php
    require 'pages/includes/config.php';
    $template_url = $config['template-url'];
    $domain = $config['domain'];

    if (isset( $_POST['do_post'] )) {
        $errors = array();

        if ($_POST['name'] == '')
            $errors[] = '';

        if ($_POST['nickname'] == '')
            $errors[] = '';

        if ($_POST['email'] == '')
            $errors[] = '';

        if ($_POST['text'] == '')
            $errors[] = '';

        if (empty($errors)) {
            mysqli_query($connection,  'INSERT INTO comments(author, nickname, email, `text`, pubdate, articles_id) VALUES (
                "' . $_POST['name'] . '", "' . $_POST['nickname'] . '", "' . $_POST['email'] . '", "' . $_POST['text'] . '", CURRENT_TIMESTAMP, "' . $_POST['id'] . '"
            )');
        }
    }

    header("Location: /article/?id=" . $_POST['id']);
?>