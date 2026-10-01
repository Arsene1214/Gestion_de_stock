/* ====================================
HORLOGE ET DATE
==================================== */

function updateDateTime() {

    const now = new Date();  
    
    const date = now.toLocaleDateString('fr-FR', {  
        weekday: 'long',  
        day: 'numeric',  
        month: 'long',  
        year: 'numeric'  
    });  
    
    const heure = now.toLocaleTimeString('fr-FR');  
    
    const dateElement = document.getElementById("date");  
    const clockElement = document.getElementById("clock");  
    
    if(dateElement){  
        dateElement.innerHTML = date;  
    }  
    
    if(clockElement){  
        clockElement.innerHTML = heure;  
    }
    
    }
    
    setInterval(updateDateTime,1000);
    updateDateTime();
    
    /* ====================================
    MENU LATERAL
    ==================================== */
    
    const menuBtn = document.getElementById("menu-btn");
    
    if(menuBtn){
    
    menuBtn.addEventListener("click",function(){
    
    const sidebar = document.querySelector(".sidebar");  
    
    sidebar.classList.toggle("hide");
    
    });
    
    }
    
    /* ====================================
    MENU ACTIF
    ==================================== */
    
    const liens = document.querySelectorAll(".sidebar .nav-link");
    
    liens.forEach(function(lien){
    
    lien.addEventListener("click",function(){  
    
        liens.forEach(function(item){  
            item.classList.remove("active");  
        });  
    
        this.classList.add("active");  
    
    });
    
    });
    
    /* ====================================
    ANIMATION DES CARTES
    ==================================== */
    
    const cards = document.querySelectorAll(".dashboard-card");
    
    cards.forEach(function(card){
    
    card.addEventListener("mouseenter",function(){  
    
        card.style.transform="translateY(-8px)";  
        card.style.transition="0.3s";  
    
    });  
    
    card.addEventListener("mouseleave",function(){  
    
        card.style.transform="translateY(0px)";  
    
    });
    
    });
    
    /* ====================================
    CONFIRMATION DE DECONNEXION
    ==================================== */
    
    const logout = document.querySelector(".logout");
    
    if(logout){
    
    logout.addEventListener("click",function(e){
    
    if(!confirm("Voulez-vous vraiment quitter ?")){  
    
        e.preventDefault();  
    
    }
    
    });
    
    }
    
