<?php
require_once __DIR__ . '/../includes/databaseConnect.php';
require_once __DIR__ . '/../includes/functions.php';

require_admin();

$user_id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$message = "";
$message_type = "";

if ($user_id <= 0) {
    die("Invalid user ID.");
}

$stmt = mysqli_prepare(
    $conn,
    "SELECT * FROM users WHERE user_id = ? LIMIT 1"
);

if (!$stmt) {
    die("Unable to load user information.");
}

mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);

$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

mysqli_stmt_close($stmt);

if (!$user) {
    die("User not found.");
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = clean_input($_POST['first_name'] ?? '');
    $middle_name = clean_input($_POST['middle_name'] ?? '');
    $last_name = clean_input($_POST['last_name'] ?? '');
    $email = strtolower(clean_input($_POST['email'] ?? ''));

    $region = clean_input($_POST['region'] ?? '');
    $province = clean_input($_POST['province'] ?? '');
    $city_municipality = clean_input($_POST['city_municipality'] ?? '');
    $barangay = clean_input($_POST['barangay'] ?? '');

    $contact_number = clean_input($_POST['contact_number'] ?? '');
    $contact_number = preg_replace('/\D/', '', $contact_number);

    $role = clean_input($_POST['role'] ?? '');
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;
    $new_password = $_POST['new_password'] ?? '';

    $user['first_name'] = $first_name;
    $user['middle_name'] = $middle_name;
    $user['last_name'] = $last_name;
    $user['email'] = $email;
    $user['region'] = $region;
    $user['province'] = $province;
    $user['municipality'] = $city_municipality;
    $user['barangay'] = $barangay;
    $user['contact_number'] = $contact_number;
    $user['role'] = $role;
    $user['is_verified'] = $is_verified;

    if ($first_name === '' || $last_name === '') {
        $message = "Please enter the user's first name and last name.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $message_type = "error";
    } elseif (
        $region === '' ||
        $province === '' ||
        $city_municipality === '' ||
        $barangay === ''
    ) {
        $message = "Please complete the address information.";
        $message_type = "error";
    } elseif (!preg_match('/^09\d{9}$/', $contact_number)) {
        $message = "Contact number must start with 09 and contain exactly 11 digits.";
        $message_type = "error";
    } elseif (!in_array($role, ['buyer', 'admin'], true)) {
        $message = "Please select a valid account role.";
        $message_type = "error";
    } elseif ($new_password !== '' && strlen($new_password) < 6) {
        $message = "New password must be at least 6 characters.";
        $message_type = "error";
    } else {
        $email_check = mysqli_prepare(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email = ?
             AND user_id != ?
             LIMIT 1"
        );

        if (!$email_check) {
            $message = "Unable to validate the email address.";
            $message_type = "error";
        } else {
            mysqli_stmt_bind_param(
                $email_check,
                "si",
                $email,
                $user_id
            );

            mysqli_stmt_execute($email_check);
            mysqli_stmt_store_result($email_check);

            if (mysqli_stmt_num_rows($email_check) > 0) {
                $message = "That email address is already used by another account.";
                $message_type = "error";
            }

            mysqli_stmt_close($email_check);
        }

        if ($message === '') {
            if ($new_password !== '') {
                $hashed_password = password_hash(
                    $new_password,
                    PASSWORD_DEFAULT
                );

                $update = mysqli_prepare(
                    $conn,
                    "UPDATE users
                     SET
                        first_name = ?,
                        middle_name = ?,
                        last_name = ?,
                        email = ?,
                        password = ?,
                        region = ?,
                        province = ?,
                        municipality = ?,
                        barangay = ?,
                        contact_number = ?,
                        role = ?,
                        is_verified = ?
                     WHERE user_id = ?"
                );

                if ($update) {
                    mysqli_stmt_bind_param(
                        $update,
                        "sssssssssssii",
                        $first_name,
                        $middle_name,
                        $last_name,
                        $email,
                        $hashed_password,
                        $region,
                        $province,
                        $city_municipality,
                        $barangay,
                        $contact_number,
                        $role,
                        $is_verified,
                        $user_id
                    );
                }
            } else {
                $update = mysqli_prepare(
                    $conn,
                    "UPDATE users
                     SET
                        first_name = ?,
                        middle_name = ?,
                        last_name = ?,
                        email = ?,
                        region = ?,
                        province = ?,
                        municipality = ?,
                        barangay = ?,
                        contact_number = ?,
                        role = ?,
                        is_verified = ?
                     WHERE user_id = ?"
                );

                if ($update) {
                    mysqli_stmt_bind_param(
                        $update,
                        "ssssssssssii",
                        $first_name,
                        $middle_name,
                        $last_name,
                        $email,
                        $region,
                        $province,
                        $city_municipality,
                        $barangay,
                        $contact_number,
                        $role,
                        $is_verified,
                        $user_id
                    );
                }
            }

            if (!$update) {
                $message = "Unable to prepare the user update.";
                $message_type = "error";
            } elseif (mysqli_stmt_execute($update)) {
                $message = "User updated successfully.";
                $message_type = "success";

                log_activity(
                    $conn,
                    "Edit User",
                    "Updated user: " . $email
                );

                mysqli_stmt_close($update);

                $reload = mysqli_prepare(
                    $conn,
                    "SELECT * FROM users WHERE user_id = ? LIMIT 1"
                );

                if ($reload) {
                    mysqli_stmt_bind_param(
                        $reload,
                        "i",
                        $user_id
                    );

                    mysqli_stmt_execute($reload);

                    $reload_result =
                        mysqli_stmt_get_result($reload);

                    $updated_user =
                        mysqli_fetch_assoc($reload_result);

                    if ($updated_user) {
                        $user = $updated_user;
                    }

                    mysqli_stmt_close($reload);
                }
            } else {
                $message = "Failed to update the user.";
                $message_type = "error";

                mysqli_stmt_close($update);
            }
        }
    }
}

seller_header("Edit User", "users");
?>

<div class="mb-8">
    <p class="text-cyan-400 font-semibold">
        User Management
    </p>

    <h1 class="text-4xl font-black mt-2">
        Edit User
    </h1>

    <p class="text-slate-400 mt-3">
        Modify account details, address, verification status, and role.
    </p>
</div>

<div class="max-w-3xl bg-slate-900 border border-slate-800 rounded-3xl p-8">

    <?php if ($message !== "") { ?>
        <?php if ($message_type === "success") { ?>
            <div class="mb-6 bg-green-500/10 border border-green-500/30 text-green-300 px-5 py-4 rounded-2xl">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php } else { ?>
            <div class="mb-6 bg-red-500/10 border border-red-500/30 text-red-300 px-5 py-4 rounded-2xl">
                <?= htmlspecialchars($message); ?>
            </div>
        <?php } ?>
    <?php } ?>

    <form method="POST" id="edit_user_form" class="space-y-5">

        <div class="grid md:grid-cols-3 gap-4">

            <div>
                <label for="first_name" class="text-sm text-slate-300">
                    First Name
                </label>

                <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>"
                    required
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >
            </div>

            <div>
                <label for="middle_name" class="text-sm text-slate-300">
                    Middle Name
                </label>

                <input
                    type="text"
                    name="middle_name"
                    id="middle_name"
                    value="<?= htmlspecialchars($user['middle_name'] ?? ''); ?>"
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >
            </div>

            <div>
                <label for="last_name" class="text-sm text-slate-300">
                    Last Name
                </label>

                <input
                    type="text"
                    name="last_name"
                    id="last_name"
                    value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>"
                    required
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >
            </div>

        </div>

        <div>
            <label for="email" class="text-sm text-slate-300">
                Email Address
            </label>

            <input
                type="email"
                name="email"
                id="email"
                value="<?= htmlspecialchars($user['email'] ?? ''); ?>"
                required
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
        </div>

        <div>
            <label for="new_password" class="text-sm text-slate-300">
                New Password
            </label>

            <input
                type="password"
                name="new_password"
                id="new_password"
                minlength="6"
                placeholder="Leave blank to keep old password"
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
        </div>

        <div>
            <label for="region" class="text-sm text-slate-300">
                Region
            </label>

            <input
                type="text"
                name="region"
                id="region"
                value="<?= htmlspecialchars($user['region'] ?? ''); ?>"
                required
                placeholder="Example: NCR"
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
        </div>

        <div>
            <label for="province" class="text-sm text-slate-300">
                Province
            </label>

            <input
                type="text"
                name="province"
                id="province"
                value="<?= htmlspecialchars($user['province'] ?? ''); ?>"
                required
                placeholder="Enter province or district"
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
        </div>

        <div>
            <label for="city_municipality" class="text-sm text-slate-300">
                City / Municipality
            </label>

            <input
                type="text"
                name="city_municipality"
                id="city_municipality"
                value="<?= htmlspecialchars($user['municipality'] ?? ''); ?>"
                required
                placeholder="Enter city or municipality"
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
        </div>

        <div>
            <label for="barangay" class="text-sm text-slate-300">
                Barangay
            </label>

            <input
                type="text"
                name="barangay"
                id="barangay"
                value="<?= htmlspecialchars($user['barangay'] ?? ''); ?>"
                required
                placeholder="Enter barangay"
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
        </div>

        <div>
            <label for="contact_number" class="text-sm text-slate-300">
                Contact Number
            </label>

            <input
                type="text"
                name="contact_number"
                id="contact_number"
                value="<?= htmlspecialchars($user['contact_number'] ?? ''); ?>"
                required
                pattern="09[0-9]{9}"
                minlength="11"
                maxlength="11"
                inputmode="numeric"
                placeholder="09XXXXXXXXX"
                title="Enter an 11-digit Philippine mobile number starting with 09."
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >

            <p
                id="contact_error"
                class="hidden text-red-400 text-xs mt-2"
            >
                Contact number must start with 09 and contain exactly 11 digits.
            </p>
        </div>

        <div>
            <label for="role" class="text-sm text-slate-300">
                Role
            </label>

            <select
                name="role"
                id="role"
                required
                class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            >
                <option
                    value="buyer"
                    <?= ($user['role'] ?? '') === 'buyer' ? 'selected' : ''; ?>
                >
                    Buyer
                </option>

                <option
                    value="admin"
                    <?= ($user['role'] ?? '') === 'admin' ? 'selected' : ''; ?>
                >
                    Admin
                </option>
            </select>
        </div>

        <label class="flex items-center gap-3">
            <input
                type="checkbox"
                name="is_verified"
                value="1"
                <?= !empty($user['is_verified']) ? 'checked' : ''; ?>
            >

            <span class="text-slate-300">
                Account is verified
            </span>
        </label>

        <div class="flex flex-wrap gap-3">

            <button
                type="submit"
                class="bg-cyan-400 hover:bg-cyan-300 text-slate-950 px-6 py-3 rounded-xl font-black transition"
            >
                Update User
            </button>

            <a
                href="manageUsers.php"
                class="border border-slate-700 hover:border-cyan-400 px-6 py-3 rounded-xl font-bold transition"
            >
                Back
            </a>

        </div>

    </form>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const contactInput =
        document.getElementById('contact_number');

    const contactError =
        document.getElementById('contact_error');

    const form =
        document.getElementById('edit_user_form');

    contactInput.addEventListener('input', function () {
        contactInput.value =
            contactInput.value
                .replace(/\D/g, '')
                .substring(0, 11);

        const valid =
            /^09\d{9}$/.test(contactInput.value);

        contactError.classList.toggle(
            'hidden',
            contactInput.value === '' || valid
        );
    });

    form.addEventListener('submit', function (event) {
        if (!/^09\d{9}$/.test(contactInput.value)) {
            event.preventDefault();

            contactError.classList.remove('hidden');
            contactInput.focus();
        }
    });
});
</script>

<?php seller_footer(); ?>
