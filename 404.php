<?php
/**
 * painteau — 404.php
 */
get_header();
?>

<main id="main-content">
<div class="pt-404">
  <div class="pt-404__inner">
    <p class="pt-404__code">404</p>
    <h1 class="pt-404__title">// page_not_found</h1>
    <p class="pt-404__msg"><?php esc_html_e( 'Cette page n\'existe pas ou a été déplacée.', 'painteau' ); ?></p>
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="pt-btn-read">→ retour à l'accueil</a>
  </div>

  <?php
  $recent = painteau_get_latest( 5, 0 );
  if ( $recent->have_posts() ) : ?>
  <div class="pt-section__inner" style="margin-top: var(--sp-3xl)">
    <h2 class="pt-section-title">// articles récents</h2>
    <div class="pt-list">
      <?php while ( $recent->have_posts() ) : $recent->the_post(); ?>
      <article class="pt-list-item" id="post-<?php the_ID(); ?>">
        <div class="pt-list-item__meta">
          <time class="pt-list-item__date" datetime="<?php the_date( 'c' ); ?>"><?php the_date( 'Y-m-d' ); ?></time>
        </div>
        <div class="pt-list-item__content">
          <h3 class="pt-list-item__title">
            <span class="pt-list-item__prefix">// </span><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
          </h3>
        </div>
        <div class="pt-list-item__time"><?php echo painteau_reading_time(); ?></div>
      </article>
      <?php endwhile; wp_reset_postdata(); ?>
    </div>
  </div>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
