<?php
function insert_ads_pro($arr)
{
    $arr['cat_name'];
    $arr['type'];
    $ad_name = $arr['cat_name'].$arr['type'];

    $time = gmtime();
    if (!empty($ad_name))
    {
        $sql  = 'SELECT a.ad_id, a.position_id, a.media_type, a.ad_link, a.ad_code, a.ad_name, a.link_man, a.link_email, p.position_name, p.ad_width, ' .
                    'p.ad_height, p.position_style, RAND() AS rnd ' .
                'FROM ' . $GLOBALS['ecs']->table('ad') . ' AS a '.
                'LEFT JOIN ' . $GLOBALS['ecs']->table('ad_position') . ' AS p ON a.position_id = p.position_id ' .
                "WHERE enabled = 1 AND start_time <= '" . $time . "' AND end_time >= '" . $time . "' ".
                    "AND a.ad_name = '" . $ad_name . "' " .
                'ORDER BY a.ad_id ';


        $row = $GLOBALS['db']->GetRow($sql);
    }


    $src = (strpos($row['ad_code'], 'http://') === false && strpos($row['ad_code'], 'https://') === false) ? "afficheimg/$row[ad_code]" : $row['ad_code'];
    $row["ad_link"] = $row["ad_link"] != '' ? "tracking?ad_id=$row[ad_id]" : '';

    $need_cache = $GLOBALS['smarty']->caching;
    $GLOBALS['smarty']->caching = false;

    $GLOBALS['smarty']->assign('ad', $row);
    $GLOBALS['smarty']->assign('src', $src);

    $val = $GLOBALS['smarty']->fetch('library/ads_pro.lbi');
    $GLOBALS['smarty']->caching = $need_cache;
    return $val;

}
?>