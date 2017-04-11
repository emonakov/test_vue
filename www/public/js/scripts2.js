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
            created: function () {
                this.fetchData();
            },
            methods: {
                fetchData: function () {
                    // getting data from rest api endpoint
                    $.getJSON('/recipe/' + recipeId, this.renderResponse.bind(this));
                },
                // render response by setting data to bound
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