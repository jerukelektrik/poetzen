# Catalog Banner Mobile Height Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Membuat banner Katalog Terbitan pada homepage memiliki rasio 4:1 di mobile dan tetap 8:1 di desktop.

**Architecture:** Wrapper banner tunggal dan slider menggunakan class responsive yang sama. Rasio yang sebelumnya inline dipindahkan ke `src/input.css`, lalu stylesheet produksi dibangun ulang.

**Tech Stack:** WordPress classic theme, PHP 8.2, Tailwind CSS 4, responsive CSS.

---

### Task 1: Scope the Catalog Banner Wrapper

**Files:**
- Modify: `front-page.php:816-838`

- [ ] Tambahkan class `poetzen-catalog-banner-frame` pada wrapper banner tunggal dan slider.
- [ ] Hapus `style="aspect-ratio: 1200/150;"` dari slider.
- [ ] Jalankan PHP lint:

```bash
"/Users/armadanurliansyah/Library/Application Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php" -l front-page.php
```

Expected: `No syntax errors detected in front-page.php`.

### Task 2: Add Responsive Aspect Ratio

**Files:**
- Modify: `src/input.css`
- Modify: `assets/css/theme.css`

- [ ] Tambahkan aturan desktop:

```css
.poetzen-catalog-banner-frame {
  aspect-ratio: 8 / 1;
}
```

- [ ] Tambahkan aturan mobile:

```css
@media (max-width: 47.999rem) {
  .poetzen-catalog-banner-frame {
    aspect-ratio: 4 / 1;
  }

  .poetzen-catalog-banner-frame img {
    height: 100% !important;
    object-fit: cover;
    object-position: center;
  }
}
```

- [ ] Jalankan `npm run build:css`.
- [ ] Pastikan selector baru terdapat dalam `assets/css/theme.css`.

### Task 3: Verify Responsive Behavior

**Files:**
- Test: `front-page.php`
- Test: `src/input.css`
- Test: `assets/css/theme.css`

- [ ] Pada viewport 393px, pastikan banner memiliki rasio 4:1 dan tinggi sekitar 86px.
- [ ] Pastikan gambar, tombol panah, dots, dan slider tidak overflow.
- [ ] Pada viewport 1440px, pastikan banner tetap memiliki rasio 8:1.
- [ ] Pastikan console browser tidak menampilkan error baru.
- [ ] Jalankan `git diff --check` dan commit perubahan.
