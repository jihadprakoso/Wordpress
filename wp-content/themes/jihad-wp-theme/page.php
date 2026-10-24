<?php get_header(); ?>

<div class="container my-5">
    <div class="row justify-content-center">
        <main class="col-lg-8">
            <?php while (have_posts()): the_post(); ?>
                <article id="post-<?php the_ID(); ?>" class="card border-0 shadow-sm rounded-4 p-4 p-md-5">
                    <h1 class="fw-bold mb-4"><?php the_title(); ?></h1>
                    <hr>
                    <div class="single-post-content mt-4">
                        <?php the_content(); ?>
                    </div>
                </article>
            <?php endwhile; ?>
        </main>
    </div>
</div>

<?php get_footer(); ?>
