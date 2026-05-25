<?php
$page_title = "ELECTRON | Search Results";
$body_class = "bg-background font-body-md text-on-background selection:bg-secondary-container";
include 'includes/header.php';
?>

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