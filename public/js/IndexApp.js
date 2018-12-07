var IndexApp = new Vue({
    el: '#IndexApp',
    data: {
        dogs: {}, //JSON object containing dog breeds and sub-breeds
        images: {}, //Image URLs for the chosen breed
        imageRowLength: 3,
        columnSize: 4,
        amount: 21, //Specifies the amount of dogs to load at a time
        current: {
            breed: '', //Name of the current chosen breed
            length: 0            
        },
        randomChecked: false,
        rowsizeOptions: [2, 3, 4]
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
            //Check if the 'breed' variable is a new breed
            if (breed != this.current.breed) {
                this.amount = 21;

                //Load the number for the amount of images available for the currently chosen breed
                $.get(`api/count.php?breed=${breed}`, (data) => {
                    this.current.length = data;
                });

                //Jump to the top of the page
                document.body.scrollTop = 0;
                document.documentElement.scrollTop = 0;
            }

            var amountQueryString = 'amount';

            if (this.randomChecked) {
                amountQueryString = 'random';
            }

            $.get(`api/breeds.php?breed=${breed}&${amountQueryString}=${this.amount}`, (data) => {
                this.images = [];

                //Sort data into rows of 3
                dogArray = [];
                for (i = 0; i < data.length; i += this.imageRowLength) {
                    dogArray.push(data.slice(i, i + this.imageRowLength));
                }

                document.title = breed;
                this.images = dogArray;
                this.current.breed = breed;
            });
        },
        /*
         * @Description:
         *      Loads an additional 21 images
         */
        loadMore: function() {
            this.amount += (this.imageRowLength == 3 ? 21 : 20);;

            this.getBreed(this.current.breed);
        },
        /*
         * @Description:
         *      Change how many images there are in an image row
         * 
         * @Params:
         *      amount (int):
         *          The new amount of images in a row
         */
        changeRowsize: function (amount) {
            //Make sure a breed has been chosen
            if (this.current.breed != '') {
                this.imageRowLength = amount;
                this.columnSize = 12 / amount;
                
                this.amount = (amount == 3 ? 21 : 20);
                
                this.getBreed(this.current.breed);
            }
        }
    }
});