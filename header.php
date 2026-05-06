<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
  <meta charset="<?php bloginfo( 'charset' ); ?>" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>

<!-- ========== HEADER / NAV ========== -->
<header class="site-header" id="site-header">
  <div class="header-inner container">

    <!-- Logo -->
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo" aria-label="aofinance ホームへ">
      <span class="logo-icon"><i class="fa-solid fa-chart-line"></i></span>
      <span class="logo-text">ao<span class="logo-accent">finance</span></span>
    </a>

    <!-- Desktop Nav -->
    <nav class="nav-desktop" aria-label="メインナビゲーション">
      <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
         class="nav-link <?php echo is_front_page() ? 'active' : ''; ?>">
        <i class="fa-solid fa-house"></i> ホーム
      </a>
      <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>"
         class="nav-link <?php echo ( is_archive() || is_single() ) ? 'active' : ''; ?>">
        <i class="fa-solid fa-newspaper"></i> 記事一覧
      </a>
      <a href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>"
         class="nav-link <?php echo is_page( 'quiz' ) ? 'active' : ''; ?>">
        <i class="fa-solid fa-circle-question"></i> 金融クイズ
      </a>
    </nav>

    <!-- Hamburger (mobile) -->
    <button class="hamburger" id="hamburger" aria-label="メニューを開く" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>

  </div>

  <!-- Mobile Nav -->
  <nav class="nav-mobile" id="nav-mobile" aria-label="モバイルナビゲーション">
    <a href="<?php echo esc_url( home_url( '/' ) ); ?>"
       class="nav-mobile-link <?php echo is_front_page() ? 'active' : ''; ?>">
      <i class="fa-solid fa-house"></i> ホーム
    </a>
    <a href="<?php echo esc_url( home_url( '/articles/' ) ); ?>"
       class="nav-mobile-link <?php echo ( is_archive() || is_single() ) ? 'active' : ''; ?>">
      <i class="fa-solid fa-newspaper"></i> 記事一覧
    </a>
    <a href="<?php echo esc_url( home_url( '/quiz/' ) ); ?>"
       class="nav-mobile-link <?php echo is_page( 'quiz' ) ? 'active' : ''; ?>">
      <i class="fa-solid fa-circle-question"></i> 金融クイズ
    </a>
  </nav>
</header>
