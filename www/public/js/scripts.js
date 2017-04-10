var application = function ($) {
    return function () {
        // Vue component to print out recipe list
        Vue.component('recipe-item', {
            props: ['recipe'],
            template: '<div class="col-md-4">' +
                '<a :href="/getrecipe/ +  recipe.id"> ' +
                    '<h4>{{ recipe.name }}</h4>' +
                    '<p><strong>Cooking time: {{ recipe.time }}</strong></p>' +
                    '<p><strong>Ingredients:</strong></p>' +
                    '<ul>' +
                        '<li v-for="ingredient in recipe.ingredients" v-once>{{ ingredient.name }}</li>' +
                    '</ul>' +
                '</a>' +
            '</div>'
        });

        // Vue application which drives the recipe pages
        var app = new Vue({
            el: '#app',
            // binding data to vue application
            data:{
                items:[],
                limit: null,
                offset: null,
                total: null
            },
            created: function () {
                this.fetchData();
            },
            methods: {
                fetchData: function () {
                    // getting data from rest api endpoint
                    $.getJSON('/recipes', function (recipes) {
                        this.items = recipes['items'];
                        this.limit = recipes['limit'];
                        this.offset = recipes['offset'];
                        this.total = recipes['total'];
                    }.bind(this));
                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(application);
})(jQuery);