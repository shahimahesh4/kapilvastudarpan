<div class="fi-topbar-custom-actions">
    <!-- Live Nepali Date Chip -->
    <div class="fi-topbar-date-chip" title="आजको मिति (Nepali Date)">
        <span class="fi-topbar-date-icon">📅</span>
        <span class="fi-topbar-date-text">{{ nepali_date() }}</span>
    </div>

    <!-- Visit Our Website Button -->
    <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="fi-topbar-website-btn" title="Visit our website (वेबसाइट हेर्नुहोस्)">
        <span>वेबसाइट हेर्नुहोस्</span>
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 13px; height: 13px; min-width: 13px; max-width: 13px; display: inline-block;">
            <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
            <polyline points="15 3 21 3 21 9"></polyline>
            <line x1="10" y1="14" x2="21" y2="3"></line>
        </svg>
    </a>

    @if(auth()->user()?->isSuperAdmin())
    <!-- Download Database Export (.sql) Button (Super Admin Only) -->
    <a href="{{ route('admin.database.export') }}" class="fi-topbar-db-btn" title="Download database.sql (पूर्ण डाटाबेस ब्याकअप डाउनलोड)">
        <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 13px; height: 13px; min-width: 13px; max-width: 13px; display: inline-block;">
            <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
            <polyline points="7 10 12 15 17 10"></polyline>
            <line x1="12" y1="15" x2="12" y2="3"></line>
        </svg>
        <span>database.sql</span>
    </a>
    @endif
</div>

<style>
    .fi-topbar-custom-actions {
        display: inline-flex;
        align-items: center;
        gap: 0.6rem;
        margin-right: 0.5rem;
    }

    .fi-topbar-date-chip {
        display: none;
        align-items: center;
        gap: 0.4rem;
        padding: 5px 12px;
        border-radius: 0.5rem;
        background: #f1f5f9;
        border: 1px solid #e2e8f0;
        font-size: 0.76rem;
        font-weight: 700;
        color: #334155;
        white-space: nowrap;
        user-select: none;
    }

    @media (min-width: 640px) {
        .fi-topbar-date-chip {
            display: inline-flex;
        }
    }

    :is(.dark) .fi-topbar-date-chip {
        background: #27272a;
        border-color: rgba(255, 255, 255, 0.12);
        color: #cbd5e1;
    }

    .fi-topbar-date-icon {
        font-size: 0.85rem;
        line-height: 1;
    }

    .fi-topbar-website-btn {
        display: inline-flex;
        align-items: center;
        gap: 0.45rem;
        padding: 6px 13px;
        border-radius: 0.5rem;
        background: #244b92;
        color: #ffffff !important;
        font-size: 0.76rem;
        font-weight: 800;
        text-decoration: none !important;
        box-shadow: 0 1px 3px rgba(36, 75, 146, 0.25);
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
        border: 1px solid #1e3a8a;
    }

    .fi-topbar-website-btn:hover {
        background: #c92137;
        border-color: #991b1b;
        color: #ffffff !important;
        transform: translateY(-1px);
        box-shadow: 0 3px 8px rgba(201, 33, 55, 0.3);
    }

    .fi-topbar-website-btn svg {
        width: 13px !important;
        height: 13px !important;
        min-width: 13px !important;
        max-width: 13px !important;
        stroke: currentColor;
    }

    .fi-topbar-db-btn {
        display: none;
        align-items: center;
        gap: 0.4rem;
        padding: 5px 12px;
        border-radius: 0.5rem;
        background: #ecfdf5;
        border: 1px solid rgba(16, 185, 129, 0.4);
        color: #065f46 !important;
        font-size: 0.76rem;
        font-weight: 700;
        text-decoration: none !important;
        transition: all 0.15s ease-in-out;
        white-space: nowrap;
    }

    @media (min-width: 768px) {
        .fi-topbar-db-btn {
            display: inline-flex;
        }
    }

    .fi-topbar-db-btn:hover {
        background: #d1fae5;
        border-color: #10b981;
        color: #047857 !important;
        transform: translateY(-1px);
        box-shadow: 0 2px 6px rgba(16, 185, 129, 0.15);
    }

    :is(.dark) .fi-topbar-db-btn {
        background: #064e3b;
        border-color: rgba(16, 185, 129, 0.5);
        color: #6ee7b7 !important;
    }
</style>
