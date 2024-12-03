    <?php
    session_start();
    include_once "conf/database.php";
    include_once "includes/header.php";

    $database = new Database();
    $db = $database->getConnection();

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        
        if (empty($_POST['photo'])) {
            $query = "UPDATE members
            SET first_name=?, last_name=?, email=?, profession=?,
            company=?, expertise=?, linkedin_profile=?, biography=?, skills=?, education=?, portfolio_links=?, social_links=?
            WHERE id=?";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['profession'],
                $_POST['company'],
                $_POST['expertise'],
                $_POST['linkedin_profile'],
                $_POST['biography'],
                $_POST['skills'],
                $_POST['education'],
                $_POST['portfolio_links'],
                $_POST['social_links'],
                $_GET['id']
            ]);
        } else {    
            $query = "UPDATE members
            SET first_name=?, last_name=?, email=?, profession=?,
            company=?, expertise=?, linkedin_profile=?, photo=?, biography=?, skills=?, education=?, portfolio_links=?, social_links=?
            WHERE id=?";
            
            $stmt = $db->prepare($query);
            $stmt->execute([
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['email'],
                $_POST['profession'],
                $_POST['company'],
                $_POST['expertise'],
                $_POST['linkedin_profile'],
                $_POST['photo'],
                $_POST['biography'],
                $_POST['skills'],
                $_POST['education'],
                $_POST['portfolio_links'],
                $_POST['social_links'],
                $_GET['id']
            ]);
        }

        header("Location: dashboard.php");
        exit();
    }

    $query = "SELECT * FROM members WHERE id = ?";
    $stmt = $db->prepare($query);
    $stmt->execute([$_GET['id']]);
    $member = $stmt->fetch(PDO::FETCH_ASSOC);
    ?>

    <label id="dark-change"></label>
    <div class="form-container">
        <h2>Edit Member</h2>
        <form method="POST">
            <div class="form-group">
                <label>First Name</label>
                <input type="text" name="first_name" class="form-control" value="<?php echo htmlspecialchars($member['first_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Last Name</label>
                <input type="text" name="last_name" class="form-control" value="<?php echo htmlspecialchars($member['last_name']); ?>" required>
            </div>

            <div class="form-group">
                <label>Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo htmlspecialchars($member['email']); ?>" required>
            </div>

            <div class="form-group">
                <label>Profession</label>
                <input name="profession" class="form-control" required/>
            </div>

            <div class="form-group">
                <label>Company</label>
                <input type="text" name="company" class="form-control" value="<?php echo htmlspecialchars($member['company']); ?>">
            </div>

            <div class="form-group">
                <label>Expertise</label>
                <textarea name="expertise" class="form-control"><?php echo htmlspecialchars($member['expertise']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Profile Photo</label>
                <input type="file" id="profilePhoto" name="photo" class="form-control" accept="image/*">
            </div>

            <div class="form-group">
                <label>LinkedIn Profile</label>
                <input type="url" name="linkedin_profile" class="form-control" value="<?php echo htmlspecialchars($member['linkedin_profile']); ?>">
            </div>

            <div class="form-group">
                <label>Biography</label>
                <textarea name="biography" class="form-control" rows="4" placeholder="Write about yourself..."><?php echo htmlspecialchars($member['biography']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Skills</label>
                <input type="text" name="skills" class="form-control" value="<?php echo htmlspecialchars($member['skills']); ?>" placeholder="e.g., JavaScript, Leadership, UX Design">
            </div>

            <div class="form-group">
                <label>Education</label>
                <textarea name="education" class="form-control" rows="3" placeholder="Add education details..."><?php echo htmlspecialchars($member['education']); ?></textarea>
            </div>

            <div class="form-group">
                <label>Portfolio Links</label>
                <input type="text" name="portfolio_links" class="form-control" value="<?php echo htmlspecialchars($member['portfolio_links']); ?>" placeholder="https://github.com">
            </div>

            <div class="form-group">
                <label>Social Media</label>
                <input type="text" name="social_links" class="form-control" value="<?php echo htmlspecialchars($member['social_links']); ?>" placeholder="e.g., LinkedIn, Twitter">
            </div>

            <button type="submit" class="btn btn-primary">Update Member</button>
        </form>
    </div>

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
    include_once "includes/footer.php";
    ?>
