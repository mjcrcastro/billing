@extends('master')

@section('products_active')
active
@stop

@section('main')
<div>
<h1>Inventario en deficit Top 10</h1>
<div id="graph"></div>
<h1>Inventario en exceso</h1>
<div id="graph2"></div>
</div>
<script src="http://cdnjs.cloudflare.com/ajax/libs/raphael/2.1.2/raphael-min.js"></script>
<script type='text/javascript'>
    
    /*
 * Play with this code and it'll update in the panel opposite.
 *
 * Why not try some of the options above?
 */
Morris.Bar({
  element: 'graph',
  data: [
    { y: '2006', a: 100},
    { y: '2007', a: 75 },
    { y: '2008', a: 50},
    { y: '2009', a: 75},
    { y: '2010', a: 50},
    { y: '2011', a: 75},
    { y: '2012', a: 100},
    { y: '2006', a: 100},
    { y: '2007', a: 75 },
    { y: '2008', a: 50},
    { y: '2009', a: 75},
    { y: '2010', a: 50},
    { y: '2011', a: 75},
    { y: '2012', a: 100}
  ],
  xkey: 'y',
  ykeys: ['a'],
  labels: ['Series A']
});

</script>
@stop