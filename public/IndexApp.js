var IndexApp = new Vue({
    el: '#IndexApp',
    data: {
        dogs: {},
        images: {},
        amount: 20
    },
    created: function () {
        $.get('api/dogs/breeds.php', (data) => {
            this.dogs = data;
        });
    },
    methods: {
        getBreed: function (breed) {
            $.get(`api/dogs/breeds.php?breed=${breed}&amount=${this.amount}`, (data) => {
                this.images = [];

                //Sort data into rows of 3
                dogArray = [];
                for (i = 0; i < data.length; i += 3) {
                    dogArray.push(data.slice(i, i+3));
                }

                this.images = dogArray;
                document.title = breed;
            });
        }
    }
});