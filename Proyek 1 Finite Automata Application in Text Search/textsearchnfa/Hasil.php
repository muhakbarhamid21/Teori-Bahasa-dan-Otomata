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
    <title>Hasil Pencarian</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="css/mycss.css">
	<link rel="stylesheet" type="text/css" href="css/hasil.css">
	<link href="https://fonts.googleapis.com/css?family=Poppins:600&display=swap" rel="stylesheet">
	<script src="https://kit.fontawesome.com/a81368914c.js"></script>
	<meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
        <header>
            <nav class="navbar navbar-expand-lg navbar-light bg-light">
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav">
                       <li class="nav-item active">
                        <a class="nav-link colink" href="home.php">Home <span class="sr-only">(current)</span></a>
                      </li>
                    </ul> 
                </div>     
            </nav>
        </header>
	<img class="wave" src="img/wave.png">
	<div class="container">
		<!-- list dokumen -->
        <div class="content">
                <?php if( isset($_POST['btnSearch']) && !empty($_POST['iSearch']) ) :?>

                    <div> 
                        <h3>Time Consumed : </h3>
                        <p><pre><?php echo $execution_time . " sec"?></pre></p>
                    </div>
                    <div class="menu">
                        <span class="menu-item"><a href="quintuple.php" target="_blank" >Quintuple</a></span>
                    </div>
                    <div class="documentContainer">
                        <ul type="none">

                            <!-- cetak semua list dokument -->
                            <?php foreach ($accepted_documents as $document) : ?>
                                <li> 
                                    <a href="<?php echo $document['document']->path; ?>" target="_blank">
                                        <h2><?php echo $document['document']->name; ?></h2>
                                    </a>
                                    <p>
                                        <?php echo "..." . $document['snippet']->str_before; ?>
                                        <strong>
                                            <?php echo " " . $document['snippet']->highlights . " "; ?>
                                        </strong>
                                        <?php echo $document['snippet']->str_after . "..."; ?>
                                    </p>
                                    <hr>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div class="footer">
                <?php endif;  ?>
                </div>
			</form>
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
