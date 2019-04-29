<!-- Arnaud DEBRABANT P1707147 - Damien PETITJEAN P1408987 -->
<?php
    session_start();
    include "includes/header.php" ?>

<div class="container">
    <div class="row blockButton">
        <div class="col-3"></div>
        <a class="btn btn-warning col-6" href="adherents.php">Adhérents</a>
        <div class="col-3"></div>
    </div>
    <div class="row blockButton">
        <div class="col-3"></div>
        <a class="btn btn-success col-6" href="courses.php">Courses</a>
        <div class="col-3"></div>
    </div>
</div>

<?php include "includes/footer.php" ?>