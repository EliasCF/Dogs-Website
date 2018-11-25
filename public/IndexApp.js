var IndexApp = new Vue({
    el: '#IndexApp',
    data: {
        stuff: ''
    },
    created: function () {
        $.get('api/dogs/breeds.php', (data) => {
            this.stuff = data;
        });
    }
});