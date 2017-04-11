var application = function ($) {
    return function () {
        // Vue application which drives the recipe pages
        var app = new Vue({
            el: '#app',
            // binding data to vue application
            data:{
                item: null,
                image: null,
                ingredients: null,
                gallery: null
            },
            // initial request to get data
            created: function () {
                this.fetchData();
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
                    if (!this.item.gallery[0]) {
                        this.image = 'media/recipe/default.png';
                    } else {
                        this.image = this.item.gallery[0].image;
                    }
                    this.gallery = recipe.gallery;
                    this.ingredients = recipe.ingredients;
                },
                // changing big image src
                changeSrc: function (src) {
                    this.image = src;
                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(application);
})(jQuery);