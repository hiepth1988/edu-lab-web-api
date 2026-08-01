---
name: XO Edu Lab Design System
colors:
  surface: '#f9f9ff'
  surface-dim: '#d0daf2'
  surface-bright: '#f9f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#f0f3ff'
  surface-container: '#e8eeff'
  surface-container-high: '#dfe8ff'
  surface-container-highest: '#d9e3fb'
  on-surface: '#111c2d'
  on-surface-variant: '#45474c'
  inverse-surface: '#273143'
  inverse-on-surface: '#ecf0ff'
  outline: '#76777d'
  outline-variant: '#c6c6cd'
  surface-tint: '#555e74'
  primary: '#01081a'
  on-primary: '#ffffff'
  primary-container: '#172033'
  on-primary-container: '#7f879f'
  inverse-primary: '#bdc6e0'
  secondary: '#116c46'
  on-secondary: '#ffffff'
  secondary-container: '#9ef1c1'
  on-secondary-container: '#18704a'
  tertiary: '#0f0800'
  on-tertiary: '#ffffff'
  tertiary-container: '#2d1e00'
  on-tertiary-container: '#a78237'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#d9e2fc'
  primary-fixed-dim: '#bdc6e0'
  on-primary-fixed: '#121b2e'
  on-primary-fixed-variant: '#3e475b'
  secondary-fixed: '#a1f4c4'
  secondary-fixed-dim: '#85d7a9'
  on-secondary-fixed: '#002112'
  on-secondary-fixed-variant: '#005233'
  tertiary-fixed: '#ffdea6'
  tertiary-fixed-dim: '#ecc06e'
  on-tertiary-fixed: '#271900'
  on-tertiary-fixed-variant: '#5d4200'
  background: '#f9f9ff'
  on-background: '#111c2d'
  surface-variant: '#d9e3fb'
typography:
  display-lg:
    fontFamily: Newsreader
    fontSize: 64px
    fontWeight: '400'
    lineHeight: 72px
    letterSpacing: -0.02em
  headline-lg:
    fontFamily: Newsreader
    fontSize: 40px
    fontWeight: '400'
    lineHeight: 48px
  headline-lg-mobile:
    fontFamily: Newsreader
    fontSize: 32px
    fontWeight: '400'
    lineHeight: 40px
  headline-md:
    fontFamily: Newsreader
    fontSize: 32px
    fontWeight: '400'
    lineHeight: 40px
  headline-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Plus Jakarta Sans
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-md:
    fontFamily: Plus Jakarta Sans
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: 0.01em
  label-sm:
    fontFamily: Plus Jakarta Sans
    fontSize: 12px
    fontWeight: '600'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 8px
  xs: 4px
  sm: 12px
  md: 24px
  lg: 48px
  xl: 80px
  gutter: 24px
  margin-mobile: 20px
  margin-desktop: 64px
  max-width: 1280px
---

## Brand & Style
The design system is built on the philosophy of "Space to Become." It emphasizes intellectual breathing room, clarity of thought, and the warmth of a modern studio environment. The aesthetic merges the precision of high-end productivity tools with the tactile comfort of premium editorial publishing.

The visual direction follows a **Modern-Minimalist** approach with **Tactile** undertones. It prioritizes:
- **Spaciousness:** Large negative spaces to reduce cognitive load.
- **Precision:** Fine lines and structured grids inspired by professional engineering tools.
- **Warmth:** A shift away from "tech-blue" toward organic tones that feel human and encouraging.
- **Intentionality:** Every element serves a purpose; decorative flourishes are replaced by high-quality typography and subtle surface transitions.

## Colors
The palette is grounded in a warm, paper-like background that reduces eye strain compared to pure white. 

- **Primary & Text:** Deep Navy (#172033) provides high contrast and a sense of authority.
- **Accents:** Emerald (#4E9F75) is used for primary actions and "growth" indicators. Gold (#D8AE5E) is reserved for highlights, achievement states, and premium features.
- **Neutrals:** Muted Slate (#667085) handles secondary information, while the Border color (#E5E7EB) maintains structure without creating visual noise.

## Typography
The typographic system uses a sophisticated pairing of an editorial serif for storytelling and a modern sans-serif for utility.

**Newsreader** is used for large displays and section headings to evoke a literary, academic feel. It should be typeset with slightly tighter letter spacing at larger sizes.

**Plus Jakarta Sans** provides a friendly yet professional voice for interface elements, body copy, and labels. Its high x-height ensures legibility in dense instructional content.

**Usage Notes:**
- Use `display-lg` exclusively for landing page heroes or major chapter starts.
- `headline-sm` switches to the Sans font to maintain clarity in smaller UI blocks.
- Maintain generous line heights (1.5x - 1.6x) for body text to support the "Space to Become" philosophy.

## Layout & Spacing
The layout follows a **Fixed Grid** model on desktop, centered within a maximum width of 1280px. This creates the "studio" feel by framing the content within the warm background.

**Grid System:**
- **Desktop:** 12-column grid with 24px gutters and 64px outer margins.
- **Tablet:** 8-column grid with 20px gutters.
- **Mobile:** 4-column grid with 16px gutters and 20px margins.

**Rhythm:**
Spacing should be aggressive. When in doubt, increase padding. Use `lg` (48px) and `xl` (80px) to separate major conceptual sections. Components should utilize `md` (24px) internal padding to ensure they never feel cramped.

## Elevation & Depth
The design system uses a **Tonal Layering** approach combined with **Ambient Shadows** to create a soft, natural sense of depth.

- **Level 0 (Background):** #F7F7F2. The canvas on which all elements sit.
- **Level 1 (Surface):** #FFFFFF. Used for cards, containers, and main content areas. These should feature a 1px border (#E5E7EB) to define edges.
- **Level 2 (Elevated):** For interactive or floating elements. Use a very soft, diffused shadow: `0px 10px 30px rgba(23, 32, 51, 0.04)`.
- **Level 3 (Overlays):** For modals and dropdowns. Use a stronger but still subtle shadow: `0px 20px 50px rgba(23, 32, 51, 0.08)`.

Avoid heavy blacks in shadows; always tint shadows with the Primary Deep Navy to maintain color harmony.

## Shapes
The shape language is defined by significant corner rounding, which softens the "technical" nature of an educational platform.

- **Standard Elements:** 16px (`rounded-lg`) for buttons, input fields, and small cards.
- **Large Containers:** 24px (`rounded-xl`) for main content areas and large modal containers.
- **Selection Indicators:** Use pill-shaped (full radius) for small tags or active state indicators.

All borders should be kept to a 1px width to maintain a "thin-line" premium aesthetic.

## Components
### Buttons
- **Primary:** Emerald background, white text. No shadow, 16px radius.
- **Secondary:** Ghost style. 1px Navy border or no border with Navy text.
- **Tertiary:** Text-only with an underline on hover, using the Gold accent for a subtle highlight.

### Input Fields
Inputs use a white surface with a 1px #E5E7EB border. On focus, the border transitions to the Emerald accent, and a soft 4px Emerald glow (10% opacity) is applied.

### Cards
Cards are the primary vehicle for content. They must always have a white background, 24px radius, and 24px-32px of internal padding. Use "Low-contrast outlines" (1px #E5E7EB) rather than heavy shadows for a cleaner look.

### Chips & Tags
Used for categories or status. Small 12px font, 1px border, and 100px radius. Use the Gold accent for "Premium" or "Expert" tags to distinguish them.

### Lists
Lists should have generous vertical padding (16px+) between items. Use subtle dividers that do not touch the edges of the container to maintain a floating, airy feel.