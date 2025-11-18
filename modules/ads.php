<?php
/** Edit by Nobj */
// define('IN_ECS', true);
define('INIT_NO_SMARTY', true);
// require __DIR__. '/../includes/init.php';

if (empty($_GET['ad_id']))
{
     ecs_header("Location: \n");
    exit;
}
else
{
    $ad_id = intval($_GET['ad_id']);
}

$db->query('UPDATE ' . $ecs->table('ad') . " SET click_count = click_count + 1 WHERE ad_id = '$ad_id'");

$sql="SELECT * FROM ". $ecs->table('ad') ." WHERE ad_id = '$ad_id'";
$ad_info=$db->getRow($sql);
if (!empty($ad_info['ad_link']))
{
    $uri =  urldecode($ad_info['ad_link']);
}
else
{
    $uri = $ecs->url();
}
ecs_header("Location: $uri\n");
exit;

?>