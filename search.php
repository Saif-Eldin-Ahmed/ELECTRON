<?php
$page_title = "ELECTRON | Search Results";
$body_class = "bg-background font-body-md text-on-background selection:bg-secondary-container";
include 'includes/header.php';
?>

<!-- Top Navigation Bar (Updated to match SCREEN_16) -->
<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 dark:bg-black/90 backdrop-blur-md border-b border-slate-100 dark:border-zinc-900 h-24">
    <div class="max-w-[1440px] mx-auto h-full flex items-center justify-between px-12">
        <!-- Left Nav -->
        <div class="flex gap-8 items-center flex-1">
            <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Audio</a>
            <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Computing</a>
        </div>
        <!-- Brand Logo -->
        <div class="flex-shrink-0">
            <a class="text-3xl font-black tracking-tighter text-slate-950 dark:text-white font-['Space_Grotesk'] uppercase" href="#">ELECTRA</a>
        </div>
        <!-- Right Nav -->
        <div class="flex gap-8 items-center flex-1 justify-end">
            <div class="hidden lg:flex gap-8">
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Mobile</a>
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Support</a>
            </div>
            <div class="flex items-center gap-6 ml-4 text-on-background">
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
<main class="max-w-[1440px] mx-auto flex px-12 pt-32 pb-10 gap-gutter min-h-screen">
    <!-- Sidebar Filters -->
    <aside class="hidden lg:flex flex-col w-64 sticky top-32 h-fit bg-white dark:bg-zinc-950 p-6 rounded-lg border border-zinc-100">
        <div class="mb-8">
            <div class="flex justify-between items-center mb-1">
                <h2 class="font-headline-md text-sm font-bold text-zinc-900 uppercase tracking-widest">Filters</h2>
                <button class="text-xs font-medium text-zinc-400 hover:text-zinc-950 transition-colors">Clear All</button>
            </div>
            <p class="text-xs text-zinc-400">Refine Results</p>
        </div>
        <div class="space-y-8">
            <!-- Categories -->
            <div>
                <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900">Categories</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-5 h-5 rounded border-zinc-300 text-secondary focus:ring-secondary" type="checkbox" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">Smartphone</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-5 h-5 rounded border-zinc-300 text-secondary focus:ring-secondary" type="checkbox" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">Audio</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-5 h-5 rounded border-zinc-300 text-secondary focus:ring-secondary" type="checkbox" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">Computing</span>
                    </label>
                </div>
            </div>
            <!-- Price Range -->
            <div>
                <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900">Price Range</h3>
                <div class="space-y-4">
                    <div class="h-1 bg-zinc-100 rounded-full relative">
                        <div class="absolute left-1/4 right-1/4 h-full bg-zinc-950 rounded-full"></div>
                        <div class="absolute left-1/4 -top-1.5 w-4 h-4 bg-white border-2 border-zinc-950 rounded-full"></div>
                        <div class="absolute right-1/4 -top-1.5 w-4 h-4 bg-white border-2 border-zinc-950 rounded-full"></div>
                    </div>
                    <div class="flex justify-between gap-4">
                        <input class="w-1/2 text-xs border-zinc-200 rounded p-2 focus:ring-0 focus:border-zinc-950" placeholder="$0" type="text" />
                        <input class="w-1/2 text-xs border-zinc-200 rounded p-2 focus:ring-0 focus:border-zinc-950" placeholder="$2000" type="text" />
                    </div>
                </div>
            </div>
            <!-- Camera Spec -->
            <div>
                <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900">Camera Spec</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-5 h-5 border-zinc-300 text-secondary focus:ring-secondary" name="camera" type="radio" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">&gt;= 50MP</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-5 h-5 border-zinc-300 text-secondary focus:ring-secondary" name="camera" type="radio" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">&gt;= 108MP</span>
                    </label>
                </div>
            </div>
            <!-- Battery Life -->
            <div>
                <h3 class="font-label-bold text-xs uppercase mb-4 text-zinc-900">Battery Life</h3>
                <div class="space-y-3">
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input class="w-5 h-5 rounded border-zinc-300 text-secondary focus:ring-secondary" type="checkbox" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">&gt;= 4500mAh</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer group">
                        <input checked="" class="w-5 h-5 rounded border-zinc-300 text-secondary focus:ring-secondary" type="checkbox" />
                        <span class="text-sm text-zinc-500 group-hover:text-zinc-900 transition-colors">&gt;= 5000mAh</span>
                    </label>
                </div>
            </div>
        </div>
    </aside>
    <!-- Main Content -->
    <section class="flex-1">
        <!-- Summary & Sort -->
        <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-10 gap-4">
            <div>
                <h1 class="font-headline-lg text-2xl font-bold text-zinc-950 mb-1">
                    <?php
                    if (isset($_GET['q']) && $_GET['q'] !== '') {
                        echo 'Search Results';
                    } else {
                        echo 'Showing 1–6 of 38 results';
                    }
                    ?>
                </h1>
                <p class="text-zinc-500 text-sm">for "<?php echo isset($_GET['q']) && $_GET['q'] !== '' ? htmlspecialchars($_GET['q']) : 'Flagship Smartphones'; ?>"</p>
            </div>
            <div class="flex gap-4">
                <div class="relative inline-block text-left">
                    <select class="appearance-none bg-white border border-zinc-200 rounded px-4 py-2 pr-10 text-sm font-medium text-zinc-700 focus:outline-none focus:ring-1 focus:ring-zinc-950 focus:border-zinc-950 cursor-pointer">
                        <option>Best Quality</option>
                        <option>Popular</option>
                        <option>Newest</option>
                        <option>Price: Low to High</option>
                    </select>
                    <div class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-zinc-700">
                        <span class="material-symbols-outlined text-sm" data-icon="expand_more">expand_more</span>
                    </div>
                </div>
            </div>
        </div>
        <!-- Product Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-8">
            <!-- Product Card 1 -->
            <article class="bg-white rounded-lg overflow-hidden border border-zinc-100 product-card-shadow flex flex-col">
                <div class="relative aspect-square bg-zinc-50 group">
                    <img alt="Samsung Galaxy S23 Ultra" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" data-alt="Close-up of a sleek dark smartphone with multiple camera lenses on a white minimalist background, professional studio lighting" src="assets/images/img_f527df1f.jpg" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-zinc-950 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Best Overall Flagship</span>
                    </div>
                    <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-zinc-900 hover:bg-zinc-950 hover:text-white transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl" data-icon="favorite">favorite</span>
                        </button>
                        <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-zinc-900 hover:bg-zinc-950 hover:text-white transition-colors shadow-sm">
                            <span class="material-symbols-outlined text-xl" data-icon="shopping_cart">shopping_cart</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1">Smartphone</span>
                    <h3 class="font-headline-md text-lg font-bold text-zinc-950 mb-2">Samsung Galaxy S23 Ultra</h3>
                    <div class="flex items-center gap-1 mb-4">
                        <div class="flex text-zinc-950">
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star_half" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star_half</span>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">(124)</span>
                    </div>
                    <div class="mt-auto flex justify-between items-end">
                        <p class="text-xl font-black text-zinc-950">$1,199</p>
                        <button class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5 hover:text-zinc-500 hover:border-zinc-500 transition-colors">Details</button>
                    </div>
                </div>
            </article>
            <!-- Product Card 2 -->
            <article class="bg-white rounded-lg overflow-hidden border border-zinc-100 product-card-shadow flex flex-col">
                <div class="relative aspect-square bg-zinc-50 group">
                    <img alt="iPhone 15 Pro" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" data-alt="High-end titanium smartphone resting on its side, deep purple metallic finish, studio product photography" src="assets/images/img_67a547cb.jpg" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-zinc-950 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Best Video Recording</span>
                    </div>
                    <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-zinc-900 shadow-sm">
                            <span class="material-symbols-outlined text-xl" data-icon="favorite">favorite</span>
                        </button>
                        <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-zinc-900 shadow-sm">
                            <span class="material-symbols-outlined text-xl" data-icon="shopping_cart">shopping_cart</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1">Smartphone</span>
                    <h3 class="font-headline-md text-lg font-bold text-zinc-950 mb-2">iPhone 15 Pro</h3>
                    <div class="flex items-center gap-1 mb-4">
                        <div class="flex text-zinc-950">
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">(281)</span>
                    </div>
                    <div class="mt-auto flex justify-between items-end">
                        <p class="text-xl font-black text-zinc-950">$999</p>
                        <button class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5">Details</button>
                    </div>
                </div>
            </article>
            <!-- Product Card 3 -->
            <article class="bg-white rounded-lg overflow-hidden border border-zinc-100 product-card-shadow flex flex-col">
                <div class="relative aspect-square bg-zinc-50 group">
                    <img alt="Pixel 8 Pro" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" data-alt="Modern smartphone with horizontal camera bar, matte porcelain finish, clean composition on soft grey background" src="assets/images/img_ffd5365b.jpg" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-zinc-950 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Best AI Integration</span>
                    </div>
                    <div class="absolute top-4 right-4 flex flex-col gap-2 opacity-0 group-hover:opacity-100 transition-opacity">
                        <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-zinc-900 shadow-sm">
                            <span class="material-symbols-outlined text-xl" data-icon="favorite">favorite</span>
                        </button>
                        <button class="w-10 h-10 bg-white rounded-full flex items-center justify-center text-zinc-900 shadow-sm">
                            <span class="material-symbols-outlined text-xl" data-icon="shopping_cart">shopping_cart</span>
                        </button>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1">Smartphone</span>
                    <h3 class="font-headline-md text-lg font-bold text-zinc-950 mb-2">Google Pixel 8 Pro</h3>
                    <div class="flex items-center gap-1 mb-4">
                        <div class="flex text-zinc-950">
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star_outline">star</span>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">(89)</span>
                    </div>
                    <div class="mt-auto flex justify-between items-end">
                        <p class="text-xl font-black text-zinc-950">$899</p>
                        <button class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5">Details</button>
                    </div>
                </div>
            </article>
            <!-- Product Card 4 -->
            <article class="bg-white rounded-lg overflow-hidden border border-zinc-100 product-card-shadow flex flex-col">
                <div class="relative aspect-square bg-zinc-50 group">
                    <img alt="iPad Pro M2" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" data-alt="Slim tablet device with vibrant screen display, metallic gray body, minimalist tech aesthetic" src="assets/images/img_d5a9d377.jpg" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-zinc-950 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Top Rated</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1">Computing</span>
                    <h3 class="font-headline-md text-lg font-bold text-zinc-950 mb-2">iPad Pro M2 12.9"</h3>
                    <div class="flex items-center gap-1 mb-4">
                        <div class="flex text-zinc-950">
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">(412)</span>
                    </div>
                    <div class="mt-auto flex justify-between items-end">
                        <p class="text-xl font-black text-zinc-950">$1,099</p>
                        <button class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5">Details</button>
                    </div>
                </div>
            </article>
            <!-- Product Card 5 -->
            <article class="bg-white rounded-lg overflow-hidden border border-zinc-100 product-card-shadow flex flex-col">
                <div class="relative aspect-square bg-zinc-50 group">
                    <img alt="Sony WH-1000XM5" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" data-alt="Over-ear noise cancelling headphones in matte sand color, soft curves and modern design, neutral studio background" src="assets/images/img_dfa57ebe.jpg" />
                    <div class="absolute top-4 left-4">
                        <span class="bg-zinc-950 text-white text-[10px] font-bold uppercase tracking-widest px-3 py-1">Best Audio Quality</span>
                    </div>
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1">Audio</span>
                    <h3 class="font-headline-md text-lg font-bold text-zinc-950 mb-2">Sony WH-1000XM5</h3>
                    <div class="flex items-center gap-1 mb-4">
                        <div class="flex text-zinc-950">
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">(1.2k)</span>
                    </div>
                    <div class="mt-auto flex justify-between items-end">
                        <p class="text-xl font-black text-zinc-950">$399</p>
                        <button class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5">Details</button>
                    </div>
                </div>
            </article>
            <!-- Product Card 6 -->
            <article class="bg-white rounded-lg overflow-hidden border border-zinc-100 product-card-shadow flex flex-col">
                <div class="relative aspect-square bg-zinc-50 group">
                    <img alt="MacBook Pro" class="w-full h-full object-contain p-8 group-hover:scale-105 transition-transform duration-500" data-alt="MacBook Pro laptop open showing a vibrant wallpaper, brushed aluminum finish, isolated on white" src="assets/images/img_7e9aff25.jpg" />
                </div>
                <div class="p-6 flex flex-col flex-grow">
                    <span class="text-[10px] text-zinc-400 font-bold uppercase tracking-widest mb-1">Computing</span>
                    <h3 class="font-headline-md text-lg font-bold text-zinc-950 mb-2">MacBook Pro 14" M3</h3>
                    <div class="flex items-center gap-1 mb-4">
                        <div class="flex text-zinc-950">
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                            <span class="material-symbols-outlined text-sm" data-icon="star" data-weight="fill" style="font-variation-settings: 'FILL' 1;">star</span>
                        </div>
                        <span class="text-xs text-zinc-400 font-medium">(156)</span>
                    </div>
                    <div class="mt-auto flex justify-between items-end">
                        <p class="text-xl font-black text-zinc-950">$1,599</p>
                        <button class="text-xs font-bold uppercase tracking-widest border-b-2 border-zinc-950 pb-0.5">Details</button>
                    </div>
                </div>
            </article>
        </div>
        <!-- Pagination -->
        <div class="mt-20 flex justify-center items-center gap-4">
            <button class="w-12 h-12 flex items-center justify-center border border-zinc-200 rounded-full text-zinc-400 hover:border-zinc-950 hover:text-zinc-950 transition-colors">
                <span class="material-symbols-outlined" data-icon="chevron_left">chevron_left</span>
            </button>
            <div class="flex gap-2">
                <button class="w-12 h-12 flex items-center justify-center bg-zinc-950 text-white rounded-full font-label-bold">1</button>
                <button class="w-12 h-12 flex items-center justify-center text-zinc-500 hover:text-zinc-950 font-label-bold transition-colors">2</button>
                <button class="w-12 h-12 flex items-center justify-center text-zinc-500 hover:text-zinc-950 font-label-bold transition-colors">3</button>
                <span class="flex items-end px-2 text-zinc-300 font-bold">...</span>
                <button class="w-12 h-12 flex items-center justify-center text-zinc-500 hover:text-zinc-950 font-label-bold transition-colors">7</button>
            </div>
            <button class="w-12 h-12 flex items-center justify-center border border-zinc-200 rounded-full text-zinc-950 hover:border-zinc-950 transition-colors">
                <span class="material-symbols-outlined" data-icon="chevron_right">chevron_right</span>
            </button>
        </div>
    </section>
</main>
<!-- Footer -->

<?php include 'includes/footer.php'; ?>