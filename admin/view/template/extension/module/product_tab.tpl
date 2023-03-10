<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
  <div class="page-header">
    <div class="container-fluid">
      <div class="pull-right">
        <button type="submit" form="form-product_tab" data-toggle="tooltip" title="<?php echo $button_save; ?>" class="btn btn-primary"><i class="fa fa-save"></i></button>
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
        <h3 class="panel-title"><i class="fa fa-pencil"></i> <?php echo $text_edit; ?></h3>
      </div>
      <div class="panel-body">
        <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-product_tab" class="form-horizontal">
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_name; ?></label>
            <div class="col-sm-10">
              <input type="text" name="name" value="<?php echo $name; ?>" placeholder="<?php echo $entry_name; ?>" id="input-name" class="form-control" />
              <?php if ($error_name) { ?>
              <div class="text-danger"><?php echo $error_name; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_features; ?></label>
            <div class="col-sm-1">
              <?php if ($featured_products) { ?>
                 <input type="checkbox" name="featured_products" value="1" checked="checked">
              <?php } else { ?>
                <input type="checkbox"  name="featured_products" value="1">
              <?php } ?>
            </div>
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_latest; ?></label>
            <div class="col-sm-1">
              <?php if ($latest_products) { ?>
                 <input type="checkbox" name="latest_products" value="1" checked="checked">
              <?php } else { ?>
                <input type="checkbox"  name="latest_products" value="1">
              <?php } ?>
            </div>
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_bestseller; ?></label>
            <div class="col-sm-1">
              <?php if ($bestseller_products) { ?>
                 <input type="checkbox" name="bestseller_products" value="1" checked="checked">
              <?php } else { ?>
                <input type="checkbox"  name="bestseller_products" value="1">
              <?php } ?>
            </div>
            <label class="col-sm-2 control-label" for="input-name"><?php echo $entry_special; ?></label>
            <div class="col-sm-1">
              <?php if ($special_products) { ?>
                 <input type="checkbox" name="special_products" value="1" checked="checked">
              <?php } else { ?>
                <input type="checkbox"  name="special_products" value="1">
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product_featured"><?php echo $entry_product_featured; ?></label>
            <div class="col-sm-10">
              <input type="text" name="product_featured" value="" placeholder="<?php echo $entry_product_featured; ?>" id="input-product_featured" class="form-control" />
              <div id="featured-product" class="well well-sm" style="height: 150px; overflow: auto;">
                <?php foreach ($products_featured as $product) { ?>
                <div id="featured-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo '('.$product['model'] . ') ' . $product['name']; ?>
                  <input type="hidden" name="product_featured[]" value="<?php echo $product['product_id']; ?>" />
                </div>
                <?php } ?>
              </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-product_latest"><?php echo $entry_product_latest; ?></label>
            <div class="col-sm-10">
              <select class="form-control" style="margin-bottom:15px;" name="product_latest_manual" id="product_latest_manual">
                  <option value="0" <?php if (!$product_latest_manual) { echo 'selected';} ?>>???? ?????????????????? ??????????????????</option>
                  <option value="1" <?php if ($product_latest_manual) { echo 'selected';} ?>>???????????????? ??????????????</option>
              </select>
                <div id="product_latest_block" class="<?php if (!$product_latest_manual) {echo 'd-none';} ?>">
                  <input type="text" name="product_latest" value="" placeholder="<?php echo $entry_product_latest; ?>" id="input-product_latest" class="form-control" />
                  <div id="latest-product" class="well well-sm" style="height: 150px; overflow: auto;">
                    <?php foreach ($products_latest as $product) { ?>
                    <div id="latest-product<?php echo $product['product_id']; ?>"><i class="fa fa-minus-circle"></i> <?php echo '('.$product['model'] . ') ' . $product['name']; ?>
                      <input type="hidden" name="product_latest[]" value="<?php echo $product['product_id']; ?>" />
                    </div>
                    <?php } ?>
                  </div>
                </div>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-limit"><?php echo $entry_limit; ?></label>
            <div class="col-sm-10">
              <input type="text" name="limit" value="<?php echo $limit; ?>" placeholder="<?php echo $entry_limit; ?>" id="input-limit" class="form-control" />
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-width"><?php echo $entry_width; ?></label>
            <div class="col-sm-10">
              <input type="text" name="width" value="<?php echo $width; ?>" placeholder="<?php echo $entry_width; ?>" id="input-width" class="form-control" />
              <?php if ($error_width) { ?>
              <div class="text-danger"><?php echo $error_width; ?></div>
              <?php } ?>
            </div>
          </div>
          <div class="form-group">
            <label class="col-sm-2 control-label" for="input-height"><?php echo $entry_height; ?></label>
            <div class="col-sm-10">
              <input type="text" name="height" value="<?php echo $height; ?>" placeholder="<?php echo $entry_height; ?>" id="input-height" class="form-control" />
              <?php if ($error_height) { ?>
              <div class="text-danger"><?php echo $error_height; ?></div>
              <?php } ?>
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
    <script type="text/javascript"><!--
$('input[name=\'product_featured\']').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: '(' + item['model'] + ') ' + item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  select: function(item) {
    $('input[name=\'product_featured\']').val('');
    
    $('#featured-product' + item['value']).remove();
    
    $('#featured-product').append('<div id="featured-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_featured[]" value="' + item['value'] + '" /></div>');  
  }
});
  
$('#featured-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});


$('input[name=\'product_latest\']').autocomplete({
  source: function(request, response) {
    $.ajax({
      url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_model=' +  encodeURIComponent(request),
      dataType: 'json',
      success: function(json) {
        response($.map(json, function(item) {
          return {
            label: '(' + item['model'] + ') ' + item['name'],
            value: item['product_id']
          }
        }));
      }
    });
  },
  select: function(item) {
    $('input[name=\'product_latest\']').val('');
    
    $('#latest-product' + item['value']).remove();
    
    $('#latest-product').append('<div id="latest-product' + item['value'] + '"><i class="fa fa-minus-circle"></i> ' + item['label'] + '<input type="hidden" name="product_latest[]" value="' + item['value'] + '" /></div>');  
  }
});
  
$('#latest-product').delegate('.fa-minus-circle', 'click', function() {
  $(this).parent().remove();
});
//--></script>


<script>
  $( "#product_latest_manual" ).change(function() {
    $( "#product_latest_manual option:selected" ).each(function() {
      if ($(this).val() == 1) {
        $('#product_latest_block').removeClass('d-none');
      } else {
        $('#product_latest_block').addClass('d-none');
      }
    })
  });
</script>
</div>
<?php echo $footer; ?>