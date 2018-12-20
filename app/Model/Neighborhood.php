<?php
namespace App\Model;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Neighborhood extends Model{

    protected $guarded = [];


    /**
     * @desc 返回配置value值
     * @param $name
     * @param $type
     * @return bool
     */
    static public function value($name,$type = 1){
        $obj = self::where(array('config_name'=>$name,'type'=>$type))->first();
        if($obj instanceof self){
            return $obj->config_value;
        }else{
            return false;
        }
    }


    static public function getColumnValueById($column,$id)
    {
        $obj = self::find($id);
        if( isset($obj->$column))
        {
            return $obj->$column;
        } else
        {
            return '';
        }
    }

    /*
     * 添加config
     */
    public function add_config($data){
        $id = DB::table($this->table)->insertGetId($data);
        return $id;
    }

    /*
     * 根据type更改
     * type 类型
     * is_enabled 1启用  2不启用
     */
    public function update_type_config($type = "sms", $is_enabled = 0){
        $affect = DB::table($this->table)->where("type", $type)->update(array("is_enabled"=>$is_enabled));
        return $affect;
    }

    /*
     * 根据名称更改
     * config_name 配置名
     */
    public function update_config_name($config_name, $data){
        $affect = DB::table($this->table)->where("config_name", $config_name)->update($data);
        return $affect;
    }

    /*
     * 根据type查询配置
     */
    public function get_type($type){
        $res = DB::table($this->table)->where("type", $type)->get();
        return $res;
    }

    /*
     * 根据type查询已启用的配置
     */
    public function select_type_enabled($type){
        $res = DB::table($this->table)->where("type", $type)->where("is_enabled", 1)->first();
        return $res;
    }

    /**
     * 根据配置名称查询
     * @param $config_name
     * @return mixed
     */
    public function get_config_by_name($config_name){
        $data = DB::table($this->table)->where('config_name', $config_name)->first();
        return $data;
    }

    public static function neighborhoodConfig()
    {
        return self::selectRaw('id as ITEMNO,neighborhood_name as ITEMNAME')->get();
    }
}