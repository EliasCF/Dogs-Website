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
                this.images = data;
            });
        }
    }
});