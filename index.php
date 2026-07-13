<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Yo'Service Pro - Services à domicile</title>
    <link rel="stylesheet" href="style.css">
</head>

<body>

    <!-- HEADER -->
    <header class="header">
    <a href="index.html" class="logo">
        <img src="logo.png" alt="YoService Pro">
    </a>

    <nav class="nav">
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="services.html">Services</a></li>
            <li><a href="tarifs.html">Tarifs</a></li>
            <li><a href="reservation.html">Réservation</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="apropos.html">À propos</a></li>
        </ul>
    </nav>

    <div class="client-buttons">
        <a href="login_client.php" class="btn-client-nav">Mon espace client</a>
        <a href="register_client.php" class="btn-register">S'inscrire</a>
    </div>
    </header>

    <!-- HERO -->
    <section class="hero">
    <div class="hero-content">
        <img src="Hero.png" alt="Service à domicile Yo'Service Pro" class="hero-img">

        <h2>Un service professionnel, rapide et fiable</h2>
        <p>Nettoyage, aide à domicile et bientôt d’autres services pour simplifier votre quotidien.</p>

        <div class="hero-buttons">
            <a href="reservation.html" class="btn-primary">Réserver maintenant</a>
            <a href="apropos.html" class="btn-secondary">Qui sommes nous ?</a>
        </div>
    </div>
</section>

    

    <!-- POURQUOI NOUS -->
    <section class="presentation">
        <h3>Pourquoi choisir Yo'Service Pro ?</h3>
        <p>
            Ponctuel, sérieux et efficace, Yo'Service Pro vous garantit un service impeccable pour votre logement.
            Chaque intervention est réalisée avec soin, dans le respect de votre espace et de vos besoins.
        </p>
        <img src="nett-int.png" alt="Nettoyage intérieur Yo'Service Pro" class="section-img">
    </section>

    <!-- SERVICES -->
    <section class="services">
        <h3>Nos prestations</h3>

        <div class="service-grid">

            <div class="service-item">
                <img src="nettoyage.png" alt="Nettoyage à domicile">
                <h4>Nettoyage à domicile</h4>
                <p>Entretien complet de votre logement, ponctuel ou régulier.</p>
            </div>

            <div class="service-item">
                <img src="aide.png" alt="Aide à domicile">
                <h4>Aide à domicile</h4>
                <p>Petits services du quotidien : courses, rangement, assistance.</p>
            </div>

        </div>
    </section>

    <section class="hero-tarifs">
    <div class="hero-content">
        <h2>Des services adaptés à vos besoins</h2>
        <a href="tarifs.html" class="btn-hero">Voir nos tarifs</a>
    </div>
    </section>


    <footer>
    <div class="footer-links">
        <a href="contact.html">Contact</a>
        <a href="mentions.html">Mentions légales</a>
        <a href="login_admin.php" class="footer-admin">Admin</a>
    </div>
    <p>© 2026 Yo'Service Pro – Tous droits réservés</p>
</footer>


</body>
</html>
