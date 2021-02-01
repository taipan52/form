<?php

namespace SampleForm;
    
/**
 * CFormValidator 
 *
 * Sample class PHP form validator
 *
 * @author  Taipan <beetle52net@gmail.com>
 * @since   2021-01-31
 *
 */

class CFormValidator {

    // RegExp шаблоны
    public static $regexp = array(
        'text'  => '/^[a-zA-ZА-Яа-яёЁ0-9\.,\-\+\(\) \\s]*$/u',
        'phone' => '/^[-+\(\)0-9]{16}$/u',
    );

    public static $err_text = array(
        'text'  => 'Возможно вы использовали опасные символы: например кавычки',
        'email' => 'Проверьте, корректно ли вы ввели e-mail',
        'phone' => 'Номер телефона может содержать только числа, знаки ( ) и +',
    );


    private $ar_validation,
            $ar_clean,
            $ar_err,
            $ar_correct,
            $ar_fields;

    public function __construct($arg_validation = array(), $arg_clean = array()) {

        $this->ar_validation = $arg_validation;
        $this->ar_clean = $arg_clean;

        $this->ar_err = array();
        $this->ar_correct = array();

    }


    /**
     * Валидация переданных полей
     *
     *
     * @param array $items
     *
     * @return boolean
     */
    public function validate($items) {

        foreach ($items as $key => $val) {
            if (strlen($val) == 0 || array_key_exists($key, $this->ar_validation) === false) {

                $this->ar_correct[] = $key;
                continue;
            }

            $result = self::validateItem($val, $this->ar_validation[$key]);

            if ($result === false) {
                $this->addError($key, $this->ar_validation[$key]);
            }
            else {
                $this->ar_correct[] = $key;
                $this->ar_fields[$key] = $val;
                
            }
        }

        return empty($this->ar_err);
    }


    /**
     * Формирование ответа на отправку формы
     *
     *
     * @return string
     */
    public function getRes() {

        $res['send'] = true;

        //corrects
        if (!empty($this->ar_correct)) {

            foreach ($this->ar_correct as $val) {
                $res['corrects'][] = array(
                    'code' => $val,
                    'value' => $this->ar_fields[$val],
                );
            }
            $res['errors'] = false;

        }

        //errors
        if (!empty($this->ar_err)) {

            foreach ($this->ar_err as $key => $val) {
                $res['errors'][] = array(
                    'code' => $key,
                    'text' => self::$err_text[$val],
                );
            }


        }

        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }

    /**
     * Зачистка полей по $this->ar_clean
     *
     *
     * @param array $items
     *
     * @return clean array $items
     */
    public function cleaning($items)
    {
        foreach ($items as $key => $val) {

            if (array_search($key, $this->ar_clean) === false && !array_key_exists($key, $this->ar_clean)) {

                continue;
            }

            $items[$key] = self::cleanItem($val, $this->ar_validation[$key]);
        }

        return $items;
    }

    /**
     * Добавление ошибки в массив $this->ar_err
     *
     *
     * @param string $field
     * @param string $type
     */
    private function addError($field, $type = 'string') {

        $this->ar_err[$field] = $type;
    }

    /**
     * Зачистка поля по типу
     *
     *
     * @param string $var
     * @param string $type
     *
     * @return string $output
     */
    public static function cleanItem($var, $type) {

        $flags = null;

        switch ($type) {

            case 'url':
                $filter = FILTER_SANITIZE_URL;
            break;

            case 'int':
                $filter = FILTER_SANITIZE_NUMBER_INT;
            break;

            case 'float':
                $filter = FILTER_SANITIZE_NUMBER_FLOAT;
                $flags = FILTER_FLAG_ALLOW_FRACTION | FILTER_FLAG_ALLOW_THOUSAND;
            break;

            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_SANITIZE_EMAIL;
            break;

            case 'string':
            default:
                $filter = FILTER_SANITIZE_STRING;
                $flags = FILTER_FLAG_NO_ENCODE_QUOTES;
            break;

        }

        $var = trim(strval($var));
        $output = filter_var($var, $filter, $flags);
        

        return $output;
    }

    /**
     * Валидация поля по типу
     *
     *
     * @param string $var
     * @param string $type
     *
     * @return boolean
     */
    public static function validateItem($var, $type) {

        //validate by regexp
        if (array_key_exists($type, self::$regexp)) {

            $returnval = filter_var(
                $var, FILTER_VALIDATE_REGEXP, 
                array(
                    'options' => array(
                        'regexp' => self::$regexp[$type]
                    )
                )
            ) !== false;

            return $returnval;

        }

        $filter = false;

        switch ($type) {

            case 'email':
                $var = substr($var, 0, 254);
                $filter = FILTER_VALIDATE_EMAIL;
            break;

            case 'int':
                $filter = FILTER_VALIDATE_INT;
            break;

            case 'boolean':
                $filter = FILTER_VALIDATE_BOOLEAN;
            break;

            case 'ip':
                $filter = FILTER_VALIDATE_IP;
            break;

            case 'url':
                $filter = FILTER_VALIDATE_URL;
            break;
        } 

        return ($filter === false) ? false : filter_var($var, $filter) !== false ? true : false;
    }

}