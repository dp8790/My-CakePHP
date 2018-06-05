<?php
$FullPath = PROJECT_URL;
foreach ($blogs as $bs) {
    ?>
    <div class='post-outer'>
        <div class='post hentry'>
            <div class='block-image'>
                <div class='thumb'>                   
                    <a href="<?php echo PROJECT_URL; ?>img/blog/<?php echo $bs['photo']; ?>" rel="prettyPhoto" class="prettyPhoto">
                        <img src="<?php echo PROJECT_URL; ?>img/blog/<?php echo $bs['photo']; ?>" alt="<?php echo $bs['title']; ?>" />
                        <div class="vlb_zoom" style="position: absolute; display: none; height: 92%; top: 10px;"></div>
                    </a>
                </div>
            </div>
            <article>               
                <h2 class='post-title entry-title'>
                    <?php echo $bs['title']; ?>
                </h2>                
                <div class='date-header'>
                    <div id='meta-post'>
                        <b>by</b>
                        <a class='g-profile' href='javascript:void(0);' rel='author' title='Dhruv Patel Blogs'>
                            <span itemprop='name'>Dhruv Patel</span>
                        </a>                        
                        <b>on </b> <?php echo date('F d,Y', strtotime($bs['created_date'])); ?>
                    </div>
                    <div class='resumo'>
                        <span>
                            <div id='summary5126501925887149861'>
                                <div dir="ltr" style="text-align: left;" trbidi="on">
                                    <div>
                                        <?php echo truncate_html($bs['description'], $length = 500, $ending = '...', true); ?>
                                    </div>
                                </div>
                            </div>
                        </span>
                    </div>
                    <div style='clear: both;'></div>
                    <div class='second-meta'>
                        <?php echo $this->Html->link('Continue Reading', array('controller' => 'blogs', 'action' => 'details', $bs['id']), array('class' => 'read-more anchor-hover')); ?>
                    </div>
                </div>
            </article>                                               
        </div>
    </div>
<?php } ?>
<div>
    <div class="col-sm-5" style="float: left; text-align: left;">
        <div class="dataTables_info" id="example2_info" role="status" aria-live="polite">
            <?php echo $this->Paginator->counter(['format' => 'Page {{page}} of {{pages}}, showing {{current}} records out of {{count}} total']); ?>
        </div>                                
    </div>
    <div class="col-sm-7" style="float: right; top: -17px; text-align: right;">
        <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
            <ul class="pagination" style="margin-top: 10px;">
                <li class="paginate_button previous disabled" id="example2_previous">
                    <?php echo $this->Paginator->prev('Previous', array('escape' => false), null, array('class' => 'prev disabled prv', 'escape' => false)); ?>                                        
                </li>
                <li class="paginate_button active">
                    <?php echo $this->Paginator->numbers(array('separator' => '', 'currentClass' => 'active', 'currentTag' => 'a', 'escape' => false)); ?>
                </li>                                  
                <li class="paginate_button next" id="example2_next">
                    <?php echo $this->Paginator->next('Next', array('escape' => false), null, array('class' => 'next disabled nxt', 'escape' => false)); ?>
                </li>
            </ul>
        </div>
    </div>
</div>