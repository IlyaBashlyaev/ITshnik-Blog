<?php
    /* Template Name: articles */
    require 'pages/includes/config.php';
    $template_url = $config['template-url'];
?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <link rel="shortcut icon" href="<?= $template_url?>/logo.png">
        <link href="https://fonts.googleapis.com/css?family=Roboto:300,400,500,700" rel="stylesheet">
        <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
        <title><?php bloginfo('name') ?></title>
    </head>

    <body onclick="hideLanguages(event)">
        <div id="wrapper">
            <?php get_header(); ?>

            <div id="content">
                <div class="container">
                    <div class="row">
                        <script>
                            var searchQuery = ''
                        </script>

                        <?php
                            $articles_exist = false;
                            $all_pages = false;
                            
                            $per_page = 4;
                            $offset = 0;
                            $page = 1;

                            if (isset($_GET['category'])) {
                                $cat_get = (int) $_GET['category'];
                                $total_count = mysqli_query($connection, "SELECT COUNT(id) AS total_count FROM articles WHERE category_id = $cat_get");
                            }

                            else {
                                $cat_get = '';
                                $total_count = mysqli_query($connection, 'SELECT COUNT(id) AS total_count FROM articles');
                            }

                            $total_count = mysqli_fetch_assoc($total_count)['total_count'];
                            $total_pages = ceil($total_count / $per_page);

                            if (isset($_GET['pages'])) {
                                $page = (int) $_GET['pages'];

                                if ($page <= 1 || $page > $total_pages) {
                                    $page = 1;
                                }

                                $offset = $per_page * $page - $per_page;

                                if (isset($_GET['category'])) {
                                    $articles = mysqli_query($connection, "SELECT * FROM articles WHERE category_id = $cat_get ORDER BY id DESC LIMIT $offset, $per_page");
                                }

                                else {
                                    $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY id DESC LIMIT $offset, $per_page");
                                }
                            }

                            else if (isset($_GET['category'])) {
                                $all_pages = true;
                                $articles = mysqli_query($connection, "SELECT * FROM articles WHERE category_id = $cat_get ORDER BY id DESC");
                            }

                            else if (isset($_GET['search_query'])) {
                                $all_pages = true;
                                $articles = mysqli_query($connection, "SELECT * FROM articles WHERE title LIKE '%" . $_GET['search_query'] . "%'");
                                ?>

                                <script>
                                    searchQuery = '<?= $_GET['search_query'] ?>'.toLowerCase()
                                </script>

                                <?php
                            }

                            else {
                                $all_pages = true;
                                $articles = mysqli_query($connection, 'SELECT * FROM articles ORDER BY id DESC');
                            }
                        ?>

                        <section class="content__left col-md-8">
                            <div class="block">
                                <?php
                                    if (mysqli_num_rows($articles) > 0) {
                                        $articles_exist = true;
                                        ?>

                                        <h3>Все статьи</h3>
                                        <div class="block__content">
                                            <article class="articles articles__horizontal">
                                                <?php
                                                    while ( $art = mysqli_fetch_assoc($articles) ) {
                                                ?>

                                                <article class="article">
                                                    <div class="article__image" style="background-image: url(<?= $template_url ?>/static/<?php echo $art['image'] ?>);"></div>
                                                    <div class="article__info">
                                                        <a class="article__title" href="/article/?id=<?php echo $art['id'] ?>"><?php echo $art['title'] ?></a>
                                                        <div class="article__info__meta">
                                                            <?php
                                                                $categories = mysqli_query($connection, "SELECT * FROM articles_categories");
                                                                $art_cat = false;

                                                                foreach ($categories as $cat) {
                                                                    if ($cat['id'] == $art['category_id']) {
                                                                        $art_cat = $cat;
                                                                        break;
                                                                    }
                                                                }
                                                            ?>

                                                            <small>Категория: <a href="/articles/?category=<?php echo $art_cat['id'] ?>"><?php echo $art_cat['title'] ?></a></small>
                                                        </div>
                                                    <div class="article__info__preview"><?php echo mb_substr($art['text'], 0, 50, 'utf-8')?> ...</div>
                                                </div>
                                            </article>

                                            <?php
                                                }
                                            ?>
                                        </div>

                                        <?php
                                    }

                                    else {
                                        ?>
                                        <h3>Мы не смогли найти ни одну статью :(</h3>
                                        <?php
                                    }
                                ?>

                                <div class="paginator"
                                    <?php
                                        if (!(
                                            $page > 1 || $page < $total_pages
                                        ) || !isset($_GET['pages'])) {
                                            echo 'style="display: none"';
                                        }
                                    ?>>
                                    
                                    <div class="paginator-main">
                                        <?php
                                            if ($articles_exist && !$all_pages) {
                                                if ($page > 1) {
                                                    ?>

                                                    <div class="custom-button" onclick="buttonClick(true, <?php echo ($page - 1) ?>)">
                                                        <span>&laquo; Прошлая страница</span>
                                                    </div>

                                                    <?php
                                                }

                                                if ($page < $total_pages) {
                                                    ?>

                                                    <div class="custom-button" onclick="buttonClick(true, <?php echo ($page + 1) ?>)">
                                                        <span>Следующая страница &raquo;</span>
                                                    </div>
                                                    
                                                    <?php
                                                }
                                            }
                                        ?>
                                    </div>

                                    <?php
                                        if (isset($_GET['pages'])) {
                                            ?>

                                            <div class="paginator-last">
                                                <div class="custom-button" onclick="buttonClick(false, null)">
                                                    <span>Все статьи</span>
                                                </div>
                                            </div>

                                            <?php
                                        }
                                    ?>
                                </div>
                            </div>

                            <?php
                                $posts = get_field('articles');

                                if ($posts) {
                                    ?>

                                    <div class="block">
                                        <a onclick="showPaidArticles()">Все статьи</a>
                                        <h3>Платные статьи</h3>

                                        <div class="block__content">
                                            <article class="articles articles__horizontal">
                                                <script>
                                                    var flag = false
                                                </script>

                                                <?php
                                                    $index = 0; $id = 1;

                                                    foreach($posts as $post) {
                                                        setup_postdata($post);

                                                        if ($cat_get) {
                                                            $category = mysqli_query($connection, "SELECT * FROM articles_categories WHERE `id` = $cat_get");
                                                            $art_cat = mysqli_fetch_assoc($category)['title'];
                                                        }

                                                        else
                                                            $art_cat = '';
                                                        ?>

                                                        <script>
                                                            var cat = '<?php the_field('category') ?>',
                                                                currentCat = '<?= $art_cat ?>'

                                                            if (cat.includes(currentCat)) {
                                                                $.ajax({
                                                                    url: '<?= $template_url ?>/online-counter.php',
                                                                    type: 'post',
                                                                    data: {key: 'paid'},
                                                                    success: userid => {
                                                                        const articles = document.querySelectorAll('.articles.articles__horizontal')[1]
                                                                        articles.innerHTML += `<article class="article" <?php
    if ($index >= 2)
        echo 'style="display: none;"';
?>>
    <div class="article__image" style="background-image: url('${'<?php the_field('image') ?>'}');"></div>

    <div class="article__info">
        <a href="/article/?userid=${userid}&id=<?= $id ?>"><?php the_title() ?></a>
        <div class="article__info__meta">
            <small>Категория: <?php the_field('category') ?></small><br>
            <small style="position: relative; top: 9px;">Стоимость: <?php the_field('price') ?> ₴</small>
        </div>
    </div>

    <div class="custom-button buy-button" onclick="makePayment('<?= $id ?>', '<?php the_field('price'); ?>')">
        <span>Купить статью</span>
    </div>
</article>`
                                                                    }
                                                                })

                                                                flag = true
                                                                <?php $index++ ?>
                                                                console.log('<?= $index ?>');
                                                            }
                                                        </script>

                                                        <?php
                                                        $id++;
                                                    }
                                                    wp_reset_postdata();
                                                ?>

                                                <script>
                                                    if (!flag) {
                                                        const block = document.querySelectorAll('.block')[1]
                                                        block.style.display = 'none'
                                                    }
                                                </script>
                                            </article>
                                        </div>
                                    </div>
                                <?php }
                            ?>
                        </section>
            
                        <section class="content__right col-md-4">
                            <?php get_sidebar(); ?>
                        </section>
                    </div>
                </div>
            </div>

            <?php get_footer(); ?>
        </div>

        <script>
            var a = document.createElement('a')

            function makePayment(index, amount) {
                window.location.href = `/wp-content/themes/Blog/make-payment.php?index=${index}&amount=${amount}`;
            }

            function buttonClick(hasPage, page) {
                var href = '/articles/'
                var cat = '<?php echo $cat_get ?>'
                
                if (hasPage || (
                    !hasPage && cat
                )) {href += '?'}

                if (hasPage) {href += `pages=${page}`}
                if (hasPage && cat) {href += '&'}
                if (cat) {href += `category=${cat}`}

                a.setAttribute('href', href)
                a.click()
            }

            function showPaidArticles() {
                const hideArticles = document.querySelectorAll('.articles.articles__horizontal')[1].querySelectorAll('.article[style="display: none;"]')
                hideArticles.forEach(hideArticle => hideArticle.style.removeProperty('display'))
            }

            function insertMark(string, pos, len) {
                return string.slice(0, pos) + '<mark>' + string.slice(pos, pos + len) + '</mark>' + string.slice(pos + len);
            }

            const articleTitltes = document.querySelector('.block').querySelectorAll('.article__title')
            if (searchQuery && articleTitltes) {
                articleTitltes.forEach(articleTitle => {
                    var title = articleTitle.innerText
                    console.log(searchQuery)
                    articleTitle.innerHTML = insertMark(title, title.toLowerCase().search(searchQuery), searchQuery.length)
                })
            }
        </script>
    </body>
</html>