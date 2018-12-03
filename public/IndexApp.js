var IndexApp = new Vue({
    el: '#IndexApp',
    data: {
        dogs: {},
        images: {},
        amount: 21,
        currentBreed: ''
    },
    created: function () {
        //Load a list of dog breeds
        $.get('api/breeds.php', (data) => {
            this.dogs = data;
        });
    },
    methods: {
        /*
         * @Description:
         *      Loads an x amount of a certain breed from the api wrapper
         * 
         * @Params:
         *      breed (string): 
         *          The breed to be fetched
         */
        getBreed: function (breed) {
            //Reset this.amount in case the 'breed' variable is a new breed
            if (breed != this.currentBreed) {
                this.amount = 21;
            }

            $.get(`api/breeds.php?breed=${breed}&amount=${this.amount}`, (data) => {
                this.images = [];

                //Sort data into rows of 3
                dogArray = [];
                for (i = 0; i < data.length; i += 3) {
                    dogArray.push(data.slice(i, i + 3));
                }

                document.title = breed;
                this.images = dogArray;
                this.currentBreed = breed;
            });
        },
        /*
         * @Description:
         *      Loads an additional 21 images
         */
        loadMore: function() {
            this.amount += 21;

            this.getBreed(this.currentBreed);
        }
    }
});