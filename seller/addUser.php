<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $complete_name = clean_input($_POST['complete_name']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $address = clean_input($_POST['address']);
    $contact_number = clean_input($_POST['contact_number']);
    $role = clean_input($_POST['role']);
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
    } else {
        $check = mysqli_prepare($conn, "SELECT user_id FROM users WHERE email = ?");
        mysqli_stmt_bind_param($check, "s", $email);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {
            $message = "Email is already registered.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $stmt = mysqli_prepare($conn, "
                INSERT INTO users (complete_name, email, password, address, contact_number, role, is_verified)
                VALUES (?, ?, ?, ?, ?, ?, ?)
            ");

            mysqli_stmt_bind_param($stmt, "ssssssi", $complete_name, $email, $hashed_password, $address, $contact_number, $role, $is_verified);

            if (mysqli_stmt_execute($stmt)) {
                $message = "User added successfully.";
                log_activity($conn, "Add User", "Added user: " . $email);
            } else {
                $message = "Failed to add user.";
            }
        }
    }
}

seller_header("Add User", "users");
?>

<div class="mb-8">
    <p class="text-cyan-400 font-semibold">User Management</p>
    <h1 class="text-4xl font-black mt-2">Add User</h1>
    <p class="text-slate-400 mt-3">Create a buyer or admin account.</p>
</div>

<div class="max-w-3xl bg-slate-900 border border-slate-800 rounded-3xl p-8">
    <?php if ($message !== "") { ?>
        <div class="mb-6 bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-5 py-4 rounded-2xl">
            <?= htmlspecialchars($message); ?>
        </div>
    <?php } ?>

    <form method="POST" class="space-y-5">
        <div>
            <label class="text-sm text-slate-300">Complete Name</label>
            <input type="text" name="complete_name" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Email Address</label>
            <input type="email" name="email" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Password</label>
            <input type="password" name="password" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Complete Address</label>
            <textarea name="address" required rows="3" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"></textarea>
        </div>

        <div>
            <label class="text-sm text-slate-300">Contact Number</label>
            <input type="text" name="contact_number" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Role</label>
            <select name="role" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
                <option value="buyer">Buyer</option>
                <option value="admin">Admin</option>
            </select>
        </div>

        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_verified" checked>
            <span class="text-slate-300">Mark account as verified</span>
        </label>

        <div class="flex gap-3">
            <button type="submit" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
                Save User
            </button>

            <a href="manageUsers.php" class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition">
                Back
            </a>
        </div>
    </form>
</div>

<?php seller_footer(); ?>
