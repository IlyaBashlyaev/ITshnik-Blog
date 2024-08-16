<?php
    require 'pages/includes/config.php';
    $template_url = $config['template-url'];
?>

<link rel="stylesheet" href="<?= $template_url ?>/style.css">
<link rel="stylesheet" href="<?= $template_url ?>/dark-mode.css">
<link rel="stylesheet" href="<?= $template_url ?>/grid.css">
<link rel="stylesheet" href="https://pro.fontawesome.com/releases/v5.10.0/css/all.css" crossorigin="anonymous">

<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.min.js" integrity="sha512-3j3VU6WC5rPQB4Ld1jnLV7Kd5xr+cq9avvhwqzbH/taCRNURoeEpoPBK9pDyeukwSxwRPJ8fDgvYXd6SkaZ2TA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit" type="text/javascript"></script>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/css/flag-icons.min.css" integrity="sha512-UwbBNAFoECXUPeDhlKR3zzWU3j8ddKIQQsDOsKhXQGdiB5i3IHEXr9kXx82+gaHigbNKbTDp3VY/G6gZqva6ZQ==" crossorigin="anonymous" referrerpolicy="no-referrer">
<link rel="stylesheet" href="<?php bloginfo('template_url') ?>/style.css">

<header id="header">
    <div class="header__top">
        <div class="container" style="position: relative;">
            <div class="header__top__logo">
                <h1><a href='/'><?php bloginfo('name') ?></a></h1>
            </div>

            <nav class="header__top__menu">
                <ul>
                    <li><a class="toggle-mode" style="cursor: pointer;" onclick="setMode(this)"><i class="fas fa-sun"></i></a></li>
                    <li><a href="/">Главная</a></li>
                    <li><a href="/about-me">Обо мне</a></li>
                    <li><a href="/articles/?pages=1">Все статьи</a></li>
                    <li><a class="languages-link" style="cursor: pointer; display: block;" onclick="showLanguages()">Язык</a></li>
                </ul>
            </nav>

            <div class="languages">
                <div class="selected-language" style="top: 0"></div>

                <div class="language" onclick="translatePage(0)">
                    <div class="language-icon">
                        <span class="flag-icon flag-icon-ru" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/flags/4x3/ru.svg) !important"></span>
                    </div>

                    <div class="language-main">
                        <span class='ru'></span>
                    </div>
                </div>

                <div class="language" onclick="translatePage(1)">
                    <div class="language-icon">
                        <span class="flag-icon flag-icon-ua" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/flags/4x3/ua.svg) !important"></span>
                    </div>

                    <div class="language-main">
                        <span class='uk'></span>
                    </div>
                </div>

                <div class="language" onclick="translatePage(2)">
                    <div class="language-icon">
                        <span class="flag-icon flag-icon-us" style="background-image: url(https://cdnjs.cloudflare.com/ajax/libs/flag-icon-css/4.1.5/flags/4x3/us.svg) !important"></span>
                    </div>

                    <div class="language-main">
                        <span class='en'></span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="header__bottom">
        <div class="container">
            <nav>
                <ul>
                    <?php
                        $categories = mysqli_query($connection, "SELECT * FROM articles_categories");
                
                        while ($cat = mysqli_fetch_assoc($categories)) {
                            $cat_id = $cat['id'];
                
                            $article_count = mysqli_query($connection, "SELECT COUNT(id) AS total_count FROM articles WHERE category_id = $cat_id");
                            $article_count = mysqli_fetch_assoc($article_count)['total_count'];
                
                            ?>
                            <li><a href="/articles/?pages=1&category=<?php echo $cat['id'] ?>"><?php echo $cat['title'] ?> (<?php echo $article_count ?>)</a></li>
                            <?php
                        }
                    ?>
                </ul>
            </nav>
        </div>
    </div>
</header>

<script>
    if (localStorage.getItem('site-mode') == 'dark')
        setMode(document.querySelector('.toggle-mode'))

    function showLanguages() {
        const languages = document.querySelector('.languages')
        languages.classList.toggle('active')
    }

    function translatePage(id) {
        if (id == 0)
            var lang = 'ru'
        else if (id == 1)
            var lang = 'uk'
        else if (id == 2)
            var lang = 'en'
        
        var selectedLanguage = document.querySelector('.selected-language')
        selectedLanguage.style.top = 45 * id + 'px'

        $.cookie('googtrans', '/ru/' + lang, { path: '/' })
        new google.translate.TranslateElement({
            pageLanguage:'ru',
            layout: google.translate.TranslateElement.InlineLayout.SIMPLE,
            autoDisplay: false
        }, 'header')

        const languagesLink = document.querySelector('.languages-link').parentNode
        languagesLink.style.display = 'none'
    }

    function hideLanguages(event) {
        const languages = document.querySelector('.languages')
        var el = event.target, isLanguages = false

        if (el.className == 'languages-link')
            return

        if (languages.classList.contains('active'))
            languages.classList.remove('active')
    }

    function setMode(element) {
        const icon = element.querySelector('i')
        document.body.classList.toggle('dark')

        if (document.body.classList.contains('dark')) {
            localStorage.setItem('site-mode', 'dark')
            icon.outerHTML = '<i class="fas fa-moon"></i>'
        }

        else {
            localStorage.setItem('site-mode', 'light')
            icon.outerHTML = '<i class="fas fa-sun"></i>'
        }
    }
</script>