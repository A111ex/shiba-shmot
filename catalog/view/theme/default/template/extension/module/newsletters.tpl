<script>
	function regNewsletter()
	{
		var emailpattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
		var email = $('#txtemail').val();
		
		if(email != "")
		{
			if(!emailpattern.test(email))
			{
				$("#text-danger-newsletter").remove();
				$("#form-newsletter-error").removeClass("has-error");
				$("#newsletter-email").append('<div class="text-danger" id="text-danger-newsletter"><?php echo $error_news_email_invalid; ?></div>');
				$("#form-newsletter-error").addClass("has-error");

				return false;
			}
			else
			{
				$.ajax({
					url: 'index.php?route=extension/module/newsletters/add',
					type: 'post',
					data: 'email=' + $('#txtemail').val(),
					dataType: 'json',
					async:false,

					success: function(json) {

						if (json.message == true) {
							alert('<?php echo $error_newsletter_sent; ?>');
							document.getElementById("form-newsletter").reset();
							return true;						
						}
						else {
							$("#text-danger-newsletter").remove();
							$("#form-newsletter-error").removeClass("has-error");
							$("#newsletter-email").append(json.message);
							$("#form-newsletter-error").addClass("has-error");
						}
					}
				});
				return false;
			}
		}
		else
		{

			$("#text-danger-newsletter").remove();
			$("#form-newsletter-error").removeClass("has-error");
			$("#newsletter-email").append('<div class="text-danger" id="text-danger-newsletter"><?php echo $error_news_email_required; ?></div>');
			$("#form-newsletter-error").addClass("has-error");

			return false;
		}
	}
</script>


<div>
	<form action="" method="post" class="form-horizontal" id="form-newsletter">
		<div class="form-group" id="form-newsletter-error">
			<h3 class="col-xs-12"><?php echo $heading_title; ?></h3>
			<p class="newsletter-info"><?php echo $text_info;?></p>
			<span class="col-xs-8" id="newsletter-email">
				<input type="email" name="txtemail" id="txtemail" value="" placeholder="<?php echo $text_subscribe_placeholder; ?>" class="form-control"/>
			</span>
			<span class="col-xs-3">
				<button type="submit" onclick="return regNewsletter();" class="btn btn-danger" >
					<i class="fa fa-envelope"></i>
					<span class="hidden-xs hidden-xs hidden-md" style="padding-left: 3px;"><?php echo $text_subscribe_btn; ?></span>
				</button>
			</div>
		</div>
	</form>
</div>