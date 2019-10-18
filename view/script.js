$(document).ready(()=>{
    let server = "silex";
    //Client
    let optionsAjaxGetClients = {
        "url":`http://${server}/client`,
        "type":"GET"
    };
    function formatData(value){
        return (value == "null" || value == null || value == "") ? "non précisé" : value;
    }
    function prepareRowClient(client,tbody){
        let row = `
                <tr>
                    <td>${formatData(client.id_client)}</td>
                    <td>${formatData(client.nom)}</td>
                    <td>${formatData(client.prenom)}</td>
                    <td>${formatData(client.adresse)}</td>
                    <td>${formatData(client.date_naissance)}</td>
                    <td>${formatData(client.civilite)}</td>
                    <td>${formatData(client.numero)}</td>
                    <td>${formatData(client.ville)}</td> 
                    <td>
                    <button class="btn btn-info" id="clientOrder--${client.id_client}">Afficher les commandes</button>
                    </td>
                </tr>
        `;

        $(tbody).append(row);
        $(`#clientOrder--${client.id_client}`).click(()=>{
            getClientOrders(client.id_client);
        });
    }
    
    $.ajax(optionsAjaxGetClients).then(response=>{
        if(response.success){
            let clients = response.results.data,
                tbodyClients = document.getElementById("tbody-client");
            clients.forEach(element => {
                prepareRowClient(element,tbodyClients);
            });
        }
    });
    //Order
    function getClientOrders(id){
        let optionsAjaxGetOrders = {
            "url":`http://${server}/client/${id}/commande`,
            "type":"GET"
        }; 
        $.ajax(optionsAjaxGetOrders).then(response=>{
            if(response.success){
                let orders = response.results.data,
                    tbodyOrders = document.getElementById("tbody-orders");
                orders.forEach(element => {
                    prepareRowOrder(element,tbodyOrders);
                });
            }
        });
    }
    function prepareRowOrder(order,tbody){
        tbody.innerHTML = ""
        let row = `
                <tr>
                    <td>${formatData(order.numero)}</td>
                    <td>${formatData(order.date_commande)}</td>
                    <td>
                        <button class="btn btn-info" id="orderProduits--${order.id_commande}" >Afficher les produits associés</button>
                    </td>
                </tr>
        `;
        $(tbody).append(row);
        $(`#orderProduits--${order.id_commande}`).click(()=>{
            getOrderProducts(order.id_commande);
        });
    }
    //Produits
    function getOrderProducts(id){
        let optionsAjaxGetOrders = {
            "url":`http://${server}/commande/${id}/produit`,
            "type":"GET"
        }; 
        $.ajax(optionsAjaxGetOrders).then(response=>{
            if(response.success){
                let produits = response.results.data,
                    tbodyOrders = document.getElementById("tbody-produits");
                console.log(`Length:${produits.length}`);
                produits.forEach(element => {
                    prepareRowProduct(element,tbodyOrders);
                });
            }
        });
    }
    function prepareRowProduct(product,tbody){
        //tbody.innerHTML = ""
        let row = `
                <tr>
                    <td>${formatData(product.libelle)}</td>
                    <td>${formatData(product.prix_unitaire)}</td>
                    <td>${formatData(product.reference)}</td>
                    <td>${formatData(product.fournisseur)}</td>
                    <td>${formatData(product.ville)}</td>
                </tr>
        `;
        $(tbody).append(row);
    }
});