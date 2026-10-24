<?php
/**
 * 404 template — halaman tidak ditemukan
 */
get_header();
?>

<div class="container my-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5 text-center">
                <div class="display-1 fw-bold text-gradient">404</div>
                <h1 class="fw-bold mb-3">Halaman Tidak Ditemukan</h1>
                <p class="text-muted mb-4">
                    Halaman yang Anda cari mungkin sudah dipindahkan, dihapus, atau tidak pernah ada.
                    Coba gunakan pencarian di bawah ini.
                </p>

                <div class="row justify-content-center mb-4">
                    <div class="col-md-8">
                        <?php get_search_form(); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="btn btn-primary rounded-pill px-4">
                        <i class="fa-solid fa-house me-1"></i> Kembali ke Beranda
                    </a>
                    <a href="javascript:history.back()" class="btn btn-outline-primary rounded-pill px-4">
                        <i class="fa-solid fa-arrow-left me-1"></i> Halaman Sebelumnya
                    </a>
                </div>
            </div>

            <?php
            $recent_posts = new WP_Query([
                'posts_per_page'      => 3,
                'ignore_sticky_posts' => true,
            ]);

            if ($recent_posts->have_posts()):
            ?>
                <h5 class="fw-bold mt-5 mb-3"><i class="fa-regular fa-newspaper text-primary me-2"></i>Artikel Terbaru</h5>
                <div class="row g-4">
                    <?php while ($recent_posts->have_posts()): $recent_posts->the_post(); ?>
                        <div class="col-md-4">
                            <article id="post-<?php the_ID(); ?>" class="card post-card shadow-sm h-100">
                                <?php if (has_post_thumbnail()): ?>
                                    <a href="<?php the_permalink(); ?>">
                                        <?php the_post_thumbnail('medium', ['class' => 'card-img-top']); ?>
                                    </a>
                                <?php endif; ?>
                                <div class="card-body p-4">
                                    <h6 class="card-title fw-bold">
                                        <a href="<?php the_permalink(); ?>" class="text-dark text-decoration-none">
                                            <?php the_title(); ?>
                                        </a>
                                    </h6>
                                    <p class="text-muted small mb-0">
                                        <i class="fa-regular fa-calendar me-1"></i> <?php echo get_the_date(); ?>
                                    </p>
                                </div>
                            </article>
                        </div>
                    <?php endwhile; ?>
                </div>
            <?php
            endif;
            wp_reset_postdata();
            ?>
        </div>
    </div>
</div>

<?php get_footer(); ?>
