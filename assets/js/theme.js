/* painteau — theme.js */
(function () {
  'use strict';

  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  // ── Search toggle ────────────────────────────────────────────────────────
  const searchToggle = document.querySelector('.nav-search-toggle');
  const searchBar    = document.querySelector('.nav-search-bar');
  const searchInput  = document.querySelector('.nav-search-input');

  if (searchToggle && searchBar) {
    searchToggle.addEventListener('click', () => {
      const hidden = searchBar.hasAttribute('hidden');
      if (hidden) {
        searchBar.removeAttribute('hidden');
        searchToggle.setAttribute('aria-expanded', 'true');
        searchInput && searchInput.focus();
      } else {
        searchBar.setAttribute('hidden', '');
        searchToggle.setAttribute('aria-expanded', 'false');
      }
    });

    document.addEventListener('keydown', (e) => {
      if (e.key === 'Escape' && !searchBar.hasAttribute('hidden')) {
        searchBar.setAttribute('hidden', '');
        searchToggle.setAttribute('aria-expanded', 'false');
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
      btn.setAttribute('aria-expanded', String(!open));
    });

    document.addEventListener('click', () => {
      dropdown.style.display = '';
      btn.setAttribute('aria-expanded', 'false');
    });

    btn.addEventListener('keydown', (e) => {
      if (e.key === 'Escape') {
        dropdown.style.display = '';
        btn.setAttribute('aria-expanded', 'false');
        btn.focus();
      }
    });
  });

  // ── Copy code buttons ─────────────────────────────────────────────────────
  document.querySelectorAll('.pt-single-content pre').forEach((pre) => {
    const btn = document.createElement('button');
    btn.className = 'pt-copy-btn';
    btn.setAttribute('aria-label', 'Copier le code');
    btn.textContent = 'copy';
    pre.appendChild(btn);

    btn.addEventListener('click', async () => {
      const code = pre.querySelector('code');
      const text = code ? code.textContent : pre.textContent;
      try {
        await navigator.clipboard.writeText(text);
        btn.textContent = 'copied!';
        btn.setAttribute('aria-label', 'Code copié');
        btn.classList.add('is-copied');
        setTimeout(() => {
          btn.textContent = 'copy';
          btn.setAttribute('aria-label', 'Copier le code');
          btn.classList.remove('is-copied');
        }, 2000);
      } catch (_) {
        btn.textContent = 'error';
        setTimeout(() => { btn.textContent = 'copy'; }, 2000);
      }
    });
  });

  // ── Reveal on scroll ─────────────────────────────────────────────────────
  const revealEls = document.querySelectorAll('.reveal');
  if (reducedMotion) {
    revealEls.forEach((el) => el.classList.add('is-visible'));
  } else if (revealEls.length && 'IntersectionObserver' in window) {
    const observer = new IntersectionObserver(
      (entries) => {
        entries.forEach((entry, i) => {
          if (entry.isIntersecting) {
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
