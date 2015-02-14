<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right"><a href="<?php echo $add; ?>" data-toggle="tooltip" title="<?php echo $button_add; ?>" class="btn btn-primary"><i class="fa fa-plus"></i></a>
        <button type="button" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></button>
      </div>
      <h1><?php echo $heading_title; ?></h1>
      <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
      </ul>
    </div>
  </div>
  <div class="container-fluid">
    <?php if ($error_warning) { ?>
    <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <?php if ($success) { ?>
    <div class="alert alert-success"><i class="fa fa-check-circle"></i> <?php echo $success; ?>
      <button type="button" class="close" data-dismiss="alert">&times;</button>
    </div>
    <?php } ?>
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-list"></i> <?php echo $text_list; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form-driver">
          <div class="table-responsive">
            <table class="table table-bordered table-hover">
              <thead>
                <tr>
                  <td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
                  <td class="text-left"><?php if ($sort == 'name') { ?>
                    <a href="<?php echo $sort_name; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_name; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_name; ?>"><?php echo $column_name; ?></a>
                    <?php } ?></td>
                  <td class="text-left"><?php echo $column_driving_licence_number; ?></td>
            			<td class="text-right"><?php if ($sort == 'status') { ?>
                    <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                    <?php } else { ?>
                    <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                    <?php } ?>
                  </td>
                  <td class="text-right"><?php echo $column_action; ?></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($drivers) { ?>
                <?php foreach ($drivers as $driver) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($driver['driver_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $driver['driver_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $driver['driver_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><a href="<?php echo $driver['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>"><?php echo $driver['name']; ?></a></td>
                  <td class="text-left"><?php echo $driver['driving_licence_number']; ?></td>
                  <td class="text-right">
                    <input type="checkbox" <?php echo ((isset($driver['status']) && ($driver['status'] == 1)) ? 'checked': ''); ?> data-toggle="toggle" data-size="small" data-cid="<?php echo $driver['driver_id']; ?>" class="btn-status" />
                  </td>
                  <td class="text-right">
                    <div class="btn-group">
                      <a href="<?php echo $driver['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary"><i class="fa fa-pencil"></i></a>
                      <a data-cid="<?php echo $driver['driver_id']; ?>" data-toggle="tooltip" title="<?php echo $button_delete; ?>" class="btn btn-danger"><i class="fa fa-trash-o"></i></a>
                    </div>
                  </td>
                </tr>
                <?php } ?>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="5"><?php echo $text_no_results; ?></td>
                </tr>
                <?php } ?>
              </tbody>
            </table>
          </div>
        </form>
        <div class="row">
          <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
          <div class="col-sm-6 text-right"><?php echo $results; ?></div>
        </div>
      </div>
    </div>
  </div>
</div>
<?php echo $footer; ?>

<script type="text/javascript">
  $('a.btn-danger').click(function(){
    var cid = $(this).data('cid');
    bootbox.confirm("<?php echo $text_confirm; ?>", function(result) {
      if(result == true) {
        $.post('<?php echo urldecode($ajax_delete); ?>',
                {'driver_id': cid},
                function(data) {
                  location.reload();
                }
        );
      }
    });
  });

  $('button.btn-danger').click(function(){
    var cid = $(this).data('cid');
    bootbox.confirm("<?php echo $text_confirm; ?>", function(result) {
      if(result == true) {
        $('#form-driver').submit();
      }
    });
  });

  $('input.btn-status').change(function() {
    var cid = $(this).data('cid');
    var status = $(this).prop('checked');
    if (status == false) {
      status = 0;
    }
    else {
      status = 1;
    }

    $.post('<?php echo $ajax_status; ?>',
            {'driver_id': cid, 'status': status}, function(){} );
  })
</script>