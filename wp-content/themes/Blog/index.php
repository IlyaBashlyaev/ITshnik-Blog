<?php
    /* Template Name: main */
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
        <title><?php bloginfo('name') ?></title>
    </head>

    <body onclick="hideLanguages(event)">
        <div id="wrapper">
            <?php get_header(); ?>

            <div id="content">
                <div class="container">
                    <div class="row">
                        <section class="content__left col-md-8">
                            <?php
                                $posts = get_field('articles');

                                if ($posts) {
                                    ?>

                                    <div class="block">
                                        <a onclick="showPaidArticles()">Все статьи</a>
                                        <h3>Платные статьи</h3>

                                        <div class="block__content">
                                            <div class="articles articles__horizontal">
                                                <?php
                                                    $index = 1;
                                                    foreach($posts as $post) {
                                                        setup_postdata($post);
                                                    ?>

                                                    <article class="article" <?php
                                                        if ($index >= 3)
                                                            echo 'style="display: none;"';
                                                    ?>>
                                                        <div class="article__image"></div>

                                                        <div class="article__info">
                                                            <a href=""><?php the_title(); ?></a>
                                                            <div class="article__info__meta">
                                                                <small>Категория: <?php the_field('category'); ?></small><br>
                                                                <small style="position: relative; top: 9px;">Стоимость: <?php the_field('price'); ?> ₴</small>
                                                            </div>
                                                        </div>

                                                        <div class="custom-button buy-button" onclick="makePayment('<?= $index ?>', '<?php the_field('price'); ?>')">
                                                            <span>Купить статью</span>
                                                        </div>
                                                    </article>

                                                    <script>
                                                        var articleImages = document.querySelector('.block__content').querySelectorAll('.article__image'),
                                                            lastArticleImage = articleImages[articleImages.length - 1],
                                                            imageSrc = '<?php the_field('image'); ?>'
                                                        lastArticleImage.style.backgroundImage = `url("${imageSrc}")`
                                                    
                                                        $.ajax({
                                                            url: '<?= $template_url ?>/online-counter.php',
                                                            type: 'post',
                                                            data: {key: 'id'},
                                                            success: id => {
                                                                const articleLink = document.querySelector('.block')
                                                                      .querySelectorAll('.article__info > a')[<?= $index - 1 ?>]
                                                                articleLink.href = `/article/?userid=${id}&id=<?= $index ?>`
                                                            }
                                                        })
                                                    </script>

                                                    <?php
                                                        $index++;
                                                    }
                                                    wp_reset_postdata();
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                <?php }
                            ?>

                            <div class="block">
                                <a onclick="showNewArticles()">Все статьи</a>
                                <h3>Новейшее в блоге</h3>

                                <div class="block__content">
                                    <div class="articles articles__horizontal articles-new">
                                        <?php
                                            $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY id DESC LIMIT 10");
                                            while ( $art = mysqli_fetch_assoc($articles) ) {
                                        ?>

                                        <article class="article">
                                            <div class="article__image" style="background-image: url(<?= $template_url ?>/static/<?php echo $art['image'] ?>);"></div>
                                                <div class="article__info">
                                                    <a href="/article/?id=<?= $art['id'] ?>"><?= $art['title'] ?></a>
                                                    <div class="article__info__meta">
                                                        <?php
                                                            $categories = mysqli_query($connection, "SELECT * FROM `articles_categories` WHERE `id` = '" . $art['category_id'] . "'");
                                                            $category = ($categories -> fetch_assoc())['title'];
                                                        ?>

                                                        <small>Категория: <a href="/articles/?pages=1&category=<?= $art_cat['category_id'] ?>"><?= $category; ?></a></small>
                                                    </div>
                                                <div class="article__info__preview"><?= mb_substr($art['text'], 0, 50, 'utf-8')?> ...</div>
                                            </div>
                                        </article>

                                        <?php } ?>
                                    </div>
                                </div>
                            </div>

                            <div class="block">
                                <a onclick="showOldArticles(1)">Все статьи</a>
                                <h3>Старейшее в блоге</h3>

                                <div class="block__content">
                                    <div class="articles articles__horizontal articles-old">
                                        <?php
                                            $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY id LIMIT 10");

                                            while ( $art = mysqli_fetch_assoc($articles) ) {
                                        ?>

                                        <article class="article">
                                            <div class="article__image" style="background-image: url(<?= $template_url ?>/static/<?php echo $art['image'] ?>);"></div>
                                                <div class="article__info">
                                                    <a href="/article/?id=<?php echo $art['id'] ?>"><?php echo $art['title'] ?></a>
                                                    <div class="article__info__meta">
                                                        <?php
                                                            $categories = mysqli_query($connection, "SELECT * FROM `articles_categories` WHERE `id` = '" . $art['category_id'] . "'");
                                                            $category = ($categories -> fetch_assoc())['title'];
                                                        ?>

                                                        <small>Категория: <a href="/articles/?pages=1&category=<?= $art_cat['category_id'] ?>"><?= $category; ?></a></small>
                                                    </div>
                                                <div class="article__info__preview"><?php echo mb_substr($art['text'], 0, 50, 'utf-8')?> ...</div>
                                            </div>
                                        </article>

                                        <?php
                                            }
                                        ?>
                                    </div>
                                </div>
                            </div>
                        </section>
            
                        <section class="content__right col-md-4">
                            <?php get_sidebar(); ?>
                        </section>
                    </div>
                </div>
            </div>
        </div>

        <?php
            if (isset($_POST['ik_co_id']) && isset($_POST['ik_inv_st'])) {
                if ($_POST['ik_co_id'] == '61fac74096476d4b775421b4' && $_POST['ik_inv_st'] == 'success') {
                    $idData = explode('_', $_POST['ik_pm_no']);
                    $id = $idData[0];
                    $index = (int) $idData[1];

                    $guests = mysqli_query($connection, "SELECT * FROM `guests` WHERE `id` = '$id'");
                    $guest = mysqli_fetch_assoc($guests);
                    $boughtArticles = json_decode($guest['bought_articles']);

                    if (!in_array($index, $boughtArticles)) {
                        $boughtArticles[] = $index;
                        $boughtArticles = json_encode($boughtArticles);
                        mysqli_query($connection, "UPDATE `guests` SET `bought_articles` = '$boughtArticles' WHERE `id` = '$id'");
                    }
                }
            }

            $categories = mysqli_query($connection, "SELECT * FROM articles_categories");
            get_footer();
        ?>

        <script>
            function makePayment(index, amount) {
                window.location.href = `/wp-content/themes/Blog/make-payment.php?index=${index}&amount=${amount}`;
                window.location.href = `/wp-content/themes/Blog/make-payment.php?index=${index}&amount=${amount}`;
            }

            function showNewArticles() {
                var all_articles = ''

                <?php
                    $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY id DESC");

                    while ( $art = mysqli_fetch_assoc($articles) ) {
                        $art_cat = false;

                        foreach ($categories as $cat) {
                            if ($cat['id'] == $art['category_id']) {
                                $art_cat = $cat;
                                break;
                            }
                        }

                        ?>

                        all_articles += `<?php echo "<article class='article'>
    <div class='article__image' style='background-image: url($template_url/static/" . $art['image'] . ");'></div>
        <div class='article__info'>
            <a href='/article/?id=" . $art['id'] . "'>" . $art['title'] . "</a>
            <div class='article__info__meta'>
                <small>Категория: <a href='/articles/?category=" . $art_cat['id'] . "'>" . $art_cat['title'] . "</a></small>
            </div>
        <div class='article__info__preview'>" . mb_substr($art['text'], 0, 50, 'utf-8') . " ...</div>
    </div>
</article>"; ?>`
                        <?php
                    }
                ?>

                articles = document.querySelectorAll('.articles.articles__horizontal')[1]
                articles.innerHTML = all_articles
            }

            function showOldArticles() {
                var all_articles = ''

                <?php
                    $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY id");

                    while ( $art = mysqli_fetch_assoc($articles) ) {
                        $art_cat = false;

                        foreach ($categories as $cat) {
                            if ($cat['id'] == $art['category_id']) {
                                $art_cat = $cat;
                                break;
                            }
                        }

                        ?>

                        all_articles += `<?php echo "<article class='article'>
    <div class='article__image' style='background-image: url($template_url/static/" . $art['image'] . ");'></div>
        <div class='article__info'>
            <a href='/article/?id=" . $art['id'] . "'>" . $art['title'] . "</a>
            <div class='article__info__meta'>
                <small>Категория: <a href='/articles/?category=" . $art_cat['id'] . "'>" . $art_cat['title'] . "</a></small>
            </div>
        <div class='article__info__preview'>" . mb_substr($art['text'], 0, 50, 'utf-8') . " ...</div>
    </div>
</article>"; ?>`
                        <?php
                    }
                ?>

                articles = document.querySelectorAll('.articles.articles__horizontal')[2]
                articles.innerHTML = all_articles
            }

            function showPaidArticles() {
                const hideArticles = document.querySelector('.articles.articles__horizontal').querySelectorAll('.article[style="display: none;"]')
                hideArticles.forEach(hideArticle => hideArticle.style.removeProperty('display'))
            }
        </script>
    </body>
</html>