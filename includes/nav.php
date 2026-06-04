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
                     <div class="absolute rounded-[2rem] right-0 top-1/2 -translate-y-1/2 w-0 opacity-0 pointer-events-none group-hover:w-60 lg:group-hover:w-72 group-hover:opacity-100 group-hover:pointer-events-auto group-focus-within:w-60 lg:group-focus-within:w-72 group-focus-within:opacity-100 group-focus-within:pointer-events-auto transition-all duration-300 ease-out z-10 overflow-hidden">
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
     </div>
 </nav>