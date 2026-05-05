/**
 * aofinance — Main JavaScript
 * Handles: Tab navigation, Hamburger menu, Keyword chips,
 *           Search input, Scroll animations, Header shadow
 */

'use strict';

/* ============================================================
   Utility
============================================================ */
const $ = (selector, root = document) => root.querySelector(selector);
const $$ = (selector, root = document) => [...root.querySelectorAll(selector)];

/* ============================================================
   1. Tab Navigation
   - Desktop nav links (.nav-link)
   - Mobile nav links (.nav-mobile-link)
   - CTA / Hero buttons with [data-tab]
   - Footer links with [data-tab]
============================================================ */
function initTabs() {
  const sections  = $$('.tab-section');
  const navLinks  = $$('[data-tab]');           // all elements with data-tab

  function activateTab(tabId) {
    // Update sections
    sections.forEach(sec => {
      sec.classList.toggle('active', sec.id === tabId);
    });

    // Update nav links
    navLinks.forEach(link => {
      const isActive = link.dataset.tab === tabId;
      link.classList.toggle('active', isActive);
    });

    // Scroll to top
    window.scrollTo({ top: 0, behavior: 'smooth' });

    // Update URL hash without triggering a jump
    history.replaceState(null, '', `#${tabId}`);
  }

  navLinks.forEach(link => {
    link.addEventListener('click', e => {
      const tab = link.dataset.tab;
      if (!tab) return;
      e.preventDefault();
      activateTab(tab);

      // Close mobile menu if open
      closeMobileMenu();
    });
  });

  // Handle initial hash on page load
  const initialHash = location.hash.replace('#', '');
  const validTabs   = ['home', 'articles', 'quiz'];
  if (validTabs.includes(initialHash)) {
    activateTab(initialHash);
  } else {
    activateTab('home');
  }
}

/* ============================================================
   2. Hamburger Menu
============================================================ */
function initHamburger() {
  const hamburger = $('#hamburger');
  const navMobile = $('#nav-mobile');
  if (!hamburger || !navMobile) return;

  hamburger.addEventListener('click', () => {
    const isOpen = navMobile.classList.toggle('open');
    hamburger.classList.toggle('open', isOpen);
    hamburger.setAttribute('aria-expanded', String(isOpen));
    hamburger.setAttribute('aria-label', isOpen ? 'メニューを閉じる' : 'メニューを開く');
  });

  // Close on outside click
  document.addEventListener('click', e => {
    if (!hamburger.contains(e.target) && !navMobile.contains(e.target)) {
      closeMobileMenu();
    }
  });
}

function closeMobileMenu() {
  const hamburger = $('#hamburger');
  const navMobile = $('#nav-mobile');
  if (!hamburger || !navMobile) return;
  navMobile.classList.remove('open');
  hamburger.classList.remove('open');
  hamburger.setAttribute('aria-expanded', 'false');
  hamburger.setAttribute('aria-label', 'メニューを開く');
}

/* ============================================================
   3. Keyword Chips & Search Input
   - Clicking a chip highlights it and "filters" (future use)
   - Clicking a category card sets the corresponding chip
   - Search input highlights matching chip text
============================================================ */
function initSearch() {
  const chips      = $$('.chip');
  const searchInput = $('#search-input');
  const searchClear = $('#search-clear');
  const catCards   = $$('.cat-card');

  // --- Chip click ---
  chips.forEach(chip => {
    chip.addEventListener('click', () => {
      chips.forEach(c => c.classList.remove('active'));
      chip.classList.add('active');

      const keyword = chip.dataset.keyword;
      if (searchInput) {
        searchInput.value = keyword === 'すべて' ? '' : keyword;
        toggleClearBtn();
      }

      // Visual feedback: pulse
      chip.animate([
        { transform: 'scale(1)' },
        { transform: 'scale(1.08)' },
        { transform: 'scale(1)' },
      ], { duration: 220, easing: 'ease-out' });
    });
  });

  // --- Search input ---
  if (searchInput) {
    searchInput.addEventListener('input', () => {
      const val = searchInput.value.trim();
      toggleClearBtn();

      if (!val) {
        // Reset to "すべて"
        setActiveChip('すべて');
        return;
      }

      // Check if value matches a chip keyword
      let matched = false;
      chips.forEach(chip => {
        const kw = chip.dataset.keyword;
        const isMatch = kw !== 'すべて' && kw.includes(val);
        chip.classList.toggle('active', isMatch);
        if (isMatch) matched = true;
      });
      if (!matched) chips.forEach(c => c.classList.remove('active'));
    });

    searchInput.addEventListener('keydown', e => {
      if (e.key === 'Escape') {
        searchInput.value = '';
        toggleClearBtn();
        setActiveChip('すべて');
        searchInput.blur();
      }
    });
  }

  // --- Clear button ---
  if (searchClear) {
    searchClear.addEventListener('click', () => {
      if (searchInput) searchInput.value = '';
      toggleClearBtn();
      setActiveChip('すべて');
      searchInput && searchInput.focus();
    });
  }

  // --- Category card click → switch to articles tab (future) + set chip ---
  catCards.forEach(card => {
    card.addEventListener('click', () => {
      const kw = card.dataset.keyword;
      setActiveChip(kw);
      if (searchInput) {
        searchInput.value = kw;
        toggleClearBtn();
      }

      // Scroll to search section smoothly
      const searchSection = $('.search-section');
      if (searchSection) {
        searchSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
      }
    });

    // Keyboard accessibility
    card.addEventListener('keydown', e => {
      if (e.key === 'Enter' || e.key === ' ') {
        e.preventDefault();
        card.click();
      }
    });
  });

  function setActiveChip(keyword) {
    chips.forEach(c => {
      c.classList.toggle('active', c.dataset.keyword === keyword);
    });
  }

  function toggleClearBtn() {
    if (!searchInput || !searchClear) return;
    searchClear.style.display = searchInput.value ? 'grid' : 'none';
  }
}

/* ============================================================
   4. Header Shadow on Scroll
============================================================ */
function initHeaderScroll() {
  const header = $('#site-header');
  if (!header) return;

  const onScroll = () => {
    header.classList.toggle('scrolled', window.scrollY > 10);
  };

  window.addEventListener('scroll', onScroll, { passive: true });
  onScroll(); // run once on load
}

/* ============================================================
   5. Scroll-triggered Fade-Up Animations
============================================================ */
function initFadeUp() {
  // Add fade-up class to target elements
  const targets = [
    '.cat-card',
    '.step-card',
    '.stat-card',
    '.cta-card',
    '.section-title',
    '.section-desc',
    '.search-bar-wrap',
    '.keyword-chips',
  ];

  targets.forEach(selector => {
    $$(selector).forEach(el => el.classList.add('fade-up'));
  });

  // IntersectionObserver
  const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        entry.target.classList.add('visible');
        observer.unobserve(entry.target);
      }
    });
  }, {
    threshold: 0.1,
    rootMargin: '0px 0px -40px 0px',
  });

  $$('.fade-up').forEach(el => observer.observe(el));
}

/* ============================================================
   6. Staggered Animation for Grids
============================================================ */
function initStagger() {
  // Category cards stagger
  $$('.cat-card').forEach((card, i) => {
    card.style.transitionDelay = `${i * 60}ms`;
  });

  // Keyword chips stagger
  $$('.chip').forEach((chip, i) => {
    chip.style.transitionDelay = `${i * 40}ms`;
  });
}

/* ============================================================
   7. Chart animation (SVG path draw)
============================================================ */
function initChartAnimation() {
  const path = $('.chart-svg path[stroke="#60a5fa"]');
  if (!path) return;

  const length = path.getTotalLength();
  path.style.strokeDasharray  = length;
  path.style.strokeDashoffset = length;
  path.style.transition = 'stroke-dashoffset 1.8s cubic-bezier(0.4,0,0.2,1) 0.4s';

  // Trigger after a short delay
  requestAnimationFrame(() => {
    setTimeout(() => {
      path.style.strokeDashoffset = '0';
    }, 300);
  });
}

/* ============================================================
   8. Active nav highlight on hash change
============================================================ */
function initHashChange() {
  window.addEventListener('hashchange', () => {
    const hash = location.hash.replace('#', '');
    const validTabs = ['home', 'articles', 'quiz'];
    if (validTabs.includes(hash)) {
      // Re-trigger tab activation without duplicating logic
      const link = $(`[data-tab="${hash}"]`);
      if (link) link.click();
    }
  });
}

/* ============================================================
   9. Smooth appearance for hero elements
============================================================ */
function initHeroEntrance() {
  const elements = [
    '.hero-badge',
    '.hero-title',
    '.hero-sub',
    '.hero-actions',
    '.hero-stats',
    '.chart-mock',
  ];

  elements.forEach((selector, i) => {
    const el = $(selector);
    if (!el) return;
    el.style.opacity   = '0';
    el.style.transform = 'translateY(20px)';
    el.style.transition = `opacity 0.6s ease ${i * 0.1}s, transform 0.6s ease ${i * 0.1}s`;

    // Trigger in next frame
    requestAnimationFrame(() => {
      setTimeout(() => {
        el.style.opacity   = '1';
        el.style.transform = 'translateY(0)';
      }, 50);
    });
  });
}

/* ============================================================
   Init
============================================================ */
document.addEventListener('DOMContentLoaded', () => {
  initTabs();
  initHamburger();
  initSearch();
  initHeaderScroll();
  initFadeUp();
  initStagger();
  initChartAnimation();
  initHashChange();
  initHeroEntrance();

  console.log('%c aofinance initialized ✓', 'color:#3b82f6;font-weight:bold;font-size:14px;');
});
