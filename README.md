# Suku Sastra Theme (Revamp)

Classic WordPress PHP theme for Suku Sastra built on top of the local `poetzen` folder.

## Requirements

- WordPress 6.5+
- PHP 8.0+
- Node.js (only for local Tailwind CSS compiling)

## Local Development & Asset Compilation

To edit custom styling or run the theme scripts:

```bash
# Install Node devDependencies
npm install

# Build compiled CSS styling
npm run build:css

# Watch for styling changes during development
npm run watch:css
```

## Content Types and Categories

- **Puisi, Cerpen, Esai**: Managed as standard WordPress posts using categories with custom excerpt rules and layout templates.
- **Ruang Baca**: Special SEO article category, queryable via Tag Hubs but excluded from the homepage feeds using native metabox toggles.
- **Review Buku (review_buku CPT)**: Structured book reviews displaying book covers, author metadata, publishers, years, and marketplace purchase CTAs.
- **Berita (berita CPT)**: General updates and announcements supporting optional YouTube video embeds.
- **Event (event CPT)**: Literary events and agendas sorting chronologically (upcoming events prioritized) and supporting status-aware booking and sold-out tickets.

## SEO Controls

Each post, page, and CPT editor screen features a side panel and normal fields to configure:
1. Custom SEO title and description.
2. Canonical URL structure.
3. Search engine indexing (Robots options `index,follow` or `noindex,follow`).
4. Redirect target URL and HTTP Status type (301 or 302 redirect).

## Verification Routine

1. **Activation**: Enable **Suku Sastra** in `wp-admin -> Appearance -> Themes`.
2. **Administration**: Check CPT menus for *Review Buku*, *Berita*, and *Event* in the admin sidebar.
3. **Metaboxes**: Edit an item and verify the metabox values (e.g. Booking URL, SEO title, redirect targets) persist upon save.
4. **Feeds**: Visit the homepage and check that Puisi, Cerpen, Esai, Book Reviews, News, and Events display correctly.
5. **Dark Mode**: Check dark, light, and system color settings using the buttons in the header menu.
