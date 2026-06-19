<?php
/**
 * painteau — page.php
 */
get_header();

if ( ! have_posts() ) {
    get_footer();
    exit;
}

the_post();
?>

<main id="main-content">
  <article class="pt-page-article" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
    <div class="pt-single__inner">
      <header class="pt-single-header">
        <h1 class="pt-single-title"><span class="pt-single-title__prefix" aria-hidden="true">// </span><?php the_title(); ?></h1>
      </header>
      <hr class="pt-rule">
      <div class="pt-single-content entry-content">
        <?php the_content(); ?>
      </div>
      <?php if ( comments_open() ) comments_template(); ?>
    </div>
  </article>
</main>

<?php get_footer(); ?>
