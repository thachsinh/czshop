<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-country" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
        <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a></div>
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
    <div class="panel panel-default">
      <div class="panel-heading">
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_form; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-vehicle" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-vin"><?php echo $entry_vin; ?></label>
            <div class="col-sm-10">
              <input type="text" name="vin" value="<?php echo $vin; ?>" placeholder="<?php echo $entry_vin; ?>" id="input-name" class="form-control" />
              <?php if ($error_vin) { ?>
              <div class="text-danger"><?php echo $error_vin; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-make"><?php echo $entry_make; ?></label>
            <div class="col-sm-10">
              <input type="text" name="make" value="<?php echo $make; ?>" placeholder="<?php echo $entry_make; ?>" id="input-make" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-model"><?php echo $entry_model; ?></label>
            <div class="col-sm-10">
              <input type="text" name="model" value="<?php echo $model; ?>" placeholder="<?php echo $entry_model; ?>" id="input-model" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-production-date"><?php echo $entry_production_date; ?></label>
            <div class="col-sm-3">
              <div class="input-group production-date">
                <input type="text" name="production_date" value="<?php echo $production_date; ?>" placeholder="<?php echo $entry_production_date; ?>" data-date-format="YYYY-MM" id="input-production-date" class="form-control" readonly="true"/>
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-model-number"><?php echo $entry_model_number; ?></label>
            <div class="col-sm-10">
              <input type="text" name="model_number" value="<?php echo $model_number; ?>" placeholder="<?php echo $entry_model_number; ?>" id="input-model-number" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-drive-type"><?php echo $entry_drive_type; ?></label>
            <div class="col-sm-10">
              <input type="text" name="drive_type" value="<?php echo $drive_type; ?>" placeholder="<?php echo $entry_drive_type; ?>" id="input-drive-type" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-chassis"><?php echo $entry_chassis; ?></label>
            <div class="col-sm-10">
              <input type="text" name="chassis" value="<?php echo $chassis; ?>" placeholder="<?php echo $entry_chassis; ?>" id="input-chassis" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-engine"><?php echo $entry_engine; ?></label>
            <div class="col-sm-10">
              <input type="text" name="engine" value="<?php echo $engine; ?>" placeholder="<?php echo $entry_engine; ?>" id="input-engine" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-transmission"><?php echo $entry_transmission; ?></label>
            <div class="col-sm-10">
              <input type="text" name="transmission" value="<?php echo $transmission; ?>" placeholder="<?php echo $entry_transmission; ?>" id="input-transmission" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-make"><?php echo $entry_make; ?></label>
            <div class="col-sm-10">
              <input type="text" name="make" value="<?php echo $make; ?>" placeholder="<?php echo $entry_make; ?>" id="input-make" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-body"><?php echo $entry_body; ?></label>
            <div class="col-sm-10">
              <input type="text" name="body" value="<?php echo $body; ?>" placeholder="<?php echo $entry_body; ?>" id="input-body" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-odometer"><?php echo $entry_odometer; ?></label>
            <div class="col-sm-10">
              <input type="text" name="odometer" value="<?php echo $odometer; ?>" placeholder="<?php echo $entry_odometer; ?>" id="input-odometer" class="form-control" />
            </div>
          </div>	
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-note"><?php echo $entry_note; ?></label>
            <div class="col-sm-10">
              <textarea name="note" rows="5" placeholder="<?php echo $entry_note; ?>" id="input-address" class="form-control"><?php echo $note; ?></textarea>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-status"><?php echo $entry_status; ?></label>
            <div class="col-sm-10">
              <select name="status" id="input-status" class="form-control">
                <?php if ($status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
	$('.production-date').datetimepicker({
		pickTime: false,
		viewMode: "months", 
	  minViewMode: "months",
	});
</script>
<?php echo $footer; ?>