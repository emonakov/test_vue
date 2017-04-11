<?php
// Main recipes endpoint
$app->get('/recipes[/]', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    /** @var \Slim\Container $this */
    /** @var \Bbc\Features\SearchParamsProcessor $paramsProcessor */
    $paramsProcessor = $this->get('params_processor');
    // processing query params
    $params = $paramsProcessor->processParams($request->getParams());
    /** @var \Bbc\Features\Recipe $recipeRepository */
    $recipeRepository = $this->get('recipe');
    // passing query params to filter method in repository class
    $recipeRepository->addFilter($params);
    $recipes = $recipeRepository->getAllItems();
    $total = $recipeRepository->getTotal();
    return $response->withJson([
        'items' => $recipes,
        'limit' => $recipeRepository->getLimit(),
        'offset' => $recipeRepository->getOffset(),
        'total' => $total,
        // imitating current logged in user Joe
        'user' => 1
    ]);
});

// Endpoint for single recipe
$app->get('/recipe/{id}', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    /** @var \Slim\Container $this */
    /** @var \Bbc\Features\Recipe $recipeRepository */
    $recipeId = (int)$args['id'];
    $recipeRepository = $this->get('recipe');
    $recipe = $recipeRepository->load($recipeId);
    return $response->withJson($recipe);
});

// Endpoint for recipe list
$app->get('/', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    return $this->renderer->render($response, 'index.phtml', $args);
});

// Endpoint for recipe single item
$app->get('/getrecipe/{id}', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    return $this->renderer->render($response, 'recipe.phtml', $args);
});