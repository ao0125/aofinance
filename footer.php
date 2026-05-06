<!-- ========== FOOTER ========== -->
<footer class="site-footer">
  <div class="footer-inner container">

    <div class="footer-brand">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="aofinance ホームへ">
        <span class="logo-icon"><i class="fa-solid fa-chart-line"></i></span>
        <span class="logo-text">ao<span class="logo-accent">finance</span></span>
      </a>
      <p class="footer-tagline">「お金」をもっと身近に。「人生」をもっと豊かに。</p>
    </div>

    <nav class="footer-nav" aria-label="フッターナビゲーション">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>">ホーム</a>
      <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>">記事一覧</a>
      <a href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>">金融クイズ</a>
    </nav>

    <div class="footer-keywords">
      <span><i class="fa-solid fa-chart-simple"></i> 株式</span>
      <span><i class="fa-solid fa-file-invoice-dollar"></i> 債券</span>
      <span><i class="fa-solid fa-layer-group"></i> 投資信託</span>
      <span><i class="fa-solid fa-gem"></i> コモディティ</span>
      <span><i class="fa-solid fa-receipt"></i> 税金</span>
      <span><i class="fa-solid fa-graduation-cap"></i> 金融知識</span>
      <span><i class="fa-solid fa-lightbulb"></i> 投資のヒント</span>
    </div>

  </div>
  <div class="footer-bottom">
    <p>© <?php echo date( 'Y' ); ?> <?php bloginfo( 'name' ); ?>. All rights reserved.
      <?php if ( function_exists( 'the_privacy_policy_link' ) ) the_privacy_policy_link( ' | ' ); ?>
    </p>
  </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
