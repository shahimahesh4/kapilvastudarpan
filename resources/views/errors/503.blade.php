<!DOCTYPE html>
<html lang="ne">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>वेबसाइट अद्यावधिक भइरहेको छ | {{ setting('site_name', 'कपिलवस्तु दर्पण') }}</title>
    <meta name="robots" content="noindex, nofollow">
    <link rel="icon" type="image/png" href="{{ setting_image_url(setting('site_favicon'), 'images/favicon.png') }}">
    <link rel="shortcut icon" href="{{ asset('favicon.ico') }}">
    <link rel="apple-touch-icon" href="{{ setting_image_url(setting('site_favicon'), 'images/favicon.png') }}">
    <style>
        :root {
            --primary: #c92137;
            --primary-dark: #a11427;
            --primary-light: #fef2f2;
            --dark: #090d16;
            --slate-900: #0f172a;
            --slate-800: #1e293b;
            --slate-700: #334155;
            --slate-600: #475569;
            --slate-500: #64748b;
            --slate-200: #e2e8f0;
            --slate-100: #f1f5f9;
            --slate-50: #f8fafc;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", "Noto Sans Devanagari", "Noto Sans", sans-serif;
            background: radial-gradient(circle at top center, #ffffff 0%, #f8fafc 55%, #eef2f6 100%);
            color: var(--slate-900);
            min-height: 100vh;
            min-height: 100dvh;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            line-height: 1.5;
            overflow-x: hidden;
            position: relative;
        }

        /* Top Crimson Ribbon */
        .top-stripe {
            height: 5px;
            background: linear-gradient(90deg, #c92137 0%, #e11d48 40%, #ea580c 75%, #f59e0b 100%);
            width: 100%;
            box-shadow: 0 2px 10px rgba(201, 33, 55, 0.2);
            flex-shrink: 0;
        }

        /* Ambient Glow Backdrop */
        .ambient-glow {
            position: absolute;
            top: 30px;
            left: 50%;
            transform: translateX(-50%);
            width: 650px;
            height: 320px;
            background: radial-gradient(circle, rgba(201, 33, 55, 0.08) 0%, rgba(245, 158, 11, 0.04) 50%, transparent 70%);
            filter: blur(60px);
            pointer-events: none;
            z-index: 0;
        }

        /* Main Container - Centered Vertically & Horizontally */
        .main-container {
            width: 100%;
            max-width: 980px;
            margin: 0 auto;
            padding: 1rem 1.25rem;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            flex: 1;
            position: relative;
            z-index: 1;
        }

        /* Official Brand Box */
        .brand-box {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 1.5rem;
            text-decoration: none;
            background: #ffffff;
            padding: 0.85rem 2.5rem;
            border-radius: 9999px;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.06), 0 1px 3px rgba(0, 0, 0, 0.03);
            border: 1px solid var(--slate-200);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
            flex-shrink: 0;
        }

        .brand-box:hover {
            transform: translateY(-2px);
            box-shadow: 0 12px 30px rgba(0, 0, 0, 0.09);
        }

        .brand-logo {
            height: 105px;
            width: auto;
            max-width: 100%;
            object-fit: contain;
            filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.05));
        }

        .brand-text {
            text-align: left;
            display: flex;
            flex-direction: column;
        }

        .brand-name {
            font-size: 2rem;
            font-weight: 800;
            color: var(--primary);
            line-height: 1.15;
            letter-spacing: -0.015em;
        }

        .brand-slogan {
            font-size: 1.05rem;
            font-weight: 600;
            color: var(--slate-500);
            line-height: 1.2;
            margin-top: 2px;
        }

        /* Main Compact Grand Card */
        .card {
            background: #ffffff;
            border-radius: 1.75rem;
            padding: 2rem 2.25rem 1.75rem 2.25rem;
            box-shadow: 0 20px 45px -10px rgba(15, 23, 42, 0.08), 0 0 0 1px rgba(226, 232, 240, 0.85);
            width: 100%;
            position: relative;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        .card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 5px;
            background: linear-gradient(90deg, var(--primary) 0%, #fb7185 50%, #f59e0b 100%);
        }

        /* Top Header Row in Card: Status Pill & Subtle Spinning Icon */
        .card-top-row {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.85rem;
            margin-bottom: 0.85rem;
            flex-wrap: wrap;
        }

        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 0.65rem;
            background: #fff7ed;
            color: #c2410c;
            border: 1.5px solid #fed7aa;
            padding: 0.45rem 1.25rem;
            border-radius: 9999px;
            font-size: 1.1rem;
            font-weight: 700;
            box-shadow: 0 2px 8px rgba(234, 88, 12, 0.08);
        }

        .pulse-dot {
            width: 11px;
            height: 11px;
            background-color: #ea580c;
            border-radius: 50%;
            position: relative;
        }

        .pulse-dot::after {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background-color: rgba(234, 88, 12, 0.45);
            animation: pulse-ring 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse-ring {
            0% { transform: scale(0.9); opacity: 1; }
            50% { transform: scale(1.6); opacity: 0; }
            100% { transform: scale(0.9); opacity: 0; }
        }

        /* Maintenance Animated Icon */
        .icon-mini {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, #fee2e2 0%, #ffedd5 100%);
            border-radius: 50%;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px rgba(201, 33, 55, 0.15);
            animation: gentle-spin 16s linear infinite;
            color: var(--primary);
            flex-shrink: 0;
        }

        @keyframes gentle-spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        .icon-mini svg {
            width: 20px;
            height: 20px;
        }

        /* Large, Clear Headings */
        h1 {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--dark);
            line-height: 1.2;
            margin-bottom: 0.35rem;
            letter-spacing: -0.015em;
        }

        .subtitle {
            font-size: 1.35rem;
            font-weight: 700;
            color: var(--primary);
            margin-bottom: 0.85rem;
            font-family: inherit;
            letter-spacing: -0.01em;
        }

        /* High-Legibility Body Description */
        .description {
            font-size: 1.35rem;
            color: var(--slate-800);
            max-width: 800px;
            margin: 0 auto 1.85rem auto;
            line-height: 1.65;
            font-weight: 500;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
            margin-bottom: 1.35rem;
            width: 100%;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 0.65rem;
            padding: 0.85rem 2.2rem;
            border-radius: 9999px;
            font-size: 1.18rem;
            font-weight: 700;
            text-decoration: none;
            transition: all 0.2s ease;
            cursor: pointer;
            border: none;
            font-family: inherit;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: #ffffff;
            box-shadow: 0 6px 18px rgba(201, 33, 55, 0.35);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(201, 33, 55, 0.45);
        }

        .btn-outline {
            background: #ffffff;
            color: var(--slate-900);
            border: 2px solid var(--slate-200);
            box-shadow: 0 3px 10px rgba(0, 0, 0, 0.03);
        }

        .btn-outline:hover {
            background: var(--slate-50);
            color: #1877f2;
            border-color: #93c5fd;
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(24, 119, 242, 0.12);
        }

        /* Contact Section */
        .contact-section {
            width: 100%;
            padding-top: 1.15rem;
            border-top: 1px dashed var(--slate-200);
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 0.75rem;
        }

        .contact-label {
            font-size: 1.1rem;
            font-weight: 700;
            color: var(--slate-700);
        }

        .contact-items {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 1rem;
        }

        .contact-link {
            background: var(--slate-50);
            border: 1px solid var(--slate-200);
            padding: 0.55rem 1.4rem;
            border-radius: 9999px;
            color: var(--slate-900);
            font-weight: 700;
            font-size: 1.12rem;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            gap: 0.55rem;
            transition: all 0.2s ease;
        }

        .contact-link:hover {
            background: #ffffff;
            color: var(--primary);
            border-color: var(--primary);
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(201, 33, 55, 0.12);
        }

        /* Clean Footer */
        footer {
            padding: 0.85rem 1rem;
            text-align: center;
            font-size: 0.95rem;
            color: var(--slate-500);
            font-weight: 500;
            flex-shrink: 0;
        }

        /* Responsive Breakpoints */
        @media (max-width: 820px) {
            .brand-logo {
                height: 78px;
            }

            .brand-box {
                padding: 0.65rem 1.6rem;
            }

            h1 {
                font-size: 2.1rem;
            }

            .subtitle {
                font-size: 1.2rem;
            }

            .description {
                font-size: 1.2rem;
            }

            .card {
                padding: 1.75rem 1.25rem;
            }

            .actions-group {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }

            .contact-items {
                flex-direction: column;
                width: 100%;
            }

            .contact-link {
                width: 100%;
                justify-content: center;
            }
        }
    </style>
</head>
<body>
    <div class="top-stripe"></div>
    <div class="ambient-glow"></div>

    <main class="main-container">
        <!-- Official Brand Logo -->
        <div class="brand-box">
            <img src="{{ setting_image_relative_url(setting('site_logo'), 'images/kapilvastu-darpan-logo.jpg') }}" alt="{{ setting('site_name', 'कपिलवस्तु दर्पण') }}" class="brand-logo" onerror="this.style.display='none'; document.getElementById('brand-fallback-text').style.display='flex';">
            <div id="brand-fallback-text" class="brand-text" style="display: none;">
                <span class="brand-name">{{ setting('site_name', 'कपिलवस्तु दर्पण') }}</span>
                <span class="brand-slogan">{{ setting('site_tagline', 'सत्य, तथ्य र निष्पक्ष पत्रकारिता') }}</span>
            </div>
        </div>

        <!-- Main Grand Status Card -->
        <div class="card">
            <!-- Top Status Row with Pill & Animated Icon -->
            <div class="card-top-row">
                <div class="status-pill">
                    <span class="pulse-dot"></span>
                    <span>प्रणाली मर्मत तथा अद्यावधिक कार्य जारी (Maintenance Mode)</span>
                </div>
                <div class="icon-mini" title="Updating">
                    <svg fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                    </svg>
                </div>
            </div>

            <!-- Headlines -->
            <h1>हामी अहिले वेबसाइट अद्यावधिक गर्दैछौं</h1>
            <div class="subtitle">We are currently updating our website</div>

            <!-- Description -->
            <p class="description">
                {{ setting('maintenance_message') ?: 'आदरणीय पाठकवृन्द, तपाईंहरूलाई अझ स्तरीय, सुरक्षित र द्रुत गतिको समाचार वाचन अनुभव प्रदान गर्नका लागि अहिले प्राविधिक मर्मत तथा प्रणाली स्तरोन्नतिको कार्य भइरहेको छ। केही समयमै पोर्टल पुनः नियमित सञ्चालनमा आउनेछ। पर्न गएको असुविधाप्रति क्षमाप्रार्थी छौं।' }}
            </p>



            <!-- Action Buttons -->
            <div class="actions-group">
                <button type="button" onclick="window.location.reload();" class="btn btn-primary">
                    <svg style="width: 22px; height: 22px;" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15" />
                    </svg>
                    <span>पुनः ताजा गर्नुहोस् (Refresh Page)</span>
                </button>

                <a href="{{ setting('social_facebook', 'https://facebook.com') }}" target="_blank" rel="noopener noreferrer" class="btn btn-outline">
                    <svg style="width: 22px; height: 22px; fill: #1877f2;" viewBox="0 0 24 24">
                        <path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/>
                    </svg>
                    <span>फेसबुकमा ताजा अपडेट</span>
                </a>
            </div>

            <!-- Emergency / Urgent Inquiries -->
            <div class="contact-section">
                <div class="contact-label">तत्काल सम्पर्क वा समाचार सम्बन्धी जानकारीका लागि:</div>
                <div class="contact-items">
                    <a href="mailto:{{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}" class="contact-link">
                        <span style="font-size: 1.25rem;">✉️</span>
                        <span>{{ setting('contact_email', 'kapilvastudarpannews@gmail.com') }}</span>
                    </a>
                    <a href="tel:{{ setting('contact_phone', '+9779766250445') }}" class="contact-link">
                        <span style="font-size: 1.25rem;">📞</span>
                        <span>{{ setting('contact_phone', '+९७७-९७६६२५०४४५') }}</span>
                    </a>
                </div>
            </div>
        </div>
    </main>

    <!-- Clean Footer -->
    <footer>
        <div>
            &copy; {{ date('Y') }} {{ setting('site_name', 'कपिलवस्तु दर्पण') }} (Kapilvastu Darpan). सर्वाधिकार सुरक्षित।
        </div>
    </footer>
</body>
</html>
