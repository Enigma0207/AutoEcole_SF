{% extends 'base.html.twig' %}

{% block title %}Hello liste permis!{% endblock %}

{% block body %}
   <h4>Nos Produits</h4>
<table class="table table-bordered ">

        <thead>
            <tr>
                <th scope="col">type</th>
                <th scope="col">price:€</th>
                <th scope="col">désciption</th>
                <th scope="col">image</th>
            </tr>
        </thead>
        <tbody>
            {% for permis1 in permis %}
        <tr>
            <td>{{ permis1.type | default('') | upper }}</td>
            <td>{{ permis1.price | default('') | upper }}</td>
            <td>{{ permis1.description }}</td>
            <td>
                {% if permis1.image %}
                    <img src="{{ asset('images/' ~ permis1.image) }}" alt="{{ permis1.id }}" />
                {% else %}
                    Pas d'image
                {% endif %}
            </td>
         </tr>
{% endfor %}
        </tbody>
    </table>
{% endblock %}
