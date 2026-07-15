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
    $first_name = clean_input($_POST['first_name'] ?? '');
    $middle_name = clean_input($_POST['middle_name'] ?? '');
    $last_name = clean_input($_POST['last_name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $region = clean_input($_POST['region'] ?? '');
    $province = clean_input($_POST['province'] ?? '');
    $city_municipality = clean_input($_POST['city_municipality'] ?? '');
    $barangay = clean_input($_POST['barangay'] ?? '');
    $contact_number = clean_input($_POST['contact_number'] ?? '');
    $role = clean_input($_POST['role'] ?? '');
    $is_verified = isset($_POST['is_verified']) ? 1 : 0;
    $new_password = $_POST['new_password'] ?? '';

    if ($first_name === "" || $last_name === "") {
        $message = "Please enter a first name and last name.";
    } elseif ($region === "" || $province === "" || $city_municipality === "" || $barangay === "") {
        $message = "Please complete the address information.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } else {
        if ($new_password !== "") {
            if (strlen($new_password) < 6) {
                $message = "New password must be at least 6 characters.";
            } else {
                $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);

                $update = mysqli_prepare($conn, "
                    UPDATE users
                    SET first_name = ?, middle_name = ?, last_name = ?, email = ?, password = ?,
                        region = ?, province = ?, municipality = ?, barangay = ?,
                        contact_number = ?, role = ?, is_verified = ?
                    WHERE user_id = ?
                ");

                mysqli_stmt_bind_param(
                    $update,
                    "sssssssssssii",
                    $first_name, $middle_name, $last_name, $email, $hashed_password,
                    $region, $province, $city_municipality, $barangay,
                    $contact_number, $role, $is_verified, $user_id
                );
            }
        } else {
            $update = mysqli_prepare($conn, "
                UPDATE users
                SET first_name = ?, middle_name = ?, last_name = ?, email = ?,
                    region = ?, province = ?, municipality = ?, barangay = ?,
                    contact_number = ?, role = ?, is_verified = ?
                WHERE user_id = ?
            ");

            mysqli_stmt_bind_param(
                $update,
                "ssssssssssii",
                $first_name, $middle_name, $last_name, $email,
                $region, $province, $city_municipality, $barangay,
                $contact_number, $role, $is_verified, $user_id
            );
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
        <div class="grid md:grid-cols-3 gap-4">
            <div>
                <label class="text-sm text-slate-300">First Name</label>
                <input type="text" name="first_name" value="<?= htmlspecialchars($user['first_name'] ?? ''); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
            </div>
            <div>
                <label class="text-sm text-slate-300">Middle Name</label>
                <input type="text" name="middle_name" value="<?= htmlspecialchars($user['middle_name'] ?? ''); ?>" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
            </div>
            <div>
                <label class="text-sm text-slate-300">Last Name</label>
                <input type="text" name="last_name" value="<?= htmlspecialchars($user['last_name'] ?? ''); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
            </div>
        </div>

        <div>
            <label class="text-sm text-slate-300">Email Address</label>
            <input type="email" name="email" value="<?= htmlspecialchars($user['email']); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div>
            <label class="text-sm text-slate-300">New Password</label>
            <input type="password" name="new_password" placeholder="Leave blank to keep old password" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
        </div>

        <div class="space-y-3">
            <label class="text-sm text-slate-300">Region</label>
            <select name="region" id="region" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"></select>

            <label class="text-sm text-slate-300">Province</label>
            <select name="province" id="province" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"></select>

            <label class="text-sm text-slate-300">City / Municipality</label>
            <select name="city_municipality" id="city" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"></select>

            <label class="text-sm text-slate-300">Barangay</label>
            <select name="barangay" id="barangay" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"></select>
        </div>

        <div>
            <label class="text-sm text-slate-300">Contact Number</label>
            <input type="text" name="contact_number" value="<?= htmlspecialchars($user['contact_number'] ?? ''); ?>" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400">
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

<script>
let locationData = {};

async function loadLocations() {
    try {
        const res = await fetch('../api/location.php');
        if (!res.ok) {
            throw new Error('Location request failed.');
        }
        locationData = await res.json();

        const regionSelect = document.getElementById('region');
        const provinceSelect = document.getElementById('province');
        const citySelect = document.getElementById('city');
        const barangaySelect = document.getElementById('barangay');

        const oldRegion = <?= json_encode($user['region'] ?? ''); ?>;
        const oldProvince = <?= json_encode($user['province'] ?? ''); ?>;
        const oldCity = <?= json_encode($user['municipality'] ?? ''); ?>;
        const oldBarangay = <?= json_encode($user['barangay'] ?? ''); ?>;

        regionSelect.innerHTML = '<option value="" disabled selected>Select Region</option>';
        provinceSelect.innerHTML = '<option value="" disabled selected>Select Province</option>';
        citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
        barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';

        Object.keys(locationData).forEach(key => {
            const option = document.createElement('option');
            option.value = key;
            option.textContent = locationData[key]['region_name'];
            regionSelect.appendChild(option);
        });

        function loadProvinces(selectedProvince = '') {
            provinceSelect.innerHTML = '<option value="" disabled selected>Select Province</option>';
            citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
            barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';

            const selectedRegion = regionSelect.value;
            if (!selectedRegion || !locationData[selectedRegion]) return;

            const provinces = locationData[selectedRegion]['province_list'];
            Object.keys(provinces).forEach(provinceName => {
                const option = document.createElement('option');
                option.value = provinceName;
                option.textContent = provinceName;
                if (provinceName === selectedProvince) option.selected = true;
                provinceSelect.appendChild(option);
            });
        }

        function loadCities(selectedCity = '') {
            citySelect.innerHTML = '<option value="" disabled selected>Select City</option>';
            barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';

            const selectedRegion = regionSelect.value;
            const selectedProvince = provinceSelect.value;
            if (!selectedRegion || !selectedProvince) return;

            const municipalities = locationData[selectedRegion]['province_list'][selectedProvince]['municipality_list'];
            Object.keys(municipalities).forEach(cityName => {
                const option = document.createElement('option');
                option.value = cityName;
                option.textContent = cityName;
                if (cityName === selectedCity) option.selected = true;
                citySelect.appendChild(option);
            });
        }

        function loadBarangays(selectedBarangay = '') {
            barangaySelect.innerHTML = '<option value="" disabled selected>Select Barangay</option>';

            const selectedRegion = regionSelect.value;
            const selectedProvince = provinceSelect.value;
            const selectedCity = citySelect.value;
            if (!selectedRegion || !selectedProvince || !selectedCity) return;

            const barangays = locationData[selectedRegion]['province_list'][selectedProvince]['municipality_list'][selectedCity]['barangay_list'];
            barangays.forEach(barangayName => {
                const option = document.createElement('option');
                option.value = barangayName;
                option.textContent = barangayName;
                if (barangayName === selectedBarangay) option.selected = true;
                barangaySelect.appendChild(option);
            });
        }

        regionSelect.addEventListener('change', () => loadProvinces());
        provinceSelect.addEventListener('change', () => loadCities());
        citySelect.addEventListener('change', () => loadBarangays());

        if (oldRegion && locationData[oldRegion]) {
            regionSelect.value = oldRegion;
            loadProvinces(oldProvince);
            if (oldProvince) {
                provinceSelect.value = oldProvince;
                loadCities(oldCity);
            }
            if (oldCity) {
                citySelect.value = oldCity;
                loadBarangays(oldBarangay);
            }
            if (oldBarangay) {
                barangaySelect.value = oldBarangay;
            }
        }
    } catch (error) {
        console.error("Failed to load locations:", error);
        alert("Failed to load Philippine locations. Please try again.");
    }
}

loadLocations();
</script>

<?php seller_footer(); ?>