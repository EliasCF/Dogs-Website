var IndexApp = new Vue({
    el: '#IndexApp',
    data: {
        stuff: ''
    },
    created: function () {
        $.get('/api/dogs/breeds?amount=1', (data) => {
            this.stuff = data;
        });
    }
});