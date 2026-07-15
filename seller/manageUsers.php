<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['delete_user'])) {
    $user_id = intval($_POST['user_id']);

    if ($user_id == $_SESSION['user_id']) {
        $message = "You cannot delete your own account.";
    } else {
        $stmt = mysqli_prepare($conn, "DELETE FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);

        if (mysqli_stmt_execute($stmt)) {
            $message = "User deleted successfully.";
            log_activity($conn, "Delete User", "Deleted user ID: " . $user_id);
        } else {
            $message = "User cannot be deleted because the account may already be connected to orders or logs.";
        }
    }
}

$users = mysqli_query($conn, "
    SELECT *, TRIM(CONCAT_WS(' ', first_name, NULLIF(middle_name, ''), last_name)) AS complete_name
    FROM users
    ORDER BY user_id DESC
");

seller_header("Manage Users", "users");
?>

<div class="flex flex-col md:flex-row md:items-end md:justify-between gap-5 mb-8">
    <div>
        <p class="text-cyan-400 font-semibold">User Management</p>
        <h1 class="text-4xl font-black mt-2">Manage Users</h1>
        <p class="text-slate-400 mt-3">Add or modify buyer and admin accounts.</p>
    </div>

    <a href="addUser.php" class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition">
        Add User
    </a>
</div>

<?php if ($message !== "") { ?>
    <div class="mb-6 bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-5 py-4 rounded-2xl">
        <?= htmlspecialchars($message); ?>
    </div>
<?php } ?>

<div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-slate-300 text-sm">
                <tr>
                    <th class="p-4">ID</th>
                    <th class="p-4">Name</th>
                    <th class="p-4">Email</th>
                    <th class="p-4">Role</th>
                    <th class="p-4">Verified</th>
                    <th class="p-4 text-right">Actions</th>
                </tr>
            </thead>

            <tbody>
                <?php while ($user = mysqli_fetch_assoc($users)) { ?>
                    <tr class="border-t border-slate-800">
                        <td class="p-4 text-slate-400">#<?= $user['user_id']; ?></td>
                        <td class="p-4 font-black"><?= htmlspecialchars($user['complete_name']); ?></td>
                        <td class="p-4 text-slate-300"><?= htmlspecialchars($user['email']); ?></td>
                        <td class="p-4">
                            <span class="px-3 py-1 rounded-full text-sm font-bold <?= $user['role'] === 'admin' ? 'bg-cyan-400/10 text-cyan-400' : 'bg-slate-700 text-slate-300'; ?>">
                                <?= htmlspecialchars($user['role']); ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <span class="<?= $user['is_verified'] ? 'text-green-400' : 'text-red-400'; ?> font-bold">
                                <?= $user['is_verified'] ? 'Yes' : 'No'; ?>
                            </span>
                        </td>
                        <td class="p-4">
                            <div class="flex justify-end gap-2">
                                <a href="editUser.php?id=<?= $user['user_id']; ?>" class="bg-blue-500 hover:bg-blue-400 text-white px-4 py-2 rounded-xl font-bold transition">
                                    Edit
                                </a>

                                <form method="POST" onsubmit="return confirm('Delete this user?');">
                                    <input type="hidden" name="user_id" value="<?= $user['user_id']; ?>">
                                    <button type="submit" name="delete_user" class="bg-red-500 hover:bg-red-400 text-white px-4 py-2 rounded-xl font-bold transition">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php seller_footer(); ?>