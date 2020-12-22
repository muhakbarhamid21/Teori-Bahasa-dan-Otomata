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

// tombol submit untuk upload file
if( isset($_POST['btnFile']) ){

    // cek tipe upload file
    if( $_POST['inputType'] == "gradually" ){
        $fileHandler->store("documents");
    } else{
        $fileHandler->delete_all("documents");
        $fileHandler->store("documents");
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Upload File</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap/css/bootstrap.css">
    <link rel="stylesheet" href="css/mycss.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">
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
		<div class="img">
			<img src="img/bg7.svg">
        </div>
        <div class="login-content">
			<form action="upload file.php" method="POST" enctype="multipart/form-data">
                <label for="files">Select files:</label>
                <input type="file" id="files" name="files[]" multiple><br><br>

                    <label>Tipe file upload : </label><br>
                    <input type="radio" id="changed" name="inputType" value="changed" checked>
                    <label for="changed">Changed</label><br>
                    <input type="radio" id="gradually" name="inputType" value="gradually">
                    <label for="gradually">Gradually</label><br>
                    <a href="myfiles.php" target="_blank" >My Files
                    <br>
                    <button class="btn"  name="btnFile">Upload</button><br>
			</form>
        </div>
		
    </div>
    <script type="text/javascript" src="js/main.js"></script>
</body>
</html>
