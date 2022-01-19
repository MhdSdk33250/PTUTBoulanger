
    $("#add").click(function () {
      console.log('test');
        var html = "{% for article in Commande.articles %}<tr><td><select name='produits[]' id='produitselected'>{% for produitoption in Produits %}<option {% if article.produit.id == produitoption.id %} {{'selected'}} {% endif %} value='{{produitoption.id}}'>{{produitoption.nomProduit}}</option>{% endfor %}</select></td> <td><input name='qte[]' type='number' value='{{article.qte}}'></td><td><select name='Poids[]' id='poidsselected'><option value='{{article.categorie.id}}'>{{article.categorie.poids}}g</option>{% for categorie in Categories %}<option value='{{categorie.id}}'>{{categorie.poids}}g</option>{% endfor %}</select>{#<input type='number' value='{{article.categorie.poids}}'>#}</td></tr>{% endfor %}";
        var test = "<td>ok</td>";

        $('#tbodytab').append(test);
    });
    $(document).on('click', '#del', function () {
        $(this).closest('#row').remove();
    });


