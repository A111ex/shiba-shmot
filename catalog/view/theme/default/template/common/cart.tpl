<div class="userblock cart">
  <div class="dropdown">
    <a href="<?php echo $checkout; ?>" class="userblock-link dropdown-toggle">
      <span class="userblock-cont">
        <div class="userblock-icon">
		  <span class="userblock-count cart-count"><?php echo $text_items; ?></span>
		  <div class="userblock-svg">
            <svg width="42" height="42" viewBox="0 0 14 14" fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="icon icon-cart"><path fill-rule="evenodd" clip-rule="evenodd" d="M1 1H3L5 8H12L13.6667 3L14 2H12.9459H4.5L4 0H3.7543H3H1H0V1H1ZM4.61144 3L5.7543 7H11.2792L12.6126 3H4.61144ZM5.5 11C5.77614 11 6 10.7761 6 10.5C6 10.2239 5.77614 10 5.5 10C5.22386 10 5 10.2239 5 10.5C5 10.7761 5.22386 11 5.5 11ZM5.5 12C6.32843 12 7 11.3284 7 10.5C7 9.67157 6.32843 9 5.5 9C4.67157 9 4 9.67157 4 10.5C4 11.3284 4.67157 12 5.5 12ZM10.5 11C10.7761 11 11 10.7761 11 10.5C11 10.2239 10.7761 10 10.5 10C10.2239 10 10 10.2239 10 10.5C10 10.7761 10.2239 11 10.5 11ZM10.5 12C11.3284 12 12 11.3284 12 10.5C12 9.67157 11.3284 9 10.5 9C9.67157 9 9 9.67157 9 10.5C9 11.3284 9.67157 12 10.5 12Z"></path></svg>
          </div>
        </div>
        <span class="userblock-info">
          <div class="userblock-text"><?php echo $text_basket; ?></div>
          <div class="userblock-text-danger"><strong class="cart-total"><?php echo $total_price; ?></strong></div>
        </span>
        
      </span>
    </a>
    <div class="dropdown-basket dropdown-menu dropdown-menu-right">
      <?php if ($products || $vouchers) { ?>
        <div class="dropdown-main">
          <div class="dropdown-title"><?php echo $text_your_basket; ?></div>
          <div class="mini-basket">
            <?php foreach ($products as $product) { ?>
            <div class="product-block flexbox">
              <div class="img-content">
                <?php if ($product['thumb']) { ?>
                <a href="<?php echo $product['href']; ?>"><img class="product-image img-responsive" src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-responsive" /></a>
                <?php } ?>
                <span class="product-quantity"><?php echo $product['quantity']; ?>x</span>
              </div>
              <div class="right-block">
                <span class="product-name"><a href="<?php echo $product['href']; ?>" class="black"><?php echo $product['name']; ?></a></span>
                
                <a class="remove-from-cart" rel="nofollow" onclick="cart.remove('<?php echo $product['cart_id']; ?>');" title="<?php echo $button_remove; ?>" data-link-action="remove-from-cart">
                  <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polyline points="3 6 5 6 21 6"></polyline>
                    <path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path>
                    <line x1="10" y1="11" x2="10" y2="17"></line>
                    <line x1="14" y1="11" x2="14" y2="17"></line>
                  </svg>
                </a>
                <div class="options-content">
                  <?php if ($product['option']) { ?>
                  <?php foreach ($product['option'] as $option) { ?>
                  <span><b><?php echo $option['name']; ?></b>: <?php echo $option['value']; ?></span>
                  <?php } ?>
                  <?php } ?>
                  <?php if ($product['recurring']) { ?>
                  <span><b><?php echo $text_recurring; ?></b>: <?php echo $product['recurring']; ?></span>
                  <?php } ?>
                </div>
                <span class="product-price"><?php echo $product['total']; ?></span>
              </div>
            </div>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
            <div class="product-block flexbox">
			  <div class="img-content">
			    <img src="/image/catalog/icon-png/gift-card.png" alt="<?php echo $voucher['description']; ?>" title="<?php echo $voucher['description']; ?>" class="img-responsive" />
			  </div>
			  <div class="right-block">
                <span class="product-name"><a><?php echo $voucher['description']; ?></a></span>
                <span class="product-price"><?php echo $voucher['amount']; ?></span>
                <span class="voucher-delete">
                  <a onclick="voucher.remove('<?php echo $voucher['key']; ?>');" title="<?php echo $button_remove; ?>" class="remove-from-cart"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><polyline points="3 6 5 6 21 6"></polyline><path d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2"></path><line x1="10" y1="11" x2="10" y2="17"></line><line x1="14" y1="11" x2="14" y2="17"></line></svg></a>
                </span>
              </div>
			</div>
            <?php } ?>
          </div>
        </div>
        <div class="dropdown-main dropdown-footer">
          <div class="price-content">
            <div class="cart-subtotals">
              <?php foreach ($totals as $total) { ?>
              <div class="products flex-container flex-between">
                <span class="labels"><?php echo $total['title']; ?></span>
                <span class="value"><?php echo $total['text']; ?></span>
              </div>
              <?php } ?>
            </div>
          </div>
          <div class="checkout">
            <a href="<?php echo $cart; ?>" class="btn btn-success btn-block"><?php echo $text_cart; ?></a>
            <a href="<?php echo $checkout; ?>" class="btn btn-primary btn-block"><?php echo $text_checkout; ?></a>
          </div>
        </div>
      <?php } else { ?>
      <div class="dropdown-main">
	    <div class="mini-basket-empty text-center">
		  <div class="empty-basket-img">
		    <svg width="100" height="100" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg" class="icon icon-cart" style=""><path fill-rule="evenodd" clip-rule="evenodd" d="M1 1H3L5 8H12L13.6667 3L14 2H12.9459H4.5L4 0H3.7543H3H1H0V1H1ZM4.61144 3L5.7543 7H11.2792L12.6126 3H4.61144ZM5.5 11C5.77614 11 6 10.7761 6 10.5C6 10.2239 5.77614 10 5.5 10C5.22386 10 5 10.2239 5 10.5C5 10.7761 5.22386 11 5.5 11ZM5.5 12C6.32843 12 7 11.3284 7 10.5C7 9.67157 6.32843 9 5.5 9C4.67157 9 4 9.67157 4 10.5C4 11.3284 4.67157 12 5.5 12ZM10.5 11C10.7761 11 11 10.7761 11 10.5C11 10.2239 10.7761 10 10.5 10C10.2239 10 10 10.2239 10 10.5C10 10.7761 10.2239 11 10.5 11ZM10.5 12C11.3284 12 12 11.3284 12 10.5C12 9.67157 11.3284 9 10.5 9C9.67157 9 9 9.67157 9 10.5C9 11.3284 9.67157 12 10.5 12Z" fill="#eee"></path></svg>
		  </div>
		  <span><?php echo $text_empty; ?></span>
		</div>
      </div>
	  <?php } ?>
    </div>
  </div>
</div>