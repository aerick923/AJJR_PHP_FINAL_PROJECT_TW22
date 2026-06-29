<?php
include 'includes/databaseConnect.php';
include 'includes/header.php';

$message = "";
$dev_link = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $complete_name = clean_input($_POST['complete_name']);
    $email = clean_input($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];
    $address = clean_input($_POST['address']);
    $contact_number = clean_input($_POST['contact_number']);

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif ($password !== $confirm_password) {
        $message = "Passwords do not match.";
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
            $token = bin2hex(random_bytes(16));

            $stmt = mysqli_prepare($conn, "INSERT INTO users (complete_name, email, password, address, contact_number, verification_token) VALUES (?, ?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($stmt, "ssssss", $complete_name, $email, $hashed_password, $address, $contact_number, $token);

            if (mysqli_stmt_execute($stmt)) {
                $verify_link = "http://localhost/PC_PARTS/verifyEmail.php?token=" . $token;

                $subject = "AJJR PC Parts Email Verification";
                $body = "Hello $complete_name,\n\nClick this link to verify your account:\n$verify_link";
                $headers = "From: no-reply@ajjrpcparts.com";

                @mail($email, $subject, $body, $headers);

                $message = "Registration successful. Please verify your email before logging in.";
                $dev_link = $verify_link;
            } else {
                $message = "Registration failed. Please try again.";
            }
        }
    }
}
?>

<section class="max-w-6xl mx-auto px-6 py-14 grid lg:grid-cols-2 gap-10 items-center">

    <div>
        <p class="text-cyan-400 font-semibold">Create an Account</p>
        <h1 class="text-5xl font-black mt-3 leading-tight">Start building your dream PC today.</h1>
        <p class="text-slate-400 mt-5 text-lg">
            Register as a buyer to browse products, add items to cart, and checkout your PC parts.
        </p>

        <div class="mt-8 bg-slate-900 border border-slate-800 rounded-3xl p-6">
            <h2 class="font-bold text-xl mb-3">Buyer Benefits</h2>
            <ul class="space-y-3 text-slate-400">
                <li>✓ Browse categorized PC parts</li>
                <li>✓ Add products to cart</li>
                <li>✓ Checkout with simple payment options</li>
                <li>✓ Secure password using PHP hashing</li>
            </ul>
        </div>
    </div>

    <form method="POST" class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">
        <h2 class="text-3xl font-black mb-2">Buyer Registration</h2>
        <p class="text-slate-400 mb-6">Fill out your information below.</p>

        <?php if ($message != "") { ?>
            <div class="mb-5 bg-cyan-400/10 border border-cyan-400/30 text-cyan-300 px-4 py-3 rounded-xl text-sm">
                <?php echo $message; ?>

                <?php if ($dev_link != "") { ?>
                    <br>
                    <a href="<?php echo $dev_link; ?>" class="underline font-bold">Click here to verify for local testing</a>
                <?php } ?>
            </div>
        <?php } ?>

        <div class="grid md:grid-cols-2 gap-4">
            <div class="md:col-span-2">
                <label class="text-sm text-slate-300">Complete Name</label>
                <input type="text" name="complete_name" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400" placeholder="Juan Dela Cruz">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-slate-300">Email Address</label>
                <input type="email" name="email" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400" placeholder="example@email.com">
            </div>

            <div>
                <label class="text-sm text-slate-300">Password</label>
                <input type="password" name="password" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400" placeholder="Minimum 6 characters">
            </div>

            <div>
                <label class="text-sm text-slate-300">Confirm Password</label>
                <input type="password" name="confirm_password" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400" placeholder="Repeat password">
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-slate-300">Complete Address</label>
                <textarea name="address" required rows="3" class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400" placeholder="Enter your complete address"></textarea>
            </div>

            <div class="md:col-span-2">
                <label class="text-sm text-slate-300">Contact Number</label>
                <input type="text" name="contact_number" required class="w-full mt-2 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400" placeholder="09XXXXXXXXX">
            </div>
        </div>

        <button type="submit" class="w-full mt-6 bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition">
            Register Account
        </button>

        <p class="text-center text-sm text-slate-400 mt-5">
            Already have an account?
            <a href="login.php" class="text-cyan-400 font-bold">Login here</a>
        </p>
    </form>

</section>

<?php include 'includes/footer.php'; ?>