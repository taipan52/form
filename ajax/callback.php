<?
require_once '../form/CFormValidator.php';

//типы валидации
$ar_validation = array(
	'region'      => 'text',
	'department'  => 'text',
	'phone'       => 'phone',
);
//список полей для зачистки
$ar_clean = array('region', 'department', 'phone');

$validator = new SampleForm\CFormValidator($ar_validation, $ar_clean);

//валидация пройдена
if($validator->validate($_POST)) {
 	
 	$_POST = $validator->cleaning($_POST);
	echo $validator->getRes();

}
//поля не валидны
else {

	echo $validator->getRes();

}
