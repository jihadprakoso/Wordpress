<?php get_header(); ?>

<div class="container my-5">
    <div class="row">
        <main class="col-lg-8">
            <?php while (have_posts()): the_post(); ?>
                <article id="post-<?php the_ID(); ?>" class="card border-0 shadow-sm rounded-4 overflow-hidden mb-5">
                    <?php if (has_post_thumbnail()): ?>
                        <?php the_post_thumbnail('large', ['class' => 'img-fluid w-100', 'style' => 'max-height:400px; object-fit:cover;']); ?>
                    <?php endif; ?>

                    <div class="card-body p-4 p-md-5">
                        <!-- Category -->
                        <?php
                        $categories = get_the_category();
                        if ($categories):
                        ?>
                            <a href="<?php echo get_category_link($categories[0]->term_id); ?>" class="post-category text-decoration-none d-inline-block mb-3">
                                <?php echo esc_html($categories[0]->name); ?>
                            </a>
                        <?php endif; ?>

                        <h1 class="fw-bold mb-4"><?php the_title(); ?></h1>

                        <div class="d-flex align-items-center gap-3 mb-4 text-muted small">
                            <span><i class="fa-regular fa-calendar me-1"></i> <?php echo get_the_date(); ?></span>
                            <span><i class="fa-regular fa-user me-1"></i> <?php the_author(); ?></span>
                            <span><i class="fa-regular fa-clock me-1"></i> <?php echo ceil(str_word_count(strip_tags(get_the_content())) / 200); ?> min read</span>
                        </div>

                        <hr class="mb-4">

                        <div class="single-post-content">
                            <?php the_content(); ?>
                        </div>

                        <hr class="mt-4">
                        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                            <div>
                                <?php the_tags('<span class="fw-bold me-2">Tags:</span>', ', ', ''); ?>
                            </div>
                            <a href="<?php echo home_url('/blog'); ?>" class="btn btn-outline-primary rounded-pill btn-sm">
                                <i class="fa-solid fa-arrow-left me-1"></i> Back to Blog
                            </a>
                        </div>
                    </div>
                </article>

                <!-- Comments -->
                <?php if (comments_open() || get_comments_number()): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                        <?php comments_template(); ?>
                    </div>
                <?php endif; ?>

            <?php endwhile; ?>
        </main>

        <aside class="col-lg-4 mt-5 mt-lg-0">
            <?php get_sidebar(); ?>
        </aside>
    </div>
</div>

<?php get_footer(); ?>
