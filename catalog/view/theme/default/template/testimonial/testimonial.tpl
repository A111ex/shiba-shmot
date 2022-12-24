<?php echo $header; ?>
<div class="container">
    <ul class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <li><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a></li>
        <?php } ?>
    </ul>
    <div class="row"><?php echo $column_left; ?>
        <?php if ($column_left && $column_right) { ?>
        <?php $class = 'col-sm-6'; ?>
        <?php } elseif ($column_left || $column_right) { ?>
        <?php $class = 'col-sm-9'; ?>
        <?php } else { ?>
        <?php $class = 'col-sm-12'; ?>
        <?php } ?>
        <div id="content" class="<?php echo $class; ?>"><?php echo $content_top; ?>
            <h1><?php echo $heading_title; ?></h1>
            <form class="form-horizontal" id="form-review">
                <?php if ($review_status) { ?>
                <div id="review"></div>
                <?php if ($review_guest) { ?>
                <h2><?php echo $text_write; ?></h2>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-name"><?php echo $entry_name; ?>:</label>
                        <input type="text" name="name" value="<?php echo $customer_name; ?>" id="input-name" class="form-control"/>
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-city"><?php echo 'Город'; ?>:</label>
                        <input type="text" name="city" value="<?php echo $customer_city; ?>" id="input-city" class="form-control"/>
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-email"><?php echo 'E-Mail (не для публикации)'; ?>:</label>
                        <input type="text" name="email" value="<?php echo $customer_email; ?>" id="input-email" class="form-control"/>
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label" for="input-review"><?php echo $entry_review; ?>:</label>
                        <textarea name="text" rows="5" id="input-review" class="form-control"></textarea>

                        <div class="help-block"><?php echo $text_note; ?></div>
                    </div>
                </div>
                <div class="form-group required">
                    <div class="col-sm-12">
                        <label class="control-label"><?php echo $entry_rating; ?>:</label>
                        &nbsp;&nbsp;&nbsp; <?php echo $entry_bad; ?>&nbsp;
                        <input type="radio" name="rating" value="1" />
                        &nbsp;
                        <input type="radio" name="rating" value="2" />
                        &nbsp;
                        <input type="radio" name="rating" value="3" />
                        &nbsp;
                        <input type="radio" name="rating" value="4" />
                        &nbsp;
                        <input type="radio" name="rating" value="5" />
                        &nbsp;<?php echo $entry_good; ?></div>
                </div>
                <div class="form-group">
					
					<span>
						<input style="display:none;" name="image1" id="review-image1" type="file" onchange="changePreview1(this);"/>						
						<div id="image1-text" onclick="uploadImage1(this);" class="upload-image-button">Загрузить фото</div>
					</span>	
					<span>
					<input style="display:none;" name="image2" id="review-image2" type="file" onchange="changePreview2(this);"/>						
						<div id="image2-text" onclick="uploadImage2(this);" class="upload-image-button">Загрузить фото</div>
					</span>	
					<span>
					<input style="display:none;" name="image3" id="review-image3" type="file" onchange="changePreview3(this);"/>						
						<div id="image3-text" onclick="uploadImage3(this);" class="upload-image-button">Загрузить фото</div>
					</span>					
			  	</div>
                <?php if (isset($site_key) && $site_key) { ?>
                <div class="form-group">
                    <div class="col-sm-12">
                        <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
                    </div>
                </div>
                <?php } elseif(isset($captcha) && $captcha){ ?>
                <?php echo $captcha; ?>
                <?php } ?>
                <div class="buttons clearfix">
                    <div class="pull-right">
                        <button type="button" id="button-review" data-loading-text="<?php echo $text_loading; ?>"
                                class="btn btn-primary"><?php echo $button_continue; ?></button>
                    </div>
                </div>
                <?php } else { ?>
                <?php echo $text_login; ?>
                <?php } ?>
                <?php } ?>
            </form>
            <?php echo $content_bottom; ?></div>
        <?php echo $column_right; ?></div>

    <script type="text/javascript"><!--
        $('#review').delegate('.pagination a', 'click', function (e) {
            e.preventDefault();
            $('#review').load(this.href);
        });

        $('#review').load('<?php echo html_entity_decode($review); ?>');

        $('#button-review').on('click', function () {

            var form = $('#form-review')[0];
            var data = new FormData(form);

            $.ajax({
                url: '<?php echo html_entity_decode($write); ?>',
                type: 'post',
                enctype: 'multipart/form-data',
                processData: false,  // Important!
                contentType: false,
                cache: false,
                dataType: 'json',
                data: data,
                beforeSend: function () {
                    if ($("textarea").is("#g-recaptcha-response")) {
                        grecaptcha.reset();
                    }
                    $('#button-review').button('loading');
                },
                complete: function () {
                    $('#button-review').button('reset');
                },
                success: function (json) {
                    $('.alert-success, .alert-danger').remove();
                    if (json['error']) {
                        $('#review').after('<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> ' + json['error'] + '</div>');
                    }
                    if (json['success']) {
                        $('#review').after('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + '</div>');

                        $('input[name=\'name\']').val('');
                        $('input[name=\'city\']').val('');
                        $('input[name=\'email\']').val('');
                        $('input[name=\'image1\']').val('');
                        $('input[name=\'image2\']').val('');
                        $('input[name=\'image3\']').val('');
                        $('#imagePreview1').remove();
                        $('#imagePreview2').remove();
                        $('#imagePreview3').remove();
                        $('#image1-text').show();
                        $('#image2-text').show();
                        $('#image3-text').show();
                        $('textarea[name=\'text\']').val('');
                        $('input[name=\'rating\']:checked').prop('checked', false);
                    }
                }
            });
        });



        
        function uploadImage1(input) {
            $("#review-image1").trigger('click');
        }
            
        function changePreview1(input) {
            if (input.files && input.files[0]) {
                
                if (!input.files[0].type.match('image.*')) {
                    alert("Можно загружать только фотографии");
                    input.value = "";
                } else {
                    $('#imagePreview1').remove();
                    $("#review-image1").after('<img id="imagePreview1" class="imagePreview" onclick="uploadImage1(this);" >');
                    $("#image1-text").hide();
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#imagePreview1')
                            .attr('src', e.target.result)
                            .width(96)
                            .height(96);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
                
                
            }
        }

        function uploadImage2(input) {
            $("#review-image2").trigger('click');
        }
            
        function changePreview2(input) {
            if (input.files && input.files[0]) {
                if (!input.files[0].type.match('image.*')) {
                    alert("Можно загружать только фотографии");
                    input.value = "";
                } else {
                    $('#imagePreview2').remove();
                    $("#review-image2").after('<img id="imagePreview2" class="imagePreview" onclick="uploadImage2(this);" >');
                    $("#image2-text").hide();
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#imagePreview2')
                            .attr('src', e.target.result)
                            .width(96)
                            .height(96);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        }

        function uploadImage3(input) {
            $("#review-image3").trigger('click');
        }
            
        function changePreview3(input) {
            if (input.files && input.files[0]) {
                if (!input.files[0].type.match('image.*')) {
                    alert("Можно загружать только фотографии");
                    input.value = "";
                } else {
                    $('#imagePreview3').remove();
                    $("#review-image3").after('<img id="imagePreview3" class="imagePreview" onclick="uploadImage3(this);" >');
                    $("#image3-text").hide();
                    var reader = new FileReader();

                    reader.onload = function (e) {
                        $('#imagePreview3')
                            .attr('src', e.target.result)
                            .width(96)
                            .height(96);
                    };

                    reader.readAsDataURL(input.files[0]);
                }
            }
        }
        //--></script>
</div>
<?php echo $footer; ?>