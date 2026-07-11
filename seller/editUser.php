<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$message = "";

$stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));

if (!$user) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $complete_name = clean_input($_POST['complete_name']);
    $email = clean_input($_POST['email']);
    $address = clean_input($_POST['address']);
    $contact_number = clean_input($_POST['contact_number']);
    $role = clean_input($_POST['role']);
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;
    $new_password = $_POST['new_password'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        if ($new_password !== "") {
            if (strlen($new_password) < 6) {
                $message = "New password must be at least 6 characters.";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update = mysqli_prepare($conn, "
                    UPDATE users
                    SET complete_name = ?, email = ?, password = ?, address = ?, contact_number = ?, role = ?, is_verified = ?
                    WHERE user_id = ?
                ");

                mysqli_stmt_bind_param($update, "ssssssii", $complete_name, $email, $hashed_password, $address, $contact_number, $role, $is_verified, $user_id);
            }
        } else {
            $update = mysqli_prepare($conn, "
                UPDATE users
                SET complete_name = ?, email = ?, address = ?, contact_number = ?, role = ?, is_verified = ?
                WHERE user_id = ?
            ");

            mysqli_stmt_bind_param($update, "sssssii", $complete_name, $email, $address, $contact_number, $role, $is_verified, $user_id);
        }

        if ($message === "") {
            if (mysqli_stmt_execute($update)) {
                $message = "User updated successfully.";
                log_activity($conn, "Edit User", "Updated user: " . $email);

                $stmt = mysqli_prepare($conn, "SELECT * FROM users WHERE user_id = ?");
                mysqli_stmt_bind_param($stmt, "i", $user_id);
                mysqli_stmt_execute($stmt);
                $user = mysqli_fetch_assoc(mysqli_stmt_get_result($stmt));
            } else {
                $message = "Failed to update user. Email may already be used.";
            }
        }
    }
}

seller_header("Edit User", "users");
?>

<div class="mb-8">
    <p class="text-cyan-400 font-semibold">User Management</p>
    <h1 class="text-4xl font-black mt-2">Edit User</h1>
    <p class="text-slate-400 mt-3">Modify account details and role.</p>
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
            <input type="text" name="complete_name" value="<?= htmlspecialchars($user['complete_name']); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">New Password</label>
            <input type="password" name="new_password" placeholder="Leave blank to keep old password" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Complete Address</label>
            <textarea name="address" required rows="3" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"><?= htmlspecialchars($user['address']); ?></textarea>
        </div>

        <div>
            <label class="text-sm text-slate-300">Contact Number</label>
            <input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number']); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">Role</label>
            <select name="role" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
                <option value="buyer" <?= $user['role'] === 'buyer' ? 'selected' : ''; ?>>Buyer</option>
                <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
            </select>
        </div>

        <label class="flex items-center gap-3">
            <input type="checkbox" name="is_verified" <?= $user['is_verified'] ? 'checked' : ''; ?>>
            <span class="text-slate-300">Account is verified</span>
        </label>

        <div class="flex gap-3">
            <button type="submit" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
                Update User
            </button>

            <a href="manageUsers.php" class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition">
                Back
            </a>
        </div>
    </form>
</div>

<?php seller_footer(); ?>
