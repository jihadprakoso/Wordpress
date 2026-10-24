<!-- Site Footer -->
<footer class="site-footer">
    <div class="container">
        <div class="row">
            <div class="col-md-4 mb-4">
                <h5><i class="fa-solid fa-bolt text-primary me-2"></i><?php bloginfo('name'); ?></h5>
                <p class="mt-3"><?php bloginfo('description'); ?></p>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Quick Links</h5>
                <?php
                wp_nav_menu([
                    'theme_location' => 'footer',
                    'container'      => false,
                    'menu_class'     => 'list-unstyled mt-3',
                    'fallback_cb'    => '__return_false',
                    'walker'         => new class extends Walker_Nav_Menu {
                        function start_el(&$output, $item, $depth = 0, $args = null, $id = 0) {
                            $output .= '<li class="mb-2"><a href="'.esc_attr($item->url).'">'.esc_html($item->title).'</a></li>';
                        }
                    },
                ]);
                ?>
            </div>
            <div class="col-md-4 mb-4">
                <h5>Connect</h5>
                <div class="d-flex gap-3 mt-3 fs-4">
                    <a href="https://github.com/jihadprakoso" target="_blank" rel="noopener noreferrer" aria-label="GitHub"><i class="fa-brands fa-github"></i></a>
                    <a href="https://www.linkedin.com/in/jihad-prakoso/" target="_blank" rel="noopener noreferrer" aria-label="LinkedIn"><i class="fa-brands fa-linkedin"></i></a>
                    <a href="https://wa.me/6285773837959" target="_blank" rel="noopener noreferrer" aria-label="WhatsApp"><i class="fa-brands fa-whatsapp"></i></a>
                </div>
            </div>
        </div>
        <hr class="border-secondary">
        <p class="text-center small mb-0">&copy; <?php echo date('Y'); ?> <?php bloginfo('name'); ?>. Powered by WordPress. Theme by Jihad.</p>
    </div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
