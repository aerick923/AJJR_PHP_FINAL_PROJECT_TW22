<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

$message = "";
$message_type = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = clean_input($_POST['first_name'] ?? '');
    $middle_name = clean_input($_POST['middle_name'] ?? '');
    $last_name = clean_input($_POST['last_name'] ?? '');
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $region = clean_input($_POST['region'] ?? '');
    $province = clean_input($_POST['province'] ?? '');
    $municipality = clean_input($_POST['municipality'] ?? '');
    $barangay = clean_input($_POST['barangay'] ?? '');
    $contact_number = clean_input($_POST['contact_number'] ?? '');

    $contact_number = preg_replace('/\D/', '', $contact_number);

    if ($first_name === '' || $last_name === '') {
        $message = "Please enter your complete name.";
        $message_type = "error";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
        $message_type = "error";
    } elseif (strlen($password) < 6) {
        $message = "Password must be at least 6 characters.";
        $message_type = "error";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
        $message_type = "error";
    } elseif (
        $region === '' ||
        $province === '' ||
        $municipality === '' ||
        $barangay === ''
    ) {
        $message = "Please select your complete address.";
        $message_type = "error";
    } elseif (!preg_match('/^09\d{9}$/', $contact_number)) {
        $message = "Contact number must start with 09 and contain exactly 11 digits.";
        $message_type = "error";
    } else {
        $check = mysqli_prepare(
            $conn,
            "SELECT user_id
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if (!$check) {
            $message = "Registration could not be processed. Please try again.";
            $message_type = "error";
        } else {
            mysqli_stmt_bind_param($check, "s", $email);
            mysqli_stmt_execute($check);
            mysqli_stmt_store_result($check);

            if (mysqli_stmt_num_rows($check) > 0) {
                $message = "Email is already registered.";
                $message_type = "error";
            } else {
                $hashed_password = password_hash(
                    $password,
                    PASSWORD_DEFAULT
                );

                $role = "buyer";
                $is_verified = 1;

                $stmt = mysqli_prepare(
                    $conn,
                    "INSERT INTO users
                    (
                        first_name,
                        middle_name,
                        last_name,
                        email,
                        password,
                        region,
                        province,
                        municipality,
                        barangay,
                        contact_number,
                        role,
                        is_verified,
                        verification_token
                    )
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NULL)"
                );

                if (!$stmt) {
                    $message = "Registration could not be processed. Please try again.";
                    $message_type = "error";
                } else {
                    mysqli_stmt_bind_param(
                        $stmt,
                        "sssssssssssi",
                        $first_name,
                        $middle_name,
                        $last_name,
                        $email,
                        $hashed_password,
                        $region,
                        $province,
                        $municipality,
                        $barangay,
                        $contact_number,
                        $role,
                        $is_verified
                    );

                    if (mysqli_stmt_execute($stmt)) {
                        $message = "Registration successful. You can now log in using your new account.";
                        $message_type = "success";

                        $first_name = "";
                        $middle_name = "";
                        $last_name = "";
                        $email = "";
                        $region = "";
                        $province = "";
                        $municipality = "";
                        $barangay = "";
                        $contact_number = "";
                    } else {
                        $message = "Registration failed. Please try again.";
                        $message_type = "error";
                    }

                    mysqli_stmt_close($stmt);
                }
            }

            mysqli_stmt_close($check);
        }
    }
}
?>

<section class="max-w-6xl mx-auto px-6 py-14 grid lg:grid-cols-2 gap-10 items-center">

    <div>
        <p class="text-cyan-400 font-semibold">
            Create an Account
        </p>

        <h1 class="text-5xl font-black mt-3 leading-tight">
            Start building your dream PC today.
        </h1>

        <p class="text-slate-400 mt-5 text-lg">
            Register as a buyer to browse products, add items to your cart,
            and checkout your PC parts.
        </p>

        <div class="mt-8 bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <h2 class="font-bold text-xl mb-3">
                Buyer Benefits
            </h2>

            <ul class="space-y-3 text-slate-400">
                <li>✓ Browse categorized PC parts</li>
                <li>✓ Add products to your cart</li>
                <li>✓ Checkout with simple payment options</li>
                <li>✓ Secure passwords using PHP password hashing</li>
            </ul>
        </div>
    </div>

    <form
        method="POST"
        id="registration_form"
        class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl"
    >
        <h2 class="text-3xl font-black mb-2">
            Buyer Registration
        </h2>

        <p class="text-slate-400 mb-6">
            Fill out your information below.
        </p>

        <?php if ($message !== "") { ?>
            <?php if ($message_type === "success") { ?>
                <div class="mb-5 bg-green-500/10 border border-green-500/30 text-green-300 px-4 py-3 rounded-xl text-sm">
                    <?php echo htmlspecialchars($message); ?>

                    <div class="mt-3">
                        <a
                            href="login.php"
                            class="inline-block bg-green-400 hover:bg-green-300 text-slate-950 px-4 py-2 rounded-lg font-black transition"
                        >
                            Go to Login
                        </a>
                    </div>
                </div>
            <?php } else { ?>
                <div class="mb-5 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php } ?>
        <?php } ?>

        <div class="grid md:grid-cols-3 gap-4 mb-4">

            <div>
                <label for="first_name" class="text-sm text-slate-300">
                    First Name
                </label>

                <input
                    type="text"
                    name="first_name"
                    id="first_name"
                    required
                    value="<?php echo htmlspecialchars($first_name ?? ''); ?>"
                    placeholder="Juan"
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
                    value="<?php echo htmlspecialchars($middle_name ?? ''); ?>"
                    placeholder="Dela"
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
                    required
                    value="<?php echo htmlspecialchars($last_name ?? ''); ?>"
                    placeholder="Cruz"
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >
            </div>

        </div>

        <div class="grid md:grid-cols-2 gap-4">

            <div class="md:col-span-2">
                <label for="email" class="text-sm text-slate-300">
                    Email Address
                </label>

                <input
                    type="email"
                    name="email"
                    id="email"
                    required
                    autocomplete="email"
                    value="<?php echo htmlspecialchars($email ?? ''); ?>"
                    placeholder="example@email.com"
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >
            </div>

            <div>
                <label for="password" class="text-sm text-slate-300">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    id="password"
                    required
                    minlength="6"
                    autocomplete="new-password"
                    placeholder="Minimum 6 characters"
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >
            </div>

            <div>
                <label for="confirm_password" class="text-sm text-slate-300">
                    Confirm Password
                </label>

                <input
                    type="password"
                    name="confirm_password"
                    id="confirm_password"
                    required
                    minlength="6"
                    autocomplete="new-password"
                    placeholder="Repeat password"
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >

                <p
                    id="password_error"
                    class="hidden text-red-400 text-xs mt-1"
                >
                    Passwords do not match.
                </p>
            </div>

            <div class="md:col-span-2 space-y-4">

                <div>
                    <label for="region" class="text-sm text-slate-300">
                        Region
                    </label>

                    <select
                        id="region"
                        required
                        class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                    >
                        <option value="">Loading regions...</option>
                    </select>
                </div>

                <div>
                    <label for="province" class="text-sm text-slate-300">
                        Province
                    </label>

                    <select
                        id="province"
                        required
                        disabled
                        class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                    >
                        <option value="">Select a region first</option>
                    </select>
                </div>

                <div>
                    <label for="city" class="text-sm text-slate-300">
                        City / Municipality
                    </label>

                    <select
                        id="city"
                        required
                        disabled
                        class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                    >
                        <option value="">Select a province first</option>
                    </select>
                </div>

                <div>
                    <label for="barangay" class="text-sm text-slate-300">
                        Barangay
                    </label>

                    <select
                        id="barangay"
                        required
                        disabled
                        class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                    >
                        <option value="">Select a city first</option>
                    </select>
                </div>

                <input
                    type="hidden"
                    name="region"
                    id="region_hidden"
                    value="<?php echo htmlspecialchars($region ?? ''); ?>"
                >

                <input
                    type="hidden"
                    name="province"
                    id="province_hidden"
                    value="<?php echo htmlspecialchars($province ?? ''); ?>"
                >

                <input
                    type="hidden"
                    name="municipality"
                    id="municipality_hidden"
                    value="<?php echo htmlspecialchars($municipality ?? ''); ?>"
                >

                <input
                    type="hidden"
                    name="barangay"
                    id="barangay_hidden"
                    value="<?php echo htmlspecialchars($barangay ?? ''); ?>"
                >

                <p
                    id="location_error"
                    class="hidden text-red-400 text-sm"
                ></p>

            </div>

            <div class="md:col-span-2">
                <label for="contact_number" class="text-sm text-slate-300">
                    Contact Number
                </label>

                <input
                    type="text"
                    name="contact_number"
                    id="contact_number"
                    required
                    value="<?php echo htmlspecialchars($contact_number ?? ''); ?>"
                    placeholder="09XXXXXXXXX"
                    pattern="09[0-9]{9}"
                    minlength="11"
                    maxlength="11"
                    inputmode="numeric"
                    autocomplete="tel"
                    title="Enter an 11-digit Philippine mobile number starting with 09."
                    class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
                >

                <p
                    id="contact_error"
                    class="hidden text-red-400 text-xs mt-1"
                >
                    Contact number must start with 09 and contain exactly 11 digits.
                </p>
            </div>

        </div>

        <button
            type="submit"
            class="w-full mt-6 bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition"
        >
            Register Account
        </button>

        <p class="text-center text-sm text-slate-400 mt-5">
            Already have an account?

            <a
                href="login.php"
                class="text-cyan-400 font-bold"
            >
                Login here
            </a>
        </p>

    </form>

</section>

<script>
document.addEventListener('DOMContentLoaded', async function () {
    const form = document.getElementById('registration_form');

    const region = document.getElementById('region');
    const province = document.getElementById('province');
    const city = document.getElementById('city');
    const barangay = document.getElementById('barangay');

    const regionHidden = document.getElementById('region_hidden');
    const provinceHidden = document.getElementById('province_hidden');
    const municipalityHidden = document.getElementById('municipality_hidden');
    const barangayHidden = document.getElementById('barangay_hidden');

    const locationError = document.getElementById('location_error');

    const password = document.getElementById('password');
    const confirmPassword = document.getElementById('confirm_password');
    const passwordError = document.getElementById('password_error');

    const contact = document.getElementById('contact_number');
    const contactError = document.getElementById('contact_error');

    let locationData = {};

    function setOptions(select, placeholder, values = []) {
        select.innerHTML =
            `<option value="">${placeholder}</option>`;

        values.forEach(value => {
            const option = document.createElement('option');

            option.value = value.value;
            option.textContent = value.text;

            select.appendChild(option);
        });
    }

    function updateAddress() {
        if (
            region.value &&
            province.value &&
            city.value &&
            barangay.value
        ) {
            regionHidden.value =
                region.options[region.selectedIndex].text;

            provinceHidden.value =
                province.options[province.selectedIndex].text;

            municipalityHidden.value =
                city.options[city.selectedIndex].text;

            barangayHidden.value =
                barangay.options[barangay.selectedIndex].text;
        } else {
            regionHidden.value = '';
            provinceHidden.value = '';
            municipalityHidden.value = '';
            barangayHidden.value = '';
        }
    }

    try {
        const response = await fetch(
            './api/philippine_provinces_cities_municipalities_and_barangays_2017v3.1.json'
        );

        if (!response.ok) {
            throw new Error('Location file not found.');
        }

        locationData = await response.json();

        setOptions(
            region,
            'Select Region',
            Object.keys(locationData).map(code => ({
                value: code,
                text: locationData[code].region_name
            }))
        );

        region.disabled = false;
    } catch (error) {
        setOptions(
            region,
            'Locations failed to load'
        );

        region.disabled = true;

        locationError.textContent =
            'Failed to load Philippine locations.';

        locationError.classList.remove('hidden');
    }

    region.addEventListener('change', function () {
        setOptions(
            province,
            'Select Province'
        );

        setOptions(
            city,
            'Select a province first'
        );

        setOptions(
            barangay,
            'Select a city first'
        );

        province.disabled = true;
        city.disabled = true;
        barangay.disabled = true;

        if (!region.value) {
            updateAddress();
            return;
        }

        const provinces =
            locationData[region.value].province_list;

        setOptions(
            province,
            'Select Province',
            Object.keys(provinces).map(name => ({
                value: name,
                text: name
            }))
        );

        province.disabled = false;

        updateAddress();
    });

    province.addEventListener('change', function () {
        setOptions(
            city,
            'Select City / Municipality'
        );

        setOptions(
            barangay,
            'Select a city first'
        );

        city.disabled = true;
        barangay.disabled = true;

        if (!province.value) {
            updateAddress();
            return;
        }

        const cities =
            locationData[region.value]
                .province_list[province.value]
                .municipality_list;

        setOptions(
            city,
            'Select City / Municipality',
            Object.keys(cities).map(name => ({
                value: name,
                text: name
            }))
        );

        city.disabled = false;

        updateAddress();
    });

    city.addEventListener('change', function () {
        setOptions(
            barangay,
            'Select Barangay'
        );

        barangay.disabled = true;

        if (!city.value) {
            updateAddress();
            return;
        }

        const barangays =
            locationData[region.value]
                .province_list[province.value]
                .municipality_list[city.value]
                .barangay_list;

        setOptions(
            barangay,
            'Select Barangay',
            barangays.map(name => ({
                value: name,
                text: name
            }))
        );

        barangay.disabled = false;

        updateAddress();
    });

    barangay.addEventListener(
        'change',
        updateAddress
    );

    function validatePasswords() {
        const matches =
            password.value === confirmPassword.value;

        passwordError.classList.toggle(
            'hidden',
            matches || confirmPassword.value === ''
        );

        return matches;
    }

    password.addEventListener(
        'input',
        validatePasswords
    );

    confirmPassword.addEventListener(
        'input',
        validatePasswords
    );

    contact.addEventListener('keydown', function (event) {
        const allowedKeys = [
            'Backspace',
            'Delete',
            'ArrowLeft',
            'ArrowRight',
            'Tab',
            'Home',
            'End'
        ];

        if (
            allowedKeys.includes(event.key) ||
            event.ctrlKey ||
            event.metaKey
        ) {
            return;
        }

        if (!/^\d$/.test(event.key)) {
            event.preventDefault();
            return;
        }

        const start = contact.selectionStart;
        const end = contact.selectionEnd;
        const currentValue = contact.value;

        const nextValue =
            currentValue.substring(0, start) +
            event.key +
            currentValue.substring(end);

        if (nextValue.length > 11) {
            event.preventDefault();
            return;
        }

        if (
            nextValue.length >= 1 &&
            nextValue.charAt(0) !== '0'
        ) {
            event.preventDefault();
            return;
        }

        if (
            nextValue.length >= 2 &&
            nextValue.substring(0, 2) !== '09'
        ) {
            event.preventDefault();
        }
    });

    contact.addEventListener('input', function () {
        let value =
            contact.value.replace(/\D/g, '');

        if (
            value.length > 0 &&
            value.charAt(0) !== '0'
        ) {
            value = '';
        }

        if (
            value.length >= 2 &&
            value.substring(0, 2) !== '09'
        ) {
            value = '0';
        }

        contact.value =
            value.substring(0, 11);

        const valid =
            /^09\d{9}$/.test(contact.value);

        const empty =
            contact.value === '';

        contactError.classList.toggle(
            'hidden',
            empty || valid
        );

        contact.classList.toggle(
            'border-red-500',
            !empty && !valid
        );

        contact.classList.toggle(
            'border-slate-700',
            empty || valid
        );
    });

    form.addEventListener('submit', function (event) {
        updateAddress();

        if (!validatePasswords()) {
            event.preventDefault();

            passwordError.classList.remove('hidden');

            confirmPassword.focus();

            return;
        }

        if (
            !regionHidden.value ||
            !provinceHidden.value ||
            !municipalityHidden.value ||
            !barangayHidden.value
        ) {
            event.preventDefault();

            locationError.textContent =
                'Please select your region, province, city, and barangay.';

            locationError.classList.remove('hidden');

            return;
        }

        if (!/^09\d{9}$/.test(contact.value)) {
            event.preventDefault();

            contactError.classList.remove('hidden');
            contact.classList.add('border-red-500');

            contact.focus();
        }
    });
});
</script>

<?php include 'includes/footer.php'; ?>
