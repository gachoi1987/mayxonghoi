<?php

$_CFG['static_path'] = $base_path.'static_'.$GLOBALS['_CFG']['template'].'/';
$_CFG['cdn1_path'] = $base_cdn1;
$_CFG['cdn_path'] = $base_cdn;
$_CFG['logo'] = 'static_'.$GLOBALS['_CFG']['template'].'/img/logo.png';
$_CFG['no_picture'] = 'images/no_picture.gif';
$_CFG['price_zero_format'] = sprintf($GLOBALS['_CFG']['currency_format'], '0.00');

$_CFG['theme_style'] = false;
$_CFG['product_tag_enabled'] = true;
$_CFG['product_click_count_enabled'] = false;
$_CFG['purchase_history_enabled'] = false;
$_CFG['comment_enabled'] = true;
$_CFG['footer_links_enabled'] = true;
$_CFG['mini_cart_enabled'] = true;
$_CFG['breadcrumb_enabled'] = true;
$_CFG['one_step_buy'] = false; /* true là nhảy đến giỏ hàng luôn ko cần hỏi lại */

/* admin */
$_CFG['gallery_width'] = 760;
$_CFG['gallery_height'] = 400;