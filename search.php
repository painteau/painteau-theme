<?php
/**
 * painteau — search.php
 */
get_header();
?>

<main id="main-content">
<div class="pt-section__inner">

  <header class="pt-archive-header">
    <?php if ( have_posts() ) : ?>
    <h1 class="pt-page-title">
      <?php printf(
        /* translators: %1$d = count, %2$s = query */
        esc_html( _n( '// %1$d résultat pour « %2$s »', '// %1$d résultats pour « %2$s »', $wp_query->found_posts, 'painteau' ) ),
        (int) $wp_query->found_posts,
        esc_html( get_search_query() )
      ); ?>
    </h1>
    <?php else : ?>
    <h1 class="pt-page-title">// aucun résultat</h1>
    <?php endif; ?>
  </header>

  <?php if ( have_posts() ) : ?>
  <div class="pt-list">
    <?php while ( have_posts() ) : the_post(); ?>
    <article class="pt-list-item reveal" id="post-<?php the_ID(); ?>" <?php post_class( 'pt-list-item' ); ?>>
      <div class="pt-list-item__meta">
        <time class="pt-list-item__date" datetime="<?php the_date( 'c' ); ?>"><?php the_date( 'Y-m-d' ); ?></time>
        <?php
        $cats = get_the_category();
        if ( $cats ) : ?>
          <a href="<?php echo esc_url( get_category_link( $cats[0]->term_id ) ); ?>" class="pt-tag">[<?php echo esc_html( $cats[0]->name ); ?>]</a>
        <?php endif; ?>
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
  <div class="pt-no-results-wrap">
    <p class="pt-no-results">
      <?php printf(
        /* translators: %s = query */
        esc_html__( 'Aucun résultat pour « %s ». Essayez avec d\'autres mots.', 'painteau' ),
        esc_html( get_search_query() )
      ); ?>
    </p>
    <form class="pt-search-form" role="search" method="get" action="<?php echo esc_url( home_url( '/' ) ); ?>">
      <span class="pt-search-form__prompt">$</span>
      <input type="search" name="s" placeholder="<?php esc_attr_e( 'grep -r …', 'painteau' ); ?>" value="<?php echo esc_attr( get_search_query() ); ?>">
      <button type="submit"><?php esc_html_e( 'search', 'painteau' ); ?></button>
    </form>
  </div>
  <?php endif; ?>

</div>
</main>

<?php get_footer(); ?>
