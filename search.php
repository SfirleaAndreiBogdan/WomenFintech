<?php 
if (isset($_POST["submit"])) {
    $str = $_POST["search"]; // Accesează corect termenul de căutare din input-ul cu name="search"

    // Asigură-te că folosești un prepared statement pentru a preveni SQL injection
    $sth = $con->prepare("SELECT * FROM members WHERE first_name LIKE :str");  // Folosește LIKE pentru a permite căutări parțiale
    $sth->bindValue(':str', '%' . $str . '%', PDO::PARAM_STR);  // Leagă valoarea cu wildcard % pentru căutare parțială
    $sth->setFetchMode(PDO::FETCH_OBJ); 
    $sth->execute();

    // Dacă există rezultate
    if ($row = $sth->fetch()) {
        ?>
        <div class="col-md-4">
            <div class="card member-card">
                <div class="card-body">
                    <h5 class="card-title"><?php echo htmlspecialchars($row->first_name . ' ' . $row->last_name); ?></h5>
                    <img id="photo" src="<?php echo "img/" . htmlspecialchars($row->photo); ?>" alt="Profile Photo">
                    <p class="card-text">
                        <strong>Profession:</strong> <?php echo htmlspecialchars($row->profession); ?><br>
                        <strong>Company:</strong> <?php echo htmlspecialchars($row->company); ?>
                    </p>
                    <a href="edit_member.php?id=<?php echo $row->id; ?>" class="btn btn-primary">Edit</a>
                    <a href="delete_member.php?id=<?php echo $row->id; ?>" class="btn btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </div>
            </div>
        </div>
        <?php
    } else {
        echo "<p>No results found.</p>";
    }
}
?>
