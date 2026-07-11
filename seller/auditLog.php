<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$logs = mysqli_query($conn, "
    SELECT audit_logs.*, users.complete_name, users.email
    FROM audit_logs
    LEFT JOIN users ON audit_logs.user_id = users.user_id
    ORDER BY audit_logs.created_at DESC
");

seller_header("Audit Log", "audit");
?>

<div class="mb-8">
    <p class="text-cyan-400 font-semibold">Reports</p>
    <h1 class="text-4xl font-black mt-2">Audit Log Report</h1>
    <p class="text-slate-400 mt-3">View activities made by users currently logged in to the system.</p>
</div>

<div class="bg-slate-900 border border-slate-800 rounded-3xl overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-800 text-slate-300 text-sm">
                <tr>
                    <th class="p-4">Date / Time</th>
                    <th class="p-4">User</th>
                    <th class="p-4">Action</th>
                    <th class="p-4">Description</th>
                </tr>
            </thead>

            <tbody>
                <?php if ($logs && mysqli_num_rows($logs) > 0) { ?>
                    <?php while ($log = mysqli_fetch_assoc($logs)) { ?>
                        <tr class="border-t border-slate-800">
                            <td class="p-4 text-slate-400 whitespace-nowrap"><?= $log['created_at']; ?></td>
                            <td class="p-4">
                                <p class="font-black"><?= htmlspecialchars($log['complete_name'] ?? 'Unknown User'); ?></p>
                                <p class="text-sm text-slate-400"><?= htmlspecialchars($log['email'] ?? 'No email'); ?></p>
                            </td>
                            <td class="p-4">
                                <span class="px-3 py-1 rounded-full bg-cyan-400/10 text-cyan-400 text-sm font-bold">
                                    <?= htmlspecialchars($log['action']); ?>
                                </span>
                            </td>
                            <td class="p-4 text-slate-300"><?= htmlspecialchars($log['description']); ?></td>
                        </tr>
                    <?php } ?>
                <?php } else { ?>
                    <tr>
                        <td colspan="4" class="p-8 text-center text-slate-400">No audit logs found.</td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</div>

<?php seller_footer(); ?>
