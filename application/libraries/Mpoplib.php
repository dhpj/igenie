<?php
defined('BASEPATH') OR exit('No direct script access allowed');


class Mpoplib {

    private $tem_type = array(
        'mpop_type_a' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 0,
                'image' => 0
            ),
            'block' => array(
                'cnt' => 5,
                'title' => array(0,0,0,0,0),
                'goods_cnt' => array(2,3,5,5,5),
                'goods_price' => array(0,0,0,0,0),
                'goods_option_cnt' => array(0,0,0,0,0),
                'goods_badge' => 1,
            ),
            'type' => array(
                'name' => '기본형',
                'src' => '/images/mpop/layout_01.jpg'
            )
        ),
        'mpop_type_b' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 2,
                'image' => 2
            ),
            'block' => array(
                'cnt' => 2,
                'title' => array(1,1),
                'goods_cnt' => array(15,15),
                'goods_price' => array(0,0,0,0,0),
                'goods_option_cnt' => array(1,1),
                'goods_badge' => 1,
            ),
            'type' => array(
                'name' => '확장형',
                'src' => '/images/mpop/layout_02.jpg'
            )
        ),
        'mpop_type_c' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 2,
                'image' => 2
            ),
            'block' => array(
                'cnt' => 2,
                'title' => array(1,1),
                'goods_cnt' => array(9,7),
                'goods_price' => array(0,0,0,0,0),
                'goods_option_cnt' => array(1,1),
                'goods_badge' => 0,
            ),
            'type' => array(
                'name' => '확장형',
                'src' => '/images/mpop/layout_03.jpg'
            )
        ),
        'mpop_type_d' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 2,
                'image' => 2
            ),
            'block' => array(
                'cnt' => 8,
                'title' => array(1,0,0,0,1,0,1,1),
                'goods_cnt' => array(1,4,2,2,4,2,11,11),
                'goods_price' => array(0,0,0,0,0,0,0,0),
                'goods_option_cnt' => array(1,1,1,1,1,1,1,1),
                'goods_badge' => 1,
            ),
            'type' => array(
                'name' => '확장형',
                'src' => '/images/mpop/layout_04.jpg'
            )
        ),
        'mpop_type_e' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 0,
                'image' => 0
            ),
            'block' => array(
                'cnt' => 4,
                'title' => array(1,1,1,1),
                'goods_cnt' => array(9,9,9,9),
                'goods_price' => array(0,0,0,0),
                'goods_option_cnt' => array(1,1,1,1),
                'goods_badge' => 1,
            ),
            'type' => array(
                'name' => '확장형',
                'src' => '/images/mpop/layout_05.jpg'
            )
        ),
        'mpop_type_f' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 3,
                'image' => 0
            ),
            'block' => array(
                'cnt' => 3,
                'title' => array(0,0,0),
                'goods_cnt' => array(6,6,10),
                'goods_price' => array(0,0,0),
                'goods_option_cnt' => array(1,1,1),
                'goods_badge' => 1,
            ),
            'type' => array(
                'name' => '확장형',
                'src' => '/images/mpop/layout_06.jpg'
            )
        ),
        'mpop_type_g' => array(
            'title' => array(
                'useyn' => 1,
                'mart_name' => array(
                    'main' => 1,
                    'sub' => 1
                ),
                'option' => 2,
                'image' => 0
            ),
            'block' => array(
                'cnt' => 4,
                'title' => array(0,1,1,1),
                'goods_cnt' => array(4,6,9,9),
                'goods_price' => array(0,0,0,0),
                'goods_option_cnt' => array(1,1,1,1),
                'goods_badge' => 1,
            ),
            'type' => array(
                'name' => '확장형',
                'src' => '/images/mpop/layout_07.jpg'
            )
        ),
    );

    public function get_type(){
        return $this->tem_type;
    }

}
