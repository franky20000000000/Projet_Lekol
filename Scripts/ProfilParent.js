 // Gestion de la modification du profil
        document.querySelectorAll('button').forEach(button => {
            if (button.textContent.includes('Modifier')) {
                button.addEventListener('click', function() {
                    alert('Passage en mode édition du profil');
                    // Ici, vous transformerez les champs en inputs pour modification
                });
            }
        });