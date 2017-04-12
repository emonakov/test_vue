var application = function ($) {
    return function () {
        // Vue application which drives the recipe pages
        var app = new Vue({
            el: '#app',
            // binding data to vue application
            data:{
                // recipe
                item: null,
                // its main image
                image: null,
                ingredients: null,
                gallery: null,
                // list of starred recipes
                stars: [],
                // if the recipe is starred
                starred: false,
                // show if no starred
                starOff: 'media/recipe/no_star.png',
                // show if starred
                starOn: 'media/recipe/star.png'
            },
            // initial request to get data
            created: function () {
                // fetch initial page data
                this.fetchData();
                // fetch stars data
                this.getStarred();
            },
            methods: {
                // fetch recipe data
                fetchData: function () {
                    // getting data from rest api endpoint
                    $.getJSON('/recipe/' + recipeId, this.renderResponse.bind(this));
                },
                // render response by setting data to bound parameters
                renderResponse: function (recipe) {
                    this.item = recipe;
                    // if no gallery images set default image
                    if (!this.item.gallery[0]) {
                        this.image = 'media/recipe/default.png';
                    } else {
                        this.image = this.item.gallery[0].image;
                    }
                    this.gallery = recipe.gallery;
                    this.ingredients = recipe.ingredients;
                },
                // changing big image src by clicking small thumbs
                changeSrc: function (src) {
                    this.image = src;
                },
                // renders stars response to the page
                renderStars: function (response) {
                    // if no errors
                    if (!response.error) {
                        // set stars
                        this.stars = response.stars;
                        // and check if current recipe is starred
                        this.starred = this.stars.indexOf(recipeId) > -1;
                    }
                },
                // get starred list
                getStarred: function () {
                    $.getJSON('/stars', this.renderStars.bind(this));
                },
                deleteStar: function () {

                },
                addStar: function () {
                    var request = $.post('/stars', {
                        recipe_id: recipeId
                    }, 'json');
                    request.done(this.renderStars.bind(this));
                },
                deleteStar: function () {
                    var request = $.ajax({
                        url: '/stars/' + recipeId,
                        type: 'DELETE',
                        contentType: 'json'
                    });
                    request.done(this.renderStars.bind(this));
                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(application);
})(jQuery);