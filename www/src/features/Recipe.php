<?php

namespace Bbc\Features;

class Recipe
{
    /**
     * @var \Bbc\Features\Model\Recipe[]
     */
    protected $items;

    /**
     * @var string
     */
    protected $query;

    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Query params
     *
     * @var array
     */
    protected $params;

    public function __construct(\PDO $db)
    {
        $this->db = $db;
        $this->query = 'SELECT main_table.* FROM recipe as main_table';
    }

    /**
     * Loads all recipes
     *
     * @return Model\Recipe[]
     */
    public function getAllItems()
    {
        $stmt = $this->prepareSqlStatement($this->query, $this->params);
        $items = $stmt->fetchAll();
        foreach ($items as $item) {
            $this->items[$item['id']] = new Model\Recipe($item);
            $this->addGalleryData($this->items[$item['id']]);
            $this->addIngredientsData($this->items[$item['id']]);
        }
        return $this->items;
    }

    public function addFilter(array $filter = [])
    {
        $filters = [];
        foreach ($filter as $key => $item) {
            if (is_array($item)) {

            } else {
                $filters[] = ":$key";
            }
        }
    }

    /**
     * Loads recipe by id
     *
     * @param $id
     * @return Model\Recipe
     */
    public function load($id)
    {
        if (!isset($this->items[$id])) {
            $q = 'SELECT main_table.* FROM recipe as main_table WHERE id=:id';
            $stmt = $stmt = $this->prepareSqlStatement($q, [':id' => $id]);
            $item = $stmt->fetch();
            $this->items[$id] = new Model\Recipe($item);
            $this->addGalleryData($this->items[$id]);
            $this->addIngredientsData($this->items[$id]);
        }
        return $this->items[$id];
    }

    /**
     * Adds gallery data to recipe object
     *
     * @param Model\Recipe $recipe
     * @return $this
     */
    public function addGalleryData(Model\Recipe $recipe)
    {
        $recipeId = $recipe->getData('id');
        $q = 'SELECT gallery_table.image FROM gallery as gallery_table WHERE recipe_id = :recipe_id';
        $stmt = $this->prepareSqlStatement($q, [':recipe_id' => $recipeId]);
        $gallery = $stmt->fetchAll();
        $recipe->setData(['gallery' => $gallery]);
        return $this;
    }

    /**
     * Adds ingredients data to recipe object
     *
     * @param Model\Recipe $recipe
     * @return $this
     */
    public function addIngredientsData(Model\Recipe $recipe)
    {
        $recipeId = $recipe->getData('id');
        $q = 'SELECT ingridient_table.name, ingridient_table.qty, ingridient_table.qty_type  FROM ingridient as ingridient_table WHERE recipe_id = :recipe_id';
        $stmt = $this->prepareSqlStatement($q, [':recipe_id' => $recipeId]);
        $gallery = $stmt->fetchAll();
        $recipe->setData(['ingredients' => $gallery]);
        return $this;
    }

    /**
     * Prepares query for fetching data
     *
     * @param $query
     * @param array $params
     * @return \PDOStatement
     */
    protected function prepareSqlStatement($query, $params = [])
    {
        $stmt = $this->db->prepare($query);
        foreach ($params as $key=>$param) {
            $stmt->bindParam($key, $param);
        }
        $stmt->execute();
        return $stmt;
    }
}