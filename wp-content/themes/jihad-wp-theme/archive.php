<?php
/**
 * Archive template — kategori, tag, penulis, tanggal, dan arsip lain
 */
get_header();
?>

<div class="container my-5">
    <div class="row">
        <!-- Main Archive Loop -->
        <main class="col-lg-8">
            <header class="mb-4">
                <h1 class="fw-bold mb-2"><?php the_archive_title(); ?></h1>
                <?php
                $archive_description = get_the_archive_description();
                if ($archive_description):
                ?>
                    <div class="text-muted"><?php echo wp_kses_post($archive_description); ?></div>
                <?php endif; ?>
                <p class="text-muted small mb-0 mt-2">
                    <i class="fa-solid fa-list-ul me-1"></i>
                    <?php echo esc_html($GLOBALS['wp_query']->found_posts); ?> artikel ditemukan
                </p>
            </header>

            <?php if (have_posts()): ?>
                <div class="row g-4">
                    <?php while (have_posts()): the_post(); ?>
                        <div class="col-md-6">
                            <article id="post-<?php the_ID(); ?>" class="card post-card shadow-sm h-100">
                                <?php if (has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-body d-flex flex-column p-4">
                                    <?php
                                    $categories = get_the_category();
                                    if ($categories):
                                    ?>
                                        <a href="<?php echo get_category_link($categories[0]->term_id); ?>" class="post-category text-decoration-none d-inline-block mb-3">
                                            <?php echo esc_html($categories[0]->name); ?>
                                        </a>
                                    <?php endif; ?>

                                    <h5 class="card-title fw-bold">
                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h5>

                                    <p class="text-muted small mt-auto mb-0">
                                        <i class="fa-regular fa-calendar me-1"></i> <?php echo get_the_date(); ?>
                                        &nbsp;&middot;&nbsp;
                                        <i class="fa-regular fa-user me-1"></i> <?php the_author(); ?>
                                    </p>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-center mt-5">
                    <?php
                    echo paginate_links([
                        'type'      => 'list',
                        'prev_text' => '&laquo;',
                        'next_text' => '&raquo;',
                    ]);
                    ?>
                </div>
            <?php else: ?>
                <div class="card shadow-sm border-0 rounded-4 p-5 text-center text-muted">
                    <i class="fa-regular fa-folder-open fa-3x mb-3"></i>
                    <h4>Belum ada artikel.</h4>
                    <p class="mb-0">Arsip ini belum memiliki artikel yang dipublikasikan.</p>
                </div>
            <?php endif; ?>
        </main>

        <!-- Sidebar -->
        <aside class="col-lg-4 mt-5 mt-lg-0">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
