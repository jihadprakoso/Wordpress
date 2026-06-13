<?php
/**
 * Sidebar template
 */
?>
<div class="sidebar-widget">
    <h5>Search</h5>
    <?php get_search_form(); ?>
</div>

<?php if (is_active_sidebar('sidebar-1')): ?>
    <?php dynamic_sidebar('sidebar-1'); ?>
<?php else: ?>
    <!-- Default sidebar content when no widgets assigned -->
    <div class="sidebar-widget">
        <h5>Recent Posts</h5>
        <ul class="list-unstyled">
            <?php
            $recent_posts = wp_get_recent_posts(['numberposts' => 5, 'post_status' => 'publish']);
            foreach ($recent_posts as $post):
            ?>
                <li class="mb-2">
                    <a href="<?php echo get_permalink($post['ID']); ?>" class="text-decoration-none text-dark">
                        <i class="fa-solid fa-chevron-right text-primary small me-1"></i>
                        <?php echo esc_html($post['post_title']); ?>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>

    <div class="sidebar-widget">
        <h5>Categories</h5>
        <ul class="list-unstyled">
            <?php
            $categories = get_categories(['orderby' => 'count', 'order' => 'DESC']);
            foreach ($categories as $cat):
            ?>
                <li class="mb-2 d-flex justify-content-between align-items-center">
                    <a href="<?php echo get_category_link($cat->term_id); ?>" class="text-decoration-none text-dark">
                        <i class="fa-solid fa-folder-open text-primary me-1 small"></i>
                        <?php echo esc_html($cat->name); ?>
                    </a>
                    <span class="badge bg-primary rounded-pill"><?php echo $cat->count; ?></span>
                </li>
            <?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>
