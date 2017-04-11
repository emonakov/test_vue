var application = function ($) {
    return function () {
        // Vue component to print out recipe list
        Vue.component('recipe-item', {
            props: ['recipe'],
            template: '<div class="col-md-4 recipe">' +
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

        // Vue component to print out recipe list
        Vue.component('recipe-paginator', {
            props: ['page'],
            template: '<li>' +
                '<a href="" v-on:click.prevent="setPage(page)"> ' +
                    '{{ page + 1 }}' +
                '</a>' +
            '</li>',
            methods: {
                setPage: function (page) {
                    this.$root.setPage(page);
                }
            }
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
                },
                times: null,
                filterQs: null,
                pages: [],
                stars: [],
                // flag to show or not message about empty stars
                starsFilter: false,
                // flag to filter by stars
                filterStars: false
            },
            created: function () {
                this.fetchData();
            },
            methods: {
                fetchData: function () {
                    // getting data from rest api endpoint
                    $.getJSON('/recipes', this.renderResponse.bind(this));
                    this.fetchTimeData();
                },
                // ideally this should be some query string builder
                filterData: function () {
                    this.starsFilter = false;
                    // filter request
                    var qs = null;
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
                    if (this.limit) {
                        qs = (qs) ? qs + '&limit=' + this.limit : 'limit=' + this.limit;
                    }
                    if (this.offset) {
                        qs = (qs) ? qs + '&offset=' + this.offset : 'offset=' + this.offset;
                    }
                    if (this.filterStars) {
                        qs = 'main_table[field]=id&main_table[value]=' + encodeURIComponent(this.stars.join(',')) +  '&main_table[op]=in';
                        this.starsFilter = true;
                        this.filterStars = false;
                    }
                    this.filterQs = qs;
                    $.getJSON('/recipes?'+this.filterQs, this.renderResponse.bind(this));
                },
                // render response by setting data to bound
                renderResponse: function (recipes) {
                    this.items = recipes['items'];
                    this.limit = recipes['limit'];
                    this.offset = recipes['offset'];
                    this.total = recipes['total'];
                    this.pages = [];
                    var lastPage = Math.ceil(this.total/this.limit);
                    for (var i=0; i<lastPage; i++) {
                        this.pages.push(i);
                    }
                },
                // fetching data for time filter purposes/shouldn't request same endpoint on production env
                fetchTimeData: function () {
                    $.getJSON('/recipes?limit=0', function (recipes) {
                        this.times = recipes['items'];
                    }.bind(this));
                },
                // buidling time filter options
                buildTimeFilterData: function () {
                    var filterData = [];
                    for (var key in this.times) {
                        if (this.times.hasOwnProperty(key)) {
                            if (filterData.indexOf(this.times[key].time) < 0) {
                                filterData.push(this.times[key].time);
                            }
                        }
                    }
                    return filterData;
                },
                // setting page
                setPage: function (page) {
                    this.offset = Math.ceil(page*this.limit);
                    this.filterData();
                },
                // get starred list
                getStarred: function () {
                    var promise = $.getJSON('/stars');
                    promise.done(function (response) {
                        if (!response.error) {
                            this.stars = response.stars;
                            this.filterStars = true;
                            this.filterData();
                        }
                    }.bind(this));
                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(application);
})(jQuery);