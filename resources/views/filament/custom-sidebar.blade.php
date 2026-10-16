<style>
    /* ========================================================
       Filament Admin Sidebar - Brand Blue Background (#244b92)
       ======================================================== */
    aside#fi-main-sidebar,
    #fi-main-sidebar.fi-sidebar,
    #fi-main-sidebar .fi-sidebar-header-ctn,
    #fi-main-sidebar .fi-sidebar-header,
    #fi-main-sidebar .fi-sidebar-nav,
    #fi-main-sidebar .fi-sidebar-footer {
        background-color: #244b92 !important;
        background: #244b92 !important;
    }

    /* Right border of the sidebar */
    aside#fi-main-sidebar {
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
        fill: currentColor;
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
       Filament Rich Editor - Spacious Height for Articles
       ======================================================== */
    .fi-fo-rich-editor .fi-fo-rich-editor-content,
    .fi-fo-rich-editor .fi-fo-rich-editor-content > .tiptap,
    .fi-fo-rich-editor .fi-fo-rich-editor-content > .ProseMirror,
    .fi-fo-rich-editor .fi-fo-rich-editor-main {
        min-height: 450px !important;
    }
</style>
