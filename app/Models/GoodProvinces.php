<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GoodProvinces extends Model{

	protected $table = 'good_provinces';

    protected $fillable = ['order_id', 'good_id', 'provinces', 'review', 'network', 'user_id'];

	/**
     * 获取订单
     */
    public function order()
    {
        return $this->belongsTo('App\Models\Orders', 'id', 'order_id');
    }

    /**
     * 对应的省市
     * @return [type] [description]
     */
    public function province(){

    	return $this->belongsTo('App\Models\Provinces', 'provinces', 'id');
    }

    /**
     * 对应的操作记录
     * @return [type] [description]
     */
    public function goodProvincesRecord(){

        return $this->hasMany('App\Models\GoodProvincesRecord', 'goodprovince_id', 'id');
    }

    /**
     * 业务种类
     * @return [type] [description]
     */
    public function goods(){

        return $this->belongsTo('App\Models\Goods', 'good_id', 'id');
    }
}