const repetiteur= document.getElementById('repetiteur');
const eleves = document.getElementById('eleves');
const taux = document.getElementById('taux');
const villes = document.getElementById('villes');

    
// Fonction pour incrémenter un nombre avec un intervalle
  function incrementNumber(a,b,c) {
    let repetiteurs=0;

    let intRepetiteur= setInterval(() => {      
        repetiteurs++;    
    a.textContent= repetiteurs+c;
        if (repetiteurs==b) {
            clearInterval(intRepetiteur);
            console.log('stop');            
        }
    }, 10);
}
incrementNumber(repetiteur,100,'+');
incrementNumber(eleves,150,'+');
incrementNumber(taux,90,'%');
incrementNumber(villes,8,'+')

// --- Fin de l'incrémentation des nombres


// --- Carousel

  const track = document.getElementById('carouselTrack');
  const prevBtn = document.getElementById('carouselPrev');
  const nextBtn = document.getElementById('carouselNext');
  const dotsWrap = document.getElementById('carouselDots');
  const dots = Array.from(dotsWrap.children);
  const slides = Array.from(track.children); // 5 slides
  const total = slides.length;

  let index = 0;

  // --- Mise à jour de la piste + indicateurs
  function update() {
    track.style.transform = `translateX(-${index * 100}%)`;
    dots.forEach((d, i) => {
      if (i === index) {
        d.classList.remove('bg-gray-300');
        d.classList.add('bg-[#2B80F6]');
      } else {
        d.classList.add('bg-gray-300');
        d.classList.remove('bg-[#2B80F6]');
      }
    });
  }

  // --- Navigation
  function next() { index = (index + 1) % total; update(); }
  function prev() { index = (index - 1 + total) % total; update(); }

  nextBtn.addEventListener('click', next);
  prevBtn.addEventListener('click', prev);



  // Clic sur les points
  dots.forEach((dot, i) => dot.addEventListener('click', () => { index = i; update(); }));

  // Empêche tout scroll horizontal global (sécurité si ta page a un overflow-x)
  // -> si tu veux l'appliquer globalement, garde la ligne suivante ;
  //    sinon commente-la.
  // document.documentElement.style.overflowX = 'hidden';

  update();



  //pour changer la couleur de la barre de navigation au scroll
  window.addEventListener('scroll', function() {
    const navbar = document.getElementById('navbar');
    navbar.classList.toggle('scrolled', window.scrollY > 100);
  });


  // --- Slider de témoignages
   let testimonialIndex = 0;
  const testimonialSlides = document.getElementById("testimonialSlides");
  const testimonialDots = document.querySelectorAll(".testimonial-dot");
  const totalTestimonialSlides = 5;

  function updateTestimonialSlider() {
    testimonialSlides.style.transform = `translateX(-${testimonialIndex * 100}%)`;
    testimonialDots.forEach((dot, idx) => {
      dot.classList.toggle("bg-blue-500", idx === testimonialIndex);
      dot.classList.toggle("bg-gray-300", idx !== testimonialIndex);
    });
  }

  function moveTestimonial(direction) {
    testimonialIndex = (testimonialIndex + direction + totalTestimonialSlides) % totalTestimonialSlides;
    updateTestimonialSlider();
  }

  // Initial load
  updateTestimonialSlider();

  //fin de la fonction slider de témoignages