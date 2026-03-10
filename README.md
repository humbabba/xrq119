# xrq119

A sci-fi-styled WordPress theme built with Tailwind CSS v4 and the WordPress block editor.

Dark HUD header and footer bars, cyan accents, corner brackets, scanline overlays, grid backgrounds, code rain animation, and glow effects give content a terminal/control-panel aesthetic.

---

## File structure

```
xrq119/
  assets/
    css/
      app.css            <- Tailwind source (includes icon system + custom components)
      cf7.css            <- Contact Form 7 styles (loaded only on CF7 pages)
    fonts/               <- Inter + JetBrains Mono (self-hosted)
    js/                  <- JS source
      app.js             <- Frontend entry (ES module: nav, code rain)
      admin.js           <- Admin/editor entry (wp-scripts: blocks, sidebar panels)
      frontend/          <- Frontend modules
        nav.js           <- Priority+ nav and category dropdown toggles
        coderain.js      <- Code rain canvas animation
        modal.js         <- Standalone modal (ES module, import to use)
        cf7.js           <- Contact Form 7 event hooks (conditionally loaded)
      admin/             <- Admin modules
        sortable.js      <- Drag-and-drop ordering (SortableJS)
        blocks/          <- Custom block definitions
        external-link-panel.js
        card-buttons-panel.js
  dist/                  <- Compiled output (generated)
    css/app.css          <- Compiled + minified CSS
    js/                  <- Compiled JS (admin.js, admin-sortable.js)
  functions.php          <- Theme setup, meta registration, block registration, Customizer, ordering, enqueues
  header.php             <- Sticky dark HUD header (logo, nav, priority+ overflow, category dropdowns)
  footer.php             <- Sticky dark HUD footer (status indicator, copyright)
  index.php              <- Post/category archive (card grid)
  single.php             <- Single post (breadcrumbs, pull quote, featured image, body, tags)
  page.php               <- Single page (renders block content with prose typography)
  style.css              <- WP theme header only
  webpack.config.js      <- Custom wp-scripts config (admin + admin-sortable entry points)
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

`npm run dev` is all you need during development; it runs both watchers in parallel.

---

## JavaScript architecture

Frontend and admin JS are organized into separate directories.

### Frontend (`assets/js/frontend/`)

Loaded as ES modules via `wp_enqueue_script_module()`.

- **`app.js`** &mdash; Main entry point, imports and initializes `nav.js` and `coderain.js`. Loaded on every page.
- **`nav.js`** &mdash; Priority+ navigation overflow and category dropdown toggles.
- **`coderain.js`** &mdash; Code rain canvas animation for the HUD header screen.
- **`modal.js`** &mdash; Standalone modal module (see Modal section below).
- **`cf7.js`** &mdash; Contact Form 7 event hooks. Conditionally loaded only on pages containing a CF7 form.

### Admin (`assets/js/admin/`)

Compiled by `@wordpress/scripts` (webpack) into `dist/js/`.

- **`admin.js`** &mdash; Entry point that imports all block definitions and editor sidebar panels.
- **`sortable.js`** &mdash; SortableJS-based drag-and-drop for admin list tables (separate entry point).
- **`blocks/`** &mdash; Custom block `edit` and `save` components.
- **`external-link-panel.js`** &mdash; Editor sidebar panel for external links.
- **`card-buttons-panel.js`** &mdash; Editor sidebar panel for card buttons.

---

## Header & Footer (HUD bars)

The theme features sticky dark header and footer bars with a subtle scanline overlay.

### Header

The header contains three areas, left-aligned with the screen area pushed to the right:

- **Logo:** Displays the custom logo (Appearance > Customize > Site Identity) or the site name as fallback text
- **Navigation:** By default, lists top-level categories that have posts (excluding Uncategorized) as links, respecting custom drag-and-drop order if set. Categories with child categories display a click-to-toggle dropdown: the first item is "All {Category}" (links to the parent archive), followed by each child. If a custom menu is assigned to the **Header Navigation** location (Appearance > Menus), it replaces the category list entirely. When items overflow the available space, they collapse into a priority+ "more" dropdown with a chevron; categories with children are flattened in this dropdown with `- ` prefixed child items.
- **Supplemental Navigation:** A second menu location. Items assigned here appear after the main navigation (whether category-based or custom Header Navigation) as part of the same `<ul>`, styled identically and subject to the same priority+ overflow handling. Assign a menu at Appearance > Menus > Supplemental Navigation.
- **Screen area:** By default, shows a "code rain" animation with a scanline effect. Can be replaced with custom HTML via the Customizer (Appearance > Customize)

### Footer

The footer contains three items in a row:

- **Status indicator:** By default, a pulsing cyan dot with "sys.online" text. Can be replaced with custom HTML via the Customizer (Appearance > Customize)
- **Copyright:** Year + site name
- **Theme ID:** "xrq119" (hidden on small screens)

### Menu locations

| Location | Description |
|---|---|
| **Header Navigation** | Replaces the default category-based nav entirely |
| **Supplemental Navigation** | Appended after the main nav (category-based or Header Navigation) |

### Customizer settings

Under **Appearance > Customize > HUD Bar Settings**:

| Setting | Description |
|---|---|
| **Header Screen HTML** | Custom HTML that completely replaces the code rain screen. Leave empty for the default animation |
| **Footer Status HTML** | Custom HTML that completely replaces the pulsing "sys.online" indicator. Leave empty for the default |

---

## Drag-and-drop ordering

Categories and posts can be reordered by dragging and dropping in the WordPress admin. Each row has a drag handle on the left.

### Category ordering

On **Posts > Categories**, drag rows to reorder. The custom order is reflected in the default header navigation and persists across page loads. A "Sorted" notice appears at the top of the page with an "Unsort" link to restore alphabetical order.

### Post ordering (per category)

On the posts list filtered by a category (e.g. **Posts** filtered by a specific category via the dropdown or a direct URL like `edit.php?category_name=portfolio` or `edit.php?cat=3`), drag rows to reorder. The custom order applies to the frontend category archive page. New posts added after ordering appear at the end (by date). A "Sorted" notice with an "Unsort" link appears when a custom order is active.

---

## Templates

### `index.php` &mdash; Post archives

Displays posts as cards in a responsive grid (1/2/3 columns). Each card shows:

- **Thumbnail** (or a fallback gradient with the title)
- **Title** (mono font, black, not uppercase)
- **Excerpt** (if set)
- **Tags** as colored pills
- **Buttons** (if configured; see Card Buttons below)

The card image and title are individually clickable links. If External Link is enabled on a post, they point to the external URL; otherwise they link to the post itself.

### `single.php` &mdash; Single post

Displays an individual post in a centered narrow column (`max-w-2xl`). Layout from top to bottom:

- **Breadcrumbs:** Hierarchical linked category names (parent > child) separated by `/`, mono font, cyan, uppercase
- **Title:** Large mono heading (styled via global h1 rule)
- **Pull quote:** The post excerpt displayed as an italic blockquote with a cyan left border
- **Featured image:** Full-width with rounded corners and a styled caption (mono, small, cyan left-border accent)
- **Body:** Full prose styling covering paragraphs, headings (h1&ndash;h6 in mono/cyan/uppercase), links, lists, blockquotes, code blocks (dark background), images, tables, and horizontal rules. Custom blocks inside posts are excluded from prose styles and render identically to how they appear on pages.
- **Tags:** Clickable cyan pills at the bottom, separated by a top border

### `page.php` &mdash; Pages

Renders block editor content directly, full-width. Prose typography (paragraphs, links, lists, blockquotes, code, tables) is applied automatically on non-home pages. Custom blocks are excluded from prose styles. The home page (set as static front page) renders without prose styles.

### Typography

Global heading styles (h1&ndash;h6) apply across all templates except the home page and custom block content. Headings use the mono font, cyan color tones, uppercase, and tracked letter-spacing at descending sizes.

---

## Editor sidebar panels

Two custom panels appear in the post editor sidebar under the document settings.

### External Link

Overrides where a post's card links to in the archive grid.

| Field | Description                   |
|---|-------------------------------|
| **Link externally?** | Toggle enables the feature    |
| **External URL** | The destination URL           |
| **Open in new tab** | Toggle adds `target="_blank"` |

When enabled, the card's image and title link to this URL instead of the post permalink. Visiting the post's own URL will also 301-redirect to the external URL. To bypass the redirect (e.g. for editing or previewing), append `?xrq119` to the post URL.

### Card Buttons

Add one or more action buttons to a post's card in the archive grid. Click **+ Add Button** to create a new button.

Each button has:

| Field | Description                                                                        |
|---|------------------------------------------------------------------------------------|
| **Label** | Button text (required; buttons without a label are skipped)                        |
| **URL** | Destination link. If left empty, defaults to the post permalink                    |
| **Background** | Color picker with presets (cyan, purple, green, orange, gray) + custom hex         |
| **Text** | Text color picker with presets (white, black, cyan, gray) + custom hex             |
| **CSS class(es)** | Space-separated CSS classes added to the button. Use icon classes here (see below) |
| **Open in new tab** | Toggle adds `target="_blank"`                                                      |

Buttons appear at the bottom of the card, separated by a subtle divider line.

---

## Contact Form 7 integration

The theme includes optional styling and JS hooks for Contact Form 7 (with the Flamingo add-on for database storage).

- **`assets/css/cf7.css`** &mdash; Theme-matched form styles (dark inputs, mono labels, cyan accents, glow on focus, styled validation and success/error messages). Loaded only on pages containing a CF7 shortcode or block.
- **`assets/js/frontend/cf7.js`** &mdash; Event hooks for CF7 form events (e.g. `wpcf7mailsent`). Loaded as an ES module only on CF7 pages.

No configuration needed. Install Contact Form 7 and Flamingo, create a form, and add it to any page.

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

Five blocks are registered under the `xrq119/` namespace and appear in the **xrq119** block category in the editor. Each block uses inline styles in its editor component to match the frontend appearance regardless of Tailwind CSS layer specificity.

| Block | Purpose |
|---|---|
| `xrq119/feature-card` | Highlighted feature with icon, heading, and description |
| `xrq119/stat-card` | Numeric stat with label |
| `xrq119/skill-group` | Group of skill tags |
| `xrq119/timeline-entry` | Timeline item with date and bullet points |
| `xrq119/icon-card` | Card with a prominent icon |

---

## CSS components

Custom CSS classes available for use in block markup or templates:

| Class | Effect |
|---|---|
| `corner-accent` | Cyan bracket accents on top-left and bottom-right corners |
| `card-title` | Mono font card heading (black, no uppercase) |
| `hover-glow` | Glow effect on hover (used on cards) |
| `hero-section` | Orb + scanline overlay for hero areas |
| `grid-bg` | Subtle cyan grid background pattern |
| `cursor-blink` | Blinking cursor ( &#124; ), appended via `::after` |
| `timeline` | Left-bordered timeline container |
| `timeline-item` | Timeline entry with cyan dot |
| `pulse-dot` | Small pulsing cyan dot (used in footer status) |
| `pulse` | Applies the pulse-glow animation to any element |
| `animate-float` | Floating animation (6s loop) |
| `animate-float-delayed` | Floating animation with 2s delay |

---

## Animations

CSS keyframe animations used throughout the theme:

| Animation | Effect |
|---|---|
| `pulse-glow` | Fades opacity between 0.4 and 1 |
| `float` | Gentle vertical bobbing (8px) |
| `scanline` | Moves a highlight band vertically across an element |

---

## Modal

A standalone ES module for displaying theme-styled modals. Import it from any frontend script:

```js
import { modal } from './frontend/modal.js';

const m = modal({
    title: 'System alert',
    body: '<p>Transmission received.</p>',
});
```

### Options

All options are optional.

| Option | Type | Default | Description |
|---|---|---|---|
| `title` | string | `''` | Heading text |
| `body` | string / HTMLElement | `''` | HTML string or DOM element for the modal body |
| `closeLabel` | string | `'Close'` | Text for the footer close button |
| `showClose` | boolean | `true` | Show the &times; button and footer close button |
| `overlayClose` | boolean | `true` | Close when clicking the backdrop |
| `escClose` | boolean | `true` | Close on Escape key |
| `width` | string | `'32rem'` | Max width (any CSS value) |
| `onOpen` | function | `null` | Called after the modal opens, receives the API object |
| `onClose` | function | `null` | Called after the modal is removed from the DOM |

### Return value

`modal()` returns an API object:

| Property | Description |
|---|---|
| `close()` | Programmatically close the modal |
| `panel` | The modal panel DOM element |
| `body` | The body content DOM element |
| `overlay` | The backdrop DOM element |

---

## Fonts

Self-hosted in `assets/fonts/`:

- **Inter** (400&ndash;800) for body text (`font-sans`)
- **JetBrains Mono** (400&ndash;700) for headings, UI, code (`font-mono`)
