<?php
/**
 * painteau — functions.php
 */

if ( ! defined( 'ABSPATH' ) ) exit;

// ── ENQUEUE ──────────────────────────────────────────────────────────────────
add_action( 'wp_enqueue_scripts', 'painteau_enqueue', 20 );

function painteau_enqueue() {
    // Google Fonts preconnect
    wp_enqueue_style( 'painteau-fonts-preconnect',        'https://fonts.googleapis.com', [], null );
    wp_enqueue_style( 'painteau-fonts-preconnect-gstatic', 'https://fonts.gstatic.com',   [], null );

    // JetBrains Mono — monospace for everything
    wp_enqueue_style(
        'painteau-google-fonts',
        'https://fonts.googleapis.com/css2?family=JetBrains+Mono:ital,wght@0,300;0,400;0,500;0,700;1,300;1,400&display=swap',
        [],
        null
    );

    // CSS principal
    wp_enqueue_style(
        'painteau-main',
        get_stylesheet_directory_uri() . '/assets/css/main.css',
        [ 'painteau-google-fonts' ],
        wp_get_theme()->get( 'Version' )
    );

    // JS principal (defer)
    wp_enqueue_script(
        'painteau-theme',
        get_stylesheet_directory_uri() . '/assets/js/theme.js',
        [],
        wp_get_theme()->get( 'Version' ),
        [ 'strategy' => 'defer', 'in_footer' => true ]
    );

    if ( is_singular() && comments_open() ) {
        wp_enqueue_script( 'comment-reply' );
    }
}

// Preconnect hints
add_action( 'wp_head', function() {
    echo '<link rel="preconnect" href="https://fonts.googleapis.com">' . "\n";
    echo '<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>' . "\n";
}, 1 );

// ── SETUP ─────────────────────────────────────────────────────────────────────
add_action( 'after_setup_theme', 'painteau_setup' );

function painteau_setup() {
    load_textdomain( 'painteau', get_template_directory() . '/languages/painteau-' . get_locale() . '.mo' );

    add_theme_support( 'title-tag' );
    add_theme_support( 'post-thumbnails' );
    add_theme_support( 'html5', [ 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption' ] );
    add_theme_support( 'custom-logo' );

    register_nav_menus( [
        'primary' => __( 'Menu principal', 'painteau' ),
        'footer'  => __( 'Footer', 'painteau' ),
    ] );
}

// ── CUSTOMIZER ───────────────────────────────────────────────────────────────
add_action( 'customize_register', 'painteau_customize_register' );

function painteau_customize_register( $wp_customize ) {
    $wp_customize->add_section( 'painteau_links', [
        'title'    => __( 'Liens du thème', 'painteau' ),
        'priority' => 110,
    ] );

    $link_settings = [
        'painteau_search_placeholder' => [ 'label' => __( 'Placeholder recherche', 'painteau' ), 'type' => 'text', 'sanitize' => 'sanitize_text_field', 'default' => 'Rechercher…' ],
    ];

    foreach ( $link_settings as $key => $cfg ) {
        $wp_customize->add_setting( $key, [ 'default' => $cfg['default'], 'sanitize_callback' => $cfg['sanitize'] ] );
        $wp_customize->add_control( $key, [ 'label' => $cfg['label'], 'section' => 'painteau_links', 'type' => $cfg['type'] ] );
    }

    // Réseaux sociaux
    $wp_customize->add_section( 'painteau_socials', [
        'title'    => __( 'Réseaux sociaux', 'painteau' ),
        'priority' => 120,
    ] );

    $socials = [
        'painteau_github'    => 'GitHub',
        'painteau_bluesky'   => 'Bluesky',
        'painteau_mastodon'  => 'Mastodon',
        'painteau_twitter'   => 'Twitter / X',
        'painteau_linkedin'  => 'LinkedIn',
        'painteau_rss'       => 'RSS Feed URL',
    ];

    foreach ( $socials as $key => $label ) {
        $wp_customize->add_setting( $key, [ 'default' => '', 'sanitize_callback' => 'esc_url_raw' ] );
        $wp_customize->add_control( $key, [ 'label' => $label, 'section' => 'painteau_socials', 'type' => 'url' ] );
    }
}

// ── HELPERS ──────────────────────────────────────────────────────────────────

function painteau_get_latest( $count = 6, $offset = 0 ) {
    return new WP_Query( [
        'posts_per_page' => $count,
        'offset'         => $offset,
        'post_status'    => 'publish',
        'orderby'        => 'date',
        'order'          => 'DESC',
    ] );
}

function painteau_reading_time() {
    $content = get_post_field( 'post_content', get_the_ID() );
    $words   = str_word_count( wp_strip_all_tags( $content ) );
    $minutes = max( 1, round( $words / 200 ) );
    /* translators: %d = nombre de minutes de lecture */
    return sprintf( _n( '%d min', '%d min', $minutes, 'painteau' ), $minutes );
}

// ── POSTS PER PAGE ───────────────────────────────────────────────────────────
add_action( 'pre_get_posts', 'painteau_posts_per_page' );
function painteau_posts_per_page( $query ) {
    if ( ! is_admin() && $query->is_main_query() ) {
        if ( $query->is_search() || $query->is_archive() || $query->is_home() ) {
            $query->set( 'posts_per_page', 10 );
        }
    }
}

// ── PAGINATION ───────────────────────────────────────────────────────────────
function painteau_pagination( $total_pages = 0 ) {
    $args = [
        'type'      => 'array',
        'prev_text' => '&larr;',
        'next_text' => '&rarr;',
    ];
    if ( $total_pages > 0 ) {
        $args['total'] = $total_pages;
    }
    $pages = paginate_links( $args );
    if ( ! $pages ) return;
    echo '<nav class="pt-pagination" aria-label="' . esc_attr__( 'Navigation des pages', 'painteau' ) . '">';
    foreach ( $pages as $page ) {
        echo $page;
    }
    echo '</nav>';
}

// ── COMMENTAIRES ─────────────────────────────────────────────────────────────
function painteau_comment( $comment, $args, $depth ) {
    $GLOBALS['comment'] = $comment;
    ?>
    <li id="comment-<?php comment_ID(); ?>" <?php comment_class( 'pt-comment-item' ); ?>>
      <div class="pt-comment-body">
        <div class="pt-comment-avatar">
          <?php echo get_avatar( $comment, 36, '', get_comment_author( $comment ), [ 'class' => 'pt-comment-avatar__img' ] ); ?>
        </div>
        <div class="pt-comment-content">
          <div class="pt-comment-meta">
            <span class="pt-comment-author"><?php comment_author_link( $comment ); ?></span>
            <span class="pt-comment-sep">·</span>
            <time class="pt-comment-date" datetime="<?php comment_date( 'c', $comment ); ?>"><?php comment_date( '', $comment ); ?></time>
            <?php if ( '0' === $comment->comment_approved ) : ?>
              <span class="pt-comment-awaiting"><?php esc_html_e( 'En attente de modération', 'painteau' ); ?></span>
            <?php endif; ?>
          </div>
          <div class="pt-comment-text"><?php comment_text( $comment ); ?></div>
          <?php comment_reply_link( array_merge( $args, [
            'depth'      => $depth,
            'max_depth'  => $args['max_depth'],
            'reply_text' => esc_html__( 'Répondre', 'painteau' ),
            'before'     => '<div class="pt-comment-reply">',
            'after'      => '</div>',
          ] ) ); ?>
        </div>
      </div>
    <?php
}

// ── NAV WALKER ───────────────────────────────────────────────────────────────
class painteau_Nav_Walker extends Walker_Nav_Menu {

    public function start_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '<div class="nav__dropdown">';
        }
    }

    public function end_lvl( &$output, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }

    public function start_el( &$output, $data_object, $depth = 0, $args = null, $current_object_id = 0 ) {
        $item         = $data_object;
        $classes      = empty( $item->classes ) ? [] : (array) $item->classes;
        $active       = in_array( 'current-menu-item', $classes ) ? ' is-active' : '';
        $is_current   = in_array( 'current-menu-item', $classes );
        $has_children = in_array( 'menu-item-has-children', $classes );

        if ( 0 === $depth ) {
            $output .= '<div class="nav__item-wrap' . ( $has_children ? ' nav__item-wrap--has-children' : '' ) . '">';
            if ( $has_children ) {
                $output .= sprintf(
                    '<button class="nav__item nav__item--parent%s" type="button" aria-haspopup="true" aria-expanded="false">%s <span class="nav__caret" aria-hidden="true">▾</span></button>',
                    esc_attr( $active ),
                    esc_html( $item->title )
                );
            } else {
                $output .= sprintf(
                    '<a href="%s" class="nav__item%s"%s>%s</a>',
                    esc_url( $item->url ),
                    esc_attr( $active ),
                    $is_current ? ' aria-current="page"' : '',
                    esc_html( $item->title )
                );
            }
        } elseif ( 1 === $depth ) {
            $output .= sprintf(
                '<a href="%s" class="nav__dropdown-item%s">%s</a>',
                esc_url( $item->url ),
                esc_attr( $active ),
                esc_html( $item->title )
            );
        }
    }

    public function end_el( &$output, $data_object, $depth = 0, $args = null ) {
        if ( 0 === $depth ) {
            $output .= '</div>';
        }
    }
}

// ── LOGIN PAGE ───────────────────────────────────────────────────────────────
add_action( 'login_enqueue_scripts', function() {
    ?>
    <style>
    :root {
        --accent: #16a34a;
        --bg:     #f7f7f3;
        --surface:#eeeee9;
        --border: rgba(22,163,74,.18);
        --fg:     #18181a;
        --fg-sec: #5a5a52;
    }
    body.login { background: var(--bg) !important; font-family: 'JetBrains Mono', monospace !important; }
    body.login #login { padding: 0 !important; width: 380px !important; }
    body.login #login h1 a {
        background-image: none !important;
        text-indent: 0 !important;
        color: var(--accent) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 20px !important;
        font-weight: 500 !important;
        width: auto !important;
        height: auto !important;
    }
    body.login #loginform,
    body.login #registerform,
    body.login #lostpasswordform {
        background: var(--surface) !important;
        border: 1px solid var(--border) !important;
        border-radius: 4px !important;
        box-shadow: 0 4px 24px rgba(0,0,0,.08) !important;
        padding: 28px 32px !important;
    }
    body.login label { color: var(--fg-sec) !important; font-size: 11px !important; font-weight: 400 !important; letter-spacing: .08em !important; text-transform: uppercase !important; }
    body.login input[type=text],
    body.login input[type=password],
    body.login input[type=email] {
        background: var(--bg) !important;
        border: 1px solid rgba(0,0,0,.12) !important;
        border-radius: 2px !important;
        color: var(--fg) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        padding: 10px 12px !important;
        box-shadow: none !important;
    }
    body.login input[type=text]:focus,
    body.login input[type=password]:focus,
    body.login input[type=email]:focus {
        border-color: var(--accent) !important;
        box-shadow: 0 0 0 2px rgba(22,163,74,.12) !important;
        outline: none !important;
    }
    body.login .button-primary {
        background: #15803d !important;
        border: none !important;
        border-radius: 2px !important;
        color: #fff !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 13px !important;
        font-weight: 500 !important;
        padding: 10px 0 !important;
        width: 100% !important;
        box-shadow: none !important;
        text-shadow: none !important;
        letter-spacing: .04em !important;
    }
    body.login .button-primary:hover { background: #15803d !important; }
    body.login #nav a, body.login #backtoblog a { color: var(--fg-sec) !important; font-size: 12px !important; }
    body.login #nav a:hover, body.login #backtoblog a:hover { color: var(--accent) !important; }
    body.login #login_error, body.login .message {
        background: var(--surface) !important;
        border-left: 3px solid var(--accent) !important;
        color: var(--fg-sec) !important;
        font-family: 'JetBrains Mono', monospace !important;
        font-size: 12px !important;
        box-shadow: none !important;
    }
    body.login input[type=checkbox] { accent-color: var(--accent) !important; }
    body.login h1 { padding-top: 32px !important; }
    </style>
    <?php
} );

add_filter( 'login_headerurl',  fn() => home_url( '/' ) );
add_filter( 'login_headertext', fn() => get_bloginfo( 'name' ) );

// ── AUTO-UPDATE — GitHub Releases ─────────────────────────────────────────────
add_filter( 'pre_set_site_transient_update_themes', 'painteau_check_theme_update' );
function painteau_check_theme_update( $transient ) {
    if ( empty( $transient->checked ) ) return $transient;

    $theme_slug    = get_option( 'stylesheet' );
    $current_ver   = wp_get_theme()->get( 'Version' );
    $transient_key = 'painteau_github_release';

    $release = get_transient( $transient_key );
    if ( false === $release ) {
        $response = wp_remote_get( 'https://api.github.com/repos/painteau/painteau-theme/releases/latest', [
            'timeout' => 10,
            'headers' => [
                'User-Agent'    => 'painteau-theme-updater',
                'Cache-Control' => 'no-cache, no-store',
                'Pragma'        => 'no-cache',
            ],
        ] );
        if ( is_wp_error( $response ) || 200 !== wp_remote_retrieve_response_code( $response ) ) {
            set_transient( $transient_key, [ 'tag_name' => $current_ver ], HOUR_IN_SECONDS );
            return $transient;
        }
        $release = json_decode( wp_remote_retrieve_body( $response ), true );
        set_transient( $transient_key, $release, HOUR_IN_SECONDS );
    }

    $latest_ver = ltrim( $release['tag_name'] ?? '', 'v' );
    if ( ! preg_match( '/^\d+\.\d+(\.\d+)?$/', $latest_ver ) ) return $transient;
    if ( version_compare( $latest_ver, $current_ver, '>' ) ) {
        $transient->response[ $theme_slug ] = [
            'theme'       => $theme_slug,
            'new_version' => $latest_ver,
            'url'         => 'https://github.com/painteau/painteau-theme',
            'package'     => 'https://github.com/painteau/painteau-theme/releases/download/v' . $latest_ver . '/painteau-theme.zip',
        ];
        $sha_url  = 'https://github.com/painteau/painteau-theme/releases/download/v' . $latest_ver . '/painteau-theme.zip.sha256';
        $sha_resp = wp_remote_get( $sha_url, [ 'timeout' => 10, 'headers' => [ 'User-Agent' => 'painteau-theme-updater' ] ] );
        if ( ! is_wp_error( $sha_resp ) && 200 === wp_remote_retrieve_response_code( $sha_resp ) ) {
            $sha = trim( wp_remote_retrieve_body( $sha_resp ) );
            if ( preg_match( '/^[a-f0-9]{64}$/', $sha ) ) {
                set_transient( 'painteau_expected_sha256', $sha, HOUR_IN_SECONDS );
            }
        }
    }

    return $transient;
}

add_filter( 'upgrader_pre_download', function( $reply, $package, $upgrader, $hook_extra ) {
    if ( ! isset( $hook_extra['theme'] ) || $hook_extra['theme'] !== get_option( 'stylesheet' ) ) {
        return $reply;
    }
    $expected_sha = get_transient( 'painteau_expected_sha256' );
    if ( ! $expected_sha ) return $reply;

    $tmpfile = download_url( $package );
    if ( is_wp_error( $tmpfile ) ) return $tmpfile;

    $actual_sha = hash_file( 'sha256', $tmpfile );
    if ( ! hash_equals( $expected_sha, $actual_sha ) ) {
        @unlink( $tmpfile );
        return new WP_Error( 'painteau_sha256_mismatch', __( 'Mise à jour annulée : checksum SHA256 invalide.', 'painteau' ) );
    }
    return $tmpfile;
}, 10, 4 );

add_filter( 'upgrader_source_selection', function( $source, $remote_source, $upgrader, $args ) {
    if ( ! isset( $args['hook_extra']['theme'] ) || $args['hook_extra']['theme'] !== get_option( 'stylesheet' ) ) {
        return $source;
    }
    $style_css = trailingslashit( $source ) . 'style.css';
    if ( ! file_exists( $style_css ) ) {
        return new WP_Error( 'painteau_update_invalid', __( 'Paquet thème invalide : style.css manquant.', 'painteau' ) );
    }
    $theme_data = get_file_data( $style_css, [ 'Theme Name' => 'Theme Name' ] );
    if ( $theme_data['Theme Name'] !== 'painteau' ) {
        return new WP_Error( 'painteau_update_invalid', __( 'Paquet thème invalide : nom incorrect.', 'painteau' ) );
    }
    return $source;
}, 10, 4 );

add_action( 'send_headers', function() {
    if ( headers_sent() ) return;
    header( 'X-Frame-Options: SAMEORIGIN' );
    header( 'X-Content-Type-Options: nosniff' );
    header( 'Referrer-Policy: strict-origin-when-cross-origin' );
} );

add_action( 'template_redirect', function() {
    if ( is_admin() ) return;
    if ( ! isset( $_SERVER['QUERY_STRING'] ) ) return;
    if ( ! preg_match( '/\bauthor=\d+\b/', $_SERVER['QUERY_STRING'] ) ) return;
    wp_redirect( home_url( '/' ), 302 );
    exit;
} );
