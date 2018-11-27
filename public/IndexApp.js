var IndexApp = new Vue({
    el: '#IndexApp',
    data: {
        dogs: {},
        images: {}
    },
    created: function () {
        $.get('api/dogs/breeds.php', (data) => {
            this.dogs = data;
        });
    },
    methods: {
        getBreed: function (breed) {
            console.log(breed);
        }
    }
});