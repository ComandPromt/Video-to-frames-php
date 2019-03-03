<?php
function caracteres($cadena) {
    $salida = array();
    $posicion = strpos($cadena, " ");
    if ($posicion == null) {
        $salida[] = $cadena;
    } else {
        while ($posicion != null) {
            if ($posicion != null) {
                $salida[] = substr($cadena, 0, $posicion);
                $cadena = substr($cadena, $posicion + 1);
            }
            $posicion = strpos($cadena, " ");
            if ($posicion == null) {
                $salida[] = $cadena;
            }
        }
    }
    return $salida;
}
function check_images($path, $extension) {
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)) {
        if ($current != "." && $current != "..") {
            if (is_dir($path . $current)) {
                showFiles($path . $current . '/');
            } else {
                if (substr($current, -3) == $extension) {
                    $files[] = $current;
                }
            }
        }
    }
    return $files;
}
function comprobar_imagenes($ruta) {
    $imagenes = ver($ruta);
    for ($x = 0; $x < count($imagenes); $x++) {
        $extension = strtolower(substr($imagenes[$x], -3));
        switch ($extension) {
            case "peg":
            case "PEG":
            case "Peg":
            case "PeG":
            case "PEg":
            case "pEG":
                $extension = "jpg";
                $nombre_nuevo = substr($imagenes[$x], 0, -4);
                break;
            default:
                $nombre_nuevo = substr($imagenes[$x], 0, -3);
                break;
                rename($ruta . "/" . $imagenes[$x], $ruta . "/" . $nombre_nuevo . $extension);
        }
    }
    png_a_jpg($ruta . "/");
}
function comprobar_ficheros() {
       if (!file_exists("imagenes/Thumb")) {
        mkdir("imagenes/Thumb", 777, true);
    }
    if (!file_exists("imagenes/gif")) {
        mkdir("imagenes/gif", 777, true);
    }
    if (file_exists("imagenes/Thumbs.db")) {
        unlink('imagenes/Thumbs.db');
    } 
    if (file_exists("imagenes/gif/Thumbs.db")) {
        unlink('imagenes/gif/Thumbs.db');
    } 
    if (file_exists("imagenes/Thumb/Thumbs.db")) {
        unlink('imagenes/Thumb/Thumbs.db');
    }
}
function showFiles($path) {
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)) {
        if ($current != "." && $current != "..") {
            if (is_dir($path . $current)) {
                showFiles($path . $current . '/');
            } else {
                if (substr($current, -4, 1) == "." || substr($current, -3, 1) == "." || substr($current, -5, 1) == ".") {
                    $files[] = $current;
                }
            }
        }
    }
    return $files;
}
function ver($path) {
    $dir = opendir($path);
    $files = array();
    while ($current = readdir($dir)) {
        if ($current != "." && $current != "..") {
            if (is_dir($path . $current)) {
                showFiles($path . $current . '/');
            } else {
                $files[] = $current;
            }
        }
    }
    return $files;
}
function jpeg_jpg(array $imagenes) {
    for ($x = 0; $x < count($imagenes); $x++) {
        if (substr($current, -4) == "jpeg") {
            rename("imagenes/" . $imagenes[$x], "imagenes/" . substr($imagenes[$x], 0, -4) . ".jpg");
        }
    }
}
function gif_a_jpg(array $imagenes, $ruta) {
    for ($x = 0; $x < count($imagenes); $x++) {
        $origen = $ruta . substr($imagenes[$x], 0, -4) . substr($imagenes[$x], -4);
        $destino = $ruta . "Thumb/" . substr($imagenes[$x], 0, -4) . "_Thumb" . ".jpg";
        imagejpeg(imagecreatefromstring(file_get_contents($origen)), $destino);
    }
}
function mover_imagenes($ruta) {
    $imagenes = showFiles($ruta);
    for ($x = 0; $x < count($imagenes); $x++) {
        $origen = $ruta . $imagenes[$x];
        $destino = $ruta . "Thumb/" . $imagenes[$x];
        $buscar = substr($imagenes[$x], 0, -4);
        $buscar = substr($buscar, -6);
        if ($buscar == "_Thumb") {
            rename($origen, $destino);
        }
    }
}
function cambiarExtension($ruta) {
    $imagenes = showFiles("imagenes/");
    $nombre = date("Y") . "_" . date("d") . "_" . date("m") . "_" . date("H") . "-" . date("i") . "-" . date("s");
    $x = 1;
    for ($i = 0; $i < count($imagenes); $i++) {
        $cadena = $ruta . "/" . $imagenes[$i];
        $extension = substr($imagenes[$i], -4);
        $origen = "imagenes/" . $imagenes[$i];
        switch ($extension) {
            case ".png":
            case ".PNG":
                if ($_SESSION['categoria'] == 9) {
                    $ok = true;
                } else {
                    $ok = false;
                    while ($ok == false) {
                        if (file_exists($nombre . "_" . $x . ".jpg")) {
                            $x++;
                        } else {
                            $destino = "imagenes/" . $nombre . "_" . $x . ".png";
                            rename($origen, $destino);
                            unlink($destino);
                            $x++;
                            $ok = true;
                        }
                    }
                }
                break;
            case ".GIF":
            case ".gif":
                if ($_SESSION['categoria'] == 9) {
                    $destino = "imagenes/gif/" . $nombre . "_" . $x . ".gif";
                    rename($origen, $destino);
                }
                break;
            case "JPEG":
            case "jpeg":
            case ".jpg":
			case ".JPG":
                if ($_SESSION['categoria'] != 9) {
                    $destino = "imagenes/" . $nombre . "_" . $x . ".jpg";
                    rename($origen, $destino);
                }
                break;
        }
        $x++;
    }
}
function png_a_jpg($ruta) {
    $imagenes = check_images("imagenes", "png");
    for ($x = 0; $x < count($imagenes); $x++) {
        $jpg = $ruta . substr($imagenes[$x], 0, -3) . "jpg";
        $image = imagecreatefrompng($ruta . $imagenes[$x]);
        imagejpeg($image, $jpg, 100);
        unlink($ruta . $imagenes[$x]);
    }
}
function redimensionarJPG($max_ancho, $max_alto, $ruta, $gif) {
    $imagenes = check_images($ruta, "jpg");
    $calidad = 100;
    for ($x = 0; $x < count($imagenes); $x++) {
        if ($gif) {
            $salida = $ruta . "/" . $imagenes[$x];
        } else {
            if ($max_ancho == 100 && $max_alto == 125) {
                $salida = "./" . $ruta . "/" . substr($imagenes[$x], 0, -4) . "_Thumb" . substr($imagenes[$x], -4);
            } else {
                $salida = "./" . $ruta . "/" . $imagenes[$x];
            }
        }
        if (substr($imagenes[$x], -10) != "_Thumb.jpg" && substr($imagenes[$x], -4, 1) == "." || $gif) {
            $img_original = imagecreatefromjpeg("./" . $ruta . "/" . $imagenes[$x]);
            list($ancho, $alto) = getimagesize("./" . $ruta . "/" . $imagenes[$x]);
            //Se calcula ancho y alto de la imagen final
            $x_ratio = $max_ancho / $ancho;
            $y_ratio = $max_alto / $alto;
            //Si el ancho y el alto de la imagen no superan los maximos, 
            //ancho final y alto final son los que tiene actualmente
            if (($ancho <= $max_ancho) && ($alto <= $max_alto)) {//Si ancho 
                $ancho_final = $ancho;
                $alto_final = $alto;
            } elseif (($x_ratio * $alto) < $max_alto) {
                $alto_final = ceil($x_ratio * $alto);
                $ancho_final = $max_ancho;
            } else {
                $ancho_final = ceil($y_ratio * $ancho);
                $alto_final = $max_alto;
            }
            $tmp = imagecreatetruecolor($ancho_final, $alto_final);
            imagecopyresampled($tmp, $img_original, 0, 0, 0, 0, $ancho_final, $alto_final, $ancho, $alto);
            imagedestroy($img_original);
            imagejpeg($tmp, $salida, $calidad);
        }
    }
}
function consecutivos($array) {
    if ($array[0] != null && $array[0] == 1) {
        $numAnt = array();
        $x = 0;
        foreach ($array as $pos => $num) {
            if ($pos > 0) {
                if (!($numAnt[($pos - 1)] + 1) == $num) {
                    $noc = array();
                    $noc1 = $numAnt[($pos - 1)];
                    $noc[] = $noc1;
                    if ($noc[1] - $noc[0] != 1) {
                        $numero = $noc[0] + 1;
                    }
                }
            }
            $numAnt[$pos] = $num;
            $x++;
        }
        if ($numAnt[0] != 1 && $array[0] != 1) {
            $numero = $numAnt[0] - 1;
        } else {
            $validar = false;
            for ($x = 0; $x < count($numAnt); $x++) {
                if ($numero == null) {
                    if ($numAnt[$x] + 1 == $numAnt[$x + 1]) {
                        $validar = true;
                    } else {
                        $numero = $numAnt[$x] + 1;
                    }
                }
            }
            if ($numero == null) {
                if (!$validar) {
                    $numero = $array[$x - 1] - 1;
                } else {
                    $numero = array_pop($array) + 1;
                }
            }
        }
    } else {
        $numero = 1;
    }
    return $numero;
}
?>
