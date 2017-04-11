var starsApplication = function ($) {
    return function () {
        // Vue application which drives the recipe pages
        var starsApp = new Vue({
            el: '#stars',
            // binding data to vue application
            data:{

            },
            // initial request to get data
            created: function () {
                this.fetchData();
            },
            methods: {
                // fetch recipe data
                fetchData: function () {
                    // getting data from rest api endpoint
                    $.getJSON('/stars/' + userId, this.renderResponse.bind(this));
                },
                // render response by setting data to bound parameters
                renderResponse: function (stars) {

                }
            }
        });
    };
}(jQuery);

(function ($) {
    $(starsApplication);
})(jQuery);