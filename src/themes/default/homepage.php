<?php getHeader(); ?>
    <section id="posts">
        <?php if (hasPosts()): ?>
            <?php foreach (thePosts() as $post): ?>
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
    <?php getAfterBody(); ?>
<?php getFooter();
