<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

include_once __DIR__ . '/functions.php';

$currentPage = basename($_SERVER['PHP_SELF']);

$currentDirectory = str_replace('\\', '/', dirname($_SERVER['PHP_SELF']));
$isSellerPage = strpos($currentDirectory, '/seller') !== false;

$basePath = $isSellerPage ? '../' : '';

function header_link_class($pageName, $currentPage)
{
    return $currentPage === $pageName ? 'aj-nav-active' : '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">

    <meta
        name="viewport"
        content="width=device-width, initial-scale=1.0"
    >

    <title>AJJR PC Parts</title>

    <link
        href="<?php echo htmlspecialchars($basePath); ?>output.css"
        rel="stylesheet"
    >

    <style>
       

        .aj-header {
            position: sticky;
            top: 0;
            z-index: 9999;
            width: 100%;
            background: rgba(2, 6, 23, 0.96);
            border-bottom: 1px solid #1e293b;
            backdrop-filter: blur(14px);
            -webkit-backdrop-filter: blur(14px);
        }

        .aj-header-container {
            width: 100%;
            max-width: 1280px;
            min-height: 82px;
            margin: 0 auto;
            padding: 12px 24px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 30px;
        }

        .aj-brand {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-shrink: 0;
            color: #f8fafc;
            text-decoration: none;
        }

        .aj-brand-logo {
            width: 55px;
            height: 55px;
            display: block;
            object-fit: contain;
            background: #ffffff;
        }

        .aj-brand-name {
            margin: 0;
            color: #f8fafc;
            font-size: 18px;
            font-weight: 900;
            line-height: 1.1;
        }

        .aj-brand-description {
            margin: 3px 0 0;
            color: #94a3b8;
            font-size: 12px;
            line-height: 1.2;
        }

        .aj-navigation {
            display: flex;
            align-items: center;
            justify-content: flex-end;
            gap: 25px;
            margin-left: auto;
        }

        .aj-navigation a {
            color: #cbd5e1;
            font-size: 14px;
            font-weight: 600;
            text-decoration: none;
            transition: color 0.2s ease, background 0.2s ease;
        }

        .aj-navigation a:hover,
        .aj-navigation .aj-nav-active {
            color: #22d3ee;
        }

        .aj-cart-link {
            display: flex;
            align-items: center;
            gap: 7px;
        }

        .aj-cart-count {
            min-width: 23px;
            height: 23px;
            padding: 0 7px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            color: #020617;
            background: #22d3ee;
            border-radius: 999px;
            font-size: 12px;
            font-weight: 800;
        }

        .aj-register-button,
        .aj-logout-button {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 800 !important;
        }

        .aj-register-button {
            color: #020617 !important;
            background: #22d3ee;
            box-shadow: 0 8px 20px rgba(34, 211, 238, 0.18);
        }

        .aj-register-button:hover {
            color: #020617 !important;
            background: #67e8f9;
        }

        .aj-logout-button {
            color: #ffffff !important;
            background: #ef4444;
        }

        .aj-logout-button:hover {
            color: #ffffff !important;
            background: #f87171;
        }

        .aj-mobile-menu {
            display: none;
            position: relative;
        }

        .aj-mobile-menu summary {
            padding: 9px 14px;
            color: #020617;
            background: #22d3ee;
            border-radius: 9px;
            cursor: pointer;
            font-size: 14px;
            font-weight: 800;
            list-style: none;
            user-select: none;
        }

        .aj-mobile-menu summary::-webkit-details-marker {
            display: none;
        }

        .aj-mobile-links {
            position: absolute;
            top: 48px;
            right: 0;
            width: 190px;
            padding: 10px;
            display: flex;
            flex-direction: column;
            gap: 4px;
            background: #0f172a;
            border: 1px solid #334155;
            border-radius: 12px;
            box-shadow: 0 16px 35px rgba(0, 0, 0, 0.45);
        }

        .aj-mobile-links a {
            padding: 10px 12px;
            color: #cbd5e1;
            border-radius: 8px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
        }

        .aj-mobile-links a:hover,
        .aj-mobile-links .aj-nav-active {
            color: #22d3ee;
            background: #1e293b;
        }

        @media (max-width: 850px) {
            .aj-header-container {
                min-height: 74px;
                padding: 10px 18px;
            }

            .aj-navigation {
                display: none;
            }

            .aj-mobile-menu {
                display: block;
            }

            .aj-brand-logo {
                width: 50px;
                height: 50px;
            }
        }

        @media (max-width: 480px) {
            .aj-header-container {
                padding: 9px 14px;
            }

            .aj-brand-name {
                font-size: 15px;
            }

            .aj-brand-description {
                display: none;
            }

            .aj-brand-logo {
                width: 45px;
                height: 45px;
            }
        }
    </style>
</head>

<body class="bg-slate-950 text-slate-100 min-h-screen flex flex-col">

<header class="aj-header">
    <nav class="aj-header-container">

        <!-- Company Logo -->
        <a
            href="<?php echo htmlspecialchars($basePath); ?>index.php"
            class="aj-brand"
        >
            <img
                src="<?php echo htmlspecialchars($basePath); ?>logo/LOGO.png"
                alt="AJJR PC Parts Logo"
                class="aj-brand-logo"
            >

            <div>
                <h1 class="aj-brand-name">
                    AJJR PC Parts
                </h1>

                <p class="aj-brand-description">
                    Online PC Parts Store
                </p>
            </div>
        </a>

        <div class="aj-navigation">

            <a
                href="<?php echo htmlspecialchars($basePath); ?>index.php"
                class="<?php echo header_link_class('index.php', $currentPage); ?>"
            >
                Home
            </a>

            <a
                href="<?php echo htmlspecialchars($basePath); ?>store.php"
                class="<?php echo header_link_class('store.php', $currentPage); ?>"
            >
                Store
            </a>

            <a
                href="<?php echo htmlspecialchars($basePath); ?>cart.php"
                class="aj-cart-link <?php echo header_link_class('cart.php', $currentPage); ?>"
            >
                Cart

                <span class="aj-cart-count">
                    <?php echo (int) cart_count(); ?>
                </span>
            </a>

            <a
                href="<?php echo htmlspecialchars($basePath); ?>about.php"
                class="<?php echo header_link_class('about.php', $currentPage); ?>"
            >
                About
            </a>

            <?php if (is_logged_in()) { ?>

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>logout.php"
                    class="aj-logout-button"
                >
                    Logout
                </a>

            <?php } else { ?>

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>login.php"
                    class="<?php echo header_link_class('login.php', $currentPage); ?>"
                >
                    Login
                </a>

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>register.php"
                    class="aj-register-button"
                >
                    Register
                </a>

            <?php } ?>

        </div>
        
        <details class="aj-mobile-menu">
            <summary>Menu</summary>

            <div class="aj-mobile-links">

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>index.php"
                    class="<?php echo header_link_class('index.php', $currentPage); ?>"
                >
                    Home
                </a>

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>store.php"
                    class="<?php echo header_link_class('store.php', $currentPage); ?>"
                >
                    Store
                </a>

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>cart.php"
                    class="<?php echo header_link_class('cart.php', $currentPage); ?>"
                >
                    Cart (<?php echo (int) cart_count(); ?>)
                </a>

                <a
                    href="<?php echo htmlspecialchars($basePath); ?>about.php"
                    class="<?php echo header_link_class('about.php', $currentPage); ?>"
                >
                    About
                </a>

                <?php if (is_logged_in()) { ?>

                    <a href="<?php echo htmlspecialchars($basePath); ?>logout.php">
                        Logout
                    </a>

                <?php } else { ?>

                    <a
                        href="<?php echo htmlspecialchars($basePath); ?>login.php"
                        class="<?php echo header_link_class('login.php', $currentPage); ?>"
                    >
                        Login
                    </a>

                    <a
                        href="<?php echo htmlspecialchars($basePath); ?>register.php"
                        class="<?php echo header_link_class('register.php', $currentPage); ?>"
                    >
                        Register
                    </a>

                <?php } ?>

            </div>
        </details>

    </nav>
</header>

<main class="flex-grow">