<?php

$down = true;
$nonav = true;
$page_title = "ELECTRON | Register";
$body_class = "font-body-md text-on-surface bg-white";
include 'includes/header.php';
?>
<!-- Main Content -->
<main class="min-h-[85vh] flex-grow flex items-center justify-center px-container-padding py-stack-gap-lg">
    <div class="w-full max-w-[440px] bg-surface-container-lowest auth-card rounded-xl p-8 flex flex-col gap-stack-gap-md">
        <!-- Header Section -->
        <div class="flex flex-col gap-base">
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Create Account</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Join us to start managing your projects efficiently.</p>
        </div>
        <!-- Form Section -->
        <form class="flex flex-col gap-stack-gap-sm" id="registrationForm">
            <!-- Name Field -->
            <div class="flex flex-row gap-2">
                <div class="flex flex-col gap-base w-full">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="name">First Name*</label>
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="name" name="name" placeholder="John" type="text" />
                </div>
                <div class="flex flex-col gap-base w-full">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="name">Last Name*</label>
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="name" name="name" placeholder="Doe" type="text" />
                </div>
            </div>
            <!-- Email Field -->
            <div class="flex flex-col gap-base">
                <label class="font-label-md text-label-md text-on-surface-variant" for="email">Email Address*</label>
                <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="email" name="email" placeholder="example123@email.com" type="email" />
            </div>
            <!-- Phone Field -->
            <div class="flex flex-col gap-base">
                <label class="font-label-md text-label-md text-on-surface-variant" for="phone">Phone Number</label>
                <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="phone" name="phone" placeholder="+1 234 567 890" type="tel" />
            </div>
            <!-- Password Field -->
            <div class="flex flex-col gap-base relative">
                <label class="font-label-md text-label-md text-on-surface-variant" for="password">Password*</label>
                <div class="relative">
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="password" name="password" placeholder="Create a password" type="password" />
                    <button class="absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <p class="font-helper-text text-helper-text text-on-surface-variant">Must be at least 8 characters long.</p>
            </div>
            <!-- Terms Checkbox -->
            <div class="flex items-start gap-3 py-2">
                <div class="flex items-center h-5">
                    <input class="w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary cursor-pointer" id="terms" name="terms" type="checkbox" />
                </div>
                <label class="font-helper-text text-helper-text text-on-secondary-container" for="terms">
                    I agree to the <a class="text-primary hover:underline" href="#">Terms of Service</a> and <a class="text-primary hover:underline" href="#">Privacy Policy</a>.
                </label>
            </div>
            <!-- Action Button -->
            <button class="w-full h-[48px] bg-primary text-on-primary font-button-text text-button-text rounded-lg hover:bg-primary-container transition-all active:scale-[0.98] mt-2" type="submit">
                Create Account
            </button>
        </form>
        <!-- Social/Divider (Optional aesthetic) -->
        <div class="relative flex items-center py-2">
            <div class="flex-grow border-t border-outline-variant"></div>
            <span class="flex-shrink mx-4 font-helper-text text-helper-text text-on-surface-variant">Or sign up with</span>
            <div class="flex-grow border-t border-outline-variant"></div>
        </div>
        <!-- Social Buttons -->
        <div class="grid grid-cols-2 gap-4">
            <button class="flex items-center justify-center h-input-height rounded-lg border border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low transition-colors font-label-md text-label-md">
                <span class="mr-2">G</span> Google
            </button>
            <button class="flex items-center justify-center h-input-height rounded-lg border border-outline-variant bg-surface-container-lowest hover:bg-surface-container-low transition-colors font-label-md text-label-md">
                <span class="mr-2">M</span> Microsoft
            </button>
        </div>
        <!-- Footer Link -->
        <div class="flex justify-center pt-2">
            <p class="font-body-md text-body-md text-on-surface-variant">
                Already have an account? <a class="text-primary font-semibold hover:underline" href="#">Sign in</a>
            </p>
        </div>
    </div>
</main>

<script>
    // Simple micro-interaction for password visibility
    const toggleBtn = document.querySelector('button[type="button"]');
    const passInput = document.getElementById('password');

    if (toggleBtn && passInput) {
        toggleBtn.addEventListener('click', () => {
            const type = passInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passInput.setAttribute('type', type);
            toggleBtn.querySelector('span').textContent = type === 'password' ? 'visibility' : 'visibility_off';
        });
    }

    // Form submission animation/feedback
    const form = document.getElementById('registrationForm');
    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const btn = form.querySelector('button[type="submit"]');
        const originalText = btn.textContent;
        btn.innerHTML = '<span class="flex items-center justify-center"><svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Creating...</span>';
        btn.disabled = true;

        setTimeout(() => {
            btn.innerHTML = originalText;
            btn.disabled = false;
            alert('Account creation simulated!');
        }, 1500);
    });
</script>
<!-- Footer -->
<?php include 'includes/footer.php'; ?>