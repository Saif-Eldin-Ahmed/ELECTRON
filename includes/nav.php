<?php
// Fetch cart count for logged-in user
$nav_cart_count = 0;
if (isset($_SESSION['id'])) {
    try {
        $nav_pdo = getDBConnection();
        $nav_cart_stmt = $nav_pdo->prepare("SELECT SUM(quantity) FROM cart_items WHERE user_id = :uid");
        $nav_cart_stmt->execute([':uid' => $_SESSION['id']]);
        $nav_cart_count = intval($nav_cart_stmt->fetchColumn());
    } catch (Exception $e) {
        $nav_cart_count = 0;
    }
}
?>
<nav class="fixed top-0 left-0 w-full z-50 bg-white/90 dark:bg-black/90 backdrop-blur-md border-b border-slate-100 dark:border-zinc-900 h-24">
    <div class="max-w-[1440px] mx-auto h-full flex items-center justify-between px-6 lg:px-12">
        <!-- Left Nav -->
        <div class="hidden lg:flex gap-8 items-center flex-1">
            <div class="flex gap-8">
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Audio</a>
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Computing</a>
            </div>
        </div>
        <!-- Brand Logo -->
        <div class="flex-shrink-0">
            <a class="text-3xl font-black tracking-tighter text-slate-950 dark:text-white font-['Space_Grotesk'] uppercase" href="index.php">ELECTRON</a>
        </div>
        <!-- Right Nav (Desktop) -->
        <div class="hidden lg:flex gap-8 items-center flex-1 justify-end">
            <div class="flex gap-8">
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Mobile</a>
                <a class="font-label-bold text-label-bold text-slate-400 dark:text-zinc-500 hover:text-slate-950 dark:hover:text-white transition-all duration-300 uppercase tracking-widest" href="#">Support</a>
            </div>
            <div class="flex items-center gap-6 ml-4">
                <!-- Hover Search Bar -->
                <div class="relative flex items-center group py-2">
                    <button class="scale-95 active:opacity-80 transition-transform group-hover:opacity-0 group-focus-within:opacity-0 transition-opacity duration-200">
                        <span class="material-symbols-outlined" data-icon="search">search</span>
                    </button>
                    <div class="absolute rounded-[2rem] right-0 top-1/2 -translate-y-1/2 w-0 opacity-0 pointer-events-none group-hover:w-72 group-hover:opacity-100 group-hover:pointer-events-auto group-focus-within:w-72 group-focus-within:opacity-100 group-focus-within:pointer-events-auto transition-all duration-300 ease-out z-10 overflow-hidden">
                        <form action="search.php" method="GET" class="relative flex items-center w-72">
                            <input type="text" name="q" placeholder="SEARCH PRODUCTS..." class="w-full bg-white/95 dark:bg-zinc-950/95 backdrop-blur-lg border border-slate-200 dark:border-zinc-800 rounded-full py-1.5 pl-4 pr-10 text-[10px] font-bold uppercase tracking-widest text-slate-950 dark:text-white placeholder-slate-400 dark:placeholder-zinc-600 focus:outline-none focus:border-slate-400 focus:ring-0 transition-all shadow-[0_8px_30px_rgb(0,0,0,0.12)]" />
                            <button type="submit" class="absolute right-3.5 text-slate-950 dark:text-white hover:scale-110 transition-transform flex items-center">
                                <span class="material-symbols-outlined text-[18px]">search</span>
                            </button>
                        </form>
                    </div>
                </div>
                <button onclick="openCartDrawer()" class="scale-95 active:opacity-80 transition-transform relative">
                    <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
                    <span class="cart-badge-count absolute -top-1 -right-1 bg-primary text-white text-[8px] px-1 rounded-full <?php echo $nav_cart_count === 0 ? 'hidden' : ''; ?>"><?php echo $nav_cart_count; ?></span>
                </button>
                <div class="relative group cursor-pointer py-2 flex items-center" id="accDropdown">
                    <button class="scale-95 active:opacity-80 transition-transform flex items-center">
                        <img src="<?php echo isset($_SESSION['pro_img']) ? $_SESSION['pro_img'] : 'assets/proImgs/Default.jpg'; ?>" alt="<?php echo isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'account'; ?>'s profile image" class="w-10 h-10 rounded-full">
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="hide-menu absolute right-0 top-full mt-1 w-48 bg-white/95 backdrop-blur-lg border border-black/10 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] z-50 overflow-hidden py-2" id="accMenu">
                        <?php if (isset($_SESSION['id'])): ?>
                            <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 border-b border-slate-100 dark:border-zinc-900 mb-1">
                                Hi, <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                            </div>
                            <a href="profile.php" class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
                                <span class="material-symbols-outlined text-base">person</span>
                                Profile
                            </a>
                            <a href="func/logout.php" class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
                                <span class="material-symbols-outlined text-base">logout</span>
                                Log Out
                            </a>
                        <?php else: ?>
                            <a href="login-page.php" class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
                                <span class="material-symbols-outlined text-base">login</span>
                                Log In
                            </a>
                            <a href="register-page.php" class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
                                <span class="material-symbols-outlined text-base">person_add</span>
                                Sign Up
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Mobile Trigger Button (visible below lg) -->
        <div class="flex lg:hidden items-center gap-4">
            <button onclick="openCartDrawer()" class="scale-95 active:opacity-80 transition-transform relative">
                <span class="material-symbols-outlined" data-icon="shopping_cart">shopping_cart</span>
                <span class="cart-badge-count absolute -top-1 -right-1 bg-primary text-white text-[8px] px-1 rounded-full <?php echo $nav_cart_count === 0 ? 'hidden' : ''; ?>"><?php echo $nav_cart_count; ?></span>
            </button>
            <button onclick="openMobileNav()" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors" aria-label="Open menu">
                <span class="material-symbols-outlined text-2xl text-slate-950 dark:text-white">menu</span>
            </button>
        </div>
    </div>
</nav>

<!-- Mobile Nav Backdrop -->
<div id="mobileNavBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] hidden lg:hidden transition-opacity duration-300 opacity-0" onclick="closeMobileNav()"></div>

<!-- Mobile Nav Drawer -->
<div id="mobileNavDrawer" class="fixed top-0 right-0 h-full w-80 max-w-[85vw] bg-white dark:bg-zinc-950 border-l border-slate-100 dark:border-zinc-900 z-[70] overflow-y-auto transform translate-x-full transition-transform duration-300 ease-in-out lg:hidden">
    <!-- Drawer Header -->
    <div class="h-24 flex items-center justify-between px-6 border-b border-slate-100 dark:border-zinc-900">
        <span class="text-sm font-bold uppercase tracking-widest text-slate-950 dark:text-white">Menu</span>
        <button onclick="closeMobileNav()" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
            <span class="material-symbols-outlined text-slate-500 dark:text-zinc-400">close</span>
        </button>
    </div>

    <!-- Mobile Search -->
    <div class="px-6 py-5 border-b border-slate-100 dark:border-zinc-900">
        <form action="search.php" method="GET" class="relative flex items-center">
            <input type="text" name="q" placeholder="SEARCH PRODUCTS..." class="w-full bg-slate-50 dark:bg-zinc-900 border border-slate-200 dark:border-zinc-800 rounded-full py-2.5 pl-4 pr-10 text-[10px] font-bold uppercase tracking-widest text-slate-950 dark:text-white placeholder-slate-400 dark:placeholder-zinc-600 focus:outline-none focus:border-slate-400 dark:focus:border-zinc-600 focus:ring-0 transition-all" />
            <button type="submit" class="absolute right-3.5 text-slate-950 dark:text-white flex items-center">
                <span class="material-symbols-outlined text-[18px]">search</span>
            </button>
        </form>
    </div>

    <!-- Mobile Nav Links -->
    <div class="px-6 py-6 space-y-1 border-b border-slate-100 dark:border-zinc-900">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-3">Navigate</p>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-lg">headphones</span>
            Audio
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-lg">computer</span>
            Computing
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-lg">smartphone</span>
            Mobile
        </a>
        <a href="#" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
            <span class="material-symbols-outlined text-lg">support_agent</span>
            Support
        </a>
    </div>

    <!-- Mobile Account -->
    <div class="px-6 py-6">
        <p class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 mb-3">Account</p>
        <div class="space-y-1">
            <?php if (isset($_SESSION['id'])): ?>
                <div class="px-4 py-3 text-xs font-bold uppercase tracking-widest text-slate-950 dark:text-white">
                    Hi, <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                </div>
                <a href="profile.php" class="flex items-center gap-3 px-4 py-3 font-bold uppercase text-sm tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
                    <img class="w-5 h-5 rounded-full" src="<?php echo isset($_SESSION['pro_img']) ? $_SESSION['pro_img'] : 'assets/proImgs/Default.jpg'; ?>" alt="<?php echo isset($_SESSION['firstname']) ? htmlspecialchars($_SESSION['firstname']) : 'account'; ?>'s profile image">
                    Profile
                </a>
                <a href="func/logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">logout</span>
                    Log Out
                </a>
            <?php else: ?>
                <a href="login-page.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">login</span>
                    Log In
                </a>
                <a href="register-page.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">person_add</span>
                    Sign Up
                </a>
            <?php endif; ?>
        </div>
    </div>
</div>
<!-- Mobile Menu Trigger -->
<script>
    document.addEventListener('DOMContentLoaded', () => {

        const accDropdown = document.getElementById('accDropdown');
        const accMenu = document.getElementById('accMenu');

        if (accDropdown && accMenu) {
            accDropdown.addEventListener('click', (e) => {
                // Toggle the dropdown if the click did not happen inside the dropdown menu itself
                if (!accMenu.contains(e.target)) {
                    accMenu.classList.toggle('show-menu');
                    accMenu.classList.toggle('hide-menu');
                }
            });

            // Close the dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!accDropdown.contains(e.target)) {
                    accMenu.classList.remove('show-menu');
                    accMenu.classList.add('hide-menu');
                }
            });
        }
    });

    function openMobileNav() {
        // Close cart if open
        closeCartDrawer();
        const drawer = document.getElementById('mobileNavDrawer');
        const backdrop = document.getElementById('mobileNavBackdrop');
        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');
        backdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        });
        document.body.style.overflow = 'hidden';
    }

    function closeMobileNav() {
        const drawer = document.getElementById('mobileNavDrawer');
        const backdrop = document.getElementById('mobileNavBackdrop');
        drawer.classList.remove('translate-x-0');
        drawer.classList.add('translate-x-full');
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }
</script>

<?php include 'includes/cart-drawer.php'; ?>