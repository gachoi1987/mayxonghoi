<?php
/*define('IN_ECS', true);
require(dirname(__FILE__) . '/includes/init.php');*/
if ((DEBUG_MODE & 2) != 2)
{
    $smarty->caching = true;
}
/* 清除缓存 */
clear_cache_files();

/*------------------------------------------------------ */
//-- INPUT
/*------------------------------------------------------ */

$id  = $db->getOne("SELECT id FROM " . $ecs->table('slug') ." WHERE slug = '".$slug."' AND module='article_cat'");
$cat_id = intval($id);

/* 获得当前页码 */
$page   = !empty($_REQUEST['page'])  && intval($_REQUEST['page'])  > 0 ? intval($_REQUEST['page'])  : 1;
/*------------------------------------------------------ */
//-- PROCESSOR
/*------------------------------------------------------ */
/* 获得页面的缓存ID */
$cache_id = sprintf('%X', crc32($cat_id . '-' . $page . '-' . $_CFG['lang'].'-'.$_device));
$_teamplate = 'article_cat'.$_device.'.dwt';
if (!$smarty->is_cached($_teamplate, $cache_id))
{
    /** Active menu */
    if(isset($active_url)){
        $smarty->assign('active_url', $active_url);
    }
    assign_template('a', array($cat_id));
    $position = assign_ur_here($cat_id, '' , 'article_cat');

    $smarty->assign('ur_here',              $position['ur_here']);
    $smarty->assign('article_categories',           article_categories_tree($cat_id));

    //$smarty->assign('helps',                get_shop_help());
    //$smarty->assign('new_goods',            get_recommend_goods('new'));
    if($client == 'pc'){
        $smarty->assign('best_goods',           get_recommend_goods('best'));
        $smarty->assign('top_goods',            get_top10());
    }
     if($_device = '_mobile'){
        $smarty->assign('agency',    get_agency());
    }
    //$smarty->assign('promotion_goods',      get_promote_goods());
    //$smarty->assign('promotion_info', get_promotion_info());
    /* Meta */
    $meta = $db->getRow("SELECT cat_name, meta_title, keywords, long_desc, cat_desc FROM " . $ecs->table('article_cat') . " WHERE cat_id = '$cat_id'");
    if ($meta === false || empty($meta))
    {
        ecs_header("Location: ./\n");
        exit;
    }
    $page_number_title = $page > 1 ? ' #'.$page : '';
    $page_title = htmlspecialchars($meta['meta_title']).$page_number_title;
    $size   = isset($_CFG['article_page_size']) && intval($_CFG['article_page_size']) > 0 ? intval($_CFG['article_page_size']) : 20;
    $count  = get_article_count($cat_id);
    $pages  = ($count > 0) ? ceil($count / $size) : 1;
    if ($page > $pages)
    {
        $page = $pages;
    }
    $pager['search']['id'] = $cat_id;
    $keywords = '';
    $goon_keywords = ''; //继续传递的搜索关键词
    /* 获得文章列表 */
    if (isset($_REQUEST['keywords']))
    {
        $keywords = addslashes(htmlspecialchars(urldecode(trim($_REQUEST['keywords']))));
        $pager['search']['keywords'] = $keywords;
        $search_url = substr(strrchr($_POST['cur_url'], '/'), 1);
        $smarty->assign('search_value',    stripslashes(stripslashes($keywords)));
        $smarty->assign('search_url',       $search_url);
        $count  = get_article_count($cat_id, $keywords);
        $pages  = ($count > 0) ? ceil($count / $size) : 1;
        if ($page > $pages)
        {
            $page = $pages;
        }
        $goon_keywords = urlencode($_REQUEST['keywords']);
        $page_title = 'Kết quả tìm kiếm tin tức với từ khóa "'.stripslashes(stripslashes($keywords)).'" - '.$_CFG['shop_name'];
    }
    $smarty->assign('artciles_list',    get_cat_articles($cat_id, $page, $size ,$keywords));

    if($client == 'pc'){
        $smarty->assign('top5',    get_top5_articles($cat_id));
        $smarty->assign('top_view',    get_top10viewed_articles($cat_id));
    }
    $smarty->assign('total_search',    $count);
    $smarty->assign('cat_id',    $cat_id);
    $smarty->assign('cat_name',    $meta['cat_name']);
    $smarty->assign('page_title',  $page_title);
    $smarty->assign('keywords',    htmlspecialchars($meta['keywords']));
    $smarty->assign('description', htmlspecialchars($meta['cat_desc']));
    $smarty->assign('long_desc', $meta['long_desc']);
    assign_pager('article_cat', $cat_id, $count, $size, '', '', $page, $goon_keywords);
    assign_dynamic('article_cat');
}
$smarty->display($_teamplate, $cache_id);
?>