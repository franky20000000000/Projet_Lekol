

function validateForm() {
    // Récupérer les valeurs des champs
    const email = document.querySelector('input[name="email"]').value.trim();
    const password = document.querySelector('input[name="motDePasse"]').value;
    const confirm = document.querySelector('input[name="confirm"]').value;
    const telephone = document.querySelector('input[name="telephone"]').value.trim();

    const emailError = document.getElementById('emailError');
    const phoneError = document.getElementById('phoneError');
    const passwordError = document.getElementById('passwordError');
    const confirmError = document.getElementById('confirmError');

    // Validation email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
   
     if (!emailRegex.test(email)) {
        emailError.hidden = false;
        emailError.textContent = 'Veuillez entrer une adresse e-mail valide';
        return false;
    }
    

    
    // Validation mot de passe
    if (password.length < 8) {
        passwordError.hidden = false;
        passwordError.textContent = 'Le mot de passe doit contenir au moins 8 caractères';
        return false;
    }
    
    if (!/(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/.test(password)) {
        passwordError.hidden = false;
        passwordError.textContent = 'Le mot de passe doit contenir au moins une majuscule, une minuscule et un chiffre';
        return false;
    }
    
    // Validation confirmation mot de passe
    if (password !== confirm) {
        confirmError.hidden = false;
        confirmError.textContent = 'Les mots de passe ne correspondent pas';
        return false;
    }
    
    // Validation téléphone (format Cameroun)
    const phoneRegex = /^(\+237)[\s-]?[6-9][0-9]{2}[\s-]?[0-9]{2}[\s-]?[0-9]{2}[\s-]?[0-9]{2}$/;
    if (!phoneRegex.test(telephone)) {
        phoneError.hidden = false;
        phoneError.textContent = 'Format de téléphone invalide. Exemple: +237 694210071';
        return false;
    }
    
    // Si tout est valide, le formulaire sera soumis au PHP
    return true;
}