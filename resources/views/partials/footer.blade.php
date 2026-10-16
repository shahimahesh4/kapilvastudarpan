<!-- Global Unified Footer Section -->
<footer class="bg-[#3d5f9e] text-white">
    <div class="w-full grid gap-8 px-4 py-12 sm:px-6 md:grid-cols-2 lg:grid-cols-4 lg:px-8">
        <!-- Publisher Info -->
        <section class="border-b border-white/15 pb-8 lg:border-b-0 lg:border-r lg:pr-8" aria-labelledby="publisher-heading">
            <h2 id="publisher-heading" class="text-xl font-extrabold border-b border-white/20 pb-2">कपिलवस्तु दर्पण न्यूज</h2>
            <div class="mt-4 space-y-1.5 text-sm text-blue-100 font-semibold">
                <p>सञ्चालक संस्था: कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि.</p>
                <p>सूचना विभाग दर्ता नं: ५३१४/८२/८३</p>
                <p>कम्पनी दर्ता नं: ३१७९१९/८२/८३</p>
                <p>प्रेस काउन्सिल सूचीकरण: २०८२/८३</p>
            </div>
            <p class="mt-3 text-sm leading-relaxed text-blue-100">
                कपिलवस्तु दर्पण स्थानीय सरोकार, जनजीवन, विकास र राष्ट्रिय सवालहरूलाई जिम्मेवार र तथ्यपूर्ण ढङ्गले प्रस्तुत गर्ने आधिकारिक डिजिटल समाचार पोर्टल हो।
            </p>
            <div class="mt-4 border-t border-white/15 pt-3 text-sm space-y-1.5">
                <p><strong class="text-white font-bold">संचालक :</strong> <span class="text-blue-100">तारानाथ केवट</span></p>
                <p><strong class="text-white font-bold">सम्पादक :</strong> <span class="text-blue-100">टुकलाल गैरे</span></p>
                <p><strong class="text-white font-bold">प्रबन्ध निर्देशक :</strong> <span class="text-blue-100">शेषकान्त मरासिनी</span></p>
            </div>
        </section>

        <!-- Contact Details -->
        <section class="border-b border-white/15 pb-8 lg:border-b-0 lg:border-r lg:px-8" aria-labelledby="contact-heading">
            <h2 id="contact-heading" class="text-xl font-extrabold border-b border-white/20 pb-2">सम्पर्क कार्यालय</h2>
            <address class="mt-4 space-y-3 not-italic text-sm text-blue-100">
                <p class="flex items-start gap-2">
                    <span class="font-bold text-white">📍</span>
                    <span>कपिलवस्तु १०, पचेहरा (तौलिहवा), लुम्बिनी प्रदेश, नेपाल</span>
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-bold text-white">✉</span>
                    <a href="mailto:kapilvastudarpannews@gmail.com" class="hover:text-white transition">kapilvastudarpannews@gmail.com</a>
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-bold text-white">☎</span>
                    <a href="tel:+9779766250445" class="hover:text-white transition">+९७७-९७६६२५०४४५, ९८१६४०८८७३</a>
                </p>
                <p class="flex items-center gap-2">
                    <span class="font-bold text-white">🌐</span>
                    <a href="https://www.kapilvastudarpan.com" class="hover:text-white transition">www.kapilvastudarpan.com</a>
                </p>
            </address>

            <div class="mt-6">
                <p class="text-xs font-bold uppercase tracking-wider text-blue-200">सामाजिक सञ्जालमा जोडिनुहोस्</p>
                <div class="mt-2 flex gap-2">
                    <a href="#" class="grid h-9 w-9 place-items-center rounded-full bg-white/15 text-white hover:bg-white hover:text-[#3d5f9e] transition font-bold" aria-label="Facebook">f</a>
                    <a href="#" class="grid h-9 w-9 place-items-center rounded-full bg-white/15 text-white hover:bg-white hover:text-[#3d5f9e] transition font-bold" aria-label="X">𝕏</a>
                    <a href="#" class="grid h-9 w-9 place-items-center rounded-full bg-white/15 text-white hover:bg-white hover:text-[#3d5f9e] transition font-bold" aria-label="YouTube">▶</a>
                </div>
            </div>
        </section>

        <!-- Quick Categories Menu -->
        <nav class="border-b border-white/15 pb-8 lg:border-b-0 lg:border-r lg:px-8" aria-labelledby="footer-menu-heading">
            <h2 id="footer-menu-heading" class="text-xl font-extrabold border-b border-white/20 pb-2">प्रमुख विधाहरू</h2>
            <ul class="mt-4 grid grid-cols-2 gap-2 text-sm text-blue-100">
                @if(isset($categories) && $categories->isNotEmpty())
                    @foreach($categories->take(10) as $cat)
                        <li>
                            <a href="{{ route('category.show', $cat->slug) }}" class="hover:text-white transition">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                @endif
            </ul>
        </nav>

        <!-- Important Links & Ads -->
        <nav class="lg:pl-8" aria-labelledby="about-heading">
            <h2 id="about-heading" class="text-xl font-extrabold border-b border-white/20 pb-2">अन्य जानकारी</h2>
            <ul class="mt-4 space-y-2 text-sm text-blue-100">
                <li><a href="#" class="hover:text-white transition">हाम्रो बारेमा</a></li>
                <li><a href="#" class="hover:text-white transition">हाम्रो टिम</a></li>
                <li><a href="#" class="hover:text-white transition">विज्ञापन दररेट</a></li>
                <li><a href="#" class="hover:text-white transition">गोपनीयता नीति</a></li>
                <li><a href="#" class="hover:text-white transition">आचारसंहिता</a></li>
            </ul>
        </nav>
    </div>

    <!-- Copyright Bar -->
    <div class="border-t border-white/15 bg-[#324f85] py-4 text-center text-xs text-blue-200">
        <div class="w-full px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-2">
            <p>© {{ now()->year }} कपिलवस्तु दर्पण न्यूज - कपिलवस्तु मल्टिपर्पोज मिडिया प्रा.लि. सर्वाधिकार सुरक्षित।</p>
            <p>Designed &amp; Developed By: <a href="https://www.siddhitechnepal.com" target="_blank" rel="noopener noreferrer" class="font-bold text-white hover:text-amber-300 transition">Siddhi Tech Nepal Pvt. Ltd.</a></p>
        </div>
    </div>
</footer>
