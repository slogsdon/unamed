<?php get_header(); ?>
    <section id="posts">
        <?php if (has_posts()): ?>
            <?php foreach (the_posts() as $post): ?>
                <?php //print_r($post);?>
                <article>
                    <h1><?php echo $post->post_title;?></h1>
                    <div>
                        <?php echo $post->post_content;?>
                    </div>
                </article>
            <?php endforeach; ?>
        <?php else: ?>
            no posts
        <?php endif; ?>
    </section>
    <?php get_after_body(); ?>
<?php get_footer();
