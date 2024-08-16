<?php
    require 'pages/includes/config.php';
    $template_url = $config['template-url'];

    $connection = new mysqli('localhost', 'root', 'root', 'Blog');
    $onlineCount = $connection -> query('SELECT * FROM `online_users`') -> num_rows;
?>

<div class="block online-block">
    <h3>Сейчас онлайн: <span><?= $onlineCount ?></span></h3>
</div>

<div class="block">
    <h3>Поиск статей</h3>
    <div class="block__content">
        <form class="form search__box" method='get' action="/articles/">
            <div class="col-md-4 search__box" style="margin-top: 8px; padding-left: 0; padding-right: 0;">
				<input type="text" name="search_query" class="form__control" placeholder="Название статьи">
			</div>

            <div class="form__group">
				<input type="submit" class="form__control" value="Найти" style="margin-top: 20px;">
			</div>
        </form>
    </div>
</div>

<div class="block">
    <a onclick="showPopularArticles()">Все статьи</a>
    <h3>Топ читаемых статей</h3>
        <div class="block__content">
            <div class="articles articles__vertical articles-popular" style="margin-top: 32px;">

                <?php
                    $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY views DESC LIMIT 5");

                    while ( $art = mysqli_fetch_assoc($articles) ) {
                        ?>

                        <article class="article" style="margin-top: 16px;">
                            <div class="article__image" style="background-image: url(<?= $template_url ?>/static/<?= $art['image'] ?>);"></div>
                            <div class="article__info">
                                <a href="/article/?id=<?php echo $art['id'] ?>"><?php echo $art['title'] ?></a>
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

                                    <small>Категория: <a href="/articles?category=<?php echo $art_cat['id'] ?>"><?php echo $art_cat['title'] ?></a></small>
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

<div class="block">
    <a onclick="showComments()">Все комментарии</a>
    <h3>Комментарии</h3>
    <div class="block__content">
        <div class="articles articles__vertical comment" style="margin-top: 32px;">

        <?php
            $comments = mysqli_query($connection, "SELECT * FROM comments ORDER BY id DESC LIMIT 5");

            while ( $comment = mysqli_fetch_assoc($comments) ) {
                ?>

                <article class="article" style="margin-top: 20px;">
                    <div class="article__image" style="background-image: url(https://www.gravatar.com/avatar/<?php echo md5($comment['email']) ?>)"></div>
                    <div class="article__info">
                        <a href="/article/?id=<?php echo $comment['articles_id'] ?>"><?php echo $comment['author'] ?></a>
                        <div class="article__info__meta"></div>
                        <div class="article__info__preview"><?php echo $comment['text'] ?></div>
                    </div>
                </article>

                <?php
            }
        ?>
        </div>
    </div>
</div>

<script>
    function showPopularArticles() {
        var all_articles = ''

        <?php
            $articles = mysqli_query($connection, "SELECT * FROM articles ORDER BY views DESC");

            while ( $art = mysqli_fetch_assoc($articles) ) {
                $art_cat = false;

                foreach ($categories as $cat) {
                    if ($cat['id'] == $art['category_id']) {
                        $art_cat = $cat;
                        break;
                    }
                }
        ?>

        all_articles += `<?php echo "<article class='article' style='margin-top: 20px;'>
    <div class='article__image' style='background-image: url($template_url/static/" . $art['image'] . ");'></div>
    <div class='article__info'>
        <a href='/article/?id=" . $art['id'] . "'>" . $art['title'] . "</a>
        <div class='article__info__meta'>
            <small>Категория: <a href='/articles?category=" . $art_cat['id'] . "'>" . $art_cat['title'] . "</a></small>
        </div>
        <div class='article__info__preview'>" . mb_substr($art['text'], 0, 50, 'utf-8') . " ...</div>
    </div>
</article>"; ?>`
                <?php
            }
        ?>

        articles = document.querySelector('.articles-popular')
        articles.innerHTML = all_articles
    }

    function showComments() {
        var all_comments = ''

        <?php
            $comments = mysqli_query($connection, "SELECT * FROM comments ORDER BY id DESC");

            while ( $comment = mysqli_fetch_assoc($comments) ) {
                $art_cat = false;

                ?>

            all_comments += `<?php echo "<article class='article' style='margin-top: 20px;'>
    <div class='article__image' style='background-image: url(https://www.gravatar.com/avatar/" . md5($comment['email']) . "?>)'></div>
    <div class='article__info'>
        <a href='/article/?id=" . $comment['articles_id'] . "'>" . $comment['author'] . "</a>
        <div class='article__info__meta'></div>
        <div class='article__info__preview'>" . $comment['text'] . "</div>
    </div>
</article>" ?>`
                <?php
            }
        ?>

        comments = document.querySelector('.comment')
        comments.innerHTML = all_comments
    }

    function onlineCounter() {
        $.ajax({
            url: '<?= $template_url ?>/online-counter.php',
            type: 'post',
            data: {},
            success: onlineCount => {
                const span = document.querySelector('.online-block span')
                span.innerText = onlineCount
            }
        })
    }

    setInterval(onlineCounter, 1000)
</script>