<?php
// Chemin du fichier à télécharger
$file = 'C:\xampp\htdocs\MIT\Formulaires\Dentaire.pdf';

if (file_exists($file)) {
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf'); // Type de contenu du fichier
    header('Content-Disposition: attachment; filename="'.basename($file).'"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($file));
    
    readfile($file);
    exit;
} else {
    echo 'Fichier non trouvé.';
}
?>
