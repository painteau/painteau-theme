<?php
/**
 * painteau — category.php
 */
get_header();
?>

<main id="main-content">
<div class="pt-section__inner">

  <header class="pt-archive-header">
    <h1 class="pt-page-title">// [<?php echo esc_html( single_cat_title( '', false ) ); ?>]</h1>
    <?php if ( category_description() ) : ?>
    <p class="pt-archive-desc"><?php echo wp_kses_post( category_description() ); ?></p>
    <?php endif; ?>
  </header>

  <?php if ( have_posts() ) : ?>
  <div class="pt-list">
    <?php while ( have_posts() ) : the_post(); ?>
    <article class="pt-list-item reveal" id="post-<?php the_ID(); ?>" <?php post_class( 'pt-list-item' ); ?>>
      <div class="pt-list-item__meta">
        <time class="pt-list-item__date" datetime="<?php the_date( 'c' ); ?>"><?php the_date( 'Y-m-d' ); ?></time>
      </div>
      <div class="pt-list-item__content">
        <h3 class="pt-list-item__title">
          <span class="pt-list-item__prefix">// </span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <?php if ( has_excerpt() ) : ?>
        <p class="pt-list-item__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
        <?php endif; ?>
      </div>
      <div class="pt-list-item__time"><?php echo painteau_reading_time(); ?></div>
    </article>
    <?php endwhile; ?>
  </div>

  <?php painteau_pagination(); ?>

  <?php else : ?>
  <p class="pt-no-results"><?php esc_html_e( 'Aucun article dans cette catégorie.', 'painteau' ); ?></p>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
