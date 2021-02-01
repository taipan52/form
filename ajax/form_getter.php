<?
require $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/main/include/prolog_before.php';
require_once '../form/CFormGetter.php';

if($_REQUEST['action'] == 'init') {

	$ar_list = SampleForm\CFormGetter::getList(4);

	$ar_res = array(
		'success' => true,
		'list' => $ar_list,
	);

	echo json_encode($ar_res);

}

if($_REQUEST['action'] == 'change' && isset($_REQUEST['region_id']) ) {


	$ar_list = SampleForm\CFormGetter::getList(5, $_REQUEST['region_id']);

	$ar_res = array(
		'success' => true,
		'list' => $ar_list,
	);	

	echo json_encode($ar_res);

}