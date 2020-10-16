<?php

use Illuminate\Database\Seeder;

class ProvincesTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('provinces')->delete();
        
        \DB::table('provinces')->insert(array (
            0 => 
            array (
                'id' => 2,
                'parent_id' => 0,
                'name' => '北京市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 => 
            array (
                'id' => 18,
                'parent_id' => 0,
                'name' => '天津市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 => 
            array (
                'id' => 35,
                'parent_id' => 0,
                'name' => '河北省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 => 
            array (
                'id' => 36,
                'parent_id' => 35,
                'name' => '石家庄市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            4 => 
            array (
                'id' => 37,
                'parent_id' => 35,
                'name' => '唐山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            5 => 
            array (
                'id' => 38,
                'parent_id' => 35,
                'name' => '秦皇岛市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            6 => 
            array (
                'id' => 39,
                'parent_id' => 35,
                'name' => '邯郸市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            7 => 
            array (
                'id' => 40,
                'parent_id' => 35,
                'name' => '邢台市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            8 => 
            array (
                'id' => 41,
                'parent_id' => 35,
                'name' => '保定市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            9 => 
            array (
                'id' => 42,
                'parent_id' => 35,
                'name' => '张家口市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            10 => 
            array (
                'id' => 43,
                'parent_id' => 35,
                'name' => '承德市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            11 => 
            array (
                'id' => 44,
                'parent_id' => 35,
                'name' => '沧州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            12 => 
            array (
                'id' => 45,
                'parent_id' => 35,
                'name' => '廊坊市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            13 => 
            array (
                'id' => 46,
                'parent_id' => 35,
                'name' => '衡水市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            14 => 
            array (
                'id' => 47,
                'parent_id' => 0,
                'name' => '山西省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            15 => 
            array (
                'id' => 48,
                'parent_id' => 47,
                'name' => '太原市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            16 => 
            array (
                'id' => 49,
                'parent_id' => 47,
                'name' => '大同市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            17 => 
            array (
                'id' => 50,
                'parent_id' => 47,
                'name' => '阳泉市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            18 => 
            array (
                'id' => 51,
                'parent_id' => 47,
                'name' => '长治市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            19 => 
            array (
                'id' => 52,
                'parent_id' => 47,
                'name' => '晋城市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            20 => 
            array (
                'id' => 53,
                'parent_id' => 47,
                'name' => '朔州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            21 => 
            array (
                'id' => 54,
                'parent_id' => 47,
                'name' => '晋中市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            22 => 
            array (
                'id' => 55,
                'parent_id' => 47,
                'name' => '运城市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            23 => 
            array (
                'id' => 56,
                'parent_id' => 47,
                'name' => '忻州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            24 => 
            array (
                'id' => 57,
                'parent_id' => 47,
                'name' => '临汾市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            25 => 
            array (
                'id' => 58,
                'parent_id' => 47,
                'name' => '吕梁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            26 => 
            array (
                'id' => 59,
                'parent_id' => 0,
                'name' => '内蒙古自治区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            27 => 
            array (
                'id' => 60,
                'parent_id' => 59,
                'name' => '呼和浩特市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            28 => 
            array (
                'id' => 61,
                'parent_id' => 59,
                'name' => '包头市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            29 => 
            array (
                'id' => 62,
                'parent_id' => 59,
                'name' => '乌海市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            30 => 
            array (
                'id' => 63,
                'parent_id' => 59,
                'name' => '赤峰市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            31 => 
            array (
                'id' => 64,
                'parent_id' => 59,
                'name' => '通辽市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            32 => 
            array (
                'id' => 65,
                'parent_id' => 59,
                'name' => '鄂尔多斯市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            33 => 
            array (
                'id' => 66,
                'parent_id' => 59,
                'name' => '呼伦贝尔市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            34 => 
            array (
                'id' => 67,
                'parent_id' => 59,
                'name' => '巴彦淖尔市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            35 => 
            array (
                'id' => 68,
                'parent_id' => 59,
                'name' => '乌兰察布市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            36 => 
            array (
                'id' => 69,
                'parent_id' => 59,
                'name' => '兴安盟',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            37 => 
            array (
                'id' => 70,
                'parent_id' => 59,
                'name' => '锡林郭勒盟',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            38 => 
            array (
                'id' => 71,
                'parent_id' => 59,
                'name' => '阿拉善盟',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            39 => 
            array (
                'id' => 72,
                'parent_id' => 0,
                'name' => '辽宁省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            40 => 
            array (
                'id' => 73,
                'parent_id' => 72,
                'name' => '沈阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            41 => 
            array (
                'id' => 74,
                'parent_id' => 72,
                'name' => '大连市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            42 => 
            array (
                'id' => 75,
                'parent_id' => 72,
                'name' => '鞍山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            43 => 
            array (
                'id' => 76,
                'parent_id' => 72,
                'name' => '抚顺市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            44 => 
            array (
                'id' => 77,
                'parent_id' => 72,
                'name' => '本溪市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            45 => 
            array (
                'id' => 78,
                'parent_id' => 72,
                'name' => '丹东市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            46 => 
            array (
                'id' => 79,
                'parent_id' => 72,
                'name' => '锦州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            47 => 
            array (
                'id' => 80,
                'parent_id' => 72,
                'name' => '营口市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            48 => 
            array (
                'id' => 81,
                'parent_id' => 72,
                'name' => '阜新市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            49 => 
            array (
                'id' => 82,
                'parent_id' => 72,
                'name' => '辽阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            50 => 
            array (
                'id' => 83,
                'parent_id' => 72,
                'name' => '盘锦市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            51 => 
            array (
                'id' => 84,
                'parent_id' => 72,
                'name' => '铁岭市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            52 => 
            array (
                'id' => 85,
                'parent_id' => 72,
                'name' => '朝阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            53 => 
            array (
                'id' => 86,
                'parent_id' => 72,
                'name' => '葫芦岛市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            54 => 
            array (
                'id' => 87,
                'parent_id' => 0,
                'name' => '吉林省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            55 => 
            array (
                'id' => 88,
                'parent_id' => 87,
                'name' => '长春市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            56 => 
            array (
                'id' => 89,
                'parent_id' => 87,
                'name' => '吉林市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            57 => 
            array (
                'id' => 90,
                'parent_id' => 87,
                'name' => '四平市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            58 => 
            array (
                'id' => 91,
                'parent_id' => 87,
                'name' => '辽源市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            59 => 
            array (
                'id' => 92,
                'parent_id' => 87,
                'name' => '通化市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            60 => 
            array (
                'id' => 93,
                'parent_id' => 87,
                'name' => '白山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            61 => 
            array (
                'id' => 94,
                'parent_id' => 87,
                'name' => '松原市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            62 => 
            array (
                'id' => 95,
                'parent_id' => 87,
                'name' => '白城市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            63 => 
            array (
                'id' => 96,
                'parent_id' => 87,
                'name' => '延边朝鲜族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            64 => 
            array (
                'id' => 97,
                'parent_id' => 0,
                'name' => '黑龙江省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            65 => 
            array (
                'id' => 98,
                'parent_id' => 97,
                'name' => '哈尔滨市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            66 => 
            array (
                'id' => 99,
                'parent_id' => 97,
                'name' => '齐齐哈尔市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            67 => 
            array (
                'id' => 100,
                'parent_id' => 97,
                'name' => '鸡西市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            68 => 
            array (
                'id' => 101,
                'parent_id' => 97,
                'name' => '鹤岗市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            69 => 
            array (
                'id' => 102,
                'parent_id' => 97,
                'name' => '双鸭山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            70 => 
            array (
                'id' => 103,
                'parent_id' => 97,
                'name' => '大庆市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            71 => 
            array (
                'id' => 104,
                'parent_id' => 97,
                'name' => '伊春市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            72 => 
            array (
                'id' => 105,
                'parent_id' => 97,
                'name' => '佳木斯市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            73 => 
            array (
                'id' => 106,
                'parent_id' => 97,
                'name' => '七台河市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            74 => 
            array (
                'id' => 107,
                'parent_id' => 97,
                'name' => '牡丹江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            75 => 
            array (
                'id' => 108,
                'parent_id' => 97,
                'name' => '黑河市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            76 => 
            array (
                'id' => 109,
                'parent_id' => 97,
                'name' => '绥化市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            77 => 
            array (
                'id' => 110,
                'parent_id' => 97,
                'name' => '大兴安岭地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            78 => 
            array (
                'id' => 111,
                'parent_id' => 0,
                'name' => '上海市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            79 => 
            array (
                'id' => 1,
                'parent_id' => 0,
                'name' => '全国',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            80 => 
            array (
                'id' => 128,
                'parent_id' => 0,
                'name' => '江苏省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            81 => 
            array (
                'id' => 129,
                'parent_id' => 128,
                'name' => '南京市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            82 => 
            array (
                'id' => 130,
                'parent_id' => 128,
                'name' => '无锡市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            83 => 
            array (
                'id' => 131,
                'parent_id' => 128,
                'name' => '徐州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            84 => 
            array (
                'id' => 132,
                'parent_id' => 128,
                'name' => '常州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            85 => 
            array (
                'id' => 133,
                'parent_id' => 128,
                'name' => '苏州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            86 => 
            array (
                'id' => 134,
                'parent_id' => 128,
                'name' => '南通市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            87 => 
            array (
                'id' => 135,
                'parent_id' => 128,
                'name' => '连云港市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            88 => 
            array (
                'id' => 136,
                'parent_id' => 128,
                'name' => '淮安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            89 => 
            array (
                'id' => 137,
                'parent_id' => 128,
                'name' => '盐城市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            90 => 
            array (
                'id' => 138,
                'parent_id' => 128,
                'name' => '扬州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            91 => 
            array (
                'id' => 139,
                'parent_id' => 128,
                'name' => '镇江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            92 => 
            array (
                'id' => 140,
                'parent_id' => 128,
                'name' => '泰州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            93 => 
            array (
                'id' => 141,
                'parent_id' => 128,
                'name' => '宿迁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            94 => 
            array (
                'id' => 142,
                'parent_id' => 0,
                'name' => '浙江省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            95 => 
            array (
                'id' => 143,
                'parent_id' => 142,
                'name' => '杭州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            96 => 
            array (
                'id' => 144,
                'parent_id' => 142,
                'name' => '宁波市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            97 => 
            array (
                'id' => 145,
                'parent_id' => 142,
                'name' => '温州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            98 => 
            array (
                'id' => 146,
                'parent_id' => 142,
                'name' => '嘉兴市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            99 => 
            array (
                'id' => 147,
                'parent_id' => 142,
                'name' => '湖州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            100 => 
            array (
                'id' => 148,
                'parent_id' => 142,
                'name' => '绍兴市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            101 => 
            array (
                'id' => 149,
                'parent_id' => 142,
                'name' => '金华市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            102 => 
            array (
                'id' => 150,
                'parent_id' => 142,
                'name' => '衢州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            103 => 
            array (
                'id' => 151,
                'parent_id' => 142,
                'name' => '舟山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            104 => 
            array (
                'id' => 152,
                'parent_id' => 142,
                'name' => '台州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            105 => 
            array (
                'id' => 153,
                'parent_id' => 142,
                'name' => '丽水市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            106 => 
            array (
                'id' => 154,
                'parent_id' => 0,
                'name' => '安徽省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            107 => 
            array (
                'id' => 155,
                'parent_id' => 154,
                'name' => '合肥市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            108 => 
            array (
                'id' => 156,
                'parent_id' => 154,
                'name' => '芜湖市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            109 => 
            array (
                'id' => 157,
                'parent_id' => 154,
                'name' => '蚌埠市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            110 => 
            array (
                'id' => 158,
                'parent_id' => 154,
                'name' => '淮南市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            111 => 
            array (
                'id' => 159,
                'parent_id' => 154,
                'name' => '马鞍山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            112 => 
            array (
                'id' => 160,
                'parent_id' => 154,
                'name' => '淮北市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            113 => 
            array (
                'id' => 161,
                'parent_id' => 154,
                'name' => '铜陵市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            114 => 
            array (
                'id' => 162,
                'parent_id' => 154,
                'name' => '安庆市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            115 => 
            array (
                'id' => 163,
                'parent_id' => 154,
                'name' => '黄山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            116 => 
            array (
                'id' => 164,
                'parent_id' => 154,
                'name' => '滁州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            117 => 
            array (
                'id' => 165,
                'parent_id' => 154,
                'name' => '阜阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            118 => 
            array (
                'id' => 166,
                'parent_id' => 154,
                'name' => '宿州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            119 => 
            array (
                'id' => 167,
                'parent_id' => 154,
                'name' => '六安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            120 => 
            array (
                'id' => 168,
                'parent_id' => 154,
                'name' => '亳州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            121 => 
            array (
                'id' => 169,
                'parent_id' => 154,
                'name' => '池州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            122 => 
            array (
                'id' => 170,
                'parent_id' => 154,
                'name' => '宣城市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            123 => 
            array (
                'id' => 171,
                'parent_id' => 0,
                'name' => '福建省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            124 => 
            array (
                'id' => 172,
                'parent_id' => 171,
                'name' => '福州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            125 => 
            array (
                'id' => 173,
                'parent_id' => 171,
                'name' => '厦门市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            126 => 
            array (
                'id' => 174,
                'parent_id' => 171,
                'name' => '莆田市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            127 => 
            array (
                'id' => 175,
                'parent_id' => 171,
                'name' => '三明市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            128 => 
            array (
                'id' => 176,
                'parent_id' => 171,
                'name' => '泉州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            129 => 
            array (
                'id' => 177,
                'parent_id' => 171,
                'name' => '漳州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            130 => 
            array (
                'id' => 178,
                'parent_id' => 171,
                'name' => '南平市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            131 => 
            array (
                'id' => 179,
                'parent_id' => 171,
                'name' => '龙岩市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            132 => 
            array (
                'id' => 180,
                'parent_id' => 171,
                'name' => '宁德市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            133 => 
            array (
                'id' => 181,
                'parent_id' => 0,
                'name' => '江西省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            134 => 
            array (
                'id' => 182,
                'parent_id' => 181,
                'name' => '南昌市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            135 => 
            array (
                'id' => 183,
                'parent_id' => 181,
                'name' => '景德镇市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            136 => 
            array (
                'id' => 184,
                'parent_id' => 181,
                'name' => '萍乡市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            137 => 
            array (
                'id' => 185,
                'parent_id' => 181,
                'name' => '九江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            138 => 
            array (
                'id' => 186,
                'parent_id' => 181,
                'name' => '新余市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            139 => 
            array (
                'id' => 187,
                'parent_id' => 181,
                'name' => '鹰潭市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            140 => 
            array (
                'id' => 188,
                'parent_id' => 181,
                'name' => '赣州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            141 => 
            array (
                'id' => 189,
                'parent_id' => 181,
                'name' => '吉安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            142 => 
            array (
                'id' => 190,
                'parent_id' => 181,
                'name' => '宜春市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            143 => 
            array (
                'id' => 191,
                'parent_id' => 181,
                'name' => '抚州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            144 => 
            array (
                'id' => 192,
                'parent_id' => 181,
                'name' => '上饶市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            145 => 
            array (
                'id' => 193,
                'parent_id' => 0,
                'name' => '山东省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            146 => 
            array (
                'id' => 194,
                'parent_id' => 193,
                'name' => '济南市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            147 => 
            array (
                'id' => 195,
                'parent_id' => 193,
                'name' => '青岛市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            148 => 
            array (
                'id' => 196,
                'parent_id' => 193,
                'name' => '淄博市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            149 => 
            array (
                'id' => 197,
                'parent_id' => 193,
                'name' => '枣庄市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            150 => 
            array (
                'id' => 198,
                'parent_id' => 193,
                'name' => '东营市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            151 => 
            array (
                'id' => 199,
                'parent_id' => 193,
                'name' => '烟台市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            152 => 
            array (
                'id' => 200,
                'parent_id' => 193,
                'name' => '潍坊市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            153 => 
            array (
                'id' => 201,
                'parent_id' => 193,
                'name' => '济宁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            154 => 
            array (
                'id' => 202,
                'parent_id' => 193,
                'name' => '泰安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            155 => 
            array (
                'id' => 203,
                'parent_id' => 193,
                'name' => '威海市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            156 => 
            array (
                'id' => 204,
                'parent_id' => 193,
                'name' => '日照市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            157 => 
            array (
                'id' => 205,
                'parent_id' => 193,
                'name' => '莱芜市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            158 => 
            array (
                'id' => 206,
                'parent_id' => 193,
                'name' => '临沂市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            159 => 
            array (
                'id' => 207,
                'parent_id' => 193,
                'name' => '德州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            160 => 
            array (
                'id' => 208,
                'parent_id' => 193,
                'name' => '聊城市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            161 => 
            array (
                'id' => 209,
                'parent_id' => 193,
                'name' => '滨州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            162 => 
            array (
                'id' => 210,
                'parent_id' => 193,
                'name' => '菏泽市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            163 => 
            array (
                'id' => 211,
                'parent_id' => 0,
                'name' => '河南省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            164 => 
            array (
                'id' => 212,
                'parent_id' => 211,
                'name' => '郑州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            165 => 
            array (
                'id' => 213,
                'parent_id' => 211,
                'name' => '开封市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            166 => 
            array (
                'id' => 214,
                'parent_id' => 211,
                'name' => '洛阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            167 => 
            array (
                'id' => 215,
                'parent_id' => 211,
                'name' => '平顶山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            168 => 
            array (
                'id' => 216,
                'parent_id' => 211,
                'name' => '安阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            169 => 
            array (
                'id' => 217,
                'parent_id' => 211,
                'name' => '鹤壁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            170 => 
            array (
                'id' => 218,
                'parent_id' => 211,
                'name' => '新乡市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            171 => 
            array (
                'id' => 219,
                'parent_id' => 211,
                'name' => '焦作市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            172 => 
            array (
                'id' => 220,
                'parent_id' => 211,
                'name' => '濮阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            173 => 
            array (
                'id' => 221,
                'parent_id' => 211,
                'name' => '许昌市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            174 => 
            array (
                'id' => 222,
                'parent_id' => 211,
                'name' => '漯河市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            175 => 
            array (
                'id' => 223,
                'parent_id' => 211,
                'name' => '三门峡市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            176 => 
            array (
                'id' => 224,
                'parent_id' => 211,
                'name' => '南阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            177 => 
            array (
                'id' => 225,
                'parent_id' => 211,
                'name' => '商丘市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            178 => 
            array (
                'id' => 226,
                'parent_id' => 211,
                'name' => '信阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            179 => 
            array (
                'id' => 227,
                'parent_id' => 211,
                'name' => '周口市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            180 => 
            array (
                'id' => 228,
                'parent_id' => 211,
                'name' => '驻马店市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            181 => 
            array (
                'id' => 229,
                'parent_id' => 211,
                'name' => '济源市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            182 => 
            array (
                'id' => 230,
                'parent_id' => 0,
                'name' => '湖北省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            183 => 
            array (
                'id' => 231,
                'parent_id' => 230,
                'name' => '武汉市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            184 => 
            array (
                'id' => 232,
                'parent_id' => 230,
                'name' => '黄石市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            185 => 
            array (
                'id' => 233,
                'parent_id' => 230,
                'name' => '十堰市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            186 => 
            array (
                'id' => 234,
                'parent_id' => 230,
                'name' => '宜昌市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            187 => 
            array (
                'id' => 235,
                'parent_id' => 230,
                'name' => '襄阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            188 => 
            array (
                'id' => 236,
                'parent_id' => 230,
                'name' => '鄂州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            189 => 
            array (
                'id' => 237,
                'parent_id' => 230,
                'name' => '荆门市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            190 => 
            array (
                'id' => 238,
                'parent_id' => 230,
                'name' => '孝感市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            191 => 
            array (
                'id' => 239,
                'parent_id' => 230,
                'name' => '荆州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            192 => 
            array (
                'id' => 240,
                'parent_id' => 230,
                'name' => '黄冈市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            193 => 
            array (
                'id' => 241,
                'parent_id' => 230,
                'name' => '咸宁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            194 => 
            array (
                'id' => 242,
                'parent_id' => 230,
                'name' => '随州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            195 => 
            array (
                'id' => 243,
                'parent_id' => 230,
                'name' => '恩施土家族苗族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            196 => 
            array (
                'id' => 244,
                'parent_id' => 230,
                'name' => '仙桃市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            197 => 
            array (
                'id' => 245,
                'parent_id' => 230,
                'name' => '潜江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            198 => 
            array (
                'id' => 246,
                'parent_id' => 230,
                'name' => '天门市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            199 => 
            array (
                'id' => 247,
                'parent_id' => 230,
                'name' => '神农架林区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            200 => 
            array (
                'id' => 248,
                'parent_id' => 0,
                'name' => '湖南省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            201 => 
            array (
                'id' => 249,
                'parent_id' => 248,
                'name' => '长沙市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            202 => 
            array (
                'id' => 250,
                'parent_id' => 248,
                'name' => '株洲市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            203 => 
            array (
                'id' => 251,
                'parent_id' => 248,
                'name' => '湘潭市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            204 => 
            array (
                'id' => 252,
                'parent_id' => 248,
                'name' => '衡阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            205 => 
            array (
                'id' => 253,
                'parent_id' => 248,
                'name' => '邵阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            206 => 
            array (
                'id' => 254,
                'parent_id' => 248,
                'name' => '岳阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            207 => 
            array (
                'id' => 255,
                'parent_id' => 248,
                'name' => '常德市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            208 => 
            array (
                'id' => 256,
                'parent_id' => 248,
                'name' => '张家界市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            209 => 
            array (
                'id' => 257,
                'parent_id' => 248,
                'name' => '益阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            210 => 
            array (
                'id' => 258,
                'parent_id' => 248,
                'name' => '郴州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            211 => 
            array (
                'id' => 259,
                'parent_id' => 248,
                'name' => '永州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            212 => 
            array (
                'id' => 260,
                'parent_id' => 248,
                'name' => '怀化市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            213 => 
            array (
                'id' => 261,
                'parent_id' => 248,
                'name' => '娄底市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            214 => 
            array (
                'id' => 262,
                'parent_id' => 248,
                'name' => '湘西土家族苗族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            215 => 
            array (
                'id' => 263,
                'parent_id' => 0,
                'name' => '广东省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            216 => 
            array (
                'id' => 264,
                'parent_id' => 263,
                'name' => '广州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            217 => 
            array (
                'id' => 265,
                'parent_id' => 263,
                'name' => '韶关市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            218 => 
            array (
                'id' => 266,
                'parent_id' => 263,
                'name' => '深圳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            219 => 
            array (
                'id' => 267,
                'parent_id' => 263,
                'name' => '珠海市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            220 => 
            array (
                'id' => 268,
                'parent_id' => 263,
                'name' => '汕头市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            221 => 
            array (
                'id' => 269,
                'parent_id' => 263,
                'name' => '佛山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            222 => 
            array (
                'id' => 270,
                'parent_id' => 263,
                'name' => '江门市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            223 => 
            array (
                'id' => 271,
                'parent_id' => 263,
                'name' => '湛江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            224 => 
            array (
                'id' => 272,
                'parent_id' => 263,
                'name' => '茂名市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            225 => 
            array (
                'id' => 273,
                'parent_id' => 263,
                'name' => '肇庆市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            226 => 
            array (
                'id' => 274,
                'parent_id' => 263,
                'name' => '惠州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            227 => 
            array (
                'id' => 275,
                'parent_id' => 263,
                'name' => '梅州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            228 => 
            array (
                'id' => 276,
                'parent_id' => 263,
                'name' => '汕尾市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            229 => 
            array (
                'id' => 277,
                'parent_id' => 263,
                'name' => '河源市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            230 => 
            array (
                'id' => 278,
                'parent_id' => 263,
                'name' => '阳江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            231 => 
            array (
                'id' => 279,
                'parent_id' => 263,
                'name' => '清远市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            232 => 
            array (
                'id' => 280,
                'parent_id' => 263,
                'name' => '东莞市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            233 => 
            array (
                'id' => 281,
                'parent_id' => 263,
                'name' => '中山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            234 => 
            array (
                'id' => 282,
                'parent_id' => 263,
                'name' => '潮州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            235 => 
            array (
                'id' => 283,
                'parent_id' => 263,
                'name' => '揭阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            236 => 
            array (
                'id' => 284,
                'parent_id' => 263,
                'name' => '云浮市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            237 => 
            array (
                'id' => 285,
                'parent_id' => 0,
                'name' => '广西壮族自治区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            238 => 
            array (
                'id' => 286,
                'parent_id' => 285,
                'name' => '南宁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            239 => 
            array (
                'id' => 287,
                'parent_id' => 285,
                'name' => '柳州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            240 => 
            array (
                'id' => 288,
                'parent_id' => 285,
                'name' => '桂林市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            241 => 
            array (
                'id' => 289,
                'parent_id' => 285,
                'name' => '梧州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            242 => 
            array (
                'id' => 290,
                'parent_id' => 285,
                'name' => '北海市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            243 => 
            array (
                'id' => 291,
                'parent_id' => 285,
                'name' => '防城港市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            244 => 
            array (
                'id' => 292,
                'parent_id' => 285,
                'name' => '钦州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            245 => 
            array (
                'id' => 293,
                'parent_id' => 285,
                'name' => '贵港市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            246 => 
            array (
                'id' => 294,
                'parent_id' => 285,
                'name' => '玉林市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            247 => 
            array (
                'id' => 295,
                'parent_id' => 285,
                'name' => '百色市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            248 => 
            array (
                'id' => 296,
                'parent_id' => 285,
                'name' => '贺州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            249 => 
            array (
                'id' => 297,
                'parent_id' => 285,
                'name' => '河池市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            250 => 
            array (
                'id' => 298,
                'parent_id' => 285,
                'name' => '来宾市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            251 => 
            array (
                'id' => 299,
                'parent_id' => 285,
                'name' => '崇左市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            252 => 
            array (
                'id' => 300,
                'parent_id' => 0,
                'name' => '海南省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            253 => 
            array (
                'id' => 301,
                'parent_id' => 300,
                'name' => '海口市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            254 => 
            array (
                'id' => 302,
                'parent_id' => 300,
                'name' => '三亚市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            255 => 
            array (
                'id' => 303,
                'parent_id' => 300,
                'name' => '三沙市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            256 => 
            array (
                'id' => 304,
                'parent_id' => 300,
                'name' => '儋州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            257 => 
            array (
                'id' => 305,
                'parent_id' => 300,
                'name' => '五指山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            258 => 
            array (
                'id' => 306,
                'parent_id' => 300,
                'name' => '琼海市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            259 => 
            array (
                'id' => 307,
                'parent_id' => 300,
                'name' => '文昌市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            260 => 
            array (
                'id' => 308,
                'parent_id' => 300,
                'name' => '万宁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            261 => 
            array (
                'id' => 309,
                'parent_id' => 300,
                'name' => '东方市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            262 => 
            array (
                'id' => 310,
                'parent_id' => 300,
                'name' => '定安县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            263 => 
            array (
                'id' => 311,
                'parent_id' => 300,
                'name' => '屯昌县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            264 => 
            array (
                'id' => 312,
                'parent_id' => 300,
                'name' => '澄迈县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            265 => 
            array (
                'id' => 313,
                'parent_id' => 300,
                'name' => '临高县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            266 => 
            array (
                'id' => 314,
                'parent_id' => 300,
                'name' => '白沙黎族自治县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            267 => 
            array (
                'id' => 315,
                'parent_id' => 300,
                'name' => '昌江黎族自治县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            268 => 
            array (
                'id' => 316,
                'parent_id' => 300,
                'name' => '乐东黎族自治县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            269 => 
            array (
                'id' => 317,
                'parent_id' => 300,
                'name' => '陵水黎族自治县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            270 => 
            array (
                'id' => 318,
                'parent_id' => 300,
                'name' => '保亭黎族苗族自治县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            271 => 
            array (
                'id' => 319,
                'parent_id' => 300,
                'name' => '琼中黎族苗族自治县',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            272 => 
            array (
                'id' => 320,
                'parent_id' => 0,
                'name' => '重庆市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            311 => 
            array (
                'id' => 359,
                'parent_id' => 0,
                'name' => '四川省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            312 => 
            array (
                'id' => 360,
                'parent_id' => 359,
                'name' => '成都市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            313 => 
            array (
                'id' => 361,
                'parent_id' => 359,
                'name' => '自贡市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            314 => 
            array (
                'id' => 362,
                'parent_id' => 359,
                'name' => '攀枝花市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            315 => 
            array (
                'id' => 363,
                'parent_id' => 359,
                'name' => '泸州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            316 => 
            array (
                'id' => 364,
                'parent_id' => 359,
                'name' => '德阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            317 => 
            array (
                'id' => 365,
                'parent_id' => 359,
                'name' => '绵阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            318 => 
            array (
                'id' => 366,
                'parent_id' => 359,
                'name' => '广元市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            319 => 
            array (
                'id' => 367,
                'parent_id' => 359,
                'name' => '遂宁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            320 => 
            array (
                'id' => 368,
                'parent_id' => 359,
                'name' => '内江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            321 => 
            array (
                'id' => 369,
                'parent_id' => 359,
                'name' => '乐山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            322 => 
            array (
                'id' => 370,
                'parent_id' => 359,
                'name' => '南充市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            323 => 
            array (
                'id' => 371,
                'parent_id' => 359,
                'name' => '眉山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            324 => 
            array (
                'id' => 372,
                'parent_id' => 359,
                'name' => '宜宾市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            325 => 
            array (
                'id' => 373,
                'parent_id' => 359,
                'name' => '广安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            326 => 
            array (
                'id' => 374,
                'parent_id' => 359,
                'name' => '达州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            327 => 
            array (
                'id' => 375,
                'parent_id' => 359,
                'name' => '雅安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            328 => 
            array (
                'id' => 376,
                'parent_id' => 359,
                'name' => '巴中市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            329 => 
            array (
                'id' => 377,
                'parent_id' => 359,
                'name' => '资阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            330 => 
            array (
                'id' => 378,
                'parent_id' => 359,
                'name' => '阿坝藏族羌族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            331 => 
            array (
                'id' => 379,
                'parent_id' => 359,
                'name' => '甘孜藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            332 => 
            array (
                'id' => 380,
                'parent_id' => 359,
                'name' => '凉山彝族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            333 => 
            array (
                'id' => 381,
                'parent_id' => 0,
                'name' => '贵州省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            334 => 
            array (
                'id' => 382,
                'parent_id' => 381,
                'name' => '贵阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            335 => 
            array (
                'id' => 383,
                'parent_id' => 381,
                'name' => '六盘水市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            336 => 
            array (
                'id' => 384,
                'parent_id' => 381,
                'name' => '遵义市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            337 => 
            array (
                'id' => 385,
                'parent_id' => 381,
                'name' => '安顺市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            338 => 
            array (
                'id' => 386,
                'parent_id' => 381,
                'name' => '毕节市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            339 => 
            array (
                'id' => 387,
                'parent_id' => 381,
                'name' => '铜仁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            340 => 
            array (
                'id' => 388,
                'parent_id' => 381,
                'name' => '黔西南布依族苗族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            341 => 
            array (
                'id' => 389,
                'parent_id' => 381,
                'name' => '黔东南苗族侗族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            342 => 
            array (
                'id' => 390,
                'parent_id' => 381,
                'name' => '黔南布依族苗族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            343 => 
            array (
                'id' => 391,
                'parent_id' => 0,
                'name' => '云南省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            344 => 
            array (
                'id' => 392,
                'parent_id' => 391,
                'name' => '昆明市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            345 => 
            array (
                'id' => 393,
                'parent_id' => 391,
                'name' => '曲靖市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            346 => 
            array (
                'id' => 394,
                'parent_id' => 391,
                'name' => '玉溪市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            347 => 
            array (
                'id' => 395,
                'parent_id' => 391,
                'name' => '保山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            348 => 
            array (
                'id' => 396,
                'parent_id' => 391,
                'name' => '昭通市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            349 => 
            array (
                'id' => 397,
                'parent_id' => 391,
                'name' => '丽江市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            350 => 
            array (
                'id' => 398,
                'parent_id' => 391,
                'name' => '普洱市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            351 => 
            array (
                'id' => 399,
                'parent_id' => 391,
                'name' => '临沧市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            352 => 
            array (
                'id' => 400,
                'parent_id' => 391,
                'name' => '楚雄彝族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            353 => 
            array (
                'id' => 401,
                'parent_id' => 391,
                'name' => '红河哈尼族彝族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            354 => 
            array (
                'id' => 402,
                'parent_id' => 391,
                'name' => '文山壮族苗族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            355 => 
            array (
                'id' => 403,
                'parent_id' => 391,
                'name' => '西双版纳傣族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            356 => 
            array (
                'id' => 404,
                'parent_id' => 391,
                'name' => '大理白族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            357 => 
            array (
                'id' => 405,
                'parent_id' => 391,
                'name' => '德宏傣族景颇族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            358 => 
            array (
                'id' => 406,
                'parent_id' => 391,
                'name' => '怒江傈僳族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            359 => 
            array (
                'id' => 407,
                'parent_id' => 391,
                'name' => '迪庆藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            360 => 
            array (
                'id' => 408,
                'parent_id' => 0,
                'name' => '西藏自治区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            361 => 
            array (
                'id' => 409,
                'parent_id' => 408,
                'name' => '拉萨市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            362 => 
            array (
                'id' => 410,
                'parent_id' => 408,
                'name' => '日喀则市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            363 => 
            array (
                'id' => 411,
                'parent_id' => 408,
                'name' => '昌都市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            364 => 
            array (
                'id' => 412,
                'parent_id' => 408,
                'name' => '林芝市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            365 => 
            array (
                'id' => 413,
                'parent_id' => 408,
                'name' => '山南市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            366 => 
            array (
                'id' => 414,
                'parent_id' => 408,
                'name' => '那曲市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            367 => 
            array (
                'id' => 415,
                'parent_id' => 408,
                'name' => '阿里地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            368 => 
            array (
                'id' => 416,
                'parent_id' => 0,
                'name' => '陕西省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            369 => 
            array (
                'id' => 417,
                'parent_id' => 416,
                'name' => '西安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            370 => 
            array (
                'id' => 418,
                'parent_id' => 416,
                'name' => '铜川市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            371 => 
            array (
                'id' => 419,
                'parent_id' => 416,
                'name' => '宝鸡市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            372 => 
            array (
                'id' => 420,
                'parent_id' => 416,
                'name' => '咸阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            373 => 
            array (
                'id' => 421,
                'parent_id' => 416,
                'name' => '渭南市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            374 => 
            array (
                'id' => 422,
                'parent_id' => 416,
                'name' => '延安市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            375 => 
            array (
                'id' => 423,
                'parent_id' => 416,
                'name' => '汉中市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            376 => 
            array (
                'id' => 424,
                'parent_id' => 416,
                'name' => '榆林市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            377 => 
            array (
                'id' => 425,
                'parent_id' => 416,
                'name' => '安康市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            378 => 
            array (
                'id' => 426,
                'parent_id' => 416,
                'name' => '商洛市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            379 => 
            array (
                'id' => 427,
                'parent_id' => 0,
                'name' => '甘肃省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            380 => 
            array (
                'id' => 428,
                'parent_id' => 427,
                'name' => '兰州市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            381 => 
            array (
                'id' => 429,
                'parent_id' => 427,
                'name' => '嘉峪关市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            382 => 
            array (
                'id' => 430,
                'parent_id' => 427,
                'name' => '金昌市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            383 => 
            array (
                'id' => 431,
                'parent_id' => 427,
                'name' => '白银市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            384 => 
            array (
                'id' => 432,
                'parent_id' => 427,
                'name' => '天水市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            385 => 
            array (
                'id' => 433,
                'parent_id' => 427,
                'name' => '武威市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            386 => 
            array (
                'id' => 434,
                'parent_id' => 427,
                'name' => '张掖市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            387 => 
            array (
                'id' => 435,
                'parent_id' => 427,
                'name' => '平凉市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            388 => 
            array (
                'id' => 436,
                'parent_id' => 427,
                'name' => '酒泉市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            389 => 
            array (
                'id' => 437,
                'parent_id' => 427,
                'name' => '庆阳市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            390 => 
            array (
                'id' => 438,
                'parent_id' => 427,
                'name' => '定西市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            391 => 
            array (
                'id' => 439,
                'parent_id' => 427,
                'name' => '陇南市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            392 => 
            array (
                'id' => 440,
                'parent_id' => 427,
                'name' => '临夏回族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            393 => 
            array (
                'id' => 441,
                'parent_id' => 427,
                'name' => '甘南藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            394 => 
            array (
                'id' => 442,
                'parent_id' => 0,
                'name' => '青海省',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            395 => 
            array (
                'id' => 443,
                'parent_id' => 442,
                'name' => '西宁市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            396 => 
            array (
                'id' => 444,
                'parent_id' => 442,
                'name' => '海东市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            397 => 
            array (
                'id' => 445,
                'parent_id' => 442,
                'name' => '海北藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            398 => 
            array (
                'id' => 446,
                'parent_id' => 442,
                'name' => '黄南藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            399 => 
            array (
                'id' => 447,
                'parent_id' => 442,
                'name' => '海南藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            400 => 
            array (
                'id' => 448,
                'parent_id' => 442,
                'name' => '果洛藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            401 => 
            array (
                'id' => 449,
                'parent_id' => 442,
                'name' => '玉树藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            402 => 
            array (
                'id' => 450,
                'parent_id' => 442,
                'name' => '海西蒙古族藏族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            403 => 
            array (
                'id' => 451,
                'parent_id' => 0,
                'name' => '宁夏回族自治区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            404 => 
            array (
                'id' => 452,
                'parent_id' => 451,
                'name' => '银川市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            405 => 
            array (
                'id' => 453,
                'parent_id' => 451,
                'name' => '石嘴山市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            406 => 
            array (
                'id' => 454,
                'parent_id' => 451,
                'name' => '吴忠市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            407 => 
            array (
                'id' => 455,
                'parent_id' => 451,
                'name' => '固原市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            408 => 
            array (
                'id' => 456,
                'parent_id' => 451,
                'name' => '中卫市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            409 => 
            array (
                'id' => 457,
                'parent_id' => 0,
                'name' => '新疆维吾尔自治区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            410 => 
            array (
                'id' => 458,
                'parent_id' => 457,
                'name' => '乌鲁木齐市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            411 => 
            array (
                'id' => 459,
                'parent_id' => 457,
                'name' => '克拉玛依市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            412 => 
            array (
                'id' => 460,
                'parent_id' => 457,
                'name' => '吐鲁番市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            413 => 
            array (
                'id' => 461,
                'parent_id' => 457,
                'name' => '哈密市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            414 => 
            array (
                'id' => 462,
                'parent_id' => 457,
                'name' => '昌吉回族自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            415 => 
            array (
                'id' => 463,
                'parent_id' => 457,
                'name' => '博尔塔拉蒙古自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            416 => 
            array (
                'id' => 464,
                'parent_id' => 457,
                'name' => '巴音郭楞蒙古自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            417 => 
            array (
                'id' => 465,
                'parent_id' => 457,
                'name' => '阿克苏地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            418 => 
            array (
                'id' => 466,
                'parent_id' => 457,
                'name' => '克孜勒苏柯尔克孜自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            419 => 
            array (
                'id' => 467,
                'parent_id' => 457,
                'name' => '喀什地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            420 => 
            array (
                'id' => 468,
                'parent_id' => 457,
                'name' => '和田地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            421 => 
            array (
                'id' => 469,
                'parent_id' => 457,
                'name' => '伊犁哈萨克自治州',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            422 => 
            array (
                'id' => 470,
                'parent_id' => 457,
                'name' => '塔城地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            423 => 
            array (
                'id' => 471,
                'parent_id' => 457,
                'name' => '阿勒泰地区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            424 => 
            array (
                'id' => 472,
                'parent_id' => 457,
                'name' => '石河子市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            425 => 
            array (
                'id' => 473,
                'parent_id' => 457,
                'name' => '阿拉尔市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            426 => 
            array (
                'id' => 474,
                'parent_id' => 457,
                'name' => '图木舒克市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            427 => 
            array (
                'id' => 475,
                'parent_id' => 457,
                'name' => '五家渠市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            428 => 
            array (
                'id' => 476,
                'parent_id' => 457,
                'name' => '铁门关市',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            429 => 
            array (
                'id' => 477,
                'parent_id' => 381,
                'name' => '贵安新区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            429 => 
            array (
                'id' => 478,
                'parent_id' => 35,
                'name' => '雄安新区',
                'status' => 1,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));
        
        
    }
}