<?php
// Get saved settings from the database
$products_endpoint  = get_option('shop_products_endpoint');
$products_per_page  = get_option('shop_products_per_page');
$orders_endpoint    = get_option('shop_orders_endpoint');

$category_prefix    = get_option('shop_category_prefix');
$category_postfix   = get_option('shop_category_postfix');
$product_prefix     = get_option('shop_product_prefix');
$product_postfix    = get_option('shop_product_postfix');
?>
<div class="wrap">
    <div class="container">
        <h1 class="wp-heading-inline mb-2">Shop Settings</h1>

        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="api-tab" data-toggle="tab" data-target="#api" type="button" role="tab" aria-controls="api" aria-selected="true">API settngs</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="seo-tab" data-toggle="tab" data-target="#seo" type="button" role="tab" aria-controls="seo" aria-selected="false">SEO settngs</button>
            </li>
        </ul>
        
        <form method="post" action="">
        
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="api" role="tabpanel" aria-labelledby="in-tab">
                <div class="container">

                        <div class="tab-content mt-5 mb-5">
                            <h3>Input API settings</h3>
                        </div>

                        <div class="form-group row">
                            <label for="products_endpoint" class="col-lg-2 col-12 pt-1">Products Endpoint:</label>
                            <div class="col-lg-4">
                                <input type="text" name="products_endpoint" id="products_endpoint" value="<?php echo esc_attr($products_endpoint); ?>" class="regular-text form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <label for="products_per_page" class="col-lg-2 col-12 pt-1">Products Per Page:</label></th>
                            <div class="col-lg-1 col-12">
                                <input type="number" name="products_per_page" id="products_per_page" value="<?php echo esc_attr($products_per_page); ?>" class="regular-text form-control">
                            </div>
                        </div>

                        <div class="tab-content mt-5 mb-5">
                            <h3>Output API settings</h3>
                        </div>

                        <div class="form-group row">
                            <label for="products_endpoint" class="col-lg-2 col-12 pt-1">Orders Endpoint:</label>
                            <div class="col-lg-4">
                                <input type="text" name="orders_endpoint" id="orders_endpoint" value="<?php echo esc_attr($orders_endpoint); ?>" class="regular-text form-control">
                            </div>
                        </div>                        


                </div>
            </div>
            <div class="tab-pane fade" id="seo" role="tabpanel" aria-labelledby="seo-tab">
                <div class="container">
                        <div class="tab-content mt-5 mb-5">
                            <h3>SEO settings</h3>
                        </div>

                        <div class="form-group row">
                            <label for="category_prefix" class="col-lg-2 col-12 pt-1">Category SEO prefix:</label>
                            <div class="col-lg-4">
                                <input type="text" name="category_prefix" id="category_prefix" value="<?php echo esc_attr($category_prefix); ?>" class="regular-text form-control">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="category_postfix" class="col-lg-2 col-12 pt-1">Category SEO postfix:</label>
                            <div class="col-lg-4">
                                <input type="text" name="category_postfix" id="category_postfix" value="<?php echo esc_attr($category_postfix); ?>" class="regular-text form-control">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="product_prefix" class="col-lg-2 col-12 pt-1">Product SEO prefix:</label>
                            <div class="col-lg-4">
                                <input type="text" name="product_prefix" id="product_prefix" value="<?php echo esc_attr($product_prefix); ?>" class="regular-text form-control">
                            </div>
                        </div>
                        
                        <div class="form-group row">
                            <label for="product_postfix" class="col-lg-2 col-12 pt-1">Product SEO postfix:</label>
                            <div class="col-lg-4">
                                <input type="text" name="product_postfix" id="product_postfix" value="<?php echo esc_attr($product_postfix); ?>" class="regular-text form-control">
                            </div>
                        </div>                          

                </div>                        
            </div>


        </div>

        <?php submit_button('Save Settings'); ?>
        </form>


    </div>
</div>