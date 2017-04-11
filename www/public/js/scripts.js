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
                        '<li v-for="ingredient in recipe.ingredients">{{ ingredient.name }}</li>' +
                    '</ul>' +
                '</a>' +
            '</div>'
        });

        // Vue component to print out recipe time option list
        Vue.component('recipe-time-option', {
            props: ['recipe'],
            template: '<option :value="recipe.time">{{ recipe.time }} minutes</option>'
        });

        // Vue application which drives the recipe pages
        var app = new Vue({
            el: '#app',
            // binding data to vue application
            data:{
                items:[],
                limit: null,
                offset: null,
                total: null,
                filter: {
                    main: null,
                    ingredient: null,
                    time: null
                }
            },
            created: function () {
                this.fetchData();
            },
            methods: {
                fetchData: function () {
                    // getting data from rest api endpoint
                    $.getJSON('/recipes', this.renderResponse.bind(this));
                },
                filterData: function () {
                    // filter request
                    var qs;
                    var recipeName = this.filter.main;
                    var ingredientName = this.filter.ingredient;
                    var time = this.filter.time;
                    var recipeQuery = (recipeName) ? 'main_table[field]=name&main_table[value]=' + encodeURIComponent('%' + recipeName + '%') +  '&main_table[op]=like' : null;
                    var ingredientQuery = (ingredientName) ? 'ingridient_table[field]=name&ingridient_table[value]=' + encodeURIComponent('%' + ingredientName + '%') +  '&ingridient_table[op]=like' : null;
                    var timeQuery = (time) ? 'main_table[field]=time&main_table[value]=' + time : null;
                    if (recipeQuery) {
                        qs = recipeQuery;
                        if (ingredientQuery) {
                            qs += '&' + ingredientQuery;
                        }
                        if (timeQuery) {
                            qs += '&' + timeQuery;
                        }
                    } else if (ingredientQuery) {
                        qs = ingredientQuery;
                        if (timeQuery) {
                            qs += '&' + timeQuery;
                        }
                    } else if (timeQuery) {
                        qs = timeQuery;
                    }
                    $.getJSON('/recipes?'+qs, this.renderResponse.bind(this));
                },
                renderResponse: function (recipes) {
                    this.items = recipes['items'];
                    this.limit = recipes['limit'];
                    this.offset = recipes['offset'];
                    this.total = recipes['total'];
                },
                buildTimeFilterData: function () {
                    var filterData = [];
                    for (var key in this.items) {
                        if (this.items.hasOwnProperty(key)) {
                            if (filterData.indexOf(this.items[key].time) < 0) {
                                filterData.push(this.items[key].time);
                            }
                        }
                    }
                    return filterData;
                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(application);
})(jQuery);