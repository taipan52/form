<?php

namespace SampleForm;

use Bitrix\Main\Loader;
Loader::includeModule('iblock');
 
/**
 * CFormGetter 
 *
 *
 * @author  Taipan <beetle52net@gmail.com>
 * @since   2021-01-31
 *
 */

class CFormGetter {


    /**
     * Получение значений для списка из Битрикс
     *
     *
     * @param array $iblock_id
     * @param array $region_id
     *
     * @return array
     */
    public static function getList($iblock_id, $region_id = false) {

    	$arSelect = Array('ID', 'IBLOCK_ID', 'NAME');
		$arFilter = Array(
			'IBLOCK_ID' => $iblock_id, 
			'ACTIVE'=>'Y', 
		);

		if($region_id) $arFilter['PROPERTY_REGS'] = $region_id;

		$res = \CIBlockElement::GetList(Array('NAME' => 'ASC'), $arFilter, false, false, $arSelect);
		while($arItem = $res->Fetch()){
			$ar_list[] = array(
				'id' => $arItem['ID'],
				'name' => $arItem['NAME'],
			);
		}

        return $ar_list;
    }

}