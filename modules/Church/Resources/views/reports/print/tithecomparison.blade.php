<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Print {{trans('church::general.yearly_report').' '. $year}} </title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <style>
        table{
            border: none !important;
            border-collapse: collapse;
        }

        table th, table td{
            border: 1px solid #000;
        } 
    </style>

</head>

<body onload="window.print()" class="p-3">
    <div class="card">
        <div class="card-header">
            <h5>{{trans('church::general.tithe_comparison').' '. $fromYear.' To '. $toYear}}</h5>
        </div>

        <div class="card">
            <div class="card-body" id="bar-graph" data-records="{{json_encode($response)}}">

            </div>

        </div>


    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/selectize.js/0.13.3/js/standalone/selectize.min.js" integrity="sha512-pF+DNRwavWMukUv/LyzDyDMn8U2uvqYQdJN0Zvilr6DDo/56xPDZdDoyPDYZRSL4aOKO/FGKXTpzDyQJ8je8Qw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>

    <script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.0/raphael-min.js"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.min.js" integrity="sha512-6Cwk0kyyPu8pyO9DdwyN+jcGzvZQbUzQNLI0PadCY3ikWFXW9Jkat+yrnloE63dzAKmJ1WNeryPd1yszfj7kqQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.css" integrity="sha512-fjy4e481VEA/OTVR4+WHMlZ4wcX/+ohNWKpVfb7q+YNnOCS++4ZDn3Vi6EaA2HJ89VXARJt7VvuAKaQ/gs1CbQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/morris.js/0.5.1/morris.js" integrity="sha512-CYkt9kgBjbQrIKQGbyezfkmhmmFwMF2VVZjdGgYJJm3KnV53Ao+aFnndz2YbmMX2Y8XGBCUzBxFTqg2LAuIJzw==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script>
        var selectizerNow = function(tis) {
            var $url = $(tis).data('url');
            var $valueField = $(tis).data('valuefield');
            var $labelField = $(tis).data('labelfield');
            var $searchField = $(tis).data('searchfield');
            var $icon = $(tis).data('icon');
            var locked = $(this).attr('readonly') == 'true';

            if ($url) {
                $(tis).selectize({
                    valueField: $valueField,
                    labelField: $labelField,
                    searchField: $searchField,
                    create: false,
                    render: {
                        option: function(item, escape) {
                            return (
                                `<div class="p-2 pt-3 pb-3"><i class="far ${$icon}"></i> ${item[$labelField]}</div>`
                            );
                        },
                    },
                    load: function(query, callback) {
                        if (!query.length) return callback();
                        var $voucherType = $('select[name="payment_voucher_type"]').val();

                        $.ajax({
                            url: $url + encodeURIComponent(query),
                            type: "GET",
                            data: {
                                'voucher_type': $voucherType
                            },
                            error: function() {
                                callback();
                            },
                            success: function(res) {
                                callback(res);
                            },
                        });
                    },
                });
            } else {
                $(tis).selectize();
            }

            if (locked) {
                tis[0].selectize.lock();
            }
        }

        $(function() {
            $(this).find('.selectize-field').each(function() {
                if ($(this)[0].selectize) {
                    $(this)[0].selectize.destroy();
                }
                selectizerNow(this);
            });
            var data = $('#bar-graph').data('records'),
                config = {
                    data: data,
                    xkey: 'name',
                    ykeys: ['value'],
                    labels: ['Tithe Total'],
                    fillOpacity: 0.6,
                    hideHover: 'auto',
                    behaveLikeLine: true,
                    resize: true,
                    pointFillColors: ['#ffffff'],
                    pointStrokeColors: ['black'],
                    lineColors: ['gray', 'red']
                };
            config.element = 'bar-graph';
            Morris.Bar(config);
        });
    </script>
</body>

</html>