<?php
// Routes
$app->get('/recipes', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    /** @var \Slim\Container $this */
    /** @var \Bbc\Features\Recipe $recipeRepository */
    $recipeRepository = $this->get('recipe');
    $recipes = $recipeRepository->getAllItems();
    return $response->withJson($recipes);
});
