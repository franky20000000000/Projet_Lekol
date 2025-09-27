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
    <title>A propos | Lekol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <!-- Charger style.css en dernier pour forcer Poppins -->
    <link rel="stylesheet" href="style.css">
</head>
<body>
     <!------------------------------------ entete ------------------------------------>
    <header id="navbar" class="fixed flex md:justify-around justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <div class="md:flex gap-5 hidden">
            <a class="transition-all duration-300 ease-in-out" href="index2.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out active" href="">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs2.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets2.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact2.php">Contact</a>
        </div>

        <?php
        // Exemple de données récupérées après inscription
        $nom =$_SESSION['nom'] ;
        $prenom =$_SESSION['prenom'];

        // On prend la première lettre du prénom et du nom
        $initiales = strtoupper(substr($prenom, 0, 1) . substr($nom, 0, 1));
        ?>
        <a class="focus-none hover:scale-105 transition-all duration-300" href="ProfilParent.php">
            <div class="w-10 h-10 cursor-pointer rounded-full bg-[#2B80F6] text-white flex items-center relative right-[3rem] justify-center text-xl font-bold shadow-lg">
            <?php echo $initiales; ?>
        </div>
        </a>

        <div id="menu-hamburger" class="md:hidden absolute top-6 right-6 z-50 transition-all duration-[1s]">
            <img src="Images/menu.png" alt="">
        </div>
        
        <div id="mobilenav" class="md:hidden scale-0 flex flex-col gap-5  absolute top-[4.3rem] right-0 bg-white p-5 rounded-lg shadow-lg transition-all duration-[1s] transform origin-top-right">
            <a class="transition-all duration-300 ease-in-out" href="index2.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out active" href="">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs2.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets2.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact2.php">Contact</a>
        </div>

    </header>

    <!------------------------------------ fin entete ------------------------------------>
    

    <!-- Fil d'Ariane -->
    <nav class="pt-[6rem] md:px-20 px-4 text-sm text-gray-500" aria-label="Fil d'Ariane">
        <ol class="flex items-center gap-2">
            <li><a href="index2.php" class="hover:text-[#2B80F6]">Accueil</a></li>
            <li aria-hidden="true" class="text-gray-300">/</li>
            <li class="text-gray-700 font-medium">À propos</li>
        </ol>
    </nav>

    <!------------------------------------ Section 1 ------------------------------------->
    <section class="flex flex-col md:flex-row px-4 md:px-20 pt-10 md:pt-12 mb-16 items-center justify-between gap-10">
        <div data-aos="fade-right" class="max-w-2xl">
            <h1 class="font-bold md:text-6xl text-4xl leading-tight">À propos de <span class="text-[#2B80F6]">Lékol</span></h1>
            <p class="mt-6 text-[15px] md:text-base text-gray-700 leading-7">Lékol est une communauté dédiée à la réussite scolaire au Cameroun. Notre mission est de connecter les parents avec des répétiteurs qualifiés, pour un accompagnement personnalisé, simple et sécurisé.</p>
            <div class="mt-8 grid grid-cols-2 gap-4">
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <p class="text-sm text-blue-700">Répétiteurs vérifiés</p>
                </div>
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <p class="text-sm text-blue-700">Accompagnement personnalisé</p>
                </div>
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <p class="text-sm text-blue-700">Recherche rapide et simple</p>
                </div>
                <div class="p-4 rounded-xl bg-blue-50 border border-blue-100">
                    <p class="text-sm text-blue-700">Plateforme 100% locale</p>
                </div>
            </div>
        </div>
        <div data-aos="fade-left" class="md:w-1/2 mt-4">
            <img src="Images/undraw_education_3vwh.png" alt="Illustration éducation" class="w-full h-auto">
        </div>
    </section>


    <!---------------------------------- Section 2 --------------------------------------------->
    <section class="w-full md:px-20 px-4 mt-5">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 items-center">
            <div data-aos="fade-up">
                <img class="rounded-2xl w-full object-cover" src="Images/schoolbus.jpg" alt="Soutien scolaire">
            </div>
            <div data-aos="fade-up" class="space-y-5">
                <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
                    <h2 class="text-2xl font-bold text-[#2B80F6] mb-2">Une plateforme 100% locale</h2>
                    <p class="text-gray-700">Nous mettons en relation les familles et des étudiants-répétiteurs motivés, avec des profils clairs et des disponibilités adaptées.</p>
                </div>
                <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100">
                    <h3 class="text-xl font-semibold mb-1">Simple et sécurisé</h3>
                    <p class="text-gray-700">Parcours fluide, profils vérifiés et informations transparentes pour décider en toute confiance.</p>
                </div>
            </div>
        </div>
    </section>


     <!---------------------------------- Section 3 --------------------------------------------->
     <section class="flex flex-col justify-center items-center mt-[60px] md:px-20 px-4">
        <div data-aos="fade-up" class="text-center max-w-3xl">
            <h2 class="text-4xl md:text-5xl font-bold"><span class="text-[#2B80F6]">Notre</span> mission</h2>
            <p class="text-gray-700 mt-3">Accompagner chaque élève vers la réussite grâce à un suivi personnalisé et des rencontres simples entre familles et répétiteurs.</p>
        </div>
        <div data-aos="fade-right" class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full mt-10">
            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 text-center">
                <div class="mx-auto flex h-[6rem] w-[6rem] bg-blue-100 justify-center items-center rounded-full">
                    <img class="h-[3rem] w-[3rem]" src="Images/family.png" alt="Parents">
                </div>
                <h3 class="text-2xl mt-4 font-semibold">Parents</h3>
                <p class="text-gray-700 mt-2">Trouvez et contactez des répétiteurs qualifiés pour vos enfants en toute confiance.</p>
            </div>
            <div data-aos="fade-left" class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 text-center">
                <div class="mx-auto flex h-[6rem] w-[6rem] bg-blue-100 justify-center items-center rounded-full">
                    <img class="h-[3rem] w-[3rem]" src="Images/student_male_2.png" alt="Répétiteurs">
                </div>
                <h3 class="text-2xl mt-4 font-semibold">Répétiteurs</h3>
                <p class="text-gray-700 mt-2">Proposez vos services et valorisez vos compétences académiques.</p>
            </div>
        </div>
     </section>


     <!---------------------------------- Section 4 --------------------------------------------->
     <section class="flex flex-col justify-center items-center mt-[60px] md:px-20 px-4">
        <h2 class="text-4xl md:text-5xl font-bold text-center"><span class="text-[#2B80F6]">Comment</span> ça marche ?</h2>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mt-10 w-full">
            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 text-center" data-aos="fade-up">
                <div class="mx-auto w-10 h-10 rounded-lg bg-blue-50 text-[#2B80F6] flex items-center justify-center">1</div>
                <h3 class="font-semibold mt-4">Cherchez</h3>
                <p class="text-gray-700 mt-1">Filtrez par matière, niveau et ville pour trouver le bon profil.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 text-center" data-aos="fade-up" data-aos-delay="100">
                <div class="mx-auto w-10 h-10 rounded-lg bg-blue-50 text-[#2B80F6] flex items-center justify-center">2</div>
                <h3 class="font-semibold mt-4">Contactez</h3>
                <p class="text-gray-700 mt-1">Échangez simplement pour valider la disponibilité et les attentes.</p>
            </div>
            <div class="bg-white rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 text-center" data-aos="fade-up" data-aos-delay="200">
                <div class="mx-auto w-10 h-10 rounded-lg bg-blue-50 text-[#2B80F6] flex items-center justify-center">3</div>
                <h3 class="font-semibold mt-4">Progressez</h3>
                <p class="text-gray-700 mt-1">Démarrez l’accompagnement et suivez les progrès.</p>
            </div>
        </div>
     </section>

     <!-- Bandeau CTA -->
     <section class="md:mx-20 mx-4 my-12">
        <div class="rounded-2xl bg-gradient-to-r from-[#2B80F6] to-blue-600 p-8 text-white flex flex-col md:flex-row items-center justify-between gap-4">
            <div>
                <h3 class="text-2xl font-semibold">Prêt à trouver un répétiteur ?</h3>
                <p class="text-white/90 text-sm mt-1">Parcourez les profils disponibles près de chez vous.</p>
            </div>
            <a href="Repetiteurs2.php" class="px-5 py-2 bg-white text-[#2B80F6] rounded-lg font-semibold hover:bg-gray-100">Découvrir</a>
        </div>
     </section>





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

    <script src="Scripts/tailwindcss.js">
    </script>
    <script src="Scripts/script.js">
    </script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
        duration: 1000,   // durée de l'animation en ms
        once: false,      // rejoue l’animation à chaque descente
        mirror: false     // pas d'animation quand on remonte
        });
    </script>
</body>
</html>