## 2026-06-08T09:43:24Z
Modernize the ACSES Portal Admin interface (sidebar, header, mobile navigation, and priority pages: Dashboard Overview, Pending Registrations, and Dues/Payment Verifications) for high-fidelity aesthetics, responsiveness, and clean layout patterns using Tailwind CSS v4 and Alpine.js.

Working directory: /Applications/XAMPP/xamppfiles/htdocs/ACSES
Integrity mode: development

## Requirements

### R1. Global Admin Layout & Premium Brand Theme
Refactor the global layout structure (`resources/views/components/layouts/dashboard.blade.php`, `resources/views/components/layouts/admin.blade.php`, `resources/views/components/admin/sidebar.blade.php`, and `resources/views/components/admin/header.blade.php`) to use a sleek, cohesive modernized forest green color palette (`#0b3019` base) aligned with the `ui-ux-god-tier` guidelines. Utilize premium typography (`Instrument Sans` with compressed title line-heights and letter-spacing), clean borders (rather than harsh dark mode shadows), subtle glassmorphism backdrop blur on headers and menus, and custom SVG/Lucide icons.

### R2. Collapsible Navigation and Mobile Drawer
Ensure the desktop admin sidebar supports a clean, collapsible navigation behavior. On mobile viewports, hide the main sidebar by default and display a slide-out drawer menu with a dark backdrop overlay. Integrate the hamburger button toggle in `resources/views/components/admin/header.blade.php` to seamlessly open/close this drawer using Alpine.js reactivity, ensuring no content layout shifts or z-index clipping.

### R3. Redesigned Bento-Grid Admin Dashboard
Modernize the main admin dashboard view (`resources/views/dashboards/admin/index.blade.php`) by structuring KPI/metric cards into an asymmetrical bento-grid. KPI cards must present clean labels, values with tight letter-spacing, trend indicators (e.g. green up-arrows, amber warning icons), and colored background boxes for icons. Style list components (upcoming events, feedback hub) with clean dividers and rounded card structures.

### R4. Modernized Registrations & Verifications Views
Upgrade the index and show views for Pending Registrations (`pending-registrations/index.blade.php` and `pending-registrations/show.blade.php`) and Dues/Payment Verifications (`dues/index.blade.php` and `dues/verifications.blade.php`). Replace generic tables with responsive, elegant table structures or card grids. Implement distinct visual badges (amber for pending, emerald for approved/active, rose for rejected) and premium button group styling for actions.

### R5. Complete Mobile Responsiveness & Form Factor Clean-up
Conduct a thorough layout audit across all remaining admin views (including students, events, resources, announcements, timeline, feedback hub, and profile settings) to eliminate horizontal page scroll, adjust table columns to fit the mobile viewport dynamically, wrap long text/data cleanly, and ensure all inputs and CTA buttons are easily tapable (height >= 36px, never taller than 40px).

## Acceptance Criteria

### Global Navigation & Layout
- [ ] Navigation sidebar is collapsible on desktop and hidden by default on mobile screens.
- [ ] Mobile slide-out drawer overlay functions smoothly with no layout clipping.
- [ ] Typography uses Instrument Sans; titles have -2% to -3% letter-spacing and compact line-height.
- [ ] Lucide or SVG icons are used consistently; all generic emojis in UI indicators are removed.

### Dashboard & Priority Pages
- [ ] The admin dashboard overview (`admin/index.blade.php`) displays KPI metrics in modular, responsive bento cards with consistent 12-16px border-radius.
- [ ] Pending registrations list and details display clean status badges (emerald/amber/rose) and well-proportioned action buttons.
- [ ] Dues and Payment Verification dashboards present clear tab interfaces and clean list grids for pending checks.

### Mobile UX & Layout Integrity
- [ ] There is no horizontal page overflow or scrolling on any admin page when viewed on mobile screens (viewport width >= 320px).
- [ ] Table content wraps cleanly or utilizes custom overflow containers with visible indications of scrollability.
- [ ] Form CTA buttons are sized to content (height 36-40px) and stack appropriately on mobile viewports.
- [ ] No regression on core Laravel backend operations: adding events, managing announcements, editing timeline milestones, approving registrations, and verifying dues remain fully functional.
