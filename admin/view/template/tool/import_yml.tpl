<?php echo $header; ?><?php echo $column_left; ?>
<div id="content">
    <div class="page-header">
        <div class="container-fluid">
            <div class="pull-right">
                <button type="submit" form="form-save" data-toggle="tooltip" title="<?php echo $entry_save_settings; ?>" class="btn btn-success"><i class="fa fa-save"></i>  <?php echo $entry_save_settings; ?></button>
                <button type="submit" form="form-import" data-toggle="tooltip" title="<?php echo $button_import; ?>" class="btn btn-danger"><i class="fa fa-refresh"></i>  <?php echo $button_import; ?></button>
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
            <button type="button" form="form-backup" class="close" data-dismiss="alert">&times;</button>
        </div>
        <?php } ?>
        <div class="panel panel-default">
            <div class="panel-heading">
                <h3 class="panel-title"><i class="fa fa-exchange"></i> <?php echo $heading_title; ?></h3>
            </div>
            <div class="panel-body">
                <form action="<?php echo $save; ?>" method="post" enctype="multipart/form-data" id="form-save" class="form-horizontal">
                    <div class="form-group">

                        <h2 class="text-center" style="margin-top:10px;margin-bottom:30px">Основные настройки:</h2>
                        <label class="col-sm-6 col-md-3 control-label" for="input-save"><?php echo $entry_url; ?></label>
                        <div class="col-sm-6 col-md-9" style="margin-bottom:10px;">
                            <input type="text" name="import_yml_url" id="input-url" class="form-control" value="<?php echo $import_yml_url; ?>"/>
                        </div>

                        <label class="col-sm-6 col-md-3 control-label" for="input-save"><?php echo $entry_start_from_product; ?></label>
                        <div class="col-sm-6 col-md-9" style="margin-bottom:10px;">
                            <input type="text" name="start_import_yml_from_product_id" id="input-url" class="form-control" value="<?php echo $start_import_yml_from_product_id; ?>"/>
                        </div>

                        <label class="col-sm-6 col-md-3 control-label" for="input-save"><?php echo $entry_margin_type; ?></label>
                        <div class="col-sm-6 col-md-9" style="margin-bottom:10px;">
                            <select class="form-control" name="import_yml_margin_type">
                                <?php if ($import_yml_margin_type == "percents") { ?>                                        
                                    <option value="fixed">в рублях</option>
                                    <option selected value="percents">в процентах</option>
                                <?php } else { ?>
                                    <option selected value="fixed">в рублях</option>
                                    <option value="percents">в процентах</option>
                                <?php } ?>
                            </select>
                        </div>

                        <label class="col-sm-6 col-md-3 control-label" for="input-save"><?php echo $entry_main_picture; ?></label>
                        <div class="col-sm-6 col-md-9">
                            <select class="form-control" name="import_yml_main_picture">
                                <?php if ($import_yml_main_picture == "2") { ?>                                        
                                    <option value="1">первая</option>
                                    <option selected value="2">вторая (если есть)</option>
                                <?php } else { ?>
                                    <option selected value="1">первая</option>
                                    <option value="2">вторая (если есть)</option>
                                <?php } ?>
                            </select>
                        </div>

                        <label class="col-sm-6 col-md-3 control-label" for="input-save"><?php echo $entry_start_import_password; ?></label>
                        <div class="col-sm-6 col-md-9" style="margin-top:10px;">
                            <input type="text" name="import_yml_start_import_password" class="form-control" value="<?php echo $import_yml_start_import_password; ?>"/>
                        </div>

                        <label class="col-sm-6 col-md-3 control-label" for="input-save"><?php echo $entry_start_import_timeout; ?></label>
                        <div class="col-sm-6 col-md-9" style="margin-top:10px;">
                            <input type="number" min="0" name="import_yml_start_import_timeout" class="form-control" value="<?= ($import_yml_start_import_timeout) ? $import_yml_start_import_timeout : '0' ?>"/>
                        </div>

                        <div class="row col-sm-12" style="margin-top:30px;">

                            <h2 class="text-center" style="margin-top:50px;margin-bottom:30px">Настройки SEO:</h2>
                            <label class="col-xs-12 col-md-12 col-lg-4 control-label" for="input-save"><?php echo $text_seo_section; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px">
                                <?php echo $text_seo_description; ?>
                            </div>


                            <h3 class="text-center" style="margin-top:20px;margin-bottom:20px">SEO категорий</h3>
                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_keyword_category; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <textarea name="import_yml_meta_keyword_category" class="form-control"><?php echo $import_yml_meta_keyword_category; ?></textarea>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_description_category; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">                                
                                <textarea name="import_yml_meta_description_category" class="form-control"><?php echo $import_yml_meta_description_category; ?></textarea>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_title_category; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <input type="text" name="import_yml_meta_title_category" class="form-control" value="<?php echo $import_yml_meta_title_category; ?>"/>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_h1_category; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <input type="text" name="import_yml_meta_h1_category" class="form-control" value="<?php echo $import_yml_meta_h1_category; ?>"/>
                            </div>
                            

                            <h3 class="text-center" style="margin-top:20px;margin-bottom:20px">SEO товаров</h3>
                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_keyword_product; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <textarea name="import_yml_meta_keyword_product" class="form-control"><?php echo $import_yml_meta_keyword_product; ?></textarea>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_description_product; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">                                
                                <textarea name="import_yml_meta_description_product" class="form-control"><?php echo $import_yml_meta_description_product; ?></textarea>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_title_product; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <input type="text" name="import_yml_meta_title_product" class="form-control" value="<?php echo $import_yml_meta_title_product; ?>"/>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_h1_product; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <input type="text" name="import_yml_meta_h1_product" class="form-control" value="<?php echo $import_yml_meta_h1_product; ?>"/>
                            </div>
                            

                            <h3 class="text-center" style="margin-top:20px;margin-bottom:20px">SEO производителей</h3>
                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_keyword_brand; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <textarea name="import_yml_meta_keyword_brand" class="form-control"><?php echo $import_yml_meta_keyword_brand; ?></textarea>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_description_brand; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">                                
                                <textarea name="import_yml_meta_description_brand" class="form-control"><?php echo $import_yml_meta_description_brand; ?></textarea>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_title_brand; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <input type="text" name="import_yml_meta_title_brand" class="form-control" value="<?php echo $import_yml_meta_title_brand; ?>"/>
                            </div>

                            <label class="col-xs-12 col-md-12 col-lg-4 control-label"><?php echo $entry_import_yml_meta_h1_brand; ?></label>
                            <div class="col-xs-12 col-md-12 col-lg-8" style="margin-bottom:10px;">
                                <input type="text" name="import_yml_meta_h1_brand" class="form-control" value="<?php echo $import_yml_meta_h1_brand; ?>"/>
                            </div>
                        </div>

                        <div class="row col-sm-12">
                            <h2 class="text-center mt-5" style="margin-top:50px;margin-bottom:30px">Наценка на товары по категориям:</h2>
                            <div class="col-xs-12 col-md-12 col-lg-4">
                                <input name="import_yml_category_margin_krossovki" value="<?= $import_yml_category_margin_krossovki;?>" class="margin form-control" type="text" style="width:80px;display: inline-block; margin:5px"> <b>Кроссовки</b>
                            </div>

                            <div class="col-xs-12 col-md-12 col-lg-4">
                                <input name="import_yml_category_margin_odezhda" value="<?= $import_yml_category_margin_odezhda;?>"  class="margin form-control" type="text" style="width:80px;display: inline-block; margin:5px"> <b>Одежда</b>
                            </div>

                            <div class="col-xs-12 col-md-12 col-lg-4">
                                <input name="import_yml_category_margin_aksessuari" value="<?= $import_yml_category_margin_aksessuari;?>" class="margin form-control" type="text" style="width:80px;display: inline-block; margin:5px"> <b>Аксессуары</b>
                            </div>





                            <?php foreach ($product_categories as $product_category) { ?>
                                <?php if ($product_category['category_id'] != 3 && $product_category['category_id'] != 4 && $product_category['category_id'] != 1789 && $product_category['category_id'] != 1163 ) { ?>

                                    <div class="col-xs-12 col-md-12 col-lg-4">
                                        <input class="margin form-control" name="import_yml_category_margin_<?php echo $product_category['category_id']; ?>" type="text" style="width:80px;display: inline-block; margin:5px" value="<?php echo $product_category['margin']; ?>">
                                        <b><?php echo $product_category['name']; ?></b>
                                    </div>

                                <?php } ?>
                            <?php } ?>


                        </div>

                        <div class="row col-sm-12">
                            <h2 class="text-center mt-5" style="margin-top:50px;margin-bottom:30px">Наценка на предметы одежды:</h2>
                            <?php foreach ($product_attributes as $product_attribute){ ?>
                                <div class="col-xs-12 col-md-6 col-lg-4"><input class="predmetOdezhdi margin form-control" name="<?php echo $product_attribute['name']; ?>" type="text" style="width:80px;display: inline-block; margin:5px" value="<?php echo $product_attribute['value']; ?>"> <b class="predmetOdezhdiValue"><?php echo $product_attribute['text']; ?></b></div>
                            <?php } ?>
                        </div>

                        <div class="col-sm-12">
                            <div class="col-sm-12 col-md-6 text-center">
                                <h2 class="text-center mt-5" style="margin-top:50px;margin-bottom:30px">Наценка на товары по названиям:</h2>
                                <?php if ($import_yml_product_names_margin) { ?>
                                    <?php
                                    $counter  = 1;
                                    foreach ($import_yml_product_names_margin as $key => $margin) {
                                        echo '<div class="row import_yml_product_names_margin_row" style="margin-top:10px"><div class="col-sm-6 col-lg-4"><input type="text" id="product_name-'.$counter.'" class="form-control" placeholder="название товара" onchange="get_import_yml_product_names_margins();" value="'.$key.'"></div><div class="col-sm-6 col-lg-4"> <input type="text" id="product_name_margin-'.$counter.'" class="margin form-control" placeholder="наценка" onkeyup="get_import_yml_product_names_margins();" value="'.$margin.'"></div></div>';

                                        $counter++;
                                    }
                                    ?>
                                <?php } else {?>
                                    <div class="row import_yml_product_names_margin_row" style="margin-top:10px">
                                    <div class="col-sm-6 col-lg-4">
                                        <input type="text" id="product_name-1" class="form-control" placeholder="название товара" onchange="get_import_yml_product_names_margins();">
                                    </div>
                                    <div class="col-sm-6 col-lg-4">
                                        <input type="text" id="product_name_margin-1" class="margin form-control" placeholder="наценка" onkeyup="get_import_yml_product_names_margins();">
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row import_yml_product_names_margin_button_row" style="margin-top:20px">
                                    <div class="col-sm-6 col-lg-4">
                                        <button class="btn btn-success col-sm-12" onclick="event.preventDefault(); add_product_names_margin();">+ Добавить</button>
                                        <input type="hidden" name ="import_yml_product_names_margin" id="import_yml_product_names_margin" value="<?= $import_yml_product_names_margin_string; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 col-md-6 text-center">
                                <h2 class="text-center mt-5" style="margin-top:50px;margin-bottom:30px">Наценка на товары по артикулам:</h2>
                                <?php if ($import_yml_product_skus_margin) { ?>
                                    <?php
                                    $counter  = 1;
                                    foreach ($import_yml_product_skus_margin as $key => $margin) {
                                        echo '<div class="row import_yml_product_skus_margin_row" style="margin-top:10px"><div class="col-sm-6 col-lg-4"><input type="text" id="product_sku-'.$counter.'" class="form-control" placeholder="артикул товара" onchange="get_import_yml_product_skus_margins();" value="'.$key.'"></div><div class="col-sm-6 col-lg-4"> <input type="text" id="product_sku_margin-'.$counter.'" class="margin form-control" placeholder="наценка" onkeyup="get_import_yml_product_skus_margins();" value="'.$margin.'"></div></div>';

                                        $counter++;
                                    }
                                    ?>
                                <?php } else {?>
                                    <div class="row import_yml_product_skus_margin_row" style="margin-top:10px">
                                    <div class="col-sm-6 col-lg-4">
                                            <input type="text" id="product_sku-1" class="form-control" placeholder="артикул товара" onchange="get_import_yml_product_skus_margins();">
                                        </div>
                                        <div class="col-sm-6 col-lg-4">
                                            <input type="text" id="product_sku_margin-1" class="margin form-control" placeholder="наценка" onkeyup="get_import_yml_product_skus_margins();">
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row import_yml_product_skus_margin_button_row" style="margin-top:20px">
                                    <div class="col-sm-6 col-lg-4">
                                        <button class="btn btn-success col-sm-12" onclick="event.preventDefault(); add_product_skus_margin();">+ Добавить</button>
                                        <input type="hidden" name ="import_yml_product_skus_margin" id="import_yml_product_skus_margin" value="<?= $import_yml_product_skus_margin_string; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12" style="margin-top:50px;">
                            <h2 class="text-center mt-5" style="margin-bottom:30px">Не импортировать:</h2>
                            <div class="col-sm-12 col-md-6">
                                <h2 class="text-center mt-5" style="margin-top:30px;margin-bottom:30px">Бренды:</h2>
                                <table class="table table-condensed table-striped" style="height: 500px;overflow: auto;display: block;max-width: max-content;margin: auto;">
                                <?php foreach ($product_manufacturers as $key => $product_manufacturer){ ?>
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="manufacturer_id-<?= $product_manufacturer ?>" name="import_yml_disabled_manufacturers[]" value="<?= $product_manufacturer?>" <?php if (is_array($import_yml_disabled_manufacturers)) { if (in_array($product_manufacturer, $import_yml_disabled_manufacturers)) {echo 'checked';} } ?>>
                                                    <?= $key ?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </table>
                            </div>

                            <div class="col-sm-12 col-md-6">
                                <h2 class="text-center mt-5" style="margin-top:30px;margin-bottom:30px">Категории:</h2>
                                <table class="table table-condensed table-striped" style="height: 500px;overflow: auto;display: block;max-width: max-content;margin: auto;">
                                <?php foreach ($product_categories as $product_category){ ?>
                                    <tr>
                                        <td>
                                            <div class="checkbox">
                                                <label>
                                                    <input type="checkbox" id="category_id-<?= $product_category['category_id'] ?>" name="import_yml_disabled_categories[]" value="<?= $product_category['category_id']?>" <?php if (is_array($import_yml_disabled_categories)) { if (in_array($product_category['category_id'], $import_yml_disabled_categories)) {echo 'checked';} } ?>>
                                                    <?= $product_category['name'] ?>
                                                </label>
                                            </div>
                                        </td>
                                    </tr>
                                <?php } ?>
                                </table>
                            </div>


                            <div class="col-sm-12 col-md-6 text-center">
                                <h2 class="text-center mt-5" style="margin-top:50px;margin-bottom:30px">Товары по названиям:</h2>
                                <?php if ($import_yml_disabled_product_name) { ?>
                                    <?php
                                    $counter  = 1;
                                    foreach ($import_yml_disabled_product_name as $key => $name) {
                                        echo '<div class="row import_yml_disabled_product_name_row" style="margin-top:10px"><div class="col-sm-3"><input type="text" id="disabled_product_name-'.$counter.'" class="form-control" placeholder="название товара" onchange="get_import_yml_disabled_product_names();" value="'.$name.'"></div></div>';

                                        $counter++;
                                    }
                                    ?>
                                <?php } else {?>
                                    <div class="row import_yml_disabled_product_name_row" style="margin-top:10px">
                                    <div class="col-sm-3">
                                            <input type="text" id="disabled_product_name-1" class="form-control" placeholder="название товара" onchange="get_import_yml_disabled_product_names();">
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row import_yml_disabled_product_name_button_row" style="margin-top:20px">
                                    <div class="col-sm-3">
                                        <button class="btn btn-success col-sm-12" onclick="event.preventDefault(); add_disabled_product_name();">+ Добавить</button>
                                        <input type="hidden" name ="import_yml_disabled_product_name" id="import_yml_disabled_product_name" value="<?= $import_yml_disabled_product_name_string; ?>">
                                    </div>
                                </div>
                            </div>


                            <div class="col-sm-12 col-md-6 text-center">
                                <h2 class="text-center mt-5" style="margin-top:50px;margin-bottom:30px">Товары по артикулам:</h2>
                                <?php if ($import_yml_disabled_product_sku) { ?>
                                    <?php
                                    $counter  = 1;
                                    foreach ($import_yml_disabled_product_sku as $key => $sku) {
                                        echo '<div class="row import_yml_disabled_product_sku_row" style="margin-top:10px"><div class="col-sm-3"><input type="text" id="disabled_product_sku-'.$counter.'" class="form-control" placeholder="артикул товара" onchange="get_import_yml_disabled_product_skus();" value="'.$sku.'"></div></div>';

                                        $counter++;
                                    }
                                    ?>
                                <?php } else {?>
                                    <div class="row import_yml_disabled_product_sku_row" style="margin-top:10px">
                                    <div class="col-sm-3">
                                            <input type="text" id="disabledproduct_sku-1" class="form-control" placeholder="артикул товара" onchange="get_import_yml_disabled_product_skus();">
                                        </div>
                                    </div>
                                <?php } ?>

                                <div class="row import_yml_disabled_product_sku_button_row" style="margin-top:20px">
                                    <div class="col-sm-3">
                                        <button class="btn btn-success col-sm-12" onclick="event.preventDefault(); add_disabled_product_sku();">+ Добавить</button>
                                        <input type="hidden" name ="import_yml_disabled_product_sku" id="import_yml_disabled_product_sku" value="<?= $import_yml_disabled_product_sku_string; ?>">
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </form>
                <form action="<?php echo $import; ?>" method="post" enctype="multipart/form-data" id="form-import" class="form-horizontal"></form>
            </div>
        </div>
    </div>
</div>

<script>
    function add_product_names_margin(){

        var import_yml_product_names_margin_rows_counter = $('.import_yml_product_names_margin_row').length + 1;

        $(".import_yml_product_names_margin_button_row").before('<div class="row import_yml_product_names_margin_row" style="margin-top:10px"><div class="col-sm-3"><input type="text"  id="product_name-'+import_yml_product_names_margin_rows_counter+'" class="form-control" placeholder="название товара" onkeyup="get_import_yml_product_names_margins();"></div><div class="col-sm-2"><input type="text" id="product_name_margin-'+import_yml_product_names_margin_rows_counter+'" class="margin form-control" placeholder="наценка" onkeyup="get_import_yml_product_names_margins();"></div></div>');
    }

    function get_import_yml_product_names_margins() {
        var import_yml_product_names_margin_string = "{";

        for (var i = 1; i <= $('.import_yml_product_names_margin_row').length; i++) {
            if ($('#product_name-' + i).val() == '' ||  $('#product_name_margin-' + i).val() == '') {
                continue;
            }

            if (i != $('.import_yml_product_names_margin_row').length) {
                    import_yml_product_names_margin_string += '"' + $('#product_name-' + i).val() + '": ' + + $('#product_name_margin-' + i).val() + ',';
            } else {
                import_yml_product_names_margin_string += '"' + $('#product_name-' + i).val() + '": ' + + $('#product_name_margin-' + i).val();
            }
        }
        import_yml_product_names_margin_string += '}';

        $('#import_yml_product_names_margin').val(import_yml_product_names_margin_string.replace(',"": 0','').replace(',}','}'));
    }

    function add_product_skus_margin(){

        var import_yml_product_skus_margin_rows_counter = $('.import_yml_product_skus_margin_row').length + 1;

        $(".import_yml_product_skus_margin_button_row").before('<div class="row import_yml_product_skus_margin_row" style="margin-top:10px"><div class="col-sm-3"><input type="text"  id="product_sku-'+import_yml_product_skus_margin_rows_counter+'" class="form-control" placeholder="артикул товара" onkeyup="get_import_yml_product_skus_margins();"></div><div class="col-sm-2"><input type="text" id="product_sku_margin-'+import_yml_product_skus_margin_rows_counter+'" class="margin form-control" placeholder="наценка" onkeyup="get_import_yml_product_skus_margins();"></div></div>');
    }

    function get_import_yml_product_skus_margins() {
        var import_yml_product_skus_margin_string = "{";

        for (var i = 1; i <= $('.import_yml_product_skus_margin_row').length; i++) {
            if ($('#product_sku-' + i).val() == '' ||  $('#product_sku_margin-' + i).val() == '') {
                continue;
            }

            if (i != $('.import_yml_product_skus_margin_row').length) {
                    import_yml_product_skus_margin_string += '"' + $('#product_sku-' + i).val() + '": ' + + $('#product_sku_margin-' + i).val() + ',';
            } else {
                import_yml_product_skus_margin_string += '"' + $('#product_sku-' + i).val() + '": ' + + $('#product_sku_margin-' + i).val();
            }
        }
        import_yml_product_skus_margin_string += '}';

        $('#import_yml_product_skus_margin').val(import_yml_product_skus_margin_string.replace(',"": 0','').replace(',}','}'));
    }

    function add_disabled_product_name() {

        var import_yml_disabled_product_name_rows_counter = $('.import_yml_disabled_product_name_row').length + 1;

        $(".import_yml_disabled_product_name_button_row").before('<div class="row import_yml_disabled_product_name_row" style="margin-top:10px"><div class="col-sm-3"><input type="text"  id="disabled_product_name-'+import_yml_disabled_product_name_rows_counter+'" class="form-control" placeholder="название товара" onkeyup="get_import_yml_disabled_product_names();"></div></div>');
    }

    function get_import_yml_disabled_product_names() {
        var import_yml_disabled_product_name_string = "[";

        for (var i = 1; i <= $('.import_yml_disabled_product_name_row').length; i++) {
            if ($('#disabled_product_name-' + i).val() == '') {
                continue;
            }

            if (i != $('.import_yml_disabled_product_name_row').length) {
                    import_yml_disabled_product_name_string += '"' + $('#disabled_product_name-' + i).val() + '",';
            } else {
                import_yml_disabled_product_name_string += '"' + $('#disabled_product_name-' + i).val() + '"';
            }
        }
        import_yml_disabled_product_name_string += ']';

        $('#import_yml_disabled_product_name').val(import_yml_disabled_product_name_string);
    }

    function add_disabled_product_sku() {

        var import_yml_disabled_product_sku_rows_counter = $('.import_yml_disabled_product_sku_row').length + 1;

        $(".import_yml_disabled_product_sku_button_row").before('<div class="row import_yml_disabled_product_sku_row" style="margin-top:10px"><div class="col-sm-3"><input type="text"  id="disabled_product_name-'+import_yml_disabled_product_sku_rows_counter+'" class="form-control" placeholder="название товара" onkeyup="get_import_yml_disabled_product_skus();"></div></div>');
    }

    function get_import_yml_disabled_product_skus() {
        var import_yml_disabled_product_sku_string = "[";

        for (var i = 1; i <= $('.import_yml_disabled_product_sku_row').length; i++) {
            if ($('#disabled_product_sku-' + i).val() == '') {
                continue;
            }

            if (i != $('.import_yml_disabled_product_sku_row').length) {
                    import_yml_disabled_product_sku_string += '"' + $('#disabled_product_sku-' + i).val() + '",';
            } else {
                import_yml_disabled_product_sku_string += '"' + $('#disabled_product_sku-' + i).val() + '"';
            }
        }
        import_yml_disabled_product_sku_string += ']';

        $('#import_yml_disabled_product_sku').val(import_yml_disabled_product_sku_string);
    }


    $('.margin').numberMask({type:'int',defaultValueInput:''});
</script>

<?php echo $footer; ?>