<?php
/*define('IN_ECS', true);
require(ROOT_PATH . '/includes/init.php');*/
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}

$cache_id = sprintf('%X', crc32($_SESSION['user_rank'] . '-' . $_CFG['lang'].'-'.$_device));
$template = 'index'.$_device.'.dwt';
if (!$smarty->is_cached($template, $cache_id))
{
    assign_template();
    $position = assign_ur_here(0,'','index');
    $smarty->assign('page_title',      $_CFG['shop_title']);    // 页面标题
    $smarty->assign('ur_here',         $position['ur_here']);  // 当前位置
    /* meta information */
    $smarty->assign('keywords',        htmlspecialchars($_CFG['shop_keywords']));
    $smarty->assign('description',     htmlspecialchars($_CFG['shop_desc']));
    $smarty->assign('categories',      get_categories_tree());
    //$smarty->assign('helps',           get_shop_help());
    $smarty->assign('best_goods',      get_recommend_goods('best'));  //hiển thị ra home mobile
    $smarty->assign('new_goods',       get_recommend_goods('new'));
    $smarty->assign('promotion_goods', get_promote_goods());
    $smarty->assign('new_articles',    index_get_new_articles(4));
    //$smarty->assign('group_buy_goods', index_get_group_buy());
    $smarty->assign('shop_notice',     $_CFG['shop_notice']);
    $smarty->assign('banner_type',     $_CFG['banner_type']);
    $smarty->assign('data_dir',        DATA_DIR);

	$smarty->assign('promote', get_specials_goods('goods_special'.$_device));
    /** Dành riêng cho bản Desktop */
        $smarty->assign('total_cat',    count_goods_category([7,3,16,82]));
        $smarty->assign('hot_goods',       get_recommend_goods('hot'));
        //$smarty->assign('categories_article',     article_categories_tree(18));
        /* links */
        $links = index_get_links();
        //$smarty->assign('img_links',       $links['img']);
        $smarty->assign('txt_links',       $links['txt']);

    /* Recommend goods ajax tab */
    // $cat_recommend_res = $db->getAll("SELECT c.cat_id, c.cat_name, cr.recommend_type FROM " . $ecs->table("cat_recommend") . " AS cr INNER JOIN " . $ecs->table("category") . " AS c ON cr.cat_id=c.cat_id");
    // if (!empty($cat_recommend_res))
    // {
    //     $cat_rec_array = array();
    //     foreach($cat_recommend_res as $cat_recommend_data)
    //     {
    //         $cat_rec[$cat_recommend_data['recommend_type']][] = array('cat_id' => $cat_recommend_data['cat_id'], 'cat_name' => $cat_recommend_data['cat_name']);
    //     }
    //     $smarty->assign('cat_rec', $cat_rec);
    // }

    assign_dynamic('index');
}
$smarty->display($template, $cache_id);
/*------------------------------------------------------ */
//-- PRIVATE FUNCTIONS
/*------------------------------------------------------ */


/**
 * 获得最新的团购活动
 *
 * @access  private
 * @return  array
 */
function index_get_group_buy()
{
    $time = gmtime();
    $limit = get_library_number('group_buy', 'index');
    $group_buy_list = array();
    if ($limit > 0)
    {
        $sql = 'SELECT gb.act_id AS group_buy_id, gb.goods_id, gb.ext_info, gb.goods_name, g.goods_thumb, g.goods_img ' .
                'FROM ' . $GLOBALS['ecs']->table('goods_activity') . ' AS gb, ' .
                    $GLOBALS['ecs']->table('goods') . ' AS g ' .
                "WHERE gb.act_type = '" . GAT_GROUP_BUY . "' " .
                "AND g.goods_id = gb.goods_id " .
                "AND gb.start_time <= '" . $time . "' " .
                "AND gb.end_time >= '" . $time . "' " .
                "AND g.is_delete = 0 " .
                "ORDER BY gb.act_id DESC " .
                "LIMIT $limit" ;
        $res = $GLOBALS['db']->query($sql);
        while ($row = $GLOBALS['db']->fetchRow($res))
        {
            /* 如果缩略图为空，使用默认图片 */
            $row['goods_img'] = get_image_path($row['goods_id'], $row['goods_img']);
            $row['thumb'] = get_image_path($row['goods_id'], $row['goods_thumb'], true);
            /* 根据价格阶梯，计算最低价 */
            $ext_info = unserialize($row['ext_info']);
            $price_ladder = $ext_info['price_ladder'];
            if (!is_array($price_ladder) || empty($price_ladder))
            {
                $row['last_price'] = price_format(0);
            }
            else
            {
                foreach ($price_ladder AS $amount_price)
                {
                    $price_ladder[$amount_price['amount']] = $amount_price['price'];
                }
            }
            ksort($price_ladder);
            $row['last_price'] = price_format(end($price_ladder));
            $row['url'] = build_uri('group_buy', array('gbid' => $row['group_buy_id']));
            $row['short_name']   = $GLOBALS['_CFG']['goods_name_length'] > 0 ?
                                           sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
            $row['short_style_name']   = add_style($row['short_name'],'');
            $group_buy_list[] = $row;
        }
    }
    return $group_buy_list;
}

/**
 * 获得所有的友情链接
 *
 * @access  private
 * @return  array
 */
function index_get_links()
{
    $sql = 'SELECT link_logo, link_name, link_url FROM ' . $GLOBALS['ecs']->table('friend_link') . ' ORDER BY show_order';
    $res = $GLOBALS['db']->getAll($sql);
    $links['img'] = $links['txt'] = array();
    foreach ($res AS $row)
    {
        if (!empty($row['link_logo']))
        {
            $links['img'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url'],
                                    'logo' => $row['link_logo']);
        }
        else
        {
            $links['txt'][] = array('name' => $row['link_name'],
                                    'url'  => $row['link_url']);
        }
    }
    return $links;
}
/**
 * Lấy total sp thuộc danh mục muốn show home
 * @param  array  $cat_id Mảng id các danh mục cần show total
 * @param  string $ext
 */
function count_goods_category($cat_id = [],$ext='')
{
    foreach($cat_id as $k => $id){
      $res[$k] = $GLOBALS['db']->getRow('SELECT cat_name, cat_id FROM ' . $GLOBALS['ecs']->table('category') . " WHERE cat_id = $id LIMIT 1");
      $res[$k]['url'] = build_uri('category', array('cid' => $res[$k]['cat_id']), $res[$k]['cat_name']);
      $children = get_children($id);
      $where  = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
      $res[$k]['total'] = $GLOBALS['db']->getOne('SELECT COUNT("cat_id") FROM ' . $GLOBALS['ecs']->table('goods') . " AS g WHERE $where $ext");
    }
    return $res;
}
?>