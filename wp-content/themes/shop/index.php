<?php
/*
Template Name: Shop
*/

global $custom_page;
$custom_page = process_url_parts();


get_header();    

?>

<div class="col-lg-9 col-12" id="content_div"></div> 

<script>
    json = <?php echo $custom_page['body']; ?>;
    products_per_page  = <?php echo get_option('shop_products_per_page'); ?>;
</script>    

<?php
get_footer();

?>