<?php
/**
 * index.php — フォールバックテンプレート
 * front-page.php / archive.php / single.php が適用されない場合に使用
 */
get_header();
?>

<main id="main-content">
  <div class="container" style="padding:4rem 0;">

    <?php if ( have_posts() ) : ?>

      <div class="articles-header">
        <h1 class="section-title">
          <span class="section-title-icon"><i class="fa-solid fa-newspaper"></i></span>
          <?php
          if ( is_search() ) {
            echo '「' . esc_html( get_search_query() ) . '」の検索結果';
          } elseif ( is_tag() ) {
            echo 'タグ：' . single_tag_title( '', false );
          } else {
            echo '記事一覧';
          }
          ?>
        </h1>
      </div>

      <div class="articles-grid" style="margin-top:2rem;">
        <?php while ( have_posts() ) : the_post();
          $cats     = get_the_category();
          $cat_s    = $cats ? $cats[0] : null;
          $c_slug   = $cat_s ? $cat_s->slug : 'commodity';
          $c_name   = $cat_s ? $cat_s->name : '';
          $c_icon   = aofinance_category_icon( $c_slug );
          $c_color  = aofinance_category_color( $c_slug );
          $c_link   = $cat_s ? get_category_link( $cat_s->term_id ) : home_url( '/articles/' );
          $read_time = get_post_meta( get_the_ID(), '_aofinance_read_time', true ) ?: '約5分';
        ?>
        <article class="article-card">
          <a href="<?php the_permalink(); ?>" class="article-card-link">
            <div class="article-card-thumb"
                 style="--thumb-color:<?php echo esc_attr( $c_color ); ?>;">
              <div class="article-card-thumb-icon">
                <i class="fa-solid <?php echo esc_attr( $c_icon ); ?>"></i>
              </div>
              <?php if ( $c_name ) : ?>
              <span class="article-card-cat-badge">
                <i class="fa-solid <?php echo esc_attr( $c_icon ); ?>"></i>
                <?php echo esc_html( $c_name ); ?>
              </span>
              <?php endif; ?>
            </div>
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
            </div>
            <div class="article-card-arrow">
              <i class="fa-solid fa-arrow-right"></i>
            </div>
          </a>
        </article>
        <?php endwhile; ?>
      </div>

      <?php aofinance_pagination(); ?>

    <?php else : ?>

      <div class="articles-empty">
        <i class="fa-solid fa-magnifying-glass"></i>
        <p>記事が見つかりませんでした。</p>
        <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
           class="btn btn-primary" style="margin-top:1.5rem;display:inline-flex;">
          <i class="fa-solid fa-house"></i> ホームへ戻る
        </a>
      </div>

    <?php endif; ?>

  </div>
</main>

<?php get_footer(); ?>
