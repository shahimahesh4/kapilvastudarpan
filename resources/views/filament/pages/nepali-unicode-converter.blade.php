<x-filament-panels::page>
    <div
        x-data="{
            mode: 'romanized',
            inputText: '',
            outputText: '',
            liveConvert: true,
            fontSize: 16,
            copied: false,
            showRules: false,

            init() {
                this.$watch('inputText', () => {
                    if (this.liveConvert) {
                        this.convert();
                    }
                });
            },

            convert() {
                if (!this.inputText) {
                    this.outputText = '';
                    return;
                }
                const engine = window.NepaliUnicode || (typeof NepaliUnicode !== 'undefined' ? NepaliUnicode : null);
                if (!engine) return;

                if (this.mode === 'preeti') {
                    this.outputText = engine.preetiToUnicode(this.inputText);
                } else if (this.mode === 'unicode-to-preeti') {
                    this.outputText = engine.unicodeToPreeti(this.inputText);
                } else {
                    this.outputText = engine.romanizedToUnicode(this.inputText);
                }
            },

            setMode(newMode) {
                this.mode = newMode;
                this.convert();
            },

            swapText() {
                const temp = this.inputText;
                this.inputText = this.outputText;
                this.outputText = temp;
                if (this.mode === 'preeti') {
                    this.mode = 'unicode-to-preeti';
                } else if (this.mode === 'unicode-to-preeti') {
                    this.mode = 'preeti';
                }
                this.convert();
            },

            clearAll() {
                this.inputText = '';
                this.outputText = '';
            },

            copyOutput() {
                if (!this.outputText) return;
                if (navigator.clipboard) {
                    navigator.clipboard.writeText(this.outputText).then(() => {
                        this.copied = true;
                        setTimeout(() => this.copied = false, 2500);
                    });
                }
            },

            downloadTxt() {
                if (!this.outputText) return;
                const element = document.createElement('a');
                const file = new Blob([this.outputText], { type: 'text/plain;charset=utf-8' });
                element.href = URL.createObjectURL(file);
                element.download = 'unicode-text-' + Date.now() + '.txt';
                document.body.appendChild(element);
                element.click();
                document.body.removeChild(element);
            },

            getWordCount(txt) {
                if (!txt) return '०';
                const words = txt.trim().split(/\s+/).filter(Boolean);
                const engine = window.NepaliUnicode || (typeof NepaliUnicode !== 'undefined' ? NepaliUnicode : null);
                return engine ? engine.toNepaliDigits(words.length) : words.length;
            },

            getCharCount(txt) {
                if (!txt) return '०';
                const engine = window.NepaliUnicode || (typeof NepaliUnicode !== 'undefined' ? NepaliUnicode : null);
                return engine ? engine.toNepaliDigits(txt.length) : txt.length;
            }
        }"
        class="kd-uni-wrapper"
    >
        <!-- Hero Header Banner -->
        <div class="kd-uni-hero">
            <div class="kd-uni-hero-content">
                <div class="kd-uni-badge">
                    <span>📰</span> सम्पादकीय उपकरण (Editorial Tool)
                </div>
                <h2 class="kd-uni-hero-title">
                    नेपाली युनिकोड तथा प्रीति कन्भर्टर
                </h2>
                <p class="kd-uni-hero-sub">
                    समाचार, लेख तथा हेडलाइनका लागि रोमन नेपाली वा परम्परागत प्रीति फन्टलाई तत्काल युनिकोडमा बदल्नुहोस्।
                </p>
            </div>

            <div class="kd-uni-hero-actions">
                <a href="{{ url('/stnapanel/articles/create') }}" class="kd-uni-btn kd-uni-btn-light">
                    <span>✍️</span>
                    <span>नयाँ समाचार लेख्नुहोस्</span>
                </a>
                <a href="{{ route('unicode.converter') }}" target="_blank" rel="noopener noreferrer" class="kd-uni-btn kd-uni-btn-ghost">
                    <span>🌐</span>
                    <span>पब्लिक टुल ↗</span>
                </a>
            </div>
        </div>

        <!-- Main Workstation Card -->
        <div class="kd-uni-card">
            <!-- Top Controls Toolbar -->
            <div class="kd-uni-toolbar">
                <!-- Mode Switcher Tabs -->
                <div class="kd-uni-modes">
                    <button
                        type="button"
                        @click="setMode('romanized')"
                        :class="mode === 'romanized' ? 'kd-mode-active-red' : 'kd-mode-inactive'"
                        class="kd-mode-btn"
                    >
                        <span>🔤</span>
                        <span>रोमन ➔ युनिकोड</span>
                    </button>
                    <button
                        type="button"
                        @click="setMode('preeti')"
                        :class="mode === 'preeti' ? 'kd-mode-active-blue' : 'kd-mode-inactive'"
                        class="kd-mode-btn"
                    >
                        <span>🔄</span>
                        <span>प्रीति ➔ युनिकोड</span>
                    </button>
                    <button
                        type="button"
                        @click="setMode('unicode-to-preeti')"
                        :class="mode === 'unicode-to-preeti' ? 'kd-mode-active-dark' : 'kd-mode-inactive'"
                        class="kd-mode-btn"
                    >
                        <span>🔁</span>
                        <span>युनिकोड ➔ प्रीति</span>
                    </button>
                </div>

                <!-- Live Switch & Clear -->
                <div class="kd-uni-tools">
                    <label class="kd-uni-toggle">
                        <input type="checkbox" x-model="liveConvert" class="kd-toggle-input">
                        <span>⚡ लाइभ रूपान्तरण (Live Typing)</span>
                    </label>
                    <button type="button" @click="clearAll" class="kd-uni-clear-btn" title="सबै खाली गर्नुहोस्">
                        🗑️ सफा गर्नुहोस्
                    </button>
                </div>
            </div>

            <!-- Two-Column Editor Layout (Side-by-Side on Desktop, Stacked on Mobile) -->
            <div class="kd-uni-editors">
                <!-- Left: Input Area -->
                <div class="kd-uni-editor-col">
                    <div class="kd-uni-editor-head">
                        <label class="kd-uni-label">
                            <span class="kd-dot kd-dot-blue"></span>
                            <span x-show="mode === 'romanized'">रोमन इनपुट (Type in English letters):</span>
                            <span x-show="mode === 'preeti'">प्रीति फन्ट इनपुट (Paste Preeti text):</span>
                            <span x-show="mode === 'unicode-to-preeti'">युनिकोड इनपुट (Paste Unicode text):</span>
                        </label>
                        <span class="kd-uni-hint" x-show="mode === 'romanized'">जस्तै: namaste, nepal</span>
                        <span class="kd-uni-hint" x-show="mode === 'preeti'">जस्तै: g]kfn</span>
                    </div>

                    <textarea
                        x-model="inputText"
                        :placeholder="mode === 'romanized' ? 'यहाँ रोमनमा टाइप गर्नुहोस् (जस्तै: nepal, namaste, kapilvastu darpan, kaam, bhaat, tapai)...' : (mode === 'preeti' ? 'यहाँ परम्परागत प्रीति फन्टको सामग्री पेस्ट गर्नुहोस् (जस्तै: g]kfn, ljBfyL{)...' : 'यहाँ युनिकोड सामग्री पेस्ट गर्नुहोस् (जस्तै: नेपाल, विद्यार्थी)...')"
                        class="kd-uni-textarea kd-uni-input"
                        :style="'font-size: ' + fontSize + 'px;'"
                    ></textarea>
                </div>

                <!-- Right: Output Area -->
                <div class="kd-uni-editor-col">
                    <div class="kd-uni-editor-head">
                        <label class="kd-uni-label">
                            <span class="kd-dot kd-dot-green"></span>
                            <span x-show="mode !== 'unicode-to-preeti'">युनिकोड नतिजा (Devanagari Unicode Output):</span>
                            <span x-show="mode === 'unicode-to-preeti'">प्रीति नतिजा (Preeti Output):</span>
                        </label>

                        <div class="kd-uni-actions">
                            <button
                                type="button"
                                @click="copyOutput"
                                :class="copied ? 'kd-btn-copied' : 'kd-btn-copy'"
                                class="kd-uni-mini-btn"
                                title="क्लिपबोर्डमा कपी गर्नुहोस्"
                            >
                                <span x-show="!copied">📋 कपी गर्नुहोस्</span>
                                <span x-show="copied">✓ कपी भयो!</span>
                            </button>

                            <button
                                type="button"
                                @click="downloadTxt"
                                class="kd-uni-mini-btn kd-btn-ghost"
                                title="टेक्स्ट फाइल डाउनलोड गर्नुहोस्"
                            >
                                <span>⬇ डाउनलोड</span>
                            </button>
                        </div>
                    </div>

                    <textarea
                        x-model="outputText"
                        readonly
                        placeholder="यहाँ रूपान्तरित अक्षरहरू देखिनेछन्..."
                        class="kd-uni-textarea kd-uni-output"
                        :style="'font-size: ' + fontSize + 'px;'"
                    ></textarea>
                </div>
            </div>

            <!-- Bottom Action & Stats Bar -->
            <div class="kd-uni-footer-bar">
                <div class="kd-uni-main-actions">
                    <button
                        type="button"
                        @click="convert"
                        class="kd-uni-action-btn kd-action-primary"
                    >
                        <span>⚡</span>
                        <span>कन्भर्ट गर्नुहोस्</span>
                    </button>

                    <button
                        type="button"
                        @click="swapText"
                        class="kd-uni-action-btn kd-action-secondary"
                        title="इनपुट र आउटपुट साट्नुहोस्"
                    >
                        <span>⇄</span>
                        <span>अदलाबदली</span>
                    </button>
                </div>

                <!-- Stats & Font Sizer -->
                <div class="kd-uni-meta">
                    <div class="kd-uni-stats">
                        <span>शब्द: <strong x-text="getWordCount(outputText)">०</strong></span>
                        <span class="kd-sep">•</span>
                        <span>अक्षर: <strong x-text="getCharCount(outputText)">०</strong></span>
                    </div>

                    <!-- Font Resizer -->
                    <div class="kd-uni-resizer" title="फन्ट साइज परिवर्तन गर्नुहोस्">
                        <span class="kd-resizer-label">फन्ट:</span>
                        <div class="kd-resizer-group">
                            <button type="button" @click="if (fontSize > 13) fontSize -= 1" class="kd-resizer-btn">A-</button>
                            <span class="kd-resizer-val" x-text="fontSize + 'px'"></span>
                            <button type="button" @click="if (fontSize < 30) fontSize += 1" class="kd-resizer-btn">A+</button>
                        </div>
                    </div>

                    <button
                        type="button"
                        @click="showRules = !showRules"
                        class="kd-uni-guide-trigger"
                    >
                        <span>📖 टाइपिङ तालिका</span>
                        <span x-text="showRules ? '▲' : '▼'"></span>
                    </button>
                </div>
            </div>

            <!-- Collapsible Cheat Sheet Guide -->
            <div x-show="showRules" x-collapse x-cloak class="kd-uni-guide">
                <div class="kd-guide-header">
                    <h4 class="kd-guide-title">
                        <span>⌨️</span> रोमन नेपाली टाइपिङ नियम तालिका (Romanized Nepali Cheat Sheet)
                    </h4>
                    <span class="kd-guide-hint">कुनै पनि शब्द सहजै टाइप गर्नुहोस्</span>
                </div>

                <div class="kd-guide-grid">
                    <!-- Col 1: Vowels -->
                    <div class="kd-guide-card">
                        <h5 class="kd-card-title">स्वर वर्ण (Vowels)</h5>
                        <ul class="kd-guide-list">
                            <li><code>a</code> = अ</li>
                            <li><code>aa / A</code> = आ / ा</li>
                            <li><code>i</code> = इ / ि</li>
                            <li><code>ee / I</code> = ई / ी</li>
                            <li><code>u</code> = उ / ु</li>
                            <li><code>oo / U</code> = ऊ / ू</li>
                            <li><code>e</code> = ए / े</li>
                            <li><code>ai</code> = ऐ / ै</li>
                            <li><code>o</code> = ओ / ो</li>
                            <li><code>au</code> = औ / ौ</li>
                            <li><code>am</code> = अं / ं</li>
                        </ul>
                    </div>

                    <!-- Col 2: Consonants -->
                    <div class="kd-guide-card">
                        <h5 class="kd-card-title">व्यञ्जन वर्ण (Consonants)</h5>
                        <ul class="kd-guide-list">
                            <li><code>k / kh</code> = क / ख</li>
                            <li><code>g / gh / ng</code> = ग / घ / ङ</li>
                            <li><code>ch / chh</code> = च / छ</li>
                            <li><code>j / jh</code> = ज / झ</li>
                            <li><code>t / th</code> = त / थ</li>
                            <li><code>d / dh / n</code> = द / ध / न</li>
                            <li><code>p / ph / f</code> = प / फ</li>
                            <li><code>b / bh / m</code> = ब / भ / म</li>
                            <li><code>y / r / l / w</code> = य / र / ल / व</li>
                            <li><code>s / sh / Sh</code> = स / श / ष</li>
                            <li><code>h</code> = ह</li>
                        </ul>
                    </div>

                    <!-- Col 3: Conjuncts & Examples -->
                    <div class="kd-guide-card">
                        <h5 class="kd-card-title">संयुक्त अक्षर र उदाहरणहरू</h5>
                        <ul class="kd-guide-list">
                            <li><code>ksh / x</code> = क्ष</li>
                            <li><code>tr</code> = त्र</li>
                            <li><code>gy / gny</code> = ज्ञ</li>
                            <li><code>shr</code> = श्र</li>
                            <li><code>k_</code> = क् (हलन्त अक्षर)</li>
                            <li><code>namaste</code> ➔ <strong>नमस्ते</strong></li>
                            <li><code>nepal</code> ➔ <strong>नेपाल</strong></li>
                            <li><code>kaam</code> ➔ <strong>काम</strong></li>
                            <li><code>bhaat</code> ➔ <strong>भात</strong></li>
                            <li><code>tapai</code> ➔ <strong>तपाईं</strong></li>
                            <li><code>kitab</code> ➔ <strong>किताब</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Inject Converter Script -->
    <script src="{{ asset('js/nepali-unicode-converter.js') }}"></script>

    <!-- Scoped Executive Styles for Filament -->
    <style>
        .kd-uni-wrapper {
            display: flex;
            flex-direction: column;
            gap: 1.5rem;
            width: 100%;
        }

        /* Hero Banner */
        .kd-uni-hero {
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
            padding: 1.75rem 2rem;
            border-radius: 1rem;
            background: linear-gradient(135deg, #1e3a8a 0%, #172554 45%, #c92137 100%);
            color: #ffffff;
            box-shadow: 0 10px 25px -5px rgba(30, 58, 138, 0.35);
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        @media (min-width: 768px) {
            .kd-uni-hero {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .kd-uni-badge {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.25rem 0.75rem;
            border-radius: 9999px;
            background: rgba(255, 255, 255, 0.18);
            color: #ffffff;
            font-size: 0.75rem;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            margin-bottom: 0.6rem;
            backdrop-filter: blur(4px);
        }

        .kd-uni-hero-title {
            font-size: 1.5rem;
            font-weight: 900;
            line-height: 1.2;
            color: #ffffff;
            margin: 0;
        }

        @media (min-width: 640px) {
            .kd-uni-hero-title {
                font-size: 1.75rem;
            }
        }

        .kd-uni-hero-sub {
            font-size: 0.875rem;
            font-weight: 500;
            color: #dbeafe;
            margin-top: 0.35rem;
            margin-bottom: 0;
            max-width: 42rem;
        }

        .kd-uni-hero-actions {
            display: flex;
            align-items: center;
            gap: 0.75rem;
            flex-wrap: wrap;
            shrink: 0;
        }

        .kd-uni-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.6rem 1.15rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 800;
            text-decoration: none;
            transition: all 0.2s ease;
            box-shadow: 0 2px 6px rgba(0, 0, 0, 0.12);
        }

        .kd-uni-btn-light {
            background: #ffffff;
            color: #1e3a8a;
        }

        .kd-uni-btn-light:hover {
            background: #f1f5f9;
            transform: translateY(-1px);
        }

        .kd-uni-btn-ghost {
            background: rgba(255, 255, 255, 0.15);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.3);
        }

        .kd-uni-btn-ghost:hover {
            background: rgba(255, 255, 255, 0.25);
            color: #ffffff;
        }

        /* Workstation Card */
        .kd-uni-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 1rem;
            padding: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
            display: flex;
            flex-direction: column;
            gap: 1.25rem;
        }

        :is(.dark) .kd-uni-card {
            background: #18181b;
            border-color: #27272a;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.2);
        }

        /* Top Toolbar */
        .kd-uni-toolbar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-bottom: 1.25rem;
            border-bottom: 1px solid #f1f5f9;
        }

        :is(.dark) .kd-uni-toolbar {
            border-color: #27272a;
        }

        @media (min-width: 768px) {
            .kd-uni-toolbar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .kd-uni-modes {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .kd-mode-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
            padding: 0.55rem 1.1rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .kd-mode-inactive {
            background: #f1f5f9;
            color: #475569;
            font-weight: 700;
        }

        .kd-mode-inactive:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        :is(.dark) .kd-mode-inactive {
            background: #27272a;
            color: #cbd5e1;
        }

        :is(.dark) .kd-mode-inactive:hover {
            background: #3f3f46;
            color: #ffffff;
        }

        .kd-mode-active-red {
            background: #c92137;
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(201, 33, 55, 0.35);
        }

        .kd-mode-active-blue {
            background: #1e3a8a;
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(30, 58, 138, 0.35);
        }

        .kd-mode-active-dark {
            background: #0f172a;
            color: #ffffff;
            font-weight: 800;
            box-shadow: 0 4px 10px rgba(15, 23, 42, 0.35);
        }

        :is(.dark) .kd-mode-active-dark {
            background: #3b82f6;
            color: #ffffff;
        }

        .kd-uni-tools {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            font-size: 0.8125rem;
        }

        .kd-uni-toggle {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            color: #475569;
            font-weight: 700;
            cursor: pointer;
            user-select: none;
        }

        :is(.dark) .kd-uni-toggle {
            color: #94a3b8;
        }

        .kd-toggle-input {
            width: 1rem;
            height: 1rem;
            accent-color: #c92137;
            cursor: pointer;
        }

        .kd-uni-clear-btn {
            background: none;
            border: none;
            color: #64748b;
            font-weight: 700;
            font-size: 0.8125rem;
            cursor: pointer;
            transition: color 0.15s ease;
        }

        .kd-uni-clear-btn:hover {
            color: #dc2626;
        }

        /* Editors Grid */
        .kd-uni-editors {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1.25rem;
        }

        @media (min-width: 1024px) {
            .kd-uni-editors {
                grid-template-columns: 1fr 1fr;
            }
        }

        .kd-uni-editor-col {
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .kd-uni-editor-head {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            min-height: 2rem;
        }

        .kd-uni-label {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            font-size: 0.8125rem;
            font-weight: 800;
            color: #1e293b;
        }

        :is(.dark) .kd-uni-label {
            color: #f1f5f9;
        }

        .kd-dot {
            width: 8px;
            height: 8px;
            border-radius: 9999px;
            display: inline-block;
        }

        .kd-dot-blue {
            background: #2563eb;
        }

        .kd-dot-green {
            background: #10b981;
        }

        .kd-uni-hint {
            font-size: 0.75rem;
            color: #94a3b8;
            font-weight: 600;
        }

        .kd-uni-actions {
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .kd-uni-mini-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.35rem;
            padding: 0.3rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            border: 1px solid transparent;
            transition: all 0.15s ease;
        }

        .kd-btn-copy {
            background: #c92137;
            color: #ffffff;
        }

        .kd-btn-copy:hover {
            background: #a51a2c;
        }

        .kd-btn-copied {
            background: #10b981;
            color: #ffffff;
        }

        .kd-btn-ghost {
            background: #f1f5f9;
            color: #475569;
            border-color: #e2e8f0;
        }

        .kd-btn-ghost:hover {
            background: #e2e8f0;
            color: #1e293b;
        }

        :is(.dark) .kd-btn-ghost {
            background: #27272a;
            color: #cbd5e1;
            border-color: #3f3f46;
        }

        .kd-uni-textarea {
            width: 100%;
            min-height: 280px;
            padding: 1rem 1.15rem;
            border-radius: 0.85rem;
            line-height: 1.6;
            outline: none;
            resize: vertical;
            transition: all 0.2s ease;
            box-sizing: border-box;
            font-family: inherit;
        }

        .kd-uni-input {
            background: #ffffff;
            border: 1.5px solid #cbd5e1;
            color: #0f172a;
            font-weight: 500;
        }

        .kd-uni-input:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.15);
        }

        :is(.dark) .kd-uni-input {
            background: #27272a;
            border-color: #3f3f46;
            color: #f8fafc;
        }

        :is(.dark) .kd-uni-input:focus {
            border-color: #60a5fa;
            box-shadow: 0 0 0 3px rgba(96, 165, 250, 0.2);
        }

        .kd-uni-output {
            background: #f8fafc;
            border: 1.5px solid #e2e8f0;
            color: #0f172a;
            font-weight: 700;
            cursor: text;
        }

        :is(.dark) .kd-uni-output {
            background: #202023;
            border-color: #27272a;
            color: #f1f5f9;
        }

        /* Bottom Footer Bar */
        .kd-uni-footer-bar {
            display: flex;
            flex-direction: column;
            gap: 1rem;
            padding-top: 1rem;
            border-top: 1px solid #f1f5f9;
        }

        :is(.dark) .kd-uni-footer-bar {
            border-color: #27272a;
        }

        @media (min-width: 768px) {
            .kd-uni-footer-bar {
                flex-direction: row;
                align-items: center;
                justify-content: space-between;
            }
        }

        .kd-uni-main-actions {
            display: flex;
            align-items: center;
            gap: 0.6rem;
            flex-wrap: wrap;
        }

        .kd-uni-action-btn {
            display: inline-flex;
            align-items: center;
            gap: 0.45rem;
            padding: 0.65rem 1.35rem;
            border-radius: 0.75rem;
            font-size: 0.8125rem;
            font-weight: 800;
            cursor: pointer;
            border: none;
            transition: all 0.2s ease;
        }

        .kd-action-primary {
            background: #c92137;
            color: #ffffff;
            box-shadow: 0 3px 8px rgba(201, 33, 55, 0.3);
        }

        .kd-action-primary:hover {
            background: #a51a2c;
            transform: translateY(-1px);
        }

        .kd-action-secondary {
            background: #f1f5f9;
            color: #334155;
            border: 1px solid #cbd5e1;
        }

        .kd-action-secondary:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        :is(.dark) .kd-action-secondary {
            background: #27272a;
            color: #cbd5e1;
            border-color: #3f3f46;
        }

        :is(.dark) .kd-action-secondary:hover {
            background: #3f3f46;
            color: #ffffff;
        }

        .kd-uni-meta {
            display: flex;
            align-items: center;
            gap: 1.25rem;
            flex-wrap: wrap;
            font-size: 0.75rem;
        }

        .kd-uni-stats {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: #64748b;
            font-weight: 700;
        }

        .kd-uni-stats strong {
            color: #0f172a;
        }

        :is(.dark) .kd-uni-stats {
            color: #94a3b8;
        }

        :is(.dark) .kd-uni-stats strong {
            color: #f1f5f9;
        }

        .kd-sep {
            color: #cbd5e1;
        }

        .kd-uni-resizer {
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        .kd-resizer-label {
            color: #64748b;
            font-weight: 700;
        }

        :is(.dark) .kd-resizer-label {
            color: #94a3b8;
        }

        .kd-resizer-group {
            display: inline-flex;
            align-items: center;
            border: 1px solid #cbd5e1;
            border-radius: 0.5rem;
            overflow: hidden;
            background: #f8fafc;
        }

        :is(.dark) .kd-resizer-group {
            border-color: #3f3f46;
            background: #27272a;
        }

        .kd-resizer-btn {
            background: none;
            border: none;
            padding: 0.2rem 0.55rem;
            font-size: 0.75rem;
            font-weight: 800;
            color: #475569;
            cursor: pointer;
        }

        .kd-resizer-btn:hover {
            background: #e2e8f0;
            color: #0f172a;
        }

        :is(.dark) .kd-resizer-btn {
            color: #cbd5e1;
        }

        :is(.dark) .kd-resizer-btn:hover {
            background: #3f3f46;
            color: #ffffff;
        }

        .kd-resizer-val {
            padding: 0.2rem 0.45rem;
            font-size: 0.7rem;
            font-weight: 800;
            font-family: monospace;
            border-left: 1px solid #cbd5e1;
            border-right: 1px solid #cbd5e1;
            color: #334155;
        }

        :is(.dark) .kd-resizer-val {
            border-color: #3f3f46;
            color: #e2e8f0;
        }

        .kd-uni-guide-trigger {
            background: none;
            border: none;
            color: #2563eb;
            font-size: 0.75rem;
            font-weight: 800;
            cursor: pointer;
            display: inline-flex;
            align-items: center;
            gap: 0.3rem;
            text-decoration: underline;
        }

        :is(.dark) .kd-uni-guide-trigger {
            color: #60a5fa;
        }

        /* Cheat Sheet Guide */
        .kd-uni-guide {
            margin-top: 1rem;
            padding: 1.25rem;
            border-radius: 0.85rem;
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            display: flex;
            flex-direction: column;
            gap: 1rem;
        }

        :is(.dark) .kd-uni-guide {
            background: #202023;
            border-color: #27272a;
        }

        .kd-guide-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 0.5rem;
            border-bottom: 1px solid #e2e8f0;
            padding-bottom: 0.6rem;
        }

        :is(.dark) .kd-guide-header {
            border-color: #2e2e33;
        }

        .kd-guide-title {
            margin: 0;
            font-size: 0.875rem;
            font-weight: 800;
            color: #0f172a;
            display: inline-flex;
            align-items: center;
            gap: 0.4rem;
        }

        :is(.dark) .kd-guide-title {
            color: #f1f5f9;
        }

        .kd-guide-hint {
            font-size: 0.72rem;
            color: #64748b;
            font-weight: 600;
        }

        :is(.dark) .kd-guide-hint {
            color: #94a3b8;
        }

        .kd-guide-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
        }

        @media (min-width: 768px) {
            .kd-guide-grid {
                grid-template-columns: repeat(3, minmax(0, 1fr));
            }
        }

        .kd-guide-card {
            background: #ffffff;
            border: 1px solid #e2e8f0;
            border-radius: 0.75rem;
            padding: 0.85rem;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        :is(.dark) .kd-guide-card {
            background: #18181b;
            border-color: #2e2e33;
        }

        .kd-card-title {
            margin: 0;
            font-size: 0.75rem;
            font-weight: 800;
            color: #0f172a;
            border-bottom: 1px solid #f1f5f9;
            padding-bottom: 0.35rem;
        }

        :is(.dark) .kd-card-title {
            color: #f1f5f9;
            border-color: #27272a;
        }

        .kd-guide-list {
            list-style: none;
            padding: 0;
            margin: 0;
            display: flex;
            flex-direction: column;
            gap: 0.3rem;
            font-size: 0.75rem;
            color: #334155;
            font-weight: 500;
        }

        :is(.dark) .kd-guide-list {
            color: #cbd5e1;
        }

        .kd-guide-list code {
            background: #f1f5f9;
            padding: 0.1rem 0.35rem;
            border-radius: 0.3rem;
            color: #c92137;
            font-family: monospace;
            font-weight: 700;
            font-size: 0.72rem;
        }

        :is(.dark) .kd-guide-list code {
            background: #27272a;
            color: #f87171;
        }
    </style>
</x-filament-panels::page>
