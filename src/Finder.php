<?php

namespace Name;

class Finder
{
    const areas = array (
        1 => '5-й поселок',
        2 => 'Голиковка',
        3 => 'Древлянка',
        4 => 'Заводская',
        5 => 'Зарека',
        6 => 'Ключевая',
        7 => 'Кукковка',
        8 => 'Новый сайнаволок',
        9 => 'Октябрьский',
        10 => 'Первомайский',
        11 => 'Перевалка',
        12 => 'Сулажгора',
        13 => 'Университетский городок',
        14 => 'Центр',
    );

    const nearby = array (
        1 => array(12,11),
        2 => array(5,7,6,8),
        3 => array(11,13),
        4 => array(10,9,12),
        5 => array(2,6,7,8),
        6 => array(5,2,7,8),
        7 => array(2,5,6,8),
        8 => array(6,2,7,5),
        9 => array(10,14),
        10 => array(9,14,12),
        11 => array(13,3,1,12),
        12 => array(1,10),
        13 => array(11,1,12),
        14 => array(9,10),
    );

    const workers = array (
        0 => array (
                'login' => 'login1',
                'area_name' => 'Октябрьский',
        ),
        1 => array (
                'login' => 'login2',
                'area_name' => 'Зарека',
        ),
        2 => array (
                'login' => 'login3',
                'area_name' => 'Сулажгора',
        ),
        3 => array (
                'login' => 'login4',
                'area_name' => 'Древлянка',
        ),
        4 => array (
                'login' => 'login5',
                'area_name' => 'Центр',
        ),
    );

    public function find($district)
    {
        
        $experts = array_filter(SELF::workers, function ($worker) use ($district) {
            return $worker['area_name'] == $district['name'];
        });
        $mapped = array_map(function ($value) {
            return $value['login'];
        },$experts);

        if (empty($mapped)) {
            $key_contiguous_district = array_search($district['name'], SELF::areas);
            $keys_contiguous = SELF::nearby[$key_contiguous_district];

            $contiguous_districts = array_filter(SELF::areas, function ($key) use ($keys_contiguous) {
                return in_array($key, $keys_contiguous);
            }, ARRAY_FILTER_USE_KEY);

            $experts = array_filter(SELF::workers, function ($worker) use ($contiguous_districts) {
                return in_array($worker['area_name'], $contiguous_districts);
            });
            $mapped = array_map(function ($value) {
                return $value['login'];
            },$experts);
        }
        return ['str' => $district['name'], 'result' => implode(',', $mapped)];
    }
}