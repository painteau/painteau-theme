/* painteau — theme.js */
(function () {
  'use strict';

  // ── Search toggle ────────────────────────────────────────────────────────
  const searchToggle = document.querySelector('.nav-search-toggle');
  const searchBar    = document.querySelector('.nav-search-bar');
  const searchInput  = document.querySelector('.nav-search-input');

  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', () => {
      const hidden = searchBar.hasAttribute('hidden');
      if (hidden) {
        searchBar.removeAttribute('hidden');
        searchInput && searchInput.focus();
      } else {
        searchBar.setAttribute('hidden', '');
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !searchBar.hasAttribute('hidden')) {
        searchBar.setAttribute('hidden', '');
        searchToggle.focus();
      }
    });
  }

  // ── Mobile nav toggle ────────────────────────────────────────────────────
  const burger  = document.querySelector('.nav-burger');
  const siteNav = document.querySelector('.site-nav');

  if (burger && siteNav) {
    burger.addEventListener('click', () => {
      const expanded = burger.getAttribute('aria-expanded') === 'true';
      burger.setAttribute('aria-expanded', String(!expanded));
      siteNav.classList.toggle('is-open');
    });
  }

  // ── Dropdown nav ────────────────────────────────────────────────────────
  document.querySelectorAll('.nav__item-wrap--has-children').forEach((wrap) => {
    const btn      = wrap.querySelector('.nav__item--parent');
    const dropdown = wrap.querySelector('.nav__dropdown');
    if (!btn || !dropdown) return;

    btn.addEventListener('click', (e) => {
      e.stopPropagation();
      const open = dropdown.style.display === 'flex';
      dropdown.style.display = open ? '' : 'flex';
    });

    document.addEventListener('click', () => {
      dropdown.style.display = '';
    });
  });

  // ── Reveal on scroll ─────────────────────────────────────────────────────
  const revealEls = document.querySelectorAll('.reveal');
  if (revealEls.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, i) => {
          if (entry.isIntersecting) {
            // Staggered delay based on index
            const delay = (i % 5) * 60;
            setTimeout(() => entry.target.classList.add('is-visible'), delay);
            observer.unobserve(entry.target);
          }
        });
      },
      { threshold: 0.05, rootMargin: '0px 0px -40px 0px' }
    );
    revealEls.forEach((el) => observer.observe(el));
  } else {
    revealEls.forEach((el) => el.classList.add('is-visible'));
  }

})();
