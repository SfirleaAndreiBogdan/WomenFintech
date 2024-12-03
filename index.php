<?php
include_once "includes/header.php";
?>
<body>
<label id="dark-change"></label>
<div class="jumbotron" data-theme="light">
    <h1 class="display-4">Welcome to Women in FinTech</h1>
    <p class="lead">Empowering women in financial technology through community and
        collaboration.</p>
    <hr class="my-4">
    <p id="add">Join our community of professional women in FinTech.</p>
    <a class="btn btn-lg" href="add_member.php" role="button">Join Now</a>
</div>
</body>
<script>
    var content = document.getElementsByTagName('body')[0];
    var dark = document.getElementById('dark-change');

    if (localStorage.getItem('theme') === 'night') {
        content.classList.add('night');
    }

    dark.addEventListener('click',function(){
    dark.classList.toggle('active')
    content.classList.toggle('night')

    if (content.classList.contains('night')) {
        localStorage.setItem('theme', 'night');
    } else {
        localStorage.setItem('theme', 'light');
    }
})
</script>
<?php
include_once "includes/footer.php";
?>
