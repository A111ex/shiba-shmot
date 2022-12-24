<?php echo $header; ?>
<div class="container">
<ul class="breadcrumb">
<?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
        </ul>
        <?php if ($error_warning) { ?>
            <div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $error_warning; ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
                </div>
                <?php } ?>
                <div class="row"><?php echo $column_left; ?>
                <?php if ($column_left && $column_right) { ?>
                    <?php $class = 'col-sm-6'; ?>
                        <?php } elseif ($column_left || $column_right) { ?>
                            <?php $class = 'col-sm-9'; ?>
                                <?php } else { ?>
                                    <?php $class = 'col-sm-12'; ?>
                                        <?php } ?>
                                        <div id="content">
                                        <h1 style="text-align: center"><?=$heading_title?></h1>

                                        <div class="">
                                        <div class="checkout checkout-checkout">

                                        <?=$content_top; ?>

                                        <div class="payment">

                                        <div class="col-lg-8 col-md-12 col-sm-12 col-xs-12 payment-data">
                                        <div class="t-head">
                                        <?=$text_customer?>
                                        </div>
                                        <?php if(!$c_logged) {?>
                                        <div id="login_warning" class='checkout-content note text-warning'>
                                            <?=$text_notlogged?>

                                        </div>

                                        <?php } ?>
                                        <div id="payment-address">
                                        <div class="checkout-content" style="overflow: hidden; display: block;">
                                        <div class="fields-group-2">

                                            <div class="col-xs-12 col-sm-4">
                                                <?php if($c_logged) {?>
                                                    <label for="lastname-ch"><span class="required">*</span>  <?=$text_lastname?>:</label><br>
                                                    <input type="text" class="form-control large-field" id="lastname-ch" name="lastname"  value="<?php echo $lastname; ?>" />
                                                    <span class="error"></span>
                                                <?php } else { ?>
                                                    <label for="lastname-ch"><span class="required">*</span>   <?=$text_lastname?>:</label><br>
                                                    <input type="text" id="lastname-ch" name="lastname" value="" class="form-control large-field">
                                                    <span class="error"></span>
                                                <?php }?>
                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <?php if($c_logged) {?>
                                                    <label for="firstname-ch"><span class="required">*</span>  <?=$text_firstname?>:</label><br>
                                                    <input type="text" class="form-control large-field" id="firstname-ch" name="firstname"  value="<?php echo $firstname; ?>" />
                                                    <span class="error"></span>
                                                <?php } else { ?>
                                                    <label for="firstname-ch"><span class="required">*</span>   <?=$text_firstname?>:</label><br>
                                                    <input type="text" id="firstname-ch" name="firstname" value="" class="form-control large-field">
                                                    <span class="error"></span>
                                                <?php }?>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <?php if($c_logged) {?>
                                                    <label for="middlename-ch"><span class="required">*</span>  <?=$text_middlename?>:</label><br>
                                                    <input type="text" class="form-control large-field" id="middlename-ch" name="middlename"  value="<?php echo $middlename; ?>" />
                                                    <span class="error"></span>
                                                <?php } else { ?>
                                                    <label for="middlename-ch"><span class="required">*</span>   <?=$text_middlename?>:</label><br>
                                                    <input type="text" id="middlename-ch" name="middlename" value="" class="form-control large-field">
                                                    <span class="error"></span>
                                                <?php }?>
                                            </div>

                                        </div>

                                        <div class="fields-group-2">
                                            <div class="col-xs-12 col-sm-4">
                                                <label for="telephone-ch"><span class="required">*</span>   <?=$text_telephone?>:</label><br>
                                                <input type="tel" id="telephone-ch" name="telephone"
                                                value="<?php echo $telephone; ?>" class="form-control large-field">
                                                <span class="error"></span>

                                            </div>

                                            <div class="col-xs-12 col-sm-4">
                                                <label for="email-ch"><span class="required">*</span>   <?=$text_email?>:</label><br>
                                                <input type="text" id="email-ch" name="email" value="<?php echo $email; ?>"
                                                class="form-control large-field">
                                                <span class="error"></span>

                                            </div>
                                            <?php foreach ($custom_fields as $custom_field) { ?>
      <?php if ($custom_field['location'] == 'account') { ?>
      <?php if ($custom_field['type'] == 'select') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>:</label>
        <select name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control">
          <option value=""><?php echo $text_select; ?></option>
          <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <option value="<?php echo $custom_field_value['custom_field_value_id']; ?>"><?php echo $custom_field_value['name']; ?></option>
          <?php } ?>
        </select>
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'radio') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label"><?php echo $custom_field['name']; ?>:</label>
        <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
          <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <div class="radio">
            <label>
              <input type="radio" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
              <?php echo $custom_field_value['name']; ?></label>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'checkbox') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label"><?php echo $custom_field['name']; ?>:</label>
        <div id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>">
          <?php foreach ($custom_field['custom_field_value'] as $custom_field_value) { ?>
          <div class="checkbox">
            <label>
              <input type="checkbox" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>][]" value="<?php echo $custom_field_value['custom_field_value_id']; ?>" />
              <?php echo $custom_field_value['name']; ?></label>
          </div>
          <?php } ?>
        </div>
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'text') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>:</label>
        <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php if (isset($account_custom_field[1])) {echo $account_custom_field[1];} ?>" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'textarea') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>:</label>
        <textarea name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" rows="5" placeholder="<?php echo $custom_field['name']; ?>" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control"><?php echo $custom_field['value']; ?></textarea>
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'file') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label"><?php echo $custom_field['name']; ?>:</label>
        <br />
        <button type="button" id="button-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default"><i class="fa fa-upload"></i> <?php echo $button_upload; ?></button>
        <input type="hidden" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" />
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'date') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>:</label>
        <div class="input-group date">
          <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'time') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>:</label>
        <div class="input-group time">
          <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
      <?php } ?>
      <?php if ($custom_field['type'] == 'datetime') { ?>
      <div id="payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-group custom-field col-xs-12 col-sm-4" data-sort="<?php echo $custom_field['sort_order']; ?>">
        <label class="control-label" for="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>"><?php echo $custom_field['name']; ?>:</label>
        <div class="input-group datetime">
          <input type="text" name="custom_field[<?php echo $custom_field['location']; ?>][<?php echo $custom_field['custom_field_id']; ?>]" value="<?php echo $custom_field['value']; ?>" placeholder="<?php echo $custom_field['name']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-payment-custom-field<?php echo $custom_field['custom_field_id']; ?>" class="form-control" />
          <span class="input-group-btn">
          <button type="button" class="btn btn-default"><i class="fa fa-calendar"></i></button>
          </span></div>
      </div>
      <?php } ?>
      <?php } ?>
      <?php } ?>
                                        </div>

                                        <div class="fields-group-2">
                                            <div class="form-group col-xs-12 col-sm-4 required">
                                                <label class="control-label" for="country-ch"><?php echo $entry_country; ?></label>
                                                <select name="country_id" id="country-ch" class="form-control">
                                                <option value=""><?php echo $text_select; ?></option>
                                                <?php foreach ($countries as $country) { ?>
                                                <?php if ($country['country_id'] == $country_id || $country['country_id'] == '176') { ?>
                                                <option value="<?php echo $country['country_id']; ?>" selected="selected"><?php echo $country['name']; ?></option>
                                                <?php } else { ?>
                                                <option value="<?php echo $country['country_id']; ?>"><?php echo $country['name']; ?></option>
                                                <?php } ?>
                                                <?php } ?>
                                                </select>
                                                <span class="error"></span>
                                            </div>
                                            <div class="form-group col-xs-12 col-sm-4 required">
                                                <label class="control-label" for="zone-ch"><?php echo $entry_zone; ?></label>
                                                <select name="zone_id" id="zone-ch" class="form-control">
                                                </select>
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-4">
                                                <label for="postcode-ch"><span class="required">*</span>   <?=$text_postcode?>:</label><br>
                                                <input type="text" id="postcode-ch" name="postcode_fastorder" onkeyup="getShippingMethods()" value="<?php echo $postcode_fastorder; ?>"
                                                class="form-control large-field">
                                                <span class="error"></span>
                                            </div>
                                        </div>
                                        <div class="fields-group-2">
                                            <div class="col-xs-12 col-sm-4">
                                                <label for="city-ch"><span class="required">*</span>   <?=$text_town?>:</label><br>
                                                <input type="text" id="city-ch" name="city" value="<?php echo $city; ?>"
                                                class="form-control large-field">
                                                <span class="error"></span>
                                            </div>
                                            <div class="col-xs-12 col-sm-8">
                                                <label for='address_1'> <?=$text_delivery_type_2?>:</label><br/>
                                                <input type="text" name="address_1" id="address_1" value="<?php echo $address_1 ?>"
                                                class="form-control large-field" placeholder="  <?=$text_delivery_placeholder?>">
                                                <span class="error"></span>
                                           </div>
                                        </div>


                                        <div class="fields-group">
                                            <label id="delivery-name" for="delivery">  <?=$text_delivery_method?>:</label> <i id="delivery-icon"></i><br>
                                            <div id="shipping-method-block">
                                                <?php if ( isset($shipping_methods[0]) ){ ?>
                                                    <select onChange="updateShipping()" name="shipping_method" id="shipping-method" class="form-control large-field">

                                                    <?php foreach ($shipping_methods as $cur_shipping_method) { ?>
                                                        <?php foreach ($cur_shipping_method as $shipping_method) { ?>
                                                        <option

                                                        value='{"title": "<?php echo $shipping_method['title'] ?>", "code": "<?php echo $shipping_method['value'] ?>", "comment":"", "shipping_method":"<?php echo $shipping_method['value'] ?>", "cost":"<?php echo $shipping_method['cost'] ?>","tax_class_id":""}'
                                                            class="form-control large-field <?= substr($shipping_method['value'], strpos($shipping_method['value'], '.')+1 )?>"><?php echo $shipping_method['title'] ?></option>

                                                        <?php } ?>
                                                    <?php } ?>
                                                </select>

                                                <?php } else { ?>
                                                    <label class="text-danger" >Введите почтовый индекс для выбора способа доставки</label>
                                                <?php } ?>
                                            </div>
                                        <span class="error"></span>
                                        <br>
                                                <!--
                                                <div class="group-check">
                                                <label><input id="to-office" class="delivery-type" type="radio"
                                                name="delivery-type" checked="checked"
                                                value=" <?=$text_delivery_type_1?>">   <?=$text_delivery_type_1?></label> &nbsp;&nbsp;
                                                <label><input id="to-address" class="delivery-type" type="radio"
                                                name="delivery-type" value=" <?=$text_delivery_type_2?>">   <?=$text_delivery_type_2?></label>
                                                </div>
                                                -->
                                                <input type='hidden' name='delivery-type' value='delivery' />
                                                
                                                <div style="font-size: 16px;"> 
                                                    <span style="font-weight: 700;">Оплатить заказ можно по следующим реквизитам:</span> <br>
                                                    <span style="font-weight: 700;">Сбербанк: </span> 2202 2023 9742 2837 <br>
                                                    <span style="font-weight: 700;">Тинькофф: </span> 5536 9140 9595 3836 <br>
                                                    <span style="font-weight: 700;">Альфа-банк: </span> 5486 7320 2210 5001 <br><br>
                                                    
                                                    <span>Прислать скриншот чека Вы можете в наш <a style="color: blue; text-decoration: underline;" href="https://wa.me/79180960022">WhatsApp</a></span>
                                                </div>


                                                </div>
                                                <div class="fields-group d-none" style="">
                                                <label for="payment_select">  <?=$text_payment_method?>:</label><br>
                                                <select id="payment_select" name="payment_method" class="form-control large-field">
                                                <?php foreach ($payment_methods as $payment_method) { ?>
                                                    <option
                                                        value='{"title": "<?php echo $payment_method['title'] ?>", "code": "<?php echo $payment_method['code'] ?>"}'
                                                        class="payment_method_value <?php echo $payment_method['code']?>"
                                                        style=""><?php echo $payment_method['title'] ?></option>
                                                        <?php } ?>
                                                        </select>
                                                        </div>

                                                        <div class="fields-group">
                                                        <label for="comment_field">  <?=$text_comment?>:</label><br>
                                                        <input type="text" id="comment_field" class="form-control large-field" name="comment"
                                                        value="<?php echo $comment ?>">
                                                        </div>
                                                        </div>
                                                        <div class="fields-group">
                                                        <?php if ($modules) { ?>
                                                            <div>
                                                                <?php foreach ($modules as $module) { ?>
                                                                    <?php echo $module; ?>
                                                                        <?php } ?>
                                                                        </div>
                                                                        <?php } ?>
                                                                        </div>

                                                                        </div>
                                                                       </div>
                                                                        <div class="col-lg-4 col-md-12 col-sm-12 col-xs-12 checkout-data">
                                                                        <div class="cart-info table-responsive">
                                                                        <table class="table">
                                                                        <thead>
                                                                        <tr>
                                                                        <td class="name t-head" colspan="2">  <?=$text_product?></td>
                                                                        <td class="price t-head"><?=$text_price?></td>
                                                                        <td class="quantity t-head"><?=$text_quantity?></td>
                                                                        </tr>
                                                                         </thead>
                                                                        <tbody>
                                                                        <?php foreach ($products as $product) { ?>
                                                                            <tr>
                                                                                <td class="image">
                                                                                    <a href="/index.php?route=product/product&product_id=<?php echo $product['product_id'] ?>"
                                                                                       title="<?php echo $product['name'] ?>">

                                                                                        <img src="<?php echo  $product['thumb'] ?>" style="max-width: 100px" alt="<?php echo $product['name'] ?>">
                                                                                    </a>

                                                                                </td>
                                                                                <td class="name">
                                                                                <a href="/index.php?route=product/product&product_id=<?php echo $product['product_id'] ?>"><?php echo $product['name'] ?></a>
                                                                                <div class="p-model">
                                                                                <?php echo $product['model'] ?>                                </div>
                                                                                <div class="cart-option">
                                                                                <?php foreach ($product['option'] as $option) { ?>
                                                                                    -
                                                                                        <small><?php echo $option['name']; ?>
                                                                                        : <?php echo $option['value']; ?></small><br/>
                                                                                        <?php } ?>
                                                                                        <?php if ($product['recurring']): ?>
                                                                                        -
                                                                                        <small><?php echo $text_payment_profile ?>
                                                                                        : <?php echo $product['profile_name'] ?></small>
                                                                                        <?php endif; ?>
                                                                                        </div>
                                                                                        </td>
                                                                                        <td class="price" nowrap><?php echo $product['price'] ?>   </td>
                                                                                        <td class="quantity"><?php echo $product['quantity'] ?>   </td>
                                                                                        </tr>

                                                                                        <?php } ?>
                                                                                        </table>
                                                                                        <hr/>
                                                                                        <table id='totals' class='table'>
                                                                                        <tbody>
            <?php foreach ($totals as $total) { ?>
            <tr class="subtotal">
              <td class="name subtotal"><strong><?php echo $total['title']; ?>:</strong></td>
              <td nowrap class="price"><?php echo $total['text']; ?></td>
            </tr>
            <?php } ?>

                                                                                        </tbody>
                                                                                        </table>
                        <div class="txt_zak">
                        Нажимая на кнопку ОФОРМИТЬ ЗАКАЗ Вы даете согласие на обработку указанных персональных данных в соответствии с Федеральным законом №152‑ФЗ «О персональных данных» от 27.07.2006 года и подтверждаете, что ознакомлены с <a href="/privacy" target="_blank">политикой безопасности </a>компании
                        </div>

                                                                    <div id="confirm">
                                                                        <div class="payment">

                                                                            <button id="ajax-button-confirm" class=" btn btn-lg btn-success">
                                                                            <?=$text_confirm?>
                                                                            </button>
                                                                            
                                                                            <button id="button-modify" class=" btn btn-lg btn-cancel">
                                                                            <?=$text_cart?>
                                                                            </button>
                                                                            
                                                                        </div>
                                                                    </div>

                                                                                        </div>
                                                                                                                                                                                <div class="col-xs-12 checkout-subinfo">
                                                                                        <?=$content_bottom?>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        </div>
                                                                                        <?php echo $column_right; ?></div>
                                                                                        </div>
                    <div id="LoginModal" class="modal fade" role="dialog">
                        <div class="modal-dialog">

                            <!-- Modal content-->
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                                    <h3><?php echo $text_returning_customer; ?></h3>
                                </div>
                                <div class="modal-body">

                                    <p><strong><?php echo $text_i_am_returning_customer; ?></strong></p>
                                    <form  method="post" enctype="multipart/form-data">
                                        <div class="form-group">
                                            <label class="control-label" for="input-email"><?php echo $entry_email; ?></label>
                                            <input type="text" name="email" value="<?php echo $email; ?>" placeholder="<?php echo $entry_email; ?>" id="input-email" class="form-control" />
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label" for="input-password"><?php echo $entry_password; ?></label>
                                            <input type="password" name="password" value="" placeholder="<?php echo $entry_password; ?>" id="input-password" class="form-control" />
                                            <a class="pull-right" href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a></div>
                                        <div class="btn btn-primary submit-login-form" ><?php echo $button_login; ?></div>
                                        <div class="text-right">
                                            <a href="<?php echo $register ?>"> <?=$text_register;?></a>

                                        </div>

                                    </form>
                                    <div class="errors-block"></div>
                                </div>
                                </div>

                            </div>

                        </div>
                    </div>
    <div class="hiden_payment_info"  style="display:none;"></div>
            </div>
    <script type="text/javascript"><!--

function updateShipping() {
    var data = $('#shipping-method').val();
    shp = JSON.parse(data)
    $.ajax({
        url: 'index.php?route=checkout/shipping_method/save',
        type: 'post',
        data: shp,
        dataType: 'json',
        beforeSend: function() {
            $('#shipping-method').addClass('loading');
		},
        success: function(json) {
            $('.alert, .text-danger').remove();

            //if (json['redirect']) {
            //    location = json['redirect'];
            //}
            if (json['error']) {
                if (json['error']['warning']) {
                 // Error ylanyrkkaan....
                 alert(json['error']['warning']);
                }
            } else {
                $.ajax({
                    url: 'index.php?route=checkout/onepagecheckout/totals',
                    type: 'get',
                    success: function(json) {
                        $('#totals tbody').remove();
                        $('#totals').append('<tbody></tbody');
                        for (t in json['totals']) {
                            $('#totals tbody').append('<tr class="name subtotal"><td class="name subtotal"><strong>'+json['totals'][t]['title']+'</strong></td><td nowrap class="price">'+json['totals'][t]["text"]+'</td></tr>');
                            }
                    }
                    });
                // Update Totalsi!
            }
            $('#shipping-method').removeClass('loading');
        },
        error: function(xhr, ajaxOptions, thrownError) {
            alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
        }
        }
        ); //ajax
}


function getShippingMethods() {

    var postcode = $('#postcode-ch').val();

    if (postcode.length == 6) {
        $.ajax({
        url: 'index.php?route=checkout/onepagecheckout/getShippingMethods',
        type: 'post',
        data: 'postcode_fastorder='+postcode,
        dataType: 'json',
        beforeSend: function() {
            $('#shipping-method-block').empty();
            $('#delivery-name').text('Рассчитываем стоимость доставки');
            $('#delivery-icon').addClass('fa fa-circle-o-notch fa-spin');
		},
        success: function(json) {
            console.log(json);
            if (json.error) {
                $('#shipping-method-block').empty();
                $('#shipping-method-block').append('<label class="text-danger">'+json.error+'</label>');
            } else {
                var options = '';
                for (i in json["shipping_methods"]) {
                    options += '<option value>Выберите способ доставки</option>';
                    for (j in json["shipping_methods"][i]) {
                        options += '<option value=\'{"title": "'+json["shipping_methods"][i][j].title +'", "code": "'+json["shipping_methods"][i][j].value+'", "comment":"", "shipping_method":"'+json["shipping_methods"][i][j].value+'", "cost":"'+json["shipping_methods"][i][j].cost+'","tax_class_id":""}\' class="form-control large-field '+json["shipping_methods"][i][j].class +'">'+json["shipping_methods"][i][j].title +'</option>';
                    }
                }

                $('#shipping-method-block').empty();
                $('#shipping-method-block').append('<select onChange="updateShipping()" name="shipping_method" id="shipping-method" class="form-control large-field">'+options+'</select>');

            }

            $('#delivery-icon').removeClass('fa fa-circle-o-notch fa-spin');
            $('#delivery-name').text('Способ доставки:');
        },
        error: function() {
            $('#delivery-icon').removeClass('fa fa-circle-o-notch fa-spin');
            $('#delivery-name').text('Не удалось рассчитать стоимость доставки для указанного почтового индекса');
        }
    });
    } else {
        $('#shipping-method-block').empty();
        $('#shipping-method-block').append('<label class="text-danger">Введите почтовый индекс для выбора способа доставки</label>');
    }

}

$(document).ready(function () {


    $('select[name=\'country_id\']').on('change', function() {
        $.ajax({
            url: 'index.php?route=extension/total/shipping/country&country_id=' + this.value,
            dataType: 'json',
            beforeSend: function() {
                $('select[name=\'country_id\']').after(' <i class="fa fa-circle-o-notch fa-spin"></i>');
            },
            complete: function() {
                $('.fa-spin').remove();
                getShippingMethods();
            },
            success: function(json) {
                if (json['postcode_required'] == '1') {
                    $('input[name=\'postcode\']').parent().parent().addClass('required');
                } else {
                    $('input[name=\'postcode\']').parent().parent().removeClass('required');
                }

                html = '<option value=""> --- Выберите --- </option>';

                if (json['zone'] && json['zone'] != '') {
                    for (i = 0; i < json['zone'].length; i++) {
                        html += '<option value="' + json['zone'][i]['zone_id'] + '"';

                        if (json['zone'][i]['zone_id'] == '0') {
                            html += ' selected="selected"';
                        }

                        html += '>' + json['zone'][i]['name'] + '</option>';
                    }
                } else {
                    html += '<option value="0" selected="selected"> --- Не выбрано --- </option>';
                }

                $('select[name=\'zone_id\']').html(html);
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    });

    setTimeout("$('#country-ch').trigger('change');",2000);






    $(' #LoginModal .submit-login-form ').on('click', function(){
        $.ajax({
                url: 'index.php?route=checkout/onepagecheckout/AjaxLogin',
                type: 'post',
                data: $('#LoginModal #input-email, #LoginModal #input-password '),
                dataType: 'json',
                beforeSend: function() {

                },
                success: function(json) {
                    console.log(json);
                   if(json.errors!=0){
                       if(typeof json.errors.warning!='undefined' && json.errors.warning!='')
                       $('#LoginModal .errors-block').html(json.errors.warning) ;
                       if(typeof json.errors.errors!='undefined'&& json.errors.errors!='')
                       $('#LoginModal .errors-block').append( '<br>' + json.errors.error ) ;
                   }
                   else if(json.errors==0){
                       $('#firstname-ch').prop('value',json.firstname);
                       $('#middlename-ch').prop('value',json.middlename);
                       $('#lastname-ch').prop('value',json.lastname);
                       $('#city-ch').prop('value',json.city);
                       $('#postcode-ch').prop('value',json.postcode);
                       $('#address_1').prop('value',json.address_1);
                       $('#email-ch').prop('value',json.email);
                       $('#telephone-ch').prop('value',json.telephone);
                       $('#LoginModal').modal('hide');
                       $('#login_warning').html('');
                   }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
                }
            }
        ); //ajax
        return false;
    });

    $('#ajax-button-confirm').on('click', function () {

        $.ajax({
            url: 'index.php?route=checkout/onepagecheckout',
            type: 'post',
            data: $('.checkout-checkout .payment-data input[type=\'text\']:not(#input-postcode), .checkout-checkout .payment-data input[type=\'tel\'], .checkout-checkout .payment-data input[type=\'radio\']:checked, .checkout-checkout .payment-datainput input[type=\'checkbox\']:checked, .checkout-checkout .payment-data  select:not(#input-country, #input-zone) '),
            dataType: 'json',
            beforeSend: function () {
                $('#ajax-button-confirm').prop( "disabled", true );
                $('#button-modify').prop( "disabled", true );
                $('#ajax-button-confirm').addClass('preloader');

            },
            complete: function () {
                $('#ajax-button-confirm').prop( "disabled", false );
                $('#button-modify').prop( "disabled", false );
                $('#ajax-button-confirm').removeClass('preloader');

            },
            success: function (json) {
                console.log(json);

                if (json.error) {
                    $(".error").html('');

                    if (json['error']['lastname']) {
                        $('#lastname-ch+.error').html(json['error']['lastname']);
                    }

                    if (json['error']['firstname']) {
                        $('#firstname-ch+.error').html(json['error']['firstname']);
                    }

                    if (json['error']['middlename']) {
                        $('#middlename-ch+.error').html(json['error']['middlename']);
                    }

                    if (json['error']['email']) {
                        $('#email-ch+.error').html(json['error']['email']);
                    }

                    if (json['error']['telephone']) {
                        $('#telephone-ch+.error').html(json['error']['telephone']);
                    }

                    if (json['error']['address_1']) {
                        $('#address_1+.error').html(json['error']['address_1']);
                    }

                    if (json['error']['city']) {
                        $('#city-ch+.error').html(json['error']['city']);
                    }

                    if (json['error']['postcode']) {
                        $('#postcode-ch+.error').html(json['error']['postcode']);
                    }

                    if (json['error']['country']) {
                        $('#country-ch+.error').html(json['error']['country']);
                    }

                    if (json['error']['zone']) {
                        $('#zone-ch+.error').html(json['error']['zone']);
                    }

                    if (json['error']['shipping_method']) {
                        $('#shipping-method-block+.error').html(json['error']['shipping_method']);
                    }

                    let el = undefined;
                    $('.error').each(function (index, element) {

                        if (
                            $(element).html() !== '' &&
                            el == undefined
                            ) {
                            el = element;
                        }

                    });
                    $('html, body').animate({
                        scrollTop: $(el).offset().top - 120  // класс объекта к которому приезжаем
                    }, 1000); // Скорость прокрутки
                }

                else if(json['cod']) {
                    $.ajax({
                        type: 'get',
                        url: 'index.php?route=extension/payment/cod/confirm',
                        cache: false,
                        beforeSend: function() {
                            $('#ajax-button-confirm').button('loading');
                        },
                        complete: function() {
                            $('#ajax-button-confirm').button('reset');
                        },
                        success: function() {
                            location = 'index.php?route=checkout/success';
                        }
                    });
                }

                else if(json['payment']) {
                    $('.hiden_payment_info').html(json['payment']);
                    console.log($('.hiden_payment_info a').attr('href'));
                    location = $('.hiden_payment_info a').attr('href');
                }
                else {
                    if (json.credit)
                        credit_confirm('/index.php?route=checkout/part_payment_cart/getResult&from_privat24=true');
                /* else
                        location = 'index.php?route=checkout/success'*/

                }
            },
            error: function (xhr, ajaxOptions, thrownError) {
                    alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });


    });

    $('#button-modify').on('click', function () {
        location = 'index.php?route=checkout/cart';
    });


});
//--></script>

<?php echo $footer; ?>
