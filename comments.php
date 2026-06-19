<?php
/**
 * painteau — comments.php
 */

if ( post_password_required() ) return;

$commenter = wp_get_current_commenter();
?>

<section class="pt-comments" id="comments">
  <div class="pt-comments__inner">

    <?php if ( have_comments() ) : ?>
    <h2 class="pt-comments-title">
      <?php printf(
        esc_html( _n( '// %s commentaire', '// %s commentaires', get_comments_number(), 'painteau' ) ),
        '<span>' . number_format_i18n( get_comments_number() ) . '</span>'
      ); ?>
    </h2>

    <ol class="pt-comment-list">
      <?php wp_list_comments( [
        'callback'    => 'painteau_comment',
        'style'       => 'ol',
        'short_ping'  => true,
        'avatar_size' => 36,
      ] ); ?>
    </ol>

    <?php the_comments_navigation( [
      'prev_text' => '&larr; ' . esc_html__( 'Commentaires plus anciens', 'painteau' ),
      'next_text' => esc_html__( 'Commentaires plus récents', 'painteau' ) . ' &rarr;',
    ] ); ?>

    <?php elseif ( comments_open() ) : ?>
    <p class="pt-comments-none"><?php esc_html_e( 'Soyez le premier à commenter.', 'painteau' ); ?></p>
    <?php endif; ?>

    <?php if ( comments_open() ) :
      comment_form( [
        'title_reply'          => esc_html__( '// laisser un commentaire', 'painteau' ),
        'title_reply_to'       => esc_html__( '// répondre à %s', 'painteau' ),
        'cancel_reply_link'    => esc_html__( 'annuler', 'painteau' ),
        'label_submit'         => esc_html__( 'publier', 'painteau' ),
        'class_submit'         => 'pt-btn-submit',
        'submit_button'        => '<button name="%1$s" type="submit" id="%2$s" class="%3$s">%4$s →</button>',
        'class_form'           => 'pt-comment-form',
        'id_form'              => 'commentform',
        'comment_notes_before' => '',
        'comment_notes_after'  => '',
        'logged_in_as'         => '',
        'comment_field'        => '<div class="pt-cf-field pt-cf-field--full"><label for="comment">' . esc_html__( 'commentaire', 'painteau' ) . ' <span aria-hidden="true">*</span></label><textarea id="comment" name="comment" rows="5" maxlength="65525" required></textarea></div>',
        'fields'               => [
          'author'  => '<div class="pt-cf-field"><label for="author">' . esc_html__( 'nom', 'painteau' ) . ' <span aria-hidden="true">*</span></label><input id="author" name="author" type="text" value="' . esc_attr( $commenter['comment_author'] ) . '" maxlength="245" autocomplete="name" required /></div>',
          'email'   => '<div class="pt-cf-field"><label for="email">' . esc_html__( 'email', 'painteau' ) . ' <span aria-hidden="true">*</span></label><input id="email" name="email" type="email" value="' . esc_attr( $commenter['comment_author_email'] ) . '" maxlength="100" autocomplete="email" required /></div>',
          'cookies' => '',
        ],
      ] );
    endif; ?>

  </div>
</section>
