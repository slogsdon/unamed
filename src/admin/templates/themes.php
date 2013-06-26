<section class="themes">
    <div class="row">
        <div class="title-and-secondary">
            <div class="span10">
                <!-- title and url -->
                <h1>Themes</h1>
            </div>
        </div>
        <div class="content">
            <div class="span10 form-horizontal">
                <!-- main -->
                <table class="table">
                    <thead>
                        <tr>
                            <th>name</th>
                        </tr>
                    </thead>
                    <?php foreach ($themes as $theme): ?>
                    <tr>
                        <td>
                            <a href="<?php adminUrl(); ?>themes/edit/<?php echo $theme;?>"><?php echo $theme;?></a>
                            <div id="actions">
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
    </div>
</section>
