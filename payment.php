<?php
ob_start();

include 'includes/databaseConnect.php';
include 'includes/header.php';

if (!is_logged_in()) {
    header("Location: login.php");
    exit();
}

if (empty($_SESSION['cart'])) {
    header("Location: store.php");
    exit();
}

$message = "";

$upload_dir = __DIR__ . '/uploads/payment_proofs/';
if (!is_dir($upload_dir)) {
    mkdir($upload_dir, 0755, true);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $user_id = $_SESSION['user_id'];
    $reference_number = clean_input($_POST['reference_number'] ?? '');

    if (!isset($_FILES['payment_proof']) || $_FILES['payment_proof']['error'] !== UPLOAD_ERR_OK) {
        $message = "Please upload a valid proof of payment file.";
    } else {
        $file = $_FILES['payment_proof'];
        $allowed_types = ['image/jpeg', 'image/png', 'image/webp', 'application/pdf'];
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime_type = finfo_file($finfo, $file['tmp_name']);
        finfo_close($finfo);

        $max_size = 5 * 1024 * 1024; 

        if (!in_array($mime_type, $allowed_types)) {
            $message = "Invalid file type. Please upload a JPG, PNG, WEBP, or PDF file.";
        } elseif ($file['size'] > $max_size) {
            $message = "File is too large. Maximum size is 5MB.";
        }
    }

    if ($message == "") {
        $cart_items = [];
        $total = 0;

        $ids = implode(",", array_map('intval', array_keys($_SESSION['cart'])));
        $result = mysqli_query($conn, "SELECT * FROM products WHERE product_id IN ($ids)");

        while ($product = mysqli_fetch_assoc($result)) {
            $quantity = $_SESSION['cart'][$product['product_id']];

            if ($quantity > $product['stock']) {
                $message = "Not enough stock for " . $product['product_name'];
                break;
            }

            $subtotal = $product['price'] * $quantity;
            $total += $subtotal;

            $cart_items[] = [
                'product' => $product,
                'quantity' => $quantity,
                'subtotal' => $subtotal
            ];
        }
    }

    if ($message == "") {
    
        $ext = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'proof_' . $user_id . '_' . time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        $destination = $upload_dir . $filename;

        if (!move_uploaded_file($file['tmp_name'], $destination)) {
            $message = "There was a problem saving your uploaded file. Please try again.";
        } else {
            $payment_method = "Proof of Payment";
            $stored_path = 'uploads/payment_proofs/' . $filename;

            $order_stmt = mysqli_prepare($conn, "INSERT INTO orders (user_id, total_amount, payment_method, payment_proof, reference_number) VALUES (?, ?, ?, ?, ?)");
            mysqli_stmt_bind_param($order_stmt, "idsss", $user_id, $total, $payment_method, $stored_path, $reference_number);
            mysqli_stmt_execute($order_stmt);

            $order_id = mysqli_insert_id($conn);

            foreach ($cart_items as $item) {
                $product_id = $item['product']['product_id'];
                $quantity = $item['quantity'];
                $price = $item['product']['price'];

                $item_stmt = mysqli_prepare($conn, "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)");
                mysqli_stmt_bind_param($item_stmt, "iiid", $order_id, $product_id, $quantity, $price);
                mysqli_stmt_execute($item_stmt);

                $stock_stmt = mysqli_prepare($conn, "UPDATE products SET stock = stock - ? WHERE product_id = ?");
                mysqli_stmt_bind_param($stock_stmt, "ii", $quantity, $product_id);
                mysqli_stmt_execute($stock_stmt);
            }

            $_SESSION['cart'] = [];
            $_SESSION['last_order_id'] = $order_id;

            header("Location: order_success.php");
            exit();
        }
    }
}
?>

<section class="max-w-5xl mx-auto px-6 py-14">

    <div class="text-center mb-10">
        <p class="text-cyan-400 font-semibold">Payment</p>
        <h1 class="text-5xl font-black mt-2">Upload Proof of Payment</h1>
        <p class="text-slate-400 mt-4">
            No real payment API is used. This is only for final project demonstration.
        </p>
    </div>

    <form method="POST" enctype="multipart/form-data" class="max-w-xl mx-auto bg-slate-900 border border-slate-800 rounded-3xl p-8 shadow-2xl">

        <?php if ($message != "") { ?>
            <div class="mb-5 bg-red-500/10 border border-red-500/30 text-red-300 px-4 py-3 rounded-xl text-sm">
                <?php echo $message; ?>
            </div>
        <?php } ?>

        <h2 class="text-2xl font-black mb-6">Payment Details</h2>

        <div class="bg-slate-950 border border-slate-800 rounded-2xl p-5 mb-6">
            <p class="text-slate-300 text-sm leading-relaxed">
                Please send your payment via <span class="font-bold text-cyan-400">GCash</span> or
                <span class="font-bold text-cyan-400">Bank Transfer</span> to the account details provided,
                then upload a screenshot or photo of your payment receipt below.
            </p>
        </div>

        <label class="block mb-2 font-bold text-sm text-slate-300">Reference / Transaction Number (optional)</label>
        <input
            type="text"
            name="reference_number"
            placeholder="e.g. 0123456789"
            class="w-full bg-slate-950 border border-slate-800 rounded-xl px-4 py-3 mb-6 text-slate-100 placeholder-slate-500 focus:outline-none focus:border-cyan-400"
        >

        <label class="block mb-2 font-bold text-sm text-slate-300">Proof of Payment <span class="text-red-400">*</span></label>
        <label class="flex flex-col items-center justify-center bg-slate-950 border-2 border-dashed border-slate-700 rounded-2xl p-8 mb-6 cursor-pointer hover:border-cyan-400 transition">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-slate-500 mb-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M12 12v9m0-9l-3 3m3-3l3 3" />
            </svg>
            <span class="text-slate-400 text-sm text-center">Click to select a file (JPG, PNG, WEBP, or PDF, max 5MB)</span>
            <span id="file-name" class="text-cyan-400 text-sm mt-2 font-bold"></span>
            <input
                type="file"
                name="payment_proof"
                accept="image/jpeg,image/png,image/webp,application/pdf"
                required
                class="hidden"
                onchange="document.getElementById('file-name').textContent = this.files.length ? this.files[0].name : '';"
            >
        </label>

        <button type="submit" class="w-full bg-cyan-400 hover:bg-cyan-300 text-slate-950 py-3 rounded-xl font-black transition">
            Submit Proof &amp; Place Order
        </button>

        <a href="checkout.php" class="block text-center mt-4 text-slate-400 hover:text-cyan-400">
            Back to Checkout
        </a>
    </form>

</section>

<?php include 'includes/footer.php'; ?>