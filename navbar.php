
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
</head>
<body>
    <nav class="nav">
        <div class="logo">
            <img src="op_white.webp" width="150">
            <img src="MIT_whitepng-removebg-preview.png" width="150">
        </div>
        <ul id="menuList">
                <li><a href="accueil.php">Mes informations</a></li>
                <li><a href="mes dossiers.php">Mes dossiers</a></li>
                <li><a href="mes reclamations.php">Mes réclamations</a></li>

            <li>
                <button class="animated-button">
                    <a href="deconnexion.php">
                        <svg xmlns="http://www.w3.org/2000/svg" class="arr-2" viewBox="0 0 24 24">
                            <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
                        </svg>
                        <span class="text">Déconnexion</span>
                        <span class="circle"></span>
                        <svg xmlns="http://www.w3.org/2000/svg" class="arr-1" viewBox="0 0 24 24">
                            <path d="M16.1716 10.9999L10.8076 5.63589L12.2218 4.22168L20 11.9999L12.2218 19.778L10.8076 18.3638L16.1716 12.9999H4V10.9999H16.1716Z"></path>
                        </svg>
                    </a>
                </button>
            </li>
        </ul>
        <div class="menu-icon">
            <i class="fas fa-bars" onclick="toggleMenu()"></i>
        </div>
    </nav>

    <script>
        let menuList = document.getElementById("menuList");
        menuList.style.maxHeight = "0px";

        function toggleMenu(){
            if(menuList.style.maxHeight === "0px") {
                menuList.style.maxHeight = "300px";
            } else {
                menuList.style.maxHeight = "0px";
            }
        }
    </script>
    <script src="https://kit.fontawesome.com/f8e1a90484.js" crossorigin="anonymous"></script>
</body>
</html>
