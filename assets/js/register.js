// ============================================================
//  script.js — ELECTRON-2 PHP PDO Registration
// ============================================================

// ---- DOM References -----------------------------------------
const form = document.getElementById('register-form');
const submitBtn = document.getElementById('submit-btn');

const firstnameEl = document.getElementById('firstname');
const lastnameEl = document.getElementById('lastname');
const emailEl = document.getElementById('email');
const phoneEl = document.getElementById('phone');
const passwordEl = document.getElementById('password');
const confirmEl = document.getElementById('confirm');
const termsEl = document.getElementById('terms');

const strengthWrap = document.getElementById('strength-wrap');
const strengthLabel = document.getElementById('strength-label');
const sBars = [document.getElementById('s1'), document.getElementById('s2'),
document.getElementById('s3'), document.getElementById('s4')];

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
const phoneRx = /^\+[1-9]\d{6,14}$/;

function validateFirstname() {
  const v = firstnameEl.value.trim();
  if (!v) { setError('firstname', 'First name is required.'); return false; }
  if (v.length < 2) { setError('firstname', 'At least 2 characters required.'); return false; }
  setError('firstname', null); return true;
}
function validateLastname() {
  const v = lastnameEl.value.trim();
  if (!v) { setError('lastname', 'Last name is required.'); return false; }
  if (v.length < 2) { setError('lastname', 'At least 2 characters required.'); return false; }
  setError('lastname', null); return true;
}
function validateEmail() {
  const v = emailEl.value.trim();
  if (!v) { setError('email', 'Email address is required.'); return false; }
  if (!emailRx.test(v)) { setError('email', 'Enter a valid email address.'); return false; }
  setError('email', null); return true;
}
function validatePhone() {
  const v = phoneEl.value.trim();
  if (!v) { setError('phone', null); return true; }
  if (!phoneRx.test(v)) { setError('phone', 'Phone number must start with + and country code, followed by digits (e.g., +1234567890).'); return false; }
  setError('phone', null); return true;
}
function validatePassword() {
  const v = passwordEl.value;
  if (!v) { setError('password', 'Password is required.'); strengthWrap.style.display = 'none'; return false; }
  if (v.length < 6) { setError('password', 'Minimum 6 characters required.'); return false; }
  setError('password', null); return true;
}
function validateConfirm() {
  const v = confirmEl.value;
  if (!v) { setError('confirm', 'Please confirm your password.'); return false; }
  if (v !== passwordEl.value) { setError('confirm', 'Passwords do not match.'); return false; }
  setError('confirm', null); return true;
}
function validateTerms() {
  if (!termsEl.checked) {
    setError('terms', 'You must agree to the terms and conditions.');
    return false;
  }
  setError('terms', null);
  return true;
}

// ---- Password Strength Meter --------------------------------
function updateStrength() {
  const v = passwordEl.value;
  if (!v) { strengthWrap.style.display = 'none'; return; }
  strengthWrap.style.display = 'block';

  let score = 0;
  if (v.length >= 6) score++;
  if (v.length >= 10) score++;
  if (/[A-Z]/.test(v) && /[a-z]/.test(v)) score++;
  if (/[0-9]/.test(v) && /[^A-Za-z0-9]/.test(v)) score++;

  const palettes = [
    null,
    { color: '#ef4444', label: 'Weak' },
    { color: '#f59e0b', label: 'Fair' },
    { color: '#eab308', label: 'Good' },
    { color: '#10b981', label: 'Strong' },
  ];

  sBars.forEach((b, i) => {
    b.style.backgroundColor = i < score && palettes[score]
      ? palettes[score].color
      : 'rgba(0, 0, 0, 0.2)';
  });

  if (palettes[score]) {
    strengthLabel.textContent = 'Strength: ' + palettes[score].label;
    strengthLabel.style.color = palettes[score].color;
  }
}

// ---- Eye Toggle Buttons -------------------------------------
function makeEyeToggle(btnId, inputEl) {
  document.getElementById(btnId).addEventListener('click', () => {
    const isText = inputEl.type === 'text';
    inputEl.type = isText ? 'password' : 'text';
  });
}
makeEyeToggle('toggle-pwd', passwordEl, 'eye-pwd');
makeEyeToggle('toggle-confirm', confirmEl, 'eye-confirm');

// ---- Live Validation Listeners ------------------------------
firstnameEl.addEventListener('blur', validateFirstname);
firstnameEl.addEventListener('input', () => { if (document.getElementById('err-firstname').classList.contains('show')) validateFirstname(); });

lastnameEl.addEventListener('blur', validateLastname);
lastnameEl.addEventListener('input', () => { if (document.getElementById('err-lastname').classList.contains('show')) validateLastname(); });

emailEl.addEventListener('blur', validateEmail);
emailEl.addEventListener('input', () => { if (document.getElementById('err-email').classList.contains('show')) validateEmail(); });

phoneEl.addEventListener('blur', validatePhone);
phoneEl.addEventListener('input', () => { if (document.getElementById('err-phone').classList.contains('show')) validatePhone(); });

passwordEl.addEventListener('input', () => {
  updateStrength();
  if (document.getElementById('err-password').classList.contains('show')) validatePassword();
  if (confirmEl.value) validateConfirm();
});

confirmEl.addEventListener('input', () => {
  if (document.getElementById('err-confirm').classList.contains('show') || confirmEl.value) validateConfirm();
});

// ---- Form Submission via fetch() -> register-db.php -----------
form.addEventListener('submit', async (e) => {
  e.preventDefault();

  // Run all validators
  const ok = [validateFirstname(), validateLastname(), validateEmail(), validatePhone(), validatePassword(), validateConfirm(), validateTerms()];
  if (ok.includes(false)) {
    toast('Please fix the highlighted errors.', 'danger');
    return;
  }


  // Build FormData to POST to register-db.php
  const data = new FormData();
  data.append('firstname', firstnameEl.value.trim());
  data.append('lastname', lastnameEl.value.trim());
  data.append('email', emailEl.value.trim());
  data.append('phone', phoneEl.value.trim());
  data.append('password', passwordEl.value);

  try {
    const res = await fetch('includes/register-db.php', { method: 'POST', body: data });
    const json = await res.json();

    if (json.success) {
      // Populate success overlay with returned record details
      document.getElementById('r-id').textContent = json.record.id;
      document.getElementById('r-fullname').textContent = json.record.firstname + " " + json.record.lastname;
      document.getElementById('r-email').textContent = json.record.email;
      document.getElementById('r-phone').textContent = json.record.phone;
      document.getElementById('r-created').textContent = json.record.created_at;

      successOverlay.classList.add('open');
      successOverlay.setAttribute('aria-hidden', 'false');
      toast('Account created successfully!', 'success');
      form.reset();
      strengthWrap.style.display = 'none';
    } else {
      toast(json.error || 'Registration failed. Please try again.', 'danger');
    }

  } catch (err) {
    toast('Network error: ' + err.message, 'danger');
  } finally {
    submitBtn.classList.remove('loading');
    submitBtn.disabled = false;
  }
});

// ---- Success Overlay Dismiss --------------------------------
btnAnother.addEventListener('click', () => {
  successOverlay.classList.remove('open');
  successOverlay.setAttribute('aria-hidden', 'true');
});

successOverlay.addEventListener('click', (e) => {
  if (e.target === successOverlay) {
    successOverlay.classList.remove('open');
    successOverlay.setAttribute('aria-hidden', 'true');
  }
});
