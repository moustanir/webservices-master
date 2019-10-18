$(document).ready(()=>{
    let server = "silex"
    let optionsAjaxGetClients = {
        "url":`http://${server}/client`,
        "type":"GET"
    };
    function formatData(value){
        return (value == "null" || value == null || value == "") ? "non précisé" : value;
    }
    function getClientOrders(id){
        let optionsAjaxGetClients = {
            "url":`http://${server}/client/${id}/commandes`,
            "type":"GET"
        }; 
        $.ajax(optionsAjaxGetClients).then(response=>{
            if(response.success){
                let orders = response.results.users,
                    tbodyOrders = document.getElementById("tbody-orders");
                orders.forEach(element => {
                    prepareRowOrder(element,tbodyOrders);
                });
            }
        });
    }
    function prepareRowClient(client,tbody){
        let row = `
                <tr id="${formatData(client.id_client)}">
                    <td>${formatData(client.nom)}</td>
                    <td>${formatData(client.prenom)}</td>
                    <td>${formatData(client.adresse)}</td>
                    <td>${formatData(client.date_naissance)}</td>
                    <td>${formatData(client.civilite)}</td>
                    <td>${formatData(client.numero)}</td>
                    <td>${formatData(client.ville)}</td> 
                    <td>
                    <button class="btn btn-info" id="order--{${formatData(client.id_client)}" data-client="${formatData(client.id_client)}" >Afficher les commandes</button>
                    </td>
                </tr>
        `;
        $(tbody).append(row);
        $(`order--${formatData(client.id_client)}`).click(()=>{
            console.log("J'affiche les commandes");
        })
    }
    $.ajax(optionsAjaxGetClients).then(response=>{
        if(response.success){
            let clients = response.results.clients,
                tbodyClients = document.getElementById("tbody-client");
            clients.forEach(element => {
                prepareRowClient(element,tbodyClients);
            });
        }
    });
});