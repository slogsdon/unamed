<section class="posts-list">
    <table class="table">
        <thead>
            <tr>
                <th style="width: 10%;">id</th>
                <th>name</th>
                <th style="width: 20%;">date modified</th>
            </tr>
        </thead>
        <?php foreach ($posts as $post): ?>
        <tr>
            <td>
                <?php echo $post->ID;?>
            </td>
            <td>
                <a href="<?php adminUrl(); ?>posts/edit/<?php echo $post->ID;?>"><?php echo $post->post_name;?></a>
                <div id="actions">
                </div>
            </td>
            <td>
                <?php echo $post->post_modified;?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</section>