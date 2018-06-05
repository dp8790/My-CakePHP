<?php $FullPath = PROJECT_URL; ?>
<section class="content">
    <div class="row">
        <div class="col-md-12">
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </div>            
                <?php
                echo $this->Form->create($Blog, array('action' => 'add_edit', 'class' => 'form-horizontal', 'novalidate', 'type' => 'file', 'id' => 'form_blog'));
                echo $this->Form->input('id', array('type' => 'hidden', 'label' => false, 'id' => 'id'));
                ?> 
                <div class="box-body">                    
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Blog title</label>
                        <div class="col-sm-4">
                            <div class="input text">
                                <?php echo $this->Form->input('title', array('type' => 'text', 'label' => false, 'class' => 'form-control'));
                                ?>
                            </div>
                        </div>
                    </div>                   
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Blog content</label>
                        <div class="col-sm-9">
                            <?php echo $this->Form->input('description', array('type' => 'textarea', 'label' => false, 'class' => 'form-control summernote')); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-2 control-label">Future image</label>
                        <div class="col-sm-10">
                            <div class="input text">                                
                                <?php if (!empty($Blog['id']) && !empty($Blog['photo'])) { ?>
                                    <div recordId ="<?php echo $Blog['id']; ?>" class="col-sm-3 img_0 img_added marginB10 ">
                                        <img class="fl col-sm-8 marginB10 paddingL0" src="<?php echo $FullPath; ?>img/blog/thumbs/<?php echo $Blog['photo']; ?>">
                                        <div class="col-xs-1 row0 paddingL0">
                                            <i class="fa fa-2x fa-trash-o img_div_del1"></i>
                                        </div>
                                    </div>
                                    <?php
                                    echo $this->Form->input('image_remove', array('type' => 'hidden', 'label' => false, 'class' => 'form-control'));
                                }
                                echo $this->Form->input('image_path', array('type' => 'file', 'label' => false, 'class' => ''));
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="box-footer" style="text-align: center;">                    
                    <button type="submit" class="btn btn-info">Save</button>
                    <?php echo $this->Html->link('Cancel', ['controller' => 'blogs', 'action' => 'index', '_full' => true], ['escape' => false, 'class' => 'btn btn-default']); ?>
                </div>               
                <?php echo $this->Form->end(); ?>
            </div>
        </div>
    </div>
</section>
<style>
    .img_div_del1 {
        cursor: pointer;
    }
    .img_added img {
        height: 100%;
        min-height: 100px;
        min-width: 100px;
        width: 85%;
    }
    .marginB10 {
        margin-bottom: 10px;
    }
    .paddingL0 {
        padding-left: 0;
    }
    .marginB10 {
        margin-bottom: 10px;
    }
    .fl {
        float: left;
    }
</style>
<script type="text/javascript">
    $(function () {
        var $valParams = {debug: false, rules: {}, messages: {}};

        $valParams['rules']['title'] = {"required": true};
        $valParams['messages']['title'] = "Please enter title";

        $valParams['rules']['description'] = {"required": true};
        $valParams['messages']['description'] = "Please enter description";

        $valParams['submitHandler'] = function (form)
        {

            var currentForm = jQuery(form);
            var currentFormSubmitOptions = formSubmitOptionsDefault;
            currentFormSubmitOptions.dataType = 'json';
            currentFormSubmitOptions.success = function (responseText, statusText, xhr, $form) {
                if (responseText.status != undefined) {
                    if (responseText.status == 0) {
                        var hideAfter = 0;
                    } else {
                        var hideAfter = '';
                    }
                }
                showSuccessDefault(responseText, statusText, xhr, $form, hideAfter);
            }
            //currentForm.ajaxSubmit(currentFormSubmitOptions);
            form.submit();
        }
        $("#form_blog").validate($valParams);

        $(document).on('click', '.img_div_del1', function () {
            var this_el = $(this);
            var mainEl = $(this_el).parents('.img_added');
            var recordId = mainEl.attr('recordId');
            var removeEl = $("#remove_id");
            if (recordId) {
                removeEl.val(removeEl.val() + "," + recordId);
                $('#image-remove').val('1');
            }
            mainEl.hide();
            return false;
        });
    });
</script>