<section class="settings">
    <form method="post">

        <div class="row">
            <div class="title-and-secondary">
                <div class="span10">
                    <!-- title and url -->
                    <h1>Settings</h1>                        
                    <!-- actions -->
                    <div class="btn-group pull-right post-actions">
                        <a class="btn" href="<?php adminUrl();?>settings">Cancel</a>
                        <a class="btn btn-primary">Update</a>
                    </div>
                </div>
            </div>
            <div class="content">
                <div class="span10 form-horizontal">
                    <!-- main -->
                    <!-- <?php print_r($settings);?> -->
                    <?php foreach ($settings as $setting => $value): ?>
                    <div class="control-group">
                        <label class="control-label" for="<?php echo $setting;?>"><?php echo $setting;?></label>
                        <div class="controls">
                            <input type="text" id="<?php echo $setting;?>" name="<?php echo $setting;?>" value="<?php echo $value;?>" />
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>

    </form>
</section>
