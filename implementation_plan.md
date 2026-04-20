# Admin Panel Improvements & UI Fixes

## 1. History Section (Admin Panel)

A new admin-only History page showing departure records with summary stat cards.

### [NEW] [History.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Controllers/Admin/History.php)

New controller that queries the `queue` table for `status = 'departed'` entries and computes:
- **Total departed** (all time)
- **Today's departures** (WHERE `departure_time >= today`)
- **This month** (WHERE `departure_time >= first of month`)
- **This year** (WHERE `departure_time >= Jan 1`)

Also fetches the paginated departure list with search support (reusing the existing History controller's query pattern).

### [NEW] [admin/history/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/history/index.php)

New view with:
- 4 stat cards at the top (Total, Today, This Month, This Year)—same card style as admin dashboard stat cards
- Search bar for filtering by plate number, owner, or destination
- Departure history table (plate, vehicle type, route, departure time, driver)
- Pagination

### [MODIFY] [Routes.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Config/Routes.php)

Add `admin/history` route inside the admin group.

### [MODIFY] [navbar.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/templates/navbar.php)

Add "History" link to admin navigation menu.

---

## 2. Layout & Positioning Standardization

Standardize all admin page headers to use a consistent layout pattern:

```html
<div class="d-flex justify-content-between align-items-center pt-3 pb-2 mb-3 border-bottom">
    <h1 class="h2">Page Title</h1>
    <div><!-- action buttons --></div>
</div>
```

### Files to update with consistent header pattern:

| File | Current | Fix |
|------|---------|-----|
| [vehicles/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/vehicles/index.php) | `<div class="row mb-3">` | Standardize to d-flex border-bottom |
| [routes/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/routes/index.php) | `<div class="row mb-3">` | Standardize |
| [terminals/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/terminals/index.php) | `<div class="row mb-3">` | Standardize |
| [announcements/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/announcements/index.php) | `<div class="row mb-3">` | Standardize |
| [departure-rules/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/departure-rules/index.php) | `<div class="row mb-3">` | Standardize |
| [logs/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/logs/index.php) | `<div class="row mb-3">` | Standardize |
| [users/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/users/index.php) | Already uses d-flex ✓ | No change |
| [admin/queue/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/queue/index.php) | `<div class="row mb-3">` | Already done ✓ |

### [MODIFY] [users/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/users/index.php)

Wrap the bare `<table>` in a `<div class="card shadow">` to match all other admin pages.

---

## 3. Schedule & Fare Pages — CSS Conflict Fix for Admin/Staff

The Schedules and Fares pages have their own complete standalone CSS (they define their own `header`, `.card-header`, `.nav-menu` styles). When an admin/staff is logged in, [admin-modern.css](file:///c:/xampp2/htdocs/jeepneynvans/public/assets/css/admin-modern.css) also loads and conflicts, causing style clashes.

### [MODIFY] [admin-modern.css](file:///c:/xampp2/htdocs/jeepneynvans/public/assets/css/admin-modern.css)

Scope the forceful card/table/button overrides to only apply within `.main-content` (the admin page area), NOT on public-page bodies. Add:

```css
body.public-page .card-header { /* Remove admin overrides */ }
body.public-page .card { /* Remove admin overrides */ }
```

### [MODIFY] [header.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/templates/header.php)

Ensure that when the `body_class` includes `public-page`, the admin-modern.css overrides don't leak into the Schedules/Fares full-page layouts.

---

## 4. Overall Table & Card Consistency

Add consistent `table-light` thead class to all admin tables that lack it, ensuring uniform gray header styling:

| File | Current thead | Fix |
|------|---------------|-----|
| [vehicles/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/vehicles/index.php) | `<thead>` (no class) | Add `class="table-light"` |
| [routes/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/routes/index.php) | `<thead>` (no class) | Add `class="table-light"` |
| [terminals/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/terminals/index.php) | `<thead>` (no class) | Add `class="table-light"` |
| [announcements/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/announcements/index.php) | `<thead>` (no class) | Add `class="table-light"` |
| [departure-rules/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/departure-rules/index.php) | `<thead>` (no class) | Add `class="table-light"` |
| [logs/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/logs/index.php) | `<thead>` (no class) | Add `class="table-light"` |
| [users/index.php](file:///c:/xampp2/htdocs/jeepneynvans/app/Views/admin/users/index.php) | `<thead>` (no class) | Add `class="table-light"` |

---

## Verification Plan

### Browser Tests
1. Admin Dashboard — stat cards still colored with white text
2. Admin History — new page with 4 stat cards and departure table
3. All admin pages — consistent page headers with border-bottom
4. All admin tables — consistent gray thead styling
5. Users page — wrapped in card
6. Schedules/Fares — no style conflicts when logged in as admin/staff
