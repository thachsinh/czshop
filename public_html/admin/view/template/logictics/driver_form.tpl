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
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-driver" class="form-horizontal">
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-birthday"><?php echo $entry_birthday; ?></label>
            <div class="col-sm-3">
              <div class="input-group date">
                <input type="text" name="birthday" value="<?php echo $birthday; ?>" placeholder="<?php echo $entry_birthday; ?>" data-date-format="YYYY-MM-DD" id="input-birthday" class="form-control" readonly="true"/>
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
              <?php if ($error_birthday) { ?>
              <div class="text-danger"><?php echo $error_birthday; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label"><?php echo $entry_gender; ?></label>
            <div class="col-sm-10">
              <label class="radio-inline">
                <?php if ($gender) { ?>
                <input type="radio" name="gender" value="1" checked="checked"/>
                <?php echo $text_male; ?>
                <?php } else { ?>
                <input type="radio" name="gender" value="1" checked="checked"/>
                <?php echo $text_male; ?>
                <?php } ?>
              </label>
              <label class="radio-inline">
                <?php if (!$gender) { ?>
                <input type="radio" name="gender" value="0"/>
                <?php echo $text_female; ?>
                <?php } else { ?>
                <input type="radio" name="gender" value="0"/>
                <?php echo $text_female; ?>
                <?php } ?>
              </label>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-address"><?php echo $entry_address; ?></label>
            <div class="col-sm-10">
              <textarea name="address" rows="5" placeholder="<?php echo $entry_address; ?>" id="input-address" class="form-control"><?php echo $address; ?></textarea>
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
          <fieldset>
          	<legend><?php echo $entry_group_licence; ?></legend>
          </fieldset>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-driving_status"><?php echo $entry_driving_status; ?></label>
            <div class="col-sm-10">
              <input type="text" name="driving_status" value="<?php echo $driving_status; ?>" placeholder="<?php echo $entry_driving_status; ?>" id="input-driving_status" class="form-control" />
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-licence-valid-from"><?php echo $entry_licence_valid_from; ?></label>
            <div class="col-sm-3">
              <div class="input-group date">
                <input type="text" name="licence_valid_from" value="<?php echo $licence_valid_from; ?>" placeholder="<?php echo $entry_licence_valid_from; ?>" data-date-format="YYYY-MM-DD" id="input-licence-valid-from" class="form-control" readonly="true"/>
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
              <?php if ($error_licence_valid_from) { ?>
              <div class="text-danger"><?php echo $error_licence_valid_from; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group required">
            <label class="col-sm-2 control-label" for="input-licence-valid-to"><?php echo $entry_licence_valid_to; ?></label>
            <div class="col-sm-3">
              <div class="input-group date">
                <input type="text" name="licence_valid_to" value="<?php echo $licence_valid_to; ?>" placeholder="<?php echo $entry_licence_valid_to; ?>" data-date-format="YYYY-MM-DD" id="input-licence-valid-to" class="form-control" readonly="true"/>
                <span class="input-group-btn">
                <button class="btn btn-default" type="button"><i class="fa fa-calendar"></i></button>
                </span></div>
              <?php if ($error_licence_valid_to) { ?>
              <div class="text-danger"><?php echo $error_licence_valid_to; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-driving-licence-number"><?php echo $entry_driving_licence_number; ?></label>
            <div class="col-sm-10">
              <input type="text" name="driving_licence_number" value="<?php echo $driving_licence_number; ?>" placeholder="<?php echo $entry_driving_licence_number; ?>" id="input-driving-licence-number" class="form-control" />
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
$('.date').datetimepicker({
	pickTime: false
});
</script>
<?php echo $footer; ?>