<?php
/**
 * painteau — single.php
 */
get_header();

if ( ! have_posts() ) {
    get_footer();
    exit;
}

the_post();
?>

<main id="main-content" class="pt-single-wrap">
<article class="pt-single" id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
  <div class="pt-single__inner">

    <header class="pt-single-header">
      <div class="pt-single-header__meta">
        <?php $cats = get_the_category();
        if ( $cats ) : ?>
          <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="pt-tag">[<?php echo esc_html( $cats[0]->name ); ?>]</a>
        <?php endif; ?>
        <time class="pt-meta" datetime="<?php the_date( 'c' ); ?>">[<?php the_date( 'Y-m-d' ); ?>]</time>
        <span class="pt-meta"><?php echo painteau_reading_time(); ?></span>
      </div>

      <h1 class="pt-single-title"><span class="pt-single-title__prefix" aria-hidden="true">// </span><?php the_title(); ?></h1>

      <div class="pt-single-header__author">
        <span class="pt-meta-label">by</span> <span class="pt-meta"><?php the_author(); ?></span>
      </div>
    </header>

    <hr class="pt-rule">

    <?php if ( has_post_thumbnail() ) : ?>
    <figure class="pt-single-image">
      <?php the_post_thumbnail( 'full', [ 'loading' => 'eager', 'alt' => esc_attr( get_the_title() ) ] ); ?>
    </figure>
    <?php endif; ?>

    <div class="pt-single-content entry-content">
      <?php the_content(); ?>
    </div>

    <?php $tags = get_the_tags();
    if ( $tags ) : ?>
    <div class="pt-single-tags">
      <?php foreach ( $tags as $tag ) : ?>
        <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="pt-tag">[<?php echo esc_html( $tag->name ); ?>]</a>
      <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <nav class="pt-single-nav" aria-label="<?php esc_attr_e( 'Navigation entre articles', 'painteau' ); ?>">
      <?php
      $prev = get_previous_post();
      $next = get_next_post();
      if ( $prev ) : ?>
        <a href="<?php echo esc_url( get_permalink( $prev ) ); ?>" class="pt-single-nav__item pt-single-nav__item--prev">
          <span class="pt-single-nav__dir">← prev</span>
          <span class="pt-single-nav__title"><?php echo esc_html( get_the_title( $prev ) ); ?></span>
        </a>
      <?php endif;
      if ( $next ) : ?>
        <a href="<?php echo esc_url( get_permalink( $next ) ); ?>" class="pt-single-nav__item pt-single-nav__item--next">
          <span class="pt-single-nav__dir">next →</span>
          <span class="pt-single-nav__title"><?php echo esc_html( get_the_title( $next ) ); ?></span>
        </a>
      <?php endif; ?>
    </nav>

    <?php comments_template(); ?>

  </div>
</article>
</main>

<?php get_footer(); ?>
