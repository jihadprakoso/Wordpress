<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="profile" href="https://gmpg.org/xfn/11">
    <?php wp_head(); ?>
</head>
<body <?php body_class('bg-light'); ?>>
<?php wp_body_open(); ?>

<!-- Site Navbar -->
<nav class="site-navbar navbar navbar-expand-lg fixed-top">
    <div class="container">
        <a class="navbar-brand" href="<?php echo home_url('/'); ?>">
            <i class="fa-solid fa-bolt text-primary me-2"></i>
            <span class="text-gradient"><?php bloginfo('name'); ?></span>
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarMain">
            <?php
            wp_nav_menu([
                'theme_location' => 'primary',
                'menu_class'     => 'navbar-nav ms-auto',
                'container'      => false,
                'fallback_cb'    => '__return_false',
                'items_wrap'     => '<ul id="%1$s" class="%2$s">%3$s</ul>',
                'depth'          => 2,
                'walker'         => new class extends Walker_Nav_Menu {
                    function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                        $output .= '<li class="nav-item"><a class="nav-link" href="'.esc_attr($item->url).'">'.esc_html($item->title).'</a></li>';
                    }
                },
            ]);
            ?>
            <a href="<?php echo home_url('/'); ?>?s=" class="btn btn-outline-primary rounded-pill ms-3 btn-sm">
                <i class="fa-solid fa-search"></i>
            </a>
        </div>
    </div>
</nav>

<div style="padding-top: 70px;"></div>
