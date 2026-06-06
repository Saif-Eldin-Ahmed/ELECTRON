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
                <div class="relative group cursor-pointer py-2 flex items-center">
                    <button class="scale-95 active:opacity-80 transition-transform flex items-center">
                        <span class="material-symbols-outlined" data-icon="person">person</span>
                    </button>

                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 top-full mt-1 w-48 bg-white/95 dark:bg-zinc-950/95 backdrop-blur-lg border border-slate-100 dark:border-zinc-900 rounded-2xl shadow-[0_8px_30px_rgb(0,0,0,0.12)] opacity-0 pointer-events-none group-hover:opacity-100 group-hover:pointer-events-auto transition-all duration-300 ease-out z-50 overflow-hidden transform scale-95 origin-top-right group-hover:scale-100 py-2">
                        <?php if (isset($_SESSION['id'])): ?>
                            <div class="px-4 py-2 text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500 border-b border-slate-100 dark:border-zinc-900 mb-1">
                                Hi, <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                            </div>
                            <a href="profile.php" class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
                                <span class="material-symbols-outlined text-base">person</span>
                                Profile
                            </a>
                            <a href="logout.php" class="flex items-center gap-3 px-4 py-2.5 text-[11px] font-bold uppercase tracking-widest text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 transition-colors">
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
                    <span class="material-symbols-outlined text-lg align-middle mr-2">account_circle</span>
                    Hi, <?php echo htmlspecialchars($_SESSION['firstname']); ?>
                </div>
                <a href="profile.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-slate-700 dark:text-zinc-300 hover:bg-slate-50 dark:hover:bg-zinc-900 rounded-lg transition-colors">
                    <span class="material-symbols-outlined text-lg">person</span>
                    Profile
                </a>
                <a href="logout.php" class="flex items-center gap-3 px-4 py-3 text-sm font-bold uppercase tracking-widest text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-950/20 rounded-lg transition-colors">
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

<!-- Cart Drawer Backdrop -->
<div id="cartBackdrop" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[60] hidden transition-opacity duration-300 opacity-0" onclick="closeCartDrawer()"></div>

<!-- Cart Drawer -->
<div id="cartDrawer" class="fixed top-0 right-0 h-full w-[440px] max-w-[90vw] bg-white dark:bg-zinc-950 border-l border-slate-100 dark:border-zinc-900 z-[70] flex flex-col transform translate-x-full transition-transform duration-300 ease-in-out">
    <!-- Drawer Header -->
    <div class="h-24 flex items-center justify-between px-6 border-b border-slate-100 dark:border-zinc-900 flex-shrink-0">
        <div class="flex items-center gap-3">
            <span class="material-symbols-outlined text-slate-950 dark:text-white">shopping_cart</span>
            <span class="text-xs font-bold uppercase tracking-widest text-slate-950 dark:text-white">Shopping Cart</span>
        </div>
        <button onclick="closeCartDrawer()" class="w-10 h-10 flex items-center justify-center rounded-lg hover:bg-slate-100 dark:hover:bg-zinc-900 transition-colors">
            <span class="material-symbols-outlined text-slate-500 dark:text-zinc-400">close</span>
        </button>
    </div>

    <!-- Drawer Content (Scrollable) -->
    <div id="cartDrawerContent" class="flex-grow overflow-y-auto p-6 space-y-4">
        <!-- Loaded via AJAX JS -->
    </div>

    <!-- Drawer Footer -->
    <div id="cartDrawerFooter" class="border-t border-slate-100 dark:border-zinc-900 p-6 bg-slate-50/50 dark:bg-zinc-900/20 flex-shrink-0 hidden">
        <div class="flex justify-between items-center mb-4">
            <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 dark:text-zinc-500">Subtotal</span>
            <span id="cartDrawerSubtotal" class="text-xl font-black text-slate-950 dark:text-white">$0.00</span>
        </div>
        <p class="text-[10px] text-slate-400 dark:text-zinc-500 mb-6 uppercase tracking-wider">Taxes and shipping calculated at checkout.</p>
        <button id="checkoutBtn" onclick="checkoutCart()" class="w-full py-4 bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 font-bold uppercase tracking-widest text-xs rounded hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors shadow-lg active:scale-[0.98] flex items-center justify-center gap-2">
            Proceed to Checkout
        </button>
    </div>
</div>

<script>
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

    // ============================================================
    //  Cart Drawer Interactivity & AJAX Controls
    // ============================================================

    function openCartDrawer() {
        // Close mobile nav if open
        closeMobileNav();
        const drawer = document.getElementById('cartDrawer');
        const backdrop = document.getElementById('cartBackdrop');
        drawer.classList.remove('translate-x-full');
        drawer.classList.add('translate-x-0');
        backdrop.classList.remove('hidden');
        requestAnimationFrame(() => {
            backdrop.classList.remove('opacity-0');
            backdrop.classList.add('opacity-100');
        });
        document.body.style.overflow = 'hidden';
        fetchCartItems();
    }

    function closeCartDrawer() {
        const drawer = document.getElementById('cartDrawer');
        const backdrop = document.getElementById('cartBackdrop');
        drawer.classList.remove('translate-x-0');
        drawer.classList.add('translate-x-full');
        backdrop.classList.remove('opacity-100');
        backdrop.classList.add('opacity-0');
        setTimeout(() => {
            backdrop.classList.add('hidden');
        }, 300);
        document.body.style.overflow = '';
    }

    async function fetchCartItems() {
        const container = document.getElementById('cartDrawerContent');
        const footer = document.getElementById('cartDrawerFooter');
        
        // Render Loading Spinner
        container.innerHTML = `
            <div class="flex flex-col items-center justify-center py-24 text-slate-400 dark:text-zinc-500">
                <span class="material-symbols-outlined text-4xl animate-spin mb-4">progress_activity</span>
                <span class="text-[10px] font-bold uppercase tracking-widest">Updating Cart...</span>
            </div>
        `;

        try {
            const res = await fetch('includes/get-cart.php');
            const data = await res.json();

            if (data.login_required) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-zinc-700 mb-4">account_circle</span>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-2">Please Log In</h3>
                        <p class="text-[10px] text-slate-500 dark:text-zinc-400 max-w-[240px] mb-6 leading-relaxed uppercase">You must be signed in to manage your shopping cart.</p>
                        <a href="login-page.php" class="px-6 py-3 bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 text-xs font-bold uppercase tracking-widest rounded hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors shadow-sm active:scale-95">Log In / Sign Up</a>
                    </div>
                `;
                footer.classList.add('hidden');
                updateNavbarBadges(0);
                return;
            }

            if (!data.success) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-20 text-center text-red-600 dark:text-red-400">
                        <span class="material-symbols-outlined text-5xl mb-4">error</span>
                        <h3 class="text-xs font-bold uppercase tracking-widest">Error Loading Cart</h3>
                        <p class="text-[10px] mt-1">${data.error || 'Unknown error occurred.'}</p>
                    </div>
                `;
                footer.classList.add('hidden');
                return;
            }

            // Update badges globally
            updateNavbarBadges(data.cart_count);

            if (!data.items || data.items.length === 0) {
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-24 text-center">
                        <span class="material-symbols-outlined text-5xl text-slate-300 dark:text-zinc-700 mb-4">shopping_basket</span>
                        <h3 class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-2">Your Cart is Empty</h3>
                        <p class="text-[10px] text-slate-500 dark:text-zinc-400 max-w-[220px] mb-6 uppercase tracking-wider">There are no items in your cart yet.</p>
                        <button onclick="closeCartDrawer()" class="px-6 py-3 border border-slate-200 dark:border-zinc-800 text-slate-900 dark:text-white text-xs font-bold uppercase tracking-widest rounded hover:bg-slate-50 dark:hover:bg-zinc-900 transition-colors active:scale-95">Continue Shopping</button>
                    </div>
                `;
                footer.classList.add('hidden');
            } else {
                footer.classList.remove('hidden');
                document.getElementById('cartDrawerSubtotal').textContent = formatCurrency(data.subtotal);

                let html = '';
                data.items.forEach(item => {
                    html += `
                        <div class="flex gap-4 py-4 border-b border-slate-100 dark:border-zinc-900 last:border-0 group relative transition-opacity duration-300" id="cart-item-${item.product_id}">
                            <!-- Product Image -->
                            <a href="product.php?id=${item.product_id}" onclick="closeCartDrawer()" class="w-20 h-20 bg-slate-50 dark:bg-zinc-900 rounded-lg flex-shrink-0 flex items-center justify-center overflow-hidden p-2 hover:scale-[1.03] transition-transform duration-300">
                                <img src="${item.image}" alt="${escapeHTML(item.name)}" class="w-full h-full object-contain">
                            </a>
                            
                            <!-- Details -->
                            <div class="flex-grow flex flex-col justify-between min-w-0">
                                <div>
                                    <a href="product.php?id=${item.product_id}" onclick="closeCartDrawer()" class="text-xs font-bold text-slate-900 dark:text-white uppercase tracking-widest truncate block hover:text-slate-500 dark:hover:text-zinc-400 transition-colors">${escapeHTML(item.name)}</a>
                                    <p class="text-xs font-black text-slate-950 dark:text-white mt-1">${formatCurrency(item.price)}</p>
                                </div>
                                
                                <!-- Quantity & Actions -->
                                <div class="flex items-center justify-between mt-2">
                                    <div class="flex items-center border border-slate-200 dark:border-zinc-800 rounded bg-white dark:bg-zinc-950 overflow-hidden">
                                        <button onclick="updateCartQty(${item.product_id}, 'decrement')" class="w-7 h-7 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-zinc-900 text-slate-500 dark:text-zinc-400 transition-colors active:scale-90" aria-label="Decrease quantity">
                                            <span class="material-symbols-outlined text-base">remove</span>
                                        </button>
                                        <span class="px-2 text-xs font-bold text-slate-900 dark:text-white min-w-[20px] text-center">${item.quantity}</span>
                                        <button onclick="updateCartQty(${item.product_id}, 'increment')" class="w-7 h-7 flex items-center justify-center hover:bg-slate-50 dark:hover:bg-zinc-900 text-slate-500 dark:text-zinc-400 transition-colors active:scale-90" aria-label="Increase quantity">
                                            <span class="material-symbols-outlined text-base">add</span>
                                        </button>
                                    </div>
                                    
                                    <button onclick="removeCartItem(${item.product_id})" class="text-slate-400 hover:text-red-500 dark:text-zinc-600 dark:hover:text-red-400 transition-colors flex items-center justify-center" aria-label="Remove item">
                                        <span class="material-symbols-outlined text-lg">delete</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    `;
                });
                container.innerHTML = html;
            }

        } catch (err) {
            container.innerHTML = `
                <div class="flex flex-col items-center justify-center py-20 text-center text-red-600 dark:text-red-400">
                    <span class="material-symbols-outlined text-5xl mb-4">cloud_off</span>
                    <h3 class="text-xs font-bold uppercase tracking-widest">Network Error</h3>
                    <p class="text-[10px] mt-1">Failed to connect to server. Please try again.</p>
                </div>
            `;
            footer.classList.add('hidden');
        }
    }

    async function updateCartQty(productId, action) {
        const itemRow = document.getElementById(`cart-item-${productId}`);
        if (itemRow) {
            itemRow.style.opacity = '0.5';
            itemRow.style.pointerEvents = 'none';
        }

        try {
            const formData = new FormData();
            formData.append('product_id', productId);
            formData.append('action', action);

            const res = await fetch('includes/update-cart-quantity.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                // Instantly re-fetch and render items in drawer
                await fetchCartItems();
            } else {
                showNavToast(data.error || 'Failed to update quantity.', 'error');
                if (itemRow) {
                    itemRow.style.opacity = '1';
                    itemRow.style.pointerEvents = 'auto';
                }
            }
        } catch (err) {
            showNavToast('Network error updating quantity.', 'error');
            if (itemRow) {
                itemRow.style.opacity = '1';
                itemRow.style.pointerEvents = 'auto';
            }
        }
    }

    async function removeCartItem(productId) {
        const itemRow = document.getElementById(`cart-item-${productId}`);
        if (itemRow) {
            itemRow.style.opacity = '0.3';
            itemRow.style.pointerEvents = 'none';
        }

        try {
            const formData = new FormData();
            formData.append('product_id', productId);

            const res = await fetch('includes/remove-cart-item.php', {
                method: 'POST',
                body: formData
            });
            const data = await res.json();

            if (data.success) {
                showNavToast(data.message || 'Removed from cart.', 'success');
                await fetchCartItems();
            } else {
                showNavToast(data.error || 'Failed to remove item.', 'error');
                if (itemRow) {
                    itemRow.style.opacity = '1';
                    itemRow.style.pointerEvents = 'auto';
                }
            }
        } catch (err) {
            showNavToast('Network error removing item.', 'error');
            if (itemRow) {
                itemRow.style.opacity = '1';
                itemRow.style.pointerEvents = 'auto';
            }
        }
    }

    async function checkoutCart() {
        const checkoutBtn = document.getElementById('checkoutBtn');
        const originalText = checkoutBtn.innerHTML;
        checkoutBtn.disabled = true;
        checkoutBtn.innerHTML = '<span class="material-symbols-outlined text-base animate-spin">progress_activity</span> Processing...';

        try {
            const res = await fetch('includes/checkout.php', {
                method: 'POST'
            });
            const data = await res.json();

            if (data.success) {
                // Empty the drawer and show successful checkout state
                const container = document.getElementById('cartDrawerContent');
                const footer = document.getElementById('cartDrawerFooter');
                
                container.innerHTML = `
                    <div class="flex flex-col items-center justify-center py-20 text-center">
                        <span class="material-symbols-outlined text-6xl text-emerald-600 dark:text-emerald-500 mb-6 animate-bounce">check_circle</span>
                        <h3 class="text-sm font-bold text-slate-900 dark:text-white uppercase tracking-widest mb-2">Order Placed!</h3>
                        <p class="text-[10px] text-slate-500 dark:text-zinc-400 max-w-[240px] mb-6 leading-relaxed uppercase">Thank you for placing your order. This mockup transaction was successfully simulated.</p>
                        <button onclick="closeCartDrawer()" class="px-6 py-3 bg-zinc-950 dark:bg-white text-white dark:text-zinc-950 text-xs font-bold uppercase tracking-widest rounded hover:bg-zinc-800 dark:hover:bg-zinc-100 transition-colors shadow-sm active:scale-95">Continue Shopping</button>
                    </div>
                `;
                footer.classList.add('hidden');
                updateNavbarBadges(0);
                showNavToast('Checkout successful!', 'success');
            } else {
                showNavToast(data.error || 'Checkout failed.', 'error');
                checkoutBtn.disabled = false;
                checkoutBtn.innerHTML = originalText;
            }
        } catch (err) {
            showNavToast('Network error during checkout.', 'error');
            checkoutBtn.disabled = false;
            checkoutBtn.innerHTML = originalText;
        }
    }

    function updateNavbarBadges(count) {
        document.querySelectorAll('.cart-badge-count').forEach(badge => {
            badge.textContent = count;
            if (count > 0) {
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        });
    }

    function formatCurrency(value) {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: 'USD'
        }).format(value);
    }

    function escapeHTML(str) {
        return str.replace(/[&<>'"]/g, 
            tag => ({
                '&': '&amp;',
                '<': '&lt;',
                '>': '&gt;',
                "'": '&#39;',
                '"': '&quot;'
            }[tag] || tag)
        );
    }

    function showNavToast(message, type) {
        const existing = document.getElementById('navToast');
        if (existing) existing.remove();

        const toast = document.createElement('div');
        toast.id = 'navToast';
        toast.className = 'fixed bottom-6 right-6 z-[100] flex items-center gap-3 px-6 py-4 rounded-2xl shadow-2xl text-xs font-bold uppercase tracking-widest transform translate-y-4 opacity-0 transition-all duration-300';

        if (type === 'success') {
            toast.classList.add('bg-emerald-600', 'text-white');
            toast.innerHTML = '<span class="material-symbols-outlined text-lg" style="font-variation-settings:\'FILL\' 1">check_circle</span>' + message;
        } else {
            toast.classList.add('bg-red-600', 'text-white');
            toast.innerHTML = '<span class="material-symbols-outlined text-lg" style="font-variation-settings:\'FILL\' 1">error</span>' + message;
        }

        document.body.appendChild(toast);

        // Animate in
        requestAnimationFrame(() => {
            toast.classList.remove('translate-y-4', 'opacity-0');
            toast.classList.add('translate-y-0', 'opacity-100');
        });

        // Animate out
        setTimeout(() => {
            toast.classList.remove('translate-y-0', 'opacity-100');
            toast.classList.add('translate-y-4', 'opacity-0');
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    }
</script>