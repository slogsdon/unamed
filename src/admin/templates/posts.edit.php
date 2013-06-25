<section class="posts-edit">
    <form method="post">

        <div class="container">
            <div class="row">
                <div class="title-and-secondary">
                    <div class="span10">
                        <!-- title and url -->
                        <input type="text" name="post_title" class="input-block-level" value="<?php echo $post->post_title;?>" placeholder="Title" />
                        http://un.shanelogsdon.com/<input type="text" name="post_name" value="<?php echo $post->post_name;?>" class="just-text" />
                        <!-- actions -->
                        <div class="btn-group pull-right post-actions">
                            <a class="btn btn-toggle-meta">
                                <div>
                                    <span class="lbl">Show</span> Meta <i class="icon-chevron-down"></i>
                                </div>
                            </a>
                            <a class="btn">Cancel</a>
                            <a class="btn btn-danger">Delete</a>
                            <a class="btn btn-primary">Update</a>
                        </div>
                    </div>
                    <div class="span6 form-horizontal">
                        <!-- meta -->
                        <div class="meta-info" style="display:none;">
                            <div class="control-group">
                                <label class="control-label" for="ID">Post ID</label>
                                <div class="controls">
                                    <input type="text" id="ID" name="ID" value="<?php echo $post->ID;?>" class="no-border" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_author">Post Author</label>
                                <div class="controls">
                                    <input type="text" id="post_author" name="post_author" value="<?php echo $post->post_author;?>" class="no-border" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_date">Post Date</label>
                                <div class="controls">
                                    <input type="text" id="post_date" name="post_date" value="<?php echo $post->post_date;?>" class="no-border" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_date_gmt">Post Date (GMT)</label>
                                <div class="controls">
                                    <input type="text" id="post_date_gmt" name="post_date_gmt" value="<?php echo $post->post_date_gmt;?>" class="no-border" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Post Status</label>
                                <div class="controls">
                                    <label class="radio inline">
                                        <input type="radio" name="post_status" value="<?php echo $post->post_status;?>" class="no-border" /> 
                                        published
                                    </label>
                                    <label class="radio inline">
                                        <input type="radio" name="post_status" value="<?php echo $post->post_status;?>" class="no-border" /> 
                                        not published
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Comment status</label>
                                <div class="controls">
                                    <label class="radio inline">
                                        <input type="radio" name="comment_status" value="<?php echo $post->comment_status;?>" class="no-border" />
                                        open
                                    </label>
                                    <label class="radio inline">
                                        <input type="radio" name="comment_status" value="<?php echo $post->comment_status;?>" class="no-border" />
                                        closed
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label">Ping Status</label>
                                <div class="controls">
                                    <label class="radio inline">
                                        <input type="radio" name="ping_status" value="<?php echo $post->ping_status;?>" class="no-border" />
                                        open
                                    </label>
                                    <label class="radio inline">
                                        <input type="radio" name="ping_status" value="<?php echo $post->ping_status;?>" class="no-border" />
                                        closed
                                    </label>
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_modified">Post Modified</label>
                                <div class="controls">
                                    <input type="text" id="post_modified" name="post_modified" value="<?php echo $post->post_modified;?>" class="no-border" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_modified_gmt">Post Modified (GMT)</label>
                                <div class="controls">
                                    <input type="text" id="post_modified_gmt" name="post_modified_gmt" value="<?php echo $post->post_modified_gmt;?>" class="no-border" disabled />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_password">Post Password</label>
                                <div class="controls">
                                    <input type="text" id="post_password" name="post_password" value="<?php echo $post->post_password;?>" class="no-border" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="post_parent">Post Parent</label>
                                <div class="controls">
                                    <input type="text" id="post_parent" name="post_parent" value="<?php echo $post->post_parent;?>" class="no-border" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="menu_order">Menu Order</label>
                                <div class="controls">
                                    <input type="text" id="menu_order" name="menu_order" value="<?php echo $post->menu_order;?>" class="no-border" />
                                </div>
                            </div>
                            <div class="control-group">
                                <label class="control-label" for="comment_count">Comment Count</label>
                                <div class="controls">
                                    <input type="text" id="comment_count" name="comment_count" value="<?php echo $post->comment_count;?>" class="no-border" disabled />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="content">
                    <div class="span10">
                        <!-- main -->
                        <div class="control-group">
                            <label class="control-label" for="post_content">Post Content</label>
                            <div class="controls">
                                <textarea id="post_content" name="post_content" class="input-block-level" rows="10"><?php echo $post->post_content;?></textarea>
                            </div>
                        </div>
                    </div>
                    <div class="span10">
                        <!-- secondary -->
                        <div class="control-group">
                            <label class="control-label"><input name="use-post-excerpt" type="checkbox" /> Post Excerpt</label>
                            <div class="controls post-excerpt-container" style="display:none;">
                                <textarea id="post_excerpt" name="post_excerpt" class="input-block-level" rows="5"><?php echo $post->post_excerpt;?></textarea>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- <input type="text" name="to_ping" value="<?php echo $post->to_ping;?>" />
        <input type="text" name="pinged" value="<?php echo $post->pinged;?>" />
        <input type="text" name="post_content_filtered" value="<?php echo $post->post_content_filtered;?>" />
        <input type="text" name="guid" value="<?php echo $post->guid;?>" />
        <input type="text" name="post_type" value="<?php echo $post->post_type;?>" />
        <input type="text" name="post_mime_type" value="<?php echo $post->post_mime_type;?>" /> -->

    </form>
</section>
