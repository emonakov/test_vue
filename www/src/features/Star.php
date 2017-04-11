<?php

namespace Bbc\Features;

class Star
{
    /**
     * @var \PDO
     */
    protected $db;

    /**
     * Star constructor.
     *
     * @param \PDO $db
     */
    public function __construct(\PDO $db)
    {
        $this->db = $db;
    }

    /**
     * Adds star for a user
     *
     * @param $recipeId
     * @param $userId
     * @return array
     */
    public function addStar($recipeId, $userId)
    {
        $query = "INSERT INTO user_star_recipe VALUES (:recipe_id, :user_id)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':recipe_id', $recipeId);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        return $this->getStars($userId);
    }

    /**
     * Returns list of starred recipes of the user
     *
     * @param $userId
     * @return array
     */
    public function getStars($userId)
    {
        $query = "SELECT recipe_id FROM user_star_recipe WHERE user_id = :user_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->execute();
        $data = $stmt->fetchAll();
        return (array) $data;
    }

    /**
     * Deletes star for a given user
     *
     * @param $recipeId
     * @param $userId
     * @return array
     */
    public function deleteStar($recipeId, $userId)
    {
        $query = "DELETE FROM user_star_recipe WHERE user_id = :user_id AND recipe_id = :recipe_id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':user_id', $userId);
        $stmt->bindParam(':recipe_id', $recipeId);
        $stmt->execute();
        return $this->getStars($userId);
    }
}