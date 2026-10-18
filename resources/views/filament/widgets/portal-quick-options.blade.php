<x-filament-widgets::widget>
    @php
        $info = $this->getSystemInfo();
        $dbStats = $this->getDatabaseStats();
    @endphp

    <div class="kd-options-card">
        <div class="kd-options-header">
            <div>
                <h3 class="kd-options-title">
                    ⚡ पोर्टल नियन्त्रण तथा मर्मत विकल्पहरू (Quick Portal Tools)
                </h3>
                <p class="kd-options-subtitle">
                    प्रणालीको क्यास व्यवस्थापन, साइटम्याप स्थिति तथा तत्काल कन्ट्रोल विकल्पहरू
                </p>
            </div>

            <!-- Diagnostics Pill -->
            <div class="kd-diag-pills">
                <span class="kd-diag-pill">
                    <span class="kd-status-dot"></span>
                    डाटाबेस: {{ $info['db_status'] }}
                </span>
                <span class="kd-diag-pill">
                    PHP v{{ $info['php_version'] }} / Laravel v{{ $info['laravel_version'] }}
                </span>
            </div>
        </div>

        <div class="kd-options-grid">
            <!-- Maintenance Action 1: Optimize & Clear Cache -->
            <button type="button" wire:click="clearSystemCache" wire:loading.attr="disabled" class="kd-opt-btn kd-opt-btn-primary">
                <span class="kd-opt-icon">🧹</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main" wire:loading.remove wire:target="clearSystemCache">
                        क्यास खाली गर्नुहोस् (Clear Cache)
                    </span>
                    <span class="kd-opt-main" wire:loading wire:target="clearSystemCache">
                        सफा गर्दैछ...
                    </span>
                    <span class="kd-opt-sub">एप्लिकेसन, रुट तथा कन्फिग क्यास</span>
                </div>
            </button>

            <!-- Maintenance Action 2: Clear View Cache -->
            <button type="button" wire:click="clearViewCache" wire:loading.attr="disabled" class="kd-opt-btn kd-opt-btn-secondary">
                <span class="kd-opt-icon">🔄</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main" wire:loading.remove wire:target="clearViewCache">
                        भ्यु क्यास रिफ्रेस (Clear Views)
                    </span>
                    <span class="kd-opt-main" wire:loading wire:target="clearViewCache">
                        रिफ्रेस गर्दैछ...
                    </span>
                    <span class="kd-opt-sub">ब्लेड टेम्प्लेट क्यास रिसेट</span>
                </div>
            </button>

            @if(auth()->user()?->isSuperAdmin())
            <!-- Quick Action: Database SQL Export / Download (Super Admin Only) -->
            <a href="{{ route('admin.database.export') }}" class="kd-opt-btn kd-opt-btn-database" title="Download database.sql">
                <span class="kd-opt-icon">💾</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main">
                        डाटाबेस डाउनलोड (database.sql)
                    </span>
                    <span class="kd-opt-sub">ब्याकअप: {{ $dbStats['formatted_size'] }} ({{ $dbStats['tables_count'] }} तालिकाहरू)</span>
                </div>
            </a>
            @endif

            <!-- Quick Link 1: Sitemap -->
            <a href="{{ url('/sitemap.xml') }}" target="_blank" rel="noopener noreferrer" class="kd-opt-btn kd-opt-btn-link">
                <span class="kd-opt-icon">🗺️</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main">
                        XML साइटम्याप (Live Sitemap)
                    </span>
                    <span class="kd-opt-sub">गुगल तथा सर्च इन्जिन इन्डेक्सिङ</span>
                </div>
            </a>

            <!-- Quick Link 2: Menu Items -->
            <a href="/stnapanel/menu-items" class="kd-opt-btn kd-opt-btn-link">
                <span class="kd-opt-icon">🧭</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main">
                        नेभिगेसन मेनु व्यवस्थापन
                    </span>
                    <span class="kd-opt-sub">हेडर तथा ड्रपडाउन मेनु मिलाउनुहोस्</span>
                </div>
            </a>

            <!-- Quick Link 3: Nepali Unicode Converter -->
            <a href="/stnapanel/nepali-unicode-converter" class="kd-opt-btn kd-opt-btn-unicode">
                <span class="kd-opt-icon">⌨️</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main">
                        नेपाली युनिकोड कन्भर्टर
                    </span>
                    <span class="kd-opt-sub">रोमन र प्रीति फन्ट रूपान्तरण</span>
                </div>
            </a>

            @php
                $smEnabled = setting_bool('sharemarket_enabled', true);
                $tickerEnabled = setting_bool('sharemarket_ticker_enabled', true);
            @endphp

            <!-- Quick Action: Toggle Share Market Feature -->
            <button type="button" wire:click="toggleShareMarket" wire:loading.attr="disabled" class="kd-opt-btn {{ $smEnabled ? 'kd-opt-btn-sharemarket' : 'kd-opt-btn-disabled' }}" title="शेयर बजार फिचर अन/अफ गर्नुहोस्">
                <span class="kd-opt-icon">{{ $smEnabled ? '📈' : '🛑' }}</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main" wire:loading.remove wire:target="toggleShareMarket">
                        शेयर बजार: {{ $smEnabled ? 'सक्रिय (On)' : 'निष्क्रिय (Off)' }}
                    </span>
                    <span class="kd-opt-main" wire:loading wire:target="toggleShareMarket">
                        अपडेट गर्दैछ...
                    </span>
                    <span class="kd-opt-sub">
                        {{ $smEnabled ? 'क्लिक गरी बन्द (Disable) गर्नुहोस्' : 'क्लिक गरी सुरु (Enable) गर्नुहोस्' }}
                    </span>
                </div>
            </button>

            <!-- Quick Action: Toggle Header Ticker Strip -->
            <button type="button" wire:click="toggleShareMarketTicker" wire:loading.attr="disabled" class="kd-opt-btn {{ $tickerEnabled ? 'kd-opt-btn-link' : 'kd-opt-btn-disabled' }}" title="हेडर टिकर अन/अफ गर्नुहोस्">
                <span class="kd-opt-icon">{{ $tickerEnabled ? '📊' : '⏸️' }}</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main" wire:loading.remove wire:target="toggleShareMarketTicker">
                        हेडर टिकर: {{ $tickerEnabled ? 'सक्रिय (On)' : 'निष्क्रिय (Off)' }}
                    </span>
                    <span class="kd-opt-main" wire:loading wire:target="toggleShareMarketTicker">
                        अपडेट गर्दैछ...
                    </span>
                    <span class="kd-opt-sub">
                        {{ $tickerEnabled ? 'क्लिक गरी टिकर लुकाउनुहोस्' : 'क्लिक गरी टिकर देखाउनुहोस्' }}
                    </span>
                </div>
            </button>

            <!-- Quick Link 4: Share Market Live -->
            <a href="{{ $smEnabled ? '/sharemarket' : '/stnapanel/website-settings' }}" {{ $smEnabled ? 'target="_blank" rel="noopener noreferrer"' : '' }} class="kd-opt-btn {{ $smEnabled ? 'kd-opt-btn-sharemarket' : 'kd-opt-btn-disabled' }}">
                <span class="kd-opt-icon">🌐</span>
                <div class="kd-opt-text">
                    <span class="kd-opt-main">
                        {{ $smEnabled ? 'शेयर बजार लाइभ पृष्ठ' : 'शेयर बजार सेटिङ्स' }}
                    </span>
                    <span class="kd-opt-sub">
                        {{ $smEnabled ? '/sharemarket प्रत्यक्ष हेर्नुहोस्' : 'सेटिङ्सबाट फिचर मिलाउनुहोस्' }}
                    </span>
                </div>
            </a>
        </div>
    </div>

    <style>
        .kd-options-card {
            background: #ffffff;
            border-radius: 1rem;
            padding: 1.25rem 1.5rem;
            border: 1px solid rgba(0, 0, 0, 0.08);
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.05);
            font-family: inherit;
        }

        :is(.dark) .kd-options-card {
            background: #18181b;
            border-color: rgba(255, 255, 255, 0.1);
        }

        .kd-options-header {
            display: flex;
            flex-direction: column;
            gap: 0.75rem;
            padding-bottom: 1rem;
            border-bottom: 1px solid rgba(0, 0, 0, 0.06);
        }

        @media (min-width: 768px) {
            .kd-options-header {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .kd-options-title {
            margin: 0;
            font-size: 1rem;
            font-weight: 800;
            color: #1e293b;
        }

        :is(.dark) .kd-options-title {
            color: #f1f5f9;
        }

        .kd-options-subtitle {
            margin: 2px 0 0 0;
            font-size: 0.78rem;
            color: #64748b;
        }

        :is(.dark) .kd-options-subtitle {
            color: #94a3b8;
        }

        .kd-diag-pills {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            gap: 0.5rem;
        }

        .kd-diag-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 4px 10px;
            border-radius: 9999px;
            background: #f1f5f9;
            color: #475569;
            font-size: 0.72rem;
            font-weight: 600;
        }

        :is(.dark) .kd-diag-pill {
            background: #27272a;
            color: #cbd5e1;
        }

        .kd-status-dot {
            width: 7px;
            height: 7px;
            border-radius: 9999px;
            background: #10b981;
            display: inline-block;
        }

        .kd-options-grid {
            display: grid;
            grid-template-columns: repeat(1, minmax(0, 1fr));
            gap: 0.75rem;
            margin-top: 1rem;
        }

        @media (min-width: 640px) {
            .kd-options-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (min-width: 1024px) {
            .kd-options-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        @media (min-width: 1280px) {
            .kd-options-grid {
                grid-template-columns: repeat(5, minmax(0, 1fr));
            }
        }

        .kd-opt-btn {
            display: flex;
            align-items: center;
            gap: 0.85rem;
            padding: 0.85rem 1rem;
            border-radius: 0.75rem;
            text-align: left;
            text-decoration: none;
            cursor: pointer;
            border: 1px solid rgba(0, 0, 0, 0.08);
            background: #f8fafc;
            transition: all 0.15s ease-in-out;
            font-family: inherit;
        }

        :is(.dark) .kd-opt-btn {
            background: #27272a;
            border-color: rgba(255, 255, 255, 0.08);
        }

        .kd-opt-btn:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.06);
        }

        .kd-opt-btn-primary:hover {
            background: #eff6ff;
            border-color: #93c5fd;
        }

        .kd-opt-btn-secondary:hover {
            background: #fefce8;
            border-color: #fde047;
        }

        .kd-opt-btn-link:hover {
            background: #f0fdf4;
            border-color: #86efac;
        }

        .kd-opt-btn-unicode:hover {
            background: #fdf2f8;
            border-color: #f472b6;
        }

        .kd-opt-btn-sharemarket:hover {
            background: #ecfdf5;
            border-color: #34d399;
        }

        .kd-opt-btn-database:hover {
            background: #ecfdf5;
            border-color: #10b981;
        }

        :is(.dark) .kd-opt-btn-database:hover {
            background: #064e3b;
            border-color: #10b981;
        }

        .kd-opt-btn-disabled {
            border-color: #fca5a5;
            background: #fff5f5;
        }

        :is(.dark) .kd-opt-btn-disabled {
            background: #2a1818;
            border-color: #7f1d1d;
        }

        .kd-opt-btn-disabled:hover {
            background: #fee2e2;
            border-color: #ef4444;
        }

        .kd-opt-icon {
            font-size: 1.4rem;
            flex-shrink: 0;
        }

        .kd-opt-text {
            display: flex;
            flex-direction: column;
            overflow: hidden;
        }

        .kd-opt-main {
            font-size: 0.82rem;
            font-weight: 700;
            color: #1e293b;
            line-height: 1.3;
        }

        :is(.dark) .kd-opt-main {
            color: #f1f5f9;
        }

        .kd-opt-sub {
            font-size: 0.7rem;
            color: #64748b;
            margin-top: 2px;
        }

        :is(.dark) .kd-opt-sub {
            color: #94a3b8;
        }
    </style>
</x-filament-widgets::widget>
