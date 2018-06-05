<?php $FullPath = PROJECT_URL; ?>
<div class='post-outer'>
    <div class='post hentry'>
        <div class='block-image'>
            <div class='thumb'>
                <a href="<?php echo PROJECT_URL; ?>img/blog/<?php echo $Blog['photo']; ?>" rel="prettyPhoto" class="prettyPhoto">
                    <img src="<?php echo PROJECT_URL; ?>img/blog/<?php echo $Blog['photo']; ?>" alt="<?php echo $Blog['title']; ?>" />
                    <div class="vlb_zoom" style="position: absolute; display: none; height: 92%; top: 10px;"></div>
                </a>
            </div>
        </div>
        <article>
            <font class='retitle'>
            <h2 class='post-title entry-title'>
                <?php echo $Blog['title']; ?>
            </h2>
            </font>
            <div class='date-header'>
                <div id='meta-post'>
                    <b>by</b>
                    <a class='g-profile' href='javascript:void(0);' rel='author' title='Dhruv Patel Blogs'>
                        <span itemprop='name'>Dhruv Patel</span>
                    </a>                        
                    <b>on </b> <?php echo date('F d,Y', strtotime($Blog['created_date'])); ?>
                </div>
                <div class='resumo'>
                    <span>
                        <div id='summary5126501925887149861'>
                            <div dir="ltr" style="text-align: left;" trbidi="on">
                                <div>
                                    <?php echo $Blog['description']; ?>
                                </div>
                            </div>
                        </div>
                    </span>
                </div>
                <div style='clear: both;'></div>
            </div>
        </article>                                               
    </div>
</div>
<div class="next_prev">
    <?php
    if (!empty($NextPrev['prev']['id'])) {
        echo $this->Html->link('<< ' . $NextPrev['prev']['title'], array('controller' => 'blogs', 'action' => 'details', $NextPrev['prev']['id']), array('class' => 'previous'));
    }
    ?>
    <?php
    if (!empty($NextPrev['next']['id'])) {
        echo $this->Html->link($NextPrev['next']['title'] . ' >>', array('controller' => 'blogs', 'action' => 'details', $NextPrev['next']['id']), array('class' => 'next'));
    }
    ?>
</div>
<style>
    .next_prev a {
        text-decoration: none !important;
        display: inline-block;
        padding: 8px 16px;
    }

    .next_prev a:hover {
        background-color: #ddd;
        color: black;
    }

    .next_prev .previous {
        background-color: #f1f1f1;
        color: black;
    }

    .next_prev .next {
        background-color: #4CAF50;
        color: white;
        float: right;
    }
</style>