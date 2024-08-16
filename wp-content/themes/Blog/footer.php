<?php require 'pages/includes/config.php' ?>

<footer id="footer">
    <div class="container">
        <div class="footer__logo">
            <h1><?php bloginfo('name') ?></h1>
        </div>

        <nav class="footer__menu">
            <ul>
                <li><a href="/">Главная</a></li>
                <li><a href="/about-me">Обо мне</a></li>
                <li><a href="<?php echo $config['url'] ?>" target="_blank">Я в YouTube</a></li>
                <li><a href="/copyright">Правообладателям</a></li>
            </ul>
        </nav>
    </div>
</footer>