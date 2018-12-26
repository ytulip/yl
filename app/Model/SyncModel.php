<?php
namespace App\Model;
use App\Util\Kit;
use Illuminate\Support\Facades\DB;

class SyncModel{


    static private function nameGetCallback($code,$callback){
        $res = $callback();
        foreach($res as $item){
            if($item->ITEMNO == $code){
                return $item->ITEMNAME;
            }
        }
        return '';
    }

    static private  function selectedCallBack($name,$value,$callback){
        $defaultAttr = array(
            'name'=>'',
            'class'=>'sync-select'
        );

        if(is_array($name)) {
            $config = array_merge($defaultAttr, $name);
        }else{
            $defaultAttr['name'] = $name;
            $config = $defaultAttr;
        }

        $res = $callback();
        $str = sprintf('<select name="%s" class="%s" %s>',$config['name'],$config['class'],isset($config['id'])?'id="' . $config['id'] . '"':'');

        if(isset($config['defaultNull']) && ($config['defaultNull']= true) || !$value){
            $str .= '<option value="">--请选择--</option>';
        }

        foreach($res as $item){
            $str .= sprintf('<option value="%s" %s>%s</option>',$item->ITEMNO,(($item->ITEMNO == $value) && ($value !== false))?'selected':'',$item->ITEMNAME);
        }
        $str .= "</select>";
        return $str;
    }



    static public function productAttr($name,$value = false,$useParam = [])
    {
        return self::selectedCallBack($name,$value,function() use ($useParam){
            return Product::getProductAttrsConfig($useParam);
        });
    }

    static public function selfGetAddress($name,$value = false)
    {
        return self::selectedCallBack($name,$value,function(){
            return UserAddress::selfGetAddressConfig();
        });
    }

    public static function mineAddress($name,$value = false,$useParam = [])
    {
        return self::selectedCallBack($name,$value,function() use ($useParam){
            return UserAddress::mineAddressConfig($useParam);
        });
    }



    /**
     * @param $name
     * @param bool $value
     * @return string
     */
    static public function deliverType($name,$value = false){
        return self::selectedCallBack($name,$value,function(){
            return Deliver::deliverTypeConfig();
        });
    }



    /**
     * @param $name
     * @param bool $value
     * @return string
     */
    static public function neighborhoods($name,$value = false){
        return self::selectedCallBack($name,$value,function(){
            return Neighborhood::neighborhoodConfig();
        });
    }


    static public function period($name,$value = false){
        return self::selectedCallBack($name,$value,function(){
            return Period::periodConfig();
        });
    }


    static public function pctnameByCode($code)
    {
        $res = DB::table('code_library')->where('ITEMNO',$code)->first();
        return Kit::issetThenReturn($res,'ITEMNAME');
    }

}