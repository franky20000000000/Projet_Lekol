<?php
session_start();

// Vérifier si l'utilisateur est connecté
if(!isset($_SESSION['id'])){
    header("Location: inscriptionparent.php");
    exit;
}
?>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <title>Contact | Lekol</title>
</head>
<body>
    <!------------------------------------ entete ------------------------------------>
    <header id="navbar" class="fixed flex md:justify-around justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <div class="md:flex gap-5 hidden">
            <a class="transition-all duration-300 ease-in-out" href="index2.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About2.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs2.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets2.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out active" href="">Contact</a>
        </div>

        <?php
        // Exemple de données récupérées après inscription
        $nom =$_SESSION['nom'] ;
        $prenom =$_SESSION['prenom'];

        // On prend la première lettre du prénom et du nom
        $initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
        ?>
        <a href="ProfilParent.php">
            <div class="w-10 h-10 cursor-pointer rounded-full bg-[#2B80F6] text-white flex items-center relative right-[3rem] justify-center text-xl font-bold shadow-lg">
            <?php echo $initiales; ?>
        </div>
        </a>

        <div id="menu-hamburger" class="md:hidden absolute top-6 right-6 z-50 transition-all duration-[1s]">
            <img src="Images/menu.png" alt="">
        </div>
        
        <div id="mobilenav" class="md:hidden scale-0 flex flex-col gap-5  absolute top-[4.3rem] right-0 bg-white p-5 rounded-lg shadow-lg transition-all duration-[1s] transform origin-top-right">
            <a class="transition-all duration-300 ease-in-out" href="">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About2.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs2.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets2.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out active">Contact</a>
        </div>

    </header>

    <h1 class="flex justify-center items-center pt-[6rem] md:text-6xl text-3xl font-bold mb-5">Contactez-<span class="text-[#2B80F6]">nous</span></h1>
    <form action="" class="rounded-2xl flex flex-col md:flex-row gap-5 md:mx-[15rem] md:p-2 md:shadow-lg justify-center items-center h-auto shadow-lg mb-10 mx-4 p-5">
        <div class="flex flex-col gap-4 bg-[#2B80F6] text-white p-[2rem] md:h-[27rem] h-auto rounded-xl justify-center items-center">
            <h1 class="text-3xl text-center">Nos informations</h1>
            <p class="relative text-[11px] text-center mb-4">Laissez un message en cas de preoccupation</p>
            <div class="flex md:flex-row flex-col items-center">
                <img class="h-[2rem] w-[2rem]" src="Images/telephone (1).png" alt="">
                <p>+237 694 210 071</p>
            </div>

            <div class="flex md:flex-row flex-col items-center">
                <img class="h[2rem] w-[2rem]" src="Images/mail.png" alt="">
                <p>lekol@gmail.com</p>
            </div>

            <div class="flex md:flex-row flex-col items-center">
                <img class="h[2rem] w-[2rem]" src="Images/location_30px.png" alt="">
                <p>Bonamoussadi,Douala</p>
            </div>
        </div>

        <div class="flex flex-col gap-5">
            <div class="flex md:flex-row flex-col gap-4">
                <div class="flex flex-col">
                    <label for="name">Nom</label>
                    <input class="bg-transparent border-0 border-b-2 border-black focus:outline-none" type="text" id="name" placeholder="Votre nom">
                </div>
                <div class="flex flex-col">
                    <label for="name">Prenom</label>
                    <input class="bg-transparent border-0 border-b-2 border-black focus:outline-none" type="text" id="name" placeholder="Votre prenom">
                </div>
            </div>

            <div class="flex md:flex-row flex-col gap-4">
                <div class="flex flex-col">
                    <label for="email">Email</label>
                    <input class="bg-transparent border-0 border-b-2 border-black focus:outline-none" type="email" id="name" placeholder="Votre email">
                </div>
                <div class="flex flex-col">
                    <label for="number">Numero</label>
                    <input class="bg-transparent border-0 border-b-2 border-black focus:outline-none" type="text" id="name" placeholder="Votre numero">
                </div>
            </div>

            <div>
                <div class="flex flex-col">
                    <label for="message">Message</label>
                    <textarea class="bg-transparent border-0 border-b-2 border-black focus:outline-none" name="" placeholder="Laissez un message" id=""></textarea>
                </div>
            </div>
            <button class="bg-[#2B80F6] p-2 rounded-lg text-white mt-5 text-xl px-5 shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50 md:w-max">Envoyer</button>
        </div>  
    </form>  



    <footer class="relative bg-black text-white overflow-hidden min-h-80">  
        <!-- Contenu principal -->
        <div class="relative z-10 container mx-auto px-8 py-10">
            <!-- Section principale -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-12 mb-16">
                
                <!-- Logo et CTA -->
                <div class="text-center lg:text-left space-y-8 transform transition-transform duration-300">
                    <div class="space-y-4">
                        <h2 class="text-5xl text-[#2B80F6] font-bold">
                            Lékol
                        </h2>
                        <p class="text-slate-300 text-sm leading-relaxed">Votre plateforme d'éducation moderne et innovante</p>
                    </div>
                    <button class="hover:to-cyan-600 bg-[#2B80F6] text-white font-bold py-2 px-5 rounded-xl transition-all duration-300 transform hover:scale-110 hover:shadow-md hover:shadow-blue-500/50">
                        <i class="fas fa-search mr-3 group-hover:animate-spin"></i>
                        Recherche
                    </button>
                </div>

                <!-- Liens rapides -->
                <div class="text-center lg:text-left space-y-6">
                    <h3 class="text-2xl font-bold cursor-pointer relative md:after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[3px] after:w-0 after:bg-gradient-to-r after:from-blue-400 after:to-cyan-400 after:transition-all after:duration-500 hover:after:w-full">
                        Liens rapides
                    </h3>
                    <div class="space-y-4">
                        <a href="#" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-home text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Accueil</span>
                        </a>
                        <a href="#" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-info-circle text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">À propos</span>
                        </a>
                        <a href="#" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-chalkboard-teacher text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Répétiteurs</span>
                        </a>
                        <a href="#" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-file-alt text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Anciens sujets</span>
                        </a>
                        <a href="#" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-envelope text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Contact</span>
                        </a>
                    </div>
                </div>

                <!-- Contact -->
                <div class="text-center lg:text-left space-y-6">
                    <h3 class="text-2xl font-bold cursor-pointer relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[3px] after:w-0 after:bg-gradient-to-r after:from-blue-400 after:to-cyan-400 after:transition-all after:duration-500 hover:after:w-full">
                        Contact
                    </h3>
                    <div class="space-y-4">
                        <a href="tel:+237694210071" class="group flex items-center justify-center lg:justify-start space-x-4 text-slate-300 hover:text-white transition-all duration-300 transform hover:scale-105">
                             <i class="fas fa-phone text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">(+237) 694 210 071</span>
                        </a>
                        <a href="mailto:lekol@gmail.com" class="group flex items-center justify-center lg:justify-start space-x-4 text-slate-300 hover:text-white transition-all duration-300 transform hover:scale-105">
                             <i class="fas fa-envelope text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">lekol@gmail.com</span>
                        </a>
                    </div>
                </div>

                <!-- Réseaux sociaux -->
                <div class="text-center lg:text-left space-y-6">
                    <h3 class="text-2xl font-bold cursor-pointer relative after:content-[''] after:absolute after:left-0 after:bottom-0 after:h-[3px] after:w-0 after:bg-gradient-to-r after:from-blue-400 after:to-cyan-400 after:transition-all after:duration-500 hover:after:w-full">
                        Communauté
                    </h3>
                    <div class="space-y-4">
                        <a href="#" class="flex items-center justify-center lg:justify-start space-x-4 text-slate-300 hover:text-white transition-all duration-300 transform hover:scale-105">
                             <i class="fa-brands fa-facebook-f text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Facebook</span>
                        </a>
                        <a href="#" class="flex items-center justify-center lg:justify-start space-x-4 text-slate-300 hover:text-white transition-all duration-300 transform hover:scale-105">
                            <i class="fa-brands fa-linkedin-in  text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">LinkedIn</span>
                        </a>
                        <a href="#" class="flex items-center justify-center lg:justify-start space-x-4 text-slate-300 hover:text-white transition-all duration-300 transform hover:scale-105">
                            <i class="fa-brands fa-tiktok text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">TikTok</span>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Séparateur -->
            <div class="relative mb-10">
                    <div class="w-full border-t"></div>   
            </div>

            <!-- Copyright -->
            <div class="text-center">
                <div class=" bg-white/5 border border-white/10 rounded-xl px-8 py-4 inline-block transform hover:scale-105 transition-all duration-300">
                    <p class="text-slate-400 hover:text-white transition-colors duration-300">
                        <i class="far fa-copyright mr-2"></i>
                       © 2025 Lékol | Tous droits réservés.
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <script src="Scripts/script.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
</body>
</html>