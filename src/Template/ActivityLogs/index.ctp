<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxcore.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxgrid.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxscrollbar.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxdropdownlist.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxgrid.pager.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxbuttons.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxcheckbox.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxlistbox.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxdropdownlist.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxmenu.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxgrid.sort.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxlistbox.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxmenu.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxgrid.filter.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxgrid.selection.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxgrid.columnsresize.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxdata.js'); ?>
<?php echo $this->Html->script('CakeSimpleModelHistory.jq_g/jqxdatatable.js'); ?>
<?php echo $this->Html->css('CakeSimpleModelHistory.jq_g/jqx.base.css'); ?>
<?php echo $this->Html->css('CakeSimpleModelHistory.app'); ?>
<div class="mk-wrp">
    <h2 class="header"><?= __('Activity Logs (Data History)') ?></h2>
    <div class="row">
        <div class="col-xs-6 col-sm-3">
            <div class="btn-danger btn-circle btn-xl">
                <i class="fa fa-database"></i>
                <h5><?= __('Number of History') ?></h5>
                <strong><?= $this->Number->format($row) ?></strong>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="btn-danger btn-circle btn-xl">
                <i class="fa fa-server"></i>
                <h5><?= __('Number of Model') ?></h5>
                <strong><?= $this->Number->format($model) ?></strong>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="btn-danger btn-circle btn-xl">
                <i class="fa fa-calendar-times-o"></i>
                <h5><?= __('Total Day') ?></h5>
                <strong><?= $this->Number->format($date) ?></strong>
            </div>
        </div>
        <div class="col-xs-6 col-sm-3">
            <div class="btn-danger btn-circle btn-xl">
                <i class="fa fa-users"></i>
                <h5><?= __('Number of User') ?></h5>
                <strong><?= $this->Number->format($user) ?></strong>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12">
            <div id="dataTable"></div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div id="modal-wrp" class="modal-content">
        </div>
    </div>
</div>
<script type="text/javascript">
    $(document).ready(function () {
        var url = "<?= $this->Url->build(["controller" => "ActivityLogs","action" => "ajax",'index']); ?>";
        // prepare the data
        var source =
        {
            dataType: "json",
            dataFields: [
                {name: 'id', type: 'int'},
                {name: 'model', type: 'string'},
                {name: 'user', type: 'string'},
                {name: 'group', type: 'string'},
                {name: 'ip_address', type: 'string'},
                {name: 'action', type: 'string'},
                {name: 'date', type: 'string'},
                {name: 'view', type: 'string'},
            ],
            id: 'id',
            url: url
        };

        var dataAdapter = new $.jqx.dataAdapter(source);
        var cellsrenderer = function (row, columnfield, value, defaulthtml, columnproperties) {
            return '<button data-id="'+value+'" data-toggle="modal" data-target=".bs-example-modal-lg" class="grid-btn btn btn-primary btn-sm" style="padding: 0 7px 0 7px!important;  margin: 3px 0 0 14px!important;">Details</button>';
        };
        $("#dataTable").jqxGrid(
            {
                width: '100%',
                source: dataAdapter,
                pageable: true,
                filterable: true,
                sortable: true,
                showfilterrow: true,
                columnsresize: true,
                pagesize: 15,
                pagesizeoptions: ['100', '200', '300', '500', '1000', '1500'],
                altrows: true,
                autoheight: true,
                columns: [
                    {text: 'Model', dataField: 'model', width: '20%'},
                    {text: 'User', dataField: 'user', width: '10%'},
                    {text: 'Group', dataField: 'edit', cellsalign: 'center', width: '15%'},
                    {text: 'IP Address', dataField: 'ip_address', cellsalign: 'center', width: '15%'},
                    {text: 'Action', dataField: 'action', cellsalign: 'center', width: '15%'},
                    {text: 'Date', dataField: 'date', cellsalign: 'center', width: '15%'},
                    { text: 'Action', datafield: 'id', cellsalign: 'right', cellsrenderer: cellsrenderer, editable: false, width: '10%' },
                ]
            });
        $(document).on('click','.grid-btn',function(){
            $('#modal-wrp').html('<h2 style="text-align: center; padding: 10px">loading...</h2>');
            var Id = $(this).data('id');
            var url = "<?= $this->Url->build(["controller" => "ActivityLogs","action" => "view"]); ?>/"+Id;
            $.ajax({
                url: url,
                type: 'GET',
                success: function (data, status)
                {
                    $('#modal-wrp').html(data);
                },
                error: function (xhr, desc, err)
                {
                    console.log("error");

                }
            });
        });
    });

</script>