<style>
    /* ========================================================
       Filament Admin Sidebar - Brand Blue Background (#244b92)
       ======================================================== */
    #fi-main-sidebar,
    #fi-main-sidebar.fi-sidebar,
    #fi-main-sidebar .fi-sidebar-header-ctn,
    #fi-main-sidebar .fi-sidebar-header,
    #fi-main-sidebar .fi-sidebar-nav,
    #fi-main-sidebar .fi-sidebar-footer {
        background-color: #244b92 !important;
        background: #244b92 !important;
    }

    /* Right border of the sidebar */
    #fi-main-sidebar {
        border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
        box-shadow: 2px 0 8px rgba(0, 0, 0, 0.06);
    }

    /* Header controls divider */
    #fi-main-sidebar .fi-sidebar-header-controls::after {
        border-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* Group Labels / Headings */
    #fi-main-sidebar .fi-sidebar-group-label {
        color: #93c5fd !important;
        font-weight: 700 !important;
        letter-spacing: 0.03em !important;
        text-transform: uppercase;
        font-size: 0.72rem;
    }

    /* Navigation Item Font Size (16px) */
    #fi-main-sidebar .fi-sidebar-item-btn,
    #fi-main-sidebar .fi-sidebar-item-label,
    #fi-main-sidebar .fi-sidebar-sub-group-item-btn,
    #fi-main-sidebar .fi-sidebar-sub-group-item-label,
    .fi-sidebar-nav .fi-sidebar-item .fi-sidebar-item-label,
    .fi-sidebar-nav .fi-sidebar-item .fi-sidebar-item-btn,
    .fi-topbar-item-btn,
    .fi-topbar-item-label {
        font-size: 16px !important;
        line-height: 1.5 !important;
    }

    /* Inactive Navigation Item Buttons */
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn {
        color: #f1f5f9 !important;
        background-color: transparent !important;
        transition: all 0.15s ease-in-out;
    }

    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #f1f5f9 !important;
    }

    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn .fi-sidebar-item-icon,
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn .fi-icon,
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn svg {
        color: #cbd5e1 !important;
    }

    /* Inactive Hover & Focus States */
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover,
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:focus-visible {
        background-color: rgba(255, 255, 255, 0.15) !important;
        color: #ffffff !important;
    }

    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover .fi-sidebar-item-label,
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover .fi-sidebar-item-icon,
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover .fi-icon,
    #fi-main-sidebar .fi-sidebar-item:not(.fi-active) > .fi-sidebar-item-btn:hover svg {
        color: #ffffff !important;
    }

    /* Active Navigation Item (Clean White Pill for maximum contrast) */
    #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn {
        background-color: #ffffff !important;
        color: #c92137 !important;
        box-shadow: 0 2px 6px rgba(0, 0, 0, 0.15) !important;
        font-weight: 700 !important;
    }

    #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-label {
        color: #c92137 !important;
        font-weight: 700 !important;
    }

    #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-sidebar-item-icon,
    #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn .fi-icon,
    #fi-main-sidebar .fi-sidebar-item.fi-active > .fi-sidebar-item-btn svg {
        color: #c92137 !important;
    }

    /* Collapse / Expand Toggle Buttons */
    #fi-main-sidebar .fi-sidebar-close-collapse-sidebar-btn,
    #fi-main-sidebar .fi-sidebar-open-collapse-sidebar-btn,
    #fi-main-sidebar .fi-sidebar-group-btn .fi-icon {
        color: #ffffff !important;
    }

    #fi-main-sidebar .fi-sidebar-close-collapse-sidebar-btn:hover,
    #fi-main-sidebar .fi-sidebar-open-collapse-sidebar-btn:hover {
        background-color: rgba(255, 255, 255, 0.15) !important;
    }

    /* Badges in sidebar */
    #fi-main-sidebar .fi-badge {
        background-color: rgba(255, 255, 255, 0.2) !important;
        color: #ffffff !important;
    }

    /* ========================================================
       Global SVG Safety Constraint
       ======================================================== */
    .fi-wi-widget svg:not([width]) {
        max-width: 100%;
        height: auto;
    }

    /* ========================================================
       Filament Rich Editor - Spacious Height for Articles
       ======================================================== */
    .fi-fo-rich-editor .fi-fo-rich-editor-content,
    .fi-fo-rich-editor .fi-fo-rich-editor-content > .tiptap,
    .fi-fo-rich-editor .fi-fo-rich-editor-content > .ProseMirror,
    .fi-fo-rich-editor .fi-fo-rich-editor-main {
        min-height: 450px !important;
    }

    /* ========================================================
       Dashboard Page - Remove redundant heading and eliminate gaps
       ======================================================== */
    .kd-compact-dashboard-page .fi-header,
    .kd-compact-dashboard-page .fi-header-heading,
    .fi-page:has(.kd-welcome-card) .fi-header,
    .fi-page:has(.kd-welcome-card) .fi-header-heading {
        display: none !important;
        margin: 0 !important;
        padding: 0 !important;
        height: 0 !important;
        min-height: 0 !important;
    }

    .kd-compact-dashboard-page,
    .kd-compact-dashboard-page.fi-page,
    .fi-page:has(.kd-welcome-card) {
        padding-top: 0.5rem !important;
    }

    .kd-compact-dashboard-page .fi-page-header-main-ctn,
    .fi-page:has(.kd-welcome-card) .fi-page-header-main-ctn {
        gap: 0.75rem !important;
        row-gap: 0.75rem !important;
    }

    .kd-compact-dashboard-page .fi-page-content,
    .fi-page:has(.kd-welcome-card) .fi-page-content {
        margin-top: 0 !important;
        padding-top: 0 !important;
    }
</style>
