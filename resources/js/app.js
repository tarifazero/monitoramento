require('./bootstrap');

window.Chart = require('chart.js');
Chart.defaults.global.defaultFontFamily = 'DM Sans';

window.enquire = require('enquire.js');

document.addEventListener('DOMContentLoaded', function () {
    setInterval(function() {
        var height = document.body.scrollHeight;
        parent.postMessage('resize::' + height, '*');
    }, 100);
});

