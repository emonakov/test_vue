<?php

namespace Bbc\Features\Model;

class Recipe implements \JsonSerializable
{
    /**
     * @var array
     */
    protected $_data;

    /**
     * @var \Bbc\Features\Recipe
     */
    protected $repository;

    /**
     * Recipe constructor.
     *
     * @param array $data
     */
    public function __construct(\Bbc\Features\Recipe $recipeRepository, array $data = [])
    {
        $this->repository = $recipeRepository;
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
        if (!isset($this->_data['gallery'])) {
            $this->repository->addGalleryData($this);
        }
        return $this->_data['gallery'];
    }

    /**
     * returns ingredients
     *
     * @return array
     */
    public function getIngredients()
    {
        if (!isset($this->_data['ingredients'])) {
            $this->repository->addIngredientsData($this);
        }
        return $this->_data['ingredients'];
    }

    /**
     * Serialize data to json
     *
     * @return array
     */
    function jsonSerialize()
    {
        $this->getGallery();
        $this->getIngredients();
        return $this->_data;
    }
}