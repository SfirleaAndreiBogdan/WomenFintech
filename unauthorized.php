<?php  
include_once "includes/header.php";?>
<!DOCTYPE html>
<html>
<head>
    <title>Acces Interzis</title>
</head>
<label id="dark-change"></label>
<body>
    <h1>Acces Interzis</h1>
    <p>Nu aveți permisiunea de a accesa această pagină.</p>
    <a href="index.php">Înapoi la pagina principală</a>
</body>
</html>
<script>
    var content = document.getElementsByTagName('body')[0];
    var dark = document.getElementById('dark-change');

    if (localStorage.getItem('theme') === 'night') {
        content.classList.add('night');
    }

    dark.addEventListener('click', function () {
        dark.classList.toggle('active');
        content.classList.toggle('night');

        if (content.classList.contains('night')) {
            localStorage.setItem('theme', 'night');
        } else {
            localStorage.setItem('theme', 'light');
        }
    });
</script>
<?php  
include_once "includes/footer.php";?>
