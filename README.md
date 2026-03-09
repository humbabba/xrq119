# xrq119

A sci-fi-styled WordPress theme built with Tailwind CSS v4 and the WordPress block editor.

Dark HUD header and footer bars, cyan accents, corner brackets, scanline overlays, grid backgrounds, code rain animation, and glow effects give content a terminal/control-panel aesthetic.

---

## File structure

```
xrq119/
  assets/
    css/app.css        <- Tailwind source (includes icon system + custom components)
    fonts/             <- Inter + JetBrains Mono (self-hosted)
    js/                <- JS source
      index.js         <- Block JS entry point
      admin-sortable.js <- Drag-and-drop ordering for admin lists (SortableJS)
      blocks/          <- Custom block definitions
      external-link-panel.js
      card-buttons-panel.js
  dist/                <- Compiled output (generated)
    css/app.css        <- Compiled + minified CSS
    js/                <- Compiled JS
  functions.php        <- Theme setup, meta registration, block registration, Customizer, drag-and-drop ordering
  header.php           <- Sticky dark HUD header (logo, nav, priority+ overflow, category dropdowns)
  footer.php           <- Sticky dark HUD footer (status indicator, copyright)
  index.php            <- Post/category archive (card grid)
  single.php           <- Single post (breadcrumbs, pull quote, featured image, body, tags)
  page.php             <- Single page (renders block content)
  style.css            <- WP theme header only
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

## Header & Footer (HUD bars)

The theme features sticky dark header and footer bars with a subtle scanline overlay and flicker animation.

### Header

The header contains three areas, left-aligned with the screen area pushed to the right:

- **Logo:** Displays the custom logo (Appearance > Customize > Site Identity) or the site nfame as fallback text
- **Navigation:** By default, lists top-level categories that have posts (excluding Uncategorized) as links, respecting custom drag-and-drop order if set. Categories with child categories display a click-to-toggle dropdown: the first item is "All {Category}" (links to the parent archive), followed by each child. If a custom menu is assigned to the **Header Navigation** location (Appearance > Menus), it replaces the category list entirely. When items overflow the available space, they collapse into a priority+ "more" dropdown with a chevron; categories with children are flattened in this dropdown with `- ` prefixed child items.
- **Screen area:** By default, shows a "code rain" animation with a scanline effect. Can be replaced with custom HTML via the Customizer (appearance > customize)

### Footer

The footer contains three items in a row:

- **Status indicator:** By default, a pulsing cyan dot with "sys.online" text. Can be replaced with custom HTML via the Customizer (appearance > customize)
- **Copyright:** Year + site name
- **Theme ID:** "xrq119" (hidden on small screens)

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
- **Title**
- **Excerpt** (if set)
- **Tags** as colored pills
- **Buttons** (if configured; see Card Buttons below)

The card image and title are individually clickable links. If External Link is enabled on a post, they point to the external URL; otherwise they link to the post itself.

### `single.php` &mdash; Single post

Displays an individual post in a centered narrow column (`max-w-2xl`). Layout from top to bottom:

- **Breadcrumbs:** Linked category names separated by `/`, mono font, cyan, uppercase
- **Title:** Large mono heading
- **Pull quote:** The post excerpt displayed as an italic blockquote with a cyan left border
- **Featured image:** Full-width with rounded corners and a styled caption (mono, small, cyan left-border accent)
- **Body:** Full prose styling covering paragraphs, headings (h2&ndash;h4 in mono/cyan/uppercase), links, lists, blockquotes, code blocks (dark background), images, tables, and horizontal rules
- **Tags:** Clickable cyan pills at the bottom, separated by a top border

### `page.php` &mdash; Block content

Renders any page's block editor content directly. Used for the home page and any other page.

---

## Editor sidebar panels

Two custom panels appear in the post editor sidebar under the document settings.

### External Link

Overrides where a post's card links to in the archive grid.

| Field | Description |
|---|---|
| **Link externally?** | Toggle &mdash; enables the feature |
| **External URL** | The destination URL |
| **Open in new tab** | Toggle &mdash; adds `target="_blank"` |

When enabled, the card's image and title link to this URL instead of the post permalink. Visiting the post's own URL will also 301-redirect to the external URL. To bypass the redirect (e.g. for editing or previewing), append `?xrq119` to the post URL.

### Card Buttons

Add one or more action buttons to a post's card in the archive grid. Click **+ Add Button** to create a new button.

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

---

## CSS components

Custom CSS classes available for use in block markup or templates:

| Class | Effect |
|---|---|
| `corner-accent` | Cyan bracket accents on top-left and bottom-right corners |
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

## Fonts

Self-hosted in `assets/fonts/`:

- **Inter** (400&ndash;800) for body text (`font-sans`)
- **JetBrains Mono** (400&ndash;700) for headings, UI, code (`font-mono`)
