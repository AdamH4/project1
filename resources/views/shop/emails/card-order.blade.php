@component('mail::message')
# Dobry den posielame Vam tento email ako fakturu k Vasej objednavke ku ktorej platbu evidujeme

Verime ze vsetko prebehlo v poriadku no ak nie nevahajte a kontaktujte nas na <a href="{{route('contacts')}}">nasej adrese</a>.

#Dorucovacia a fakturacna adresa:
<li>{{$information['first_name']}}</li>
<li>{{$information['second_name']}}</li>
<li>{{$information['city']}}</li>
<li>{{$information['street']}}</li>
<li>{{$information['postcode']}}</li>
<li>{{$information['country']}}</li>
<li>{{$information['phone_number']}}</li>
@if($information['note'])
    #Poznamka:
    <li>{{$information['note']}}</li>
@endif

#Vasa objednávka vám bude odoslaná službou akú ste si vybrali({{$type}})

#Vas objednany tovar
@component('mail::table')
    <table class="table">
        <thead class="thead-dark">
        <tr>
            <th>Produkt</th>
            <th>Množstvo</th>
            <th>Cena</th>
        </tr>
        </thead>
        <tbody>
        @foreach($products as $product)
            <tr>
                <td>
                    <a href="{{route('product.show',$product->id)}}">
                        <img src="{{ asset('images/'. $product->options->picture) }}" height="100" width="100">
                        {{$product->name}}
                    </a>
                </td>
                <td>
                    <div class="quantity col-2">
                        {{$product->qty}}
                    </div>
                </td>
                <td>
                    <div class="price">
                        {{$product->price}}
                    </div>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endcomponent
<hr>
<h4>Suma celkom: {{$total}}</h4>

<h4>Dakujeme Vam za nakup.</h4>
@endcomponent
