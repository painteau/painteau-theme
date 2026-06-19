<?php
/**
 * painteau — front-page.php
 */
get_header();

$paged    = max( 1, (int) get_query_var( 'paged' ) );
$per_page = 10;

$total_published = (int) wp_count_posts( 'post' )->publish;
$total_pages     = max( 1, (int) ceil( ( $total_published - 1 ) / $per_page ) );
$grid_offset     = ( $paged - 1 ) * $per_page + 1;
?>

<main id="main-content">

<?php if ( 1 === $paged ) :
  $featured = painteau_get_latest( 1, 0 );
  if ( $featured->have_posts() ) :
    $featured->the_post(); ?>

<section class="pt-featured">
  <?php if ( has_post_thumbnail() ) : ?>
  <div class="pt-featured__image">
    <a href="<?php the_permalink(); ?>">
      <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'fetchpriority' => 'high', 'alt' => esc_attr( get_the_title() ) ] ); ?>
    </a>
  </div>
  <?php endif; ?>

  <div class="pt-featured__inner">
    <div class="pt-featured__meta">
      <span class="pt-featured__label">// featured</span>
      <?php $cats = get_the_category();
      if ( $cats ) : ?>
        <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="pt-tag">[<?php echo esc_html( $cats[0]->name ); ?>]</a>
      <?php endif; ?>
    </div>

    <h1 class="pt-featured__title">
      <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
    </h1>

    <?php if ( has_excerpt() ) : ?>
    <p class="pt-featured__excerpt"><?php echo esc_html( wp_trim_words( get_the_excerpt(), 35 ) ); ?></p>
    <?php endif; ?>

    <div class="pt-featured__footer">
      <span class="pt-meta">[<?php echo get_the_date( 'Y-m-d' ); ?>]</span>
      <span class="pt-meta-sep">·</span>
      <span class="pt-meta"><?php echo painteau_reading_time(); ?></span>
      <a href="<?php the_permalink(); ?>" class="pt-btn-read">→ lire<span class="screen-reader-text"> l'article « <?php echo esc_html( get_the_title() ); ?> »</span></a>
    </div>
  </div>
</section>

<?php wp_reset_postdata(); endif; ?>
<?php endif; ?>

<!-- LISTE DES ARTICLES -->
<?php
$grid = painteau_get_latest( $per_page, $grid_offset );
if ( $grid->have_posts() ) : ?>
<section class="pt-section">
  <div class="pt-section__inner">

    <?php if ( 1 === $paged ) : ?>
    <h2 class="pt-section-title">// derniers articles</h2>
    <?php endif; ?>

    <div class="pt-list">
      <?php while ( $grid->have_posts() ) : $grid->the_post(); ?>
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
          <h3 class="pt-list-item__title">
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
      <?php endwhile; wp_reset_postdata(); ?>
    </div>

    <?php painteau_pagination( $total_pages ); ?>

  </div>
</section>
<?php endif; ?>

</main>

<?php get_footer(); ?>
