@php
    $enabled = setting_bool('push_notifications_enabled', true);
@endphp

@if($enabled)
    <!-- Left Bottom Notification Subscriber Widget & Popup Prompt -->
    <div
        id="kd-notification-widget"
        x-data="{
            isOpen: false,
            showAutoPrompt: false,
            permission: (typeof window !== 'undefined' && 'Notification' in window) ? Notification.permission : 'unsupported',
            isSubscribed: false,
            isProcessing: false,
            logoUrl: '{{ asset('images/kapilvastu-darpan-logo.png') }}',
            siteName: '{{ addslashes(setting('site_name', 'कपिलवस्तु दर्पण')) }}',

            init() {
                if (!('Notification' in window)) {
                    this.permission = 'unsupported';
                    return;
                }

                this.permission = Notification.permission;
                const stored = localStorage.getItem('kd_notification_subscribed');
                this.isSubscribed = (this.permission === 'granted' && stored === 'true');
                const dismissedUntil = localStorage.getItem('kd_notify_prompt_dismissed_until');
                const now = Date.now();

                // If permission is default and not dismissed recently, auto-display popup card after 2.5 seconds
                if (this.permission === 'default' && (!dismissedUntil || now > parseInt(dismissedUntil, 10))) {
                    setTimeout(() => {
                        if (this.permission === 'default' && !this.isOpen) {
                            this.showAutoPrompt = true;
                        }
                    }, 2500);
                }
            },

            dismissAutoPrompt() {
                this.showAutoPrompt = false;
                // Don't prompt again for 3 days
                localStorage.setItem('kd_notify_prompt_dismissed_until', (Date.now() + 3 * 24 * 60 * 60 * 1000).toString());
            },

            toggleCard() {
                this.showAutoPrompt = false;
                this.isOpen = !this.isOpen;
            },

            async requestSubscription() {
                if (!('Notification' in window)) {
                    if (window.Swal) {
                        Swal.fire({
                            icon: 'info',
                            title: 'ब्राउजर असमर्थित',
                            text: 'तपाईंको ब्राउजरले वेब सूचना (Web Notification) समर्थन गर्दैन।',
                            confirmButtonColor: '#0955cf'
                        });
                    } else {
                        alert('तपाईंको ब्राउजरले वेब सूचना समर्थन गर्दैन।');
                    }
                    return;
                }

                this.isProcessing = true;

                try {
                    const result = await Notification.requestPermission();
                    this.permission = result;

                    if (result === 'granted') {
                        this.isSubscribed = true;
                        this.showAutoPrompt = false;
                        this.isOpen = false;
                        localStorage.setItem('kd_notification_subscribed', 'true');
                        localStorage.removeItem('kd_notify_prompt_dismissed_until');

                        // Immediate native browser push notification
                        try {
                            const n = new Notification(this.siteName + ' (डिजिटल न्यूज)', {
                                body: 'धन्यवाद! तपाईंले ताजा, ब्रेकिङ तथा विशेष समाचारहरूको सूचना सफलतापूर्वक सक्रिय गर्नुभयो।',
                                icon: this.logoUrl,
                                badge: this.logoUrl,
                                tag: 'kd-welcome-notification'
                            });
                            n.onclick = function() {
                                window.focus();
                                n.close();
                            };
                        } catch (e) {
                            console.log('Native notification error:', e);
                        }

                        // Register subscriber in database via API
                        fetch('{{ route('api.notifications.subscribe') }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                endpoint: null
                            })
                        }).catch(() => {});

                        // In-app success confirmation toast
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'success',
                                title: 'सूचना सदस्यता सक्रिय भयो!',
                                text: 'अब तपाईंले कपिलवस्तु दर्पणका सबै ताजा समाचारहरूको सूचना सिधै आफ्नो स्क्रिनमा प्राप्त गर्नुहुनेछ।',
                                confirmButtonColor: '#c92137'
                            });
                        } else {
                            alert('सूचना सदस्यता सफलतापूर्वक सक्रिय भयो!');
                        }
                    } else if (result === 'denied') {
                        this.showAutoPrompt = false;
                        if (window.Swal) {
                            Swal.fire({
                                icon: 'warning',
                                title: 'सूचना ब्लक गरिएको छ',
                                html: 'तपाईंको ब्राउजरले सूचना ब्लक गरेको छ। सूचना पाउन ब्राउजरको URL बारमा रहेको <strong>🔒 (Site Settings)</strong> मा क्लिक गरी <strong>Notifications</strong> लाई <strong>Allow</strong> गर्नुहोस्।',
                                confirmButtonColor: '#0955cf'
                            });
                        } else {
                            alert('सूचना ब्लक गरिएको छ। कृपया ब्राउजर सेटिङमा गई Notification Allow गर्नुहोस्।');
                        }
                    }
                } catch (err) {
                    console.error('Notification subscription error:', err);
                } finally {
                    this.isProcessing = false;
                }
            },

            async sendTestNotification() {
                if (this.permission !== 'granted') {
                    this.requestSubscription();
                    return;
                }

                try {
                    const res = await fetch('{{ route('api.notifications.test-payload') }}');
                    const data = await res.json();

                    const n = new Notification(data.title || this.siteName, {
                        body: data.body,
                        icon: data.icon || this.logoUrl,
                        tag: data.tag || ('test-' + Date.now())
                    });

                    n.onclick = function() {
                        window.focus();
                        if (data.url) {
                            window.location.href = data.url;
                        }
                        n.close();
                    };

                    if (window.Swal) {
                        Swal.fire({
                            toast: true,
                            position: 'top-end',
                            icon: 'success',
                            title: 'ब्राउजर सूचना पठाइयो!',
                            text: 'आफ्नो स्क्रिन वा टास्कबारमा परीक्षण सूचना हेर्नुहोस्।',
                            showConfirmButton: false,
                            timer: 3000,
                            timerProgressBar: true
                        });
                    }
                } catch (e) {
                    console.error(e);
                }
            },

            async unsubscribe() {
                this.isSubscribed = false;
                localStorage.removeItem('kd_notification_subscribed');

                fetch('{{ route('api.notifications.unsubscribe') }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        'Accept': 'application/json'
                    }
                }).catch(() => {});

                this.isOpen = false;
                if (window.Swal) {
                    Swal.fire({
                        icon: 'info',
                        title: 'सूचना बन्द गरियो',
                        text: 'तपाईंले सूचना सदस्यता निष्क्रिय गर्नुभएको छ।',
                        confirmButtonColor: '#0955cf',
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            }
        }"
        class="relative"
    >
        <!-- ========================================================= -->
        <!-- 1. POPUP NOTIFICATION PROMPT: "Subscribe to get notification" -->
        <!-- Appears on bottom-left for unprompted visitors             -->
        <!-- ========================================================= -->
        <div
            x-cloak
            x-show="showAutoPrompt"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 translate-y-4 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-4 scale-95"
            class="fixed bottom-16 left-4 sm:left-5 z-50 w-[calc(100vw-2rem)] max-w-[300px] sm:max-w-[320px] rounded-xl bg-white border border-stone-200 p-3 sm:p-3.5 shadow-xl text-stone-900"
            role="dialog"
            aria-modal="true"
            aria-label="ताजा समाचार सूचना सदस्यता"
        >
            <div class="flex items-start gap-2.5">
                <!-- Bell Icon with pulsing glow -->
                <div class="relative shrink-0 grid h-8 w-8 place-items-center rounded-lg bg-gradient-to-tr from-[#c92137] to-[#e63946] text-white shadow-xs">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-lg bg-red-400 opacity-40"></span>
                    <svg viewBox="0 0 24 24" class="h-4 w-4 fill-none stroke-current stroke-2 kd-bell-wiggle" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
                    </svg>
                </div>

                <div class="flex-1 min-w-0">
                    <div class="flex items-start justify-between gap-1.5">
                        <h3 class="text-xs sm:text-sm font-bold text-stone-900 leading-snug">
                            ताजा समाचारको सूचना पाउनुहोस्
                        </h3>
                        <button
                            type="button"
                            @click="dismissAutoPrompt()"
                            class="grid h-5 w-5 place-items-center rounded-full text-stone-400 hover:bg-stone-100 hover:text-stone-700 transition cursor-pointer text-sm leading-none"
                            aria-label="बन्द गर्नुहोस्"
                        >×</button>
                    </div>
                    <p class="mt-1 text-[11px] text-stone-600 leading-tight">
                        कपिलवस्तु दर्पणका ताजा तथा ब्रेकिङ समाचारको तुरुन्त सूचना प्राप्त गर्नुहोस्।
                    </p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="mt-2.5 flex items-center gap-1.5 pt-2 border-t border-stone-100">
                <button
                    type="button"
                    @click="requestSubscription()"
                    :disabled="isProcessing"
                    class="flex-1 inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0955cf] hover:bg-[#0747ad] text-white px-2.5 py-1.5 text-xs font-bold shadow-xs hover:opacity-95 transition cursor-pointer disabled:opacity-50"
                >
                    <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                    <span x-text="isProcessing ? 'अनुमति माग्दैछ...' : 'अहिले अनुमति दिनुहोस्'">अहिले अनुमति दिनुहोस्</span>
                </button>

                <button
                    type="button"
                    @click="dismissAutoPrompt()"
                    class="rounded-lg border border-stone-200 bg-stone-50 hover:bg-stone-100 text-stone-600 px-2.5 py-1.5 text-xs font-semibold transition cursor-pointer"
                >
                    पछि
                </button>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 2. INTERACTIVE MANAGEMENT POPOVER (When clicking Left Bell)-->
        <!-- ========================================================= -->
        <div
            x-cloak
            x-show="isOpen"
            @click.outside="isOpen = false"
            x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0 translate-y-3 scale-95"
            x-transition:enter-end="opacity-100 translate-y-0 scale-100"
            x-transition:leave="transition ease-in duration-150"
            x-transition:leave-start="opacity-100 translate-y-0 scale-100"
            x-transition:leave-end="opacity-0 translate-y-3 scale-95"
            class="fixed bottom-16 left-4 sm:left-5 z-50 w-[calc(100vw-2rem)] max-w-[300px] sm:max-w-[320px] rounded-xl bg-white border border-stone-200 p-3 sm:p-3.5 shadow-xl text-stone-900"
            role="dialog"
            aria-modal="true"
        >
            <!-- Card Header -->
            <div class="flex items-center justify-between border-b border-stone-100 pb-2 mb-2.5">
                <div class="flex items-center gap-1.5">
                    <span class="grid h-6 w-6 place-items-center rounded-md bg-red-100 text-[#c92137] text-xs">🔔</span>
                    <h3 class="font-bold text-stone-900 text-xs sm:text-sm">
                        सूचना व्यवस्थापन
                    </h3>
                </div>
                <button
                    type="button"
                    @click="isOpen = false"
                    class="grid h-5 w-5 place-items-center rounded-full text-stone-400 hover:bg-stone-100 hover:text-stone-700 transition cursor-pointer text-sm leading-none"
                    aria-label="बन्द गर्नुहोस्"
                >×</button>
            </div>

            <!-- Card Body Based on Status -->
            <div class="space-y-2.5">
                <!-- If Subscribed -->
                <template x-if="isSubscribed">
                    <div>
                        <div class="flex items-center gap-1.5 rounded-lg bg-emerald-50 border border-emerald-200 p-2 text-[11px] font-bold text-emerald-800">
                            <span class="grid h-4 w-4 place-items-center rounded-full bg-emerald-600 text-white text-[9px] font-black">✓</span>
                            <span>सूचना सदस्यता सक्रिय छ</span>
                        </div>
                        <p class="mt-2 text-[11px] text-stone-600 leading-tight">
                            तपाईंले ताजा र ब्रेकिङ समाचारको प्रत्यक्ष नोटिफिकेसन प्राप्त गर्नुहुनेछ।
                        </p>

                        <!-- Action Buttons -->
                        <div class="mt-2.5 space-y-1.5">
                            <button
                                type="button"
                                @click="sendTestNotification()"
                                class="w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0955cf] hover:bg-[#0747ad] text-white py-1.5 text-xs font-bold shadow-xs transition cursor-pointer"
                            >
                                <span>📲</span>
                                <span>परीक्षण सूचना पठाउनुहोस्</span>
                            </button>

                            <button
                                type="button"
                                @click="unsubscribe()"
                                class="w-full inline-flex items-center justify-center gap-1 rounded-lg border border-stone-200 bg-stone-50 hover:bg-rose-50 hover:border-rose-200 hover:text-rose-700 text-stone-600 py-1.5 text-xs font-medium transition cursor-pointer"
                            >
                                <span>🔕</span>
                                <span>सूचना बन्द गर्नुहोस्</span>
                            </button>
                        </div>
                    </div>
                </template>

                <!-- If Denied / Blocked in Browser -->
                <template x-if="permission === 'denied'">
                    <div>
                        <div class="flex items-start gap-1.5 rounded-lg bg-rose-50 border border-rose-200 p-2 text-[11px] font-bold text-rose-800">
                            <span class="text-xs text-rose-600 shrink-0">⚠️</span>
                            <span>ब्राउजरमा सूचना ब्लक गरिएको छ</span>
                        </div>
                        <p class="mt-1.5 text-[11px] text-stone-600 leading-tight">
                            ब्राउजरको Address Bar मा रहेको <strong>🔒 (Site Settings)</strong> बाट <strong>Notifications</strong> लाई <strong>Allow</strong> गर्नुहोस्।
                        </p>
                    </div>
                </template>

                <!-- If Not Subscribed / Default -->
                <template x-if="!isSubscribed && permission !== 'denied'">
                    <div>
                        <p class="text-[11px] text-stone-600 leading-tight">
                            कपिलवस्तु दर्पणका ताजा तथा विशेष समाचारहरू तुरुन्त प्राप्त गर्न सदस्यता लिनुहोस्।
                        </p>

                        <button
                            type="button"
                            @click="requestSubscription()"
                            :disabled="isProcessing"
                            class="mt-2.5 w-full inline-flex items-center justify-center gap-1.5 rounded-lg bg-[#0955cf] hover:bg-[#0747ad] text-white py-1.5 text-xs font-bold shadow-xs transition cursor-pointer disabled:opacity-50"
                        >
                            <svg viewBox="0 0 24 24" class="h-3.5 w-3.5 fill-none stroke-current stroke-2"><path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" /></svg>
                            <span x-text="isProcessing ? 'प्रक्रिया हुँदैछ...' : 'सूचना सदस्यता लिनुहोस्'">सूचना सदस्यता लिनुहोस्</span>
                        </button>
                    </div>
                </template>
            </div>
        </div>

        <!-- ========================================================= -->
        <!-- 3. LEFT BOTTOM FLOATING BELL BUTTON (Compact & Sleek)      -->
        <!-- Positioned fixed on left bottom corner of screen           -->
        <!-- ========================================================= -->
        <button
            type="button"
            @click="toggleCard()"
            class="fixed bottom-4 left-4 sm:bottom-5 sm:left-5 z-40 group flex h-10 w-10 sm:h-11 sm:w-11 items-center justify-center rounded-full bg-[#0955cf] hover:bg-[#0747ad] text-white shadow-md hover:shadow-lg hover:scale-105 active:scale-95 transition-all duration-200 cursor-pointer focus:outline-none focus:ring-2 focus:ring-blue-400/40"
            :class="{ 'ring-2 ring-emerald-400/60 bg-emerald-600': isSubscribed }"
            :aria-expanded="isOpen || showAutoPrompt ? 'true' : 'false'"
            aria-label="ताजा समाचार सूचना सदस्यता (Notifications)"
            title="ताजा समाचार सूचना सदस्यता लिनुहोस्"
        >
            <!-- Animated Ping Badge when Not Subscribed -->
            <template x-if="!isSubscribed">
                <span class="absolute -top-0.5 -right-0.5 flex h-3 w-3">
                    <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-amber-400 opacity-75"></span>
                    <span class="relative inline-flex rounded-full h-3 w-3 bg-amber-500 border border-white shadow-2xs"></span>
                </span>
            </template>

            <!-- Subscribed Green Checkmark Badge -->
            <template x-if="isSubscribed">
                <span class="absolute -top-0.5 -right-0.5 grid h-3.5 w-3.5 place-items-center rounded-full bg-emerald-500 text-white border border-white text-[8px] font-black shadow-2xs">
                    ✓
                </span>
            </template>

            <!-- Bell Icon (Compact) -->
            <svg
                viewBox="0 0 24 24"
                class="h-4.5 w-4.5 sm:h-5 sm:w-5 fill-none stroke-current stroke-2 transition-transform duration-300 group-hover:rotate-12"
                :class="{ 'kd-bell-shake': !isSubscribed }"
                aria-hidden="true"
            >
                <path stroke-linecap="round" stroke-linejoin="round" d="M14.857 17.082a23.848 23.848 0 0 0 5.454-1.31A8.967 8.967 0 0 1 18 9.75V9A6 6 0 0 0 6 9v.75a8.967 8.967 0 0 1-2.312 6.022c1.733.64 3.56 1.085 5.455 1.31m5.714 0a24.255 24.255 0 0 1-5.714 0m5.714 0a3 3 0 1 1-5.714 0" />
            </svg>
        </button>
    </div>

    <!-- Keyframe animation for subtle bell attention wiggle -->
    <style>
        @keyframes kdBellWiggle {
            0%, 100% { transform: rotate(0deg); }
            15% { transform: rotate(14deg); }
            30% { transform: rotate(-12deg); }
            45% { transform: rotate(10deg); }
            60% { transform: rotate(-8deg); }
            75% { transform: rotate(4deg); }
        }
        .kd-bell-wiggle {
            animation: kdBellWiggle 1.8s ease-in-out infinite;
            transform-origin: top center;
        }
        .kd-bell-shake {
            animation: kdBellWiggle 3.5s ease-in-out infinite;
            transform-origin: top center;
        }
    </style>
@endif
