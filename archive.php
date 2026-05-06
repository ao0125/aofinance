<?php
/**
 * archive.php — 記事一覧ページテンプレート
 * index.html の #articles タブのデザインをWordPressで再現
 */
get_header();

// 現在のカテゴリ情報
$current_cat      = get_queried_object();
$current_cat_slug = is_category() ? $current_cat->slug : '';
?>

<main id="main-content">
  <div class="articles-page container">

    <!-- ページタイトル -->
    <div class="articles-header">
      <h1 class="section-title" id="articles-heading">
        <span class="section-title-icon"><i class="fa-solid fa-newspaper"></i></span>
        <?php if ( is_category() ) : ?>
          <?php echo esc_html( $current_cat->name ); ?> の記事一覧
        <?php else : ?>
          記事一覧
        <?php endif; ?>
      </h1>
      <p class="section-desc">
        <?php if ( is_category() && $current_cat->description ) : ?>
          <?php echo esc_html( $current_cat->description ); ?>
        <?php else : ?>
          カテゴリから記事を探してみましょう。
        <?php endif; ?>
      </p>
    </div>

    <!-- カテゴリフィルター -->
    <div class="articles-filter" role="group" aria-label="カテゴリフィルター">
      <?php
      $filter_cats = [
        [ 'label' => 'すべて',        'icon' => 'fa-border-all',          'slug' => '' ],
        [ 'label' => 'コモディティ',   'icon' => 'fa-gem',                'slug' => 'commodity' ],
        [ 'label' => '株式',          'icon' => 'fa-chart-simple',        'slug' => 'stocks'    ],
        [ 'label' => '債券',          'icon' => 'fa-file-invoice-dollar', 'slug' => 'bonds'     ],
        [ 'label' => '投資信託',      'icon' => 'fa-layer-group',         'slug' => 'funds'     ],
        [ 'label' => '税金',          'icon' => 'fa-receipt',             'slug' => 'tax'       ],
        [ 'label' => '金融知識',      'icon' => 'fa-graduation-cap',      'slug' => 'knowledge' ],
        [ 'label' => '投資のヒント',   'icon' => 'fa-lightbulb',          'slug' => 'tips'      ],
      ];
      foreach ( $filter_cats as $fc ) :
        $is_active = ( $fc['slug'] === $current_cat_slug || ( $fc['slug'] === '' && ! is_category() ) );
        $link      = $fc['slug'] ? get_category_link( get_category_by_slug( $fc['slug'] ) ) : home_url( '/articles/' );
      ?>
      <a href="<?php echo esc_url( $link ); ?>"
         class="chip<?php echo $is_active ? ' active' : ''; ?>"
         aria-current="<?php echo $is_active ? 'page' : 'false'; ?>">
        <i class="fa-solid <?php echo esc_attr( $fc['icon'] ); ?>"></i>
        <?php echo esc_html( $fc['label'] ); ?>
      </a>
      <?php endforeach; ?>
    </div>

    <!-- 記事グリッド -->
    <div class="articles-grid" id="articles-grid">

      <?php if ( have_posts() ) : ?>
        <?php while ( have_posts() ) : the_post();
          $cats      = get_the_category();
          $cat_s     = $cats ? $cats[0] : null;
          $c_slug    = $cat_s ? $cat_s->slug  : 'commodity';
          $c_name    = $cat_s ? $cat_s->name  : 'コモディティ';
          $c_icon    = aofinance_category_icon( $c_slug );
          $c_color   = aofinance_category_color( $c_slug );
          $c_link    = $cat_s ? get_category_link( $cat_s->term_id ) : home_url( '/articles/' );
          $read_time = get_post_meta( get_the_ID(), '_aofinance_read_time', true ) ?: '約5分';
          $tags      = get_the_tags();
        ?>
        <article class="article-card" data-category="<?php echo esc_attr( $c_name ); ?>">
          <a href="<?php the_permalink(); ?>" class="article-card-link"
             aria-label="<?php echo esc_attr( get_the_title() ); ?>を読む">

            <!-- サムネイル -->
            <div class="article-card-thumb"
                 style="--thumb-color:<?php echo esc_attr( $c_color ); ?>;">
              <?php if ( has_post_thumbnail() ) : ?>
                <?php the_post_thumbnail( 'medium', [ 'style' => 'width:100%;height:100%;object-fit:cover;position:absolute;inset:0;opacity:.4;' ] ); ?>
              <?php endif; ?>
              <div class="article-card-thumb-icon">
                <i class="fa-solid <?php echo esc_attr( $c_icon ); ?>"></i>
              </div>
              <span class="article-card-cat-badge">
                <i class="fa-solid <?php echo esc_attr( $c_icon ); ?>"></i>
                <?php echo esc_html( $c_name ); ?>
              </span>
            </div>

            <!-- ボディ -->
            <div class="article-card-body">
              <h2 class="article-card-title"><?php the_title(); ?></h2>
              <p class="article-card-excerpt">
                <?php echo esc_html( aofinance_get_excerpt( get_the_ID(), 70 ) ); ?>
              </p>
              <div class="article-card-meta">
                <span><i class="fa-regular fa-calendar"></i>
                  <?php echo get_the_date( 'Y年n月j日' ); ?>
                </span>
                <span><i class="fa-regular fa-clock"></i>
                  <?php echo esc_html( $read_time ); ?>
                </span>
              </div>
              <?php if ( $tags ) : ?>
              <div class="article-card-tags">
                <?php foreach ( array_slice( $tags, 0, 4 ) as $tag ) : ?>
                <span class="tag"><?php echo esc_html( $tag->name ); ?></span>
                <?php endforeach; ?>
              </div>
              <?php endif; ?>
            </div>

            <div class="article-card-arrow">
              <i class="fa-solid fa-arrow-right"></i>
            </div>

          </a>
        </article>
        <?php endwhile; ?>

      <?php else : ?>
        <!-- 記事なし -->
        <div class="articles-empty" style="grid-column:1/-1;">
          <i class="fa-solid fa-magnifying-glass"></i>
          <p>該当する記事が見つかりませんでした。</p>
        </div>
      <?php endif; ?>

    </div><!-- /articles-grid -->

    <!-- ページネーション -->
    <?php aofinance_pagination(); ?>

  </div>
</main>

<?php get_footer(); ?>
