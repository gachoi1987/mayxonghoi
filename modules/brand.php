<?php

/*define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');*/

if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = false;
}
if(!empty($slug)){
    $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='brand' AND slug = '".$slug."'");
    $brand_id = $id > 0 ? intval($id) : 0;
}else{
    $brand_id = 0;
}
/** Default Brand index -  brand_list.dwt*/
if (empty($brand_id))
{
    $cache_id = sprintf('%X', crc32($_CFG['lang'].'-'.$_device));
    $_template = 'brand_list'.$_device.'.dwt';
    if (!$smarty->is_cached($_template, $cache_id))
    {
        assign_template();
        $position = assign_ur_here('', $_LANG['all_brand']);
        $smarty->assign('page_title',      $position['title']);
        $smarty->assign('ur_here',         $position['ur_here']);

        $smarty->assign('categories',      get_categories_tree());
        //$smarty->assign('helps',           get_shop_help());
        //$smarty->assign('top_goods',       get_top10());

        $smarty->assign('brand_list', get_brands());
    }

    $smarty->display($_template, $cache_id);
    exit();
}

/** Brand following category id  - brand.dwt */
$page = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
//$size = !empty($_CFG['page_size']) && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;
$size = 20;
//$cate = !empty($_REQUEST['cat'])   && intval($_REQUEST['cat'])   > 0 ? intval($_REQUEST['cat'])   : 0;
$cate_id = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE slug = '".$slug_brand."' AND module='category'");
$cate = !empty($cate_id) ? $cate_id : 0;

$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
$default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');

$sort  = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : $default_sort_order_method;
$display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
$display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';
setcookie('ECS[display]', $display, gmtime() + 86400 * 7,$cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);

/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

$cache_id = sprintf('%X', crc32($brand_id . '-' . $display . '-' . $sort . '-' . $order . '-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' . $_CFG['lang'] . '-' . $cate.'-'.$_device));
$_template = 'brand'.$_device.'.dwt';
if (!$smarty->is_cached($_template, $cache_id))
{
    $brand_info = get_brand_info($brand_id);
    $cate_info = get_cat_info($cate);

    if (empty($brand_info))
    {
        ecs_header("Location: ./\n");
        exit;
    }

    assign_template();
    $smarty->assign('data_dir',    DATA_DIR);
    if(isset($cate_info['cat_name'])){
        $page_title = $cate_info['cat_name'].' thương hiệu '.$brand_info['brand_name'];
        $keywords = $cate_info['keywords'].', '.$brand_info['brand_name'].', hãng '.$brand_info['brand_name'];
    }else{
        $page_title = 'Thương hiệu '.$brand_info['brand_name'];
        $keywords = $page_title.', hãng '.$brand_info['brand_name'].', '.$brand_info['brand_name'];
    }
    $description = !empty($brand_info['brand_desc']) ? $brand_info['brand_desc'] : $page_title;
    $brand_info['title'] = $page_title;

    $position = assign_ur_here($cate, $page_title);
    $smarty->assign('page_title',    $page_title);
    $smarty->assign('keywords',    htmlspecialchars($keywords));
    $smarty->assign('description', htmlspecialchars($description));
    $smarty->assign('ur_here',        $position['ur_here']);
    $smarty->assign('brand_id',       $brand_id);
    $smarty->assign('category',       $cate);

    $smarty->assign('categories',     get_categories_tree());
    //$smarty->assign('helps',          get_shop_help());
    //$smarty->assign('top_goods',      get_top10());
    $smarty->assign('show_marketprice', $_CFG['show_marketprice']);
    $smarty->assign('brand_cat_list', brand_related_cat($brand_id));

    // $vote = get_vote();
    // if (!empty($vote))
    // {
    //     $smarty->assign('vote_id',     $vote['id']);
    //     $smarty->assign('vote',        $vote['content']);
    // }

    $smarty->assign('best_goods',      brand_recommend_goods('best', $brand_id, $cate));
    //$smarty->assign('promotion_goods', brand_recommend_goods('promote', $brand_id, $cate));
    $smarty->assign('brand',           $brand_info);
    //$smarty->assign('promotion_info', get_promotion_info());

    $count = goods_count_by_brand($brand_id, $cate);

    $goodslist = brand_get_goods($brand_id, $cate, $size, $page, $sort, $order);

    if($display == 'grid')
    {
        if(count($goodslist) % 2 != 0)
        {
            $goodslist[] = array();
        }
    }
    $smarty->assign('goods_list',      $goodslist);
    $smarty->assign('script_name', 'brand');
    $smarty->assign('total', $count);
    $viewmore_number = intval($count)-($page*$size);
    $smarty->assign('viewmore_number', $viewmore_number);
    assign_pager('brand', $cate, $count, $size, $sort, $order, $page, '', $brand_id, 0, 0, $display); // 分页
    assign_dynamic('brand');
}

$smarty->display($_template, $cache_id);

/*------------------------------------------------------ */
//-- PRIVATE FUNCTION
/*------------------------------------------------------ */

/**
 *
 * @access  private
 * @param   integer $id
 * @return  void
 */
function get_brand_info($id)
{
    $sql = 'SELECT * FROM ' . $GLOBALS['ecs']->table('brand') . " WHERE brand_id = '$id'";

    return $GLOBALS['db']->getRow($sql);
}

function get_cat_info($cat_id)
{
    return $GLOBALS['db']->getRow('SELECT cat_name, meta_title, keywords, cat_desc FROM ' . $GLOBALS['ecs']->table('category') ." WHERE cat_id = '$cat_id'");
}
/**
 *
 * @access  private
 * @param   string  $type
 * @param   integer $brand
 * @return  array
 */
function brand_recommend_goods($type, $brand, $cat = 0)
{
    static $result = NULL;

    $time = gmtime();

    if ($result === NULL)
    {
        if ($cat > 0)
        {
            $cat_where = "AND " . get_children($cat);
        }
        else
        {
            $cat_where = '';
        }

        $sql = 'SELECT g.goods_id, g.goods_name, g.market_price, g.shop_price AS org_price, g.promote_price, ' .
                    "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, ".
                    'promote_start_date, promote_end_date, g.goods_brief, g.goods_thumb, goods_img, ' .
                    'b.brand_name, g.is_best, g.is_new, g.is_hot, g.is_promote ' .
                'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
                'LEFT JOIN ' . $GLOBALS['ecs']->table('brand') . ' AS b ON b.brand_id = g.brand_id ' .
                'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp '.
                    "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' ".
                "WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.brand_id = '$brand' AND " .
                    "(g.is_best = 1 OR (g.is_promote = 1 AND promote_start_date <= '$time' AND ".
                    "promote_end_date >= '$time')) $cat_where" .
               'ORDER BY g.sort_order, g.last_update DESC';
        $result = $GLOBALS['db']->getAll($sql);
    }

    $num = 0;
    $type2lib = array('best'=>'recommend_best', 'new'=>'recommend_new', 'hot'=>'recommend_hot', 'promote'=>'recommend_promotion');
    $num = get_library_number($type2lib[$type]);

    $idx = 0;
    $goods = array();
    foreach ($result AS $row)
    {
        if ($idx >= $num)
        {
            break;
        }

        if (($type == 'best' && $row['is_best'] == 1) ||
            ($type == 'promote' && $row['is_promote'] == 1 &&
            $row['promote_start_date'] <= $time && $row['promote_end_date'] >= $time))
        {
            if ($row['promote_price'] > 0)
            {
                $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
                $goods[$idx]['promote_price'] = $promote_price > 0 ? price_format($promote_price) : '';
            }
            else
            {
                $goods[$idx]['promote_price'] = '';
            }

            $goods[$idx]['id']           = $row['goods_id'];
            $goods[$idx]['name']         = $row['goods_name'];
            $goods[$idx]['brief']        = $row['goods_brief'];
            $goods[$idx]['brand_name']   = $row['brand_name'];
            $goods[$idx]['short_style_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                               sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
            $goods[$idx]['market_price'] = price_format($row['market_price']);
            $goods[$idx]['shop_price']   = price_format($row['shop_price']);
            $goods[$idx]['thumb']        = get_image_path($row['goods_id'], $row['goods_thumb'], true);
            $goods[$idx]['goods_img']    = get_image_path($row['goods_id'], $row['goods_img']);
            $goods[$idx]['url']          = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);

            $idx++;
        }
    }

    return $goods;
}

/**
 *
 * @access  private
 * @param   integer     $brand_id
 * @param   integer     $cate
 * @return  integer
 */
function goods_count_by_brand($brand_id, $cate = 0)
{
    $sql = 'SELECT COUNT(*) FROM ' .$GLOBALS['ecs']->table('goods'). ' AS g '.
            "WHERE brand_id = '$brand_id' AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0";

    if ($cate > 0)
    {
        $sql .= " AND " . get_children($cate);
    }

    return $GLOBALS['db']->getOne($sql);
}

/**
 *
 * @access  private
 * @param   integer  $brand_id
 * @return  array
 */
function brand_get_goods($brand_id, $cate, $size, $page, $sort, $order)
{
    $cate_where = ($cate > 0) ? 'AND ' . get_children($cate) : '';

    /* 获得商品列表 */
    $sql = 'SELECT g.goods_id, g.goods_name, g.market_price, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, " .
                'g.promote_start_date, g.promote_end_date, g.desc_short, g.seller_note, g.goods_thumb , g.goods_img ' .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
            "WHERE g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND g.brand_id = '$brand_id' $cate_where".
            "ORDER BY $sort $order";

    $res = $GLOBALS['db']->selectLimit($sql, $size, ($page - 1) * $size);

    $arr = array();
    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        if ($row['promote_price'] > 0)
        {
            $promote_price = bargain_price($row['promote_price'], $row['promote_start_date'], $row['promote_end_date']);
        }
        else
        {
            $promote_price = 0;
        }

        $arr[$row['goods_id']]['goods_id']      = $row['goods_id'];
        if($GLOBALS['display'] == 'grid')
        {
            $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        }
        else
        {
            $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];
        }
        $arr[$row['goods_id']]['desc_short']   = nl2p(strip_tags($row['desc_short']));
        $arr[$row['goods_id']]['seller_note']  = nl2p(strip_tags($row['seller_note']));
        $arr[$row['goods_id']]['price']    = $row['shop_price'];
        $arr[$row['goods_id']]['market_price']  = price_format($row['market_price']);
        $arr[$row['goods_id']]['shop_price']    = price_format($row['shop_price']);
        $arr[$row['goods_id']]['promote_price'] = ($promote_price > 0) ? price_format($promote_price) : '';
        $arr[$row['goods_id']]['goods_thumb']   = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']     = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['url']           = build_uri('goods', array('gid' => $row['goods_id']), $row['goods_name']);
    }

    return $arr;
}

/**
 *
 * @access  public
 * @param   integer $brand
 * @return  array
 */
function brand_related_cat($brand)
{
    $arr[] = array('cat_id' => 0,
                 'cat_name' => $GLOBALS['_LANG']['all_category'],
                 'url'      => build_uri('brand', array('bid' => $brand), $GLOBALS['_LANG']['all_category']));

    $sql = "SELECT c.cat_id, c.cat_name, COUNT(g.goods_id) AS goods_count FROM ".
            $GLOBALS['ecs']->table('category'). " AS c, ".
            $GLOBALS['ecs']->table('goods') . " AS g " .
            "WHERE g.brand_id = '$brand' AND c.cat_id = g.cat_id AND g.is_on_sale = 1 AND g.is_delete = 0 ".
            "GROUP BY g.cat_id";
    $res = $GLOBALS['db']->query($sql);

    while ($row = $GLOBALS['db']->fetchRow($res))
    {
        $row['url'] = build_uri('brand', array('cid' => $row['cat_id'], 'bid' => $brand), $row['cat_name']);
        $arr[] = $row;
    }

    return $arr;
}

?>