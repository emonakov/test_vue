<?php

namespace Bbc\Features\Model;

class Recipe implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $_data;

    /**
     * Recipe constructor.
     *
     * @param array $data
     */
    public function __construct(array $data = [])
    {
        $this->setData($data);
    }

    /**
     * Set data to object
     *
     * @param array $data
     * @return $this
     */
    public function setData(array $data)
    {
        foreach ($data as $key => $datum) {
            $this->_data[$key] = $datum;
        }
        return $this;
    }

    /**
     * Get data from object
     *
     * @param null $key
     * @return array|mixed|null
     */
    public function getData($key = null) {
        if (!$key) {
            return $this->_data;
        }
        return (isset($this->_data[$key])) ? $this->_data[$key] : null;
    }

    /**
     * returns recipe id
     *
     * @return int
     */
    public function id()
    {
        return $this->_data['id'];
    }

    /**
     * returns images
     *
     * @return array
     */
    public function getGallery()
    {
        return $this->_data['gallery'];
    }

    /**
     * returns ingredients
     *
     * @return array
     */
    public function getIngredients()
    {
        return $this->_data['ingredients'];
    }

    /**
     * Serialize data to json
     *
     * @return array
     */
    function jsonSerialize()
    {
        return $this->_data;
    }
}