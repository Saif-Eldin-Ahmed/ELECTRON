<?php
$page_title = "ELECTRON | Digital Flagship";
$body_class = "font-body-md text-on-surface bg-white";
include 'includes/header.php';
?>

<div class="custom-frame overflow-hidden relative">
    <!-- Header -->
    <header class="absolute top-0 w-full z-50 bg-transparent">
        <div class="mx-auto px-6 flex justify-between items-center h-24">
            <nav class="flex gap-10 items-center">
                <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">Home</a>
                <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">Request Order</a>
                <div class="flex items-center gap-1 group cursor-pointer">
                    <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">Special Offer</a>
                    <span class="material-symbols-outlined text-sm">expand_more</span>
                </div>
            </nav>
            <div class="absolute left-1/2 -translate-x-1/2">
                <span class="text-3xl font-black tracking-tighter text-black">ELECTRON</span>
            </div>
            <nav class="flex gap-10 items-center">
                <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">Search</a>
                <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">Account</a>
                <a class="text-[11px] font-bold uppercase tracking-widest text-black" href="#">Cart ( 0 )</a>
            </nav>
        </div>
    </header>
    <!-- Hero Section -->
    <section class="relative h-[870px] min-h-[600px] flex items-center hero-bg">
        <div class="absolute inset-0 bg-white/20"></div>
        <div class="absolute inset-0 flex justify-center items-center gap-12 pointer-events-none">
            <div class="w-64 h-80 bg-white/40 backdrop-blur-md rounded-[3rem] shadow-2xl p-6 floating -mt-32">
                <img alt="Premium Headphones" class="w-full h-full object-contain" src="assets/images/img_cda5e480.jpg" />
            </div>
            <div class="w-64 h-80 bg-white/40 backdrop-blur-md rounded-[3rem] shadow-2xl p-6 floating-delayed mt-10">
                <img alt="Pro Device" class="w-full h-full object-contain" src="assets/images/img_41c53f7d.jpg" />
            </div>
            <div class="w-64 h-80 bg-white/40 backdrop-blur-md rounded-[3rem] shadow-2xl p-6 floating-more-delayed -mt-20">
                <img alt="Flagship Smartphone" class="w-full h-full object-contain" src="assets/images/img_5ab27c1d.jpg" />
            </div>
        </div>
        <div class="relative z-10 w-full px-6 flex flex-col justify-end h-full pb-24">
            <div class="max-w-xl">
                <h1 class="font-bold text-6xl leading-[1.1] text-black uppercase tracking-tight mb-8">THE FUTURE AT YOUR FINGERTIPS</h1>
                <div class="flex justify-between items-end">
                    <p class="font-body-md text-black/60 max-w-xs">Discover the convergence of precision engineering and lifestyle aesthetics with our curated collection.</p>
                    <button class="bg-black text-white px-10 py-3 rounded-full font-bold uppercase text-[12px] tracking-widest hover:opacity-80 transition-opacity">Explore</button>
                </div>
            </div>
        </div>
    </section>
</div>
<main class="w-full mx-auto px-6">
    <!-- Category Grid -->
    <section class="mt-6">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-[#d7e9f7] rounded-[2rem] p-12 h-[500px] flex flex-col justify-between group overflow-hidden relative">
                <div>
                    <h3 class="text-4xl font-bold text-[#1a3a5f] leading-tight">Audio<br />Systems</h3>
                </div>
                <div class="relative flex justify-center flex-1">
                    <img alt="Audio Systems" class="w-4/5 object-contain group-hover:scale-105 transition-transform duration-700" src="assets/images/img_59ed7574.jpg" />
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-bold text-[#1a3a5f]/60 uppercase tracking-widest">Hi-Fi Solutions</p>
                    <span class="material-symbols-outlined text-2xl">north_east</span>
                </div>
            </div>
            <div class="bg-[#e6f4ea] rounded-[2rem] h-[500px] flex flex-col justify-between group overflow-hidden relative px-12 py-8">
                <div>
                    <h3 class="text-4xl font-bold text-[#1e4620] leading-tight">Computing<br />Devices</h3>
                </div>
                <div class="relative flex justify-center flex-1 items-center">
                    <img alt="Computing" class="object-contain group-hover:scale-105 transition-transform duration-700 h-[90%] w-auto" src="assets/images/img_2a743d1d.jpg" />
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-bold text-[#1e4620]/60 uppercase tracking-widest">Pro Performance</p>
                    <span class="material-symbols-outlined text-2xl">north_east</span>
                </div>
            </div>
            <div class="bg-slate-100 rounded-[2rem] p-12 h-[500px] flex flex-col justify-between group overflow-hidden relative">
                <div>
                    <h3 class="text-4xl font-bold text-slate-900 leading-tight">Mobile<br />Essentials</h3>
                </div>
                <div class="relative flex justify-center flex-1">
                    <img alt="Mobile" class="w-4/5 object-contain group-hover:scale-105 transition-transform duration-700" src="assets/images/img_6e30900c.jpg" />
                </div>
                <div class="flex justify-between items-center">
                    <p class="text-sm font-bold text-slate-500 uppercase tracking-widest">Connected Living</p>
                    <span class="material-symbols-outlined text-2xl text-slate-900">north_east</span>
                </div>
            </div>
        </div>
    </section>
    <!-- Subcategories Toggles -->
    <section class="mt-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-6">
            <div class="bg-surface-container-low rounded-[1.5rem] py-6 px-8 flex justify-between items-center cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-base tracking-tight">Personal Audio</span>
                <img alt="" class="h-8 w-auto grayscale opacity-50" src="https://lh3.googleusercontent.com/aida/ADBb0ug0G7w-vul81qC-mikSerbzaiGm2KMWz_1OTtfHlDGIidXhQ7kz6PLlBgEQmPpNAx9byaxzE7pSTFR1Y0r7WnyfDldLRcVIxnsHDLHfwbmCXEH30tOIpXvc6m9Ap6OiCrVgDo1jjG7bPAYm0dxqPbEv2UNMdpwTdrejZtMds2ntceVmkh2BP5cnBaMPPnGH-qt9w3OF6KcAM9GJXbirIlLkgStmLwWlvmNZv8LVU0rI7MRRpxHQAQLFT_aYjXSm9s-VCxIbseo7" />
            </div>
            <div class="bg-surface-container-low rounded-[1.5rem] py-6 px-8 flex justify-between items-center cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-base tracking-tight">Smart Home</span>
                <div class="w-10 h-6 bg-orange-400 rounded-full flex items-center justify-center">
                    <div class="w-4 h-1 bg-white rounded-full"></div>
                </div>
            </div>
            <div class="bg-surface-container-low rounded-[1.5rem] py-6 px-8 flex justify-between items-center cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-base tracking-tight">Wearables</span>
                <div class="w-10 h-10 bg-green-500 rounded-full flex items-center justify-center rotate-45">
                    <div class="w-4 h-6 border-2 border-white rounded-full"></div>
                </div>
            </div>
            <div class="bg-surface-container-low rounded-[1.5rem] py-6 px-8 flex justify-between items-center cursor-pointer hover:bg-surface-container transition-colors border border-black/5">
                <span class="font-headline-md text-base tracking-tight">Power Kits</span>
                <div class="w-12 h-6 bg-yellow-400 rounded-full flex items-center justify-center shadow-inner">
                    <div class="w-6 h-3 bg-white/60 rounded-full"></div>
                </div>
            </div>
        </div>
    </section>
    <!-- Flagship Releases (Featured Products) -->
    <section class="mt-16">
        <div class="flex justify-between items-end mb-10">
            <h2 class="text-5xl font-headline-md uppercase tracking-tight text-black">Flagship Releases</h2>
            <a class="text-xs font-bold border-b-2 border-black pb-1 uppercase tracking-widest hover:text-black/70 hover:border-black/70 transition-colors" href="#">View All</a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8">
            <!-- Product Card 1 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#bae6fd_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Sonic Core 2000" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_f65dbb08.jpg" />
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Audio</p>
                    <h3 class="font-headline-md text-xl tracking-tight">iPhone 15</h3>
                    <p class="font-bold text-zinc-900">$499.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5">Shop Now</button>
                </div>
            </div>
            <!-- Product Card 2 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#2563eb_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Type-X" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_9befdc43.jpg" />
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Peripherals</p>
                    <h3 class="font-headline-md text-xl tracking-tight">iPhone 15 Pro' (Blue Titanium)</h3>
                    <p class="font-bold text-zinc-900">$229.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5">Shop Now</button>
                </div>
            </div>
            <!-- Product Card 3 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#d8b4fe_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Slate Pro" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_5501e0e5.jpg" />
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Computing</p>
                    <h3 class="font-headline-md text-xl tracking-tight">iPhone 15 Pro' (Natural Titanium)</h3>
                    <p class="font-bold text-zinc-900">$1,199.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5">Shop Now</button>
                </div>
            </div>
            <!-- Product Card 4 -->
            <div class="group">
                <div class="aspect-[3/4] bg-surface-container-low rounded-[2rem] overflow-hidden mb-6 flex items-end justify-center relative border border-black/5">
                    <div class="absolute inset-0 flex items-center justify-center pointer-events-none opacity-80">
                        <div class="w-full h-full bg-[radial-gradient(circle,_#fb923c_0%,_transparent_75%)] blur-3xl"></div>
                    </div><img alt="Volt Shell" class="w-full h-[90%] object-contain object-bottom group-hover:scale-105 transition-transform duration-700 relative z-10" src="assets/images/img_75af29ec.jpg" />
                </div>
                <div class="space-y-2">
                    <p class="text-[10px] font-bold text-zinc-400 uppercase tracking-widest">Power</p>
                    <h3 class="font-headline-md text-xl tracking-tight">iPhone 15 Pro' (White Titanium)</h3>
                    <p class="font-bold text-zinc-900">$89.00</p>
                    <button class="text-[10px] font-bold uppercase tracking-widest pt-4 hover:text-blue-600 transition-colors border-b border-transparent hover:border-blue-600 pb-0.5">Shop Now</button>
                </div>
            </div>
        </div>
    </section>
    <!-- New Sections From Reference Image -->
    <section class="space-y-8 mt-16">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-stretch mt-16">
            <!-- Left Column: Stacked items -->
            <div class="grid grid-rows-2 gap-8 h-full">
                <!-- Smart Audio Hero Card -->
                <div class="bg-[#e2f0e6] rounded-[2rem] p-12 flex relative overflow-hidden group">
                    <div class="z-10 max-w-[280px]">
                        <h3 class="font-headline-md text-4xl mb-4 tracking-tight leading-[1.1]">Next-gen Audio Spatial Control</h3>
                        <p class="text-sm opacity-70 mb-8 leading-relaxed">Immersive 3D audio precision for high-fidelity listeners.</p>
                        <button class="bg-[#1a3a24] text-white px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-black transition-colors">Explore</button>
                    </div>
                    <div class="absolute right-[-10%] top-1/2 -translate-y-1/2 w-[55%] group-hover:scale-105 transition-transform duration-700">
                        <img alt="Audio Device" class="w-full h-auto object-contain drop-shadow-2xl" src="assets/images/img_256a167e.jpg" />
                    </div>
                </div>
                <!-- Power Tech Card -->
                <div class="bg-[#f7ece2] rounded-[2rem] p-12 flex relative overflow-hidden group">
                    <div class="z-10 max-w-[280px]">
                        <h3 class="font-headline-md text-4xl mb-4 tracking-tight leading-[1.1]">Ultra-Fast Energy Hub</h3>
                        <p class="text-sm opacity-70 mb-8 leading-relaxed">Power all your devices simultaneously with smart delivery.</p>
                        <button class="bg-black text-white px-8 py-3 rounded-full text-[10px] font-bold uppercase tracking-widest hover:bg-black/80 transition-colors">Explore</button>
                    </div>
                    <div class="absolute right-[-5%] bottom-[-15%] w-[55%] group-hover:-translate-y-3 transition-transform duration-700">
                        <img alt="Power Device" class="w-full h-auto object-contain drop-shadow-2xl" src="assets/images/img_eca8edd1.jpg" />
                    </div>
                </div>
            </div>
            <!-- Right Column: Single Large Card -->
            <div class="rounded-[2rem] p-14 flex flex-col justify-between relative overflow-hidden group h-full" style="background: linear-gradient(to top right, #e0f2fe 0%, #f3e8ff 20%, #fce7f3 40%, #ffedd5 60%, #fef9c3 80%, #dcfce7 100%);">
                <div class="z-10 relative">
                    <h3 class="font-headline-md text-5xl mb-5 tracking-tight leading-[1.1]">Precision Touch Interface</h3>
                    <p class="text-base opacity-70 max-w-sm leading-relaxed">Revolutionary tactile feedback for the ultimate creative workflow.</p>
                </div>
                <div class="absolute right-[-5%] top-1/2 -translate-y-1/2 w-[70%] group-hover:scale-105 transition-transform duration-700">
                    <img alt="Interface" class="w-full h-auto object-contain drop-shadow-2xl" src="assets/images/img_4e39c1fa.jpg" />
                </div>
                <div class="z-10 relative mt-56">
                    <button class="bg-[#1a2c42] text-white px-10 py-4 rounded-full text-[12px] font-bold uppercase tracking-widest hover:bg-black transition-colors">Explore</button>
                </div>
            </div>
        </div>
        <!-- Brand Story Minimal Section -->
        <section class="mt-24 flex flex-col items-center text-center mx-auto w-full"><img alt="Designed for the Modern World" class="w-full h-auto rounded-[2rem]" src="assets/images/img_b731058b.jpg" /></section>
        <!-- Call to Action Banner -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mt-16">
            <div class="bg-[#fdf9d8] rounded-[2rem] p-16 flex flex-col justify-between min-h-[440px] relative overflow-hidden group">
                <span class="text-8xl font-black text-black/5 absolute -bottom-12 left-0 tracking-tighter uppercase transition-transform duration-1000 group-hover:scale-105 origin-bottom-left">ELECTRON</span>
                <div class="relative z-10">
                    <h2 class="text-6xl font-headline-md mb-14 tracking-tighter text-black uppercase">ELECTRON</h2>
                    <nav class="flex flex-wrap gap-x-10 gap-y-6">
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Home</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Shop</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Shipping</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Privacy</a>
                        <a class="text-[11px] font-bold uppercase tracking-widest text-black/80 hover:text-black transition-colors" href="#">Social Media</a>
                    </nav>
                </div>
                <p class="text-[10px] font-bold text-black/40 uppercase tracking-widest relative z-10 mt-16">QUALITY ELECTRONICS DELIVERED TO YOUR DOORSTEP</p>
            </div>
            <div class="bg-gradient-to-br from-stone-50 to-stone-200 rounded-[2rem] p-8 md:p-16 flex flex-col md:flex-row items-center text-center md:text-left justify-between min-h-[440px] relative overflow-hidden group">
                <div class="space-y-6 z-10 w-full md:w-1/2 flex flex-col items-center md:items-start relative h-full justify-center">
                    <span class="bg-gradient-to-r from-zinc-300 to-zinc-400 text-black px-5 py-2 rounded-full text-[10px] font-bold uppercase tracking-widest inline-block shadow-sm">Labs Program</span>
                    <h2 class="font-headline-md text-4xl md:text-5xl tracking-tight leading-[1.1]">Apply for the beta.<br />We'll handle the rest.</h2>
                    <button class="bg-gradient-to-r from-zinc-200 to-zinc-500 text-black px-12 py-4 rounded-full font-bold uppercase text-[12px] tracking-widest hover:from-zinc-300 hover:to-zinc-600 hover:-translate-y-1 shadow-md transition-all w-full max-w-xs mt-8">Apply Now</button>
                </div>
                <div class="w-full md:absolute md:right-[-25%] md:top-1/2 md:-translate-y-1/2 w-full md:w-[80%] max-w-[400px] md:max-w-none my-8 md:my-0 flex justify-center items-center">
                    <div class="absolute inset-0 bg-[radial-gradient(circle,_rgba(255,255,255,0.8)_0%,_transparent_70%)] blur-2xl z-0 hidden md:block"></div>
                    <img alt="MacBook Pro product image (half view)" class="w-full h-auto object-contain drop-shadow-2xl md:group-hover:-translate-x-1 md:group-hover:drop-shadow-[0_25px_25px_rgba(0,0,0,0.3)] transition-all duration-500 z-10 relative motion-reduce:transition-none motion-reduce:transform-none" src="assets/images/img_2a743d1d.jpg" />
                </div>
            </div>
        </div>
    </section>
</main>
<!-- Footer -->

<?php include 'includes/footer-home.php'; ?>