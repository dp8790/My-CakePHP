<?php
if (isCallByAjax()) {
    foreach ($page_data['data'] as $k => &$d) {
        $temp = array();
        if (isset($d['photo']) && !empty($d['photo'])) {
            $Photo = $this->Html->image(PROJECT_URL . "img/blog/thumbs/" . $d['photo'], array('class' => 'imgTd'));
        } else {
            $Photo = '';
        }
        $temp[] = $Photo;
        $temp[] = $d['title'];
        $temp[] = truncate_html($d['description'], $length = 100, $ending = '...', true);
        $dd = $this->Html->link('<i class="fa fa-pencil"></i>', ['controller' => 'Blogs', 'action' => 'add_edit', $d['id'],Cake\Utility\Inflector::slug($d['title']), '_full' => true], ['escape' => false]);
        $dd .= ' / ';
        $dd .= $this->Form->postLink('', array('controller' => 'Blogs', 'action' => 'delete', $d['id']), array('escape' => false, 'confirm' => 'Are you sure?', 'class' => 'fa fa-trash-o'));
        $checked = "";
        if ($d['status'] == 1) {
            $checked = "checked";
        }
        $dd .= '<label class="switch"><input onchange="active_inactive(this);" type="checkbox" BlogId="' . $d["id"] . '" ' . $checked . ' /><div class="slider"></div></label>';
        $temp[] = $dd;
        $d = $temp;
    }
    $page_data['data'] = array_values($page_data['data']);
    $page_data['recordsTotal'] = $page_data['recordsTotal'];
    $page_data['recordsFiltered'] = $page_data['recordsFiltered'];
    echo json_encode($page_data);
    exit;
}
?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"><?php echo $title_for_layout; ?></h3>
                </div>
                <div class="box-body">
                    <table cellpadding="0" cellspacing="0" width="100%" class="table" id="datatable-example">
                        <thead>
                            <tr>
                                <td></td>
                                <td>
                                    <input type="text" data-column="0" placeholder="title" class="search-input-text form-control">
                                </td>
                                <td></td>
                                <td></td> 
                            </tr>
                            <tr>
                                <th>Photo</th>
                                <th>Title</th>
                                <th>Descriptions</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>           
        </div>
    </div>
</section>



<script type="text/javascript" language="javascript" >
    var formURL = '<?php echo PROJECT_URL; ?>';
    $(document).ready(function () {
        var dataTable = $('#datatable-example').DataTable({
            "processing": true,
            "serverSide": true,
            "bSort": false,
			//"scrollY":"500px",
			//"scrollCollapse": true,
			//"pageLength": 5, // By Default Length is 10
            "ajax": {
                url: "<?php echo PROJECT_URL; ?>admin/blogs/index",
                type: "post",
                error: function () {
                    $(".datatable-example-error").html("");
                    $("#datatable-example").append('<tbody class="datatable-example-error"><tr><th colspan="3" style="text-align:center;">There was an error. Please try agin.</th></tr></tbody>');
                    $("#datatable-example_processing").css("display", "none");
                }
            }
        });
        $("#datatable-example_filter").css("display", "none");
        $('.search-input-text').on('keyup change', function () {
            var i = $(this).attr('data-column');
            var v = $(this).val();
            dataTable.columns(i).search(v).draw();
        });
    });
    function active_inactive(element) {
        var Status = 0;
        var BlogId = $(element).attr("BlogId");
        if ($(element).is(':checked')) {
            Status = 1;
        }
        if (BlogId != '') {
            $.ajax({
                url: formURL + 'admin/blogs/active_inactive',
                type: "POST",
                data: {BlogId: BlogId, Status: Status},
                dataType: "json",
                success: function (data) {
                    if (data.status == 'success') {
                        successAlert(data.msg);
                    } else if (data.status == 'error') {
                        errorAlert(data.msg);
                    }
                },
                error: function () {
                    errorAlert("Error on ajax!");
                }
            });
        }
    }
</script>
<style>
    thead td input, thead td select {
        padding: 0 5px !important;
    }

    .imgTd {
        height: 75px;
        width: 75%;
    }
    thead td {
        padding: 0 5px 5px 0 !important;
    }
    .switch {
        display: inline-block;
        height: 20px;
        left: 5px;
        position: relative;
        top: 10px;
        width: 31px;
    }

    .switch input {display:none;}

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #FE0707;
        -webkit-transition: .4s;
        transition: .4s;
    }

    .slider:before {
        background-color: white;
        bottom: 2px;
        content: "";
        height: 16px;
        left: -10px;
        position: absolute;
        transition: all 0.4s ease 0s;
        width: 13px;
    }

    input:checked + .slider {
        background-color: #008D4C;
    }

    input:not(:checked) + .slider:before {
        left: 2px;
    }

    input:focus + .slider {
        box-shadow: 0 0 1px #2196F3;
    }

    input:checked + .slider:before {
        -webkit-transform: translateX(26px);
        -ms-transform: translateX(26px);
        transform: translateX(26px);
    }
</style>
