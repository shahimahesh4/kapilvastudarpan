<x-filament-widgets::widget>
    <div class="kd-welcome-card">
        <!-- Ambient Decorative Glow -->
        <div class="kd-glow kd-glow-right" aria-hidden="true"></div>
        <div class="kd-glow kd-glow-left" aria-hidden="true"></div>

        <div class="kd-welcome-header">
            <!-- Greeting & Live Date -->
            <div class="kd-greeting-box">
                <div class="kd-avatar-wrapper">
                    <img src="{{ asset('images/profile-avatar.png') }}" alt="कपिलवस्तु दर्पण" class="kd-avatar-img">
                </div>
                <div>
                    <div class="kd-greeting-title-row">
                        <h2 class="kd-greeting-title">
                            नमस्ते, {{ filament()->auth()->user()?->name ?? auth()->user()?->name ?? 'एडमिन' }}! 👋
                        </h2>
                        <span class="kd-status-badge">
                            ● लाइभ अनलाइन
                        </span>
                    </div>
                    <p class="kd-greeting-sub">
                        {{ setting('site_name', 'कपिलवस्तु दर्पण') }} — डिजिटल न्युज तथा सामग्री नियन्त्रण कक्ष
                    </p>
                </div>
            </div>

            <!-- Date & Frontend Status -->
            <div class="kd-action-buttons">
                <div class="kd-date-chip">
                    <span style="font-size: 14px;">📅</span>
                    <span>{{ nepali_date() }}</span>
                </div>

                <a href="{{ route('home') }}" target="_blank" rel="noopener noreferrer" class="kd-website-btn">
                    <span>वेबसाइट हेर्नुहोस्</span>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" style="width: 14px; height: 14px; min-width: 14px; max-width: 14px; display: inline-block;">
                        <path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"></path>
                        <polyline points="15 3 21 3 21 9"></polyline>
                        <line x1="10" y1="14" x2="21" y2="3"></line>
                    </svg>
                </a>
            </div>
        </div>

        <!-- Quick Action Shortcut Buttons Grid -->
        <div class="kd-shortcuts-grid">
            <a href="/admin/articles/create" class="kd-shortcut-btn">
                <span class="kd-shortcut-icon">✍️</span>
                <span class="kd-shortcut-label">नयाँ समाचार थप्नुहोस्</span>
            </a>

            <a href="/admin/pages/create" class="kd-shortcut-btn">
                <span class="kd-shortcut-icon">📄</span>
                <span class="kd-shortcut-label">नयाँ पृष्ठ बनाउनुहोस्</span>
            </a>

            <a href="/admin/rashifals" class="kd-shortcut-btn">
                <span class="kd-shortcut-icon">🌟</span>
                <span class="kd-shortcut-label">राशिफल व्यवस्थापन</span>
            </a>

            <a href="/admin/advertisements" class="kd-shortcut-btn">
                <span class="kd-shortcut-icon">📢</span>
                <span class="kd-shortcut-label">विज्ञापन नियन्त्रण</span>
            </a>

            <a href="/admin/website-settings" class="kd-shortcut-btn">
                <span class="kd-shortcut-icon">⚙️</span>
                <span class="kd-shortcut-label">वेबसाइट सेटिङ</span>
            </a>
        </div>
    </div>

    <style>
        .kd-welcome-card {
            position: relative;
            overflow: hidden;
            border-radius: 1rem;
            background: linear-gradient(135deg, #0955cf 0%, #123e80 50%, #172b4d 100%);
            padding: 1.5rem 1.75rem;
            color: #ffffff;
            box-shadow: 0 4px 14px rgba(0, 0, 0, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.12);
            font-family: inherit;
        }

        .kd-glow {
            position: absolute;
            pointer-events: none;
            border-radius: 9999px;
            filter: blur(48px);
        }

        .kd-glow-right {
            top: -4rem;
            right: -4rem;
            width: 16rem;
            height: 16rem;
            background: rgba(255, 255, 255, 0.12);
        }

        .kd-glow-left {
            bottom: -4rem;
            left: -4rem;
            width: 16rem;
            height: 16rem;
            background: rgba(220, 38, 38, 0.18);
        }

        .kd-welcome-header {
            position: relative;
            z-index: 10;
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        @media (min-width: 1024px) {
            .kd-welcome-header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .kd-greeting-box {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .kd-avatar-wrapper {
            width: 52px;
            height: 52px;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 1rem;
            background: rgba(255, 255, 255, 0.18);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.25);
            box-shadow: inset 0 1px 2px rgba(255, 255, 255, 0.2);
        }

        .kd-avatar-img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            filter: drop-shadow(0 2px 4px rgba(0,0,0,0.15));
        }

        .kd-greeting-title-row {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .kd-greeting-title {
            margin: 0;
            font-size: 1.35rem;
            font-weight: 900;
            line-height: 1.3;
            letter-spacing: -0.01em;
            color: #ffffff;
        }

        .kd-status-badge {
            display: inline-flex;
            align-items: center;
            padding: 2px 10px;
            border-radius: 9999px;
            background: rgba(52, 211, 153, 0.2);
            border: 1px solid rgba(110, 231, 183, 0.35);
            color: #a7f3d0;
            font-size: 11px;
            font-weight: 700;
            line-height: 1.4;
        }

        .kd-greeting-sub {
            margin: 4px 0 0 0;
            font-size: 0.85rem;
            color: #dbeafe;
            font-weight: 500;
        }

        .kd-action-buttons {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.65rem;
        }

        .kd-date-chip {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 7px 14px;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.12);
            border: 1px solid rgba(255, 255, 255, 0.18);
            font-size: 0.78rem;
            font-weight: 700;
            color: #ffffff;
        }

        .kd-website-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            padding: 7px 16px;
            border-radius: 0.75rem;
            background: #ffffff;
            color: #0955cf;
            font-size: 0.78rem;
            font-weight: 800;
            text-decoration: none;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.1);
            transition: all 0.15s ease-in-out;
        }

        .kd-website-btn:hover {
            background: #fde047;
            color: #1e293b;
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.15);
        }

        .kd-website-btn svg {
            width: 14px !important;
            height: 14px !important;
            min-width: 14px !important;
            max-width: 14px !important;
            flex-shrink: 0;
        }

        .kd-shortcuts-grid {
            position: relative;
            z-index: 10;
            margin-top: 1.25rem;
            padding-top: 1.25rem;
            border-top: 1px solid rgba(255, 255, 255, 0.15);
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0.65rem;
        }

        @media (min-width: 640px) {
            .kd-shortcuts-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .kd-shortcuts-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }

        .kd-shortcut-btn {
            display: flex;
            align-items: center;
            gap: 0.65rem;
            padding: 8px 12px;
            border-radius: 0.75rem;
            background: rgba(255, 255, 255, 0.1);
            border: 1px solid rgba(255, 255, 255, 0.12);
            color: #ffffff;
            font-size: 0.76rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.15s ease-in-out;
            overflow: hidden;
        }

        .kd-shortcut-btn:hover {
            background: rgba(255, 255, 255, 0.22);
            border-color: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
            color: #ffffff;
        }

        .kd-shortcut-icon {
            font-size: 1rem;
            flex-shrink: 0;
            transition: transform 0.15s;
        }

        .kd-shortcut-btn:hover .kd-shortcut-icon {
            transform: scale(1.15);
        }

        .kd-shortcut-label {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
    </style>
</x-filament-widgets::widget>
