<!-- Google Fonts Preconnect & Kanit (ADR-0009) -->
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Kanit:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400&display=swap" rel="stylesheet">
<!-- FontAwesome 6 Core Icons -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    /* Universal Kanit Typography Across Application (ADR-0009) */
    html, body,
    h1, h2, h3, h4, h5, h6,
    p, span, a, label, li, ul, ol,
    input, button, select, textarea, optgroup, option,
    table, thead, tbody, tfoot, tr, th, td,
    .card, .card-header, .card-body, .card-footer, .card-title,
    .navbar, .navbar-brand, .nav, .nav-item, .nav-link,
    .badge, .alert, .dropdown, .dropdown-menu, .dropdown-item,
    .modal, .modal-title, .modal-body, .modal-footer,
    .btn, .btn-sm, .btn-lg, .form-control, .form-select, .form-check-label,
    .table-responsive, .text-sans-serif, .font-sans,
    div {
        font-family: 'Kanit', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif !important;
    }

    /* Preserve Icon Fonts */
    i, .fa, .fas, .far, .fab, .fal, .fad,
    [class*="fa-"], [class^="fa-"],
    .ni, [class*="ni-"], [class^="ni-"],
    .nucleo, .material-icons, [class*="bi-"], [class^="bi-"] {
        font-family: 'Font Awesome 6 Free', 'Font Awesome 5 Free', 'FontAwesome', 'NucleoIcons', 'bootstrap-icons' !important;
    }
    .fab {
        font-family: 'Font Awesome 6 Brands', 'Font Awesome 5 Brands', 'FontAwesome' !important;
    }
</style>
