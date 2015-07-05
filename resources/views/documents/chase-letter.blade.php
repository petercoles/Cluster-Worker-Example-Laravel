<!DOCTYPE html>
<html>
<head>
<style>
body {font-family:"DejaVu Sans";}
#address {position:absolute; width:100%; top:50mm; left:50mm;}
#reference {position:absolute; width:100%; text-align:right; top:105mm; right:0;}
#salutation {position:absolute; width:100%; top:130mm; left:40mm;}
#value {position:absolute; width:100%; text-align:center; left:20mm; top:140mm;}
</style>
</head>
<body>

<div id="address">
{{ $document['customer'] }}<br>
{{ $document['street_address'] }}<br>
{{ $document['street_name'] }}<br>
{{ $document['city'] }}<br>
{{ $document['state'] }}<br>
{{ $document['postcode'] }}<br>
</div>

<div id="salutation">Dear {{ $document['customer'] }}</div>

<div id="reference">Our reference {{ $document['reference'] }}</div>

<div id="value">
{{ $document['currency'] }} {{ number_format($document['value'] / 100, 2) }}
</div>

</body>
</html>