<?php global $custom_page; ?>
<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
    <meta charset="<?php bloginfo('charset'); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?php wp_head(); ?>
</head>
<body <?php body_class($custom_page['body_class']); ?>>
<div class="wrap">
    <div class="container">
        <header>
            <div class="row">
                <div class="col-lg-3 col-12 pt-3">
                    <h1><a href="/"><?php echo get_bloginfo(); ?></a></h1>
                    <h2 id="seo_descr">&nbsp;</h2>  
                </div>
                <div class="col-lg-7 col-12" id="page_title">
                    <h2 id="page_title" class="pt-4"><?php echo $custom_page['page_title']; ?></h2>  
                </div>
                <div class="col-lg-2 col-12 pt-5">
                    <div id="cart"><a href="/cart">My cart: <span id="cart-quantity"><?php echo (isset($_SESSION['cart'])) ? array_sum($_SESSION['cart']) : 0; ?></span></div>  
                </div>                                          
            </div>    
        </header>    
    </div>

    <div class="container">
        <div class="row">
            <div class="col-lg-3 col-12">
                <?php echo generate_menu(); ?>
            </div>
            
