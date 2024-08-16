<?php
    require 'pages/includes/config.php';
    $template_url = $config['template-url'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">

        <link rel="shortcut icon" href="<?= $template_url ?>/logo.png">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <script src="https://cdn.jsdelivr.net/npm/jquery.redirect@1.1.4/jquery.redirect.min.js"></script>
        <title>ITshnik - Блог о мире IT</title>
    </head>

    <body>
        <script>
            $.ajax({
                url: '<?= $template_url ?>/online-counter.php',
                type: 'post',
                data: {key: 'id'},
                success: id => {
                    $.redirect('https://sci.interkassa.com', {
                        ik_co_id: '61fac74096476d4b775421b4',
                        ik_pm_no: id + '_<?= $_GET['index'] ?>',
                        ik_am: '<?= $_GET['amount'] ?>',
                        ik_cur: 'UAH',
                        ik_desc: 'Selling of paid articles'
                    }, 'POST')
                }
            })


            const submit = document.querySelector('input[type="submit"]')
            // submit.click()
        </script>
    </body>
</html>