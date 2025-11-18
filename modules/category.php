<?php
/*define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');*/
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}
/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

$id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE module='category' AND slug = '".$slug."'");
$cat_id = $id > 0 ? intval($id) : 0;
if($cat_id == 0)
{
    ecs_header("Location: ./\n");
    exit;
}
/* 初始化分页信息 */
$page = isset($_REQUEST['page'])   && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
$size = isset($_CFG['page_size'])  && intval($_CFG['page_size']) > 0 ? intval($_CFG['page_size']) : 10;
//$brand = isset($_REQUEST['brand']) && intval($_REQUEST['brand']) > 0 ? intval($_REQUEST['brand']) : 0;

if(isset($slug_brand))
{
    $id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE slug = '".$slug_brand."' AND module='brand'");
    $brand = $id > 0 ? intval($id) : 0;
}else{
    $brand = 0;
}

$price_max = isset($_REQUEST['duoi']) && intval($_REQUEST['duoi']) > 0 ? intval($_REQUEST['duoi']) : 0;
$price_min = isset($_REQUEST['tu']) && intval($_REQUEST['tu']) > 0 ? intval($_REQUEST['tu']) : 0;
$recommend = isset($_REQUEST['rc']) && !empty($_REQUEST['rc']) ? $_REQUEST['rc'] : '';

$filter_attr_str = isset($_REQUEST['filter_attr']) ? htmlspecialchars(trim($_REQUEST['filter_attr'])) : '0';
$filter_attr_str = trim(urldecode($filter_attr_str));
$filter_attr_str = preg_match('/^[\d\.]+$/',$filter_attr_str) ? $filter_attr_str : '';
$filter_attr = empty($filter_attr_str) ? '' : explode('.', $filter_attr_str);

/* 排序、显示方式以及类型 */
$default_display_type = $_CFG['show_order_type'] == '0' ? 'list' : ($_CFG['show_order_type'] == '1' ? 'grid' : 'text');
$default_sort_order_method = $_CFG['sort_order_method'] == '0' ? 'DESC' : 'ASC';
$default_sort_order_type   = $_CFG['sort_order_type'] == '0' ? 'goods_id' : ($_CFG['sort_order_type'] == '1' ? 'shop_price' : 'last_update');
$sort  = (isset($_REQUEST['sort'])  && in_array(trim(strtolower($_REQUEST['sort'])), array('goods_id', 'shop_price', 'last_update'))) ? trim($_REQUEST['sort'])  : $default_sort_order_type;
$order = (isset($_REQUEST['order']) && in_array(trim(strtoupper($_REQUEST['order'])), array('ASC', 'DESC')))                              ? trim($_REQUEST['order']) : $default_sort_order_method;
$display  = (isset($_REQUEST['display']) && in_array(trim(strtolower($_REQUEST['display'])), array('list', 'grid', 'text'))) ? trim($_REQUEST['display'])  : (isset($_COOKIE['ECS']['display']) ? $_COOKIE['ECS']['display'] : $default_display_type);
$display  = in_array($display, array('list', 'grid', 'text')) ? $display : 'text';
setcookie('ECS[display]', $display, gmtime() + 86400 * 7, $cookie_path, $cookie_domain, $cookie_secure, $cookie_http_only);
/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */

$cache_id = sprintf('%X', crc32($cat_id . '-' . $display . '-' . $sort  .'-' . $order  .'-' . $page . '-' . $size . '-' . $_SESSION['user_rank'] . '-' .
    $_CFG['lang'] .'-'. $brand. '-' . $price_max . '-' .$price_min . '-' . $filter_attr_str.'-'.$_device));
$_template = 'category'.$_device.'.dwt';
if (!$smarty->is_cached($_template, $cache_id))
{
    /** Active menu */
    if(isset($active_url)){
        $smarty->assign('active_url', $active_url);
    }
    $children = get_children($cat_id);
    $cat = get_cat_info($cat_id);
    if (!empty($cat))
    {
        $smarty->assign('keywords',    htmlspecialchars($cat['keywords']));
        $smarty->assign('cat_name',    htmlspecialchars($cat['cat_name']));
        $smarty->assign('description', htmlspecialchars($cat['cat_desc']));
        $smarty->assign('cat_style',   htmlspecialchars($cat['style']));
    }
    else
    {
        ecs_header("Location: ./\n");
        exit;
    }
    /* 赋值固定内容 */
    if ($brand > 0)
    {
        $sql = "SELECT brand_name FROM " .$GLOBALS['ecs']->table('brand'). " WHERE brand_id = '$brand'";
        $brand_name = $db->getOne($sql);
        $brand_name = ' '.$brand_name;
    }
    else
    {
        $brand_name = '';
    }
    /**
     * Lọc theo bước giá
     * $price_start = Giá SP nhỏ nhất làm tròn + bước giá ==> Dưới xxx
     * $price_end = Giá SP lớn nhất làm tròn + bước giá
     */
    /* 获取价格分级 */
    if ($cat['grade'] == 0  && $cat['parent_id'] != 0)
    {
        $cat['grade'] = get_parent_grade($cat_id); //如果当前分类级别为空，取最近的上级分类
    }

    if ($cat['grade'] > 1)
    {

        $sql = "SELECT min(g.shop_price) AS min, max(g.shop_price) as max ".
               " FROM " . $ecs->table('goods'). " AS g ".
               " WHERE ($children OR " . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1  ';
               //获得当前分类下商品价格的最大值、最小值

        $row = $db->getRow($sql);

        // 取得价格分级最小单位级数，比如，千元商品最小以100为级数
        $price_grade = 0.0001;
        for($i=-2; $i<= log10($row['max']); $i++)
        {
            $price_grade *= 10;
        }

        //跨度
        $dx = ceil(($row['max'] - $row['min']) / ($cat['grade']) / $price_grade) * $price_grade;
        if($dx == 0)
        {
            $dx = $price_grade;
        }

        for($i = 1; $row['min'] > $dx * $i; $i ++);

        for($j = 1; $row['min'] > $dx * ($i-1) + $price_grade * $j; $j++);
        $row['min'] = $dx * ($i-1) + $price_grade * ($j - 1);

        for(; $row['max'] >= $dx * $i; $i ++);
        $row['max'] = $dx * ($i) + $price_grade * ($j - 1);

        $sql = "SELECT (FLOOR((g.shop_price - $row[min]) / $dx)) AS sn, COUNT(*) AS goods_num  ".
               " FROM " . $ecs->table('goods') . " AS g ".
               " WHERE ($children OR " . get_extension_goods($children) . ') AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 '.
               " GROUP BY sn ";

        $price_grade = $db->getAll($sql);

        foreach ($price_grade as $key=>$val)
        {
            $temp_key = $key + 1;
            $price_grade[$temp_key]['goods_num'] = $val['goods_num'];
            $price_grade[$temp_key]['start'] = $row['min'] + round($dx * $val['sn']);
            $price_grade[$temp_key]['end'] = $row['min'] + round($dx * ($val['sn'] + 1));
            $pstart = $price_grade[$temp_key]['start'] == 0 ? 'Dưới ' : price_format($price_grade[$temp_key]['start']).'&nbsp;-&nbsp;';
            $price_grade[$temp_key]['price_range'] =  $pstart . price_format($price_grade[$temp_key]['end']);
            $price_grade[$temp_key]['formated_start'] = price_format($price_grade[$temp_key]['start']);
            $price_grade[$temp_key]['formated_end'] = price_format($price_grade[$temp_key]['end']);
            $price_grade[$temp_key]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_grade[$temp_key]['start'], 'price_max'=> $price_grade[$temp_key]['end'], 'filter_attr'=>$filter_attr_str), $cat['cat_name']);

            /* 判断价格区间是否被选中 */
            if (isset($_REQUEST['tu']) && $price_grade[$temp_key]['start'] == $price_min && $price_grade[$temp_key]['end'] == $price_max)
            {
                $price_grade[$temp_key]['selected'] = 1;
            }
            else
            {
                $price_grade[$temp_key]['selected'] = 0;
            }
        }

        $price_grade[0]['start'] = 0;
        $price_grade[0]['end'] = 0;
        $price_grade[0]['price_range'] = $_LANG['all_attribute'];
        $price_grade[0]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>0, 'price_max'=> 0, 'filter_attr'=>$filter_attr_str), $cat['cat_name']);
        $price_grade[0]['selected'] = empty($price_max) ? 1 : 0;

        $smarty->assign('price_grade',     $price_grade);

    }
    /* Lọc hãng Sx */
    $sql = "SELECT b.brand_id, b.brand_name, COUNT(*) AS goods_num ".
            "FROM " . $GLOBALS['ecs']->table('brand') . "AS b, ".
                $GLOBALS['ecs']->table('goods') . " AS g LEFT JOIN ". $GLOBALS['ecs']->table('goods_cat') . " AS gc ON g.goods_id = gc.goods_id " .
            "WHERE g.brand_id = b.brand_id AND ($children OR " . 'gc.cat_id ' . db_create_in(array_unique(array_merge(array($cat_id), array_keys(cat_list($cat_id, 0, false))))) . ") AND b.is_show = 1 " .
            " AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 ".
            "GROUP BY b.brand_id HAVING goods_num > 0 ORDER BY b.sort_order, b.brand_id ASC";
    $brands = $GLOBALS['db']->getAll($sql);
    foreach ($brands AS $key => $val)
    {
        $temp_key = $key + 1;
        $brands[$temp_key]['brand_name'] = $val['brand_name'];
        $brands[$temp_key]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => $val['brand_id'], 'price_min'=>$price_min, 'price_max'=> $price_max, 'filter_attr'=>$filter_attr_str, 'rc'=>$recommend), $cat['cat_name']);

        if ($brand == $brands[$key]['brand_id'])
            $brands[$temp_key]['selected'] = 1;
        else
            $brands[$temp_key]['selected'] = 0;
    }
    $brands[0]['brand_name'] = $_LANG['all_attribute'];
    $brands[0]['url'] = build_uri('category', array('cid' => $cat_id, 'bid' => 0, 'price_min'=>$price_min, 'price_max'=> $price_max, 'filter_attr'=>$filter_attr_str, 'rc'=>$recommend), $cat['cat_name']);
    $brands[0]['selected'] = empty($brand) ? 1 : 0;
    $smarty->assign('brands', $brands);
    /* Lọc thuộc tính */
    $ext = '';
    $ext_title = '';
    if ($cat['filter_attr'] > 0)
    {
        $cat_filter_attr = explode(',', $cat['filter_attr']);       //提取出此分类的筛选属性
        $all_attr_list = array();

        foreach ($cat_filter_attr AS $key => $value)
        {
            $sql = "SELECT a.attr_name FROM " . $ecs->table('attribute') . " AS a, " . $ecs->table('goods_attr') . " AS ga, " . $ecs->table('goods') . " AS g WHERE ($children OR " . get_extension_goods($children) . ") AND a.attr_id = ga.attr_id AND g.goods_id = ga.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 AND a.attr_id='$value'";

            if($temp_name = $db->getOne($sql))
            {
                $all_attr_list[$key]['filter_attr_name'] = $temp_name;
                $sql = "SELECT a.attr_id, MIN(a.goods_attr_id ) AS goods_id, a.attr_value AS attr_value FROM " . $ecs->table('goods_attr') . " AS a, " . $ecs->table('goods') .
                       " AS g" .
                       " WHERE ($children OR " . get_extension_goods($children) . ') AND g.goods_id = a.goods_id AND g.is_delete = 0 AND g.is_on_sale = 1 AND g.is_alone_sale = 1 '.
                       " AND a.attr_id='$value' ".
                       " GROUP BY a.attr_value";
                $attr_list = $db->getAll($sql);
                $temp_arrt_url_arr = array();
                for ($i = 0; $i < count($cat_filter_attr); $i++)        //获取当前url中已选择属性的值，并保留在数组中
                {
                    $temp_arrt_url_arr[$i] = !empty($filter_attr[$i]) ? $filter_attr[$i] : 0;
                }
                $temp_arrt_url_arr[$key] = 0;                           //“全部”的信息生成
                $temp_arrt_url = implode('.', $temp_arrt_url_arr);
                $all_attr_list[$key]['attr_list'][0]['attr_value'] = $_LANG['all_attribute'];
                $all_attr_list[$key]['attr_list'][0]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_min, 'price_max'=>$price_max, 'filter_attr'=>$temp_arrt_url, 'rc'=>$recommend), $cat['cat_name']);
                $all_attr_list[$key]['attr_list'][0]['selected'] = empty($filter_attr[$key]) ? 1 : 0;
                foreach ($attr_list as $k => $v)
                {
                    $temp_key = $k + 1;
                    $temp_arrt_url_arr[$key] = $v['goods_id'];       //为url中代表当前筛选属性的位置变量赋值,并生成以‘.’分隔的筛选属性字符串
                    $temp_arrt_url = implode('.', $temp_arrt_url_arr);
                    $all_attr_list[$key]['attr_list'][$temp_key]['attr_value'] = $v['attr_value'];
                    $all_attr_list[$key]['attr_list'][$temp_key]['url'] = build_uri('category', array('cid'=>$cat_id, 'bid'=>$brand, 'price_min'=>$price_min, 'price_max'=>$price_max, 'filter_attr'=>$temp_arrt_url, 'rc'=>$recommend), $cat['cat_name']);
                    if (!empty($filter_attr[$key]) AND $filter_attr[$key] == $v['goods_id'])
                    {
                        $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 1;
                        $ext_title .= ', '.$temp_name.' '.$v['attr_value'];
                    }
                    else
                    {
                        $all_attr_list[$key]['attr_list'][$temp_key]['selected'] = 0;
                    }
                }
            }
        }
        $ext_title = ' '.ltrim($ext_title, ', ');

        //var_dump($all_attr_list);
        $smarty->assign('filter_attr_list',  $all_attr_list);
        /* 扩展商品查询条件 */
        if (!empty($filter_attr))
        {
            $ext_sql = "SELECT DISTINCT(b.goods_id) FROM " . $ecs->table('goods_attr') . " AS a, " . $ecs->table('goods_attr') . " AS b " .  "WHERE ";
            $ext_group_goods = array();
            foreach ($filter_attr AS $k => $v)                      // 查出符合所有筛选属性条件的商品id */
            {
                if (is_numeric($v) && $v !=0 &&isset($cat_filter_attr[$k]))
                {
                    $sql = $ext_sql . "b.attr_value = a.attr_value AND b.attr_id = " . $cat_filter_attr[$k] ." AND a.goods_attr_id = " . $v;
                    $ext_group_goods = $db->getColCached($sql);
                    $ext .= ' AND ' . db_create_in($ext_group_goods, 'g.goods_id');
                }
            }
        }
    }
    /**
     * Tính năng mới thêm để tránh trùng lặp meta title
     * Lọc sp recommend 1:new|2:best|3:hot tại danh mục hiện hành
     */
     $ext_rc_title = '';
    if($recommend == 'moi'){
        $ext .= ' AND g.is_new=1 ';
        $ext_rc_title .= ' mới';
    }elseif ($recommend == 'ban-chay') {
        $ext .= ' AND g.is_best=1 ';
        $ext_rc_title .= ' bán chạy';
    }elseif ($recommend == 'hot') {
        $ext .= ' AND g.is_hot=1 ';
        $ext_rc_title .= ' đang Hot';
    }
    /** Add by Nobj */
    $ext_title_price = '';
    if($price_min && $price_max){
        $fprice_min = price_format($price_min);
        $fprice_max = price_format($price_max);
        $ext_title_price = ' giá từ '.$fprice_min.' đến '.$fprice_max;
    }
    elseif($price_min){
        $fprice_min = price_format($price_min);
        $ext_title_price = ' giá trên '.$fprice_min;
    }elseif($price_max){
        $fprice_max = price_format($price_max);
        $ext_title_price = ' giá dưới '.$fprice_max;
    }

    if (!empty($filter_attr)){
        $cat_title =  $cat['cat_name'];
    }elseif($brand_name) {
        $cat_title = $cat['cat_name'].$brand_name;
    }else{
       $cat_title =  htmlspecialchars($cat['meta_title']);
    }

    $sort_title = '';
    if(isset($_REQUEST['sort']) && $_REQUEST['sort'] && $_REQUEST['order'] == 'ASC'){
        $sort_title =  ', giá tăng dần';
    }
    elseif(isset($_REQUEST['sort']) && $_REQUEST['sort'] && $_REQUEST['order'] == 'DESC'){
        $sort_title = ', giá giảm dần';
    }
    $paginator_title = $page > 1 ? ' #'.$page : '';
    $page_title = $cat_title.$ext_title_price.$ext_rc_title.$ext_title.$sort_title.$paginator_title;

    /**  End  */
    assign_template('c', array($cat_id));
    $position = assign_ur_here($cat_id, '', 'category');
    $smarty->assign('page_title', $page_title);
    $smarty->assign('ur_here',          $position['ur_here']);
   //$smarty->assign('categories',       get_categories_tree($cat_id));
    //$smarty->assign('helps',            get_shop_help());
    //$smarty->assign('top_goods',        get_top10());
    $smarty->assign('show_marketprice', $_CFG['show_marketprice']);
    $smarty->assign('category',         $cat_id);
    $smarty->assign('parent_id',        $cat['parent_id']);
    $smarty->assign('brand_id',         $brand);
    	$smarty->assign('slug',        		$slug);
	$smarty->assign('category',        	$url);
    $smarty->assign('brand_name',       $brand_name);
    $smarty->assign('price_max',        $price_max);
    $smarty->assign('price_min',        $price_min);
    $smarty->assign('filter_attr',      $filter_attr_str);
    $smarty->assign('recommend',        $recommend);
    $smarty->assign('long_desc',        $cat['long_desc']);

    // if ($brand > 0)
    // {
    //     $arr['all'] = array('brand_id'  => 0,
    //                     'brand_name'    => $GLOBALS['_LANG']['all_goods'],
    //                     'brand_logo'    => '',
    //                     'goods_num'     => '',
    //                     'url'           => build_uri('category', array('cid'=>$cat_id), $cat['cat_name'])
    //                 );
    // }
    // else
    // {
    //     $arr = array();
    // }
    // $brand_list = array_merge($arr, get_brands($cat_id, 'category'));
    // $smarty->assign('brand_list',      $brand_list);

    $smarty->assign('data_dir',    DATA_DIR);

    //$smarty->assign('promotion_info', get_promotion_info());

    if($client == 'pc'){
         $smarty->assign('catsubs', get_cat_child($cat_id, 0));
         $smarty->assign('hot_goods',  get_category_recommend_goods('hot', $children, $brand, $price_min, $price_max, $ext));
        //$smarty->assign('best_goods',      get_category_recommend_goods('best', $children, $brand, $price_min, $price_max, $ext));
        //$smarty->assign('promotion_goods', get_category_recommend_goods('promote', $children, $brand, $price_min, $price_max, $ext));
    }
    if($_device == '_mobile'){
        $smarty->assign('catsubs', get_cat_child_mobile($cat_id, $cat['parent_id']));
        $smarty->assign('agency',    get_agency());
    }

    $count = get_cagtegory_goods_count($children, $brand, $price_min, $price_max, $ext);
    $max_page = ($count> 0) ? ceil($count / $size) : 1;

    if ($page > $max_page)
    {
        $page = $max_page;
    }
    $goodslist = category_get_goods($children, $brand, $price_min, $price_max, $ext, $size, $page, $sort, $order);
    if($display == 'grid')
    {
        if(count($goodslist) % 2 != 0)
        {
            $goodslist[] = array();
        }
    }
    $smarty->assign('goods_list',       $goodslist);
    $smarty->assign('category',         $cat_id);
    $smarty->assign('parent_id',        $cat['parent_id']);
    $smarty->assign('ads_id',        $cat['ads_id']);

    $smarty->assign('script_name', 'category');
    $cat_url = build_uri('category', array('cid'=>$cat_id));
    $smarty->assign('cat_url',    $cat_url);

    $viewmore_number = intval($count)-($page*$size);
    $smarty->assign('viewmore_number', $viewmore_number);
    assign_pager('category', $cat_id, $count, $size, $sort, $order, $page, '', $brand, $price_min, $price_max, $display, $filter_attr_str); // 分页
    assign_dynamic('category');

}
$smarty->display($_template, $cache_id);


/**
 *
 * @param   integer $cat_id
 *
 * @return  void
 */
function get_cat_info($cat_id)
{
    return $GLOBALS['db']->getRow('SELECT cat_name, ads_id, meta_title, keywords, long_desc, cat_desc, style, grade, grade_value, filter_attr, parent_id FROM ' . $GLOBALS['ecs']->table('category') .
        " WHERE cat_id = '$cat_id'");
}
/**
 * @access  public
 * @param   string  $children
 * @return  array
 */
function category_get_goods($children, $brand, $min, $max, $ext, $size, $page, $sort, $order)
{
    $display = $GLOBALS['display'];
    $where = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND ".
            "g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
    if ($brand > 0)
    {
        $where .=  "AND g.brand_id=$brand ";
    }
    if ($min > 0)
    {
        $where .= " AND g.shop_price >= $min ";
    }
    if ($max > 0)
    {
        $where .= " AND g.shop_price <= $max ";
    }
    /* 获得商品列表 */
    $sql = 'SELECT g.goods_id, g.goods_name, g.goods_name_style, g.market_price, g.is_new, g.is_best, g.is_hot, g.shop_price AS org_price, ' .
                "IFNULL(mp.user_price, g.shop_price * '$_SESSION[discount]') AS shop_price, g.promote_price, g.goods_type, " .
                'g.promote_start_date, g.promote_end_date, g.online_price, g.desc_short, g.seller_note, g.show_thumb_special, g.goods_thumb_url, g.goods_thumb , g.goods_img ' .
            'FROM ' . $GLOBALS['ecs']->table('goods') . ' AS g ' .
            'LEFT JOIN ' . $GLOBALS['ecs']->table('member_price') . ' AS mp ' .
                "ON mp.goods_id = g.goods_id AND mp.user_rank = '$_SESSION[user_rank]' " .
            "WHERE $where $ext ORDER BY $sort $order";
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
        /* Phan tram giam gia */
        if($row['online_price'] > 0){
            $best_price = $row['online_price'];
        }else{
            $best_price = $promote_price > 0 ? $promote_price : $row['shop_price'];
        }
        if($best_price > 0 && $row['market_price'] > 0){
            $arr[$row['goods_id']]['rate_sale'] =  100-round(($best_price/$row['market_price'])*100, 0);
        }else{
            $arr[$row['goods_id']]['rate_sale']  = '';
        }
        /* End Phan tram giam gia */

        $arr[$row['goods_id']]['goods_id']         = $row['goods_id'];
        if($display == 'grid')
        {
            $arr[$row['goods_id']]['goods_name']       = $GLOBALS['_CFG']['goods_name_length'] > 0 ? sub_str($row['goods_name'], $GLOBALS['_CFG']['goods_name_length']) : $row['goods_name'];
        }
        else
        {
            $arr[$row['goods_id']]['goods_name']       = $row['goods_name'];
        }
        $arr[$row['goods_id']]['is_best']      = $row['is_best'];
        $arr[$row['goods_id']]['is_hot']       = $row['is_hot'];
        $arr[$row['goods_id']]['is_new']       = $row['is_new'];
        $arr[$row['goods_id']]['name']             = $row['goods_name'];
        $arr[$row['goods_id']]['desc_short']   = nl2p(strip_tags($row['desc_short']));
        $arr[$row['goods_id']]['seller_note']  = nl2p(strip_tags($row['seller_note']));
        $arr[$row['goods_id']]['goods_style_name'] = add_style($row['goods_name'],$row['goods_name_style']);
        $arr[$row['goods_id']]['market_price']     = $row['market_price'] > 0 ? price_format($row['market_price']) : '';
        $arr[$row['goods_id']]['shop_price']       = price_format($best_price);
        $arr[$row['goods_id']]['price']            = $row['shop_price'];
        $arr[$row['goods_id']]['type']             = $row['goods_type'];
        $arr[$row['goods_id']]['promote_price']    = ($promote_price > 0) ? price_format($promote_price) : '';
        $arr[$row['goods_id']]['goods_thumb']      = get_image_path($row['goods_id'], $row['goods_thumb'], true);
        $arr[$row['goods_id']]['goods_img']        = get_image_path($row['goods_id'], $row['goods_img']);
        $arr[$row['goods_id']]['thumb_url']        = $row['goods_thumb_url'];
        $arr[$row['goods_id']]['show_special']     = $row['show_thumb_special'];
        $arr[$row['goods_id']]['url']              = build_uri('goods', array('gid'=>$row['goods_id']), $row['goods_name']);
    }
    return $arr;
}
/**
 * 获得分类下的商品总数
 *
 * @access  public
 * @param   string     $cat_id
 * @return  integer
 */
function get_cagtegory_goods_count($children, $brand = 0, $min = 0, $max = 0, $ext='')
{
    $where  = "g.is_on_sale = 1 AND g.is_alone_sale = 1 AND g.is_delete = 0 AND ($children OR " . get_extension_goods($children) . ')';
    if ($brand > 0)
    {
        $where .=  " AND g.brand_id = $brand ";
    }
    if ($min > 0)
    {
        $where .= " AND g.shop_price >= $min ";
    }
    if ($max > 0)
    {
        $where .= " AND g.shop_price <= $max ";
    }
    /* 返回商品总数 */
    return $GLOBALS['db']->getOne('SELECT COUNT(*) FROM ' . $GLOBALS['ecs']->table('goods') . " AS g WHERE $where $ext");
}
/**
 * 取得最近的上级分类的grade值
 *
 * @access  public
 * @param   int     $cat_id    //当前的cat_id
 *
 * @return int
 */
function get_parent_grade($cat_id)
{
    static $res = NULL;
    if ($res === NULL)
    {
        $data = read_static_cache('cat_parent_grade');
        if ($data === false)
        {
            $sql = "SELECT parent_id, cat_id, grade, grade_value  ".
                   " FROM " . $GLOBALS['ecs']->table('category');
            $res = $GLOBALS['db']->getAll($sql);
            write_static_cache('cat_parent_grade', $res);
        }
        else
        {
            $res = $data;
        }
    }
    if (!$res)
    {
        return 0;
    }
    $parent_arr = array();
    $grade_arr = array();
    foreach ($res as $val)
    {
        $parent_arr[$val['cat_id']] = $val['parent_id'];
        $grade_arr[$val['cat_id']] = $val['grade'];

    }
    while ($parent_arr[$cat_id] >0 && $grade_arr[$cat_id] == 0)
    {
        $cat_id = $parent_arr[$cat_id];
    }
    return $grade_arr[$cat_id];
}
?>
