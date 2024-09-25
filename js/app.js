document.addEventListener('DOMContentLoaded', function() {
    const agentsDiv = document.getElementById('agents');

    // Récupère les agents via l'API Valorant
    fetch('https://valorant-api.com/v1/agents')
        .then(response => {
            if (!response.ok) {
                throw new Error('Erreur lors du chargement des agents : ' + response.statusText);
            }
            return response.json();
        })
        .then(data => {
            data.data.forEach(agent => {
                const agentCard = document.createElement('div');
                agentCard.classList.add('card');

                // Crée le contenu de la carte agent
                agentCard.innerHTML = `
                    <img src="${agent.displayIcon}" alt="${agent.displayName}">
                    <div class="card-content">
                        <h2 class="card-title">${agent.displayName}</h2>

                        <button class="accordion">Afficher la description</button>
                        <div class="panel">
                            <p class="card-description">${agent.description}</p>
                        </div>

                        <button class="accordion">Afficher les compétences</button>
                        <div class="panel">
                            <ul>
                                ${agent.abilities.map(ability => `
                                    <li><strong>${ability.displayName}:</strong> ${ability.description}</li>
                                `).join('')}
                            </ul>
                        </div>

                        <button class="btn-favorite" data-agent-id="${agent.uuid}" data-agent-name="${agent.displayName}" data-agent-description="${agent.description}" data-agent-image="${agent.displayIcon}">Ajouter aux favoris</button>
                    </div>
                `;

                // Ajoute la carte de l'agent au conteneur
                agentsDiv.appendChild(agentCard);
            });

            // Gestion des boutons accordéon pour afficher/masquer les descriptions et compétences
            const accordions = document.querySelectorAll('.accordion');
            accordions.forEach(button => {
                button.addEventListener('click', function() {
                    this.classList.toggle('active');
                    const panel = this.nextElementSibling;
                    panel.style.display = panel.style.display === 'block' ? 'none' : 'block';
                });
            });

            // Gestion du bouton "Ajouter aux favoris"
            const favoriteButtons = document.querySelectorAll('.btn-favorite');
            favoriteButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const agentId = this.getAttribute('data-agent-id');
                    const agentName = this.getAttribute('data-agent-name');
                    const agentDescription = this.getAttribute('data-agent-description');
                    const agentImage = this.getAttribute('data-agent-image');

                    console.log("Bouton 'Ajouter aux favoris' cliqué pour l'agent ID : " + agentId);

                    // Envoie de la requête AJAX pour ajouter aux favoris et dans la table agents
                    fetch('php/add_favorite.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/x-www-form-urlencoded'
                        },
                        body: new URLSearchParams({
                            agent_id: agentId,
                            agent_name: agentName,
                            agent_description: agentDescription,
                            agent_image: agentImage
                        })
                    })
                    .then(response => {
                        console.log("Status de la réponse:", response.status);
                        if (!response.ok) {
                            throw new Error('Erreur HTTP : ' + response.statusText);
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log("Réponse du serveur:", data); // Affiche la réponse complète dans la console
                        if (data.status === 'success') {
                            alert(data.message);
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erreur lors de l\'ajout aux favoris :', error);
                        alert('Une erreur est survenue lors de l\'ajout aux favoris.');
                    });
                });
            });
        })
        .catch(error => {
            console.error('Erreur lors du chargement des agents:', error);
            alert('Une erreur est survenue lors du chargement des agents.');
        });
});
