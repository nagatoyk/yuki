<?php
// .-----------------------------------------------------------------------------------
// |  Software: [HDPHP framework]
// |   Version: 2013.01
// |      Site: http://www.hdphp.com
// |-----------------------------------------------------------------------------------
// |    Author: 向军 <houdunwangxj@gmail.com>
// | Copyright (c) 2012-2013, http://houdunwang.com. All Rights Reserved.
// |-----------------------------------------------------------------------------------
// |   License: http://www.apache.org/licenses/LICENSE-2.0
// '-----------------------------------------------------------------------------------

/**
 * 视图模型处理类
 * @package     Model
 * @subpackage  Driver
 * @author      后盾向军 <houdunwangxj@gmail.com>
 */
class ViewModel extends Model
{
    public $view = array();
    public $joinTable = array();
    //设置关联表
    public function relation($joinTable = array())
    {
        if (is_string($joinTable)) {
            $this->joinTable = explode(',', $joinTable);
        }
        return $this;
    }
    //查询
    public function select($data = array())
    {
        $from = '';
        foreach ($this->view as $table => $set) {
            if(!empty($this->joinTable) && !in_array($table,$this->joinTable))continue;
            $from .= C('DB_PREFIX') . $table . ' ' . $table;
            //字段
            foreach ($set as $name => $f) {
                if ($name !== '_type' && $name !== '_on') {
                    $field = is_string($name) ? array($table . '.' . $name => $f) : $f;
                    $this->field($field);
                }
            }
            if (isset($set['_on'])) {
                $on = preg_replace('@__(\w+)__@', '\1', $set['_on']);
                $from .= " ON $on ";
            }
            //_TYPE关联方式
            if (isset($set['_type'])) {
                $from .= ' '.strtoupper($set['_type']).' JOIN ';
            }
        }
        if(substr($from,-11)=='INNER JOIN ')$from=substr($from,0,-11);
        if(substr($from,-11)=='RIGHT JOIN ')$from=substr($from,0,-11);
        if(substr($from,-10)=='LEFT JOIN ')$from=substr($from,0,-10);
        $this->db->opt['table'] = $from;
        $this->joinTable = array();
        return parent::select($data);
    }
}