<?php
/**
 * Created by PhpStorm.
 * User: zoco
 * Date: 16/8/16
 * Time: 15:21
 */

require __DIR__ . '/../vendor/autoload.php';
$config = array(
    'dbms'       => 'mysql',
    'type'       => Venus\Database::TYPE_PDO,
    'host'       => "127.0.0.1",
    'port'       => 3306,
    'user'       => "root",
    'password'   => "123456789",
    'name'       => "mysql",
    'charset'    => "utf8",
    'persistent' => false,
);
$db     = new \Venus\Database($config);
$db->connect();
$sdb = $db->dbApt;
$sdb->select('*'); //$sdb->select('*',true);
$sdb->from('user');
$sdb->where('id=1'); //$sdb->where('*id=1',true);
$sdb->order('id desc');
$sdb->limit(1);
var_dump($sdb->getAll());