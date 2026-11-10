# Matrimonial Design Kit

A portable, community-agnostic design system crafted specifically for serious matrimonial and family-oriented matchmaking platforms. Built with human-crafted editorial sensibilities, strict WCAG AA contrast standards, and responsive, accessible components.

---

## 1. Directory Structure

```text
design-kit/
├── design-tokens.css          # Theme-independent visual rules (scales, radii, spacing)
├── theme.css                  # Site-specific values (accents, background tints, pattern)
├── components/
│   ├── buttons.html           # Primary, secondary, subtle, danger, loading, disabled
│   ├── inputs.html            # Text, select, date (dd/mm/yyyy), textarea, checkbox, radio
│   ├── profile-card.html      # Candidate card (Grid view & List view)
│   ├── profile-detail-sections.html # Sectioned definition-list rows (<dl>, <dt>, <dd>)
│   ├── filter-sidebar.html    # Desktop sidebar + mobile bottom-sheet drawer
│   ├── stepper.html           # Multi-step progress wizard with save-and-continue-later
│   ├── badges.html            # Verification, workflow status, tier, counter indicators
│   ├── empty-states.html      # No results, empty inbox, empty shortlist
│   └── skeletons.html         # Accessible loading pulse states
├── README.md                  # This specification document
└── AGENT_GUIDE.md             # Self-contained instructions for an AI coding agent
```

---

## 2. Core Separation Principle

The visual system is separated into two decoupled files:

1. **`design-tokens.css` (Reusable everywhere unchanged):**
   - Neutral scale (`--neutral-25` to `--neutral-950`).
   - Semantic status signals (Success, Warning, Danger, Info).
   - Type scale (`11px` to `48px`), font weights, and line heights.
   - Spacing scale (`4px` to `80px` on a strict 4/8 grid).
   - Border radius rules (`4px` to `6px` for UI; `16px` to `24px` for hero banners).
   - Hairline border widths, grounded shadows, transition durations (`120ms`–`160ms`), and minimum touch targets (`44px`).

2. **`theme.css` (Brand / Community specific):**
   - Primary and secondary accent colors.
   - Canvas background tints (unbleached warm off-white).
   - Surface text ink colors and hairline border hues.
   - High-visibility keyboard focus ring.
   - Faint geometric background pattern (3–5% opacity).
   - Dark theme mode variables.

---

## 3. Spacing & Architectural Grid Rules

All layout margins, paddings, and component dimensions adhere to a strict **4px / 8px grid**:

| Token | Pixels | Common Usage |
| :--- | :--- | :--- |
| `--space-1` | `4px` | Badge padding, icon-to-text gap, hairline dividers |
| `--space-2` | `8px` | Button inline gap, chip spacing, form row spacing |
| `--space-3` | `12px` | Input padding, compact card padding |
| `--space-4` | `16px` | Standard card padding, list item spacing, stack gaps |
| `--space-5` | `20px` | Dialog padding, drawer headers |
| `--space-6` | `24px` | Section margins, grid gutters |
| `--space-8` | `32px` | Form block separation, page header margins |
| `--space-10` | `40px` | Hero container vertical padding (mobile) |
| `--space-12` | `48px` | Landing section vertical padding (desktop) |
| `--space-16` | `64px` | Major landing page block dividers |

> [!IMPORTANT]
> Never use arbitrary spacing values (e.g. `margin: 17px` or `padding: 23px`). Always snap to the 4/8 grid tokens.

---

## 4. Typography Scale & Multilingual Rules

Matrimonial platforms across South Asia and global diasporas require bilingual English and Bengali script support.

```css
--font-sans: 'Inter', 'Noto Sans Bengali', 'Hind Siliguri', -apple-system, sans-serif;
```

### Scale & Hierarchy

| Token | Size | Line Height | Usage |
| :--- | :--- | :--- | :--- |
| `--text-2xs` | `11px` (`0.6875rem`) | `1.25` | Badges, micro-labels, timestamps |
| `--text-xs` | `12px` (`0.75rem`) | `1.375` | Button labels, form field labels, secondary card info |
| `--text-sm` | `13px` (`0.8125rem`) | `1.5` | Standard body text, definition descriptions |
| `--text-base` | `15px` (`0.9375rem`) | `1.65` | Editorial paragraphs, long-form biodata bios |
| `--text-md` | `17px` (`1.0625rem`) | `1.5` | Card titles, modal headers |
| `--text-lg` | `20px` (`1.25rem`) | `1.375` | Section headers, candidate detail title |
| `--text-xl` | `24px` (`1.5rem`) | `1.25` | Page titles, dashboard greetings |
| `--text-2xl` | `30px` (`1.875rem`) | `1.2` | Hero headlines (mobile) |
| `--text-3xl` | `36px` (`2.25rem`) | `1.15` | Hero headlines (desktop) |

### Multilingual Line-Height Rule
Bengali conjuncts and diacritics (hasant, kar signs) clip or collide under standard Latin line heights. Always use `--leading-relaxed` (`1.65`) for body copy and definition descriptions when Bengali text is present.

---

## 5. Border Radius & Surface Hierarchy Rules

AI-generated interfaces overuse `rounded-2xl` and `rounded-3xl` uniformly on every small card. In a hand-crafted human design, corner curvature follows a strict functional hierarchy:

1. **Interactive Elements (`--radius-sm`: `4px`):**
   - Buttons, text inputs, select dropdowns, search chips.
2. **Cards & Containers (`--radius-md`: `6px`):**
   - Profile cards, form containers, filter drawers, definition ledger boxes (`.card-ledger`).
3. **Hero & Dashboard Highlight Containers (`--radius-2xl` to `--radius-3xl`: `16px` to `24px`):**
   - Floating hero banner on the landing page and executive dashboard banners.
   - **Crucial Rule:** The hero banner must **never** bleed 100% full-width to the viewport edge. It sits inside a framed container (`max-w-7xl mx-auto px-4 sm:px-6 lg:px-8`) with warm off-white canvas breathing room around it.

---

## 6. Component Class Contracts

| Class | Element | Visual Behavior |
| :--- | :--- | :--- |
| `.card-ledger` | `article`, `div` | Warm surface, 1px hairline border, subtle shadow, disciplined hover state |
| `.btn-primary` | `button`, `a` | Accent background, 44px min touch target, 4px radius, clear focus ring |
| `.btn-secondary` | `button`, `a` | Surface background, hairline border, subtle hover tint |
| `.btn-subtle` | `button`, `a` | Transparent background, text secondary, low-key ghost button |
| `.badge-verified`| `span` | Light accent background, hairline border, check/shield icon |
| `.badge-status` | `span` | Semantic colored pill (Pending, Accepted, Declined) |
| `.photo-shield` | `img` | Modesty 14px blur filter until mutual interest / explicit permission |
| `.pattern-header`| `section`, `div` | Restrained 3–5% geometric radial pattern backdrop |
| `.def-row` | `div` | Flex row definition item (horizontal on sm+ screens) |
| `.def-term` | `dt` | Bold label, minimum width 180px, secondary text color |
| `.def-desc` | `dd` | Primary text color, medium weight, relaxed line height |

---

## 7. How to Swap Themes for Different Projects

To adapt this design system for another matrimony or social platform, keep `design-tokens.css` untouched and replace the values in `theme.css`:

### Example A: Hindu Matrimony (Vivah Platform)
```css
:root {
  --accent-primary: #80131d;       /* Vedic Crimson / Maroon */
  --accent-hover: #630c14;
  --accent-subtle: #fdf5f5;
  --accent-border: #f0c4c8;
  --accent-text: #630c14;

  --secondary-primary: #b38128;    /* Auspicious Gold / Brass */
  --secondary-subtle: #fbf7ee;
  --secondary-border: #ebd9be;
  --secondary-text: #785210;

  --bg-canvas: #faf8f4;            /* Warm Ivory Canvas */
}
```

### Example B: Christian Matrimony (Maitri Platform)
```css
:root {
  --accent-primary: #142850;       /* Sincere Deep Navy */
  --accent-hover: #0c1b38;
  --accent-subtle: #edf3fa;
  --accent-border: #b8cde4;
  --accent-text: #0c1b38;

  --secondary-primary: #6b1426;    /* Dignified Burgundy */
  --secondary-subtle: #faf0f2;
  --secondary-border: #e3bcc4;
  --secondary-text: #500e1c;

  --bg-canvas: #f8f9fa;            /* Clean Stone Base */
}
```

---

## 8. Non-Negotiable Consistency Checklist

- **No Emojis as UI icons:** Always use monoline SVG icons (Lucide or Phosphor).
- **No Romantic Marketing Clichés:** Use respectful, plain, family-appropriate microcopy.
- **Strict Date Format:** Always `dd/mm/yyyy` for South Asian platforms.
- **Default Photo Modesty:** Candidate photos are protected/blurred by default until mutual interest is established.
- **Always WCAG AA Compliant:** Text contrast against canvas and surfaces must exceed 4.5:1.
- **Visible Keyboard Focus:** Never remove focus rings; use the high-visibility token `var(--focus-ring)`.

