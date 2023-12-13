let $ = jQuery;

$.migrateMute = true;
$.migrateTrace = false;

$(function () {

    // place order

    $('#place-order').on('click', function() {
    
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'place_order',
            },
            success: function(response) {
                console.log('sended');
                // TODO

                // clear up frontend
                // popup regards
            }
        });
    });   
    
    
    if (typeof json === 'undefined') return false;
    
    // products output
    if ($(json.products).length) {

        // create products container
        $('#content_div').append($('<div>', { id: 'products', 'class': 'products' }));
        var productContainer = $('#products');

        // pagination
        var currentPage = 1;
        var totalPages = Math.ceil(json.total / products_per_page);  

        // create navigation container
        if (totalPages != 1) {
            var navigationDiv = $('<div>').attr('id', 'pagination');
            var prevLink =  $('<a>', {id: 'prev', href:'#', text: 'Previous'}).appendTo(navigationDiv);
            var pageNum = $('<span>').attr('id', 'page-num').appendTo(navigationDiv);
            var nextLink =  $('<a>', {id: 'next', href:'#', text: 'Next'}).appendTo(navigationDiv);
            navigationDiv.appendTo('#page_title'); 
        }       

        updateProductList();
        updatePaginationButtons();
    
        $('#prev').on('click', function(e) {
            e.preventDefault();
            if (currentPage > 1) {
                currentPage--;
                updateProductList();
                updatePaginationButtons();
            }
        });
    
        $('#next').on('click', function(e) {
            e.preventDefault();
            if (currentPage < totalPages) {
                currentPage++;
                updateProductList();
                updatePaginationButtons();
            }
        });  

    }

    // single product

    if ($(json.id).length) {

        // container
        var productContainer = $('<div>').addClass('single_product');
        var productRow = $('<div>').addClass('row').appendTo(productContainer);
        var imageColumn = $('<div>').addClass('col-md-6').appendTo(productRow);
        var detailsColumn = $('<div>').addClass('col-md-6').appendTo(productRow);

        // carousel
        var carousel = $('<div>').addClass('carousel slide').attr('id', 'product-images').attr('data-ride', 'carousel').appendTo(imageColumn);
        var carouselInner = $('<div>').addClass('carousel-inner').appendTo(carousel);
        $.each(json.images, function (index, imageUrl) {
            var imageClass = index === 0 ? 'carousel-item active' : 'carousel-item';
            var imageDiv = $('<div>').addClass(imageClass).appendTo(carouselInner);
            var image = $('<img>').addClass('d-block w-100').attr('src', imageUrl).attr('alt', 'Product Image').appendTo(imageDiv);
        });
        var prevButton = $('<a>').addClass('carosel_control carousel-control-prev').attr('href', '#product-images').attr('role', 'button').attr('data-slide', 'prev').appendTo(carousel);
        $('<span>').addClass('carousel-control-prev-icon').attr('aria-hidden', 'true').appendTo(prevButton);
        $('<span>').addClass('sr-only').text('Previous').appendTo(prevButton);
        var nextButton = $('<a>').addClass('carosel_control carousel-control-next').attr('href', '#product-images').attr('role', 'button').attr('data-slide', 'next').appendTo(carousel);
        $('<span>').addClass('carousel-control-next-icon').attr('aria-hidden', 'true').appendTo(nextButton);
        $('<span>').addClass('sr-only').text('Next').appendTo(nextButton);

        var productTitle = $('<h1>').attr('id', 'product-title').text(json.title).appendTo(detailsColumn);
        var categoryHref = $('<a>',{class: 'category-href', href: '/' + json.category, text: makeCategory(json.category)}).appendTo(detailsColumn);
        var productDescription = $('<p>').attr('id', 'product-description').text(json.description).appendTo(detailsColumn);
        var productBrand = $('<p>').attr('id', 'product-brand').text('Brand: ' + json.brand).appendTo(detailsColumn);
        var productRating = $('<p>').attr('id', 'product-rating').text('Rating: ' + json.rating).appendTo(detailsColumn);
        var productPrice = $('<p>').attr('id', 'product-price').html('Price: $' + countDiscount(json.price, json.discountPercentage) + '<span>$' + json.price + '</span>').appendTo(detailsColumn);
        var productInStock = $('<p>').attr('id', 'product-stock').text(json.stock + ' in Stock').appendTo(detailsColumn);

        var cartButton = $('<a>', {id: 'cart-button', class: 'btn btn-success', text: 'Add to cart', href: '#', 'data-id' : json.id, 'data-title': json.title, 'data-thumbnail': json.thumbnail, 'data-url' : '/' + json.category + '/' + json.url}).appendTo(detailsColumn);

        // pagination

        var getItem = function(key, i) {
            var keys = Object.keys(json.pagination).map(Number),
                total = keys.length,
                index = keys.indexOf(key);

            if (i == -1 && index == 0) { index = total - 1; }
            else if (i == 1 && index == total - 1 ) { index = 0; }            
            else index = index + i;
            return json.pagination[keys[index]];
        }
        var navigationContainer = $('<div>').addClass('navigation').appendTo(detailsColumn);
        var prevButton = $('<a>', {id: 'prev-button', text: 'Previous', href: '/' + json.category + '/' + getItem(json.id, -1)}).appendTo(navigationContainer);
        var nextButton = $('<a>', {id: 'next-button', text: 'Next', href: '/' + json.category + '/' + getItem(json.id, 1)}).appendTo(navigationContainer);

        productContainer.appendTo('#content_div');

    }

    // pagination function for category
    function updateProductList() {
        productContainer.empty();
        var start = (currentPage  - 1) * products_per_page;
        var end = start + products_per_page;

        console.log(start, end);

        for (var i = start; i < end; i++) {
            if (json.products[i]) {
                var product = json.products[i];
                var productHTML = `
                    <div class="product">
                        <a href="/${product.category}/${product.url}"><img src="${product.thumbnail}" alt="${product.title}"></a>
                        <h3>${product.title}</h3>
                        <a href="/${product.category}/${product.url}">Buy for $${countDiscount(product.price, product.discountPercentage)}</a>
                        <div class="rating">${product.rating} </div>
                    </div>
                `;
                productContainer.append(productHTML);
            }
        }
    }

    function updatePaginationButtons() {
        $('#page-num').text('Page ' + currentPage + ' of ' + totalPages);
        $('#prev').prop('disabled', currentPage === 1);
        $('#next').prop('disabled', currentPage === totalPages);
    } 
    

    // add to cart

    $('#cart-button').on('click', function() {
        var productId = $(this).data('id'),
            title = $(this).data('title'),
            thumbnail = $(this).data('thumbnail'),
            url = $(this).data('url');
        
        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'add_to_cart',
                product_id: productId,
                title: title,
                thumbnail: thumbnail,
                url: url
            },
            success: function(response) {
                $('#cart-quantity').text(response);
            }
        });
    });

    function countDiscount(value, percent) {
        newPrice = Math.round(value - value * percent / 100);
        return newPrice.toLocaleString('en-US');
    }

    function makeCategory(value) {
        str = value.replace(/-/g, ' ');
        return str;
    }


});