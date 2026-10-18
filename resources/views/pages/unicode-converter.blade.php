<x-layouts.app
    title="नेपाली युनिकोड कन्भर्टर | प्रीति र रोमनाइज्ड रूपान्तरण - {{ setting('site_name', 'कपिलवस्तु दर्पण') }}"
    description="नेपाली युनिकोड कन्भर्टर — रोमन नेपालीलाई युनिकोडमा र परम्परागत प्रीति फन्टलाई नेपाली युनिकोडमा सजिलै परिवर्तन गर्नुहोस्। नि:शुल्क, छिटो र सरल अनलाइन टुल।"
    keywords="नेपाली युनिकोड, प्रीति टु युनिकोड, रोमन नेपाली युनिकोड, preeti to unicode, nepali unicode converter, romanized nepali unicode, kapilvastudarpan"
>
    <!-- Breadcrumb -->
    <div class="bg-stone-100/80 border-b border-stone-200 py-3">
        <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8">
            <nav class="flex items-center gap-2 text-xs sm:text-sm text-stone-500 font-medium" aria-label="Breadcrumb">
                <a href="{{ route('home') }}" class="hover:text-[#c92137] transition flex items-center gap-1">
                    <svg class="h-3.5 w-3.5" fill="currentColor" viewBox="0 0 20 20"><path d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"/></svg>
                    <span>गृहपृष्ठ</span>
                </a>
                <span>›</span>
                <span class="text-stone-400">उपयोगी सामग्री</span>
                <span>›</span>
                <span class="text-[#c92137] font-bold">नेपाली युनिकोड कन्भर्टर</span>
            </nav>
        </div>
    </div>

    <!-- Main Container (Full Width) -->
    <div class="mx-auto max-w-[1280px] px-4 sm:px-6 lg:px-8 py-6 sm:py-8">
        <main class="w-full space-y-6" id="main-content">

                <!-- Header Title Banner -->
                <div class="bg-[#0955cf] text-white rounded-2xl p-6 sm:p-8 shadow-md relative overflow-hidden">
                    <div class="relative z-10 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
                        <div>
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-black bg-white/20 text-white tracking-wide uppercase mb-2">
                                <span>⚡</span> अनलाइन टुल (Smart Converter)
                            </span>
                            <h1 class="text-2xl sm:text-3xl font-black tracking-tight text-white">
                                नेपाली युनिकोड कन्भर्टर
                            </h1>
                            <p class="text-sm sm:text-base text-blue-100 font-medium mt-1">
                                रोमन नेपाली ➔ युनिकोड, प्रीति ➔ युनिकोड र युनिकोड ➔ प्रीति तत्काल रूपान्तरण गर्नुहोस्
                            </p>
                        </div>
                        <div class="hidden sm:flex items-center justify-center h-16 w-16 rounded-2xl bg-white/15 border border-white/20 text-3xl shadow-inner shrink-0">
                            ⌨️
                        </div>
                    </div>
                </div>

                <!-- Converter Interactive Component (Alpine.js) -->
                <div
                    x-data="{
                        mode: '{{ $mode }}',
                        inputText: '{{ addslashes($inputText) }}',
                        outputText: '{{ addslashes($convertedText) }}',
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
                            if (this.inputText && !this.outputText) {
                                this.convert();
                            }
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

                        handleInput() {
                            if (this.liveConvert) {
                                this.convert();
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
                            navigator.clipboard.writeText(this.outputText).then(() => {
                                this.copied = true;
                                setTimeout(() => this.copied = false, 2500);
                            });
                        },

                        downloadTxt() {
                            if (!this.outputText) return;
                            const element = document.createElement('a');
                            const file = new Blob([this.outputText], { type: 'text/plain;charset=utf-8' });
                            element.href = URL.createObjectURL(file);
                            element.download = 'nepali-unicode-' + Date.now() + '.txt';
                            document.body.appendChild(element);
                            element.click();
                            document.body.removeChild(element);
                        },

                        getWordCount(txt) {
                            if (!txt) return '०';
                            const words = txt.trim().split(/\s+/).filter(Boolean);
                            return NepaliUnicode.toNepaliDigits(words.length);
                        },

                        getCharCount(txt) {
                            if (!txt) return '०';
                            return NepaliUnicode.toNepaliDigits(txt.length);
                        }
                    }"
                    class="bg-white rounded-2xl border border-stone-200/90 shadow-md p-5 sm:p-7 space-y-6"
                >
                    <!-- Mode Switcher Tabs -->
                    <div class="border-b border-stone-200 pb-4">
                        <label class="block text-xs font-black uppercase tracking-wider text-stone-500 mb-2.5">
                            रूपान्तरणको माध्यम छान्नुहोस् (Select Conversion Mode):
                        </label>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-2">
                            <!-- Mode 1: Romanized to Unicode -->
                            <button
                                type="button"
                                @click="setMode('romanized')"
                                :class="mode === 'romanized' ? 'bg-[#0955cf] text-white shadow-sm font-black' : 'bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold'"
                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm sm:text-[15px] transition-all cursor-pointer"
                            >
                                <span>🔤</span>
                                <span>रोमन ➔ युनिकोड</span>
                            </button>

                            <!-- Mode 2: Preeti to Unicode -->
                            <button
                                type="button"
                                @click="setMode('preeti')"
                                :class="mode === 'preeti' ? 'bg-[#0955cf] text-white shadow-sm font-black' : 'bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold'"
                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm sm:text-[15px] transition-all cursor-pointer"
                            >
                                <span>🔄</span>
                                <span>प्रीति ➔ युनिकोड</span>
                            </button>

                            <!-- Mode 3: Unicode to Preeti -->
                            <button
                                type="button"
                                @click="setMode('unicode-to-preeti')"
                                :class="mode === 'unicode-to-preeti' ? 'bg-[#0955cf] text-white shadow-sm font-black' : 'bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold'"
                                class="flex items-center justify-center gap-2 px-4 py-3 rounded-xl text-sm sm:text-[15px] transition-all cursor-pointer"
                            >
                                <span>🔁</span>
                                <span>युनिकोड ➔ प्रीति</span>
                            </button>
                        </div>
                    </div>

                    <!-- Input Section -->
                    <div class="space-y-2">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <label for="unicode-input" class="text-sm font-extrabold text-stone-800 flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-blue-600"></span>
                                <span x-show="mode === 'romanized'">रोमन इनपुट (Type Roman English e.g. "namaste nepal"):</span>
                                <span x-show="mode === 'preeti'">प्रीति फन्ट इनपुट (Paste Preeti Text e.g. "g]kfn"):</span>
                                <span x-show="mode === 'unicode-to-preeti'">युनिकोड इनपुट (Paste Unicode Text e.g. "नेपाल"):</span>
                            </label>

                            <div class="flex items-center gap-3 text-xs">
                                <label class="inline-flex items-center gap-1.5 text-stone-600 cursor-pointer font-medium select-none">
                                    <input type="checkbox" x-model="liveConvert" class="rounded text-[#c92137] focus:ring-[#c92137]">
                                    <span>लाइभ रूपान्तरण (Instant Typing)</span>
                                </label>
                                <button type="button" @click="clearAll" class="text-stone-500 hover:text-red-600 font-bold transition">
                                    सबै खाली गर्नुहोस्
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <textarea
                                id="unicode-input"
                                x-model="inputText"
                                @input="handleInput"
                                :placeholder="mode === 'romanized' ? 'यहाँ रोमनमा टाइप गर्नुहोस् (जस्तै: mero desh nepal, namaste, kapilvastu darpan)...' : (mode === 'preeti' ? 'यहाँ प्रीति (Preeti/Kantipur) फन्टको टेक्स्ट पेस्ट गर्नुहोस् (जस्तै: g]kfn)...' : 'यहाँ नेपाली युनिकोड टेक्स्ट पेस्ट गर्नुहोस् (जस्तै: नेपाल)...')"
                                rows="6"
                                class="w-full rounded-xl border border-stone-300 focus:border-[#244b92] focus:ring-2 focus:ring-[#244b92]/20 p-4 text-stone-800 font-medium transition resize-y shadow-inner"
                                :style="'font-size: ' + fontSize + 'px;'"
                            ></textarea>
                        </div>
                    </div>

                    <!-- Middle Action Bar -->
                    <div class="flex flex-wrap items-center justify-between gap-3 pt-1">
                        <div class="flex items-center gap-2">
                            <button
                                type="button"
                                @click="convert"
                                class="inline-flex items-center gap-1.5 px-5 py-2.5 rounded-xl bg-[#c92137] hover:bg-[#a51a2c] text-white font-extrabold text-sm shadow-sm transition-all hover:scale-[1.02] cursor-pointer"
                            >
                                <span>⚡</span>
                                <span>कन्भर्ट गर्नुहोस्</span>
                            </button>

                            <button
                                type="button"
                                @click="swapText"
                                title="अदलाबदली गर्नुहोस्"
                                class="inline-flex items-center gap-1 px-3.5 py-2.5 rounded-xl bg-stone-100 hover:bg-stone-200 text-stone-700 font-bold text-sm transition cursor-pointer"
                            >
                                <span>⇄</span>
                                <span class="hidden sm:inline">अदलाबदली</span>
                            </button>
                        </div>

                        <!-- Font Size & Rules Trigger -->
                        <div class="flex items-center gap-2">
                            <span class="text-xs font-bold text-stone-500 hidden sm:inline">फन्ट:</span>
                            <div class="inline-flex items-center border border-stone-300 rounded-lg overflow-hidden bg-stone-50">
                                <button type="button" @click="if (fontSize > 13) fontSize -= 1" class="px-2.5 py-1 text-xs font-bold text-stone-700 hover:bg-stone-200" title="सानो बनाउनुहोस्">A-</button>
                                <span class="px-2 text-xs font-bold text-stone-600 border-x border-stone-300" x-text="fontSize + 'px'"></span>
                                <button type="button" @click="if (fontSize < 30) fontSize += 1" class="px-2.5 py-1 text-xs font-bold text-stone-700 hover:bg-stone-200" title="ठूलो बनाउनुहोस्">A+</button>
                            </div>

                            <button
                                type="button"
                                @click="showRules = !showRules"
                                class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg border border-stone-300 text-xs font-bold text-stone-700 hover:bg-stone-100 transition"
                            >
                                <span>📖</span>
                                <span>टाइप तालिका</span>
                            </button>
                        </div>
                    </div>

                    <!-- Output Section -->
                    <div class="space-y-2 pt-2 border-t border-stone-200">
                        <div class="flex items-center justify-between flex-wrap gap-2">
                            <label for="unicode-output" class="text-sm font-extrabold text-stone-800 flex items-center gap-1.5">
                                <span class="h-2 w-2 rounded-full bg-emerald-500"></span>
                                <span x-show="mode !== 'unicode-to-preeti'">युनिकोड नतिजा (Devanagari Unicode Output):</span>
                                <span x-show="mode === 'unicode-to-preeti'">प्रीति फन्ट नतिजा (Preeti Output):</span>
                            </label>

                            <!-- Copy & Download Toolbar -->
                            <div class="flex items-center gap-2">
                                <button
                                    type="button"
                                    @click="copyOutput"
                                    :class="copied ? 'bg-emerald-600 text-white' : 'bg-stone-100 hover:bg-stone-200 text-stone-800'"
                                    class="inline-flex items-center gap-1.5 px-3.5 py-1.5 rounded-lg text-xs font-black transition-all cursor-pointer shadow-2xs"
                                >
                                    <span x-show="!copied">📋 कपी गर्नुहोस्</span>
                                    <span x-show="copied">✓ कपी भयो!</span>
                                </button>

                                <button
                                    type="button"
                                    @click="downloadTxt"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 rounded-lg bg-stone-100 hover:bg-stone-200 text-stone-700 text-xs font-bold transition cursor-pointer"
                                    title="फाइल डाउनलोड गर्नुहोस्"
                                >
                                    <span>⬇</span>
                                    <span class="hidden sm:inline">डाउनलोड</span>
                                </button>
                            </div>
                        </div>

                        <div class="relative">
                            <textarea
                                id="unicode-output"
                                x-model="outputText"
                                readonly
                                rows="6"
                                placeholder="यहाँ रूपान्तरण भएको टेक्स्ट देखिनेछ..."
                                class="w-full rounded-xl border border-stone-300 bg-stone-50/80 p-4 text-stone-900 font-bold transition resize-y shadow-inner cursor-text"
                                :style="'font-size: ' + fontSize + 'px;'"
                            ></textarea>
                        </div>

                        <!-- Statistics Counter -->
                        <div class="flex items-center justify-between text-xs font-bold text-stone-500 px-1 pt-1">
                            <div class="flex items-center gap-4">
                                <span>शब्द: <strong class="text-stone-800 font-extrabold" x-text="getWordCount(outputText)">०</strong></span>
                                <span>अक्षर: <strong class="text-stone-800 font-extrabold" x-text="getCharCount(outputText)">०</strong></span>
                            </div>
                            <span class="text-emerald-700 font-bold" x-show="outputText">✓ रूपान्तरण तयार छ</span>
                        </div>
                    </div>

                    <!-- Keyboard Layout Guide & Typing Rules (Toggleable) -->
                    <div x-show="showRules" x-collapse x-cloak class="border-t border-stone-200 pt-5 mt-4 space-y-4">
                        <div class="flex items-center justify-between pb-2 border-b border-stone-100">
                            <h3 class="text-base font-extrabold text-stone-900 flex items-center gap-2">
                                <span>⌨️</span> रोमन नेपाली टाइप गर्ने तरिका (Romanized Typing Cheat Sheet)
                            </h3>
                            <button type="button" @click="showRules = false" class="text-xs font-bold text-stone-400 hover:text-stone-700">बन्द गर्नुहोस् ×</button>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-xs">
                            <!-- Column 1: Vowels -->
                            <div class="bg-stone-50 p-3.5 rounded-xl border border-stone-200/70 space-y-2">
                                <h4 class="font-extrabold text-stone-900 border-b border-stone-200 pb-1 text-xs">स्वर वर्ण (Vowels)</h4>
                                <ul class="space-y-1 text-stone-700 font-medium">
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">a</code> = अ</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">aa / A</code> = आ / ा</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">i</code> = इ / ि</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">ee / I</code> = ई / ी</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">u</code> = उ / ु</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">oo / U</code> = ऊ / ू</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">e</code> = ए / े</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">ai</code> = ऐ / ै</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">o</code> = ओ / ो</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">au</code> = औ / ौ</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-red-600 font-mono">am</code> = अं / ं</li>
                                </ul>
                            </div>

                            <!-- Column 2: Consonants -->
                            <div class="bg-stone-50 p-3.5 rounded-xl border border-stone-200/70 space-y-2">
                                <h4 class="font-extrabold text-stone-900 border-b border-stone-200 pb-1 text-xs">व्यञ्जन वर्ण (Consonants)</h4>
                                <ul class="space-y-1 text-stone-700 font-medium">
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">k / kh</code> = क / ख</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">g / gh / ng</code> = ग / घ / ङ</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">ch / chh</code> = च / छ</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">j / jh / yn</code> = ज / झ / ञ</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">T / Th / D / Dh / N</code> = ट / ठ / ड / ढ / ण</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">t / th / d / dh / n</code> = त / थ / द / ध / न</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">p / ph / b / bh / m</code> = प / फ / ब / भ / म</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">y / r / l / w / v</code> = य / र / ल / व</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-blue-600 font-mono">sh / Sh / s / h</code> = श / ष / स / ह</li>
                                </ul>
                            </div>

                            <!-- Column 3: Conjuncts & Examples -->
                            <div class="bg-stone-50 p-3.5 rounded-xl border border-stone-200/70 space-y-2">
                                <h4 class="font-extrabold text-stone-900 border-b border-stone-200 pb-1 text-xs">संयुक्त अक्षर र उदाहरण</h4>
                                <ul class="space-y-1 text-stone-700 font-medium">
                                    <li><code class="bg-white px-1 py-0.5 rounded text-purple-600 font-mono">ksh / x</code> = क्ष</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-purple-600 font-mono">tr</code> = त्र</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-purple-600 font-mono">gy / gny</code> = ज्ञ</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-purple-600 font-mono">shr</code> = श्र</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-stone-700 font-mono">namaste</code> ➔ नमस्ते</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-stone-700 font-mono">nepal</code> ➔ नेपाल</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-stone-700 font-mono">dhanyabad</code> ➔ धन्यवाद</li>
                                    <li><code class="bg-white px-1 py-0.5 rounded text-stone-700 font-mono">kapilvastu</code> ➔ कपिलवस्तु</li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Informational Guide & SEO Content -->
                <div class="bg-stone-50 rounded-2xl p-6 border border-stone-200/80 space-y-4">
                    <h2 class="text-lg font-black text-stone-900">
                        नेपाली युनिकोड कन्भर्टर किन प्रयोग गर्ने?
                    </h2>
                    <p class="text-sm leading-relaxed text-stone-700">
                        परम्परागत नेपाली फन्टहरू (जस्तै: प्रीति, कान्तिपुर, सगरमाथा आदि) विभिन्न कम्प्युटर वा मोबाइलमा फन्ट इन्स्टल नभएमा राम्रोसँग नदेखिने समस्या हुन्छ। तर <strong>नेपाली युनिकोड</strong> अन्तर्राष्ट्रिय स्तरको मानक भएकाले फेसबुक, वेबसाइट, वर्ड, मोबाइल तथा सबै डिजिटल उपकरणमा कुनै अतिरिक्त फन्ट डाउनलोड नगरी सीधै पढ्न र लेख्न सकिन्छ।
                    </p>
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 pt-2">
                        <div class="bg-white p-3.5 rounded-xl border border-stone-200 shadow-2xs">
                            <span class="text-xl">🚀</span>
                            <h3 class="font-extrabold text-xs text-stone-900 mt-1">तत्काल लाइभ टाइप</h3>
                            <p class="text-[11px] text-stone-600 mt-0.5">रोमनमा टाइप गर्दा गर्दै स्वतः नेपाली युनिकोडमा परिवर्तन हुन्छ।</p>
                        </div>
                        <div class="bg-white p-3.5 rounded-xl border border-stone-200 shadow-2xs">
                            <span class="text-xl">📑</span>
                            <h3 class="font-extrabold text-xs text-stone-900 mt-1">प्रीति फन्ट रूपान्तरण</h3>
                            <p class="text-[11px] text-stone-600 mt-0.5">पुराना डकुमेन्ट तथा समाचारका सामग्री युनिकोडमा सजिलै बदल्नुहोस्।</p>
                        </div>
                        <div class="bg-white p-3.5 rounded-xl border border-stone-200 shadow-2xs">
                            <span class="text-xl">🔒</span>
                            <h3 class="font-extrabold text-xs text-stone-900 mt-1">शतप्रतिशत सुरक्षित</h3>
                            <p class="text-[11px] text-stone-600 mt-0.5">तपाईंको डेटा आफ्नै ब्राउजरमा प्रोसेस हुन्छ, कहीँ सेभ हुँदैन।</p>
                        </div>
                    </div>
                </div>

        </main>
    </div>
</x-layouts.app>
