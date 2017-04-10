<?php

namespace Bbc\Features;

/**
 * Class SearchParamsProcessor
 *
 * @package Bbc\Features
 */
class SearchParamsProcessor
{
    /**
     * List of valid tables
     *
     * @var array
     */
    protected $validTables = ['main_table', 'ingridient_table'];

    /**
     * List of valid param names
     *
     * @var array
     */
    protected $validParamNames = ['field', 'value', 'op'];

    /**
     * Converts and validates query params
     *
     * @param $params
     * @return array
     */
    public function processParams($params)
    {
        $data = [];
        if ($this->validParams($params)) {
            foreach ($params as $key=>$param) {
                $fields = explode(',', $param['field']);
                $values = explode(',', $param['value']);
                $ops = isset($param['op']) ? explode(',', $param['op']) : ['='];
                foreach ($fields as $index => $field) {
                    $data[$key]['field'][$index] = $field;
                    $data[$key]['value'][$index] = $values[$index];
                    $data[$key]['op'][$index] = (isset($ops[$index])) ? $ops[$index] : '=';
                }
            }
        }
        return $data;
    }

    /**
     * Validate params against valid params list and valid tables list
     *
     * @param $params
     * @return bool
     */
    protected function validParams($params) {
        foreach ($params as $key=>$param) {
            if (!in_array($key, $this->validTables)) {
                return false;
            }
            foreach ($param as $paramKey => $value) {
                if (!in_array($paramKey, $this->validParamNames)) {
                    return false;
                }
            }
        }
        return true;
    }

}