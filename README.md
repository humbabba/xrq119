# xrq119

A sci-fi-styled WordPress portfolio theme built with Tailwind CSS v4 and the WordPress block editor.

Cyan accents, corner brackets, scanline overlays, grid backgrounds, and glow effects give content a terminal/HUD aesthetic.

---

## File structure

```
xrq119/
  assets/
    css/app.css        ← Tailwind source (includes icon system + custom components)
    fonts/             ← Inter + JetBrains Mono (self-hosted)
  build/               ← Compiled block JS (generated)
  dist/
    app.css            ← Compiled + minified CSS (generated)
  src/
    index.js           ← Block JS entry point
    blocks/            ← Custom block definitions
    external-link-panel.js
    card-buttons-panel.js
  functions.php        ← Theme setup, meta registration, block registration
  header.php
  footer.php
  index.php            ← Post archive / portfolio grid
  page.php             ← Single page (renders block content)
  home-blocks.html     ← Reference markup for home page blocks
  style.css            ← WP theme header only
```

---

## Build commands

Requires Node.js. Install dependencies first:

```bash
npm install
```

| Command | What it does |
|---|---|
| `npm run build` | Compile blocks + minify Tailwind for production |
| `npm run dev` | Watch both CSS and block JS for changes |
| `npm run dev:css` | Watch Tailwind CSS only |
| `npm run dev:blocks` | Watch block JS only |

`npm run dev` is all you need during development &mdash; it runs both watchers in parallel.

---

## Templates

### `index.php` &mdash; Portfolio grid

Displays posts as project cards in a responsive grid (1/2/3 columns). Each card shows:

- **Thumbnail** (or a fallback gradient with the year)
- **Title**
- **Excerpt** (if set)
- **Tags** as colored pills
- **Date**
- **Buttons** (if configured &mdash; see Card Buttons below)

The card image and title are individually clickable links. If External Link is enabled on a post, they point to the external URL; otherwise they link to the post itself.

### `page.php` &mdash; Block content

Renders any page or post's block editor content directly. Used for the home page and any other page.

---

## Editor sidebar panels

Two custom panels appear in the post editor sidebar under the document settings.

### External Link

Overrides where a post's card links to in the portfolio grid.

| Field | Description |
|---|---|
| **Link externally?** | Toggle &mdash; enables the feature |
| **External URL** | The destination URL |
| **Open in new tab** | Toggle &mdash; adds `target="_blank"` |

When enabled, the card's image and title link to this URL instead of the post permalink.

### Card Buttons

Add one or more action buttons to a post's card in the portfolio grid. Click **+ Add Button** to create a new button.

Each button has:

| Field | Description |
|---|---|
| **Label** | Button text (required &mdash; buttons without a label are skipped) |
| **URL** | Destination link. If left empty, defaults to the post permalink |
| **Background** | Color picker with presets (cyan, purple, green, orange, gray) + custom hex |
| **Text** | Text color picker with presets (white, black, cyan, gray) + custom hex |
| **CSS class(es)** | Space-separated CSS classes added to the button. Use icon classes here (see below) |
| **Open in new tab** | Toggle &mdash; adds `target="_blank"` |

Buttons appear at the bottom of the card, separated by a subtle divider line.

---

## Icon classes

The theme includes a built-in icon system. Icons are CSS-only (no external libraries), rendered via `::before` pseudo-elements using SVG masks. They inherit the current text color and scale with font size.

Add any icon class to a Card Button's **CSS class(es)** field, or use them anywhere in your markup.

### General

| Class | Icon |
|---|---|
| `icon-external` | External link (box and arrow) |
| `icon-globe` | Globe / website |
| `icon-download` | Download arrow |
| `icon-mail` | Email envelope |
| `icon-star` | Star / favorite |
| `icon-docs` | Book / documentation |

### Media

| Class | Icon |
|---|---|
| `icon-video` | Play / video |
| `icon-audio` | Music note |

### Developer

| Class | Icon |
|---|---|
| `icon-github` | GitHub |
| `icon-code` | Code brackets `</>` |
| `icon-terminal` | Terminal prompt |
| `icon-codepen` | CodePen |
| `icon-npm` | npm |
| `icon-stackoverflow` | Stack Overflow |

### Social

| Class | Icon |
|---|---|
| `icon-twitter` | X / Twitter |
| `icon-linkedin` | LinkedIn |
| `icon-instagram` | Instagram |
| `icon-facebook` | Facebook |
| `icon-youtube` | YouTube |
| `icon-discord` | Discord |
| `icon-twitch` | Twitch |
| `icon-spotify` | Spotify |
| `icon-soundcloud` | SoundCloud |

### Combining classes

You can combine an icon class with any other CSS classes:

```
icon-github my-custom-class
```

---

## Custom blocks

Five blocks are registered under the `xrq119/` namespace and appear in the **xrq119** block category in the editor.

| Block | Purpose |
|---|---|
| `xrq119/feature-card` | Highlighted feature with icon, heading, and description |
| `xrq119/stat-card` | Numeric stat with label |
| `xrq119/skill-group` | Group of skill tags |
| `xrq119/timeline-entry` | Timeline item with date and bullet points |
| `xrq119/icon-card` | Card with a prominent icon |

These are designed for the home page content. See `home-blocks.html` for reference markup.

---

## CSS components

Custom CSS classes available for use in block markup or templates:

| Class | Effect                                                    |
|---|-----------------------------------------------------------|
| `corner-accent` | Cyan bracket accents on top-left and bottom-right corners |
| `hover-glow` | Glow effect on hover (used on cards)                      |
| `hero-section` | Orb + scanline overlay for hero areas                     |
| `grid-bg` | Subtle cyan grid background pattern                       |
| `cursor-blink` | Blinking cursor ( &#124; ), appended via `::after`       |
| `timeline` | Left-bordered timeline container                          |
| `timeline-item` | Timeline entry with cyan dot                              |
| `animate-float` | Floating animation (6s loop)                              |
| `animate-float-delayed` | Floating animation with 2s delay                          |

---

## Fonts

Self-hosted in `assets/fonts/`:

- **Inter** (400&ndash;800) &mdash; body text (`font-sans`)
- **JetBrains Mono** (400&ndash;700) &mdash; headings, UI, code (`font-mono`)
