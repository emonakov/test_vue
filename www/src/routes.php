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
    $error = false;
    try {
        $recipes = $recipeRepository->getAllItems();
    } catch (\Exception $e) {
        $recipes = [];
        $error = $e->getMessage();
    }
    $total = $recipeRepository->getTotal();
    return $response->withJson([
        'items' => $recipes,
        'limit' => $recipeRepository->getLimit(),
        'offset' => $recipeRepository->getOffset(),
        'total' => $total,
        // imitating current logged in user Joe
        'user' => 1,
        'error' => $error
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

// Endpoint for stars list
$app->get('/stars', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $userId = 1;
    /** @var \Bbc\Features\Star $star */
    $star = $this->get('star');
    $data = [];
    try {
        $data['stars'] = $star->getStars($userId);
    } catch (\Exception $e) {
        $data['error'] = true;
        $data['message'] = $e->getMessage();
    }
    return $response->withJson($data);
});

// Endpoint for adding stars
$app->post('/stars', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $userId = 1;
    $params = $request->getParams();
    /** @var \Bbc\Features\Star $star */
    $star = $this->get('star');
    $data = [];
    try {
        $data['stars'] = $star->addStar($params['recipe_id'], $userId);
    } catch (\Exception $e) {
        $data['error'] = true;
        $data['message'] = $e->getMessage();
    }
    return $response->withJson($data);
});

// Endpoint for deleting stars
$app->delete('/stars', function (\Slim\Http\Request $request, \Slim\Http\Response $response, $args) {
    $userId = 1;
    $data = [];
    $params = $request->getParams();
    /** @var \Bbc\Features\Star $star */
    $star = $this->get('star');
    try {
        $data['stars'] = $star->deleteStar($params['recipe_id'], $userId);
    } catch (\Exception $e) {
        $data['error'] = true;
        $data['message'] = $e->getMessage();
    }
    return $response->withJson($data);
});