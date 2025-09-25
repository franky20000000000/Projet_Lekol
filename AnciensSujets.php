<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Anciens sujets | Lekol</title>
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <!------------------------------------ entete ------------------------------------>
    <header id="navbar" class="fixed flex md:justify-around justify-between z-50 items-center p-4 shadow-[0_0.5px_6px_rgba(0,0,0,0.1)] w-full text-xl">
        <div>
            <p class="text-[#2B80F6] font-bold text-3xl">Lékol</p>
        </div>
        <div class="md:flex gap-5 hidden">
            <a class="transition-all duration-300 ease-in-out" href="index.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out  active" href="AnciensSujets.php">Anciens sujets</a>
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
        
        <div id="mobilenav" class="md:hidden  flex flex-col gap-5  absolute top-[4.3rem] right-0 bg-white p-5 rounded-lg shadow-lg transition-all duration-[1s] scale-0 transform origin-top-right">
            <a class="transition-all duration-300 ease-in-out" href="index.php">Accueil</a>
            <a class="transition-all duration-300 ease-in-out" href="About.php">A Propos</a>
            <a class="transition-all duration-300 ease-in-out" href="Repetiteurs.php">Répétiteurs</a>
            <a class="transition-all duration-300 ease-in-out active" href="">Anciens sujets</a>
            <a class="transition-all duration-300 ease-in-out" href="Contact.php">Contact</a>
        </div>

    </header>

    <section class="pt-[7rem] mb-[3rem] md:mx-20 mx-4 grid md:grid-cols-3 grid-cols-1 gap-10 h-auto">
          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Mathematiques</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Physique</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Chimie</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">SVT</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Informatique</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Litterature</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Langues</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Anglais</p>
          </div>

          <div class="flex flex-col items-center justify-center p-5 h-[13rem] w-70 border-2 border-[#2B80F6] rounded-lg transition-all duration-300 hover:scale-105 cursor-pointer">
            <img class="h-20 w-20" src="Images/file_40px.png" alt="">
            <p class="text-3xl">Philosophie</p>
          </div>
    </section>


    <script src="Scripts/script.js"></script>
    <script src="Scripts/tailwindcss.js"></script>
</body>
</html>