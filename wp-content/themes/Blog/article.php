<?php
	/* Template Name: article */
	require 'pages/includes/config.php';
	$template_url = $config['template-url'];
?>

<!DOCTYPE html>
<html lang="en">
	<head>
    	<meta charset="UTF-8">
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
								if (!isset($_GET['userid'])) {
									$article = mysqli_query($connection, 'SELECT * FROM articles WHERE id = ' . (int) $_GET['id']);
									
									if (mysqli_num_rows($article) == 0) {
										?>
										
										<div class="block">
											<h3>Увы, но такой статьи не существует :(</h3>
										</div>

										<?php
									}

									else if (mysqli_num_rows($article) > 0) {
										$art = mysqli_fetch_assoc($article);
										mysqli_query($connection, 'UPDATE articles SET `views` = `views` + 1 WHERE `id` = ' . (int) $art['id']);
										$comments = mysqli_query($connection, 'SELECT * FROM comments WHERE articles_id = ' . (int) $art['id'] . ' ORDER BY id DESC');
										?>

										<div class="block">
											<a><?php echo $art['views'] ?> просмотр<?php
												$views = (int) $art['views'];

												if ($views % 10 >= 5 || $views % 10 == 0 || ($views % 100 >= 11 && $views % 100 <= 14)) {echo 'ов';}
												else if ($views % 10 >= 2 && $views % 10 <= 4) {echo 'a';}
											?></a>

											<h3><?php echo $art['title'] ?></h3>
											<div class="block__content">
												<div class="image-block">
													<img src="<?= $template_url; ?>/static/<?php echo $art['image'] ?>" style='max-width: 100%; margin-top: 32px;'>
												</div>

												<div class="full-text" style='margin-top: 32px;'><?php echo $art['text'] ?></div>
											</div>
										</div>

										<div class="block" <?php
											if (mysqli_num_rows($comments) == 0)
												echo 'style="display: none;"';
										?>>
											<a href="#comment-add-form">Добавить комментарий</a>
											<h3>Комментарии</h3>
											<div class="block__content">
												<div class="articles articles__vertical" style="margin-top: 32px;">

												<?php
													while ($comment = mysqli_fetch_assoc($comments)) {
														?>

														<article class="article" style="margin-top: 20px;">
															<div class="article__image" style="background-image: url(https://www.gravatar.com/avatar/<?php echo md5($comment['email']) ?>)"></div>
															<div class="article__info">
																<a href="/article.php?id=<?php echo $comment['articles_id'] ?>"><?php echo $comment['author'] ?> (<?php echo $comment['nickname'] ?>)</a>
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

										<div id='comment-add-form' class="block">
											<h3>Добавить комментарий</h3>

											<div class="block__content" style='margin-top: 24px;'>
												<form class="form" method='post' action='<?= $template_url; ?>/send-comment.php'>
													<input type="hidden" name='id' value='<?= $art['id'] ?>'>

													<div class="form__group">
														<div class="row">
															<div class="col-md-4" style="margin-top: 8px;">
																<input type="text" name='name' class="form__control" placeholder="Имя" 
																	<?php
																		if (array_key_exists('name', $_POST)) {echo 'value="' . $_POST['name'] . '"';}
																	?>
																>
															</div>

															<div class="col-md-4" style="margin-top: 8px;">
																<input type="text" name='nickname' class="form__control" placeholder="Никнейм"
																	<?php
																		if (array_key_exists('nickname', $_POST)) {echo 'value="' . $_POST['nickname'] . '"';}
																	?>
																>
															</div>

															<div class="col-md-4" style="margin-top: 8px;">
																<input type="text" name='email' class="form__control" placeholder="Email" <?php
																	if (array_key_exists('email', $_POST)) {echo 'value="' . $_POST['email'] . '"';}
																?>>
															</div>
														</div>
													</div>

													<div class="form__group">
														<textarea name="text" class="form__control" placeholder="Текст комментария ..." oninput="commentCreating(this)"><?php if (array_key_exists('text', $_POST)) {echo $_POST['text'];} ?></textarea>
													</div>

													<div class="form__group">
														<input type="submit" name='do_post' class="form__control" value="Добавить комментарий">
													</div>
												</form>
											</div>
										</div>

										<?php
									}
								}

								else if (isset($_GET['id']) && isset($_GET['userid'])) {
									$posts = get_field('articles');

									if ($posts) {
										$index = 1; $flag = false;

										foreach($posts as $post) {
											setup_postdata($post);

											if ($index == (int) $_GET['id']) {
												$flag = true; $isPaid = false;
												$userid = $_GET['userid'];

												$guests = mysqli_query($connection, "SELECT * FROM `guests` WHERE `id` = '$userid'");
												$guest = mysqli_fetch_assoc($guests);
												$boughtArticles = json_decode($guest['bought_articles']);
												
												if (in_array((int) $_GET['id'], $boughtArticles))
													$isPaid = true;
											?>

											<div class="block">
												<a>Стоимость: <?php the_field('price'); ?> ₴</a>
												<h3><?php the_title(); ?></h3>

												<div class="block__content">
													<div class="image-block"><img></div>

													<div class="full-text" style='margin-top: 32px;'>
														<?php
															if ($isPaid) {
																?>
																<div class="full-text" style='margin-top: 32px;'><?php the_field('text'); ?></div>
																<?php
															}

															else {
																?>
																
																<p>Увы, но вы ещё не заплатили денег для того, чтобы купить эту статью :(</p>
																<div class="custom-button buy-button" style="margin-top: 32px; width: 149.1px;" onclick="makePayment('<?= $_GET['id'] ?>', '<?php the_field('price'); ?>')">
																	<span>Купить статью</span>
																</div>

																<?php
															}
														?>
													</div>
												</div>
											</div>

											<script>
												var articleImage = document.querySelector('img'),
													imageSrc = '<?php the_field('image'); ?>'
												articleImage.src = imageSrc.replace('http://blog.com', '')
											</script>

											<?php
												break;
											}

											$index++;
										}

										if (!$flag) {
											?>
									
											<div class="block">
												<h3>Увы, но такой статьи не существует :(</h3>
											</div>

											<?php
										}
									}
								}
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
			function makePayment(index, amount) {
                window.location.href = `/wp-content/themes/Blog/make-payment.php?index=${index}&amount=${amount}`;
            }
			
			function commentCreating(textarea) {
				textarea.style.height = '100px'
				textarea.style.height = textarea.scrollHeight + 2 + 'px'
			}
		</script>
  	</body>
</html>