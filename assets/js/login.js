// ============================================================
//  Login.js — ELECTRON-2 PHP PDO User Login
// ============================================================

// ---- DOM References -----------------------------------------
const form = document.getElementById('login-form');
const submitBtn = document.getElementById('submit-btn');

const emailEl = document.getElementById('email');
const passwordEl = document.getElementById('password');

const successOverlay = document.getElementById('success-overlay');
const btnAnother = document.getElementById('btn-another');
const toastWrap = document.getElementById('toast-wrap');

// ---- Helpers ------------------------------------------------


/** Show or clear an error message under a field */
function setError(fieldId, message) {
    const group = document.getElementById('group-' + fieldId);
    const msg = document.getElementById('err-' + fieldId);
    if (message) {
        msg.textContent = message;
        msg.classList.add('show');
        group.classList.add('has-error');
    } else {
        msg.textContent = '';
        msg.classList.remove('show');
        group.classList.remove('has-error');
    }
}

/** Emit a toast notification */
function toast(message, type = 'success') {
    const t = document.createElement('div');
    t.className = `toast is-${type}`;
    t.innerHTML = `<span class="toast-body">${message}</span>`;
    toastWrap.appendChild(t);

    setTimeout(() => t.remove(), 5000);
}

// ---- Validators ---------------------------------------------
const emailRx = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

function validateEmail() {
    const v = emailEl.value.trim();
    if (!v) { setError('email', 'Email address is required.'); return false; }
    if (!emailRx.test(v)) { setError('email', 'Enter a valid email address.'); return false; }
    setError('email', null); return true;
}

function validatePassword() {
    const v = passwordEl.value;
    if (!v) { setError('password', 'Password is required.'); return false; }
    setError('password', null); return true;
}

// ---- Eye Toggle Buttons -------------------------------------
function makeEyeToggle(btnId, inputEl) {
    document.getElementById(btnId).addEventListener('click', () => {
        const isText = inputEl.type === 'text';
        inputEl.type = isText ? 'password' : 'text';
    });
}
makeEyeToggle('toggle-pwd', passwordEl);

// ---- Live Validation Listeners ------------------------------
emailEl.addEventListener('blur', validateEmail);
emailEl.addEventListener('input', () => { if (document.getElementById('err-email').classList.contains('show')) validateEmail(); });

passwordEl.addEventListener('blur', validatePassword);
passwordEl.addEventListener('input', () => { if (document.getElementById('err-password').classList.contains('show')) validatePassword(); });


// ---- Form Submission --------------------------------------

form.addEventListener('submit', async (e) => {
    e.preventDefault();

    // Run all validators
    const ok = [validateEmail(), validatePassword()];
    if (ok.includes(false)) {
        toast('Please fix the highlighted errors.', 'danger');
        return;
    }

    // Build FormData to POST to register-db.php
    const data = new FormData();

    data.append('email', emailEl.value.trim());
    data.append('password', passwordEl.value);

    try {
        const res = await fetch('includes/login-db.php', { method: 'POST', body: data });
        const json = await res.json();

        if (json.success) {
            // Populate success overlay with returned user details
            document.getElementById('r-id').textContent = json.user.id;
            document.getElementById('r-fullname').textContent = json.user.firstname + " " + json.user.lastname;
            document.getElementById('r-email').textContent = json.user.email;
            document.getElementById('r-phone').textContent = json.user.phone;
            document.getElementById('r-created').textContent = json.user.created_at;

            successOverlay.classList.add('open');
            successOverlay.setAttribute('aria-hidden', 'false');
            toast('Logged in Successfully!', 'success');
        } else {
            toast(json.error || 'Logging in failed. Please try again.', 'danger');
        }
    } catch { err } {
        toast('Network error: ' + err.message, 'danger');
    }
});