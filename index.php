<?php
/**
 * painteau — index.php (fallback)
 */
get_header();
?>

<main id="main-content">
<div class="pt-section__inner">
  <?php if ( have_posts() ) : ?>
  <div class="pt-list">
    <?php while ( have_posts() ) : the_post(); ?>
    <article class="pt-list-item reveal" id="post-<?php the_ID(); ?>" <?php post_class( 'pt-list-item' ); ?>>
      <div class="pt-list-item__meta">
        <time class="pt-list-item__date" datetime="<?php the_date( 'c' ); ?>"><?php the_date( 'Y-m-d' ); ?></time>
        <span class="pt-list-item__time"><?php echo painteau_reading_time(); ?></span>
      </div>
      <div class="pt-list-item__content">
        <h3 class="pt-list-item__title">
          <span class="pt-list-item__prefix" aria-hidden="true">// </span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
      </div>
      <div class="pt-list-item__thumb" aria-hidden="true">
        <?php if ( has_post_thumbnail() ) : ?>
        <a href="<?php the_permalink(); ?>" tabindex="-1"><?php the_post_thumbnail( 'thumbnail', [ 'loading' => 'lazy', 'alt' => '' ] ); ?></a>
        <?php endif; ?>
      </div>
    </article>
    <?php endwhile; ?>
  </div>
  <?php painteau_pagination(); ?>
  <?php else : ?>
  <p class="pt-no-results"><?php esc_html_e( 'Aucun article.', 'painteau' ); ?></p>
  <?php endif; ?>
</div>
</main>

<?php get_footer(); ?>
