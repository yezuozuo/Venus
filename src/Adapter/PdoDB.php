<?php
/**
 * Created by PhpStorm.
 * User: zoco
 * Date: 16/8/16
 * Time: 17:06
 */

namespace Venus\Adapter;

use Venus\IDatabase;

/**
 * Class PdoDB
 *
 * @package Venus\Adapter
 */
class PdoDB extends \PDO implements IDatabase {

    /**
     * @var bool
     */
    public $debug = false;

    /**
     * @var
     */
    protected $config;

    /**
     * @param $dbConfig
     */
    public function __construct($dbConfig) {
        $this->config = $dbConfig;
    }

    public function connect() {
        $dsn = $this->config['dbms'] . ":host=" . $this->config['host'] . ";dbname=" . $this->config['name'];

        if (!empty($this->config['persistent'])) {
            parent::__construct($dsn, $this->config['user'], $this->config['password'], array(\PDO::ATTR_PERSISTENT => true));
        } else {
            parent::__construct($dsn, $this->config['user'], $this->config['password']);
        }

        $this->setAttribute(\PDO::ATTR_DEFAULT_FETCH_MODE, \PDO::FETCH_ASSOC);
    }

    /**
     * @param string $str
     * @param null   $paramType
     * @return string
     */
    public function quote($str, $paramType = null) {
        return trim(parent::quote($str, $paramType), '\'');
    }

    /**
     * 执行一个SQL语句
     *
     * @param string $sql
     * @return \PDOStatement
     */
    public final function query($sql) {
        if ($this->debug) {
            echo $sql;
        }
        $res = parent::query($sql);

        return $res;
    }

    /**
     * 执行一个参数化SQL语句,并返回一行结果
     *
     * @param $sql
     * @return bool|mixed
     */
    public final function queryLine($sql) {
        $params = func_get_args();
        if ($this->debug) {
            var_dump($params);
        }
        array_shift($params);
        $stm = $this->prepare($sql);
        if ($stm->execute($params)) {
            $ret = $stm->fetch();
            $stm->closeCursor();

            return $ret;
        } else {
            trigger_error("SQL Error", implode(", ", $this->errorInfo()) . "$sql");
            exit();
        }
    }

    /**
     * 执行一个参数化SQL语句,并返回所有结果
     *
     * @param $sql
     * @return array|bool
     */
    public final function queryAll($sql) {
        $params = func_get_args();
        if ($this->debug) {
            var_dump($params);
        }
        array_shift($params);
        $stm = $this->prepare($sql);
        if ($stm->execute($params)) {
            $ret = $stm->fetchAll();
            $stm->closeCursor();

            return $ret;
        } else {
            trigger_error("SQL Error", implode(", ", $this->errorInfo()) . "$sql");
            exit();
        }
    }

    /**
     * 执行一个参数化SQL语句
     *
     * @param $sql
     * @return bool|string
     */
    public final function execute($sql) {
        $params = func_get_args();
        if ($this->debug) {
            var_dump($params);
        }
        array_shift($params);
        $stm = $this->prepare($sql);
        if ($stm->execute($params)) {
            return $this->lastInsertId();
        } else {
            trigger_error("SQL Error", implode(", ", $this->errorInfo()) . "$sql");
            exit();
        }
    }

    /**
     * 关闭连接，释放资源
     */
    public function close() {
        unset($this);
    }

    public function update() {}

    public function delete() {}

    public function ping() {}

    public function insert() {}
}
