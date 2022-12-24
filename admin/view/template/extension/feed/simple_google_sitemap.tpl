<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-feed" name="apply" data-toggle="tooltip" title="<?php echo $button_apply; ?>" class="btn btn-success"><i class="fa fa-check"></i></button>
                <button type="submit" form="form-feed" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
                <a href="<?php echo $cancel; ?>" data-toggle="tooltip" title="<?php echo $button_cancel; ?>" class="btn btn-default"><i class="fa fa-reply"></i></a>
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
            <div class="alert alert-danger alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <?php if ($success) { ?>
            <div class="alert alert-success alert-dismissible"><i class="fa fa-exclamation-circle"></i> <?php echo $success; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
                <span class="pull-right"><?php echo $text_info; ?></span>
            </div>
            <div class="panel-body">
                <ul class="nav nav-tabs">
                    <li class='active'><a href="#tab-main" data-toggle="tab"><?php echo $tab_main; ?></a></li>
                </ul>
                <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-feed" class="form-horizontal">
                    <div class="tab-content">
                        <div class="tab-pane active form-horizontal" id="tab-main">
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="help" title="<?php echo $help_status; ?>" data-toggle="tooltip"><?php echo $entry_status; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="simple_google_sitemap_status" id="input-status" class="form-control">
                                        <option value="1" <?php echo "1" == $simple_google_sitemap_status ? "selected" : ""; ?>><?php echo $text_enabled; ?></option>
                                        <option value="0" <?php echo "0" == $simple_google_sitemap_status ? "selected" : ""; ?>><?php echo $text_disabled; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="help" title="<?php echo $help_image; ?>" data-toggle="tooltip"><?php echo $entry_image; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="simple_google_sitemap_image" id="input-image" class="form-control">
                                        <option value="0" <?php echo "0" == $simple_google_sitemap_image ? "selected" : ""; ?>><?php echo $text_disabled; ?></option>
                                        <option value="1" <?php echo "1" == $simple_google_sitemap_image ? "selected" : ""; ?>><?php echo $text_cache; ?></option>
                                        <option value="2" <?php echo "2" == $simple_google_sitemap_image ? "selected" : ""; ?>><?php echo $text_original; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><span class="help" title="<?php echo $help_log; ?>" data-toggle="tooltip"><?php echo $entry_log; ?></span></label>
                                <div class="col-sm-10">
                                    <select name="simple_google_sitemap_log" id="input-log" class="form-control">
                                        <option value="1" <?php echo "1" == $simple_google_sitemap_log ? "selected" : ""; ?>><?php echo $text_enabled; ?></option>
                                        <option value="0" <?php echo "0" == $simple_google_sitemap_log ? "selected" : ""; ?>><?php echo $text_disabled; ?></option>
                                    </select>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-2 control-label"><?php echo $entry_feed; ?></label>
                                <div class="col-sm-10">
                                    <input readonly type="text"value="<?php echo $feed; ?>" placeholder="<?php echo $entry_feed; ?>" id="input-feed" class="form-control"/>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<?php echo $footer; ?>
