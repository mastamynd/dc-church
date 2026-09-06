const mix = require('laravel-mix');

mix.js('Resources/assets/js/payment-schedules.js', 'Resources/assets/js/payment-schedules.min.js')
    .sass('./../../resources/assets/sass/argon.scss', './../../public/css');
