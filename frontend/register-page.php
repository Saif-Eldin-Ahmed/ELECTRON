<?php

$down = true;
$acc = true;
$reg = true;
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
        <form class="flex flex-col gap-stack-gap-sm" id="register-form" novalidate>
            <!-- Name Field -->
            <div class="flex flex-row gap-2">
                <!-- First Name -->
                <div class="flex flex-col gap-base w-full input-group" id="group-firstname">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="firstname">First Name*</label>
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="firstname" name="firstname" placeholder="John" type="text" required />
                    <span class="validation-msg" id="err-firstname"></span>
                </div>
                <!-- Last Name -->
                <div class="flex flex-col gap-base w-full input-group" id="group-lastname">
                    <label class="font-label-md text-label-md text-on-surface-variant" for="lastname">Last Name*</label>
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="lastname" name="lastname" placeholder="Doe" type="text" required />
                    <span class="validation-msg" id="err-lastname"></span>
                </div>
            </div>
            <!-- Email Field -->
            <div class="flex flex-col gap-base input-group" id="group-email">
                <label class="font-label-md text-label-md text-on-surface-variant" for="email">Email Address*</label>
                <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="email" name="email" placeholder="example123@email.com" type="email" required />
                <span class="validation-msg" id="err-email"></span>
            </div>
            <!-- Phone Field -->
            <div class="flex flex-col gap-base" id="group-phone">
                <label class="font-label-md text-label-md text-on-surface-variant" for="phone">Phone Number</label>
                <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="phone" name="phone" placeholder="+1234567890" type="tel" />
                <span class="validation-msg" id="err-phone"></span>
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
            </div>
            <!-- Password Strength -->
            <div class="strength-wrap" id="strength-wrap">
                <div class="strength-bars">
                    <div class="s-bar" id="s1"></div>
                    <div class="s-bar" id="s2"></div>
                    <div class="s-bar" id="s3"></div>
                    <div class="s-bar" id="s4"></div>
                </div>
                <span class="strength-label" id="strength-label">Strength</span>
            </div>
            <span class="validation-msg" id="err-password"></span>
            <!-- Confirm Password Field -->
            <div class="flex flex-col gap-base relative input-group" id="group-confirm">
                <label class="font-label-md text-label-md text-on-surface-variant" for="confirm">Confirm Password*</label>
                <div class="relative">
                    <input class="w-full h-input-height px-4 rounded-lg border border-outline-variant bg-[#F9FAFB] focus:border-primary focus:ring-1 focus:ring-primary outline-none transition-all font-body-md text-body-md" id="confirm" name="confirm" placeholder="XrkGFt067*" type="password" required />
                    <button class="eye-btn absolute right-4 top-1/2 -translate-y-1/2 text-on-surface-variant hover:text-primary transition-colors" type="button" id="toggle-confirm" aria-label="Toggle confirm password visibility">
                        <span class="material-symbols-outlined text-[20px]">visibility</span>
                    </button>
                </div>
                <span class="validation-msg" id="err-confirm"></span>
            </div>
            <!-- Terms Checkbox -->
            <div class="flex items-start gap-3 py-2" id="group-terms">
                <div class=" flex items-center h-5">
                    <input class="group-terms w-4 h-4 text-primary border-outline-variant rounded focus:ring-primary cursor-pointer" id="terms" name="terms" type="checkbox" />
                </div>
                <label class="font-helper-text text-helper-text text-on-secondary-container" for="terms">
                    I agree to the <a class="text-primary hover:underline" href="#">Terms of Service</a> and <a class="text-primary hover:underline" href="#">Privacy Policy</a>.
                </label>
            </div>
            <span class="validation-msg" id="err-terms"></span>
            <!-- Action Button -->
            <button class="w-full h-[48px] bg-primary text-on-primary font-button-text text-button-text rounded-lg hover:bg-primary-container transition-all active:scale-[0.98] mt-2" type="submit" id="submit-btn">
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
        <div class="flex justify-center pt-2">
            <p class="font-body-md text-body-md text-on-surface-variant">
                Already have an account? <a class="text-primary font-semibold hover:underline" href="#">Sign in</a>
            </p>
        </div>
    </div>
</main>
<!-- ========== Success Overlay ========== -->
<div class="success-overlay" id="success-overlay" aria-hidden="true">
    <div class="success-card">
        <h2>Registration Successful!</h2>
        <p>A new row was inserted into the <code>users</code> table.</p>

        <div class="record-box">
            <h3>Inserted Record</h3>
            <div class="flex justify-between"><span>ID</span><strong id="r-id">—</strong></div>
            <div class="flex justify-between"><span>Full Name</span><strong id="r-fullname">—</strong></div>
            <div class="flex justify-between"><span>Email</span><strong id="r-email">—</strong></div>
            <div class="flex justify-between"><span>Phone</span><strong id="r-phone">—</strong></div>
            <div class="flex justify-between"><span>Created At</span><strong id="r-created">—</strong></div>
        </div>

        <div class="success-actions">
            <a href="login-page.php" class="btn-outline yes">Log in</a>
        </div>
    </div>
</div>
<!-- Toast container -->
<div class="border-outline-variant toast-wrap" id="toast-wrap"></div>

<!-- Footer -->
<?php include 'includes/footer.php'; ?>