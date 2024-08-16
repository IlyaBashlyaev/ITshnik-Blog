<?php
  /* Template Name: copyright */
?>

<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <link rel="shortcut icon" href="<?php bloginfo('template_url') ?>/logo.png">
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
              <div class="block">
                <h3>Правообладателям</h3>

                <div class="block__content" style="margin-top: 32px;">
                  <div class="full-text">
                    Текст о копирайте ...
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

      <?php get_footer(); ?>
    </div>
  </body>
</html>