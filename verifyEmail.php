<?php
include 'includes/db_connect.php';
include 'includes/header.php';

$message = "Invalid verification link.";

if (isset($_GET['token'])) {
    $token = $_GET['token'];

    $stmt = mysqli_prepare($conn, "UPDATE users SET is_verified = 1, verification_token = NULL WHERE verification_token = ?");
    mysqli_stmt_bind_param($stmt, "s", $token);
    mysqli_stmt_execute($stmt);

    if (mysqli_stmt_affected_rows($stmt) > 0) {
        $message = "Your email has been verified successfully. You may now login.";
    }
}
?>

<section class="max-w-xl mx-auto px-6 py-20 text-center">
    <div class="bg-slate-900 border border-slate-800 rounded-3xl p-10">
        <div class="w-20 h-20 mx-auto bg-cyan-400 rounded-full flex items-center justify-center text-slate-950 text-4xl font-black">
            ✓
        </div>

        <h1 class="text-3xl font-black mt-6">Email Verification</h1>
        <p class="text-slate-400 mt-4"><?php echo $message; ?></p>

        <a href="login.php" class="inline-block mt-8 bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-bold transition">
            Go to Login
        </a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>