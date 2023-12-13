<?php
/*
Template Name: Shop
*/

global $custom_page;
$custom_page = process_url_parts();
$custom_page['page_title'] = 'My Cart';


get_header();    

?>

<div class="col-lg-9 col-12" id="content_div">

<?php 
    //unset($_SESSION['cart']);
    //unset($_SESSION['cart_dscription']);
    $json_url = get_option('shop_products_endpoint');

    if (isset($_SESSION['cart']) && count($_SESSION['cart'])) {
        
        echo  '<table class="table table-hover cart-table">';
        
        foreach ($_SESSION['cart'] as $product_id => $quantity) {
            
            $product = $_SESSION['cart_dscription'][$product_id];
            
            echo '<tr>
                    <td><img src="'.$product['thumbnail'].'"></td>
                    <td><a href="'.$product['url'].'">' . $product['title'].'</a></td>
                    <td>' . $quantity. '</td>
                   </tr>';
        }

        echo '</table>';

        echo '<a class="btn btn-success mt-5" id="place-order" href="#">Place the order</a>';

    }

?>
</div> 

<script>

</script>    

<?php
get_footer();

?>