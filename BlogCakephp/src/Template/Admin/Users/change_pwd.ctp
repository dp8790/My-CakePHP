<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </div>            
                <?php
                echo $this->Form->create($user, array('action' => 'change_pwd', 'class' => 'form-horizontal', 'novalidate'));
                ?> 
                <div class="box-body">                  
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Password</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('old_password', array('type' => 'password', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">New Password</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('password1', array('type' => 'password', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                    </div>                    
                    <div class="form-group">
                        <label class="col-sm-4 control-label">Confirm Password</label>
                        <div class="col-sm-5">
                            <?php echo $this->Form->input('password2', array('type' => 'password', 'label' => false, 'class' => 'form-control'));
                            ?>
                        </div>
                    </div>                                            
                </div>                                
                <div class="box-footer" style="text-align: center;">                    
                    <?php //echo $this->Html->link('Cancel', ['controller' => 'Settings', 'action' => 'index', '_full' => true], ['escape' => false, 'class' => 'btn btn-default']); ?>
                    <button type="submit" class="btn btn-info">Save</button>
                </div>               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>