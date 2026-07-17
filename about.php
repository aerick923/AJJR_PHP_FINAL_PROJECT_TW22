<?php
include 'includes/header.php';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>About | AJJR PC Parts</title>
    <link href="./output.css" rel="stylesheet">
</head>

<body class="bg-slate-950 text-white min-h-screen">

    <section class="relative overflow-hidden border-b border-slate-800">
        <div class="absolute inset-0 bg-gradient-to-br from-cyan-500/10 via-slate-950 to-blue-700/10"></div>

        <div class="relative max-w-7xl mx-auto px-6 py-20 text-center">
            <div class="inline-flex items-center gap-2 bg-slate-900 border border-slate-800 rounded-full px-4 py-2 mb-6">
                <span class="w-2 h-2 bg-cyan-400 rounded-full"></span>
                <p class="text-sm text-slate-300">About Our Final Project</p>
            </div>

            <h1 class="text-5xl md:text-6xl font-black leading-tight">
                About
                <span class="text-cyan-400">AJJR PC Parts</span>
            </h1>

            <p class="text-slate-400 text-lg mt-6 max-w-3xl mx-auto leading-relaxed">
                AJJR PC Parts is a student-made e-commerce website for computer parts.
                This project was created to demonstrate the use of HTML, CSS, JavaScript,
                PHP, MySQL, and Tailwind CSS in building a functional online store.
            </p>
        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="grid lg:grid-cols-2 gap-10 items-center">

            <div class="bg-slate-900 border border-slate-800 rounded-[2rem] p-8 shadow-xl">
                <div class="bg-gradient-to-br from-cyan-400 to-blue-600 rounded-[1.5rem] p-10 text-center">
                    <div class="text-8xl mb-6">🖥️</div>

                    <h2 class="text-4xl font-black text-white">
                        AJJR PC Parts
                    </h2>

                    <p class="text-cyan-50 mt-3">
                        Build your dream PC, one part at a time.
                    </p>
                </div>
            </div>

            <div>
                <p class="text-cyan-400 font-semibold">
                    Who We Are
                </p>

                <h2 class="text-4xl font-black mt-3">
                    Your Online PC Parts Store
                </h2>

                <p class="text-slate-400 mt-5 leading-relaxed">
                    AJJR PC Parts is an online store that offers different computer
                    parts such as processors, RAM, graphics cards, motherboards,
                    storage devices, power supplies, cooling parts, cases, and
                    peripherals.
                </p>

                <p class="text-slate-400 mt-4 leading-relaxed">
                    The website allows buyers to register, log in, browse categorized
                    products, add items to their cart, checkout, and proceed to a
                    simple payment page. For the seller side, the system includes
                    product management, inventory reports, user management, and
                    audit logs.
                </p>

                <div class="grid sm:grid-cols-3 gap-4 mt-8">
                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <h3 class="text-3xl font-black text-cyan-400">
                            14
                        </h3>

                        <p class="text-sm text-slate-400 mt-1">
                            Categories
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <h3 class="text-3xl font-black text-cyan-400">
                            PHP
                        </h3>

                        <p class="text-sm text-slate-400 mt-1">
                            Backend
                        </p>
                    </div>

                    <div class="bg-slate-900 border border-slate-800 rounded-2xl p-5">
                        <h3 class="text-3xl font-black text-cyan-400">
                            MySQL
                        </h3>

                        <p class="text-sm text-slate-400 mt-1">
                            Database
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-10">
        <div class="grid md:grid-cols-2 gap-8">

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 hover:border-cyan-400 transition">
                <div class="w-14 h-14 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-3xl mb-5">
                    🎯
                </div>

                <h2 class="text-2xl font-black">
                    Our Goal
                </h2>

                <p class="text-slate-400 mt-4 leading-relaxed">
                    Our goal is to create a simple but functional e-commerce website
                    where customers can easily browse PC parts, add products to their
                    cart, and complete a sample checkout process.
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-8 hover:border-cyan-400 transition">
                <div class="w-14 h-14 bg-cyan-400/10 rounded-2xl flex items-center justify-center text-3xl mb-5">
                    ⚡
                </div>

                <h2 class="text-2xl font-black">
                    Our Purpose
                </h2>

                <p class="text-slate-400 mt-4 leading-relaxed">
                    This project helps us practice database management, form
                    validation, product listing, user registration, cart handling,
                    checkout processing, and PHP programming.
                </p>
            </div>

        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <p class="text-cyan-400 font-semibold">
                Website Features
            </p>

            <h2 class="text-4xl md:text-5xl font-black mt-3">
                What Our Website Can Do
            </h2>

            <p class="text-slate-400 mt-4 max-w-2xl mx-auto">
                These are the main functions included in our project.
            </p>
        </div>

        <div class="grid sm:grid-cols-2 lg:grid-cols-4 gap-6">

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition">
                <div class="text-4xl mb-5">
                    👤
                </div>

                <h3 class="text-xl font-black">
                    Buyer Registration
                </h3>

                <p class="text-slate-400 text-sm mt-3">
                    Buyers can create an account with their name, email, password,
                    complete address, and contact number.
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition">
                <div class="text-4xl mb-5">
                    🛒
                </div>

                <h3 class="text-xl font-black">
                    Shopping Cart
                </h3>

                <p class="text-slate-400 text-sm mt-3">
                    Buyers can add products to their cart and review their selected
                    items before checkout.
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition">
                <div class="text-4xl mb-5">
                    💳
                </div>

                <h3 class="text-xl font-black">
                    Payment Page
                </h3>

                <p class="text-slate-400 text-sm mt-3">
                    The website includes a simple payment page without using a real
                    payment API.
                </p>
            </div>

            <div class="bg-slate-900 border border-slate-800 rounded-3xl p-6 hover:border-cyan-400 transition">
                <div class="text-4xl mb-5">
                    📊
                </div>

                <h3 class="text-xl font-black">
                    Admin Reports
                </h3>

                <p class="text-slate-400 text-sm mt-3">
                    The seller side includes product management, user management,
                    inventory reports, and audit logs.
                </p>
            </div>

        </div>
    </section>

    <section class="max-w-5xl mx-auto px-6 py-16">
        <div class="text-center mb-12">
            <p class="text-cyan-400 font-semibold">
                Meet the Team
            </p>

            <h2 class="text-4xl md:text-5xl font-black mt-3">
                The People Behind AJJR
            </h2>
        </div>

        <div class="space-y-8 text-center">

            <div>
                <h3 class="text-2xl font-black text-white">
                    Aerick Lee P. Alba
                </h3>

                <p class="text-cyan-400 font-semibold mt-2">
                    Group Leader of AJJR
                </p>
            </div>

            <div>
                <h3 class="text-2xl font-black text-white">
                    Jose Cordero III
                </h3>

                <p class="text-slate-400 font-semibold mt-2">
                    Member of AJJR
                </p>
            </div>

            <div>
                <h3 class="text-2xl font-black text-white">
                    Jonash Aaron De Guia
                </h3>

                <p class="text-slate-400 font-semibold mt-2">
                    Member of AJJR
                </p>
            </div>

            <div>
                <h3 class="text-2xl font-black text-white">
                    Romar Lising
                </h3>

                <p class="text-slate-400 font-semibold mt-2">
                    Member of AJJR
                </p>
            </div>

        </div>
    </section>

    <section class="max-w-7xl mx-auto px-6 py-16">
        <div class="bg-gradient-to-r from-cyan-400 to-blue-600 rounded-[2rem] p-10 md:p-14 text-center">
            <h2 class="text-4xl md:text-5xl font-black text-white">
                Ready to browse PC parts?
            </h2>

            <p class="text-cyan-50 mt-4 max-w-2xl mx-auto">
                Visit our store page and check out our available computer parts.
            </p>

            <a
                href="store.php"
                class="inline-block mt-8 bg-slate-950 hover:bg-slate-900 text-white px-8 py-4 rounded-2xl font-black transition"
            >
                Go to Store
            </a>
        </div>
    </section>

    <footer class="border-t border-slate-800 bg-slate-950 mt-10">
        <div class="max-w-7xl mx-auto px-6 py-10 text-center">
            <h2 class="text-xl font-black text-cyan-400">
                AJJR PC Parts
            </h2>

            <p class="text-slate-400 text-sm mt-2">
                This website is for educational purposes only and is a
                requirement for our final project.
            </p>

            <p class="text-slate-600 text-xs mt-5">
                © 2026 AJJR PC Parts. All rights reserved.
            </p>
        </div>
    </footer>

</body>

</html>
