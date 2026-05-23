<?php
$page_title = "ELECTRON | Sonic Core 2000";
$body_class = "bg-background text-on-background font-body-md selection:bg-secondary-container";
include 'includes/header.php';
?>

<!-- TopNavBar -->
<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 dark:bg-black/90 backdrop-blur-md border-b border-slate-100 dark:border-zinc-900 h-24">
    <div class="max-w-[1440px] mx-auto h-full flex items-center justify-between px-12">
        <!-- Left Nav -->
        <div class="flex gap-8 items-center flex-1">
            <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Audio</a>
            <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Computing</a>
        </div>
        <!-- Brand Logo -->
        <div class="flex-shrink-0">
            <a class="text-3xl font-black tracking-tighter text-slate-950 dark:text-white font-['Space_Grotesk'] uppercase" href="index.php">ELECTRON</a>
        </div>
        <!-- Right Nav -->
        <div class="flex gap-8 items-center flex-1 justify-end">
            <div class="hidden lg:flex gap-8">
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Mobile</a>
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Support</a>
            </div>
            <div class="flex items-center gap-6 ml-4">
                <!-- Hover Search Bar -->
                <div class="relative flex items-center group py-2">
                    <button class="scale-95 active:opacity-80 transition-transform group-hover:opacity-0 group-focus-within:opacity-0 transition-opacity duration-200">
                        <span class="material-symbols-outlined" data-icon="search">search</span>
                    </button>
                    <div class="absolute right-0 top-1/2 -translate-y-1/2 w-0 opacity-0 pointer-events-none group-hover:w-60 lg:group-hover:w-72 group-hover:opacity-100 group-hover:pointer-events-auto group-focus-within:w-60 lg:group-focus-within:w-72 group-focus-within:opacity-100 group-focus-within:pointer-events-auto transition-all duration-300 ease-out z-10 overflow-hidden">
                        <form action="search.php" method="GET" class="relative flex items-center w-60 lg:w-72">
                            <input type="text" name="q" placeholder="SEARCH PRODUCTS..." class="w-full bg-white/95 dark:bg-zinc-950/95 backdrop-blur-lg border border-slate-200 dark:border-zinc-800 rounded-full py-1.5 pl-4 pr-10 text-[10px] font-bold uppercase tracking-widest text-slate-950 dark:text-white placeholder-slate-400 dark:placeholder-zinc-600 focus:outline-none focus:border-slate-400 focus:ring-0 transition-all shadow-[0_8px_30px_rgb(0,0,0,0.12)]" />
                            <button type="submit" class="absolute right-3.5 text-slate-950 dark:text-white hover:scale-110 transition-transform flex items-center">
                                <span class="material-symbols-outlined text-[18px]">search</span>
                            </button>
                        </form>
                    </div>
                </div>
                <button class="scale-95 active:opacity-80 transition-transform relative">
                    <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
                    <span class="absolute -top-1 -right-1 bg-primary text-white text-[8px] px-1 rounded-full">2</span>
                </button>
                <button class="scale-95 active:opacity-80 transition-transform"><span class="material-symbols-outlined" data-icon="person">person</span></button>
            </div>
        </div>
    </div>
</nav>
<main class="mt-24">
    <!-- Product Section -->
    <section class="max-w-[1440px] mx-auto px-12 py-section-gap flex flex-col lg:flex-row gap-gutter">
        <!-- Left Side: Content -->
        <div class="w-full lg:w-5/12 flex flex-col justify-center">
            <nav class="flex items-center gap-2 mb-stack-lg text-slate-400">
                <span class="font-label-bold text-[10px] uppercase tracking-widest">Audio</span>
                <span class="material-symbols-outlined text-sm">chevron_right</span>
                <span class="font-label-bold text-[10px] uppercase tracking-widest text-on-surface">Headphones</span>
            </nav>
            <h1 class="font-display-xl text-display-xl mb-stack-md">SAMSUNG S26 ULTRA</h1>
            <div class="flex items-center gap-4 mb-stack-lg">
                <p class="font-headline-md text-headline-md">$1299.00</p>
                <div class="bg-secondary-container/20 px-3 py-1 rounded-full">
                    <span class="text-secondary font-label-bold text-xs uppercase tracking-tighter">In Stock</span>
                </div>
            </div>
            <p class="font-body-lg text-body-lg text-on-surface-variant mb-section-gap leading-relaxed max-w-md">
                Engineered with proprietary bio-cellulose drivers and active spatial mapping. The Sonic Core 2000 represents the pinnacle of acoustic precision and luxury comfort.
            </p>
            <!-- Specs Grid -->
            <div class="grid grid-cols-2 gap-stack-lg mb-section-gap">
                <div>
                    <span class="font-label-bold text-[10px] text-slate-400 uppercase tracking-widest block mb-2">Battery Life</span>
                    <p class="font-headline-md text-xl">60 Hours</p>
                </div>
                <div>
                    <span class="font-label-bold text-[10px] text-slate-400 uppercase tracking-widest block mb-2">Noise Cancellation</span>
                    <p class="font-headline-md text-xl">Adaptive Ultra</p>
                </div>
                <div>
                    <span class="font-label-bold text-[10px] text-slate-400 uppercase tracking-widest block mb-2">Connection</span>
                    <p class="font-headline-md text-xl">Lossless 5.4</p>
                </div>
                <div>
                    <span class="font-label-bold text-[10px] text-slate-400 uppercase tracking-widest block mb-2">Weight</span>
                    <p class="font-headline-md text-xl">280 Grams</p>
                </div>
            </div>
            <!-- Interaction Area -->
            <div class="flex flex-col gap-6">
                <div class="flex items-center gap-8">
                    <div class="flex items-center border border-outline-variant rounded-lg p-1">
                        <button class="w-12 h-12 flex items-center justify-center hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined">remove</span></button>
                        <span class="w-12 text-center font-headline-md text-lg">01</span>
                        <button class="w-12 h-12 flex items-center justify-center hover:bg-surface-container-low transition-colors"><span class="material-symbols-outlined">add</span></button>
                    </div>
                    <div class="flex gap-3">
                        <button class="w-10 h-10 rounded-full border-2 border-primary bg-primary flex items-center justify-center"></button>
                        <button class="w-10 h-10 rounded-full border border-outline-variant bg-slate-200 flex items-center justify-center"></button>
                        <button class="w-10 h-10 rounded-full border border-outline-variant bg-zinc-800 flex items-center justify-center"></button>
                    </div>
                </div>
                <div class="flex gap-gutter">
                    <button class="rounded-[2rem] flex-1 bg-primary text-white font-label-bold py-6 px-12 uppercase tracking-widest hover:bg-slate-800 transition-all active:scale-[0.98]">
                        Add to Cart
                    </button>
                    <button class="w-20 rounded-[2rem] border border-outline-variant flex items-center justify-center hover:bg-white transition-all">
                        <span class="material-symbols-outlined" data-icon="favorite">favorite</span>
                    </button>
                </div>
            </div>
        </div>
        <!-- Right Side: Gallery -->
        <div class="w-full lg:w-7/12 flex flex-col">
            <div class="bg-surface-container-low product-gradient rounded-xl overflow-hidden aspect-[4/5] flex items-center justify-center p-12">
                <img alt="Samsung S26 Ultra" class="w-full h-full object-contain mix-blend-multiply" data-alt="Premium sleek matte black over-ear headphones centered on a soft grey minimalist studio background with elegant side lighting" src="assets/images/img_6e30900c.jpg" />
            </div>
            <div class="grid grid-cols-4 gap-gutter mt-gutter">
                <div class="aspect-square bg-surface-container rounded-lg border-2 border-primary p-4 cursor-pointer">
                    <img alt="Thumbnail 1" class="w-full h-full object-contain" data-alt="Close up of headphones ear cup showing premium mesh material and precise stitching details in high key lighting" src="assets/images/img_606ffabb.jpg" />
                </div>
                <div class="aspect-square bg-surface-container rounded-lg border border-transparent p-4 cursor-pointer hover:border-outline-variant transition-all">
                    <img alt="Thumbnail 2" class="w-full h-full object-contain" data-alt="Side profile of luxury headphones highlighting the sleek adjustable headband and metallic accents on a neutral backdrop" src="assets/images/img_3b1b011f.jpg" />
                </div>
                <div class="aspect-square bg-surface-container rounded-lg border border-transparent p-4 cursor-pointer hover:border-outline-variant transition-all">
                    <img alt="Thumbnail 3" class="w-full h-full object-contain" data-alt="Detailed view of control buttons on headphones earcup focusing on the tactile quality and premium finish" src="assets/images/img_1cf77dd8.jpg" />
                </div>
                <div class="aspect-square bg-surface-container rounded-lg border border-transparent flex items-center justify-center cursor-pointer hover:border-outline-variant transition-all">
                    <span class="material-symbols-outlined text-4xl text-slate-400">play_circle</span>
                </div>
            </div>
        </div>
    </section>
    <!-- Bento Specifications Section -->
    <section class="bg-surface-container-lowest py-section-gap">
        <div class="max-w-[1440px] mx-auto px-12">
            <div class="flex justify-between items-end mb-16">
                <div>
                    <span class="font-label-bold text-label-bold text-secondary uppercase tracking-[0.2em] mb-4 block">Performance</span>
                    <h2 class="font-headline-lg text-headline-lg">ENGINEERED DEPTH</h2>
                </div>
                <p class="max-w-sm text-on-surface-variant font-body-md">Every component of the Sonic Core 2000 is meticulously crafted to deliver an unparalleled auditory landscape.</p>
            </div>
            <div class="grid grid-cols-12 gap-gutter">
                <!-- Bento Item 1 -->
                <div class="col-span-12 md:col-span-8 bg-surface-container-low rounded-xl p-12 flex flex-col justify-between min-h-[400px]">
                    <div>
                        <span class="material-symbols-outlined text-primary text-5xl mb-8" data-icon="waves" data-weight="fill">waves</span>
                        <h3 class="font-headline-md text-headline-md mb-4">Adaptive Spatial Mapping</h3>
                        <p class="font-body-lg text-on-surface-variant max-w-md">Our AI-driven sensor array analyzes your ear shape 1,000 times per second to optimize sound delivery for your unique anatomy.</p>
                    </div>
                    <img alt="Samsung S26 Ultra" class="self-end h-32 object-contain" data-alt="Abstract visualization of fluid blue sound waves flowing through a dark space with micro-particles and light streaks" src="assets/images/img_e6f86be2.jpg" />
                </div>
                <!-- Bento Item 2 -->
                <div class="col-span-12 md:col-span-4 bg-primary text-white rounded-xl p-12 flex flex-col justify-between overflow-hidden">
                    <div>
                        <h3 class="font-headline-md text-headline-md mb-4">Pure Titanium Build</h3>
                        <p class="font-body-md opacity-70">Ultra-light aerospace grade titanium frame ensures durability without the weight.</p>
                    </div>
                    <div class="mt-8 flex justify-center">
                        <span class="material-symbols-outlined text-[120px] opacity-20">precision_manufacturing</span>
                    </div>
                </div>
                <!-- Bento Item 3 -->
                <div class="col-span-12 md:col-span-4 bg-secondary-container/10 rounded-xl p-12 border border-secondary-container/20">
                    <span class="material-symbols-outlined text-secondary text-4xl mb-6">bolt</span>
                    <h3 class="font-headline-md text-2xl mb-2">Rapid Charge</h3>
                    <p class="font-body-md text-on-surface-variant">5 minutes of charging provides 6 hours of high-fidelity playback.</p>
                </div>
                <!-- Bento Item 4 -->
                <div class="col-span-12 md:col-span-4 bg-surface-container-high rounded-xl p-12 flex flex-col">
                    <span class="material-symbols-outlined text-primary text-4xl mb-6">shield</span>
                    <h3 class="font-headline-md text-2xl mb-2">2 Year Warranty</h3>
                    <p class="font-body-md text-on-surface-variant">Comprehensive coverage for manufacturing defects and battery longevity.</p>
                </div>
                <!-- Bento Item 5 -->
                <div class="col-span-12 md:col-span-4 bg-tertiary-container rounded-xl p-12 text-white">
                    <span class="material-symbols-outlined text-tertiary-fixed text-4xl mb-6">eco</span>
                    <h3 class="font-headline-md text-2xl mb-2">Sustainability</h3>
                    <p class="font-body-md text-slate-400">100% recycled aluminum and vegan protein leather components.</p>
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Footer -->

<?php include 'includes/footer.php'; ?>