<?php
// Connexion BD et récupération des répétiteurs pour le carrousel d'accueil
$host = "localhost";
$dbname = "lekol";
$username = "root";
$password = "";

$repetiteursAccueil = [];
$avis = [];
try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Récupérer les répétiteurs pour le carrousel
    $stmt = $pdo->prepare("SELECT id, nom, prenom, matieres, description, photo_profil FROM repetiteur WHERE statut = 'actif' ORDER BY id DESC LIMIT 12");
    $stmt->execute();
    $repetiteursAccueil = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Récupérer les avis approuvés avec les informations des parents
    $stmt = $pdo->prepare("SELECT a.*, p.nom as parent_nom, p.prenom as parent_prenom 
                          FROM avis a 
                          LEFT JOIN parent p ON a.parent_id = p.id 
                          WHERE a.statut = 'approuve' 
                          ORDER BY a.date_creation DESC 
                          LIMIT 5");
    $stmt->execute();
    $avis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // rester silencieux sur la page d'accueil
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lekol</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="https://unpkg.com/aos@2.3.1/dist/aos.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Charger style.css en dernier pour forcer Poppins -->
    <link rel="stylesheet" href="style.css">
</head>
<body class="overflow-x-hidden">
    <!------------------------------------ entete ------------------------------------>
    <header id="navbar" class="fixed flex md:justify-around md:items-center justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <div class="md:flex gap-5 hidden">
            <a class="transition-all duration-300 ease-in-out active" href="">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact.php">Contact</a>
        </div>
        
        <div class="flex flex-row justify-between gap-[4rem] items-center">
            <a href="Choix.php"><button class="bg-[#2B80F6] p-2 px-5 rounded-lg text-white hidden md:block shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">S'inscrire</button></a>

            <a href="Connexion.php">
                <div class="md:w-[3rem] md:h-[3rem] h-[2rem] w-[2rem] cursor-pointer rounded-full bg-[#2B80F6] text-white flex items-center relative right-[3rem] justify-center shadow-lg">
                    <i class="fa-solid fa-user"></i>
                </div>
            </a>
        </div>

        <div id="menu-hamburger" class="md:hidden absolute top-6 right-6 z-50 transition-all duration-[1s]">
            <img src="Images/menu.png" alt="">
        </div>
        
        <div id="mobilenav" class="md:hidden scale-0 flex flex-col gap-5  absolute top-[4.3rem] right-0 bg-white p-5 rounded-lg shadow-lg transition-all duration-[1s] transform origin-top-right">
            <a class="transition-all duration-300 ease-in-out active" href="">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out" href="AnciensSujets.php">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact.php">Contact</a>
        </div>

    </header>


    <!---------------------------------- section1 --------------------------------------->
    <section class="flex flex-col md:flex-row px-4 md:px-20 md:pt-20 pt-[5rem] mb-16 items-center justify-between">
        <!-- Première div : glisse depuis la gauche -->
        <div data-aos="fade-right">
            <h1 class="font-bold md:text-6xl mt-10 text-4xl mb-10">Répétiteurs à <br> portée de clic </h1>
            <p class="md:text-xl text-justify mt-10">
            <span class="text-[#2B80F6]">Lékol</span> est une plateforme qui connecte les <br>
            parents d'élèves et étudiants pour un <br>
            accompagnement scolaire personnalisé.
            </p>
            <a href="Choix.php"><button class="bg-[#2B80F6] p-2 rounded-lg text-white md:mt-20 mt-5 text-xl px-5 shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">Je m'inscris</button></a>
        </div>

        <!-- Deuxième div : glisse depuis la droite -->
        <div data-aos="fade-left" class="md:w-1/2 mt-10">
            <img src="Images/undraw_educator_6dgp.png" alt="">
        </div>
    </section>



    <!------------------------------- section 2 ------------------------------------------->
    <section data-aos="fade-up" class="w-full px-4">
        <div class="bg-gray-200 px-4 md:mx-20 h-auto rounded-lg mb-[60px] flex flex-col py-10 justify-center">
            <h1 class="text-3xl relative font-bold md:left-9 text-center mb-5">NOTRE IMPACT EN CHIFFRE</h1>
            <p class="relative md:left-10 mx-3 text-justify md:w-max whitespace-normal">Depuis notre lancement, Lékol transforme l'éducation au Cameroun en facilitant l'accès
                à un soutien scolaire de qualité pour tous.</p>

            <div class="flex md:flex-row gap-4 flex-col justify-around mt-[50px]">
                <div class="justify-center items-center flex flex-col">
                    <p>Répétiteurs inscrits</p>
                    <span id="repetiteur" class="font-bold text-6xl text-[#2B80F6]"></span>
                </div>
                <div class="justify-center items-center flex flex-col">
                    <p>Elèves accompagnés</p>
                    <span id="eleves" class="font-bold text-6xl text-[#2B80F6]"> </span>
                </div>
                <div class="justify-center items-center flex flex-col">
                    <p>Taux de réussite</p>
                    <span id="taux" class="font-bold text-6xl text-[#2B80F6]"></span>
                </div>
                <div class="justify-center items-center flex flex-col">
                    <p >Villes couvertes</p>
                    <span id="villes" class="font-bold text-6xl text-[#2B80F6]"></span>
                </div>
            </div>
        </div>        
    </section>


        <!------------------------------- section 3 ------------------------------------------->
        <section class="flex md:mx-20 mx-4 justify-between items-center flex-col md:flex-row ">
            <div data-aos="fade-right" class="md:w-1/2">
                <img src="Images/undraw_online-test_20lm.png" alt="">
            </div>
            <div data-aos="fade-left" class="md:text-end">
                <h1 class="md:text-6xl text-[2rem] font-bold mb-5"><span class="text-[#2B80F6]">Comment</span> ça marche ?</h1>
                <p class="md:text-xl">Inscrivez-vous gratuitement <br>
                    Trouvez un répétiteur pres de chez vous <br>
                    Contactez-le en un clic (par appel ou via WhatsApp)
                </p>
                <button class="bg-[#2B80F6] p-2 rounded-lg text-white md:mt-20 mt-5 text-xl px-5 shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">Voir plus</button>
            </div>      
        </section>


        <!------------------------------- section 4 ------------------------------------------->
        <section data-aos="fade-up" class="relative w-full max-w-7xl mx-auto mt-16 px-4">
            <div class="flex w-full justify-end pb-2 font-bold relative right-5">
                <a href="Repetiteurs.php">Voir Plus ></a>
            </div>
        <!-- Bouton gauche -->
    <button type="button" id="carouselPrev"
            class="absolute left-1 md:-left-2 top-1/2 -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow hover:bg-gray-100 focus:outline-none select-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="md:w-5 md:h-5 w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
    </button>

        <!-- Fenêtre du carrousel -->
    <div class="overflow-hidden">
        <!-- Piste du carrousel (c'est elle qui se déplace) -->
        <div id="carouselTrack" class="flex transition-transform duration-500 ease-out">
            <?php $slides = array_chunk($repetiteursAccueil, 4); if (!empty($slides)) { foreach ($slides as $slide) { ?>
        <div class="shrink-0 w-full grid grid-cols-1 sm:grid-cols-2 gap-6 px-4">
                <?php foreach ($slide as $rep) { ?>
                <article class="bg-gray-100 rounded-2xl p-6">
            <div class="flex items-center gap-4">
                <div class="w-20 h-20 rounded-full bg-pink-200 overflow-hidden flex items-center justify-center">
                            <?php if (!empty($rep['photo_profil'])) { ?>
                                <img src="<?php echo htmlspecialchars($rep['photo_profil']); ?>" alt="photo profil" class="w-full h-full object-cover">
                            <?php } else { ?>
                <img src="Images/student-7378903_1920.jpg" alt="photo profil" class="w-full h-full object-cover">
                            <?php } ?>
                </div>
                <div class="min-w-0">
                            <h3 class="text-2xl font-semibold leading-tight"><?php echo htmlspecialchars($rep['prenom'].' '.$rep['nom']); ?></h3>
                <div class="flex items-center gap-1 mt-1">
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-yellow-400" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                    <svg class="w-4 h-4 text-gray-300" viewBox="0 0 20 20" fill="currentColor"><path d="M9.049 2.927c.3-.907 1.602-.907 1.902 0l1.16 3.57a1 1 0 00.95.69h3.756c.97 0 1.372 1.24.589 1.81l-3.04 2.21a1 1 0 00-.365 1.118l1.16 3.57c.302.907-.754 1.657-1.54 1.118l-3.04-2.21a1 1 0 00-1.176 0l-3.04 2.21c-.785.539-1.841-.211-1.54-1.118l1.16-3.57a1 1 0 00-.364-1.118l-3.04-2.21c-.783-.57-.38-1.81.588-1.81h3.756a1 1 0 00.95-.69l1.16-3.57z"/></svg>
                </div>
                            <p class="text-sm text-gray-600 mt-1"><?php echo htmlspecialchars($rep['matieres'] ?? ''); ?></p>
                </div>
            </div>
                    <p class="text-sm text-gray-700 mt-4"><?php echo htmlspecialchars($rep['description'] ?? ''); ?></p>
            </article>
                <?php } ?>
            </div>
            <?php } } ?>
        </div>


    <!-- Bouton droite -->
    <button type="button" id="carouselNext"
            class="absolute right-1 md:-right-2 top-1/2 md:block -translate-y-1/2 z-10 bg-white p-2 rounded-full shadow hover:bg-gray-100 focus:outline-none select-none">
        <svg xmlns="http://www.w3.org/2000/svg" class="md:w-5 md:h-5 w-3 h-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
        </svg>
    </button>

    <!-- Indicateurs -->
    <div id="carouselDots" class="flex justify-center mt-6 gap-2">
        <?php $numSlides = (int)ceil(count($repetiteursAccueil) / 4); if ($numSlides > 0) { for ($i = 0; $i < $numSlides; $i++) { ?>
            <button type="button" class="h-3 w-3 rounded-full <?php echo $i === 0 ? 'bg-[#2B80F6]' : 'bg-gray-300'; ?>" aria-label="Aller au slide <?php echo $i + 1; ?>"></button>
        <?php } } ?>
    </div>
    <script>
    (function(){
        const track = document.getElementById('carouselTrack');
        const dotsContainer = document.getElementById('carouselDots');
        if (!track || !dotsContainer) return;
        const dots = Array.from(dotsContainer.querySelectorAll('button'));
        const prev = document.getElementById('carouselPrev');
        const next = document.getElementById('carouselNext');
        const numSlides = dots.length;
        let current = 0;
        function update(){
            track.style.transform = 'translateX(-' + (current * 100) + '%)';
            dots.forEach((b, i) => {
                if (i === current) { b.classList.remove('bg-gray-300'); b.classList.add('bg-[#2B80F6]'); }
                else { b.classList.add('bg-gray-300'); b.classList.remove('bg-[#2B80F6]'); }
            });
        }
        dots.forEach((b, i) => b.addEventListener('click', () => { current = i; update(); }));
        if (prev) prev.addEventListener('click', () => { current = Math.max(0, current - 1); update(); });
        if (next) next.addEventListener('click', () => { current = Math.min(numSlides - 1, current + 1); update(); });
    })();
    </script>
</section>

<!------------------------------- section 5 ------------------------------------------->
<section data-aos="fade-up" class="w-full mt-[65px] px-4 md:px-20">
    <div class="max-w-7xl mx-auto">
        <div class="text-center mb-12">
            <h1 class="md:text-6xl text-[2rem] font-bold"><span class="text-[#2B80F6]">Pourquoi</span> nous choisir?</h1>
            <p class="mt-3 text-gray-600 max-w-2xl mx-auto">Des atouts concrets pour vous accompagner, avec simplicité, transparence et efficacité.</p>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <article class="group rounded-2xl p-6 bg-white/70 backdrop-blur shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" role="article" aria-label="Proximité">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-12 w-12 rounded-xl bg-blue-50 flex items-center justify-center ring-1 ring-blue-100">
                        <img class="h-6 w-6" src="Images/map-pin.png" alt="Icône Proximité">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Proximité</h3>
                        <p class="mt-1 text-gray-600">Trouvez rapidement des répétiteurs qualifiés près de chez vous pour un accompagnement personnalisé.</p>
                    </div>
                </div>
            </article>
            <article class="group rounded-2xl p-6 bg-white/70 backdrop-blur shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" role="article" aria-label="Profils vérifiés">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-12 w-12 rounded-xl bg-green-50 flex items-center justify-center ring-1 ring-green-100">
                        <img class="h-6 w-6" src="Images/circle-check.png" alt="Icône Profils vérifiés">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Profils vérifiés</h3>
                        <p class="mt-1 text-gray-600">Documents et identités contrôlés pour garantir sérieux, transparence et fiabilité.</p>
            </div>
        </div>
            </article>
            <article class="group rounded-2xl p-6 bg-white/70 backdrop-blur shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" role="article" aria-label="Contact rapide">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-12 w-12 rounded-xl bg-purple-50 flex items-center justify-center ring-1 ring-purple-100">
                        <img class="h-6 w-6" src="Images/phone.png" alt="Icône Contact rapide">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Contact rapide</h3>
                        <p class="mt-1 text-gray-600">Un clic suffit pour joindre un répétiteur par appel ou WhatsApp.</p>
            </div>
        </div>
            </article>
            <article class="group rounded-2xl p-6 bg-white/70 backdrop-blur shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" role="article" aria-label="Anciens sujets">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-12 w-12 rounded-xl bg-amber-50 flex items-center justify-center ring-1 ring-amber-100">
                        <img class="h-6 w-6" src="Images/file-text.png" alt="Icône Anciens sujets">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Anciens sujets</h3>
                        <p class="mt-1 text-gray-600">Accès aux sujets et corrigés des examens nationaux pour booster vos révisions.</p>
            </div>
        </div>
            </article>
            <article class="group rounded-2xl p-6 bg-white/70 backdrop-blur shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" role="article" aria-label="Avis et évaluations">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-12 w-12 rounded-xl bg-yellow-50 flex items-center justify-center ring-1 ring-yellow-100">
                        <img class="h-6 w-6" src="Images/star.png" alt="Icône Avis">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Avis et évaluations</h3>
                        <p class="mt-1 text-gray-600">Des retours authentiques pour choisir en toute confiance.</p>
            </div>
        </div>
            </article>
            <article class="group rounded-2xl p-6 bg-white/70 backdrop-blur shadow-sm border border-gray-100 hover:shadow-lg transition-all duration-300 hover:-translate-y-1" role="article" aria-label="Revenus">
                <div class="flex items-start gap-4">
                    <div class="shrink-0 h-12 w-12 rounded-xl bg-cyan-50 flex items-center justify-center ring-1 ring-cyan-100">
                        <img class="h-6 w-6" src="Images/map-pin.png" alt="Icône Revenus">
                    </div>
                    <div>
                        <h3 class="text-xl font-semibold">Revenus</h3>
                        <p class="mt-1 text-gray-600">Valorisez vos compétences et générez des revenus en toute simplicité.</p>
                    </div>
            </div>
            </article>
        </div>
    </div>
</section>

<!------------------------------- section 6 ------------------------------------------->
<section data-aos="fade-up" class="flex flex-col mt-[65px] items-center justify-center">
    <h1 class="md:text-6xl text-[2rem] font-bold"><span class="text-[#2B80F6]">Anciens</span> sujets & corrigés</h1>
    <p class="my-5 text-center">Prépare toi efficacement aux différents examens.</p>
    <div class="flex flex-col md:flex-row gap-5 my-10 justify-between">
        <div class="flex flex-col items-center justify-center p-5 md:mr-10 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Mathematiques</p>
        </div>
        <div class="flex flex-col items-center justify-center p-20 md:mr-10 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Physique</p>
        </div>
        <div class="flex flex-col items-center justify-center p-20 md:mr-10 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Chimie</p>
        </div>
    </div>
    <a href="AnciensSujets.php"><button class="relative -top-10 bg-[#2B80F6] p-2 rounded-lg -mt-20 text-white md:mt-20 mt-5 text-xl px-5 shadow-lg transition-all duration-300 hover:scale-110 hover:shadow-[#2B80F6]-400/50">Explorer tous les sujets</button></a>
</section>

<!------------------------------- section 7 ------------------------------------------->
<section data-aos="fade-up" class="relative w-full flex justify-center items-center py-12 px-4 bg-white">
  <div class="md:mr-20 md:ml-20 mx-4 w-full relative overflow-hidden">
    
    <!-- Slides container -->
    <div id="testimonialSlides" class="flex transition-transform duration-500 ease-in-out">
      <?php if (empty($avis)): ?>
        <!-- Message par défaut si aucun avis -->
      <div class="min-w-full flex justify-center">
        <div class="bg-white border-gray-400 border shadow-lg rounded-2xl shadow-sm p-6 sm:p-10 w-3xl w-full flex flex-col sm:flex-row items-center gap-6">
          <img src="Images/ai-generated-9010550_1920.png" alt="profile" class="w-24 h-24 rounded-full object-cover">
          <div class="flex-1 text-center sm:text-left">
              <h3 class="text-xl font-bold">Aucun avis disponible</h3>
              <p class="text-gray-600 mb-4">Soyez le premier à laisser un avis !</p>
              <p class="text-gray-800 mb-4">"Rejoignez notre communauté et partagez votre expérience avec nos répétiteurs qualifiés."</p>
            <!-- Stars -->
            <div class="flex justify-center sm:justify-start">
                <span class="text-gray-400 text-2xl">★</span>
                <span class="text-gray-400 text-2xl">★</span>
                <span class="text-gray-400 text-2xl">★</span>
              <span class="text-gray-400 text-2xl">★</span>
              <span class="text-gray-400 text-2xl">★</span>
            </div>
            </div>
          </div>
        </div>
      <?php else: ?>
        <?php foreach ($avis as $index => $avi): ?>
      <div class="min-w-full flex justify-center">
        <div class="bg-white border-gray-400 border shadow-lg rounded-2xl shadow-sm p-6 sm:p-10 w-3xl w-full flex flex-col sm:flex-row items-center gap-6">
          <img src="Images/ai-generated-9010550_1920.png" alt="profile" class="w-24 h-24 rounded-full object-cover">
          <div class="flex-1 text-center sm:text-left">
                <h3 class="text-xl font-bold"><?php echo htmlspecialchars(($avi['parent_prenom'] ?? 'Parent') . ' ' . ($avi['parent_nom'] ?? 'Anonyme')); ?></h3>
                <p class="text-gray-600 mb-4">Parent d'élève</p>
                <p class="text-gray-800 mb-4">"<?php echo htmlspecialchars($avi['commentaire']); ?>"</p>
                <!-- Stars dynamiques -->
            <div class="flex justify-center sm:justify-start">
                  <?php 
                  $note = (int)$avi['note'];
                  for ($i = 1; $i <= 5; $i++): 
                    $starClass = $i <= $note ? 'text-yellow-400' : 'text-gray-400';
                  ?>
                    <span class="<?php echo $starClass; ?> text-2xl">★</span>
                  <?php endfor; ?>
        </div>
      </div>
            </div>
          </div>
        <?php endforeach; ?>
      <?php endif; ?>

    </div>

    <!-- Navigation buttons -->
    <button onclick="moveTestimonial(-1)" class="absolute left-2 top-1/2 -translate-y-1/2  p-2 rounded-full shadow hover:bg-gray-200">
      &#10094;
    </button>
    <button onclick="moveTestimonial(1)" class="absolute right-2 top-1/2 -translate-y-1/2  p-2 rounded-full shadow hover:bg-gray-200">
      &#10095;
    </button>

    <!-- Indicators -->
    <div class="flex justify-center mt-6 space-x-3">
      <span class="testimonial-dot w-4 h-4 bg-blue-500 rounded-full cursor-pointer"></span>
      <span class="testimonial-dot w-4 h-4 bg-gray-300 rounded-full cursor-pointer"></span>
      <span class="testimonial-dot w-4 h-4 bg-gray-300 rounded-full cursor-pointer"></span>
      <span class="testimonial-dot w-4 h-4 bg-gray-300 rounded-full cursor-pointer"></span>
      <span class="testimonial-dot w-4 h-4 bg-gray-300 rounded-full cursor-pointer"></span>
    </div>
  </div>
</section>


<!------------------------------- section 8 ------------------------------------------->
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
                        <a href="index.php" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-home text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Accueil</span>
                        </a>
                        <a href="About.php" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-info-circle text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">À propos</span>
                        </a>
                        <a href="Repetiteurs.php" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-chalkboard-teacher text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Répétiteurs</span>
                        </a>
                        <a href="AnciensSujets.php" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
                            <i class="fas fa-file-alt text-blue-400 group-hover:text-cyan-300 transition-colors duration-300"></i>
                            <span class="group-hover:font-semibold">Anciens sujets</span>
                        </a>
                        <a href="Contact.php" class="group flex items-center justify-center lg:justify-start space-x-3 text-slate-300 hover:text-white transition-all duration-300 transform hover:translate-x-2">
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
    <script src="Scripts/script.js"></script>
    <script src="Scripts/scriptIncrement.js"></script>
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    <script>
        AOS.init({
        duration: 1000,   // durée de l'animation en ms
        once: false,      // rejoue l'animation à chaque descente
        mirror: false     // pas d'animation quand on remonte
        });
    </script>
</body>
</html>