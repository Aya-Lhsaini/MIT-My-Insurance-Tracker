<?php
require_once 'database.php'; 

// Read the JSON data
$data = json_decode(file_get_contents('php://input'), true);


// Update the reclamation to mark it as read
$sql = 'UPDATE reclamations SET lu = 1 WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':id', $sujetId, PDO::PARAM_INT);
$stmt->execute();

// Respond with a success message
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="nav.css">
    <link href="https://fonts.googleapis.com/css2?family=Lato&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .notification-badge {
            background-color: red;
            color: white;
            border-radius: 50%;
            padding: 2px 8px;
            font-size: 12px;
            margin-left: 5px;
        }
        
     
        .nav .logo img {
            margin-right: 10px;
        }

        #menuList {
            list-style-type: none;
            margin: 0;
            padding: 0;
            display: flex;
        }

        #menuList li {
            margin: 0 15px;
        }

        #menuList li a {
            color: white;
            text-decoration: none;
        }

        #menuList li a:hover {
            text-decoration: underline;
        }

        .menu-icon {
            display: none;
        }

        

        @media (max-width: 768px) {
            #menuList {
                display: none;
                flex-direction: column;
                max-height: 0;
                overflow: hidden;
            }

            .menu-icon {
                display: block;
                font-size: 24px;
                cursor: pointer;
            }

            #menuList.show {
                display: flex;
            }
        }
    </style>
</head>
<body>
    <nav class="nav">
        <div class="logo">
            <img src="op_white.webp" width="150">
            <img src="MIT_whitepng-removebg-preview.png" width="150">
        </div>
        <ul id="menuList">
            <li><a href="admin_dashboard.php">Informations admin</a></li>  
            <li><a href="utilisateurs.php">Tous les utilisateurs</a></li>
            <li><a href="admin_dossiers.php">Tous les dossiers</a></li>
            <li><a href="admin_reclamations.php">Tous les réclamations </a></li>
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

        function toggleMenu(){
            if(menuList.style.maxHeight === "0px" || menuList.style.maxHeight === "") {
                menuList.style.maxHeight = "300px";
                menuList.classList.add('show');
            } else {
                menuList.style.maxHeight = "0px";
                menuList.classList.remove('show');
            }
        }

        function showSujet(element, sujetId) {
            const modal = document.getElementById('sujetModal');
            const modalContent = document.getElementById('modalContent');

            modalContent.textContent = element.dataset.sujet;

            modal.style.display = 'block';

            element.classList.remove('hidden-sujet');
            element.classList.add('read-sujet');

            fetch('update_sujet_status.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ id: sujetId })
            }).then(response => response.json())
              .then(data => {
                  if (data.status === 'success') {
                      // Update the unread count
                      updateUnreadCount();
                  }
              });
        }

        function closeModal() {
            const modal = document.getElementById('sujetModal');
            modal.style.display = 'none';
        }

        window.onclick = function(event) {
            const modal = document.getElementById('sujetModal');
            if (event.target == modal) {
                closeModal();
            }
        }

        function updateUnreadCount() {
            fetch('update_unread_count.php')
                .then(response => response.text())
                .then(data => {
                    document.getElementById('unreadCount').innerHTML = data > 0 ? `<span class='notification-badge'>${data}</span>` : '';
                });
        }

        window.onload = function() {
            updateUnreadCount();
        }
    </script>
    <script src="https://kit.fontawesome.com/f8e1a90484.js" crossorigin="anonymous"></script>
</body>
</html>
