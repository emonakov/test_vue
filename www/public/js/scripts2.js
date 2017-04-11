var application = function ($) {
    return function () {
        // Vue application which drives the recipe pages
        var app = new Vue({
            el: '#app',
            // binding data to vue application
            data:{
                item: null
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
                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(application);
})(jQuery);