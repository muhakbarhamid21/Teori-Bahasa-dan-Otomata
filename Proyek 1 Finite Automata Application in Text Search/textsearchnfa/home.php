<?php 

require('FileHandler.php');
require('Connection.php');
require('NFA.php');
require('Document.php');
require('Snippet.php');

// koneksi database
$conn = Connection::get_connection("text search");

// buat file hanlder
$fileHandler = new FileHandler($conn);

// tombol cari
$accepted_documents = [];
if( isset($_POST['btnSearch']) ){

    if ( !empty($_POST['iSearch']) ){

        $start_time = microtime(true); // waktu mulai

        // membuat mesin nfa
        $nfa = NFA::create_textSearch_machine($_POST['iSearch']);

        // ambil semua file
        $files = $fileHandler->get_all("documents");

        // ambil satu dokumen
        foreach( $files as $file ){
            $path = "doc/" . $file["name"] . "." . $file["extensions"] ;
            $document = new Document($path);

            //cari hasil dokumen diterima/tidak
            $result = $nfa->result($document);

            //simpan dokumen jika diterima
            if( $result['accepted'] ){
                $snippet = new Snippet();
                $new_snippet = $snippet->get_snippet($document, $result['snippet_key']);
                $accepted_documents[] = [
                    "document" => $document,
                    "snippet" => $new_snippet
                ];
            }
        }
        
        $end_time = microtime(true); // waktu selesai
        $execution_time = ($end_time - $start_time); // hitung waktu

        // session
        session_start();
        $_SESSION['nfa'] = $nfa;
    }
}

?>

<!--WEB-->
<!DOCTYPE html>
<html>
<head>
	<title>Text Search</title>
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
	<img class="wave" src="img/wave.png">
	<div class="container">
		<div class="img">
			<img src="img/bg6.svg">
		</div>
		<div class="login-content">
			<form action="Hasil.php" enctype='multipart/form-data' method='POST'>
				<h2 class="title">Text Search</h2>
           		<div class="input-div one">
           		   <div class="i">
					<i class="fas fa-search"></i>
           		   </div>
           		   <div class="div">
                    <h5>Masukkan Text</h5>
                    <input type="text"  class="input" id="search" name="iSearch" size="50" value="<?php if( isset($_POST['iSearch']) ) echo $_POST['iSearch'];  ?>">
           		   </div>
				</div>
                <a href="upload file.php">Upload Doc</a>  
                <button type="submit" class="btn" name="btnSearch">CARI</button> 
			</form>
        </div>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
