<div id="modal-quickview" class="modal">
  <div class="modal-dialog modal-xl">
    <div class="modal-content">
      <div class="modal-header form-head form-border" id="modalFormHeader">
        <button type="button" class="popup-close" data-dismiss="modal" aria-hidden="true">&times;</button>
        <h2 class="modal-title main-heading"><?php echo $heading_title; ?></h2>
      </div>
      <div class="modal-body">
        <div class="quickview-image">
          <?php if ($thumb) { ?>
          <div class="thumb">
            <img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" class="img-responsive" />
          </div>
          <?php } ?>
          <?php if ($images) { ?>
          <div id="additional-images">
            <?php if ($small_thumb) { ?>
            <img src="<?php echo $small_thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" class="img-thumbnail active" />
            <?php } ?>
            <?php foreach ($images as $image) { ?>
            <img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" class="img-thumbnail"/>
            <?php } ?>
          </div>
          <?php } ?>
        </div>
        <div class="quickview-center">
          <?php if ($manufacturers_img && 1==2) { ?>
          <div class="logo-brand form-group">     
            <a href="<?php echo $manufacturers; ?>"><?php echo ($manufacturers_img) ? '<img src="'.$manufacturers_img.'" data-toggle="tooltip" title="'.$manufacturer.'" />' : '' ;?></a>      
          </div>
          <?php } ?>
          <?php if ($attribute_group) { ?>
          <label class="control-label box-title"><?php echo $text_attribute; ?></label>
          <div class="short-attribute mb-20">
            <?php foreach (array_slice($attribute_group, 0, 8) as $attribute) { ?>
            <div class="short-attribute-list">
              <div class="short-attr-left"><span><?php echo $attribute['name']; ?></span></div>
              <div class="short-attr-right"><span><?php echo $attribute['text']; ?></span></div>
            </div>
            <?php } ?>				
          </div>
          <?php } ?>
          <?php if ($review_status) { ?>
          <div class="rating product-rating form-group">
            <div class="rating-star">
              <?php for ($i = 1; $i <= 5; $i++) { ?>
              <?php if ($rating < $i) { ?>
              <span class="star-empty"></span>
              <?php } else { ?>
              <span class="star"></span>
              <?php } ?>
              <?php } ?>
            </div>
            <div class="rating-count">
              <a href="<?php echo $product_href; ?>"><?php echo $text_review; ?></a>
            </div>
          </div>
          <?php } ?> 
        </div>
        <div class="quick-view-price price-block quick-view-right product-content">
          <div class="form-group product-informer">
            <div class="product-stock">
              <?php if ($quantity <= 0) { ?>
              <span class="text-danger stock"><?php echo $stock; ?></span>
              <?php } else { ?>
              <span class="text-success stock"><?php echo $text_stock; ?></span>
              <?php } ?>
            </div>
            <div class="product-sku">
              <?php if ($sku) { ?>  
              <span class="stock-value"><?php echo $text_sku; ?> <?php echo $sku; ?></span>
              <?php } ?>
            </div>
          </div>
          <?php if ($price) { ?>        
          <div class="price-block__summary">
            <?php if (!$special) { ?>
            <div class="price-block__base m-b-15"><?php echo $price; ?></div>
            <?php } else { ?>
            <div class="price-block__old-price"><?php echo $price; ?></div>
            <div class="price-block__base price-new"><?php echo $special; ?></div>
            <div class="price-block-economy"><span class="economy-text"><?php echo $text_economy; ?></span><span class="price-benefit price-economy"><?php echo $economy; ?></span></div>
            <?php } ?>
            <?php if ($tax) { ?>
            <div class="price-tax m-b-15"><span class="text-muted"><?php echo $text_tax; ?></span> <strong><?php echo $tax; ?></strong></div>
            <?php } ?>                                  
          </div>
          <?php if ($discounts) { ?>
          <div class="all-price m-t-2">
            <?php foreach ($discounts as $discount) { ?>
            <div class="discount-price"><span class="text-muted"><span><?php echo $discount['quantity']; ?></span><?php echo $text_discount; ?></span> &nbsp; <span class="discount-summ"><?php echo $discount['price']; ?></span></div>
            <?php } ?>
          </div>
          <?php } ?>                         		  
          <?php } ?>
          <div class="button-group quickview-buttons">
            

                 
              <div class="product-options">
                <?php if ($options) { ?>			
                <?php foreach ($options as $option) { ?>
                <?php if ($option['type'] == 'select') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                  <select name="option[<?php echo $option['product_option_id']; ?>]" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                    <option value="<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                      <?php if ($option_value['price']) { ?>
                      (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                      <?php } ?>
                    </option>
                    <?php } ?>
                  </select>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'radio') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label   class="control-label"><?php echo $option['name']; ?></label>
                  <div id="input-option<?php echo $option['product_option_id']; ?>">
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                    <div class="radio">

                        <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" id="quickview-option-<?php echo $option_value['product_option_value_id']; ?>" value="<?php echo $option_value['product_option_value_id']; ?>" />
                        <?php if ($option_value['image']) { ?>
                        <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-thumbnail" />
                        <?php } ?>
                        <?php if ($option_value['price']) { ?>
                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                        <?php } ?>
                      <label onclick="reset_in_cart();" for="quickview-option-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?></label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'checkbox') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label"><?php echo $option['name']; ?></label>
                  <div id="input-option<?php echo $option['product_option_id']; ?>">
                    <?php foreach ($option['product_option_value'] as $option_value) { ?>
                    <div class="checkbox fake-checkbox">               
                      <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" id="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>" />
                      <label class="option-btn checkbox-btn" for="<?php echo $option['product_option_id']; ?>_<?php echo $option_value['product_option_value_id']; ?>" <?php if ($option_value['price']) { ?> data-toggle="tooltip" title="<?php echo $option_value['price_prefix']; ?> <?php echo $option_value['price']; ?>" <?php } ?>>
                      <?php if ($option_value['image']) { ?>
                      <img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" class="img-responsive" /> 
                      <?php } ?>
                      <?php echo $option_value['name']; ?>                    
                      </label>
                    </div>
                    <?php } ?>
                  </div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'text') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                  <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'textarea') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                  <textarea name="option[<?php echo $option['product_option_id']; ?>]" rows="5" placeholder="<?php echo $option['name']; ?>" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control"><?php echo $option['value']; ?></textarea>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'file') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label"><?php echo $option['name']; ?></label>
                  <button type="button" id="button-upload<?php echo $option['product_option_id']; ?>" data-loading-text="<?php echo $text_loading; ?>" class="btn btn-default btn-block btn-option"><i class="ion-android-upload fa-lg"></i> <?php echo $button_upload; ?></button>
                  <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" id="input-option<?php echo $option['product_option_id']; ?>" />
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'date') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                  <div class="input-group date">
                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button class="btn btn-primary btn-option" type="button"><i class="ion-calendar fa-lg"></i></button>
                    </span>
                  </div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'datetime') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                  <div class="input-group datetime">
                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="YYYY-MM-DD HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-option"><i class="ion-calendar fa-lg"></i></button>
                    </span>
                  </div>
                </div>
                <?php } ?>
                <?php if ($option['type'] == 'time') { ?>
                <div class="form-group<?php echo ($option['required'] ? ' required' : ''); ?>">
                  <label class="control-label" for="input-option<?php echo $option['product_option_id']; ?>"><?php echo $option['name']; ?></label>
                  <div class="input-group time">
                    <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['value']; ?>" data-date-format="HH:mm" id="input-option<?php echo $option['product_option_id']; ?>" class="form-control" />
                    <span class="input-group-btn">
                    <button type="button" class="btn btn-primary btn-option"><i class="ion-calendar fa-lg"></i></button>
                    </span>
                  </div>
                </div>
                <?php } ?>
                <?php } ?>
                <?php } ?>			
                <?php if ($recurrings) { ?>
                <hr>
                <h3><?php echo $text_payment_recurring; ?></h3>
                <div class="form-group required">
                  <select name="recurring_id" class="form-control">
                    <option value=""><?php echo $text_select; ?></option>
                    <?php foreach ($recurrings as $recurring) { ?>
                    <option value="<?php echo $recurring['recurring_id']; ?>"><?php echo $recurring['name']; ?></option>
                    <?php } ?>
                  </select>
                  <div class="help-block" id="recurring-description"></div>
                </div>
                <?php } ?>
              </div>

              <div class="form-group">
            <?php if ($sizes_help_image) { ?>
                  <div class="sizes-help"><a class="btn-primary btn-danger"  href="<?php echo $sizes_help_image;?>">Таблица размеров</a></div>
                  <script>
                    $('.sizes-help').magnificPopup({
                      type:'image',
                      delegate: 'a',
                      gallery: {
                        enabled:true
                      }
                    });
                    </script>
              <?php } ?>
            </div>

              <div class="button-group-order">

              <div class="btn-incart">
                <button type="button" id="quickview-button-cart" data-toggle-text="<?php echo $text_in_cart; ?>" class="btn btn-primary btn-block"><?php echo $button_cart; ?></button>                                               				    
                <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />			    
              </div>
            </div>
          </div>
          <br>
          <div class="quickview-toolbar-btn">
            <div class="product-button__toolbar">
              <button type="button" class="button-toolbar btn btn-outline btn-xs" onclick="wishlist.add('<?php echo $product_id; ?>');"><?php echo $text_wishlist; ?></button>
              <button type="button" class="button-toolbar btn btn-outline btn-xs" onclick="compare.add('<?php echo $product_id; ?>');"><?php echo $text_compare; ?></button>
            </div>
          </div>
          <div class="form-group product-informer">
            <div class="list-product">
              <!-- <div class="list-value product-model">
                <span class="title-value"><strong><?php echo $text_model; ?></strong> <?php echo $model; ?></span>
              </div> -->
              <?php if ($reward) { ?>
              <div class="list-value product-reward">
                <span class="title-value"><strong><?php echo $text_reward; ?></strong> + <?php echo $reward; ?></span>
              </div>
              <?php } ?>
              <?php if ($points) { ?>
              <!-- <div class="list-value product-points">
                <span class="title-value"><strong><?php echo $text_points; ?></strong> <?php echo $points; ?></span>
              </div> -->
              <?php } ?>
            </div>
          </div>
          <?php if ($minimum > 1) { ?>
          <div class="alert-minimum text-danger"><i class="fa fa-exclamation-circle"></i> <?php echo $text_minimum; ?></div>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>
<script><!--
$('#additional-images img').on("click", function () {
	var activeElement = $(this);
	oldSrc = activeElement.attr('src'),
		newSrc = oldSrc.replace('55x55', '350x350');
	$.when($('#additional-images img').removeClass('active')).then(function () {
		$('.thumb img').attr('src', newSrc);
		activeElement.addClass('active');
	});
});
//-->
</script>
<script><!--
$('#quickview-button-cart').on('click', function () {
  var button = $(this);
  if ($(button).hasClass('in_cart')) {return}
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('#modal-quickview input[type=\'text\'], #modal-quickview input[type=\'hidden\'], #modal-quickview input[type=\'radio\']:checked, #modal-quickview input[type=\'checkbox\']:checked, #modal-quickview select, #modal-quickview textarea'),
		dataType: 'json',
		success: function (json) {
			$('#popup-option, #popup-cart').remove();
			$('.form-group').removeClass('has-error');

			if (json['error']) {
        $('.product-options .text-danger').remove();
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						var element = $('#input-option' + i.replace('_', '-'));

						if (element.parent().hasClass('input-group')) {
              $('.product-options .text-danger').remove();
							element.parent().after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						} else {
							element.after('<div class="text-danger">' + json['error']['option'][i] + '</div>');
						}
					}

				}

				if (json['error']['recurring']) {
					$('select[name=\'recurring_id\']').after('<div class="text-danger">' + json['error']['recurring'] + '</div>');
				}

				// Highlight any found errors
				$('.text-danger').parent().addClass('has-error');
			}

			if (json['success']) {

        $('.product-options input[type=radio]').prop( "checked", false );

				button.removeClass('btn-primary');
				button.addClass('in_cart btn-block');
				button.attr('onclick', 'location.href="<?php echo $cart; ?>"');

				$('.plus-minus').hide();

				var toggleText = button.data('toggle-text');

				button.data('toggle-text', button.text())
					.text(toggleText);

				setTimeout(function () {
					$(".cart-count").html(json.total_products);
					$(".cart-count").addClass('updated-cart-count');
          $(".userblock-text-danger strong").html(json.total_price);

          setTimeout(function () {
            $(".cart-count").removeClass('updated-cart-count');
          }, 300);
        }, 100);

				$(".cart .dropdown-basket").load("index.php?route=common/cart/info .dropdown-basket .dropdown-main");
			}
		},
		error: function (xhr, ajaxOptions, thrownError) {
			alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
		}
	});
});
//-->
</script>
<script><!--
// $('.date').datetimepicker({
// 	pickTime: false
// });
// $('.datetime').datetimepicker({
// 	pickDate: true,
// 	pickTime: true
// });
// $('.time').datetimepicker({
// 	pickDate: false
// });
$('button[id^=\'button-upload\']').on('click', function () {
	var node = this;

	$('#form-upload').remove();

	$('body').prepend('<form enctype="multipart/form-data" id="form-upload" style="display: none;"><input type="file" name="file" /></form>');

	$('#form-upload input[name=\'file\']').trigger('click');

	timer = setInterval(function () {
		if ($('#form-upload input[name=\'file\']').val() != '') {
			clearInterval(timer);

			$.ajax({
				url: 'index.php?route=tool/upload',
				type: 'post',
				dataType: 'json',
				data: new FormData($('#form-upload')[0]),
				cache: false,
				contentType: false,
				processData: false,
				beforeSend: function () {
					$(node).button('loading');
				},
				complete: function () {
					$(node).button('reset');
				},
				success: function (json) {
					$('.product-options .text-danger').remove();

					if (json['error']) {
						$(node).parent().find('input').after('<div class="text-danger">' + json['error'] + '</div>');
					}

					if (json['success']) {
						alert(json['success']);

						$(node).parent().find('input').attr('value', json['code']);
					}
				},
				error: function (xhr, ajaxOptions, thrownError) {
					alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
				}
			});
		}
	}, 500);
});
$('select[name=\'recurring_id\'], input[name="quantity"]').change(function () {
	$.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name=\'product_id\'], input[name=\'quantity\'], select[name=\'recurring_id\']'),
		dataType: 'json',
		beforeSend: function () {
			$('#recurring-description').html('');
		},
		success: function (json) {
			$('.product-options .text-danger').remove();

			if (json['success']) {
				$('#recurring-description').html(json['success']);
			}
		}
	});
});
  
function reset_in_cart() {
    if ($('.in_cart').length) {
        $('#quickview-button-cart').attr('onclick', '');
        $('.in_cart').removeClass('in_cart');

        var toggleText =  $('#quickview-button-cart').data('toggle-text');
        $('#quickview-button-cart').data('toggle-text',  $('#quickview-button-cart').text()).text(toggleText);
    }
}
//-->
</script>