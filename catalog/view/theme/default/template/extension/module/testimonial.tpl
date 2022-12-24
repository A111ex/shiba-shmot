<?php if($layout==1){ ?>
<style type="text/css">
    .horizontal-sreview .review-author {
        font-size: 14px;
    }
    .horizontal-sreview .review-date-added {
        color: #999;
        margin-left: 10px;
    }
    .horizontal-sreview-all {
        float: right;
        margin: 0 25px 0 0;
        width: 100%;
        text-align: right;
    }
    .horizontal-sreview {
        border: 1px solid #ddd;
        margin-bottom: 20px;
        overflow: auto;
    }
    .horizontal-sreview .caption {
        padding: 15px 20px;
        min-height: 100px;
    }
</style>
<?php if($heading_title){ ?>
<h3><?php echo $heading_title; ?></h3>
<?php } ?>
<div class="row">
    <?php foreach ($reviews as $review) { ?>
    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
        <div class="horizontal-sreview transition">
            <div class="caption review-caption">
                <span class="review-author"><?php echo $review['author']; ?></span>
                <span class="review-date-added"><?php echo $review['date_added']; ?></span>
                <div class="rating">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if ($review['rating'] < $i) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"
                                             style='color: #FC0;'></i></span>
                    <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"
                                             style='color: #FC0;'></i><i
                            class="fa fa-star-o fa-stack-2x"
                            style='color: #E69500;'></i></span>
                    <?php } ?>
                    <?php } ?>
                </div>
                <p><?php echo $review['text']; ?></p>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($button_all){ ?>
    <div class="horizontal-sreview-all"><a href="<?php echo $keyword; ?>"><?php echo $button_all_text; ?></a></div>
    <?php } ?>
</div>
<?php }else{ ?>
<style type="text/css">
    .vertical-sreview .review-author {
        font-size: 14px;
    }
    .vertical-sreview .review-date-added {
        color: #999;
        margin-left: 10px;
    }
    .vertical-sreview-all {
        float: right;
        margin: 0px 25px 40px 0px;
        width: 100%;
        text-align: right;
    }
    .vertical-sreview {
        border: 1px solid #ddd;
        margin-bottom: 20px;
        overflow: auto;
    }
    .vertical-sreview .caption {
        padding: 15px 20px;
        min-height: 100px;
    }
</style>
<?php if($heading_title){ ?>
<h3><?php echo $heading_title; ?></h3>
<?php } ?>
<div class="row">
    <?php foreach ($reviews as $review) { ?>
    <div class="product-layout col-lg-4 col-md-4 col-sm-6 col-xs-12">
        <div class="vertical-sreview transition">
            <div class="caption review-caption">
                <span class="review-author"><?php echo $review['author']; ?></span>
                
                <span class="review-date-added" style="float:right"><?php echo $review['date_added']; ?></span>
                <span class="review-date-added" style="float:right"><?= $review['city']?></span>
                <div class="rating" style="margin-top:10px; margin-bottom:10px">
                    <?php for ($i = 1; $i <= 5; $i++) { ?>
                    <?php if ($review['rating'] < $i) { ?>
                <span class="fa fa-stack"><i class="fa fa-star-o fa-stack-2x"
                                             style='color: #f00'></i></span>
                    <?php } else { ?>
                <span class="fa fa-stack"><i class="fa fa-star fa-stack-2x"
                                             style='color: #f00;'></i><i
                            class="fa fa-star-o fa-stack-2x"
                            style='color: #f00;'></i></span>
                    <?php } ?>
                    <?php } ?>
                </div>
                <p><?php echo $review['text']; ?></p>
                <?php if ($review['image1'] != '' or $review['image2'] != '' or $review['image3'] != '') { ?>
                    <div class="photos">
                        <?php if ($review['image1'] != '') {
                        echo '<a href="' . $review['popup_image1'] . '"><img src="' . $review['image1'] . '"/></a>';
                        } ?>
                        <?php if ($review['image2'] != '') {
                        echo '<a href="' . $review['popup_image2'] . '"><img src="' . $review['image2'] . '"/></a>';
                        } ?>
                        <?php if ($review['image3'] != '') {
                        echo '<a href="' . $review['popup_image3'] . '"><img src="' . $review['image3'] . '"/></a>';
                        } ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    </div>
    <?php } ?>
    <?php if($button_all){ ?>
    <div class="vertical-sreview-all"><a class="btn btn-danger" href="<?php echo $keyword; ?>"><?php echo $button_all_text; ?></a></div>
    <?php } ?>
</div>
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