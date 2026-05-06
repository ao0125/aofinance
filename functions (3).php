<?php
/**
 * aofinance — functions.php
 * テーマの基本設定・CSS/JS読み込み・カスタム機能
 */

/* ============================================================
   1. テーマサポート設定
============================================================ */
function aofinance_setup() {
    // タイトルタグをWordPressに管理させる
    add_theme_support( 'title-tag' );

    // アイキャッチ画像を有効化
    add_theme_support( 'post-thumbnails' );

    // HTML5マークアップ
    add_theme_support( 'html5', [
        'search-form', 'comment-form', 'comment-list', 'gallery', 'caption',
    ]);

    // カスタムロゴ
    add_theme_support( 'custom-logo', [
        'height'      => 40,
        'width'       => 160,
        'flex-height' => true,
        'flex-width'  => true,
    ]);

    // ナビゲーションメニューを登録
    register_nav_menus([
        'primary' => 'メインナビゲーション',
        'footer'  => 'フッターナビゲーション',
    ]);

    // 抜粋の文字数
    add_filter( 'excerpt_length', fn() => 80 );
    add_filter( 'excerpt_more',   fn() => '…' );
}
add_action( 'after_setup_theme', 'aofinance_setup' );


/* ============================================================
   2. CSS / JS の読み込み
============================================================ */
function aofinance_enqueue_assets() {

    $ver = wp_get_theme()->get( 'Version' );
    $uri = get_template_directory_uri();

    // ---- Google Fonts ----
    wp_enqueue_style(
        'aofinance-fonts',
        'https://fonts.googleapis.com/css2?family=Noto+Sans+JP:wght@300;400;500;600;700&family=Inter:wght@300;400;500;600;700;800&display=swap',
        [],
        null
    );

    // ---- Font Awesome ----
    wp_enqueue_style(
        'font-awesome',
        'https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    // ---- メインCSS ----
    wp_enqueue_style(
        'aofinance-style',
        $uri . '/assets/css/style.css',
        [ 'aofinance-fonts', 'font-awesome' ],
        $ver
    );

    // ---- 記事ページCSS（single.phpのみ） ----
    if ( is_single() ) {
        wp_enqueue_style(
            'aofinance-article',
            $uri . '/assets/css/article.css',
            [ 'aofinance-style' ],
            $ver
        );
    }

    // ---- メインJS ----
    wp_enqueue_script(
        'aofinance-main',
        $uri . '/assets/js/main.js',
        [],
        $ver,
        true  // フッターで読み込み
    );

    // ---- 記事ページJS + Chart.js（single.phpのみ） ----
    if ( is_single() ) {
        wp_enqueue_script(
            'chartjs',
            'https://cdn.jsdelivr.net/npm/chart.js',
            [],
            '4',
            true
        );
        wp_enqueue_script(
            'aofinance-article',
            $uri . '/assets/js/article.js',
            [ 'chartjs' ],
            $ver,
            true
        );
    }
}
add_action( 'wp_enqueue_scripts', 'aofinance_enqueue_assets' );


/* ============================================================
   3. カスタム投稿タイプ・カテゴリ設定
============================================================ */

// カテゴリのスラッグと表示アイコンのマッピング（テンプレートで使用）
function aofinance_category_icon( $slug ) {
    $icons = [
        'stocks'       => 'fa-chart-simple',
        'bonds'        => 'fa-file-invoice-dollar',
        'funds'        => 'fa-layer-group',
        'commodity'    => 'fa-gem',
        'tax'          => 'fa-receipt',
        'knowledge'    => 'fa-graduation-cap',
        'tips'         => 'fa-lightbulb',
    ];
    return $icons[ $slug ] ?? 'fa-newspaper';
}

// カテゴリのカラーマッピング
function aofinance_category_color( $slug ) {
    $colors = [
        'stocks'       => '#3b82f6',
        'bonds'        => '#6366f1',
        'funds'        => '#0ea5e9',
        'commodity'    => '#f59e0b',
        'tax'          => '#10b981',
        'knowledge'    => '#8b5cf6',
        'tips'         => '#ec4899',
    ];
    return $colors[ $slug ] ?? '#3b82f6';
}


/* ============================================================
   4. カスタムフィールド（記事メタ情報）
============================================================ */
function aofinance_register_meta_boxes() {
    add_meta_box(
        'aofinance_article_meta',
        '記事メタ情報',
        'aofinance_render_meta_box',
        'post',
        'side',
        'high'
    );
}
add_action( 'add_meta_boxes', 'aofinance_register_meta_boxes' );

function aofinance_render_meta_box( $post ) {
    wp_nonce_field( 'aofinance_save_meta', 'aofinance_meta_nonce' );
    $read_time  = get_post_meta( $post->ID, '_aofinance_read_time',  true );
    $chart_data = get_post_meta( $post->ID, '_aofinance_chart_data', true );
    ?>
    <p>
        <label for="aofinance_read_time"><strong>読了時間（例：約5分）</strong></label><br>
        <input type="text" id="aofinance_read_time" name="aofinance_read_time"
               value="<?php echo esc_attr( $read_time ); ?>"
               style="width:100%;margin-top:4px;" placeholder="約5分">
    </p>
    <p style="margin-top:12px;">
        <label for="aofinance_chart_data"><strong>Chart.js データ（JSON）</strong></label><br>
        <textarea id="aofinance_chart_data" name="aofinance_chart_data"
                  rows="5" style="width:100%;margin-top:4px;font-size:11px;"
                  placeholder='{"type":"doughnut","labels":["投資","宝飾品"],"data":[43.5,32.7]}'
        ><?php echo esc_textarea( $chart_data ); ?></textarea>
        <small style="color:#888;">グラフを記事に埋め込む場合に使用</small>
    </p>
    <?php
}

function aofinance_save_meta( $post_id ) {
    if ( ! isset( $_POST['aofinance_meta_nonce'] ) ) return;
    if ( ! wp_verify_nonce( $_POST['aofinance_meta_nonce'], 'aofinance_save_meta' ) ) return;
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( ! current_user_can( 'edit_post', $post_id ) ) return;

    if ( isset( $_POST['aofinance_read_time'] ) ) {
        update_post_meta( $post_id, '_aofinance_read_time',
            sanitize_text_field( $_POST['aofinance_read_time'] ) );
    }
    if ( isset( $_POST['aofinance_chart_data'] ) ) {
        update_post_meta( $post_id, '_aofinance_chart_data',
            wp_kses_post( $_POST['aofinance_chart_data'] ) );
    }
}
add_action( 'save_post', 'aofinance_save_meta' );


/* ============================================================
   5. ウィジェットエリア登録
============================================================ */
function aofinance_widgets_init() {
    register_sidebar([
        'name'          => '記事サイドバー',
        'id'            => 'article-sidebar',
        'description'   => '記事詳細ページのサイドバー',
        'before_widget' => '<div class="sidebar-card" id="%1$s">',
        'after_widget'  => '</div>',
        'before_title'  => '<div class="sidebar-card-header">',
        'after_title'   => '</div>',
    ]);
}
add_action( 'widgets_init', 'aofinance_widgets_init' );


/* ============================================================
   6. カスタム抜粋（記事カード用）
============================================================ */
function aofinance_get_excerpt( $post_id = null, $length = 80 ) {
    $post = get_post( $post_id );
    if ( ! $post ) return '';
    if ( $post->post_excerpt ) {
        return wp_trim_words( $post->post_excerpt, $length );
    }
    return wp_trim_words( strip_shortcodes( $post->post_content ), $length );
}


/* ============================================================
   7. ページネーション
============================================================ */
function aofinance_pagination() {
    $args = [
        'prev_text' => '<i class="fa-solid fa-arrow-left"></i> 前のページ',
        'next_text' => '次のページ <i class="fa-solid fa-arrow-right"></i>',
        'type'      => 'list',
    ];
    $pagination = paginate_links( $args );
    if ( $pagination ) {
        echo '<nav class="pagination-wrap" aria-label="ページネーション">' . $pagination . '</nav>';
    }
}


/* ============================================================
   8. Open Graph / SEO メタタグ
============================================================ */
function aofinance_og_meta() {
    global $post;

    $title       = is_singular() ? get_the_title() : get_bloginfo( 'name' );
    $description = is_singular()
        ? aofinance_get_excerpt( $post->ID, 100 )
        : get_bloginfo( 'description' );
    $url         = is_singular() ? get_permalink() : home_url( '/' );
    $image       = is_singular() && has_post_thumbnail()
        ? get_the_post_thumbnail_url( $post->ID, 'large' )
        : get_template_directory_uri() . '/assets/images/og-default.png';

    ?>
    <meta property="og:title"       content="<?php echo esc_attr( $title ); ?>">
    <meta property="og:description" content="<?php echo esc_attr( $description ); ?>">
    <meta property="og:url"         content="<?php echo esc_url( $url ); ?>">
    <meta property="og:image"       content="<?php echo esc_url( $image ); ?>">
    <meta property="og:type"        content="<?php echo is_singular() ? 'article' : 'website'; ?>">
    <meta property="og:site_name"   content="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
    <meta name="twitter:card"       content="summary_large_image">
    <?php
}
add_action( 'wp_head', 'aofinance_og_meta' );


/* ============================================================
   9. bodyクラスにカスタムクラスを追加
============================================================ */
function aofinance_body_classes( $classes ) {
    if ( is_single() )   $classes[] = 'is-article';
    if ( is_archive() )  $classes[] = 'is-archive';
    if ( is_front_page() ) $classes[] = 'is-front';
    return $classes;
}
add_action( 'body_class', 'aofinance_body_classes' );


/* ============================================================
   10. 管理画面のフッタークレジットを変更
============================================================ */
add_filter( 'admin_footer_text', fn() => '<span>aofinance theme powered by <a href="https://wordpress.org">WordPress</a></span>' );
