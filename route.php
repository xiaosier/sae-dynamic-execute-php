<?php
/**
 * Created by PhpStorm.
 * User: mingming6
 * Date: 2018/5/11
 * Time: 9:12
 */
// 用于动态的路由配置
$mysql_instace = new SaeMysql();
$route_key = $_GET['route_key'];

if (!$route_key) {
    die('403 fobbiden');
}

$route_key = $mysql_instace->escape($route_key);

// 检查memcached中是否有这个key，如果没有这个key，则去MySQL查询
$memcached_instance = new Memcached();
$memcached_instance->addServer('127.0.0.1', 27001);

$ret = $memcached_instance->get($route_key);

if (!$ret) {
    /*get content from mysql*/
    $sql = sprintf("SELECT `content` FROM `dynamic` WHERE `route_key`='%s'", $route_key);
    $content = $mysql_instace->getVar($sql);
    if (!$content) {
        /*未找到内容*/
        die('404 Not Found');
    }
    /*缓存*/
    $memcached_instance->set($route_key, $content, 86400);
} else {
    $content = $ret;
}

// 将内容写到临时目录
$tmp_path = sprintf('%s/%s.php', SAE_TMP_PATH, md5($route_key));

file_put_contents($tmp_path, $content);

/*加载你的其他内容，和你的实际业务结合*/
require($tmp_path);
