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
                 <button class="scale-95 active:opacity-80 transition-transform"><span class="material-symbols-outlined" data-icon="person">person</span></button>
             </div>
         </div>
     </div>
 </nav>