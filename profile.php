<?php
// ============================================================
//  profile.php — User Profile Page
// ============================================================

session_start();

// Redirect to login if user is not authenticated
if (!isset($_SESSION['id'])) {
    header("Location: login-page.php");
    exit;
}

require_once 'includes/config.php';

try {
    $pdo = getDBConnection();
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE `id` = :id LIMIT 1");
    $stmt->execute([':id' => $_SESSION['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        // User not found in database, clean session and redirect
        session_destroy();
        header("Location: login-page.php");
        exit;
    }
} catch (PDOException $e) {
    // Fallback to session variables if database fails
    $user = $_SESSION;
}

$page_title = "ELECTRON | Profile";
$body_class = "bg-gradient-to-br from-stone-50 via-zinc-100 to-indigo-50/30 min-h-screen font-body-md text-on-surface";
include 'includes/header.php';
?>

<main class="max-w-4xl mx-auto px-6 pt-36 pb-20 flex justify-center items-center min-h-[90vh]">
    <div class="w-full bg-white/65 backdrop-blur-xl border border-white/80 shadow-[0_20px_50px_rgba(0,0,0,0.05)] rounded-[2.5rem] p-8 md:p-12 lg:p-16 flex flex-col md:flex-row gap-10 md:gap-16 items-center md:items-start relative overflow-hidden">
        <!-- Background decorative blurs -->
        <div class="absolute -top-24 -left-24 w-48 h-48 bg-blue-200/20 rounded-full blur-3xl pointer-events-none"></div>
        <div class="absolute -bottom-24 -right-24 w-48 h-48 bg-purple-200/20 rounded-full blur-3xl pointer-events-none"></div>

        <!-- Left Column: Avatar & Quick Actions -->
        <div class="flex flex-col items-center gap-6 text-center md:w-1/3 z-10">
            <div class="w-32 h-32 rounded-full bg-gradient-to-tr from-slate-900 via-zinc-800 to-slate-700 p-[3px] shadow-lg flex items-center justify-center">
                <div class="w-full h-full rounded-full bg-white flex items-center justify-center overflow-hidden">
                    <img class="w-full h-full object-cover" src="<?php echo isset($user['pro_img']) ? $user['pro_img'] : 'assets/proImgs/Default.jpg'; ?>" alt="<?php echo isset($user['firstname']) ? htmlspecialchars($user['firstname']) : 'account'; ?>'s profile image">
                </div>
            </div>
            <div>
                <h2 class="text-2xl font-black tracking-tight text-slate-950 uppercase">
                    <?php echo htmlspecialchars(($user['firstname'] ?? '') . ' ' . ($user['lastname'] ?? '')); ?>
                </h2>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-widest mt-1">Customer Profile</p>
            </div>

            <div class="flex flex-col gap-3 w-full mt-4">
                <a href="index.php" class="w-full bg-slate-950 hover:bg-slate-800 text-white font-bold uppercase text-[11px] tracking-widest py-3.5 px-6 rounded-full shadow-sm hover:shadow transition-all active:scale-[0.98] text-center">
                    Continue Shopping
                </a>
                <a href="logout.php" class="w-full border border-red-200 hover:border-red-300 text-red-600 hover:bg-red-50/50 font-bold uppercase text-[11px] tracking-widest py-3.5 px-6 rounded-full transition-all active:scale-[0.98] text-center">
                    Log Out
                </a>
            </div>
        </div>

        <!-- Right Column: User Details Grid -->
        <div class="flex-grow w-full md:w-2/3 space-y-8 z-10">
            <div>
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-6 pb-2 border-b border-slate-100">Personal Information</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">First Name</span>
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($user['firstname'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Last Name</span>
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($user['lastname'] ?? 'N/A'); ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Email Address</span>
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($user['email'] ?? 'N/A'); ?></p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Phone Number</span>
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($user['phone'] ? $user['phone'] : 'N/A'); ?></p>
                    </div>
                </div>
            </div>

            <div>
                <h3 class="text-[11px] font-bold uppercase tracking-widest text-slate-400 mb-6 pb-2 border-b border-slate-100">Security & Activity</h3>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-8 gap-y-6">
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Member Since</span>
                        <p class="text-sm font-semibold text-slate-900">
                            <?php
                            if (isset($user['created_at'])) {
                                echo htmlspecialchars(date('F j, Y', strtotime($user['created_at'])));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>
                    </div>
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Last Login IP</span>
                        <p class="text-sm font-semibold text-slate-900"><?php echo htmlspecialchars($user['last_login_ip'] ? $user['last_login_ip'] : 'N/A'); ?></p>
                    </div>
                    <div class="sm:col-span-2">
                        <span class="text-[10px] font-bold uppercase tracking-widest text-slate-400 block mb-1">Last Login Time</span>
                        <p class="text-sm font-semibold text-slate-900">
                            <?php
                            if (isset($user['last_login_at'])) {
                                echo htmlspecialchars(date('F j, Y, g:i a', strtotime($user['last_login_at'])));
                            } else {
                                echo 'N/A';
                            }
                            ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>