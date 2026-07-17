<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include 'includes/databaseConnect.php';
include_once 'includes/functions.php';

$message = "";
$email = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = clean_input($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "Please enter a valid email address.";
    } elseif ($password === '') {
        $message = "Please enter your password.";
    } else {
        $stmt = mysqli_prepare(
            $conn,
            "SELECT
                user_id,
                first_name,
                middle_name,
                last_name,
                email,
                password,
                role
             FROM users
             WHERE email = ?
             LIMIT 1"
        );

        if (!$stmt) {
            $message = "Unable to process your login. Please try again.";
        } else {
            mysqli_stmt_bind_param(
                $stmt,
                "s",
                $email
            );

            mysqli_stmt_execute($stmt);

            $result =
                mysqli_stmt_get_result($stmt);

            $user =
                mysqli_fetch_assoc($result);

            if (!$user) {
                $message = "Account not found.";
            } elseif (
                !password_verify(
                    $password,
                    $user['password']
                )
            ) {
                $message = "Incorrect password.";
            } else {
                session_regenerate_id(true);

                $_SESSION['user_id'] =
                    $user['user_id'];

                $_SESSION['first_name'] =
                    $user['first_name'];

                $_SESSION['middle_name'] =
                    $user['middle_name'];

                $_SESSION['last_name'] =
                    $user['last_name'];

                $_SESSION['email'] =
                    $user['email'];

                $_SESSION['role'] =
                    $user['role'];

                if ($user['role'] === 'admin') {
                    header(
                        "Location: seller/dashboard.php"
                    );
                } else {
                    header(
                        "Location: store.php"
                    );
                }

                exit;
            }

            mysqli_stmt_close($stmt);
        }
    }
}

include 'includes/header.php';
?>

<section class="max-w-md mx-auto px-6 py-20">

    <form
        method="POST"
        class="bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl"
    >
        <h1 class="text-4xl font-black mb-2">
            Welcome Back
        </h1>

        <p class="text-slate-400 mb-6">
            Login to continue shopping.
        </p>

        <?php if ($message !== "") { ?>
            <div class="mb-5 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm">
                <?php echo htmlspecialchars($message); ?>
            </div>
        <?php } ?>

        <label
            for="email"
            class="text-sm text-slate-300"
        >
            Email Address
        </label>

        <input
            type="email"
            name="email"
            id="email"
            required
            autocomplete="email"
            value="<?php echo htmlspecialchars($email); ?>"
            class="w-full mt-2 mb-4 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            placeholder="example@email.com"
        >

        <label
            for="password"
            class="text-sm text-slate-300"
        >
            Password
        </label>

        <input
            type="password"
            name="password"
            id="password"
            required
            autocomplete="current-password"
            class="w-full mt-2 mb-6 bg-slate-950 border border-slate-700 rounded-xl px-4 py-3 outline-none focus:border-cyan-400"
            placeholder="Enter password"
        >

        <button
            type="submit"
            class="w-full bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition"
        >
            Login
        </button>

        <p class="text-center text-sm text-slate-400 mt-5">
            No account yet?

            <a
                href="register.php"
                class="text-cyan-400 font-bold"
            >
                Register here
            </a>
        </p>
    </form>

</section>

<?php include 'includes/footer.php'; ?>
