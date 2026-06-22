# Peristiwa Mobile List Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Mengubah section Peristiwa homepage versi mobile menjadi list vertikal dengan teks di kiri dan thumbnail persegi di kanan, tanpa mengubah grid desktop.

**Architecture:** Markup dan query WordPress yang ada tetap digunakan. `front-page.php` hanya mendapat class pembatas khusus section, sedangkan seluruh perubahan presentasi ditempatkan dalam media query mobile di `src/input.css`, sehingga `news-card.php` dan pemakaiannya di halaman lain tidak terpengaruh.

**Tech Stack:** WordPress classic theme, PHP 8.2, Tailwind CSS 4, CSS responsive, Codex in-app browser.

---

## File Structure

- Modify: `front-page.php` — memberi class semantik khusus pada container dan item Peristiwa.
- Modify: `src/input.css` — mengubah carousel Peristiwa menjadi list mobile dan menyusun ulang elemen `news-card`.
- Modify: `assets/css/theme.css` — hasil build Tailwind dari stylesheet sumber.

### Task 1: Add Scoped Peristiwa Hooks

**Files:**
- Modify: `front-page.php:1103-1106`

- [ ] **Step 1: Verify the hooks do not exist**

Run:

```bash
rg -n "ss-peristiwa-list|ss-peristiwa-list-item" front-page.php
```

Expected: no matches and exit status 1.

- [ ] **Step 2: Add section-specific classes without changing data or markup structure**

Replace the carousel and item opening tags with:

```php
<div class="ss-news-carousel ss-peristiwa-list flex gap-4 overflow-x-auto snap-x snap-mandatory no-scrollbar pb-2 md:grid md:gap-6 md:grid-cols-3 md:overflow-visible md:snap-none">
	<?php while ( $news->have_posts() ) : $news->the_post(); ?>
		<div class="ss-news-carousel-item ss-peristiwa-list-item w-[82vw] shrink-0 snap-start md:w-auto md:shrink">
```

Remove `data-drag-scroll` from this container because the mobile layout no longer scrolls horizontally.

- [ ] **Step 3: Lint the modified PHP file**

Run:

```bash
"/Users/armadanurliansyah/Library/Application Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin/php" -l front-page.php
```

Expected: `No syntax errors detected in front-page.php`.

- [ ] **Step 4: Commit the scoped markup**

```bash
git add front-page.php
git commit -m "refactor: scope peristiwa homepage list"
```

### Task 2: Implement the Mobile Grid List

**Files:**
- Modify: `src/input.css` inside the existing `@media (max-width: 47.999rem)` responsive area.
- Modify: `assets/css/theme.css` through the build command.

- [ ] **Step 1: Add mobile-only list styles**

Add these scoped rules:

```css
@media (max-width: 47.999rem) {
  #peristiwa .ss-peristiwa-list {
    box-shadow: none;
    cursor: default;
    display: grid;
    gap: 0;
    overflow: visible;
    padding-bottom: 0;
    touch-action: pan-y;
    width: 100%;
  }

  #peristiwa .ss-peristiwa-list-item {
    border-bottom: 1px solid rgba(148, 163, 184, 0.22);
    scroll-snap-align: none;
    width: 100%;
  }

  #peristiwa .ss-peristiwa-list-item > article {
    display: grid;
    gap: 0.35rem 1rem;
    grid-template-areas:
      "title media"
      "meta media";
    grid-template-columns: minmax(0, 1fr) 5.75rem;
    padding: 1rem 0;
  }

  #peristiwa .ss-peristiwa-list-item article > a:first-child {
    align-self: start;
    border: 0;
    border-radius: 0.625rem;
    box-shadow: none;
    grid-area: media;
  }

  #peristiwa .ss-peristiwa-list-item article > a:first-child img,
  #peristiwa .ss-peristiwa-list-item article > a:first-child .ss-post-card-placeholder {
    aspect-ratio: 1 / 1;
    border-radius: inherit;
    object-fit: cover;
  }

  #peristiwa .ss-peristiwa-list-item article > div:nth-of-type(1) {
    align-self: start;
    font-size: 0.72rem;
    gap: 0.25rem;
    grid-area: meta;
    margin-top: 0;
    text-transform: none;
  }

  #peristiwa .ss-peristiwa-list-item article > div:nth-of-type(2) {
    align-self: start;
    grid-area: title;
    margin-top: 0;
  }

  #peristiwa .ss-peristiwa-list-item .ss-news-card-title {
    font-family: var(--font-sans), sans-serif;
    font-size: 1rem;
    line-height: 1.32;
  }

  #peristiwa .ss-peristiwa-list-item article > div:nth-of-type(2) > svg,
  #peristiwa .ss-peristiwa-list-item article > p {
    display: none;
  }
}
```

- [ ] **Step 2: Build the production stylesheet**

Run:

```bash
npm run build:css
```

Expected: Tailwind finishes successfully and updates `assets/css/theme.css`.

- [ ] **Step 3: Confirm the compiled CSS contains the scoped selectors**

Run:

```bash
rg -n "ss-peristiwa-list|ss-peristiwa-list-item" assets/css/theme.css
```

Expected: compiled selectors for the new mobile layout are present.

- [ ] **Step 4: Commit the responsive styling**

```bash
git add src/input.css assets/css/theme.css
git commit -m "feat: add mobile peristiwa list layout"
```

### Task 3: Verify Mobile and Desktop Behavior

**Files:**
- Test: `front-page.php`
- Test: `src/input.css`
- Test: `assets/css/theme.css`

- [ ] **Step 1: Run the theme PHP test suite**

Run:

```bash
PATH="/Users/armadanurliansyah/Library/Application Support/Local/lightning-services/php-8.2.29+0/bin/darwin-arm64/bin:$PATH" npm run test:php
```

Expected: all helper, query, and event-state tests pass.

- [ ] **Step 2: Open and reload the local homepage**

Navigate to:

```text
http://sukusastra.local/
```

Reload after the CSS build so LocalWP serves the new asset.

- [ ] **Step 3: Verify the 390px mobile viewport**

Check:

- Three Peristiwa posts form one vertical list.
- Each row has text on the left and a 92px square thumbnail on the right.
- There is no horizontal scrollbar or swipe behavior in this section.
- Excerpts and diagonal arrows are hidden.
- Long titles wrap without overlapping the image.
- Vertical scrolling remains responsive through the section.

- [ ] **Step 4: Verify the 430px mobile viewport**

Repeat the checks at 430px and confirm the section margins remain aligned with the homepage container.

- [ ] **Step 5: Verify the desktop viewport**

At 1440px, confirm:

- Peristiwa still renders as three equal columns.
- Full 3:2 images, metadata, titles, arrows, and excerpts remain visible.
- Adjacent Review Buku and Agenda/Event sections are unchanged.

- [ ] **Step 6: Inspect the final diff**

Run:

```bash
git diff HEAD~2 -- front-page.php src/input.css assets/css/theme.css
```

Expected: only the scoped Peristiwa hooks and responsive styling are present.
