<?php
// Main recipes endpoint
$app->get('/recipes[/]', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    /** @var \Slim\Container $this */
    /** @var \Bbc\Features\SearchParamsProcessor $paramsProcessor */
    $paramsProcessor = $this->get('params_processor');
    $params = $paramsProcessor->processParams($request->getParams());
    /** @var \Bbc\Features\Recipe $recipeRepository */
    $recipeRepository = $this->get('recipe');
    $recipeRepository->addFilter($params);
    $recipes = $recipeRepository->getAllItems();
    $total = $recipeRepository->getTotal();
    return $response->withJson([
        'items' => $recipes,
        'limit' => $recipeRepository->getLimit(),
        'offset' => $recipeRepository->getOffset(),
        'total' => $total,
        'returned' => $recipeRepository->getItemsReturned()
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