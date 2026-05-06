<?php
/**
 * front-page.php — トップページテンプレート
 * 現在の index.html のホームセクションをWordPressで再現
 */
get_header();
?>

<main id="main-content">

  <!-- ===== Hero ===== -->
  <div class="hero">
    <div class="hero-bg-shapes" aria-hidden="true">
      <div class="shape shape-1"></div>
      <div class="shape shape-2"></div>
      <div class="shape shape-3"></div>
      <div class="shape shape-4"></div>
    </div>
    <div class="hero-content container">
      <h1 class="hero-title">
        「お金」をもっと身近に。<br />
        「人生」をもっと豊かに。
      </h1>
      <p class="hero-sub">
        株式・債券・投資信託・税金など、複雑な金融の世界をわかりやすく解説。<br />
        初心者から経験者まで、あなたの「知りたい」に応えるブログです。
      </p>
      <div class="hero-actions">
        <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="btn btn-primary">
          <i class="fa-solid fa-book-open"></i> 記事を読む
        </a>
        <a href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>" class="btn btn-outline">
          <i class="fa-solid fa-circle-question"></i> クイズに挑戦
        </a>
      </div>
    </div>
  </div>

  <!-- ===== キーワード検索 ===== -->
  <section class="search-section container" aria-labelledby="search-heading">
    <h2 class="section-title" id="search-heading">
      <span class="section-title-icon"><i class="fa-solid fa-magnifying-glass"></i></span>
      キーワードで記事を探す
    </h2>
    <p class="section-desc">気になるテーマを選んで、関連記事をまとめてチェックしましょう。</p>

    <div class="search-bar-wrap">
      <div class="search-bar">
        <i class="fa-solid fa-magnifying-glass search-icon"></i>
        <input type="text" id="search-input" placeholder="キーワードを入力..." aria-label="記事を検索" />
        <button class="search-clear" id="search-clear" aria-label="クリア" style="display:none;">
          <i class="fa-solid fa-xmark"></i>
        </button>
      </div>
    </div>

    <div class="keyword-chips" role="group" aria-label="キーワードフィルター">
      <?php
      $chips = [
        [ 'keyword' => 'すべて',     'icon' => 'fa-border-all',          'active' => true  ],
        [ 'keyword' => '株式',       'icon' => 'fa-chart-simple',        'active' => false ],
        [ 'keyword' => '債券',       'icon' => 'fa-file-invoice-dollar', 'active' => false ],
        [ 'keyword' => '投資信託',   'icon' => 'fa-layer-group',         'active' => false ],
        [ 'keyword' => 'コモディティ','icon' => 'fa-gem',                'active' => false ],
        [ 'keyword' => '税金',       'icon' => 'fa-receipt',             'active' => false ],
        [ 'keyword' => '金融知識',   'icon' => 'fa-graduation-cap',      'active' => false ],
        [ 'keyword' => '投資のヒント','icon' => 'fa-lightbulb',          'active' => false ],
      ];
      foreach ( $chips as $chip ) :
        $active = $chip['active'] ? ' active' : '';
      ?>
      <button class="chip<?php echo $active; ?>" data-keyword="<?php echo esc_attr( $chip['keyword'] ); ?>">
        <i class="fa-solid <?php echo esc_attr( $chip['icon'] ); ?>"></i>
        <?php echo esc_html( $chip['keyword'] ); ?>
      </button>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ===== カテゴリ一覧 ===== -->
  <section class="categories-section container" aria-labelledby="cat-heading">
    <h2 class="section-title" id="cat-heading">
      <span class="section-title-icon"><i class="fa-solid fa-th-large"></i></span>
      カテゴリ一覧
    </h2>
    <p class="section-desc">あなたの興味に合わせてカテゴリを選んでください。</p>

    <div class="category-grid">
      <?php
      $categories = [
        [ 'keyword' => '株式',        'icon' => 'fa-chart-simple',        'tag' => 'Stocks',    'color' => '#3b82f6', 'slug' => 'stocks'    ],
        [ 'keyword' => '債券',        'icon' => 'fa-file-invoice-dollar', 'tag' => 'Bonds',     'color' => '#6366f1', 'slug' => 'bonds'     ],
        [ 'keyword' => '投資信託',    'icon' => 'fa-layer-group',         'tag' => 'Funds',     'color' => '#0ea5e9', 'slug' => 'funds'     ],
        [ 'keyword' => 'コモディティ', 'icon' => 'fa-gem',                'tag' => 'Commodity', 'color' => '#f59e0b', 'slug' => 'commodity' ],
        [ 'keyword' => '税金',        'icon' => 'fa-receipt',             'tag' => 'Tax',       'color' => '#10b981', 'slug' => 'tax'       ],
        [ 'keyword' => '金融知識',    'icon' => 'fa-graduation-cap',      'tag' => 'Knowledge', 'color' => '#8b5cf6', 'slug' => 'knowledge' ],
        [ 'keyword' => '投資のヒント', 'icon' => 'fa-lightbulb',          'tag' => 'Tips',      'color' => '#ec4899', 'slug' => 'tips'      ],
      ];
      foreach ( $categories as $cat ) :
        $cat_obj  = get_category_by_slug( $cat['slug'] );
        $cat_link = $cat_obj ? get_category_link( $cat_obj->term_id ) : home_url( '/articles/' );
      ?>
      <article class="cat-card"
               data-keyword="<?php echo esc_attr( $cat['keyword'] ); ?>"
               role="button" tabindex="0"
               aria-label="<?php echo esc_attr( $cat['keyword'] ); ?>カテゴリ"
               style="--cat-color:<?php echo esc_attr( $cat['color'] ); ?>;"
               onclick="location.href='<?php echo esc_url( $cat_link ); ?>'">
        <div class="cat-card-bg-icon" aria-hidden="true"><i class="fa-solid <?php echo esc_attr( $cat['icon'] ); ?>"></i></div>
        <div class="cat-card-header">
          <div class="cat-card-icon"><i class="fa-solid <?php echo esc_attr( $cat['icon'] ); ?>"></i></div>
          <span class="cat-card-tag"><?php echo esc_html( $cat['tag'] ); ?></span>
        </div>
        <h3 class="cat-card-title"><?php echo esc_html( $cat['keyword'] ); ?></h3>
        <span class="cat-card-arrow"><i class="fa-solid fa-arrow-right"></i></span>
      </article>
      <?php endforeach; ?>
    </div>
  </section>

  <!-- ===== 3ステップガイド ===== -->
  <section class="guide-section" aria-labelledby="guide-heading">
    <div class="container">
      <h2 class="section-title" id="guide-heading">
        <span class="section-title-icon"><i class="fa-solid fa-map"></i></span>
        aofinanceの使い方
      </h2>
      <p class="section-desc">3つのステップで、金融リテラシーを高めましょう。</p>
      <div class="steps-grid">
        <div class="step-card">
          <div class="step-num">01</div>
          <div class="step-icon"><i class="fa-solid fa-book-open"></i></div>
          <h3 class="step-title">記事を読む</h3>
          <p class="step-desc">興味あるカテゴリやキーワードから記事を探して、金融の基礎知識を身につけましょう。</p>
        </div>
        <div class="step-connector" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></div>
        <div class="step-card">
          <div class="step-num">02</div>
          <div class="step-icon"><i class="fa-solid fa-circle-question"></i></div>
          <h3 class="step-title">クイズで確認</h3>
          <p class="step-desc">学んだ内容を金融クイズで確認。楽しみながら理解度をチェックできます。</p>
        </div>
        <div class="step-connector" aria-hidden="true"><i class="fa-solid fa-chevron-right"></i></div>
        <div class="step-card">
          <div class="step-num">03</div>
          <div class="step-icon"><i class="fa-solid fa-rocket"></i></div>
          <h3 class="step-title">実践に活かす</h3>
          <p class="step-desc">知識を投資や家計管理に活かして、豊かな人生の第一歩を踏み出しましょう。</p>
        </div>
      </div>
    </div>
  </section>

  <!-- ===== CTA Banner ===== -->
  <section class="cta-section container" aria-labelledby="cta-heading">
    <div class="cta-card">
      <div class="cta-content">
        <h2 class="cta-title" id="cta-heading">
          <i class="fa-solid fa-chart-line"></i> 今日から始める金融学習
        </h2>
        <p class="cta-desc">記事・クイズ・カテゴリ検索を使って、あなたのペースで学んでいきましょう。</p>
      </div>
      <div class="cta-actions">
        <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="btn btn-white">
          <i class="fa-solid fa-newspaper"></i> 記事一覧へ
        </a>
        <a href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>" class="btn btn-outline-white">
          <i class="fa-solid fa-circle-question"></i> クイズへ
        </a>
      </div>
    </div>
  </section>

</main>

<?php get_footer(); ?>
