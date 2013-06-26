<section class="plugins">
    <div class="row">
        <div class="title-and-secondary">
            <div class="span10">
                <!-- title and url -->
                <h1>Plugins</h1>
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
                    <?php foreach ($plugins as $plugin): ?>
                    <tr>
                        <td>
                            <a href="<?php adminUrl(); ?>plugins/edit/<?php echo $plugin;?>"><?php echo $plugin;?></a>
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
