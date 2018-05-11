<?php
/**
 * Created by PhpStorm.
 * User: mingming6
 * Date: 2018/5/11
 * Time: 9:23
 */
// 写入测试的内容

$content = '<?php phpinfo();';

$key = 'phpinfo';

$mysql_instance = new SaeMysql();
$sql = sprintf("REPLACE INTO `dynamic`(route_key,content)VALUES('%s', '%s')", $key, $content);

$ret = $mysql_instance->runSql($sql);

if ($ret) {
    echo("push success");
} else {
    echo("push failed");
}