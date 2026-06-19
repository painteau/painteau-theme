<?php
/**
 * painteau — home.php
 */
get_header();
?>

<main id="main-content">
<div class="pt-section__inner">

  <h1 class="pt-page-title">// articles</h1>

  <?php if ( have_posts() ) : ?>
  <div class="pt-list">
    <?php while ( have_posts() ) : the_post(); ?>
    <article class="pt-list-item reveal" id="post-<?php the_ID(); ?>" <?php post_class( 'pt-list-item' ); ?>>
      <div class="pt-list-item__meta">
        <time class="pt-list-item__date" datetime="<?php the_date( 'c' ); ?>"><?php the_date( 'Y-m-d' ); ?></time>
        <?php $cats = get_the_category();
        if ( $cats ) : ?>
          <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="pt-tag">[<?php echo esc_html( $cats[0]->name ); ?>]</a>
        <?php endif; ?>
        <span class="pt-list-item__time"><?php echo painteau_reading_time(); ?></span>
      </div>
      <div class="pt-list-item__content">
        <h2 class="pt-list-item__title">
          <span class="pt-list-item__prefix" aria-hidden="true">// </span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>
        <?php if ( has_excerpt() ) : ?>
        <p class="pt-list-item__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 20 ) ); ?></p>
        <?php endif; ?>
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
  <p class="pt-no-results"><?php esc_html_e( 'Aucun article trouvé.', 'painteau' ); ?></p>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
