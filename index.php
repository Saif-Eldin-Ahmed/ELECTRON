<?php
$nonav = true;
$page_title = "ELECTRON | Digital Flagship";
$body_class = "font-body-md text-on-surface bg-white";
include 'includes/header.php';
?>

<div class="custom-frame overflow-hidden relative">
    <!-- Header -->
    <header class="absolute top-0 w-full z-50 bg-transparent">
        <div class="mx-auto px-6 md:px-8 lg:px-12 flex justify-between items-center h-20 md:h-24">
            <!-- Mobile Menu -->
            <div id="openMenuBtn" class="md:hidden flex items-center cursor-pointer">
                <span class="material-symbols-outlined text-2xl text-black">menu</span>
            </div>

            <nav class="hidden md:flex md:gap-4 lg:gap-10 items-center">
                <div class="flex items-center gap-1 group cursor-pointer">
                    <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">account</a>
                    <span class="material-symbols-outlined text-sm">expand_more</span>
                </div>
                <div class="flex items-center gap-1 group cursor-pointer">
                    <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">cart</a>
                    <span class="material-symbols-outlined text-sm">expand_more</span>
                </div>
            </nav>
            <div class="absolute left-1/2 -translate-x-1/2">
                <a href="index.php" class="text-2xl md:text-3xl font-black tracking-tighter text-black uppercase">ELECTRON</a>
            </div>
            <nav class="hidden md:flex md:gap-4 lg:gap-10 items-center">
                <!-- Desktop Hover Search Bar -->
                <div class="relative flex items-center group py-2">
                    <span class="text-[11px] font-bold uppercase tracking-widest text-black cursor-pointer group-hover:opacity-0 group-focus-within:opacity-0 transition-opacity duration-200">Search</span>
                    <div class="rounded-[2rem] absolute right-0 top-1/2 -translate-y-1/2 w-0 opacity-0 pointer-events-none group-hover:w-60 lg:group-hover:w-72 group-hover:opacity-100 group-hover:pointer-events-auto group-focus-within:w-60 lg:group-focus-within:w-72 group-focus-within:opacity-100 group-focus-within:pointer-events-auto transition-all duration-300 ease-out z-10 overflow-hidden">
                        <form action="search.php" method="GET" class="relative flex items-center w-60 lg:w-72">
                            <input type="text" name="q" placeholder="SEARCH PRODUCTS..." class="w-full bg-white/95 backdrop-blur-lg border border-black/20 rounded-full py-1.5 pl-4 pr-10 text-[10px] font-bold uppercase tracking-widest text-black placeholder-black/40 focus:outline-none focus:border-black focus:ring-0 transition-all shadow-[0_8px_30px_rgb(0,0,0,0.12)]" />
                            <button type="submit" class="absolute right-3.5 text-black hover:scale-110 transition-transform flex items-center">
                                <span class="material-symbols-outlined text-[18px]">search</span>
                            </button>
                        </form>
                    </div>
                </div>
                <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">special offer</a>
            </nav>

            <!-- Mobile Cart -->
            <div class="md:hidden flex items-center">
                <span class="material-symbols-outlined text-2xl text-black">shopping_bag</span>
            </div>
        </div>
    </header>
    <!-- Hero Section -->
    <section class="relative h-[85vh] md:h-[700px] lg:h-[870px] min-h-[550px] md:min-h-[600px] flex items-center hero-bg">
        <div class="absolute inset-0 bg-white/20"></div>
        <div class="absolute inset-0 flex justify-center items-center gap-4 md:gap-8 lg:gap-12 pointer-events-none overflow-hidden">
            <div class="hidden md:block md:w-56 lg:w-64 md:h-72 lg:h-80 bg-white/40 backdrop-blur-md rounded-[2.5rem] lg:rounded-[3rem] shadow-2xl p-4 lg:p-6 floating  -mt-32">
                <img alt="Premium Headphones" class="w-full h-full object-contain" src="assets/images/img_cda5e480.jpg" />
            </div>
            <div class="w-60 md:w-56 lg:w-64 h-72 md:h-72 lg:h-80 bg-white/40 backdrop-blur-md rounded-[2.5rem] lg:rounded-[3rem] shadow-2xl p-4 lg:p-6 floating floating-delayed -mt-64 md:mt-10">
                <img alt="IPad" class="w-full h-full object-contain" src="assets/images/img_fc558cd.png" />
            </div>
            <div class="hidden md:block md:w-56 lg:w-64 md:h-72 lg:h-80 bg-white/40 backdrop-blur-md rounded-[2.5rem] lg:rounded-[3rem] shadow-2xl p-4 lg:p-6 floating floating-more-delayed -mt-20">
                <img alt="Flagship Smartphone" class="w-full h-full object-contain" src="assets/images/img_5ab27c1d.jpg" />
            </div>
        </div>
        <div class="relative z-10 w-full px-6 flex flex-col justify-end h-full pb-16 md:pb-24">
            <div class="max-w-xl text-center md:text-left mx-auto md:mx-0">
                <h1 class="font-bold text-4xl md:text-5xl lg:text-6xl leading-[1.1] text-black uppercase tracking-tight mb-6 md:mb-8">THE FUTURE AT YOUR FINGERTIPS</h1>
                <div class="flex flex-col md:flex-row justify-between items-center md:items-end gap-6 md:gap-4 lg:gap-0">
                    <p class="font-body-md text-black/80 md:text-black/60 max-w-[280px] md:max-w-[240px] lg:max-w-xs text-sm md:text-sm lg:text-base">Discover the convergence of precision engineering and lifestyle aesthetics with our curated collection.</p>
                    <button class="bg-white/30 backdrop-blur-md border border-white/50 text-black shadow-[0_8px_32px_rgba(0,0,0,0.1)] px-10 py-4 md:py-3 w-full md:w-auto rounded-full font-bold uppercase text-[12px] tracking-widest hover:bg-white/50 transition-all">Explore</button>
                </div>
            </div>
        </div>
    </section>
</div>
<main class="w-full mx-auto px-6">
    <!-- Category Grid -->
    <section class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 md:gap-4 lg:gap-6">
            <div class="bg-[#d7e9f7] rounded-[2rem] h-[400px] md:h-[400px] lg:h-[500px] flex flex-col justify-between group overflow-hidden relative px-8 md:px-6 lg:px-12 py-8">
                <div>
                    <h3 class="text-3xl md:text-3xl lg:text-4xl font-bold text-[#1a3a5f] leading-tight">Audio<br />Systems</h3>
                </div>
                <div class="relative flex justify-center items-center flex-1">
                    <img alt="Audio Systems" class="h-[200px] object-contain group-hover:scale-105 transition-transform duration-700" src="assets/images/img_59ed7574.png" />
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-xs md:text-[10px] lg:text-sm font-bold text-[#1a3a5f]/60 uppercase tracking-widest">Hi-Fi Solutions</p>
                    <span class="material-symbols-outlined text-2xl">north_east</span>
                </div>
            </div>
            <div class="bg-[#e6f4ea] rounded-[2rem] h-[400px] md:h-[400px] lg:h-[500px] flex flex-col justify-between group overflow-hidden relative px-8 md:px-6 lg:px-12 py-8">
                <div>
                    <h3 class="text-3xl md:text-3xl lg:text-4xl font-bold text-[#1e4620] leading-tight">Computing<br />Devices</h3>
                </div>
                <div class="relative flex justify-center items-center flex-1 ">
                    <img alt="Computing" class="h-[200px] object-contain group-hover:scale-105 transition-transform duration-700" src="assets/images/img_7778cd9c.png" />
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-xs md:text-[10px] lg:text-sm font-bold text-[#1e4620]/60 uppercase tracking-widest">Pro Performance</p>
                    <span class="material-symbols-outlined text-2xl">north_east</span>
                </div>
            </div>
            <div class="bg-slate-100 rounded-[2rem] h-[400px] md:h-[400px] lg:h-[500px] flex flex-col justify-between group overflow-hidden relative px-8 md:px-6 lg:px-12 py-8">
                <div>
                    <h3 class="text-3xl md:text-3xl lg:text-4xl font-bold text-slate-900 leading-tight">Mobile<br />Essentials</h3>
                </div>
                <div class="relative flex justify-center items-center flex-1">
                    <img alt="Mobile" class="h-[175px] md:scale-[1] sm:scale-[1.4] object-contain group-hover:scale-105 sm:group-hover:scale-[1.5] md:group-hover:scale-[1.05] transition-transform duration-700" src="assets/images/img_6e30900c.png" />
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-xs md:text-[10px] lg:text-sm font-bold text-slate-500 uppercase tracking-widest">Connected Living</p>
                    <span class="material-symbols-outlined text-2xl text-slate-900">north_east</span>
                </div>
            </div>
        </div>
    </section>
    <!-- Subcategories Toggles -->
    <section class="mt-6 md:mt-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 md:gap-4 lg:gap-6">
            <div class="bg-surface-container-low rounded-[1.5rem] py-4 md:py-4 lg:py-6 px-4 md:px-4 lg:px-8 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-0 cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-sm md:text-sm lg:text-base tracking-tight text-center lg:text-left">Personal Audio</span>
                <div class="h-[56.57px] flex justify-center items-center">
                    <div class="w-10 h-6 bg-blue-400 rounded-full flex items-center justify-center">
                        <div class="w-4 h-1 bg-white rounded-full"></div>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-low rounded-[1.5rem] py-4 md:py-4 lg:py-6 px-4 md:px-4 lg:px-8 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-0 cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-sm md:text-sm lg:text-base tracking-tight text-center lg:text-left">Smart Home</span>
                <div class="h-[56.57px] flex justify-center items-center">
                    <div class="w-10 h-6 bg-orange-400 rounded-full flex items-center justify-center">
                        <div class="w-4 h-1 bg-white rounded-full"></div>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-low rounded-[1.5rem] py-4 md:py-4 lg:py-6 px-4 md:px-4 lg:px-8 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-0 cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-sm md:text-sm lg:text-base tracking-tight text-center lg:text-left">Wearables</span>
                <div class="h-[56.57px] flex justify-center items-center">
                    <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center rotate-45">
                        <div class="w-4 h-6 border-2 border-white rounded-full"></div>
                    </div>
                </div>
            </div>
            <div class="bg-surface-container-low rounded-[1.5rem] py-4 md:py-4 lg:py-6 px-4 md:px-4 lg:px-8 flex flex-col lg:flex-row justify-between items-center gap-4 lg:gap-0 cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-sm md:text-sm lg:text-base tracking-tight text-center lg:text-left">Power Kits</span>
                <div class="h-[56.57px] flex justify-center items-center">
                    <div class="w-12 h-6 bg-yellow-400 rounded-full flex items-center justify-center shadow-inner">
                        <div class="w-6 h-3 bg-white/60 rounded-full"></div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- Flagship Releases (Featured Products) -->
    <section class="mt-12 md:mt-16">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-end gap-4 sm:gap-0 mb-8 md:mb-10">
            <h2 class="text-4xl md:text-5xl font-headline-md uppercase tracking-tight text-black">Flagship Releases</h2>
            <a class="text-xs font-bold border-b-2 border-black pb-1 uppercase tracking-widest hover:text-black/70 hover:border-black/70 transition-colors" href="#">View All</a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-8 md:gap-4 lg:gap-8">
            <!-- Product Card 1 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-4 md:mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80 group-hover:opacity-100 group-hover:scale-[1.5]  transition-all duration-700">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#bae6fd_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Sonic Core 2000" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_f65dbb08.jpg" />
                </div>
                <div class="space-y-1 md:space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Audio</p>
                    <h3 class="font-headline-md text-lg md:text-base lg:text-xl tracking-tight">iPhone 15</h3>
                    <p class="font-bold text-zinc-900">$499.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-2 md:pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5 inline-block">Shop Now</button>
                </div>
            </div>
            <!-- Product Card 2 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-4 md:mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80 group-hover:opacity-100 group-hover:scale-[1.5]  transition-all duration-700">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#2563eb_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Type-X" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_9befdc43.jpg" />
                </div>
                <div class="space-y-1 md:space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Peripherals</p>
                    <h3 class="font-headline-md text-lg md:text-base lg:text-xl tracking-tight">iPhone 15 Pro' (Blue Titanium)</h3>
                    <p class="font-bold text-zinc-900">$229.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-2 md:pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5 inline-block">Shop Now</button>
                </div>
            </div>
            <!-- Product Card 3 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-4 md:mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80 group-hover:opacity-100 group-hover:scale-[1.5] transition-all duration-700">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#d8b4fe_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Slate Pro" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_5501e0e5.jpg" />
                </div>
                <div class="space-y-1 md:space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Computing</p>
                    <h3 class="font-headline-md text-lg md:text-base lg:text-xl tracking-tight">iPhone 15 Pro' (Natural Titanium)</h3>
                    <p class="font-bold text-zinc-900">$1,199.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-2 md:pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5 inline-block">Shop Now</button>
                </div>
            </div>
            <!-- Product Card 4 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-4 md:mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80 group-hover:opacity-100 group-hover:scale-[1.5]  transition-all duration-700">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#fb923c_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Volt Shell" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_75af29ec.jpg" />
                </div>
                <div class="space-y-1 md:space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Power</p>
                    <h3 class="font-headline-md text-lg md:text-base lg:text-xl tracking-tight">iPhone 15 Pro' (White Titanium)</h3>
                    <p class="font-bold text-zinc-900">$89.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-2 md:pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5 inline-block">Shop Now</button>
                </div>
            </div>
        </div>
    </section>
    <!-- New Sections From Reference Image -->
    <section class="space-y-8 mt-12 md:mt-16">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 items-stretch mt-8 md:mt-16">
            <!-- Left Column: Stacked items -->
            <div class="grid grid-rows-1 md:grid-rows-2 gap-8 h-full">
                <!-- Smart Audio Hero Card -->
                <div class="bg-[#e2f0e6] rounded-[2rem] p-8 md:p-10 lg:p-12 flex flex-col md:flex-row relative overflow-hidden group min-h-[380px] md:min-h-[300px] lg:min-h-0">
                    <div class="z-10 w-full md:max-w-[340px] lg:max-w-[280px] h-full flex flex-col justify-between">

                        <div>
                            <h3 class="font-headline-md text-3xl md:text-3xl lg:text-4xl mb-4 tracking-tight leading-[1.1]">Next-gen Audio Spatial Control</h3>
                            <p class="text-sm opacity-70 mb-6 md:mb-6 lg:mb-8 leading-relaxed">Immersive 3D audio precision for high-fidelity listeners.</p>
                        </div>
                        <button class="bg-white/30 backdrop-blur-md border border-white/50 text-[#1a3a24] shadow-[0_8px_32px_rgba(0,0,0,0.1)] px-8 py-4 md:py-3 rounded-full text-[12px] md:text-[10px] font-bold uppercase tracking-widest hover:bg-white/50 transition-all w-full md:w-auto">Explore</button>
                    </div>
                    <div class="absolute right-[-5%] w-[300px] md:top-1/2 md:-translate-y-1/2 group-hover:-translate-x-3 transition-transform duration-700 pointer-events-none">
                        <img alt="Audio Device" class="w-full h-auto object-contain drop-shadow-2xl" src="assets/images/img_256a167e.jpg" />
                    </div>
                </div>
                <!-- Power Tech Card -->
                <div class="bg-[#f7ece2] rounded-[2rem] p-8 md:p-10 lg:p-12 flex flex-col md:flex-row relative overflow-hidden group min-h-[380px] md:min-h-[300px] lg:min-h-0">
                    <div class="z-10 w-full md:max-w-[340px] lg:max-w-[280px] h-full flex flex-col justify-between">
                        <div>
                            <h3 class="font-headline-md text-3xl md:text-3xl lg:text-4xl mb-4 tracking-tight leading-[1.1]">Ultra-Fast Energy Hub</h3>
                            <p class="text-sm opacity-70 mb-6 md:mb-6 lg:mb-8 leading-relaxed">Power all your devices simultaneously with smart delivery.</p>
                        </div>
                        <button class="bg-white/30 backdrop-blur-md border border-white/50 text-black shadow-[0_8px_32px_rgba(0,0,0,0.1)] px-8 py-4 md:py-3 rounded-full text-[12px] md:text-[10px] font-bold uppercase tracking-widest hover:bg-white/50 transition-all w-full md:w-auto">Explore</button>
                    </div>
                    <div class="absolute right-[-5%] md:right-[2%] bottom-[-10%] sm:bottom-[0%] w-[300px] group-hover:-translate-y-3 transition-transform duration-700 pointer-events-none">
                        <img alt="Power Device" class="w-full h-auto object-contain drop-shadow-2xl" src="assets/images/img_eca8edd1.jpg" />
                    </div>
                </div>
            </div>
            <!-- Right Column: Single Large Card -->
            <div class="rounded-[2rem] p-8 md:p-10 lg:p-14 flex flex-col justify-between relative overflow-hidden group h-full min-h-[450px] md:min-h-[300px] lg:min-h-0" style="background: linear-gradient(to top right, #e0f2fe 0%, #f3e8ff 20%, #fce7f3 40%, #ffedd5 60%, #fef9c3 80%, #dcfce7 100%);">
                <div class="z-10 relative w-[340px] lg:w-full flex flex-col justify-between h-full">
                    <div class="z-10 relative lg:w-auto">
                        <h3 class="font-headline-md text-4xl md:text-4xl lg:text-5xl mb-4 md:mb-5 tracking-tight leading-[1.1]">Precision Touch Interface</h3>
                        <p class="text-base md:text-sm lg:text-base opacity-70 max-w-sm leading-relaxed">Revolutionary tactile feedback for the ultimate creative workflow.</p>
                    </div>
                    <button class="bg-white/30 backdrop-blur-md border border-white/50 text-[#1a2c42] shadow-[0_8px_32px_rgba(0,0,0,0.1)] px-10 py-4 rounded-full text-[12px] font-bold uppercase tracking-widest hover:bg-white/50 transition-all max-w-[559px]">Explore</button>
                </div>
                <div class="absolute right-[-10%] top-1/2 -translate-y-[40%] w-[400px] md:w-[500px] lg:max-w-[600px] lg:w-[80%] group-hover:-translate-x-3 transition-transform duration-700 pointer-events-none">
                    <img alt="Interface" class="w-full h-auto object-contain drop-shadow-2xl" src="assets/images/img_ff8dc7dc.png" />
                </div>
            </div>
        </div>
        <!-- New release section -->
        <section class="rounded-[2rem] mt-12 md:mt-16 lg:mt-24 items-center mx-auto w-full overflow-hidden max-h-[70vh]"><img alt="Samsung S26 Ultra" class="rounded-[2rem] w-full object-contain" src="assets/images/img_b731058b.jpg" /></section>
        <!-- Call to Action Banner -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-12 md:mt-16">
            <div class="bg-[#fdf9d8] rounded-[2rem] p-8 md:p-12 lg:p-16 flex flex-col justify-between min-h-[380px] md:min-h-[350px] lg:min-h-[440px] relative overflow-hidden group">
                <span class="text-6xl md:text-7xl lg:text-8xl font-black text-black/5 absolute -bottom-6 md:-bottom-8 lg:-bottom-12 left-0 tracking-tighter uppercase transition-transform duration-1000 group-hover:scale-105 origin-bottom-left">ELECTRON</span>
                <div class="relative z-10">
                    <h2 class="text-4xl md:text-5xl lg:text-6xl font-headline-md mb-8 md:mb-10 lg:mb-14 tracking-tighter text-black uppercase">ELECTRON</h2>
                    <nav class="flex flex-wrap gap-x-6 md:gap-x-8 lg:gap-x-10 gap-y-4 md:gap-y-5 lg:gap-y-6">
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Home</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Shop</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Shipping</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Privacy</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Social Media</a>
                    </nav>
                </div>
                <p class="text-[10px] font-bold text-black/40 uppercase tracking-widest relative z-10 mt-12 md:mt-14 lg:mt-16">QUALITY ELECTRONICS DELIVERED TO YOUR DOORSTEP</p>
            </div>
            <div class="bg-gradient-to-br from-stone-50 to-stone-200 rounded-[2rem] p-8 md:p-12 lg:p-16 flex flex-col md:flex-row items-center text-center md:text-left justify-between min-h-[440px] md:min-h-[350px] lg:min-h-[440px] relative overflow-hidden group">
                <div class="space-y-4 md:space-y-5 lg:space-y-6 z-10 w-full md:w-3/5 lg:w-1/2 flex flex-col items-center md:items-start relative h-full justify-center mt-32 md:mt-0">
                    <span class="bg-gradient-to-r from-zinc-300 to-zinc-400 text-black px-5 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest inline-block shadow-sm">Labs Program</span>
                    <h2 class="font-headline-md text-4xl md:text-4xl lg:text-5xl tracking-tight leading-[1.1]">Apply for the beta.<br />We'll handle the rest.</h2>
                    <button class="bg-white/30 backdrop-blur-md border border-white/50 text-black shadow-[0_8px_32px_rgba(0,0,0,0.1)] px-12 py-4 md:py-3 lg:py-4 rounded-full font-bold uppercase text-[12px] md:text-[10px] lg:text-[12px] tracking-widest hover:bg-white/50 hover:-translate-y-1 transition-all w-full sm:max-w-xs mt-6 md:mt-6 lg:mt-8">Apply Now</button>
                </div>
                <div class="absolute right-1/2 translate-x-1/2 md:translate-x-0 md:right-[-10%] lg:right-[-25%] md:top-1/2 md:-translate-y-1/2 w-[250px] sm:max-w-[300px] md:max-w-[280px] lg:max-w-none lg:w-[80%] flex justify-center items-center pointer-events-none">
                    <img alt="MacBook Pro apply now" class="w-full h-auto object-contain md:group-hover:-translate-x-1 group-hover:drop-shadow-[0_25px_25px_rgba(0,0,0,0.3)] transition-all duration-500 z-10 relative" src="assets/images/img_cd9f9dd9.png" />
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Mobile Menu Overlay -->
<div id="mobileMenu" class="fixed inset-0 bg-white z-[100] transform translate-y-full transition-transform duration-500 ease-in-out flex flex-col pt-24 px-8 pb-12 overflow-y-auto">
    <button id="closeMenuBtn" class="absolute top-8 right-6 text-black">
        <span class="material-symbols-outlined text-3xl">close</span>
    </button>
    <div class="flex flex-col gap-8 mt-12">
        <a class="text-3xl font-headline-md uppercase tracking-tight text-black border-b border-black/10 pb-4 hover:text-black/70" href="#">Special Offer</a>
        <form action="search.php" method="GET" class="relative flex items-center border-b border-black/10 pb-4">
            <input type="text" name="q" placeholder="SEARCH PRODUCTS..." class="w-full bg-transparent text-3xl font-headline-md uppercase tracking-tight text-black placeholder-black/30 focus:outline-none border-none p-0 focus:ring-0" />
            <button type="submit" class="absolute right-0 text-black flex items-center">
                <span class="material-symbols-outlined text-3xl">search</span>
            </button>
        </form>
        <a class="text-3xl font-headline-md uppercase tracking-tight text-black border-b border-black/10 pb-4 hover:text-black/70" href="#">Account</a>
        <a class="text-3xl font-headline-md uppercase tracking-tight text-black border-b border-black/10 pb-4 hover:text-black/70" href="#">Cart</a>
    </div>
    <div class="mt-auto pt-12">
        <p class="text-[10px] font-bold text-black/40 uppercase tracking-widest">ELECTRON DIGITAL FLAGSHIP</p>
    </div>
</div>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const openBtn = document.getElementById('openMenuBtn');
        const closeBtn = document.getElementById('closeMenuBtn');
        const mobileMenu = document.getElementById('mobileMenu');

        if (openBtn && closeBtn && mobileMenu) {
            openBtn.addEventListener('click', () => {
                mobileMenu.classList.remove('translate-y-full');
            });

            closeBtn.addEventListener('click', () => {
                mobileMenu.classList.add('translate-y-full');
            });
        }
    });
</script>