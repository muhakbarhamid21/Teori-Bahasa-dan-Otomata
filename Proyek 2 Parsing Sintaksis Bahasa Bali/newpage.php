<!--Kode PHP-->
<?php
include "koneksi.php";
session_start();

if (isset($_POST['submit'])) {
    $inputUser = $_POST['input'];
    $hasil = [];
    function test($input)                                                       //
    {
        global $conn;
        global $hasil;
        if (empty($input)) {
            return 0;
        } else {
            $input2 = explode(' ', $input);
            $query = "SELECT * FROM cnf WHERE body LIKE BINARY '" . $input2[0] . "%'";
            $result = mysqli_query($conn, $query);
            $result = mysqli_fetch_assoc($result);
            if (is_array($result)) {
                $str = $result['body'];
                $rule = $result['head'];
                $temp = $input;
                $tempResult = substr($temp, 0, strlen($str));
                if (strtoupper($str) == strtoupper($tempResult)) {
                    array_push($hasil, $str);
                    array_push($hasil, $rule);
                    return test(substr($input, strlen($str) + 1));
                } else {
                    return test(substr($input, strlen($input2[0]) + 1));
                }
            } else {
                $str = '';
                $rule = '';
                array_push($hasil, $str);
                array_push($hasil, $rule);
                return test(substr($input, strlen($input2[0]) + 1));
            }
        }
    }
}

?>

<!--Halaman WEB-->
<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <link rel="stylesheet" href="css/style.css">

    <title>Projek TBO 2 CYK</title>
  </head>
  <body>
  <script src="jquery.min.js"></script>
    <section class="form my-4 mx-5">
        <div class="container">
            <div class="row no-gutters">
                <div class="col-lg-5">
                    <!--Slide Show -->
                    <div id="mycarousel" class="carousel slide" data-ride="carousel">
                        <ol class="carousel-indicators">
                            <li data-target="#mycarousel" data-slide-to="0" class="active"></li>
                            <li data-target="#mycarousel" data-slide-to="1"></li>
                            <li data-target="#mycarousel" data-slide-to="2"></li>
                        </ol>
                        <div class="carousel-inner carousel">
                            <div class="carousel-item active" data-interval="4500">
                                <img src="img/bg1.jpg" class="img-fluid img" style="height: 410px;" alt="bg1">
                            </div>
                            <div class="carousel-item" data-interval="4500">
                                <img src="img/bg2.jpg" class="img-fluid img" style="height: 410px;" alt="bg2">
                            </div>
                            <div class="carousel-item" data-interval="4500">
                                <img src="img/bg3.jpg" class="img-fluid img" style="height: 410px;" alt="bg3">
                            </div>
                        </div>
                        <a class="carousel-control-prev" href="#mycarousel" role="button" data-slide="prev">
                            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                            <span class="sr-only">Previous</span>
                        </a>
                        <a class="carousel-control-next" href="#mycarousel" role="button" data-slide="next">
                            <span class="carousel-control-next-icon" aria-hidden="true"></span>
                            <span class="sr-only">Next</span>
                        </a>
                    </div>
                </div>
                <div class="col-lg-7 px-5 pt-5">
                    <h1 class="font-weight-bold py-3">Program CYK Bahasa BALI</h1>
                    <h4>Masukkan Kalimat Bahasa Bali</h4>
                    <form action="" method="post">
                        <div class="form-row">
                            <div class="col-lg-7">
                                <input type="text" name="input" placeholder="Kalimat Bahasa Bali" class="form-control my-3 p-4">
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="col-lg-7">
                                <button type="submit" name="submit" class="btn1 mt-3 mb-5">Uji</button>                                
                            </div>
                        </div>
                    </form>
                    <p id="result"></p>
                    <input type="hidden" id="akurasi" name="akurasi" value="0">
                    <?php
                        if (isset($_POST['submit'])) {
                            test($inputUser);
                            // var_dump($hasil);
                        }
                    ?>
                </div>
            </div>
        </div>
        <br>
    </section>
    <!--Java Script-->
    <script>
        var grama = {};

        function getRules() {
            let link = 'rule.txt';
            let doc = '';
            $.ajax(link, {
                async: false,
                success: function(data) {
                    doc = data;
                },
                error: function(xhr) {
                    console.log('Error : File ' + link + '\nStatus : ' + xhr.status + ' ' + xhr.statusText);
                }
            });
            return doc;
        }

        function createRules() {
            var grama = {};
            var data = getRules().match(/[^\r\n]+/g);
            var data2 = [];
            var data3 = [];

            for (let i = 0; i < data.length; i++) {
                data2.push(data[i].split('>'));
                for (let j = 0; j < data2[i].length; j++) {
                    data3.push(data2[i][j].split(' '));
                }
            }

            // create object sesuai rule, i = key, i+1 = item
            for (var i = 0; i < data3.length; i = i + 2) {
                if (data3[i + 1].length == 4) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3]];
                } else if (data3[i + 1].length == 5) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4]];
                } else if (data3[i + 1].length == 6) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5]];
                } else if (data3[i + 1].length == 7) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6]];
                } else if (data3[i + 1].length == 8) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7]];
                } else if (data3[i + 1].length == 12) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11]];
                } else if (data3[i + 1].length == 13) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11], data3[i + 1][12]];
                } else if (data3[i + 1].length == 14) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11], data3[i + 1][12], data3[i + 1][13]];
                } else if (data3[i + 1].length == 21) {
                    grama[data3[i][0]] = [data3[i + 1][0], data3[i + 1][1], data3[i + 1][2], data3[i + 1][3], data3[i + 1][4], data3[i + 1][5], data3[i + 1][6], data3[i + 1][7], data3[i + 1][8], data3[i + 1][9], data3[i + 1][10], data3[i + 1][11], data3[i + 1][12], data3[i + 1][13], data3[i + 1][14], data3[i + 1][15], data3[i + 1][16], data3[i + 1][17], data3[i + 1][18], data3[i + 1][19], data3[i + 1][20]];
                }

            }
            return grama;
        }
        grama = createRules();

        //berfungsi untuk menentukan nilai setiap sel di cyk dimana baris > 0
        var iterasilanjutan = function(tab1, tab2, gram) {
            var temp = [];
            for (var r in gram) {
                for (var i in gram[r]) {
                    if (tab1.indexOf(gram[r][i].charAt(0)) >= 0 && tab2.indexOf(gram[r][i].charAt(1)) >= 0) {
                        temp.push(r);
                    }
                }
            }
            return temp;
        };
        function convert(tab) {
            var coba = {
                K: "A",
                S: "B",
                FN: "C",
                Nama: "D",
                P: "E",
                FA: "F",
                Kj: "G",
                O: "H",
                Ket: "I",
                Pn: "J",
                Bd: "K",
                Pr: "L",
                Pel: "M",
                Fp: "N",
                Ps: "O",
                FV: "P",
                Bil: "Q",
                Gt: "R",
                Sf: "S",
                Kt: "T"
            }
            for (let i = 0; i < tab[0].length; i++) {
                tab[0][i] = coba[tab[0][i]];
            }
            return tab;
        }

        //cyk algorytm
        var cyk = function(gram) {
            var len = <?= count($hasil) / 2 ?>;

            //kami mengisi tabel dengan spasi kosong
            var tab = new Array(len);
            for (var t = 0; t < len; t++) {
                tab[t] = new Array(0);
            }

            // menentukan baris pertama cyk
            <?php
            $index = 0;
            for ($z = 0; $z < count($hasil) / 2; $z++) :
            ?>
                tab[0][<?= $z ?>] = '<?= $hasil[$z + $z + 1] ?>';
            <?php endfor; ?>

            tab = convert(tab);
            //iterasi di atas baris
            for (var j = 1; j <= len - 1; j++) {
                //iterasi kolom
                for (var i = 0; i <= len - j - 1; i++) {
                    tab[j][i] = [];
                    //iterasi atas pesanan yang lebih rendah dari kami
                    for (var k = 0; k < j; k++) {
                        //indeks sel yang dibandingkan
                        var baris = j - k - 1;
                        var kolom = i + k + 1;
                        //menambahkan aturan yang sesuai untuk satu pertandingan
                        tab[j][i] = tab[j][i].concat(iterasilanjutan(tab[k][i], tab[baris][kolom], gram));
                    }
                }
            }

            for (var m in tab[len - 1][0]) {
                if (tab[len - 1][0][m] === 'A') {
                    return true;
                }
            }
            return false;
        };

        var test = function() {
            try {
                if (cyk(grama)) {
                    document.getElementById('result').innerHTML = 'Kalimat yang anda input adalah <span>kalimat baku</span>';
                    document.getElementById('akurasi').value = '1';
                } else {
                    document.getElementById('result').innerHTML = 'Kalimat yang anda input adalah <span>kalimat tidak baku</span>';
                    document.getElementById('akurasi').value = '0';
                }
            } catch (error) {
                document.getElementById('result').innerHTML = 'Kalimat yang anda input adalah <span>kalimat tidak baku</span>';
                document.getElementById('akurasi').value = '0';
            }
        };
        test();
    </script>
    <!-- Option 2: jQuery, Popper.js, and Bootstrap JS-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.min.js" integrity="sha384-w1Q4orYjBQndcko6MimVbzY0tgp4pWB4lZ7lr30WKz0vr/aWKhXdBNmNb5D92v7s" crossorigin="anonymous"></script>
  </body>
</html>