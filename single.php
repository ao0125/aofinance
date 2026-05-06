<?php
/**
 * single.php — 記事詳細ページテンプレート
 * articles/gold-commodity.html のデザインをWordPressで再現
 */
get_header();

// 記事メタ情報を取得
$read_time  = get_post_meta( get_the_ID(), '_aofinance_read_time',  true ) ?: '約5分';
$chart_data = get_post_meta( get_the_ID(), '_aofinance_chart_data', true );
$categories = get_the_category();
$cat        = $categories ? $categories[0] : null;
$cat_name   = $cat ? $cat->name  : 'コモディティ';
$cat_slug   = $cat ? $cat->slug  : 'commodity';
$cat_icon   = aofinance_category_icon( $cat_slug );
$cat_color  = aofinance_category_color( $cat_slug );
$cat_link   = $cat ? get_category_link( $cat->term_id ) : home_url( '/articles/' );
?>

<main class="article-main">

  <!-- ---- Article Hero ---- -->
  <div class="article-hero">
    <div class="article-hero-bg" aria-hidden="true">
      <div class="ah-shape ah-shape-1"></div>
      <div class="ah-shape ah-shape-2"></div>
      <div class="ah-shape ah-shape-3"></div>
    </div>
    <div class="article-hero-inner container">

      <div class="article-meta-top">
        <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>" class="article-back-link">
          <i class="fa-solid fa-arrow-left"></i> 記事一覧へ
        </a>
        <a href="<?php echo esc_url( $cat_link ); ?>" class="article-category-badge">
          <i class="fa-solid <?php echo esc_attr( $cat_icon ); ?>"></i>
          <?php echo esc_html( $cat_name ); ?>
        </a>
      </div>

      <h1 class="article-hero-title"><?php the_title(); ?></h1>

      <div class="article-hero-meta">
        <span><i class="fa-regular fa-calendar"></i>
          <?php echo get_the_date( 'Y年n月j日' ); ?>
        </span>
        <span><i class="fa-regular fa-clock"></i>
          読了時間：<?php echo esc_html( $read_time ); ?>
        </span>
        <?php if ( $cat ) : ?>
        <span><i class="fa-solid fa-tag"></i>
          <?php echo esc_html( $cat_name ); ?>
        </span>
        <?php endif; ?>
      </div>

    </div>
  </div>

  <!-- ---- Article Body ---- -->
  <div class="article-body-wrap container">
    <div class="article-layout">

      <!-- ===== 本文 ===== -->
      <article class="article-content" id="article-content">

        <?php if ( has_excerpt() ) : ?>
        <p class="article-lead"><?php the_excerpt(); ?></p>
        <?php endif; ?>

        <?php
        // Chart.jsデータがある場合はscriptタグをインライン注入
        if ( $chart_data ) :
          echo '<script>window.aoChartData = ' . wp_json_encode( json_decode( $chart_data ) ) . ';</script>';
        endif;
        ?>

        <?php the_content(); ?>

        <!-- タグ一覧 -->
        <?php
        $tags = get_the_tags();
        if ( $tags ) :
        ?>
        <div class="article-tag-wrap" style="margin-top:2rem;">
          <div class="article-card-tags">
            <?php foreach ( $tags as $tag ) : ?>
            <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
              <?php echo esc_html( $tag->name ); ?>
            </a>
            <?php endforeach; ?>
          </div>
        </div>
        <?php endif; ?>

      </article>

      <!-- ===== サイドバー ===== -->
      <aside class="article-sidebar" aria-label="サイドバー">

        <!-- 目次（自動生成）-->
        <div class="sidebar-card toc-card" id="toc-card">
          <div class="sidebar-card-header">
            <i class="fa-solid fa-list"></i> 目次
          </div>
          <nav aria-label="記事目次" id="toc-nav">
            <ol class="toc-list" id="toc-list">
              <!-- JavaScriptで自動生成 -->
            </ol>
          </nav>
        </div>

        <!-- 記事情報 -->
        <div class="sidebar-card">
          <div class="sidebar-card-header">
            <i class="fa-solid fa-circle-info"></i> 記事情報
          </div>
          <dl class="article-info-list">
            <dt>カテゴリ</dt>
            <dd>
              <a href="<?php echo esc_url( $cat_link ); ?>"
                 class="badge badge--commodity"
                 style="--cat-color:<?php echo esc_attr( $cat_color ); ?>;">
                <i class="fa-solid <?php echo esc_attr( $cat_icon ); ?>"></i>
                <?php echo esc_html( $cat_name ); ?>
              </a>
            </dd>
            <?php if ( $tags ) : ?>
            <dt>タグ</dt>
            <dd>
              <div class="tag-list">
                <?php foreach ( $tags as $tag ) : ?>
                <a href="<?php echo esc_url( get_tag_link( $tag->term_id ) ); ?>" class="tag">
                  <?php echo esc_html( $tag->name ); ?>
                </a>
                <?php endforeach; ?>
              </div>
            </dd>
            <?php endif; ?>
            <dt>公開日</dt>
            <dd><?php echo get_the_date( 'Y年n月j日' ); ?></dd>
            <dt>更新日</dt>
            <dd><?php echo get_the_modified_date( 'Y年n月j日' ); ?></dd>
          </dl>
        </div>

        <!-- 関連記事 -->
        <?php
        $related = new WP_Query([
          'category__in'   => wp_list_pluck( $categories, 'term_id' ),
          'post__not_in'   => [ get_the_ID() ],
          'posts_per_page' => 3,
          'orderby'        => 'rand',
        ]);
        if ( $related->have_posts() ) :
        ?>
        <div class="sidebar-card">
          <div class="sidebar-card-header">
            <i class="fa-solid fa-newspaper"></i> 関連記事
          </div>
          <ul class="related-list">
            <?php while ( $related->have_posts() ) : $related->the_post();
              $r_cats  = get_the_category();
              $r_slug  = $r_cats ? $r_cats[0]->slug : 'commodity';
              $r_icon  = aofinance_category_icon( $r_slug );
            ?>
            <li class="related-item">
              <a href="<?php the_permalink(); ?>" style="display:flex;gap:.75rem;align-items:flex-start;text-decoration:none;">
                <div class="related-icon">
                  <i class="fa-solid <?php echo esc_attr( $r_icon ); ?>"></i>
                </div>
                <div class="related-text">
                  <p class="related-title"><?php the_title(); ?></p>
                  <p class="related-status"><?php echo get_the_date( 'Y年n月j日' ); ?></p>
                </div>
              </a>
            </li>
            <?php endwhile; wp_reset_postdata(); ?>
          </ul>
        </div>
        <?php endif; ?>

      </aside>

    </div>
  </div>

  <!-- ---- 前後ナビ ---- -->
  <nav class="article-nav-bottom container" aria-label="記事ナビゲーション">
    <div style="display:flex;gap:1rem;flex-wrap:wrap;justify-content:space-between;">
      <?php
      $prev = get_previous_post();
      $next = get_next_post();
      ?>
      <?php if ( $prev ) : ?>
      <a href="<?php echo esc_url( get_permalink( $prev->ID ) ); ?>"
         class="article-nav-btn article-nav-btn--back">
        <i class="fa-solid fa-arrow-left"></i>
        <span><?php echo esc_html( get_the_title( $prev->ID ) ); ?></span>
      </a>
      <?php else : ?>
      <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>"
         class="article-nav-btn article-nav-btn--back">
        <i class="fa-solid fa-arrow-left"></i>
        <span>記事一覧へ戻る</span>
      </a>
      <?php endif; ?>
      <?php if ( $next ) : ?>
      <a href="<?php echo esc_url( get_permalink( $next->ID ) ); ?>"
         class="article-nav-btn"
         style="background:#fff;color:#1d4ed8;border:2px solid #bfdbfe;box-shadow:0 1px 3px rgba(0,0,0,.08);">
        <span><?php echo esc_html( get_the_title( $next->ID ) ); ?></span>
        <i class="fa-solid fa-arrow-right"></i>
      </a>
      <?php endif; ?>
    </div>
  </nav>

</main>

<!-- 目次を自動生成するスクリプト -->
<script>
document.addEventListener('DOMContentLoaded', function () {
  const content  = document.getElementById('article-content');
  const tocList  = document.getElementById('toc-list');
  if (!content || !tocList) return;

  const headings = content.querySelectorAll('h2, h3');
  if (!headings.length) { document.getElementById('toc-card').style.display = 'none'; return; }

  headings.forEach(function (h, i) {
    if (!h.id) h.id = 'heading-' + i;
    const li   = document.createElement('li');
    const link = document.createElement('a');
    link.href      = '#' + h.id;
    link.className = h.tagName === 'H3' ? 'toc-link toc-link--sub' : 'toc-link';
    link.textContent = h.textContent;
    link.addEventListener('click', function (e) {
      e.preventDefault();
      const top = h.getBoundingClientRect().top + window.scrollY - 90;
      window.scrollTo({ top: top, behavior: 'smooth' });
    });
    li.appendChild(link);
    tocList.appendChild(li);
  });

  // スクロール連動ハイライト
  const tocLinks = tocList.querySelectorAll('.toc-link');
  const observer = new IntersectionObserver(function (entries) {
    entries.forEach(function (entry) {
      if (entry.isIntersecting) {
        tocLinks.forEach(function (l) { l.classList.remove('active'); });
        const active = tocList.querySelector('[href="#' + entry.target.id + '"]');
        if (active) active.classList.add('active');
      }
    });
  }, { rootMargin: '-20% 0px -60% 0px' });

  headings.forEach(function (h) { observer.observe(h); });
});
</script>

<?php get_footer(); ?>
