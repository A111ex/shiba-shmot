<?php if ($reviews) { ?>
<?php foreach ($reviews as $review) { ?>
  <div class="content testimonial">
    <div class="left">
      <div class="title">
        <span class="name"><?php echo $review['author']; ?></span>
        <span class="info">
          <?= $review['city']?>
          <? if($review['city'] != '') echo " | "; ?>
          <?=$review['date_added']; ?>
        </span>
      </div>

    <div class="description"><?=$review['text']?></div>

    <?php if ($review['image1'] != '' or $review['image2'] != '' or $review['image3'] != '') { ?>
      <div class="photos">
        <?php if ($review['image1'] != '') {
        echo '<a href="' . $review['popup_image1'] . '"><img src="' . $review['image1'] . '"/></a>';
        } ?>
        <?php if ($review['image2'] != '') {
        echo '<a href="' . $review['popup_image1'] . '"><img src="' . $review['image2'] . '"/></a>';
        } ?>
        <?php if ($review['image3'] != '') {
        echo '<a href="' . $review['popup_image1'] . '"><img src="' . $review['image3'] . '"/></a>';
        } ?>
      </div>
    <?php } ?>
    </div>
    <div class="right">
      <?php if ($review['rating']) { ?>
        <?php echo 'Рейтинг'; ?><br>
        <?php for ($i = 1; $i <= 5; $i++) { ?>
        <?php if ($review['rating'] < $i) { ?>
        <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x" style='color: #f00; margin-top:10px;'></i></span>
        <?php } else { ?>
        <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x" style='color: #f00; margin-top:10px;'></i><i class="fa fa-star-o fa-stack-2x" style='color: #f00; margin-top:10px;'></i></span>
        <?php } ?>
        <?php } ?>
      <?php } ?>
    </div>
  </div>
<?php } ?>
<div class="row">
  <div class="col-sm-6 text-left"><?php echo $pagination; ?></div>
  <div class="col-sm-6 text-right"><?php echo $results; ?></div>
</div>
<?php } else { ?>
<p><?php echo $text_no_reviews; ?></p>
<?php } ?>

<script>
  $(document).ready(function() {
	$('.photos').magnificPopup({
		type:'image',
		delegate: 'a',
		gallery: {
			enabled:true
		}
	});
});
</script>