<div class='widget PopularPosts' data-version='1' id='PopularPosts1'>
    <h2>Popular</h2>
    <div class='widget-content popular-posts'>
        <ul>
            <?php foreach ($popularBlogs as $pb) { ?>
                <li>
                    <div class='item-thumbnail-only'>
                        <div class='item-thumbnail'>
                            <a href="<?php echo PROJECT_URL; ?>img/blog/<?php echo $pb['photo']; ?>" rel="prettyPhoto" class="prettyPhoto">
                                <img src="<?php echo PROJECT_URL; ?>img/blog/thumbs/<?php echo $pb['photo']; ?>" width="72" height="72" alt="<?php echo $pb['title']; ?>" />
                                <div class="vlb_zoom" style="position: absolute; display: none; height: 72px;"></div>
                            </a>
                        </div>
                        <div class='item-title'>
                            <?php echo $this->Html->link($pb['title'], array('controller' => 'blogs', 'action' => 'details', $pb['id'])); ?>
                        </div>
                    </div>
                    <div style='clear: both;'></div>
                </li>
            <?php } ?>
        </ul>
    </div>
</div>