window.addEventListener('scroll', function() {
     var navbar = document.querySelector('.navbar');
     if (window.scrollY > 50) {
       navbar.classList.add('navbar-scrolled');
     } else {
       navbar.classList.remove('navbar-scrolled');
     }
   });
   
   AOS.init();
   
   
   
   window.addEventListener('scroll', function() {
     var backToTopButton = document.getElementById('backToTop');
     if (window.scrollY > 300) {
       backToTopButton.style.display = 'block';
     } else {
       backToTopButton.style.display = 'none';
     }
   });
   
   document.getElementById('backToTop').addEventListener('click', function(e) {
     e.preventDefault();
     window.scrollTo({top: 0, behavior: 'smooth'});
   });