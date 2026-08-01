---
name: XO Edu Lab
colors:
  surface: '#f8f9ff'
  surface-dim: '#cbdbf5'
  surface-bright: '#f8f9ff'
  surface-container-lowest: '#ffffff'
  surface-container-low: '#eff4ff'
  surface-container: '#e5eeff'
  surface-container-high: '#dce9ff'
  surface-container-highest: '#d3e4fe'
  on-surface: '#0b1c30'
  on-surface-variant: '#434655'
  inverse-surface: '#213145'
  inverse-on-surface: '#eaf1ff'
  outline: '#737686'
  outline-variant: '#c3c6d7'
  surface-tint: '#0053db'
  primary: '#004ac6'
  on-primary: '#ffffff'
  primary-container: '#2563eb'
  on-primary-container: '#eeefff'
  inverse-primary: '#b4c5ff'
  secondary: '#006c49'
  on-secondary: '#ffffff'
  secondary-container: '#6cf8bb'
  on-secondary-container: '#00714d'
  tertiary: '#784b00'
  on-tertiary: '#ffffff'
  tertiary-container: '#996100'
  on-tertiary-container: '#ffeedd'
  error: '#ba1a1a'
  on-error: '#ffffff'
  error-container: '#ffdad6'
  on-error-container: '#93000a'
  primary-fixed: '#dbe1ff'
  primary-fixed-dim: '#b4c5ff'
  on-primary-fixed: '#00174b'
  on-primary-fixed-variant: '#003ea8'
  secondary-fixed: '#6ffbbe'
  secondary-fixed-dim: '#4edea3'
  on-secondary-fixed: '#002113'
  on-secondary-fixed-variant: '#005236'
  tertiary-fixed: '#ffddb8'
  tertiary-fixed-dim: '#ffb95f'
  on-tertiary-fixed: '#2a1700'
  on-tertiary-fixed-variant: '#653e00'
  background: '#f8f9ff'
  on-background: '#0b1c30'
  surface-variant: '#d3e4fe'
typography:
  display-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 48px
    fontWeight: '700'
    lineHeight: 56px
    letterSpacing: -0.02em
  display-lg-mobile:
    fontFamily: Be Vietnam Pro
    fontSize: 32px
    fontWeight: '700'
    lineHeight: 40px
    letterSpacing: -0.02em
  headline-md:
    fontFamily: Be Vietnam Pro
    fontSize: 24px
    fontWeight: '600'
    lineHeight: 32px
    letterSpacing: -0.01em
  body-lg:
    fontFamily: Be Vietnam Pro
    fontSize: 18px
    fontWeight: '400'
    lineHeight: 28px
  body-md:
    fontFamily: Be Vietnam Pro
    fontSize: 16px
    fontWeight: '400'
    lineHeight: 24px
  label-sm:
    fontFamily: Be Vietnam Pro
    fontSize: 14px
    fontWeight: '500'
    lineHeight: 20px
    letterSpacing: 0.02em
  caption:
    fontFamily: Be Vietnam Pro
    fontSize: 12px
    fontWeight: '400'
    lineHeight: 16px
rounded:
  sm: 0.25rem
  DEFAULT: 0.5rem
  md: 0.75rem
  lg: 1rem
  xl: 1.5rem
  full: 9999px
spacing:
  base: 4px
  xs: 8px
  sm: 16px
  md: 24px
  lg: 48px
  xl: 80px
  gutter: 24px
  margin-mobile: 16px
  margin-desktop: 64px
---

## Brand & Style

The design system for this product is centered on the philosophy of "Space to Become." It reflects a premium educational technology environment that balances professional rigor with a supportive, growth-oriented atmosphere.

The visual style is **Corporate Modern with a Minimalist lean**, emphasizing clarity and openness. It avoids cluttered interfaces in favor of generous whitespace and a highly structured information hierarchy. The emotional response should be one of calm confidence, reliability, and intellectual space. The UI acts as a silent partner in the user's educational journey—present and functional, but never overwhelming the content or the "story" of the learner's progress.

## Colors

The palette is designed to maintain a "Calm Light" environment. 

- **Primary Blue (#2563EB):** Used for primary actions, active states, and brand-critical indicators. It represents depth and professional stability.
- **Secondary Green (#10B981):** Reserved for success states, progress completion, and positive growth accents.
- **Surface (#F8FAFC):** An off-white, cool-toned gray that reduces eye strain and provides a premium feel compared to pure white.
- **Neutral Grays:** Used for structural borders (#E2E8F0) and secondary text to establish a clear hierarchy without high-contrast harshness.

## Typography

The typography uses **Be Vietnam Pro** to achieve a friendly yet contemporary tone. The type scale is generous, prioritizing legibility and a "story-driven" editorial feel.

- **Headlines:** Use tighter letter-spacing and heavier weights to anchor the page.
- **Body Text:** Uses a comfortable 1.5x line height to ensure long-form educational content is digestible.
- **Labels:** Set in Medium weight with slight tracking for high scanability in data-heavy views or navigation.

## Layout & Spacing

The layout follows a **Fluid Grid** model with a soft 8px rhythm. 

- **Desktop (1440px+):** 12-column grid with 64px outside margins and 24px gutters.
- **Tablet (768px - 1024px):** 8-column grid with 32px margins.
- **Mobile (<768px):** 4-column grid with 16px margins.

Use "lg" and "xl" spacing tokens for vertical section separation to reinforce the "Space to Become" philosophy. Content should never feel cramped; if a component feels crowded, increase padding to the next token level.

## Elevation & Depth

This design system uses **Tonal Layering** combined with **Ambient Shadows**. Depth is used to signify interactivity and importance rather than literal physical height.

- **Base Layer:** The Surface color (#F8FAFC).
- **Raised Layer (Cards/Modals):** Pure White (#FFFFFF) with a very soft, diffused shadow (0px 4px 20px rgba(0, 0, 0, 0.04)).
- **Interactive State:** On hover, cards should subtly lift using a slightly more pronounced shadow (0px 10px 30px rgba(37, 99, 235, 0.08))—tinting the shadow with the primary blue to add a premium touch.
- **Borders:** Use low-contrast 1px solid borders (#E2E8F0) for secondary containers that do not require shadow-based elevation.

## Shapes

The shape language is consistently **Rounded**. This softens the "institutional" feel of traditional education and makes the lab environment feel more approachable.

- **Standard Elements (Buttons, Inputs):** 0.5rem (8px) radius.
- **Containers (Cards, KPI Grids):** 1rem (16px) radius.
- **Large Sections (Feature blocks):** 1.5rem (24px) radius.

## Components

### Buttons
Primary buttons use the Primary Blue with white text and 8px rounded corners. Secondary buttons use a transparent background with a 1px border of Primary Blue.

### KPI Grids
Dynamic KPI cards should feature a subtle gradient background or a tinted "Success Green" for positive trends. Text inside should follow the `label-sm` for titles and `headline-md` for the metric value.

### Timeline Connectors
Use a 2px dashed or solid neutral line (#E2E8F0) to connect chronological nodes. Active nodes should be highlighted with a Primary Blue ring.

### Cards
Cards are the primary container. They must always use the White background, 16px radius, and the ambient shadow defined in the Elevation section. Internal padding for cards should default to `md` (24px).

### Input Fields
Inputs use a 1px neutral border that transitions to Primary Blue on focus. Use `caption` text for help text or error messages directly below the field.