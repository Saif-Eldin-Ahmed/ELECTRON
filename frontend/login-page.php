<?php
session_start();

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit;
}

$down = true;
$acc = true;
$log = true;
$page_title = "ELECTRON | Login";
$body_class = "font-body-md text-on-surface bg-white";
include 'includes/header.php';
?>
<!-- Main Content -->
<main class="min-h-[85vh] flex-grow flex items-center justify-center px-container-padding py-stack-gap-lg">
    <div class="w-full max-w-[440px] bg-surface-container-lowest auth-card rounded-xl p-8 flex flex-col gap-stack-gap-md">
        <!-- Header Section -->
        <div class="flex flex-col gap-base">
            <h1 class="font-headline-lg text-headline-lg text-on-surface">Login Account</h1>
            <p class="font-body-md text-body-md text-on-surface-variant">Welcome back! Please enter your details to access your account.</p>
        </div>
        <!-- Form Section -->
        <form class="flex flex-col gap-stack-gap-sm" id="login-form" novalidate>
            <!-- Email Field -->
            <div class="flex flex-col gap-base input-group" id="group-email">
                <label class="font-label-md text-label-md text-on-surface-variant" for="email">Email Address*</label>
                <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="email" name="email" placeholder="example123@email.com" type="email" required />
                <span class="validation-msg" id="err-email"></span>
            </div>
            <!-- Password Field -->
            <div class="flex flex-col gap-base relative input-group" id="group-password">
                <label class="font-label-md text-label-md text-on-surface-variant" for="password">Password*</label>
                <div class="relative">
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="password" name="password" placeholder="XrkGFt067*" type="password" required />
                    <button class="eye-btn absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button" id="toggle-pwd">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <span class="validation-msg" id="err-password"></span>
            </div>
            <!-- Action Button -->
            <button class="w-full h-[48px] bg-primary text-on-primary font-button-text text-button-text rounded-lg hover:bg-primary-container transition-all active:scale-[0.98] mt-2" type="submit" id="submit-btn">
                Login
            </button>
        </form>
        <!-- Social/Divider (Optional aesthetic) -->
        <div class="relative flex items-center py-2">
            <div class="flex-grow border-t border-outline-variant"></div>
            <span class="flex-shrink mx-4 font-helper-text text-helper-text text-on-surface-variant">Or Log in with</span>
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
        <div class="flex justify-center pt-2">
            <p class="font-body-md text-body-md text-on-surface-variant">
                Don't have an account? <a class="text-primary font-semibold hover:underline" href="register-page.php">Sign up</a>
            </p>
        </div>
    </div>
</main>
<!-- ========== Success Overlay ========== -->
<div class="success-overlay" id="success-overlay" aria-hidden="true">
    <div class="success-card">
        <div class="record-box">
            <div class="record-row text-center">
                <h3>Are you</h3>
                <h1 id="r-fullname">—</h1>
            </div>
        </div>

        <div class="success-actions">
            <button class="btn-outline yes" id="btn-yes">Yes</button>
            <button class="btn-outline no" id="btn-no">No</button>
        </div>
    </div>
</div>
<!-- Toast container -->
<div class="border-outline-variant toast-wrap" id="toast-wrap"></div>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>