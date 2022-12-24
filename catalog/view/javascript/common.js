function getURLVar(key) {
    var value = [];

    var query = document.location.search.split('?');

    if (query[1]) {
        var part = query[1].split('&');

        for (i = 0; i < part.length; i++) {
            var data = part[i].split('=');

            if (data[0] && data[1]) {
                value[data[0]] = data[1];
            }
        }

        if (value[key]) {
            return value[key];
        } else {
            return '';
        }
    }
}

$(document).ready(function() {
    // Highlight any found errors
    $('.text-danger').each(function() {
        var element = $(this).parent().parent();

        if (element.hasClass('form-group')) {
            element.addClass('has-error');
        }
    });

    // Currency
    $('#form-currency .currency-select').on('click', function(e) {
        e.preventDefault();

        $('#form-currency input[name=\'code\']').val($(this).attr('name'));

        $('#form-currency').submit();
    });

    // Language
    $('#form-language .language-select').on('click', function(e) {
        e.preventDefault();

        $('#form-language input[name=\'code\']').val($(this).attr('name'));

        $('#form-language').submit();
    });

    /* Search */
    $('#search input[name=\'search\']').parent().find('button').on('click', function() {
        var url = $('base').attr('href') + 'index.php?route=product/search';

        var value = $('.header-search-block #search input[name=\'search\']').val();

        if (value) {
            url += '&search=' + encodeURIComponent(value);
        }

        location = url;
    });

    $('#search input[name=\'search\']').on('keydown', function(e) {
        if (e.keyCode == 13) {
            $('.header-search-block #search input[name=\'search\']').parent().find('button').trigger('click');
        }
    });

    // Menu
    $('#menu .dropdown-menu').each(function() {
        /*var menu = $('#menu').offset();
        var dropdown = $(this).parent().offset();

        var i = (dropdown.left + $(this).outerWidth()) - (menu.left + $('#menu').outerWidth());

        if (i > 0) {
            $(this).css('margin-left', '-' + (i + 10) + 'px');
        }*/
    });

    // Product List
    $('#list-view').click(function() {
        $('#content .product-grid > .clearfix').remove();

        $('#content .row > .product-grid').attr('class', 'product-layout product-list col-xs-12');
        $('#grid-view').removeClass('active');
        $('#list-view').addClass('active');

        localStorage.setItem('display', 'list');
    });

    // Product Grid
    $('#grid-view').click(function() {
        // What a shame bootstrap does not take into account dynamically loaded columns
        var cols = $('#column-right, #column-left').length;

        if (cols == 2) {
            $('#content .product-list').attr('class', 'product-layout product-grid col-lg-6 col-md-6 col-sm-12 col-xs-6');
        } else if (cols == 1) {
            $('#content .product-list').attr('class', 'product-layout product-grid col-lg-3 col-md-3 col-sm-4 col-xs-6');
        } else {
            $('#content .product-list').attr('class', 'product-layout product-grid col-lg-2 col-md-3 col-sm-4 col-xs-6');
        }

        $('#list-view').removeClass('active');
        $('#grid-view').addClass('active');

        localStorage.setItem('display', 'grid');
    });

    if (localStorage.getItem('display') == 'list') {
        $('#list-view').trigger('click');
        $('#list-view').addClass('active');
    } else {
        $('#grid-view').trigger('click');
        $('#grid-view').addClass('active');
    }

    // Checkout
    $(document).on('keydown', '#collapse-checkout-option input[name=\'email\'], #collapse-checkout-option input[name=\'password\']', function(e) {
        if (e.keyCode == 13) {
            $('#collapse-checkout-option #button-login').trigger('click');
        }
    });

    // tooltips on hover
    $('[data-toggle=\'tooltip\']').tooltip({ container: 'body', trigger: 'hover' });

    // Makes tooltips work on ajax generated content
    $(document).ajaxStop(function() {
        $('[data-toggle=\'tooltip\']').tooltip({ container: 'body' });
    });

    name_scroll();
});

// Cart add remove functions
var cart = {
    'add': function(product_id, quantity) {
        $.ajax({
            url: 'index.php?route=checkout/cart/add',
            type: 'post',
            data: 'product_id=' + product_id + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            complete: function() {
                $('#cart > button').button('reset');
            },
            success: function(json) {
                $('.alert, .text-danger').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['success']) {
                    $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    // Need to set timeout otherwise it wont update the total
                    setTimeout(function() {
                        $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                    }, 100);

                    $('html, body').animate({ scrollTop: 0 }, 'slow');

                    // $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    $(".cart .dropdown-basket").load("index.php?route=common/cart/info .dropdown-basket .dropdown-main");

                    setTimeout(function () {
                        $(".cart-count").html(json.total_products);
                        $(".cart-count").addClass('updated-cart-count');
                        $(".userblock-text-danger strong").html(json.total_price);
                
                        setTimeout(function () {
                            $(".cart-count").removeClass('updated-cart-count');
                        }, 300);
                    }, 100);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'update': function(key, quantity) {
        $.ajax({
            url: 'index.php?route=checkout/cart/edit',
            type: 'post',
            data: 'key=' + key + '&quantity=' + (typeof(quantity) != 'undefined' ? quantity : 1),
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            complete: function() {
                $('#cart > button').button('reset');
            },
            success: function(json) {
                // Need to set timeout otherwise it wont update the total
                setTimeout(function() {
                    $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                }, 100);

                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    location = 'index.php?route=checkout/cart';
                } else {
                    // $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    $(".cart .dropdown-basket").load("index.php?route=common/cart/info .dropdown-basket .dropdown-main");

                    setTimeout(function () {
                        $(".cart-count").html(json.total_products);
                        $(".cart-count").addClass('updated-cart-count');
                        $(".userblock-text-danger strong").html(json.total_price);
                
                        setTimeout(function () {
                            $(".cart-count").removeClass('updated-cart-count');
                        }, 300);
                    }, 100);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'updateCart': function(key, quantity) {
        $.ajax({
            url: "index.php?route=checkout/cart/updateCart",
            type: "post",
            data: key + "=" + (typeof(quantity) != "undefined" ? quantity : 1),
            dataType: "html",
            success: function(html) {
                // console.log(html);


                let beginCartCount = html.lastIndexOf('<span class="userblock-count cart-count">') + 41
                let endCartCount = html.indexOf('</span>', beginCartCount);

                let cartCount = html.substring(
                    beginCartCount,
                    endCartCount
                );
                $('.cart-count').text(cartCount);

                
                let beginCartTotal = html.lastIndexOf('<strong class="cart-total">') + 27
                let endCartTotal = html.indexOf('</strong>', beginCartTotal);

                let cartTotal = html.substring(
                    beginCartTotal,
                    endCartTotal
                );
                $('.cart-total').html(cartTotal);


                let beginCheckoutTable = html.lastIndexOf('<div class="table-responsive cart-table">')
                let endCheckoutTable = html.indexOf('</form>', beginCheckoutTable);

                let checkoutTable = html.substring(
                    beginCheckoutTable,
                    endCheckoutTable
                );
                $('#cartForm').html(checkoutTable);


                let beginDetailTotal = html.lastIndexOf('<table class="table table-bordered" id="totals">') + 48
                let endDetailTotal = html.indexOf('</table>', beginDetailTotal);

                let detailTotal = html.substring(
                    beginDetailTotal,
                    endDetailTotal
                );
                $('#totals').html('<tbody>'+detailTotal+'</tbody>');


                // let beginMessageCart = html.lastIndexOf('<div class="message_cart text-danger">') + 38
                // let endMessageCart = html.indexOf('</div>', beginMessageCart) + 6;

                // let messageCart = html.substring(
                //     beginMessageCart,
                //     endMessageCart
                // );
                // $('.message_cart').html(messageCart);



                // setTimeout(function() {
                //     $(".cart-count").html(json.total);
                // }, 100);
                if (getURLVar("route") == "checkout/cart" || getURLVar("route") == "checkout/checkout") {
                    location = "index.php?route=checkout/cart"
                } else {
                    $(".cart .dropdown-basket").load("index.php?route=common/cart/info .dropdown-basket .dropdown-main");
                }

                let el = document.getElementsByName(key)[0];
                el.focus();
                let elLength = el.value.length;
                el.setSelectionRange(elLength, elLength);

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        })
    },
    'remove': function(key) {
        $.ajax({
            url: 'index.php?route=checkout/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            complete: function() {
                $('#cart > button').button('reset');
            },
            success: function(json) {
                // Need to set timeout otherwise it wont update the total
                setTimeout(function() {
                    $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                }, 100);

                var now_location = String(document.location.pathname);

                if ((now_location == '/cart/') || (now_location == '/checkout/') || (getURLVar('route') == 'checkout/cart') || (getURLVar('route') == 'checkout/checkout')) {
                    location = 'index.php?route=checkout/cart';
                } else if ((getURLVar('route') == 'checkout/onepagecheckout')) {
                    location = '/index.php?route=checkout/onepagecheckout';
                } else {
                    // $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    $(".cart .dropdown-basket").load("index.php?route=common/cart/info .dropdown-basket .dropdown-main");

                    setTimeout(function () {
                        $(".cart-count").html(json.total_products);
                        $(".cart-count").addClass('updated-cart-count');
                        $(".userblock-text-danger strong").html(json.total_price);
                
                        setTimeout(function () {
                            $(".cart-count").removeClass('updated-cart-count');
                        }, 300);
                    }, 100);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

var voucher = {
    'add': function() {

    },
    'remove': function(key) {
        $.ajax({
            url: 'index.php?route=checkout/cart/remove',
            type: 'post',
            data: 'key=' + key,
            dataType: 'json',
            beforeSend: function() {
                $('#cart > button').button('loading');
            },
            complete: function() {
                $('#cart > button').button('reset');
            },
            success: function(json) {
                // Need to set timeout otherwise it wont update the total
                setTimeout(function() {
                    $('#cart > button').html('<span id="cart-total"><i class="fa fa-shopping-cart"></i> ' + json['total'] + '</span>');
                }, 100);

                if (getURLVar('route') == 'checkout/cart' || getURLVar('route') == 'checkout/checkout') {
                    location = 'index.php?route=checkout/cart';
                } else {
                    // $('#cart > ul').load('index.php?route=common/cart/info ul li');
                    $(".cart .dropdown-basket").load("index.php?route=common/cart/info .dropdown-basket .dropdown-main");

                    setTimeout(function () {
                        $(".cart-count").html(json.total_products);
                        $(".cart-count").addClass('updated-cart-count');
                        $(".userblock-text-danger strong").html(json.total_price);
                
                        setTimeout(function () {
                            $(".cart-count").removeClass('updated-cart-count');
                        }, 300);
                    }, 100);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    }
}

var wishlist = {
    'add': function(product_id) {
        $.ajax({
            url: 'index.php?route=account/wishlist/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function(json) {
                $('.alert').remove();

                if (json['redirect']) {
                    location = json['redirect'];
                }

                if (json['success']) {
                    $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');
                }

                $('#wishlist-total span').html(json['total']);
                $('#wishlist-total').attr('title', json['total']);

                $('html, body').animate({ scrollTop: 0 }, 'slow');
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function() {

    }
}

var compare = {
    'add': function(product_id) {
        $.ajax({
            url: 'index.php?route=product/compare/add',
            type: 'post',
            data: 'product_id=' + product_id,
            dataType: 'json',
            success: function(json) {
                $('.alert').remove();

                if (json['success']) {
                    $('#content').parent().before('<div class="alert alert-success"><i class="fa fa-check-circle"></i> ' + json['success'] + ' <button type="button" class="close" data-dismiss="alert">&times;</button></div>');

                    $('#compare-total fa').html(json['total']);

                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(thrownError + "\r\n" + xhr.statusText + "\r\n" + xhr.responseText);
            }
        });
    },
    'remove': function() {

    }
}

/* Agree to Terms */
$(document).delegate('.agree', 'click', function(e) {
    e.preventDefault();

    $('#modal-agree').remove();

    var element = this;

    $.ajax({
        url: $(element).attr('href'),
        type: 'get',
        dataType: 'html',
        success: function(data) {
            html = '<div id="modal-agree" class="modal">';
            html += '  <div class="modal-dialog">';
            html += '    <div class="modal-content">';
            html += '      <div class="modal-header">';
            html += '        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>';
            html += '        <h4 class="modal-title">' + $(element).text() + '</h4>';
            html += '      </div>';
            html += '      <div class="modal-body">' + data + '</div>';
            html += '    </div';
            html += '  </div>';
            html += '</div>';

            $('body').append(html);

            $('#modal-agree').modal('show');
        }
    });
});

// Product count
$(document).on("click", ".number-spinner button", function() {
    var a = $(this),
        c = a.closest(".number-spinner").find("input").val().trim(),
        b = 1,
        diff = 1,
        maxVal = a.closest(".number-spinner").find("input").attr('maxVal');

    if (a.attr("data-dir") == "up") {
        b = parseInt(c) + 1
    } else {
        if (c > 1) {
            b = parseInt(c) - 1
        } else {
            return false
        }
    }
    a.closest(".number-spinner").find("input").val(b);

    cart.updateCart(a.closest(".number-spinner").find("input").attr('name'), b);

});

$(document).on("click", ".plus-minus button", function() {
    var e = $(this),
        d = e.closest(".plus-minus").find("input").val().trim(),
        f = 1,
        diff = 1,
        maxVal = e.closest(".plus-minus").find("input").attr('maxVal');
    

    if (e.attr("data-dir") == "up") {
        f = parseInt(d) + 1
    } else {
        if (d > 1) {
            f = parseInt(d) - 1
        } else {
            f = 1
        }
    }
    e.closest(".plus-minus").find("input").val(f)
});

// Autocomplete */
(function($) {
    $.fn.autocomplete = function(option) {
        return this.each(function() {
            this.timer = null;
            this.items = new Array();

            $.extend(this, option);

            $(this).attr('autocomplete', 'off');

            // Focus
            $(this).on('focus', function() {
                this.request();
            });

            // Blur
            $(this).on('blur', function() {
                setTimeout(function(object) {
                    object.hide();
                }, 200, this);
            });

            // Keydown
            $(this).on('keydown', function(event) {
                switch (event.keyCode) {
                    case 27: // escape
                        this.hide();
                        break;
                    default:
                        this.request();
                        break;
                }
            });

            // Click
            this.click = function(event) {
                event.preventDefault();

                value = $(event.target).parent().attr('data-value');

                if (value && this.items[value]) {
                    this.select(this.items[value]);
                }
            }

            // Show
            this.show = function() {
                var pos = $(this).position();

                $(this).siblings('ul.dropdown-menu').css({
                    top: pos.top + $(this).outerHeight(),
                    left: pos.left
                });

                $(this).siblings('ul.dropdown-menu').show();
            }

            // Hide
            this.hide = function() {
                $(this).siblings('ul.dropdown-menu').hide();
            }

            // Request
            this.request = function() {
                clearTimeout(this.timer);

                this.timer = setTimeout(function(object) {
                    object.source($(object).val(), $.proxy(object.response, object));
                }, 200, this);
            }

            // Response
            this.response = function(json) {
                html = '';

                if (json.length) {
                    for (i = 0; i < json.length; i++) {
                        this.items[json[i]['value']] = json[i];
                    }

                    for (i = 0; i < json.length; i++) {
                        if (!json[i]['category']) {
                            html += '<li data-value="' + json[i]['value'] + '"><a href="#">' + json[i]['label'] + '</a></li>';
                        }
                    }

                    // Get all the ones with a categories
                    var category = new Array();

                    for (i = 0; i < json.length; i++) {
                        if (json[i]['category']) {
                            if (!category[json[i]['category']]) {
                                category[json[i]['category']] = new Array();
                                category[json[i]['category']]['name'] = json[i]['category'];
                                category[json[i]['category']]['item'] = new Array();
                            }

                            category[json[i]['category']]['item'].push(json[i]);
                        }
                    }

                    for (i in category) {
                        html += '<li class="dropdown-header">' + category[i]['name'] + '</li>';

                        for (j = 0; j < category[i]['item'].length; j++) {
                            html += '<li data-value="' + category[i]['item'][j]['value'] + '"><a href="#">&nbsp;&nbsp;&nbsp;' + category[i]['item'][j]['label'] + '</a></li>';
                        }
                    }
                }

                if (html) {
                    this.show();
                } else {
                    this.hide();
                }

                $(this).siblings('ul.dropdown-menu').html(html);
            }

            $(this).after('<ul class="dropdown-menu"></ul>');
            $(this).siblings('ul.dropdown-menu').delegate('a', 'click', $.proxy(this.click, this));

        });
    }
})(window.jQuery);


// BMV BEGIN
function run_scroll() {
    var $link = $(this),
        target = $link.width() - $link.parent().width();
    if (target > 0) {
        $link.stop().animate({
                'margin-left': -target
            },
            20 * target, 'linear'
        );
    }
}

function reset_scroll() {
    var $link = $(this);
    $link.stop().animate({
            'margin-left': 0
        },
        250
    );
}

function name_scroll() {
    $('.caption a').hover(run_scroll, reset_scroll);
}

// Quickview
var quickview = function (product_id) {		
	$.ajax({
		url: "index.php?route=extension/module/quickview",
		type: "get",
		data: "product_id=" + product_id,
		dataType: "html",
		success: function (json) {
			$("#modal-quickview").remove();
			if (json) {
				$("body").append(json);	
                $("#modal-quickview").modal("show");
                draggable(document.getElementById('modalFormHeader'));
			}
		},
		error: function(xhr, ajaxOptions, thrownError) {
			alert('Ошибка! свяжитесь с администрацией');
		}
	})
};

function draggable(element) {
    
    if (element) {
      let form = element.parentElement.parentElement;
    
      var isMouseDown = false;
  
      // initial mouse X and Y for `mousedown`
      var mouseX;
      var mouseY;
  
      // element X and Y before and after move
      var elementX = 0;
      var elementY = 0;
  
      // mouse button down over the element
      if (element) {
        element.addEventListener('mousedown', onMouseDown);
      }
    
  
  
      function onMouseDown(event) {
        mouseX = event.clientX;
        mouseY = event.clientY;
        isMouseDown = true;
        var header = document.getElementsByClassName('modal-header')[0];
        header.classList.add("cursor-grabbing");
      }
  
      // mouse button released
      if (element) {
        element.addEventListener('mouseup', onMouseUp);
      }
  
  
      function onMouseUp(event) {
        isMouseDown = false;
        elementX = parseInt(form.style.left) || 0;
        elementY = parseInt(form.style.top) || 0;
        var header = document.getElementsByClassName('modal-header')[0];
        header.classList.remove("cursor-grabbing");
      }
  
      // need to attach to the entire document
      // in order to take full width and height
      // this ensures the element keeps up with the mouse
      if (element) {
        document.addEventListener('mousemove', onMouseMove);
      }
  
      
      function onMouseMove(event) {
        if (!isMouseDown) return;
        var deltaX = event.clientX - mouseX;
        var deltaY = event.clientY - mouseY;
        form.style.left = elementX + deltaX + 'px';
        form.style.top = elementY + deltaY + 'px';
      }
    }
  }



// BMV END