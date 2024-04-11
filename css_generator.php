<?php


// ----- option -r ou --recursive ----- //

$recu = false;


foreach ($argv as $argument) {
    if ($argument == "-r" || $argument == "--recursive") {
        $recu = true;
    }
    


}

 // ------------------------------------------------ Recursive --------------------------------------------------------- //
function recursive($dir, &$imgpath = [], $recu = false) {

    if ($dossier = opendir($dir)) {
        while (($filename = readdir($dossier)) !== false) {

            if ($filename !== "." && $filename !== "..") {
                $chemin = $dir . "/" . $filename;


                if (is_dir($chemin) && $recu == true) {
                    recursive($chemin, $imgpath);

                    
                } else { 


                    $extension = pathinfo($chemin, PATHINFO_EXTENSION);


                    if (strtolower($extension) == "png" && is_file($chemin)) {
                        $imgpath[] = $chemin;
                    }
                }
            }
        }

        closedir($dossier);
    }
    
}

//------------------------------------------------------- Sprite -------------------------------------------------------//
function sprite($recursive) {
   

    list($width, $height) = getimagesize($recursive[0]);


    $newimg = imagecreatetruecolor($width * count($recursive), $height);

    $x = 0;


    $i = 1;

    
    $css ="";


    foreach ($recursive as $image) {
        $imgpng = imagecreatefrompng($image);


        imagecopy($newimg, $imgpng, $x, 0, 0, 0, $width, $height);
            $x += $width;

            $css .= ".img$i {\n background-image: url('$image');\n\twidth: $width" . "px;\n\theight: $height" . "px;\n}\n";
               

            $dircss = "style.css";
                    

            $i++;



        imagedestroy($imgpng);
    }

            fopen($dircss, 'w');


            file_put_contents($dircss, $css);
            
                


    imagepng($newimg, "images/sprite.png");
    

    imagedestroy($newimg);

    return "Vous avez réussi à créer un sprite !!\n";


}

 

$imgPaths = [];
recursive("./images", $imgPaths, $recu);


echo sprite($imgPaths);